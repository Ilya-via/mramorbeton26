<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

admin_require_login();
$user = admin_current_user();

if (!catalog_db_has_catalog_data()) {
    admin_render_db_notice($user);
    return;
}

$selectedCategoryId = app_get_string('category_id') !== '' ? (int) app_get_string('category_id') : null;

try {
    if (app_is_post()) {
        admin_require_csrf();
        if (app_post_string('action') === 'delete') {
            catalog_admin_delete_product((int) app_post_string('id'));
            app_flash_set('success', 'Товар удалён.');
            app_redirect('products.php' . ($selectedCategoryId ? '?category_id=' . $selectedCategoryId : ''));
        }
    }
} catch (Throwable $e) {
    app_flash_set('error', $e->getMessage());
    app_redirect('products.php' . ($selectedCategoryId ? '?category_id=' . $selectedCategoryId : ''));
}

$categories = catalog_admin_category_options();
$products = catalog_admin_list_products($selectedCategoryId);

admin_render_header('Товары', $user);
?>
  <section class="admin-card">
    <div class="admin-toolbar">
      <div>
        <h1>Товары</h1>
        <p class="admin-page-lead">Здесь редактируются карточки товаров: название, фото, цена, характеристики, показ на главной и рекомендации.</p>
      </div>
      <a class="admin-button" href="product-edit.php">Добавить товар</a>
    </div>
    <form class="admin-filter" method="get">
      <label for="category_id">Показать товары только из раздела</label>
      <select id="category_id" name="category_id">
        <option value="">Все категории</option>
        <?php foreach ($categories as $category) : ?>
          <option value="<?= (int) $category['id'] ?>"<?= admin_selected($selectedCategoryId, $category['id']) ?>>
            <?= admin_e($category['title']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button class="admin-button--ghost" type="submit">Применить</button>
    </form>
  </section>

  <section class="admin-table-wrap">
    <?php if ($products === []) : ?>
      <div class="admin-empty">
        <h2>Товары не найдены</h2>
        <p>Попробуйте снять фильтр или добавьте новый товар.</p>
      </div>
    <?php else : ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Товар</th>
          <th>Категория</th>
          <th>Показывать на главной</th>
          <th>Статус</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) : ?>
          <tr>
            <td>
              <strong><?= admin_e($product['title']) ?></strong><br>
              <span class="admin-hint">Порядок на сайте: <?= (int) $product['sort_order'] ?></span><br>
              <span class="admin-hint">Служебный адрес: <?= admin_e($product['slug']) ?></span>
            </td>
            <td><?= admin_e($product['category_title']) ?></td>
            <td>
              <?php if (!empty($product['is_featured'])) : ?>
                <span class="admin-badge admin-badge--success">Да</span>
              <?php else : ?>
                <span class="admin-badge admin-badge--muted">Нет</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if (!empty($product['is_active'])) : ?>
                <span class="admin-badge admin-badge--success">Активен</span>
              <?php else : ?>
                <span class="admin-badge admin-badge--muted">Скрыт</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="admin-inline-actions">
                <a class="admin-button--ghost" href="product-edit.php?id=<?= (int) $product['id'] ?>">Редактировать</a>
                <form method="post" onsubmit="return confirm('Удалить товар?');">
                  <input type="hidden" name="csrf_token" value="<?= admin_e(app_csrf_token()) ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
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
