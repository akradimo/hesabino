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

            <!-- منوی کالا و خدمات -->
            <div class="menu-group">
                <div class="menu-item menu-toggle <?= str_starts_with($current_page, 'products') ? 'active' : '' ?>">
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <i class="fas fa-box ml-2"></i>
                            کالا و خدمات
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform"></i>
                    </div>
                </div>
                <div class="submenu">
                    <a href="<?= url('src/products/add_product.php?type=product') ?>" class="submenu-item">
                        <i class="fas fa-plus ml-2"></i>
                        کالای جدید
                    </a>
                    <a href="<?= url('src/products/add_product.php?type=service') ?>" class="submenu-item">
                        <i class="fas fa-plus ml-2"></i>
                        خدمات جدید
                    </a>
                    <a href="<?= url('src/products/products.php?type=product') ?>" class="submenu-item">
                        <i class="fas fa-list ml-2"></i>
                        فهرست کالاها
                    </a>
                    <a href="<?= url('src/products/products.php?type=service') ?>" class="submenu-item">
                        <i class="fas fa-list ml-2"></i>
                        فهرست خدمات
                    </a>
                    <a href="<?= url('src/products/price_list.php') ?>" class="submenu-item">
                        <i class="fas fa-tags ml-2"></i>
                        به‌روزرسانی لیست قیمت
                    </a>
                    <a href="<?= url('src/products/barcode.php') ?>" class="submenu-item">
                        <i class="fas fa-barcode ml-2"></i>
                        چاپ بارکد
                    </a>
                    <a href="<?= url('src/products/barcode_bulk.php') ?>" class="submenu-item">
                        <i class="fas fa-print ml-2"></i>
                        چاپ بارکد تعدادی
                    </a>
                    <a href="<?= url('src/products/price_lists.php') ?>" class="submenu-item">
                        <i class="fas fa-file-alt ml-2"></i>
                        لیست قیمت‌ها
                    </a>
                </div>
            </div>

            <!-- بقیه منوها -->
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