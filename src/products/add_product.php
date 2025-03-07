<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

// بررسی درخواست POST برای افزودن محصول جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    // تعریف فیلدهای مجاز برای پردازش
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
        'purchase_price',
        'purchase_description'
    ];

    // آماده‌سازی داده‌ها برای ذخیره
    $data = [];
    foreach ($fields as $field) {
        // پردازش فیلدهای چک‌باکس
        if (in_array($field, ['stock_control', 'sales_tax_included', 'purchase_tax_included'])) {
            $data[$field] = isset($_POST[$field]) ? 1 : 0;
        }
        // پردازش فیلدهای عددی
        elseif (in_array($field, ['sale_price', 'purchase_price', 'sales_tax_rate', 'purchase_tax_rate', 'conversion_factor'])) {
            $data[$field] = !empty($_POST[$field]) ? floatval($_POST[$field]) : 0;
        }
        // پردازش فیلدهای عددی صحیح
        elseif (in_array($field, ['reorder_point', 'lead_time', 'min_order'])) {
            $data[$field] = !empty($_POST[$field]) ? intval($_POST[$field]) : 0;
        }
        // پردازش سایر فیلدها
        else {
            $data[$field] = $db->escape($_POST[$field] ?? '');
        }
    }

    // پردازش و آپلود تصویر
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../assets/images/products/';
        // ایجاد پوشه در صورت عدم وجود
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // تولید نام یکتا برای تصویر
        $file_info = pathinfo($_FILES['image']['name']);
        $extension = strtolower($file_info['extension']);
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($extension, $allowed_types)) {
            $image_name = uniqid('product_') . '.' . $extension;
            $image_path = $upload_dir . $image_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                $data['image'] = $image_name;
            }
        }
    }

    // اضافه کردن تاریخ ایجاد و بروزرسانی
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    // ساخت و اجرای کوئری SQL
    $columns = implode(', ', array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";
    $sql = "INSERT INTO products ($columns) VALUES ($values)";

    // اجرای کوئری و نمایش نتیجه
    if ($db->query($sql)) {
        $message = [
            'type' => 'success',
            'text' => 'محصول جدید با موفقیت اضافه شد.'
        ];
    } else {
        $message = [
            'type' => 'error',
            'text' => 'خطا در افزودن محصول: ' . $db->getConnection()->error
        ];
    }
}

