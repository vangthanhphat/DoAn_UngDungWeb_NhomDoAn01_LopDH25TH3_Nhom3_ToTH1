<?php
/**
 * BIGCLOCK - SWISS LUXURY TIMEPIECE
 * Trang Chi Tiết Sản Phẩm Đẳng Cấp
 */
require_once 'connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: index.php#products");
    exit();
}

// Lấy thông tin đồng hồ từ CSDL
$stmt = $conn->prepare("SELECT * FROM dongho WHERE MaDongHo = ? AND (TrangThai = 1 OR TrangThai IS NULL)");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows == 0) {
    header("Location: index.php#products");
    exit();
}

$sp = $res->fetch_assoc();
$hinhAnh = str_replace('../', '', $sp['HinhAnh'] ?? 'images/no-image.jpg');

// Lấy 4 sản phẩm liên quan
$stmt_rel = $conn->prepare("SELECT * FROM dongho WHERE MaDongHo != ? AND (TrangThai = 1 OR TrangThai IS NULL) ORDER BY RAND() LIMIT 4");
$stmt_rel->bind_param("i", $id);
$stmt_rel->execute();
$res_rel = $stmt_rel->get_result();

$siteTitle = $sp['TenDongHo'] . " - BIGCLOCK";
include 'header.php';
?>

