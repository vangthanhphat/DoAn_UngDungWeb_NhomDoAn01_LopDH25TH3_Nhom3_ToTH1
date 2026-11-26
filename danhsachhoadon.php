<?php
require_once 'connect.php';

if (!isset($_SESSION['MaNguoiDung']) || !isset($_SESSION['Quyen']) || $_SESSION['Quyen'] != 1) {
    header("Location: DangNhap.php");
    exit();
}

// XỬ LÝ CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
if (isset($_POST['capnhat_trangthai'])) {
    $maHD = intval($_POST['ma_hoadon']);
    $tt_moi = intval($_POST['trangthai_moi']);
    
    // Lấy trạng thái cũ
    $sql_old = "SELECT TrangThaiDonHang FROM hoadon WHERE MaHoaDon = $maHD";
    $res_old = $conn->query($sql_old);
    if ($res_old && $res_old->num_rows > 0) {
        $tt_cu = $res_old->fetch_assoc()['TrangThaiDonHang'];
        
        // Nếu chuyển sang trạng thái 4 (Đã hủy) từ trạng thái khác: Hoàn lại số lượng kho
        if ($tt_cu != 4 && $tt_moi == 4) {
            $sql_ct = "SELECT MaDongHo, SoLuong FROM chitiethoadon WHERE MaHoaDon = $maHD";
            $res_ct = $conn->query($sql_ct);
            while ($ct = $res_ct->fetch_assoc()) {
                $mdh = $ct['MaDongHo'];
                $sl = $ct['SoLuong'];
                $conn->query("UPDATE dongho SET SoLuong = SoLuong + $sl WHERE MaDongHo = $mdh");
            }
        } 
        // Nếu chuyển từ trạng thái 4 (Đã hủy) sang trạng thái khác: Trừ lại tồn kho
        elseif ($tt_cu == 4 && $tt_moi != 4) {
            $sql_ct = "SELECT MaDongHo, SoLuong FROM chitiethoadon WHERE MaHoaDon = $maHD";
            $res_ct = $conn->query($sql_ct);
            while ($ct = $res_ct->fetch_assoc()) {
                $mdh = $ct['MaDongHo'];
                $sl = $ct['SoLuong'];
                $conn->query("UPDATE dongho SET SoLuong = SoLuong - $sl WHERE MaDongHo = $mdh");
            }
        }

        $conn->query("UPDATE hoadon SET TrangThaiDonHang = $tt_moi WHERE MaHoaDon = $maHD");
        $_SESSION['success'] = "Cập nhật trạng thái đơn hàng #$maHD thành công!";
    }
    header("Location: danhsachhoadon.php");
    exit();
}

// Tìm kiếm theo mã đơn hàng
$search_mahd = isset($_GET['timkiem_mahd']) ? trim($_GET['timkiem_mahd']) : '';

$sql = "SELECT h.MaHoaDon, h.NgayLap, h.TongTien, h.TrangThaiDonHang,
            COALESCE(NULLIF(h.TenKhachHang, ''), u.TenNguoiDung) AS TenNguoiDung,
            COALESCE(NULLIF(h.EmailKhachHang, ''), u.Email) AS Email,
            COALESCE(NULLIF(h.SDT_GiaoHang, ''), u.SDT) AS SDT
        FROM hoadon h
        LEFT JOIN user u ON h.MaNguoiDung = u.MaNguoiDung
        WHERE 1=1"; 

if ($search_mahd !== '') {
    $sql .= " AND h.MaHoaDon = '" . intval($search_mahd) . "'";
}
$sql .= " ORDER BY h.NgayLap DESC"; 
$result = $conn->query($sql);
?>

<?php include 'header_admin.php'; ?>

