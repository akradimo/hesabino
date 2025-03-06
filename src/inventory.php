<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

// به‌روزرسانی موجودی
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_inventory'])) {
    $product_id = $db->escape($_POST['product_id']);
    $quantity = $db->escape($_POST['quantity']);

    $sql = "UPDATE products SET quantity = quantity + $quantity WHERE id='$product_id'";
    if ($db->query($sql)) {
        echo "<p>موجودی با موفقیت به‌روزرسانی شد.</p>";
    } else {
        echo "<p>خطا در به‌روزرسانی موجودی.</p>";
    }
}

// نمایش موجودی محصولات
$sql = "SELECT * FROM products";
$result = $db->query($sql);
?>

<h2>مدیریت موجودی انبار</h2>

<!-- فرم به‌روزرسانی موجودی -->
<form method="POST" action="">
    <label for="product_id">محصول:</label>
    <select id="product_id" name="product_id" required>
        <option value="">انتخاب محصول</option>
        <?php
        $products = $db->query("SELECT * FROM products");
        while ($product = $products->fetch_assoc()): ?>
        <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <label for="quantity">تعداد:</label>
    <input type="number" id="quantity" name="quantity" required>
    <button type="submit" name="update_inventory">به‌روزرسانی موجودی</button>
</form>

<!-- جدول نمایش موجودی محصولات -->
<table>
    <thead>
        <tr>
            <th>نام محصول</th>
            <th>موجودی</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
require_once '../templates/footer.php';
?>