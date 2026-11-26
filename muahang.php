<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'connect.php'; 

// 1. Kiểm tra đăng nhập (Bắt buộc để đặt hàng)
if (!isset($_SESSION['MaNguoiDung'])) {
    echo "<script>alert('Vui lòng đăng nhập để tiến hành đặt hàng!'); window.location='DangNhap.php';</script>";
    exit();
}
$maNguoiDung = $_SESSION['MaNguoiDung'];

// Lấy thông tin người dùng từ CSDL để điền sẵn vào form
$sql_user = "SELECT * FROM user WHERE MaNguoiDung = $maNguoiDung";
$res_user = $conn->query($sql_user);
$user_info = $res_user ? $res_user->fetch_assoc() : null;

// 2. Chức năng "Mua ngay" từ nút bấm ở trang chủ
if (isset($_GET['MaDongHo'])) {
    $id = intval($_GET['MaDongHo']);

    // Kiểm tra tồn kho
    $sql_check = "SELECT SoLuong FROM dongho WHERE MaDongHo = $id";
    $res_chk = $conn->query($sql_check);
    if ($res_chk && $res_chk->num_rows > 0) {
        $row_check = $res_chk->fetch_assoc();
        if ($row_check['SoLuong'] <= 0) {
            echo "<script>alert('Sản phẩm đã hết hàng!'); window.location='index.php';</script>";
            exit();
        }

        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = array();
        if (isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] += 1;
        else $_SESSION['cart'][$id] = 1;
    }
    header('Location: muahang.php');
    exit();
}

// 3. Tính tổng tiền giỏ hàng
$tong_tien = 0;
$ds_sanpham = array();
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $id => $soluong) {
        $id = intval($id);
        $sql = "SELECT * FROM dongho WHERE MaDongHo = $id";
        $res = $conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $row['cart_qty'] = $soluong;
            $row['subtotal'] = $row['DonGia'] * $soluong;
            $tong_tien += $row['subtotal'];
            $ds_sanpham[] = $row;
        }
    }
} else {
    echo "<script>alert('Giỏ hàng của bạn đang trống!'); window.location='index.php';</script>";
    exit();
}

