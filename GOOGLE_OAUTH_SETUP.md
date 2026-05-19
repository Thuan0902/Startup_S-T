# Google OAuth Setup Guide

## Cách thiết lập đăng nhập Google

### 1. Tạo Google OAuth App
1. Truy cập [Google Cloud Console](https://console.cloud.google.com/)
2. Tạo project mới hoặc chọn project hiện có
3. Kích hoạt Google+ API
4. Tạo OAuth 2.0 credentials:
   - Chọn "OAuth 2.0 Client IDs"
   - Application type: Web application
   - Authorized redirect URIs: `http://localhost/auth/google/callback`

### 2. Cập nhật .env file
Thay thế các giá trị trong `.env`:
```
GOOGLE_CLIENT_ID=your_actual_client_id
GOOGLE_CLIENT_SECRET=your_actual_client_secret
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

### 3. Test đăng nhập
- Truy cập `/login`
- Click "Đăng nhập với Google"
- Chấp thuận quyền truy cập
- Sẽ tự động tạo tài khoản hoặc link với tài khoản hiện có

## Lưu ý
- Đảm bảo APP_URL trong .env đúng với domain của bạn
- Nếu deploy production, cập nhật redirect URI trong Google Console
- Google OAuth sẽ tự động tạo tài khoản mới nếu email chưa tồn tại

### 3. Test đăng nhập
- Truy cập `/login`
- Click "Đăng nhập với Google"
- Chấp thuận quyền truy cập
- Sẽ tự động tạo tài khoản hoặc link với tài khoản hiện có

## Lưu ý
- Đảm bảo APP_URL trong .env đúng với domain của bạn
- Nếu deploy production, cập nhật redirect URI trong Google Console
- Google OAuth sẽ tự động tạo tài khoản mới nếu email chưa tồn tại