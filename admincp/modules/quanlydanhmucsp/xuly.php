<?php
include('../../config/config.php');

// Kiểm tra xem có yêu cầu thêm danh mục không
if (isset($_POST['themdanhmuc'])) {
    // Nhận dữ liệu từ form
    $tenloaisp = $_POST['tendanhmuc']; 
    $thutu = $_POST['thutu'];

    // Thêm danh mục vào cơ sở dữ liệu
    if ($tenloaisp && $thutu) {
        $stmt = mysqli_prepare($mysqli, "INSERT INTO tbl_danhmuc (tendanhmuc, thutu) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "si", $tenloaisp, $thutu);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: ../../index.php?action=quanlydanhmucsanpham&query=them');
            exit();
        } else {
            echo "Lỗi khi thêm danh mục: " . mysqli_error($mysqli);
        }
    } else {
        echo "Dữ liệu không hợp lệ!";
    }
}

// Kiểm tra xem có yêu cầu sửa danh mục không
elseif (isset($_POST['suadanhmuc'])) {
    $tenloaisp = $_POST['tendanhmuc']; 
    $thutu = $_POST['thutu'];
    $id_danhmuc = $_GET['iddanhmuc'];  // Nhận id danh mục từ URL

    // Sửa danh mục
    if ($tenloaisp && $thutu) {
        $stmt = mysqli_prepare($mysqli, "UPDATE tbl_danhmuc SET tendanhmuc=?, thutu=? WHERE id_danhmuc=?");
        mysqli_stmt_bind_param($stmt, "sii", $tenloaisp, $thutu, $id_danhmuc);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: ../../index.php?action=quanlydanhmucsanpham&query=them');
            exit();
        } else {
            echo "Lỗi khi sửa danh mục: " . mysqli_error($mysqli);
        }
    } else {
        echo "Dữ liệu không hợp lệ!";
    }
}

// Kiểm tra xem có yêu cầu xóa danh mục không
elseif (isset($_GET['iddanhmuc']) && isset($_GET['action']) && $_GET['action'] == 'xoa') {
    $id_danhmuc = $_GET['iddanhmuc'];

    // Bắt đầu transaction
    mysqli_begin_transaction($mysqli);

    // Kiểm tra xem có sản phẩm nào trong danh mục không
    $check_query = "SELECT COUNT(*) FROM tbl_sanpham WHERE id_danhmuc = $id_danhmuc";
    $check_result = mysqli_query($mysqli, $check_query);
    $check_row = mysqli_fetch_row($check_result);
    $count = $check_row[0];

    if ($count > 0) {
        // Nếu có sản phẩm, xóa sản phẩm trước
        $delete_products_query = "DELETE FROM tbl_sanpham WHERE id_danhmuc = $id_danhmuc";
        if (!mysqli_query($mysqli, $delete_products_query)) {
            mysqli_rollback($mysqli); // Rollback nếu không thể xóa sản phẩm
            echo "Không thể xóa sản phẩm trong danh mục";
            exit();
        }
    }

    // Xóa danh mục
    $delete_category_query = "DELETE FROM tbl_danhmuc WHERE id_danhmuc = $id_danhmuc";
    if (mysqli_query($mysqli, $delete_category_query)) {
        // Commit transaction nếu xóa thành công
        mysqli_commit($mysqli);
        header('Location: ../../index.php?action=quanlydanhmucsanpham&query=them');
        exit();
    } else {
        mysqli_rollback($mysqli);
        echo "Không thể xóa danh mục";
    }
} else {
    echo "Dữ liệu không hợp lệ!";
}

// Đóng kết nối
mysqli_close($mysqli);
?>