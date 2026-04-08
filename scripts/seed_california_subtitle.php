<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

$pdo = app_pdo();
if (!$pdo instanceof PDO || !app_db_ready()) {
    fwrite(STDERR, "Database is not ready.\n");
    exit(1);
}

$stmt = $pdo->prepare(
    'UPDATE products
     SET subtitle_text = :subtitle
     WHERE slug = :slug'
);

$stmt->execute([
    'subtitle' => "Высокопрочный бетон по технологии вибролитья\nс улучшенными показателями морозостойкости\nи истираемости",
    'slug' => 'kaliforniya-kamen',
]);

fwrite(STDOUT, "updated\n");
