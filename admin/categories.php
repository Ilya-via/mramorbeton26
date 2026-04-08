<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

admin_require_login();
$user = admin_current_user();

if (!catalog_db_has_catalog_data()) {
    admin_render_db_notice($user);
    return;
}

try {
    if (app_is_post()) {
        admin_require_csrf();
        if (app_post_string('action') === 'delete') {
            catalog_admin_delete_category((int) app_post_string('id'));
            app_flash_set('success', 'Категория удалена.');
            app_redirect('categories.php');
        }
    }
} catch (Throwable $e) {
    app_flash_set('error', $e->getMessage());
    app_redirect('categories.php');
}

$categories = catalog_admin_list_categories();

admin_render_header('Категории', $user);
?>
  <section class="admin-card">
    <div class="admin-toolbar">
      <div>
        <h1>Разделы каталога</h1>
        <p class="admin-page-lead">Здесь редактируются крупные блоки каталога: например, тротуарная плитка, фасадные панели и другие направления.</p>
      </div>
      <a class="admin-button" href="category-edit.php">Добавить категорию</a>
    </div>
  </section>

  <section class="admin-table-wrap">
    <?php if ($categories === []) : ?>
      <div class="admin-empty">
        <h2>Пока нет ни одной категории</h2>
        <p>Добавьте первый раздел каталога, и он появится на сайте.</p>
      </div>
    <?php else : ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Категория</th>
          <th>Как показывать</th>
          <th>Товары</th>
          <th>Статус</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $category) : ?>
          <tr>
            <td>
              <strong><?= admin_e($category['title']) ?></strong><br>
              <span class="admin-hint">Порядок на сайте: <?= (int) $category['sort_order'] ?></span><br>
              <span class="admin-hint">Служебный адрес: <?= admin_e($category['slug']) ?></span>
            </td>
            <td><?= admin_e(admin_layout_name($category['layout'] ?? '')) ?></td>
            <td><?= (int) $category['product_count'] ?></td>
            <td>
              <?php if (!empty($category['is_active'])) : ?>
                <span class="admin-badge admin-badge--success">Активна</span>
              <?php else : ?>
                <span class="admin-badge admin-badge--muted">Скрыта</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="admin-inline-actions">
                <a class="admin-button--ghost" href="category-edit.php?id=<?= (int) $category['id'] ?>">Редактировать</a>
                <form method="post" onsubmit="return confirm('Удалить категорию?');">
                  <input type="hidden" name="csrf_token" value="<?= admin_e(app_csrf_token()) ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= (int) $category['id'] ?>">
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
