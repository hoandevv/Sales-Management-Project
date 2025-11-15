<?php
include('../../config/config.php'); // Bao gồm kết nối cơ sở dữ liệu

// Kiểm tra sửa danh mục
if (isset($_POST['submitlienhe'])) {
    $thongtinlienhe = $_POST['thongtinlienhe'];
    $id = $_GET['id'];

    // Cập nhật thông tin liên hệ
    $sql_update = "UPDATE tbl_lienhe SET thongtinlienhe = '$thongtinlienhe' WHERE id = '$id'";
    if (mysqli_query($mysqli, $sql_update)) {
        header('Location: ../../index.php?action=quanlylienhe&query=capnhat');
        exit();
    } else {
        echo "Lỗi: " . mysqli_error($mysqli);
    }
}

// Truy vấn thông tin liên hệ
$sql_lienhe = "SELECT * FROM tbl_lienhe WHERE id = 1";
$query_lienhe = mysqli_query($mysqli, $sql_lienhe);
$dong = mysqli_fetch_array($query_lienhe);
?>