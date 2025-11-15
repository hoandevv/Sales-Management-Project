    <?php
    include('../../config/config.php');

    // Thêm bài viết
    if (isset($_POST['thembaiviet'])) {
      
        $tenbaiviet = $_POST['tenbaiviet'];
        $hinhanh = $_FILES['hinhanh']['name']; // Lấy tên hình ảnh
        $hinhanh_tmp = $_FILES['hinhanh']['tmp_name']; // Lấy file tạm thời
        $tomtat = $_POST['tomtat'];
        $noidung = $_POST['noidung'];
        $id_danhmuc = $_POST['danhmuc']; // Lấy ID danh mục
        $tinhtrang = $_POST['tinhtrang'];

        // Xử lý upload ảnh
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($hinhanh);
        move_uploaded_file($hinhanh_tmp, $target_file);

        // Thêm bài viết vào cơ sở dữ liệu
        $sql = "INSERT INTO tbl_baiviet (tenbaiviet, hinhanh, tomtat, noidung, id_danhmuc, tinhtrang)
                VALUES ('$tenbaiviet', '$hinhanh', '$tomtat', '$noidung', '$id_danhmuc', '$tinhtrang')";
        
        if (mysqli_query($mysqli, $sql)) {
        header('Location: ../../index.php?action=quanlybaiviet&query=them');
        } else {
            echo "Lỗi: " . mysqli_error($mysqli);
        }
    }

    // Sửa bài viết
    if (isset($_POST['suabaiviet'])) {
        $id = $_POST['id'];
        $tenbaiviet = $_POST['tenbaiviet'];
        $tomtat = $_POST['tomtat'];
        $noidung = $_POST['noidung'];
        $id_danhmuc = $_POST['danhmuc'];
        $tinhtrang = $_POST['tinhtrang'];
        
        // Xử lý hình ảnh nếu có
        $hinhanh = $_FILES['hinhanh']['name'];

        $hinhanh = $_FILES['hinhanh']['name'];
        if ($hinhanh != '') {
            // Nếu có ảnh mới
            $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
            move_uploaded_file($hinhanh_tmp, 'uploads/' . $hinhanh);

            $sql = "UPDATE tbl_baiviet 
            SET tenbaiviet = '$tenbaiviet', hinhanh = '$hinhanh', tomtat = '$tomtat', noidung = '$noidung', id_danhmuc = '$id_danhmuc', tinhtrang = '$tinhtrang'
            WHERE id = '$id'";

        }else {
        $sql = "UPDATE tbl_baiviet 
                SET tenbaiviet = '$tenbaiviet', tomtat = '$tomtat', noidung = '$noidung', id_danhmuc = '$id_danhmuc', tinhtrang = '$tinhtrang'
                WHERE id = '$id'";
        }

        if (mysqli_query($mysqli, $sql)) {
            header('Location: ../../index.php?action=quanlybaiviet&query=them');
        } else {
            echo "Lỗi: " . mysqli_error($mysqli);
        }
    }

    // Xóa bài viết
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql_get_img = "SELECT hinhanh FROM tbl_baiviet WHERE id = '$id'";
        $result = mysqli_query($mysqli, $sql_get_img);
        $row = mysqli_fetch_array($result);
        $hinhanh = $row['hinhanh'];

        // Xóa bài viết
        $sql = "DELETE FROM tbl_baiviet WHERE id = '$id'";
        if (mysqli_query($mysqli, $sql)) {
            // Xóa hình ảnh nếu có
            if ($hinhanh) {
                unlink("uploads/" . $hinhanh);
            }
            header('Location:../../index.php?action=quanlybaiviet&query=them');
        } else {
            echo "Lỗi: " . mysqli_error($mysqli);
        }
    }
    ?>