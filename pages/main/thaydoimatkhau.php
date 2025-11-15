<?php
if (isset($_POST['doimatkhau'])) { // lấy dữ liệu từ form nhập của khách hàng 
  
    $taikhoan = $_POST['email']; // gán giá trị từ form vào biến
    $matkhau_cu = md5($_POST['password_cu']);
    $matkhau_moi = md5($_POST['password_moi']);
    
    // Truy vấn kiểm tra tài khoản
    $sql = "SELECT * FROM tbl_dangki WHERE email = '$taikhoan' AND matkhau = '$matkhau_cu' LIMIT 1";
    $row = $mysqli->query($sql);

    // Kiểm tra kết quả truy vấn
    if ($row && $row->num_rows > 0) {
        // Cập nhật mật khẩu mới
        $sql_update = mysqli_query($mysqli, "UPDATE tbl_dangki SET matkhau = '$matkhau_moi' WHERE email = '$taikhoan'");

        if ($sql_update) {
            echo '<script>alert("Đổi mật khẩu thành công!");</script>';
        } else {
            echo '<script>alert("Lỗi khi cập nhật mật khẩu!");</script>';
        }
    } else {
        echo '<script>alert("Tài khoản hoặc mật khẩu cũ không đúng!");</script>';
    }
}

?>




<div class="container">
    <form action=""  method="POST" class="login-form">
       <h5> Đổi mật khẩu</h5>

        <div class="form-group t">
            <label for="username">Nhập Tài Khoản</label>
            <div class="form_user">
                <i class="icon fa-solid fa-user"></i>
                <input type="text" id="username" name="email" placeholder="Email" required>
            </div>
        </div>

        <div class="form-group t">
            <label for="password">Nhập Mật Khẩu Cũ</label>
            <div class="form_pass">
                <i class="icon fa-solid fa-key"></i>
                <input type="password" id="password" name="password_cu" placeholder="Password" required>
            </div>
        </div>

        <div class="form-group t">
            <label for="password">Nhập Mật Khẩu Mới</label>
            <div class="form_pass">
                <i class="icon fa-solid fa-key"></i>
                <input type="password" id="password" name="password_moi" placeholder="Password" required>
            </div>
        </div>

        <div class="dangnhap">
            <button type="submit" name="doimatkhau" class="btn btn-primary">Đổi mật khẩu</button>
            <button type="reset" class="btn btn-secondary">Nhập Lại</button>
        </div>
    </form>
</div>

