<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'connect.php'; 

// Xử lý cập nhật số lượng nếu có POST
if (isset($_POST['update_cart'])) {
    if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $id => $qty) {
            $id = intval($id);
            $qty = intval($qty);
            if ($qty > 0) {
                // Kiểm tra tồn kho
                $chk = $conn->query("SELECT SoLuong FROM dongho WHERE MaDongHo = $id")->fetch_assoc();
                if ($chk && $qty <= $chk['SoLuong']) {
                    $_SESSION['cart'][$id] = $qty;
                } else {
                    $_SESSION['cart'][$id] = $chk['SoLuong'];
                    $_SESSION['cart_msg'] = "Một số sản phẩm đã được giới hạn theo số lượng tồn kho thực tế.";
                }
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
    }
    header("Location: giohang.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giỏ Hàng Của Bạn - BIGCLOCK</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --luxury-black: #0d0d0d;
            --luxury-gold: #D4AF37;
            --luxury-white: #FFFFFF;
            --dark-bg: #050505;
        }
        body {
            background-color: var(--dark-bg);
            color: var(--luxury-white);
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 40px 0;
            min-height: 100vh;
        }
        .cart-wrapper {
            background-color: #121212;
            border: 1px solid rgba(212, 175, 55, 0.35);
            border-radius: 14px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.85);
            margin-bottom: 30px;
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 25px;
        }
        .brand-logo h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--luxury-gold);
            letter-spacing: 2px;
            font-weight: bold;
        }
        h2.title {
            color: var(--luxury-white);
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.4rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 1px dashed rgba(212, 175, 55, 0.3);
            padding-bottom: 15px;
        }
        .table {
            color: #fff !important;
            vertical-align: middle;
            margin-bottom: 0;
        }
        .table th {
            color: var(--luxury-gold) !important;
            border-bottom: 2px solid var(--luxury-gold) !important;
            text-transform: uppercase;
            font-size: 0.85rem;
            text-align: center;
            padding: 15px 10px;
            background-color: #1a1a1a !important;
        }
        .table td {
            background-color: transparent !important;
            border-bottom: 1px solid #2a2a2a;
            padding: 15px 10px;
            color: #fff !important;
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
        .btn-outline-gold {
            border: 1px solid var(--luxury-gold);
            color: var(--luxury-gold);
            background: transparent;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 6px;
            transition: 0.3s;
        }
        .btn-outline-gold:hover {
            background-color: var(--luxury-gold);
            color: #000;
        }
        
        /* TĂNG CƯỜNG ĐỘ TƯƠNG PHẢN - CHỐNG MỜ CHỮ TRÊN NỀN ĐEN */
        .text-muted, p.text-muted, span.text-muted, label.text-muted, small.text-muted, div.text-muted {
            color: #cbd5e1 !important;
        }
        .text-secondary {
            color: #cbd5e1 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cart-wrapper">
            <div class="brand-logo">
                <a href="index.php" style="text-decoration: none;">
                    <img src="images/logo.png?v=2" alt="BIGCLOCK" style="height: 60px; margin-bottom: 10px;">
                    <h1 style="font-family: 'Cinzel', serif; letter-spacing: 3px; font-weight: 800; color: #D4AF37;">BIGCLOCK</h1>
                </a>
                <p class="small text-uppercase" style="letter-spacing: 3px; color: #cbd5e1; font-weight: 600;">Swiss &amp; Japanese Luxury Timepiece</p>
            </div>
            
            <h2 class="title"><i class="fas fa-shopping-bag me-2"></i> Giỏ Hàng Của Bạn</h2>

            <?php if(isset($_SESSION['cart_msg'])): ?>
                <div class="alert alert-warning text-dark py-2 px-3 mb-3">
                    <i class="fas fa-exclamation-triangle me-1"></i> <?= $_SESSION['cart_msg']; unset($_SESSION['cart_msg']); ?>
                </div>
            <?php endif; ?>

            <?php
            $has_items = false;
            $tong_tien = 0;
            if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                $has_items = true;
            }
            ?>

            <?php if ($has_items): ?>
                <form action="giohang.php" method="POST">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Hình ảnh</th>
                                    <th style="text-align: left;">Tên đồng hồ</th>
                                    <th>Đơn giá</th>
                                    <th width="120">Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $stt = 1;
                                foreach ($_SESSION['cart'] as $id => $soluong):
                                    $id = intval($id);
                                    $sql = "SELECT * FROM dongho WHERE MaDongHo = $id";
                                    $res = $conn->query($sql);
                                    if ($res && $res->num_rows > 0):
                                        $sp = $res->fetch_assoc();
                                        $hinh = str_replace('../', '', $sp['HinhAnh'] ?? 'images/no-image.jpg');
                                        $thanh_tien = $sp['DonGia'] * $soluong;
                                        $tong_tien += $thanh_tien;
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $stt++ ?></td>
                                        <td class="text-center">
                                            <img src="<?= htmlspecialchars($hinh) ?>" alt="" style="width: 65px; height: 65px; object-fit: contain; background: #000; border: 1px solid #333; border-radius: 6px;">
                                        </td>
                                        <td style="text-align: left;">
                                            <b class="text-white" style="font-size: 1.05rem;"><?= htmlspecialchars($sp['TenDongHo']) ?></b>
                                            <div class="small" style="color: #cbd5e1; margin-top: 4px;">Mã SP: <b class="text-white">#<?= $sp['MaDongHo'] ?></b> | Còn: <b style="color: #4ade80;"><?= $sp['SoLuong'] ?></b> chiếc</div>
                                        </td>
                                        <td class="text-center text-warning fw-bold">
                                            <?= number_format($sp['DonGia'], 0, ',', '.') ?> VNĐ
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="quantities[<?= $id ?>]" value="<?= $soluong ?>" min="1" max="<?= $sp['SoLuong'] ?>" class="form-control text-center mx-auto" style="width: 80px; background: #222; color: #D4AF37; border: 1px solid #555; font-weight: bold;">
                                        </td>
                                        <td class="text-center text-warning fw-bold" style="font-size: 1.1rem;">
                                            <?= number_format($thanh_tien, 0, ',', '.') ?> VNĐ
                                        </td>
                                        <td class="text-center">
                                            <a href="xoagiohang.php?id=<?= $id ?>" class="btn btn-sm btn-outline-danger" title="Xóa sản phẩm này" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row align-items-center mt-4 pt-3" style="border-top: 1px solid #333;">
                        <div class="col-md-6 mb-3 mb-md-0 d-flex gap-2 flex-wrap">
                            <a href="index.php#products" class="btn btn-outline-gold">
                                <i class="fas fa-arrow-left me-2"></i> Tiếp tục chọn mẫu khác
                            </a>
                            <button type="submit" name="update_cart" class="btn btn-outline-light">
                                <i class="fas fa-sync-alt me-1"></i> Cập nhật giỏ hàng
                            </button>
                            <a href="xoagiohang.php?action=clear" class="btn btn-outline-danger" onclick="return confirm('Bạn có chắc muốn làm trống giỏ hàng?');">
                                <i class="fas fa-trash me-1"></i> Xóa tất cả
                            </a>
                        </div>
                        <div class="col-md-6 text-md-end text-center">
                            <div class="mb-3">
                                <span style="color: #ffffff; font-size: 1.25rem; font-weight: 700; letter-spacing: 0.5px;">Tổng cộng thanh toán: </span>
                                <span style="color: #FFD700; font-size: 2rem; font-weight: 800; font-family: 'Cinzel', serif; letter-spacing: 1px;">
                                    <?= number_format($tong_tien, 0, ',', '.') ?> VNĐ
                                </span>
                            </div>
                            <a href="muahang.php" class="btn btn-gold btn-lg px-4 text-uppercase">
                                <i class="fas fa-credit-card me-2"></i> Tiến Hành Đặt Hàng &amp; Thanh Toán
                            </a>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h4 class="text-white">Giỏ hàng của bạn đang trống!</h4>
                    <p class="text-muted">Hãy khám phá bộ sưu tập đồng hồ đỉnh cao của BIGCLOCK để chọn cho mình mẫu ưng ý nhất.</p>
                    <a href="index.php#products" class="btn btn-gold mt-2 px-4 py-2">
                        <i class="fas fa-gem me-2"></i> Khám Phá Sản Phẩm Ngay
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
