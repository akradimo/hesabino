<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

// افزودن دسته‌بندی جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = $db->escape($_POST['name']);
    $parent_id = $db->escape($_POST['parent_id']);

    // اگر parent_id خالی است، آن را NULL قرار دهید
    if (empty($parent_id)) {
        $parent_id = NULL;
    }

    $sql = "INSERT INTO categories (name, parent_id) VALUES ('$name', '$parent_id')";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>دسته‌بندی با موفقیت افزوده شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در افزودن دسته‌بندی.</p>";
    }
}

// دریافت دسته‌بندی‌ها از دیتابیس
$categories = $db->query("SELECT * FROM categories");
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">مدیریت دسته‌بندی‌ها</h2>

    <!-- فرم افزودن دسته‌بندی -->
    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700">نام دسته‌بندی:</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="mb-6">
            <label for="parent_id" class="block text-sm font-medium text-gray-700">دسته‌بندی والد:</label>
            <select id="parent_id" name="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">بدون والد</option>
                <?php while ($category = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" name="add_category" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">افزودن دسته‌بندی</button>
    </form>

    <!-- نمایش دسته‌بندی‌ها -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-right">نام دسته‌بندی</th>
                    <th class="px-6 py-3 text-right">والد</th>
                    <th class="px-6 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($category = $categories->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo $category['name']; ?></td>
                    <td class="px-6 py-4">
                        <?php
                        if ($category['parent_id']) {
                            $parent = $db->query("SELECT name FROM categories WHERE id='{$category['parent_id']}'")->fetch_assoc();
                            echo $parent['name'];
                        } else {
                            echo "بدون والد";
                        }
                        ?>
                    </td>
                    <td class="px-6 py-4">
                        <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="text-blue-600 hover:text-blue-800">ویرایش</a>
                        <a href="?delete_category=<?php echo $category['id']; ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
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