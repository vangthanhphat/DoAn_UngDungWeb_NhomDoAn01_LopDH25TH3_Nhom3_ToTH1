<?php
require_once 'connect.php';

// Lấy 8 sản phẩm mới nhất
$sql = "SELECT * FROM dongho ORDER BY MaDongHo DESC LIMIT 8";
$result = $conn->query($sql);

// Lấy thống kê tổng quan
$cnt_sp = $conn->query("SELECT COUNT(*) as t FROM dongho")->fetch_assoc()['t'];
$cnt_dh = $conn->query("SELECT COUNT(*) as t FROM hoadon")->fetch_assoc()['t'];
$cnt_dt = $conn->query("SELECT SUM(TongTien) as t FROM hoadon WHERE TrangThaiDonHang != 4")->fetch_assoc()['t'] ?? 0;
$cnt_nd = $conn->query("SELECT COUNT(*) as t FROM user")->fetch_assoc()['t'];
?>

<?php include 'header_admin.php'; ?>

<div class="admin-content">
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-speedometer2 me-2"></i> BẢNG ĐIỀU KHIỂN QUẢN TRỊ</h2>
            <p class="mb-0" style="color: #cbd5e1; font-size: 0.95rem;">Hệ thống giám sát kinh doanh &amp; quản lý vận hành <b>BIGCLOCK</b></p>
        </div>
        <div>
            <a href="them_sanpham.php" class="btn btn-gold me-2"><i class="bi bi-plus-lg me-1"></i> Thêm Đồng Hồ</a>
            <a href="doanhthu.php" class="btn btn-outline-warning"><i class="bi bi-file-earmark-excel me-1"></i> Xuất Báo Cáo</a>
        </div>
    </div>

    <!-- 4 Khối Thống Kê Tổng Quan -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="luxury-box p-3 d-flex align-items-center mb-0">
                <div class="p-3 rounded-circle me-3" style="background: rgba(212,175,55,0.15); color: #D4AF37; font-size: 1.8rem;">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <span class="small text-uppercase fw-bold" style="color: #e2e8f0; letter-spacing: 1px; display: block; margin-bottom: 2px;">Doanh Thu Thực Tế</span>
                    <h3 class="text-warning mb-0 fw-bold"><?= number_format($cnt_dt, 0, ',', '.') ?> VNĐ</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="luxury-box p-3 d-flex align-items-center mb-0">
                <div class="p-3 rounded-circle me-3" style="background: rgba(0,255,136,0.15); color: #00ff88; font-size: 1.8rem;">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div>
                    <span class="small text-uppercase fw-bold" style="color: #e2e8f0; letter-spacing: 1px; display: block; margin-bottom: 2px;">Tổng Đơn Hàng</span>
                    <h3 class="text-white mb-0 fw-bold"><?= $cnt_dh ?> đơn</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="luxury-box p-3 d-flex align-items-center mb-0">
                <div class="p-3 rounded-circle me-3" style="background: rgba(13,202,240,0.15); color: #0dcaf0; font-size: 1.8rem;">
                    <i class="bi bi-watch"></i>
                </div>
                <div>
                    <span class="small text-uppercase fw-bold" style="color: #e2e8f0; letter-spacing: 1px; display: block; margin-bottom: 2px;">Mẫu Đồng Hồ</span>
                    <h3 class="text-white mb-0 fw-bold"><?= $cnt_sp ?> mẫu</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="luxury-box p-3 d-flex align-items-center mb-0">
                <div class="p-3 rounded-circle me-3" style="background: rgba(255,193,7,0.15); color: #ffc107; font-size: 1.8rem;">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <span class="small text-uppercase fw-bold" style="color: #e2e8f0; letter-spacing: 1px; display: block; margin-bottom: 2px;">Thành Viên</span>
                    <h3 class="text-white mb-0 fw-bold"><?= $cnt_nd ?> người</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh Sách Sản Phẩm Xem Nhanh -->
    <div class="luxury-box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-warning mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif;"><i class="bi bi-stars me-2"></i> Đồng Hồ Mới Nhất Trong Kho</h4>
            <a href="danhsach.php" class="btn btn-sm btn-outline-warning">Xem Tất Cả <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): 
                    $hinh = str_replace('../', '', $row['HinhAnh'] ?? 'images/no-image.jpg');
                ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card h-100 bg-black border" style="border-color: rgba(212,175,55,0.3) !important;">
                            <div class="p-3 text-center" style="height: 160px; display: flex; align-items: center; justify-content: center; background: #000; border-bottom: 1px solid #222;">
                                <img src="<?= htmlspecialchars($hinh) ?>" alt="" style="max-height: 130px; max-width: 100%; object-fit: contain;">
                            </div>
                            <div class="card-body p-3 text-center d-flex flex-column">
                                <h6 class="text-warning mb-1 fw-bold"><?= htmlspecialchars($row['TenDongHo']) ?></h6>
                                <div class="text-white fw-bold mb-2"><?= number_format($row['DonGia'], 0, ',', '.') ?> VNĐ</div>
                                <div class="mt-auto d-flex justify-content-between align-items-center small text-muted pt-2 border-top border-secondary">
                                    <span>Tồn: <b class="<?= $row['SoLuong'] > 0 ? 'text-success' : 'text-danger' ?>"><?= $row['SoLuong'] ?></b></span>
                                    <span><?= $row['TrangThai'] == 1 ? '<span class="badge bg-success">Đang bán</span>' : '<span class="badge bg-secondary">Tạm ẩn</span>' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
