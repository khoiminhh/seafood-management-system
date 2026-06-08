# Hướng Dẫn Cài Đặt Hệ Thống Quản Lý Hải Sản

## Yêu Cầu Hệ Thống
- PHP 8.1+
- MySQL 8.0+
- Node.js 16+
- Composer
- Git

## Cài Đặt Backend

### 1. Clone Repository
```bash
git clone https://github.com/khoiminhh/seafood-management-system.git
cd seafood-management-system/backend
```

### 2. Cài Đặt Dependencies
```bash
composer install
```

### 3. Cấu Hình Environment
```bash
cp .env.example .env
```
Chỉnh sửa file `.env` với thông tin database của bạn

### 4. Tạo Database
```bash
mysql -u root -p < ../database/schema.sql
```

### 5. Chạy Server
```bash
php -S localhost:8000 -t public
```

## Cài Đặt Frontend

### 1. Vào Thư Mục Frontend
```bash
cd ../frontend
```

### 2. Cài Đặt Dependencies
```bash
npm install
```

### 3. Cấu Hình API
Tạo file `.env.local`:
```
VUE_APP_API_URL=http://localhost:8000/api
```

### 4. Chạy Development Server
```bash
npm run dev
```

## Cài Đặt Admin Dashboard

```bash
cd ../admin
npm install
npm run dev
```

## Truy Cập Ứng Dụng
- Frontend (Website): http://localhost:5173
- Admin Dashboard: http://localhost:5174
- Backend API: http://localhost:8000/api
