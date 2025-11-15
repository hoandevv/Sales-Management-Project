<?php
include('../../config/config.php');

function removeAccents($str) {
    $chars = array(
        'à'=>'a', 'á'=>'a', 'ạ'=>'a', 'ả'=>'a', 'ã'=>'a', 'â'=>'a', 'ầ'=>'a', 'ấ'=>'a', 'ậ'=>'a', 'ẩ'=>'a', 'ẫ'=>'a', 'ă'=>'a', 'ằ'=>'a', 'ắ'=>'a', 'ặ'=>'a', 'ẳ'=>'a', 'ẵ'=>'a',
        'è'=>'e', 'é'=>'e', 'ẹ'=>'e', 'ẻ'=>'e', 'ẽ'=>'e', 'ê'=>'e', 'ề'=>'e', 'ế'=>'e', 'ệ'=>'e', 'ể'=>'e', 'ễ'=>'e',
        'ì'=>'i', 'í'=>'i', 'ị'=>'i', 'ỉ'=>'i', 'ĩ'=>'i',
        'ò'=>'o', 'ó'=>'o', 'ọ'=>'o', 'ỏ'=>'o', 'õ'=>'o', 'ô'=>'o', 'ồ'=>'o', 'ố'=>'o', 'ộ'=>'o', 'ổ'=>'o', 'ỗ'=>'o', 'ơ'=>'o', 'ờ'=>'o', 'ớ'=>'o', 'ợ'=>'o', 'ở'=>'o', 'ỡ'=>'o',
        'ù'=>'u', 'ú'=>'u', 'ụ'=>'u', 'ủ'=>'u', 'ũ'=>'u', 'ư'=>'u', 'ừ'=>'u', 'ứ'=>'u', 'ự'=>'u', 'ử'=>'u', 'ữ'=>'u',
        'ỳ'=>'y', 'ý'=>'y', 'ỵ'=>'y', 'ỷ'=>'y', 'ỹ'=>'y',
        'đ'=>'d',
        'À'=>'A', 'Á'=>'A', 'Ạ'=>'A', 'Ả'=>'A', 'Ã'=>'A', 'Â'=>'A', 'Ầ'=>'A', 'Ấ'=>'A', 'Ậ'=>'A', 'Ẩ'=>'A', 'Ẫ'=>'A', 'Ă'=>'A', 'Ằ'=>'A', 'Ắ'=>'A', 'Ặ'=>'A', 'Ẳ'=>'A', 'Ẵ'=>'A',
        'È'=>'E', 'É'=>'E', 'Ẹ'=>'E', 'Ẻ'=>'E', 'Ẽ'=>'E', 'Ê'=>'E', 'Ề'=>'E', 'Ế'=>'E', 'Ệ'=>'E', 'Ể'=>'E', 'Ễ'=>'E',
        'Ì'=>'I', 'Í'=>'I', 'Ị'=>'I', 'Ỉ'=>'I', 'Ĩ'=>'I',
        'Ò'=>'O', 'Ó'=>'O', 'Ọ'=>'O', 'Ỏ'=>'O', 'Õ'=>'O', 'Ô'=>'O', 'Ồ'=>'O', 'Ố'=>'O', 'Ộ'=>'O', 'Ổ'=>'O', 'Ỗ'=>'O', 'Ơ'=>'O', 'Ờ'=>'O', 'Ớ'=>'O', 'Ợ'=>'O', 'Ở'=>'O', 'Ỡ'=>'O',
        'Ù'=>'U', 'Ú'=>'U', 'Ụ'=>'U', 'Ủ'=>'U', 'Ũ'=>'U', 'Ư'=>'U', 'Ừ'=>'U', 'Ứ'=>'U', 'Ự'=>'U', 'Ử'=>'U', 'Ữ'=>'U',
        'Ỳ'=>'Y', 'Ý'=>'Y', 'Ỵ'=>'Y', 'Ỷ'=>'Y', 'Ỹ'=>'Y',
        'Đ'=>'D'
    );
    return strtr($str, $chars);
}

