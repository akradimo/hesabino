<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// دریافت لیست محصولات از دیتابیس
$stmt = $db->query("SELECT * FROM products WHERE deleted_at IS NULL ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت محصولات - حسابینو</title>
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">مدیریت محصولات</h1>
                <a href="add_product.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    افزودن محصول جدید
                </a>
            </div>

            <?php if(hasSuccess()): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= getSuccess() ?>
                </div>
            <?php endif; ?>

            <?php if(hasError()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= getError() ?>
                </div>
            <?php endif; ?>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">کد محصول</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نام محصول</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قیمت</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">موجودی</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach($products as $product): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $product['code'] ?? '-' ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= clean($product['name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= formatPrice($product['price'] ?? 0) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $product['stock'] ?? 0 ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="text-blue-600 hover:text-blue-900 ml-3">ویرایش</a>
                                    <a href="delete_product.php?id=<?= $product['id'] ?>" 
                                       class="text-red-600 hover:text-red-900" 
                                       onclick="return confirm('آیا از حذف این محصول اطمینان دارید؟')">حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>