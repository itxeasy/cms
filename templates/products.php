<?php
// File: templates/products.php
// ----------------------------
// Template để hiển thị danh sách tất cả sản phẩm.

global $pdo, $lang; // Lấy các biến toàn cục

// Truy vấn để lấy tất cả sản phẩm cho ngôn ngữ hiện tại
$stmt = $pdo->prepare("
    SELECT p.image, pt.name, pt.slug, pt.description
    FROM products p
    JOIN product_translations pt ON p.id = pt.product_id
    WHERE pt.language_code = ?
    ORDER BY p.created_at DESC
");
$stmt->execute([CURRENT_LANG]);
$all_products = $stmt->fetchAll();

// Cập nhật thẻ meta cho trang danh sách sản phẩm
// (Bạn có thể làm cho nó phức tạp hơn bằng cách thêm một bảng cho meta trang)
$page_meta_title = $lang['products_title'] . ' | ' . SITE_NAME;
$page_meta_description = $lang['products_subtitle'];

// Ghi đè các biến trong header.php
// Cách này không lý tưởng, một template engine sẽ tốt hơn, nhưng nó hoạt động.
echo "<script>
    document.title = '".addslashes($page_meta_title)."';
    document.querySelector('meta[name=\"description\"]').setAttribute('content', '".addslashes($page_meta_description)."');
</script>";
?>

<div class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-blue-700 mb-4"><?= $lang['products_title'] ?></h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto"><?= $lang['products_subtitle'] ?></p>
        </div>

        <?php if (count($all_products) > 0): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($all_products as $product): ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300 flex flex-col">
                        <img src="<?= SITE_URL ?>/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-56 object-cover">
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 flex-grow"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="text-gray-600 mb-4 text-sm flex-grow">
                                <?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...
                            </p>
                            <a href="<?= SITE_URL ?>/<?= CURRENT_LANG ?>/<?= $lang['routes']['products'] ?>/<?= $product['slug'] ?>" class="mt-auto w-full text-center block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all">
                                <?= $lang['view_details'] ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-16">
                <p class="text-xl text-gray-500">No products found for this language.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

