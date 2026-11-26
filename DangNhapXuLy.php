<?php
require_once 'connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$TenDangNhap = trim($_POST['TenDangNhap'] ?? '');
$MatKhau = trim($_POST['MatKhau'] ?? '');

if (empty($TenDangNhap) || empty($MatKhau)) {
    $_SESSION['message'] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!";
    $_SESSION['message_type'] = "alert-danger";
    header("Location: DangNhap.php");
    exit();
}

// Mã hóa MD5
$MatKhauMD5 = md5($MatKhau);

// Kiểm tra trong database (hỗ trợ cả md5 của 123456 và admin)
$sql = "SELECT * FROM user WHERE TenDangNhap = ? AND (MatKhau = ? OR (TenDangNhap = 'admin' AND MatKhau = '21232f297a57a5a743894a0e4a801fc3' AND ? = 'admin'))";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $TenDangNhap, $MatKhauMD5, $MatKhau);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    $user = $res->fetch_assoc();
    $_SESSION['MaNguoiDung'] = $user['MaNguoiDung'];
    $_SESSION['HoTen'] = $user['TenNguoiDung'];
    $_SESSION['Quyen'] = $user['Quyen'];

    if ($user['Quyen'] == 1) {
        header("Location: quanly.php");
    } else {
        header("Location: index.php");
    }
    exit();
} else {
    $_SESSION['message'] = "Tên đăng nhập hoặc mật khẩu không chính xác!";
    $_SESSION['message_type'] = "alert-danger";
    header("Location: DangNhap.php");
    exit();
}
?>
