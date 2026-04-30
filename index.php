<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/home-content.php';

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

$featureTones = ['', 'dark', 'accent'];

$projectCards = $projectsHome['cards'] ?? [];
$projectMain = $projectCards[0] ?? null;
$projectStack = array_slice($projectCards, 1);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>MRAMORBETON</title>
  <meta
    name="description"
    content="Производство бетонных изделий под заказ: тротуарная плитка, брусчатка, фасадные панели, бордюры и декоративные элементы."
  >
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Spectral+SC:wght@700&display=swap"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="styles.css?v=1.4">
  <link rel="stylesheet" href="lead-form.css?v=1.1">
  <link rel="shortcut icon" href="assets/images/logo.svg" type="image/x-icon">
  <script>
    (function () {
      try {
        var h = window.location.hash;
        if (!h || h.length < 2) return;
        var id = decodeURIComponent(h.slice(1));
        if (!id) return;
        try {
          sessionStorage.setItem("mb_pending_home_scroll", id);
        } catch (e) {}
        if (window.history && window.history.replaceState) {
          window.history.replaceState(null, "", window.location.pathname + window.location.search);
        }
      } catch (e) {}
    })();
  </script>
</head>
<body class="page-home">
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="#hero" aria-label="MRAMORBETON">
        <img class="brand-logo" src="assets/images/logo.svg" alt="Логотип MRAMORBETON">
        <span class="brand-copy">
          <strong>MRAMORBETON</strong>
          <small>Производство бетонных изделий</small>
        </span>
      </a>

      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
        Меню
      </button>

      <nav class="site-nav" id="site-nav">
        <a href="catalog.php">Каталог</a>
        <a href="#about">О нас</a>
        <a href="projects.php">Проекты</a>
        <a href="#process">Как мы работаем</a>
        <a href="useful-info.html">Полезная информация</a>
        <a href="contacts.html">Контакты</a>
      </nav>

      <div class="header-contacts">
        <div class="header-phone-wrap">
          <a class="header-phone" href="<?= home_e($header['phone_href']) ?>"><?= home_e($header['phone_text']) ?></a>
          <span><?= home_e($header['hours_text']) ?></span>
        </div>
        <a class="button button-accent button-small" href="#contact-form">Заказать звонок</a>
      </div>
    </div>
  </header>

  <main>
    <section class="hero" id="hero">
      <div class="container hero-grid">
        <div class="hero-copy">
          <h1><?= $hero['title_html'] !== '' ? $hero['title_html'] : home_e($hero['title_html']) ?></h1>
          <p class="hero-description">
            <?= home_e($hero['description']) ?>
          </p>
          <div class="hero-actions">
            <a class="button button-dark button-icon" href="<?= home_attr_url($hero['button_url']) ?>">
              <span><?= home_e($hero['button_text']) ?></span>
              <span class="button-arrow" aria-hidden="true"></span>
            </a>
          </div>
        </div>

        <div class="hero-media image-frame image-frame-hero">
          <?php if (!empty($hero['image_path'])) : ?>
          <img
            src="<?= home_e(home_image_src($hero['image_path'])) ?>"
            alt="<?= home_e($hero['image_alt'] ?: 'Главное изображение') ?>"
            loading="eager"
          >
          <?php endif; ?>
        </div>

        <ul class="hero-features">
          <li>Собственное производство</li>
          <li>Доставка по всей Беларуси</li>
          <li>Гарантия 3 года</li>
        </ul>
      </div>
    </section>

    <section class="section" id="catalog">
      <div class="container">
        <div class="section-heading">
          <p class="section-tag">Каталог</p>
          <h2>Что мы производим</h2>
        </div>
        <div class="catalog-grid" id="catalog-grid"></div>
      </div>
    </section>

    <section class="section section-tech">
      <div class="container">
        <div class="section-heading">
          <p class="section-tag"><?= home_e($features['section_tag']) ?></p>
          <h2><?= home_e($features['heading']) ?></h2>
        </div>
        <div class="features-grid" id="features-grid">
          <?php foreach (($features['items'] ?? []) as $i => $item) :
            $tone = $featureTones[$i % count($featureTones)];
            $number = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
          ?>
          <article class="feature-card <?= home_e($tone) ?>">
            <div class="feature-card-top">
              <h3><?= home_e($item['title'] ?? '') ?></h3>
              <span class="feature-card-badge" aria-hidden="true"><?= home_e($number) ?></span>
            </div>
            <p><?= home_e($item['text'] ?? '') ?></p>
          </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="section section-about" id="about">
      <div class="container">
        <div class="about-grid">
          <div class="about-visual">
            <span class="about-visual__square" aria-hidden="true"></span>
            <div class="about-photo image-frame">
              <?php if (!empty($about['image_path'])) : ?>
              <img
                src="<?= home_e(home_image_src($about['image_path'])) ?>"
                alt="<?= home_e($about['image_alt'] ?: 'О нас') ?>"
                loading="lazy"
              >
              <?php endif; ?>
            </div>
            <div class="about-badge">
              <strong>100+</strong>
              <span>довольных клиентов</span>
            </div>
          </div>

          <div class="about-copy">
            <p class="section-tag"><?= home_e($about['section_tag']) ?></p>
            <h2>
              <?= $about['heading_html'] !== '' ? $about['heading_html'] : home_e($about['heading_html']) ?>
            </h2>
            <p class="about-copy__lead">
              <?= home_e_multiline($about['lead']) ?>
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="section section-projects" id="projects">
      <div class="container">
        <div class="section-heading section-heading-inline">
          <div>
            <p class="section-tag"><?= home_e($projectsHome['section_tag']) ?></p>
            <h2><?= home_e($projectsHome['heading']) ?></h2>
          </div>
          <a class="button button-outline" href="<?= home_attr_url($projectsHome['button_url']) ?>"><?= home_e($projectsHome['button_text']) ?></a>
        </div>

        <div class="projects-grid" id="projects-slider">
          <?php if ($projectMain !== null) : ?>
          <?php $mainTag = !empty($projectMain['link']) ? 'a' : 'article'; ?>
          <<?= $mainTag ?> class="project-card project-card-large"<?= !empty($projectMain['link']) ? ' href="' . home_attr_url($projectMain['link']) . '"' : '' ?>>
            <?php if (!empty($projectMain['image_path'])) : ?>
            <img
              src="<?= home_e(home_image_src($projectMain['image_path'])) ?>"
              alt="<?= home_e($projectMain['image_alt'] ?: ($projectMain['title'] ?? '')) ?>"
              loading="lazy"
            >
            <?php endif; ?>
            <div class="project-overlay">
              <h3><?= home_e($projectMain['title'] ?? '') ?></h3>
              <?php if (!empty($projectMain['subtitle'])) : ?>
              <p><?= home_e($projectMain['subtitle']) ?></p>
              <?php endif; ?>
            </div>
          </<?= $mainTag ?>>
          <?php endif; ?>

          <?php if ($projectStack !== []) : ?>
          <div class="projects-stack">
            <?php foreach ($projectStack as $card) : ?>
            <?php $tag = !empty($card['link']) ? 'a' : 'article'; ?>
            <<?= $tag ?> class="project-card"<?= !empty($card['link']) ? ' href="' . home_attr_url($card['link']) . '"' : '' ?>>
              <?php if (!empty($card['image_path'])) : ?>
              <img
                src="<?= home_e(home_image_src($card['image_path'])) ?>"
                alt="<?= home_e($card['image_alt'] ?: ($card['title'] ?? '')) ?>"
                loading="lazy"
              >
              <?php endif; ?>
              <div class="project-overlay">
                <h3><?= home_e($card['title'] ?? '') ?></h3>
                <?php if (!empty($card['subtitle'])) : ?>
                <p><?= home_e($card['subtitle']) ?></p>
                <?php endif; ?>
              </div>
            </<?= $tag ?>>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <div class="mobile-slider-controls" aria-label="Навигация по объектам">
          <button class="mobile-slider-control mobile-slider-control--prev" type="button" data-scroll-target="projects-slider" aria-label="Предыдущий объект"></button>
          <button class="mobile-slider-control mobile-slider-control--next" type="button" data-scroll-target="projects-slider" aria-label="Следующий объект"></button>
        </div>
        <a class="button button-outline mobile-section-button" href="<?= home_attr_url($projectsHome['button_url']) ?>"><?= home_e($projectsHome['button_text']) ?></a>
      </div>
    </section>

    <section class="section" id="instagram">
      <div class="container">
        <div class="section-heading section-heading-inline">
          <div>
            <p class="section-tag"><?= home_e($instagram['section_tag']) ?></p>
            <h2><?= home_e($instagram['heading']) ?></h2>
            <p class="section-note">
              <?= home_e($instagram['note_prefix']) ?>
              <strong>
                <a
                  class="instagram-handle-link"
                  href="<?= home_attr_url($instagram['handle_url']) ?>"
                  target="_blank"
                  rel="noopener noreferrer"
                  ><?= home_e($instagram['handle_text']) ?></a>
              </strong>
              <?= home_e($instagram['note_suffix']) ?>
            </p>
          </div>
          <a
            class="button button-outline"
            href="<?= home_attr_url($instagram['button_url']) ?>"
            target="_blank"
            rel="noopener noreferrer"
            ><?= home_e($instagram['button_text']) ?></a>
        </div>
        <div class="instagram-grid" id="instagram-grid">
          <?php foreach (($instagram['items'] ?? []) as $item) : ?>
          <article class="instagram-card">
            <div class="image-frame">
              <?php if (!empty($item['image_path'])) : ?>
              <img src="<?= home_e(home_image_src($item['image_path'])) ?>" alt="<?= home_e($item['image_alt'] ?? '') ?>" loading="lazy">
              <?php endif; ?>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
        <div class="mobile-slider-controls" aria-label="Навигация по Instagram">
          <button class="mobile-slider-control mobile-slider-control--prev" type="button" data-scroll-target="instagram-grid" aria-label="Предыдущая публикация"></button>
          <button class="mobile-slider-control mobile-slider-control--next" type="button" data-scroll-target="instagram-grid" aria-label="Следующая публикация"></button>
        </div>
        <a
          class="button button-dark mobile-section-button"
          href="<?= home_attr_url($instagram['button_url']) ?>"
          target="_blank"
          rel="noopener noreferrer"
          ><?= home_e($instagram['button_text']) ?></a>
      </div>
    </section>

    <section class="section section-process" id="process">
      <div class="container">
        <div class="process-layout">
          <div class="process-copy">
            <p class="section-tag"><?= home_e($process['section_tag']) ?></p>
            <h2><?= home_e($process['heading']) ?></h2>
            <p class="process-intro">
              <?= home_e($process['intro']) ?>
            </p>
          </div>
          <div class="process-steps-panel">
            <div class="process-steps" id="process-steps">
              <?php foreach (($process['items'] ?? []) as $i => $item) :
                $number = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
              ?>
              <article class="process-step<?= $i === 0 ? ' process-step--current' : '' ?>">
                <span class="process-step__number" aria-hidden="true"><?= home_e($number) ?></span>
                <div class="process-step__body">
                  <h3 class="process-step__title"><?= home_e($item['title'] ?? '') ?></h3>
                  <div class="process-step__text-block">
                    <p class="process-step__text"><?= home_e($item['text'] ?? '') ?></p>
                    <?php if (!empty($item['text_extra'])) : ?>
                    <p class="process-step__text"><?= home_e($item['text_extra']) ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </article>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="shop">
      <div class="container">
        <div class="section-heading">
          <p class="section-tag">магазин</p>
          <h2>Популярные товары</h2>
        </div>
        <div class="products-grid" id="products-grid"></div>
        <div class="mobile-slider-controls" aria-label="Навигация по товарам">
          <button class="mobile-slider-control mobile-slider-control--prev" type="button" data-scroll-target="products-grid" aria-label="Предыдущий товар"></button>
          <button class="mobile-slider-control mobile-slider-control--next" type="button" data-scroll-target="products-grid" aria-label="Следующий товар"></button>
        </div>
      </div>
    </section>

    <section class="section" id="contacts">
      <div class="container">
        <div class="contact-banner">
          <div class="contact-copy">
            <div class="contact-copy-lead">
              <h2><?= $leadForm['heading_html'] !== '' ? $leadForm['heading_html'] : home_e($leadForm['heading_html']) ?></h2>
              <p>
                <?= home_e($leadForm['lead']) ?>
              </p>
            </div>

            <div class="contact-phone-card">
              <span class="contact-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M7.5 4.5H4.8A1.8 1.8 0 0 0 3 6.3c0 8.118 6.582 14.7 14.7 14.7a1.8 1.8 0 0 0 1.8-1.8v-2.7a1.2 1.2 0 0 0-.87-1.154l-3.252-.93a1.2 1.2 0 0 0-1.214.353l-.713.871a12.035 12.035 0 0 1-5.084-5.084l.871-.713a1.2 1.2 0 0 0 .353-1.214l-.93-3.252A1.2 1.2 0 0 0 7.5 4.5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
              <div class="contact-phone-card__text">
                <small><?= home_e($leadForm['phone_label']) ?></small>
                <a href="<?= home_attr_url($leadForm['phone_href']) ?>"><?= home_e($leadForm['phone_text']) ?></a>
              </div>
            </div>
          </div>

          <form class="lead-form" id="contact-form" action="#" method="post" novalidate>
            <div class="lead-form__inner">
              <div class="lead-form__field">
                <label class="lead-form__label" for="contact-name">Ваше имя</label>
                <input
                  class="lead-form__control"
                  type="text"
                  name="name"
                  id="contact-name"
                  placeholder="Александр"
                  autocomplete="name"
                >
              </div>
              <div class="lead-form__field">
                <label class="lead-form__label" for="contact-phone">Номер телефона</label>
                <input
                  class="lead-form__control"
                  type="tel"
                  name="phone"
                  id="contact-phone"
                  placeholder="+375 (___) ___-__-__"
                  autocomplete="tel"
                  inputmode="tel"
                >
              </div>
              <div class="lead-form__field">
                <label class="lead-form__label" for="contact-message">Ваше сообщение</label>
                <textarea
                  class="lead-form__control lead-form__control--message"
                  name="message"
                  id="contact-message"
                  rows="2"
                  placeholder="Введите сообщение"
                ></textarea>
              </div>
              <button class="lead-form__submit" type="submit">
                <span>Отправить заявку</span>
                <svg class="lead-form__submit-icon" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill="currentColor" d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z" />
                </svg>
              </button>
              <p class="lead-form__policy">
                Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности и обработки персональных данных
              </p>
              <p class="lead-form__status" id="form-status" aria-live="polite"></p>
            </div>
          </form>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-grid">
      <div class="footer-brand">
        <div class="footer-brand-top">
          <a class="brand footer-brand-logo" href="#hero" aria-label="MRAMORBETON">
            <img class="brand-logo" src="assets/images/logo.svg" alt="" width="45" height="45" decoding="async">
            <span class="brand-copy">
              <strong>MRAMORBETON</strong>
              <small>Производство бетонных изделий</small>
            </span>
          </a>
        </div>
        <p class="footer-brand-text">
          <?= $footer['brand_text_html'] !== '' ? $footer['brand_text_html'] : home_e($footer['brand_text_html']) ?>
        </p>
      </div>

      <nav class="footer-column" aria-label="Продукция">
        <p class="footer-heading">Продукция</p>
        <div class="footer-links">
          <?php foreach (($footer['products'] ?? []) as $product) : ?>
          <a href="<?= home_attr_url($product['href'] ?? '#') ?>"><?= home_e($product['label'] ?? '') ?></a>
          <?php endforeach; ?>
        </div>
      </nav>

      <nav class="footer-column" aria-label="Компания">
        <p class="footer-heading">Компания</p>
        <div class="footer-links">
          <a href="catalog.php">Каталог</a>
          <a href="#about">О нас</a>
          <a href="projects.php">Проекты</a>
          <a href="#process">Как мы работаем</a>
          <a href="contacts.html">Контакты</a>
        </div>
      </nav>

      <div class="footer-column footer-column-contacts">
        <p class="footer-heading">Контакты</p>
        <div class="footer-contacts-stack">
          <a class="footer-contact" href="<?= home_attr_url($footer['contact_phone_href']) ?>">
            <span class="footer-contact-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M7.5 4.5H4.8A1.8 1.8 0 0 0 3 6.3c0 8.118 6.582 14.7 14.7 14.7a1.8 1.8 0 0 0 1.8-1.8v-2.7a1.2 1.2 0 0 0-.87-1.154l-3.252-.93a1.2 1.2 0 0 0-1.214.353l-.713.871a12.035 12.035 0 0 1-5.084-5.084l.871-.713a1.2 1.2 0 0 0 .353-1.214l-.93-3.252A1.2 1.2 0 0 0 7.5 4.5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
            <span class="footer-contact-body">
              <span class="footer-contact-primary"><?= home_e($footer['contact_phone_text']) ?></span>
              <?php if (!empty($footer['contact_phone_meta'])) : ?>
              <span class="footer-contact-meta"><?= home_e($footer['contact_phone_meta']) ?></span>
              <?php endif; ?>
            </span>
          </a>

          <?php if (!empty($footer['contact_email_text'])) : ?>
          <a class="footer-contact" href="<?= home_attr_url($footer['contact_email_href']) ?>">
            <span class="footer-contact-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 7.5 12 13l8-5.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.7"/>
              </svg>
            </span>
            <span class="footer-contact-body">
              <span class="footer-contact-primary"><?= home_e($footer['contact_email_text']) ?></span>
            </span>
          </a>
          <?php endif; ?>

          <?php if (!empty($footer['contact_address_text'])) : ?>
          <div class="footer-contact">
            <span class="footer-contact-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 20c3.333-3.895 5-6.727 5-8.5a5 5 0 1 0-10 0c0 1.773 1.667 4.605 5 8.5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="11" r="1.8" fill="currentColor"/>
              </svg>
            </span>
            <span class="footer-contact-body">
              <span class="footer-contact-primary footer-contact-primary--wide"><?= home_e($footer['contact_address_text']) ?></span>
            </span>
          </div>
          <?php endif; ?>

          <div class="socials socials-contacts" aria-label="Соцсети">
            <a class="social social--whatsapp" href="https://wa.me/375293258259" aria-label="WhatsApp" target="_blank" rel="noopener noreferrer">
              <img src="assets/images/footer-icon-1.svg" width="39" height="39" alt="" decoding="async">
            </a>
            <a class="social social--viber" href="viber://chat?number=%2B375293258259" aria-label="Viber">
              <img src="assets/images/footer-icon-2.svg" width="39" height="39" alt="" decoding="async">
            </a>
            <a class="social social--telegram" href="https://t.me/+375293258259" aria-label="Telegram" target="_blank" rel="noopener noreferrer">
              <img src="assets/images/footer-icon-3.svg" width="39" height="39" alt="" decoding="async">
            </a>
            <a class="social social--photo" href="https://www.instagram.com/mramorbetonminsk?igsh=MTBjd3dqODFic3J4Mw%3D%3D&amp;utm_source=qr" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
              <img src="assets/images/footer-icon-4.svg" width="39" height="39" alt="" decoding="async">
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script src="script.js?v=1.3"></script>
</body>
</html>
