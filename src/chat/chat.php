<?php
// شروع session
session_start();

// اطمینان از اینکه کاربر لاگین کرده است
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php"); // اگر کاربر لاگین نکرده باشد، به صفحه اصلی هدایت شود
    exit();
}

// اتصال به دیتابیس
require_once '../../includes/db.php'; // مسیر درست به فایل db.php

// افزودن پیام جدید
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $sender_id = $_SESSION['user_id']; // فرستنده، کاربر فعلی است
    $receiver_id = $db->escape($_POST['receiver_id']);
    $message = $db->escape($_POST['message']);

    // درج پیام در دیتابیس
    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$message')";
    if ($db->query($sql)) {
        echo "<p class='bg-green-100 text-green-800 p-2 rounded'>پیام با موفقیت ارسال شد.</p>";
    } else {
        echo "<p class='bg-red-100 text-red-800 p-2 rounded'>خطا در ارسال پیام.</p>";
    }
}

// دریافت پیام‌ها
$user_id = $_SESSION['user_id'];
$sql = "SELECT messages.*, users.username as sender_name 
        FROM messages 
        LEFT JOIN users ON messages.sender_id = users.id 
        WHERE (sender_id='$user_id' OR receiver_id='$user_id') 
        ORDER BY created_at ASC";
$result = $db->query($sql);

// اضافه کردن هدر
require_once '../../templates/header.php'; // مسیر درست به فایل header.php
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">سیستم چت</h2>

    <!-- فرم ارسال پیام -->
    <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md mb-6">
        <input type="hidden" name="sender_id" value="<?php echo $user_id; ?>">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="receiver_id" class="block text-sm font-medium text-gray-700">گیرنده:</label>
                <select id="receiver_id" name="receiver_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">انتخاب کاربر</option>
                    <?php
                    $users = $db->query("SELECT * FROM users WHERE id != '$user_id'");
                    while ($user = $users->fetch_assoc()): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">پیام:</label>
                <textarea id="message" name="message" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
            </div>
        </div>
        <button type="submit" name="send_message" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">ارسال پیام</button>
    </form>

    <!-- نمایش پیام‌ها -->
    <div id="chat-messages" class="bg-white rounded-lg shadow-md p-6">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="message mb-4">
            <strong><?php echo $row['sender_id'] == $user_id ? 'شما' : $row['sender_name']; ?>:</strong>
            <p class="text-gray-700"><?php echo $row['message']; ?></p>
            <small class="text-gray-500"><?php echo $row['created_at']; ?></small>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
// بروزرسانی خودکار پیام‌ها با استفاده از Ajax
setInterval(function() {
    fetch('get_messages.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('chat-messages').innerHTML = data;
        });
}, 5000); // هر 5 ثانیه بروزرسانی شود
</script>

<?php
// اضافه کردن فوتر
require_once '../../templates/footer.php'; // مسیر درست به فایل footer.php
?>