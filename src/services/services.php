<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

// نمایش محصولات
$sql = "SELECT * FROM products";
$result = $db->query($sql);
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">لیست محصولات</h2>

    <!-- فیلترها -->
    <div class="mb-4">
        <input type="text" id="search" placeholder="جستجوی سریع..." class="w-full p-2 rounded-md border-gray-300 shadow-sm">
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-right">نام</th>
                    <th class="px-6 py-3 text-right">کد</th>
                    <th class="px-6 py-3 text-right">دسته‌بندی</th>
                    <th class="px-6 py-3 text-right">قیمت</th>
                    <th class="px-6 py-3 text-right">موجودی</th>
                    <th class="px-6 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo $row['name']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['barcode']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['category']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['price']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['initial_stock']; ?></td>
                    <td class="px-6 py-4">
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800">ویرایش</a>
                        <a href="?delete_product=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
                        <a href="print_barcode.php?id=<?php echo $row['id']; ?>" class="text-green-600 hover:text-green-800">چاپ بارکد</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// فیلتر جستجو
document.getElementById('search').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(row => {
        const name = row.querySelector('td').textContent.toLowerCase();
        row.style.display = name.includes(searchValue) ? '' : 'none';
    });
});
</script>

<?php
require_once '../../templates/footer.php';
?>