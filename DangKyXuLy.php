<?php
require_once 'connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$TenNguoiDung = trim($_POST['TenNguoiDung'] ?? '');
$TenDangNhap = trim($_POST['TenDangNhap'] ?? '');
$SDT = trim($_POST['SDT'] ?? '');
$Email = trim($_POST['Email'] ?? '');
$DiaChi = trim($_POST['DiaChi'] ?? '');
$MatKhau = trim($_POST['MatKhau'] ?? '');
$XacNhanMatKhau = trim($_POST['XacNhanMatKhau'] ?? '');

if (empty($TenNguoiDung) || empty($TenDangNhap) || empty($MatKhau)) {
    $_SESSION['message'] = "Vui lòng điền đầy đủ các thông tin bắt buộc (*)!";
    $_SESSION['message_type'] = "alert-danger";
    header("Location: DangKy.php");
    exit();
}

if ($MatKhau !== $XacNhanMatKhau) {
    $_SESSION['message'] = "Mật khẩu xác nhận không khớp!";
    $_SESSION['message_type'] = "alert-danger";
    header("Location: DangKy.php");
    exit();
}

// Kiểm tra xem tên đăng nhập đã có ai dùng chưa
$sql_check = "SELECT MaNguoiDung FROM user WHERE TenDangNhap = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $TenDangNhap);
$stmt_check->execute();
$res_check = $stmt_check->get_result();

if ($res_check->num_rows > 0) {
    $_SESSION['message'] = "Tên đăng nhập này đã tồn tại, vui lòng chọn tên khác!";
    $_SESSION['message_type'] = "alert-warning";
    header("Location: DangKy.php");
    exit();
}

// Lưu người dùng mới vào CSDL với mật khẩu mã hóa MD5
$MatKhauMD5 = md5($MatKhau);
$quyen = 0; // Khách hàng

$sql_insert = "INSERT INTO user (TenNguoiDung, TenDangNhap, MatKhau, Quyen, SDT, Email, DiaChi) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("sssisss", $TenNguoiDung, $TenDangNhap, $MatKhauMD5, $quyen, $SDT, $Email, $DiaChi);

if ($stmt_insert->execute()) {
    $_SESSION['message'] = "Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.";
    $_SESSION['message_type'] = "alert-success";
    header("Location: DangNhap.php");
    exit();
} else {
    $_SESSION['message'] = "Đã xảy ra lỗi khi tạo tài khoản. Vui lòng thử lại!";
    $_SESSION['message_type'] = "alert-danger";
    header("Location: DangKy.php");
    exit();
}
?>
