<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản - BIGCLOCK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --luxury-gold: #D4AF37;
            --luxury-black: #0c0c0c;
        }

        body {
            background-color: var(--luxury-black);
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-image: radial-gradient(circle, #222 0%, #080808 100%);
            padding: 30px 15px;
        }

        .auth-container {
            background-color: rgba(18, 18, 18, 0.98);
            border: 1px solid rgba(212, 175, 55, 0.45);
            padding: 35px 40px;
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.95), 0 0 25px rgba(212, 175, 55, 0.25);
            width: 100%;
            max-width: 480px;
        }

        .auth-title {
            color: var(--luxury-gold);
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-align: center;
            margin-bottom: 8px;
            font-size: 1.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .form-label {
            color: #f1f5f9;
            font-size: 0.88rem;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .luxury-input {
            background-color: #1a1a1a !important;
            border: 1px solid #475569 !important;
            color: #ffffff !important;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .luxury-input::placeholder {
            color: #cbd5e1 !important;
            opacity: 1 !important;
        }

        .luxury-input::-webkit-input-placeholder {
            color: #cbd5e1 !important;
            opacity: 1 !important;
        }

        .luxury-input:focus {
            border-color: var(--luxury-gold) !important;
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.45) !important;
            background-color: #202020 !important;
            color: #ffffff !important;
        }

        .text-muted, p.text-muted, span.text-muted, div.text-muted {
            color: #cbd5e1 !important;
            font-weight: 500 !important;
        }

        .btn-gold {
            background-color: var(--luxury-gold);
            color: #000;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 12px;
            border-radius: 8px;
            border: none;
            width: 100%;
            transition: 0.3s;
        }

        .btn-gold:hover {
            background-color: #FFD700;
            box-shadow: 0 0 18px rgba(212, 175, 55, 0.7);
            color: #000;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="text-center mb-3">
            <a href="index.php" style="text-decoration: none;">
                <img src="images/logo.png?v=2" alt="BIGCLOCK" style="height: 55px; margin-bottom: 6px;">
                <h4 style="color: #D4AF37; font-family: 'Cinzel', serif; letter-spacing: 3px; margin: 0; font-weight: 800;">BIGCLOCK</h4>
            </a>
            <h2 class="auth-title mt-2 mb-1">ĐĂNG KÝ TÀI KHOẢN</h2>
            <p class="small text-muted mb-0">Trở thành thành viên của <b>BIGCLOCK</b></p>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['message_type'] ?? 'alert-info' ?> py-2 px-3 mb-3 text-center small">
                <?= $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
        <?php endif; ?>

        <form action="DangKyXuLy.php" method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-id-card me-1"></i> Họ và tên của bạn (*)</label>
                <input type="text" name="TenNguoiDung" class="form-control luxury-input" placeholder="Ví dụ: Nguyễn Văn Khách" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-user me-1"></i> Tên đăng nhập mong muốn (*)</label>
                <input type="text" name="TenDangNhap" class="form-control luxury-input" placeholder="Ví dụ: khachhang123" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-phone me-1"></i> Số điện thoại</label>
                    <input type="tel" name="SDT" class="form-control luxury-input" placeholder="Số điện thoại...">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-envelope me-1"></i> Email</label>
                    <input type="email" name="Email" class="form-control luxury-input" placeholder="Email...">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-map-marker-alt me-1"></i> Địa chỉ giao hàng mặc định</label>
                <input type="text" name="DiaChi" class="form-control luxury-input" placeholder="Số nhà, đường, quận/huyện, tỉnh/thành...">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-lock me-1"></i> Mật khẩu (*)</label>
                    <input type="password" name="MatKhau" class="form-control luxury-input" placeholder="Tối thiểu 6 ký tự" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fas fa-check me-1"></i> Xác nhận mật khẩu (*)</label>
                    <input type="password" name="XacNhanMatKhau" class="form-control luxury-input" placeholder="Nhập lại mật khẩu" required>
                </div>
            </div>

            <button type="submit" class="btn btn-gold text-uppercase mt-2">
                <i class="fas fa-user-plus me-2"></i> HOÀN TẤT ĐĂNG KÝ
            </button>
        </form>

        <div class="text-center mt-3 pt-3" style="border-top: 1px solid #282828;">
            <p class="small mb-1" style="color: #cbd5e1; font-weight: 500;">Đã có tài khoản thành viên?</p>
            <a href="DangNhap.php" style="color: #D4AF37; text-decoration: none; font-weight: 600;">
                <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập ngay
            </a>
            <div class="mt-2">
                <a href="index.php" class="small text-secondary text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Quay về trang chủ</a>
            </div>
        </div>
    </div>
</body>
</html>
