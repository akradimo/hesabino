<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $db->escape($_POST['username']);
    $password = $db->escape($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $db->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../index.php");
            exit();
        } else {
            echo "<p>نام کاربری یا رمز عبور اشتباه است.</p>";
        }
    } else {
        echo "<p>نام کاربری یا رمز عبور اشتباه است.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به سیستم</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <h2>ورود به سیستم</h2>
    <form method="POST" action="">
        <label for="username">نام کاربری:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">رمز عبور:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" name="login">ورود</button>
    </form>
</body>
</html>