<div class="admin-content">
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-receipt me-2"></i> QUẢN LÝ ĐƠN HÀNG</h2>
            <p class="text-muted mb-0">Theo dõi, duyệt đơn hàng và tự động cập nhật số lượng tồn kho</p>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: rgba(0,255,136,0.15); border-color: #00ff88; color: #00ff88;">
            <i class="bi bi-check-circle me-2"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Bộ lọc tìm kiếm -->
    <div class="luxury-box mb-4">
        <form method="GET" action="danhsachhoadon.php" class="row align-items-center g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-warning">#Mã đơn:</span>
                    <input type="number" name="timkiem_mahd" class="form-control bg-dark border-secondary text-white" placeholder="Nhập số mã đơn hàng..." value="<?= htmlspecialchars($search_mahd) ?>">
                    <button type="submit" class="btn btn-gold">Tìm Kiếm</button>
                    <?php if ($search_mahd !== ''): ?>
                        <a href="danhsachhoadon.php" class="btn btn-outline-secondary">Xóa</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-7 text-md-end text-muted small">
                Tổng cộng có: <b class="text-warning"><?= $result ? $result->num_rows : 0 ?></b> đơn đặt hàng
            </div>
        </form>
    </div>

    <!-- Bảng danh sách đơn hàng -->
    <div class="luxury-box">
        <div class="table-responsive">
            <table class="luxury-table">
                <thead>
                    <tr>
                        <th width="70">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đặt hàng</th>
                        <th>Tổng tiền</th>
                        <th width="160">Trạng thái hiện tại</th>
                        <th width="200">Cập nhật trạng thái</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><b class="text-warning">#<?= $row['MaHoaDon'] ?></b></td>
                                <td style="text-align: left;">
                                    <b style="color: #ffffff; font-size: 1rem;"><?= htmlspecialchars($row['TenNguoiDung']) ?></b>
                                    <div class="small" style="color: #cbd5e1; font-weight: 500; margin-top: 2px;"><?= htmlspecialchars($row['Email'] ?? '') ?></div>
                                </td>
                                <td><?= htmlspecialchars($row['SDT'] ?? '') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['NgayLap'])) ?></td>
                                <td><b class="text-warning"><?= number_format($row['TongTien'], 0, ',', '.') ?> VNĐ</b></td>
                                <td>
                                    <?php 
                                        $tt = $row['TrangThaiDonHang'];
                                        if ($tt == 0) echo '<span class="badge bg-warning text-dark px-3 py-1">Chờ duyệt</span>';
                                        elseif ($tt == 1) echo '<span class="badge bg-info text-dark px-3 py-1">Đã duyệt</span>';
                                        elseif ($tt == 2) echo '<span class="badge bg-primary px-3 py-1">Đang giao</span>';
                                        elseif ($tt == 3) echo '<span class="badge bg-success px-3 py-1">Hoàn thành</span>';
                                        elseif ($tt == 4) echo '<span class="badge bg-danger px-3 py-1">Đã hủy</span>';
                                    ?>
                                </td>
                                <td>
                                    <form method="POST" action="danhsachhoadon.php" class="d-flex gap-1 justify-content-center">
                                        <input type="hidden" name="ma_hoadon" value="<?= $row['MaHoaDon'] ?>">
                                        <select name="trangthai_moi" class="form-select form-select-sm bg-dark border-secondary text-white" style="width: 130px;">
                                            <option value="0" <?= $tt == 0 ? 'selected' : '' ?>>Chờ duyệt</option>
                                            <option value="1" <?= $tt == 1 ? 'selected' : '' ?>>Đã duyệt</option>
                                            <option value="2" <?= $tt == 2 ? 'selected' : '' ?>>Đang giao</option>
                                            <option value="3" <?= $tt == 3 ? 'selected' : '' ?>>Hoàn thành</option>
                                            <option value="4" <?= $tt == 4 ? 'selected' : '' ?>>Đã hủy</option>
                                        </select>
                                        <button type="submit" name="capnhat_trangthai" class="btn btn-sm btn-gold" title="Cập nhật">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="chitiethoadon.php?id=<?= $row['MaHoaDon'] ?>" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-muted py-4">Không tìm thấy đơn hàng nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
