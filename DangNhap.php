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
    <title>Đăng Nhập - BIGCLOCK</title>
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
            padding: 20px;
        }

        .auth-container {
            background-color: rgba(18, 18, 18, 0.98);
            border: 1px solid rgba(212, 175, 55, 0.45);
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.95), 0 0 25px rgba(212, 175, 55, 0.25);
            width: 100%;
            max-width: 440px;
        }

        .auth-title {
            color: var(--luxury-gold);
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-align: center;
            margin-bottom: 8px;
            font-size: 1.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .form-label {
            color: #f1f5f9;
            font-size: 0.9rem;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .luxury-input {
            background-color: #1a1a1a !important;
            border: 1px solid #475569 !important;
            color: #ffffff !important;
            padding: 12px 15px;
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
            padding: 13px;
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
                <img src="images/logo.png?v=2" alt="BIGCLOCK" style="height: 60px; margin-bottom: 6px;">
                <h4 style="color: #D4AF37; font-family: 'Cinzel', serif; letter-spacing: 3px; margin: 0; font-weight: 800;">BIGCLOCK</h4>
            </a>
            <h2 class="auth-title mt-3 mb-2">ĐĂNG NHẬP</h2>
            <p class="small mb-2" style="color: #cbd5e1; font-weight: 500;">Chào mừng trở lại với thương hiệu <b style="color: #ffffff;">BIGCLOCK</b></p>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?= $_SESSION['message_type'] ?? 'alert-info' ?> py-2 px-3 mb-3 text-center small">
                <?= $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
        <?php endif; ?>

        <form action="DangNhapXuLy.php" method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-user me-1"></i> Tên đăng nhập</label>
                <input type="text" name="TenDangNhap" class="form-control luxury-input" placeholder="Ví dụ: admin hoặc khachhang" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock me-1"></i> Mật khẩu</label>
                <input type="password" name="MatKhau" class="form-control luxury-input" placeholder="Nhập mật khẩu..." required>
            </div>

            <button type="submit" class="btn btn-gold text-uppercase mt-2">
                <i class="fas fa-sign-in-alt me-2"></i> ĐĂNG NHẬP
            </button>
        </form>

        <div class="text-center mt-4 pt-3" style="border-top: 1px solid #282828;">
            <p class="small mb-2" style="color: #cbd5e1; font-weight: 500;">Chưa có tài khoản thành viên?</p>
            <a href="DangKy.php" style="color: #D4AF37; text-decoration: none; font-weight: 600;">
                <i class="fas fa-user-plus me-1"></i> Đăng ký tài khoản mới ngay
            </a>
            <div class="mt-3">
                <a href="index.php" class="small text-decoration-none" style="color: #cbd5e1;"><i class="fas fa-arrow-left me-1"></i> Quay về trang chủ</a>
            </div>
        </div>

        <div class="p-3 mt-3 rounded" style="background: rgba(212,175,55,0.08); border: 1px dashed rgba(212,175,55,0.3); font-size: 12px; color: #f1f5f9;">
            <b class="gold-text">Tài khoản mẫu:</b><br>
            - Quản trị: <code style="color: #fca5a5; font-weight: 600;">admin</code> / <code style="color: #fca5a5;">123456</code><br>
            - Khách hàng: <code style="color: #93c5fd; font-weight: 600;">khachhang</code> / <code style="color: #93c5fd;">123456</code>
        </div>
    </div>
</body>
</html>
