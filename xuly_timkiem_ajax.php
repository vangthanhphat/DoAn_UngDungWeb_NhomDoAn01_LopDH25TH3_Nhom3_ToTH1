<?php
require_once 'connect.php';

if (isset($_GET['keyword'])) {
    $keyword = $conn->real_escape_string(trim($_GET['keyword']));
    
    // Tìm kiếm tối đa 5 sản phẩm gần đúng nhất
    $sql = "SELECT MaDongHo, TenDongHo, HinhAnh, DonGia FROM dongho 
            WHERE (TrangThai = 1 OR TrangThai IS NULL) AND TenDongHo LIKE '%$keyword%' 
            LIMIT 5";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $raw_img = $row['HinhAnh'] ?? 'images/no-image.jpg';
            $img = htmlspecialchars(str_replace('../', '', $raw_img));
            $name = htmlspecialchars($row['TenDongHo']);
            $price = number_format($row['DonGia'], 0, ',', '.') . ' VNĐ';
            $id = $row['MaDongHo'];
            
            echo "
            <div style='padding: 12px; border-bottom: 1px solid rgba(212,175,55,0.2); cursor: pointer; display: flex; align-items: center;' 
                 onclick=\"window.location.href='chitiet.php?id=$id';\"
                 onmouseover=\"this.style.backgroundColor='rgba(212,175,55,0.2)'\" 
                 onmouseout=\"this.style.backgroundColor='transparent'\">
                <img src='$img' style='width: 48px; height: 48px; object-fit: contain; background: #000; margin-right: 14px; border: 1px solid #D4AF37; border-radius: 6px; padding: 2px;'>
                <div>
                    <div style='color: #fff; font-weight: bold; font-size: 0.95rem;'>$name</div>
                    <div style='color: #D4AF37; font-size: 0.85rem; font-weight: 600;'>$price</div>
                </div>
            </div>
            ";
        }
    } else {
        echo "<div style='padding: 14px; color: #aaa; text-align: center; font-style: italic; font-size: 0.9rem;'>Không tìm thấy mẫu đồng hồ phù hợp</div>";
    }
}
?>
