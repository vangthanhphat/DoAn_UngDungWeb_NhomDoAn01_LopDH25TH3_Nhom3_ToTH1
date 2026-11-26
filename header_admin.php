<?php
require_once 'connect.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['MaNguoiDung']) || !isset($_SESSION['Quyen']) || $_SESSION['Quyen'] != 1) {
    $_SESSION['message'] = "Bạn cần đăng nhập bằng tài khoản Quản Trị Viên (Admin) để truy cập khu vực này!";
    $_SESSION['message_type'] = "alert-danger";
    header("Location: DangNhap.php");
    exit();
}

// Cập nhật họ tên mới nhất từ CSDL vào session nếu có kết nối
if (isset($conn) && isset($_SESSION['MaNguoiDung'])) {
    $uid_ad = intval($_SESSION['MaNguoiDung']);
    $res_ad = $conn->query("SELECT TenNguoiDung FROM user WHERE MaNguoiDung = $uid_ad");
    if ($res_ad && $row_ad = $res_ad->fetch_assoc()) {
        $_SESSION['HoTen'] = $row_ad['TenNguoiDung'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIGCLOCK - Quản Trị Hệ Thống</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --gold: #D4AF37;
            --gold-light: #FFD700;
            --dark-bg: #090909;
            --sidebar-bg: #111111;
            --card-bg: #161616;
            --white: #FFFFFF;
            --sidebar-width: 260px;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--white);
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding-left: var(--sidebar-width); 
            margin: 0;
            min-height: 100vh;
        }

        /* SIDEBAR QUẢN TRỊ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            border-right: 1px solid rgba(212, 175, 55, 0.3);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 22px 18px;
            text-align: center;
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
        }

        .sidebar-brand h2 {
            color: var(--gold);
            font-family: 'Cinzel', serif;
            margin: 0;
            font-weight: 800;
            font-size: 1.45rem;
            letter-spacing: 3px;
        }

        .sidebar-brand p {
            color: #888;
            font-size: 0.75rem;
            margin: 4px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .sidebar-user {
            padding: 16px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(212,175,55,0.05);
        }

        .sidebar-user img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid var(--gold);
            margin-right: 12px;
            object-fit: cover;
        }

        .sidebar-user-info h6 {
            margin: 0;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .sidebar-user-info span {
            color: var(--gold);
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 22px;
            color: #bbb;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu li a i {
            margin-right: 14px;
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
        }

        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            color: #000;
            background-color: var(--gold);
            border-left-color: #fff;
            font-weight: 700;
        }

        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            color: #ff5252;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: 0.2s;
        }

        .sidebar-footer a:hover {
            color: #ff8585;
        }

        /* NỘI DUNG CHÍNH */
        .admin-content {
            padding: 30px;
        }

        .page-header {
            border-bottom: 2px solid rgba(212, 175, 55, 0.3);
            padding-bottom: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title {
            color: var(--gold);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            font-size: 2rem;
            letter-spacing: 2px;
        }

        .luxury-box {
            background-color: var(--card-bg);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6);
            margin-bottom: 30px;
        }

        .luxury-table {
            width: 100%;
            color: #fff;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .luxury-table th {
            background-color: #1e1e1e;
            color: var(--gold);
            border-bottom: 2px solid var(--gold);
            padding: 14px 10px;
            text-align: center;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .luxury-table td {
            padding: 14px 10px;
            border-bottom: 1px solid #282828;
            text-align: center;
            vertical-align: middle;
            color: #f1f5f9;
        }

        .luxury-table tbody tr:hover td {
            background-color: rgba(212, 175, 55, 0.08);
        }

        /* ĐỘ TƯƠNG PHẢN CAO CHO DARK MODE - CHỐNG MỜ CHỮ */
        .text-muted {
            color: #cbd5e1 !important;
        }

        p.text-muted {
            color: #d1d5db !important;
        }

        .small.text-muted {
            color: #cbd5e1 !important;
        }

        /* Tiêu đề KPI card */
        .small.text-uppercase {
            color: #e2e8f0 !important;
            font-weight: 600 !important;
            letter-spacing: 1px !important;
        }

        .luxury-table td.text-muted {
            color: #93c5fd !important;
            font-weight: 600;
        }

        .btn-gold {
            background: var(--gold);
            color: #000;
            font-weight: bold;
            padding: 8px 18px;
            border-radius: 5px;
            border: none;
            transition: 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-gold:hover {
            background: var(--gold-light);
            box-shadow: 0 0 15px rgba(212,175,55,0.4);
            color: #000;
        }
    </style>
</head>
<body>

    <!-- THANH MENU BÊN TRÁI (SIDEBAR) -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <a href="quanly.php" style="text-decoration: none;">
                <img src="images/logo.png?v=2" alt="BIGCLOCK" style="height: 45px; margin-bottom: 6px;">
                <h2>BIGCLOCK</h2>
            </a>
            <p>Admin Management Panel</p>
        </div>

        <div class="sidebar-user">
            <img src="images/user.jpg" alt="Avatar Admin">
            <div class="sidebar-user-info">
                <h6><?= htmlspecialchars($_SESSION['HoTen'] ?? 'Quản Trị Viên') ?></h6>
                <span><i class="bi bi-shield-check"></i> Administrator</span>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="quanly.php" class="<?= basename($_SERVER['PHP_SELF']) == 'quanly.php' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i> Bảng Điều Khiển
                </a>
            </li>
            <li>
                <a href="doanhthu.php" class="<?= basename($_SERVER['PHP_SELF']) == 'doanhthu.php' ? 'active' : '' ?>">
                    <i class="bi bi-graph-up-arrow"></i> Báo Cáo Doanh Thu
                </a>
            </li>
            <li>
                <a href="danhsach.php" class="<?= basename($_SERVER['PHP_SELF']) == 'danhsach.php' ? 'active' : '' ?>">
                    <i class="bi bi-watch"></i> Quản Lý Đồng Hồ
                </a>
            </li>
            <li>
                <a href="them_sanpham.php" class="<?= basename($_SERVER['PHP_SELF']) == 'them_sanpham.php' ? 'active' : '' ?>">
                    <i class="bi bi-plus-circle"></i> Thêm Đồng Hồ Mới
                </a>
            </li>
            <li>
                <a href="danhsachhoadon.php" class="<?= in_array(basename($_SERVER['PHP_SELF']), ['danhsachhoadon.php', 'chitiethoadon.php']) ? 'active' : '' ?>">
                    <i class="bi bi-receipt"></i> Quản Lý Đơn Hàng
                </a>
            </li>
            <li>
                <a href="quanlytaikhoan.php" class="<?= in_array(basename($_SERVER['PHP_SELF']), ['quanlytaikhoan.php', 'sua_taikhoan.php']) ? 'active' : '' ?>">
                    <i class="bi bi-people"></i> Quản Lý Người Dùng
                </a>
            </li>
            <li>
                <a href="index.php" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> Xem Website Cửa Hàng
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <a href="DangXuat.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất khỏi trang Quản trị?');">
                <i class="bi bi-box-arrow-left me-2 fs-5"></i> Đăng Xuất An Toàn
            </a>
        </div>
    </div>
