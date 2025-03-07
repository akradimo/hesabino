<?php
require_once '../../includes/init.php';

// بررسی پارامتر ورودی
$barcode = clean($_GET['code'] ?? '');
if (empty($barcode)) {
    header('HTTP/1.0 400 Bad Request');
    die('کد بارکد الزامی است');
}

// تنظیمات بارکد
$barcode_settings = [
    'width' => 2,           // عرض میله‌های بارکد
    'height' => 50,         // ارتفاع بارکد
    'padding' => 10,        // فاصله از لبه‌ها
    'show_text' => false    // نمایش متن زیر بارکد
];

// کتابخانه بارکد
require_once '../../vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;
$generator = new BarcodeGeneratorPNG();

try {
    // تولید تصویر بارکد
    $barcode_image = $generator->getBarcode(
        $barcode,
        $generator::TYPE_CODE_128,
        $barcode_settings['width'],
        $barcode_settings['height']
    );

    // ایجاد تصویر نهایی با پدینگ
    $width = imagesx($barcode_image);
    $height = imagesy($barcode_image);
    $final_width = $width + (2 * $barcode_settings['padding']);
    $final_height = $height + (2 * $barcode_settings['padding']);
    
    $final_image = imagecreatetruecolor($final_width, $final_height);
    $white = imagecolorallocate($final_image, 255, 255, 255);
    imagefill($final_image, 0, 0, $white);
    
    imagecopy(
        $final_image,
        $barcode_image,
        $barcode_settings['padding'],
        $barcode_settings['padding'],
        0,
        0,
        $width,
        $height
    );

    // نمایش تصویر
    header('Content-Type: image/png');
    imagepng($final_image);
    
    // آزادسازی حافظه
    imagedestroy($barcode_image);
    imagedestroy($final_image);
    
} catch (Exception $e) {
    header('HTTP/1.0 500 Internal Server Error');
    die('خطا در تولید بارکد: ' . $e->getMessage());
}