<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_clean();
require_once 'connect.php'; 

if (isset($_POST['MaDongHo'])) {
    $id = intval($_POST['MaDongHo']);
    
    // 1. Kiểm tra tồn kho
    $sql_check = "SELECT SoLuong, TenDongHo FROM dongho WHERE MaDongHo = $id";
    $res_check = $conn->query($sql_check);
    if (!$res_check || $res_check->num_rows == 0) {
        if (isset($_POST['ajax'])) { echo "HEZHANG"; exit(); }
        echo "<script>alert('Sản phẩm không tồn tại!'); history.back();</script>";
        exit();
    }
    
    $row_check = $res_check->fetch_assoc();
    if ($row_check['SoLuong'] <= 0) {
        if (isset($_POST['ajax'])) {
            echo "HEZHANG";
            exit();
        }
        echo "<script>alert('Sản phẩm đã hết hàng!'); history.back();</script>";
        exit();
    }
    
    // 2. Thêm vào giỏ
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $sl_them = isset($_POST['SoLuong']) ? max(1, (int)$_POST['SoLuong']) : (isset($_POST['SoLuongThem']) ? max(1, (int)$_POST['SoLuongThem']) : 1);

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $sl_them;
    } else {
        $_SESSION['cart'][$id] = $sl_them;
    }

    // Đếm tổng số lượng món trong giỏ
    $tong_so_luong = 0;
    foreach($_SESSION['cart'] as $sp_id => $soluong){
        $tong_so_luong += $soluong;
    }

    if (isset($_POST['ajax'])) {
        echo $tong_so_luong;
        exit();
    }

    if (isset($_POST['muangay'])) {
        header('Location: giohang.php');
        exit();
    }

    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: giohang.php');
    }
    exit();
}
?>
