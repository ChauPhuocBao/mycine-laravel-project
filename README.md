# MyCine – Movie Management Web App

MyCine là một ứng dụng web quản lý phim được xây dựng bằng **Laravel** — cho phép người dùng duyệt phim theo thể loại, xem thông tin chi tiết và tải dữ liệu phim từ API bên ngoài. Đây là project demo triển khai ứng dụng Laravel hoàn chỉnh với backend + frontend, database migrations, seeders và các lệnh artisan tùy chỉnh.

---

## 🚀 Tính năng chính

- 🔹 Quản lý phim theo thể loại (Categories)
- 🔹 Lấy dữ liệu phim từ **TMDB API**
- 🔹 Giao diện người dùng xem phim
- 🔹 Migrations & seeders tự động tạo dữ liệu mẫu
- 🔹 Sử dụng Laravel + Blade + Tailwind CSS/Vite
- 🔹 Artisan custom commands để populate & fetch phim

---

## 🧰 Công nghệ sử dụng

- **Laravel (PHP Framework)** – server-side MVC  
- **Blade Templates** – View engine  
- **Tailwind CSS + Vite** – Frontend styling & bundling  
- **MySQL / MariaDB** – Database  
- **TMDB API** – Lấy dữ liệu phim từ The Movie Database  
- **Composer & NPM** – Dependency management

---

## 🛠️ Cài đặt & chạy project (Local)

1. **Clone repository**
    ```bash
    git clone https://github.com/ChauPhuocBao/mycine-laravel-project.git
    cd mycine-laravel-project
    ```

2. **Install dependencies**
    ```bash
    composer install
    npm install
    npm run build
    ```

3. **Tạo file .env**
    ```bash
    cp .env.example .env
    ```
   - Mở `.env` và cấu hình:
     - DB_DATABASE, DB_USERNAME, DB_PASSWORD
     - `TMDB_TOKEN=3b9ba02d0fe01618c2d8db672d2b5b8d`

4. **Khởi tạo project**
    ```bash
    php artisan key:generate
    php artisan migrate --seed
    php artisan storage:link
    ```

5. **Populate dữ liệu phim**
    ```bash
    php artisan movies:populate
    php artisan movies:fetch-trending
    ```

6. **Chạy ứng dụng**
    ```bash
    php artisan serve
    ```
   - Mở trình duyệt: `http://127.0.0.1:8000`

---

## 📌 Lệnh Artisan hữu ích

| Lệnh | Mô tả |
|------|-------|
| `movies:populate` | Lấy dữ liệu movie theo thể loại từ TMDB |
| `movies:fetch-trending` | Lấy danh sách trending làm carousel |
| `storage:link` | Tạo symbolic link tới `public/storage` |

---

## 📁 Cấu trúc folder

