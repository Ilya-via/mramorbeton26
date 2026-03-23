<?php
declare(strict_types=1);

function catalog_esc(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function catalog_get_categories(): array
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