// دریافت لیست دسته‌بندی‌ها
$categories = $db->query("SELECT * FROM categories ORDER BY name ASC");
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">افزودن محصول جدید</h2>
        <a href="index.php" class="btn btn-secondary">
            بازگشت به لیست محصولات
        </a>
    </div>

    <?php if (isset($message)): ?>
        <div class="alert alert-<?php echo $message['type']; ?>">
            <?php echo $message['text']; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="card" onsubmit="return validateProductForm()">
        <div id="form-errors" class="mb-4"></div>

        <!-- منوی تب‌ها -->
        <div class="tabs-container mb-6">
            <div class="flex border-b border-gray-200">
                <button type="button" data-tab="general" class="tab-button active">
                    <span class="ml-2">اطلاعات کلی</span>
                </button>
                <button type="button" data-tab="units" class="tab-button">
                    <span class="ml-2">واحدها</span>
                </button>
                <button type="button" data-tab="inventory" class="tab-button">
                    <span class="ml-2">موجودی</span>
                </button>
                <button type="button" data-tab="pricing" class="tab-button">
                    <span class="ml-2">قیمت‌گذاری</span>
                </button>
                <button type="button" data-tab="tax" class="tab-button">
                    <span class="ml-2">مالیات</span>
                </button>
            </div>

            <!-- محتوای تب‌ها -->
            <div class="tab-contents mt-6">
                <!-- تب اطلاعات کلی -->
                <div id="general-tab" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="product_code" class="form-label">کد محصول</label>
                            <input type="text" id="product_code" name="product_code" class="form-input" required>
                        </div>
                        
                        <div>
                            <label for="product_type" class="form-label">نوع محصول</label>
                            <select id="product_type" name="product_type" class="form-select">
                                <option value="کالا">کالا</option>
                                <option value="خدمات">خدمات</option>
                            </select>
                        </div>

                        <div>
                            <label for="name" class="form-label">نام محصول</label>
                            <input type="text" id="name" name="name" class="form-input" required>
                        </div>

                        <div>
                            <label for="barcode" class="form-label">بارکد</label>
                            <div class="flex gap-2">
                                <input type="text" id="barcode" name="barcode" class="form-input">
                                <button type="button" class="btn btn-secondary" data-action="generate-barcode">
                                    تولید خودکار
                                </button>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="form-label">توضیحات</label>
                            <textarea id="description" name="description" rows="4" class="form-input"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label for="image" class="form-label">تصویر محصول</label>
                            <input type="file" id="image" name="image" accept="image/*" class="form-input">
                        </div>
                    </div>
                </div>

                <!-- تب واحدها -->
                <div id="units-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="main_unit" class="form-label">واحد اصلی</label>
                            <select id="main_unit" name="main_unit" class="form-select" required>
                                <option value="">انتخاب کنید</option>
                                <option value="عدد">عدد</option>
                                <option value="کیلوگرم">کیلوگرم</option>
                                <option value="متر">متر</option>
                                <option value="لیتر">لیتر</option>
                            </select>
                        </div>

                        <div>
                            <label for="sub_unit" class="form-label">واحد فرعی</label>
                            <select id="sub_unit" name="sub_unit" class="form-select">
                                <option value="">بدون واحد فرعی</option>
                                <option value="عدد">عدد</option>
                                <option value="کیلوگرم">کیلوگرم</option>
                                <option value="متر">متر</option>
                                <option value="لیتر">لیتر</option>
                            </select>
                        </div>

                        <div>
                            <label for="conversion_factor" class="form-label">ضریب تبدیل</label>
                            <input type="number" id="conversion_factor" name="conversion_factor" 
                                   class="form-input" step="0.01" min="0">
                        </div>
                    </div>
                </div>

                <!-- تب موجودی -->
                <div id="inventory-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="stock_control" name="stock_control" class="form-checkbox">
                                <span class="mr-2">کنترل موجودی</span>
                            </label>
                        </div>

                        <div>
                            <label for="reorder_point" class="form-label">نقطه سفارش</label>
                            <input type="number" id="reorder_point" name="reorder_point" class="form-input" min="0">
                        </div>

                        <div>
                            <label for="lead_time" class="form-label">زمان انتظار (روز)</label>
                            <input type="number" id="lead_time" name="lead_time" class="form-input" min="0">
                        </div>

                        <div>
                            <label for="min_order" class="form-label">حداقل سفارش</label>
                            <input type="number" id="min_order" name="min_order" class="form-input" min="0">
                        </div>
                    </div>
                </div>

                <!-- تب قیمت‌گذاری -->
                <div id="pricing-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="sale_price" class="form-label">قیمت فروش</label>
                            <input type="number" id="sale_price" name="sale_price" class="form-input" min="0">
                        </div>

                        <div>
                            <label for="purchase_price" class="form-label">قیمت خرید</label>
                            <input type="number" id="purchase_price" name="purchase_price" class="form-input" min="0">
                        </div>

                        <div class="md:col-span-2">
                            <label for="sale_description" class="form-label">توضیحات فروش</label>
                            <textarea id="sale_description" name="sale_description" rows="3" class="form-input"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label for="purchase_description" class="form-label">توضیحات خرید</label>
                            <textarea id="purchase_description" name="purchase_description" rows="3" class="form-input"></textarea>
                        </div>
                    </div>
                </div>

                <!-- تب مالیات -->
                <div id="tax-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">مالیات فروش</label>
                            <div class="space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="sales_tax_included" name="sales_tax_included" class="form-checkbox">
                                    <span class="mr-2">مشمول مالیات فروش</span>
                                </label>
                                <div>
                                    <label for="sales_tax_rate" class="form-label">درصد مالیات فروش</label>
                                    <input type="number" id="sales_tax_rate" name="sales_tax_rate" 
                                           class="form-input" step="0.01" min="0" max="100">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">مالیات خرید</label>
                            <div class="space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="purchase_tax_included" name="purchase_tax_included" class="form-checkbox">
                                    <span class="mr-2">مشمول مالیات خرید</span>
                                </label>
                                <div>
                                    <label for="purchase_tax_rate" class="form-label">درصد مالیات خرید</label>
                                    <input type="number" id="purchase_tax_rate" name="purchase_tax_rate" 
                                           class="form-input" step="0.01" min="0" max="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- دکمه‌های فرم -->
            <div class="flex justify-end gap-4 mt-6">
                <button type="submit" name="add_product" class="btn btn-primary">
                    <i class="fas fa-save ml-2"></i>
                    ذخیره محصول
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-times ml-2"></i>
                    انصراف
                </a>
            </div>
        </form>
    </div>
