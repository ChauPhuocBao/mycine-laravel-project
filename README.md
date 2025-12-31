# MyCine – Movie Management Web App

MyCine là một ứng dụng web quản lý phim được xây dựng bằng **Laravel** — cho phép người dùng duyệt phim theo thể loại, xem thông tin chi tiết và tải dữ liệu phim từ API bên ngoài. Đây là project demo triển khai ứng dụng Laravel hoàn chỉnh với backend + frontend, database migrations, seeders và các lệnh artisan tùy chỉnh.

---

## Tính năng chính

- 🔹 Quản lý phim theo thể loại (Categories)
- 🔹 Lấy dữ liệu phim từ **TMDB API**
- 🔹 Giao diện người dùng xem phim
- 🔹 Migrations & seeders tự động tạo dữ liệu mẫu
- 🔹 Sử dụng Laravel + Blade + Tailwind CSS/Vite
- 🔹 Artisan custom commands để populate & fetch phim

---

## Công nghệ sử dụng

- **Laravel (PHP Framework)**
- **Blade Templates**
- **Tailwind CSS + Vite**
- **MySQL (XAMPP – phpMyAdmin)**
- **TMDB API**
- **Composer & NPM**

---

## Cài đặt & chạy project (Sử dụng XAMPP)

### 1. Clone repository
```bash
git clone https://github.com/ChauPhuocBao/mycine-laravel-project.git
cd mycine-laravel-project
```

---

### 2. Cài đặt dependencies
```bash
composer install
npm install
npm run build
```

---

### 3. Tạo Database bằng XAMPP

1. Mở **XAMPP Control Panel**
2. Start **Apache** và **MySQL**
3. Truy cập: http://localhost/phpmyadmin
4. Tạo database mới:
   - Database name: `mycine`
   - Charset: `utf8mb4_unicode_ci`

---

### 4. Cấu hình file `.env`
```bash
cp .env.example .env
```

Chỉnh sửa các dòng sau trong `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mycine
DB_USERNAME=root
DB_PASSWORD=
```

Thêm TMDB API key:
```env
TMDB_API_KEY=3b9ba02d0fe01618c2d8db672d2b5b8d
```

---

### 5. Khởi tạo project
```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

---

### 6. Populate dữ liệu phim
```bash
php artisan movies:populate
php artisan movies:fetch-trending
```

---

### 7. Chạy ứng dụng
```bash
php artisan serve
```

Truy cập website tại:  
 http://127.0.0.1:8000

---

## Các lệnh Artisan quan trọng

| Lệnh | Mô tả |
|------|------|
| movies:populate | Lấy danh sách phim theo thể loại |
| movies:fetch-trending | Lấy phim trending cho carousel |
| migrate --seed | Tạo bảng & dữ liệu mẫu |
| storage:link | Link thư mục storage |

---

## Cấu trúc thư mục

```
├── app/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
├── public/
├── resources/
│   ├── views/
├── routes/
├── tests/
├── composer.json
├── package.json
└── .env.example
```

---

## Ghi chú

- Project dùng cho mục đích học tập / demo Laravel
- Yêu cầu **PHP >= 8.1**
- Cần **TMDB API key** để load dữ liệu phim
- Khuyến nghị chạy bằng **XAMPP trên Windows**

---