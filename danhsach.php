<?php
require_once 'connect.php';

if (!isset($_SESSION['MaNguoiDung']) || !isset($_SESSION['Quyen']) || $_SESSION['Quyen'] != 1) {
    header("Location: DangNhap.php");
    exit();
}

// 1. XỬ LÝ ẨN/HIỆN TRẠNG THÁI SẢN PHẨM
if (isset($_POST['toggle_id'])) {
    $toggle_id = intval($_POST['toggle_id']);
    $new_status = intval($_POST['current_status']) == 1 ? 0 : 1;
    $stmt = $conn->prepare("UPDATE dongho SET TrangThai = ? WHERE MaDongHo = ?");
    $stmt->bind_param("ii", $new_status, $toggle_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Đã cập nhật trạng thái hiển thị của đồng hồ #$toggle_id!";
    }
    header("Location: danhsach.php");
    exit();
}

// 2. XỬ LÝ XÓA SẢN PHẨM
if (isset($_GET['xoa'])) {
    $id = intval($_GET['xoa']);
    
    // Kiểm tra xem đồng hồ đã có trong hóa đơn chưa (ràng buộc khóa ngoại)
    $check_fk = $conn->prepare("SELECT MaChiTiet FROM chitiethoadon WHERE MaDongHo = ?");
    $check_fk->bind_param("i", $id);
    $check_fk->execute();
    $check_fk->store_result();
    
    if ($check_fk->num_rows > 0) {
        $_SESSION['error'] = "KHÔNG THỂ XÓA! Đồng hồ này đã có trong đơn hàng của khách. Bạn có thể chọn chức năng TẠM ẨN thay vì xóa.";
    } else {
        $check = $conn->prepare("SELECT HinhAnh FROM dongho WHERE MaDongHo = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $check->bind_result($hinhanh);
        if ($check->fetch()) {
            $check->close();
            $stmt = $conn->prepare("DELETE FROM dongho WHERE MaDongHo = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Xóa đồng hồ thành công khỏi hệ thống!";
            } else {
                $_SESSION['error'] = "Lỗi khi xóa: " . $conn->error;
            }
        }
    }
    header("Location: danhsach.php");
    exit();
}

// 3. TÌM KIẾM
$search = isset($_GET['timkiem']) ? trim($_GET['timkiem']) : '';
$where = "WHERE 1=1";
if (!empty($search)) {
    $safe_search = $conn->real_escape_string($search);
    $where .= " AND TenDongHo LIKE '%$safe_search%'";
}

$sql = "SELECT * FROM dongho $where ORDER BY MaDongHo DESC";
$result = $conn->query($sql);
?>

<?php include 'header_admin.php'; ?>

<div class="admin-content">
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-watch me-2"></i> QUẢN LÝ DANH SÁCH ĐỒNG HỒ</h2>
            <p class="text-muted mb-0">Xem, chỉnh sửa giá bán, giá nhập, tồn kho và trạng thái các mẫu đồng hồ</p>
        </div>
        <div>
            <a href="them_sanpham.php" class="btn btn-gold">
                <i class="bi bi-plus-lg me-1"></i> Thêm Đồng Hồ Mới
            </a>
        </div>
    </div>

    <!-- Thông báo -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: rgba(0,255,136,0.15); border-color: #00ff88; color: #00ff88;">
            <i class="bi bi-check-circle me-2"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Bộ lọc & Tìm kiếm -->
    <div class="luxury-box mb-4">
        <form method="GET" action="danhsach.php" class="row align-items-center g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-warning"><i class="bi bi-search"></i></span>
                    <input type="text" name="timkiem" class="form-control bg-dark border-secondary text-white" placeholder="Tìm theo tên đồng hồ..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-gold">Tìm Kiếm</button>
                    <?php if (!empty($search)): ?>
                        <a href="danhsach.php" class="btn btn-outline-secondary">Xóa</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6 text-md-end text-muted small">
                Tổng cộng có: <b class="text-warning"><?= $result ? $result->num_rows : 0 ?></b> mẫu đồng hồ
            </div>
        </form>
    </div>

    <!-- Bảng danh sách sản phẩm -->
    <div class="luxury-box">
        <div class="table-responsive">
            <table class="luxury-table">
                <thead>
                    <tr>
                        <th width="50">Mã</th>
                        <th width="80">Hình ảnh</th>
                        <th style="text-align: left;">Tên mẫu đồng hồ</th>
                        <th>Giá nhập</th>
                        <th>Giá bán</th>
                        <th>Chênh lệch</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th width="180">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): 
                            $hinh = str_replace('../', '', $row['HinhAnh'] ?? 'images/no-image.jpg');
                            $chenhlech = $row['DonGia'] - $row['GiaNhap'];
                        ?>
                            <tr>
                                <td>#<?= $row['MaDongHo'] ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars($hinh) ?>" alt="" style="width: 55px; height: 55px; object-fit: contain; background: #000; border: 1px solid #333; border-radius: 6px; padding: 2px;">
                                </td>
                                <td style="text-align: left;">
                                    <b class="text-white"><?= htmlspecialchars($row['TenDongHo']) ?></b>
                                </td>
                                <td style="color: #93c5fd; font-weight: 600;"><?= number_format($row['GiaNhap'], 0, ',', '.') ?> VNĐ</td>
                                <td class="text-warning fw-bold"><?= number_format($row['DonGia'], 0, ',', '.') ?> VNĐ</td>
                                <td class="text-success fw-bold">+<?= number_format($chenhlech, 0, ',', '.') ?> VNĐ</td>
                                <td>
                                    <span class="badge <?= $row['SoLuong'] > 10 ? 'bg-success' : ($row['SoLuong'] > 0 ? 'bg-warning text-dark' : 'bg-danger') ?> px-2 py-1">
                                        <?= $row['SoLuong'] ?> chiếc
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="danhsach.php" style="display: inline-block;">
                                        <input type="hidden" name="toggle_id" value="<?= $row['MaDongHo'] ?>">
                                        <input type="hidden" name="current_status" value="<?= $row['TrangThai'] ?>">
                                        <?php if ($row['TrangThai'] == 1): ?>
                                            <button type="submit" class="btn btn-sm btn-success px-2 py-1" title="Bấm để ẩn sản phẩm">
                                                <i class="bi bi-eye"></i> Đang bán
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-sm btn-secondary px-2 py-1" title="Bấm để hiện sản phẩm">
                                                <i class="bi bi-eye-slash"></i> Tạm ẩn
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                                <td>
                                    <a href="sua_sanpham.php?id=<?= $row['MaDongHo'] ?>" class="btn btn-sm btn-outline-warning me-1" title="Chỉnh sửa">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
                                    <a href="danhsach.php?xoa=<?= $row['MaDongHo'] ?>" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa đồng hồ này? Hành động này không thể hoàn tác.');">
                                        <i class="bi bi-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-muted py-4">Chưa có sản phẩm nào phù hợp.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
