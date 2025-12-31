Hướng dẫn cài đặt

Để chạy project này, thực hiện theo các bước sau:
1. Sao chép Repository
Bash

git clone https://github.com/username/mycine.git
cd mycine

2. Cài đặt các gói phụ thuộc
Bash

# Cài đặt PHP dependencies
composer install

# Cài đặt thư viện Frontend
npm install
npm run build

3. Cấu hình Môi trường

Sao chép file .env.example thành .env và cấu hình các thông số cần thiết:
Bash

cp .env.example .env

Mở file .env và cập nhật:

    Database: DB_DATABASE, DB_USERNAME, DB_PASSWORD.

    TMDb API: Thêm TMDB_TOKEN=your_access_token_here.

4. Khởi tạo Project
Bash

# Tạo Application Key
php artisan key:generate

# Tạo bảng và nạp dữ liệu mẫu (Categories)
php artisan migrate --seed

# Tạo liên kết thư mục lưu trữ
php artisan storage:link

5. Đổ dữ liệu phim từ API

Chạy các lệnh custom sau để làm đầy dữ liệu phim:
Bash


# Lấy danh sách phim phổ biến cho các thể loại
php artisan movies:populate

# Lấy 3 phim trending làm carousel
php artisan movies:fetch-trending

6. Chạy ứng dụng
Bash

php artisan serve

Truy cập: http://127.0.0.1:8000
