<?php
require_once '../../includes/init.php';

$current_page = 'products-price-lists';
$page_title = 'لیست قیمت‌ها';

// دریافت همه لیست قیمت‌ها
$price_lists = $db->getAll("
    SELECT 
        pl.*,
        (SELECT COUNT(*) FROM price_list_items WHERE price_list_id = pl.id) as items_count,
        (SELECT MIN(created_at) FROM price_list_items WHERE price_list_id = pl.id) as first_item_date,
        (SELECT MAX(created_at) FROM price_list_items WHERE price_list_id = pl.id) as last_item_date
    FROM price_lists pl
    WHERE pl.deleted_at IS NULL
    ORDER BY pl.is_active DESC, pl.created_at DESC
");

// تغییر وضعیت فعال/غیرفعال
if (isset($_POST['toggle_status'])) {
    $id = intval($_POST['id']);
    $is_active = intval($_POST['is_active']);
    
    // اگر قرار است لیستی فعال شود، بقیه لیست‌ها غیرفعال می‌شوند
    if ($is_active) {
        $db->execute("UPDATE price_lists SET is_active = 0 WHERE id != ?", [$id]);
    }
    
    $db->execute(
        "UPDATE price_lists SET is_active = ?, updated_at = NOW() WHERE id = ?",
        [$is_active, $id]
    );
    
    setMessage('success', 'وضعیت لیست قیمت با موفقیت تغییر کرد');
    redirect('src/products/price_lists.php');
}

// حذف لیست قیمت
if (isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    
    try {
        $db->beginTransaction();
        
        // حذف آیتم‌های لیست قیمت
        $db->execute("DELETE FROM price_list_items WHERE price_list_id = ?", [$id]);
        
        // حذف لیست قیمت
        $db->execute(
            "UPDATE price_lists SET deleted_at = NOW() WHERE id = ?",
            [$id]
        );
        
        $db->commit();
        setMessage('success', 'لیست قیمت با موفقیت حذف شد');
    } catch (Exception $e) {
        $db->rollBack();
        setMessage('error', 'خطا در حذف لیست قیمت');
    }
    
    redirect('src/products/price_lists.php');
}

require_once '../../templates/header.php';
?>

<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">لیست قیمت‌ها</h1>
        <a href="<?= url('src/products/add_price_list.php') ?>" class="btn btn-primary">
            افزودن لیست قیمت جدید
        </a>
    </div>

    <?php if(hasSuccess()): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= getSuccess() ?>
        </div>
    <?php endif; ?>

    <?php if(hasError()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= getError() ?>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="w-full table-striped">
            <thead>
                <tr>
                    <th class="py-3 px-4 text-right">نام لیست</th>
                    <th class="py-3 px-4 text-right">تعداد آیتم‌ها</th>
                    <th class="py-3 px-4 text-right">اولین ثبت قیمت</th>
                    <th class="py-3 px-4 text-right">آخرین ثبت قیمت</th>
                    <th class="py-3 px-4 text-right">وضعیت</th>
                    <th class="py-3 px-4 text-right">تاریخ ایجاد</th>
                    <th class="py-3 px-4 text-center">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($price_lists)): ?>
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">
                            هیچ لیست قیمتی یافت نشد.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($price_lists as $list): ?>
                        <tr>
                            <td class="py-3 px-4">
                                <?= $list['name'] ?>
                                <?php if($list['description']): ?>
                                    <span class="text-gray-500 text-sm block">
                                        <?= $list['description'] ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4">
                                <?= number_format($list['items_count']) ?> مورد
                            </td>
                            <td class="py-3 px-4">
                                <?= $list['first_item_date'] ? formatDate($list['first_item_date']) : '---' ?>
                            </td>
                            <td class="py-3 px-4">
                                <?= $list['last_item_date'] ? formatDate($list['last_item_date']) : '---' ?>
                            </td>
                            <td class="py-3 px-4">
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="id" value="<?= $list['id'] ?>">
                                    <input type="hidden" name="is_active" value="<?= $list['is_active'] ? 0 : 1 ?>">
                                    <button type="submit" name="toggle_status" 
                                            class="<?= $list['is_active'] ? 'text-green-600' : 'text-gray-600' ?> hover:underline">
                                        <?= $list['is_active'] ? 'فعال' : 'غیرفعال' ?>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-4">
                                <?= formatDate($list['created_at']) ?>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="<?= url("src/products/edit_price_list.php?id={$list['id']}") ?>" 
                                   class="text-blue-600 hover:text-blue-800 mx-1" title="ویرایش">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="id" value="<?= $list['id'] ?>">
                                    <button type="submit" name="delete" 
                                            class="text-red-600 hover:text-red-800 mx-1"
                                            onclick="return confirm('آیا از حذف این لیست قیمت اطمینان دارید؟')"
                                            title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <a href="<?= url("src/products/price_list_items.php?id={$list['id']}") ?>" 
                                   class="text-green-600 hover:text-green-800 mx-1" title="مشاهده آیتم‌ها">
                                    <i class="fas fa-list"></i>
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