# Hệ Thống Thống Kê Click Analytics

Hệ thống này theo dõi lưu lượng người truy cập, thời gian người dùng ở lại và số lần click trên website.

## 🚀 Cài Đặt

### 1. Chạy Migration để tạo bảng

```bash
php artisan migrate
```

Điều này sẽ tạo hai bảng:
- `click_analytics` - Lưu trữ mỗi lần click của người dùng
- `user_sessions` - Lưu trữ thông tin session của người dùng

### 2. Xác Nhận Các Tệp Đã Được Tạo

Các tệp sau đã được tạo/cập nhật:

**Models:**
- `app/Models/ClickAnalytic.php` - Model cho bảng click_analytics
- `app/Models/UserSession.php` - Model cho bảng user_sessions

**Controllers:**
- `app/Http/Controllers/AnalyticsController.php` - API controller cho analytics

**Migrations:**
- `database/migrations/2026_04_19_000001_create_click_analytics_table.php`
- `database/migrations/2026_04_19_000002_create_user_sessions_table.php`

**Views:**
- `resources/views/Admin/admin.blade.php` - Đã cập nhật với 3 thẻ hiển thị thống kê

**Routes:**
- `routes/api.php` - Đã thêm 5 route mới cho analytics

**Frontend:**
- `public/js/analytics-tracking.js` - Script theo dõi người dùng
- `resources/views/welcome.blade.php` - Cập nhật với CSRF token và script tracking

## 📊 Cách Sử Dụng

### Trên Trang Admin

Vào trang admin, bạn sẽ thấy ba thẻ thống kê hôm nay:

1. **Tổng Lượt Truy Cập** - Số lượng người truy cập trong ngày
2. **Tổng Thời Gian (Phút)** - Tổng thời gian người dùng ở lại trên website
3. **Tổng Số Click** - Tổng số lần click trong ngày

### Theo Dõi Click Trên Frontend

Để theo dõi click trên các bài viết hoặc sản phẩm, thêm thuộc tính `data-article-id` hoặc `data-product-id` vào các liên kết:

**Cho bài viết:**
```html
<a href="{{ route('article.show', $article->id) }}" data-article-id="{{ $article->id }}">
    {{ $article->title }}
</a>
```

**Cho sản phẩm:**
```html
<a href="{{ route('product.show', $product->id) }}" data-product-id="{{ $product->id }}">
    {{ $product->name }}
</a>
```

**Cho các loại khác:**
```html
<a href="#" class="track-click" data-track-type="custom" data-track-id="123">
    Click me
</a>
```

## 🔄 API Endpoints

Tất cả các endpoint đều ở đường dẫn `/api/analytics/`:

### 1. Bắt Đầu Session
```
POST /api/analytics/start-session
```
Gọi tự động khi trang tải. Tạo một session mới cho người dùng.

### 2. Ghi Lại Click
```
POST /api/analytics/record-click
Body:
{
    "item_type": "article",  // "article" hoặc "product"
    "item_id": 1,
    "page_url": "https://example.com"
}
```

### 3. Kết Thúc Session
```
POST /api/analytics/end-session
Body:
{
    "session_id": "...",
    "duration_seconds": 300,
    "total_clicks": 5
}
```

### 4. Lấy Thống Kê Hôm Nay
```
GET /api/analytics/daily-stats
```
Trả về: tổng truy cập, tổng thời gian, tổng click, click theo loại

### 5. Lấy Thống Kê Theo Kỳ
```
GET /api/analytics/stats?period=day
```
Các giá trị period: `day`, `week`, `month`, `year`

## 📈 Cấu Trúc Dữ Liệu

### Bảng `click_analytics`
```
- id
- user_id (nullable)
- ip_address
- item_type (article | product)
- item_id
- page_url
- user_agent
- clicked_at (timestamp)
- created_at
- updated_at
```

### Bảng `user_sessions`
```
- id
- user_id (nullable)
- session_id (unique)
- ip_address
- user_agent
- started_at (timestamp)
- ended_at (timestamp, nullable)
- duration_seconds (int)
- total_clicks (int)
- created_at
- updated_at
```

## 🛠️ Tùy Chỉnh

### Thay Đổi Tần Suất Cập Nhật Thống Kê

Trong `resources/views/Admin/admin.blade.php`, tìm dòng:
```javascript
setInterval(loadDailyStats, 30000); // 30 giây
```

Thay `30000` bằng giá trị mới (tính bằng mili giây)

### Thêm Tracking Cho Các Loại Khác

Sửa `public/js/analytics-tracking.js` và thêm vào hàm `attachClickListeners`:
```javascript
document.querySelectorAll('[data-custom-id]').forEach(el => {
    el.addEventListener('click', (e) => {
        const customId = el.getAttribute('data-custom-id');
        this.recordClick('custom_type', customId);
    });
});
```

## 🔐 Bảo Mật

- Tất cả các request API được bảo vệ bằng CSRF token
- IP address của người dùng được lưu trữ
- User agent được lưu trữ để phân tích trình duyệt

## 📝 Ghi Chú

- Session tự động được lưu khi người dùng rời trang
- Thời gian ở lại được tính bằng giây
- Dữ liệu được lưu với timezone của server
- API endpoint không có middleware auth, có thể thêm nếu cần

## 🐛 Khắc Phục Sự Cố

### Thống kê không cập nhật
1. Kiểm tra console của browser để xem có lỗi gì không
2. Kiểm tra xem CSRF token có được gửi đúng không
3. Kiểm tra `php artisan migrate` đã chạy thành công chưa

### Click không được ghi lại
1. Kiểm tra xem element có `data-article-id` hoặc `data-product-id` không
2. Kiểm tra console browser
3. Kiểm tra network tab xem request có gửi đi không

### Không có dữ liệu trong database
1. Kiểm tra permission của database
2. Kiểm tra migrations đã chạy đúng
3. Xem log file của Laravel: `storage/logs/laravel.log`
