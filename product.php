<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/catalog-data.php';

$slug = isset($_GET['p']) ? (string) $_GET['p'] : '';
$found = catalog_find_product($slug);

if ($found === null) {
    http_response_code(404);
    $notFound = true;
    $pageTitle = 'Товар не найден — MRAMORBETON';
} else {
    $notFound = false;
    $categorySlug = $found['category_slug'];
    $category = $found['category'];
    $product = $found['product'];
    $pageTitle = $product['title'] . ' — MRAMORBETON';

    $gallery = $product['gallery'] ?? [];
    $singleGallery = count($gallery) === 0;
    $mainImage = !empty($product['image'])
        ? [
            'full' => $product['image'],
            'thumb' => $product['image'],
            'alt' => $product['title'],
        ]
        : ($gallery[0] ?? null);

    $specSize = $product['spec_size'] ?? $product['gabarity'] ?? '—';
    $specWeight = $product['spec_weight'] ?? $product['weight'] ?? '—';
    $thicknessOptions = array_values($product['thickness_options'] ?? []);
    $showThickness = !empty($product['show_thickness']) && $thicknessOptions !== [];
    $subtitle = trim((string) ($product['subtitle'] ?? $product['subtitle_text'] ?? ''));
    $isOutOfStock = !empty($product['is_out_of_stock']);

    $colorPrices = $product['color_prices'] ?? [
        ['label' => 'Серый', 'amount' => '30', 'unit' => 'руб/м²'],
        ['label' => 'Цветной', 'amount' => '36', 'unit' => 'руб/м²'],
        ['label' => 'Мраморный', 'amount' => '41', 'unit' => 'руб/м²'],
    ];

    if ($showThickness) {
        $selectedThickness = $thicknessOptions[0];
        $specSize = $selectedThickness['spec_size'] ?: $specSize;
        $specWeight = $selectedThickness['spec_weight'] ?: $specWeight;
        $colorPrices = $selectedThickness['prices'] ?: $colorPrices;
    }

    $metaDescription = $product['description'] ?? ($product['title'] . ' — MRAMORBETON.');
    $related = catalog_get_related_products_fallback($categorySlug, $product['slug'], 3);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= catalog_esc($pageTitle) ?></title>
  <?php if (!$notFound) : ?>
  <meta name="description" content="<?= catalog_esc($metaDescription) ?>">
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Spectral+SC:wght@700&display=swap"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="product.css">
  <script>document.documentElement.classList.add('js');</script>
  <link rel="shortcut icon" href="assets/images/logo.svg" type="image/x-icon">
</head>
<body class="page-product<?= !$notFound ? ' page-product--loading' : '' ?>">
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

  <main class="product-main">
    <div class="container">
      <?php if ($notFound) : ?>
        <nav class="product-breadcrumbs" aria-label="Хлебные крошки">
          <a href="index.html">Главная</a>
          <span aria-hidden="true">/</span>
          <span class="product-breadcrumbs-current">Не найдено</span>
        </nav>
        <div class="category-not-found" style="padding: 48px 0 80px;">
          <h1 class="category-title">Товар не найден</h1>
          <p class="category-lead">Перейдите в каталог или выберите другую позицию.</p>
          <a class="button button-dark" href="catalog.php">В каталог</a>
        </div>
      <?php else : ?>
      <nav class="product-breadcrumbs" aria-label="Хлебные крошки">
        <a href="index.html">Главная</a>
        <span aria-hidden="true">/</span>
        <a class="product-breadcrumbs-catalog" href="catalog.php">Каталог</a>
        <span class="product-breadcrumbs-catalog-separator" aria-hidden="true">/</span>
        <a class="product-breadcrumbs-category" href="category.php?c=<?= catalog_esc($categorySlug) ?>"><?= catalog_esc($category['title']) ?></a>
        <span aria-hidden="true">/</span>
        <span class="product-breadcrumbs-current"><?= catalog_esc($product['title']) ?></span>
      </nav>

      <div class="product-loading-shell" aria-hidden="true">
        <div class="product-loading-layout">
          <div class="product-loading-gallery">
            <div class="product-loading-box product-loading-box--main"></div>
            <div class="product-loading-thumbs">
              <div class="product-loading-box product-loading-box--thumb"></div>
              <div class="product-loading-box product-loading-box--thumb"></div>
              <div class="product-loading-box product-loading-box--thumb"></div>
            </div>
          </div>

          <div class="product-loading-info">
            <span class="product-loading-line product-loading-line--title"></span>
            <span class="product-loading-line product-loading-line--subtitle"></span>
            <span class="product-loading-line product-loading-line--subtitle-short"></span>

            <div class="product-loading-specs">
              <div class="product-loading-spec-card">
                <span class="product-loading-chip"></span>
                <span class="product-loading-line product-loading-line--card-left"></span>
                <span class="product-loading-line product-loading-line--card-right"></span>
              </div>
              <div class="product-loading-spec-card">
                <span class="product-loading-chip"></span>
                <span class="product-loading-line product-loading-line--card-left"></span>
                <span class="product-loading-line product-loading-line--card-right"></span>
              </div>
            </div>

            <div class="product-loading-prices">
              <div class="product-loading-price-row">
                <span class="product-loading-line product-loading-line--price-left"></span>
                <span class="product-loading-line product-loading-line--price-center"></span>
                <span class="product-loading-line product-loading-line--price-right"></span>
              </div>
              <div class="product-loading-price-row">
                <span class="product-loading-line product-loading-line--price-left short"></span>
                <span class="product-loading-line product-loading-line--price-center"></span>
                <span class="product-loading-line product-loading-line--price-right short"></span>
              </div>
              <div class="product-loading-price-row">
                <span class="product-loading-line product-loading-line--price-left"></span>
                <span class="product-loading-line product-loading-line--price-center"></span>
                <span class="product-loading-line product-loading-line--price-right"></span>
              </div>
              <div class="product-loading-price-row">
                <span class="product-loading-line product-loading-line--price-left short"></span>
                <span class="product-loading-line product-loading-line--price-center"></span>
                <span class="product-loading-line product-loading-line--price-right short"></span>
              </div>
            </div>

            <span class="product-loading-button"></span>
          </div>
        </div>

        <section class="product-loading-related" aria-hidden="true">
          <h2 class="product-related__title">Возможно вас заинтересует</h2>
          <div class="product-loading-related__grid">
            <?php for ($i = 0; $i < 3; $i++) : ?>
            <article class="product-loading-card">
              <div class="product-loading-box product-loading-box--card"></div>
              <div class="product-loading-card__body">
                <span class="product-loading-line product-loading-line--card-title"></span>
                <span class="product-loading-line product-loading-line--card-text"></span>
                <span class="product-loading-line product-loading-line--card-text short"></span>
                <span class="product-loading-card__button"></span>
              </div>
            </article>
            <?php endfor; ?>
          </div>
        </section>
      </div>

      <div class="product-page-content">
      <header class="product-mobile-intro">
        <h1 class="product-mobile-intro__title"><?= catalog_esc($product['title']) ?></h1>
        <?php if ($subtitle !== '') : ?>
        <p class="product-mobile-intro__subtitle"><?= catalog_esc($subtitle) ?></p>
        <?php endif; ?>
      </header>
      <div class="product-layout">
        <div class="product-gallery<?= $singleGallery ? ' product-gallery--single' : '' ?>">
          <button
            type="button"
            class="product-gallery__main"
            aria-label="Открыть фото в полноэкранном просмотре"
          >
            <img
              id="product-main-img"
              src="<?= catalog_esc($mainImage['full'] ?? '') ?>"
              alt="<?= catalog_esc($mainImage['alt'] ?? $product['title']) ?>"
              width="634"
              height="396"
              loading="eager"
            >
            <?php if ($isOutOfStock) : ?>
            <div class="product-stock-banner" aria-label="Нет в наличии">
              <div class="product-stock-banner__icon" aria-hidden="true">
                <span></span>
              </div>
              <div class="product-stock-banner__text">
                <strong>Нет в наличии</strong>
                <span>Товар временно отсутствует на складе</span>
              </div>
            </div>
            <?php endif; ?>
          </button>
          <div class="product-gallery__thumbs" role="group" aria-label="Дополнительные фото">
            <?php foreach ($gallery as $i => $slide) : ?>
              <button
                type="button"
                class="product-thumb<?= $i === 0 ? ' is-active' : '' ?>"
                data-full-src="<?= catalog_esc($slide['full']) ?>"
                data-alt="<?= catalog_esc($slide['alt'] ?? $product['title']) ?>"
                aria-label="Показать фото <?= (int) ($i + 1) ?>"
                aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>"
              >
                <img src="<?= catalog_esc($slide['thumb']) ?>" alt="" loading="lazy">
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="product-info">
          <h1 class="product-title"><?= catalog_esc($product['title']) ?></h1>
          <?php if ($subtitle !== '') : ?>
          <p class="product-subtitle"><?= catalog_esc($subtitle) ?></p>
          <?php endif; ?>

          <div class="product-specs-wrap">
            <div class="product-spec-row">
              <div class="product-spec-left">
                <div class="product-spec-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none">
                    <path d="M4 8.5h16v7H4z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                    <path d="M7 11v2M10 10.5v3M13 11v2M16 10.5v3M19 11v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                  </svg>
                </div>
                <p class="product-spec-label">Размер (мм)</p>
              </div>
              <p id="product-spec-size-value" class="product-spec-value"><?= catalog_format_dimension_stack_html((string) $specSize) ?></p>
            </div>
            <div class="product-spec-row">
              <div class="product-spec-left">
                <div class="product-spec-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none">
                    <path d="M6 8V6a6 6 0 0 1 12 0v2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                    <rect x="4" y="8" width="16" height="14" rx="2.5" stroke="currentColor" stroke-width="1.7"/>
                    <path d="M9 12h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                  </svg>
                </div>
                <p class="product-spec-label">Вес (кг/м²)</p>
              </div>
              <p id="product-spec-weight-value" class="product-spec-value"><?= catalog_esc($specWeight) ?></p>
            </div>
          </div>

          <?php if ($showThickness) : ?>
          <div>
            <h2 class="product-block-title" id="thickness-heading">Выберите толщину</h2>
            <div class="product-thickness" role="group" aria-labelledby="thickness-heading">
              <?php foreach ($thicknessOptions as $index => $option) : ?>
              <button
                type="button"
                class="product-thickness-option<?= $index === 0 ? ' is-selected' : '' ?>"
                data-thickness-option="<?= catalog_esc($option['key'] ?: ('option-' . $index)) ?>"
                data-spec-size="<?= catalog_esc($option['spec_size'] ?? '') ?>"
                data-spec-weight="<?= catalog_esc($option['spec_weight'] ?? '') ?>"
                data-prices="<?= catalog_esc((string) json_encode($option['prices'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>"
                aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>"
              >
                <span class="product-thickness-option__label"><?= catalog_esc($option['label'] ?? '') ?></span>
                <span class="product-thickness-option__value"><?= catalog_esc($option['value'] ?? '') ?></span>
              </button>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <div class="product-prices">
            <h2 class="product-block-title">Стоимость по цветам:</h2>
            <div id="product-price-rows">
            <?php foreach ($colorPrices as $row) : ?>
            <div class="product-price-row">
              <p class="product-price-label"><?= catalog_esc($row['label']) ?></p>
              <span class="product-price-leader" aria-hidden="true"></span>
              <p class="product-price-value"><?= catalog_esc($row['amount']) ?> <span><?= catalog_esc($row['unit']) ?></span></p>
            </div>
            <?php endforeach; ?>
            </div>
          </div>

          <a
            class="product-order-btn"
            href="index.html#contact-form"
            data-lead-popup="product"
            data-lead-title="<?= catalog_esc(trim(($category['title'] ?? '') !== '' ? ($category['title'] . ' «' . $product['title'] . '»') : $product['title'])) ?>"
            data-lead-message="<?= catalog_esc('Интересует товар: ' . $product['title']) ?>"
          >Заказать</a>
        </div>
      </div>

      <?php if ($related !== []) : ?>
      <section class="product-related" aria-labelledby="related-heading">
        <h2 id="related-heading" class="product-related__title">Возможно вас заинтересует</h2>
        <div id="product-related-grid" class="product-related__grid">
          <?php foreach ($related as $rp) : ?>
            <?php
            $rg = $rp['gabarity'] ?? $rp['meta'] ?? '—';
            $rw = $rp['weight'] ?? '—';
            ?>
          <article class="product-card">
            <a class="product-card__media" href="<?= catalog_esc(catalog_product_link($rp)) ?>" aria-label="Открыть товар <?= catalog_esc($rp['title'] ?? '') ?>">
              <?php if (!empty($rp['is_out_of_stock'])) : ?>
              <span class="product-stock-badge product-stock-badge--compact">Нет в наличии</span>
              <?php endif; ?>
              <img
                src="<?= catalog_esc($rp['image'] ?? '') ?>"
                alt=""
                loading="lazy"
                width="378"
                height="240"
              >
            </a>
            <div class="product-card__body">
              <h3 class="product-card__title"><?= catalog_esc($rp['title'] ?? '') ?></h3>
              <dl class="product-card__meta">
                <div class="product-card__row">
                  <dt>Габариты:</dt>
                  <dd><?= catalog_format_dimension_stack_html((string) $rg) ?></dd>
                </div>
                <div class="product-card__row">
                  <dt>Вес(кг/м²):</dt>
                  <dd><?= catalog_esc($rw) ?></dd>
                </div>
              </dl>
            </div>
            <a class="product-card__action" href="<?= catalog_esc(catalog_product_link($rp)) ?>">
              Подробнее
              <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path
                  d="M5 12h14M13 6l6 6-6 6"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </a>
          </article>
          <?php endforeach; ?>
        </div>
        <div class="mobile-slider-controls" aria-label="Навигация по похожим товарам">
          <button class="mobile-slider-control mobile-slider-control--prev" type="button" data-scroll-target="product-related-grid" aria-label="Предыдущий товар"></button>
          <button class="mobile-slider-control mobile-slider-control--next" type="button" data-scroll-target="product-related-grid" aria-label="Следующий товар"></button>
        </div>
      </section>
      <?php endif; ?>
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

  <?php if (!$notFound) : ?>
  <div
    id="product-lightbox"
    class="product-lightbox"
    role="dialog"
    aria-modal="true"
    aria-label="Галерея товара"
    aria-hidden="true"
    hidden
  >
    <div class="product-lightbox__backdrop" data-lightbox-close tabindex="-1"></div>
    <div class="product-lightbox__content">
      <button type="button" class="product-lightbox__close" data-lightbox-close aria-label="Закрыть">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>

      <div class="product-lightbox__stage">
        <button type="button" class="product-lightbox__nav product-lightbox__nav--prev" data-lightbox-prev aria-label="Предыдущее фото">
          <svg viewBox="0 0 24 48" fill="none" aria-hidden="true">
            <path d="M16 8L8 24l8 16" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <div class="product-lightbox__frame">
          <img id="product-lightbox-img" src="" alt="">
        </div>
        <button type="button" class="product-lightbox__nav product-lightbox__nav--next" data-lightbox-next aria-label="Следующее фото">
          <svg viewBox="0 0 24 48" fill="none" aria-hidden="true">
            <path d="M8 8l8 16-8 16" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>

      <div id="product-lightbox-thumbs" class="product-lightbox__thumbs" role="tablist" aria-label="Миниатюры"></div>
    </div>
  </div>
  <?php endif; ?>

  <script src="script.js"></script>
  <?php if (!$notFound) : ?>
  <script src="product.js"></script>
  <?php endif; ?>
</body>
</html>
