<?php
// File: templates/home.php
// ------------------------
// Template cho trang chủ.

global $pdo, $lang; // Lấy biến toàn cục

// Lấy 4 sản phẩm mới nhất để hiển thị trên trang chủ
$stmt = $pdo->prepare("
    SELECT p.image, pt.name, pt.slug
    FROM products p
    JOIN product_translations pt ON p.id = pt.product_id
    WHERE pt.language_code = ?
    ORDER BY p.created_at DESC
    LIMIT 4
");
$stmt->execute([CURRENT_LANG]);
$featured_products = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section class="hero-bg min-h-screen flex items-center">
    <div class="container mx-auto px-4 text-center text-white">
        <h1 class="text-5xl lg:text-7xl font-bold mb-6"><?= $lang['hero_title'] ?></h1>
        <p class="text-xl lg:text-2xl mb-8 opacity-90"><?= $lang['hero_subtitle'] ?></p>
        <a href="#products" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-8 rounded-lg text-lg">
            <?= $lang['learn_more'] ?>
        </a>
    </div>
</section>

<!-- Products Section -->
<section id="products" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-blue-700 mb-4"><?= $lang['products_title'] ?></h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto"><?= $lang['products_subtitle'] ?></p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($featured_products as $product): ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
                    <img src="<?= SITE_URL ?>/<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 h-16"><?= htmlspecialchars($product['name']) ?></h3>
                        <a href="<?= SITE_URL ?>/<?= CURRENT_LANG ?>/<?= $lang['routes']['products'] ?>/<?= $product['slug'] ?>" class="w-full text-center block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all">
                            <?= $lang['view_details'] ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?= SITE_URL ?>/<?= CURRENT_LANG ?>/<?= $lang['routes']['products'] ?>" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-4 px-8 rounded-lg text-lg">
                View All Products
            </a>
        </div>
    </div>
</section>