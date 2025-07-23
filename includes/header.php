<?php
// File: includes/header.php
// -------------------------
// Lưu ý: Đây là phiên bản rút gọn dựa trên HTML của bạn.

global $pdo, $lang; // Lấy biến toàn cục

// Lấy danh sách ngôn ngữ để hiển thị trong bộ chuyển đổi
$stmt = $pdo->query("SELECT name, code FROM languages");
$all_languages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= CURRENT_LANG ?>" dir="<?= LANG_DIRECTION ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Các thẻ Meta Title, Description, Keywords sẽ được đặt động ở mỗi trang -->
    <title>ITXExport - <?= $lang['nav_home'] ?></title> 
    <meta name="description" content="<?= $lang['hero_subtitle'] ?>">
    <meta name="keywords" content="vietnam export, frozen food, dried seafood, agricultural products">

    <!-- Tailwind CSS & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-bg { background-image: linear-gradient(rgba(30, 64, 175, 0.8), rgba(59, 130, 246, 0.8)), url('<?= SITE_URL ?>/assets/images/hero-background.jpg'); background-size: cover; background-position: center; }
        /* Thêm các style khác từ file HTML của bạn vào đây */
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <a href="<?= SITE_URL ?>/<?= CURRENT_LANG ?>" class="flex items-center space-x-3">
                    <div class="w-16 h-12 rounded-lg flex items-center justify-center">
                        <img src="<?= SITE_URL ?>/assets/images/logo.svg" alt="Logo">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">ITXExport</h1>
                        <p class="text-sm text-blue-600 font-medium">Vietnam's Finest Exports</p>
                    </div>
                </a>
                
                <!-- Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="<?= SITE_URL ?>/<?= CURRENT_LANG ?>/home" class="text-gray-700 hover:text-blue-600 font-medium"><?= $lang['nav_home'] ?></a>
                    <a href="<?= SITE_URL ?>/<?= CURRENT_LANG ?>/<?= $lang['routes']['products'] ?>" class="text-gray-700 hover:text-blue-600 font-medium"><?= $lang['nav_products'] ?></a>
                    <!-- Thêm các link khác -->
                </nav>
                
                <!-- Language Toggle -->
                <div class="flex items-center space-x-2">
                    <?php foreach ($all_languages as $language): ?>
                        <a href="<?= SITE_URL ?>/<?= $language['code'] ?>" 
                           class="px-3 py-2 text-sm font-medium rounded-lg 
                                  <?= (CURRENT_LANG == $language['code']) 
                                      ? 'bg-blue-600 text-white' 
                                      : 'text-gray-600 hover:text-blue-600 border border-gray-300' ?>">
                           <?= strtoupper($language['code']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </header>

