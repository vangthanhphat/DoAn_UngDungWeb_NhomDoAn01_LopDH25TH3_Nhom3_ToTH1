<?php
require_once 'connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id'])) {
    die("Truy cập bị từ chối!");
}

$maHoaDon = intval($_GET['id']);

// Lấy thông tin hóa đơn (Admin hoặc đúng chủ sở hữu)
$sql_hd = "SELECT h.*, 
           COALESCE(NULLIF(h.TenKhachHang, ''), u.TenNguoiDung) AS TenNguoiDung, 
           COALESCE(NULLIF(h.EmailKhachHang, ''), u.Email) AS Email, 
           COALESCE(NULLIF(h.SDT_GiaoHang, ''), u.SDT) AS SDT 
           FROM hoadon h 
           LEFT JOIN user u ON h.MaNguoiDung = u.MaNguoiDung 
           WHERE h.MaHoaDon = ?";
$stmt = $conn->prepare($sql_hd);
$stmt->bind_param("i", $maHoaDon);
$stmt->execute();
$result_hd = $stmt->get_result();

if ($result_hd->num_rows == 0) {
    die("<h2 style='text-align:center; margin-top:50px; color:red;'>Không tìm thấy hóa đơn!</h2>");
}
$hd = $result_hd->fetch_assoc();

// Bảo mật: Nếu là khách hàng bình thường thì chỉ được xem hóa đơn của chính mình
if (!isset($_SESSION['Quyen']) || $_SESSION['Quyen'] != 1) {
    if (!isset($_SESSION['MaNguoiDung']) || $_SESSION['MaNguoiDung'] != $hd['MaNguoiDung']) {
        die("<h2 style='text-align:center; margin-top:50px; color:red;'>Bạn không có quyền xem hóa đơn này!</h2>");
    }
}

// Lấy chi tiết các mặt hàng
$sql_ct = "SELECT ct.*, dh.TenDongHo FROM chitiethoadon ct 
           JOIN dongho dh ON ct.MaDongHo = dh.MaDongHo 
           WHERE ct.MaHoaDon = ?";
