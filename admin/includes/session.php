<?php
// File: /admin/includes/session.php
// ---------------------------------
// Tệp này sẽ được include ở đầu mỗi trang admin được bảo vệ.
// Nó kiểm tra đăng nhập và cung cấp các hàm kiểm tra quyền.

// Bắt đầu session một cách an toàn
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Kiểm tra xem người dùng đã đăng nhập chưa.
 * Nếu chưa, chuyển hướng về trang đăng nhập.
 */
function auth_check() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Kiểm tra xem người dùng có quyền thực hiện một hành động cụ thể không.
 * @param string $feature Ví dụ: 'products', 'seo'
 * @param string $action Ví dụ: 'create', 'edit', 'delete'
 * @return bool
 */
function has_permission($feature, $action) {
    // Admin có tất cả các quyền
    if (isset($_SESSION['permissions']['all']) && $_SESSION['permissions']['all'] === true) {
        return true;
    }

    // Kiểm tra quyền cụ thể
    if (isset($_SESSION['permissions'][$feature]) && in_array($action, $_SESSION['permissions'][$feature])) {
        return true;
    }

    return false;
}
?>