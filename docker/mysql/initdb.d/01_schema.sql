-- phpMyAdmin SQL Dump
-- Cơ sở dữ liệu: `web1`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Tạo database và chọn
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `web1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `web1`;

-- --------------------------------------------------------
-- Bảng tbl_admin
-- --------------------------------------------------------
CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_danhmuc
-- --------------------------------------------------------
CREATE TABLE `tbl_danhmuc` (
  `id_danhmuc` int(11) NOT NULL AUTO_INCREMENT,
  `tendanhmuc` varchar(255) NOT NULL,
  `thutu` int(11) NOT NULL,
  PRIMARY KEY (`id_danhmuc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_danhmucbaiviet
-- --------------------------------------------------------
CREATE TABLE `tbl_danhmucbaiviet` (
  `id_baiviet` int(11) NOT NULL AUTO_INCREMENT,
  `tendanhmuc_baiviet` varchar(255) NOT NULL,
  `thutu` int(11) NOT NULL,
  PRIMARY KEY (`id_baiviet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_baiviet
-- --------------------------------------------------------
CREATE TABLE `tbl_baiviet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenbaiviet` varchar(255) NOT NULL,
  `hinhanh` varchar(255) NOT NULL,
  `tomtat` text NOT NULL,
  `noidung` text NOT NULL,
  `id_danhmuc` int(11) NOT NULL,
  `tinhtrang` tinyint(1) DEFAULT 1,
  `ngaytao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_id_danhmuc` (`id_danhmuc`),
  CONSTRAINT `fk_baiviet_danhmucbaiviet` FOREIGN KEY (`id_danhmuc`) REFERENCES `tbl_danhmucbaiviet` (`id_baiviet`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_dangki
-- --------------------------------------------------------
CREATE TABLE `tbl_dangki` (
  `id_dangki` int(11) NOT NULL AUTO_INCREMENT,
  `tenkhachhang` varchar(255) NOT NULL,
  `diachi` text NOT NULL,
  `matkhau` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dienthoai` varchar(15) NOT NULL,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_dangki`),
  UNIQUE KEY `uniq_email` (`email`),
  UNIQUE KEY `uniq_dienthoai` (`dienthoai`),
  KEY `idx_email_active` (`email`, `is_active`),
  KEY `idx_otp` (`otp_code`, `otp_expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_sanpham
-- --------------------------------------------------------
CREATE TABLE `tbl_sanpham` (
  `id_sanpham` int(11) NOT NULL AUTO_INCREMENT,
  `tensanpham` varchar(255) NOT NULL,
  `masp` varchar(50) NOT NULL,
  `giaspcu` decimal(10,2) NOT NULL,
  `giasp` decimal(10,2) NOT NULL,
  `soluong` int(11) NOT NULL,
  `hinhanh` varchar(255) NOT NULL,
  `tomtat` text NOT NULL,
  `noidung` text NOT NULL,
  `id_danhmuc` int(11) NOT NULL,
  `tinhtrang` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_sanpham`),
  KEY `idx_id_danhmuc` (`id_danhmuc`),
  CONSTRAINT `fk_sanpham_danhmuc` FOREIGN KEY (`id_danhmuc`) REFERENCES `tbl_danhmuc` (`id_danhmuc`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_cart
-- --------------------------------------------------------
CREATE TABLE `tbl_cart` (
  `id_cart` int(11) NOT NULL AUTO_INCREMENT,
  `code_cart` varchar(50) NOT NULL,
  `id_khachhang` int(11) NOT NULL,
  `cart_status` tinyint(1) NOT NULL,
  `cart_payment` varchar(255) NOT NULL,
  `cart_shipping` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_cart`),
  KEY `idx_id_khachhang` (`id_khachhang`),
  KEY `idx_cart_status_updated` (`cart_status`, `updated_at`),
  CONSTRAINT `fk_cart_dangki` FOREIGN KEY (`id_khachhang`) REFERENCES `tbl_dangki` (`id_dangki`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_cart_details
-- --------------------------------------------------------
CREATE TABLE `tbl_cart_details` (
  `id_cart_details` int(11) NOT NULL AUTO_INCREMENT,
  `code_cart` varchar(255) NOT NULL,
  `id_sanpham` int(11) NOT NULL,
  `soluongmua` int(11) NOT NULL,
  PRIMARY KEY (`id_cart_details`),
  KEY `idx_id_sanpham` (`id_sanpham`),
  CONSTRAINT `fk_cart_details_sanpham` FOREIGN KEY (`id_sanpham`) REFERENCES `tbl_sanpham` (`id_sanpham`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_sanphamyeuthich
-- --------------------------------------------------------
CREATE TABLE `tbl_sanphamyeuthich` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_product_id` (`product_id`),
  CONSTRAINT `fk_yeuthich_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_dangki` (`id_dangki`) ON DELETE CASCADE,
  CONSTRAINT `fk_yeuthich_product` FOREIGN KEY (`product_id`) REFERENCES `tbl_sanpham` (`id_sanpham`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_vanchuyen
-- --------------------------------------------------------
CREATE TABLE `tbl_vanchuyen` (
  `id_shipping` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `id_dangki` int(11) NOT NULL,
  PRIMARY KEY (`id_shipping`),
  KEY `tbl_vanchuyen_ibfk_1` (`id_dangki`),
  CONSTRAINT `tbl_vanchuyen_ibfk_1` FOREIGN KEY (`id_dangki`) REFERENCES `tbl_dangki` (`id_dangki`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_lienhe
-- --------------------------------------------------------
CREATE TABLE `tbl_lienhe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thongtinlienhe` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_account_verification
-- --------------------------------------------------------
CREATE TABLE `tbl_account_verification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `verification_code` varchar(10) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_used` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_verification_code` (`verification_code`, `expires_at`),
  CONSTRAINT `fk_verification_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_dangki` (`id_dangki`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Bảng tbl_password_reset
-- --------------------------------------------------------
CREATE TABLE `tbl_password_reset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_email_token` (`email`, `token`),
  KEY `idx_expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;

-- Tạo trigger để cập nhật updated_at khi cart_status thay đổi
DELIMITER ;;
DROP TRIGGER IF EXISTS update_cart_updated_at;;
CREATE TRIGGER update_cart_updated_at
    BEFORE UPDATE ON tbl_cart
    FOR EACH ROW
BEGIN
    IF OLD.cart_status != NEW.cart_status THEN
        SET NEW.updated_at = NOW();
    END IF;
END;;
DELIMITER ;

-- Tạo event để tự động hoàn thành đơn hàng sau 3 ngày
DELIMITER ;;
DROP EVENT IF EXISTS auto_complete_delivery;;
CREATE EVENT auto_complete_delivery
ON SCHEDULE EVERY 1 DAY STARTS '2024-01-01 00:00:00'
DO
BEGIN
    UPDATE tbl_cart
    SET cart_status = 3
    WHERE cart_status = 2
    AND TIMESTAMPDIFF(DAY, created_at, NOW()) >= 3;
END;;
DELIMITER ;

-- Bật event scheduler
SET GLOBAL event_scheduler = ON;
