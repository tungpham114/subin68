<?php
require_once 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // Lưu thông tin liên hệ vào cơ sở dữ liệu (bảng contacts nếu có, hoặc gửi email)
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $subject, $message]);
        $success = "Cảm ơn bạn! Tin nhắn đã được gửi thành công.";
    } else {
        $error = "Vui lòng điền đầy đủ thông tin.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ - Handmade Việt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .contact-banner {
            background: url('uploads/login_banner.jpg') no-repeat center/cover;
            height: 400px;
            position: relative;
            color: white;
        }
        .contact-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-teal-600 text-red p-4 shadow-md">
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="mr-4">Xin chào, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                    <a href="create_service.php" class="mr-4 hover:underline">Đăng bài</a>
                    <a href="forum.php" class="mr-4 hover:underline">Diễn đàn</a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin.php" class="mr-4 hover:underline">Quản lý</a>
                    <?php endif; ?>
                    <a href="logout.php" class="hover:underline">Đăng xuất</a>
                <?php else: ?>
                    <a href="login.php" class="mr-4 hover:underline">Đăng nhập</a>
                    <a href="register.php" class="hover:underline">Đăng ký</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Banner liên hệ -->
    <div class="contact-banner">
        <div class="contact-content">
            <h2 class="text-3xl font-bold">Liên hệ với chúng tôi</h2>
            <p class="mt-2">Hỗ trợ và hợp tác cùng Handmade Việt</p>
        </div>
    </div>

    <main class="container mx-auto p-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-teal-700 mb-4">Thông tin liên hệ</h2>
            <p class="text-gray-700 mb-2"><strong>Email:</strong> handmadeviet@group0.com</p>
            <p class="text-gray-700 mb-2"><strong>Điện thoại:</strong> 090xxxxxxx</p>
            <p class="text-gray-700 mb-4"><strong>Địa chỉ:</strong> Trường Đại học Thương mại, Hà Nội</p>

            <h2 class="text-2xl font-bold text-teal-700 mb-4 mt-6">Gửi tin nhắn cho chúng tôi</h2>
            <?php if (isset($error)): ?>
                <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="text-green-500 mb-4"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <form method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700">Họ và tên:</label>
                    <input type="text" name="name" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">Email:</label>
                    <input type="email" name="email" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">Chủ đề:</label>
                    <input type="text" name="subject" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                </div>
                <div>
                    <label class="block text-gray-700">Nội dung:</label>
                    <textarea name="message" rows="4" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" required></textarea>
                </div>
                <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700">Gửi tin nhắn</button>
            </form>
        </div>
    </main>

     <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-hand-holding-heart text-2xl"></i>
                        <h3 class="text-xl font-bold">Handmade Việt</h3>
                    </div>
                    <p class="text-gray-400 mb-4">Cộng đồng chia sẻ và đánh giá dịch vụ handmade chất lượng nhất Việt Nam</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Về chúng tôi</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Giới thiệu</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Đội ngũ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Nguyên tắc cộng đồng</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Việc làm</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Liên kết</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Dịch vụ nổi bật</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Thảo luận</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Đánh giá</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Hướng dẫn mua hàng</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Liên hệ</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2 text-gray-400"></i>
                            <span class="text-gray-400">Số 123, đường ABC, phường XYZ, Hà Nội</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-2 text-gray-400"></i>
                            <span class="text-gray-400">0968.688.688</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-gray-400"></i>
                            <span class="text-gray-400">contact@handmadeviet.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500">
                <p>© 2025 Handmade Việt. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>
</body>
</html>