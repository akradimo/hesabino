<?php
require_once '../../includes/init.php';

$current_page = 'products-barcode-bulk';
$page_title = 'چاپ بارکد تعدادی';
$user = CURRENT_USER;
$current_date = CURRENT_TIME;

// بررسی درخواست چاپ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_quantities = [];
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $product_quantities[$product_id] = intval($quantity);
        }
    }
    
    if (empty($product_quantities)) {
        setMessage('error', 'لطفاً تعداد بارکد مورد نظر را وارد کنید');
    }
}

// دریافت پارامترهای جستجو
$search = clean($_GET['search'] ?? '');
$category_id = clean($_GET['category_id'] ?? '');
$sort_by = clean($_GET['sort_by'] ?? 'name');
$sort_order = clean($_GET['sort_order'] ?? 'asc');

// ساختن کوئری جستجو
$where = ["type = 'product' AND deleted_at IS NULL"];
if ($search) {
    $where[] = "(name LIKE '%{$search}%' OR code LIKE '%{$search}%' OR barcode LIKE '%{$search}%')";
}
if ($category_id) {
    $where[] = "category_id = " . intval($category_id);
}

// تعیین ترتیب نمایش
$order_by = match($sort_by) {
    'code' => 'p.code',
    'price' => 'p.price',
    'category' => 'c.name',
    default => 'p.name'
};
$order_by .= " $sort_order";

// دریافت کالاها
$products = $db->getAll("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE " . implode(' AND ', $where) . "
    ORDER BY $order_by
");

// دریافت دسته‌بندی‌ها
$categories = $db->getAll("SELECT * FROM categories WHERE type = 'product' AND deleted_at IS NULL");

// تنظیمات چاپ
$print_settings = [
    'paper_size' => 'A4',
    'orientation' => 'portrait',
    'columns' => 3,
    'rows' => 8,
    'margin' => [
        'top' => 10,
        'right' => 10,
        'bottom' => 10,
        'left' => 10
    ],
    'barcode' => [
        'height' => 50,
        'width' => 2,
        'padding' => 10
    ],
    'font' => [
        'family' => 'IRANSans',
        'size' => [
            'name' => 12,
            'price' => 10,
            'code' => 8
        ]
    ]
];

?>

<?php require_once '../../templates/header.php'; ?>

<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">چاپ بارکد تعدادی</h1>
        <?php if (!empty($product_quantities)): ?>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print ml-2"></i>
                چاپ <?= array_sum($product_quantities) ?> بارکد
            </button>
        <?php endif; ?>
    </div>

    <?php if(hasError()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= getError() ?>
        </div>
    <?php endif; ?>

    <!-- فرم جستجو و فیلتر -->
    <form method="GET" class="bg-gray-50 p-4 rounded-lg mb-6 print:hidden">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">جستجو</label>
                <input type="text" name="search" value="<?= $search ?>" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                       placeholder="جستجو در نام، کد یا بارکد">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">دسته‌بندی</label>
                <select name="category_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">همه دسته‌بندی‌ها</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= ($category_id == $category['id']) ? 'selected' : '' ?>>
                            <?= $category['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">مرتب‌سازی</label>
                <div class="flex gap-2">
                    <select name="sort_by" class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="name" <?= ($sort_by === 'name') ? 'selected' : '' ?>>نام</option>
                        <option value="code" <?= ($sort_by === 'code') ? 'selected' : '' ?>>کد</option>
                        <option value="price" <?= ($sort_by === 'price') ? 'selected' : '' ?>>قیمت</option>
                        <option value="category" <?= ($sort_by === 'category') ? 'selected' : '' ?>>دسته‌بندی</option>
                    </select>
                    <select name="sort_order" class="px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="asc" <?= ($sort_order === 'asc') ? 'selected' : '' ?>>صعودی</option>
                        <option value="desc" <?= ($sort_order === 'desc') ? 'selected' : '' ?>>نزولی</option>
                    </select>
                </div>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn btn-secondary">اعمال فیلتر</button>
                <?php if($search || $category_id || $sort_by !== 'name' || $sort_order !== 'asc'): ?>
                    <a href="<?= url('src/products/barcode_bulk_print.php') ?>" class="btn btn-link mr-2">
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
        <!-- فرم انتخاب تعداد بارکد -->
        <form method="POST" class="print:hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-striped">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 text-right">کد</th>
                            <th class="py-3 px-4 text-right">نام کالا</th>
                            <th class="py-3 px-4 text-right">دسته‌بندی</th>
                            <th class="py-3 px-4 text-right">قیمت</th>
                            <th class="py-3 px-4 text-right">بارکد</th>
                            <th class="py-3 px-4 text-center">تعداد چاپ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $product): ?>
                            <tr>
                                <td class="py-3 px-4"><?= $product['code'] ?></td>
                                <td class="py-3 px-4"><?= $product['name'] ?></td>
                                <td class="py-3 px-4"><?= $product['category_name'] ?? '---' ?></td>
                                <td class="py-3 px-4"><?= formatPrice($product['price']) ?></td>
                                <td class="py-3 px-4"><?= $product['barcode'] ?: '---' ?></td>
                                <td class="py-3 px-4 text-center">
                                    <?php if($product['barcode']): ?>
                                        <input type="number" name="quantities[<?= $product['id'] ?>]" 
                                               value="<?= $product_quantities[$product['id']] ?? 0 ?>"
                                               min="0" max="100"
                                               class="w-20 px-2 py-1 border rounded text-center">
                                    <?php else: ?>
                                        <span class="text-red-500 text-sm">بدون بارکد</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-left">
                <button type="submit" class="btn btn-primary">
                    نمایش پیش‌نمایش چاپ
                </button>
            </div>
        </form>

        <?php if(!empty($product_quantities)): ?>
            <!-- نمایش بارکدها برای چاپ -->
            <div class="print:block hidden">
                <div class="grid grid-cols-<?= $print_settings['columns'] ?> gap-4 print:gap-0">
                    <?php foreach($product_quantities as $product_id => $quantity): ?>
                        <?php 
                        $product = array_filter($products, fn($p) => $p['id'] == $product_id)[0];
                        for($i = 0; $i < $quantity; $i++):
                        ?>
                            <div class="border rounded p-4 print:border-0 print:p-2 text-center"
                                 style="page-break-inside: avoid;">
                                <div class="barcode mb-2">
                                    <img src="<?= url("src/products/generate_barcode.php?code={$product['barcode']}") ?>" 
                                         alt="<?= $product['barcode'] ?>"
                                         class="mx-auto"
                                         style="height: <?= $print_settings['barcode']['height'] ?>px">
                                </div>
                                <div class="text-sm">
                                    <?= $product['barcode'] ?>
                                </div>
                                <div class="font-bold mt-2" 
                                     style="font-size: <?= $print_settings['font']['size']['name'] ?>px">
                                    <?= $product['name'] ?>
                                </div>
                                <div class="text-sm text-gray-600 print:text-gray-800">
                                    <?= formatPrice($product['price']) ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- استایل‌های مخصوص چاپ -->
<style type="text/css" media="print">
    @page {
        size: <?= $print_settings['paper_size'] ?> <?= $print_settings['orientation'] ?>;
        margin: <?= $print_settings['margin']['top'] ?>mm <?= $print_settings['margin']['right'] ?>mm 
                <?= $print_settings['margin']['bottom'] ?>mm <?= $print_settings['margin']['left'] ?>mm;
    }
    .print\:hidden {
        display: none !important;
    }
    .print\:block {
        display: block !important;
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