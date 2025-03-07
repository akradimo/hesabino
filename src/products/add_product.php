<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $fields = [
        'product_code',
        'product_type',
        'name',
        'barcode',
        'description',
        'main_unit',
        'sub_unit',
        'conversion_factor',
        'stock_control',
        'reorder_point',
        'lead_time',
        'min_order',
        'sales_tax_included',
        'sales_tax_rate',
        'purchase_tax_included',
        'purchase_tax_rate',
        'sale_price',
        'sale_description',
        'purchase_price'
    ];

    $data = [];
    foreach ($fields as $field) {
        if ($field == 'stock_control' || $field == 'sales_tax_included' || $field == 'purchase_tax_included') {
            $data[$field] = isset($_POST[$field]) ? 1 : 0;
        } else {
            $data[$field] = $db->escape($_POST[$field] ?? '');
        }
    }

    // آپلود تصویر
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../assets/images/products/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $data['image'] = $image_name;
        }
    }

    // ساخت کوئری INSERT
    $columns = implode(', ', array_keys($data));
    $values = "'" . implode("', '", $data) . "'";
    $sql = "INSERT INTO products ($columns) VALUES ($values)";

    if ($db->query($sql)) {
        echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4' role='alert'>
                <strong class='font-bold'>موفق!</strong>
                <span class='block sm:inline'> محصول با موفقیت اضافه شد.</span>
              </div>";
    } else {
        echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4' role='alert'>
                <strong class='font-bold'>خطا!</strong>
                <span class='block sm:inline'> خطا در افزودن محصول: " . $db->getConnection()->error . "</span>
              </div>";
    }
}

$categories = $db->query("SELECT * FROM categories");
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">افزودن محصول جدید</h2>

    <form method="POST" action="" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
        <!-- اطلاعات اصلی -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="product_code" class="block text-sm font-medium text-gray-700">کد محصول:</label>
                <input type="text" id="product_code" name="product_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            
            <div>
                <label for="product_type" class="block text-sm font-medium text-gray-700">نوع کالا:</label>
                <select id="product_type" name="product_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="کالا">کالا</option>
                    <option value="خدمات">خدمات</option>
                </select>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">نام محصول:</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div>
                <label for="barcode" class="block text-sm font-medium text-gray-700">بارکد:</label>
                <div class="flex gap-2">
                    <input type="text" id="barcode" name="barcode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <button type="button" onclick="generateBarcode()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">تولید خودکار</button>
                </div>
            </div>
        </div>

        <!-- تب‌ها -->
        <div class="mb-6">
            <div class="flex border-b border-gray-200 mb-4">
                <button type="button" data-tab="general" class="tab-button px-4 py-2 border-b-2 border-blue-500">عمومی</button>
                <button type="button" data-tab="units" class="tab-button px-4 py-2">واحدها</button>
                <button type="button" data-tab="inventory" class="tab-button px-4 py-2">موجودی</button>
                <button type="button" data-tab="pricing" class="tab-button px-4 py-2">قیمت‌گذاری</button>
                <button type="button" data-tab="tax" class="tab-button px-4 py-2">مالیات</button>
            </div>

            <!-- تب عمومی -->
            <div id="general-tab" class="tab-content">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">توضیحات:</label>
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">تصویر محصول:</label>
                        <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full">
                    </div>
                </div>
            </div>

            <!-- تب واحدها -->
            <div id="units-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="main_unit" class="block text-sm font-medium text-gray-700">واحد اصلی:</label>
                        <select id="main_unit" name="main_unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="عدد">عدد</option>
                            <option value="کیلوگرم">کیلوگرم</option>
                            <option value="متر">متر</option>
                            <option value="لیتر">لیتر</option>
                        </select>
                    </div>
                    <div>
                        <label for="sub_unit" class="block text-sm font-medium text-gray-700">واحد فرعی:</label>
                        <select id="sub_unit" name="sub_unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">بدون واحد فرعی</option>
                            <option value="عدد">عدد</option>
                            <option value="کیلوگرم">کیلوگرم</option>
                            <option value="متر">متر</option>
                            <option value="لیتر">لیتر</option>
                        </select>
                    </div>
                    <div>
                        <label for="conversion_factor" class="block text-sm font-medium text-gray-700">ضریب تبدیل:</label>
                        <input type="number" id="conversion_factor" name="conversion_factor" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
            </div>

            <!-- تب موجودی -->
            <div id="inventory-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="stock_control" name="stock_control" class="rounded border-gray-300 text-blue-600 shadow-sm">
                            <span class="ml-2">کنترل موجودی</span>
                        </label>
                    </div>
                    <div>
                        <label for="reorder_point" class="block text-sm font-medium text-gray-700">نقطه سفارش:</label>
                        <input type="number" id="reorder_point" name="reorder_point" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="lead_time" class="block text-sm font-medium text-gray-700">زمان انتظار (روز):</label>
                        <input type="number" id="lead_time" name="lead_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="min_order" class="block text-sm font-medium text-gray-700">حداقل سفارش:</label>
                        <input type="number" id="min_order" name="min_order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
            </div>

            <!-- تب قیمت‌گذاری -->
            <div id="pricing-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700">قیمت فروش:</label>
                        <input type="number" id="sale_price" name="sale_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="purchase_price" class="block text-sm font-medium text-gray-700">قیمت خرید:</label>
                        <input type="number" id="purchase_price" name="purchase_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="sale_description" class="block text-sm font-medium text-gray-700">توضیحات فروش:</label>
                        <textarea id="sale_description" name="sale_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>
                </div>
            </div>

            <!-- تب مالیات -->
            <div id="tax-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="sales_tax_included" name="sales_tax_included" class="rounded border-gray-300 text-blue-600 shadow-sm">
                            <span class="ml-2">مشمول مالیات فروش</span>
                        </label>
                        <div class="mt-2">
                            <label for="sales_tax_rate" class="block text-sm font-medium text-gray-700">درصد مالیات فروش:</label>
                            <input type="number" id="sales_tax_rate" name="sales_tax_rate" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="purchase_tax_included" name="purchase_tax_included" class="rounded border-gray-300 text-blue-600 shadow-sm">
                            <span class="ml-2">مشمول مالیات خرید</span>
                        </label>
                        <div class="mt-2">
                            