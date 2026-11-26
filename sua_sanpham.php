<?php
require_once 'connect.php';

if (!isset($_SESSION['MaNguoiDung']) || !isset($_SESSION['Quyen']) || $_SESSION['Quyen'] != 1) {
    header("Location: DangNhap.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql_get = "SELECT * FROM dongho WHERE MaDongHo = ?";
$stmt_get = $conn->prepare($sql_get);
$stmt_get->bind_param("i", $id);
$stmt_get->execute();
$res_get = $stmt_get->get_result();

if ($res_get->num_rows == 0) {
    $_SESSION['error'] = "Không tìm thấy đồng hồ!";
    header("Location: danhsach.php");
    exit();
}
$sp = $res_get->fetch_assoc();

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['capnhat'])) {
    $tendh = trim($_POST['TenDongHo']);
    $dongia = floatval($_POST['DonGia']);
    $gianhap = floatval($_POST['GiaNhap']);
    $soluong = intval($_POST['SoLuong']);
    $trangthai = intval($_POST['TrangThai']);
    $mota = trim($_POST['MoTa'] ?? '');
    $hinhanh = $sp['HinhAnh'];

    // Nếu người dùng chọn file ảnh mới
    if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_ext = strtolower(pathinfo($_FILES['HinhAnh']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($file_ext, $allowed)) {
            $new_filename = uniqid('bc_') . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
            if (move_uploaded_file($_FILES['HinhAnh']['tmp_name'], $target_file)) {
                $hinhanh = $target_file;
            }
        }
    }

    $sql_up = "UPDATE dongho SET TenDongHo=?, DonGia=?, GiaNhap=?, SoLuong=?, MoTa=?, HinhAnh=?, TrangThai=? WHERE MaDongHo=?";
    $stmt_up = $conn->prepare($sql_up);
    $stmt_up->bind_param("sddissii", $tendh, $dongia, $gianhap, $soluong, $mota, $hinhanh, $trangthai, $id);
    
    if ($stmt_up->execute()) {
        $_SESSION['success'] = "Cập nhật thông tin đồng hồ #$id thành công!";
        header("Location: danhsach.php");
        exit();
    } else {
        $error = "Lỗi khi cập nhật: " . $conn->error;
    }
}

$hinhHienTai = str_replace('../', '', $sp['HinhAnh'] ?? 'images/no-image.jpg');
?>

<?php include 'header_admin.php'; ?>

<div class="admin-content">
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-pencil-square me-2"></i> CHỈNH SỬA ĐỒNG HỒ #<?= $id ?></h2>
            <p class="text-muted mb-0">Cập nhật giá cả, số lượng tồn kho và hình ảnh sản phẩm</p>
        </div>
        <div>
            <a href="danhsach.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Quay lại danh sách</a>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger py-2 px-3 mb-3"><?= $error ?></div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="luxury-box">
                <form action="sua_sanpham.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label text-warning small fw-bold">Tên mẫu đồng hồ (*):</label>
                        <input type="text" name="TenDongHo" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($sp['TenDongHo']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-warning small fw-bold">Giá nhập vốn (VNĐ):</label>
                            <input type="number" step="0.01" name="GiaNhap" class="form-control bg-dark border-secondary text-white" value="<?= $sp['GiaNhap'] ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-warning small fw-bold">Giá bán niêm yết ($):</label>
                            <input type="number" step="0.01" name="DonGia" class="form-control bg-dark border-secondary text-white" value="<?= $sp['DonGia'] ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-warning small fw-bold">Số lượng tồn kho:</label>
                            <input type="number" name="SoLuong" class="form-control bg-dark border-secondary text-white" value="<?= $sp['SoLuong'] ?>" min="0" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-warning small fw-bold">Trạng thái:</label>
                            <select name="TrangThai" class="form-select bg-dark border-secondary text-white">
                                <option value="1" <?= $sp['TrangThai'] == 1 ? 'selected' : '' ?>>Đang bán</option>
                                <option value="0" <?= $sp['TrangThai'] == 0 ? 'selected' : '' ?>>Tạm ẩn</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-warning small fw-bold">Hình ảnh sản phẩm:</label>
                        <div class="d-flex align-items-center mb-2">
                            <img src="<?= htmlspecialchars($hinhHienTai) ?>" style="width: 70px; height: 70px; object-fit: contain; background: #000; border: 1px solid #444; border-radius: 6px; margin-right: 15px;">
                            <span class="small text-muted">Ảnh hiện tại: <code><?= htmlspecialchars($sp['HinhAnh']) ?></code></span>
                        </div>
                        <input type="file" name="HinhAnh" class="form-control bg-dark border-secondary text-white" accept="image/*">
                        <small class="text-muted">Để trống nếu không muốn thay đổi ảnh hiện tại.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-warning small fw-bold">Mô tả sản phẩm:</label>
                        <textarea name="MoTa" rows="4" class="form-control bg-dark border-secondary text-white"><?= htmlspecialchars($sp['MoTa'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" name="capnhat" class="btn btn-gold flex-fill py-3 text-uppercase">
                            <i class="bi bi-save me-2"></i> LƯU THAY ĐỔI
                        </button>
                        <a href="danhsach.php" class="btn btn-outline-secondary flex-fill py-3 text-center">Hủy Bỏ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
