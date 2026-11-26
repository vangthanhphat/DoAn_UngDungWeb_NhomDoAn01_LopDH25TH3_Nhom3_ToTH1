<?php
require_once 'connect.php';

if (!isset($_SESSION['MaNguoiDung']) || !isset($_SESSION['Quyen']) || $_SESSION['Quyen'] != 1) {
    header("Location: DangNhap.php");
    exit();
}

$tu_ngay = isset($_GET['tu_ngay']) ? $_GET['tu_ngay'] : date('Y-m-d', strtotime('-30 days'));
$den_ngay = isset($_GET['den_ngay']) ? $_GET['den_ngay'] : date('Y-m-d');

$sql_tu_ngay = $tu_ngay . " 00:00:00";
$sql_den_ngay = $den_ngay . " 23:59:59";

// 1. TÍNH TỔNG QUAN DOANH THU & LỢI NHUẬN
$sql_tongquan = "SELECT 
                    COUNT(DISTINCT h.MaHoaDon) as TongDonHang,
                    SUM(ct.SoLuong * ct.DonGia) as TongDoanhThu,
                    SUM(ct.SoLuong * IFNULL(dh.GiaNhap, 0)) as TongTienVon
                 FROM hoadon h
                 JOIN chitiethoadon ct ON h.MaHoaDon = ct.MaHoaDon
                 JOIN dongho dh ON ct.MaDongHo = dh.MaDongHo
                 WHERE h.NgayLap >= '$sql_tu_ngay' AND h.NgayLap <= '$sql_den_ngay' AND h.TrangThaiDonHang != 4";
$result_tq = $conn->query($sql_tongquan);
$tongquan = $result_tq->fetch_assoc();

$tongDon = $tongquan['TongDonHang'] ?? 0;
$tongDoanhThu = $tongquan['TongDoanhThu'] ?? 0;
$tongVon = $tongquan['TongTienVon'] ?? 0;
$tongLoiNhuan = $tongDoanhThu - $tongVon;

// 2. CHI TIẾT THEO NGÀY
$sql_ngay = "SELECT 
                DATE(h.NgayLap) as Ngay,
                COUNT(DISTINCT h.MaHoaDon) as SoDon,
                SUM(ct.SoLuong) as SoSanPhamBanRa,
                SUM(ct.SoLuong * ct.DonGia) as DoanhThuNgay,
                SUM(ct.SoLuong * IFNULL(dh.GiaNhap, 0)) as TienVonNgay
             FROM hoadon h
             JOIN chitiethoadon ct ON h.MaHoaDon = ct.MaHoaDon
             JOIN dongho dh ON ct.MaDongHo = dh.MaDongHo
             WHERE h.NgayLap >= '$sql_tu_ngay' AND h.NgayLap <= '$sql_den_ngay' AND h.TrangThaiDonHang != 4
             GROUP BY DATE(h.NgayLap)
             ORDER BY DATE(h.NgayLap) DESC";
$result_ngay = $conn->query($sql_ngay);

