<?php
require_once '../../includes/init.php';

$current_page = 'dashboard';
$page_title = 'داشبورد مدیریت';

// دریافت آمار کلی
$stats = [
    'products' => $db->getOne("SELECT COUNT(*) as count FROM products WHERE deleted_at IS NULL")['count'] ?? 0,
    'customers' => $db->getOne("SELECT COUNT(*) as count FROM customers WHERE deleted_at IS NULL")['count'] ?? 0,
    'invoices' => $db->getOne("SELECT COUNT(*) as count FROM invoices WHERE deleted_at IS NULL")['count'] ?? 0,
    'total_sales' => $db->getOne("SELECT SUM(total_amount) as total FROM invoices WHERE deleted_at IS NULL")['total'] ?? 0,
];

// آخرین محصولات
$latest_products = $db->getAll("SELECT * FROM products WHERE deleted_at IS NULL ORDER BY id DESC LIMIT 5");

// آخرین فاکتورها
$latest_invoices = $db->getAll("SELECT i.*, c.name as customer_name 
                               FROM invoices i 
                               LEFT JOIN customers c ON i.customer_id = c.id 
                               WHERE i.deleted_at IS NULL 
                               ORDER BY i.id DESC LIMIT 5");

require_once '../../templates/header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-box text-2xl"></i>
            </div>
            <div class="mr-4">
                <h2 class="text-sm font-medium text-gray-600">تعداد محصولات</h2>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['products']) ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="mr-4">
                <h2 class="text-sm font-medium text-gray-600">تعداد مشتریان</h2>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['customers']) ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-file-invoice text-2xl"></i>
            </div>
            <div class="mr-4">
                <h2 class="text-sm font-medium text-gray-600">تعداد فاکتورها</h2>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['invoices']) ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-money-bill text-2xl"></i>
            </div>
            <div class="mr-4">
                <h2 class="text-sm font-medium text-gray-600">مجموع فروش</h2>
                <p class="text-2xl font-bold text-gray-900"><?= formatPrice($stats['total_sales']) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">آخرین محصولات</h2>
            <a href="<?= url('src/products/products.php') ?>" class="text-blue-600 hover:text-blue-800">
                مشاهده همه
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-right">
                        <th class="pb-3 text-sm font-medium text-gray-600">نام محصول</th>
                        <th class="pb-3 text-sm font-medium text-gray-600">قیمت</th>
                        <th class="pb-3 text-sm font-medium text-gray-600">موجودی</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($latest_products as $product): ?>
                    <tr class="border-t">
                        <td class="py-3"><?= $product['name'] ?></td>
                        <td class="py-3"><?= formatPrice($product['price']) ?></td>
                        <td class="py-3"><?= $product['stock'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">آخرین فاکتورها</h2>
            <a href="<?= url('src/invoices/invoices.php') ?>" class="text-blue-600 hover:text-blue-800">
                مشاهده همه
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-right">
                        <th class="pb-3 text-sm font-medium text-gray-600">شماره</th>
                        <th class="pb-3 text-sm font-medium text-gray-600">مشتری</th>
                        <th class="pb-3 text-sm font-medium text-gray-600">مبلغ کل</th>
                        <th class="pb-3 text-sm font-medium text-gray-600">تاریخ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($latest_invoices as $invoice): ?>
                    <tr class="border-t">
                        <td class="py-3"><?= $invoice['invoice_number'] ?></td>
                        <td class="py-3"><?= $invoice['customer_name'] ?></td>
                        <td class="py-3"><?= formatPrice($invoice['total_amount']) ?></td>
                        <td class="py-3"><?= formatDate($invoice['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../templates/footer.php'; ?>