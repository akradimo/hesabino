<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

// افزودن کاربر جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $db->escape($_POST['username']);
    $password = password_hash($db->escape($_POST['password']), PASSWORD_DEFAULT);
    $role = $db->escape($_POST['role']);

    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>کاربر با موفقیت افزوده شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در افزودن کاربر.</p>";
    }
}

// حذف کاربر
if (isset($_GET['delete_user'])) {
    $id = $db->escape($_GET['delete_user']);
    $sql = "DELETE FROM users WHERE id='$id'";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>کاربر با موفقیت حذف شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در حذف کاربر.</p>";
    }
}

// نمایش کاربران
$sql = "SELECT * FROM users";
$result = $db->query($sql);
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">مدیریت کاربران</h2>

    <!-- فرم افزودن کاربر -->
    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">نام کاربری:</label>
                <input type="text" id="username" name="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">رمز عبور:</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">نقش:</label>
                <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="user">کاربر عادی</option>
                    <option value="admin">مدیر</option>
                </select>
            </div>
        </div>
        <button type="submit" name="add_user" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">افزودن کاربر</button>
    </form>

    <!-- جدول نمایش کاربران -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-right">نام کاربری</th>
                    <th class="px-6 py-3 text-right">نقش</th>
                    <th class="px-6 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo $row['username']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['role']; ?></td>
                    <td class="px-6 py-4">
                        <a href="?delete_user=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
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