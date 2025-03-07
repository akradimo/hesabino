<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

// گزارش فروش بر اساس کاربر
$sql_user = "SELECT users.username, SUM(sales.total_price) as total_price 
             FROM sales 
             LEFT JOIN users ON sales.user_id = users.id 
             GROUP BY users.username 
             ORDER BY total_price DESC";
$result_user = $db->query($sql_user);

// گزارش فروش بر اساس تخفیف
$sql_discount = "SELECT discounts.code, SUM(sales.total_price) as total_price 
                FROM sales 
                LEFT JOIN discounts ON sales.discount_id = discounts.id 
                GROUP BY discounts.code 
                ORDER BY total_price DESC";
$result_discount = $db->query($sql_discount);
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">گزارش‌های پیشرفته</h2>

    <!-- گزارش فروش بر اساس کاربر -->
    <h3 class="text-xl font-bold mb-2">فروش بر اساس کاربر</h3>
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-right">نام کاربر</th>
                    <th class="px-6 py-3 text-right">فروش کل</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result_user->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo $row['username']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['total_price']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- گزارش فروش بر اساس تخفیف -->
    <h3 class="text-xl font-bold mb-2">فروش بر اساس تخفیف</h3>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-right">کد تخفیف</th>
                    <th class="px-6 py-3 text-right">فروش کل</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result_discount->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4"><?php echo $row['code']; ?></td>
                    <td class="px-6 py-4"><?php echo $row['total_price']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once '../templates/footer.php';
?>