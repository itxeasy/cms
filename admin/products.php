<?php
// File: /admin/products.php
// -------------------------
// Trang chính để xem và quản lý tất cả sản phẩm.

require_once '../config.php';
require_once '../includes/db.php';
require_once 'includes/session.php';

// Kiểm tra quyền: người dùng phải có quyền 'view' trong mục 'products'
auth_check();
if (!has_permission('products', 'view')) {
    // Nếu không có quyền, hiển thị thông báo lỗi và dừng
    echo "You do not have permission to view this page.";
    exit;
}

// Lấy tất cả sản phẩm cùng với tên mặc định (tiếng Anh) để hiển thị
$stmt = $pdo->prepare("
    SELECT p.id, p.sku, p.image, pt.name 
    FROM products p
    LEFT JOIN product_translations pt ON p.id = pt.product_id AND pt.language_code = 'en'
    ORDER BY p.id DESC
");
$stmt->execute();
$products = $stmt->fetchAll();

include 'includes/header.php'; // Nạp header của trang admin
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Manage Products</h1>
    <?php if (has_permission('products', 'create')): ?>
        <a href="product-edit.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Add New Product
        </a>
    <?php endif; ?>
</div>

<!-- Hiển thị thông báo thành công/lỗi (nếu có) -->
<?php
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = '';
    $type = 'success'; // Mặc định là thành công

    if ($status == 'deleted') {
        $message = 'Product has been deleted successfully.';
    } elseif ($status == 'saved') {
        $message = 'Product has been saved successfully.';
    } elseif ($status == 'error') {
        $message = 'An error occurred. Please try again.';
        $type = 'error';
    }

    if ($message) {
        $bgColor = $type == 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        echo "<div class='border {$bgColor} px-4 py-3 rounded-lg relative mb-4' role='alert'>{$message}</div>";
    }
}
?>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">SKU</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product Name (EN)</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <img src="../<?= htmlspecialchars($product['image'] ?: 'assets/images/placeholder.png') ?>" alt="Product Image" class="w-16 h-16 object-cover rounded">
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($product['sku']) ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($product['name'] ?: 'N/A') ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <?php if (has_permission('products', 'edit')): ?>
                            <a href="product-edit.php?id=<?= $product['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                        <?php endif; ?>
                        <?php if (has_permission('products', 'delete')): ?>
                            <a href="product-delete.php?id=<?= $product['id'] ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>