-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 11, 2025 lúc 04:51 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `handmade_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `service_id`, `user_id`, `content`, `created_at`) VALUES
(18, 1, 2, 'Đây là bình luận đầu tiên từ admin1 về sản phẩm thủ công!', '2025-06-07 02:50:23'),
(19, 1, 3, 'Rất thích các sản phẩm handmade, cảm ơn user1!', '2025-06-07 02:50:23'),
(20, 1, 2, 'Admin2 đồng ý với ý kiến trên, rất tuyệt!', '2025-06-07 02:50:23'),
(21, 1, 6, 'User2 muốn hỏi thêm về cách làm đồ handmade.', '2025-06-07 02:50:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Nguyễn Văn A', 'vana@example.com', 'Hỗ trợ kỹ thuật', 'Xin chào, tôi gặp lỗi khi đăng bài. Mong được hỗ trợ!', '2025-06-09 08:00:00'),
(2, 'Trần Thị B', 'thib@example.com', 'Hợp tác kinh doanh', 'Tôi muốn hợp tác để phân phối sản phẩm gốm. Liên hệ nhé!', '2025-06-09 09:00:00'),
(3, 'Lê Văn C', 'venc@gmail.com', 'Phản hồi sản phẩm', 'Sản phẩm lụa Vạn Phúc rất đẹp, cảm ơn đội ngũ!', '2025-06-09 10:30:00'),
(4, 'Phạm Thị D', 'thid@yahoo.com', 'Đề xuất cải tiến', 'Gợi ý thêm tính năng tìm kiếm nâng cao.', '2025-06-09 14:15:00'),
(5, 'Hoàng Văn E', 'vene@hotmail.com', 'Khiếu nại', 'Đơn hàng của tôi bị giao trễ, xin kiểm tra lại.', '2025-06-09 16:45:00'),
(6, 'Trần Văn F', 'vanf@outlook.com', 'Hỏi thông tin', 'Muốn biết thêm về chính sách đổi trả.', '2025-06-09 21:09:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 2, 'Bạn đã đăng bài mới: Thêu tay hoa văn truyền thống', 1, '2025-05-10 02:00:00'),
(2, 3, 'Dịch vụ của bạn đã nhận được nhận xét mới từ user1', 0, '2025-05-15 03:00:00'),
(3, 2, 'Dịch vụ của bạn đã nhận được bình luận mới từ user2', 0, '2025-05-15 03:30:00'),
(4, 3, 'Bạn đã đăng nhập thành công!', 1, '2025-05-15 04:00:00'),
(5, 2, 'Chào mừng bạn đến với Handmade Việt!', 1, '2025-05-02 05:00:00'),
(6, 6, 'Chào mừng bạn đến với Handmade Việt!', 1, '2025-06-06 04:26:18'),
(7, 6, 'Bạn đã đăng nhập thành công!', 1, '2025-06-06 04:26:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `service_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 3, 5, 'Rất đẹp, hoa văn tinh xảo, đáng giá tiền!', '2025-05-15 03:00:00'),
(2, 1, 2, 4, 'Chất lượng tốt, nhưng giao hàng hơi chậm.', '2025-05-16 05:00:00'),
(3, 2, 3, 3, 'Hình khắc đẹp nhưng gỗ hơi mỏng.', '2025-05-17 07:00:00'),
(4, 3, 2, 5, 'Giỏ mây rất chắc chắn, tôi rất thích!', '2025-05-18 09:00:00'),
(5, 4, 3, 4, 'Sản phẩm gốm đẹp, nhưng màu sắc không đúng như hình.', '2025-05-19 11:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `services`
--

INSERT INTO `services` (`id`, `user_id`, `title`, `description`, `price`, `image`, `created_at`) VALUES
(1, 2, 'Thêu tay hoa văn truyền thống', 'Dịch vụ thêu tay các hoa văn truyền thống trên vải lụa, phù hợp làm quà tặng.', 500000, 'thue-hoa-van.jpg', '2025-05-10 02:00:00'),
(2, 2, 'Khắc gỗ thủ công', 'Khắc các hình ảnh, chữ viết lên gỗ theo yêu cầu, đảm bảo tinh xảo.', 300000, 'khac-go.jpg', '2025-05-11 04:00:00'),
(3, 3, 'Đan giỏ mây tre', 'Đan giỏ mây tre thủ công, bền đẹp, thân thiện với môi trường.', 200000, 'gio-may-tre.jpg', '2025-05-12 06:00:00'),
(4, 3, 'Làm đồ gốm trang trí', 'Tạo các sản phẩm gốm trang trí như bình hoa, chén nhỏ với họa tiết độc đáo.', 400000, 'do-gom.jpg', '2025-05-13 08:00:00'),
(5, 2, 'May váy truyền thống', 'May váy áo dài truyền thống với chất liệu lụa cao cấp.', 800000, 'ao-dai.jpg', '2025-05-14 10:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `topics`
--

INSERT INTO `topics` (`id`, `user_id`, `title`, `content`, `created_at`) VALUES
(1, 2, 'Hướng dẫn làm đồ gốm', 'Ai có kinh nghiệm làm đồ gốm handmade không? Mình mới bắt đầu, cần tư vấn!', '2025-06-06 10:33:45'),
(2, 3, 'Chia sẻ ý tưởng váy mây', 'Mình muốn thiết kế váy mây, ai có ý tưởng không?', '2025-06-06 10:33:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `topic_comments`
--

CREATE TABLE `topic_comments` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `topic_comments`
--

INSERT INTO `topic_comments` (`id`, `topic_id`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 3, 'Mình đã làm gốm 2 năm, bạn nên bắt đầu với đất sét mềm nhé!', '2025-06-06 10:34:01'),
(2, 2, 2, 'Bạn thử kết hợp mây với vải lụa, sẽ rất đẹp!', '2025-06-06 10:34:01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'user1', 'user1@example.com', '$2y$10$6i2eA5B5p5z9f8k9k2h2g.O3k4m5n6o7p8q9r0s1t2u3v4w5x6y7z8', 'user', '2025-05-02 05:00:00'),
(3, 'user2', 'user2@example.com', '$2y$10$6i2eA5B5p5z9f8k9k2h2g.O3k4m5n6o7p8q9r0s1t2u3v4w5x6y7z8', 'user', '2025-05-03 07:00:00'),
(6, 'subin788', 'subin788@gmail.com', '$2y$10$F8J2rbtcz4qhudpTuuspgueBQDQz3xVJt0FbXG5fWLtbx5voYJ9VO', 'user', '2025-06-06 04:26:18'),
(32, 'subin', 'subin7888@gmail.com', '$2y$10$0wOcdNJoOLy0aQeOOBmHauYZF4ZNpzyOVAmTQu.Wjt3TqyNKYstXy', 'admin', '2025-06-11 02:14:09');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `topic_comments`
--
ALTER TABLE `topic_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `topic_comments`
--
ALTER TABLE `topic_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `topic_comments`
--
ALTER TABLE `topic_comments`
  ADD CONSTRAINT `topic_comments_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  ADD CONSTRAINT `topic_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
