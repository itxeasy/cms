<?php
// File: /admin/login.php
// ----------------------
// Giao diện trang đăng nhập.

// Bắt đầu session để kiểm tra xem người dùng đã đăng nhập chưa
session_start();

// Nếu đã đăng nhập, chuyển hướng đến dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once '../config.php'; // Nạp tệp cấu hình gốc
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-lg">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-gray-500">Please sign in to continue</p>
            </div>

            <?php
            // Hiển thị thông báo lỗi nếu có
            if (isset($_GET['error'])) {
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">';
                echo '<span>' . htmlspecialchars($_GET['error']) . '</span>';
                echo '</div>';
            }
            ?>

            <form action="login.php" method="POST">
                <div class="mb-5">
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all">
                    Sign In
                </button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
// Xử lý logic đăng nhập ngay trong tệp này cho đơn giản
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../includes/db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header("Location: login.php?error=Username and password are required.");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT users.*, roles.permissions FROM users JOIN roles ON users.role_id = roles.id WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Đăng nhập thành công, lưu thông tin vào session
            session_regenerate_id(true); // Bảo mật session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['permissions'] = json_decode($user['permissions'], true);

            header("Location: index.php");
            exit;
        } else {
            // Sai username hoặc password
            header("Location: login.php?error=Invalid username or password.");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: login.php?error=Database error. Please try again.");
        exit;
    }
}
?>
