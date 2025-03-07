<?php
// توابع نمایش پیام ها
function hasSuccess() {
    return isset($_SESSION['success']);
}

function hasError() {
    return isset($_SESSION['error']);
}

function getSuccess() {
    if (isset($_SESSION['success'])) {
        $message = $_SESSION['success'];
        unset($_SESSION['success']);
        return $message;
    }
    return '';
}

function getError() {
    if (isset($_SESSION['error'])) {
        $message = $_SESSION['error'];
        unset($_SESSION['error']);
        return $message;
    }
    return '';
}

// توابع امنیتی
function clean($string) {
    return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
}

// توابع پیام رسانی
function setMessage($type, $message) {
    $_SESSION[$type] = $message;
}

function getMessage($type) {
    if (isset($_SESSION[$type])) {
        $message = $_SESSION[$type];
        unset($_SESSION[$type]);
        return $message;
    }
    return '';
}

// تولید کد محصول
function generateProductCode() {
    return 'P' . date('ymd') . rand(1000, 9999);
}

// بررسی دسترسی کاربر
function checkAccess($permission) {
    // در آینده پیاده‌سازی خواهد شد
    return true;
}

// تبدیل اعداد انگلیسی به فارسی
function toFarsiNumber($number) {
    $farsi_array = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english_array = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($english_array, $farsi_array, $number);
}

// تبدیل اعداد فارسی به انگلیسی
function toEnglishNumber($number) {
    $farsi_array = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english_array = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($farsi_array, $english_array, $number);
}

// مدیریت آپلود فایل
function uploadFile($file, $path, $allowed_types = ['jpg', 'jpeg', 'png']) {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_types)) {
        return false;
    }

    $upload_path = UPLOAD_PATH . '/' . trim($path, '/');
    if (!file_exists($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    $file_name = uniqid() . '.' . $file_extension;
    $file_path = $upload_path . '/' . $file_name;

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        return $file_name;
    }

    return false;
}