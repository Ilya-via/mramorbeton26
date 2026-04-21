<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if (admin_current_user() !== null) {
    app_redirect('index.php');
}

$error = null;
$redirect = app_get_string('redirect', 'index.php');

if (app_is_post()) {
    try {
        admin_require_csrf();
        $redirect = app_post_string('redirect', 'index.php');
        if (admin_login(app_post_string('username'), app_post_string('password'))) {
            app_flash_set('success', 'Вы вошли в админку.');
            app_redirect($redirect !== '' ? $redirect : 'index.php');
        }
        $error = 'Неверный логин или пароль.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Вход | Админка MRAMORBETON</title>
  <link rel="stylesheet" href="admin.css">
  <link rel="shortcut icon" href="../assets/images/logo.svg" type="image/x-icon">
</head>
<body class="admin-body">
  <div class="admin-login">
    <div class="admin-login__card">
      <h1>Вход в админку</h1>
      <p>Управление каталогом, товарами и блоком рекомендаций.</p>

      <?php if (!app_db_ready() || !admin_has_users()) : ?>
        <div class="admin-alert admin-alert--info">
          Сначала выполните `F:\xampp\php\php.exe scripts/setup_catalog_db.php --admin-user=admin --admin-pass=StrongPassword123`.
        </div>
      <?php endif; ?>

      <?php if ($error !== null) : ?>
        <div class="admin-alert admin-alert--error"><?= admin_e($error) ?></div>
      <?php endif; ?>

      <form class="admin-form" method="post">
        <input type="hidden" name="csrf_token" value="<?= admin_e(app_csrf_token()) ?>">
        <input type="hidden" name="redirect" value="<?= admin_e($redirect) ?>">
        <div class="admin-form__field">
          <label for="username">Логин</label>
          <input id="username" type="text" name="username" value="admin" autocomplete="username">
        </div>
        <div class="admin-form__field">
          <label for="password">Пароль</label>
          <input id="password" type="password" name="password" autocomplete="current-password">
        </div>
        <button class="admin-button" type="submit">Войти</button>
      </form>
    </div>
  </div>
</body>
</html>
