<?php
require_once 'connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['MaNguoiDung']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$maHoaDon = intval($_GET['id']);
$maNguoiDung = $_SESSION['MaNguoiDung'];

// Kiểm tra quyền hủy: Đúng khách hàng và đơn hàng ở trạng thái 0 (Chờ duyệt) hoặc 1 (Đã duyệt)
$sql_check = "SELECT TrangThaiDonHang FROM hoadon WHERE MaHoaDon = $maHoaDon AND MaNguoiDung = $maNguoiDung";
$result = $conn->query($sql_check);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $trangThai = $row['TrangThaiDonHang'];
    
    if ($trangThai == 0 || $trangThai == 1) {
        // 1. Đổi trạng thái thành 4 (Đã hủy)
        $conn->query("UPDATE hoadon SET TrangThaiDonHang = 4 WHERE MaHoaDon = $maHoaDon");
        
        // 2. Tự động hoàn trả số lượng sản phẩm vào kho
        $sql_ct = "SELECT MaDongHo, SoLuong FROM chitiethoadon WHERE MaHoaDon = $maHoaDon";
        $result_ct = $conn->query($sql_ct);
        if ($result_ct) {
            while ($ct = $result_ct->fetch_assoc()) {
                $maDongHo = $ct['MaDongHo'];
                $soLuongTra = $ct['SoLuong'];
                $conn->query("UPDATE dongho SET SoLuong = SoLuong + $soLuongTra WHERE MaDongHo = $maDongHo");
            }
        }
        $_SESSION['success_msg'] = "Hủy đơn hàng #$maHoaDon thành công! Số lượng sản phẩm đã được tự động hoàn lại kho.";
    }
}

header("Location: donhang_khach.php");
exit();
?>
