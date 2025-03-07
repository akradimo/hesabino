<?php
require_once '../../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: categories.php");
    exit();
}

$id = $db->escape($_GET['id']);
$sql = "DELETE FROM categories WHERE id='$id'";
if ($db->query($sql)) {
    header("Location: categories.php?success=دسته‌بندی با موفقیت حذف شد.");
} else {
    header("Location: categories.php?error=خطا در حذف دسته‌بندی.");
}
?>