<?php
// تنظیمات پایه سامانه
define('SITE_URL', 'http://localhost/hesabino');
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOAD_PATH', BASE_PATH . '/assets/uploads');

// تنظیمات دیتابیس
define('DB_HOST', 'localhost');
define('DB_NAME', 'hesabino');
define('DB_USER', 'root');
define('DB_PASS', '');

// تنظیمات زمانی
date_default_timezone_set('Asia/Tehran');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// توابع پایه
function url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function asset($path = '') {
    return ASSETS_URL . '/' . ltrim($path, '/');
}

function redirect($path = '') {
    header('Location: ' . url($path));
    exit;
}

function clean($string) {
    return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
}

// تابع نمایش خطا
function show_error($message) {
    $_SESSION['error'] = $message;
    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: ' . SITE_URL);
    }
    exit;
}

// تابع نمایش پیام موفقیت
function show_success($message) {
    $_SESSION['success'] = $message;
    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: ' . SITE_URL);
    }
    exit;
}

// شروع نشست
session_start();

// اتصال به دیتابیس
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
} catch(PDOException $e) {
    die('خطا در اتصال به پایگاه داده: ' . $e->getMessage());
}

// تنظیمات امنیتی
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Content-Type: text/html; charset=utf-8');