<div style="background-color: #080808; color: #fff; min-height: 100vh; padding-top: 100px; padding-bottom: 70px;">
    <div class="container py-4">
        
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background: transparent; padding: 0; font-size: 0.9rem;">
                <li class="breadcrumb-item"><a href="index.php" style="color: #cbd5e1; text-decoration: none;"><i class="fas fa-home me-1"></i> Trang Chủ</a></li>
                <li class="breadcrumb-item"><a href="index.php#products" style="color: #cbd5e1; text-decoration: none;">Bộ Sưu Tập</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #D4AF37; font-weight: 600;"><?= htmlspecialchars($sp['TenDongHo']) ?></li>
            </ol>
        </nav>

        <!-- Khung chi tiết sản phẩm chính -->
        <div class="row g-5 align-items-start">
            
            <!-- Cột Trái: Trưng Bày Hình Ảnh Đồng Hồ -->
            <div class="col-lg-6">
                <div style="background: radial-gradient(circle, #1a1a1a 0%, #0d0d0d 100%); border: 2px solid rgba(212, 175, 55, 0.4); border-radius: 16px; padding: 40px; text-align: center; box-shadow: 0 15px 50px rgba(0,0,0,0.9); position: relative;">
                    <div style="position: absolute; top: 20px; left: 20px;">
                        <span class="badge" style="background: rgba(212,175,55,0.15); color: #D4AF37; border: 1px solid #D4AF37; padding: 8px 14px; font-size: 0.8rem; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 700;">
                            <i class="fas fa-certificate me-1"></i> Swiss Luxury Master
                        </span>
                    </div>
                    <img src="<?= htmlspecialchars($hinhAnh) ?>" alt="<?= htmlspecialchars($sp['TenDongHo']) ?>" class="img-fluid" style="max-height: 440px; width: auto; object-fit: contain; filter: drop-shadow(0 10px 25px rgba(0,0,0,0.8)); transition: transform 0.4s ease;" id="mainWatchImg">
                    
                    <div class="mt-4 pt-3 d-flex justify-content-center gap-3" style="border-top: 1px solid rgba(212,175,55,0.2);">
                        <span style="color: #cbd5e1; font-size: 0.85rem;"><i class="fas fa-shield-alt text-warning me-1"></i> Kính Sapphire AR</span>
                        <span style="color: #cbd5e1; font-size: 0.85rem;"><i class="fas fa-water text-info me-1"></i> Chống nước 10 - 30 ATM</span>
                        <span style="color: #cbd5e1; font-size: 0.85rem;"><i class="fas fa-medal text-warning me-1"></i> Bảo hành 5 năm</span>
                    </div>
                </div>
            </div>

            <!-- Cột Phải: Thông Tin Mua Hàng & Giá Trị -->
            <div class="col-lg-6">
                <div class="ps-lg-3">
                    <div class="d-flex align-items-center mb-2 gap-2">
                        <span class="text-warning small">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </span>
                        <span style="color: #cbd5e1; font-size: 0.85rem;">(5.0 / 5.0 đánh giá từ chuyên gia)</span>
                        <span class="ms-auto" style="color: #94a3b8; font-size: 0.85rem;">Mã SP: <b class="text-white">BC-<?= str_pad($sp['MaDongHo'], 4, '0', STR_PAD_LEFT) ?></b></span>
                    </div>

                    <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.2rem; font-weight: 800; color: #fff; line-height: 1.3; margin-bottom: 15px;">
                        <?= htmlspecialchars($sp['TenDongHo']) ?>
                    </h1>

                    <!-- Mức Giá -->
                    <div class="p-3 mb-4 rounded" style="background: rgba(212,175,55,0.08); border-left: 4px solid #D4AF37;">
                        <div class="d-flex align-items-baseline gap-3">
                            <span style="font-family: 'Cinzel', serif; font-size: 2.4rem; font-weight: 800; color: #D4AF37;">
                                <?= number_format($sp['DonGia'], 0, ',', '.') ?> VNĐ
                            </span>
                            <span class="badge bg-dark border border-secondary text-light">Đã bao gồm VAT</span>
                        </div>
                        <small style="color: #cbd5e1;"><i class="fas fa-truck-moving text-warning me-1"></i> Miễn phí vận chuyển bảo hiểm hỏa tốc toàn quốc</small>
                    </div>

                    <!-- Tình Trạng Kho Hàng -->
                    <div class="mb-4 d-flex align-items-center gap-3">
                        <span style="color: #4ade80; font-weight: 600; font-size: 0.95rem;">
                            <i class="fas fa-check-circle me-1"></i> Còn hàng (<?= intval($sp['SoLuong']) ?> chiếc sẵn có tại Boutique Đồng Khởi)
                        </span>
                    </div>

                    <!-- Mô tả sản phẩm -->
                    <div class="mb-4">
                        <h6 style="color: #D4AF37; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Kiệt Tác Chế Tác:</h6>
                        <p style="color: #e2e8f0; font-size: 1.02rem; line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($sp['MoTa'])) ?>
                        </p>
                    </div>

                    <!-- Form Mua Hàng & Thêm Vào Giỏ -->
                    <form action="themgiohang.php" method="POST" class="mb-4">
                        <input type="hidden" name="MaDongHo" value="<?= $sp['MaDongHo'] ?>">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <label style="color: #cbd5e1; font-weight: 600;">Số lượng:</label>
                            <div class="d-flex align-items-center" style="background: #181818; border: 1px solid #475569; border-radius: 8px; overflow: hidden;">
                                <button type="button" class="btn btn-sm text-white px-3 py-2" onclick="var q = document.getElementById('slInput'); if(q.value > 1) q.value--;">-</button>
                                <input type="number" id="slInput" name="SoLuong" value="1" min="1" max="<?= $sp['SoLuong'] ?>" style="width: 55px; background: transparent; border: none; color: #fff; text-align: center; font-weight: bold;">
                                <button type="button" class="btn btn-sm text-white px-3 py-2" onclick="var q = document.getElementById('slInput'); if(q.value < <?= $sp['SoLuong'] ?>) q.value++;">+</button>
                            </div>
                            <span style="color: #94a3b8; font-size: 0.85rem;">Hỗ trợ đóng gói quà tặng theo yêu cầu</span>
                        </div>

                        <div class="d-flex flex-wrap gap-3">
                            <button type="submit" name="themgiohang" class="btn btn-outline-gold flex-grow-1 py-3 text-uppercase fw-bold" style="letter-spacing: 1.5px; border-radius: 8px;">
                                <i class="fas fa-cart-plus me-2"></i> THÊM VÀO GIỎ HÀNG
                            </button>
                            <button type="submit" name="muangay" value="1" class="btn btn-gold flex-grow-1 py-3 text-uppercase fw-bold" style="letter-spacing: 1.5px; border-radius: 8px;">
                                <i class="fas fa-bolt me-2"></i> MUA NGAY HỎA TỐC
                            </button>
                        </div>
                    </form>

                    <!-- Hotline Tư Vấn Trực Tiếp -->
                    <div class="p-3 rounded d-flex align-items-center justify-content-between" style="background: #141414; border: 1px solid rgba(212,175,55,0.25);">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-headset fs-3 text-warning me-3"></i>
                            <div>
                                <h6 class="text-white mb-0" style="font-size: 0.95rem;">Cần tư vấn chuyên sâu về cỗ máy này?</h6>
                                <small style="color: #cbd5e1;">Chuyên viên đồng hồ cao cấp sẵn sàng hỗ trợ 24/7</small>
                            </div>
                        </div>
                        <a href="tel:19008899" class="btn btn-sm btn-gold px-3 py-2 fw-bold" style="border-radius: 6px;">
                            <i class="fas fa-phone-alt me-1"></i> 1900 8899
                        </a>
                    </div>

                </div>
            </div>

        </div>

        <!-- Bảng Thông Số Kỹ Thuật Đầy Đủ & Đặc Quyền Dịch Vụ -->
        <div class="mt-5 pt-4">
            <div class="row g-4">
                
                <!-- Bảng Thông Số Chi Tiết -->
                <div class="col-lg-7">
                    <div class="p-4 rounded h-100" style="background: #111; border: 1px solid rgba(212,175,55,0.3); border-radius: 14px;">
                        <h4 style="color: #D4AF37; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; margin-bottom: 20px; font-size: 1.3rem;">
                            <i class="fas fa-cogs me-2"></i> BẢNG THÔNG SỐ KỸ THUẬT CHI TIẾT
                        </h4>
                        
                        <table class="table table-dark table-striped mb-0" style="font-size: 0.95rem;">
                            <tbody>
                                <tr>
                                    <td style="color: #cbd5e1; width: 40%;"><i class="fas fa-tag text-warning me-2"></i> Thương hiệu:</td>
                                    <td class="text-white fw-bold">BIGCLOCK SWISS TIMEPIECE</td>
                                </tr>
                                <tr>
                                    <td style="color: #cbd5e1;"><i class="fas fa-microchip text-warning me-2"></i> Bộ máy chuyển động:</td>
                                    <td class="text-white fw-bold">Swiss Automatic Calibre BC-Series (Tự động lên cót)</td>
                                </tr>
                                <tr>
                                    <td style="color: #cbd5e1;"><i class="fas fa-circle-notch text-warning me-2"></i> Đường kính mặt số:</td>
                                    <td class="text-white fw-bold">41 mm - 42 mm (Chuẩn cổ tay quý ông hiện đại)</td>
                                </tr>
                                <tr>
                                    <td style="color: #cbd5e1;"><i class="fas fa-arrows-alt-v text-warning me-2"></i> Bề dày vỏ:</td>
                                    <td class="text-white fw-bold">11.8 mm (Thiết kế thanh lịch)</td>
                                </tr>
                                <tr>
                                    <td style="color: #cbd5e1;"><i class="fas fa-gem text-warning me-2"></i> Mặt kính:</td>
                                    <td class="text-white fw-bold">Sapphire nguyên khối phủ AR chống chói đa lớp</td>
                                </tr>
                                <tr>
                                    <td style="color: #cbd5e1;"><i class="fas fa-shield-alt text-warning me-2"></i> Chất liệu vỏ:</td>
                                    <td class="text-white fw-bold">Thép không gỉ 316L / Vàng hồng 18K / Titanium Cấp 5</td>
                                </tr>
                                <tr>
                                    <td style="color: #cbd5e1;"><i class="fas fa-tint text-warning me-2"></i> Độ chịu nước:</td>
                                    <td class="text-white fw-bold">10 ATM (100 mét) - Đi bơi, rửa tay, đi mưa tuyệt đối an toàn</td>
                                </tr>
                                <tr>
                                    <td style="color: #cbd5e1;"><i class="fas fa-battery-full text-warning me-2"></i> Thời gian trữ cót:</td>
                                    <td class="text-white fw-bold">48 đến 72 giờ vận hành ổn định liên tục</td>
                                </tr>
                                <tr>
                                    <td style="color: #cbd5e1;"><i class="fas fa-globe text-warning me-2"></i> Xuất xứ:</td>
                                    <td class="text-white fw-bold">Thụy Sĩ (Swiss Made) / Lắp ráp thủ công chuẩn nghệ nhân</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4 Đặc Quyền Dịch Vụ Khách Hàng -->
                <div class="col-lg-5">
                    <div class="row g-3 h-100">
                        <div class="col-12">
                            <div class="p-3 rounded h-100" style="background: #141414; border: 1px solid rgba(212,175,55,0.25);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-award text-warning fs-4 me-3"></i>
                                    <h6 class="text-white mb-0 fw-bold">100% Chính Hãng &amp; Minh Bạch</h6>
                                </div>
                                <p class="small mb-0" style="color: #cbd5e1; line-height: 1.6;">Đầy đủ giấy chứng nhận nguồn gốc xuất xứ CO/CQ, thẻ bảo hành quốc tế từ nhà sản xuất. Đền bù 200% nếu phát hiện giả mạo.</p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded h-100" style="background: #141414; border: 1px solid rgba(212,175,55,0.25);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-tools text-warning fs-4 me-3"></i>
                                    <h6 class="text-white mb-0 fw-bold">Bảo Hành 5 Năm &amp; Lau Dầu Miễn Phí</h6>
                                </div>
                                <p class="small mb-0" style="color: #cbd5e1; line-height: 1.6;">Chế độ bảo hành toàn cầu 5 năm, miễn phí kiểm tra độ kháng từ, căn chỉnh sai số và thay dầu định kỳ trọn đời.</p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded h-100" style="background: #141414; border: 1px solid rgba(212,175,55,0.25);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-shipping-fast text-warning fs-4 me-3"></i>
                                    <h6 class="text-white mb-0 fw-bold">Giao Hỏa Tốc Hộp Gỗ Sơn Mài</h6>
                                </div>
                                <p class="small mb-0" style="color: #cbd5e1; line-height: 1.6;">Giao hỏa tốc 2 giờ tại TP.HCM &amp; Hà Nội. Sản phẩm được đặt trong hộp gỗ sơn mài sang trọng kèm thẻ bảo hành danh giá.</p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded h-100" style="background: #141414; border: 1px solid rgba(212,175,55,0.25);">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-sync-alt text-warning fs-4 me-3"></i>
                                    <h6 class="text-white mb-0 fw-bold">Đổi Mới Trong 30 Ngày</h6>
                                </div>
                                <p class="small mb-0" style="color: #cbd5e1; line-height: 1.6;">Hỗ trợ 1 đổi 1 trong vòng 30 ngày nếu phát sinh lỗi kỹ thuật từ nhà sản xuất. Thủ tục tiếp nhận nhanh chóng trong 15 phút.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Các Tuyệt Tác Cùng Bộ Sưu Tập -->
        <div class="mt-5 pt-5" style="border-top: 1px solid rgba(212,175,55,0.2);">
            <div class="text-center mb-4">
                <h6 class="gold-text text-uppercase" style="letter-spacing: 2px;">CÓ THỂ BẠN QUAN TÂM</h6>
                <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.9rem; font-weight: 700; color: #fff;">
                    Các Tuyệt Tác Cùng Bộ Sưu Tập
                </h3>
            </div>
            
            <div class="row g-4">
                <?php while ($r_rel = $res_rel->fetch_assoc()): 
                    $img_rel = str_replace('../', '', $r_rel['HinhAnh'] ?? 'images/no-image.jpg');
                ?>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="card h-100 luxury-product-card" style="overflow: hidden; background: #141414; border: 1px solid rgba(212,175,55,0.3); border-radius: 10px;">
                            <a href="chitiet.php?id=<?= $r_rel['MaDongHo'] ?>" style="text-decoration: none;">
                                <div class="p-3 text-center" style="background: #000; height: 190px; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid rgba(212,175,55,0.2);">
                                    <img src="<?= htmlspecialchars($img_rel) ?>" alt="<?= htmlspecialchars($r_rel['TenDongHo']) ?>" style="max-height: 160px; max-width: 100%; object-fit: contain;">
                                </div>
                            </a>
                            <div class="card-body p-3 text-center d-flex flex-column">
                                <h6 class="luxury-product-title mb-2">
                                    <a href="chitiet.php?id=<?= $r_rel['MaDongHo'] ?>" style="color: #D4AF37; text-decoration: none;">
                                        <?= htmlspecialchars($r_rel['TenDongHo']) ?>
                                    </a>
                                </h6>
                                <div class="mt-auto">
                                    <span style="color: #fff; font-weight: bold; font-size: 1.1rem; display: block; margin-bottom: 8px;">
                                        <?= number_format($r_rel['DonGia'], 0, ',', '.') ?> VNĐ
                                    </span>
                                    <a href="chitiet.php?id=<?= $r_rel['MaDongHo'] ?>" class="btn btn-sm btn-outline-gold w-100 py-1" style="font-size: 13px;">
                                        <i class="fas fa-eye me-1"></i> Xem Chi Tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
