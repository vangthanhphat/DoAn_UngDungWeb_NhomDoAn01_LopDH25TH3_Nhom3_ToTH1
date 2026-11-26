<?php
require_once 'connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['MaNguoiDung'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem đơn hàng của bạn!'); window.location='DangNhap.php';</script>";
    exit();
}
$maNguoiDung = $_SESSION['MaNguoiDung'];

$sql = "SELECT * FROM hoadon WHERE MaNguoiDung = '$maNguoiDung' ORDER BY NgayLap DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Đơn Hàng Của Tôi - BIGCLOCK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --gold: #D4AF37; --black: #080808; --dark-gray: #141414; --text-light: #fefefe; }
        body { background-color: var(--black); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-light); padding: 40px 0; min-height: 100vh;}
        .order-container { background: var(--dark-gray); border-radius: 16px; padding: 35px; border: 1px solid rgba(212, 175, 55, 0.3); max-width: 1200px; margin: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.85); }
        .section-title { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1.8rem; text-transform: uppercase; letter-spacing: 2px; color: var(--gold); border-bottom: 2px solid var(--gold); padding-bottom: 15px; margin-bottom: 30px; }
        .table-custom { color: #fff; margin-bottom: 0; }
        .table-custom th { background-color: #1e1e1e !important; color: var(--gold) !important; border-bottom: 2px solid var(--gold) !important; padding: 15px; text-align: center; white-space: nowrap; font-size: 0.85rem; text-transform: uppercase;}
        .table-custom td { padding: 15px; border-bottom: 1px solid #282828; vertical-align: middle; text-align: center; background-color: transparent !important; color: #fff !important;}
        .table-custom tr:hover td { background-color: rgba(212, 175, 55, 0.08) !important; }
        
        .btn-vat { background-color: transparent; color: var(--gold); border: 1px solid var(--gold); padding: 6px 14px; border-radius: 5px; text-decoration: none; transition: 0.3s; font-size: 0.85rem; font-weight: 600; display: inline-block; margin: 2px;}
        .btn-vat:hover { background-color: var(--gold); color: #000; box-shadow: 0 0 10px rgba(212, 175, 55, 0.5); }
        
        .btn-cancel { background-color: transparent; color: #ff4d4d; border: 1px solid #ff4d4d; padding: 6px 14px; border-radius: 5px; text-decoration: none; transition: 0.3s; font-size: 0.85rem; font-weight: 600; display: inline-block; margin: 2px;}
        .btn-cancel:hover { background-color: #ff4d4d; color: #fff; box-shadow: 0 0 10px rgba(255, 77, 77, 0.5); }
        
        .btn-back { color: #cbd5e1; text-decoration: none; display: inline-block; margin-bottom: 25px; transition: 0.3s; font-size: 0.95rem; font-weight: 500;}
        .btn-back:hover { color: var(--gold); }
        .text-muted { color: #cbd5e1 !important; }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn-back"><i class="fas fa-arrow-left me-2"></i> Trở về cửa hàng BIGCLOCK</a>
        
        <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success" style="background-color: rgba(0,255,136,0.12); border-color: #00ff88; color: #00ff88;">
                <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
            </div>
        <?php endif; ?>

        <div class="order-container">
            <h2 class="section-title"><i class="fas fa-box-open me-2"></i> Lịch Sử Đơn Hàng Của Tôi</h2>

            <?php if($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Địa chỉ giao hàng</th>
                                <th>Số điện thoại</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái đơn</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><b class="text-warning">#BIG-<?= str_pad($row['MaHoaDon'], 5, '0', STR_PAD_LEFT) ?></b></td>
                                    <td><?= date('d/m/Y H:i', strtotime($row['NgayLap'])) ?></td>
                                    <td style="max-width: 250px; text-align: left;"><?= htmlspecialchars($row['DiaChi']) ?></td>
                                    <td><?= htmlspecialchars($row['SDT_GiaoHang']) ?></td>
                                    <td><b class="text-warning"><?= number_format($row['TongTien'], 0, ',', '.') ?> VNĐ</b></td>
                                    <td>
                                        <?php 
                                            $tt = $row['TrangThaiDonHang'];
                                            if ($tt == 0) echo '<span class="badge bg-warning text-dark px-3 py-2">Chờ duyệt</span>';
                                            elseif ($tt == 1) echo '<span class="badge bg-info text-dark px-3 py-2">Đã duyệt</span>';
                                            elseif ($tt == 2) echo '<span class="badge bg-primary px-3 py-2">Đang giao</span>';
                                            elseif ($tt == 3) echo '<span class="badge bg-success px-3 py-2">Hoàn thành</span>';
                                            elseif ($tt == 4) echo '<span class="badge bg-danger px-3 py-2">Đã hủy</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <a href="hoadon_vat.php?id=<?= $row['MaHoaDon'] ?>" target="_blank" class="btn-vat" title="Xem hóa đơn điện tử">
                                            <i class="fas fa-file-invoice me-1"></i> Hóa đơn VAT
                                        </a>
                                        <a href="bienlai.php?id=<?= $row['MaHoaDon'] ?>" target="_blank" class="btn-vat" title="Xem phiếu giao hàng">
                                            <i class="fas fa-receipt me-1"></i> Phiếu giao
                                        </a>
                                        <?php if($tt == 0 || $tt == 1): ?>
                                            <a href="huy_donhang.php?id=<?= $row['MaHoaDon'] ?>" class="btn-cancel" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này? Số lượng sản phẩm sẽ được tự động hoàn lại kho.');">
                                                <i class="fas fa-ban me-1"></i> Hủy đơn
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-white">Bạn chưa có đơn hàng nào tại BIGCLOCK!</h5>
                    <p class="text-muted">Hãy lựa chọn cho mình chiếc đồng hồ ưng ý từ bộ sưu tập cao cấp của chúng tôi.</p>
                    <a href="index.php#products" class="btn btn-warning mt-2 fw-bold px-4 py-2">Khám Phá Đồng Hồ Ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