// 3. TÍNH NĂNG XUẤT EXCEL (.XLS)
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    $filename = "Bao_Cao_Doanh_Thu_BIGCLOCK_" . date('d_m_Y') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
    echo '<head><meta charset="utf-8"></head><body>';
    echo '<h2 style="text-align: center; color: #D4AF37;">CÔNG TY CP ĐỒNG HỒ CAO CẤP BIGCLOCK</h2>';
    echo '<h3 style="text-align: center;">BÁO CÁO DOANH THU &amp; LỢI NHUẬN BÁN HÀNG</h3>';
    echo '<p style="text-align: center;">Khoảng thời gian: ' . date('d/m/Y', strtotime($tu_ngay)) . ' - ' . date('d/m/Y', strtotime($den_ngay)) . '</p>';
    
    echo '<table border="1" style="border-collapse: collapse; width: 100%; margin-bottom: 25px;">
            <tr>
                <th style="background-color: #D4AF37; color: #000; padding: 10px;">Tổng Số Đơn Hàng</th>
                <th style="background-color: #D4AF37; color: #000; padding: 10px;">Tổng Doanh Thu (VNĐ)</th>
                <th style="background-color: #D4AF37; color: #000; padding: 10px;">Tổng Tiền Vốn (VNĐ)</th>
                <th style="background-color: #D4AF37; color: #000; padding: 10px;">Lợi Nhuận Thuần (VNĐ)</th>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold; padding: 10px;">' . $tongDon . '</td>
                <td style="text-align: center; font-weight: bold; padding: 10px;">' . number_format($tongDoanhThu, 0, ',', '.') . ' VNĐ</td>
                <td style="text-align: center; font-weight: bold; padding: 10px;">' . number_format($tongVon, 0, ',', '.') . ' VNĐ</td>
                <td style="text-align: center; font-weight: bold; color: green; padding: 10px;">' . number_format($tongLoiNhuan, 0, ',', '.') . ' VNĐ</td>
            </tr>
          </table><br>';

    echo '<table border="1" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="background-color: #222; color: #fff; padding: 10px;">Ngày Bán</th>
                    <th style="background-color: #222; color: #fff; padding: 10px;">Số Đơn</th>
                    <th style="background-color: #222; color: #fff; padding: 10px;">Sản Phẩm Đã Bán</th>
                    <th style="background-color: #222; color: #fff; padding: 10px;">Doanh Thu (VNĐ)</th>
                    <th style="background-color: #222; color: #fff; padding: 10px;">Tiền Vốn (VNĐ)</th>
                    <th style="background-color: #222; color: #fff; padding: 10px;">Lợi Nhuận (VNĐ)</th>
                </tr>
            </thead>
            <tbody>';
    
    if ($result_ngay && $result_ngay->num_rows > 0) {
        mysqli_data_seek($result_ngay, 0); 
        while ($row = $result_ngay->fetch_assoc()) {
            $loi_nhuan_ngay = $row['DoanhThuNgay'] - $row['TienVonNgay'];
            echo '<tr>
                    <td style="text-align: center; padding: 8px;">' . date('d/m/Y', strtotime($row['Ngay'])) . '</td>
                    <td style="text-align: center; padding: 8px;">' . $row['SoDon'] . '</td>
                    <td style="text-align: center; padding: 8px;">' . $row['SoSanPhamBanRa'] . ' chiếc</td>
                    <td style="text-align: right; font-weight: bold; padding: 8px;">' . number_format($row['DoanhThuNgay'], 0, ',', '.') . ' VNĐ</td>
                    <td style="text-align: right; padding: 8px;">' . number_format($row['TienVonNgay'], 0, ',', '.') . ' VNĐ</td>
                    <td style="text-align: right; font-weight: bold; color: green; padding: 8px;">' . number_format($loi_nhuan_ngay, 0, ',', '.') . ' VNĐ</td>
                  </tr>';
        }
    } else {
        echo '<tr><td colspan="6" style="text-align: center; padding: 15px;">Không có giao dịch trong khoảng thời gian này</td></tr>';
    }
    echo '</tbody></table></body></html>';
    exit();
}
?>

<?php include 'header_admin.php'; ?>

