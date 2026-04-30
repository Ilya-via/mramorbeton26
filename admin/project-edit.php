<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

admin_require_login();
$user = admin_current_user();

$id = app_get_string('id') !== '' ? (int) app_get_string('id') : null;
$project = projects_admin_get($id);

if ($project === null) {
    app_flash_set('error', 'Проект не найден.');
    app_redirect('projects.php');
}

$error = null;

if (app_is_post()) {
    try {
        admin_require_csrf();
        $savedId = projects_admin_save($_POST, $_FILES);
        app_flash_set('success', 'Проект сохранён.');
        app_redirect('project-edit.php?id=' . $savedId);
    } catch (Throwable $e) {
        $error = $e->getMessage();
        $project = array_merge($project, $_POST);
        $project['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
    }
}

admin_render_header($id === null ? 'Новый проект' : 'Редактирование проекта', $user);
?>
  <section class="admin-card">
    <div class="admin-toolbar">
      <div>
        <h1><?= $id === null ? 'Новый проект' : 'Редактирование проекта' ?></h1>
      </div>
      <a class="admin-button--ghost" href="projects.php">Назад к списку</a>
    </div>

    <?php if ($error !== null) : ?>
      <div class="admin-alert admin-alert--error"><?= admin_e($error) ?></div>
    <?php endif; ?>

    <form class="admin-form" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= admin_e(app_csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= admin_e((string) ($project['id'] ?? '')) ?>">

      <div class="admin-section-stack">
        <section class="admin-section-card">
          <h2>Основная информация</h2>
          <div class="admin-form__grid">
            <div class="admin-form__field">
              <label for="title">Название проекта</label>
              <input id="title" data-autoslug-source data-autoslug-target="#slug" type="text" name="title" value="<?= admin_e($project['title'] ?? '') ?>" required>
            </div>
            <div class="admin-form__field">
              <label for="project_url">Ссылка на проект</label>
              <input id="project_url" type="text" name="project_url" value="<?= admin_e($project['project_url'] ?? '') ?>" placeholder="https://... или projects-detail.php">
            </div>
            <div class="admin-form__field admin-form__field--full">
              <label for="description">Описание (необязательно)</label>
              <textarea id="description" name="description"><?= admin_e($project['description'] ?? '') ?></textarea>
              <p class="admin-hint">Если указано, будет показано под названием карточки.</p>
            </div>
            <div class="admin-form__field">
              <label for="image_alt">Alt-текст изображения</label>
              <input id="image_alt" type="text" name="image_alt" value="<?= admin_e($project['image_alt'] ?? '') ?>">
            </div>
          </div>
        </section>

        <section class="admin-section-card admin-section-card--soft">
          <h2>Фото проекта</h2>
          <div class="admin-pair">
            <div class="admin-form__grid">
              <div class="admin-form__field admin-form__field--full">
                <label for="image_path">Ссылка или путь к изображению</label>
                <input id="image_path" type="text" name="image_path" value="<?= admin_e($project['image_path'] ?? '') ?>">
              </div>
              <div class="admin-form__field admin-form__field--full">
                <label for="image_upload">Или загрузите новое изображение</label>
                <input id="image_upload" type="file" name="image_upload" accept="image/*">
                <p class="admin-hint">Если загрузить файл, путь выше будет заменён.</p>
              </div>
            </div>
            <div class="admin-image-preview" data-image-preview data-preview-input="#image_path" data-preview-upload="#image_upload">
              <div class="admin-image-preview__placeholder" data-preview-placeholder>Здесь будет показано изображение проекта</div>
              <img alt="Предпросмотр изображения проекта" hidden>
            </div>
          </div>
        </section>

        <section class="admin-section-card">
          <h2>Публикация</h2>
          <div class="admin-form__grid">
            <div class="admin-form__field">
              <label for="sort_order">Порядок показа</label>
              <input id="sort_order" type="number" name="sort_order" value="<?= admin_e((string) ($project['sort_order'] ?? 100)) ?>">
            </div>
            <div class="admin-form__field">
              <label for="slug">Служебный адрес (slug)</label>
              <input id="slug" type="text" name="slug" value="<?= admin_e($project['slug'] ?? '') ?>">
              <p class="admin-hint">Можно оставить пустым — сформируется по названию.</p>
            </div>
            <div class="admin-checkboxes">
              <label class="admin-checkbox">
                <input type="checkbox" name="is_active" value="1"<?= admin_checked(!empty($project['is_active'])) ?>>
                <span>Показывать проект на странице сайта</span>
              </label>
            </div>
          </div>
        </section>
      </div>

      <div class="admin-form__actions">
        <button class="admin-button" type="submit">Сохранить</button>
      </div>
    </form>
  </section>
<?php
admin_render_footer();
