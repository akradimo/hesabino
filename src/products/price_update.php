<?php
require_once '../../includes/init.php';

$current_page = 'products-price-update';
$page_title = 'به‌روزرسانی لیست قیمت';

// دریافت لیست قیمت‌های فعال
$price_lists = $db->getAll("SELECT * FROM price_lists WHERE is_active = 1 AND deleted_at IS NULL");

// دریافت پارامترهای جستجو
$search = clean($_GET['search'] ?? '');
$category_id = clean($_GET['category_id'] ?? '');
$type = clean($_GET['type'] ?? 'all');

// ساختن کوئری جستجو
$where = ["p.deleted_at IS NULL"];
if ($search) {
    $where[] = "(p.name LIKE '%{$search}%' OR p.code LIKE '%{$search}%')";
}
if ($category_id) {
    $where[] = "p.category_id = " . intval($category_id);
}
if ($type !== 'all') {
    $where[] = "p.type = '" . ($type === 'product' ? 'product' : 'service') . "'";
}

// دریافت محصولات و خدمات با قیمت‌های فعلی
$items = $db->getAll("
    SELECT 
        p.*,
        c.name as category_name,
        GROUP_CONCAT(
            CONCAT(pl.id, ':', COALESCE(pli.price, 0))
            ORDER BY pl.id
        ) as price_list_prices
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    LEFT JOIN price_list_items pli ON p.id = pli.product_id 
    LEFT JOIN price_lists pl ON pli.price_list_id = pl.id AND pl.is_active = 1
    WHERE " . implode(' AND ', $where) . "
    GROUP BY p.id
    ORDER BY p.type, p.name
");

// دریافت دسته‌بندی‌ها برای فیلتر
$categories = $db->getAll("SELECT * FROM categories WHERE deleted_at IS NULL");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $db->beginTransaction();
        
        foreach ($_POST['prices'] as $product_id => $price_lists) {
            foreach ($price_lists as $price_list_id => $price) {
                $price = toEnglishNumber(clean($price));
                
                // حذف قیمت قبلی
                $db->execute(
                    "DELETE FROM price_list_items 
                     WHERE product_id = ? AND price_list_id = ?",
                    [$product_id, $price_list_id]
                );
                
                // افزودن قیمت جدید
                if ($price > 0) {
                    $db->insert(
                        "INSERT INTO price_list_items (product_id, price_list_id, price) 
                         VALUES (?, ?, ?)",
                        [$product_id, $price_list_id, $price]
                    );
                }
            }
        }
        
        $db->commit();
        setMessage('success', 'لیست قیمت‌ها با موفقیت به‌روزرسانی شد');
        redirect('src/products/price_update.php');
    } catch (Exception $e) {
        $db->rollBack();
        setMessage('error', 'خطا در به‌روزرسانی لیست قیمت‌ها');
    }
}

require_once '../../templates/header.php';
?>

<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">به‌روزرسانی لیست قیمت</h1>
        <div>
            <a href="<?= url('src/products/price_lists.php') ?>" class="btn btn-secondary ml-2">
                مدیریت لیست قیمت‌ها
            </a>
        </div>
    </div>

    <?php if(empty($price_lists)): ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
            هیچ لیست قیمت فعالی وجود ندارد. ابتدا یک لیست قیمت ایجاد کنید.
        </div>
    <?php else: ?>
        <!-- فیلترها -->
        <form method="GET" class="bg-gray-50 p-4 rounded-lg mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">جستجو</label>
                    <input type="text" name="search" value="<?= $search ?>" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                           placeholder="جستجو در نام یا کد...">
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع</label>
                    <select name="type" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="all" <?= ($type === 'all') ? 'selected' : '' ?>>همه</option>
                        <option value="product" <?= ($type === 'product') ? 'selected' : '' ?>>کالا</option>
                        <option value="service" <?= ($type === 'service') ? 'selected' : '' ?>>خدمات</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-secondary">اعمال فیلتر</button>
                    <?php if($search || $category_id || $type !== 'all'): ?>
                        <a href="<?= url('src/products/price_update.php') ?>" class="btn btn-link mr-2">
                            پاک کردن فیلترها
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <form method="POST">
            <div class="overflow-x-auto">
                <table class="w-full table-striped">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 text-right">کد</th>
                            <th class="py-3 px-4 text-right">نام</th>
                            <th class="py-3 px-4 text-right">نوع</th>
                            <th class="py-3 px-4 text-right">دسته‌بندی</th>
                            <th class="py-3 px-4 text-right">قیمت پایه</th>
                            <?php foreach($price_lists as $price_list): ?>
                                <th class="py-3 px-4 text-right">
                                    <?= $price_list['name'] ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($items)): ?>
                            <tr>
                                <td colspan="<?= 5 + count($price_lists) ?>" class="py-4 text-center text-gray-500">
                                    هیچ موردی یافت نشد.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($items as $item): ?>
                                <?php
                                $price_list_prices = [];
                                if ($item['price_list_prices']) {
                                    foreach(explode(',', $item['price_list_prices']) as $price_data) {
                                        list($list_id, $price) = explode(':', $price_data);
                                        $price_list_prices[$list_id] = $price;
                                    }
                                }
                                ?>
                                <tr>
                                    <td class="py-3 px-4"><?= $item['code'] ?></td>
                                    <td class="py-3 px-4"><?= $item['name'] ?></td>
                                    <td class="py-3 px-4">
                                        <?= $item['type'] === 'product' ? 'کالا' : 'خدمات' ?>
                                    </td>
                                    <td class="py-3 px-4"><?= $item['category_name'] ?? '---' ?></td>
                                    <td class="py-3 px-4"><?= formatPrice($item['price']) ?></td>
                                    <?php foreach($price_lists as $price_list): ?>
                                        <td class="py-3 px-4">
                                            <input type="text" 
                                                   name="prices[<?= $item['id'] ?>][<?= $price_list['id'] ?>]"
                                                   value="<?= $price_list_prices[$price_list['id']] ?? '' ?>"
                                                   class="w-full px-3 py-1 border rounded focus:outline-none focus:border-blue-500"
                                                   placeholder="<?= $item['price'] ?>">
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if(!empty($items)): ?>
                <div class="mt-6 text-left">
                    <button type="submit" class="btn btn-primary">
                        ذخیره تغییرات
                    </button>
                </div>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>

<?php require_once '../../templates/footer.php'; ?>