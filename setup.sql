-- Cơ sở dữ liệu: `export_db`
-- Bảng để lưu các ngôn ngữ được hỗ trợ
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên ngôn ngữ (e.g., English)',
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã ngôn ngữ (e.g., en, vi)',
  `direction` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ltr' COMMENT 'Hướng văn bản (ltr hoặc rtl cho tiếng Ả Rập)',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chèn các ngôn ngữ ban đầu
INSERT INTO `languages` (`name`, `code`, `direction`, `is_default`) VALUES
('English', 'en', 'ltr', 1),
('Tiếng Việt', 'vi', 'ltr', 0),
('日本語', 'ja', 'ltr', 0),
('العربية', 'ar', 'rtl', 0);

-- Bảng để lưu thông tin sản phẩm gốc
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đường dẫn tới ảnh đại diện',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng để lưu nội dung dịch của sản phẩm
CREATE TABLE `product_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `language_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL thân thiện (e.g., frozen-pangasius-fillet)',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specifications` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thông số kỹ thuật, lưu dưới dạng JSON',
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_lang_slug` (`product_id`,`language_code`),
  KEY `product_id` (`product_id`),
  KEY `language_code` (`language_code`),
  CONSTRAINT `product_translations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_translations_ibfk_2` FOREIGN KEY (`language_code`) REFERENCES `languages` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chèn dữ liệu mẫu cho một vài sản phẩm dựa trên hình ảnh bạn cung cấp
-- Sản phẩm 1: Dưa chuột muối
INSERT INTO `products` (`id`, `sku`, `image`) VALUES (1, 'PK-CUC-01', 'assets/images/pickled-cucumber.jpg');
INSERT INTO `product_translations` (`product_id`, `language_code`, `name`, `slug`, `description`, `meta_title`, `meta_keywords`) VALUES
(1, 'en', 'Pickled Cucumber / Gherkin', 'pickled-cucumber-gherkin', 'High-quality pickled cucumbers from Vietnam, available in various sizes and packaging options.', 'Premium Pickled Cucumber from Vietnam | ITXExport', 'pickled cucumber, gherkin, vietnamese pickles, canned vegetables'),
(1, 'vi', 'Dưa chuột bao tử muối', 'dua-chuot-bao-tu-muoi', 'Dưa chuột bao tử muối chất lượng cao từ Việt Nam, có nhiều kích cỡ và lựa chọn đóng gói.', 'Dưa Chuột Bao Tử Muối Xuất Khẩu | ITXExport', 'dưa chuột muối, dưa bao tử, rau củ đóng hộp, xuất khẩu nông sản');

-- Sản phẩm 2: Nấm rơm đông lạnh
INSERT INTO `products` (`id`, `sku`, `image`) VALUES (2, 'FR-MUSH-01', 'assets/images/frozen-mushrooms.jpg');
INSERT INTO `product_translations` (`product_id`, `language_code`, `name`, `slug`, `description`, `meta_title`, `meta_keywords`) VALUES
(2, 'en', 'Frozen Straw Mushrooms', 'frozen-straw-mushrooms', 'Freshly frozen straw mushrooms, perfect for various culinary uses. Packed in 1kg bags, loaded 10 tons per 20ft container.', 'Frozen Straw Mushrooms Exporter | ITXExport', 'frozen mushrooms, straw mushrooms, frozen vegetables, iqf mushrooms, vietnam frozen food'),
(2, 'vi', 'Nấm Rơm Đông Lạnh', 'nam-rom-dong-lanh', 'Nấm rơm tươi được cấp đông ngay, lý tưởng cho nhiều món ăn. Đóng gói 1kg/túi, container 20ft chứa 10 tấn.', 'Xuất Khẩu Nấm Rơm Đông Lạnh | ITXExport', 'nấm rơm đông lạnh, nấm đông lạnh, rau củ đông lạnh, nông sản việt nam');

-- Sản phẩm 3: Dứa đông lạnh IQF
INSERT INTO `products` (`id`, `sku`, `image`) VALUES (3, 'IQF-PINE-01', 'assets/images/iqf-pineapple.jpg');
INSERT INTO `product_translations` (`product_id`, `language_code`, `name`, `slug`, `description`, `meta_title`, `meta_keywords`) VALUES
(3, 'en', 'IQF Pineapple', 'iqf-pineapple', 'Type: Queen. Size: 10x10 mm. Packing: 10 kgs/box. Loading: 23 tons/cont 40RF.', 'IQF Pineapple Exporter - Queen Variety | ITXExport', 'iqf pineapple, frozen pineapple, frozen fruits, queen pineapple, vietnam tropical fruits'),
(3, 'vi', 'Dứa Đông Lạnh IQF', 'dua-dong-lanh-iqf', 'Loại: Nữ hoàng (Queen). Kích cỡ: 10x10 mm. Đóng gói: 10 kg/thùng. Tải trọng: 23 tấn/cont 40RF.', 'Dứa Đông Lạnh IQF (Nữ Hoàng) Xuất Khẩu | ITXExport', 'dứa đông lạnh iqf, dứa nữ hoàng, trái cây đông lạnh, xuất khẩu trái cây');

UPDATE `product_translations`
SET `specifications` = '{"type": "Queen", "size": "10x10 mm", "packing": "10 kgs/box", "loading": "23 tons/cont 40RF"}'
WHERE `product_id` = 3 AND `language_code` = 'en';

UPDATE `product_translations`
SET `specifications` = '{"loai": "Nữ hoàng (Queen)", "kich_co": "10x10 mm", "dong_goi": "10 kg/thùng", "tai_trong": "23 tấn/cont 40RF"}'
WHERE `product_id` = 3 AND `language_code` = 'vi';