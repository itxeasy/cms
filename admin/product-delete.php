<?php
// File: /admin/product-delete.php
// -------------------------------
// Xử lý logic xóa sản phẩm.

require_once '../config.php';
require_once '../includes/db.php';
require_once 'includes/session.php';

// Kiểm tra quyền: người dùng phải có quyền 'delete' trong mục 'products'
auth_check();
if (!has_permission('products', 'delete')) {
    echo "You do not have permission to delete products.";
    exit;
}

// Kiểm tra xem ID có hợp lệ không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php?status=error");
    exit;
}

$product_id = $_GET['id'];

try {
    // Xóa sản phẩm khỏi bảng `products`. 
    // Do có ràng buộc `ON DELETE CASCADE`, các bản dịch liên quan trong `product_translations` cũng sẽ tự động bị xóa.
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    
    // Chuyển hướng về trang danh sách với thông báo thành công
    header("Location: products.php?status=deleted");
    exit;

} catch (PDOException $e) {
    // Nếu có lỗi CSDL, chuyển hướng với thông báo lỗi
    // Ghi log lỗi: error_log($e->getMessage());
    header("Location: products.php?status=error");
    exit;
}
