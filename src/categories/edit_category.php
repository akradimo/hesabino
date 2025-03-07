<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

if (!isset($_GET['id'])) {
    header("Location: categories.php");
    exit();
}

$id = $db->escape($_GET['id']);
$sql = "SELECT * FROM categories WHERE id='$id'";
$result = $db->query($sql);
$category = $result->fetch_assoc();

if (!$category) {
    echo "<p class='bg-red-100 text-red-800 p-2 rounded'>دسته‌بندی یافت نشد.</p>";
    exit();
}

// ویرایش دسته‌بندی
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_category'])) {
    $name = $db->escape($_POST['name']);
    $parent_id = $db->escape($_POST['parent_id']);

    $sql = "UPDATE categories SET name='$name', parent_id='$parent_id' WHERE id='$id'";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>دسته‌بندی با موفقیت ویرایش شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در ویرایش دسته‌بندی.</p>";
    }
}

// دریافت دسته‌بندی‌ها از دیتابیس
$categories = $db->query("SELECT * FROM categories");
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">ویرایش دسته‌بندی</h2>

    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700">نام دسته‌بندی:</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="<?php echo $category['name']; ?>" required>
        </div>

        <div class="mb-6">
            <label for="parent_id" class="block text-sm font-medium text-gray-700">دسته‌بندی والد:</label>
            <select id="parent_id" name="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">بدون والد</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $category['parent_id'] ? 'selected' : ''; ?>><?php echo $cat['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" name="edit_category" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">ذخیره تغییرات</button>
    </form>
</div>

<?php
require_once '../../templates/footer.php';
?>