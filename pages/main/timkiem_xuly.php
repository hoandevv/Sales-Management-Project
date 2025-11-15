<?php
session_start();
include('../../admincp/config/config.php');

if(isset($_POST['tukhoa'])) {
    $tukhoa = mysqli_real_escape_string($mysqli, $_POST['tukhoa']);
    $_SESSION['search_keyword'] = $tukhoa;
    
    // Truy vấn lấy sản phẩm và danh mục
    $sql_pro = "SELECT tbl_sanpham.*, tbl_danhmuc.tendanhmuc 
                FROM tbl_sanpham 
                LEFT JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc 
                WHERE tbl_sanpham.tensanpham LIKE '%$tukhoa%'";
    $query_pro = mysqli_query($mysqli, $sql_pro);
    
    // Lưu kết quả vào session
    $_SESSION['search_results'] = array();
    while ($row = mysqli_fetch_array($query_pro)) {
        $_SESSION['search_results'][] = $row;
    }
}

// Chuyển hướng về trang hiển thị kết quả
header("Location: ../../index.php?quanly=timkiem");
exit();
?>
