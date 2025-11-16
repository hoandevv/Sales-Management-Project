# GearShop - Website Bán Bàn Phím

[![PHP Version](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)](https://docker.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)

Website thương mại điện tử chuyên bán bàn phím chất lượng cao với giao diện thân thiện và tính năng quản lý toàn diện.

---

## ⏱️ Time : 2 weeks

---

## 📋 Mục lục

- [Tổng quan](#-tổng-quan)
- [Tính năng](#-tính-năng)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
- [Cài đặt](#-cài-đặt)
- [Sử dụng](#-sử-dụng)
- [Cơ sở dữ liệu](#-cơ-sở-dữ-liệu)
- [Truy cập Admin](#-truy-cập-admin)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [Đóng góp](#-đóng-góp)
- [Liên hệ](#-liên-hệ)
- [Giấy phép](#-giấy-phép)

---

## 🎯 Tổng quan

GearShop là một nền tảng thương mại điện tử hiện đại được xây dựng bằng PHP thuần, chuyên cung cấp các loại bàn phím chất lượng cao. Website có hai phần chính:

- **Frontend**: Giao diện khách hàng với trải nghiệm mua sắm mượt mà.
- **Admin Panel**: Hệ thống quản lý toàn diện cho người bán.

---

## ✨ Tính năng

### 🛒 Tính năng khách hàng

- **Đăng ký tài khoản**: Xác thực OTP qua email (3 bước: nhập email → xác nhận OTP → hoàn thành đăng ký).
- **Đăng nhập/Đăng xuất**: Bảo mật với mã hóa mật khẩu.
- **Quên mật khẩu**: Khôi phục mật khẩu qua email với OTP (3 bước).
- **Đổi mật khẩu**: Thay đổi mật khẩu tài khoản.
- **Duyệt sản phẩm**: Xem danh sách sản phẩm theo danh mục, tìm kiếm sản phẩm.
- **Chi tiết sản phẩm**: Xem thông tin chi tiết và hình ảnh sản phẩm.
- **Giỏ hàng**: Thêm/xóa/sửa số lượng sản phẩm, tính tổng tiền tự động.
- **Danh sách yêu thích**: Quản lý sản phẩm yêu thích cá nhân.
- **Đặt hàng**: Chọn phương thức vận chuyển, nhập thông tin giao hàng.
- **Thanh toán**: Hỗ trợ COD.
- **Theo dõi đơn hàng**: Xem lịch sử đơn hàng, trạng thái giao hàng.
- **Liên hệ**: Gửi thông tin liên hệ đến quản trị viên.
- **Tin tức**: Đọc bài viết, tin tức về sản phẩm.

### 👨‍💼 Tính năng quản trị viên

- **Dashboard thống kê**: Tổng quan doanh thu, đơn hàng, sản phẩm, khách hàng; thống kê theo tháng; top sản phẩm bán chạy.
- **Quản lý sản phẩm**: Thêm/sửa/xóa sản phẩm, upload hình ảnh, phân loại danh mục.
- **Quản lý danh mục**: Thêm/sửa/xóa danh mục sản phẩm.
- **Quản lý đơn hàng**: Xem danh sách đơn hàng, cập nhật trạng thái (Chưa xử lý → Đã xác nhận → Đang vận chuyển → Đã giao hàng), tìm kiếm theo tên khách hàng.
- **Quản lý bài viết**: Thêm/sửa/xóa bài viết tin tức.
- **Quản lý danh mục bài viết**: Phân loại bài viết.
- **Quản lý liên hệ**: Xem và xử lý thông tin liên hệ từ khách hàng.
- **Quản lý mật khẩu**: Đổi mật khẩu admin.

### 📧 Tính năng tự động và bảo mật

- **Gửi email tự động**: Thông báo xác nhận đơn hàng cho khách, thông báo đơn hàng mới cho admin.
- **Xác thực OTP**: Bảo mật đăng ký và khôi phục mật khẩu qua email.
- **Bảo mật dữ liệu**: Mã hóa mật khẩu, bảo vệ SQL injection với prepared statements.
- **Quản lý phiên**: Session management an toàn.
- **Responsive Design**: Giao diện tương thích mọi thiết bị với Bootstrap 5.
- **Upload file**: Upload hình ảnh sản phẩm với kiểm tra bảo mật.

---

## 🛠️ Công nghệ sử dụng

### Backend

- **PHP 8.2**: Ngôn ngữ lập trình chính
- **MySQL 8.0**: Hệ quản trị cơ sở dữ liệu
- **PHPMailer**: Gửi email tự động

### Frontend

- **HTML5/CSS3**: Cấu trúc và styling
- **Bootstrap 5.3**: Framework CSS responsive
- **JavaScript**: Tương tác người dùng
- **Font Awesome**: Icon library

### DevOps

- **Docker**: Containerization
- **Docker Compose**: Orchestration
- **Apache 2.4**: Web server

---

## 💻 Yêu cầu hệ thống

- **Docker & Docker Compose**: Version 20.10+
- **Git**: Version 2.0+
- **Web Browser**: Chrome, Firefox, Safari, Edge (latest versions)
- **RAM**: Tối thiểu 2GB
- **Disk Space**: 500MB trống

---

## 🚀 Cài đặt

### 1. Clone repository
```bash
git clone <repository-url>
cd project-final-1
```

### 2. Khởi động Docker containers
```bash
docker-compose up -d
```

### 3. Kiểm tra trạng thái
```bash
docker-compose ps
```

### 4. Truy cập ứng dụng
- **Website chính**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
  - Username: `root`
  - Password: `rootpassword`
  - Database: `web1`

### 5. Dừng ứng dụng
```bash
docker-compose down
```

---

## 📖 Sử dụng

### Khách hàng
1. **Đăng ký tài khoản**: Truy cập trang đăng ký, nhập thông tin và xác thực email
2. **Duyệt sản phẩm**: Xem danh sách sản phẩm theo danh mục
3. **Thêm vào giỏ hàng**: Chọn sản phẩm và thêm vào giỏ
4. **Đặt hàng**: Điền thông tin giao hàng và thanh toán
5. **Theo dõi đơn hàng**: Xem lịch sử đơn hàng trong tài khoản

### Quản trị viên
1. **Đăng nhập Admin**: Truy cập `/admincp/login.php`
2. **Quản lý sản phẩm**: Thêm/sửa/xóa sản phẩm và danh mục
3. **Xử lý đơn hàng**: Cập nhật trạng thái đơn hàng
4. **Xem thống kê**: Theo dõi doanh thu và hiệu suất

---

## 🗄️ Cơ sở dữ liệu

### Các bảng chính
- `tbl_admin`: Thông tin quản trị viên
- `tbl_dangki`: Thông tin khách hàng
- `tbl_sanpham`: Danh sách sản phẩm
- `tbl_danhmuc`: Danh mục sản phẩm
- `tbl_cart`: Đơn hàng
- `tbl_cart_details`: Chi tiết đơn hàng
- `tbl_sanphamyeuthich`: Sản phẩm yêu thích
- `tbl_baiviet`: Bài viết tin tức
- `tbl_lienhe`: Thông tin liên hệ

### Tính năng tự động
- **Trigger**: Cập nhật thời gian đơn hàng
- **Event**: Tự động hoàn thành đơn hàng sau 3 ngày

---

## 🔐 Truy cập Admin

### Thông tin đăng nhập mặc định
- **URL**: http://localhost:8080/admincp/login.php
- **Username**: `admin`
- **Password**: `admin123`

> ⚠️ **Lưu ý**: Đổi mật khẩu sau lần đăng nhập đầu tiên

---

## 📁 Cấu trúc dự án

```
project-final-1/
├── admincp/                 # Admin panel
│   ├── index.php           # Admin homepage
│   ├── login.php           # Admin login
│   ├── config/             # Admin config
│   ├── modules/            # Admin modules
│   └── adcss/              # Admin styles
├── pages/                   # Frontend pages
│   ├── header.php          # Site header
│   ├── menu.php            # Navigation menu
│   ├── footer.php          # Site footer
│   ├── main.php            # Main content router
│   └── main/               # Page components
├── includes/                # PHP classes & utilities
│   ├── controllers/        # Business logic
│   ├── models/             # Data models
│   └── Database.php        # Database connection
├── css/                     # Stylesheets
├── mail/                    # Email functionality
├── docker/                  # Docker configuration
│   ├── Dockerfile          # PHP-Apache container
│   ├── apache-config.conf  # Apache config
│   └── mysql/
│       └── initdb.d/       # Database schema
├── docker-compose.yml       # Docker orchestration
├── index.php               # Main entry point
├── config.php              # Main configuration
└── README.md               # Documentation
```

---

## 🤝 Đóng góp

Chúng tôi hoan nghênh mọi đóng góp! Vui lòng:

1. Fork dự án
2. Tạo branch mới (`git checkout -b feature/AmazingFeature`)
3. Commit thay đổi (`git commit -m 'Add some AmazingFeature'`)
4. Push lên branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

### Quy tắc đóng góp
- Tuân thủ PSR-12 coding standards
- Viết comment rõ ràng cho code phức tạp
- Test kỹ trước khi commit
- Cập nhật documentation nếu cần

---

## 📞 Liên hệ

**GearShop Team**
- **Email**: abc@domain.com
- **Địa chỉ**: SỐ 54 TRIỀU KHÚC UTT
- **Điện thoại**: +84 123 456 789

### Mạng xã hội
- [Facebook](https://www.facebook.com/)
- [Twitter](https://www.twitter.com/)
- [Instagram](https://www.instagram.com/)

---

## 📄 Giấy phép

Dự án này được phân phối dưới giấy phép MIT. Xem file `LICENSE` để biết thêm chi tiết.

---

<div align="center">
  <p>Được phát triển với ❤️ bởi GearShop Team</p>
  <p>&copy; 2025 GearShop. All rights reserved.</p>
</div>
