# HỆ THỐNG THƯƠNG MẠI ĐIỆN TỬ SHOWROOM ĐỒNG HỒ CAO CẤP BIGCLOCK

> **Học phần:** Phát triển ứng dụng web  
> **Khoa:** Công Nghệ Thông Tin – Trường Đại học An Giang (ĐHQG TP.HCM)  
> **Giảng viên hướng dẫn:** ThS. Thiều Thanh Quang Phú  

---

## 👨‍💻 THÔNG TIN NHÓM SINH VIÊN THỰC HIỆN

* **Sinh viên 1:** Văng Thành Phát — **MSSV:** `DTH245849` — **Lớp:** `DH25TH3`
* **Sinh viên 2:** Nguyễn Hoàng Thiện — **MSSV:** `DTH245892` — **Lớp:** `DH25TH3`
* **Nhóm Đồ Án:** Nhóm 03 — **Tổ thực hành:** Tổ 01
* **Repository:** [https://github.com/vangthanhphat/DoAn_UngDungWeb_NhomDoAn01_LopDH25TH3_Nhom3_ToTH1](https://github.com/vangthanhphat/DoAn_UngDungWeb_NhomDoAn01_LopDH25TH3_Nhom3_ToTH1)

---

## 1. GIỚI THIỆU DỰ ÁN
* **Tên thương hiệu:** **BIGCLOCK - SWISS LUXURY TIMEPIECE**
* **Slogan:** *Tinh hoa thời gian - Đẳng cấp vĩnh cửu*
* **Chủ đề:** Website Thương mại điện tử Bán & Giới thiệu Đồng Hồ Cao Cấp Thụy Sĩ & Nhật Bản.
* **Phong cách giao diện:** **Dark Luxury Theme** (Đen huyền bí kết hợp Vàng Gold hoàng gia, typography *Plus Jakarta Sans* & *Cinzel*).
* **Công nghệ phát triển:**
  * **Back-end:** PHP thuần kết nối `mysqli` với Prepared Statements chống SQL Injection.
  * **Cơ sở dữ liệu:** MySQL / MariaDB (`bigclock_db` chuẩn utf8mb4, tự động nhận diện cả Vertrigo và XAMPP).
  * **Front-end:** HTML5, CSS3 Luxury, JavaScript ES6 (Live Search AJAX, Popup Modal xem nhanh, Toast giỏ hàng ngầm).
  * **Tích hợp AI:** **Chatbot AI tư vấn bán hàng** thông minh kết nối Groq API (Mô hình LLaMA 3.3 / Qwen), tự động đọc dữ liệu đồng hồ trong kho để tư vấn cho khách hàng bằng tiếng Việt và đơn vị VNĐ.
  * **Thanh toán & Hóa đơn:** Tích hợp VietQR chuyển khoản tự động, In hóa đơn điện tử VAT và Xuất báo cáo Excel.

---

## 2. CẤU TRÚC THƯ MỤC DỰ ÁN
```text
bigclock/
├── css/
│   └── index.css                      # Giao diện Dark Luxury dùng chung toàn website
├── images/                            # Hình ảnh logo, banner, đồng hồ, video chế tác, mã VietQR
│   ├── products/                      # Ảnh sản phẩm đồng hồ
│   ├── luxury/                        # Ảnh chụp nghệ thuật 16 mẫu Thụy Sĩ
│   └── VD.mp4                         # Video thủ công chế tác
├── uploads/                           # Lưu trữ ảnh khi Quản trị viên thêm đồng hồ mới
│
├── connect.php                        # Kết nối CSDL thông minh (Tự nhận diện pass XAMPP & Vertrigo)
├── database.sql                       # Tệp CSDL 4 bảng quan hệ chuẩn hóa kèm dữ liệu mẫu
├── index.php                          # Trang chủ chính thức (Hero video, Lọc, Tìm kiếm)
├── index_top.php                      # Hero banner video, giới thiệu danh mục
├── index_Products.php                 # Lưới sản phẩm, ô Live Search AJAX, Modal xem nhanh
├── index_bottom.php                   # Di sản thương hiệu, Đánh giá khách hàng, Boutique showroom
├── header.php                         # Header & Thanh điều hướng Topbar
├── footer.php                         # Footer thông tin, Bản quyền và Chatbot AI góc màn hình
│
├── chitiet.php                        # Trang chi tiết sản phẩm đầy đủ thông số kỹ thuật
├── xuly_timkiem_ajax.php              # API tìm kiếm gợi ý tức thì khi gõ từ khóa
├── xuly_bot.php                       # API Chatbot AI tư vấn sản phẩm (Groq API)
├── themgiohang.php                    # Xử lý thêm vào giỏ hàng (Hỗ trợ cả POST và AJAX ngầm)
├── giohang.php                        # Quản lý giỏ hàng (Xem, cập nhật số lượng, xóa món, xóa hết)
├── xoagiohang.php                     # Xóa sản phẩm khỏi giỏ hàng
├── muahang.php                        # Xác nhận đặt hàng (Thông tin người nhận, SĐT, Địa chỉ, COD/QR)
├── thanhtoan_qr.php                   # Quét mã VietQR và xác nhận ĐÃ THANH TOÁN (trừ kho & xóa session)
├── camon.php                          # Trang cảm ơn sau thanh toán kèm nút xem hóa đơn VAT & phiếu giao
├── donhang_khach.php                  # Khách hàng xem lịch sử đơn đã đặt, in VAT và nút Hủy đơn
├── huy_donhang.php                    # Hủy đơn hàng và TỰ ĐỘNG HOÀN TRẢ SỐ LƯỢNG VÀO TỒN KHO
├── hoadon_vat.php                     # Hóa đơn điện tử GTGT (VAT) chuẩn in ấn kèm chữ ký số
├── bienlai.php                        # Phiếu giao hàng khổ A6 kèm mã vạch Barcode
├── DangNhap.php / DangNhapXuLy.php    # Đăng nhập hệ thống (Phân luồng Admin / Khách)
├── DangKy.php / DangKyXuLy.php        # Đăng ký tài khoản thành viên mới
├── DangXuat.php                       # Đăng xuất an toàn
├── ho-so.php                          # Xem và cập nhật hồ sơ cá nhân, đổi mật khẩu
│
├── quanly.php                         # Bảng điều khiển Quản trị (Dashboard 4 widget thống kê)
├── header_admin.php                   # Menu Sidebar cố định 260px bên trái dành riêng cho Admin
├── danhsach.php                       # Quản lý danh sách đồng hồ (Ẩn/Hiện, Sửa, Xóa, Lọc)
├── them_sanpham.php                   # Thêm đồng hồ mới (Upload ảnh vào uploads/)
├── sua_sanpham.php                    # Chỉnh sửa giá bán, giá nhập, số lượng tồn kho
├── danhsachhoadon.php                 # Quản lý đơn hàng (Đổi trạng thái, tự động cộng/trừ kho)
├── chitiethoadon.php                  # Xem chi tiết đơn hàng, danh sách sản phẩm và in hóa đơn
├── doanhthu.php                       # Báo cáo Doanh thu & Lợi nhuận thuần (Lọc theo ngày, XUẤT EXCEL .XLS)
├── quanlytaikhoan.php                 # Quản lý danh sách người dùng, xóa thành viên
└── sua_taikhoan.php                   # Phân quyền Admin / Khách hàng, Reset mật khẩu
```

---

## 3. HƯỚNG DẪN CÀI ĐẶT & CHẠY ỨNG DỤNG

### Chạy trên XAMPP (Khuyên dùng)
1. Copy thư mục `bigclock` vào thư mục `C:\xampp\htdocs\bigclock`.
2. Mở **XAMPP Control Panel**, nhấn **Start** cho cả **Apache** và **MySQL**.
3. Mở trình duyệt vào phpMyAdmin: `http://localhost/phpmyadmin/`.
4. Bấm vào tab **Import** -> Chọn file `database.sql` trong thư mục đồ án -> Bấm **Import** (hoặc **Go**).
5. Truy cập website: `http://localhost/bigclock/` (hoặc `http://localhost:8080/bigclock/`).

### Chạy trên Vertrigo Server
1. Copy thư mục `bigclock` vào `C:\Program Files\VertrigoServ\www\bigclock`.
2. Mở phpMyAdmin: `http://localhost/phpmyadmin/` (User: `root`, Password: `vertrigo`).
3. Import tệp `database.sql`.
4. Truy cập website: `http://localhost/bigclock/`.

---

## 4. TÀI KHOẢN MẪU ĐĂNG NHẬP KIỂM THỬ

| Họ và Tên | MSSV | Tên đăng nhập | Mật khẩu | Quyền hạn | Mục đích sử dụng |
|---|---|---|---|---|---|
| **Văng Thành Phát** | `DTH245849` | **admin** | **123456** | **Quản Trị Viên (Admin)** | Toàn quyền quản trị Dashboard, Sản phẩm, Đơn hàng, Doanh thu |
| **Nguyễn Hoàng Thiện** | `DTH245892` | **khachhang** | **123456** | **Khách hàng VIP** | Mua hàng, xem lịch sử đơn, in hóa đơn điện tử VAT |
| **Tài khoản test 1** | `Khách 1` | **minhanh** | **123456** | **Khách hàng** | Tài khoản khách hàng thành viên kiểm thử |
| **Tài khoản test 2** | `Khách 2` | **giaphu** | **123456** | **Khách hàng** | Tài khoản khách hàng thành viên kiểm thử |

---

## 5. NHỮNG TÍNH NĂNG NỔI BẬT
1. 💎 **Giao diện Dark Luxury Đẳng Cấp:** Tông màu Đen - Vàng Gold hoàng gia, video chuyển động ở banner chính, hiệu ứng hover phóng to ảnh, typography chuyên nghiệp.
2. 🤖 **Chatbot AI Tư Vấn Bán Hàng (Groq LLaMA 3.3):** Bot AI tự động đọc dữ liệu trong database để trả lời trực tiếp cho khách hàng ngay trên giao diện web bằng tiếng Việt và đơn vị VNĐ.
3. ⚡ **Live Search AJAX Siêu Nhanh:** Gợi ý tên và hình ảnh đồng hồ tức thì khi gõ phím mà không cần tải lại trang.
4. 📱 **Popup Modal Xem Nhanh:** Khách hàng click vào bất kỳ sản phẩm nào sẽ mở popup xem thông số, số lượng tồn kho và chọn "Mua ngay" hoặc "Thêm vào giỏ".
5. 📊 **Báo Cáo Doanh Thu & Xuất File Excel (.xls):** Quản trị viên lọc theo khoảng ngày, tính toán doanh thu, giá vốn và lợi nhuận thuần, sau đó tải về file Excel chỉ với 1 click.
6. 🧾 **Hóa Đơn Điện Tử VAT & Phiếu Giao A6:** Thiết kế chuẩn hóa đơn điện tử đỏ có mã tra cứu và dấu mộc chữ ký số điện tử.
7. 🔄 **Cơ Chế Tự Động Hoàn Tồn Kho:** Khi khách hàng hoặc Admin hủy đơn hàng, hệ thống tự động cộng ngược số lượng sản phẩm về lại kho hàng; khi duyệt lại sẽ tự trừ kho, chống thất thoát hàng hóa.
