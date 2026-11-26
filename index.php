<?php
/**
 * BIGCLOCK - SWISS LUXURY TIMEPIECE
 * Trang chủ chính thức
 */
require_once 'connect.php';

// Logic phân trang, tìm kiếm & sắp xếp
$limit = 8; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page <= 0) $page = 1;
$offset = ($page - 1) * $limit;

// Bắt dữ liệu từ thanh tìm kiếm & lọc đa tiêu chí
$tu_khoa = isset($_GET['tukhoa']) ? trim($_GET['tukhoa']) : '';
$loc_gia = isset($_GET['locgia']) ? $_GET['locgia'] : '';
$danh_muc = isset($_GET['danhmuc']) ? trim($_GET['danhmuc']) : '';
$khoang_gia = isset($_GET['khoanggia']) ? trim($_GET['khoanggia']) : '';

// Điều kiện lọc
$where = "WHERE (TrangThai = 1 OR TrangThai IS NULL)";
if (!empty($tu_khoa)) {
    $where .= " AND (TenDongHo LIKE '%" . $conn->real_escape_string($tu_khoa) . "%' OR MoTa LIKE '%" . $conn->real_escape_string($tu_khoa) . "%')";
}

// Lọc theo bộ sưu tập
if ($danh_muc === 'dayda') {
    $where .= " AND (TenDongHo LIKE '%Da%' OR MoTa LIKE '%da%' OR TenDongHo LIKE '%Heritage%' OR MoTa LIKE '%Alligator%' OR TenDongHo LIKE '%Prestige%')";
} elseif ($danh_muc === 'thep') {
    $where .= " AND (TenDongHo LIKE '%Mesh%' OR TenDongHo LIKE '%Titanium%' OR TenDongHo LIKE '%Steel%' OR MoTa LIKE '%thép%' OR MoTa LIKE '%ceramic%' OR MoTa LIKE '%lặn%')";
} elseif ($danh_muc === 'kimcuong') {
    $where .= " AND (TenDongHo LIKE '%Diamond%' OR TenDongHo LIKE '%Gold%' OR MoTa LIKE '%kim cương%' OR MoTa LIKE '%vàng%' OR TenDongHo LIKE '%Pearl%')";
} elseif ($danh_muc === 'automatic') {
    $where .= " AND (TenDongHo LIKE '%Tourbillon%' OR TenDongHo LIKE '%Chrono%' OR MoTa LIKE '%Tourbillon%' OR MoTa LIKE '%cơ%' OR MoTa LIKE '%Chronometer%')";
} elseif ($danh_muc === 'doi') {
    $where .= " AND (TenDongHo LIKE '%Duo%' OR MoTa LIKE '%đôi%' OR TenDongHo LIKE '%Constellation%')";
}

// Lọc theo khoảng giá VNĐ
if ($khoang_gia === 'duoi100') {
    $where .= " AND DonGia < 100000000";
} elseif ($khoang_gia === '100-200') {
    $where .= " AND DonGia >= 100000000 AND DonGia <= 200000000";
} elseif ($khoang_gia === 'tren200') {
    $where .= " AND DonGia > 200000000";
}

// Sắp xếp giá
$order = "ORDER BY MaDongHo DESC";
if ($loc_gia == 'tang') {
    $order = "ORDER BY DonGia ASC";
} elseif ($loc_gia == 'giam') {
    $order = "ORDER BY DonGia DESC";
} elseif ($loc_gia == 'moinhat') {
    $order = "ORDER BY MaDongHo DESC";
}

// Đếm tổng số lượng bản ghi
$sql_count = "SELECT COUNT(MaDongHo) AS total FROM dongho $where";
$result_count = $conn->query($sql_count);
$total_records = $result_count ? $result_count->fetch_assoc()['total'] : 0;
$total_pages = ceil($total_records / $limit);

// Lấy danh sách sản phẩm
$sql = "SELECT * FROM dongho $where $order LIMIT $offset, $limit";
$result = $conn->query($sql);

$is_home = true;
include 'header.php';
include 'index_top.php';
include 'index_Products.php';
include 'index_bottom.php';
include 'footer.php';
?>
