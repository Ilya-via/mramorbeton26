<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

admin_require_login();
$user = admin_current_user();

if (!catalog_db_has_catalog_data()) {
    admin_render_db_notice($user);
    return;
}

$id = app_get_string('id') !== '' ? (int) app_get_string('id') : null;
$product = catalog_admin_get_product($id);

if ($product === null) {
    app_flash_set('error', 'Товар не найден.');
    app_redirect('products.php');
}

$categories = catalog_admin_category_options(false);
$relatedOptions = catalog_admin_product_options($id);
$error = null;

$galleryText = '';
$mainImagePath = (string) ($product['image_path'] ?? ($product['image'] ?? ''));
foreach ($product['gallery_rows'] ?? [] as $row) {
    $fullPath = (string) ($row['full_path'] ?? '');
    if ($mainImagePath !== '' && $fullPath === $mainImagePath) {
        continue;
    }
    $galleryText .= $fullPath . PHP_EOL;
}

$pricesText = '';
foreach ($product['price_rows'] ?? [] as $row) {
    $pricesText .= ($row['label'] ?? '') . '|' . ($row['amount'] ?? '') . '|' . ($row['unit'] ?? '') . PHP_EOL;
}

$thicknessOptionsJson = json_encode($product['thickness_options'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($thicknessOptionsJson === false) {
    $thicknessOptionsJson = '[]';
}

if (app_is_post()) {
    try {
        admin_require_csrf();
        $savedId = catalog_admin_save_product($_POST, $_FILES);
        app_flash_set('success', 'Товар сохранён.');
        app_redirect('product-edit.php?id=' . $savedId);
    } catch (Throwable $e) {
        $error = $e->getMessage();
        $product = array_merge($product, $_POST);
        $product['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
        $product['is_featured'] = !empty($_POST['is_featured']) ? 1 : 0;
        $product['is_out_of_stock'] = !empty($_POST['is_out_of_stock']) ? 1 : 0;
        $product['show_thickness'] = !empty($_POST['show_thickness']) ? 1 : 0;
        $product['related_ids'] = array_map('intval', $_POST['related_ids'] ?? []);
        $galleryText = (string) ($_POST['gallery_text'] ?? '');
        $pricesText = (string) ($_POST['prices_text'] ?? '');
        $thicknessOptionsJson = (string) ($_POST['thickness_options_json'] ?? '[]');
    }
}

admin_render_header($id === null ? 'Новый товар' : 'Редактирование товара', $user);
?>
  <section class="admin-card">
    <div class="admin-toolbar">
      <div>
        <h1><?= $id === null ? 'Новый товар' : 'Редактирование товара' ?></h1>
      </div>
      <a class="admin-button--ghost" href="products.php">Назад к списку</a>
      
    </div>
    <br>

    <?php if ($error !== null) : ?>
      <div class="admin-alert admin-alert--error"><?= admin_e($error) ?></div>
    <?php endif; ?>

    <form class="admin-form" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= admin_e(app_csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= admin_e((string) ($product['id'] ?? '')) ?>">
      <div class="admin-section-stack">
        <section class="admin-section-card">
          <h2>Основная информация</h2> <br>
          <div class="admin-form__grid">
            <div class="admin-form__field">
              <label for="category_id">Раздел каталога</label>
              <select id="category_id" name="category_id" required>
                <option value="">Выберите раздел</option>
                <?php foreach ($categories as $category) : ?>
                  <option value="<?= (int) $category['id'] ?>"<?= admin_selected($product['category_id'] ?? '', $category['id']) ?>>
                    <?= admin_e($category['title']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="admin-form__field">
              <label for="title">Название товара</label>
              <input id="title" data-autoslug-source data-autoslug-target="#slug" type="text" name="title" value="<?= admin_e($product['title'] ?? '') ?>" required>
            </div>
            <div class="admin-form__field admin-form__field--full">
              <label for="subtitle_text">Подзаголовок под названием</label>
              <textarea id="subtitle_text" name="subtitle_text"><?= admin_e($product['subtitle_text'] ?? ($product['subtitle'] ?? '')) ?></textarea>
              <p class="admin-hint">Необязательно. Если оставить пустым, на странице товара подзаголовок не покажется.</p>
            </div>
            <input type="hidden" id="description" name="description" value="<?= admin_e($product['description'] ?? '') ?>">
          </div>
        </section>

        <section class="admin-section-card admin-section-card--soft">
          <h2>Главная фотография</h2>
          <input id="image_path" type="hidden" name="image_path" value="<?= admin_e($mainImagePath) ?>">
          <div class="admin-upload-dropzone" data-single-image-uploader data-hidden-input="#image_path" data-file-input="#image_upload">
            <input id="image_upload" class="admin-hidden" type="file" name="image_upload" accept="image/*">
            <div class="admin-image-preview" data-image-preview data-preview-input="#image_path" data-preview-upload="#image_upload">
              <div class="admin-image-preview__placeholder" data-preview-placeholder>Здесь будет показана главная фотография товара</div>
              <img alt="Предпросмотр главного изображения товара" hidden>
            </div>
            <div class="admin-upload-dropzone__body">
              <p class="admin-subtitle">Перетащите сюда изображение или выберите файл.</p>
              <button class="admin-button--ghost" type="button" data-file-trigger>Выбрать файл</button>
            </div>
          </div>
        </section>

        <section class="admin-section-card">
          <h2>Основные характеристики</h2>
          <div class="admin-form__grid">
            <div class="admin-form__field">
              <label for="gabarity">Габариты</label>
              <input id="gabarity" type="text" name="gabarity" value="<?= admin_e($product['gabarity'] ?? '') ?>">
            </div>
            <div class="admin-form__field">
              <label for="weight">Вес</label>
              <input id="weight" type="text" name="weight" value="<?= admin_e($product['weight'] ?? '') ?>">
            </div>
          </div>
          <p class="admin-hint">Подпись под названием и цена в каталоге формируются автоматически из этих данных и из цен товара.</p>
        </section>

        <section class="admin-section-card">
          <h2>Детальная страница товара</h2>
          <div class="admin-form__grid">
            <div class="admin-form__field">
              <label for="spec_size">Размер на странице товара</label>
              <input id="spec_size" type="text" name="spec_size" value="<?= admin_e($product['spec_size'] ?? '') ?>">
            </div>
            <div class="admin-form__field">
              <label for="spec_weight">Вес на странице товара</label>
              <input id="spec_weight" type="text" name="spec_weight" value="<?= admin_e($product['spec_weight'] ?? '') ?>">
            </div>
            <div class="admin-checkboxes">
              <label class="admin-checkbox">
                <input type="checkbox" name="show_thickness" value="1" data-thickness-toggle<?= admin_checked(!empty($product['show_thickness'])) ?>>
                <span>Показывать переключатель толщины</span>
              </label>
            </div>
          </div>
          <div class="admin-section-card admin-section-card--soft" data-show-when-thickness>
            <h3>Варианты толщины</h3>
            <p class="admin-subtitle">Если переключатель включён, добавьте здесь варианты. Для каждого варианта можно задать свои размеры, вес и цены.</p>
            <div class="admin-thickness-editor" data-thickness-editor data-target="#thickness_options_json">
              <div class="admin-thickness-editor__rows" data-thickness-rows></div>
              <button class="admin-button--ghost" type="button" data-thickness-add>Добавить вариант толщины</button>
            </div>
            <textarea id="thickness_options_json" class="admin-hidden" name="thickness_options_json"><?= admin_e($thicknessOptionsJson) ?></textarea>
          </div>
        </section>

        <section class="admin-section-card" data-show-when-no-thickness>
          <h2>Цены по цветам или вариантам</h2>
          <div class="admin-collection" data-collection-editor data-target="#prices_text" data-separator="|" data-fields='[{"key":"label","label":"Название","placeholder":"Серый"},{"key":"amount","label":"Цена","placeholder":"30"},{"key":"unit","label":"Единица","placeholder":"руб/м²"}]'>
            <div data-collection-rows class="admin-collection__rows"></div>
            <button class="admin-button--ghost" type="button" data-collection-add>Добавить ещё цену</button>
          </div>
          <textarea id="prices_text" class="admin-hidden" name="prices_text"><?= admin_e(trim($pricesText)) ?></textarea>
        </section>

        <section class="admin-section-card">
          <h2>Галерея товара</h2>
          <p class="admin-subtitle">Добавляйте только сами изображения. Миниатюра создаётся автоматически, подпись вручную не нужна.</p>
          <div class="admin-gallery-manager" data-gallery-manager data-target="#gallery_text" data-file-input="#gallery_uploads">
            <div class="admin-gallery-grid" data-gallery-grid></div>
            <div class="admin-upload-dropzone admin-upload-dropzone--compact" data-gallery-dropzone>
              <input id="gallery_uploads" class="admin-hidden" type="file" name="gallery_uploads[]" accept="image/*" multiple>
              <div class="admin-upload-dropzone__body">
                <p class="admin-subtitle">Перетащите сюда дополнительные фото или выберите файлы.</p>
                <button class="admin-button--ghost" type="button" data-gallery-trigger>Выбрать файлы</button>
              </div>
            </div>
          </div>
          <textarea id="gallery_text" class="admin-hidden" name="gallery_text"><?= admin_e(trim($galleryText)) ?></textarea>
        </section>

        <section class="admin-section-card">
          <h2>Рекомендации под товаром</h2>
          <p class="admin-subtitle">Можно выбрать товары вручную. Если ничего не отмечать, сайт сам подберёт похожие позиции. Максимум: 3 товара.</p>
          <div class="admin-form__field">
            <label for="related_search">Быстрый поиск по списку</label>
            <input id="related_search" type="text" data-filter-input data-filter-target="#related_choice_grid" placeholder="Начните вводить название товара">
          </div>
          <div id="related_choice_grid" class="admin-choice-grid">
            <?php foreach ($relatedOptions as $option) : ?>
              <?php $selected = in_array((int) $option['id'], array_map('intval', $product['related_ids'] ?? []), true); ?>
              <label class="admin-choice" data-filter-item="<?= admin_e($option['category_title'] . ' ' . $option['title']) ?>">
                <input type="checkbox" name="related_ids[]" value="<?= (int) $option['id'] ?>"<?= $selected ? ' checked' : '' ?>>
                <span>
                  <strong><?= admin_e($option['title']) ?></strong>
                  <span class="admin-choice__meta"><?= admin_e($option['category_title']) ?></span>
                </span>
              </label>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="admin-section-card">
          <h2>Публикация и показ на сайте</h2>
          <div class="admin-checkboxes">
            <label class="admin-checkbox">
              <input type="checkbox" name="is_active" value="1"<?= admin_checked(!empty($product['is_active'])) ?>>
              <span>Показывать товар на сайте</span>
            </label>
            <label class="admin-checkbox">
              <input type="checkbox" name="is_out_of_stock" value="1"<?= admin_checked(!empty($product['is_out_of_stock'])) ?>>
              <span>Показывать метку «Нет в наличии»</span>
            </label>
            <label class="admin-checkbox">
              <input type="checkbox" name="is_featured" value="1"<?= admin_checked(!empty($product['is_featured'])) ?>>
              <span>Показывать товар в блоке популярных товаров на главной</span>
            </label>
          </div>
          <p class="admin-hint">Если включить метку, на странице товара и на карточках в каталоге/рекомендациях появится плашка как в макете.</p>
        </section>

        <section class="admin-section-card">
          <details>
            <summary>Дополнительные настройки</summary>
            <div class="admin-form__grid">
              <div class="admin-form__field">
                <label for="sort_order">Порядок показа</label>
                <input id="sort_order" type="number" name="sort_order" value="<?= admin_e((string) ($product['sort_order'] ?? 100)) ?>">
              </div>
            </div>
          </details>
        </section>
      </div>

      <div class="admin-form__actions">
        <button class="admin-button" type="submit">Сохранить</button>
      </div>
    </form>
  </section>
<?php
admin_render_footer();
