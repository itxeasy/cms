<?php
// File: /admin/logout.php
// -----------------------
// Xử lý đăng xuất.

session_start();
session_unset();
session_destroy();

header("Location: login.php");
exit;
?>