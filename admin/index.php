<?php
// File: /admin/index.php
// ----------------------
// Trang dashboard chính.

require_once '../config.php';
require_once '../includes/db.php';
require_once 'includes/session.php';

// Kiểm tra xem người dùng đã đăng nhập và có quyền truy cập chưa
auth_check();

// Nạp header
include 'includes/header.php';
?>

<!-- Nội dung chính của trang Dashboard -->
<h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Card: Total Products -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-box-open fa-2x"></i>
            </div>
            <div class="ml-4">
                <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
                    $total_products = $stmt->fetchColumn();
                ?>
                <p class="text-gray-600">Total Products</p>
                <p class="text-2xl font-bold"><?= $total_products ?></p>
            </div>
        </div>
    </div>

    <!-- Card: Total Users -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div class="ml-4">
                 <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
                    $total_users = $stmt->fetchColumn();
                ?>
                <p class="text-gray-600">Registered Users</p>
                <p class="text-2xl font-bold"><?= $total_users ?></p>
            </div>
        </div>
    </div>
    <!-- Thêm các card thống kê khác nếu muốn -->
</div>

<!-- Thêm các phần khác của dashboard ở đây -->

<?php
// Nạp footer
include 'includes/footer.php';
?>
