<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

if (!isset($_GET['id'])) {
    header("Location: services.php");
    exit();
}

$id = $db->escape($_GET['id']);
$sql = "SELECT * FROM services WHERE id='$id'";
$result = $db->query($sql);
$service = $result->fetch_assoc();

if (!$service) {
    echo "<p>خدمت یافت نشد.</p>";
    exit();
}

// ویرایش خدمت
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_service'])) {
    $name = $db->escape($_POST['name']);
    $price = $db->escape($_POST['price']);

    $sql = "UPDATE services SET name='$name', price='$price' WHERE id='$id'";
    if ($db->query($sql)) {
        echo "<p>خدمت با موفقیت ویرایش شد.</p>";
    } else {
        echo "<p>خطا در ویرایش خدمت.</p>";
    }
}
?>

<h2>ویرایش خدمت</h2>

<form method="POST" action="">
    <label for="name">نام خدمت:</label>
    <input type="text" id="name" name="name" value="<?php echo $service['name']; ?>" required>
    <label for="price">قیمت:</label>
    <input type="number" id="price" name="price" value="<?php echo $service['price']; ?>" required>
    <button type="submit" name="edit_service">ذخیره تغییرات</button>
</form>

<?php
require_once '../templates/footer.php';
?>