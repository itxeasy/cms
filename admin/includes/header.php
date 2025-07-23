<?php
// File: /admin/includes/header.php
// --------------------------------
// Header cho tất cả các trang trong khu vực admin.

// Tệp session.php đã được include từ trang gọi nó.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link:hover { background-color: #3b82f6; color: white; }
        .sidebar-link.active { background-color: #2563eb; color: white; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-gray-700">
                ITXExport Admin
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="index.php" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 active">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>Dashboard</span>
                </a>
                
                <?php if (has_permission('products', 'view')): ?>
                <a href="products.php" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200">
                    <i class="fas fa-box-open w-6"></i>
                    <span>Products</span>
                </a>
                <?php endif; ?>

                <?php if (has_permission('seo', 'edit')): ?>
                <a href="seo.php" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200">
                    <i class="fas fa-chart-line w-6"></i>
                    <span>SEO Settings</span>
                </a>
                <?php endif; ?>

                <?php if (has_permission('all', true)): // Chỉ admin mới thấy ?>
                <a href="users.php" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200">
                    <i class="fas fa-users-cog w-6"></i>
                    <span>Users & Roles</span>
                </a>
                <a href="backup.php" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200">
                    <i class="fas fa-database w-6"></i>
                    <span>Backup</span>
                </a>
                <?php endif; ?>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="bg-white shadow-md p-4 flex justify-between items-center">
                <div>
                    <!-- Có thể thêm breadcrumbs ở đây -->
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</span>
                    <a href="logout.php" class="text-gray-500 hover:text-red-600" title="Logout">
                        <i class="fas fa-sign-out-alt text-xl"></i>
                    </a>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6 overflow-y-auto">

