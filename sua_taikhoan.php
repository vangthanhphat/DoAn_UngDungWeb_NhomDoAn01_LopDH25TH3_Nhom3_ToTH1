<?php
require_once 'connect.php';

if (!isset($_SESSION['MaNguoiDung']) || !isset($_SESSION['Quyen']) || $_SESSION['Quyen'] != 1) {
    header("Location: DangNhap.php");
    exit();
}

$id = isset($_GET['MaNguoiDung']) ? intval($_GET['MaNguoiDung']) : 0;

$sql_get = "SELECT * FROM user WHERE MaNguoiDung = ?";
$stmt_get = $conn->prepare($sql_get);
$stmt_get->bind_param("i", $id);
$stmt_get->execute();
$res_get = $stmt_get->get_result();

if ($res_get->num_rows == 0) {
    $_SESSION['error'] = "Không tìm thấy người dùng!";
    header("Location: quanlytaikhoan.php");
    exit();
}
$user = $res_get->fetch_assoc();

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['capnhat'])) {
    $tenNguoiDung = trim($_POST['TenNguoiDung']);
    $email = trim($_POST['Email'] ?? '');
    $sdt = trim($_POST['SDT'] ?? '');
    $diaChi = trim($_POST['DiaChi'] ?? '');
    $quyen = intval($_POST['Quyen']);
    $matKhauMoi = trim($_POST['MatKhau'] ?? '');

    if (!empty($matKhauMoi)) {
        $matKhauMD5 = md5($matKhauMoi);
        $sql_up = "UPDATE user SET TenNguoiDung=?, Email=?, SDT=?, DiaChi=?, Quyen=?, MatKhau=? WHERE MaNguoiDung=?";
        $stmt_up = $conn->prepare($sql_up);
        $stmt_up->bind_param("ssssisi", $tenNguoiDung, $email, $sdt, $diaChi, $quyen, $matKhauMD5, $id);
    } else {
        $sql_up = "UPDATE user SET TenNguoiDung=?, Email=?, SDT=?, DiaChi=?, Quyen=? WHERE MaNguoiDung=?";
        $stmt_up = $conn->prepare($sql_up);
        $stmt_up->bind_param("ssssii", $tenNguoiDung, $email, $sdt, $diaChi, $quyen, $id);
    }

    if ($stmt_up->execute()) {
        $_SESSION['success'] = "Cập nhật tài khoản người dùng thành công!";
        header("Location: quanlytaikhoan.php");
        exit();
    } else {
        $error = "Lỗi khi cập nhật: " . $conn->error;
    }
}
?>

<?php include 'header_admin.php'; ?>

<div class="admin-content">
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-person-gear me-2"></i> CHỈNH SỬA TÀI KHOẢN #<?= $id ?></h2>
            <p class="text-muted mb-0">Cập nhật thông tin, cấp quyền Quản Trị Viên hoặc Reset mật khẩu</p>
        </div>
        <div>
            <a href="quanlytaikhoan.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Quay lại danh sách</a>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger py-2 px-3 mb-3"><?= $error ?></div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="luxury-box">
                <form action="sua_taikhoan.php?MaNguoiDung=<?= $id ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-warning small fw-bold">Tên đăng nhập (Cố định):</label>
                        <input type="text" class="form-control bg-dark border-secondary text-muted" value="<?= htmlspecialchars($user['TenDangNhap']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-warning small fw-bold">Họ và tên người dùng (*):</label>
                        <input type="text" name="TenNguoiDung" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($user['TenNguoiDung']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-warning small fw-bold">Số điện thoại:</label>
                            <input type="tel" name="SDT" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($user['SDT'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-warning small fw-bold">Email:</label>
                            <input type="email" name="Email" class="form-control bg-dark border-secondary text-white" value="<?= htmlspecialchars($user['Email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-warning small fw-bold">Địa chỉ:</label>
                        <textarea name="DiaChi" class="form-control bg-dark border-secondary text-white" rows="2"><?= htmlspecialchars($user['DiaChi'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-warning small fw-bold">Phân quyền tài khoản:</label>
                        <select name="Quyen" class="form-select bg-dark border-secondary text-white">
                            <option value="0" <?= $user['Quyen'] == 0 ? 'selected' : '' ?>>Khách Hàng (Customer)</option>
                            <option value="1" <?= $user['Quyen'] == 1 ? 'selected' : '' ?>>Quản Trị Viên (Admin)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-warning small fw-bold">Mật khẩu mới (Để trống nếu không đổi):</label>
                        <input type="password" name="MatKhau" class="form-control bg-dark border-secondary text-white" placeholder="Nhập mật khẩu mới nếu muốn reset cho thành viên này...">
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" name="capnhat" class="btn btn-gold flex-fill py-3 text-uppercase">
                            <i class="bi bi-save me-2"></i> LƯU THÔNG TIN
                        </button>
                        <a href="quanlytaikhoan.php" class="btn btn-outline-secondary flex-fill py-3 text-center">Hủy Bỏ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
