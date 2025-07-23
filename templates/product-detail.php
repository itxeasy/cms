<?php
// File: templates/product-detail.php
// ----------------------------------
// Template để hiển thị chi tiết một sản phẩm.

global $pdo, $lang, $product_slug; // Lấy các biến toàn cục

// Truy vấn để lấy chi tiết sản phẩm dựa trên slug và ngôn ngữ
$stmt = $pdo->prepare("
    SELECT p.id, p.sku, p.image, pt.name, pt.description, pt.specifications, pt.meta_title, pt.meta_description, pt.meta_keywords
    FROM products p
    JOIN product_translations pt ON p.id = pt.product_id
    WHERE pt.language_code = ? AND pt.slug = ?
");
$stmt->execute([CURRENT_LANG, $product_slug]);
$product = $stmt->fetch();

// Nếu không tìm thấy sản phẩm, hiển thị lỗi 404
if (!$product) {
    http_response_code(404);
    echo "<div class='text-center p-20'><h1>404 - Product Not Found</h1><p>The product you are looking for does not exist or is not available in this language.</p></div>";
    // Ở đây, bạn nên gọi tệp footer và thoát để không hiển thị phần còn lại của trang.
    // require_once __DIR__ . '/../includes/footer.php';
    // exit();
    return; // Dừng thực thi tệp này
}

// Cập nhật các thẻ meta động cho SEO
echo "<script>
    document.title = '".addslashes(htmlspecialchars($product['meta_title']))."';
    document.querySelector('meta[name=\"description\"]').setAttribute('content', '".addslashes(htmlspecialchars($product['meta_description']))."');
    document.querySelector('meta[name=\"keywords\"]').setAttribute('content', '".addslashes(htmlspecialchars($product['meta_keywords']))."');
</script>";

// Giải mã chuỗi JSON của thông số kỹ thuật
$specifications = json_decode($product['specifications'], true);

?>

<div class="bg-white py-20">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <!-- Product Image Gallery -->
            <div>
                <img src="<?= SITE_URL ?>/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full rounded-2xl shadow-xl">
                <!-- Bạn có thể thêm một thư viện ảnh nhỏ ở đây nếu có nhiều ảnh -->
            </div>

            <!-- Product Information -->
            <div>
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($product['name']) ?></h1>
                <p class="text-gray-500 mb-6">SKU: <?= htmlspecialchars($product['sku']) ?></p>
                
                <div class="prose lg:prose-xl max-w-none text-gray-600 mb-8">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </div>

                <!-- Specifications -->
                <?php if (!empty($specifications) && is_array($specifications)): ?>
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Specifications</h3>
                        <ul class="space-y-3">
                            <?php foreach ($specifications as $key => $value): ?>
                                <li class="flex justify-between border-b pb-2">
                                    <strong class="text-gray-700"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $key))) ?>:</strong>
                                    <span class="text-gray-600 text-right"><?= htmlspecialchars($value) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Contact Button -->
                <div class="mt-10">
                    <a href="<?= SITE_URL ?>/<?= CURRENT_LANG ?>/<?= $lang['routes']['contact'] ?>?product=<?= urlencode($product['name']) ?>" class="w-full text-center block bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-8 rounded-lg text-lg transition-all">
                        <i class="fas fa-envelope mr-2"></i>
                        Inquire About This Product
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

