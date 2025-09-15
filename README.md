# 📝 Laravel Medium Clone

Một nền tảng blog hiện đại được xây dựng bằng Laravel 12, lấy cảm hứng từ Medium.com với đầy đủ tính năng mạng xã hội và quản lý nội dung.

![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

## ✨ Tính năng chính

### 👤 Quản lý người dùng
- **Đăng ký/Đăng nhập** với Laravel Breeze
- **Xác thực email** bắt buộc
- **Hồ sơ cá nhân** với avatar và bio
- **Follow/Unfollow** người dùng khác
- **Trang profile công khai** với URL dạng `/@username`

### 📖 Quản lý bài viết
- **CRUD hoàn chỉnh** (Create, Read, Update, Delete)
- **Rich text editor** cho nội dung
- **Upload ảnh** với Spatie Media Library
- **Tự động tạo slug** unique với Spatie Sluggable
- **Phân loại bài viết** theo categories
- **Lên lịch đăng bài** (Scheduled Posts)
- **Tính thời gian đọc** tự động
- **SEO-friendly URLs** dạng `/@username/post-slug`

### 🎨 Tương tác xã hội
- **Clap system** (tương tự Medium's applause)
- **Comment/Response system** với nested replies
- **Follow timeline** - chỉ xem bài viết từ người bạn follow
- **Public/Private posts** dựa trên publish date

### 🎯 Giao diện người dùng
- **Responsive design** với Tailwind CSS
- **Medium-inspired UI/UX**
- **Dark/Light mode ready**
- **Component-based architecture** với Blade components
- **Real-time interactions** với Alpine.js

## 🏗️ Kiến trúc hệ thống

### Models & Relationships
```
User (1:N) → Posts
User (M:N) → Users (Followers)
Post (1:N) → Comments
Post (1:N) → Claps
Post (N:1) → Category
Post (1:N) → Media (Spatie)
Comment (1:N) → Comments (Nested)
```

### Core Controllers
- **PostController** - CRUD operations, scheduling
- **CommentController** - Response system
- **ClapController** - Appreciation system
- **FollowerController** - Social connections
- **ProfileController** - User management
- **PublicProfileController** - Public profiles

## 🛠️ Công nghệ sử dụng

### Backend
- **Laravel 12.0** - PHP Framework
- **Laravel Breeze** - Authentication scaffolding
- **Spatie Media Library** - File management
- **Spatie Sluggable** - SEO-friendly URLs
- **SQLite** - Database
- **Queue System** - Background jobs

### Frontend
- **Blade Templates** - Server-side rendering
- **Tailwind CSS** - Utility-first CSS
- **Alpine.js** - Lightweight JavaScript
- **Vite** - Asset bundling

### Testing
- **Pest PHP** - Modern testing framework
- **Laravel Pint** - Code styling

## 🚀 Cài đặt

### Yêu cầu hệ thống
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite

### Các bước cài đặt

1. **Clone repository**
```bash
git clone <repository-url>
cd laravel-course
```

2. **Cài đặt dependencies**
```bash
composer install
npm install
```

3. **Cấu hình môi trường**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Tạo database và chạy migrations**
```bash
touch database/database.sqlite
php artisan migrate --seed
```

5. **Tạo storage link**
```bash
php artisan storage:link
```

6. **Build assets**
```bash
npm run build
# hoặc cho development
npm run dev
```

7. **Chạy ứng dụng**
```bash
# Chạy server
php artisan serve

# Chạy queue worker (terminal khác)
php artisan queue:work

# Chạy asset watcher (terminal khác)
npm run dev
```

## 📱 Sử dụng

### Người dùng thường
1. **Đăng ký tài khoản** và xác thực email
2. **Cập nhật profile** với avatar và bio
3. **Tạo bài viết** với ảnh và nội dung
4. **Follow người dùng** khác để xem bài viết của họ
5. **Tương tác** bằng clap và comment

### Tác giả
1. **Quản lý bài viết** trong "My Posts"
2. **Lên lịch đăng bài** cho tương lai
3. **Edit/Delete** bài viết của mình
4. **Theo dõi tương tác** (claps, comments)

## 🔧 Commands hữu ích

```bash
# Chạy all-in-one development
composer run dev

# Chạy tests
php artisan test

# Clear cache
php artisan optimize:clear

# Tạo fake data
php artisan db:seed

# Publish scheduled posts (chạy tự động)
php artisan posts:publish-scheduled
```

## 📊 Database Schema

### Users
- id, name, username, email, bio, image
- email_verified_at, password, timestamps

### Posts  
- id, title, slug, content, category_id, user_id
- published_at, created_at, updated_at

### Comments
- id, post_id, user_id, parent_id, content, timestamps

### Claps
- id, post_id, user_id, timestamps

### Followers
- id, user_id, follower_id, timestamps

### Categories
- id, name, slug, timestamps

### Media (Spatie)
- id, model_type, model_id, collection_name
- name, file_name, mime_type, disk, size, timestamps

## 🎨 UI Components

### Reusable Blade Components
- `<x-post-item>` - Card hiển thị bài viết
- `<x-user-avatar>` - Avatar người dùng
- `<x-clap-button>` - Nút clap với counter
- `<x-comment-button>` - Nút comment với counter
- `<x-follow-ctr>` - Follow/Unfollow button
- `<x-post-actions>` - Edit/Delete actions cho author

## 🔒 Bảo mật

- **Authentication** với Laravel Breeze
- **Authorization** - chỉ author mới edit/delete posts
- **CSRF Protection** trên tất cả forms
- **SQL Injection Prevention** với Eloquent ORM
- **File Upload Validation** với size và type limits
- **Email Verification** bắt buộc

## 🚀 Tính năng nâng cao

### Scheduled Posts
- Tạo bài viết với `published_at` trong tương lai
- Tự động hiển thị khi đến thời gian
- Command `posts:publish-scheduled` chạy mỗi phút

### Media Management
- Upload ảnh với multiple conversions
- Automatic image optimization
- Queue-based processing
- Storage link integration

### Social Features
- Following system với timeline cá nhân hóa
- Nested comments (replies to replies)
- Real-time clap counting
- Public profile pages

## 📈 Performance

- **Eager Loading** relationships để tránh N+1 queries
- **Database Indexing** trên các cột quan trọng
- **Image Optimization** với Spatie Media Library
- **Queue Jobs** cho các tác vụ nặng
- **Pagination** cho danh sách bài viết

## 🤝 Đóng góp

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

## 📝 License

Dự án này được phân phối dưới [MIT License](LICENSE).

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Spatie](https://spatie.be) - Amazing Laravel packages
- [Medium.com](https://medium.com) - UI/UX inspiration
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework

---

**Được phát triển bởi Vũ Nguyễn Duy Linh**

> Đây là một dự án học tập nhằm thực hành các kỹ năng phát triển web với Laravel và các công nghệ hiện đại.