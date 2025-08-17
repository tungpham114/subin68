<?php
require_once 'includes/db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu - Handmade Việt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .intro-banner {
            background: url('uploads/login_banner.jpg') no-repeat center/cover;
            height: 400px;
            position: relative;
            color: while ;
        }
        .intro-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }
        .team-section img {
            max-width: 150px;
            border-radius: 50%;
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

    <!-- Banner giới thiệu -->
    <div class="intro-banner">
        <div class="intro-content">
            <h2 class="text-3xl font-bold">Giới thiệu về Handmade Việt</h2>
            <p class="mt-2">Khám phá câu chuyện và sứ mệnh của chúng tôi</p>
        </div>
    </div>

    <main class="container mx-auto p-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-teal-700 mb-4">Câu chuyện Handmade Việt</h2>
            <p class="text-gray-700 mb-4">
                Handmade Việt được khởi xướng bởi Nhóm Handmade Việt , vào tháng 01/2025 với mong muốn bảo tồn và quảng bá ngành thủ công truyền thống Việt Nam. Ý tưởng xuất phát từ thực tế rằng nhiều làng nghề như gốm Bát Tràng, lụa Vạn Phúc đang dần mai một do thiếu kênh tiếp thị hiệu quả. Chúng tôi đã dành 6 tháng để nghiên cứu, thiết kế, và phát triển nền tảng này, với mục tiêu tạo cầu nối giữa nghệ nhân và người tiêu dùng.
            </p>
            <p class="text-gray-700 mb-4">
                Mỗi sản phẩm trên Handmade Việt không chỉ là một món đồ, mà còn là câu chuyện văn hóa, sự khéo léo và tâm huyết của các nghệ nhân. Chúng tôi cam kết mang đến một không gian số hóa để bảo tồn di sản và hỗ trợ cộng đồng thủ công.
            </p>

            <h2 class="text-2xl font-bold text-teal-700 mb-4 mt-6">Tầm nhìn và Sứ mệnh</h2>
            <p class="text-gray-700 mb-4">
                Tầm nhìn của chúng tôi là trở thành nền tảng hàng đầu về sản phẩm thủ công tại Việt Nam, hỗ trợ ít nhất 50 nghệ nhân trong năm đầu tiên và mở rộng sang 5 ngôn ngữ (Việt, Anh, Pháp, Trung, Nhật) trong vòng 3 năm. Sứ mệnh là kết nối cộng đồng, nâng cao nhận thức về giá trị thủ công, và thúc đẩy kinh tế bền vững cho các làng nghề.
            </p>

            <h2 class="text-2xl font-bold text-teal-700 mb-4 mt-6">Đội ngũ phát triển</h2>
            <div class="team-section grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <img src="https://via.placeholder.com/150?text=Thành+viên+1" alt="Thành viên 1" class="mx-auto">
                    <p class="text-teal-700 font-semibold mt-2">Nguyễn Văn A</p>
                    <p class="text-gray-600">Lập trình viên trưởng</p>
                </div>
                <div class="text-center">
                    <img src="https://via.placeholder.com/150?text=Thành+viên+2" alt="Thành viên 2" class="mx-auto">
                    <p class="text-teal-700 font-semibold mt-2">Trần Thị B</p>
                    <p class="text-gray-600">Thiết kế giao diện</p>
                </div>
                <div class="text-center">
                    <img src="https://via.placeholder.com/150?text=Thành+viên+3" alt="Thành viên 3" class="mx-auto">
                    <p class="text-teal-700 font-semibold mt-2">Lê Văn C</p>
                    <p class="text-gray-600">Quản trị hệ thống</p>
                </div>
            </div>
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