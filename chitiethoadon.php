<?php
require_once 'connect.php';

if (!isset($_SESSION['MaNguoiDung']) || !isset($_SESSION['Quyen']) || $_SESSION['Quyen'] != 1) {
    header("Location: DangNhap.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: danhsachhoadon.php");
    exit();
}

$hoadon_id = intval($_GET['id']);

// Lấy thông tin chung của đơn hàng
$sql_hoadon = "SELECT h.*, 
               COALESCE(NULLIF(h.TenKhachHang, ''), u.TenNguoiDung) AS TenNguoiDung, 
               COALESCE(NULLIF(h.EmailKhachHang, ''), u.Email) AS Email, 
               COALESCE(NULLIF(h.SDT_GiaoHang, ''), u.SDT) AS UserSDT 
               FROM hoadon h 
               LEFT JOIN user u ON h.MaNguoiDung = u.MaNguoiDung 
               WHERE h.MaHoaDon = ?";
$stmt_hd = $conn->prepare($sql_hoadon);
$stmt_hd->bind_param("i", $hoadon_id);
$stmt_hd->execute();
$res_hd = $stmt_hd->get_result();

if ($res_hd->num_rows == 0) {
    die("Không tìm thấy đơn hàng này!");
}
$hd = $res_hd->fetch_assoc();

// Lấy chi tiết các sản phẩm trong đơn
$sql_ct = "SELECT ct.*, dh.TenDongHo, dh.HinhAnh 
           FROM chitiethoadon ct 
           JOIN dongho dh ON ct.MaDongHo = dh.MaDongHo 
           WHERE ct.MaHoaDon = ?";
$stmt_ct = $conn->prepare($sql_ct);
$stmt_ct->bind_param("i", $hoadon_id);
$stmt_ct->execute();
$res_ct = $stmt_ct->get_result();
?>

<?php include 'header_admin.php'; ?>

<div class="admin-content">
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-receipt me-2"></i> CHI TIẾT ĐƠN HÀNG #<?= $hoadon_id ?></h2>
            <p class="text-muted mb-0">Thời gian đặt: <?= date('d/m/Y H:i', strtotime($hd['NgayLap'])) ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="hoadon_vat.php?id=<?= $hoadon_id ?>" target="_blank" class="btn btn-gold">
                <i class="bi bi-printer me-1"></i> In Hóa Đơn VAT
            </a>
            <a href="bienlai.php?id=<?= $hoadon_id ?>" target="_blank" class="btn btn-outline-warning">
                <i class="bi bi-file-earmark-text me-1"></i> In Phiếu Giao
            </a>
            <a href="danhsachhoadon.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Thông tin khách hàng & Giao hàng -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="luxury-box h-100 p-4">
                <h5 class="text-warning mb-3 pb-2 border-bottom border-secondary" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    <i class="bi bi-person-circle me-2"></i> Thông Tin Khách Hàng
                </h5>
                <p class="mb-2"><span class="text-muted">Họ tên:</span> <b style="color: #ffffff;"><?= htmlspecialchars($hd['TenNguoiDung']) ?></b></p>
                <p class="mb-2"><span class="text-muted">Email:</span> <b style="color: #ffffff;"><?= htmlspecialchars($hd['Email'] ?? 'Chưa cập nhật') ?></b></p>
                <p class="mb-2"><span class="text-muted">SĐT tài khoản:</span> <b style="color: #ffffff;"><?= htmlspecialchars($hd['UserSDT'] ?? 'Chưa có') ?></b></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="luxury-box h-100 p-4">
                <h5 class="text-warning mb-3 pb-2 border-bottom border-secondary" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    <i class="bi bi-geo-alt me-2"></i> Thông Tin Giao Hàng &amp; Trạng Thái
                </h5>
                <p class="mb-2"><span class="text-muted">SĐT nhận hàng:</span> <b class="text-warning"><?= htmlspecialchars($hd['SDT_GiaoHang']) ?></b></p>
                <p class="mb-2"><span class="text-muted">Địa chỉ nhận:</span> <b style="color: #ffffff;"><?= htmlspecialchars($hd['DiaChi']) ?></b></p>
                <p class="mb-0">
                    <span class="text-muted">Trạng thái đơn:</span> 
                    <?php 
                        $tt = $hd['TrangThaiDonHang'];
                        if ($tt == 0) echo '<span class="badge bg-warning text-dark px-3 py-1">Chờ duyệt</span>';
                        elseif ($tt == 1) echo '<span class="badge bg-info text-dark px-3 py-1">Đã duyệt</span>';
                        elseif ($tt == 2) echo '<span class="badge bg-primary px-3 py-1">Đang giao</span>';
                        elseif ($tt == 3) echo '<span class="badge bg-success px-3 py-1">Hoàn thành</span>';
                        elseif ($tt == 4) echo '<span class="badge bg-danger px-3 py-1">Đã hủy</span>';
                    ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách các mặt hàng trong đơn -->
    <div class="luxury-box">
        <h5 class="text-warning mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif;">Danh Sách Đồng Hồ Đã Đặt</h5>
        <div class="table-responsive">
            <table class="luxury-table">
                <thead>
                    <tr>
                        <th width="60">STT</th>
                        <th width="80">Hình ảnh</th>
                        <th style="text-align: left;">Tên mẫu đồng hồ</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stt = 1; 
                    $tong = 0;
                    while ($ct = $res_ct->fetch_assoc()): 
                        $hinh = str_replace('../', '', $ct['HinhAnh'] ?? 'images/no-image.jpg');
                        $sub = $ct['SoLuong'] * $ct['DonGia'];
                        $tong += $sub;
                    ?>
                        <tr>
                            <td><?= $stt++ ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($hinh) ?>" alt="" style="width: 50px; height: 50px; object-fit: contain; background: #000; border: 1px solid #333; border-radius: 4px;">
                            </td>
                            <td style="text-align: left;"><b class="text-white"><?= htmlspecialchars($ct['TenDongHo']) ?></b></td>
                            <td><?= number_format($ct['DonGia'], 0, ',', '.') ?> VNĐ</td>
                            <td><b class="text-warning"><?= $ct['SoLuong'] ?> chiếc</b></td>
                            <td class="text-warning fw-bold"><?= number_format($sub, 0, ',', '.') ?> VNĐ</td>
                        </tr>
                    <?php endwhile; ?>
                    <tr style="background: rgba(212,175,55,0.08);">
                        <td colspan="5" style="text-align: right; padding-right: 20px;"><b>TỔNG CỘNG THANH TOÁN:</b></td>
                        <td><b class="text-warning fs-5"><?= number_format($tong, 0, ',', '.') ?> VNĐ</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
