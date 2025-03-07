<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

$category_id = isset($_GET['category_id']) ? $db->escape($_GET['category_id']) : null;
$search = isset($_GET['search']) ? $db->escape($_GET['search']) : '';

$sql = "SELECT products.*, categories.name as category_name 
        FROM products 
        LEFT JOIN categories ON products.category_id = categories.id";
        
if ($category_id) {
    $sql .= " WHERE category_id = '$category_id'";
}
if ($search) {
    $sql .= $category_id ? " AND " : " WHERE ";
    $sql .= "(products.name LIKE '%$search%' OR categories.name LIKE '%$search%')";
}

$result = $db->query($sql);
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">لیست محصولات</h2>

    <form method="GET" action="" class="mb-6">
        <div class="flex items-center gap-4">
            <input type="text" name="search" placeholder="جستجو..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">جستجو</button>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-right">تصویر</th>
                    <th class="px-6 py-3 text-right">نام محصول</th>
                    <th class="px-6 py-3 text-right">دسته‌بندی</th>
                    <th class="px-6 py-3 text-right">قیمت</th>
                    <th class="px-6 py-3 text-right">موجودی</th>
                    <th class="px-6 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4">
                        <?php if ($row['image']): ?>
                            <img src="../../assets/images/products/<?php echo $row['image']; ?>" alt="تصویر محصول" class="w-16 h-16 object-cover">
                        <?php else: ?>
                            <span>بدون تصویر</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4"><?php echo $row['name']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['category_name']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['price']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['initial_stock']; ?></td>
                    <td class="px-6 py-4">
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800">ویرایش</a>
                        <a href="?delete_product=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once '../../templates/footer.php';
?>