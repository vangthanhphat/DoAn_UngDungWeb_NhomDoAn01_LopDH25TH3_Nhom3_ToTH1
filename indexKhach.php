<?php
require_once 'connect.php';
if (!isset($_SESSION['MaNguoiDung'])) {
    header("Location: DangNhap.php");
    exit();
}
// Chuyển về index.php có session khách hàng
include 'index.php';
?>
