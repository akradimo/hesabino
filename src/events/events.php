<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

// افزودن رویداد جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $title = $db->escape($_POST['title']);
    $description = $db->escape($_POST['description']);
    $event_date = $db->escape($_POST['event_date']);

    $sql = "INSERT INTO events (title, description, event_date) VALUES ('$title', '$description', '$event_date')";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>رویداد با موفقیت ثبت شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در ثبت رویداد.</p>";
    }
}

// حذف رویداد
if (isset($_GET['delete_event'])) {
    $id = $db->escape($_GET['delete_event']);
    $sql = "DELETE FROM events WHERE id='$id'";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>رویداد با موفقیت حذف شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در حذف رویداد.</p>";
    }
}

// نمایش رویدادها
$sql = "SELECT * FROM events ORDER BY event_date ASC";
$result = $db->query($sql);
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">مدیریت رویدادها</h2>

    <!-- فرم افزودن رویداد -->
    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">عنوان رویداد:</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">توضیحات:</label>
                <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
            </div>
            <div>
                <label for="event_date" class="block text-sm font-medium text-gray-700">تاریخ رویداد:</label>
                <input type="date" id="event_date" name="event_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
        </div>
        <button type="submit" name="add_event" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">ثبت رویداد</button>
    </form>

    <!-- جدول نمایش رویدادها -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-right">عنوان</th>
                    <th class="px-6 py-3 text-right">توضیحات</th>
                    <th class="px-6 py-3 text-right">تاریخ رویداد</th>
                    <th class="px-6 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo $row['title']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['description']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['event_date']; ?></td>
                    <td class="px-6 py-4">
                        <a href="?delete_event=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
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