<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

// افزودن خدمت جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $name = $db->escape($_POST['name']);
    $price = $db->escape($_POST['price']);

    $sql = "INSERT INTO services (name, price) VALUES ('$name', '$price')";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>خدمت با موفقیت افزوده شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در افزودن خدمت.</p>";
    }
}

// نمایش خدمات
$sql = "SELECT * FROM services";
$result = $db->query($sql);
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">مدیریت خدمات</h2>

    <!-- فرم افزودن خدمت -->
    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">نام خدمت:</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">قیمت:</label>
                <input type="number" id="price" name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
        </div>
        <button type="submit" name="add_service" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">افزودن خدمت</button>
    </form>

    <!-- جدول نمایش خدمات -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-right">نام خدمت</th>
                    <th class="px-6 py-3 text-right">قیمت</th>
                    <th class="px-6 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo $row['name']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['price']; ?></td>
                    <td class="px-6 py-4">
                        <a href="edit_service.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800">ویرایش</a>
                        <a href="?delete_service=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once '../templates/footer.php';
?>