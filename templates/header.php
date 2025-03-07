<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حسابینو - سامانه حسابداری آنلاین</title>
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
</head>
<body>
    <!-- هدر -->
    <header class="header">
        <div class="container flex justify-between items-center h-16">
            <div class="flex items-center">
                <img src="<?= asset('images/logo.png') ?>" alt="حسابینو" class="h-8 w-8">
                <span class="text-xl font-bold text-blue-600 mr-2">حسابینو</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">خوش آمدید، <?= $_SESSION['user']['name'] ?? 'کاربر' ?></span>
                <img src="<?= asset('images/avatar.png') ?>" alt="پروفایل" class="h-8 w-8 rounded-full">
            </div>
        </div>
    </header>

    <!-- منو کناری -->
    <aside class="sidebar">
        <nav>
            <a href="<?= url('src/dashboard') ?>" class="menu-item <?= $current_page === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home ml-2"></i>
                داشبورد
            </a>
            <a href="<?= url('src/products/products.php') ?>" class="menu-item <?= $current_page === 'products' ? 'active' : '' ?>">
                <i class="fas fa-box ml-2"></i>
                محصولات
            </a>
            <a href="<?= url('src/customers/customers.php') ?>" class="menu-item <?= $current_page === 'customers' ? 'active' : '' ?>">
                <i class="fas fa-users ml-2"></i>
                مشتریان
            </a>
            <a href="<?= url('src/invoices/invoices.php') ?>" class="menu-item <?= $current_page === 'invoices' ? 'active' : '' ?>">
                <i class="fas fa-file-invoice ml-2"></i>
                فاکتورها
            </a>
            <a href="<?= url('src/reports/reports.php') ?>" class="menu-item <?= $current_page === 'reports' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar ml-2"></i>
                گزارشات
            </a>
            <a href="<?= url('src/settings/settings.php') ?>" class="menu-item <?= $current_page === 'settings' ? 'active' : '' ?>">
                <i class="fas fa-cog ml-2"></i>
                تنظیمات
            </a>
        </nav>
    </aside>

    <!-- محتوای اصلی -->
    <main class="main-content">