</div>

<!-- کد جاوااسکریپت محاسبات مالی -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // محاسبه مالیات و قیمت نهایی به صورت خودکار
    const priceFields = ['sale_price', 'sales_tax_rate', 'sales_tax_included'];
    priceFields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.addEventListener('change', محاسبه_مالیات_و_قیمت_نهایی);
        }
    });
});

// تابع محاسبه مالیات و قیمت نهایی
function محاسبه_مالیات_و_قیمت_نهایی() {
    const قیمت_فروش = parseFloat(document.getElementById('sale_price').value) || 0;
    const درصد_مالیات = parseFloat(document.getElementById('sales_tax_rate').value) || 0;
    const شامل_مالیات = document.getElementById('sales_tax_included').checked;
    
    let مبلغ_مالیات = 0;
    let قیمت_نهایی = 0;
    
    if (شامل_مالیات) {
        مبلغ_مالیات = (قیمت_فروش * درصد_مالیات) / (100 + درصد_مالیات);
        قیمت_نهایی = قیمت_فروش;
    } else {
        مبلغ_مالیات = (قیمت_فروش * درصد_مالیات) / 100;
        قیمت_نهایی = قیمت_فروش + مبلغ_مالیات;
    }
    
    // نمایش نتایج
    if (document.getElementById('tax_amount')) {
        document.getElementById('tax_amount').value = Math.round(مبلغ_مالیات).toLocaleString('fa-IR');
    }
    if (document.getElementById('final_price')) {
        document.getElementById('final_price').value = Math.round(قیمت_نهایی).toLocaleString('fa-IR');
    }
}

// تابع تولید بارکد تصادفی
function تولید_بارکد() {
    const پیشوند = '200'; // پیشوند برای بارکدهای داخلی
    const بخش_تصادفی = Math.floor(Math.random() * 10000000000).toString().padStart(10, '0');
    document.getElementById('barcode').value = پیشوند + بخش_تصادفی;
}

// تابع اعتبارسنجی فرم
function اعتبارسنجی_فرم() {
    const فیلدهای_اجباری = [
        { id: 'name', پیام: 'نام محصول را وارد کنید' },
        { id: 'product_code', پیام: 'کد محصول را وارد کنید' },
        { id: 'main_unit', پیام: 'واحد اصلی را انتخاب کنید' }
    ];
    
    let معتبر_است = true;
    const محل_خطاها = document.getElementById('form-errors');
    محل_خطاها.innerHTML = '';
    
    فیلدهای_اجباری.forEach(فیلد => {
        const المان = document.getElementById(فیلد.id);
        if (!المان.value.trim()) {
            معتبر_است = false;
            محل_خطاها.innerHTML += `<div class="text-red-500 text-sm mb-1">${فیلد.پیام}</div>`;
            المان.classList.add('border-red-500');
        } else {
            المان.classList.remove('border-red-500');
        }
    });
    
    return معتبر_است;
}
</script>

<?php require_once '../../templates/footer.php'; ?>