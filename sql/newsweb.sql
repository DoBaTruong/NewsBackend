-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for online_news
CREATE DATABASE IF NOT EXISTS `online_news` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `online_news`;

-- Dumping structure for table online_news.news
CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint(200) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abstract` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `photo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `featured` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `authors` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `viewer` bigint(200) unsigned NOT NULL DEFAULT '0',
  `comment` bigint(20) DEFAULT '0',
  `published_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table online_news.news: ~13 rows (approximately)
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` (`id`, `title`, `slug`, `abstract`, `photo`, `category_id`, `featured`, `authors`, `viewer`, `comment`, `published_at`) VALUES
	(1, 'Lượng trứng, sữa, trà nên dùng mỗi ngày', 'luong-trung-sua-tra-nen-dung-moi-ngay', 'Chúng ta có thể ăn bánh mì, trứng, chuối, uống sữa, trà mỗi ngày, nhưng đừng quên cân đối số lượng, không sử dụng quá nhiều.', 'img/news/luong-trung-sua-tra-nen-dung-moi-ngay.jpeg', 1, 0, 'An Yên', 1, 0, '2022-02-20 17:59:00'),
	(2, 'Các dấu hiệu ở mắt cảnh báo sức khỏe bất ổn', 'cac-dau-hieu-o-mat-canh-bao-suc-khoe-bat-on', 'Thay đổi thị lực, đau, chảy nước mắt có thể là dấu hiệu cảnh báo vấn đề nghiêm trọng của sức khỏe.', 'img/news/cac-dau-hieu-o-mat-canh-bao-suc-khoe-bat-on.jpeg', 1, 0, 'An Yên', 0, 0, '2022-02-20 18:08:00'),
	(3, 'Loại gia vị giá rẻ ở Việt Nam ngăn ngừa đủ loại bệnh tật', 'loai-gia-vi-gia-re-o-viet-nam-ngan-ngua-du-loai-benh-tat', 'Nghệ thuộc họ gừng, là loại thực phẩm quen thuộc với người Việt Nam, có trong bài thuốc dân gian chữa viêm loét.', 'img/news/loai-gia-vi-gia-re-o-viet-nam-ngan-ngua-du-loai-benh-tat.jpeg', 1, 0, 'An Yên', 0, 0, '2022-02-20 18:14:00'),
	(4, 'Điều gì xảy ra khi uống trà gừng thường xuyên?', 'dieu-gi-xay-ra-khi-uong-tra-gung-thuong-xuyen', 'Trà gừng chống viêm, giảm đau họng, cảm lạnh nhưng nếu uống nhiều có thể gây đầy bụng, tiêu chảy.', 'img/news/dieu-gi-xay-ra-khi-uong-tra-gung-thuong-xuyen.jpeg', 1, 1, 'An Yên', 0, 0, '2022-02-20 18:23:00'),
	(5, 'Các thực phẩm hạn chế mua khi được giảm giá ở siêu thị', 'cac-thuc-pham-han-che-mua-khi-duoc-giam-gia-o-sieu-thi', 'Bạn nên cân nhắc khi mua rau quả cắt sẵn, bánh ngọt, trứng, sữa khi có những đợt giảm giá.', 'img/news/cac-thuc-pham-han-che-mua-khi-duoc-giam-gia-o-sieu-thi.jpeg', 1, 1, 'An Yên', 0, 0, '2022-02-13 18:25:00'),
	(6, 'Đồ uống lành mạnh nhất được người Việt dùng hằng ngày', 'do-uong-lanh-manh-nhat-duoc-nguoi-viet-dung-hang-ngay', 'Theo chuyên gia về trà Ketan Desai, trà xanh là một trong những thức uống lành mạnh nhất. ', 'img/news/do-uong-lanh-manh-nhat-duoc-nguoi-viet-dung-hang-ngay.jpeg', 1, 1, 'An Yên', 0, 0, '2022-02-19 18:29:00'),
	(7, 'Sẽ bãi bỏ hàng loạt thủ tục hành chính về kiểm dịch', 'se-bai-bo-hang-loat-thu-tuc-hanh-chinh-ve-kiem-dich', 'Theo phản ánh của ông Âu Thanh Long, Phó Chủ tịch Hiệp hội chăn nuôi Đông Nam Bộ, hiện các doanh nghiệp kinh doanh trong lĩnh vực chăn nuôi, chế biến gia súc gia cầm ngoài việc chịu nhiều chi phí, lệ phí thú y thì các quy trình quản lý, kiểm dịch thú y cũng gây nhiều khó khăn cho doanh nghiệp.', 'img/news/se-bai-bo-hang-loat-thu-tuc-hanh-chinh-ve-kiem-dich.jpeg', 3, 1, 'Theo Cổng thông tin điện tử Chính phủ', 0, 0, '2022-02-20 18:31:00'),
	(8, 'Bao giờ dán nhãn thực phẩm biến đổi gien?', 'bao-gio-dan-nhan-thuc-pham-bien-doi-gien', 'Ở Việt Nam, việc dán nhãn thực phẩm biến đổi gien chỉ áp dụng đối với thực phẩm bao gói sẵn có thành phần GMO trên 5%, còn đối với thức ăn chăn nuôi vẫn chưa áp dụng.', 'img/news/bao-gio-dan-nhan-thuc-pham-bien-doi-gien.jpeg', 3, 1, 'QUANG HUY', 0, 0, '2022-02-19 18:33:00'),
	(9, 'Xử phạt vi phạm an toàn thực phẩm hơn 1,1 tỉ đồng', 'xu-phat-vi-pham-an-toan-thuc-pham-hon-11-ti-dong', 'Ngày 13.1, đoàn công tác của Bộ NN-PTNT đã kiểm tra, làm việc về an toàn thực phẩm (ATTP) trong sản xuất, kinh doanh nông lâm thủy sản dịp Tết Nguyên đán Nhâm Dần và lễ hội Xuân 2022 tại TP.HCM.', 'img/news/xu-phat-vi-pham-an-toan-thuc-pham-hon-11-ti-dong.jpeg', 3, 1, 'Thanh Niên', 0, 0, '2022-02-19 18:41:00'),
	(10, 'Đà Nẵng: Kiểm nghiệm chả, rượu gạo của các cơ sở chưa đăng ký sản phẩm', 'da-nang-kiem-nghiem-cha-ruou-gao-cua-cac-co-so-chua-dang-ky-san-pham', 'Ngày 17.12, Phòng Cảnh sát môi trường Công an TP.Đà Nẵng cho biết đã lấy các mẫu chả, rượu gạo đưa đi kiểm định để củng cố hồ sơ xử lý các cơ sở sản xuất, kinh doanh.', 'img/news/da-nang-kiem-nghiem-cha-ruou-gao-cua-cac-co-so-chua-dang-ky-san-pham.jpeg', 7, 0, 'Nguyễn Tú', 0, 0, '2022-02-19 18:44:00'),
	(11, 'Test nhanh kiểm tra an toàn thực phẩm', 'test-nhanh-kiem-tra-an-toan-thuc-pham', 'Theo Cục An toàn vệ sinh thực phẩm (Bộ Y tế), vừa qua, TP.HCM và các địa phương đã sử dụng hiệu quả test nhanh trong kiểm tra, giám sát an toàn thực phẩm (ATTP), ngăn ngừa ngộ độc thực phẩm tại các chợ truyền thống.', 'img/news/test-nhanh-kiem-tra-an-toan-thuc-pham.jpeg', 7, 0, 'Thanh Niên', 0, 0, '2022-02-27 18:45:00'),
	(12, 'Bộ Công thương đăng ký cho 88 doanh nghiệp xuất thực phẩm đi Trung Quốc', 'bo-cong-thuong-dang-ky-cho-88-doanh-nghiep-xuat-thuc-pham-di-trung-quoc', 'Ngày 4.11, Bộ Công thương cho biết, Bộ đã hoàn tất việc đăng ký 88 doanh nghiệp (DN) sản xuất thực phẩm xuất khẩu đi Trung Quốc thuộc thẩm quyền phụ trách của Bộ theo quy trình đăng ký nhanh.', 'img/news/bo-cong-thuong-dang-ky-cho-88-doanh-nghiep-xuat-thuc-pham-di-trung-quoc.jpeg', 7, 0, 'Thanh Niên', 0, 0, '2022-02-19 18:46:00'),
	(13, 'Người dân thêm quyền tham gia giám sát nguồn gốc thực phẩm', 'nguoi-dan-them-quyen-tham-gia-giam-sat-nguon-goc-thuc-pham', 'Truy xuất nguồn gốc thực phẩm, dự án do Ban quản lý An toàn thực phẩm TP.Đà Nẵng triển khai nhằm mục tiêu tạo ra thói quen tiêu dùng chủ động để người dân có thể kiểm soát nguồn gốc thực phẩm từ trang trại nuôi trồng đến bàn ăn, đang rất được quan tâm.', 'img/news/nguoi-dan-them-quyen-tham-gia-giam-sat-nguon-goc-thuc-pham.jpeg', 7, 1, 'Nguyễn Tú', 0, 0, '2022-02-19 18:47:00');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
