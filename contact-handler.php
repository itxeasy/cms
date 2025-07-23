<?php
// File: contact-handler.php
// -------------------------
// Tệp xử lý logic gửi form liên hệ.
// Đặt tệp này ở thư mục gốc của dự án.

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form và làm sạch
    $name = strip_tags(trim($_POST["full_name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);
    $current_lang = $_POST["lang"] ?? 'en'; // Lấy ngôn ngữ để chuyển hướng lại

    // Kiểm tra dữ liệu
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        header("Location: " . SITE_URL . "/$current_lang/contact?status=error");
        exit;
    }

    // Cấu hình email
    $recipient = "your-email@example.com"; // <-- THAY ĐỔI THÀNH EMAIL CỦA BẠN
    $subject = "New Contact Form Submission from " . SITE_NAME;
    
    // Nội dung email
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    // Headers
    $email_headers = "From: $name <$email>";

    // Gửi email
    // Lưu ý: Để hàm mail() hoạt động, server của bạn cần được cấu hình để gửi mail (cài đặt SMTP).
    // Trên localhost (XAMPP), bạn có thể cần cấu hình file php.ini và sendmail.ini.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Gửi thành công, chuyển hướng về trang liên hệ với thông báo thành công
        header("Location: " . SITE_URL . "/$current_lang/contact?status=success");
    } else {
        // Gửi thất bại
        header("Location: " . SITE_URL . "/$current_lang/contact?status=error");
    }

} else {
    // Không phải là yêu cầu POST, chuyển hướng về trang chủ
    header("Location: " . SITE_URL);
}
?>