$stmt_ct = $conn->prepare($sql_ct);
$stmt_ct->bind_param("i", $maHoaDon);
$stmt_ct->execute();
$result_ct = $stmt_ct->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Hóa Đơn Điện Tử VAT - #BIG-<?= str_pad($maHoaDon, 6, '0', STR_PAD_LEFT) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #dedede; padding: 30px 0; color: #000; }
        .invoice-box { max-width: 820px; margin: auto; padding: 45px 50px; border: 1px solid #bbb; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.25); position: relative;}
        
        .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px double #000; padding-bottom: 20px; margin-bottom: 25px; }
        .shop-info h2 { color: #000; font-weight: bold; letter-spacing: 2px; margin: 0 0 6px 0; font-size: 1.8rem; }
        .shop-info p { margin: 3px 0; color: #333; font-size: 0.95rem; }
        
        .invoice-title { text-align: right; }
        .invoice-title h1 { color: #000; font-weight: bold; margin: 0 0 8px 0; font-size: 2rem; }
        .invoice-title p { margin: 3px 0; font-style: italic; color: #444; }
        
        .customer-info { margin-bottom: 25px; line-height: 1.6;}
        .customer-info h5 { font-weight: bold; border-bottom: 1px solid #333; padding-bottom: 4px; display: inline-block; margin-bottom: 12px; font-size: 1.05rem;}
        .customer-info p { margin: 4px 0;}
        
        .table-invoice { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .table-invoice th { background-color: #f2f2f2; border: 1px solid #000; padding: 10px; text-align: center; font-weight: bold;}
        .table-invoice td { border: 1px solid #000; padding: 9px; text-align: center; }
        .table-invoice td.text-start { text-align: left; }
        
        .total-section { text-align: right; margin-bottom: 35px; }
        .total-section p { margin: 6px 0; font-size: 1.05rem; }
        .total-amount { font-size: 1.5rem; font-weight: bold; color: #000; }
        .vat-note { font-style: italic; font-size: 0.9rem; color: #555; }
        
        .signature-section { display: flex; justify-content: space-between; text-align: center; margin-top: 25px; }
        .signature-box { width: 45%; }
        .signature-img { width: 200px; height: auto; margin-top: 10px; mix-blend-mode: multiply; } 
        
        .btn-print { display: block; width: 180px; margin: 25px auto; background: #000; color: #D4AF37; border: 2px solid #D4AF37; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 1rem; transition: 0.3s; text-align: center;}
        .btn-print:hover { background: #D4AF37; color: #000; }
        
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-box { box-shadow: none; border: none; padding: 0; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print"><i class="fas fa-print me-2"></i> IN HÓA ĐƠN VAT</button>

    <div class="invoice-box">
        <div class="invoice-header">
            <div class="shop-info">
                <h2>BIGCLOCK SWISS BOUTIQUE</h2>
                <p><strong>Đơn vị bán hàng:</strong> CÔNG TY CP ĐỒNG HỒ CAO CẤP BIGCLOCK</p>
                <p><strong>Mã số thuế:</strong> 0314889988</p>
                <p><strong>Địa chỉ:</strong> Số 88 Đường Đồng Khởi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh</p>
                <p><strong>Hotline CSKH:</strong> 1900 8899 | <strong>Email:</strong> cskh@bigclock.vn</p>
            </div>
            <div class="invoice-title">
                <h1>HÓA ĐƠN GTGT</h1>
                <p>Ký hiệu: <strong>1C26TBC</strong></p>
                <p>Số: <strong>#VAT-<?= str_pad($maHoaDon, 6, '0', STR_PAD_LEFT) ?></strong></p>
                <p>Ngày lập: <?= date('d/m/Y', strtotime($hd['NgayLap'])) ?></p>
            </div>
        </div>

        <div class="customer-info">
            <h5>THÔNG TIN KHÁCH HÀNG / NGƯỜI MUA HÀNG</h5>
            <p><strong>Họ và tên người mua:</strong> <?= htmlspecialchars($hd['TenNguoiDung']) ?></p>
            <p><strong>Số điện thoại liên hệ:</strong> <?= htmlspecialchars($hd['SDT_GiaoHang']) ?></p>
            <p><strong>Địa chỉ nhận hàng:</strong> <?= htmlspecialchars($hd['DiaChi']) ?></p>
            <p><strong>Hình thức thanh toán:</strong> Chuyển khoản VietQR / COD</p>
        </div>

        <table class="table-invoice">
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="45%">Tên hàng hóa, dịch vụ</th>
                    <th width="15%">Số lượng</th>
                    <th width="15%">Đơn giá (VNĐ)</th>
                    <th width="20%">Thành tiền (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $stt = 1; 
                $tongTienHang = 0;
                while($ct = $result_ct->fetch_assoc()): 
                    $thanhTien = $ct['SoLuong'] * $ct['DonGia'];
                    $tongTienHang += $thanhTien;
                ?>
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td class="text-start">Đồng hồ cao cấp <?= htmlspecialchars($ct['TenDongHo']) ?></td>
                        <td><?= $ct['SoLuong'] ?></td>
                        <td><?= number_format($ct['DonGia'], 0, ',', '.') ?> $</td>
                        <td><strong><?= number_format($thanhTien, 0, ',', '.') ?> $</strong></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="total-section">
            <p>Cộng tiền hàng: <?= number_format($tongTienHang, 0, ',', '.') ?> VNĐ</p>
            <p>Tiền thuế GTGT (10%): <strong>Đã bao gồm trong giá bán</strong></p>
            <p class="total-amount">Tổng cộng tiền thanh toán: <?= number_format($hd['TongTien'], 0, ',', '.') ?> VNĐ</p>
            <p class="vat-note">(Giá niêm yết đã bao gồm thuế Giá Trị Gia Tăng và bảo hành quốc tế 5 năm)</p>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p><strong>Người mua hàng</strong></p>
                <p style="font-style: italic; color: #666; font-size: 0.9rem;">(Ký, ghi rõ họ tên)</p>
                <br><br><br><br>
                <p><strong><?= htmlspecialchars($hd['TenNguoiDung']) ?></strong></p>
            </div>
            <div class="signature-box">
                <p><strong>Người bán hàng / Thủ trưởng đơn vị</strong></p>
                <p style="font-style: italic; color: #666; font-size: 0.9rem;">(Ký số điện tử hợp chuẩn)</p>
                <img src="images/chukyso.png" alt="Chữ ký số điện tử" class="signature-img">
            </div>
        </div>
    </div>
</body>
</html>
