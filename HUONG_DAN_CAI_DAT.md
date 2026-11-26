# ĐỒ ÁN PHÁT TRIỂN ỨNG DỤNG WEB: CỬA HÀNG ĐỒNG HỒ CAO CẤP BIGCLOCK

## 1. GIỚI THIỆU DỰ ÁN
* **Tên thương hiệu:** **BIGCLOCK - SWISS & JAPANESE LUXURY TIMEPIECE**
* **Slogan:** *Tinh hoa thời gian - Đẳng cấp vĩnh cửu*
* **Chủ đề:** Website Thương mại điện tử Bán & Giới thiệu Đồng Hồ Cao Cấp Thụy Sĩ & Nhật Bản.
* **Phong cách giao diện:** **Dark Luxury Theme** (Đen huyền bí kết hợp Vàng Gold hoàng gia, typography *Playfair Display* & *Montserrat*).
* **Công nghệ phát triển:**
  * **Back-end:** PHP thuần kết nối `mysqli` với Prepared Statements chống SQL Injection.
  * **Cơ sở dữ liệu:** MySQL / MariaDB (Tự động nhận diện mật khẩu Vertrigo `vertrigo` và XAMPP mật khẩu rỗng).
  * **Front-end:** HTML5, CSS3 Luxury, JavaScript ES6 (Live Search AJAX, Popup Modal xem nhanh, Toast giỏ hàng ngầm).
  * **Tích hợp AI:** **Chatbot AI tư vấn bán hàng** thông minh kết nối Groq API (Mô hình LLaMA 3.3 70B Versatile), tự động đọc dữ liệu đồng hồ trong kho để tư vấn cho khách hàng.

---

## 2. CẤU TRÚC THƯ MỤC DỰ ÁN
```text
D:\do an web\bigclock\
├── css/
│   ├── index.css                  # Giao diện Dark Luxury khách hàng
│   ├── dangnhap.css               # Giao diện trang Đăng nhập
│   └── dangky1.css                # Giao diện trang Đăng ký
├── images/
│   ├── products/                  # Hình ảnh các mẫu đồng hồ cao cấp
│   ├── logo.png                   # Logo thương hiệu BIGCLOCK
│   ├── Banner1.png                # Banner nền trang chủ
│   ├── VD.mp4                     # Video nghệ thuật chế tác ở Hero Banner
│   ├── qrcode.jpg                 # Mã VietQR chuyển khoản ngân hàng
│   └── chukyso.png                # Dấu mộc chữ ký số điện tử trên Hóa đơn VAT
├── uploads/                       # Thư mục lưu ảnh khi Admin thêm/sửa sản phẩm
│
├── connect.php                    # Kết nối CSDL thông minh (Tương thích Vertrigo & XAMPP)
├── database.sql                   # Tệp CSDL 4 bảng quan hệ chuẩn hóa kèm dữ liệu mẫu
├── index.php                      # Trang chủ chính thức (Hero video, Lọc, Tìm kiếm, Phân trang)
├── index_top.php                  # Hero banner video, Giới thiệu các bộ sưu tập
├── index_Products.php             # Lưới sản phẩm, ô Live Search AJAX, Popup Modal chi tiết
├── index_bottom.php              # Di sản thương hiệu, Đánh giá khách hàng, Tin tức, Bản đồ
├── header.php                     # Header & Thanh điều hướng Topbar, News marquee ticker
├── footer.php                     # Footer thông tin, Bản quyền và Chatbot AI góc màn hình
│
├── xuly_timkiem_ajax.php          # API tìm kiếm gợi ý tức thì khi gõ từ khóa
├── xuly_bot.php                   # API Chatbot AI tư vấn sản phẩm (Groq LLaMA 3.3)
├── themgiohang.php                # Xử lý thêm vào giỏ hàng (Hỗ trợ cả POST và AJAX ngầm)
├── giohang.php                    # Quản lý giỏ hàng (Xem, cập nhật số lượng, xóa món, xóa hết)
├── xoagiohang.php                 # Xóa sản phẩm khỏi giỏ hàng
├── muahang.php                    # Xác nhận đặt hàng (Thông tin người nhận, SĐT, Địa chỉ, COD/QR)
├── camon.php                      # Trang đặt hàng thành công kèm mã VietQR chuyển khoản
├── donhang_khach.php              # Khách hàng xem lịch sử đơn đã đặt, nút xem VAT và nút Hủy đơn
├── huy_donhang.php                # Hủy đơn hàng và TỰ ĐỘNG HOÀN TRẢ SỐ LƯỢNG VÀO TỒN KHO
├── hoadon_vat.php                 # Hóa đơn điện tử GTGT (VAT) chuẩn in ấn kèm chữ ký số
├── bienlai.php                    # Phiếu giao hàng khổ A6 kèm mã vạch Barcode
├── DangNhap.php / DangNhapXuLy.php# Đăng nhập hệ thống (Phân luồng Admin / Khách)
├── DangKy.php / DangKyXuLy.php    # Đăng ký tài khoản thành viên mới
├── DangXuat.php                   # Đăng xuất an toàn
├── ho-so.php                      # Xem và cập nhật hồ sơ cá nhân, đổi mật khẩu
│
├── quanly.php                     # Bảng điều khiển Quản trị (Dashboard 4 widget thống kê)
├── header_admin.php               # Menu Sidebar cố định 260px bên trái dành riêng cho Admin
├── danhsach.php                   # Quản lý danh sách đồng hồ (Ẩn/Hiện, Sửa, Xóa, Lọc)
├── them_sanpham.php               # Thêm đồng hồ mới (Upload ảnh vào uploads/)
├── sua_sanpham.php                # Chỉnh sửa giá bán, giá nhập, số lượng tồn kho
├── danhsachhoadon.php             # Quản lý đơn hàng (Đổi trạng thái: Chờ duyệt, Duyệt, Giao, Hoàn thành, Hủy - Tự động cộng/trừ kho)
├── chitiethoadon.php              # Xem chi tiết đơn hàng, danh sách sản phẩm và in hóa đơn
├── doanhthu.php                   # Báo cáo Doanh thu & Lợi nhuận thuần (Lọc theo ngày, XUẤT EXCEL .XLS)
├── quanlytaikhoan.php             # Quản lý danh sách người dùng, xóa thành viên
└── sua_taikhoan.php               # Phân quyền Admin / Khách hàng, Reset mật khẩu
```

