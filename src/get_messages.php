<?php
require_once '../includes/db.php';

$user_id = $_SESSION['user_id']; // فرض می‌کنیم کاربر وارد سیستم شده است
$sql = "SELECT * FROM messages WHERE (sender_id='$user_id' OR receiver_id='$user_id') ORDER BY created_at ASC";
$result = $db->query($sql);

while ($row = $result->fetch_assoc()): ?>
<div class="message">
    <strong><?php echo $row['sender_id'] == $user_id ? 'شما' : 'کاربر دیگر'; ?>:</strong>
    <p><?php echo $row['message']; ?></p>
    <small><?php echo $row['created_at']; ?></small>
</div>
<?php endwhile; ?>