<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay'])) {
    $amount = $db->escape($_POST['amount']);
    $description = $db->escape($_POST['description']);

    // در اینجا کد اتصال به زرین‌پال یا درگاه پرداخت دیگر قرار می‌گیرد.
    // برای مثال:
    $merchant_id = 'YOUR_MERCHANT_ID';
    $callback_url = SITE_URL . '/payment_callback.php';
    $payment_url = "https://www.zarinpal.com/pg/StartPay/$merchant_id/$amount/$description/$callback_url";

    header("Location: $payment_url");
    exit();
}
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">پرداخت</h2>

    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">مبلغ (تومان):</label>
                <input type="number" id="amount" name="amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">توضیحات:</label>
                <input type="text" id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
        </div>
        <button type="submit" name="pay" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">پرداخت</button>
    </form>
</div>

<?php
require_once '../templates/footer.php';
?>