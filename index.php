<?php
require_once 'includes/db.php';
session_start();

// Phân trang
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Bộ lọc
$keyword = isset($_GET['search']) ? '%' . trim($_GET['search']) . '%' : null;
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'created_at_desc';
$price_min = isset($_GET['price_min']) ? (int)$_GET['price_min'] : null;
$price_max = isset($_GET['price_max']) ? (int)$_GET['price_max'] : null;

$where = [];
$params = [];

if ($keyword) {
    $where[] = '(s.title LIKE ? OR s.description LIKE ?)';
    $params[] = $keyword;
    $params[] = $keyword;
}

if ($price_min !== null) {
    $where[] = 's.price >= ?';
    $params[] = $price_min;
}

if ($price_max !== null) {
    $where[] = 's.price <= ?';
    $params[] = $price_max;
}

$where_sql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$order_by = 's.created_at DESC';
if ($sort_by === 'price_asc') {
    $order_by = 's.price ASC';
} elseif ($sort_by === 'price_desc') {
    $order_by = 's.price DESC';
}

// Đếm tổng số bài đăng
$count_sql = "SELECT COUNT(*) FROM services s $where_sql";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_services = $count_stmt->fetchColumn();
$total_pages = ceil($total_services / $limit);

// Lấy danh sách bài đăng
$sql = "SELECT s.*, u.username FROM services s JOIN users u ON s.user_id = u.id $where_sql ORDER BY $order_by LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll();

// Lấy thông báo
$notifications = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
    $notifications = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handmade Việt</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .banner {
            background: url('uploads/handmade_banner.jpg') no-repeat center/cover;
            height: 400px;
            position: relative;
            color: while;
        }
        .banner-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }
        .banner-content h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .banner-content p {
            font-size: 1.2rem;
        }
        header {
            position: relative;
            z-index: 10; /* Đảm bảo header nằm trên banner */
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="mr-4">Xin chào, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                    <a href="create_service.php" class="mr-4 hover:underline">Đăng bài</a>
                    <a href="forum.php" class="mr-4 hover:underline">Diễn đàn</a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin.php" class="mr-4 hover:underline">Quản lý</a>
                    <?php endif; ?>
                    <a href="gioi-thieu.php" class="mr-4 hover:underline">Giới thiệu</a>
        <a href="lien-he.php" class="mr-4 hover:underline">Liên hệ</a>
                    <a href="logout.php" class="hover:underline">Đăng xuất</a>
                <?php else: ?>
                    <a href="login.php" class="mr-4 hover:underline">Đăng nhập</a>
                    <a href="register.php" class="hover:underline">Đăng ký</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Banner -->
    <div class="banner">
        <div class="banner-content">
            <h1 class="text-white">Khám Phá Handmade Việt Nam</h1>
            <p class="text-white">Tận hưởng những sản phẩm thủ công độc đáo và truyền thống</p>
            <a href="#services" class="mt-4 inline-block bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Khám Phá Ngay</a>
        </div>
    </div>

    <main class="container mx-auto p-6">
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                    <?= htmlspecialchars($notif['message']) ?>
                </div>
            <?php endforeach; ?>
            <?php
            $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
            $stmt->execute([$_SESSION['user_id']]);
            ?>
        <?php endif; ?>

        <h2 class="text-3xl font-bold mb-6 text-teal-700" id="services">Danh sách dịch vụ handmade</h2>
        <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold text-teal-600 mb-4">Tìm kiếm sản phẩm</h2>
            <form id="searchForm" action="search.php" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" id="searchName" name="searchName" placeholder="Tên sản phẩm" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <input type="text" id="searchBrand" name="searchBrand" placeholder="Hãng sản xuất" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <select id="sortBy" name="sortBy" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="price_asc">Giá thấp đến cao</option>
                    <option value="price_desc">Giá cao đến thấp</option>
                    <option value="newest">Mới nhất</option>
                </select>
                <button type="submit" class="bg-blue-600 text-black p-3 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-700 hover:shadow-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </form>
        </div>

        <div id="searchResults" class="grid grid-cols-1 md:grid-cols-3 gap-6"></div>
    </div>

    <script>
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('search.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                let resultsDiv = document.getElementById('searchResults');
                resultsDiv.innerHTML = '';
                if (data.length > 0 && !data.error) {
                    data.forEach(item => {
                        resultsDiv.innerHTML += `
                            <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                                <img src="${item.image || 'https://via.placeholder.com/300'}" alt="${item.title}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-xl font-semibold text-teal-600">${item.title}</h3>
                                    <p class="text-gray-600 mt-2">${item.description || 'Chưa có mô tả'}</p>
                                    <p class="text-lg font-bold text-teal-500 mt-2">Giá: ${item.price} VNĐ</p>
                                    <p class="text-sm text-gray-500">Hãng: ${item.brand || 'Chưa có'}</p>
                                    <p class="text-sm text-gray-400">Ngày tạo: ${new Date(item.created_at).toLocaleDateString()}</p>
                                </div>
                            </div>`;
                    });
                } else {
                    resultsDiv.innerHTML = '<p class="text-center text-gray-500">Không tìm thấy sản phẩm hoặc có lỗi!</p>';
                }
            })
            .catch(error => console.error('Lỗi:', error));
        });
    </script>
        <hr class="my-6 border-gray-300">
        <?php if (empty($services)): ?>
            <p class="text-gray-600">Không tìm thấy dịch vụ nào.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($services as $s): ?>
                    <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <?php if ($s['image']): ?>
                            <img src="uploads/<?= htmlspecialchars($s['image']) ?>" alt="<?= htmlspecialchars($s['title']) ?>" class="w-full h-48 object-cover rounded-md mb-4">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x200?text=Không+có+hình+ảnh" alt="No Image" class="w-full h-48 object-cover rounded-md mb-4">
                        <?php endif; ?>
                        <a href="service_detail.php?id=<?= $s['id'] ?>">
                            <h3 class="text-lg font-semibold text-teal-700 hover:underline"><?= htmlspecialchars($s['title']) ?></h3>
                        </a>
                        <p class="text-gray-700 mb-2 line-clamp-3"><?= nl2br(htmlspecialchars($s['description'])) ?></p>
                        <p class="text-teal-600 font-semibold mb-2">Giá: <?= number_format($s['price']) ?> VNĐ</p>
                        <p class="text-gray-600 text-sm">Đăng bởi: <?= htmlspecialchars($s['username']) ?> - <?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-6 flex justify-center space-x-2">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i === $page): ?>
                        <span class="px-4 py-2 bg-teal-600 text-white rounded-md"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>&search=<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>&sort_by=<?= $sort_by ?>&price_min=<?= $price_min ?>&price_max=<?= $price_max ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
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