<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

// افزودن محصول جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $db->escape($_POST['name']);
    $barcode = $db->escape($_POST['barcode']);
    $category_id = $db->escape($_POST['category_id']);
    $sale_price = $db->escape($_POST['sale_price']);
    $sale_description = $db->escape($_POST['sale_description']);
    $purchase_price = $db->escape($_POST['purchase_price']);
    $purchase_description = $db->escape($_POST['purchase_description']);
    $unit = $db->escape($_POST['unit']);
    $general_description = $db->escape($_POST['general_description']);
    $control_stock = isset($_POST['control_stock']) ? 1 : 0;
    $reorder_point = $db->escape($_POST['reorder_point']);
    $min_order = $db->escape($_POST['min_order']);
    $lead_time = $db->escape($_POST['lead_time']);
    
    // مدیریت آپلود تصویر
    $image_name = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../assets/images/products/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // آپلود موفق
        } else {
            echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در آپلود تصویر</p>";
        }
    }

    // ذخیره محصول در دیتابیس
    $sql = "INSERT INTO products (name, barcode, category_id, sale_price, sale_description, 
            purchase_price, purchase_description, unit, general_description, control_stock, 
            reorder_point, min_order, lead_time, image) 
            VALUES ('$name', '$barcode', '$category_id', '$sale_price', '$sale_description', 
            '$purchase_price', '$purchase_description', '$unit', '$general_description', 
            $control_stock, '$reorder_point', '$min_order', '$lead_time', " . 
            ($image_name ? "'$image_name'" : "NULL") . ")";

    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>محصول با موفقیت افزوده شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در افزودن محصول: " . $db->getConnection()->error . "</p>";
    }
}

// دریافت دسته‌بندی‌ها از دیتابیس
$categories = $db->query("SELECT * FROM categories");
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">افزودن محصول جدید</h2>

    <form method="POST" action="" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
        <!-- تصویر محصول -->
        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700">تصویر محصول:</label>
            <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full">
            <p class="text-sm text-gray-500">فرمت‌های مجاز: JPG، PNG، GIF</p>
        </div>

        <!-- نام محصول -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700">نام محصول:</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <!-- بارکد -->
        <div class="mb-6">
            <label for="barcode" class="block text-sm font-medium text-gray-700">بارکد:</label>
            <div class="flex gap-2">
                <input type="text" id="barcode" name="barcode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <button type="button" onclick="generateBarcode()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">تولید خودکار</button>
            </div>
        </div>

        <!-- دسته‌بندی -->
        <div class="mb-6">
            <label for="category_id" class="block text-sm font-medium text-gray-700">دسته‌بندی:</label>
            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="">انتخاب دسته‌بندی</option>
                <?php while ($category = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- تب‌ها -->
        <div class="mb-6">
            <div class="flex space-x-4 border-b border-gray-200 tab-container">
                <button type="button" data-tab="sales" class="tab-button active">فروش</button>
                <button type="button" data-tab="general" class="tab-button">عمومی</button>
                <button type="button" data-tab="inventory" class="tab-button">موجودی کالا</button>
                <button type="button" data-tab="tax" class="tab-button">مالیات</button>
            </div>

            <!-- محتوای تب فروش -->
            <div id="sales-tab" class="tab-content">
                <h3 class="text-xl font-bold mt-4">فروش</h3>
                <div class="mt-4">
                    <label for="sale_price" class="block text-sm font-medium text-gray-700">قیمت فروش (ریال):</label>
                    <input type="number" id="sale_price" name="sale_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="mt-4">
                    <label for="sale_description" class="block text-sm font-medium text-gray-700">توضیحات فروش:</label>
                    <textarea id="sale_description" name="sale_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
                <div class="mt-4">
                    <label for="purchase_price" class="block text-sm font-medium text-gray-700">قیمت خرید (ریال):</label>
                    <input type="number" id="purchase_price" name="purchase_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="mt-4">
                    <label for="purchase_description" class="block text-sm font-medium text-gray-700">توضیحات خرید:</label>
                    <textarea id="purchase_description" name="purchase_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
            </div>

            <!-- محتوای تب عمومی -->
            <div id="general-tab" class="tab-content hidden">
                <h3 class="text-xl font-bold mt-4">عمومی</h3>
                <div class="mt-4">
                    <label for="unit" class="block text-sm font-medium text-gray-700">واحد اصلی:</label>
                    <select id="unit" name="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="عدد">عدد</option>
                        <option value="کیلوگرم">کیلوگرم</option>
                        <option value="لیتر">لیتر</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label for="general_description" class="block text-sm font-medium text-gray-700">توضیحات:</label>
                    <textarea id="general_description" name="general_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>
            </div>

            <!-- محتوای تب موجودی کالا -->
            <div id="inventory-tab" class="tab-content hidden">
                <h3 class="text-xl font-bold mt-4">موجودی کالا</h3>
                <div class="mt-4">
                    <label for="control_stock" class="block text-sm font-medium text-gray-700">
                        <input type="checkbox" id="control_stock" name="control_stock" class="mr-2">کنترل موجودی
                    </label>
                    <p class="text-sm text-gray-500">اگر این گزینه فعال باشد، موجودی محصول کنترل می‌شود.</p>
                </div>
                <div class="mt-4">
                    <label for="reorder_point" class="block text-sm font-medium text-gray-700">نقطه سفارش:</label>
                    <input type="number" id="reorder_point" name="reorder_point" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="0">
                </div>
                <div class="mt-4">
                    <label for="min_order" class="block text-sm font-medium text-gray-700">حداقل سفارش:</label>
                    <input type="number" id="min_order" name="min_order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="0">
                </div>
                <div class="mt-4">
                    <label for="lead_time" class="block text-sm font-medium text-gray-700">زمان انتظار (روز):</label>
                    <input type="number" id="lead_time" name="lead_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="0">
                </div>
            </div>

            <!-- محتوای تب مالیات -->
            <div id="tax-tab" class="tab-content hidden">
                <h3 class="text-xl font-bold mt-4">مالیات</h3>
                <p class="text-gray-500">این بخش در آینده بروزرسانی خواهد شد.</p>
            </div>
        </div>

        <!-- دکمه‌ها -->
        <div class="flex gap-4">
            <button type="submit" name="add_product" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">ذخیره محصول</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../assets/js/tabs.js"></script>
<script>
// تابع تولید خودکار بارکد
function generateBarcode() {
    const barcode = Math.floor(100000000000 + Math.random() * 900000000000);
    document.getElementById('barcode').value = barcode;
}
</script>

<?php
require_once '../../templates/footer.php';
?>