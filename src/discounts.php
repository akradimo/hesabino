<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

// افزودن تخفیف جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_discount'])) {
    $code = $db->escape($_POST['code']);
    $discount_percent = $db->escape($_POST['discount_percent']);
    $start_date = $db->escape($_POST['start_date']);
    $end_date = $db->escape($_POST['end_date']);

    $sql = "INSERT INTO discounts (code, discount_percent, start_date, end_date) VALUES ('$code', '$discount_percent', '$start_date', '$end_date')";
    if ($db->query($sql)) {
        echo "<p>تخفیف با موفقیت افزوده شد.</p>";
    } else {
        echo "<p>خطا در افزودن تخفیف.</p>";
    }
}

// حذف تخفیف
if (isset($_GET['delete_discount'])) {
    $id = $db->escape($_GET['delete_discount']);
    $sql = "DELETE FROM discounts WHERE id='$id'";
    if ($db->query($sql)) {
        echo "<p>تخفیف با موفقیت حذف شد.</p>";
    } else {
        echo "<p>خطا در حذف تخفیف.</p>";
    }
}

// نمایش تخفیف‌ها
$sql = "SELECT * FROM discounts";
$result = $db->query($sql);
?>

<h2>مدیریت تخفیف‌ها</h2>

<!-- فرم افزودن تخفیف -->
<form method="POST" action="">
    <label for="code">کد تخفیف:</label>
    <input type="text" id="code" name="code" required>
    <label for="discount_percent">درصد تخفیف:</label>
    <input type="number" id="discount_percent" name="discount_percent" step="0.01" required>
    <label for="start_date">تاریخ شروع:</label>
    <input type="date" id="start_date" name="start_date" required>
    <label for="end_date">تاریخ پایان:</label>
    <input type="date" id="end_date" name="end_date" required>
    <button type="submit" name="add_discount">افزودن تخفیف</button>
</form>

<!-- جدول نمایش تخفیف‌ها -->
<table>
    <thead>
        <tr>
            <th>کد تخفیف</th>
            <th>درصد تخفیف</th>
            <th>تاریخ شروع</th>
            <th>تاریخ پایان</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['code']; ?></td>
            <td><?php echo $row['discount_percent']; ?>%</td>
            <td><?php echo $row['start_date']; ?></td>
            <td><?php echo $row['end_date']; ?></td>
            <td>
                <a href="?delete_discount=<?php echo $row['id']; ?>" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
require_once '../templates/footer.php';
?>