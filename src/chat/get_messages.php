<?php
session_start();
require_once '../../includes/db.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT messages.*, users.username as sender_name 
        FROM messages 
        LEFT JOIN users ON messages.sender_id = users.id 
        WHERE (sender_id='$user_id' OR receiver_id='$user_id') 
        ORDER BY created_at ASC";
$result = $db->query($sql);

while ($row = $result->fetch_assoc()): ?>
<div class="message mb-4">
    <strong><?php echo $row['sender_id'] == $user_id ? 'شما' : $row['sender_name']; ?>:</strong>
    <p class="text-gray-700"><?php echo $row['message']; ?></p>
    <small class="text-gray-500"><?php echo $row['created_at']; ?></small>
</div>
<?php endwhile; ?>