---

## 3. HƯỚNG DẪN CÀI ĐẶT & CHẠY ỨNG DỤNG

### Cách 1: Chạy trên Vertrigo Server
1. Mở thư mục cài đặt Vertrigo Server (thường là `C:\Program Files\VertrigoServ\www\` hoặc ổ đĩa bạn cài đặt).
2. Tạo một Shortcut hoặc copy thư mục `bigclock` vào `www`:
   * Đường dẫn web sẽ là: `http://localhost/bigclock/`
3. Mở phpMyAdmin: `http://localhost/phpmyadmin/` (User: `root`, Pass: `vertrigo`).
4. Nhập (Import) tệp `database.sql` nằm trong thư mục `D:\do an web\bigclock\database.sql`.

### Cách 2: Chạy trên XAMPP
1. Mở thư mục `C:\xampp\htdocs\`.
2. Copy thư mục `bigclock` vào `C:\xampp\htdocs\bigclock`.
3. Mở **XAMPP Control Panel**, bấm **Start** cho cả Apache và MySQL.
4. Mở trình duyệt vào phpMyAdmin: `http://localhost/phpmyadmin/` (User: `root`, Pass để trống).
5. Bấm vào tab **Import** -> Chọn file `database.sql` -> Bấm **Go**.

---

## 4. TÀI KHOẢN MẪU & THÀNH VIÊN ĐỒ ÁN

| Họ và Tên | Tên đăng nhập | Mật khẩu | Quyền hạn | Mục đích sử dụng |
|---|---|---|---|---|
| **Văng Thành Phát** | **admin** | **123456** *(hoặc `admin`)* | **Quản Trị Viên (Admin)** | Toàn quyền quản trị Dashboard, Sản phẩm, Đơn hàng, Doanh thu |
| **Nguyễn Hoàng Thiện** | **khachhang** | **123456** | **Khách hàng VIP** | Mua hàng, xem lịch sử đơn, in hóa đơn điện tử VAT |
| **Huỳnh Minh Anh** | **minhanh** | **123456** | **Khách hàng** | Tài khoản khách hàng thành viên kiểm thử |
| **Lê Gia Phú** | **hoangnam** | **123456** | **Khách hàng** | Tài khoản khách hàng thành viên kiểm thử |

---

## 5. NHỮNG ĐIỂM SÁNG & TÍNH NĂNG ĐẠT ĐIỂM TỐI ĐA
1. 💎 **Giao diện Dark Luxury Đẳng Cấp:** Tông màu Đen - Vàng Gold hoàng gia, video chuyển động ở banner chính, hiệu ứng hover phóng to ảnh, typography chuyên nghiệp.
2. 🤖 **Chatbot AI Tư Vấn Bán Hàng (Groq LLaMA 3.3):** Bot AI tự động đọc dữ liệu trong database để trả lời trực tiếp cho khách hàng ngay trên giao diện web.
3. ⚡ **Live Search AJAX Siêu Nhanh:** Gợi ý tên và hình ảnh đồng hồ tức thì khi gõ phím mà không cần tải lại trang.
4. 📱 **Popup Modal Xem Nhanh:** Khách hàng click vào bất kỳ sản phẩm nào sẽ mở popup xem thông số, số lượng tồn kho và chọn "Mua ngay" hoặc "Thêm vào giỏ".
5. 📊 **Báo Cáo Doanh Thu & Xuất File Excel (.xls):** Quản trị viên lọc theo khoảng ngày, tính toán doanh thu, giá vốn và lợi nhuận thuần, sau đó tải về file Excel chỉ với 1 click.
6. 🧾 **Hóa Đơn Điện Tử VAT & Phiếu Giao A6:** Thiết kế chuẩn hóa đơn điện tử đỏ có mã tra cứu và dấu mộc chữ ký số điện tử.
7. 🔄 **Cơ Chế Tự Động Hoàn Tồn Kho:** Khi khách hàng hoặc Admin hủy đơn hàng, hệ thống tự động cộng ngược số lượng sản phẩm về lại kho hàng; khi duyệt lại sẽ tự trừ kho, chống thất thoát hàng hóa.