// 4. XỬ LÝ KHI BẤM NÚT "XÁC NHẬN ĐẶT HÀNG"
if (isset($_POST['btnDatHang'])) {
    $diachi = trim($_POST['diachi']);
    $sdt = trim($_POST['sdt']);
    $ngaylap = date('Y-m-d H:i:s');
    $qr = 'qrcode.jpg';

    if (empty($diachi) || empty($sdt)) {
        $error = "Vui lòng nhập đầy đủ địa chỉ và số điện thoại giao hàng!";
    } else {
        // Kiểm tra tồn kho lần cuối
        $du_kho = true;
        foreach ($_SESSION['cart'] as $id => $soluong) {
            $chk = $conn->query("SELECT TenDongHo, SoLuong FROM dongho WHERE MaDongHo = $id")->fetch_assoc();
            if ($chk['SoLuong'] < $soluong) {
                $du_kho = false;
                $error = "Mẫu " . $chk['TenDongHo'] . " chỉ còn " . $chk['SoLuong'] . " chiếc trong kho. Vui lòng cập nhật lại số lượng!";
                break;
            }
        }

        if ($du_kho) {
            // Thêm vào bảng hoadon
            $sql_hd = "INSERT INTO hoadon (MaNguoiDung, DiaChi, SDT_GiaoHang, NgayLap, TongTien, HinhQR, TrangThaiDonHang) 
                       VALUES (?, ?, ?, ?, ?, ?, 0)";
            $stmt_hd = $conn->prepare($sql_hd);
            $stmt_hd->bind_param("isssds", $maNguoiDung, $diachi, $sdt, $ngaylap, $tong_tien, $qr);
            
            if ($stmt_hd->execute()) {
                $maHoaDon = $conn->insert_id;

                // Thêm vào chi tiết hóa đơn (chưa trừ kho, chưa xóa session)
                $sql_ct = "INSERT INTO chitiethoadon (MaHoaDon, MaDongHo, SoLuong, DonGia) VALUES (?, ?, ?, ?)";
                $stmt_ct = $conn->prepare($sql_ct);

                foreach ($_SESSION['cart'] as $id => $soluong) {
                    $dg = $conn->query("SELECT DonGia FROM dongho WHERE MaDongHo = $id")->fetch_assoc()['DonGia'];
                    $stmt_ct->bind_param("iiid", $maHoaDon, $id, $soluong, $dg);
                    $stmt_ct->execute();
                }

                // Chuyển tới trang quét mã VietQR để người mua thanh toán
                header("Location: thanhtoan_qr.php?ma_hoadon=" . $maHoaDon);
                exit();
            } else {
                $error = "Có lỗi xảy ra khi lưu đơn hàng. Vui lòng thử lại!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xác Nhận Đặt Hàng - BIGCLOCK</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --luxury-gold: #D4AF37;
            --dark-bg: #080808;
            --card-bg: #141414;
        }
        body {
            background-color: var(--dark-bg);
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 40px 0;
            min-height: 100vh;
        }
        .checkout-box {
            background-color: var(--card-bg);
            border: 1px solid rgba(212, 175, 55, 0.35);
            border-radius: 14px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.85);
        }
        .form-control, .form-select {
            background-color: #1e1e1e;
            border: 1px solid #444;
            color: #fff;
            padding: 12px 15px;
            border-radius: 6px;
        }
        .form-control:focus, .form-select:focus {
            background-color: #252525;
            border-color: var(--luxury-gold);
            color: #fff;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.4);
        }
        .btn-gold {
            background-color: var(--luxury-gold);
            color: #000;
            font-weight: 700;
            border: none;
            padding: 14px 25px;
            border-radius: 6px;
            transition: 0.3s;
        }
        .btn-gold:hover {
            background-color: #FFD700;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.7);
            color: #000;
        }

        /* ĐỘ TƯƠNG PHẢN CAO CHO DARK MODE - CHỐNG MỜ CHỮ */
        .text-muted, p.text-muted, span.text-muted, label.text-muted, small.text-muted, div.text-muted {
            color: #cbd5e1 !important;
        }
        .text-secondary {
            color: #cbd5e1 !important;
        }
        .form-label {
            color: #f1f5f9 !important;
            font-weight: 600 !important;
            font-size: 0.92rem !important;
            margin-bottom: 6px !important;
        }
        .form-control::placeholder, textarea::placeholder {
            color: #94a3b8 !important;
            opacity: 1 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="checkout-box">
                    <div class="text-center mb-4">
                        <a href="index.php" style="text-decoration: none;">
                            <img src="images/logo.png?v=2" alt="BIGCLOCK" style="height: 55px; margin-bottom: 8px;">
                            <h2 style="font-family: 'Cinzel', serif; color: #D4AF37; margin: 0; font-weight: 800; letter-spacing: 3px;">BIGCLOCK</h2>
                        </a>
                        <p class="small text-muted text-uppercase" style="letter-spacing: 2px;">Thanh Toán &amp; Xác Nhận Giao Hàng</p>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger py-2 px-3 mb-4">
                            <i class="fas fa-exclamation-circle me-1"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form action="muahang.php" method="POST">
                        <div class="row g-4">
                            <!-- Cột trái: Thông tin nhận hàng -->
                            <div class="col-lg-6">
                                <h5 class="text-warning mb-3 pb-2" style="border-bottom: 1px solid rgba(212,175,55,0.3); font-family: 'Plus Jakarta Sans', sans-serif;">
                                    <i class="fas fa-user-shield me-2"></i> 1. Thông Tin Nhận Hàng
                                </h5>

                                <div class="mb-3">
                                    <label class="form-label small">Họ và tên người nhận:</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user_info['TenNguoiDung'] ?? '') ?>" readonly style="background: #1a1a1a; color: #e2e8f0; border: 1px solid #444;">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small">Số điện thoại liên hệ (*):</label>
                                    <input type="tel" name="sdt" class="form-control" value="<?= htmlspecialchars($user_info['SDT'] ?? '') ?>" placeholder="Nhập số điện thoại nhận hàng..." required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small">Địa chỉ giao hàng (*):</label>
                                    <textarea name="diachi" class="form-control" rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..." required><?= htmlspecialchars($user_info['DiaChi'] ?? '') ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small">Phương thức thanh toán:</label>
                                    <select class="form-select">
                                        <option value="vietqr">Chuyển khoản VietQR Instant (Khuyên dùng - Nhận QR ngay)</option>
                                        <option value="cod">Thanh toán khi nhận hàng (COD kiểm tra đồng hồ trước khi thanh toán)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Cột phải: Tóm tắt đơn hàng -->
                            <div class="col-lg-6">
                                <h5 class="text-warning mb-3 pb-2" style="border-bottom: 1px solid rgba(212,175,55,0.3); font-family: 'Plus Jakarta Sans', sans-serif;">
                                    <i class="fas fa-receipt me-2"></i> 2. Đơn Hàng Của Bạn
                                </h5>

                                <div class="p-3 mb-3" style="background: #0d0d0d; border-radius: 8px; border: 1px solid #222; max-height: 250px; overflow-y: auto;">
                                    <?php foreach ($ds_sanpham as $item): 
                                        $hinh = str_replace('../', '', $item['HinhAnh'] ?? 'images/no-image.jpg');
                                    ?>
                                        <div class="d-flex align-items-center mb-3 pb-2" style="border-bottom: 1px solid #222;">
                                            <img src="<?= htmlspecialchars($hinh) ?>" style="width: 50px; height: 50px; object-fit: contain; background: #000; border-radius: 4px; margin-right: 12px; border: 1px solid #333;">
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-white small"><?= htmlspecialchars($item['TenDongHo']) ?></div>
                                                <div class="small" style="color: #cbd5e1;"><?= number_format($item['DonGia'], 0, ',', '.') ?> VNĐ x <?= $item['cart_qty'] ?> chiếc</div>
                                            </div>
                                            <div class="text-warning fw-bold small">
                                                <?= number_format($item['subtotal'], 0, ',', '.') ?> VNĐ
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="p-3 mb-4" style="background: #181818; border-radius: 8px; border: 1px solid rgba(212,175,55,0.2);">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span style="color: #cbd5e1;">Cộng tiền hàng:</span>
                                        <span style="color: #ffffff; font-weight: 600;"><?= number_format($tong_tien, 0, ',', '.') ?> VNĐ</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span style="color: #cbd5e1;">Phí bảo hiểm &amp; Vận chuyển Hỏa Tốc:</span>
                                        <span class="text-success fw-bold">MIỄN PHÍ</span>
                                    </div>
                                    <hr style="border-color: #333;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <b style="color: #ffffff; font-size: 1.15rem; letter-spacing: 0.5px;">TỔNG THANH TOÁN:</b>
                                        <b style="color: #FFD700; font-size: 1.8rem; font-family: 'Cinzel', serif; letter-spacing: 1px;"><?= number_format($tong_tien, 0, ',', '.') ?> VNĐ</b>
                                    </div>
                                </div>

                                <button type="submit" name="btnDatHang" class="btn btn-gold w-100 text-uppercase py-3">
                                    <i class="fas fa-check-circle me-2"></i> XÁC NHẬN ĐẶT HÀNG NGAY
                                </button>
                                <div class="text-center mt-3">
                                    <a href="giohang.php" class="text-muted small text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Quay lại giỏ hàng</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
