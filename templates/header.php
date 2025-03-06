<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>برنامه حسابداری فروشگاه</title>
    <!-- لینک CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- لینک فایل style.css -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-blue-600 text-white py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold">برنامه حسابداری فروشگاه</h1>
            <nav class="mt-2">
                <ul class="flex space-x-4">
                    <li><a href="<?php echo SITE_URL; ?>/index.php" class="hover:text-blue-200">خانه</a></li>
                    
                    <!-- منوی کالا و خدمات -->
                    <li class="relative group">
                        <a href="#" class="hover:text-blue-200">کالا و خدمات</a>
                        <!-- زیرمنو -->
                        <ul class="absolute hidden bg-white text-gray-800 rounded-lg shadow-md mt-2 group-hover:block">
                            <li><a href="<?php echo SITE_URL; ?>/src/products/add_product.php" class="block px-4 py-2 hover:bg-blue-100">افزودن محصول</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/src/products/products.php" class="block px-4 py-2 hover:bg-blue-100">لیست محصولات</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/src/services/add_service.php" class="block px-4 py-2 hover:bg-blue-100">افزودن خدمات</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/src/services/services.php" class="block px-4 py-2 hover:bg-blue-100">لیست خدمات</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="<?php echo SITE_URL; ?>/src/sales/sales.php" class="hover:text-blue-200">فروش</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/src/feedbacks/feedbacks.php" class="hover:text-blue-200">بازخوردها</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/src/chat/chat.php" class="hover:text-blue-200">چت</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/src/events/events.php" class="hover:text-blue-200">رویدادها</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/src/users/users.php" class="hover:text-blue-200">کاربران</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/src/payment/payment.php" class="hover:text-blue-200">پرداخت</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/src/reports/advanced_reports.php" class="hover:text-blue-200">گزارش‌ها</a></li>
                </ul>
            </nav>
        </div>
    </header>