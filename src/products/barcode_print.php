<?php
require_once '../../includes/init.php';

$current_page = 'products-barcode';
$page_title = 'چاپ بارکد';

// دریافت پارامترهای جستجو
$search = clean($_GET['search'] ?? '');
$category_id = clean($_GET['category_id'] ?? '');

// فقط کالاها را نمایش می‌دهیم، نه خدمات
$where = ["type = 'product' AND deleted_at IS NULL"];
if ($search) {
    $where[] = "(name LIKE '%{$search}%' OR code LIKE '%{$search}%' OR barcode LIKE '%{$search}%')";
}
if ($category_id) {
    $where[] = "category_id = " . intval($category_id);
}

// دریافت کالاها
$products = $db->getAll("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE " . implode(' AND ', $where) . "
    ORDER BY p.name ASC
");

// دریافت دسته‌بندی‌های کالا
$categories = $db->getAll("SELECT * FROM categories WHERE type = 'product' AND deleted_at IS NULL");

// تنظیمات چاپ
$print_settings = [
    'paper_size' => 'A4',
    'orientation' => 'portrait',
    'columns' => 3,
    'rows' => 8,
    'margin_top' => 10,
    'margin_right' => 10,
    'margin_bottom' => 10,
    'margin_left' => 10,
    'barcode_height' => 50,
    'font_size' => 12
];

require_once '../../templates/header.php';
?>

<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">چاپ بارکد</h1>
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print ml-2"></i>
                چاپ بارکدها
            </button>
        </div>
    </div>

    <!-- فیلترها -->
    <form method="GET" class="bg-gray-50 p-4 rounded-lg mb-6 print:hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">جستجو</label>
                <input type="text" name="search" value="<?= $search ?>" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                       placeholder="جستجو در نام، کد یا بارکد...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">دسته‌بندی</label>
                <select name="category_id" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">همه دسته‌بندی‌ها</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= ($category_id == $category['id']) ? 'selected' : '' ?>>
                            <?= $category['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn btn-secondary">اعمال فیلتر</button>
                <?php if($search || $category_id): ?>
                    <a href="<?= url('src/products/barcode_print.php') ?>" class="btn btn-link mr-2">
                        پاک کردن فیلترها
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <?php if(empty($products)): ?>
        <div class="text-center text-gray-500 py-8">
            هیچ کالایی برای چاپ بارکد یافت نشد.
        </div>
    <?php else: ?>
        <!-- نمایش بارکدها -->
        <div class="grid grid-cols-3 gap-4 print:gap-0">
            <?php foreach($products as $product): ?>
                <div class="border rounded p-4 print:border-0 print:p-2 text-center">
                    <?php if($product['barcode']): ?>
                        <div class="barcode mb-2">
                            <img src="<?= url("src/products/generate_barcode.php?code={$product['barcode']}") ?>" 
                                 alt="<?= $product['barcode'] ?>"
                                 class="mx-auto"
                                 style="height: <?= $print_settings['barcode_height'] ?>px">
                        </div>
                        <div class="text-sm">
                            <?= $product['barcode'] ?>
                        </div>
                    <?php else: ?>
                        <div class="text-red-500 text-sm">
                            بارکد تعریف نشده است
                        </div>
                    <?php endif; ?>
                    <div class="font-bold mt-2" style="font-size: <?= $print_settings['font_size'] ?>px">
                        <?= $product['name'] ?>
                    </div>
                    <div class="text-sm text-gray-600 print:text-gray-800">
                        <?= formatPrice($product['price']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- استایل‌های مخصوص چاپ -->
<style type="text/css" media="print">
    @page {
        size: <?= $print_settings['paper_size'] ?> <?= $print_settings['orientation'] ?>;
        margin: <?= $print_settings['margin_top'] ?>mm <?= $print_settings['margin_right'] ?>mm <?= $print_settings['margin_bottom'] ?>mm <?= $print_settings['margin_left'] ?>mm;
    }
    .print\:hidden {
        display: none !important;
    }
    .header, .sidebar {
        display: none !important;
    }
    .main-content {
        margin: 0 !important;
        padding: 0 !important;
    }
    .card {
        box-shadow: none !important;
        padding: 0 !important;
    }
</style>

<?php require_once '../../templates/footer.php'; ?>