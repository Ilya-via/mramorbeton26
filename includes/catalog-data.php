<?php
declare(strict_types=1);

require_once __DIR__ . '/app.php';

function catalog_esc(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function catalog_get_legacy_categories(): array
{
    return [
        'trotuarnaya-plitka' => [
            'title' => 'Тротуарная плитка',
            'description' => 'Надёжные бетонные изделия для благоустройства и строительства',
            'image' => 'assets/images/catalog-1.png',
            'layout' => 'paving',
            'kicker' => 'Каталог продукции 2026',
            'heading' => 'Производство и продажа тротуарной плитки в Минске',
            'lead' => 'Наша компания специализируется на производстве и продаже тротуарной плитки из высокопрочного бетона. Мы предлагаем широкий ассортимент форм, цветов и размеров тротуарной плитки, которая отличается высоким качеством и долговечностью.',
            'breadcrumbs' => 'short',
            'products' => [
                [
                    'slug' => 'kaliforniya-kamen',
                    'title' => 'Калифорния камень',
                    'subtitle' => "Высокопрочный бетон по технологии вибролитья\nс улучшенными показателями морозостойкости\nи истираемости",
                    'description' => 'Калифорния камень: тротуарная плитка 300×300×40 мм, вес 90 кг/м². Производство MRAMORBETON.',
                    'gabarity' => '300×300×40',
                    'weight' => '90 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-1.png',
                ],
                [
                    'slug' => 'trotuarnaya-gladkaya',
                    'title' => 'Гладкая',
                    'description' => 'Гладкая тротуарная плитка 300×300×40 мм, вес 90 кг/м². MRAMORBETON.',
                    'gabarity' => '300×300×40',
                    'weight' => '90 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-2.png',
                ],
                [
                    'slug' => 'shagren-2',
                    'title' => 'Шагрень 2',
                    'description' => 'Тротуарная плитка «Шагрень 2» 300×300×40 мм, вес 90 кг/м².',
                    'gabarity' => '300×300×40',
                    'weight' => '90 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-3.png',
                ],
                [
                    'slug' => 'staryy-arbat',
                    'title' => 'Старый Арбат',
                    'description' => 'Тротуарная плитка «Старый Арбат»: 195×140×40 и 150×140×40 мм, вес 90 кг/м².',
                    'gabarity' => '195×140×40; 150×140×40',
                    'weight' => '90 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-4.png',
                ],
                [
                    'slug' => 'bulyzhnaya-mostovaya',
                    'title' => 'Булыжная Мостовая',
                    'description' => 'Плитка «Булыжная Мостовая» 150×300×40 мм, вес 80 кг/м².',
                    'gabarity' => '150 × 300 × 40',
                    'weight' => '80 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-5.png',
                ],
                [
                    'slug' => 'kamennaya-roza',
                    'title' => 'Каменная роза',
                    'description' => 'Каменная роза: 6 фактур, толщина 40 мм, вес 90 кг/м².',
                    'gabarity' => '6 разных фактур, толщина 40 мм',
                    'weight' => '90 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-6.png',
                ],
                [
                    'slug' => 'domino-plitka',
                    'title' => 'Домино',
                    'description' => 'Тротуарная плитка «Домино» 300×150×40 мм, вес 80 кг/м².',
                    'gabarity' => '300×150×40',
                    'weight' => '80 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-7.png',
                ],
                [
                    'slug' => 'polskaya-2',
                    'title' => 'Польская 2',
                    'description' => 'Тротуарная плитка «Польская 2» 250×250×35 мм, вес 80 кг/м².',
                    'gabarity' => '250×250×35',
                    'weight' => '80 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-8.png',
                ],
                [
                    'slug' => 'staryy-kirpich',
                    'title' => 'Старый кирпич',
                    'description' => 'Тротуарная плитка «Старый кирпич» 250×125×60 мм, вес 90 кг/м².',
                    'gabarity' => '250×125×60',
                    'weight' => '90 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-9.png',
                ],
                [
                    'slug' => 'monarh-plitka',
                    'title' => 'Монарх',
                    'description' => 'Тротуарная плитка «Монарх» 435×220×30 мм, вес 80 кг/м².',
                    'gabarity' => '435×220×30',
                    'weight' => '80 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-10.png',
                ],
                [
                    'slug' => 'doska-plitka',
                    'title' => 'Доска',
                    'description' => 'Тротуарная плитка «Доска»: несколько форматов, вес 80 кг/м².',
                    'gabarity' => '195×195×40; 195×590×40; 195×790×40',
                    'weight' => '80 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-11.png',
                ],
                [
                    'slug' => 'afinskaya-mostovaya',
                    'title' => 'Афинская мостовая',
                    'description' => 'Тротуарная плитка «Афинская мостовая»: широкий ряд размеров, вес 90 кг/м².',
                    'gabarity' => '300×300×45, 300×200×45, 300×150×45, 200×200×45, 200×100×45, 150×150×45, 150×115×45, 150×90×45, 155×75×45, 130×105×45',
                    'weight' => '90 кг/м²',
                    'image' => 'assets/paving-tiles/paving-tiles-12.png',
                ],
            ],
        ],
        'fasadnye-paneli' => [
            'title' => 'Фасадные панели',
            'description' => 'Декоративная отделка фасадов и цоколей',
            'image' => 'assets/images/catalog-2.png',
            'products' => [
                [
                    'slug' => 'fasad-kaliforniya',
                    'title' => 'Фасадная плитка «Калифорния»',
                    'description' => 'Фасадная плитка «Калифорния» 300×300×40 мм для отделки фасадов и цоколей.',
                    'meta' => 'Размеры(мм): 300x300x40',
                    'price' => 'от 32.00 руб./m2',
                    'gabarity' => '300×300×40',
                    'weight' => '90 кг/м²',
                    'image' => 'assets/images/product-california-stone.png',
                ],
                [
                    'slug' => 'fasad-tsvetok-elit',
                    'title' => 'Фасадный камень «Цветок элит»',
                    'description' => 'Фасадный камень «Цветок элит» — декоративная фактура, размеры по каталогу.',
                    'meta' => 'Размеры(мм): по каталогу',
                    'price' => 'от 30.00 руб./m2',
                    'gabarity' => 'по каталогу',
                    'weight' => '90 кг/м²',
                    'image' => 'assets/images/product-flower-elite.png',
                ],
            ],
        ],
        'nakladnye-prostupi' => [
            'title' => 'Накладные проступи',
            'description' => 'Бетонные элементы для облицовки лестниц',
            'image' => 'assets/images/catalog-3.png',
            'layout' => 'overhead_steps',
            'kicker' => 'Каталог продукции 2026',
            'heading' => "Производство и реализация\nНакладных бетонных проступей в Минске",
            'heading_br' => true,
            'lead' => 'Наша компания специализируется на производстве и реализации накладных бетонных проступей из высокопрочного бетона. Мы предлагаем широкий ассортимент форм, цветов и размеров накладных бетонных проступей, которые отличаются высоким качеством и долговечностью.',
            'breadcrumbs' => 'short',
            'products' => [
                [
                    'slug' => 'stupen-shagren-4-prorezi',
                    'title' => 'Ступень шагрень армированная 4 прорези',
                    'description' => 'Ступень шагрень армированная 4 прорези: размеры, вес, толщина, стоимость по цветам. Заказ у MRAMORBETON.',
                    'meta' => 'Размеры и вес — в карточке товара',
                    'price' => 'по запросу',
                    'image' => 'assets/overhead-steps/overhead-steps-1.png',
                    'page' => 'detailed',
                    'show_thickness' => true,
                    'spec_size' => '1210×320×40, 1350×320×35, 1500×350×50, 1500×300×60, 1550×350×50',
                    'spec_weight' => '—',
                    'gabarity' => '1210×320×40, 1350×320×35, 1500×350×50, 1500×300×60, 1550×350×50',
                    'weight' => '—',
                    'gallery' => [
                        [
                            'full' => 'assets/images/product-step-shagreen-main.png',
                            'thumb' => 'assets/images/product-step-shagreen-thumb-1.png',
                            'alt' => 'Ступень шагрень армированная 4 прорези, вид 1',
                        ],
                        [
                            'full' => 'assets/images/product-step-shagreen-2.png',
                            'thumb' => 'assets/images/product-step-shagreen-thumb-2.png',
                            'alt' => 'Ступень шагрень армированная 4 прорези, вид 2',
                        ],
                        [
                            'full' => 'assets/images/product-step-shagreen-3.png',
                            'thumb' => 'assets/images/product-step-shagreen-thumb-3.png',
                            'alt' => 'Ступень шагрень армированная 4 прорези, вид 3',
                        ],
                    ],
                    'color_prices' => [
                        ['label' => 'Серый', 'amount' => '30', 'unit' => 'руб/м²'],
                        ['label' => 'Цветной', 'amount' => '36', 'unit' => 'руб/м²'],
                        ['label' => 'Мраморный', 'amount' => '41', 'unit' => 'руб/м²'],
                    ],
                    'thickness_options' => [
                        [
                            'key' => 'standard',
                            'label' => 'Стандарт',
                            'value' => '30, 35, 40 мм',
                            'spec_size' => '1210×320×40, 1350×320×35',
                            'spec_weight' => '—',
                            'prices' => [
                                ['label' => 'Серый', 'amount' => '30', 'unit' => 'руб/м²'],
                                ['label' => 'Цветной', 'amount' => '36', 'unit' => 'руб/м²'],
                                ['label' => 'Мраморный', 'amount' => '41', 'unit' => 'руб/м²'],
                            ],
                        ],
                        [
                            'key' => 'reinforced',
                            'label' => 'Усиленная',
                            'value' => '45, 50, 60 мм',
                            'spec_size' => '1500×350×50, 1500×300×60, 1550×350×50',
                            'spec_weight' => '—',
                            'prices' => [
                                ['label' => 'Серый', 'amount' => '30', 'unit' => 'руб/м²'],
                                ['label' => 'Цветной', 'amount' => '36', 'unit' => 'руб/м²'],
                                ['label' => 'Мраморный', 'amount' => '41', 'unit' => 'руб/м²'],
                            ],
                        ],
                    ],
                ],
                [
                    'slug' => 'stupen-gladkaya-4-prorezi',
                    'title' => 'Ступень гладкая армированная 4 прорези',
                    'description' => 'Ступень гладкая армированная 4 прорези — накладная проступь MRAMORBETON.',
                    'gabarity' => '1150×260×50, 1210×320×35',
                    'weight' => '—',
                    'image' => 'assets/overhead-steps/overhead-steps-2.png',
                ],
                [
                    'slug' => 'stupen-gladkaya-3-prorezi',
                    'title' => 'Ступень гладкая армированная 3 прорези',
                    'description' => 'Ступень гладкая армированная 3 прорези — широкий ряд типоразмеров.',
                    'gabarity' => '1150×335×50, 1200×400×45, 1250×370×30, 1300×325×30, 1335×330×40, 1350×320×40, 1350×340×40, 1360×350×40, 1395×350×50, 1400×325×30, 1407×350×50, 1500×325×30, 1550×300×50, 1620×400×45',
                    'weight' => '—',
                    'image' => 'assets/overhead-steps/overhead-steps-3.png',
                ],
                [
                    'slug' => 'stupen-gladkaya-armirovannaya',
                    'title' => 'Ступень гладкая армированная',
                    'description' => 'Ступень гладкая армированная для лестниц и крыльца.',
                    'gabarity' => '1200×300×30, 1285×350×45, 1350×320×42, 1380×470×40, 1385×330×38',
                    'weight' => '—',
                    'image' => 'assets/overhead-steps/overhead-steps-4.png',
                ],
                [
                    'slug' => 'stupen-anti-slip-odna-polosa',
                    'title' => 'Ступень с анти скользящей полосой армированная',
                    'description' => 'Ступень с антискользящей полосой, армированная.',
                    'gabarity' => '1800×400×40',
                    'weight' => '—',
                    'image' => 'assets/overhead-steps/overhead-steps-5.png',
                ],
                [
                    'slug' => 'stupen-3-shirokie-anti-slip',
                    'title' => 'Ступень 3 широкие анти скользящие полосы армированная',
                    'description' => 'Ступень с тремя широкими антискользящими полосами.',
                    'gabarity' => '1195×335×90',
                    'weight' => '—',
                    'image' => 'assets/overhead-steps/overhead-steps-6.png',
                ],
                [
                    'slug' => 'stupen-shagren-3-prorezi',
                    'title' => 'Ступень шагрень с 3 прорезями',
                    'description' => 'Ступень шагрень с тремя прорезями.',
                    'gabarity' => '700×345×35',
                    'weight' => '—',
                    'image' => 'assets/overhead-steps/overhead-steps-7.png',
                ],
                [
                    'slug' => 'podstupenok-armirovannyi',
                    'title' => 'Подступенок армированный',
                    'description' => 'Подступенок армированный — типоразмеры по каталогу.',
                    'gabarity' => '700×115×25, 1150×120×25, 1150×170×25, 1200×120×25, 1200×130×25, 1200×140×25, 1200×150×25, 1200×160×25, 1200×180×25, 1210×107×25, 1210×110×20, 1210×160×25, 1300×130×25, 1345×110×25, 1350×110×30, 1350×115×25, 1350×150×35, 1360×110×40, 1360×130×25, 1500×150×25, 1550×117×25, 1550×150×25, 1570×150×25, 1625×150×25, 1625×120×25, 1800×150×30',
                    'weight' => '—',
                    'image' => 'assets/overhead-steps/overhead-steps-8.png',
                ],
            ],
        ],
        'bordyury-i-vodostoki' => [
            'title' => 'Бордюры и водостоки',
            'description' => 'Организация границ и отвода воды',
            'image' => 'assets/images/catalog-4.png',
            'products' => [
                [
                    'slug' => 'bordyur-1000-220',
                    'title' => 'Бордюр тротуарный 1000x220',
                    'description' => 'Бордюр тротуарный 1000×220×75 мм. Цена от 10 руб./шт.',
                    'meta' => 'Размеры(мм): 1000x220x75',
                    'price' => 'от 10 руб./шт',
                    'gabarity' => '1000×220×75',
                    'weight' => '—',
                    'image' => 'assets/images/product-curb.png',
                    'color_prices' => [
                        ['label' => 'Серый', 'amount' => 'от 10', 'unit' => 'руб/шт'],
                        ['label' => 'Цветной', 'amount' => 'уточняйте', 'unit' => ''],
                    ],
                ],
            ],
        ],
        'ritualnye-plity' => [
            'title' => 'Армированные ритуальные плиты',
            'description' => 'Плиты и элементы для благоустройства мемориальных зон',
            'image' => 'assets/images/catalog-5.png',
            'products' => [
                [
                    'title' => 'Ритуальная плита стандарт',
                    'meta' => 'Размеры(мм): по запросу',
                    'price' => 'по запросу',
                    'image' => 'assets/images/catalog-5.png',
                    'url' => 'index.html#contact-form',
                ],
                [
                    'title' => 'Ритуальная плита усиленная',
                    'meta' => 'Армирование, морозостойкость',
                    'price' => 'по запросу',
                    'image' => 'assets/images/catalog-5.png',
                    'url' => 'index.html#contact-form',
                ],
            ],
        ],
        'parapetnye-plity' => [
            'title' => 'Армированные парапетные плиты',
            'description' => 'Защитные бетонные крышки для заборов и ограждений',
            'image' => 'assets/images/catalog-6.png',
            'products' => [
                [
                    'title' => 'Парапетная плита гладкая',
                    'meta' => 'Размеры(мм): по запросу',
                    'price' => 'по запросу',
                    'image' => 'assets/images/catalog-6.png',
                    'url' => 'index.html#contact-form',
                ],
            ],
        ],
        'poshagovye-plity' => [
            'title' => 'Армированные пошаговые плиты',
            'description' => 'Плиты для декоративных садовых дорожек',
            'image' => 'assets/images/catalog-7.png',
            'products' => [
                [
                    'slug' => 'poshagovaya-gladkaya',
                    'title' => 'Пошаговая плита гладкая',
                    'description' => 'Пошаговая плита гладкая 800×400×50 мм. От 35 руб./шт.',
                    'meta' => 'Размеры(мм): 800x400x50',
                    'price' => 'от 35 руб./шт',
                    'gabarity' => '800×400×50',
                    'weight' => '—',
                    'image' => 'assets/images/product-step-slab.png',
                    'color_prices' => [
                        ['label' => 'Стандарт', 'amount' => 'от 35', 'unit' => 'руб/шт'],
                        ['label' => 'Другие форматы', 'amount' => 'по запросу', 'unit' => ''],
                    ],
                ],
            ],
        ],
    ];
}

function catalog_derive_price_from_rows(array $rows): string
{
    foreach ($rows as $row) {
        $amount = trim((string) ($row['amount'] ?? ''));
        $unit = trim((string) ($row['unit'] ?? ''));
        if ($amount === '' && $unit === '') {
            continue;
        }

        if ($amount !== '' && preg_match('/^(от|по|уточня)/ui', $amount)) {
            return trim($amount . ' ' . $unit);
        }

        if ($amount === '') {
            return $unit;
        }

        return trim('от ' . $amount . ' ' . $unit);
    }

    return '';
}

function catalog_finalize_product(array $product): array
{
    $thicknessOptions = array_values($product['thickness_options'] ?? []);
    $priceRows = $product['color_prices'] ?? [];

    if (!empty($product['show_thickness']) && $thicknessOptions !== []) {
        $firstOption = $thicknessOptions[0];
        if (!empty($firstOption['prices']) && is_array($firstOption['prices'])) {
            $priceRows = $firstOption['prices'];
        }
        if (!empty($firstOption['spec_size'])) {
            $product['spec_size'] = $firstOption['spec_size'];
        }
        if (!empty($firstOption['spec_weight'])) {
            $product['spec_weight'] = $firstOption['spec_weight'];
        }
    }

    $meta = trim((string) ($product['meta'] ?? ''));
    if ($meta === '') {
        $size = trim((string) ($product['spec_size'] ?? $product['gabarity'] ?? ''));
        $meta = $size !== '' ? 'Размеры(мм): ' . $size : '';
    }

    $price = trim((string) ($product['price'] ?? ''));
    if ($price === '') {
        $price = catalog_derive_price_from_rows($priceRows);
    }

    $product['meta'] = $meta;
    $product['price'] = $price;
    $product['is_out_of_stock'] = !empty($product['is_out_of_stock']);

    return $product;
}

function catalog_db_has_catalog_data(): bool
{
    static $hasData = null;

    if ($hasData !== null) {
        return $hasData;
    }

    if (!app_db_ready()) {
        $hasData = false;
        return false;
    }

    try {
        $pdo = app_pdo();
        $hasData = $pdo instanceof PDO && (int) $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn() > 0;
    } catch (Throwable $e) {
        $hasData = false;
    }

    return $hasData;
}

function catalog_build_db_categories(): array
{
    if (!catalog_db_has_catalog_data()) {
        return [];
    }

    $pdo = app_pdo();
    if (!$pdo instanceof PDO) {
        return [];
    }

    try {
        $categoryStmt = $pdo->query(
            'SELECT id, slug, title, description, image_path, layout, kicker, heading, lead, breadcrumbs_type, sort_order
             FROM categories
             WHERE is_active = 1
             ORDER BY sort_order ASC, id ASC'
        );
        $productStmt = $pdo->query(
            'SELECT id, category_id, slug, title, subtitle_text, description, meta_text, price_text, image_path, is_out_of_stock, external_url, page_type,
                    show_thickness, spec_size, spec_weight, gabarity, weight, is_featured, sort_order
             FROM products
             WHERE is_active = 1
             ORDER BY sort_order ASC, id ASC'
        );
        $galleryStmt = $pdo->query(
            'SELECT product_id, full_path, thumb_path, alt_text, sort_order
             FROM product_images
             ORDER BY sort_order ASC, id ASC'
        );
        $priceStmt = $pdo->query(
            'SELECT product_id, label, amount, unit, sort_order
             FROM product_color_prices
             ORDER BY sort_order ASC, id ASC'
        );
        $thicknessStmt = $pdo->query(
            'SELECT id, product_id, option_key, label, value_text, spec_size, spec_weight, sort_order
             FROM product_thickness_options
             ORDER BY sort_order ASC, id ASC'
        );
        $thicknessPriceStmt = $pdo->query(
            'SELECT thickness_option_id, label, amount, unit, sort_order
             FROM product_thickness_option_prices
             ORDER BY sort_order ASC, id ASC'
        );
    } catch (Throwable $e) {
        return [];
    }

    $categories = [];
    $categoryIdsToSlug = [];

    foreach ($categoryStmt->fetchAll() as $row) {
        $slug = (string) $row['slug'];
        $categoryIdsToSlug[(int) $row['id']] = $slug;
        $categories[$slug] = [
            'id' => (int) $row['id'],
            'slug' => $slug,
            'title' => (string) $row['title'],
            'description' => (string) ($row['description'] ?? ''),
            'image' => (string) ($row['image_path'] ?? ''),
            'layout' => (string) ($row['layout'] ?? ''),
            'kicker' => (string) ($row['kicker'] ?? ''),
            'heading' => (string) ($row['heading'] ?? ''),
            'lead' => (string) ($row['lead'] ?? ''),
            'breadcrumbs' => (string) ($row['breadcrumbs_type'] ?? ''),
            'sort_order' => (int) $row['sort_order'],
            'products' => [],
        ];
    }

    $imagesByProduct = [];
    foreach ($galleryStmt->fetchAll() as $row) {
        $imagesByProduct[(int) $row['product_id']][] = [
            'full' => (string) $row['full_path'],
            'thumb' => (string) ($row['thumb_path'] ?: $row['full_path']),
            'alt' => (string) ($row['alt_text'] ?? ''),
        ];
    }

    $pricesByProduct = [];
    foreach ($priceStmt->fetchAll() as $row) {
        $pricesByProduct[(int) $row['product_id']][] = [
            'label' => (string) ($row['label'] ?? ''),
            'amount' => (string) ($row['amount'] ?? ''),
            'unit' => (string) ($row['unit'] ?? ''),
        ];
    }

    $thicknessPricesByOption = [];
    foreach ($thicknessPriceStmt->fetchAll() as $row) {
        $thicknessPricesByOption[(int) $row['thickness_option_id']][] = [
            'label' => (string) ($row['label'] ?? ''),
            'amount' => (string) ($row['amount'] ?? ''),
            'unit' => (string) ($row['unit'] ?? ''),
        ];
    }

    $thicknessOptionsByProduct = [];
    foreach ($thicknessStmt->fetchAll() as $row) {
        $optionId = (int) $row['id'];
        $thicknessOptionsByProduct[(int) $row['product_id']][] = [
            'id' => $optionId,
            'key' => (string) ($row['option_key'] ?? ''),
            'label' => (string) ($row['label'] ?? ''),
            'value' => (string) ($row['value_text'] ?? ''),
            'spec_size' => (string) ($row['spec_size'] ?? ''),
            'spec_weight' => (string) ($row['spec_weight'] ?? ''),
            'prices' => $thicknessPricesByOption[$optionId] ?? [],
        ];
    }

    foreach ($productStmt->fetchAll() as $row) {
        $categoryId = (int) $row['category_id'];
        $categorySlug = $categoryIdsToSlug[$categoryId] ?? null;
        if ($categorySlug === null || !isset($categories[$categorySlug])) {
            continue;
        }

        $productId = (int) $row['id'];
        $product = [
            'id' => $productId,
            'slug' => (string) ($row['slug'] ?? ''),
            'title' => (string) $row['title'],
            'subtitle' => (string) ($row['subtitle_text'] ?? ''),
            'description' => (string) ($row['description'] ?? ''),
            'meta' => (string) ($row['meta_text'] ?? ''),
            'price' => (string) ($row['price_text'] ?? ''),
            'image' => (string) ($row['image_path'] ?? ''),
            'is_out_of_stock' => (bool) ($row['is_out_of_stock'] ?? false),
            'url' => (string) ($row['external_url'] ?? ''),
            'page' => (string) ($row['page_type'] ?? ''),
            'show_thickness' => (bool) $row['show_thickness'],
            'spec_size' => (string) ($row['spec_size'] ?? ''),
            'spec_weight' => (string) ($row['spec_weight'] ?? ''),
            'gabarity' => (string) ($row['gabarity'] ?? ''),
            'weight' => (string) ($row['weight'] ?? ''),
            'is_featured' => (bool) $row['is_featured'],
            'gallery' => $imagesByProduct[$productId] ?? [],
            'color_prices' => $pricesByProduct[$productId] ?? [],
            'thickness_options' => $thicknessOptionsByProduct[$productId] ?? [],
        ];

        $categories[$categorySlug]['products'][] = catalog_finalize_product($product);
    }

    return $categories;
}

function catalog_get_categories(): array
{
    static $categories = null;

    if ($categories !== null) {
        return $categories;
    }

    $categories = catalog_build_db_categories();
    if ($categories !== []) {
        return $categories;
    }

    $categories = catalog_get_legacy_categories();

    return $categories;
}

function catalog_get_home_categories(int $limit = 7): array
{
    $rows = [];
    foreach (catalog_get_categories() as $slug => $category) {
        $rows[] = [
            'slug' => $slug,
            'title' => $category['title'],
            'description' => $category['description'],
            'image' => $category['image'],
        ];
    }

    return array_slice($rows, 0, $limit);
}

function catalog_get_featured_products(int $limit = 4): array
{
    if (catalog_db_has_catalog_data()) {
        $out = [];
        foreach (catalog_get_categories() as $categorySlug => $category) {
            foreach ($category['products'] as $product) {
                if (!empty($product['is_featured'])) {
                    $product['category_slug'] = $categorySlug;
                    $out[] = $product;
                }
            }
        }

        if ($out !== []) {
            return array_slice($out, 0, $limit);
        }
    }

    $fallbackSlugs = [
        'fasad-tsvetok-elit',
        'kaliforniya-kamen',
        'bordyur-1000-220',
        'poshagovaya-gladkaya',
    ];
    $featured = [];

    foreach ($fallbackSlugs as $slug) {
        $found = catalog_find_product($slug);
        if ($found === null) {
            continue;
        }
        $product = $found['product'];
        $product['category_slug'] = $found['category_slug'];
        $featured[] = catalog_finalize_product($product);
    }

    return array_slice($featured, 0, $limit);
}

function catalog_find_category(string $slug): ?array
{
    if ($slug === '' || !preg_match('/^[a-z0-9-]+$/', $slug)) {
        return null;
    }
    $all = catalog_get_categories();
    return $all[$slug] ?? null;
}

function catalog_product_link(array $product): string
{
    if (!empty($product['slug'])) {
        return 'product.php?' . http_build_query(['p' => $product['slug']]);
    }

    return $product['url'] ?? '#';
}

function catalog_find_product(string $slug): ?array
{
    if ($slug === '' || !preg_match('/^[a-z0-9-]+$/', $slug)) {
        return null;
    }
    foreach (catalog_get_categories() as $catSlug => $cat) {
        foreach ($cat['products'] as $product) {
            if (($product['slug'] ?? '') === $slug) {
                return [
                    'category_slug' => $catSlug,
                    'category' => $cat,
                    'product' => $product,
                ];
            }
        }
    }

    return null;
}

function catalog_get_related_products(string $categorySlug, string $excludeSlug, int $limit = 3): array
{
    if (catalog_db_has_catalog_data()) {
        $pdo = app_pdo();
        if ($pdo instanceof PDO) {
            $stmt = $pdo->prepare(
                'SELECT rp2.id, rp2.slug, rp2.title, rp2.subtitle_text, rp2.description, rp2.meta_text, rp2.price_text, rp2.image_path,
                        rp2.is_out_of_stock,
                        rp2.external_url, rp2.page_type, rp2.show_thickness, rp2.spec_size, rp2.spec_weight, rp2.gabarity,
                        rp2.weight, rp2.is_featured
                 FROM products source
                 INNER JOIN product_related rel ON rel.product_id = source.id
                 INNER JOIN products rp2 ON rp2.id = rel.related_product_id
                 WHERE source.slug = :slug AND rp2.is_active = 1
                 ORDER BY rel.sort_order ASC, rel.id ASC
                 LIMIT ' . (int) $limit
            );
            $stmt->execute(['slug' => $excludeSlug]);
            $manual = [];
            foreach ($stmt->fetchAll() as $row) {
                $manual[] = [
                    'id' => (int) $row['id'],
                    'slug' => (string) ($row['slug'] ?? ''),
                    'title' => (string) $row['title'],
                    'subtitle' => (string) ($row['subtitle_text'] ?? ''),
                    'description' => (string) ($row['description'] ?? ''),
                    'meta' => (string) ($row['meta_text'] ?? ''),
                    'price' => (string) ($row['price_text'] ?? ''),
                    'image' => (string) ($row['image_path'] ?? ''),
                    'is_out_of_stock' => (bool) ($row['is_out_of_stock'] ?? false),
                    'url' => (string) ($row['external_url'] ?? ''),
                    'page' => (string) ($row['page_type'] ?? ''),
                    'show_thickness' => (bool) $row['show_thickness'],
                    'spec_size' => (string) ($row['spec_size'] ?? ''),
                    'spec_weight' => (string) ($row['spec_weight'] ?? ''),
                    'gabarity' => (string) ($row['gabarity'] ?? ''),
                    'weight' => (string) ($row['weight'] ?? ''),
                    'is_featured' => (bool) $row['is_featured'],
                ];
            }

            if ($manual !== []) {
                return array_map('catalog_finalize_product', $manual);
            }
        }
    }

    $cat = catalog_get_categories()[$categorySlug] ?? null;
    if ($cat === null) {
        return [];
    }
    $out = [];
    foreach ($cat['products'] as $p) {
        $ps = $p['slug'] ?? '';
        if ($ps === '' || $ps === $excludeSlug) {
            continue;
        }
        $out[] = $p;
        if (count($out) >= $limit) {
            break;
        }
    }

    return $out;
}

function catalog_get_related_products_fallback(string $categorySlug, string $excludeSlug, int $limit = 3): array
{
    $related = catalog_get_related_products($categorySlug, $excludeSlug, $limit);
    if (count($related) >= $limit) {
        return $related;
    }
    foreach (catalog_get_categories() as $slug => $cat) {
        foreach ($cat['products'] as $p) {
            $ps = $p['slug'] ?? '';
            if ($ps === '' || $ps === $excludeSlug) {
                continue;
            }
            foreach ($related as $existing) {
                if (($existing['slug'] ?? '') === $ps) {
                    continue 2;
                }
            }
            $related[] = $p;
            if (count($related) >= $limit) {
                return $related;
            }
        }
    }

    return $related;
}
