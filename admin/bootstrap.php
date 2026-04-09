<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/catalog-data.php';
require_once __DIR__ . '/../includes/catalog-admin.php';
require_once __DIR__ . '/../includes/admin-auth.php';

function admin_e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function admin_selected($left, $right): string
{
    return (string) $left === (string) $right ? ' selected' : '';
}

function admin_checked(bool $value): string
{
    return $value ? ' checked' : '';
}

function admin_layout_name(?string $layout): string
{
    switch ((string) $layout) {
        case 'paving':
            return 'Плитка / мощение';
        case 'overhead_steps':
            return 'Накладные проступи';
        default:
            return 'Обычный список';
    }
}

function admin_render_header(string $title, ?array $user = null): void
{
    $flash = app_flash_get();
    ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= admin_e($title) ?> | Админка MRAMORBETON</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Spectral+SC:wght@700&display=swap"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="admin.css">
  <script defer src="admin.js"></script>
</head>
<body class="admin-body">
  <header class="admin-header">
    <div class="admin-header__inner">
      <a class="admin-brand" href="index.php" aria-label="MRAMORBETON Admin">
        <img class="admin-brand__logo" src="../assets/images/logo.svg" alt="">
        <span class="admin-brand__copy">
          <strong>MRAMORBETON</strong>
          <small>Админ-панель</small>
        </span>
      </a>
      <?php if ($user !== null) : ?>
      <nav class="admin-nav">
        <a href="index.php">Главная</a>
        <a href="categories.php">Категории</a>
        <a href="products.php">Товары</a>
        <a href="../catalog.php" target="_blank" rel="noreferrer">Сайт</a>
        <a href="logout.php">Выйти</a>
      </nav>
      <div class="admin-user"><?= admin_e($user['display_name'] ?? $user['username'] ?? 'Администратор') ?></div>
      <?php endif; ?>
    </div>
  </header>
  <main class="admin-main">
    <div class="admin-container">
      <?php if ($flash !== null) : ?>
        <div class="admin-alert admin-alert--<?= admin_e($flash['type'] ?? 'info') ?>">
          <?= admin_e($flash['message'] ?? '') ?>
        </div>
      <?php endif; ?>
<?php
}

function admin_render_footer(): void
{
    ?>
    </div>
  </main>
</body>
</html>
<?php
}

function admin_require_csrf(): void
{
    if (!app_verify_csrf($_POST['csrf_token'] ?? null)) {
        throw new RuntimeException('Сессия устарела. Обновите страницу и попробуйте снова.');
    }
}

function admin_render_db_notice(?array $user = null): void
{
    admin_render_header('Настройка базы данных', $user);
    ?>
      <section class="admin-card">
        <h1>Нужно инициализировать MySQL</h1>
        <p>Перед использованием админки выполните одноразовый импорт схемы и данных каталога:</p>
        <pre class="admin-code">F:\xampp\php\php.exe scripts/setup_catalog_db.php --admin-user=admin --admin-pass=StrongPassword123</pre>
        <p>По умолчанию будут использованы параметры XAMPP из `config/database.php` или значения по умолчанию `root` / без пароля.</p>
      </section>
    <?php
    admin_render_footer();
}
