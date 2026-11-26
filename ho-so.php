<?php
require_once 'connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['MaNguoiDung'])) {
    header("Location: DangNhap.php");
    exit();
}

$id = $_SESSION['MaNguoiDung'];

// Lấy thông tin người dùng
$sql = "SELECT * FROM user WHERE MaNguoiDung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Cập nhật thông tin
if (isset($_POST['update_profile'])) {
    $hoTen = trim($_POST['HoTen']);
    $email = trim($_POST['Email']);
    $sdt = trim($_POST['SDT']);
    $diaChi = trim($_POST['DiaChi']);
    $matKhauMoi = trim($_POST['MatKhauMoi'] ?? '');
    
    if (!empty($matKhauMoi)) {
        $passMD5 = md5($matKhauMoi);
        $sql_up = "UPDATE user SET TenNguoiDung=?, Email=?, SDT=?, DiaChi=?, MatKhau=? WHERE MaNguoiDung=?";
        $stmt_up = $conn->prepare($sql_up);
        $stmt_up->bind_param("sssssi", $hoTen, $email, $sdt, $diaChi, $passMD5, $id);
    } else {
        $sql_up = "UPDATE user SET TenNguoiDung=?, Email=?, SDT=?, DiaChi=? WHERE MaNguoiDung=?";
        $stmt_up = $conn->prepare($sql_up);
        $stmt_up->bind_param("ssssi", $hoTen, $email, $sdt, $diaChi, $id);
    }
    
    if ($stmt_up->execute()) {
        $_SESSION['HoTen'] = $hoTen; 
        $msg = "Cập nhật hồ sơ cá nhân thành công!";
        // Làm mới dữ liệu
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Cá Nhân - BIGCLOCK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root { --luxury-gold: #D4AF37; --luxury-black: #0c0c0c; }
        body { background-color: var(--luxury-black); color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; padding: 40px 15px;}
        
        .profile-card {
            background: #141414;
            border: 1px solid rgba(212, 175, 55, 0.35);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.85);
            padding: 35px;
        }

        .form-control {
            background-color: #1e1e1e !important;
            border: 1px solid #444 !important;
            color: #fff !important;
            padding: 10px 14px;
            border-radius: 6px;
        }

        .form-control:focus {
            border-color: var(--luxury-gold) !important;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.4) !important;
        }

        .btn-gold {
            background-color: var(--luxury-gold);
            color: #000;
            font-weight: 700;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn-gold:hover {
            background-color: #FFD700;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.6);
            color: #000;
        }

        .text-muted, p.text-muted {
            color: #cbd5e1 !important;
        }
        .form-control::placeholder {
            color: #94a3b8 !important;
            opacity: 1 !important;
        }
    </style>
</head>
<body>
    <div class="container" style="max-width: 650px;">
        <div class="mb-3">
            <a href="index.php" class="text-muted text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Trở về cửa hàng BIGCLOCK</a>
        </div>

        <div class="profile-card">
            <div class="text-center mb-4">
                <i class="fas fa-user-circle fa-4x text-warning mb-2"></i>
                <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; color: #D4AF37; margin: 0;">HỒ SƠ TÀI KHOẢN</h3>
                <p class="small text-muted">Thành viên: <b><?= htmlspecialchars($user['TenDangNhap']) ?></b> (<?= $user['Quyen'] == 1 ? 'Quản Trị Viên' : 'Khách Hàng' ?>)</p>
            </div>

            <?php if (isset($msg)): ?>
                <div class="alert alert-success py-2 px-3 mb-3 text-center small">
                    <i class="fas fa-check-circle me-1"></i> <?= $msg ?>
                </div>
            <?php endif; ?>

            <form action="ho-so.php" method="POST">
                <div class="mb-3">
                    <label class="form-label text-warning small fw-bold">Họ và tên:</label>
                    <input type="text" name="HoTen" class="form-control" value="<?= htmlspecialchars($user['TenNguoiDung']) ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-warning small fw-bold">Số điện thoại:</label>
                        <input type="tel" name="SDT" class="form-control" value="<?= htmlspecialchars($user['SDT'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-warning small fw-bold">Email:</label>
                        <input type="email" name="Email" class="form-control" value="<?= htmlspecialchars($user['Email'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-warning small fw-bold">Địa chỉ giao hàng:</label>
                    <textarea name="DiaChi" class="form-control" rows="2"><?= htmlspecialchars($user['DiaChi'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label text-warning small fw-bold">Đổi mật khẩu mới (để trống nếu không đổi):</label>
                    <input type="password" name="MatKhauMoi" class="form-control" placeholder="Nhập mật khẩu mới nếu muốn thay đổi...">
                </div>

                <button type="submit" name="update_profile" class="btn btn-gold w-100 text-uppercase">
                    <i class="fas fa-save me-2"></i> LƯU THAY ĐỔI
                </button>
            </form>
        </div>
    </div>
</body>
</html>