<div class="admin-content">
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-graph-up-arrow me-2"></i> BÁO CÁO DOANH THU &amp; LỢI NHUẬN</h2>
            <p class="mb-0" style="color: #cbd5e1; font-size: 0.95rem;">Hệ thống phân tích lợi nhuận tài chính của <b>BIGCLOCK</b></p>
        </div>
        
        <!-- Nút Xuất Excel -->
        <div>
            <a href="?tu_ngay=<?= $tu_ngay ?>&den_ngay=<?= $den_ngay ?>&export=excel" class="btn btn-gold">
                <i class="bi bi-file-earmark-excel me-2"></i> XUẤT BÁO CÁO EXCEL (.XLS)
            </a>
        </div>
    </div>

    <!-- Bộ Lọc Khoảng Ngày -->
    <div class="luxury-box mb-4">
        <form method="GET" action="doanhthu.php" class="row align-items-end g-3">
            <div class="col-md-4">
                <label class="form-label text-warning small fw-bold"><i class="bi bi-calendar-event me-1"></i> Từ ngày:</label>
                <input type="date" name="tu_ngay" class="form-control" value="<?= htmlspecialchars($tu_ngay) ?>" style="background: #222; border-color: #555; color: #fff;">
            </div>
            <div class="col-md-4">
                <label class="form-label text-warning small fw-bold"><i class="bi bi-calendar-check me-1"></i> Đến ngày:</label>
                <input type="date" name="den_ngay" class="form-control" value="<?= htmlspecialchars($den_ngay) ?>" style="background: #222; border-color: #555; color: #fff;">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-gold flex-fill">
                    <i class="bi bi-funnel me-1"></i> Xem Báo Cáo
                </button>
                <a href="doanhthu.php" class="btn btn-outline-secondary">Đặt Lại</a>
            </div>
        </form>
    </div>

    <!-- 4 Khối Chỉ Số Tổng Hợp -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="luxury-box p-3 text-center mb-0">
                <span class="small text-uppercase fw-bold" style="color: #e2e8f0; letter-spacing: 1px; display: block;">Tổng Đơn Hàng Thành Công</span>
                <h2 class="text-white fw-bold my-2"><?= number_format($tongDon) ?></h2>
                <span class="badge bg-secondary">Đơn hoàn tất</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="luxury-box p-3 text-center mb-0">
                <span class="small text-uppercase fw-bold" style="color: #e2e8f0; letter-spacing: 1px; display: block;">Tổng Doanh Thu</span>
                <h2 class="text-warning fw-bold my-2"><?= number_format($tongDoanhThu, 0, ',', '.') ?> VNĐ</h2>
                <span class="badge bg-warning text-dark">Gross Revenue</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="luxury-box p-3 text-center mb-0">
                <span class="small text-uppercase fw-bold" style="color: #e2e8f0; letter-spacing: 1px; display: block;">Tổng Tiền Vốn Hàng Bán</span>
                <h2 class="text-light fw-bold my-2"><?= number_format($tongVon, 0, ',', '.') ?> VNĐ</h2>
                <span class="badge bg-dark border">Cost of Goods</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="luxury-box p-3 text-center mb-0" style="border-color: #00ff88;">
                <span class="small text-uppercase fw-bold" style="color: #e2e8f0; letter-spacing: 1px; display: block;">Lợi Nhuận Thuần</span>
                <h2 class="text-success fw-bold my-2"><?= number_format($tongLoiNhuan, 0, ',', '.') ?> VNĐ</h2>
                <span class="badge bg-success">Net Profit (VNĐ)</span>
            </div>
        </div>
    </div>

    <!-- Bảng Thống Kê Theo Ngày -->
    <div class="luxury-box">
        <h4 class="text-warning mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif;"><i class="bi bi-table me-2"></i> Chi Tiết Doanh Thu Theo Từng Ngày Bán</h4>
        
        <div class="table-responsive">
            <table class="luxury-table">
                <thead>
                    <tr>
                        <th>Ngày Bán</th>
                        <th>Số Lượng Đơn</th>
                        <th>Số Đồng Hồ Bán Ra</th>
                        <th>Doanh Thu Trong Ngày</th>
                        <th>Tiền Vốn Nhập</th>
                        <th>Lợi Nhuận Thuần</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_ngay && $result_ngay->num_rows > 0): ?>
                        <?php while ($row = $result_ngay->fetch_assoc()): 
                            $ln = $row['DoanhThuNgay'] - $row['TienVonNgay'];
                        ?>
                            <tr>
                                <td><b><?= date('d/m/Y', strtotime($row['Ngay'])) ?></b></td>
                                <td><span class="badge bg-info text-dark px-3 py-1"><?= $row['SoDon'] ?> đơn</span></td>
                                <td style="color: #f1f5f9;"><?= $row['SoSanPhamBanRa'] ?> chiếc</td>
                                <td class="text-warning fw-bold"><?= number_format($row['DoanhThuNgay'], 0, ',', '.') ?> VNĐ</td>
                                <td style="color: #93c5fd; font-weight: 600;"><?= number_format($row['TienVonNgay'], 0, ',', '.') ?> VNĐ</td>
                                <td class="text-success fw-bold">+<?= number_format($ln, 0, ',', '.') ?> VNĐ</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-muted py-4">Không có giao dịch phát sinh trong khoảng thời gian đã chọn.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
