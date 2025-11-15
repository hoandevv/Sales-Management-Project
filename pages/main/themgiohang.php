<?php
session_start();
include('../../admincp/config/config.php');

// Trừ số lượng sản phẩm
if (isset($_GET['tru'])) { 
    $id = $_GET['tru'];   // lấy đúng id cần trừ 
    $newcart = array();
    foreach ($_SESSION['cart'] as $cart_item) { // duyệt qua toàn bộ sản phẩm trong giỏ hàng
        if ($cart_item['id'] == $id) {
            $cart_item['soluong'] -= 1;
            if ($cart_item['soluong'] > 0) {
                $newcart[] = $cart_item; // Giữ lại sản phẩm nếu số lượng > 0
            }
        } else {
            $newcart[] = $cart_item;
        }
    }
    $_SESSION['cart'] = $newcart;
    header('location:../../index.php?quanly=giohang');
    exit();
} 
// Cộng số lượng sản phẩm
if (isset($_GET['cong'])) {
    $id = $_GET['cong'];
    $newcart = array();
    
    // Truy vấn thông tin số lượng sản phẩm trong kho
    $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham='$id' LIMIT 1";
    $query = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_array($query);
    $soluong_ton_kho = $row['soluong']; // Số lượng trong kho

    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $id) {
            // Kiểm tra nếu số lượng trong giỏ hàng < số lượng trong kho
            if ($cart_item['soluong'] < $soluong_ton_kho) {
                $cart_item['soluong'] += 1; // Tăng số lượng nếu không vượt quá số lượng kho
            }
        }
        $newcart[] = $cart_item;
    }
    $_SESSION['cart'] = $newcart;
    header('location:../../index.php?quanly=giohang');
    exit();
}

// Xóa 1 sản phẩm
if (isset($_GET['xoa'])) {
    $id = $_GET['xoa'];
    $newcart = array();
    foreach ($_SESSION['cart'] as $cart_item) {
        if ($cart_item['id'] != $id) {
            $newcart[] = $cart_item;
        }
    }
    $_SESSION['cart'] = $newcart;
    header('location:../../index.php?quanly=giohang');
    exit();
}

// Xóa tất cả sản phẩm trong giỏ hàng
if (isset($_GET['xoatatca'])) {
    unset($_SESSION['cart']);
    header('location:../../index.php?quanly=giohang');
    exit();
}

// Thêm sản phẩm vào giỏ hàng
if (isset($_GET['idsanpham'])) {
    $idsp = $_GET['idsanpham']; 
    $soluong = 1; // Số lượng mặc định

    // Truy vấn thông tin sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham='$idsp' LIMIT 1";
    $query = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_array($query);

    if ($row) {
        // Lấy số lượng sản phẩm trong kho
        $soluong_ton_kho = $row['soluong']; 

        // Kiểm tra xem số lượng trong giỏ có vượt quá số lượng tồn kho không
        if ($soluong > $soluong_ton_kho) {
            $soluong = $soluong_ton_kho; 
        }

        // Tạo mảng sản phẩm mới
        $new_product = array(
            'tensanpham' => $row['tensanpham'],
            'id' => $idsp,
            'soluong' => $soluong,
            'giasp' => $row['giasp'],
            'hinhanh' => $row['hinhanh'],
            'masp' => $row['masp']
        );

        // Kiểm tra giỏ hàng
        if (isset($_SESSION['cart'])) {
            $found = false;

            // Duyệt giỏ hàng
            foreach ($_SESSION['cart'] as &$cart_item) {
                if ($cart_item['id'] == $idsp) {
                    // Nếu đã tồn tại, tăng số lượng nhưng không vượt quá số lượng trong kho
                    if ($cart_item['soluong'] < $soluong_ton_kho) {
                        $cart_item['soluong'] += 1;
                    }
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $_SESSION['cart'][] = $new_product; // Nếu chưa có, thêm mới
            }
        } else {
            $_SESSION['cart'] = array($new_product); // Tạo giỏ hàng mới
        }
    }

    // Chuyển hướng về giỏ hàng
    header('location:../../index.php?quanly=giohang');
    exit();
}
?>
