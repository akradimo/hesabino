<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

// نمایش گزارش فروش
$sql = "SELECT sales.*, products.name as product_name, services.name as service_name 
        FROM sales 
        LEFT JOIN products ON sales.product_id = products.id 
        LEFT JOIN services ON sales.service_id = services.id";
$result = $db->query($sql);
?>

<h2>گزارش فروش</h2>

<table>
    <thead>
        <tr>
            <th>محصول</th>
            <th>خدمت</th>
            <th>تعداد</th>
            <th>قیمت کل</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['service_name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo $row['total_price']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
require_once '../templates/footer.php';
?>