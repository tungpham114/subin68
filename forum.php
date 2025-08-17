<?php
require_once 'includes/db.php';
session_start();

// Xử lý tạo chủ đề mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_topic']) && isset($_SESSION['user_id'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO topics (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $title, $content]);
        header("Location: forum.php");
        exit;
    } else {
        $error = "Vui lòng điền đầy đủ tiêu đề và nội dung.";
    }
}

// Xử lý gửi bình luận
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_SESSION['user_id'])) {
    $topic_id = (int)$_POST['topic_id'];
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO topic_comments (topic_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$topic_id, $_SESSION['user_id'], $content]);
        header("Location: forum.php#topic-$topic_id");
        exit;
    }
}

// Lấy danh sách chủ đề
$topics_stmt = $pdo->query("SELECT t.*, u.username FROM topics t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC");
$topics = $topics_stmt->fetchAll();

// Lấy bình luận cho từng chủ đề
$comments = [];
foreach ($topics as $topic) {
    $stmt = $pdo->prepare("SELECT tc.*, u.username FROM topic_comments tc JOIN users u ON tc.user_id = u.id WHERE tc.topic_id = ? ORDER BY tc.created_at ASC");
    $stmt->execute([$topic['id']]);
    $comments[$topic['id']] = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diễn đàn - Handmade Việt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .forum-banner {
            background: url('uploads/forum_banner.jpg') no-repeat center/cover;
            height: 500px;
            position: relative;
            color: white;
        }
        .forum-content {
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

    <!-- Banner diễn đàn -->
    <div class="forum-banner">
        <div class="forum-content">
            <h2 class="text-3xl font-bold">Diễn đàn Handmade Việt</h2>
            <p class="mt-2">Chia sẻ và thảo luận về các sản phẩm thủ công</p>
        </div>
    </div>

    <main class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6 text-teal-700">Diễn đàn thảo luận</h2>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Tạo chủ đề mới</h3>
                <?php if (isset($error)): ?>
                    <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <form method="post" class="space-y-4">
                    <div>
                        <label class="block text-gray-700">Tiêu đề:</label>
                        <input type="text" name="title" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-gray-700">Nội dung:</label>
                        <textarea name="content" rows="4" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                    </div>
                    <button type="submit" name="create_topic" class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700">Đăng chủ đề</button>
                </form>
            </div>
        <?php else: ?>
            <p class="text-gray-600 mb-6">Vui lòng <a href="login.php" class="text-teal-600 hover:underline">đăng nhập</a> để tham gia thảo luận.</p>
        <?php endif; ?>

        <div class="space-y-6">
            <?php foreach ($topics as $topic): ?>
                <div id="topic-<?= $topic['id'] ?>" class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-teal-700"><?= htmlspecialchars($topic['title']) ?></h3>
                    <p class="text-gray-600 text-sm mb-2">Đăng bởi: <?= htmlspecialchars($topic['username']) ?> - <?= date('d/m/Y H:i', strtotime($topic['created_at'])) ?></p>
                    <p class="text-gray-700 mb-4"><?= nl2br(htmlspecialchars($topic['content'])) ?></p>
                    
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Bình luận</h4>
                    <?php if (!empty($comments[$topic['id']])): ?>
                        <div class="space-y-4 mb-4">
                            <?php foreach ($comments[$topic['id']] as $comment): ?>
                                <div class="border-t pt-2">
                                    <p class="text-gray-600 text-sm">Đăng bởi: <?= htmlspecialchars($comment['username']) ?> - <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></p>
                                    <p class="text-gray-700"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-600">Chưa có bình luận nào.</p>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form method="post" class="mt-4">
                            <input type="hidden" name="topic_id" value="<?= $topic['id'] ?>">
                            <textarea name="content" rows="2" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Viết bình luận..."></textarea>
                            <button type="submit" name="comment" class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 mt-2">Gửi bình luận</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
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