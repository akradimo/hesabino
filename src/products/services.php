<?php
require_once '../../includes/init.php';

$current_page = 'products-services';
$page_title = 'فهرست خدمات';

// دریافت پارامترهای جستجو
$search = clean($_GET['search'] ?? '');
$category_id = clean($_GET['category_id'] ?? '');

// ساختن کوئری جستجو
$where = ["type = 'service' AND deleted_at IS NULL"];
if ($search) {
    $where[] = "(name LIKE '%{$search}%' OR code LIKE '%{$search}%' OR description LIKE '%{$search}%')";
}
if ($category_id) {
    $where[] = "category_id = " . intval($category_id);
}

// دریافت لیست خدمات با اطلاعات دسته‌بندی
$services = $db->getAll("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE " . implode(' AND ', $where) . "
    ORDER BY p.id DESC
");

// دریافت لیست دسته‌بندی‌های خدمات برای فیلتر
$categories = $db->getAll("SELECT * FROM categories WHERE type = 'service' AND deleted_at IS NULL");

require_once '../../templates/header.php';
?>

<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">فهرست خدمات</h1>
        <a href="<?= url('src/products/add_service.php') ?>" class="btn btn-primary">
            افزودن خدمات جدید
        </a>
    </div>

    <!-- فیلترها -->
    <form method="GET" class="bg-gray-50 p-4 rounded-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">جستجو</label>
                <input type="text" name="search" value="<?= $search ?>" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                       placeholder="جستجو در نام، کد یا توضیحات...">
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
                    <a href="<?= url('src/products/services.php') ?>" class="btn btn-link mr-2">
                        پاک کردن فیلترها
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <!-- جدول خدمات -->
    <div class="overflow-x-auto">
        <table class="w-full table-striped">
            <thead>
                <tr>
                    <th class="py-3 px-4 text-right">کد</th>
                    <th class="py-3 px-4 text-right">نام خدمات</th>
                    <th class="py-3 px-4 text-right">دسته‌بندی</th>
                    <th class="py-3 px-4 text-right">قیمت</th>
                    <th class="py-3 px-4 text-right">مالیات</th>
                    <th class="py-3 px-4 text-right">تاریخ ثبت</th>
                    <th class="py-3 px-4 text-center">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($services)): ?>
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">
                            هیچ خدماتی یافت نشد.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($services as $service): ?>
                        <tr>
                            <td class="py-3 px-4"><?= $service['code'] ?></td>
                            <td class="py-3 px-4"><?= $service['name'] ?></td>
                            <td class="py-3 px-4"><?= $service['category_name'] ?? '---' ?></td>
                            <td class="py-3 px-4"><?= formatPrice($service['price']) ?></td>
                            <td class="py-3 px-4"><?= $service['tax_percent'] ?>%</td>
                            <td class="py-3 px-4"><?= formatDate($service['created_at']) ?></td>
                            <td class="py-3 px-4 text-center">
                                <a href="<?= url("src/products/edit_service.php?id={$service['id']}") ?>" 
                                   class="text-blue-600 hover:text-blue-800 mx-1" title="ویرایش">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= url("src/products/delete_service.php?id={$service['id']}") ?>" 
                                   class="text-red-600 hover:text-red-800 mx-1" 
                                   onclick="return confirm('آیا از حذف این خدمات اطمینان دارید؟')"
                                   title="حذف">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../templates/footer.php'; ?>