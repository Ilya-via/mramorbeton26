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
$category = catalog_admin_get_category($id);

if ($category === null) {
    app_flash_set('error', 'Категория не найдена.');
    app_redirect('categories.php');
}

$error = null;

if (app_is_post()) {
    try {
        admin_require_csrf();
        $savedId = catalog_admin_save_category($_POST, $_FILES);
        app_flash_set('success', 'Категория сохранена.');
        app_redirect('category-edit.php?id=' . $savedId);
    } catch (Throwable $e) {
        $error = $e->getMessage();
        $category = array_merge($category, $_POST);
        $category['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
    }
}

admin_render_header($id === null ? 'Новая категория' : 'Редактирование категории', $user);
?>
  <section class="admin-card">
    <div class="admin-toolbar">
      <div>
        <h1><?= $id === null ? 'Новый раздел каталога' : 'Редактирование раздела каталога' ?></h1>
        <br>
      </div>
      <a class="admin-button--ghost" href="categories.php">Назад к списку</a>
    </div>
    <?php if ($error !== null) : ?>
      <div class="admin-alert admin-alert--error"><?= admin_e($error) ?></div>
    <?php endif; ?>

    <form class="admin-form" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= admin_e(app_csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= admin_e((string) ($category['id'] ?? '')) ?>">
      <input type="hidden" name="layout" value="<?= admin_e($category['layout'] ?? '') ?>">
      <input type="hidden" name="breadcrumbs_type" value="<?= admin_e($category['breadcrumbs_type'] ?? ($category['breadcrumbs'] ?? '')) ?>">
      <input type="hidden" name="kicker" value="Каталог продукции 2026">

      <div class="admin-section-stack">
        <section class="admin-section-card">
          <h2>Основная информация</h2>
          <p class="admin-subtitle">Эти данные увидят посетители в каталоге.</p>
          <div class="admin-form__grid">
            <div class="admin-form__field">
              <label for="title">Название раздела</label>
              <input id="title" data-autoslug-source data-autoslug-target="#slug" type="text" name="title" value="<?= admin_e($category['title'] ?? '') ?>" required>
            </div>
            <div class="admin-form__field">
              <label for="description">Короткое описание</label>
              <textarea id="description" name="description"><?= admin_e($category['description'] ?? '') ?></textarea>
              <p class="admin-hint">Кратко опишите, что находится в этом разделе.</p>
            </div>
          </div>
        </section>

        <section class="admin-section-card admin-section-card--soft">
          <h2>Обложка раздела</h2>
          <div class="admin-pair">
            <div class="admin-form__grid">
              <div class="admin-form__field admin-form__field--full">
                <label for="image_path">Ссылка или путь к изображению</label>
                <input id="image_path" type="text" name="image_path" value="<?= admin_e($category['image_path'] ?? ($category['image'] ?? '')) ?>">
              </div>
              <div class="admin-form__field admin-form__field--full">
                <label for="image_upload">Или загрузите новую картинку</label>
                <input id="image_upload" type="file" name="image_upload" accept="image/*">
                <p class="admin-hint">Если загрузить файл, он заменит путь из поля выше.</p>
              </div>
            </div>
            <div class="admin-image-preview" data-image-preview data-preview-input="#image_path" data-preview-upload="#image_upload">
              <div class="admin-image-preview__placeholder" data-preview-placeholder>Здесь будет показана текущая картинка раздела</div>
              <img alt="Предпросмотр изображения раздела" hidden>
            </div>
          </div>
        </section>

        <section class="admin-section-card">
          <h2>Публикация</h2>
          <div class="admin-checkboxes">
            <label class="admin-checkbox">
              <input type="checkbox" name="is_active" value="1"<?= admin_checked(!empty($category['is_active'])) ?>>
              <span>Показывать этот раздел на сайте</span>
            </label>
          </div>
        </section>

        <section class="admin-section-card">
          <details>
            <summary>Дополнительные настройки</summary>
            <div class="admin-form__grid">
              <div class="admin-form__field">
                <label for="slug">Служебный адрес раздела</label>
                <input id="slug" type="text" name="slug" value="<?= admin_e($category['slug'] ?? '') ?>">
                <p class="admin-hint">Можно оставить пустым: адрес сформируется автоматически.</p>
              </div>
              <div class="admin-form__field">
                <label for="sort_order">Порядок показа на сайте</label>
                <input id="sort_order" type="number" name="sort_order" value="<?= admin_e((string) ($category['sort_order'] ?? 100)) ?>">
              </div>
              <div class="admin-form__field admin-form__field--full">
                <label for="heading">Большой заголовок страницы раздела</label>
                <input id="heading" type="text" name="heading" value="<?= admin_e($category['heading'] ?? '') ?>">
              </div>
              <div class="admin-form__field admin-form__field--full">
                <label for="lead">Подробный вводный текст страницы</label>
                <textarea id="lead" name="lead"><?= admin_e($category['lead'] ?? '') ?></textarea>
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
