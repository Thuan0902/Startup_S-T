# 🚀 Hướng Dẫn Cài Đặt Nhanh - Hệ Thống Analytics

## Bước 1: Tạo Bảng Database

Mở terminal/PowerShell tại thư mục project và chạy:

```bash
php artisan migrate
```

Đợi cho đến khi lệnh hoàn tất. Bạn sẽ thấy các bảng được tạo.

## Bước 2: Kiểm Tra Các Tệp

Tất cả các tệp đã được tạo/cập nhật:

✅ **Models** (trong `app/Models/`):
- `ClickAnalytic.php`
- `UserSession.php`

✅ **Controller** (trong `app/Http/Controllers/`):
- `AnalyticsController.php`

✅ **Routes** (đã cập nhật):
- `routes/api.php` - Thêm 5 route mới

✅ **Views** (đã cập nhật):
- `resources/views/Admin/admin.blade.php` - Thêm 3 thẻ thống kê
- `resources/views/welcome.blade.php` - Thêm CSRF token + script tracking
- `resources/views/Admin/layoutAdmin.blade.php` - Thêm CSRF token

✅ **JavaScript** (trong `public/js/`):
- `analytics-tracking.js` - Tracking script

## Bước 3: Truy Cập Trang Admin

1. Mở trang admin của bạn
2. Bạn sẽ thấy 3 thẻ mới ở trên cùng:
   - **Tổng Lượt Truy Cập**
   - **Tổng Thời Gian (Phút)**
   - **Tổng Số Click**

## Bước 4: Thêm Tracking Vào Links

Để theo dõi click trên bài viết, sửa template và thêm `data-article-id`:

**Trước:**
```html
<a href="{{ route('article.show', $article->id) }}">{{ $article->title }}</a>
```

**Sau:**
```html
<a href="{{ route('article.show', $article->id) }}" data-article-id="{{ $article->id }}">
    {{ $article->title }}
</a>
```

Tương tự cho sản phẩm, thêm `data-product-id`:
```html
<a href="{{ route('product.show', $product->id) }}" data-product-id="{{ $product->id }}">
    {{ $product->name }}
</a>
```

## Bước 5: Test Hệ Thống

1. Mở trang web của bạn
2. Click vào một bài viết hoặc sản phẩm
3. Quay lại trang admin
4. Bạn sẽ thấy số liệu cập nhật

## 📊 Dữ Liệu Được Thu Thập

Mỗi khi người dùng vào website:
- ✅ Session được tạo
- ✅ Mỗi click được ghi lại
- ✅ Thời gian ở lại được tính

Khi người dùng rời đi:
- ✅ Thời gian tổng cộng được lưu
- ✅ Tổng số click được lưu

## 🎯 Dữ Liệu Hiển Thị

Trang admin hiển thị **hôm nay**:
- Số lượng truy cập
- Tổng thời gian (phút)
- Trung bình thời gian/người
- Tổng số click

## 💡 Ghi Chú

- Dữ liệu cập nhật mỗi 30 giây
- Không cần đăng nhập để tracking
- IP address được lưu trữ
- Hỗ trợ cả người dùng đã đăng nhập lẫn khách

## ❓ Có Sự Cố?

**Thống kê không hiển thị:**
- Kiểm tra console (F12) có lỗi không
- Chạy lại `php artisan migrate`
- Kiểm tra `storage/logs/laravel.log`

**Click không được ghi:**
- Kiểm tra xem link có `data-article-id` hoặc `data-product-id` không
- Xem Network tab (F12) để kiểm tra request API

## 📖 Tài Liệu Chi Tiết

Xem file `ANALYTICS_README.md` để biết thêm chi tiết và API documentation.
