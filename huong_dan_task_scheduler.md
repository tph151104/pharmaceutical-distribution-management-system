# Hướng Dẫn Cài Đặt Windows Task Scheduler Cho Hệ Thống Tự Động

## Mục đích
Cài đặt để Windows tự động gọi Laravel chạy các Bot mỗi 12 tiếng:
- **Bot 1:** `inventory:check-expired` – Quét và cách ly thuốc hết hạn sang KV05.
- **Bot 2:** `order:auto-complete` – Tự hoàn thành đơn hàng đã giao > 3 ngày.

---

## Các Bước Thực Hiện

### Bước 1: Mở Task Scheduler
- Gõ **"Task Scheduler"** vào thanh Search của Windows → Mở ứng dụng.

### Bước 2: Tạo Task Mới
- Bấm **Create Basic Task** ở menu bên phải.
- **Name:** Gõ `Laravel Scheduler`.
- Bấm **Next >**.

### Bước 3: Thiết Lập Trigger (Lịch chạy)
- Chọn **Daily** → Bấm **Next >**.
- **Start:** Chọn ngày hôm nay.
- **Giờ:** Đặt `12:00:00 SA` (tức 00:00 nửa đêm).
- **Recur every:** `1` days.
- Bấm **Next >**.

### Bước 4: Thiết Lập Action (Hành động)
- Chọn **Start a program** → Bấm **Next >**.
- Điền 3 ô như sau:

| Ô | Giá trị |
|---|---------|
| **Program/script** | `E:\wamp\bin\php\php8.2.29\php.exe` |
| **Add arguments** | `artisan schedule:run` |
| **Start in** | `E:\wamp\www\TTCK-WebKhoThuocTay\Kho-Thuoc-Tay` |

- Bấm **Next >**.

### Bước 5: Hoàn Tất & Mở Properties
- Tick ô **Open the Properties dialog for this task when I click Finish**.
- Bấm **Finish**.

### Bước 6: Chỉnh Properties

#### Tab General:
- Tick ô **Hidden** (góc dưới bên trái) → Ẩn cửa sổ đen khi Bot chạy.

#### Tab Triggers:
- Bấm đúp vào dòng trigger trong danh sách.
- Kéo xuống dưới cùng, tick ô **Repeat task every** → chọn hoặc gõ **`12 hours`**.
- Ô **for a duration of** → chọn **Indefinitely**.
- Bấm **OK**.

#### Tab Actions:
- Kiểm tra lại 3 ô đã điền đúng chưa (Program, Arguments, Start in).
- Nếu thiếu thì bấm **Edit** để sửa.

### Bước 7: Lưu
- Bấm **OK** ở cửa sổ Properties để lưu toàn bộ.

---

## Kết Quả Sau Khi Cài Đặt

| Thời điểm | Bot chạy |
|---|---|
| **00:00** (nửa đêm) | Quét thuốc hết hạn + Chốt đơn hàng |
| **12:00** (trưa) | Quét thuốc hết hạn + Chốt đơn hàng |

---

## Lệnh Chạy Tay (Khi Cần Xử Lý Gấp)

```bash
# Quét thuốc hết hạn ngay lập tức
php artisan inventory:check-expired

# Chốt hoàn thành đơn hàng ngay lập tức
php artisan order:auto-complete
```

---

## Lưu Ý Quan Trọng

> [!WARNING]
> Task Scheduler chỉ hoạt động khi **máy tính đang bật**. Nếu máy tắt lúc 00:00 thì Bot sẽ không chạy.

> [!NOTE]
> Trong môi trường thật (Production/Server Linux), thay vì dùng Task Scheduler, bạn sẽ dùng **Crontab** với lệnh: `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`
