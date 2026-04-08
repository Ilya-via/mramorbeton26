<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/catalog-data.php';

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Run this script from CLI.\n");
    exit(1);
}

$options = getopt('', ['admin-user::', 'admin-pass::', 'reset']);
$config = app_config()['db'];
$databaseName = $config['database'];

$rootDsn = sprintf(
    'mysql:host=%s;port=%s;charset=%s',
    $config['host'],
    $config['port'],
    $config['charset']
);

$rootPdo = new PDO(
    $rootDsn,
    $config['username'],
    $config['password'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

$rootPdo->exec(sprintf(
    'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET %s COLLATE %s',
    str_replace('`', '``', $databaseName),
    $config['charset'],
    $config['charset'] . '_unicode_ci'
));

$pdo = new PDO(
    app_db_dsn(),
    $config['username'],
    $config['password'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

$schemaSql = file_get_contents(__DIR__ . '/../database/schema.sql');
if ($schemaSql === false) {
    throw new RuntimeException('Cannot read database/schema.sql');
}

foreach (array_filter(array_map('trim', explode(';', $schemaSql))) as $statement) {
    if ($statement !== '') {
        $pdo->exec($statement);
    }
}

app_db_run_migrations($pdo);

if (isset($options['reset'])) {
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    foreach (['product_related', 'product_color_prices', 'product_images', 'products', 'categories', 'admin_users'] as $table) {
        $pdo->exec('TRUNCATE TABLE ' . $table);
    }
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
}

$legacy = catalog_get_legacy_categories();
$featuredSlugs = [
    'fasad-tsvetok-elit',
    'kaliforniya-kamen',
    'bordyur-1000-220',
    'poshagovaya-gladkaya',
];

$pdo->beginTransaction();

try {
    $pdo->exec('DELETE FROM product_related');
    $pdo->exec('DELETE FROM product_color_prices');
    $pdo->exec('DELETE FROM product_images');
    $pdo->exec('DELETE FROM products');
    $pdo->exec('DELETE FROM categories');

    $categoryStmt = $pdo->prepare(
        'INSERT INTO categories (slug, title, description, image_path, layout, kicker, heading, lead, breadcrumbs_type, sort_order, is_active)
         VALUES (:slug, :title, :description, :image_path, :layout, :kicker, :heading, :lead, :breadcrumbs_type, :sort_order, 1)'
    );
    $productStmt = $pdo->prepare(
        'INSERT INTO products (category_id, slug, title, subtitle_text, description, meta_text, price_text, image_path, is_out_of_stock, external_url, page_type,
                               show_thickness, spec_size, spec_weight, gabarity, weight, sort_order, is_active, is_featured)
         VALUES (:category_id, :slug, :title, :subtitle_text, :description, :meta_text, :price_text, :image_path, :is_out_of_stock, :external_url, :page_type,
                 :show_thickness, :spec_size, :spec_weight, :gabarity, :weight, :sort_order, 1, :is_featured)'
    );
    $galleryStmt = $pdo->prepare(
        'INSERT INTO product_images (product_id, full_path, thumb_path, alt_text, sort_order)
         VALUES (:product_id, :full_path, :thumb_path, :alt_text, :sort_order)'
    );
    $priceStmt = $pdo->prepare(
        'INSERT INTO product_color_prices (product_id, label, amount, unit, sort_order)
         VALUES (:product_id, :label, :amount, :unit, :sort_order)'
    );
    $thicknessStmt = $pdo->prepare(
        'INSERT INTO product_thickness_options (product_id, option_key, label, value_text, spec_size, spec_weight, sort_order)
         VALUES (:product_id, :option_key, :label, :value_text, :spec_size, :spec_weight, :sort_order)'
    );
    $thicknessPriceStmt = $pdo->prepare(
        'INSERT INTO product_thickness_option_prices (thickness_option_id, label, amount, unit, sort_order)
         VALUES (:thickness_option_id, :label, :amount, :unit, :sort_order)'
    );

    $productSlugRegistry = [];
    $importedCategories = 0;
    $importedProducts = 0;

    $categoryIndex = 1;
    foreach ($legacy as $categorySlug => $category) {
        $categoryStmt->execute([
            'slug' => $categorySlug,
            'title' => $category['title'] ?? '',
            'description' => $category['description'] ?? '',
            'image_path' => $category['image'] ?? '',
            'layout' => $category['layout'] ?? '',
            'kicker' => $category['kicker'] ?? '',
            'heading' => $category['heading'] ?? '',
            'lead' => $category['lead'] ?? '',
            'breadcrumbs_type' => $category['breadcrumbs'] ?? '',
            'sort_order' => $categoryIndex++,
        ]);
        $categoryId = (int) $pdo->lastInsertId();
        $importedCategories++;

        $productIndex = 1;
        foreach ($category['products'] ?? [] as $product) {
            $baseSlug = trim((string) ($product['slug'] ?? ''));
            $slug = $baseSlug !== '' ? app_slugify($baseSlug) : app_slugify((string) ($product['title'] ?? 'product'));
            $suffix = 2;
            while (isset($productSlugRegistry[$slug])) {
                $slug = $slug . '-' . $suffix;
                $suffix++;
            }
            $productSlugRegistry[$slug] = true;

            $productStmt->execute([
                'category_id' => $categoryId,
                'slug' => $slug,
                'title' => $product['title'] ?? '',
                'subtitle_text' => $product['subtitle'] ?? '',
                'description' => $product['description'] ?? '',
                'meta_text' => $product['meta'] ?? '',
                'price_text' => $product['price'] ?? '',
                'image_path' => $product['image'] ?? '',
                'is_out_of_stock' => !empty($product['is_out_of_stock']) ? 1 : 0,
                'external_url' => $product['url'] ?? '',
                'page_type' => $product['page'] ?? '',
                'show_thickness' => !empty($product['show_thickness']) ? 1 : 0,
                'spec_size' => $product['spec_size'] ?? ($product['gabarity'] ?? ''),
                'spec_weight' => $product['spec_weight'] ?? ($product['weight'] ?? ''),
                'gabarity' => $product['gabarity'] ?? '',
                'weight' => $product['weight'] ?? '',
                'sort_order' => $productIndex++,
                'is_featured' => in_array($slug, $featuredSlugs, true) ? 1 : 0,
            ]);
            $productId = (int) $pdo->lastInsertId();
            $importedProducts++;

            $gallery = $product['gallery'] ?? [];
            if ($gallery === [] && !empty($product['image'])) {
                $gallery[] = [
                    'full' => $product['image'],
                    'thumb' => $product['image'],
                    'alt' => $product['title'] ?? '',
                ];
            }
            foreach ($gallery as $index => $row) {
                $galleryStmt->execute([
                    'product_id' => $productId,
                    'full_path' => $row['full'] ?? '',
                    'thumb_path' => $row['thumb'] ?? ($row['full'] ?? ''),
                    'alt_text' => $row['alt'] ?? ($product['title'] ?? ''),
                    'sort_order' => $index + 1,
                ]);
            }

            foreach (($product['color_prices'] ?? []) as $index => $row) {
                $priceStmt->execute([
                    'product_id' => $productId,
                    'label' => $row['label'] ?? '',
                    'amount' => $row['amount'] ?? '',
                    'unit' => $row['unit'] ?? '',
                    'sort_order' => $index + 1,
                ]);
            }

            foreach (($product['thickness_options'] ?? []) as $index => $option) {
                $thicknessStmt->execute([
                    'product_id' => $productId,
                    'option_key' => $option['key'] ?? app_slugify((string) ($option['label'] ?? ('option-' . ($index + 1)))),
                    'label' => $option['label'] ?? '',
                    'value_text' => $option['value'] ?? '',
                    'spec_size' => $option['spec_size'] ?? ($product['spec_size'] ?? ''),
                    'spec_weight' => $option['spec_weight'] ?? ($product['spec_weight'] ?? ''),
                    'sort_order' => $index + 1,
                ]);
                $thicknessOptionId = (int) $pdo->lastInsertId();

                foreach (($option['prices'] ?? []) as $priceIndex => $row) {
                    $thicknessPriceStmt->execute([
                        'thickness_option_id' => $thicknessOptionId,
                        'label' => $row['label'] ?? '',
                        'amount' => $row['amount'] ?? '',
                        'unit' => $row['unit'] ?? '',
                        'sort_order' => $priceIndex + 1,
                    ]);
                }
            }
        }
    }

    $adminUser = (string) ($options['admin-user'] ?? 'admin');
    $adminPass = (string) ($options['admin-pass'] ?? 'admin12345');
    $adminStmt = $pdo->prepare(
        'INSERT INTO admin_users (username, password_hash, display_name, is_active)
         VALUES (:username, :password_hash, :display_name, 1)'
    );
    $adminStmt->execute([
        'username' => $adminUser,
        'password_hash' => password_hash($adminPass, PASSWORD_DEFAULT),
        'display_name' => 'Администратор',
    ]);

    $pdo->commit();

    fwrite(STDOUT, "Database ready.\n");
    fwrite(STDOUT, "Imported categories: {$importedCategories}\n");
    fwrite(STDOUT, "Imported products: {$importedProducts}\n");
    fwrite(STDOUT, "Admin login: {$adminUser}\n");
    fwrite(STDOUT, "Admin password: {$adminPass}\n");
    fwrite(STDOUT, "Open /admin/login.php after starting Apache and MySQL in XAMPP.\n");
} catch (Throwable $e) {
    $pdo->rollBack();
    fwrite(STDERR, "Setup failed: " . $e->getMessage() . "\n");
    exit(1);
}
