<?php
declare(strict_types=1);

require_once __DIR__ . '/app.php';

function catalog_admin_assert_db(): PDO
{
    $pdo = app_pdo();
    if (!$pdo instanceof PDO || !app_db_ready()) {
        throw new RuntimeException('База данных не настроена. Сначала импортируйте схему и данные каталога.');
    }

    return $pdo;
}

function catalog_admin_category_options(bool $includeInactive = true): array
{
    $pdo = catalog_admin_assert_db();
    $sql = 'SELECT id, slug, title, sort_order, is_active FROM categories';
    if (!$includeInactive) {
        $sql .= ' WHERE is_active = 1';
    }
    $sql .= ' ORDER BY sort_order ASC, id ASC';

    return $pdo->query($sql)->fetchAll();
}

function catalog_admin_list_categories(): array
{
    $pdo = catalog_admin_assert_db();

    return $pdo->query(
        'SELECT c.id, c.slug, c.title, c.layout, c.image_path, c.sort_order, c.is_active, COUNT(p.id) AS product_count
         FROM categories c
         LEFT JOIN products p ON p.category_id = c.id
         GROUP BY c.id
         ORDER BY c.sort_order ASC, c.id ASC'
    )->fetchAll();
}

function catalog_admin_get_category(?int $id): ?array
{
    if ($id === null) {
        return [
            'id' => null,
            'slug' => '',
            'title' => '',
            'description' => '',
            'image_path' => '',
            'layout' => '',
            'kicker' => '',
            'heading' => '',
            'lead' => '',
            'breadcrumbs_type' => '',
            'sort_order' => 100,
            'is_active' => 1,
        ];
    }

    $pdo = catalog_admin_assert_db();
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return is_array($row) ? $row : null;
}

function catalog_admin_save_category(array $post, array $files = []): int
{
    $pdo = catalog_admin_assert_db();
    $id = isset($post['id']) && $post['id'] !== '' ? (int) $post['id'] : null;
    $title = trim((string) ($post['title'] ?? ''));
    $slug = trim((string) ($post['slug'] ?? ''));
    $slug = $slug !== '' ? app_slugify($slug) : app_slugify($title);

    if ($title === '') {
        throw new RuntimeException('Укажите название категории.');
    }

    if ($slug === '') {
        throw new RuntimeException('Не удалось сформировать slug категории.');
    }

    $imagePath = trim((string) ($post['image_path'] ?? ''));
    if (isset($files['image_upload']) && is_array($files['image_upload'])) {
        $uploaded = app_save_uploaded_image($files['image_upload'], 'categories', $title);
        if ($uploaded !== null) {
            $imagePath = $uploaded;
        }
    }

    $existingCategory = $id !== null ? catalog_admin_get_category($id) : null;

    $data = [
        'slug' => $slug,
        'title' => $title,
        'description' => trim((string) ($post['description'] ?? '')),
        'image_path' => $imagePath,
        'layout' => trim((string) ($post['layout'] ?? ($existingCategory['layout'] ?? ''))),
        'kicker' => 'Каталог продукции 2026',
        'heading' => trim((string) ($post['heading'] ?? '')),
        'lead' => trim((string) ($post['lead'] ?? '')),
        'breadcrumbs_type' => trim((string) ($post['breadcrumbs_type'] ?? ($existingCategory['breadcrumbs_type'] ?? ''))),
        'sort_order' => (int) ($post['sort_order'] ?? 100),
        'is_active' => !empty($post['is_active']) ? 1 : 0,
    ];

    $duplicateStmt = $pdo->prepare('SELECT id FROM categories WHERE slug = :slug AND (:id IS NULL OR id != :id) LIMIT 1');
    $duplicateStmt->execute(['slug' => $slug, 'id' => $id]);
    if ($duplicateStmt->fetch()) {
        throw new RuntimeException('Категория с таким slug уже существует.');
    }

    if ($id === null) {
        $stmt = $pdo->prepare(
            'INSERT INTO categories (slug, title, description, image_path, layout, kicker, heading, lead, breadcrumbs_type, sort_order, is_active)
             VALUES (:slug, :title, :description, :image_path, :layout, :kicker, :heading, :lead, :breadcrumbs_type, :sort_order, :is_active)'
        );
        $stmt->execute($data);

        return (int) $pdo->lastInsertId();
    }

    $data['id'] = $id;
    $stmt = $pdo->prepare(
        'UPDATE categories
         SET slug = :slug,
             title = :title,
             description = :description,
             image_path = :image_path,
             layout = :layout,
             kicker = :kicker,
             heading = :heading,
             lead = :lead,
             breadcrumbs_type = :breadcrumbs_type,
             sort_order = :sort_order,
             is_active = :is_active
         WHERE id = :id'
    );
    $stmt->execute($data);

    return $id;
}

