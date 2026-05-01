<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

admin_require_login();
$user = admin_current_user();

if (!is_array($user) || empty($user['id'])) {
    app_flash_set('error', 'Сессия устарела. Войдите заново.');
    app_redirect('login.php');
}

$error = null;

if (app_is_post()) {
    try {
        admin_require_csrf();
        $action = app_post_string('action');

        if ($action === 'change_password') {
            admin_change_password(
                (int) $user['id'],
                app_post_string('current_password'),
                app_post_string('new_password'),
                app_post_string('confirm_password')
            );
            app_flash_set('success', 'Пароль успешно изменен.');
            app_redirect('users.php');
        }

        if ($action === 'create_user') {
            admin_create_user(
                app_post_string('username'),
                app_post_string('display_name'),
                app_post_string('password'),
                app_post_string('confirm_password')
            );
            app_flash_set('success', 'Новый пользователь добавлен.');
            app_redirect('users.php');
        }

        throw new RuntimeException('Неизвестное действие формы.');
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$users = admin_list_users();

admin_render_header('Пользователи и пароли', $user);
?>
  <section class="admin-card">
    <h1>Пользователи админки</h1>
    <p class="admin-page-lead">Здесь можно сменить пароль текущего пользователя и создать нового пользователя для доступа в админку.</p>
  </section>

  <?php if ($error !== null) : ?>
    <div class="admin-alert admin-alert--error"><?= admin_e($error) ?></div>
  <?php endif; ?>

  <section class="admin-card">
    <h2>Текущие пользователи</h2>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Имя</th>
            <th>Статус</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $row) : ?>
          <tr>
            <td><?= (int) ($row['id'] ?? 0) ?></td>
            <td><?= admin_e((string) ($row['username'] ?? '')) ?></td>
            <td><?= admin_e((string) ($row['display_name'] ?? '')) ?></td>
            <td><?= !empty($row['is_active']) ? 'Активен' : 'Отключен' ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>

  <section class="admin-card">
    <h2>Сменить свой пароль</h2>
    <form class="admin-form" method="post" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?= admin_e(app_csrf_token()) ?>">
      <input type="hidden" name="action" value="change_password">
      <div class="admin-form__grid">
        <div class="admin-form__field">
          <label for="current_password">Текущий пароль</label>
          <input id="current_password" type="password" name="current_password" required autocomplete="current-password">
        </div>
        <div class="admin-form__field">
          <label for="new_password">Новый пароль</label>
          <input id="new_password" type="password" name="new_password" required minlength="8" autocomplete="new-password">
        </div>
        <div class="admin-form__field">
          <label for="confirm_password">Повторите новый пароль</label>
          <input id="confirm_password" type="password" name="confirm_password" required minlength="8" autocomplete="new-password">
        </div>
      </div>
      <div class="admin-form__actions">
        <button class="admin-button" type="submit">Сменить пароль</button>
      </div>
    </form>
  </section>

  <section class="admin-card">
    <h2>Создать нового пользователя</h2>
    <form class="admin-form" method="post" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?= admin_e(app_csrf_token()) ?>">
      <input type="hidden" name="action" value="create_user">
      <div class="admin-form__grid">
        <div class="admin-form__field">
          <label for="username">Логин</label>
          <input id="username" type="text" name="username" required minlength="3" maxlength="100" pattern="[A-Za-z0-9._-]+" placeholder="manager_1">
        </div>
        <div class="admin-form__field">
          <label for="display_name">Имя для отображения</label>
          <input id="display_name" type="text" name="display_name" required maxlength="150" placeholder="Менеджер">
        </div>
        <div class="admin-form__field">
          <label for="password">Пароль</label>
          <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
        </div>
        <div class="admin-form__field">
          <label for="confirm_password_new_user">Повторите пароль</label>
          <input id="confirm_password_new_user" type="password" name="confirm_password" required minlength="8" autocomplete="new-password">
        </div>
      </div>
      <div class="admin-form__actions">
        <button class="admin-button" type="submit">Создать пользователя</button>
      </div>
    </form>
  </section>
<?php
admin_render_footer();
