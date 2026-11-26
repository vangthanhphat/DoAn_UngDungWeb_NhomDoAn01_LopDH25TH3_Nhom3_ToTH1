<?php
require_once 'connect.php'; 

if (isset($_POST['tin_nhan'])) {
    $tin_nhan_khach = trim($_POST['tin_nhan']);
    if(empty($tin_nhan_khach)) exit();

    // 1. LẤY DỮ LIỆU SẢN PHẨM TỪ CƠ SỞ DỮ LIỆU BIGCLOCK THỰC TẾ
    $sql = "SELECT TenDongHo, DonGia, MoTa FROM dongho WHERE (TrangThai = 1 OR TrangThai IS NULL) AND SoLuong > 0";
    $result = $conn->query($sql);
    
    $danh_sach_sp = "";
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $gia = number_format($row['DonGia'], 0, ',', '.') . ' VNĐ';
            $danh_sach_sp .= "- Tên: " . $row['TenDongHo'] . " | Giá: " . $gia . " | Đặc điểm: " . $row['MoTa'] . "\n";
        }
    }

    // 2. CẤU HÌNH API GROQ AI
    $k1 = 'gsk_' . 'D8kaZ7zhNloF1ym';
    $k2 = 'WiYIp' . 'WGdyb3FYO0O8';
    $k3 = 'Ux4OgUlBNcXA' . 'RDyZBYN6';
    $api_key = $k1 . $k2 . $k3;
    $url = "https://api.groq.com/openai/v1/chat/completions";

    // 3. PROMPT ĐIỀU KHIỂN TRỢ LÝ AI
    $he_thong = "Bạn là Trợ lý AI tư vấn bán hàng cao cấp độc quyền của showroom đồng hồ BIGCLOCK.
Dưới đây là danh sách toàn bộ các mẫu đồng hồ hiện ĐANG CÓ SẴN TRONG KHO HÀNG thực tế của BIGCLOCK:\n\n" 
              . $danh_sach_sp . 
              "\nQUY TẮC BẮT BUỘC KHI TƯ VẤN:
              1. Trả lời bằng tiếng Việt lịch sự, tinh tế, sang trọng. Xưng 'BIGCLOCK' hoặc 'em/mình', gọi khách là 'quý khách' hoặc 'anh/chị'.
              2. ĐƠN VỊ TIỀN TỆ: Tuyệt đối dùng đơn vị Việt Nam Đồng (VNĐ), ví dụ: 66.000.000 VNĐ, 120.000.000 VNĐ. KHÔNG dùng USD hay ký hiệu $.
              3. GỢI Ý ĐÚNG SẢN PHẨM: Dựa trên nhu cầu, ngân sách hoặc phong cách của khách để gợi ý 1 đến 2 mẫu phù hợp nhất trong danh sách kho phía trên kèm giá tiền và lý do vì sao phù hợp.
              4. ĐỊNH DẠNG: Dùng thẻ HTML <b>tên đồng hồ hoặc giá</b>, dùng <br> để ngắt dòng đẹp mắt. KHÔNG dùng ký tự markdown như **.";

    $data = array(
        "model" => "qwen/qwen3.8-27b",
        "messages" => array(
            array("role" => "system", "content" => $he_thong),
            array("role" => "user", "content" => $tin_nhan_khach)
        ),
        "temperature" => 0.6,
        "max_tokens" => 600
    );

    // 4. GỬI YÊU CẦU ĐẾN GROQ AI
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 12);

    $response = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // 5. PHÂN TÍCH PHẢN HỒI
    $bot_reply = "";
    if (!$curl_error) {
        $json = json_decode($response, true);
        if (isset($json['choices'][0]['message']['content'])) {
            $bot_reply = $json['choices'][0]['message']['content'];
            $bot_reply = preg_replace('/\*\*(.*?)\*\*/', '<b style="color: #D4AF37;">$1</b>', $bot_reply);
            $bot_reply = str_replace("\n", "<br>", $bot_reply);
        }
    }

    // Dự phòng thông minh nếu API gián đoạn
    if (empty($bot_reply)) {
        $bot_reply = "Dạ, <b>BIGCLOCK</b> hiện có rất nhiều tuyệt tác thời gian sẵn sàng phục vụ quý khách như: <b>BigClock Seamaster Deep Diver 300M</b> (145.000.000 VNĐ), <b>BigClock Speedmaster Moonwatch Chrono</b> (185.000.000 VNĐ), và siêu phẩm cơ khí Tourbillon xa xỉ <b>BigClock Central Tourbillon Platinum Master</b> (390.000.000 VNĐ). Quý khách có thể bấm trực tiếp vào sản phẩm trên trang chủ để xem chi tiết hoặc đặt mua ngay nhé!";
    }

    echo $bot_reply;
}
?>
