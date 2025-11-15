<?php
include('../../config/config.php');

// Kiểm tra thêm danh mục
if (isset($_POST['themdanhmucbaiviet'])) {
    $tendanhmucbaiviet = $_POST['tendanhmucbaiviet'];
    $thutu = $_POST['thutu'];

  
    $sql_them = "INSERT INTO tbl_danhmucbaiviet (tendanhmuc_baiviet, thutu) VALUES ('$tendanhmucbaiviet', '$thutu')";
    if (mysqli_query($mysqli, $sql_them)) {
        header('Location: ../../index.php?action=quanlydanhmucbaiviet&query=them');
    } else {
        echo "Lỗi: " . mysqli_error($mysqli);
    }
}

// Kiểm tra sửa danh mục
elseif (isset($_POST['suadanhmucbaiviet'])) {
    $id_baiviet = $_GET['idbaiviet'];
    $tendanhmucbaiviet = $_POST['tendanhmucbaiviet'];
    $thutu = $_POST['thutu'];

 
    $sql_update = "UPDATE tbl_danhmucbaiviet SET tendanhmuc_baiviet = '$tendanhmucbaiviet', thutu = '$thutu' WHERE id_baiviet = '$id_baiviet'";
    if (mysqli_query($mysqli, $sql_update)) {
        header('Location: ../../index.php?action=quanlydanhmucbaiviet&query=them');
    } else {
        echo "Lỗi: " . mysqli_error($mysqli);
    }
}

// Kiểm tra xóa danh mục
else {
    $id_baiviet = $_GET['idbaiviet'];
    $sql_xoa = "DELETE FROM tbl_danhmucbaiviet WHERE id_baiviet = '$id_baiviet'";
    if (mysqli_query($mysqli, $sql_xoa)) {
        header('Location: ../../index.php?action=quanlydanhmucbaiviet&query=them');
    } else {
        echo "Lỗi: " . mysqli_error($mysqli);
    }
}

mysqli_close($mysqli);
?>
