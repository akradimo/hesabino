<?php
// اتصال به دیتابیس
require_once '../includes/db.php';

// بررسی آیا کاربر لاگین کرده است یا خیر (امنیت)
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// دریافت اطلاعات فروش برای ویرایش
if (isset($_GET['id'])) {
    $sale_id = $_GET['id'];
    $sql = "SELECT * FROM sales WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$sale_id]);
    $sale = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sale) {
        $_SESSION['error'] = "فروش مورد نظر یافت نشد.";
        header('Location: sales.php');
        exit();
    }
}

// ویرایش فروش
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_sale'])) {
    $sale_id = $_POST['sale_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $customer_id = $_POST['customer_id'];
    $sale_date = $_POST['sale_date'];
    $total_amount = $_POST['total_amount'];

    // اعتبارسنجی داده‌ها
    if (!empty($product_id) && !empty($quantity) && !empty($customer_id) && !empty($sale_date) && !empty($total_amount)) {
        $sql = "UPDATE sales SET product_id = ?, quantity = ?, customer_id = ?, sale_date = ?, total_amount = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id, $quantity, $customer_id, $sale_date, $total_amount, $sale_id]);

        // پیام موفقیت
        $_SESSION['message'] = "فروش با موفقیت ویرایش شد.";
        header('Location: sales.php');
        exit();
    } else {
        $_SESSION['error'] = "لطفاً تمام فیلدها را پر کنید.";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ویرایش فروش</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>ویرایش فروش</h1>

        <!-- نمایش پیام‌ها -->
        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- فرم ویرایش فروش -->
        <form method="POST" action="">
            <input type="hidden" name="sale_id" value="<?php echo $sale['id']; ?>">

            <label for="product_id">محصول:</label>
            <select name="product_id" id="product_id" required>
                <?php
                $products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $product) {
                    $selected = ($product['id'] == $sale['product_id']) ? 'selected' : '';
                    echo "<option value='{$product['id']}' $selected>{$product['name']}</option>";
                }
                ?>
            </select>

            <label for="quantity">تعداد:</label>
            <input type="number" name="quantity" id="quantity" value="<?php echo $sale['quantity']; ?>" required>

            <label for="customer_id">مشتری:</label>
            <select name="customer_id" id="customer_id" required>
                <?php
                $customers = $pdo->query("SELECT * FROM customers")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($customers as $customer) {
                    $selected = ($customer['id'] == $sale['customer_id']) ? 'selected' : '';
                    echo "<option value='{$customer['id']}' $selected>{$customer['name']}</option>";
                }
                ?>
            </select>

            <label for="sale_date">تاریخ فروش:</label>
            <input type="date" name="sale_date" id="sale_date" value="<?php echo $sale['sale_date']; ?>" required>

            <label for="total_amount">مبلغ کل:</label>
            <input type="number" name="total_amount" id="total_amount" value="<?php echo $sale['total_amount']; ?>" required>

            <button type="submit" name="edit_sale">ذخیره تغییرات</button>
        </form>
    </div>

    <?php include '../templates/footer.php'; ?>
</body>
</html>