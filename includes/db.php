<?php
// File: includes/db.php
// ---------------------
// Tệp xử lý kết nối đến cơ sở dữ liệu.

require_once __DIR__ . '/../config.php';

try {
    // Tạo kết nối PDO
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    
    // Thiết lập chế độ báo lỗi của PDO thành exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Thiết lập chế độ fetch mặc định
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Nếu kết nối thất bại, hiển thị thông báo lỗi
    die("LỖI: Không thể kết nối đến CSDL. " . $e->getMessage());
}
?>