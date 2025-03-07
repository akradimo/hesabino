<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

$status = $_GET['Status'];
$authority = $_GET['Authority'];

if ($status == 'OK') {
    // در اینجا کد بررسی تراکنش با زرین‌پال یا درگاه پرداخت دیگر قرار می‌گیرد.
    // برای مثال:
    $merchant_id = 'YOUR_MERCHANT_ID';
    $amount = $_SESSION['amount']; // مبلغ پرداختی از سشن
    $url = "https://www.zarinpal.com/pg/rest/WebGate/PaymentVerification.json";
    $data = [
        'MerchantID' => $merchant_id,
        'Authority' => $authority,
        'Amount' => $amount,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result['Status'] == 100) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>پرداخت با موفقیت انجام شد. کد پیگیری: " . $result['RefID'] . "</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در پرداخت. لطفا دوباره تلاش کنید.</p>";
    }
} else {
    echo "<p class='bg-red-100 text-red-800 p-2 rounded'>پرداخت لغو شد.</p>";
}

require_once '../templates/footer.php';
?>