# GearShop - Website Bán Bàn Phím

[![PHP Version](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)](https://docker.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)

Website thương mại điện tử chuyên bán bàn phím chất lượng cao với giao diện thân thiện và tính năng quản lý toàn diện.

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

- **PHP 8.2**  
- **MySQL 8.0**  
- **PHPMailer**

### Frontend

- **HTML5/CSS3**  
- **Bootstrap 5.3**  
- **JavaScript**  
- **Font Awesome**

### DevOps

- **Docker & Docker Compose**  
- **Apache 2.4**

---

## 💻 Yêu cầu hệ thống

- Docker & Docker Compose (20.10+)  
- Git (2.0+)  
- Web Browser: Chrome, Firefox, Safari, Edge (latest versions)  
- RAM: Tối thiểu 2GB  
- Disk Space: 500MB trống

---

## 🚀 Cài đặt

### 1. Clone repository

```bash
git clone <repository-url>
cd project-final-1
