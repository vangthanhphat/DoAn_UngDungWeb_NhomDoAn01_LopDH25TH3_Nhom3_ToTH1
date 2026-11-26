<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'connect.php';

if (!isset($_GET['ma_hoadon'])) {
    header("Location: index.php");
    exit();
}

$maHoaDon = intval($_GET['ma_hoadon']);

// Lấy thông tin hóa đơn
$sql = "SELECT h.*, u.TenNguoiDung, u.Email FROM hoadon h 
        JOIN user u ON h.MaNguoiDung = u.MaNguoiDung 
        WHERE h.MaHoaDon = $maHoaDon";
$res = $conn->query($sql);

if (!$res || $res->num_rows == 0) {
    echo "<h2 style='text-align:center; color:#fff; margin-top:50px;'>Đơn hàng không tồn tại!</h2>";
    exit();
}
$hd = $res->fetch_assoc();

// XỬ LÝ KHI KHÁCH HÀNG BẤM NÚT "TÔI ĐÃ THANH TOÁN"
if (isset($_POST['btnDaThanhToan'])) {
    // 1. Trừ tồn kho các sản phẩm trong hóa đơn này (nếu chưa trừ)
    if (!isset($_SESSION['paid_orders']) || !in_array($maHoaDon, $_SESSION['paid_orders'])) {
        $sql_ct = "SELECT MaDongHo, SoLuong FROM chitiethoadon WHERE MaHoaDon = $maHoaDon";
        $res_ct = $conn->query($sql_ct);
        if ($res_ct) {
            while ($ct = $res_ct->fetch_assoc()) {
                $mdh = intval($ct['MaDongHo']);
                $sl = intval($ct['SoLuong']);
                $conn->query("UPDATE dongho SET SoLuong = SoLuong - $sl WHERE MaDongHo = $mdh");
            }
        }
        if (!isset($_SESSION['paid_orders'])) {
            $_SESSION['paid_orders'] = array();
        }
        $_SESSION['paid_orders'][] = $maHoaDon;
    }

    // 2. Xóa giỏ hàng Session về 0
    unset($_SESSION['cart']);

    // 3. Chuyển sang màn hình Cảm ơn đặt hàng thành công
    header("Location: camon.php?ma_hoadon=" . $maHoaDon);
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanh Toán Đơn Hàng #BIG-<?= str_pad($maHoaDon, 5, '0', STR_PAD_LEFT) ?> - BIGCLOCK</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --luxury-gold: #D4AF37;
            --dark-bg: #070707;
            --card-bg: #121212;
        }
        body {
            background-color: var(--dark-bg);
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 40px 0;
            min-height: 100vh;
        }
        .payment-box {
            background: var(--card-bg);
            border: 2px solid var(--luxury-gold);
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.95);
            max-width: 580px;
            margin: 0 auto;
        }
        .qr-card {
            background: #000;
            border-radius: 14px;
            border: 1px solid rgba(212,175,55,0.45);
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.8);
        }
        .btn-gold {
            background: linear-gradient(135deg, #FFD700 0%, #D4AF37 100%);
            color: #000;
            font-weight: 800;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #fff 0%, #FFD700 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.6);
            color: #000;
        }
        .text-muted {
            color: #cbd5e1 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-box text-center">
            
            <!-- Logo & Thương hiệu -->
            <div class="mb-3">
                <a href="index.php" style="text-decoration: none;">
                    <img src="images/logo.png?v=2" alt="BIGCLOCK" style="height: 52px; margin-bottom: 6px;">
                    <h3 style="font-family: 'Cinzel', serif; color: var(--luxury-gold); margin: 0; font-weight: 800; letter-spacing: 3px;">BIGCLOCK</h3>
                </a>
                <p class="small text-muted mt-1 text-uppercase" style="letter-spacing: 2px;">Cổng Thanh Toán VietQR Instant</p>
            </div>

            <!-- Tóm tắt số tiền -->
            <div class="p-3 mb-4 text-start" style="background: #181818; border-radius: 10px; border: 1px solid rgba(212,175,55,0.3);">
                <div class="d-flex justify-content-between mb-1">
                    <span style="color: #cbd5e1; font-size: 0.95rem;">Mã đơn hàng:</span>
                    <b class="text-warning">#BIG-<?= str_pad($maHoaDon, 5, '0', STR_PAD_LEFT) ?></b>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span style="color: #cbd5e1; font-size: 0.95rem;">Khách hàng:</span>
                    <b style="color: #ffffff;"><?= htmlspecialchars($hd['TenNguoiDung']) ?></b>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-secondary">
                    <span style="color: #ffffff; font-weight: 700; font-size: 1.05rem;">Tổng thanh toán:</span>
                    <b class="text-warning fs-4" style="font-family: 'Cinzel', serif; letter-spacing: 1px;"><?= number_format($hd['TongTien'], 0, ',', '.') ?> VNĐ</b>
                </div>
            </div>

            <!-- Khung Mã QR Thanh Toán -->
            <div class="qr-card mb-4">
                <h5 class="text-warning fw-bold mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    <i class="fas fa-qrcode me-2"></i> QUÉT MÃ VIETQR ĐỂ THANH TOÁN
                </h5>
                <p class="small text-muted mb-3">Sử dụng ứng dụng Ngân hàng hoặc ví điện tử để quét mã tự động điền số tiền và nội dung chuyển khoản:</p>
                
                <div class="p-2 d-inline-block bg-white rounded-3 mb-2 shadow">
                    <img src="images/qrcode.jpg?v=<?= time() ?>" alt="Mã VietQR Techcombank" style="max-width: 280px; width: 100%; border-radius: 8px; display: block;">
                </div>
                
                <div class="mt-3 p-2 rounded" style="background: rgba(212,175,55,0.12); border: 1px dashed rgba(212,175,55,0.4);">
                    <div style="color: #cbd5e1; font-size: 0.92rem;">Chủ TK: <b style="color: #ffffff;">VĂNG THÀNH PHÁT</b> | Ngân hàng: <b class="text-warning">Techcombank</b></div>
                    <div style="color: #cbd5e1; font-size: 0.92rem; margin-top: 4px;">Nội dung CK: <b class="text-warning fs-5">BIGCLOCK <?= $maHoaDon ?></b></div>
                </div>
            </div>

            <!-- Nút bấm xác nhận ĐÃ THANH TOÁN -->
            <form method="POST">
                <button type="submit" name="btnDaThanhToan" class="btn btn-gold btn-lg w-100 text-uppercase py-3">
                    <i class="fas fa-check-circle me-2"></i> ĐÃ THANH TOÁN
                </button>
            </form>

            <div class="mt-3">
                <a href="giohang.php" class="text-muted small text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại giỏ hàng
                </a>
            </div>

        </div>
    </div>
</body>
</html>
