<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/catalog-data.php';

header('Content-Type: application/json; charset=UTF-8');

$categories = catalog_get_home_categories(7);
$featuredProducts = catalog_get_featured_products(4);

$payload = [
    'categories' => array_values(array_map(
        static function (array $category): array {
            return [
                'slug' => (string) ($category['slug'] ?? ''),
                'title' => (string) ($category['title'] ?? ''),
                'description' => (string) ($category['description'] ?? ''),
                'image' => (string) ($category['image'] ?? ''),
            ];
        },
        $categories
    )),
    'featuredProducts' => array_values(array_map(
        static function (array $product): array {
            $metaLines = [];
            if (!empty($product['meta'])) {
                $metaLines[] = (string) $product['meta'];
            } elseif (!empty($product['gabarity'])) {
                $metaLines[] = 'Размеры(мм): ' . (string) $product['gabarity'];
            }

            if (!empty($product['weight'])) {
                $metaLines[] = 'Вес(кг/м2): ' . (string) $product['weight'];
            }

            return [
                'slug' => (string) ($product['slug'] ?? ''),
                'title' => (string) ($product['title'] ?? ''),
                'metaLines' => $metaLines,
                'priceText' => (string) ($product['price'] ?? ''),
                'image' => (string) ($product['image'] ?? ''),
            ];
        },
        $featuredProducts
    )),
];

echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
