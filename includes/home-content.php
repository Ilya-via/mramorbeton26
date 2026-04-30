<?php
declare(strict_types=1);

require_once __DIR__ . '/app.php';

/**
 * Возвращает значения по умолчанию для всех редактируемых блоков главной.
 * Сохранены те же тексты/картинки/пункты, что были в index.html и script.js,
 * чтобы на пустой БД сайт выглядел один-в-один как раньше.
 */
function home_settings_defaults(): array
{
    return [
        'header' => [
            'phone_text' => '+375 (29) 325-82-59',
            'phone_href' => 'tel:+375293258259',
            'hours_text' => 'Пн-Вс: 09:00 — 20:00',
        ],

        'hero' => [
            'title_html' => "Производство<br>\nбетонных изделий<br>\n<span>под заказ</span>",
            'description' => 'Надёжные бетонные изделия для благоустройства и строительства',
            'button_text' => 'Перейти в каталог',
            'button_url' => 'catalog.php',
            'image_path' => 'assets/images/hero-main.png',
            'image_alt' => 'Бетонные ступени и благоустройство территории',
        ],

        'features' => [
            'section_tag' => 'Технологии',
            'heading' => 'Особенности производства',
            'items' => [
                [
                    'title' => 'Высокопрочный бетон',
                    'text' => 'Использование бетона марки M500 со специальными добавками обеспечивает исключительную плотность и прочность изделий на протяжении десятилетий.',
                ],
                [
                    'title' => 'Морозостойкость',
                    'text' => 'Особая структура бетона предотвращает образование трещин при резких перепадах температур, что критично для климата Минска.',
                ],
                [
                    'title' => 'Эстетика и стиль',
                    'text' => 'Широкий ассортимент форм, размеров и цветов позволяет создавать уникальные и стильные покрытия для любых ландшафтных проектов.',
                ],
            ],
        ],

        'about' => [
            'section_tag' => 'о нас',
            'heading_html' => "Промышленный масштаб с вниманием<br>\nк деталям",
            'lead' => "Наша компания специализируется на производстве и продаже продукции из высокопрочного бетона в Минске. Мы предлагаем широкий ассортимент тротуарной плитки, брусчатки, искусственного камня и фасадных панелей. Благодаря собственному производству, мы контролируем каждый этап производства, что позволяет нам гарантировать высокое качество и долговечность нашей продукции.",
            'image_path' => 'assets/images/about-production.png',
            'image_alt' => 'Производство бетонных изделий',
        ],

        'projects_home' => [
            'section_tag' => 'НАШ ОПЫТ',
            'heading' => 'Реализованные объекты',
            'button_text' => 'Смотреть все объекты',
            'button_url' => 'projects.php',
            'cards' => [
                [
                    'image_path' => 'assets/images/project-industrial.png',
                    'image_alt' => 'ЖК Индустриальный',
                    'title' => 'ЖК "Индустриальный"',
                    'subtitle' => 'Комплексное благоустройство территории',
                    'link' => '',
                ],
                [
                    'image_path' => 'assets/images/project-residence.png',
                    'image_alt' => 'Частная резиденция',
                    'title' => 'Частная резиденция',
                    'subtitle' => '',
                    'link' => '',
                ],
                [
                    'image_path' => 'assets/images/project-park.png',
                    'image_alt' => 'Парк "Монолит"',
                    'title' => 'Парк "Монолит"',
                    'subtitle' => '',
                    'link' => '',
                ],
            ],
        ],

        'instagram' => [
            'section_tag' => 'соцсети',
            'heading' => 'Наш инстаграм',
            'note_prefix' => 'Переходите в наш инстаграм',
            'note_suffix' => 'для просмотра последних новинок!',
            'handle_text' => '@mramorbetonminsk',
            'handle_url' => 'https://www.instagram.com/mramorbetonminsk/',
            'button_text' => 'Подписаться',
            'button_url' => 'https://www.instagram.com/mramorbetonminsk/',
            'items' => [
                ['image_path' => 'assets/images/instagram-1.png', 'image_alt' => 'Фотография объекта из Instagram 1'],
                ['image_path' => 'assets/images/instagram-2.png', 'image_alt' => 'Фотография объекта из Instagram 2'],
                ['image_path' => 'assets/images/instagram-3.png', 'image_alt' => 'Фотография объекта из Instagram 3'],
                ['image_path' => 'assets/images/instagram-4.png', 'image_alt' => 'Фотография объекта из Instagram 4'],
            ],
        ],

        'process' => [
            'section_tag' => 'Процесс',
            'heading' => 'Как мы работаем',
            'intro' => 'Мы выстроили прозрачную систему взаимодействия, чтобы вы получили идеальный результат точно в срок',
            'items' => [
                [
                    'title' => 'Заявка и консультация',
                    'text' => 'Оставляете заявку, наш специалист уточняет детали и помогает с выбором материалов под ваши задачи.',
                    'text_extra' => '',
                ],
                [
                    'title' => 'Расчет и договор',
                    'text' => 'Составляем подробную смету, фиксируем сроки и стоимость в договоре.',
                    'text_extra' => 'Никаких скрытых платежей.',
                ],
                [
                    'title' => 'Производство',
                    'text' => 'Запускаем ваш заказ в работу. Вы можете в любой момент приехать на производство и увидеть процесс.',
                    'text_extra' => '',
                ],
                [
                    'title' => 'Доставка и приемка',
                    'text' => 'Привозим готовую продукцию, разгружаем и подписываем акт приемки. Наслаждаетесь результатом.',
                    'text_extra' => '',
                ],
            ],
        ],

        'lead_form' => [
            'heading_html' => "Готовы начать проект?<br>\nДавайте обсудим.",
            'lead' => 'Заполните форму, и наш главный инженер свяжется с вами для бесплатной консультации по вашему объекту.',
            'phone_label' => 'связаться с нами',
            'phone_text' => '+375 (29) 325-82-59',
            'phone_href' => 'tel:+375293258259',
        ],

        'footer' => [
            'brand_text_html' => "Профессиональное производство<br>\nбетонных изделий для современной<br>\nгородской и частной инфраструктуры.<br>\nГарантия качества на века.",
            'products' => [
                ['label' => 'Брусчатка', 'href' => 'category.php?c=trotuarnaya-plitka'],
                ['label' => 'Тротуарная плитка', 'href' => 'category.php?c=trotuarnaya-plitka'],
                ['label' => 'Фасадные панели', 'href' => 'category.php?c=fasadnye-paneli'],
                ['label' => 'Бордюры и водостоки', 'href' => 'category.php?c=bordyury-i-vodostoki'],
                ['label' => 'Накладные проступи', 'href' => 'category.php?c=nakladnye-prostupi'],
                ['label' => 'Ритуальные плиты', 'href' => 'category.php?c=ritualnye-plity'],
                ['label' => 'Пошаговые плиты', 'href' => 'category.php?c=poshagovye-plity'],
                ['label' => 'Накрывные элементы', 'href' => 'category.php?c=parapetnye-plity'],
            ],
            'contact_phone_text' => '+375 (29) 325-82-59',
            'contact_phone_href' => 'tel:+375293258259',
            'contact_phone_meta' => 'Пн-Вс: 09:00 — 20:00',
            'contact_email_text' => 'Mramorbeton.by@gmail.com',
            'contact_email_href' => 'mailto:Mramorbeton.by@gmail.com',
            'contact_address_text' => 'Минская обл., Минский р-н, Хатежинский с/с, д. Васьковщина',
        ],
    ];
}

