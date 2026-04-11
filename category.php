<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/catalog-data.php';

$slug = isset($_GET['c']) ? (string) $_GET['c'] : '';
$category = catalog_find_category($slug);

if ($category === null) {
    http_response_code(404);
    $pageTitle = 'Категория не найдена — MRAMORBETON';
    $notFound = true;
    $isPavingLayout = false;
    $isOverheadStepsLayout = false;
    $isTileCatalogLayout = false;
} else {
    $notFound = false;
    $pageTitle = $category['title'] . ' — MRAMORBETON';
    $layout = $category['layout'] ?? '';
    $isPavingLayout = $layout === 'paving';
    $isOverheadStepsLayout = $layout === 'overhead_steps';
    $isTileCatalogLayout = $isPavingLayout || $isOverheadStepsLayout;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= catalog_esc($pageTitle) ?></title>
  <?php if (!$notFound) : ?>
  <meta name="description" content="<?= catalog_esc($isTileCatalogLayout ? ($category['lead'] ?? $category['description']) : $category['description']) ?>">
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Spectral+SC:wght@700&display=swap"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="catalog.css">
  <link rel="stylesheet" href="category.css">
  <?php if ($isTileCatalogLayout) : ?>
  <link rel="stylesheet" href="category-paving.css">
  <?php endif; ?>
  <?php if ($isOverheadStepsLayout) : ?>
  <link rel="stylesheet" href="category-overhead-steps.css">
  <?php endif; ?>
</head>
<body class="page-catalog page-category<?= $isTileCatalogLayout ? ' page-category-paving' : '' ?><?= $isOverheadStepsLayout ? ' page-category-overhead-steps' : '' ?>">
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
        <a href="catalog.php">Каталог</a>
        <a href="index.html#about">О нас</a>
        <a href="projects.html">Проекты</a>
        <a href="index.html#process">Как мы работаем</a>
        <a href="useful-info.html">Полезная информация</a>
        <a href="contacts.html">Контакты</a>
      </nav>

      <div class="header-contacts">
        <div class="header-phone-wrap">
          <a class="header-phone" href="tel:+375293258259">+375 (29) 325-82-59</a>
          <span>Пн-Вс: 09:00 — 20:00</span>
        </div>
        <a class="button button-accent button-small" href="index.html#contact-form">Заказать звонок</a>
      </div>
    </div>
  </header>

  <main class="category-page-main">
    <div class="container">
      <nav class="catalog-breadcrumbs" aria-label="Хлебные крошки">
        <a href="index.html">Главная</a>
        <?php if ($notFound) : ?>
          <span aria-hidden="true">/</span>
          <span class="catalog-breadcrumbs-current">Не найдено</span>
        <?php elseif (($category['breadcrumbs'] ?? '') === 'short') : ?>
          <span aria-hidden="true">/</span>
          <span class="catalog-breadcrumbs-current"><?= catalog_esc($category['title']) ?></span>
        <?php else : ?>
          <span aria-hidden="true">/</span>
          <a href="catalog.php">Каталог</a>
          <span aria-hidden="true">/</span>
          <span class="catalog-breadcrumbs-current"><?= catalog_esc($category['title']) ?></span>
        <?php endif; ?>
      </nav>

      <?php if ($notFound) : ?>
        <div class="category-not-found">
          <h1 class="category-title">Раздел не найден</h1>
          <p class="category-lead">Проверьте ссылку или вернитесь в каталог.</p>
          <a class="button button-dark" href="catalog.php">В каталог</a>
        </div>
      <?php elseif ($isTileCatalogLayout) : ?>
        <header class="paving-category-intro">
          <p class="paving-category-kicker"><?= catalog_esc($category['kicker'] ?? '') ?></p>
          <h1 class="paving-category-heading"><?= !empty($category['heading_br']) ? nl2br(catalog_esc($category['heading'] ?? $category['title']), false) : catalog_esc($category['heading'] ?? $category['title']) ?></h1>
          <p class="paving-category-lead"><?= catalog_esc($category['lead'] ?? $category['description']) ?></p>
        </header>

        <div class="paving-tiles-grid">
          <?php foreach ($category['products'] as $product) : ?>
            <article class="paving-tile-card">
              <a class="paving-tile-card__link" href="<?= catalog_esc(catalog_product_link($product)) ?>">
                <div class="paving-tile-card__media image-frame">
                  <?php if (!empty($product['is_out_of_stock'])) : ?>
                  <span class="product-stock-badge product-stock-badge--compact">Нет в наличии</span>
                  <?php endif; ?>
                  <img
                    src="<?= catalog_esc($product['image']) ?>"
                    alt="<?= catalog_esc($product['title']) ?>"
                    loading="lazy"
                  >
                </div>
                <div class="paving-tile-card__body">
                  <h2 class="paving-tile-card__title"><?= catalog_esc($product['title']) ?></h2>
                  <dl class="paving-tile-card__specs">
                    <div class="paving-tile-card__row">
                      <dt>Габариты:</dt>
                      <dd><?= catalog_esc($product['gabarity'] ?? '') ?></dd>
                    </div>
                    <div class="paving-tile-card__row">
                      <dt>Вес(кг/m2):</dt>
                      <dd><?= catalog_esc($product['weight'] ?? '') ?></dd>
                    </div>
                  </dl>
                  <span class="paving-tile-card__cta">
                    <span>Подробнее</span>
                    <span class="paving-tile-card__cta-icon" aria-hidden="true"></span>
                  </span>
                </div>
              </a>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        <header class="category-intro">
          <h1 class="category-title"><?= catalog_esc($category['title']) ?></h1>
          <p class="category-lead"><?= catalog_esc($category['description']) ?></p>
        </header>

        <div class="products-grid category-products-grid">
          <?php foreach ($category['products'] as $product) : ?>
            <a class="product-card product-card-link" href="<?= catalog_esc(catalog_product_link($product)) ?>">
              <div class="product-media image-frame">
                <?php if (!empty($product['is_out_of_stock'])) : ?>
                <span class="product-stock-badge product-stock-badge--compact">Нет в наличии</span>
                <?php endif; ?>
                <img src="<?= catalog_esc($product['image']) ?>" alt="<?= catalog_esc($product['title']) ?>" loading="lazy">
              </div>
              <div class="product-content">
                <h3><?= catalog_esc($product['title']) ?></h3>
                <p class="product-meta"><?= catalog_esc($product['meta']) ?></p>
                <div class="product-bottom">
                  <span class="product-price"><?= catalog_esc($product['price']) ?></span>
                  <span class="product-action" aria-hidden="true">
                    <img class="product-action__icon" src="assets/images/arrow.svg" width="18" height="18" alt="" decoding="async">
                  </span>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
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
              <span class="footer-contact-primary">+375 (29) 325-82-59</span>
              <span class="footer-contact-meta">Пн-Вс: 09:00 — 20:00</span>
            </span>
          </a>

          <a class="footer-contact" href="mailto:mramorbeton@gmail.com">
            <span class="footer-contact-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 7.5 12 13l8-5.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.7"/>
              </svg>
            </span>
            <span class="footer-contact-body">
              <span class="footer-contact-primary">mramorbeton@gmail.com</span>
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
              <span class="footer-contact-primary footer-contact-primary--wide">Минская обл., Минский р-н, Хатежинский с/с, д. Васьковщина</span>
            </span>
          </div>

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

  <script src="script.js"></script>
</body>
</html>
