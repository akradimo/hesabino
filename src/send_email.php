<?php
require_once '../includes/db.php';
require_once '../templates/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_email'])) {
    $to = $db->escape($_POST['to']);
    $subject = $db->escape($_POST['subject']);
    $message = $db->escape($_POST['message']);

    $headers = "From: no-reply@example.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "<p>ایمیل با موفقیت ارسال شد.</p>";
    } else {
        echo "<p>خطا در ارسال ایمیل.</p>";
    }
}
?>

<h2>ارسال ایمیل</h2>

<form method="POST" action="">
    <label for="to">به:</label>
    <input type="email" id="to" name="to" required>
    <label for="subject">موضوع:</label>
    <input type="text" id="subject" name="subject" required>
    <label for="message">متن ایمیل:</label>
    <textarea id="message" name="message" required></textarea>
    <button type="submit" name="send_email">ارسال ایمیل</button>
</form>

<?php
require_once '../templates/footer.php';
?>