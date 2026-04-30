<?php
declare(strict_types=1);

require_once __DIR__ . '/app.php';

function projects_default_items(): array
{
    return [
        [
            'slug' => 'zhk-industrialnyj',
            'title' => 'ЖК "Индустриальный"',
            'description' => '',
            'image_path' => 'assets/images/projects-1.png',
            'image_alt' => 'Жилой комплекс «Индустриальный» — мощение и благоустройство',
            'project_url' => '',
            'sort_order' => 10,
            'is_active' => 1,
        ],
        [
            'slug' => 'kottedzhnyj-poselok-lesnoj',
            'title' => 'Коттеджный посёлок "Лесной"',
            'description' => '',
            'image_path' => 'assets/images/projects-2.png',
            'image_alt' => 'Коттеджный посёлок «Лесной»',
            'project_url' => '',
            'sort_order' => 20,
            'is_active' => 1,
        ],
        [
            'slug' => 'chastnyj-dom-minskaya-oblast-1',
            'title' => 'Частный дом, Минская область',
            'description' => '',
            'image_path' => 'assets/images/projects-3.png',
            'image_alt' => 'Частный дом, Минская область',
            'project_url' => '',
            'sort_order' => 30,
            'is_active' => 1,
        ],
        [
            'slug' => 'chastnyj-dom-minskaya-oblast-2',
            'title' => 'Частный дом, Минская область',
            'description' => '',
            'image_path' => 'assets/images/projects-4.png',
            'image_alt' => 'Частный дом, Минская область — бордюры и мощение',
            'project_url' => '',
            'sort_order' => 40,
            'is_active' => 1,
        ],
        [
            'slug' => 'rezidenciya-v-pos-borovlyany',
            'title' => 'Резиденция в пос. Боровляны',
            'description' => '',
            'image_path' => 'assets/images/projects-5.png',
            'image_alt' => 'Резиденция в посёлке Боровляны — ступени и дорожки',
            'project_url' => '',
            'sort_order' => 50,
            'is_active' => 1,
        ],
        [
            'slug' => 'ofisnyj-kompleks-premium',
            'title' => 'Офисный комплекс "Премиум"',
            'description' => '',
            'image_path' => 'assets/images/projects-6.png',
            'image_alt' => 'Офисный комплекс «Премиум»',
            'project_url' => '',
            'sort_order' => 60,
            'is_active' => 1,
        ],
    ];
}

function projects_ensure_table(PDO $pdo): void
{
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
}

function projects_get_items(bool $onlyActive = true): array
{
    $pdo = app_pdo();
    if ($pdo instanceof PDO) {
        try {
            projects_ensure_table($pdo);
            $sql = 'SELECT id, slug, title, description, image_path, image_alt, project_url, sort_order, is_active
                    FROM projects';
            if ($onlyActive) {
                $sql .= ' WHERE is_active = 1';
            }
            $sql .= ' ORDER BY sort_order ASC, id ASC';

            $rows = $pdo->query($sql)->fetchAll();
            if (is_array($rows) && $rows !== []) {
                return $rows;
            }
        } catch (Throwable $e) {
            // Fallback к дефолтным карточкам ниже.
        }
    }

    $fallback = projects_default_items();
    if (!$onlyActive) {
        return $fallback;
    }

    return array_values(array_filter($fallback, static function (array $item): bool {
        return !empty($item['is_active']);
    }));
}
