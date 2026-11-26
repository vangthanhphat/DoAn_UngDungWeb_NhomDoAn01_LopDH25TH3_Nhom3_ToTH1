<?php
require_once 'connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id'])) {
    die("Không tìm thấy mã đơn hàng!");
}
$mahd = intval($_GET['id']);

// Lấy thông tin hóa đơn và khách hàng
$sql_hd = "SELECT h.*, 
           COALESCE(NULLIF(h.TenKhachHang, ''), u.TenNguoiDung) AS TenNguoiDung, 
           COALESCE(NULLIF(h.SDT_GiaoHang, ''), u.SDT) AS SDT, 
           COALESCE(NULLIF(h.EmailKhachHang, ''), u.Email) AS Email 
           FROM hoadon h 
           LEFT JOIN user u ON h.MaNguoiDung = u.MaNguoiDung 
           WHERE h.MaHoaDon = '$mahd'";
$result_hd = $conn->query($sql_hd);

if (!$result_hd || $result_hd->num_rows == 0) {
    die("Hóa đơn không tồn tại!");
}
$hoadon = $result_hd->fetch_assoc();

// Lấy chi tiết sản phẩm
$sql_ct = "SELECT ct.*, dh.TenDongHo 
           FROM chitiethoadon ct 
           JOIN dongho dh ON ct.MaDongHo = dh.MaDongHo 
           WHERE ct.MaHoaDon = '$mahd'";
$result_ct = $conn->query($sql_ct);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Phiếu Giao Hàng - #BIG-<?= htmlspecialchars($mahd) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #555; display: flex; flex-direction: column; align-items: center; padding: 20px; margin: 0; }
        .receipt-container { background: #fff; width: 10.5cm; padding: 18px; color: #000; box-shadow: 0 0 15px rgba(0,0,0,0.5); border-radius: 4px; box-sizing: border-box; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .barcode { font-family: 'Libre Barcode 39', cursive; font-size: 42px; text-align: center; margin: 4px 0; }
        .section { border-bottom: 1px dashed #000; padding-bottom: 8px; margin-bottom: 8px; }
        .bold { font-weight: bold; font-size: 13px; margin-bottom: 4px; }
        .text { font-size: 12px; margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; font-size: 12px; }
        .cod-box { background-color: #000; color: #fff; text-align: center; padding: 8px; margin-top: 12px; font-weight: bold; font-size: 18px; }
        .btn-print { background: #D4AF37; color: #000; font-weight: bold; border: none; padding: 10px 25px; border-radius: 4px; margin-bottom: 15px; cursor: pointer; font-size: 14px;}
        
        @media print {
            body { background: #fff; padding: 0; }
            .receipt-container { box-shadow: none; width: 100%; padding: 0; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn-print">🖨️ IN PHIẾU GIAO HÀNG (A6)</button>

    <div class="receipt-container">
        <div class="header">
            <h2>BIGCLOCK LOGISTICS</h2>
            <div class="barcode">*BIG<?= htmlspecialchars($mahd) ?>*</div>
            <p class="text bold">Mã vận đơn: #BIG-<?= str_pad($mahd, 5, '0', STR_PAD_LEFT) ?></p>
            <p class="text">Ngày xuất kho: <?= date('d/m/Y H:i', strtotime($hoadon['NgayLap'])) ?></p>
        </div>

        <div class="section">
            <p class="bold">NGƯỜI GỬI:</p>
            <p class="text"><b>BIGCLOCK BOUTIQUE</b> - 1900 8899</p>
            <p class="text">Số 88 Đường Đồng Khởi, P. Bến Nghé, Quận 1, TP. HCM</p>
        </div>

        <div class="section">
            <p class="bold">NGƯỜI NHẬN:</p>
            <p class="text"><b><?= htmlspecialchars($hoadon['TenNguoiDung']) ?></b> - <?= htmlspecialchars($hoadon['SDT_GiaoHang']) ?></p>
            <p class="text"><?= htmlspecialchars($hoadon['DiaChi']) ?></p>
        </div>

        <div class="section">
            <p class="bold">DANH SÁCH MẶT HÀNG:</p>
            <table>
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th width="40" style="text-align: center;">SL</th>
                        <th width="80" style="text-align: right;">Đơn giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($ct = $result_ct->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($ct['TenDongHo']) ?></td>
                            <td style="text-align: center;"><?= $ct['SoLuong'] ?></td>
                            <td style="text-align: right;"><?= number_format($ct['DonGia'], 0, ',', '.') ?> $</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="cod-box">
            TỔNG TIỀN: <?= number_format($hoadon['TongTien'], 0, ',', '.') ?> VNĐ
        </div>

        <div style="margin-top: 10px; font-size: 11px; text-align: center; font-style: italic;">
            Hàng giá trị cao - Cho phép kiểm tra hàng trước khi nhận - Không đồng kiểm rơi vỡ.
        </div>
    </div>
</body>
</html>
