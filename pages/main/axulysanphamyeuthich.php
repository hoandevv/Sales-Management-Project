<?php
session_start();
include('../../admincp/config/config.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    $_SESSION['error'] = "Vui lòng đăng nhập để sử dụng tính năng này!";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$user_id = (int)$_SESSION['user']['id'];

if (isset($_POST['add_favorite'])) {
    if (!isset($_POST['product_id'])) {
        $_SESSION['error'] = "Thiếu thông tin sản phẩm!";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $product_id = (int)$_POST['product_id'];

    // Kiểm tra xem sản phẩm đã có trong danh sách yêu thích chưa
    $sql_check = "SELECT * FROM tbl_sanphamyeuthich WHERE user_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($mysqli, $sql_check);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        // Nếu chưa có thì thêm vào
        $sql_add = "INSERT INTO tbl_sanphamyeuthich (user_id, product_id) VALUES (?, ?)";
        $stmt_add = mysqli_prepare($mysqli, $sql_add);
        mysqli_stmt_bind_param($stmt_add, 'ii', $user_id, $product_id);
        
        if (mysqli_stmt_execute($stmt_add)) {
            $_SESSION['success'] = "Đã thêm vào danh sách yêu thích!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi thêm vào yêu thích: " . mysqli_error($mysqli);
        }
    } else {
        $_SESSION['error'] = "Sản phẩm đã có trong danh sách yêu thích!";
    }
}

if (isset($_POST['remove_favorite'])) {
    if (!isset($_POST['product_id'])) {
        $_SESSION['error'] = "Thiếu thông tin sản phẩm!";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $product_id = (int)$_POST['product_id'];

    // Xóa sản phẩm khỏi danh sách yêu thích
    $sql_delete = "DELETE FROM tbl_sanphamyeuthich WHERE user_id = ? AND product_id = ?";
    $stmt_del = mysqli_prepare($mysqli, $sql_delete);
    mysqli_stmt_bind_param($stmt_del, 'ii', $user_id, $product_id);
    
    if (mysqli_stmt_execute($stmt_del)) {
        $_SESSION['success'] = "Đã xóa khỏi danh sách yêu thích!";
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra khi xóa khỏi yêu thích: " . mysqli_error($mysqli);
    }
}

// Quay lại trang trước đó
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
