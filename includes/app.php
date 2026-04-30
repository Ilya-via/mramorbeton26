<?php
declare(strict_types=1);

function app_path(string $relative = ''): string
{
    $base = dirname(__DIR__);
    if ($relative === '') {
        return $base;
    }

    return $base . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative);
}

function app_url_path(string $path): string
{
    return str_replace('\\', '/', $path);
}

function app_config(): array
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $defaults = [
        'db' => [
            'host' => getenv('DB_HOST') ?: '127.0.0.1',
            'port' => getenv('DB_PORT') ?: '3306',
            'database' => getenv('DB_NAME') ?: 'mramorbeton',
            'username' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASS') ?: '',
            'charset' => 'utf8mb4',
        ],
        'admin' => [
            'session_key' => 'mramorbeton_admin_user_id',
        ],
    ];

    $configFile = app_path('config/database.php');
    if (is_file($configFile)) {
        $custom = require $configFile;
        if (is_array($custom)) {
            $defaults = array_replace_recursive($defaults, $custom);
        }
    }

    $config = $defaults;

    return $config;
}

function app_db_dsn(): string
{
    $db = app_config()['db'];

    return sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $db['host'],
        $db['port'],
        $db['database'],
        $db['charset']
    );
}

function app_pdo(): ?PDO
{
    static $pdo = false;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if ($pdo === null) {
        return null;
    }

    try {
        $db = app_config()['db'];
        $pdo = new PDO(
            app_db_dsn(),
            $db['username'],
            $db['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    } catch (PDOException $e) {
        $pdo = null;
    }

    return $pdo;
}

function app_db_ready(): bool
{
    static $ready = null;

    if ($ready !== null) {
        return $ready;
    }

    $pdo = app_pdo();
    if (!$pdo instanceof PDO) {
        $ready = false;
        return false;
    }

    try {
        app_db_run_migrations($pdo);
        $pdo->query('SELECT 1 FROM categories LIMIT 1');
        $pdo->query('SELECT 1 FROM products LIMIT 1');
        $ready = true;
    } catch (Throwable $e) {
        $ready = false;
    }

    return $ready;
}

function app_db_table_exists(PDO $pdo, string $table): bool
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*)
         FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = :schema AND TABLE_NAME = :table_name'
    );
    $stmt->execute([
        'schema' => app_config()['db']['database'],
        'table_name' => $table,
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function app_db_column_exists(PDO $pdo, string $table, string $column): bool
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*)
         FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = :schema AND TABLE_NAME = :table_name AND COLUMN_NAME = :column_name'
    );
    $stmt->execute([
        'schema' => app_config()['db']['database'],
        'table_name' => $table,
        'column_name' => $column,
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

function app_db_run_migrations(PDO $pdo): void
{
    static $done = false;

    if ($done) {
        return;
    }

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS site_settings (
            setting_key VARCHAR(190) NOT NULL PRIMARY KEY,
            setting_value LONGTEXT NOT NULL,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS projects (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(190) NOT NULL UNIQUE,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            image_path VARCHAR(255) NOT NULL DEFAULT "",
            image_alt VARCHAR(255) NOT NULL DEFAULT "",
            project_url VARCHAR(255) NOT NULL DEFAULT "",
            sort_order INT NOT NULL DEFAULT 100,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    if (app_db_table_exists($pdo, 'products') && !app_db_column_exists($pdo, 'products', 'subtitle_text')) {
        $pdo->exec("ALTER TABLE products ADD COLUMN subtitle_text TEXT NOT NULL AFTER title");
    }

    if (app_db_table_exists($pdo, 'products') && !app_db_column_exists($pdo, 'products', 'is_out_of_stock')) {
        $pdo->exec("ALTER TABLE products ADD COLUMN is_out_of_stock TINYINT(1) NOT NULL DEFAULT 0 AFTER image_path");
    }

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS product_thickness_options (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            product_id INT UNSIGNED NOT NULL,
            option_key VARCHAR(100) NOT NULL DEFAULT "",
            label VARCHAR(255) NOT NULL DEFAULT "",
            value_text VARCHAR(255) NOT NULL DEFAULT "",
            spec_size TEXT NOT NULL,
            spec_weight VARCHAR(255) NOT NULL DEFAULT "",
            sort_order INT NOT NULL DEFAULT 1,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_product_thickness_options_product
                FOREIGN KEY (product_id) REFERENCES products(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS product_thickness_option_prices (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            thickness_option_id INT UNSIGNED NOT NULL,
            label VARCHAR(255) NOT NULL DEFAULT "",
            amount VARCHAR(255) NOT NULL DEFAULT "",
            unit VARCHAR(100) NOT NULL DEFAULT "",
            sort_order INT NOT NULL DEFAULT 1,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_product_thickness_option_prices_option
                FOREIGN KEY (thickness_option_id) REFERENCES product_thickness_options(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    app_db_seed_default_thickness_options($pdo);

    $done = true;
}

function app_db_seed_default_thickness_options(PDO $pdo): void
{
    if (!app_db_table_exists($pdo, 'product_thickness_options')) {
        return;
    }

    $stmt = $pdo->query(
        'SELECT p.id, p.spec_size, p.spec_weight
         FROM products p
         WHERE p.show_thickness = 1
           AND NOT EXISTS (
             SELECT 1
             FROM product_thickness_options o
             WHERE o.product_id = p.id
           )'
    );
    $products = $stmt->fetchAll();
    if ($products === []) {
        return;
    }

    $optionStmt = $pdo->prepare(
        'INSERT INTO product_thickness_options (product_id, option_key, label, value_text, spec_size, spec_weight, sort_order)
         VALUES (:product_id, :option_key, :label, :value_text, :spec_size, :spec_weight, :sort_order)'
    );
    $priceSelectStmt = $pdo->prepare(
        'SELECT label, amount, unit, sort_order
         FROM product_color_prices
         WHERE product_id = :product_id
         ORDER BY sort_order ASC, id ASC'
    );
    $priceInsertStmt = $pdo->prepare(
        'INSERT INTO product_thickness_option_prices (thickness_option_id, label, amount, unit, sort_order)
         VALUES (:thickness_option_id, :label, :amount, :unit, :sort_order)'
    );

    foreach ($products as $product) {
        $variants = [
            ['option_key' => 'standard', 'label' => 'Стандарт', 'value_text' => '30, 35, 40 мм'],
            ['option_key' => 'reinforced', 'label' => 'Усиленная', 'value_text' => '45, 50, 60 мм'],
        ];

        $priceSelectStmt->execute(['product_id' => (int) $product['id']]);
        $prices = $priceSelectStmt->fetchAll();

        foreach ($variants as $index => $variant) {
            $optionStmt->execute([
                'product_id' => (int) $product['id'],
                'option_key' => $variant['option_key'],
                'label' => $variant['label'],
                'value_text' => $variant['value_text'],
                'spec_size' => (string) ($product['spec_size'] ?? ''),
                'spec_weight' => (string) ($product['spec_weight'] ?? ''),
                'sort_order' => $index + 1,
            ]);
            $optionId = (int) $pdo->lastInsertId();

            foreach ($prices as $price) {
                $priceInsertStmt->execute([
                    'thickness_option_id' => $optionId,
                    'label' => (string) ($price['label'] ?? ''),
                    'amount' => (string) ($price['amount'] ?? ''),
                    'unit' => (string) ($price['unit'] ?? ''),
                    'sort_order' => (int) ($price['sort_order'] ?? 1),
                ]);
            }
        }
    }
}

function app_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_start();
}

function app_redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function app_flash_set(string $type, string $message): void
{
    app_start_session();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function app_flash_get(): ?array
{
    app_start_session();
    if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function app_csrf_token(): string
{
    app_start_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function app_verify_csrf(?string $token): bool
{
    app_start_session();

    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function app_request_method(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function app_is_post(): bool
{
    return app_request_method() === 'POST';
}

function app_post_string(string $key, string $default = ''): string
{
    $value = $_POST[$key] ?? $default;

    return is_string($value) ? trim($value) : $default;
}

function app_get_string(string $key, string $default = ''): string
{
    $value = $_GET[$key] ?? $default;

    return is_string($value) ? trim($value) : $default;
}

function app_post_bool(string $key): bool
{
    return isset($_POST[$key]) && $_POST[$key] !== '0';
}

function app_slugify(string $value): string
{
    $map = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e',
        'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm',
        'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'cz', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh',
        'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ];

    $value = mb_strtolower(trim($value), 'UTF-8');
    $value = strtr($value, $map);
    $value = preg_replace('/[^a-z0-9]+/u', '-', $value) ?? '';
    $value = trim($value, '-');

    return $value !== '' ? $value : 'item';
}

function app_relative_upload_dir(string $scope): string
{
    return 'uploads/' . trim($scope, '/');
}

function app_save_uploaded_image(array $file, string $scope, string $baseName): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Не удалось загрузить изображение.');
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        throw new RuntimeException('Некорректный загруженный файл.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string) $finfo->file($tmpName);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
        'image/svg+xml' => 'svg',
    ];

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Поддерживаются только изображения JPG, PNG, WEBP, GIF и SVG.');
    }

    $relativeDir = app_relative_upload_dir($scope);
    $absoluteDir = app_path($relativeDir);
    if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0777, true) && !is_dir($absoluteDir)) {
        throw new RuntimeException('Не удалось создать папку для загрузок.');
    }

    $fileName = app_slugify($baseName) . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    $targetPath = $absoluteDir . DIRECTORY_SEPARATOR . $fileName;

    if (!move_uploaded_file($tmpName, $targetPath)) {
        throw new RuntimeException('Не удалось сохранить файл.');
    }

    return app_url_path($relativeDir . '/' . $fileName);
}

function app_parse_lines(string $value, string $separator = '|'): array
{
    $rows = [];
    foreach (preg_split('/\R/u', $value) ?: [] as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $parts = array_map('trim', explode($separator, $line));
        $rows[] = $parts;
    }

    return $rows;
}
