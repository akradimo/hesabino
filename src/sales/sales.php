<?php
require_once '../../includes/db.php';
require_once '../../templates/header.php';

// افزودن فروش جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_sale'])) {
    $product_id = $db->escape($_POST['product_id']);
    $service_id = $db->escape($_POST['service_id']);
    $quantity = $db->escape($_POST['quantity']);
    $total_price = $db->escape($_POST['total_price']);
    $discount_id = $db->escape($_POST['discount_id']);

    $sql = "INSERT INTO sales (product_id, service_id, quantity, total_price, discount_id) 
            VALUES ('$product_id', '$service_id', '$quantity', '$total_price', '$discount_id')";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>فروش با موفقیت ثبت شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در ثبت فروش.</p>";
    }
}

// نمایش فروش‌ها
$sql = "SELECT sales.*, products.name as product_name, services.name as service_name, discounts.code as discount_code 
        FROM sales 
        LEFT JOIN products ON sales.product_id = products.id 
        LEFT JOIN services ON sales.service_id = services.id 
        LEFT JOIN discounts ON sales.discount_id = discounts.id";
$result = $db->query($sql);
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">مدیریت فروش</h2>

    <!-- فرم افزودن فروش -->
    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700">محصول:</label>
                <select id="product_id" name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">انتخاب محصول</option>
                    <?php
                    $products = $db->query("SELECT * FROM products");
                    while ($product = $products->fetch_assoc()): ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
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
                    <option value="<?php echo $service['id']; ?>"><?php echo $service['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700">تعداد:</label>
                <input type="number" id="quantity" name="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label for="total_price" class="block text-sm font-medium text-gray-700">قیمت کل:</label>
                <input type="number" id="total_price" name="total_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label for="discount_id" class="block text-sm font-medium text-gray-700">تخفیف:</label>
                <select id="discount_id" name="discount_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">بدون تخفیف</option>
                    <?php
                    $discounts = $db->query("SELECT * FROM discounts");
                    while ($discount = $discounts->fetch_assoc()): ?>
                    <option value="<?php echo $discount['id']; ?>"><?php echo $discount['code']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <button type="submit" name="add_sale" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">ثبت فروش</button>
    </form>

    <!-- جدول نمایش فروش‌ها -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-right">محصول</th>
                    <th class="px-6 py-3 text-right">خدمت</th>
                    <th class="px-6 py-3 text-right">تعداد</th>
                    <th class="px-6 py-3 text-right">قیمت کل</th>
                    <th class="px-6 py-3 text-right">تخفیف</th>
                    <th class="px-6 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo $row['product_name']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['service_name']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['quantity']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['total_price']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['discount_code']; ?></td>
                    <td class="px-6 py-4">
                        <a href="edit_sale.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800">ویرایش</a>
                        <a href="?delete_sale=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once '../../templates/footer.php';
?>