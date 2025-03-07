<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = $db->escape($_GET['id']);
$sql = "SELECT * FROM products WHERE id='$id'";
$result = $db->query($sql);
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p class='bg-red-100 text-red-800 p-2 rounded'>محصول یافت نشد.</p>";
    exit();
}

// ویرایش محصول
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product'])) {
    $name = $db->escape($_POST['name']);
    $barcode = $db->escape($_POST['barcode']);
    $category_id = $db->escape($_POST['category_id']);
    $price = $db->escape($_POST['price']);
    $initial_stock = $db->escape($_POST['initial_stock']);
    $description = $db->escape($_POST['description']);

    // آپلود تصویر
    $image_name = $product['image'];
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $image_path = '../../assets/images/products/' . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $sql = "UPDATE products SET name='$name', barcode='$barcode', category_id='$category_id', price='$price', initial_stock='$initial_stock', description='$description', image='$image_name' WHERE id='$id'";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>محصول با موفقیت ویرایش شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در ویرایش محصول.</p>";
    }
}

// دریافت دسته‌بندی‌ها از دیتابیس
$categories = $db->query("SELECT * FROM categories");
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">ویرایش محصول</h2>

    <form method="POST" action="" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- نام محصول -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">نام محصول:</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="<?php echo $product['name']; ?>" required>
            </div>

            <!-- بارکد -->
            <div>
                <label for="barcode" class="block text-sm font-medium text-gray-700">بارکد:</label>
                <input type="text" id="barcode" name="barcode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="<?php echo $product['barcode']; ?>">
            </div>

            <!-- دسته‌بندی -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">دسته‌بندی:</label>
                <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">انتخاب دسته‌بندی</option>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- قیمت -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">قیمت:</label>
                <input type="number" id="price" name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="<?php echo $product['price']; ?>" required>
            </div>

            <!-- موجودی اولیه -->
            <div>
                <label for="initial_stock" class="block text-sm font-medium text-gray-700">موجودی اولیه:</label>
                <input type="number" id="initial_stock" name="initial_stock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="<?php echo $product['initial_stock']; ?>" required>
            </div>

            <!-- تصویر -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">تصویر محصول:</label>
                <input type="file" id="image" name="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <?php if ($product['image']): ?>
                    <img src="../../assets/images/products/<?php echo $product['image']; ?>" alt="تصویر محصول" class="mt-2 w-32 h-32 object-cover">
                <?php endif; ?>
            </div>
        </div>

        <!-- توضیحات -->
        <div class="mt-4">
            <label for="description" class="block text-sm font-medium text-gray-700">توضیحات:</label>
            <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo $product['description']; ?></textarea>
        </div>

        <!-- دکمه‌ها -->
        <div class="mt-6">
            <button type="submit" name="edit_product" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">ذخیره تغییرات</button>
        </div>
    </form>
</div>

<?php
require_once '../../templates/footer.php';
?>