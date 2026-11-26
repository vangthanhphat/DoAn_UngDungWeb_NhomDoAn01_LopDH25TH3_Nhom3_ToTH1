<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$siteTitle = "BIGCLOCK";
$slogan = "Tinh hoa thời gian - Đẳng cấp vĩnh cửu";
$currentYear = date("Y");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $siteTitle; ?> - Đồng hồ cao cấp Thụy Sĩ & Nhật Bản</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="css/index.css" rel="stylesheet">
    <style>
        /* Navbar Dark Luxury */
        .navbar {
            min-height: auto !important; 
            padding-top: 10px !important; 
            padding-bottom: 0px !important;
            transition: all 0.3s ease;
            background-color: rgba(10, 10, 10, 0.95) !important;
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .navbar.scrolled {
            background-color: rgba(0, 0, 0, 0.98) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
        }
        
        .navbar-brand h2 {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            font-size: 1.6rem;
            letter-spacing: 2px;
            color: #D4AF37;
            margin: 0;
        }
        
        .navbar-nav .nav-link {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 500;
            font-size: 0.85rem;
            letter-spacing: 1px;
            color: #e5e5e5;
            padding: 0.5rem 0.9rem;
            text-transform: uppercase;
            position: relative;
            transition: 0.3s;
        }
        
        .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
            color: #D4AF37 !important;
        }
        
        .dropdown-menu {
            background-color: #161616;
            border: 1px solid rgba(212, 175, 55, 0.4);
            box-shadow: 0 10px 30px rgba(0,0,0,0.8);
        }
        
        .dropdown-item {
            color: #e5e5e5;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            padding: 10px 20px;
            transition: 0.3s;
        }
        
        .dropdown-item:hover {
            background-color: rgba(212, 175, 55, 0.2);
            color: #D4AF37;
        }
        
        .dropdown-divider {
            border-top: 1px solid rgba(212, 175, 55, 0.3);
        }
        
        .news-ticker {
            font-size: 13px; 
            color: #D4AF37;
            border-top: 1px solid rgba(212, 175, 55, 0.15); 
            width: 100%;
            height: 28px; 
            line-height: 28px; 
            margin-top: 4px; 
            margin-bottom: 0px !important;
            overflow: hidden;
            background: rgba(0,0,0,0.4);
        }

        .news-ticker marquee {
            margin: 0;
            padding: 0;
            height: 28px;
            line-height: 28px;
        }

        :root {
            --luxury-black: #0d0d0d;
            --luxury-gold: #D4AF37;
            --luxury-white: #FFFFFF;
        }

        body {
            background-color: var(--luxury-black);
            color: var(--luxury-white);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .luxury-product-card {
            background-color: #141414;
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .luxury-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.35);
            border-color: var(--luxury-gold);
        }

        .luxury-product-img {
            height: 170px; 
            width: 100%;
            object-fit: contain;
            transition: transform 0.4s ease;
        }

        .luxury-product-title {
            color: var(--luxury-gold);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .gold-text {
            color: var(--luxury-gold);
        }

        .btn-gold {
            background-color: var(--luxury-gold);
            color: #000;
            font-weight: 700;
            letter-spacing: 1px;
            border: none;
            transition: 0.3s;
        }

        .btn-gold:hover {
            background-color: #FFD700;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.6);
            color: #000;
        }

        .btn-outline-gold {
            border: 1px solid var(--luxury-gold);
            color: var(--luxury-gold);
            font-weight: 600;
            background: transparent;
            transition: 0.3s;
        }

        .btn-outline-gold:hover {
            background-color: var(--luxury-gold);
            color: #000;
        }

        .newsletter-input {
            background-color: rgba(255, 255, 255, 0.08) !important; 
            border: 1px solid rgba(212, 175, 55, 0.5) !important; 
            color: #ffffff !important;
            padding: 12px 15px !important; 
            border-radius: 4px !important; 
        }
        .newsletter-input::placeholder {
            color: #94a3b8 !important;
            opacity: 1 !important;
        }
        .newsletter-input::-webkit-input-placeholder {
            color: #94a3b8 !important;
            opacity: 1 !important;
        }
        .newsletter-input::-moz-placeholder {
            color: #94a3b8 !important;
            opacity: 1 !important;
        }
        .newsletter-input:-ms-input-placeholder {
            color: #94a3b8 !important;
            opacity: 1 !important;
        }
        .newsletter-input:focus {
            border-color: var(--luxury-gold) !important; 
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.5) !important; 
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container d-flex align-items-center">
            
            <a class="navbar-brand d-flex align-items-center" href="index.php" style="margin-right: 25px; text-decoration: none;">
                <img src="images/logo.png?v=3" alt="BIGCLOCK Logo" style="height: 44px; width: 44px; object-fit: contain; margin-right: 12px; filter: drop-shadow(0 2px 8px rgba(212,175,55,0.35));">
                <div class="d-flex flex-column justify-content-center text-start">
                    <span style="font-family: 'Cinzel', serif; font-size: 1.45rem; letter-spacing: 3px; color: #D4AF37; margin: 0; line-height: 1.1; font-weight: 800;">BIGCLOCK</span>
                    <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.68rem; letter-spacing: 2.5px; color: #cbd5e1; text-transform: uppercase; font-weight: 600; margin-top: 2px;">SWISS LUXURY</span>
                </div>
            </a>
            
            <div class="d-flex flex-column flex-grow-1">
                
                <div class="d-flex justify-content-end justify-content-lg-between align-items-center w-100">
                    
                    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item"><a class="nav-link active" href="index.php#home">Trang chủ</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php#collections">Bộ sưu tập</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php#products">Sản phẩm</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php#about">Về chúng tôi</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php#news">Tin tức</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php#testimonials">Đánh giá</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php#contact">Liên hệ</a></li>
                        </ul>
                        
                        <div class="d-flex ms-lg-3 align-items-center mt-3 mt-lg-0">
                            <!-- Giỏ hàng icon -->
                            <a href="giohang.php" class="nav-link me-4" style="position: relative; color: #D4AF37; font-size: 1.25rem;">
                                <i class="fas fa-shopping-cart"></i>
                                <span id="cart-count" style="position: absolute; top: -2px; right: -10px; background-color: #d9534f; color: white; border-radius: 50%; padding: 2px 7px; font-size: 11px; font-weight: bold; border: 1px solid #000;">
                                    <?php 
                                        $tong_so_luong = 0;
                                        if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])){
                                            foreach($_SESSION['cart'] as $id => $soluong){
                                                $tong_so_luong += $soluong;
                                            }
                                        }
                                        echo $tong_so_luong; 
                                    ?>
                                </span>
                            </a>

                            <!-- Tài khoản dropdown -->
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" style="color: #D4AF37; font-weight: 600; font-size: 0.9rem;">
                                    <?php if(isset($_SESSION['HoTen'])): ?>
                                        <i class="fas fa-user-circle me-2 fs-5"></i> <?php echo htmlspecialchars($_SESSION['HoTen']); ?>
                                    <?php else: ?>
                                        <i class="fas fa-user me-2"></i> Tài khoản
                                    <?php endif; ?>
                                </a>
                                
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <?php if(isset($_SESSION['HoTen'])): ?>
                                        
                                        <?php if(isset($_SESSION['Quyen']) && $_SESSION['Quyen'] == 1): ?>
                                            <li><a class="dropdown-item" href="doanhthu.php"><i class="fas fa-chart-line text-warning me-2"></i> Báo cáo doanh thu</a></li>
                                            <li><a class="dropdown-item" href="danhsach.php"><i class="fas fa-watch me-2"></i> Quản lý đồng hồ</a></li>
                                            <li><a class="dropdown-item" href="danhsachhoadon.php"><i class="fas fa-file-invoice-dollar me-2"></i> Quản lý đơn hàng</a></li>
                                            <li><a class="dropdown-item" href="quanlytaikhoan.php"><i class="fas fa-users-cog me-2"></i> Quản lý người dùng</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                        <?php endif; ?>
                                        
                                        <li><a class="dropdown-item" href="ho-so.php"><i class="fas fa-user-edit me-2"></i> Hồ sơ của tôi</a></li>
                                        <li><a class="dropdown-item" href="donhang_khach.php"><i class="fas fa-box-open me-2"></i> Đơn hàng của tôi</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="DangXuat.php"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                                        
                                    <?php else: ?>
                                        <li><a class="dropdown-item" href="DangNhap.php"><i class="fas fa-sign-in-alt me-2 text-warning"></i> Đăng nhập</a></li>
                                        <li><a class="dropdown-item" href="DangKy.php"><i class="fas fa-user-plus me-2 text-info"></i> Đăng ký thành viên</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dải chữ chạy tin tức Khuyến mãi -->
                <div class="news-ticker d-none d-lg-block">
                    <marquee behavior="scroll" direction="left" scrollamount="4">
                        <span class="me-5"><i class="fas fa-crown text-warning me-1"></i> <b>BIGCLOCK</b> - Tưng bừng khai trương showroom mới, tặng gói bảo dưỡng Master Chronometer trọn đời!</span>
                        <span class="me-5"><i class="fas fa-fire text-danger me-1"></i> Bộ sưu tập BigClock Tourbillon giới hạn 50 chiếc toàn cầu vừa cập bến.</span>
                        <span><i class="fas fa-shield-alt text-success me-1"></i> Cam kết 100% chính hãng Thụy Sĩ &amp; Nhật Bản - Bảo hành quốc tế 5 năm.</span>
                    </marquee>
                </div>
                
            </div>
        </div>
    </nav>
    
    <!-- Script xử lý thêm vào giỏ hàng AJAX mượt mà -->
    <script>
    function themVaoGioNgam(event, form) {
        event.preventDefault(); 
        var formData = new FormData(form);
        fetch('themgiohang.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            let res = data.trim(); 
            if (res === 'HEZHANG') {
                alert('Rất tiếc! Mẫu đồng hồ này tạm thời đã hết hàng trong kho.');
            } else if (res !== '' && !isNaN(res)) {
                let badge = document.getElementById('cart-count');
                if (badge) badge.innerText = res;
                alert('Thành công! Đã thêm đồng hồ vào giỏ hàng.');
            } else {
                window.location.reload(); 
            }
        })
        .catch(error => {
            window.location.reload(); 
        });
        return false;
    }
    </script>
