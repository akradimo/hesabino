<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

// حذف دسته‌بندی
if (isset($_GET['delete_category'])) {
    $id = $db->escape($_GET['delete_category']);
    try {
        if ($db->deleteCategory($id)) {
            echo "<p class='bg-green-100 text-green-800 p-2 rounded'>دسته‌بندی با موفقیت حذف شد.</p>";
        } else {
            echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در حذف دسته‌بندی.</p>";
        }
    } catch (Exception $e) {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در حذف دسته‌بندی: {$e->getMessage()}</p>";
    }
}

// افزودن دسته‌بندی جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = $db->escape($_POST['name']);
    $parent_path = $db->escape($_POST['parent_path']);

    // اگر parent_path خالی است، آن را NULL قرار دهید
    if (empty($parent_path)) {
        $parent_path = '';
    }

    $path = $parent_path ? $parent_path . '/' . $name : $name;

    // بررسی وجود دسته‌بندی با نام مشابه در همان مسیر
    $check_sql = "SELECT * FROM categories WHERE path = '$path'";
    $check_result = $db->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>دسته‌بندی با این نام در این مسیر وجود دارد.</p>";
    } else {
        $sql = "INSERT INTO categories (name, path) VALUES ('$name', '$path')";
        if ($db->query($sql)) {
            echo "<p class='bg-green-100 text-green-800 p-2 rounded'>دسته‌بندی با موفقیت افزوده شد.</p>";
        } else {
            echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در افزودن دسته‌بندی.</p>";
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

$search = isset($_GET['search']) ? $_GET['search'] : '';
$categoriesTree = getCategoriesTree($search);
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
            <label for="parent_path" class="block text-sm font-medium text-gray-700">مسیر دسته‌بندی والد:</label>
            <select id="parent_path" name="parent_path" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">بدون والد</option>
                <?php foreach ($categoriesTree as $category): ?>
                    <option value="<?php echo $category['path']; ?>"><?php echo str_repeat('<i class="fa-solid fa-chevron-right"></i> ', substr_count($category['path'], '/')) . $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" name="add_category" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">افزودن دسته‌بندی</button>
    </form>

    <!-- فرم جستجو و فیلتر دسته‌بندی‌ها -->
    <form method="GET" action="" class="mb-6">
        <div class="flex items-center gap-4">
            <input type="text" name="search" placeholder="جستجو..." value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">جستجو</button>
        </div>
    </form>

    <!-- نمایش دسته‌بندی‌ها به صورت درختی -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold mb-4">دسته‌بندی‌ها</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نام دسته‌بندی</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مسیر</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مشاهده محصولات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                function renderCategoryTree($categories) {
                    $prevDepth = 0;
                    foreach ($categories as $category) {
                        $depth = substr_count($category['path'], '/');
                        if ($depth > $prevDepth) {
                            echo "<tr class='bg-gray-100'>";
                        } elseif ($depth < $prevDepth) {
                            echo str_repeat("</tr>", $prevDepth - $depth);
                        }
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . str_repeat('<i class="fa-solid fa-chevron-right"></i> ', $depth) . "{$category['name']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>{$category['path']}</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>";
                        echo "<a href='edit_category.php?id={$category['id']}' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'><i class='fa fa-edit'></i> ویرایش</a> ";
                        echo "<a href='?delete_category={$category['id']}' class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded' onclick=\"return confirm('آیا مطمئن هستید؟')\"><i class='fa fa-trash'></i> حذف</a>";
                        echo "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>";
                        echo "<a href='../products/products.php?category_id={$category['id']}' class='bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded'><i class='fa fa-eye'></i> مشاهده محصولات</a>";
                        echo "</td>";
                        echo "</tr>";
                        $prevDepth = $depth;
                    }
                    echo str_repeat("</tr>", $prevDepth);
                }
                renderCategoryTree($categoriesTree);
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleCategory(element) {
    var nextUl = element.parentElement.querySelector('ul');
    if (nextUl) {
        if (nextUl.style.display === 'none') {
            nextUl.style.display = 'block';
            element.textContent = '-';
        } else {
            nextUl.style.display = 'none';
            element.textContent = '+';
        }
    }
}
</script>

<?php
require_once '../../templates/footer.php';
?>