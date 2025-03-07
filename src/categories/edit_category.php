<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

$id = $db->escape($_GET['id']);
$category = $db->query("SELECT * FROM categories WHERE id = '$id'")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_category'])) {
    $name = $db->escape($_POST['name']);
    $parent_path = $db->escape($_POST['parent_path']);
    
    // اگر parent_path خالی است، آن را NULL قرار دهید
    if (empty($parent_path)) {
        $parent_path = '';
    }

    $new_path = $parent_path ? $parent_path . '/' . $name : $name;

    // بررسی وجود دسته‌بندی با نام مشابه در همان مسیر
    $check_sql = "SELECT * FROM categories WHERE path = '$new_path' AND id != '$id'";
    $check_result = $db->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>دسته‌بندی با این نام در این مسیر وجود دارد.</p>";
    } else {
        $sql = "UPDATE categories SET name = '$name', path = '$new_path' WHERE id = '$id'";
        if ($db->query($sql)) {
            echo "<p class='bg-green-100 text-green-800 p-2 rounded'>دسته‌بندی با موفقیت ویرایش شد.</p>";
            $category['name'] = $name;
            $category['path'] = $new_path;
        } else {
            echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در ویرایش دسته‌بندی.</p>";
        }
    }
}

// تابع برای دریافت دسته‌بندی‌ها به صورت درختی
function getCategoriesTree($search = '') {
    global $db;
    $categories = [];
    $sql = "SELECT * FROM categories";
    if ($search) {
        $search = $db->escape($search);
        $sql .= " WHERE name LIKE '%$search%'";
    }
    $sql .= " ORDER BY path ASC";
    $result = $db->query($sql);

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    return $categories;
}

$categoriesTree = getCategoriesTree();
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">ویرایش دسته‌بندی</h2>

    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700">نام دسته‌بندی:</label>
            <input type="text" id="name" name="name" value="<?php echo $category['name']; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="mb-6">
            <label for="parent_path" class="block text-sm font-medium text-gray-700">مسیر دسته‌بندی والد:</label>
            <select id="parent_path" name="parent_path" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">بدون والد</option>
                <?php foreach ($categoriesTree as $cat): ?>
                    <option value="<?php echo $cat['path']; ?>" <?php if ($cat['path'] == dirname($category['path'])) echo 'selected'; ?>><?php echo str_repeat('<i class="fa fa-folder"></i> ', substr_count($cat['path'], '/')) . $cat['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" name="edit_category" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">ویرایش دسته‌بندی</button>
    </form>
</div>

<?php
require_once '../../templates/footer.php';
?>