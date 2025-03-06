<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

// افزودن بازخورد جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_feedback'])) {
    $customer_name = $db->escape($_POST['customer_name']);
    $message = $db->escape($_POST['message']);

    $sql = "INSERT INTO feedbacks (customer_name, message) VALUES ('$customer_name', '$message')";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>بازخورد با موفقیت ثبت شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در ثبت بازخورد.</p>";
    }
}

// حذف بازخورد
if (isset($_GET['delete_feedback'])) {
    $id = $db->escape($_GET['delete_feedback']);
    $sql = "DELETE FROM feedbacks WHERE id='$id'";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>بازخورد با موفقیت حذف شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در حذف بازخورد.</p>";
    }
}

// نمایش بازخوردها
$sql = "SELECT * FROM feedbacks ORDER BY created_at DESC";
$result = $db->query($sql);
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">مدیریت بازخورد مشتریان</h2>

    <!-- فرم افزودن بازخورد -->
    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="customer_name" class="block text-sm font-medium text-gray-700">نام مشتری:</label>
                <input type="text" id="customer_name" name="customer_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">پیام:</label>
                <textarea id="message" name="message" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
            </div>
        </div>
        <button type="submit" name="add_feedback" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">ثبت بازخورد</button>
    </form>

    <!-- جدول نمایش بازخوردها -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-right">نام مشتری</th>
                    <th class="px-6 py-3 text-right">پیام</th>
                    <th class="px-6 py-3 text-right">تاریخ ثبت</th>
                    <th class="px-6 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo $row['customer_name']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['message']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['created_at']; ?></td>
                    <td class="px-6 py-4">
                        <a href="?delete_feedback=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
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