if (isset($_POST['themsanpham'])) {
    // Nhận dữ liệu từ form
    $tensanpham = $_POST['tensanpham'];
    $masp = $_POST['masp'];
    $giaspcu= $_POST['giaspcu'];
    $giasp = $_POST['giasp'];
    $soluong = $_POST['soluong'];
    $tomtat = $_POST['tomtat'];
    $noidung = $_POST['noidung'];
    $tinhtrang = $_POST['tinhtrang'];
    $danhmuc = $_POST['id_danhmuc'];
    
    // Tạo slug từ tên sản phẩm
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', removeAccents($tensanpham))));

    // Xử lý ảnh
    $hinhanh = $_FILES['hinhanh']['name'];
    $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
    $hinhanh_size = $_FILES['hinhanh']['size'];
    $hinhanh_error = $_FILES['hinhanh']['error'];

    // Đổi tên ảnh để tránh trùng lặp
    if ($hinhanh != '') {
        $hinhanh = time() . '_' . $hinhanh;
        $upload_dir = 'uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if ($hinhanh_size > 5000000) {
            die("Ảnh quá lớn, vui lòng chọn ảnh dưới 5MB.");
        }

        move_uploaded_file($hinhanh_tmp, $upload_dir . $hinhanh);
    }

    $sql_them = "INSERT INTO tbl_sanpham (tensanpham, masp, giaspcu, giasp, soluong, hinhanh, tomtat, noidung, tinhtrang, id_danhmuc) 
                 VALUES ('$tensanpham', '$masp', '$giaspcu', '$giasp', '$soluong', '$hinhanh', '$tomtat', '$noidung', '$tinhtrang', '$danhmuc')";

    if (mysqli_query($mysqli, $sql_them)) {
        header('Location: ../../index.php?action=quanlysp&query=them');
        exit;
    } else {
        echo "Lỗi khi thêm sản phẩm: " . mysqli_error($mysqli);
    }
}

// Kiểm tra xem có gửi form sửa sản phẩm hay không
elseif (isset($_POST['suasanpham'])) {
    $idsanpham = $_GET['idsanpham'];  // Nhận id sản phẩm từ URL
    // Nhận dữ liệu từ form
    $tensanpham =  $_POST['tensanpham'];
    $masp =  $_POST['masp'];
    $giaspcu = $_POST['giaspcu'];
    $giasp =  $_POST['giasp'];
    $soluong =  $_POST['soluong'];
    $tomtat =  $_POST['tomtat'];
    $noidung =  $_POST['noidung'];
    $tinhtrang =  $_POST['tinhtrang'];
    $danhmuc =  $_POST['id_danhmuc'];

    // Xử lý ảnh
    $hinhanh = $_FILES['hinhanh']['name'];
    if ($hinhanh != '') {
        // Nếu có ảnh mới
        $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
        move_uploaded_file($hinhanh_tmp, 'uploads/' . $hinhanh);

        // Cập nhật ảnh vào cơ sở dữ liệu
        $sql_update = "UPDATE tbl_sanpham 
                       SET tensanpham='$tensanpham', masp='$masp', giaspcu='$giaspcu',giasp='$giasp', soluong='$soluong', 
                           hinhanh='$hinhanh', tomtat='$tomtat', noidung='$noidung', tinhtrang='$tinhtrang', id_danhmuc='$danhmuc'
                       WHERE id_sanpham='$idsanpham'";
    } else {
        // Nếu không có ảnh mới, chỉ cập nhật các trường khác
        $sql_update = "UPDATE tbl_sanpham 
                       SET tensanpham='$tensanpham', masp='$masp', giaspcu='$giaspcu',giasp='$giasp', soluong='$soluong', 
                           tomtat='$tomtat', noidung='$noidung', tinhtrang='$tinhtrang', id_danhmuc='$danhmuc'
                       WHERE id_sanpham='$idsanpham'";
    }

    if (mysqli_query($mysqli, $sql_update)) {
        header('Location: ../../index.php?action=quanlysp&query=them');
        exit;
    } else {
        echo "Lỗi khi sửa sản phẩm: " . mysqli_error($mysqli);
    }
}

// Kiểm tra xem có yêu cầu xóa sản phẩm không
elseif (isset($_GET['idsanpham'])) {
    // Nhận id sản phẩm từ URL
    $idsanpham = $_GET['idsanpham'];

    // Truy vấn để lấy hình ảnh sản phẩm cần xóa
    $sql = "SELECT hinhanh FROM tbl_sanpham WHERE id_sanpham = ? LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $idsanpham);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hinhanh = $row['hinhanh'];

        // Xóa hình ảnh trong thư mục uploads
        if ($hinhanh != "" && file_exists('uploads/' . $hinhanh)) {
            unlink('uploads/' . $hinhanh);
        }

        // Xóa sản phẩm khỏi cơ sở dữ liệu
        $sql_xoa = "DELETE FROM tbl_sanpham WHERE id_sanpham = ?";
        $stmt_xoa = $mysqli->prepare($sql_xoa);
        $stmt_xoa->bind_param("i", $idsanpham);

        if ($stmt_xoa->execute()) {
            header('Location: ../../index.php?action=quanlysp&query=them');
            exit;
        } else {
            echo "Lỗi khi xóa sản phẩm: " . $stmt_xoa->error;
        }
    } else {
        echo "Sản phẩm không tồn tại.";
    }
}

// Đóng kết nối
$mysqli->close();
?>