/**
 * Рекурсивный мердж: значения справа дополняют слева, но массивы списков
 * (числовые ключи) полностью заменяются. Нужно для items, products и т.п.
 */
function home_settings_merge(array $base, array $override): array
{
    foreach ($override as $key => $value) {
        if (is_array($value) && isset($base[$key]) && is_array($base[$key]) && !home_settings_is_list($value) && !home_settings_is_list($base[$key])) {
            $base[$key] = home_settings_merge($base[$key], $value);
        } else {
            $base[$key] = $value;
        }
    }

    return $base;
}

function home_settings_is_list(array $value): bool
{
    if ($value === []) {
        return true;
    }
    $i = 0;
    foreach (array_keys($value) as $key) {
        if ($key !== $i) {
            return false;
        }
        $i++;
    }
    return true;
}

function home_settings_get_all(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $defaults = home_settings_defaults();

    $stored = [];
    if (app_db_ready()) {
        $pdo = app_pdo();
        if ($pdo instanceof PDO) {
            try {
                $rows = $pdo->query('SELECT setting_key, setting_value FROM site_settings')->fetchAll();
                foreach ($rows as $row) {
                    $key = (string) ($row['setting_key'] ?? '');
                    if ($key === '') {
                        continue;
                    }
                    $decoded = json_decode((string) ($row['setting_value'] ?? ''), true);
                    if (!is_array($decoded)) {
                        continue;
                    }
                    $stored[$key] = $decoded;
                }
            } catch (Throwable $e) {
                $stored = [];
            }
        }
    }

    $cache = home_settings_merge($defaults, $stored);

    return $cache;
}

