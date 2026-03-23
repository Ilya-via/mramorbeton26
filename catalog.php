<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/catalog-data.php';

$categories = catalog_get_categories();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Каталог — MRAMORBETON</title>
  <meta
    name="description"
    content="Каталог бетонных изделий MRAMORBETON: накладные проступи, тротуарная плитка, фасадные панели, бордюры, ритуальные и пошаговые плиты."
  >
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Spectral+SC:wght@700&display=swap"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="catalog.css">
</head>
<body class="page-catalog">
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="index.html" aria-label="MRAMORBETON">
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
        <a href="catalog.php" aria-current="page">Каталог</a>
        <a href="index.html#about">О нас</a>
        <a href="projects.html">Проекты</a>
        <a href="index.html#process">Как мы работаем</a>
        <a href="useful-info.html">Полезная информация</a>
        <a href="contacts.html">Контакты</a>
      </nav>

      <div class="header-contacts">
        <div class="header-phone-wrap">
          <a class="header-phone" href="tel:+375293258259">+375 29 325-82-59</a>
          <span>9:00-20:00 Пн-Вс</span>
        </div>
        <a class="button button-accent button-small" href="index.html#contact-form">Заказать звонок</a>
      </div>
    </div>
  </header>

  <main class="catalog-page-main">
    <div class="container">
      <nav class="catalog-breadcrumbs" aria-label="Хлебные крошки">
        <a href="index.html">Главная</a>
        <span aria-hidden="true">/</span>
        <span class="catalog-breadcrumbs-current">Каталог</span>
      </nav>

      <header class="catalog-intro">
        <p class="catalog-kicker">Каталог продукции 2026</p>
        <h1 class="catalog-title">Бетонные изделия для благоустройства и строительства</h1>
        <p class="catalog-lead">Полный ассортимент бетонных изделий с характеристиками и актуальными ценами</p>
      </header>

      <div class="catalog-grid">
        <?php foreach ($categories as $slug => $cat) : ?>
          <a
            class="catalog-card catalog-page-card catalog-card-link"
            href="category.php?c=<?= catalog_esc($slug) ?>"
          >
            <div class="catalog-page-card-media">
              <img src="<?= catalog_esc($cat['image']) ?>" alt="<?= catalog_esc($cat['title']) ?>" loading="lazy">
            </div>
            <div class="catalog-page-card-content">
              <h3><?= catalog_esc($cat['title']) ?></h3>
              <p class="catalog-card-desc"><?= catalog_esc($cat['description']) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container footer-grid">
      <div class="footer-brand">
        <div class="footer-brand-top">
          <a class="brand footer-brand-logo" href="index.html" aria-label="MRAMORBETON">
            <img class="brand-logo" src="assets/images/logo.svg" alt="" width="45" height="45" decoding="async">
            <span class="brand-copy">
              <strong>MRAMORBETON</strong>
              <small>Производство бетонных изделий</small>
            </span>
          </a>
        </div>
        <p class="footer-brand-text">
          Профессиональное производство<br>
          бетонных изделий для современной<br>
          городской и частной инфраструктуры.<br>
          Гарантия качества на века.
        </p>
      </div>

      <nav class="footer-column" aria-label="Продукция">
        <p class="footer-heading">Продукция</p>
        <div class="footer-links">
          <a href="category.php?c=trotuarnaya-plitka">Брусчатка</a>
          <a href="category.php?c=trotuarnaya-plitka">Тротуарная плитка</a>
          <a href="category.php?c=fasadnye-paneli">Фасадные панели</a>
          <a href="category.php?c=bordyury-i-vodostoki">Бордюры и водостоки</a>
          <a href="category.php?c=nakladnye-prostupi">Накладные проступи</a>
          <a href="category.php?c=ritualnye-plity">Ритуальные плиты</a>
          <a href="category.php?c=poshagovye-plity">Пошаговые плиты</a>
          <a href="category.php?c=parapetnye-plity">Накрывные элементы</a>
        </div>
      </nav>

      <nav class="footer-column" aria-label="Компания">
        <p class="footer-heading">Компания</p>
        <div class="footer-links">
          <a href="catalog.php">Каталог</a>
          <a href="index.html#about">О нас</a>
          <a href="projects.html">Проекты</a>
          <a href="index.html#process">Как мы работаем</a>
          <a href="contacts.html">Контакты</a>
        </div>
      </nav>

      <div class="footer-column footer-column-contacts">
        <p class="footer-heading">Контакты</p>
        <div class="footer-contacts-stack">
          <a class="footer-contact" href="tel:+375293258259">
            <span class="footer-contact-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M7.5 4.5H4.8A1.8 1.8 0 0 0 3 6.3c0 8.118 6.582 14.7 14.7 14.7a1.8 1.8 0 0 0 1.8-1.8v-2.7a1.2 1.2 0 0 0-.87-1.154l-3.252-.93a1.2 1.2 0 0 0-1.214.353l-.713.871a12.035 12.035 0 0 1-5.084-5.084l.871-.713a1.2 1.2 0 0 0 .353-1.214l-.93-3.252A1.2 1.2 0 0 0 7.5 4.5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
            <span class="footer-contact-body">
              <span class="footer-contact-primary">+375 29 325-82-59</span>
              <span class="footer-contact-meta">Пн-Пт: 9:00 — 20:00</span>
            </span>
          </a>

          <a class="footer-contact" href="mailto:Mramorbeton.by@gmail.com">
            <span class="footer-contact-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 7.5 12 13l8-5.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.7"/>
              </svg>
            </span>
            <span class="footer-contact-body">
              <span class="footer-contact-primary">Mramorbeton.by@gmail.com</span>
            </span>
          </a>

          <div class="footer-contact">
            <span class="footer-contact-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 20c3.333-3.895 5-6.727 5-8.5a5 5 0 1 0-10 0c0 1.773 1.667 4.605 5 8.5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="11" r="1.8" fill="currentColor"/>
              </svg>
            </span>
            <span class="footer-contact-body">
              <span class="footer-contact-primary footer-contact-primary--wide">Минский р-н, Хатежинский c.c., д.Васьковщина</span>
            </span>
          </div>

          <div class="socials socials-contacts" aria-label="Соцсети">
            <a class="social social--whatsapp" href="#" aria-label="WhatsApp">
              <img src="assets/images/footer-icon-1.svg" width="39" height="39" alt="" decoding="async">
            </a>
            <a class="social social--viber" href="#" aria-label="Viber">
              <img src="assets/images/footer-icon-2.svg" width="39" height="39" alt="" decoding="async">
            </a>
            <a class="social social--telegram" href="#" aria-label="Telegram">
              <img src="assets/images/footer-icon-3.svg" width="39" height="39" alt="" decoding="async">
            </a>
            <a class="social social--instagram" href="#" aria-label="Instagram">
              <img src="assets/images/footer-icon-4.svg" width="39" height="39" alt="" decoding="async">
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>
