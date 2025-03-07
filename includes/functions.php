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

// توابع فرمت‌بندی
function formatPrice($price) {
    return number_format($price, 0, '.', ',') . ' تومان';
}

function formatDate($date) {
    return jdate($date)->format('Y/m/d');
}

function formatTime($time) {
    return jdate($time)->format('H:i');
}

// توابع امنیتی
function sanitize($string) {
    return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
}

// توابع مسیریابی
function getUrl($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function getAsset($path = '') {
    return ASSETS_URL . '/' . ltrim($path, '/');
}

// توابع دسترسی
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function isLoggedIn() {
    return isset($_SESSION['user']);
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