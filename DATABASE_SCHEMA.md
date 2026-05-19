# Database Schema - Analytics System

## 📊 Bảng: `click_analytics`

**Mục đích:** Lưu trữ từng lần click của người dùng

```sql
CREATE TABLE click_analytics (
    id                BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id           BIGINT UNSIGNED NULLABLE,  -- ID người dùng (NULL nếu khách)
    ip_address        VARCHAR(45) NULLABLE,      -- IP address của người dùng
    item_type         VARCHAR(255),              -- Loại: 'article' hoặc 'product'
    item_id           BIGINT UNSIGNED,           -- ID của bài viết hoặc sản phẩm
    page_url          TEXT NULLABLE,             -- URL của trang click
    user_agent        TEXT NULLABLE,             -- Thông tin trình duyệt
    clicked_at        TIMESTAMP,                 -- Thời gian click chính xác
    created_at        TIMESTAMP,
    updated_at        TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX(item_type),
    INDEX(item_id),
    INDEX(clicked_at)
);
```

### Ví dụ dữ liệu:
```
| id | user_id | ip_address  | item_type | item_id | clicked_at         | page_url              |
|----|---------|-------------|-----------|---------|--------------------|-----------------------|
| 1  | NULL    | 192.168.1.1 | article   | 5       | 2026-04-19 10:30:45| /blog                 |
| 2  | 3       | 192.168.1.2 | product   | 12      | 2026-04-19 10:32:10| /products             |
| 3  | NULL    | 192.168.1.3 | article   | 5       | 2026-04-19 10:35:20| /blog                 |
```

---

## 👥 Bảng: `user_sessions`

**Mục đích:** Lưu trữ thông tin session của người dùng với thời gian ở lại và số click

```sql
CREATE TABLE user_sessions (
    id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id             BIGINT UNSIGNED NULLABLE,    -- ID người dùng (NULL nếu khách)
    session_id          VARCHAR(255) UNIQUE,          -- ID session (từ Laravel)
    ip_address          VARCHAR(45) NULLABLE,         -- IP address
    user_agent          TEXT NULLABLE,                -- Thông tin trình duyệt
    started_at          TIMESTAMP,                    -- Thời gian bắt đầu
    ended_at            TIMESTAMP NULLABLE,           -- Thời gian kết thúc
    duration_seconds    INT DEFAULT 0,                -- Tổng thời gian ở lại (giây)
    total_clicks        INT DEFAULT 0,                -- Tổng số click
    created_at          TIMESTAMP,
    updated_at          TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX(started_at)
);
```

### Ví dụ dữ liệu:
```
| id | user_id | session_id | started_at         | ended_at           | duration_seconds | total_clicks |
|----|---------|-----------|--------------------|--------------------|------------------|--------------|
| 1  | NULL    | abc123xyz | 2026-04-19 10:00:00| 2026-04-19 10:15:30| 930              | 3            |
| 2  | 5       | def456uvw | 2026-04-19 10:20:00| 2026-04-19 10:45:00| 1500             | 5            |
| 3  | NULL    | ghi789rst | 2026-04-19 11:00:00| NULL               | 0                | 0            |
```

---

## 🔄 Mối Quan Hệ (Relationships)

```
ClickAnalytic
  ├── belongsTo: User
  ├── belongsTo: Article (nếu item_type = 'article')
  └── belongsTo: Product (nếu item_type = 'product')

UserSession
  └── belongsTo: User

User
  ├── hasMany: ClickAnalytic
  └── hasMany: UserSession
```

---

## 📈 Các Truy Vấn Phổ Biến

### 1. Tổng số truy cập hôm nay
```sql
SELECT COUNT(*) as total_visitors
FROM user_sessions
WHERE DATE(started_at) = CURDATE();
```

### 2. Tổng thời gian ở lại hôm nay (phút)
```sql
SELECT ROUND(SUM(duration_seconds) / 60, 2) as total_minutes
FROM user_sessions
WHERE DATE(started_at) = CURDATE();
```

