-- ==========================================================
-- CƠ SỞ DỮ LIỆU ĐỒ ÁN: SHOWROOM ĐỒNG HỒ CAO CẤP BIGCLOCK
-- Tương thích hoàn toàn XAMPP & Vertrigo Server
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `bigclock_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bigclock_db`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `chitiethoadon`;
DROP TABLE IF EXISTS `hoadon`;
DROP TABLE IF EXISTS `dongho`;
DROP TABLE IF EXISTS `user`;
SET FOREIGN_KEY_CHECKS = 1;

-- Cấu trúc bảng `user`
CREATE TABLE `user` (
  `MaNguoiDung` int(10) NOT NULL AUTO_INCREMENT,
  `TenNguoiDung` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TenDangNhap` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MatKhau` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Quyen` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1: Admin, 0: KhÃ¡ch hÃ ng',
  `SDT` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DiaChi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`MaNguoiDung`),
  UNIQUE KEY `TenDangNhap` (`TenDangNhap`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `user`
INSERT INTO `user` (`MaNguoiDung`, `TenNguoiDung`, `TenDangNhap`, `MatKhau`, `Quyen`, `SDT`, `Email`, `DiaChi`) VALUES ('1', 'Văng Thành Phát', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', '1900 8899', 'admin@bigclock.vn', 'Số 88 Phố Đồng Khởi, Quận 1, TP. Hồ Chí Minh');
INSERT INTO `user` (`MaNguoiDung`, `TenNguoiDung`, `TenDangNhap`, `MatKhau`, `Quyen`, `SDT`, `Email`, `DiaChi`) VALUES ('2', 'Nguyễn Hoàng Thiện', 'khachhang', 'e10adc3949ba59abbe56e057f20f883e', '0', '0988 123 456', 'hoangthien@gmail.com', '123 Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh');
INSERT INTO `user` (`MaNguoiDung`, `TenNguoiDung`, `TenDangNhap`, `MatKhau`, `Quyen`, `SDT`, `Email`, `DiaChi`) VALUES ('3', 'Huỳnh Minh Anh', 'minhanh', 'e10adc3949ba59abbe56e057f20f883e', '0', '0912 345 678', 'minhanh@gmail.com', '456 Đường Lê Lợi, Quận Hải Châu, TP. Đà Nẵng');
INSERT INTO `user` (`MaNguoiDung`, `TenNguoiDung`, `TenDangNhap`, `MatKhau`, `Quyen`, `SDT`, `Email`, `DiaChi`) VALUES ('4', 'Lê Gia Phú', 'giaphu', 'e10adc3949ba59abbe56e057f20f883e', '0', '0933 678 999', 'giaphu@gmail.com', '789 Đường Cầu Giấy, Quận Cầu Giấy, TP. Hà Nội');

-- Cấu trúc bảng `dongho`
CREATE TABLE `dongho` (
  `MaDongHo` int(10) NOT NULL AUTO_INCREMENT,
  `TenDongHo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `DonGia` decimal(12,0) NOT NULL,
  `GiaNhap` decimal(12,0) NOT NULL DEFAULT '0',
  `SoLuong` int(10) NOT NULL DEFAULT '0',
  `MoTa` text COLLATE utf8mb4_unicode_ci,
  `HinhAnh` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TrangThai` tinyint(1) DEFAULT '1' COMMENT '1: Äang bÃ¡n, 0: Táº¡m áº©n',
  PRIMARY KEY (`MaDongHo`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `dongho`
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('1', 'BigClock Seamaster Deep Diver 300M', '145000000', '115000000', '25', 'Đồng hồ thợ lặn chuyên nghiệp đạt chuẩn kháng nước 300M với van thoát khí Heli tự động. Vành niềng Ceramic đen bóng chống trầy xước tuyệt đối, cọc số dạ quang Super-LumiNova phát sáng rực rỡ dưới đáy đại dương.', 'images/luxury/sp_diver300m.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('2', 'BigClock Speedmaster Moonwatch Chrono', '185000000', '145000000', '18', 'Huyền thoại bấm giờ vũ trụ đạt chứng nhận Master Chronometer Thụy Sĩ. Bộ máy lên cót Calibre đỉnh cao kháng từ 15.000 Gauss, mặt số phụ đo đếm chính xác từng phần mười giây.', 'images/luxury/sp_moonwatch.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('3', 'BigClock Dark Side Ceramic Edition', '295000000', '230000000', '12', 'Vỏ gốm zirconium oxide ceramic đen nguyên khối độc bản siêu cứng và siêu nhẹ. Điểm xuyết chi tiết kim cọc vàng trắng và mặt kính vòm sapphire hai mặt, biểu tượng của sự uy quyền và phong thái bí ẩn.', 'images/luxury/sp_darkside.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('4', 'BigClock De Ville Ladymatic Pearl', '175000000', '135000000', '20', 'Tuyệt phẩm đồng hồ nữ nạm kim cương thiên nhiên quanh vành bezel. Mặt số khảm xà cừ ngọc trai biển sâu ngũ sắc lấp lánh, tôn vinh vẻ đẹp thanh lịch quý phái của các quý cô thượng lưu.', 'images/luxury/sp_ladymatic.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('5', 'BigClock Trésor Rose Gold Elegance', '115000000', '88000000', '21', 'Thiết kế vỏ mỏng tinh tế bằng vàng Sedna 18K ấm áp, hai hàng kim cương uốn lượn gợi cảm dọc hai bên thân vỏ. Dây lụa satin đỏ rượu sang trọng kết hợp khóa cài vàng khối danh giá.', 'images/luxury/sp_tresor.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('6', 'BigClock Central Tourbillon Platinum Master', '390000000', '310000000', '8', 'Kiệt tác Haute Horlogerie đỉnh cao bậc nhất với lồng Tourbillon đặt ngay tại trung tâm mặt số. Vỏ bạch kim Platinum 950 nguyên khối, chế tác giới hạn chỉ 25 chiếc toàn cầu, khẳng định vị thế tối thượng.', 'images/luxury/sp_tourbillon.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('7', 'BigClock Heritage First Space Leather', '135000000', '105000000', '28', 'Tái hiện mẫu đồng hồ huyền thoại du hành không gian với dây da bò thủ công màu nâu cổ điển. Vỏ thép 39.7mm tỉ lệ vàng, mặt số đối xứng kinh điển cùng chức năng bấm giờ chính xác.', 'images/luxury/sp_space_leather.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('8', 'BigClock Constellation Blue Alligator', '160000000', '125000000', '24', 'Dòng Constellation biểu tượng với các móng vuốt kinh điển tại vị trí 3h và 9h. Niềng gốm xanh bóng phối cùng dây da cá sấu Alligator xanh thẳm thời thượng, đạt chuẩn Chronometer Thụy Sĩ.', 'images/luxury/sp_constellation_blue.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('9', 'BigClock Aqua Terra Shades Saffron', '128000000', '98000000', '30', 'Mặt số màu vàng Saffron rực rỡ chải tia Sunray từ tâm mang đến hiệu ứng thị giác mê hoặc dưới ánh nắng. Thân vỏ thép 316L đánh bóng kết hợp bộ máy tự động Calibre kháng từ tuyệt đối.', 'images/luxury/sp_aquaterra_saffron.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('10', 'BigClock Diamond Bezel Royal White', '210000000', '165000000', '15', 'Đỉnh cao trang sức xa xỉ với vành bezel nạm 52 viên kim cương tự nhiên VVS tinh khiết. Mặt số trắng opal phối cọc số vàng, bộ dây thép đúc đặc 3 dải sang trọng và lịch lãm.', 'images/luxury/sp_diamond_bezel.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('11', 'BigClock Seamaster Planet Ocean 600M', '168000000', '130000000', '20', 'Đồng hồ thợ lặn chuyên nghiệp chinh phục độ sâu 600M (2000ft) với van xả khí Heli. Vành xoay gốm ceramic màu cam viền dạ quang rực rỡ, kính sapphire vòm chống trầy xước.', 'images/luxury/sp_planetocean_600m.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('12', 'BigClock Speedmaster Chronoscope Blue', '195000000', '155000000', '16', 'Mặt số xanh Navy huyền ảo với 3 thang đo cổ điển: Telemeter đo khoảng cách, Pulsometer đo nhịp tim và Tachymeter đo vận tốc. Bộ máy lên cót tay Calibre 9908 đạt chứng nhận Thụy Sĩ.', 'images/luxury/sp_chronoscope_blue.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('13', 'BigClock De Ville Prestige Power Reserve', '140000000', '110000000', '22', 'Phong cách quý ông thanh lịch với kim báo mức năng lượng cót góc 6 giờ và kim rốn góc 9 giờ. Dây da cá sấu Alligator xanh bóng cao cấp, mặt số chải tia Sun-brushed quyến rũ.', 'images/luxury/sp_deville_prestige.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('14', 'BigClock Seamaster Railmaster Steel', '125000000', '95000000', '24', 'Thiết kế mặt số thép chải xước dọc lấy cảm hứng từ những cỗ tàu lửa huyền thoại thế kỷ 20. Khả năng kháng từ trường cực đại 15.000 Gauss bảo vệ bộ máy trong mọi môi trường công nghệ.', 'images/luxury/sp_railmaster_vintage.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('15', 'BigClock Constellation Observatory Gold', '220000000', '175000000', '14', 'Biểu tượng chòm sao với viền demi vàng Sedna 18K và cọc số La Mã khắc laze tinh tế. Nắp đáy chạm nổi huy hiệu Đài thiên văn Geneva được bao quanh bởi 8 vì sao tượng trưng cho 8 kỳ tích đo lường.', 'images/luxury/sp_constellation_observatory.png', '1');
INSERT INTO `dongho` (`MaDongHo`, `TenDongHo`, `DonGia`, `GiaNhap`, `SoLuong`, `MoTa`, `HinhAnh`, `TrangThai`) VALUES ('16', 'BigClock Constellation Petit Diamond 28mm', '98000000', '75000000', '28', 'Dành riêng cho quý cô yêu kiều với đường kính 28mm thanh thoát. Mặt số màu phấn pastel dịu ngọt đính 12 viên kim cương tự nhiên thay thế cọc số, bộ máy thạch anh chuẩn xác cao Thụy Sĩ.', 'images/luxury/sp_constellation_quartz28.png', '1');

-- Cấu trúc bảng `hoadon`
CREATE TABLE `hoadon` (
  `MaHoaDon` int(10) NOT NULL AUTO_INCREMENT,
  `MaNguoiDung` int(10) NOT NULL,
  `TenKhachHang` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EmailKhachHang` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DiaChi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SDT_GiaoHang` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NgayLap` datetime NOT NULL,
  `TongTien` decimal(12,0) NOT NULL,
  `HinhQR` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'qrcode.jpg',
  `TrangThaiDonHang` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Chá» duyá»‡t, 1: ÄÃ£ duyá»‡t, 2: Äang giao, 3: HoÃ n thÃ nh, 4: ÄÃ£ há»§y',
  PRIMARY KEY (`MaHoaDon`),
  KEY `fk_hoadon_user` (`MaNguoiDung`),
  CONSTRAINT `fk_hoadon_user` FOREIGN KEY (`MaNguoiDung`) REFERENCES `user` (`MaNguoiDung`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `hoadon`
INSERT INTO `hoadon` (`MaHoaDon`, `MaNguoiDung`, `TenKhachHang`, `EmailKhachHang`, `DiaChi`, `SDT_GiaoHang`, `NgayLap`, `TongTien`, `HinhQR`, `TrangThaiDonHang`) VALUES ('1', '2', 'Trần Sĩ Tấn', 'sitan@gmail.com', '123 Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', '0988 123 456', '2026-11-20 09:30:00', '145000000', 'qrcode.jpg', '3');
INSERT INTO `hoadon` (`MaHoaDon`, `MaNguoiDung`, `TenKhachHang`, `EmailKhachHang`, `DiaChi`, `SDT_GiaoHang`, `NgayLap`, `TongTien`, `HinhQR`, `TrangThaiDonHang`) VALUES ('2', '3', 'Huỳnh Hiền Triết', 'hientriet@gmail.com', '456 Đường Lê Lợi, Quận Hải Châu, TP. Đà Nẵng', '0912 345 678', '2026-11-22 14:15:00', '185000000', 'qrcode.jpg', '3');
INSERT INTO `hoadon` (`MaHoaDon`, `MaNguoiDung`, `TenKhachHang`, `EmailKhachHang`, `DiaChi`, `SDT_GiaoHang`, `NgayLap`, `TongTien`, `HinhQR`, `TrangThaiDonHang`) VALUES ('3', '4', 'Hà Minh Trí', 'minhtri@gmail.com', '789 Đường Cầu Giấy, Quận Cầu Giấy, TP. Hà Nội', '0933 678 999', '2026-11-23 11:20:00', '470000000', 'qrcode.jpg', '2');
INSERT INTO `hoadon` (`MaHoaDon`, `MaNguoiDung`, `TenKhachHang`, `EmailKhachHang`, `DiaChi`, `SDT_GiaoHang`, `NgayLap`, `TongTien`, `HinhQR`, `TrangThaiDonHang`) VALUES ('4', '2', 'Võ Đức Thịnh', 'ducthinh@gmail.com', '123 Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', '0988 123 456', '2026-11-25 16:45:00', '295000000', 'qrcode.jpg', '1');
INSERT INTO `hoadon` (`MaHoaDon`, `MaNguoiDung`, `TenKhachHang`, `EmailKhachHang`, `DiaChi`, `SDT_GiaoHang`, `NgayLap`, `TongTien`, `HinhQR`, `TrangThaiDonHang`) VALUES ('5', '3', 'Trần Sĩ Tấn', 'sitan@gmail.com', '456 Đường Lê Lợi, Quận Hải Châu, TP. Đà Nẵng', '0912 345 678', '2026-11-26 10:00:00', '390000000', 'qrcode.jpg', '0');
INSERT INTO `hoadon` (`MaHoaDon`, `MaNguoiDung`, `TenKhachHang`, `EmailKhachHang`, `DiaChi`, `SDT_GiaoHang`, `NgayLap`, `TongTien`, `HinhQR`, `TrangThaiDonHang`) VALUES ('6', '1', NULL, NULL, 'Số 88 Phố Đồng Khởi, Quận 1, TP. Hồ Chí Minh', '1900 8899', '2026-11-26 10:14:05', '115000000', 'qrcode.jpg', '0');
INSERT INTO `hoadon` (`MaHoaDon`, `MaNguoiDung`, `TenKhachHang`, `EmailKhachHang`, `DiaChi`, `SDT_GiaoHang`, `NgayLap`, `TongTien`, `HinhQR`, `TrangThaiDonHang`) VALUES ('7', '1', NULL, NULL, 'Số 88 Phố Đồng Khởi, Quận 1, TP. Hồ Chí Minh', '1900 8899', '2026-11-26 12:43:41', '125000000', 'qrcode.jpg', '0');

-- Cấu trúc bảng `chitiethoadon`
CREATE TABLE `chitiethoadon` (
  `MaChiTiet` int(10) NOT NULL AUTO_INCREMENT,
  `MaHoaDon` int(10) NOT NULL,
  `MaDongHo` int(10) NOT NULL,
  `SoLuong` int(10) NOT NULL,
  `DonGia` decimal(12,0) NOT NULL,
  PRIMARY KEY (`MaChiTiet`),
  KEY `fk_cthd_hoadon` (`MaHoaDon`),
  KEY `fk_cthd_dongho` (`MaDongHo`),
  CONSTRAINT `fk_cthd_dongho` FOREIGN KEY (`MaDongHo`) REFERENCES `dongho` (`MaDongHo`) ON DELETE CASCADE,
  CONSTRAINT `fk_cthd_hoadon` FOREIGN KEY (`MaHoaDon`) REFERENCES `hoadon` (`MaHoaDon`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu bảng `chitiethoadon`
INSERT INTO `chitiethoadon` (`MaChiTiet`, `MaHoaDon`, `MaDongHo`, `SoLuong`, `DonGia`) VALUES ('1', '1', '1', '1', '145000000');
INSERT INTO `chitiethoadon` (`MaChiTiet`, `MaHoaDon`, `MaDongHo`, `SoLuong`, `DonGia`) VALUES ('2', '2', '2', '1', '185000000');
INSERT INTO `chitiethoadon` (`MaChiTiet`, `MaHoaDon`, `MaDongHo`, `SoLuong`, `DonGia`) VALUES ('3', '3', '4', '1', '175000000');
INSERT INTO `chitiethoadon` (`MaChiTiet`, `MaHoaDon`, `MaDongHo`, `SoLuong`, `DonGia`) VALUES ('4', '3', '3', '1', '295000000');
INSERT INTO `chitiethoadon` (`MaChiTiet`, `MaHoaDon`, `MaDongHo`, `SoLuong`, `DonGia`) VALUES ('5', '4', '3', '1', '295000000');
INSERT INTO `chitiethoadon` (`MaChiTiet`, `MaHoaDon`, `MaDongHo`, `SoLuong`, `DonGia`) VALUES ('6', '5', '6', '1', '390000000');
INSERT INTO `chitiethoadon` (`MaChiTiet`, `MaHoaDon`, `MaDongHo`, `SoLuong`, `DonGia`) VALUES ('7', '6', '5', '1', '115000000');
INSERT INTO `chitiethoadon` (`MaChiTiet`, `MaHoaDon`, `MaDongHo`, `SoLuong`, `DonGia`) VALUES ('8', '7', '14', '1', '125000000');

