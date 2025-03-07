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

// تنظیمات کاربر و زمان
define('CURRENT_USER', 'akradimo');
define('CURRENT_TIME', '1403-12-17 10:01:40');

// لود کردن فایل‌های اصلی به ترتیب
require_once BASE_PATH . '/includes/jdf.php';      // تابع‌های تاریخ شمسی
require_once BASE_PATH . '/includes/functions.php'; // توابع عمومی
require_once BASE_PATH . '/includes/db.php';        // کلاس دیتابیس

// تابع‌های مسیردهی
function url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function asset($path = '') {
    return ASSETS_URL . '/' . ltrim($path, '/');
}

// تبدیل تاریخ میلادی به شمسی
function toJalali($date) {
    if (!$date) return '';
    $timestamp = strtotime($date);
    list($year, $month, $day) = explode('-', date('Y-m-d', $timestamp));
    return gregorian_to_jalali($year, $month, $day, '/');
}

// تبدیل تاریخ شمسی به میلادی
function toGregorian($date) {
    if (!$date) return '';
    list($year, $month, $day) = explode('/', $date);
    return implode('-', jalali_to_gregorian($year, $month, $day));
}

// فرمت‌بندی قیمت
function formatPrice($price) {
    return number_format($price, 0, '.', ',') . ' تومان';
}

// فرمت‌بندی تاریخ
function formatDate($date) {
    return $date ? toJalali($date) : '';
}

// تنظیم متغیرهای پایه برای همه صفحات
$current_page = '';
$page_title = 'حسابینو';
$page_description = 'سامانه حسابداری آنلاین';