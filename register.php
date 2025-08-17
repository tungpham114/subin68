<?php
require_once 'includes/db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = 'Vui lòng điền đầy đủ thông tin.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Mật khẩu xác nhận không khớp.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Mật khẩu phải dài ít nhất 6 ký tự.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = 'Email đã tồn tại.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);
            
            $user_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
            $stmt->execute([$user_id, "Chào mừng bạn đến với Handmade Việt!"]);
            
            $success = 'Đăng ký thành công! Vui lòng đăng nhập.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Handmade Việt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Handmade Việt</h1>
        <nav>
            <a href="index.php">Trang chủ</a>
            <a href="login.php">Đăng nhập</a>
        </nav>
    </header>
    <main class="auth-container">
        <h2>Đăng ký tài khoản</h2>
        <?php foreach ($errors as $e): ?>
            <p class="error"><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Tên người dùng" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="password" name="confirm" placeholder="Xác nhận mật khẩu" required>
            <button type="submit">Đăng ký</button>
            <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </form>
    </main>
</body>
</html>