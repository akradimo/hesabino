<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = $db->escape($_GET['id']);
$sql = "SELECT * FROM products WHERE id='$id'";
$result = $db->query($sql);
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p>محصول یافت نشد.</p>";
    exit();
}

// ویرایش محصول
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product'])) {
    $name = $db->escape($_POST['name']);
    $price = $db->escape($_POST['price']);
    $quantity = $db->escape($_POST['quantity']);

    $sql = "UPDATE products SET name='$name', price='$price', quantity='$quantity' WHERE id='$id'";
    if ($db->query($sql)) {
        echo "<p>محصول با موفقیت ویرایش شد.</p>";
    } else {
        echo "<p>خطا در ویرایش محصول.</p>";
    }
}
?>

<h2>ویرایش محصول</h2>

<form method="POST" action="">
    <label for="name">نام محصول:</label>
    <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>
    <label for="price">قیمت:</label>
    <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required>
    <label for="quantity">تعداد:</label>
    <input type="number" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>
    <button type="submit" name="edit_product">ذخیره تغییرات</button>
</form>

<?php
require_once '../templates/footer.php';
?>