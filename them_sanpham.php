<?php
require_once 'connect.php';

if (!isset($_SESSION['MaNguoiDung']) || !isset($_SESSION['Quyen']) || $_SESSION['Quyen'] != 1) {
    header("Location: DangNhap.php");
    exit();
}

// Xử lý thêm sản phẩm
if (($_SERVER['REQUEST_METHOD'] ?? '') == 'POST' && isset($_POST['luu'])) {
    $tendh = trim($_POST['TenDongHo']);
    $dongia = floatval($_POST['DonGia']);
    $gianhap = floatval($_POST['GiaNhap']);
    $soluong = intval($_POST['SoLuong']);
    $mota = trim($_POST['MoTa'] ?? '');
    $hinhanh = 'images/no-image.jpg';

    // Xử lý upload ảnh
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

    $sql = "INSERT INTO dongho (TenDongHo, DonGia, GiaNhap, SoLuong, MoTa, HinhAnh, TrangThai) VALUES (?, ?, ?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sddiss", $tendh, $dongia, $gianhap, $soluong, $mota, $hinhanh);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Thêm mẫu đồng hồ mới thành công!";
        header("Location: danhsach.php");
        exit();
    } else {
        $error = "Lỗi khi thêm đồng hồ: " . $conn->error;
    }
}
?>

<?php include 'header_admin.php'; ?>

<div class="admin-content">
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-plus-circle me-2"></i> THÊM MẪU ĐỒNG HỒ MỚI</h2>
            <p class="text-muted mb-0">Bổ sung sản phẩm mới vào danh mục của <b>BIGCLOCK</b></p>
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
                <form action="them_sanpham.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label text-warning small fw-bold">Tên mẫu đồng hồ (*):</label>
                        <input type="text" name="TenDongHo" class="form-control bg-dark border-secondary text-white" placeholder="Ví dụ: BigClock Royal Chronometer Gold Edition" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-warning small fw-bold">Giá nhập vốn (VNĐ) (*):</label>
                            <input type="number" step="0.01" name="GiaNhap" class="form-control bg-dark border-secondary text-white" placeholder="Ví dụ: 4500" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-warning small fw-bold">Giá bán niêm yết (VNĐ) (*):</label>
                            <input type="number" step="0.01" name="DonGia" class="form-control bg-dark border-secondary text-white" placeholder="Ví dụ: 6800" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-warning small fw-bold">Số lượng nhập kho (*):</label>
                            <input type="number" name="SoLuong" class="form-control bg-dark border-secondary text-white" value="50" min="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-warning small fw-bold">Hình ảnh sản phẩm:</label>
                        <input type="file" name="HinhAnh" class="form-control bg-dark border-secondary text-white" accept="image/*">
                        <small class="text-muted">Định dạng hỗ trợ: JPG, PNG, WEBP. Ảnh được lưu vào thư mục <code>uploads/</code>.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-warning small fw-bold">Mô tả chi tiết kỹ thuật:</label>
                        <textarea name="MoTa" rows="4" class="form-control bg-dark border-secondary text-white" placeholder="Chất liệu vỏ, loại máy Automatic, kính sapphire, tính năng kháng nước, trữ cót..."></textarea>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" name="luu" class="btn btn-gold flex-fill py-3 text-uppercase">
                            <i class="bi bi-check-lg me-2"></i> LƯU ĐỒNG HỒ MỚI
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
