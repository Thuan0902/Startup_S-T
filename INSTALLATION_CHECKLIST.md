# ✅ CHECKLIST CÀI ĐẶT

Hãy làm theo các bước này để hoàn thiện cài đặt hệ thống Analytics.

---

## 🟦 BƯỚC 1: CHẠY MIGRATIONS

- [ ] Mở Terminal/PowerShell tại thư mục project
- [ ] Chạy lệnh: `php artisan migrate`
- [ ] Đợi cho đến khi thấy "Migrated" messages
- [ ] Kiểm tra trong phpMyAdmin:
  - [ ] Tìm bảng `click_analytics`
  - [ ] Tìm bảng `user_sessions`

**Nếu gặp lỗi:**
- Kiểm tra `.env` - database name/user/pass đúng chưa
- Chạy `php artisan migrate:fresh` (CẢNH BÁO: Xóa tất cả dữ liệu!)

---

## 🟦 BƯỚC 2: KIỂM TRA CÁC TỆP

- [ ] Kiểm tra các file được tạo tồn tại:
  - [ ] `app/Models/ClickAnalytic.php`
  - [ ] `app/Models/UserSession.php`
  - [ ] `app/Http/Controllers/AnalyticsController.php`
  - [ ] `public/js/analytics-tracking.js`

---

## 🟦 BƯỚC 3: KIỂM TRA ROUTES

- [ ] Chạy `php artisan route:list | grep analytics`
- [ ] Bạn sẽ thấy 5 route:
  - [ ] POST /api/analytics/start-session
  - [ ] POST /api/analytics/record-click
  - [ ] POST /api/analytics/end-session
  - [ ] GET /api/analytics/daily-stats
  - [ ] GET /api/analytics/stats

---

## 🟦 BƯỚC 4: KIỂM TRA GIAO DIỆN ADMIN

- [ ] Mở trang admin: `http://localhost/admin`
- [ ] Bạn sẽ thấy 3 thẻ mới ở trên cùng:
  - [ ] Thẻ "Tổng Lượt Truy Cập" (blue, icon users)
  - [ ] Thẻ "Tổng Thời Gian (Phút)" (cyan, icon clock)
  - [ ] Thẻ "Tổng Số Click" (green, icon click)

**Nếu không thấy:**
- Kiểm tra `resources/views/Admin/admin.blade.php` có code mới không
- Xem console browser (F12) có lỗi gì không

---

## 🟦 BƯỚC 5: THÊM TRACKING VÀO LINKS

Tìm các file template hiển thị bài viết/sản phẩm:

**Bài viết:**
- [ ] Mở file template hiển thị bài viết
- [ ] Tìm link `<a href=...>{{ $article->title }}</a>`
- [ ] Thêm `data-article-id="{{ $article->id }}"`:
  ```html
  <a href="..." data-article-id="{{ $article->id }}">{{ $article->title }}</a>
  ```

**Sản phẩm:**
- [ ] Mở file template hiển thị sản phẩm
- [ ] Tìm link `<a href=...>{{ $product->name }}</a>`
- [ ] Thêm `data-product-id="{{ $product->id }}"`:
  ```html
  <a href="..." data-product-id="{{ $product->id }}">{{ $product->name }}</a>
  ```

---

## 🟦 BƯỚC 6: TEST HỆ THỐNG

- [ ] Mở trang web chính của bạn
- [ ] Kiểm tra F12 → Console có lỗi gì không
- [ ] Kiểm tra F12 → Network:
  - [ ] Có request đến `/api/analytics/start-session` không?
  - [ ] Có request đến `/api/analytics/record-click` khi click links không?
- [ ] Click vào một bài viết hoặc sản phẩm (cái có `data-*-id`)
- [ ] Quay lại trang admin
- [ ] Kiểm tra số liệu:
  - [ ] Có tăng không?
  - [ ] "Tổng Lượt Truy Cập" tăng từ 0 lên 1 trở lên?
  - [ ] "Tổng Số Click" tăng?

**Nếu không có thay đổi:**
- Kiểm tra F12 → Console có lỗi (đỏ)?
- Kiểm tra Network tab → requests gửi đi không?
- Nếu có error "CSRF token mismatch", chạy lại migration
- Xem `storage/logs/laravel.log` để debug

---

## 🟦 BƯỚC 7: HIỆU CHỈNH CÀI ĐẶT (TUỲ CHỌN)

### Nếu bạn muốn:

**Thay đổi tần suất cập nhật thống kê:**
- [ ] Mở `resources/views/Admin/admin.blade.php`
- [ ] Tìm dòng: `setInterval(loadDailyStats, 30000);`
- [ ] Thay `30000` bằng:
  - `5000` = 5 giây
  - `10000` = 10 giây
  - `60000` = 1 phút

**Thêm tracking cho các loại khác:**
- [ ] Xem file `TRACKING_EXAMPLES.blade.php`
- [ ] Copy ví dụ phù hợp

**Hiển thị dữ liệu chi tiết:**
- [ ] Xem file `DISPLAY_EXAMPLES.md`
- [ ] Copy ví dụ code

---

## 🟦 BƯỚC 8: KIỂM TRA DATABASE

**Xem dữ liệu được lưu:**

1. Mở phpMyAdmin
2. Vào bảng `user_sessions`:
   - Bạn sẽ thấy các row mới với session ID, thời gian, số click

3. Vào bảng `click_analytics`:
   - Bạn sẽ thấy các row mới với item_type, item_id, clicked_at

---

## 🟦 BƯỚC 9: DOCUMENTATION

- [ ] Đọc `IMPLEMENTATION_SUMMARY.md` - Tóm tắt toàn bộ
- [ ] Đọc `ANALYTICS_README.md` - Chi tiết API
- [ ] Đọc `DATABASE_SCHEMA.md` - Cấu trúc database
- [ ] Đọc `DISPLAY_EXAMPLES.md` - Cách hiển thị dữ liệu

---

## ✨ HOÀN TẤT!

Nếu tất cả các mục trên đều ✅, hệ thống đã cài đặt thành công!

---

## 🆘 TROUBLESHOOTING

### ❌ Migration báo lỗi "table already exists"
```bash
php artisan migrate:reset
php artisan migrate
```

### ❌ Không thấy dữ liệu trong admin
1. Mở F12 → Console, kiểm tra lỗi
2. Kiểm tra Network → Có request API không?
3. Xem `storage/logs/laravel.log`

### ❌ Click không được ghi lại
1. Kiểm tra link có `data-article-id` hay `data-product-id` không
2. Mở F12 → Network → Click link → Có request không?
3. Kiểm tra Network tab xem có 200 OK response không

### ❌ CSRF token mismatch error
1. Kiểm tra `<meta name="csrf-token">` trong HTML source (Ctrl+U)
2. Nếu không có, make sure file view kế thừa từ layout có CSRF token

### ❌ Database lỗi khi migrate
1. Kiểm tra `.env`:
   - `DB_HOST` = localhost
   - `DB_PORT` = 3306
   - `DB_DATABASE` = tên database của bạn
   - `DB_USERNAME` = user database
   - `DB_PASSWORD` = password
2. Test kết nối database
3. Chạy `php artisan migrate` lại

---

## 📞 NẾU CẦN GIÚP

1. Kiểm tra tất cả files đã tạo có tồn tại không
2. Chạy `php artisan cache:clear`
3. Chạy `php artisan config:clear`
4. Chạy `composer dump-autoload`
5. Khởi động lại browser

---

**Chúc bạn thành công! 🚀**