### 3. Tổng số click hôm nay
```sql
SELECT COUNT(*) as total_clicks
FROM click_analytics
WHERE DATE(clicked_at) = CURDATE();
```

### 4. Click theo loại (article vs product)
```sql
SELECT 
    item_type,
    COUNT(*) as click_count
FROM click_analytics
WHERE DATE(clicked_at) = CURDATE()
GROUP BY item_type;
```

### 5. Top 5 bài viết được click nhiều nhất hôm nay
```sql
SELECT 
    item_id,
    COUNT(*) as click_count,
    a.title
FROM click_analytics ca
LEFT JOIN articles a ON ca.item_id = a.id
WHERE ca.item_type = 'article' 
    AND DATE(ca.clicked_at) = CURDATE()
GROUP BY ca.item_id
ORDER BY click_count DESC
LIMIT 5;
```

### 6. Top 5 sản phẩm được click nhiều nhất hôm nay
```sql
SELECT 
    item_id,
    COUNT(*) as click_count,
    p.name
FROM click_analytics ca
LEFT JOIN products p ON ca.item_id = p.id
WHERE ca.item_type = 'product' 
    AND DATE(ca.clicked_at) = CURDATE()
GROUP BY ca.item_id
ORDER BY click_count DESC
LIMIT 5;
```

### 7. Thống kê hàng ngày (tuần trước)
```sql
SELECT 
    DATE(started_at) as date,
    COUNT(*) as visitors,
    ROUND(SUM(duration_seconds) / 60, 2) as total_minutes,
    SUM(total_clicks) as total_clicks
FROM user_sessions
WHERE started_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY DATE(started_at)
ORDER BY date DESC;
```

### 8. Người dùng có session dài nhất
```sql
SELECT 
    us.session_id,
    us.user_id,
    us.started_at,
    us.duration_seconds,
    u.name,
    u.email
FROM user_sessions us
LEFT JOIN users u ON us.user_id = u.id
WHERE DATE(us.started_at) = CURDATE()
ORDER BY us.duration_seconds DESC
LIMIT 10;
```

### 9. IP address của những người click nhiều nhất
```sql
SELECT 
    ip_address,
    COUNT(*) as click_count
FROM click_analytics
WHERE DATE(clicked_at) = CURDATE()
GROUP BY ip_address
ORDER BY click_count DESC
LIMIT 10;
```

### 10. Người dùng đã đăng nhập vs khách
```sql
SELECT 
    CASE 
        WHEN user_id IS NULL THEN 'Khách'
        ELSE 'Đã đăng nhập'
    END as user_type,
    COUNT(*) as count
FROM user_sessions
WHERE DATE(started_at) = CURDATE()
GROUP BY user_id IS NULL;
```

---

## 🛠️ Optimization Tips

1. **Index:** Bảng đã có index trên `clicked_at`, `item_type`, `item_id` để tối ưu truy vấn
2. **Archive:** Sau một thời gian, có thể archive dữ liệu cũ vào bảng riêng
3. **Cleanup:** Có thể xóa session đã ended + không có click sau 30 ngày

```sql
-- Archive dữ liệu 90 ngày trước
DELETE FROM click_analytics 
WHERE clicked_at < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- Archive session cũ
DELETE FROM user_sessions 
WHERE ended_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
```

---

## 📊 Dữ Liệu được Ghi Lại

Mỗi lần click:
- ✅ User ID (nếu đăng nhập)
- ✅ IP Address
- ✅ Item Type (article/product)
- ✅ Item ID
- ✅ Page URL
- ✅ User Agent (Browser Info)
- ✅ Exact Timestamp

Mỗi session:
- ✅ Session ID
- ✅ Start Time
- ✅ End Time
- ✅ Duration (giây)
- ✅ Total Clicks
- ✅ User ID (nếu có)
- ✅ IP Address
- ✅ User Agent
