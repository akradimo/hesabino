<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_sms'])) {
    $phone_number = $db->escape($_POST['phone_number']);
    $message = $db->escape($_POST['message']);

    // در اینجا کد اتصال به سرویس ارسال پیامک قرار می‌گیرد.
    // برای مثال:
    $api_key = 'YOUR_API_KEY';
    $sender = 'YOUR_SENDER_NUMBER';
    $url = "https://api.kavenegar.com/v1/$api_key/sms/send.json";
    $data = [
        'receptor' => $phone_number,
        'message' => $message,
        'sender' => $sender,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result['return']['status'] == 200) {
        echo "<p>پیامک با موفقیت ارسال شد.</p>";
    } else {
        echo "<p>خطا در ارسال پیامک.</p>";
    }
}
?>

<h2>ارسال پیامک</h2>

<form method="POST" action="">
    <label for="phone_number">شماره موبایل:</label>
    <input type="text" id="phone_number" name="phone_number" required>
    <label for="message">متن پیامک:</label>
    <textarea id="message" name="message" required></textarea>
    <button type="submit" name="send_sms">ارسال پیامک</button>
</form>

<?php
require_once '../templates/footer.php';
?>