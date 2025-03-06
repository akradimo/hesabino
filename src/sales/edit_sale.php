<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

if (!isset($_GET['id'])) {
    header("Location: sales.php");
    exit();
}

$id = $db->escape($_GET['id']);
$sql = "SELECT * FROM sales WHERE id='$id'";
$result = $db->query($sql);
$sale = $result->fetch_assoc();

if (!$sale) {
    echo "<p class='bg-red-100 text-red-800 p-2 rounded'>فروش یافت نشد.</p>";
    exit();
}

// ویرایش فروش
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_sale'])) {
    $product_id = $db->escape($_POST['product_id']);
    $service_id = $db->escape($_POST['service_id']);
    $quantity = $db->escape($_POST['quantity']);
    $total_price = $db->escape($_POST['total_price']);
    $discount_id = $db->escape($_POST['discount_id']);

    $sql = "UPDATE sales SET product_id='$product_id', service_id='$service_id', quantity='$quantity', total_price='$total_price', discount_id='$discount_id' WHERE id='$id'";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>فروش با موفقیت ویرایش شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در ویرایش فروش.</p>";
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">ویرایش فروش</h2>

    <form method="POST" action="">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700">محصول:</label>
                <select id="product_id" name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">انتخاب محصول</option>
                    <?php
                    $products = $db->query("SELECT * FROM products");
                    while ($product = $products->fetch_assoc()): ?>
                    <option value="<?php echo $product['id']; ?>" <?php echo $product['id'] == $sale['product_id'] ? 'selected' : ''; ?>><?php echo $product['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="service_id" class="block text-sm font-medium text-gray-700">خدمت:</label>
                <select id="service_id" name="service_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">انتخاب خدمت</option>
                    <?php
                    $services = $db->query("SELECT * FROM services");
                    while ($service = $services->fetch_assoc()): ?>
                    <option value="<?php echo $service['id']; ?>" <?php echo $service['id'] == $sale['service_id'] ? 'selected' : ''; ?>><?php echo $service['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700">تعداد:</label>
                <input type="number" id="quantity" name="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="<?php echo $sale['quantity']; ?>" required>
            </div>
            <div>
                <label for="total_price" class="block text-sm font-medium text-gray-700">قیمت کل:</label>
                <input type="number" id="total_price" name="total_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="<?php echo $sale['total_price']; ?>" required>
            </div>
            <div>
                <label for="discount_id" class="block text-sm font-medium text-gray-700">تخفیف:</label>
                <select id="discount_id" name="discount_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">بدون تخفیف</option>
                    <?php
                    $discounts = $db->query("SELECT * FROM discounts");
                    while ($discount = $discounts->fetch_assoc()): ?>
                    <option value="<?php echo $discount['id']; ?>" <?php echo $discount['id'] == $sale['discount_id'] ? 'selected' : ''; ?>><?php echo $discount['code']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <button type="submit" name="edit_sale" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">ذخیره تغییرات</button>
    </form>
</div>

<?php
require_once '../../templates/footer.php';
?>