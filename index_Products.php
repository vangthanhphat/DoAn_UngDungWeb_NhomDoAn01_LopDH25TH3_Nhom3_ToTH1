<section class="py-5" id="products" style="background-color: #0a0a0a;">
    <div class="container py-5">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10 text-center">
                <h6 class="gold-text text-uppercase" style="letter-spacing: 2px;">DANH MỤC THỜI THƯỢNG</h6>
                <h2 class="section-title" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.2rem; color: #fff;">
                    <?php echo isset($is_home) ? 'Những Cỗ Máy Thời Gian Nổi Bật' : 'Tất Cả Sản Phẩm'; ?>
                </h2>
                <p style="color: #cbd5e1; font-size: 1.05rem;">Tuyển chọn những siêu phẩm đồng hồ Thụy Sĩ &amp; Nhật Bản đỉnh cao tại <b>BIGCLOCK</b></p>
                
                <!-- 1. Thanh danh mục Bộ Sưu Tập (Category Filter Pills) -->
                <?php 
                $cur_dm = isset($_GET['danhmuc']) ? $_GET['danhmuc'] : '';
                $cur_kg = isset($_GET['khoanggia']) ? $_GET['khoanggia'] : '';
                $cur_lg = isset($_GET['locgia']) ? $_GET['locgia'] : '';
                $cur_tk = isset($_GET['tukhoa']) ? htmlspecialchars($_GET['tukhoa']) : '';
                ?>
                <div class="d-flex justify-content-center flex-wrap gap-2 mt-4 mb-4">
                    <a href="?#products" class="btn btn-sm <?= empty($cur_dm) ? 'btn-gold' : 'btn-outline-gold' ?>" style="border-radius: 20px; font-size: 0.85rem; padding: 6px 16px;">
                        Tất Cả
                    </a>
                    <a href="?danhmuc=dayda<?= !empty($cur_kg)?'&khoanggia='.$cur_kg:'' ?><?= !empty($cur_lg)?'&locgia='.$cur_lg:'' ?>#products" class="btn btn-sm <?= $cur_dm == 'dayda' ? 'btn-gold' : 'btn-outline-gold' ?>" style="border-radius: 20px; font-size: 0.85rem; padding: 6px 16px;">
                        <i class="fas fa-gem me-1"></i> Dây Da Cổ Điển
                    </a>
                    <a href="?danhmuc=thep<?= !empty($cur_kg)?'&khoanggia='.$cur_kg:'' ?><?= !empty($cur_lg)?'&locgia='.$cur_lg:'' ?>#products" class="btn btn-sm <?= $cur_dm == 'thep' ? 'btn-gold' : 'btn-outline-gold' ?>" style="border-radius: 20px; font-size: 0.85rem; padding: 6px 16px;">
                        <i class="fas fa-shield-alt me-1"></i> Thép Không Gỉ 316L
                    </a>
                    <a href="?danhmuc=kimcuong<?= !empty($cur_kg)?'&khoanggia='.$cur_kg:'' ?><?= !empty($cur_lg)?'&locgia='.$cur_lg:'' ?>#products" class="btn btn-sm <?= $cur_dm == 'kimcuong' ? 'btn-gold' : 'btn-outline-gold' ?>" style="border-radius: 20px; font-size: 0.85rem; padding: 6px 16px;">
                        <i class="fas fa-crown me-1"></i> Kim Cương &amp; Xa Xỉ
                    </a>
                    <a href="?danhmuc=automatic<?= !empty($cur_kg)?'&khoanggia='.$cur_kg:'' ?><?= !empty($cur_lg)?'&locgia='.$cur_lg:'' ?>#products" class="btn btn-sm <?= $cur_dm == 'automatic' ? 'btn-gold' : 'btn-outline-gold' ?>" style="border-radius: 20px; font-size: 0.85rem; padding: 6px 16px;">
                        <i class="fas fa-cogs me-1"></i> Tourbillon Cơ Khí
                    </a>
                    <a href="?danhmuc=doi<?= !empty($cur_kg)?'&khoanggia='.$cur_kg:'' ?><?= !empty($cur_lg)?'&locgia='.$cur_lg:'' ?>#products" class="btn btn-sm <?= $cur_dm == 'doi' ? 'btn-gold' : 'btn-outline-gold' ?>" style="border-radius: 20px; font-size: 0.85rem; padding: 6px 16px;">
                        <i class="fas fa-heart me-1"></i> Đồng Hồ Cặp Đôi
                    </a>
                </div>

                <!-- 2. Thanh tìm kiếm AJAX & Bộ lọc đa tiêu chí -->
                <form id="filterForm" class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 12px;" 
                      onsubmit="window.location.href = '?danhmuc=' + encodeURIComponent(this.danhmuc.value) + '&khoanggia=' + encodeURIComponent(this.khoanggia.value) + '&locgia=' + encodeURIComponent(this.locgia.value) + '&tukhoa=' + encodeURIComponent(this.tukhoa.value) + '#products'; return false;">
                    
                    <input type="hidden" name="danhmuc" value="<?= htmlspecialchars($cur_dm) ?>">

                    <!-- Ô tìm kiếm từ khóa AJAX -->
                    <div style="position: relative; max-width: 320px; width: 100%;">
                        <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #D4AF37;"></i>
                        <input type="text" id="searchInput" name="tukhoa" autocomplete="off" class="form-control" 
                               style="background-color: #141414; color: #fff; border: 1px solid rgba(212,175,55,0.45); border-radius: 25px; padding-left: 44px; box-shadow: none; height: 42px; font-size: 0.9rem;" 
                               placeholder="Tìm tên đồng hồ..." 
                               value="<?= $cur_tk ?>"
                               onkeyup="timKiemAjax(this.value)">
                        
                        <!-- Khung kết quả AJAX đổ xuống -->
                        <div id="searchResults" style="display:none; position:absolute; top:100%; left:0; right:0; background:#181818; border:1px solid #D4AF37; z-index:1050; border-radius:12px; margin-top:8px; max-height:350px; overflow-y:auto; text-align: left; box-shadow: 0 10px 30px rgba(0,0,0,0.9);"></div>
                    </div>
                    
                    <!-- Lọc khoảng giá -->
                    <div style="max-width: 200px; width: 100%;">
                        <select name="khoanggia" class="form-select" style="background-color: #141414; color: #fff; border: 1px solid rgba(212,175,55,0.45); border-radius: 25px; box-shadow: none; cursor: pointer; height: 42px; font-size: 0.9rem;">
                            <option value="" style="background: #222; color: #fff;">-- Khoảng giá VNĐ --</option>
                            <option value="duoi100" style="background: #222; color: #fff;" <?= $cur_kg == 'duoi100' ? 'selected' : '' ?>>Dưới 100 triệu</option>
                            <option value="100-200" style="background: #222; color: #fff;" <?= $cur_kg == '100-200' ? 'selected' : '' ?>>100 - 200 triệu</option>
                            <option value="tren200" style="background: #222; color: #fff;" <?= $cur_kg == 'tren200' ? 'selected' : '' ?>>Trên 200 triệu</option>
                        </select>
                    </div>

                    <!-- Sắp xếp giá -->
                    <div style="max-width: 190px; width: 100%;">
                        <select name="locgia" class="form-select" style="background-color: #141414; color: #fff; border: 1px solid rgba(212,175,55,0.45); border-radius: 25px; box-shadow: none; cursor: pointer; height: 42px; font-size: 0.9rem;">
                            <option value="" style="background: #222; color: #fff;">-- Sắp xếp --</option>
                            <option value="tang" style="background: #222; color: #fff;" <?= $cur_lg == 'tang' ? 'selected' : '' ?>>Giá tăng dần ⬆</option>
                            <option value="giam" style="background: #222; color: #fff;" <?= $cur_lg == 'giam' ? 'selected' : '' ?>>Giá giảm dần ⬇</option>
                            <option value="moinhat" style="background: #222; color: #fff;" <?= $cur_lg == 'moinhat' ? 'selected' : '' ?>>Mới nhất</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-gold px-4 fw-bold" style="border-radius: 25px; height: 42px; font-size: 0.9rem;">
                        <i class="fas fa-filter me-1"></i> ÁP DỤNG
                    </button>
                    
                    <?php if(!empty($cur_tk) || !empty($cur_lg) || !empty($cur_dm) || !empty($cur_kg)): ?>
                        <a href="index.php#products" class="btn btn-outline-gold px-3" style="border-radius: 25px; height: 42px; font-size: 0.9rem; display: inline-flex; align-items: center;" title="Xóa tất cả bộ lọc">
                            <i class="fas fa-times me-1"></i> Xóa lọc
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Lưới sản phẩm -->
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): 
                    $hinhAnh = str_replace('../', '', $row['HinhAnh'] ?? 'images/no-image.jpg');
                ?>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card h-100 luxury-product-card" style="background: #141414; border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 12px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            
                            <!-- Bấm vào hình ảnh dẫn tới trang chi tiết -->
                            <a href="chitiet.php?id=<?= $row['MaDongHo'] ?>" style="text-decoration: none;">
                                <div class="luxury-product-img-container p-3" style="background: #000; border-bottom: 1px solid rgba(212,175,55,0.2); text-align: center; height: 210px; display: flex; align-items: center; justify-content: center; position: relative;">
                                    <span class="badge" style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.7); color: #D4AF37; border: 1px solid #D4AF37; font-size: 10px;">
                                        Swiss Master
                                    </span>
                                    <img src="<?= htmlspecialchars($hinhAnh) ?>" alt="<?= htmlspecialchars($row['TenDongHo']) ?>" class="luxury-product-img" style="max-height: 170px; width: 100%; object-fit: contain; transition: transform 0.4s ease;">
                                </div>
                            </a>

                            <div class="card-body p-3 text-center d-flex flex-column">
                                <h5 class="luxury-product-title" style="margin-bottom: 10px;">
                                    <a href="chitiet.php?id=<?= $row['MaDongHo'] ?>" style="color: #D4AF37; text-decoration: none; font-size: 1.05rem; font-weight: 700;">
                                        <?= htmlspecialchars($row['TenDongHo']) ?>
                                    </a>
                                </h5>

                                <div class="mt-auto">
                                    <span style="color: #ffffff; font-weight: 800; font-size: 1.25rem; display: block; margin-bottom: 12px;">
                                        <?= number_format($row['DonGia'], 0, ',', '.') ?> VNĐ
                                    </span>

                                    <!-- Nút hành động: Xem chi tiết (sang trang chitiet.php) và Xem nhanh (Modal) -->
                                    <div class="d-flex gap-2">
                                        <a href="chitiet.php?id=<?= $row['MaDongHo'] ?>" class="btn btn-gold flex-grow-1 py-2 fw-bold" style="font-size: 0.85rem; border-radius: 6px;">
                                            <i class="fas fa-eye me-1"></i> Xem Chi Tiết
                                        </a>
                                        <button class="btn btn-outline-gold px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalProduct_<?= $row['MaDongHo'] ?>" title="Xem nhanh thông số" style="border-radius: 6px;">
                                            <i class="fas fa-search-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Chi Tiết Nhanh Từng Sản Phẩm -->
                    <div class="modal fade" id="modalProduct_<?= $row['MaDongHo'] ?>" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content" style="background-color: #141414; border: 2px solid #D4AF37; box-shadow: 0 10px 40px rgba(0,0,0,0.9); border-radius: 14px;">
                          <div class="modal-header" style="border-bottom: 1px solid rgba(212,175,55,0.3); padding: 15px 25px;">
                            <h4 class="modal-title" style="color: #D4AF37; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: bold; letter-spacing: 1px;">
                                <i class="fas fa-award me-2"></i><?= htmlspecialchars($row['TenDongHo']) ?>
                            </h4>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-5 text-center mb-4 mb-md-0">
                                    <div style="background: #000; padding: 20px; border-radius: 10px; border: 1px solid rgba(212,175,55,0.2);">
                                        <img src="<?= htmlspecialchars($hinhAnh) ?>" alt="<?= htmlspecialchars($row['TenDongHo']) ?>" style="width: 100%; max-height: 280px; object-fit: contain;">
                                    </div>
                                </div>
                                <div class="col-md-7 ps-md-4">
                                    <div class="mb-3">
                                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase">Swiss Made / Japan Standard</span>
                                    </div>
                                    <h2 style="color: #D4AF37; font-weight: bold; margin-bottom: 15px;">
                                        <?= number_format($row['DonGia'], 0, ',', '.') ?> VNĐ
                                    </h2>
                                    
                                    <p style="color: #cbd5e1; font-size: 0.95rem; line-height: 1.7; margin-bottom: 20px;">
                                        <strong style="color: #fff;">Mô tả:</strong> <?= htmlspecialchars($row['MoTa'] ?? 'Tuyệt tác thời gian đến từ nhà BIGCLOCK, chế tác tinh xảo với độ hoàn thiện hoàn hảo.') ?>
                                    </p>

                                    <p style="font-size: 0.95rem; margin-bottom: 25px;">
                                        <strong style="color: #fff;">Tình trạng:</strong> 
                                        <?php if($row['SoLuong'] > 0): ?>
                                            <span style="color: #4ade80; font-weight: 600;"><i class="fas fa-check-circle me-1"></i>Còn hàng (<?= $row['SoLuong'] ?> chiếc có sẵn)</span>
                                        <?php else: ?>
                                            <span class='text-danger fw-bold'><i class="fas fa-times-circle me-1"></i>Tạm hết hàng</span>
                                        <?php endif; ?>
                                    </p>
                                    
                                    <form onsubmit="return themVaoGioNgam(event, this);" action="themgiohang.php" method="POST">
                                        <input type="hidden" name="MaDongHo" value="<?= $row['MaDongHo'] ?>">
                                        <input type="hidden" name="ajax" value="1">
                                        
                                        <div class="d-flex align-items-center mb-4">
                                            <label class="me-3 text-white fw-bold">Số lượng:</label>
                                            <input type="number" name="SoLuongThem" value="1" min="1" max="<?= max(1, $row['SoLuong']) ?>" class="form-control" style="width: 90px; background: #222; color: #D4AF37; border: 1px solid #D4AF37; text-align: center; font-weight: bold;">
                                        </div>

                                        <div class="d-flex gap-3">
                                            <button type="submit" name="add_to_cart" class="btn btn-outline-gold flex-fill py-3" <?= $row['SoLuong'] <= 0 ? 'disabled' : '' ?>>
                                                <i class="fas fa-cart-plus me-2"></i> THÊM VÀO GIỎ
                                            </button>
                                            <a href="chitiet.php?id=<?= $row['MaDongHo'] ?>" class="btn btn-gold flex-fill py-3 d-flex justify-content-center align-items-center text-uppercase">
                                                <i class="fas fa-info-circle me-2"></i> XEM ĐẦY ĐỦ
                                            </a>
                                        </div>
                                    </form>

                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div style="background-color: rgba(212, 175, 55, 0.05); border: 1px solid #D4AF37; padding: 50px 20px; text-align: center; border-radius: 12px;">
                        <i class="fas fa-search" style="font-size: 3rem; color: #D4AF37; margin-bottom: 20px;"></i>
                        <h4 style="color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;">Không tìm thấy mẫu đồng hồ phù hợp</h4>
                        <p style="color: #cbd5e1;">Vui lòng thử lại với từ khóa khác hoặc điều chỉnh bộ lọc.</p>
                        <a href="index.php#products" class="btn btn-gold mt-2">Xem Tất Cả Sản Phẩm</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Phân trang -->
        <?php 
        $url_params = "";
        if (!empty($cur_tk)) $url_params .= "&tukhoa=" . urlencode($cur_tk);
        if (!empty($cur_lg)) $url_params .= "&locgia=" . urlencode($cur_lg);
        if (!empty($cur_dm)) $url_params .= "&danhmuc=" . urlencode($cur_dm);
        if (!empty($cur_kg)) $url_params .= "&khoanggia=" . urlencode($cur_kg);
        ?>
        
        <?php if (isset($total_pages) && $total_pages > 1): ?>
        <style>
            .custom-page-link { background-color: transparent !important; color: #D4AF37 !important; border: 1px solid #D4AF37 !important; border-radius: 4px; margin: 0 4px; transition: all 0.3s ease; }
            .custom-page-link:hover { background-color: rgba(212, 175, 55, 0.25) !important; color: #FFD700 !important; }
            .page-item.active .custom-page-link { background-color: #D4AF37 !important; color: #000 !important; border-color: #D4AF37 !important; font-weight: bold; }
            .page-item.disabled .custom-page-link { color: #555 !important; border-color: #444 !important; }
        </style>
        
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link custom-page-link" href="?page=<?= $page - 1 ?><?= $url_params ?>#products">&laquo;</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="page-link custom-page-link" href="?page=<?= $i ?><?= $url_params ?>#products"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link custom-page-link" href="?page=<?= $page + 1 ?><?= $url_params ?>#products">&raquo;</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- Script Live Search AJAX & Thêm vào giỏ ngầm -->
<script>
function timKiemAjax(keyword) {
    let resultsBox = document.getElementById('searchResults');
    if (keyword.trim().length === 0) {
        resultsBox.style.display = 'none';
        resultsBox.innerHTML = '';
        return;
    }
    
    fetch('xuly_timkiem_ajax.php?keyword=' + encodeURIComponent(keyword))
        .then(res => res.text())
        .then(data => {
            resultsBox.innerHTML = data;
            resultsBox.style.display = 'block';
        })
        .catch(err => {
            console.error('Lỗi tìm kiếm:', err);
        });
}

// Ẩn kết quả tìm kiếm khi bấm ra ngoài
document.addEventListener('click', function(e) {
    let box = document.getElementById('searchResults');
    let input = document.getElementById('searchInput');
    if (box && input && !box.contains(e.target) && e.target !== input) {
        box.style.display = 'none';
    }
});

function themVaoGioNgam(e, form) {
    e.preventDefault();
    let formData = new FormData(form);
    fetch('themgiohang.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.text())
    .then(data => {
        if(data === 'HEZHANG') {
            alert('Sản phẩm đã tạm hết hàng!');
        } else {
            let countEl = document.getElementById('cart-count');
            if (countEl) countEl.innerText = data;
            alert('Đã thêm sản phẩm vào giỏ hàng thành công!');
            let modalEl = bootstrap.Modal.getInstance(form.closest('.modal'));
            if (modalEl) modalEl.hide();
        }
    })
    .catch(err => {
        form.submit();
    });
    return false;
}
</script>
