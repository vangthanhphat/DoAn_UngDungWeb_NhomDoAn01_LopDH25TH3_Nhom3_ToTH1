<?php
require_once 'connect.php';
if (!isset($_SESSION['MaNguoiDung']) || $_SESSION['Quyen'] != 1) {
    header("Location: DangNhap.php");
    exit();
}
// Quản trị viên truy cập trang này sẽ chuyển hướng tới Bảng điều khiển Quản trị
header("Location: quanly.php");
exit();
?>
