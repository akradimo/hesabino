<?php
// اتصال به دیتابیس
require_once '../includes/db.php';

// بررسی آیا کاربر لاگین کرده است یا خیر (امنیت)
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// ثبت فروش جدید
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_sale'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $customer_id = $_POST['customer_id'];
    $sale_date = $_POST['sale_date'];
    $total_amount = $_POST['total_amount'];

    // اعتبارسنجی داده‌ها
    if (!empty($product_id) && !empty($quantity) && !empty($customer_id) && !empty($sale_date) && !empty($total_amount)) {
        $sql = "INSERT INTO sales (product_id, quantity, customer_id, sale_date, total_amount) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id, $quantity, $customer_id, $sale_date, $total_amount]);

        // پیام موفقیت
        $_SESSION['message'] = "فروش با موفقیت ثبت شد.";
        header('Location: sales.php');
        exit();
    } else {
        $_SESSION['error'] = "لطفاً تمام فیلدها را پر کنید.";
    }
}

// دریافت لیست فروش‌ها
$sql = "SELECT sales.id, products.name AS product_name, sales.quantity, customers.name AS customer_name, sales.sale_date, sales.total_amount 
        FROM sales 
        JOIN products ON sales.product_id = products.id 
        JOIN customers ON sales.customer_id = customers.id 
        ORDER BY sales.sale_date DESC";
$sales = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت فروش</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php include '../templates/header.php'; ?>

    <div class="container">
        <h1>مدیریت فروش</h1>

        <!-- نمایش پیام‌ها -->
        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- فرم ثبت فروش جدید -->
        <h2>ثبت فروش جدید</h2>
        <form method="POST" action="">
            <label for="product_id">محصول:</label>
            <select name="product_id" id="product_id" required>
                <?php
                $products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $product) {
                    echo "<option value='{$product['id']}'>{$product['name']}</option>";
                }
                ?>
            </select>

            <label for="quantity">تعداد:</label>
            <input type="number" name="quantity" id="quantity" required>

            <label for="customer_id">مشتری:</label>
            <select name="customer_id" id="customer_id" required>
                <?php
                $customers = $pdo->query("SELECT * FROM customers")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($customers as $customer) {
                    echo "<option value='{$customer['id']}'>{$customer['name']}</option>";
                }
                ?>
            </select>

            <label for="sale_date">تاریخ فروش:</label>
            <input type="date" name="sale_date" id="sale_date" required>

            <label for="total_amount">مبلغ کل:</label>
            <input type="number" name="total_amount" id="total_amount" required>

            <button type="submit" name="add_sale">ثبت فروش</button>
        </form>

        <!-- نمایش لیست فروش‌ها -->
        <h2>لیست فروش‌ها</h2>
        <table>
            <thead>
                <tr>
                    <th>شماره فاکتور</th>
                    <th>محصول</th>
                    <th>تعداد</th>
                    <th>مشتری</th>
                    <th>تاریخ فروش</th>
                    <th>مبلغ کل</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale) : ?>
                    <tr>
                        <td><?php echo $sale['id']; ?></td>
                        <td><?php echo $sale['product_name']; ?></td>
                        <td><?php echo $sale['quantity']; ?></td>
                        <td><?php echo $sale['customer_name']; ?></td>
                        <td><?php echo $sale['sale_date']; ?></td>
                        <td><?php echo $sale['total_amount']; ?></td>
                        <td>
                            <a href="edit_sale.php?id=<?php echo $sale['id']; ?>">ویرایش</a>
                            <a href="delete_sale.php?id=<?php echo $sale['id']; ?>" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include '../templates/footer.php'; ?>
</body>
</html>