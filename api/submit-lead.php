<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Метод не поддерживается'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Поле с нейтральным именем: «website» часто автозаполняют менеджеры паролей — заявка «пропадала».
if (!empty(trim((string) ($_POST['lead_hp'] ?? '')))) {
    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
    exit;
}

$configPath = dirname(__DIR__) . '/config/telegram.php';
if (!is_file($configPath)) {
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => 'Заявки временно недоступны'], JSON_UNESCAPED_UNICODE);
    exit;
}

$config = require $configPath;
if (!is_array($config)) {
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => 'Заявки временно недоступны'], JSON_UNESCAPED_UNICODE);
    exit;
}

$botToken = trim((string) (getenv('TELEGRAM_BOT_TOKEN') ?: ($config['bot_token'] ?? '')));
$chatId = trim((string) (getenv('TELEGRAM_GROUP_CHAT_ID') ?: getenv('TELEGRAM_CHAT_ID') ?: ($config['chat_id'] ?? '')));

if ($botToken === '' || $chatId === '') {
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => 'Заявки временно недоступны'], JSON_UNESCAPED_UNICODE);
    exit;
}

$name = trim((string) ($_POST['name'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));
if ($message === '') {
    $message = trim((string) ($_POST['interest'] ?? ''));
}
$pageUrl = trim((string) ($_POST['page_url'] ?? ''));
$context = trim((string) ($_POST['context'] ?? ''));

$len = static function (string $s): int {
    return function_exists('mb_strlen') ? mb_strlen($s, 'UTF-8') : strlen($s);
};
$cut = static function (string $s, int $max): string {
    if (function_exists('mb_substr')) {
        return mb_strlen($s, 'UTF-8') > $max ? mb_substr($s, 0, $max, 'UTF-8') : $s;
    }

    return strlen($s) > $max ? substr($s, 0, $max) : $s;
};

if ($name === '' || $len($name) > 200) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Укажите имя'], JSON_UNESCAPED_UNICODE);
    exit;
}

$digits = preg_replace('/\D+/', '', $phone) ?? '';
if (strlen($digits) < 12) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Укажите полный номер телефона'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($len($message) > 3500) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Сообщение слишком длинное'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($len($pageUrl) > 2000) {
    $pageUrl = $cut($pageUrl, 2000);
}
if ($len($context) > 500) {
    $context = $cut($context, 500);
}

$lines = [
    'Заявка с сайта MRAMORBETON',
    '',
    'Имя: ' . $name,
    'Телефон: ' . $phone,
    'Сообщение: ' . ($message !== '' ? $message : '—'),
];
if ($context !== '') {
    $lines[] = 'Тема / контекст: ' . $context;
}
if ($pageUrl !== '') {
    $lines[] = 'Страница: ' . $pageUrl;
}

$text = implode("\n", $lines);
if ($len($text) > 4000) {
    $text = $cut($text, 4000) . '…';
}

$url = 'https://api.telegram.org/bot' . $botToken . '/sendMessage';
$payload = json_encode(
    [
        'chat_id' => $chatId,
        'text' => $text,
        'disable_web_page_preview' => true,
    ],
    JSON_UNESCAPED_UNICODE
);

if ($payload === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Не удалось сформировать запрос'], JSON_UNESCAPED_UNICODE);
    exit;
}

$responseBody = '';
$httpCode = 0;

if (function_exists('curl_init')) {
    $ch = curl_init($url);
    if ($ch !== false) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=UTF-8']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $exec = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $responseBody = $exec === false ? '' : (string) $exec;
    }
} else {
    $ctx = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json; charset=UTF-8\r\n",
            'content' => $payload,
            'timeout' => 20,
        ],
    ]);
    $responseBody = (string) @file_get_contents($url, false, $ctx);
    if (!empty($http_response_header[0]) && preg_match('{HTTP/\S+\s+(\d+)}', $http_response_header[0], $m)) {
        $httpCode = (int) $m[1];
    }
}

$decoded = json_decode($responseBody, true);
$telegramOk = is_array($decoded) && !empty($decoded['ok']);

if ($httpCode !== 200 || !$telegramOk) {
    $desc = is_array($decoded) ? (string) ($decoded['description'] ?? '') : '';
    error_log('mramorbeton submit-lead Telegram fail http=' . $httpCode . ' body=' . $responseBody);

    $userMsg = 'Не удалось отправить заявку. Попробуйте позже или позвоните нам.';
    $lower = strtolower($desc);
    if ($httpCode === 0 || $responseBody === '') {
        $userMsg = 'Сервер не смог связаться с Telegram (сеть или блокировка исходящих запросов). Проверьте хостинг или firewall.';
    } elseif (strpos($lower, 'unauthorized') !== false || strpos($lower, 'invalid token') !== false) {
        $userMsg = 'Ошибка Telegram: неверный или отозванный токен бота. Создайте новый токен в @BotFather и обновите config/telegram.php.';
    } elseif (strpos($lower, 'chat not found') !== false) {
        $userMsg = 'Ошибка Telegram: чат не найден. Укажите верный chat_id группы (у супергрупп обычно начинается с -100...) и убедитесь, что бот в группе.';
    } elseif (strpos($lower, 'need administrator rights') !== false || strpos($lower, 'not enough rights') !== false) {
        $userMsg = 'Ошибка Telegram: у бота нет прав писать в эту группу. Включите разрешение на сообщения или сделайте бота администратором.';
    } elseif (strpos($lower, 'bot was kicked') !== false || strpos($lower, 'blocked') !== false) {
        $userMsg = 'Ошибка Telegram: бот удалён из группы или заблокирован. Добавьте бота в группу снова.';
    } elseif ($desc !== '') {
        $userMsg = 'Ошибка Telegram: ' . $desc;
    }

    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => $userMsg], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
