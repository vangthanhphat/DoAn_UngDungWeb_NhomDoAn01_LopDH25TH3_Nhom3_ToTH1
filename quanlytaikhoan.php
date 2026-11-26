<?php
require_once 'connect.php';

if (!isset($_SESSION['MaNguoiDung']) || !isset($_SESSION['Quyen']) || $_SESSION['Quyen'] != 1) {
    header("Location: DangNhap.php");
    exit();
}

// Xử lý xóa tài khoản
if (isset($_GET['xoa'])) {
    $id = intval($_GET['xoa']);

    if ($id == $_SESSION['MaNguoiDung']) {
        $_SESSION['error'] = "Không thể tự xóa tài khoản của chính bạn!";
    } else {
        // Kiểm tra xem người dùng đã từng mua hàng chưa
        $check_fk = $conn->prepare("SELECT MaHoaDon FROM hoadon WHERE MaNguoiDung = ?");
        $check_fk->bind_param("i", $id);
        $check_fk->execute();
        $check_fk->store_result();

        if ($check_fk->num_rows > 0) {
            $_SESSION['error'] = "KHÔNG THỂ XÓA! Người dùng này đã có đơn hàng trong hệ thống.";
        } else {
            $stmt = $conn->prepare("DELETE FROM user WHERE MaNguoiDung = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Xóa tài khoản thành công!";
            } else {
                $_SESSION['error'] = "Lỗi khi xóa: " . $conn->error;
            }
        }
    }
    header("Location: quanlytaikhoan.php");
    exit();
}

$sql = "SELECT * FROM user ORDER BY Quyen DESC, MaNguoiDung ASC";
$result = $conn->query($sql);
?>

<?php include 'header_admin.php'; ?>

<div class="admin-content">
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-people me-2"></i> QUẢN LÝ NGƯỜI DÙNG</h2>
            <p class="text-muted mb-0">Quản lý danh sách thành viên, phân quyền Quản Trị Viên và Khách Hàng</p>
        </div>
    </div>

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

    <div class="luxury-box">
        <div class="table-responsive">
            <table class="luxury-table">
                <thead>
                    <tr>
                        <th width="70">ID</th>
                        <th>Họ và tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Quyền hạn</th>
                        <th width="160">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $row['MaNguoiDung'] ?></td>
                                <td style="text-align: left;"><b class="text-white"><?= htmlspecialchars($row['TenNguoiDung']) ?></b></td>
                                <td><code><?= htmlspecialchars($row['TenDangNhap']) ?></code></td>
                                <td><?= htmlspecialchars($row['SDT'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['Email'] ?? '-') ?></td>
                                <td>
                                    <?php if ($row['Quyen'] == 1): ?>
                                        <span class="badge bg-warning text-dark px-3 py-1 fw-bold"><i class="bi bi-shield-lock me-1"></i> Quản Trị Viên</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary px-3 py-1">Khách Hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="sua_taikhoan.php?MaNguoiDung=<?= $row['MaNguoiDung'] ?>" class="btn btn-sm btn-outline-warning me-1" title="Sửa quyền / Reset mật khẩu">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
                                    <?php if ($row['MaNguoiDung'] != $_SESSION['MaNguoiDung']): ?>
                                        <a href="quanlytaikhoan.php?xoa=<?= $row['MaNguoiDung'] ?>" class="btn btn-sm btn-outline-danger" title="Xóa tài khoản" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                                            <i class="bi bi-trash"></i> Xóa
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
