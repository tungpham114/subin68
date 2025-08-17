<?php
// Kết nối database
require_once 'includes/db.php';

$pdo = new PDO("mysql:host=localhost;dbname=handmade_db", "root", ""); // Thay 'handmade' nếu cần
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Xử lý thêm người dùng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $role]);
    header("Location: admin.php");
    exit;
}

// Xử lý thêm bài đăng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $title = $_POST['title'];
    $user_id = $_POST['user_id'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("INSERT INTO services (title, user_id, price) VALUES (?, ?, ?)");
    $stmt->execute([$title, $user_id, $price]);
    header("Location: admin.php");
    exit;
}

// Xử lý xóa người dùng
if (isset($_GET['delete_user']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit;
}

// Xử lý xóa bài đăng
if (isset($_GET['delete_service']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit;
}

// Truy vấn dữ liệu từ bảng users
$users_stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $users_stmt->fetchAll();

// Truy vấn dữ liệu từ bảng services và join với users
$services_stmt = $pdo->query("SELECT s.*, u.username FROM services s JOIN users u ON s.user_id = u.id ORDER BY s.id DESC");
$services = $services_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý - Handmade Việt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            margin: 0;
            padding: 0;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 2rem;
        }
        .header .logo {
            width: 40px;
            height: 40px;
            vertical-align: middle;
            margin-right: 10px;
        }
        .nav {
            background: #34495e;
            padding: 0.5rem;
            text-align: right;
        }
        .nav a {
            color: white;
            margin-left: 1rem;
            text-decoration: none;
            font-weight: 600;
        }
        .nav a:hover {
            text-decoration: underline;
        }
        .content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .section-title {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 0.5rem;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .table th, .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background: #ecf0f1;
            color: #2c3e50;
            font-weight: 600;
        }
        .table td {
            color: #34495e;
        }
        .table td:last-child {
            text-align: center;
        }
        .action-btn {
            padding: 0.25rem 0.5rem;
            margin: 0 0.25rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }
        .action-btn.edit {
            background: #3498db;
            color: white;
        }
        .action-btn.delete {
            background: #e74c3c;
            color: white;
        }
        .action-btn:hover {
            opacity: 0.9;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group button {
            padding: 0.5rem 1rem;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            opacity: 0.9;
        }
        .footer {
            text-align: center;
            padding: 1rem;
            background: #2c3e50;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>
            <url('uploads/login_banner.jpg') alt="Logo" class="logo">
            Handmade Việt
        </h1>
        <p>Trang chủ - Quản lý</p>
    </header>

    <nav class="nav">
        <a href="logout.php">Đăng xuất</a>
    </nav>

    <div class="content">
        <h2 class="section-title">Quản Lý</h2>

        <h3 class="section-title">Thêm người dùng</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Tên người dùng:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="role">Vai trò:</label>
                <select name="role" id="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="add_user">Thêm người dùng</button>
            </div>
        </form>

        <h3 class="section-title">Thêm bài đăng</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Tiêu đề:</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div class="form-group">
                <label for="user_id">Người đăng:</label>
                <select name="user_id" id="user_id" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Giá (VNĐ):</label>
                <input type="number" name="price" id="price" step="1000" required>
            </div>
            <div class="form-group">
                <button type="submit" name="add_service">Thêm bài đăng</button>
            </div>
        </form>

        <h3 class="section-title">Danh sách người dùng</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <button class="action-btn edit" onclick="window.location.href='edit_user.php?id=<?= $user['id'] ?>'">Sửa</button>
                            <button class="action-btn delete" onclick="if(confirm('Bạn có chắc muốn xóa?')) window.location.href='admin.php?delete_user=1&id=<?= $user['id'] ?>'">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="section-title">Danh sách bài đăng</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Người đăng</th>
                    <th>Giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?= htmlspecialchars($service['id']) ?></td>
                        <td><?= htmlspecialchars($service['title']) ?></td>
                        <td><?= htmlspecialchars($service['username']) ?></td>
                        <td><?= number_format($service['price'], 0, ',', '.') ?> VNĐ</td>
                        <td>
                            <button class="action-btn edit" onclick="window.location.href='edit_service.php?id=<?= $service['id'] ?>'">Sửa</button>
                            <button class="action-btn delete" onclick="if(confirm('Bạn có chắc muốn xóa?')) window.location.href='admin.php?delete_service=1&id=<?= $service['id'] ?>'">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        <p>© 2025 Handmade Việt. All rights reserved.</p>
    </footer>
</body>
</html>