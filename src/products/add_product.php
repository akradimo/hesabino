<?php
require_once '../../includes/init.php';

// اگر فرم ارسال شده باشد
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $code = $_POST['code'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    
    if (empty($name)) {
        setMessage('error', 'نام محصول نمی‌تواند خالی باشد');
    } else {
        try {
            $db->insert(
                "INSERT INTO products (name, code, price, stock, created_at) VALUES (?, ?, ?, ?, NOW())",
                [$name, $code, $price, $stock]
            );
            setMessage('success', 'محصول با موفقیت اضافه شد');
            header('Location: products.php');
            exit;
        } catch (Exception $e) {
            setMessage('error', 'خطا در ثبت محصول');
        }
    }
}

$current_page = 'products';
require_once '../../templates/header.php';
?>

<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">افزودن محصول جدید</h1>
        <a href="products.php" class="btn btn-primary">
            بازگشت به لیست محصولات
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
                کد محصول
            </label>
            <input type="text" name="code" id="code" 
                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                   value="<?= $_POST['code'] ?? '' ?>">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                نام محصول
                <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="name" required
                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                   value="<?= $_POST['name'] ?? '' ?>">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                قیمت (تومان)
            </label>
            <input type="number" name="price" id="price"
                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                   value="<?= $_POST['price'] ?? 0 ?>">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="stock">
                موجودی
            </label>
            <input type="number" name="stock" id="stock"
                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                   value="<?= $_POST['stock'] ?? 0 ?>">
        </div>

        <button type="submit" class="btn btn-primary">
            ذخیره محصول
        </button>
    </form>
</div>

<?php require_once '../../templates/footer.php'; ?>