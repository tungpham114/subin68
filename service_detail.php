<?php
require_once 'includes/db.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$service_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT s.*, u.username FROM services s JOIN users u ON s.user_id = u.id WHERE s.id = ?");
$stmt->execute([$service_id]);
$service = $stmt->fetch();

if (!$service) {
    header("Location: index.php");
    exit;
}

// Xử lý gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    if ($rating >= 1 && $rating <= 5) {
        $stmt = $pdo->prepare("INSERT INTO reviews (service_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$service_id, $_SESSION['user_id'], $rating, $comment]);
        header("Location: service_detail.php?id=$service_id");
        exit;
    }
}

// Lấy danh sách đánh giá
$reviews_stmt = $pdo->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.service_id = ? ORDER BY r.created_at DESC");
$reviews_stmt->execute([$service_id]);
$reviews = $reviews_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($service['title']) ?> - Handmade Việt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-100">
    <header class="bg-teal-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold"><a href="index.php">Handmade Việt</a></h1>
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
    <main class="container mx-auto p-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-teal-700 mb-4"><?= htmlspecialchars($service['title']) ?></h2>
            <?php if ($service['image']): ?>
                <img src="uploads/<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['title']) ?>" class="w-full h-64 object-cover rounded-md mb-4">
            <?php else: ?>
                <img src="https://via.placeholder.com/300x200?text=Không+có+hình+ảnh" alt="No Image" class="w-full h-64 object-cover rounded-md mb-4">
            <?php endif; ?>
            <p class="text-gray-700 mb-4"><?= nl2br(htmlspecialchars($service['description'])) ?></p>
            <p class="text-teal-600 font-semibold mb-2">Giá: <?= number_format($service['price']) ?> VNĐ</p>
            <p class="text-gray-600 text-sm">Đăng bởi: <?= htmlspecialchars($service['username']) ?> - <?= date('d/m/Y H:i', strtotime($service['created_at'])) ?></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mt-6">
            <h3 class="text-xl font-semibold text-teal-700 mb-4">Đánh giá và nhận xét</h3>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" class="mb-6">
                    <div class="mb-4">
                        <label class="block text-gray-700">Đánh giá (1-5 sao):</label>
                        <select name="rating" class="p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="1">1 sao</option>
                            <option value="2">2 sao</option>
                            <option value="3">3 sao</option>
                            <option value="4">4 sao</option>
                            <option value="5">5 sao</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Nhận xét:</label>
                        <textarea name="comment" rows="3" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                    </div>
                    <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700">Gửi đánh giá</button>
                </form>
            <?php else: ?>
                <p class="text-gray-600 mb-4">Vui lòng <a href="login.php" class="text-teal-600 hover:underline">đăng nhập</a> để gửi đánh giá.</p>
            <?php endif; ?>

            <?php if (!empty($reviews)): ?>
                <div class="space-y-4">
                    <?php foreach ($reviews as $review): ?>
                        <div class="border-t pt-4">
                            <p class="text-gray-600 text-sm">Đăng bởi: <?= htmlspecialchars($review['username']) ?> - <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></p>
                            <p class="text-yellow-500">Đánh giá: <?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?></p>
                            <p class="text-gray-700"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-600">Chưa có đánh giá nào.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>