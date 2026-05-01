<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

admin_require_login();
$user = admin_current_user();

if (!catalog_db_has_catalog_data()) {
    admin_render_db_notice($user);
    return;
}

$categories = catalog_admin_list_categories();
$products = catalog_admin_list_products();
$featuredCount = 0;
foreach ($products as $product) {
    if (!empty($product['is_featured'])) {
        $featuredCount++;
    }
}

admin_render_header('Главная', $user);
?>
  <section class="admin-stats">
    <article class="admin-stat">
      <p>Категории</p>
      <h2><?= count($categories) ?></h2>
    </article>
    <article class="admin-stat">
      <p>Товары</p>
      <h2><?= count($products) ?></h2>
    </article>
    <article class="admin-stat">
      <p>Товары на главной</p>
      <h2><?= $featuredCount ?></h2>
    </article>
  </section>

  <section class="admin-card">
    <h1>Панель управления каталогом</h1>
    <p class="admin-page-lead">Здесь можно менять разделы каталога, товары, блок популярных товаров на главной и ручные рекомендации.</p>
    <div class="admin-actions">
      <a class="admin-button" href="category-edit.php">Добавить категорию</a>
      <a class="admin-button" href="product-edit.php">Добавить товар</a>
      <a class="admin-button--ghost" href="categories.php">Открыть категории</a>
      <a class="admin-button--ghost" href="products.php">Открыть товары</a>
      <a class="admin-button--ghost" href="users.php">Пользователи</a>
    </div>
  </section>

  <section class="admin-card">
    <h2>Содержимое главной страницы</h2>
    <p class="admin-page-lead">Редактирование текстов, фотографий и кнопок отдельных блоков главной: первый экран, особенности производства, «О нас», объекты, инстаграм, шаги работы, контактная форма и футер.</p>
    <div class="admin-actions">
      <a class="admin-button" href="home.php">Редактировать главную</a>
      <a class="admin-button--ghost" href="../index.php" target="_blank" rel="noreferrer">Открыть главную</a>
    </div>
  </section>

  <section class="admin-card">
    <h2>Страница «Проекты»</h2>
    <p class="admin-page-lead">Добавляйте новые проекты, меняйте фото, название, описание, ссылку и порядок показа карточек на странице проектов.</p>
    <div class="admin-actions">
      <a class="admin-button" href="projects.php">Управлять проектами</a>
      <a class="admin-button--ghost" href="../projects.php" target="_blank" rel="noreferrer">Открыть страницу</a>
    </div>
  </section>

  <section class="admin-card">
    <h2>Как пользоваться</h2>
    <div class="admin-helper-grid">
      <article class="admin-helper">
        <h3>1. Сначала категории</h3>
        <p>Создайте или поправьте разделы каталога: название, картинку и порядок отображения.</p>
      </article>
      <article class="admin-helper">
        <h3>2. Затем товары</h3>
        <p>Добавьте товар в нужную категорию, загрузите фото и укажите нужные характеристики.</p>
      </article>
      <article class="admin-helper">
        <h3>3. Всё остальное автоматически</h3>
        <p>Если не выбирать рекомендации вручную, сайт сам подберёт похожие товары в блоке «Возможно вас заинтересует».</p>
      </article>
    </div>
  </section>
<?php
admin_render_footer();
