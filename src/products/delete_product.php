<?php
require_once '../../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = $db->escape($_GET['id']);
$sql = "DELETE FROM products WHERE id='$id'";
if ($db->query($sql)) {
    header("Location: products.php?success=محصول با موفقیت حذف شد.");
} else {
    header("Location: products.php?error=خطا در حذف محصول.");
}
?>