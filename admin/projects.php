<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

admin_require_login();
$user = admin_current_user();

try {
    if (app_is_post()) {
        admin_require_csrf();
        if (app_post_string('action') === 'delete') {
            projects_admin_delete((int) app_post_string('id'));
            app_flash_set('success', 'Проект удалён.');
            app_redirect('projects.php');
        }
    }
} catch (Throwable $e) {
    app_flash_set('error', $e->getMessage());
    app_redirect('projects.php');
}

$projects = projects_admin_list();

admin_render_header('Проекты', $user);
?>
  <section class="admin-card">
    <div class="admin-toolbar">
      <div>
        <h1>Проекты</h1>
        <p class="admin-page-lead">Здесь можно добавлять, сортировать и редактировать карточки страницы «Проекты»: фото, название, описание и ссылку.</p>
      </div>
      <a class="admin-button" href="project-edit.php">Добавить проект</a>
    </div>
  </section>

  <section class="admin-table-wrap">
    <?php if ($projects === []) : ?>
      <div class="admin-empty">
        <h2>Пока нет проектов</h2>
        <p>Добавьте первый проект — он появится на странице «Проекты» на сайте.</p>
      </div>
    <?php else : ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Проект</th>
          <th>Ссылка</th>
          <th>Статус</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $project) : ?>
          <tr>
            <td>
              <strong><?= admin_e($project['title']) ?></strong><br>
              <span class="admin-hint">Порядок на сайте: <?= (int) $project['sort_order'] ?></span><br>
              <span class="admin-hint">Служебный адрес: <?= admin_e($project['slug']) ?></span>
            </td>
            <td>
              <?php if (!empty($project['project_url'])) : ?>
                <a class="admin-link" href="<?= admin_e($project['project_url']) ?>" target="_blank" rel="noreferrer">Открыть</a>
              <?php else : ?>
                <span class="admin-hint">Без ссылки</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if (!empty($project['is_active'])) : ?>
                <span class="admin-badge admin-badge--success">Активен</span>
              <?php else : ?>
                <span class="admin-badge admin-badge--muted">Скрыт</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="admin-inline-actions">
                <a class="admin-button--ghost" href="project-edit.php?id=<?= (int) $project['id'] ?>">Редактировать</a>
                <form method="post" onsubmit="return confirm('Удалить проект?');">
                  <input type="hidden" name="csrf_token" value="<?= admin_e(app_csrf_token()) ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= (int) $project['id'] ?>">
                  <button class="admin-button--danger" type="submit">Удалить</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </section>
<?php
admin_render_footer();
