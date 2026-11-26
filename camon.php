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

// Lấy chi tiết các món đã đặt
$sql_ct = "SELECT ct.*, dh.TenDongHo, dh.HinhAnh 
           FROM chitiethoadon ct 
           JOIN dongho dh ON ct.MaDongHo = dh.MaDongHo 
           WHERE ct.MaHoaDon = $maHoaDon";
$res_ct = $conn->query($sql_ct);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đặt Hàng Thành Công - BIGCLOCK</title>
    
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
            padding: 50px 0;
            min-height: 100vh;
        }
        .success-box {
            background: var(--card-bg);
            border: 2px solid var(--luxury-gold);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.9);
        }
        .btn-gold {
            background: var(--luxury-gold);
            color: #000;
            font-weight: 700;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            transition: 0.3s;
        }
        .btn-gold:hover {
            background: #FFD700;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.7);
            color: #000;
        }
        .btn-outline-gold {
            border: 1px solid var(--luxury-gold);
            color: var(--luxury-gold);
            background: transparent;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 6px;
            transition: 0.3s;
        }
        .btn-outline-gold:hover {
            background: var(--luxury-gold);
            color: #000;
        }

        /* ĐỘ TƯƠNG PHẢN CAO CHO DARK MODE - CHỐNG MỜ CHỮ */
        .text-muted, p.text-muted, span.text-muted, label.text-muted, small.text-muted, div.text-muted {
            color: #cbd5e1 !important;
        }
        .text-secondary {
            color: #cbd5e1 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="success-box text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle" style="font-size: 4.5rem; color: #00ff88;"></i>
                    </div>
                    
                    <h2 class="text-white mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">CẢM ƠN QUÝ KHÁCH ĐÃ ĐẶT HÀNG!</h2>
                    <p class="text-muted">Đơn hàng của bạn đã được ghi nhận trên hệ thống <b>BIGCLOCK</b>.</p>
                    
                    <div class="p-3 my-4" style="background: #181818; border-radius: 10px; border: 1px solid rgba(212,175,55,0.3); text-align: left;">
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <span style="color: #cbd5e1; font-weight: 600;">Mã đơn hàng:</span> <b class="text-warning">#BIG-<?= str_pad($maHoaDon, 5, '0', STR_PAD_LEFT) ?></b>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <span style="color: #cbd5e1; font-weight: 600;">Thời gian đặt:</span> <b style="color: #ffffff;"><?= date('d/m/Y H:i', strtotime($hd['NgayLap'])) ?></b>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <span style="color: #cbd5e1; font-weight: 600;">Người nhận:</span> <b style="color: #ffffff;"><?= htmlspecialchars($hd['TenNguoiDung']) ?></b>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <span style="color: #cbd5e1; font-weight: 600;">Số điện thoại:</span> <b style="color: #ffffff;"><?= htmlspecialchars($hd['SDT_GiaoHang']) ?></b>
                            </div>
                            <div class="col-12 mb-2">
                                <span style="color: #cbd5e1; font-weight: 600;">Địa chỉ nhận:</span> <b style="color: #ffffff;"><?= htmlspecialchars($hd['DiaChi']) ?></b>
                            </div>
                            <div class="col-12">
                                <span style="color: #ffffff; font-weight: 700; font-size: 1.1rem;">Tổng thanh toán:</span> <b class="text-warning fs-4" style="font-family: 'Cinzel', serif; letter-spacing: 1px;"><?= number_format($hd['TongTien'], 0, ',', '.') ?> VNĐ</b>
                            </div>
                        </div>
                    </div>

                    <!-- Mã QR Thanh Toán -->
                    <div class="p-4 my-4" style="background: #000; border-radius: 12px; border: 1px solid rgba(212,175,55,0.4); max-width: 420px; margin: 0 auto;">
                        <h6 class="text-warning fw-bold mb-2"><i class="fas fa-qrcode me-2"></i> QUÉT MÃ VIETQR ĐỂ THANH TOÁN</h6>
                        <p class="small text-muted mb-3">Sử dụng ứng dụng Ngân hàng hoặc ví điện tử để quét mã tự động điền số tiền và nội dung chuyển khoản:</p>
                        <img src="images/qrcode.jpg" alt="QR Thanh toán" style="max-width: 200px; border-radius: 8px; border: 2px solid #D4AF37;">
                        <p class="small text-warning mt-2 mb-0">Nội dung CK: <b>BIGCLOCK <?= $maHoaDon ?></b></p>
                    </div>

                    <!-- Các nút tiện ích -->
                    <div class="d-flex flex-wrap gap-2 justify-content-center mt-4">
                        <a href="hoadon_vat.php?id=<?= $maHoaDon ?>" class="btn btn-outline-gold" target="_blank">
                            <i class="fas fa-file-invoice me-1"></i> Xem Hóa Đơn Điện Tử VAT
                        </a>
                        <a href="bienlai.php?id=<?= $maHoaDon ?>" class="btn btn-outline-gold" target="_blank">
                            <i class="fas fa-receipt me-1"></i> Xem Phiếu Giao Hàng
                        </a>
                        <a href="donhang_khach.php" class="btn btn-gold">
                            <i class="fas fa-box me-1"></i> Lịch Sử Đơn Hàng Của Tôi
                        </a>
                        <a href="index.php" class="btn btn-outline-light">
                            <i class="fas fa-home me-1"></i> Về Trang Chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
