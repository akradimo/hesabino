<?php
// تنظیمات پایه
session_start();
date_default_timezone_set('Asia/Tehran');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ثابت‌های مسیر
define('SITE_URL', 'http://localhost/project');
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_URL', SITE_URL . '/assets');
define('TEMPLATE_PATH', BASE_PATH . '/templates');
define('UPLOAD_PATH', BASE_PATH . '/uploads');

// تنظیمات دیتابیس
define('DB_HOST', 'localhost');
define('DB_NAME', 'hesabino');
define('DB_USER', 'root');
define('DB_PASS', '');

// کاربر فعلی
define('CURRENT_USER', 'akradimo');
define('CURRENT_TIME', '2025-03-07 06:15:50');

// لود کردن فایل‌های اصلی
require_once BASE_PATH . '/includes/functions.php';
require_once BASE_PATH . '/includes/db.php';

// تابع مسیر‌دهی
function url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function asset($path = '') {
    return ASSETS_URL . '/' . ltrim($path, '/');
}

// تنظیم متغیرهای پایه
$current_page = '';
$page_title = 'حسابینو';