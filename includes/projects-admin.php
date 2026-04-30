<?php
declare(strict_types=1);

require_once __DIR__ . '/projects-data.php';

function projects_admin_seed_defaults_if_empty(PDO $pdo): void
{
    $count = (int) $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $defaults = projects_default_items();
    if ($defaults === []) {
        return;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO projects (slug, title, description, image_path, image_alt, project_url, sort_order, is_active)
         VALUES (:slug, :title, :description, :image_path, :image_alt, :project_url, :sort_order, :is_active)'
    );

    foreach ($defaults as $item) {
        $stmt->execute([
            'slug' => (string) ($item['slug'] ?? ''),
            'title' => (string) ($item['title'] ?? ''),
            'description' => (string) ($item['description'] ?? ''),
            'image_path' => (string) ($item['image_path'] ?? ''),
            'image_alt' => (string) ($item['image_alt'] ?? ''),
            'project_url' => (string) ($item['project_url'] ?? ''),
            'sort_order' => (int) ($item['sort_order'] ?? 100),
            'is_active' => !empty($item['is_active']) ? 1 : 0,
        ]);
    }
}

function projects_admin_assert_db(): PDO
{
    $pdo = app_pdo();
    if (!$pdo instanceof PDO) {
        throw new RuntimeException('База данных не настроена. Проверьте подключение в config/database.php.');
    }

    projects_ensure_table($pdo);
    projects_admin_seed_defaults_if_empty($pdo);

    return $pdo;
}

function projects_admin_list(): array
{
    $pdo = projects_admin_assert_db();

    return $pdo->query(
        'SELECT id, slug, title, description, image_path, image_alt, project_url, sort_order, is_active
         FROM projects
         ORDER BY sort_order ASC, id ASC'
    )->fetchAll();
}

function projects_admin_get(?int $id): ?array
{
    if ($id === null) {
        return [
            'id' => null,
            'slug' => '',
            'title' => '',
            'description' => '',
            'image_path' => '',
            'image_alt' => '',
            'project_url' => '',
            'sort_order' => 100,
            'is_active' => 1,
        ];
    }

    $pdo = projects_admin_assert_db();
    $stmt = $pdo->prepare(
        'SELECT id, slug, title, description, image_path, image_alt, project_url, sort_order, is_active
         FROM projects
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return is_array($row) ? $row : null;
}

function projects_admin_save(array $post, array $files = []): int
{
    $pdo = projects_admin_assert_db();
    $id = isset($post['id']) && $post['id'] !== '' ? (int) $post['id'] : null;
    $title = trim((string) ($post['title'] ?? ''));
    if ($title === '') {
        throw new RuntimeException('Укажите название проекта.');
    }

    $slug = trim((string) ($post['slug'] ?? ''));
    $slug = $slug !== '' ? app_slugify($slug) : app_slugify($title);
    if ($slug === '') {
        throw new RuntimeException('Не удалось сформировать адрес проекта.');
    }

    $duplicateStmt = $pdo->prepare(
        'SELECT id
         FROM projects
         WHERE slug = :slug
           AND (:id IS NULL OR id != :id)
         LIMIT 1'
    );
    $duplicateStmt->execute([
        'slug' => $slug,
        'id' => $id,
    ]);
    if ($duplicateStmt->fetch()) {
        throw new RuntimeException('Проект с таким адресом уже существует.');
    }

    $imagePath = trim((string) ($post['image_path'] ?? ''));
    if (isset($files['image_upload']) && is_array($files['image_upload'])) {
        $uploaded = app_save_uploaded_image($files['image_upload'], 'projects', $title);
        if ($uploaded !== null) {
            $imagePath = $uploaded;
        }
    }

    $data = [
        'slug' => $slug,
        'title' => $title,
        'description' => trim((string) ($post['description'] ?? '')),
        'image_path' => $imagePath,
        'image_alt' => trim((string) ($post['image_alt'] ?? '')),
        'project_url' => trim((string) ($post['project_url'] ?? '')),
        'sort_order' => (int) ($post['sort_order'] ?? 100),
        'is_active' => !empty($post['is_active']) ? 1 : 0,
    ];

    if ($id === null) {
        $stmt = $pdo->prepare(
            'INSERT INTO projects (slug, title, description, image_path, image_alt, project_url, sort_order, is_active)
             VALUES (:slug, :title, :description, :image_path, :image_alt, :project_url, :sort_order, :is_active)'
        );
        $stmt->execute($data);

        return (int) $pdo->lastInsertId();
    }

    $data['id'] = $id;
    $stmt = $pdo->prepare(
        'UPDATE projects
         SET slug = :slug,
             title = :title,
             description = :description,
             image_path = :image_path,
             image_alt = :image_alt,
             project_url = :project_url,
             sort_order = :sort_order,
             is_active = :is_active
         WHERE id = :id'
    );
    $stmt->execute($data);

    return $id;
}

function projects_admin_delete(int $id): void
{
    $pdo = projects_admin_assert_db();
    $stmt = $pdo->prepare('DELETE FROM projects WHERE id = :id');
    $stmt->execute(['id' => $id]);
}
