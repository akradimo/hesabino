<?php
require_once '../../includes/init.php';

$current_page = 'products-service-add';
$page_title = 'افزودن خدمات جدید';

// دریافت لیست دسته‌بندی‌های خدمات
$categories = $db->getAll("SELECT * FROM categories WHERE type = 'service' AND deleted_at IS NULL");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $code = clean($_POST['code']) ?: generateProductCode();
    $price = toEnglishNumber(clean($_POST['price']));
    $tax_percent = toEnglishNumber(clean($_POST['tax_percent']));
    $category_id = clean($_POST['category_id']);
    $description = clean($_POST['description']);

    if (empty($name)) {
        setMessage('error', 'نام خدمات نمی‌تواند خالی باشد');
    } else {
        try {
            $db->insert(
                "INSERT INTO products (name, code, type, price, tax_percent, category_id, description, created_at) 
                VALUES (?, ?, 'service', ?, ?, ?, ?, NOW())",
                [$name, $code, $price, $tax_percent, $category_id, $description]
            );
            setMessage('success', 'خدمات جدید با موفقیت ثبت شد');
            redirect('src/products/services.php');
        } catch (Exception $e) {
            setMessage('error', 'خطا در ثبت خدمات');
        }
    }
}

require_once '../../templates/header.php';
?>

<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">افزودن خدمات جدید</h1>
        <a href="<?= url('src/products/services.php') ?>" class="btn btn-primary">
            بازگشت به لیست خدمات
        </a>
    </div>

    <?php if(hasError()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= getError() ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="w-full max-w-lg">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="code">
                کد خدمات
            </label>
            <input type="text" name="code" id="code" 
                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                   value="<?= $_POST['code'] ?? '' ?>"
                   placeholder="در صورت خالی بودن، کد به صورت خودکار تولید می‌شود">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                نام خدمات
                <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="name" required
                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                   value="<?= $_POST['name'] ?? '' ?>">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                دسته‌بندی
            </label>
            <select name="category_id" id="category_id"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                <option value="">انتخاب کنید</option>
                <?php foreach($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= (($_POST['category_id'] ?? '') == $category['id']) ? 'selected' : '' ?>>
                        <?= $category['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                قیمت (تومان)
            </label>
            <input type="text" name="price" id="price"
                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                   value="<?= $_POST['price'] ?? '0' ?>">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="tax_percent">
                درصد مالیات
            </label>
            <input type="text" name="tax_percent" id="tax_percent"
                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                   value="<?= $_POST['tax_percent'] ?? '0' ?>">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                توضیحات
            </label>
            <textarea name="description" id="description" rows="4"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"><?= $_POST['description'] ?? '' ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            ذخیره خدمات
        </button>
    </form>
</div>

<?php require_once '../../templates/footer.php'; ?>