/**
 * Список «безопасных» HTML-тегов, разрешённых в полях с *_html.
 * Используется простой whitelist через регулярки (без DOM-парсера).
 */
function home_settings_sanitize_html(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    // Сначала экранируем всё.
    $escaped = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Возвращаем разрешённые теги: <br>, <br/>, <br />, <span>...</span>.
    $escaped = preg_replace('#&lt;\s*br\s*/?\s*&gt;#i', '<br>', $escaped) ?? $escaped;
    $escaped = preg_replace('#&lt;span&gt;#i', '<span>', $escaped) ?? $escaped;
    $escaped = preg_replace('#&lt;/span&gt;#i', '</span>', $escaped) ?? $escaped;
    $escaped = preg_replace('#&lt;strong&gt;#i', '<strong>', $escaped) ?? $escaped;
    $escaped = preg_replace('#&lt;/strong&gt;#i', '</strong>', $escaped) ?? $escaped;

    return $escaped;
}

/** Безопасный вывод: текст экранируется как обычный, но переносы строк → <br>. */
function home_e_multiline(string $value): string
{
    $escaped = htmlspecialchars(trim($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return nl2br($escaped);
}

function home_e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function home_attr_url(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function home_image_src(string $path): string
{
    $path = trim($path);
    if ($path === '') {
        return '';
    }

    return $path;
}

/**
 * Сохраняет настройки главной из POST/FILES.
 * Бросает RuntimeException при ошибках валидации/IO.
 */
function home_settings_save(array $post, array $files): void
{
    $pdo = app_pdo();
    if (!$pdo instanceof PDO) {
        throw new RuntimeException('Нет соединения с БД.');
    }

    // Гарантируем, что таблица site_settings создана. Если БД ещё не
    // инициализирована полностью, app_db_ready() всё равно создаст эту
    // таблицу первой среди миграций.
    try {
        app_db_run_migrations($pdo);
    } catch (Throwable $e) {
        // Игнорируем — главное, чтобы site_settings существовала; если она
        // не создалась, INSERT ниже выбросит понятную ошибку.
    }

    $payload = [
        'header' => home_settings_collect_header($post),
        'hero' => home_settings_collect_hero($post, $files),
        'features' => home_settings_collect_features($post),
        'about' => home_settings_collect_about($post, $files),
        'projects_home' => home_settings_collect_projects_home($post, $files),
        'instagram' => home_settings_collect_instagram($post, $files),
        'process' => home_settings_collect_process($post),
        'lead_form' => home_settings_collect_lead_form($post),
        'footer' => home_settings_collect_footer($post),
    ];

    $stmt = $pdo->prepare(
        'INSERT INTO site_settings (setting_key, setting_value)
         VALUES (:k, :v)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );

    foreach ($payload as $key => $value) {
        $json = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new RuntimeException('Не удалось сохранить блок: ' . $key);
        }
        $stmt->execute(['k' => $key, 'v' => $json]);
    }
}

function home_post_string(array $source, string $key, string $default = ''): string
{
    if (!isset($source[$key])) {
        return $default;
    }
    $value = $source[$key];
    if (!is_string($value)) {
        return $default;
    }

    return trim($value);
}

function home_settings_collect_header(array $post): array
{
    $header = isset($post['header']) && is_array($post['header']) ? $post['header'] : [];

    return [
        'phone_text' => home_post_string($header, 'phone_text'),
        'phone_href' => home_post_string($header, 'phone_href'),
        'hours_text' => home_post_string($header, 'hours_text'),
    ];
}

function home_settings_collect_hero(array $post, array $files): array
{
    $hero = isset($post['hero']) && is_array($post['hero']) ? $post['hero'] : [];
    $imagePath = home_post_string($hero, 'image_path');

    $upload = home_extract_single_upload($files, 'hero_image');
    if ($upload !== null) {
        $saved = app_save_uploaded_image($upload, 'home/hero', 'hero');
        if ($saved !== null) {
            $imagePath = $saved;
        }
    }

    return [
        'title_html' => home_settings_sanitize_html(home_post_string($hero, 'title_html')),
        'description' => home_post_string($hero, 'description'),
        'button_text' => home_post_string($hero, 'button_text'),
        'button_url' => home_post_string($hero, 'button_url'),
        'image_path' => $imagePath,
        'image_alt' => home_post_string($hero, 'image_alt'),
    ];
}

function home_settings_collect_features(array $post): array
{
    $features = isset($post['features']) && is_array($post['features']) ? $post['features'] : [];
    $itemsRaw = isset($features['items']) && is_array($features['items']) ? $features['items'] : [];

    $items = [];
    foreach ($itemsRaw as $item) {
        if (!is_array($item)) {
            continue;
        }
        $title = home_post_string($item, 'title');
        $text = home_post_string($item, 'text');
        if ($title === '' && $text === '') {
            continue;
        }
        $items[] = ['title' => $title, 'text' => $text];
    }

    return [
        'section_tag' => home_post_string($features, 'section_tag'),
        'heading' => home_post_string($features, 'heading'),
        'items' => $items,
    ];
}

function home_settings_collect_about(array $post, array $files): array
{
    $about = isset($post['about']) && is_array($post['about']) ? $post['about'] : [];
    $imagePath = home_post_string($about, 'image_path');

    $upload = home_extract_single_upload($files, 'about_image');
    if ($upload !== null) {
        $saved = app_save_uploaded_image($upload, 'home/about', 'about');
        if ($saved !== null) {
            $imagePath = $saved;
        }
    }

    return [
        'section_tag' => home_post_string($about, 'section_tag'),
        'heading_html' => home_settings_sanitize_html(home_post_string($about, 'heading_html')),
        'lead' => home_post_string($about, 'lead'),
        'image_path' => $imagePath,
        'image_alt' => home_post_string($about, 'image_alt'),
    ];
}

function home_settings_collect_projects_home(array $post, array $files): array
{
    $projects = isset($post['projects_home']) && is_array($post['projects_home']) ? $post['projects_home'] : [];
    $cardsRaw = isset($projects['cards']) && is_array($projects['cards']) ? $projects['cards'] : [];
    $uploads = home_extract_indexed_uploads($files, 'projects_home_files');

    $cards = [];
    foreach ($cardsRaw as $index => $card) {
        if (!is_array($card)) {
            continue;
        }
        $imagePath = home_post_string($card, 'image_path');
        if (isset($uploads[$index])) {
            $saved = app_save_uploaded_image($uploads[$index], 'home/projects', 'project-' . ($index + 1));
            if ($saved !== null) {
                $imagePath = $saved;
            }
        }

        $title = home_post_string($card, 'title');
        $subtitle = home_post_string($card, 'subtitle');
        $link = home_post_string($card, 'link');
        $alt = home_post_string($card, 'image_alt');

        if ($title === '' && $subtitle === '' && $imagePath === '' && $link === '') {
            continue;
        }

        $cards[] = [
            'image_path' => $imagePath,
            'image_alt' => $alt !== '' ? $alt : $title,
            'title' => $title,
            'subtitle' => $subtitle,
            'link' => $link,
        ];
    }

    return [
        'section_tag' => home_post_string($projects, 'section_tag'),
        'heading' => home_post_string($projects, 'heading'),
        'button_text' => home_post_string($projects, 'button_text'),
        'button_url' => home_post_string($projects, 'button_url'),
        'cards' => $cards,
    ];
}

function home_settings_collect_instagram(array $post, array $files): array
{
    $instagram = isset($post['instagram']) && is_array($post['instagram']) ? $post['instagram'] : [];
    $itemsRaw = isset($instagram['items']) && is_array($instagram['items']) ? $instagram['items'] : [];
    $uploads = home_extract_indexed_uploads($files, 'instagram_files');

    $items = [];
    foreach ($itemsRaw as $index => $item) {
        if (!is_array($item)) {
            continue;
        }
        $imagePath = home_post_string($item, 'image_path');
        if (isset($uploads[$index])) {
            $saved = app_save_uploaded_image($uploads[$index], 'home/instagram', 'instagram-' . ($index + 1));
            if ($saved !== null) {
                $imagePath = $saved;
            }
        }
        $alt = home_post_string($item, 'image_alt');
        if ($imagePath === '' && $alt === '') {
            continue;
        }
        $items[] = [
            'image_path' => $imagePath,
            'image_alt' => $alt !== '' ? $alt : 'Фотография объекта из Instagram',
        ];
    }

    return [
        'section_tag' => home_post_string($instagram, 'section_tag'),
        'heading' => home_post_string($instagram, 'heading'),
        'note_prefix' => home_post_string($instagram, 'note_prefix'),
        'note_suffix' => home_post_string($instagram, 'note_suffix'),
        'handle_text' => home_post_string($instagram, 'handle_text'),
        'handle_url' => home_post_string($instagram, 'handle_url'),
        'button_text' => home_post_string($instagram, 'button_text'),
        'button_url' => home_post_string($instagram, 'button_url'),
        'items' => $items,
    ];
}

function home_settings_collect_process(array $post): array
{
    $process = isset($post['process']) && is_array($post['process']) ? $post['process'] : [];
    $itemsRaw = isset($process['items']) && is_array($process['items']) ? $process['items'] : [];

    $items = [];
    foreach ($itemsRaw as $item) {
        if (!is_array($item)) {
            continue;
        }
        $title = home_post_string($item, 'title');
        $text = home_post_string($item, 'text');
        $textExtra = home_post_string($item, 'text_extra');
        if ($title === '' && $text === '' && $textExtra === '') {
            continue;
        }
        $items[] = [
            'title' => $title,
            'text' => $text,
            'text_extra' => $textExtra,
        ];
    }

    return [
        'section_tag' => home_post_string($process, 'section_tag'),
        'heading' => home_post_string($process, 'heading'),
        'intro' => home_post_string($process, 'intro'),
        'items' => $items,
    ];
}

function home_settings_collect_lead_form(array $post): array
{
    $form = isset($post['lead_form']) && is_array($post['lead_form']) ? $post['lead_form'] : [];

    return [
        'heading_html' => home_settings_sanitize_html(home_post_string($form, 'heading_html')),
        'lead' => home_post_string($form, 'lead'),
        'phone_label' => home_post_string($form, 'phone_label'),
        'phone_text' => home_post_string($form, 'phone_text'),
        'phone_href' => home_post_string($form, 'phone_href'),
    ];
}

function home_settings_collect_footer(array $post): array
{
    $footer = isset($post['footer']) && is_array($post['footer']) ? $post['footer'] : [];
    $productsRaw = isset($footer['products']) && is_array($footer['products']) ? $footer['products'] : [];

    $products = [];
    foreach ($productsRaw as $row) {
        if (!is_array($row)) {
            continue;
        }
        $label = home_post_string($row, 'label');
        $href = home_post_string($row, 'href');
        if ($label === '' && $href === '') {
            continue;
        }
        $products[] = ['label' => $label, 'href' => $href];
    }

    return [
        'brand_text_html' => home_settings_sanitize_html(home_post_string($footer, 'brand_text_html')),
        'products' => $products,
        'contact_phone_text' => home_post_string($footer, 'contact_phone_text'),
        'contact_phone_href' => home_post_string($footer, 'contact_phone_href'),
        'contact_phone_meta' => home_post_string($footer, 'contact_phone_meta'),
        'contact_email_text' => home_post_string($footer, 'contact_email_text'),
        'contact_email_href' => home_post_string($footer, 'contact_email_href'),
        'contact_address_text' => home_post_string($footer, 'contact_address_text'),
    ];
}

/**
 * Достаёт одиночный загруженный файл вида $files['hero_image']
 * (обычный <input type="file" name="hero_image">).
 */
function home_extract_single_upload(array $files, string $name): ?array
{
    if (!isset($files[$name]) || !is_array($files[$name])) {
        return null;
    }
    $entry = $files[$name];
    if (!isset($entry['error']) || $entry['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($entry['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    return $entry;
}

/**
 * Преобразует $_FILES['xxx'] для <input type="file" name="xxx[INDEX]" multiple|nested>
 * в массив обычных «single-file» структур по индексу строки.
 */
function home_extract_indexed_uploads(array $files, string $name): array
{
    if (!isset($files[$name]) || !is_array($files[$name])) {
        return [];
    }

    $entry = $files[$name];
    if (!isset($entry['name']) || !is_array($entry['name'])) {
        return [];
    }

    $result = [];
    foreach ($entry['name'] as $index => $fileName) {
        if (!is_string($fileName) || $fileName === '') {
            continue;
        }
        $error = (int) ($entry['error'][$index] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($error !== UPLOAD_ERR_OK) {
            continue;
        }
        $result[(int) $index] = [
            'name' => $fileName,
            'type' => (string) ($entry['type'][$index] ?? ''),
            'tmp_name' => (string) ($entry['tmp_name'][$index] ?? ''),
            'error' => $error,
            'size' => (int) ($entry['size'][$index] ?? 0),
        ];
    }

    return $result;
}
