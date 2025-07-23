<?php
// File: index.php
// ---------------
// Tệp điều hướng chính của toàn bộ trang web.

require_once 'config.php';
require_once 'includes/db.php';

// --- XỬ LÝ NGÔN NGỮ ---

// Lấy danh sách ngôn ngữ từ CSDL
$stmt = $pdo->query("SELECT code, direction FROM languages");
$available_langs = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Lấy ngôn ngữ mặc định
$stmt = $pdo->query("SELECT code FROM languages WHERE is_default = 1");
$default_lang = $stmt->fetchColumn();

// Lấy URL được yêu cầu
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url_parts = explode('/', $url);

// Xác định ngôn ngữ từ URL
$current_lang = $default_lang;
if (isset($url_parts[0]) && array_key_exists($url_parts[0], $available_langs)) {
    $current_lang = $url_parts[0];
    array_shift($url_parts); // Xóa phần ngôn ngữ khỏi URL
}

// Lưu ngôn ngữ hiện tại vào session và hằng số
$_SESSION['lang'] = $current_lang;
define('CURRENT_LANG', $current_lang);
define('LANG_DIRECTION', $available_langs[$current_lang]);

// Load tệp ngôn ngữ tương ứng
$lang_file = __DIR__ . '/lang/' . CURRENT_LANG . '.php';
if (file_exists($lang_file)) {
    $lang = require $lang_file;
} else {
    // Nếu tệp ngôn ngữ không tồn tại, sử dụng tệp mặc định
    $lang = require __DIR__ . '/lang/' . $default_lang . '.php';
}

// --- HỆ THỐNG ĐỊNH TUYẾN (ROUTING) ---

// Xác định trang cần tải dựa trên URL
$page = !empty($url_parts[0]) ? $url_parts[0] : 'home';

// Xác định tham số (ví dụ: slug sản phẩm)
$param = !empty($url_parts[1]) ? $url_parts[1] : null;

// Bao gồm header của trang
require_once 'includes/header.php';

// Tải template trang tương ứng
switch ($page) {
    case 'home':
        require_once 'templates/home.php';
        break;
    
    case $lang['routes']['products']: // Sử dụng slug từ tệp ngôn ngữ
        if ($param) {
            // Nếu có tham số, hiển thị chi tiết sản phẩm
            $product_slug = $param;
            require_once 'templates/product-detail.php';
        } else {
            // Nếu không, hiển thị danh sách sản phẩm
            require_once 'templates/products.php';
        }
        break;

    // Thêm các case khác cho các trang như 'about', 'contact' ở đây
    // case $lang['routes']['about']:
    //     require_once 'templates/about.php';
    //     break;

    // === PHẦN MỚI THÊM VÀO ===
    case $lang['routes']['about']:
        require_once 'templates/about.php';
        break;

    case $lang['routes']['contact']:
        require_once 'templates/contact.php';
        break;
    // === KẾT THÚC PHẦN MỚI ===

    default:
        // Nếu không tìm thấy trang, hiển thị trang 404
        http_response_code(404);
        // require_once 'templates/404.php';
        echo "<div class='text-center p-20'><h1>404 - Page Not Found</h1></div>";
        break;
}

// Bao gồm footer của trang
require_once 'includes/footer.php';

?>