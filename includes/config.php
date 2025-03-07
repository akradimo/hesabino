<?php
define('SITE_URL', 'http://localhost/project');
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOAD_PATH', BASE_PATH . '/uploads');

// Database Settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'hesabino');
define('DB_USER', 'root');
define('DB_PASS', '');

date_default_timezone_set('Asia/Tehran');
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

session_start();