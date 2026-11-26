<?php
/**
 * BIGCLOCK - SWISS LUXURY TIMEPIECE
 * Tệp kết nối Cơ sở dữ liệu thông minh
 * Tự động hỗ trợ cả Vertrigo Server (pass 'vertrigo') và XAMPP (pass rỗng)
 */

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

$db_host = 'localhost';
$db_user = 'root';
$primary_db = 'bigclock_db';
$fallback_db = 'shopdongho';

// Thử kết nối tới database chính (bigclock_db) với pass vertrigo
$conn = @new mysqli($db_host, $db_user, 'vertrigo', $primary_db);

if ($conn->connect_error) {
    // Thử kết nối không pass (XAMPP)
    $conn = @new mysqli($db_host, $db_user, '', $primary_db);
}

// Nếu bigclock_db chưa có, thử fallback sang database shopdongho
if ($conn->connect_error) {
    $conn = @new mysqli($db_host, $db_user, 'vertrigo', $fallback_db);
    if ($conn->connect_error) {
        $conn = @new mysqli($db_host, $db_user, '', $fallback_db);
    }
}

// Nếu vẫn thất bại, báo lỗi hướng dẫn
if ($conn->connect_error) {
    die("<div style='background:#0a0a0a; color:#D4AF37; font-family:Arial,sans-serif; text-align:center; padding:40px; border:2px solid #D4AF37; max-width:650px; margin:50px auto; border-radius:10px; box-shadow:0 0 20px rgba(212,175,55,0.4);'>
            <h2 style='margin-top:0;'>LỖI KẾT NỐI CƠ SỞ DỮ LIỆU!</h2>
            <p style='color:#fff; font-size:15px;'>Hệ thống không thể kết nối tới MySQL. Vui lòng kiểm tra:</p>
            <ul style='color:#ccc; text-align:left; display:inline-block; font-size:14px; line-height:1.8;'>
                <li>Máy chủ Web (Vertrigo Server hoặc XAMPP) đã được khởi động chưa.</li>
                <li>Bạn đã Import tệp <b>database.sql</b> vào <b>phpMyAdmin</b> chưa.</li>
                <li>Chi tiết lỗi: <b style='color:#ff6b6b;'>" . htmlspecialchars($conn->connect_error) . "</b></li>
            </ul>
         </div>");
}

$conn->set_charset("utf8mb4");
$conn->query("SET NAMES 'utf8mb4'");

// Cấu hình chung cho thương hiệu BIGCLOCK
$siteTitle = "BIGCLOCK";
$slogan = "Tinh hoa thời gian - Đẳng cấp vĩnh cửu";
$currentYear = date("Y");
?>
