<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

// افزودن دسته‌بندی جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = $db->escape($_POST['name']);

    $sql = "INSERT INTO categories (name) VALUES ('$name')";
    if ($db->query($sql)) {
        echo "<p>دسته‌بندی با موفقیت افزوده شد.</p>";
    } else {
        echo "<p>خطا در افزودن دسته‌بندی.</p>";
    }
}

// حذف دسته‌بندی
if (isset($_GET['delete_category'])) {
    $id = $db->escape($_GET['delete_category']);
    $sql = "DELETE FROM categories WHERE id='$id'";
    if ($db->query($sql)) {
        echo "<p>دسته‌بندی با موفقیت حذف شد.</p>";
    } else {
        echo "<p>خطا در حذف دسته‌بندی.</p>";
    }
}

// نمایش دسته‌بندی‌ها
$sql = "SELECT * FROM categories";
$result = $db->query($sql);
?>

<h2>مدیریت دسته‌بندی‌ها</h2>

<!-- فرم افزودن دسته‌بندی -->
<form method="POST" action="">
    <label for="name">نام دسته‌بندی:</label>
    <input type="text" id="name" name="name" required>
    <button type="submit" name="add_category">افزودن دسته‌بندی</button>
</form>

<!-- جدول نمایش دسته‌بندی‌ها -->
<table>
    <thead>
        <tr>
            <th>نام دسته‌بندی</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td>
                <a href="?delete_category=<?php echo $row['id']; ?>" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
require_once '../templates/footer.php';
?>