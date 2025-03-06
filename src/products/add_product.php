<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

// افزودن محصول جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $barcode = $db->escape($_POST['barcode']);
    $category = $db->escape($_POST['category']);
    $price_type = $db->escape($_POST['price_type']);
    $price = $db->escape($_POST['price']);
    $currency = $db->escape($_POST['currency']);
    $initial_stock = $db->escape($_POST['initial_stock']);
    $unit = $db->escape($_POST['unit']);
    $description = $db->escape($_POST['description']);
    $min_order = $db->escape($_POST['min_order']);
    $taxable = isset($_POST['taxable']) ? 1 : 0;
    $tax_rate = $db->escape($_POST['tax_rate']);

    $sql = "INSERT INTO products (barcode, category, price_type, price, currency, initial_stock, unit, description, min_order, taxable, tax_rate) 
            VALUES ('$barcode', '$category', '$price_type', '$price', '$currency', '$initial_stock', '$unit', '$description', '$min_order', '$taxable', '$tax_rate')";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>محصول با موفقیت افزوده شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در افزودن محصول.</p>";
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">افزودن محصول جدید</h2>

    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md">
        <!-- بخش بارکد -->
        <div class="mb-6">
            <label for="barcode" class="block text-sm font-medium text-gray-700">بارکد:</label>
            <div class="flex gap-2">
                <input type="text" id="barcode" name="barcode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <button type="button" onclick="generateBarcode()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">تولید خودکار</button>
            </div>
        </div>

        <!-- بخش دسته‌بندی -->
        <div class="mb-6">
            <label for="category" class="block text-sm font-medium text-gray-700">دسته‌بندی:</label>
            <input type="text" id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <!-- بخش قیمت فروش -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">قیمت فروش:</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <select id="price_type" name="price_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="عمده">عمده</option>
                    <option value="پرسنل">پرسنل</option>
                </select>
                <input type="number" id="price" name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <select id="currency" name="currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="تومان">تومان</option>
                    <option value="دلار">دلار</option>
                    <option value="یورو">یورو</option>
                </select>
            </div>
        </div>

        <!-- بخش موجودی اولیه -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">موجودی اولیه:</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="number" id="initial_stock" name="initial_stock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <select id="unit" name="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="عدد">عدد</option>
                    <option value="کیلوگرم">کیلوگرم</option>
                    <option value="لیتر">لیتر</option>
                </select>
            </div>
        </div>

        <!-- تب‌ها -->
        <div class="mb-6">
            <div class="flex space-x-4 border-b border-gray-200">
                <button type="button" onclick="showTab('sales')" class="tab-button active">فروش</button>
                <button type="button" onclick="showTab('inventory')" class="tab-button">موجودی</button>
                <button type="button" onclick="showTab('tax')" class="tab-button">مالیات</button>
            </div>

            <!-- تب فروش -->
            <div id="sales-tab" class="tab-content">
                <label for="description" class="block text-sm font-medium text-gray-700 mt-4">توضیحات فروش:</label>
                <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
            </div>

            <!-- تب موجودی -->
            <div id="inventory-tab" class="tab-content hidden">
                <label for="min_order" class="block text-sm font-medium text-gray-700 mt-4">حداقل سفارش:</label>
                <input type="number" id="min_order" name="min_order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <p class="text-red-500 text-sm mt-2 hidden" id="min-order-warning">توجه: حداقل سفارش کمتر از مقدار مجاز است!</p>
            </div>

            <!-- تب مالیات -->
            <div id="tax-tab" class="tab-content hidden">
                <div class="mt-4">
                    <label for="taxable" class="block text-sm font-medium text-gray-700">مشمول مالیات:</label>
                    <input type="checkbox" id="taxable" name="taxable" class="mt-1">
                </div>
                <div class="mt-4">
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700">درصد مالیات:</label>
                    <input type="number" id="tax_rate" name="tax_rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
        </div>

        <!-- دکمه‌ها -->
        <div class="flex gap-4">
            <button type="button" onclick="printBarcode()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">چاپ بارکد</button>
            <button type="submit" name="add_product" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">ذخیره و جدید</button>
        </div>
    </form>
</div>

<script>
// تابع تولید خودکار بارکد
function generateBarcode() {
    const barcode = Math.floor(100000000000 + Math.random() * 900000000000);
    document.getElementById('barcode').value = barcode;
}

// تابع نمایش تب‌ها
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(button => button.classList.remove('active'));
    document.getElementById(`${tabName}-tab`).classList.remove('hidden');
    document.querySelector(`[onclick="showTab('${tabName}')"]`).classList.add('active');
}

// تابع چاپ بارکد
function printBarcode() {
    const barcode = document.getElementById('barcode').value;
    if (barcode) {
        alert(`چاپ بارکد: ${barcode}`);
        // اینجا می‌توانید کد چاپ بارکد را اضافه کنید.
    } else {
        alert('لطفاً ابتدا بارکد را وارد کنید.');
    }
}
</script>

<?php
require_once '../../templates/footer.php';
?>