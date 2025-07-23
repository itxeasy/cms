<?php
// File: config.php
// -----------------
// Tệp cấu hình chính cho trang web.

// Cấu hình CSDL
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user'); // <-- THAY ĐỔI
define('DB_PASS', 'your_db_password'); // <-- THAY ĐỔI
define('DB_NAME', 'export_db'); // <-- THAY ĐỔI

// Cấu hình trang web
define('SITE_URL', 'http://localhost/your-project-folder'); // <-- THAY ĐỔI
define('SITE_NAME', 'ITXExport');

// Bật báo cáo lỗi để dễ dàng gỡ lỗi trong quá trình phát triển
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Thiết lập múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Bắt đầu session để lưu trữ ngôn ngữ hiện tại
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>


