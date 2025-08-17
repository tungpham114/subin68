<?php
require_once 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = (int)$_POST['price'];

    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024;
        $file_type = mime_content_type($_FILES['image']['tmp_name']);
        $file_size = $_FILES['image']['size'];

        if (!in_array($file_type, $allowed_types)) {
            $errors[] = 'Chỉ chấp nhận file JPEG, PNG hoặc GIF.';
        } elseif ($file_size > $max_size) {
            $errors[] = 'Dung lượng file tối đa là 5MB.';
        } else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = uniqid() . '.' . $ext;
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image);
        }
    }

    if (empty($title) || empty($description) || $price <= 0) {
        $errors[] = 'Vui lòng điền đầy đủ thông tin và giá phải lớn hơn 0.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO services (user_id, title, description, price, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $title, $description, $price, $image]);
        
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], "Bạn đã đăng bài mới: " . htmlspecialchars($title)]);
        
        $success = 'Đăng bài thành công!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng bài - Handmade Việt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Handmade Việt</h1>
        <nav>
            <span>Xin chào, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <a href="index.php">Trang chủ</a>
            <a href="logout.php">Đăng xuất</a>
        </nav>
    </header>
    <main class="auth-container">
        <h2>Đăng bài dịch vụ mới</h2>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $e): ?>
                <p class="error"><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Tiêu đề" required>
            <textarea name="description" placeholder="Mô tả dịch vụ" rows="5" required></textarea>
            <input type="number" name="price" placeholder="Giá (VNĐ)" min="1" required>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Đăng bài</button>
        </form>
    </main>
</body>
</html>