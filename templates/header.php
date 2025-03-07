
<?php require_once __DIR__ . '/../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حسابینو - سیستم حسابداری آنلاین</title>
    
    <!-- استایل‌ها -->
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6/css/all.min.css">
    
    <!-- اسکریپت‌ها -->
    <script src="<?= asset('js/persian.js') ?>" defer></script>
    <script src="<?= asset('js/tabs.js') ?>" defer></script>
</head>
<body>
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="<?= url() ?>" class="flex items-center">
                        <img src="<?= asset('images/logo.png') ?>" alt="حسابینو" class="h-8 w-8">
                        <span class="text-xl font-bold text-blue-600 mr-2">حسابینو</span>
                    </a>
                </div>

                <!-- منوی اصلی -->
                <div class="hidden md:flex items-center space-x-4 space-x-reverse">
                    <a href="/src/dashboard" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home ml-1"></i>
                        داشبورد
                    </a>
                    <a href="/src/products" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-box ml-1"></i>
                        محصولات
                    </a>
                    <a href="/src/invoices" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-file-invoice ml-1"></i>
                        فاکتورها
                    </a>
                    <a href="/src/reports" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-chart-bar ml-1"></i>
                        گزارشات
                    </a>
                </div>

                <!-- منوی کاربر -->
                <div class="flex items-center">
                    <div class="relative ml-3">
                        <button type="button" class="flex items-center gap-2 text-sm focus:outline-none" id="user-menu-button">
                            <img class="h-8 w-8 rounded-full" src="/public/assets/images/avatar.png" alt="تصویر کاربر">
                            <span class="text-gray-700"><?php echo htmlspecialchars($_SESSION['نام_کاربری'] ?? 'کاربر'); ?></span>
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </button>

                        <!-- منوی کشویی کاربر -->
                        <div class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1" id="user-menu">
                            <a href="/src/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user ml-2"></i>
                                پروفایل
                            </a>
                            <a href="/src/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog ml-2"></i>
                                تنظیمات
                            </a>
                            <hr class="my-1">
                            <form action="/src/auth/logout.php" method="POST" class="block">
                                <button type="submit" class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt ml-2"></i>
                                    خروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- منوی موبایل -->
        <div class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/src/dashboard" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home ml-2"></i>
                    داشبورد
                </a>
                <a href="/src/products" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-box ml-2"></i>
                    محصولات
                </a>
                <a href="/src/invoices" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-file-invoice ml-2"></i>
                    فاکتورها
                </a>
                <a href="/src/reports" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-chart-bar ml-2"></i>
                    گزارشات
                </a>
            </div>
        </div>
    </nav>

    <!-- محتوای اصلی -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">