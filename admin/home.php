<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../includes/home-content.php';

admin_require_login();
$user = admin_current_user();

$error = null;

if (app_is_post()) {
    try {
        admin_require_csrf();
        home_settings_save($_POST, $_FILES);
        app_flash_set('success', 'Содержимое главной страницы сохранено.');
        app_redirect('home.php');
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

$home = home_settings_get_all();

$header = $home['header'];
$hero = $home['hero'];
$features = $home['features'];
$about = $home['about'];
$projectsHome = $home['projects_home'];
$instagram = $home['instagram'];
$process = $home['process'];
$leadForm = $home['lead_form'];
$footer = $home['footer'];

// JSON для динамических редакторов в JS.
$projectsHomeJson = json_encode($projectsHome['cards'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]';
$instagramItemsJson = json_encode($instagram['items'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]';
$featureItemsJson = json_encode($features['items'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]';
$processItemsJson = json_encode($process['items'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]';
$footerProductsJson = json_encode($footer['products'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]';

admin_render_header('Главная страница', $user);
?>
  <section class="admin-card">
    <div class="admin-toolbar">
      <div>
        <h1>Главная страница</h1>
        <p class="admin-page-lead">Здесь редактируется контент только перечисленных блоков. Шапка с меню, блок «100+ довольных клиентов» и иконки соцсетей в футере остаются без изменений.</p>
      </div>
      <a class="admin-button--ghost" href="../index.php" target="_blank" rel="noreferrer">Открыть главную</a>
    </div>
  </section>

  <?php if ($error !== null) : ?>
    <div class="admin-alert admin-alert--error"><?= admin_e($error) ?></div>
  <?php endif; ?>

  <form class="admin-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= admin_e(app_csrf_token()) ?>">

    <div class="admin-section-stack">
      <!-- Шапка -->
      <section class="admin-section-card">
        <h2>Шапка сайта</h2>
        <p class="admin-subtitle">Только текст номера телефона и время работы. Остальные пункты меню не меняются.</p>
        <div class="admin-form__grid">
          <div class="admin-form__field">
            <label for="header_phone_text">Телефон (текст на сайте)</label>
            <input id="header_phone_text" type="text" name="header[phone_text]" value="<?= admin_e($header['phone_text']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="header_phone_href">Телефон (ссылка tel:)</label>
            <input id="header_phone_href" type="text" name="header[phone_href]" value="<?= admin_e($header['phone_href']) ?>">
            <p class="admin-hint">Например: tel:+375293258259</p>
          </div>
          <div class="admin-form__field">
            <label for="header_hours_text">Время работы</label>
            <input id="header_hours_text" type="text" name="header[hours_text]" value="<?= admin_e($header['hours_text']) ?>">
          </div>
        </div>
      </section>

      <!-- Hero -->
      <section class="admin-section-card">
        <h2>Первый экран</h2>
        <div class="admin-form__grid">
          <div class="admin-form__field admin-form__field--full">
            <label for="hero_title_html">Заголовок</label>
            <textarea id="hero_title_html" name="hero[title_html]" rows="3"><?= admin_e($hero['title_html']) ?></textarea>
            <p class="admin-hint">Можно использовать &lt;br&gt; для переноса и &lt;span&gt;…&lt;/span&gt; для оранжевого выделения.</p>
          </div>
          <div class="admin-form__field admin-form__field--full">
            <label for="hero_description">Подзаголовок</label>
            <textarea id="hero_description" name="hero[description]" rows="2"><?= admin_e($hero['description']) ?></textarea>
          </div>
          <div class="admin-form__field">
            <label for="hero_button_text">Текст кнопки</label>
            <input id="hero_button_text" type="text" name="hero[button_text]" value="<?= admin_e($hero['button_text']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="hero_button_url">Ссылка кнопки</label>
            <input id="hero_button_url" type="text" name="hero[button_url]" value="<?= admin_e($hero['button_url']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="hero_image_alt">Подпись к изображению (alt)</label>
            <input id="hero_image_alt" type="text" name="hero[image_alt]" value="<?= admin_e($hero['image_alt']) ?>">
          </div>
        </div>
        <h3>Главное фото</h3>
        <input id="hero_image_path" type="hidden" name="hero[image_path]" value="<?= admin_e($hero['image_path']) ?>">
        <div class="admin-upload-dropzone" data-single-image-uploader data-hidden-input="#hero_image_path" data-file-input="#hero_image_upload">
          <input id="hero_image_upload" class="admin-hidden" type="file" name="hero_image" accept="image/*">
          <div class="admin-image-preview" data-image-preview data-preview-input="#hero_image_path" data-preview-upload="#hero_image_upload">
            <div class="admin-image-preview__placeholder" data-preview-placeholder>Здесь будет показано изображение первого экрана</div>
            <img alt="Предпросмотр изображения первого экрана" hidden>
          </div>
          <div class="admin-upload-dropzone__body">
            <p class="admin-subtitle">Перетащите сюда новое изображение или выберите файл.</p>
            <button class="admin-button--ghost" type="button" data-file-trigger>Выбрать файл</button>
          </div>
        </div>
      </section>

      <!-- Особенности -->
      <section class="admin-section-card">
        <h2>Особенности производства</h2>
        <p class="admin-subtitle">Можно править только заголовок секции и текст карточек. Номера 01/02/03 проставляются автоматически.</p>
        <div class="admin-form__grid">
          <div class="admin-form__field">
            <label for="features_section_tag">Маленький заголовок</label>
            <input id="features_section_tag" type="text" name="features[section_tag]" value="<?= admin_e($features['section_tag']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="features_heading">Большой заголовок</label>
            <input id="features_heading" type="text" name="features[heading]" value="<?= admin_e($features['heading']) ?>">
          </div>
        </div>

        <div
          class="admin-home-list"
          data-home-list-editor
          data-name="features[items]"
          data-fields='[{"key":"title","label":"Заголовок карточки","placeholder":"Высокопрочный бетон"},{"key":"text","label":"Описание","placeholder":"Текст карточки","multiline":true}]'
          data-add-label="Добавить особенность"
        >
          <div data-home-list-rows></div>
          <button class="admin-button--ghost" type="button" data-home-list-add>Добавить особенность</button>
        </div>
        <textarea class="admin-hidden" data-home-list-state="features[items]"><?= admin_e($featureItemsJson) ?></textarea>
      </section>

      <!-- О нас -->
      <section class="admin-section-card">
        <h2>О нас</h2>
        <p class="admin-subtitle">Блок «100+ довольных клиентов» оставляем как есть, его не редактируем.</p>
        <div class="admin-form__grid">
          <div class="admin-form__field">
            <label for="about_section_tag">Маленький заголовок</label>
            <input id="about_section_tag" type="text" name="about[section_tag]" value="<?= admin_e($about['section_tag']) ?>">
          </div>
          <div class="admin-form__field admin-form__field--full">
            <label for="about_heading_html">Заголовок</label>
            <textarea id="about_heading_html" name="about[heading_html]" rows="2"><?= admin_e($about['heading_html']) ?></textarea>
            <p class="admin-hint">Можно использовать &lt;br&gt; для переноса.</p>
          </div>
          <div class="admin-form__field admin-form__field--full">
            <label for="about_lead">Текст под заголовком</label>
            <textarea id="about_lead" name="about[lead]" rows="6"><?= admin_e($about['lead']) ?></textarea>
          </div>
          <div class="admin-form__field">
            <label for="about_image_alt">Подпись к фото (alt)</label>
            <input id="about_image_alt" type="text" name="about[image_alt]" value="<?= admin_e($about['image_alt']) ?>">
          </div>
        </div>
        <h3>Фото</h3>
        <input id="about_image_path" type="hidden" name="about[image_path]" value="<?= admin_e($about['image_path']) ?>">
        <div class="admin-upload-dropzone" data-single-image-uploader data-hidden-input="#about_image_path" data-file-input="#about_image_upload">
          <input id="about_image_upload" class="admin-hidden" type="file" name="about_image" accept="image/*">
          <div class="admin-image-preview" data-image-preview data-preview-input="#about_image_path" data-preview-upload="#about_image_upload">
            <div class="admin-image-preview__placeholder" data-preview-placeholder>Здесь будет показано фото блока «О нас»</div>
            <img alt="Предпросмотр фото блока «О нас»" hidden>
          </div>
          <div class="admin-upload-dropzone__body">
            <p class="admin-subtitle">Перетащите сюда новое изображение или выберите файл.</p>
            <button class="admin-button--ghost" type="button" data-file-trigger>Выбрать файл</button>
          </div>
        </div>
      </section>

      <!-- Реализованные объекты -->
      <section class="admin-section-card">
        <h2>Реализованные объекты на главной</h2>
        <p class="admin-subtitle">Первая карточка большая, остальные показываются справа стопкой. Можно указать ссылку на страницу объекта.</p>
        <div class="admin-form__grid">
          <div class="admin-form__field">
            <label for="projects_home_section_tag">Маленький заголовок</label>
            <input id="projects_home_section_tag" type="text" name="projects_home[section_tag]" value="<?= admin_e($projectsHome['section_tag']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="projects_home_heading">Большой заголовок</label>
            <input id="projects_home_heading" type="text" name="projects_home[heading]" value="<?= admin_e($projectsHome['heading']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="projects_home_button_text">Текст кнопки</label>
            <input id="projects_home_button_text" type="text" name="projects_home[button_text]" value="<?= admin_e($projectsHome['button_text']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="projects_home_button_url">Ссылка кнопки</label>
            <input id="projects_home_button_url" type="text" name="projects_home[button_url]" value="<?= admin_e($projectsHome['button_url']) ?>">
          </div>
        </div>

        <div
          class="admin-home-list"
          data-home-list-editor
          data-name="projects_home[cards]"
          data-image-field="image_path"
          data-image-input-name="projects_home_files"
          data-fields='[{"key":"title","label":"Название","placeholder":"ЖК Индустриальный"},{"key":"subtitle","label":"Подпись (только для большой карточки)","placeholder":"Комплексное благоустройство"},{"key":"link","label":"Ссылка на объект","placeholder":"projects.php"},{"key":"image_alt","label":"Alt-текст","placeholder":"Описание фото"}]'
          data-add-label="Добавить объект"
        >
          <div data-home-list-rows></div>
          <button class="admin-button--ghost" type="button" data-home-list-add>Добавить объект</button>
        </div>
        <textarea class="admin-hidden" data-home-list-state="projects_home[cards]"><?= admin_e($projectsHomeJson) ?></textarea>
      </section>

      <!-- Instagram -->
      <section class="admin-section-card">
        <h2>Инстаграм</h2>
        <div class="admin-form__grid">
          <div class="admin-form__field">
            <label for="instagram_section_tag">Маленький заголовок</label>
            <input id="instagram_section_tag" type="text" name="instagram[section_tag]" value="<?= admin_e($instagram['section_tag']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="instagram_heading">Большой заголовок</label>
            <input id="instagram_heading" type="text" name="instagram[heading]" value="<?= admin_e($instagram['heading']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="instagram_note_prefix">Текст до ссылки</label>
            <input id="instagram_note_prefix" type="text" name="instagram[note_prefix]" value="<?= admin_e($instagram['note_prefix']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="instagram_note_suffix">Текст после ссылки</label>
            <input id="instagram_note_suffix" type="text" name="instagram[note_suffix]" value="<?= admin_e($instagram['note_suffix']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="instagram_handle_text">Ник в инстаграм</label>
            <input id="instagram_handle_text" type="text" name="instagram[handle_text]" value="<?= admin_e($instagram['handle_text']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="instagram_handle_url">Ссылка на профиль</label>
            <input id="instagram_handle_url" type="text" name="instagram[handle_url]" value="<?= admin_e($instagram['handle_url']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="instagram_button_text">Текст кнопки</label>
            <input id="instagram_button_text" type="text" name="instagram[button_text]" value="<?= admin_e($instagram['button_text']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="instagram_button_url">Ссылка кнопки</label>
            <input id="instagram_button_url" type="text" name="instagram[button_url]" value="<?= admin_e($instagram['button_url']) ?>">
          </div>
        </div>

        <div
          class="admin-home-list"
          data-home-list-editor
          data-name="instagram[items]"
          data-image-field="image_path"
          data-image-input-name="instagram_files"
          data-fields='[{"key":"image_alt","label":"Alt-текст","placeholder":"Описание изображения"}]'
          data-add-label="Добавить публикацию"
        >
          <div data-home-list-rows></div>
          <button class="admin-button--ghost" type="button" data-home-list-add>Добавить публикацию</button>
        </div>
        <textarea class="admin-hidden" data-home-list-state="instagram[items]"><?= admin_e($instagramItemsJson) ?></textarea>
      </section>

      <!-- Процесс -->
      <section class="admin-section-card">
        <h2>Как мы работаем</h2>
        <p class="admin-subtitle">Номера шагов проставляются автоматически.</p>
        <div class="admin-form__grid">
          <div class="admin-form__field">
            <label for="process_section_tag">Маленький заголовок</label>
            <input id="process_section_tag" type="text" name="process[section_tag]" value="<?= admin_e($process['section_tag']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="process_heading">Большой заголовок</label>
            <input id="process_heading" type="text" name="process[heading]" value="<?= admin_e($process['heading']) ?>">
          </div>
          <div class="admin-form__field admin-form__field--full">
            <label for="process_intro">Текст под заголовком</label>
            <textarea id="process_intro" name="process[intro]" rows="3"><?= admin_e($process['intro']) ?></textarea>
          </div>
        </div>

        <div
          class="admin-home-list"
          data-home-list-editor
          data-name="process[items]"
          data-fields='[{"key":"title","label":"Название шага","placeholder":"Заявка и консультация"},{"key":"text","label":"Описание","placeholder":"Текст шага","multiline":true},{"key":"text_extra","label":"Дополнительный абзац (необязательно)","placeholder":"","multiline":true}]'
          data-add-label="Добавить шаг"
        >
          <div data-home-list-rows></div>
          <button class="admin-button--ghost" type="button" data-home-list-add>Добавить шаг</button>
        </div>
        <textarea class="admin-hidden" data-home-list-state="process[items]"><?= admin_e($processItemsJson) ?></textarea>
      </section>

      <!-- Форма заявки -->
      <section class="admin-section-card">
        <h2>Форма заявки</h2>
        <p class="admin-subtitle">Текстовый блок рядом с формой. Сама форма и поля не меняются.</p>
        <div class="admin-form__grid">
          <div class="admin-form__field admin-form__field--full">
            <label for="lead_form_heading_html">Заголовок</label>
            <textarea id="lead_form_heading_html" name="lead_form[heading_html]" rows="2"><?= admin_e($leadForm['heading_html']) ?></textarea>
            <p class="admin-hint">Можно использовать &lt;br&gt; для переноса.</p>
          </div>
          <div class="admin-form__field admin-form__field--full">
            <label for="lead_form_lead">Подпись под заголовком</label>
            <textarea id="lead_form_lead" name="lead_form[lead]" rows="3"><?= admin_e($leadForm['lead']) ?></textarea>
          </div>
          <div class="admin-form__field">
            <label for="lead_form_phone_label">Подпись над телефоном</label>
            <input id="lead_form_phone_label" type="text" name="lead_form[phone_label]" value="<?= admin_e($leadForm['phone_label']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="lead_form_phone_text">Телефон (текст)</label>
            <input id="lead_form_phone_text" type="text" name="lead_form[phone_text]" value="<?= admin_e($leadForm['phone_text']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="lead_form_phone_href">Телефон (ссылка tel:)</label>
            <input id="lead_form_phone_href" type="text" name="lead_form[phone_href]" value="<?= admin_e($leadForm['phone_href']) ?>">
          </div>
        </div>
      </section>

      <!-- Footer -->
      <section class="admin-section-card">
        <h2>Футер</h2>
        <p class="admin-subtitle">Редактируются текст под логотипом, контакты и пункты колонки «Продукция». Иконки соцсетей и колонка «Компания» остаются как есть.</p>
        <div class="admin-form__grid">
          <div class="admin-form__field admin-form__field--full">
            <label for="footer_brand_text_html">Текст под логотипом</label>
            <textarea id="footer_brand_text_html" name="footer[brand_text_html]" rows="4"><?= admin_e($footer['brand_text_html']) ?></textarea>
            <p class="admin-hint">Можно использовать &lt;br&gt; для переноса.</p>
          </div>
        </div>

        <h3>Колонка «Продукция»</h3>
        <p class="admin-subtitle">Можно добавлять и удалять пункты, у каждого свой текст и ссылка.</p>
        <div
          class="admin-home-list"
          data-home-list-editor
          data-name="footer[products]"
          data-fields='[{"key":"label","label":"Название","placeholder":"Брусчатка"},{"key":"href","label":"Ссылка","placeholder":"category.php?c=trotuarnaya-plitka"}]'
          data-add-label="Добавить пункт"
        >
          <div data-home-list-rows></div>
          <button class="admin-button--ghost" type="button" data-home-list-add>Добавить пункт</button>
        </div>
        <textarea class="admin-hidden" data-home-list-state="footer[products]"><?= admin_e($footerProductsJson) ?></textarea>

        <h3>Контакты</h3>
        <div class="admin-form__grid">
          <div class="admin-form__field">
            <label for="footer_contact_phone_text">Телефон (текст)</label>
            <input id="footer_contact_phone_text" type="text" name="footer[contact_phone_text]" value="<?= admin_e($footer['contact_phone_text']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="footer_contact_phone_href">Телефон (ссылка tel:)</label>
            <input id="footer_contact_phone_href" type="text" name="footer[contact_phone_href]" value="<?= admin_e($footer['contact_phone_href']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="footer_contact_phone_meta">Подпись под телефоном</label>
            <input id="footer_contact_phone_meta" type="text" name="footer[contact_phone_meta]" value="<?= admin_e($footer['contact_phone_meta']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="footer_contact_email_text">Email (текст)</label>
            <input id="footer_contact_email_text" type="text" name="footer[contact_email_text]" value="<?= admin_e($footer['contact_email_text']) ?>">
          </div>
          <div class="admin-form__field">
            <label for="footer_contact_email_href">Email (ссылка mailto:)</label>
            <input id="footer_contact_email_href" type="text" name="footer[contact_email_href]" value="<?= admin_e($footer['contact_email_href']) ?>">
          </div>
          <div class="admin-form__field admin-form__field--full">
            <label for="footer_contact_address_text">Адрес</label>
            <input id="footer_contact_address_text" type="text" name="footer[contact_address_text]" value="<?= admin_e($footer['contact_address_text']) ?>">
          </div>
        </div>
      </section>
    </div>

    <div class="admin-form__actions">
      <button class="admin-button" type="submit">Сохранить</button>
    </div>
  </form>
<?php
admin_render_footer();
