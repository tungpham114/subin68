<?php
require_once 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Chuyển hướng dựa trên vai trò
                if ($user['role'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error = "Mật khẩu không đúng.";
            }
        } else {
            $error = "Email không tồn tại.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Handmade Việt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .login-banner {
            background: url('uploads/login_banner.jpg') no-repeat center/cover;
            height: 500px;
            position: relative;
            color: red;
        }
        .login-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-[#4a2c2a] text-red p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold"><a href="index.php" class="flex items-center">
                <svg width="40" height="40" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" class="mr-2">
                    <path d="M50 10 Q40 20, 40 30 Q40 40, 50 50 Q60 40, 60 30 Q60 20, 50 10 Z" fill="#2dd4bf" />
                    <path d="M45 30 Q35 40, 40 50 Q45 60, 50 55 Q55 60, 60 50 Q65 40, 55 30 Z" fill="#facc15" />
                    <path d="M40 50 Q30 60, 35 70 Q40 80, 50 75 Q60 80, 65 70 Q70 60, 60 50 Z" fill="#2dd4bf" opacity="0.7" />
                    <path d="M20 70 C25 80, 35 85, 40 80 C45 75, 50 70, 55 75 C60 80, 70 85, 80 70" stroke="#facc15" stroke-width="2" fill="none" />
                    <text x="30" y="90" font-family="Poppins, sans-serif" font-size="10" fill="#2dd4bf" font-weight="600">Handmade</text>
                    <text x="60" y="90" font-family="Poppins, sans-serif" font-size="10" fill="#facc15" font-weight="700">Việt</text>
                </svg>
                Handmade Việt
            </a></h1>
            <nav>
                <a href="index.php" class="mr-4 hover:underline">Trang chủ</a>
                <a href="forum.php" class="mr-4 hover:underline">Diễn đàn</a>
                <a href="register.php" class="hover:underline">Đăng ký</a>
            </nav>
        </div>
    </header>

    <!-- Banner đăng nhập -->
    <div class="login-banner">
        <div class="login-content">
            <h2 class="text-3xl font-bold mb-6 text-center text-white">Đăng nhập</h2>
            <?php if (isset($error)): ?>
                <p class="text-red-500 mb-4 text-center"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="post" class="space-y-6">
                <div>
                    <label for="email" class="block text-red font-medium mb-2">Email:</label>
                    <input type="email" id="email" name="email" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 transition duration-200" placeholder="Nhập email của bạn">
                </div>
                <div>
                    <label for="password" class="block text-red font-medium mb-2">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 transition duration-200" placeholder="Nhập mật khẩu">
                </div>
                <button type="submit" class="w-full bg-red-600 text-white p-3 rounded-md hover:bg-red-700 transition duration-200 font-semibold">Đăng nhập</button>
            </form>
            <p class="mt-4 text-center text-white">Chưa có tài khoản? <a href="register.php" class="text-yellow-300 hover:underline">Đăng ký ngay</a></p>
        </div>
    </div>

    <footer class="bg-teal-600 text-red p-4 mt-6">
        <div class="container mx-auto text-center">
            <p>© 2025 Handmade Việt. All rights reserved.</p>
            <div class="mt-2">
                <a href="#" class="text-yellow-300 hover:underline mx-2">Liên hệ</a> |
                <a href="#" class="text-yellow-300 hover:underline mx-2">Chính sách</a>
            </div>
        </div>
    </footer>
</body>
</html>