function catalog_admin_delete_category(int $id): void
{
    $pdo = catalog_admin_assert_db();
    $countStmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id = :id');
    $countStmt->execute(['id' => $id]);
    if ((int) $countStmt->fetchColumn() > 0) {
        throw new RuntimeException('Нельзя удалить категорию, пока в ней есть товары.');
    }

    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function catalog_admin_list_products(?int $categoryId = null): array
{
    $pdo = catalog_admin_assert_db();
    $sql = 'SELECT p.*, c.title AS category_title, c.slug AS category_slug
            FROM products p
            INNER JOIN categories c ON c.id = p.category_id';
    $params = [];
    if ($categoryId !== null) {
        $sql .= ' WHERE p.category_id = :category_id';
        $params['category_id'] = $categoryId;
    }
    $sql .= ' ORDER BY c.sort_order ASC, p.sort_order ASC, p.id ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function catalog_admin_product_options(?int $excludeId = null): array
{
    $pdo = catalog_admin_assert_db();
    $sql = 'SELECT p.id, p.title, p.slug, c.title AS category_title
            FROM products p
            INNER JOIN categories c ON c.id = p.category_id';
    $params = [];
    if ($excludeId !== null) {
        $sql .= ' WHERE p.id != :id';
        $params['id'] = $excludeId;
    }
    $sql .= ' ORDER BY c.sort_order ASC, p.sort_order ASC, p.id ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function catalog_admin_get_product(?int $id): ?array
{
    if ($id === null) {
        return [
            'id' => null,
            'category_id' => '',
            'slug' => '',
            'title' => '',
            'subtitle_text' => '',
            'description' => '',
            'meta_text' => '',
            'price_text' => '',
            'image_path' => '',
            'is_out_of_stock' => 0,
            'external_url' => '',
            'page_type' => '',
            'show_thickness' => 0,
            'spec_size' => '',
            'spec_weight' => '',
            'gabarity' => '',
            'weight' => '',
            'sort_order' => 100,
            'is_active' => 1,
            'is_featured' => 0,
            'gallery_rows' => [],
            'price_rows' => [],
            'thickness_options' => [],
            'related_ids' => [],
        ];
    }

    $pdo = catalog_admin_assert_db();
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();
    if (!is_array($product)) {
        return null;
    }

    $galleryStmt = $pdo->prepare('SELECT full_path, thumb_path, alt_text FROM product_images WHERE product_id = :id ORDER BY sort_order ASC, id ASC');
    $galleryStmt->execute(['id' => $id]);
    $product['gallery_rows'] = $galleryStmt->fetchAll();

    $priceStmt = $pdo->prepare('SELECT label, amount, unit FROM product_color_prices WHERE product_id = :id ORDER BY sort_order ASC, id ASC');
    $priceStmt->execute(['id' => $id]);
    $product['price_rows'] = $priceStmt->fetchAll();

    $relatedStmt = $pdo->prepare('SELECT related_product_id FROM product_related WHERE product_id = :id ORDER BY sort_order ASC, id ASC');
    $relatedStmt->execute(['id' => $id]);
    $product['related_ids'] = array_map('intval', array_column($relatedStmt->fetchAll(), 'related_product_id'));

    $optionStmt = $pdo->prepare(
        'SELECT id, option_key, label, value_text, spec_size, spec_weight
         FROM product_thickness_options
         WHERE product_id = :id
         ORDER BY sort_order ASC, id ASC'
    );
    $optionStmt->execute(['id' => $id]);
    $options = $optionStmt->fetchAll();

    $priceStmt = $pdo->prepare(
        'SELECT label, amount, unit
         FROM product_thickness_option_prices
         WHERE thickness_option_id = :thickness_option_id
         ORDER BY sort_order ASC, id ASC'
    );
    $product['thickness_options'] = [];
    foreach ($options as $option) {
        $priceStmt->execute(['thickness_option_id' => (int) $option['id']]);
        $product['thickness_options'][] = [
            'key' => (string) ($option['option_key'] ?? ''),
            'label' => (string) ($option['label'] ?? ''),
            'value' => (string) ($option['value_text'] ?? ''),
            'spec_size' => (string) ($option['spec_size'] ?? ''),
            'spec_weight' => (string) ($option['spec_weight'] ?? ''),
            'prices' => $priceStmt->fetchAll(),
        ];
    }

    return $product;
}

function catalog_admin_parse_thickness_options(string $value): array
{
    if (trim($value) === '') {
        return [];
    }

    $decoded = json_decode($value, true);
    if (!is_array($decoded)) {
        throw new RuntimeException('Не удалось прочитать варианты толщины. Попробуйте заново заполнить блок.');
    }

    $result = [];
    foreach ($decoded as $index => $item) {
        if (!is_array($item)) {
            continue;
        }

        $label = trim((string) ($item['label'] ?? ''));
        $valueText = trim((string) ($item['value'] ?? ''));
        $specSize = trim((string) ($item['spec_size'] ?? ''));
        $specWeight = trim((string) ($item['spec_weight'] ?? ''));
        $prices = [];

        foreach (($item['prices'] ?? []) as $price) {
            if (!is_array($price)) {
                continue;
            }
            $priceLabel = trim((string) ($price['label'] ?? ''));
            $amount = trim((string) ($price['amount'] ?? ''));
            $unit = trim((string) ($price['unit'] ?? ''));
            if ($priceLabel === '' && $amount === '' && $unit === '') {
                continue;
            }
            $prices[] = [
                'label' => $priceLabel,
                'amount' => $amount,
                'unit' => $unit,
            ];
        }

        if ($label === '' && $valueText === '' && $specSize === '' && $specWeight === '' && $prices === []) {
            continue;
        }

        if ($label === '') {
            throw new RuntimeException('У каждого варианта толщины должно быть название кнопки.');
        }

        $result[] = [
            'key' => trim((string) ($item['key'] ?? '')) ?: app_slugify($label),
            'label' => $label,
            'value' => $valueText,
            'spec_size' => $specSize,
            'spec_weight' => $specWeight,
            'prices' => $prices,
        ];
    }

    return $result;
}

function catalog_admin_derive_product_meta(string $specSize, string $gabarity): string
{
    $size = trim($specSize) !== '' ? trim($specSize) : trim($gabarity);
    return $size !== '' ? 'Размеры(мм): ' . $size : '';
}

function catalog_admin_derive_product_price(array $priceRows, array $thicknessOptions): string
{
    $source = $priceRows;
    if ($thicknessOptions !== [] && !empty($thicknessOptions[0]['prices']) && is_array($thicknessOptions[0]['prices'])) {
        $source = $thicknessOptions[0]['prices'];
    }

    foreach ($source as $row) {
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

function catalog_admin_parse_gallery_input(string $value, string $title): array
{
    $rows = [];
    foreach (app_parse_lines($value) as $parts) {
        $full = trim((string) ($parts[0] ?? ''));
        if ($full === '') {
            continue;
        }
        $rows[] = [
            'full_path' => $full,
            'thumb_path' => trim((string) ($parts[1] ?? $full)),
            'alt_text' => trim((string) ($parts[2] ?? $title)),
        ];
    }

    return $rows;
}

function catalog_admin_parse_price_input(string $value): array
{
    $rows = [];
    foreach (app_parse_lines($value) as $parts) {
        $label = trim((string) ($parts[0] ?? ''));
        $amount = trim((string) ($parts[1] ?? ''));
        $unit = trim((string) ($parts[2] ?? ''));
        if ($label === '' && $amount === '' && $unit === '') {
            continue;
        }
        $rows[] = [
            'label' => $label,
            'amount' => $amount,
            'unit' => $unit,
        ];
    }

    return $rows;
}

function catalog_admin_save_product(array $post, array $files = []): int
{
    $pdo = catalog_admin_assert_db();
    $id = isset($post['id']) && $post['id'] !== '' ? (int) $post['id'] : null;
    $title = trim((string) ($post['title'] ?? ''));
    if ($title === '') {
        throw new RuntimeException('Укажите название товара.');
    }

    $categoryId = (int) ($post['category_id'] ?? 0);
    if ($categoryId <= 0) {
        throw new RuntimeException('Выберите категорию товара.');
    }

    $slug = trim((string) ($post['slug'] ?? ''));
    $slug = $slug !== '' ? app_slugify($slug) : app_slugify($title);

    $duplicateStmt = $pdo->prepare('SELECT id FROM products WHERE slug = :slug AND (:id IS NULL OR id != :id) LIMIT 1');
    $duplicateStmt->execute(['slug' => $slug, 'id' => $id]);
    if ($duplicateStmt->fetch()) {
        throw new RuntimeException('Товар с таким slug уже существует.');
    }

    $imagePath = trim((string) ($post['image_path'] ?? ''));
    if (isset($files['image_upload']) && is_array($files['image_upload'])) {
        $uploaded = app_save_uploaded_image($files['image_upload'], 'products', $title);
        if ($uploaded !== null) {
            $imagePath = $uploaded;
        }
    }

    $galleryRows = catalog_admin_parse_gallery_input((string) ($post['gallery_text'] ?? ''), $title);
    if (isset($files['gallery_uploads']) && is_array($files['gallery_uploads']['name'] ?? null)) {
        $count = count($files['gallery_uploads']['name']);
        for ($i = 0; $i < $count; $i++) {
            $chunk = [
                'name' => $files['gallery_uploads']['name'][$i] ?? '',
                'type' => $files['gallery_uploads']['type'][$i] ?? '',
                'tmp_name' => $files['gallery_uploads']['tmp_name'][$i] ?? '',
                'error' => $files['gallery_uploads']['error'][$i] ?? UPLOAD_ERR_NO_FILE,
                'size' => $files['gallery_uploads']['size'][$i] ?? 0,
            ];
            $uploaded = app_save_uploaded_image($chunk, 'products/gallery', $title . '-' . ($i + 1));
            if ($uploaded !== null) {
                $galleryRows[] = [
                    'full_path' => $uploaded,
                    'thumb_path' => $uploaded,
                    'alt_text' => $title,
                ];
            }
        }
    }
    if ($imagePath !== '') {
        $galleryRows = array_values(array_filter(
            $galleryRows,
            static fn (array $row): bool => (string) ($row['full_path'] ?? '') !== $imagePath
        ));
    }

    $priceRows = catalog_admin_parse_price_input((string) ($post['prices_text'] ?? ''));
    $thicknessOptions = catalog_admin_parse_thickness_options((string) ($post['thickness_options_json'] ?? ''));
    $relatedIds = array_values(array_unique(array_map('intval', $post['related_ids'] ?? [])));
    $relatedIds = array_values(array_filter($relatedIds, static fn (int $value): bool => $value > 0 && $value !== $id));
    if (count($relatedIds) > 3) {
        throw new RuntimeException('Можно выбрать максимум 3 рекомендации для товара.');
    }

    $data = [
        'category_id' => $categoryId,
        'slug' => $slug,
        'title' => $title,
        'subtitle_text' => trim((string) ($post['subtitle_text'] ?? '')),
        'description' => trim((string) ($post['description'] ?? '')),
        'meta_text' => catalog_admin_derive_product_meta(
            trim((string) ($post['spec_size'] ?? '')),
            trim((string) ($post['gabarity'] ?? ''))
        ),
        'price_text' => catalog_admin_derive_product_price($priceRows, $thicknessOptions),
        'image_path' => $imagePath,
        'is_out_of_stock' => !empty($post['is_out_of_stock']) ? 1 : 0,
        'external_url' => trim((string) ($post['external_url'] ?? '')),
        'page_type' => trim((string) ($post['page_type'] ?? '')),
        'show_thickness' => !empty($post['show_thickness']) ? 1 : 0,
        'spec_size' => trim((string) ($post['spec_size'] ?? '')),
        'spec_weight' => trim((string) ($post['spec_weight'] ?? '')),
        'gabarity' => trim((string) ($post['gabarity'] ?? '')),
        'weight' => trim((string) ($post['weight'] ?? '')),
        'sort_order' => (int) ($post['sort_order'] ?? 100),
        'is_active' => !empty($post['is_active']) ? 1 : 0,
        'is_featured' => !empty($post['is_featured']) ? 1 : 0,
    ];

    if ($data['description'] === '') {
        $data['description'] = $data['subtitle_text'] !== ''
            ? $title . '. ' . str_replace(["\r", "\n"], ' ', $data['subtitle_text'])
            : $title . ' — MRAMORBETON.';
    }

    if ($data['show_thickness'] && $thicknessOptions === []) {
        throw new RuntimeException('Добавьте хотя бы один вариант толщины или отключите переключатель толщины.');
    }

    $pdo->beginTransaction();

    try {
        if ($id === null) {
            $stmt = $pdo->prepare(
                'INSERT INTO products (category_id, slug, title, subtitle_text, description, meta_text, price_text, image_path, is_out_of_stock, external_url, page_type,
                                       show_thickness, spec_size, spec_weight, gabarity, weight, sort_order, is_active, is_featured)
                 VALUES (:category_id, :slug, :title, :subtitle_text, :description, :meta_text, :price_text, :image_path, :is_out_of_stock, :external_url, :page_type,
                         :show_thickness, :spec_size, :spec_weight, :gabarity, :weight, :sort_order, :is_active, :is_featured)'
            );
            $stmt->execute($data);
            $id = (int) $pdo->lastInsertId();
        } else {
            $data['id'] = $id;
            $stmt = $pdo->prepare(
                'UPDATE products
                 SET category_id = :category_id,
                     slug = :slug,
                     title = :title,
                     subtitle_text = :subtitle_text,
                     description = :description,
                     meta_text = :meta_text,
                     price_text = :price_text,
                     image_path = :image_path,
                     is_out_of_stock = :is_out_of_stock,
                     external_url = :external_url,
                     page_type = :page_type,
                     show_thickness = :show_thickness,
                     spec_size = :spec_size,
                     spec_weight = :spec_weight,
                     gabarity = :gabarity,
                     weight = :weight,
                     sort_order = :sort_order,
                     is_active = :is_active,
                     is_featured = :is_featured
                 WHERE id = :id'
            );
            $stmt->execute($data);
        }

        $pdo->prepare('DELETE FROM product_images WHERE product_id = :id')->execute(['id' => $id]);
        $pdo->prepare('DELETE FROM product_color_prices WHERE product_id = :id')->execute(['id' => $id]);
        $pdo->prepare('DELETE FROM product_thickness_options WHERE product_id = :id')->execute(['id' => $id]);
        $pdo->prepare('DELETE FROM product_related WHERE product_id = :id')->execute(['id' => $id]);

        if ($galleryRows !== []) {
            $stmt = $pdo->prepare(
                'INSERT INTO product_images (product_id, full_path, thumb_path, alt_text, sort_order)
                 VALUES (:product_id, :full_path, :thumb_path, :alt_text, :sort_order)'
            );
            foreach ($galleryRows as $index => $row) {
                $stmt->execute([
                    'product_id' => $id,
                    'full_path' => $row['full_path'],
                    'thumb_path' => $row['thumb_path'],
                    'alt_text' => $row['alt_text'],
                    'sort_order' => $index + 1,
                ]);
            }
        }

        if ($priceRows !== []) {
            $stmt = $pdo->prepare(
                'INSERT INTO product_color_prices (product_id, label, amount, unit, sort_order)
                 VALUES (:product_id, :label, :amount, :unit, :sort_order)'
            );
            foreach ($priceRows as $index => $row) {
                $stmt->execute([
                    'product_id' => $id,
                    'label' => $row['label'],
                    'amount' => $row['amount'],
                    'unit' => $row['unit'],
                    'sort_order' => $index + 1,
                ]);
            }
        }

        if ($thicknessOptions !== []) {
            $optionStmt = $pdo->prepare(
                'INSERT INTO product_thickness_options (product_id, option_key, label, value_text, spec_size, spec_weight, sort_order)
                 VALUES (:product_id, :option_key, :label, :value_text, :spec_size, :spec_weight, :sort_order)'
            );
            $optionPriceStmt = $pdo->prepare(
                'INSERT INTO product_thickness_option_prices (thickness_option_id, label, amount, unit, sort_order)
                 VALUES (:thickness_option_id, :label, :amount, :unit, :sort_order)'
            );

            foreach ($thicknessOptions as $index => $option) {
                $optionStmt->execute([
                    'product_id' => $id,
                    'option_key' => $option['key'],
                    'label' => $option['label'],
                    'value_text' => $option['value'],
                    'spec_size' => $option['spec_size'],
                    'spec_weight' => $option['spec_weight'],
                    'sort_order' => $index + 1,
                ]);
                $optionId = (int) $pdo->lastInsertId();

                foreach ($option['prices'] as $priceIndex => $row) {
                    $optionPriceStmt->execute([
                        'thickness_option_id' => $optionId,
                        'label' => $row['label'],
                        'amount' => $row['amount'],
                        'unit' => $row['unit'],
                        'sort_order' => $priceIndex + 1,
                    ]);
                }
            }
        }

        if ($relatedIds !== []) {
            $stmt = $pdo->prepare(
                'INSERT INTO product_related (product_id, related_product_id, sort_order)
                 VALUES (:product_id, :related_product_id, :sort_order)'
            );
            foreach ($relatedIds as $index => $relatedId) {
                $stmt->execute([
                    'product_id' => $id,
                    'related_product_id' => $relatedId,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }

    return $id;
}

function catalog_admin_delete_product(int $id): void
{
    $pdo = catalog_admin_assert_db();
    $pdo->beginTransaction();

    try {
        $pdo->prepare('DELETE FROM product_related WHERE product_id = :id OR related_product_id = :id')->execute(['id' => $id]);
        $pdo->prepare('DELETE FROM product_images WHERE product_id = :id')->execute(['id' => $id]);
        $pdo->prepare('DELETE FROM product_color_prices WHERE product_id = :id')->execute(['id' => $id]);
        $pdo->prepare('DELETE FROM products WHERE id = :id')->execute(['id' => $id]);
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}
