<?php
// File: /admin/product-edit.php
// -----------------------------
// Form để thêm hoặc chỉnh sửa sản phẩm với các tab đa ngôn ngữ.

require_once '../config.php';
require_once '../includes/db.php';
require_once 'includes/session.php';

// Xác định hành động (thêm mới hoặc chỉnh sửa) và kiểm tra quyền
$is_editing = isset($_GET['id']) && !empty($_GET['id']);
if ($is_editing) {
    auth_check();
    if (!has_permission('products', 'edit')) {
        echo "You do not have permission to edit products.";
        exit;
    }
} else {
    auth_check();
    if (!has_permission('products', 'create')) {
        echo "You do not have permission to create products.";
        exit;
    }
}

// Lấy danh sách ngôn ngữ
$stmt = $pdo->query("SELECT * FROM languages ORDER BY id");
$languages = $stmt->fetchAll();

$product = [
    'sku' => '',
    'image' => ''
];
$translations = [];

// Nếu là chỉnh sửa, lấy dữ liệu sản phẩm từ CSDL
if ($is_editing) {
    $product_id = $_GET['id'];
    
    // Lấy thông tin sản phẩm gốc
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    // Lấy tất cả các bản dịch của sản phẩm đó
    $stmt = $pdo->prepare("SELECT * FROM product_translations WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $all_translations = $stmt->fetchAll();
    
    // Sắp xếp các bản dịch theo language_code để dễ truy cập
    foreach ($all_translations as $trans) {
        $translations[$trans['language_code']] = $trans;
    }
}

// Xử lý khi form được gửi đi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        // --- Xử lý upload ảnh ---
        $image_path = $_POST['current_image']; // Giữ ảnh cũ nếu không có ảnh mới
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../assets/images/products/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_name = time() . '_' . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $file_name;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = "assets/images/products/" . $file_name;
            }
        }

        // --- Lưu dữ liệu vào bảng `products` ---
        if ($is_editing) {
            $product_id = $_POST['product_id'];
            $stmt = $pdo->prepare("UPDATE products SET sku = ?, image = ? WHERE id = ?");
            $stmt->execute([$_POST['sku'], $image_path, $product_id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (sku, image) VALUES (?, ?)");
            $stmt->execute([$_POST['sku'], $image_path]);
            $product_id = $pdo->lastInsertId();
        }

        // --- Lưu dữ liệu vào bảng `product_translations` ---
        foreach ($languages as $lang) {
            $lc = $lang['code'];
            $name = $_POST['name'][$lc];
            $slug = !empty($_POST['slug'][$lc]) ? $_POST['slug'][$lc] : strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            
            $stmt = $pdo->prepare("
                INSERT INTO product_translations (product_id, language_code, name, slug, description, specifications, meta_title, meta_description, meta_keywords)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                name = VALUES(name), slug = VALUES(slug), description = VALUES(description), specifications = VALUES(specifications), 
                meta_title = VALUES(meta_title), meta_description = VALUES(meta_description), meta_keywords = VALUES(meta_keywords)
            ");
            $stmt->execute([
                $product_id,
                $lc,
                $name,
                $slug,
                $_POST['description'][$lc],
                $_POST['specifications'][$lc],
                $_POST['meta_title'][$lc],
                $_POST['meta_description'][$lc],
                $_POST['meta_keywords'][$lc]
            ]);
        }
        
        $pdo->commit();
        header("Location: products.php?status=saved");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        // Ghi log lỗi: error_log($e->getMessage());
        header("Location: product-edit.php?id=" . ($is_editing ? $product_id : '') . "&status=error");
        exit;
    }
}

include 'includes/header.php';
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">
    <?= $is_editing ? 'Edit Product' : 'Add New Product' ?>
</h1>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="product-edit.php<?= $is_editing ? '?id='.$product_id : '' ?>" method="POST" enctype="multipart/form-data">
        <?php if ($is_editing): ?>
            <input type="hidden" name="product_id" value="<?= $product_id ?>">
        <?php endif; ?>

        <!-- Phần thông tin chung -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                <input type="text" name="sku" id="sku" value="<?= htmlspecialchars($product['sku']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                <input type="file" name="image" id="image" class="w-full">
                <input type="hidden" name="current_image" value="<?= htmlspecialchars($product['image']) ?>">
                <?php if ($is_editing && $product['image']): ?>
                    <img src="../<?= htmlspecialchars($product['image']) ?>" class="mt-2 h-20 rounded">
                <?php endif; ?>
            </div>
        </div>

        <!-- Phần đa ngôn ngữ với Tabs -->
        <div x-data="{ activeTab: '<?= $languages[0]['code'] ?>' }" class="border rounded-lg">
            <!-- Language Tabs -->
            <div class="flex border-b">
                <?php foreach ($languages as $lang): ?>
                    <button type="button" @click="activeTab = '<?= $lang['code'] ?>'" 
                            :class="{ 'bg-blue-500 text-white': activeTab === '<?= $lang['code'] ?>', 'bg-gray-100': activeTab !== '<?= $lang['code'] ?>' }"
                            class="px-6 py-3 font-medium focus:outline-none">
                        <?= $lang['name'] ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Tab Content -->
            <?php foreach ($languages as $lang): ?>
                <?php $lc = $lang['code']; $trans = $translations[$lc] ?? []; ?>
                <div x-show="activeTab === '<?= $lc ?>'" class="p-6 space-y-4">
                    <h3 class="text-xl font-semibold">Content for <?= $lang['name'] ?></h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <input type="text" name="name[<?= $lc ?>]" value="<?= htmlspecialchars($trans['name'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Slug</label>
                        <input type="text" name="slug[<?= $lc ?>]" value="<?= htmlspecialchars($trans['slug'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Auto-generated if left blank">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description[<?= $lc ?>]" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?= htmlspecialchars($trans['description'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Specifications (JSON format)</label>
                        <textarea name="specifications[<?= $lc ?>]" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg font-mono"><?= htmlspecialchars($trans['specifications'] ?? '') ?></textarea>
                    </div>

                    <h4 class="text-lg font-semibold pt-4 border-t">SEO Settings</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                        <input type="text" name="meta_title[<?= $lc ?>]" value="<?= htmlspecialchars($trans['meta_title'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                        <textarea name="meta_description[<?= $lc ?>]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg"><?= htmlspecialchars($trans['meta_description'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords (comma-separated)</label>
                        <input type="text" name="meta_keywords[<?= $lc ?>]" value="<?= htmlspecialchars($trans['meta_keywords'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-8 text-right">
            <a href="products.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg mr-4">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                <i class="fas fa-save mr-2"></i>Save Product
            </button>
        </div>
    </form>
</div>

<!-- Alpine.js để quản lý các tab -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

<?php include 'includes/footer.php'; ?>
