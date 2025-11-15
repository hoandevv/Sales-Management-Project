<h5 >Thông tin vận chuyển đơn hàng tới bạn !!</h5>
        
<div class="container">
    <!-- Hiển thị các bước của quá trình mua hàng -->
    <div class="arrow-steps clearfix">
        <div class="step done"> 
            <span> <a href="index.php?quanly=giohang">Giỏ hàng</a> </span> 
        </div>
        <div class="step current"> 
            <span> <a href="index.php?quanly=vanchuyen">Vận chuyển</a> </span> 
        </div>
        <div class="step ">
            <span>Thanh toán</span> 
        </div> 
    </div>

    <?php 
// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    echo '<script>alert("Vui lòng đăng nhập để thực hiện đặt hàng"); window.location.href = "index.php?quanly=dangnhap";</script>';
    exit();
}

// Kiểm tra nếu người dùng gửi form để thêm thông tin vận chuyển
if (isset($_POST['themvanchuyen'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $note = htmlspecialchars(trim($_POST['note']));
    $id_dangki = $_SESSION['user']['id'];

        // Kiểm tra định dạng số điện thoại
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            echo "<script>alert('Số điện thoại không hợp lệ. Vui lòng nhập lại.');</script>";
        } else {
            // Thêm thông tin vào cơ sở dữ liệu nếu số điện thoại hợp lệ
            $stmt = $mysqli->prepare("INSERT INTO tbl_vanchuyen (name, phone, address, note, id_dangki) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $name, $phone, $address, $note, $id_dangki);

            if ($stmt->execute()) {
                echo 'Thêm thông tin vận chuyển thành công!';
            } else {
                echo "Lỗi: " . $stmt->error;
            }
        }
    } elseif (isset($_POST['capnhatvanchuyen'])) {
        
        $name = ($_POST['name']);
        $phone = ($_POST['phone']);
        $address = ($_POST['address']);
        $note = ($_POST['note']);
        $id_dangki = $_SESSION['user']['id'];

        // Kiểm tra định dạng số điện thoại
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            echo "<script>alert('Số điện thoại không hợp lệ. Vui lòng nhập lại.');</script>";
        } else {
            // Cập nhật thông tin vào cơ sở dữ liệu
            $stmt = $mysqli->prepare("UPDATE tbl_vanchuyen SET name = ?, phone = ?, address = ?, note = ? WHERE id_dangki = ?");
            $stmt->bind_param("ssssi", $name, $phone, $address, $note, $id_dangki);

            if ($stmt->execute()) {
                echo 'Cập nhật thông tin vận chuyển thành công!';
                $_SESSION['vanchuyen'] = true;
            } else {
                echo "Lỗi: " . $stmt->error;
            }
        }
    }
    // Lấy thông tin vận chuyển từ cơ sở dữ liệu
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
        echo '<script>alert("Vui lòng đăng nhập để xem thông tin vận chuyển"); window.location.href = "index.php?quanly=dangnhap";</script>';
        exit();
    }
    
    $id_dangki = $_SESSION['user']['id'];
    $sql_get_vanchuyen = mysqli_query($mysqli, "SELECT * FROM tbl_vanchuyen WHERE id_dangki='$id_dangki' LIMIT 1");
    
    if ($sql_get_vanchuyen === false) {
        die("Lỗi truy vấn: " . mysqli_error($mysqli));
    }
    
    $count_get_vanchuyen = mysqli_num_rows($sql_get_vanchuyen);

    if ($count_get_vanchuyen > 0) {
        // Nếu đã có thông tin vận chuyển, hiển thị dữ liệu
        $row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
        $name = $row_get_vanchuyen['name'];
        $phone = $row_get_vanchuyen['phone'];
        $address = $row_get_vanchuyen['address'];
        $note = $row_get_vanchuyen['note'];
    } else {
        // Nếu không có thông tin vận chuyển, gán giá trị mặc định
        $name = '';
        $phone = '';
        $address = '';
        $note = '';
    }
    ?>

    <!-- Form để người dùng nhập hoặc cập nhật thông tin vận chuyển -->
    <div class="row">
    <form action="" method="post" class="form-vanchuyen">
        <label for="name">Họ và tên:</label><br>
        <input type="text" name="name" value="<?php echo ($name); ?>" required class="form-control"><br><br>
        
        <label for="phone">Số điện thoại:</label><br>
        <input type="text" name="phone" value="<?php echo ($phone); ?>" required class="form-control"><br><br>
        
        <label for="address">Địa chỉ:</label><br>
        <input type="text" name="address" value="<?php echo ($address); ?>" required class="form-control"><br><br>
        
        <label for="note">Ghi chú:</label><br>
        <textarea name="note" class="form-control"><?php echo ($note); ?></textarea><br><br>
        
        <?php 
        // Nếu không có thông tin vận chuyển, hiển thị nút thêm thông tin
        if ($name == '' && $phone == '' && $address == '') { 
        ?>
            <button type="submit" name="themvanchuyen" class="btn btn-primary">Thêm thông tin vận chuyển</button>
            <a href="index.php?quanly=thongtinthanhtoan" class="btn btn-success" id="proceedToPayment" style="display: none;">Tiến hành thanh toán</a>
            <script>
                // Hiển thị nút thanh toán sau khi thêm thông tin vận chuyển
                document.querySelector('form').addEventListener('submit', function() {
                    setTimeout(() => {
                        document.getElementById('proceedToPayment').style.display = 'inline-block';
                    }, 1000);
                });
            </script>
        <?php 
        } else { 
        ?>
            <!-- Nếu đã có thông tin vận chuyển, hiển thị nút cập nhật thông tin -->
            <button type="submit" name="capnhatvanchuyen" class="btn btn-primary">Cập nhật thông tin vận chuyển</button>
            
        <?php 
        } 
        ?>
    </form>
</div>

    <!-- Bảng hiển thị giỏ hàng -->
    <table style="width:100%; text-align:center; border-collapse: collapse;" border="1">
        <tr>
            <th>ID</th>
            <th>Mã sản phẩm</th>
            <th>Tên sản phẩm</th>
            <th>Hình ảnh</th>
            <th>Số lượng</th>
            <th>Giá sản phẩm</th>
            <th>Thành tiền</th>
            <th>Quản lý</th>
        </tr>

        <?php
        // Kiểm tra nếu có sản phẩm trong giỏ hàng
        if (isset($_SESSION['cart'])) {
            $i = 0;
            $tongtien = 0;

            foreach ($_SESSION['cart'] as $cart_item) {
                $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
                $tongtien += $thanhtien;
                $i++;
        ?>

        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $cart_item['masp']; ?></td>
            <td><?php echo $cart_item['tensanpham']; ?></td>
            <td><img src="admincp/modules/quanlysp/uploads/<?php echo $cart_item['hinhanh']; ?>" width="150px" /></td>
            <td>
                <a href="pages/main/themgiohang.php?tru=<?php echo $cart_item['id']; ?>"><i class="fa-solid fa-minus"></i></a>
                <?php echo $cart_item['soluong']; ?>
                <a href="pages/main/themgiohang.php?cong=<?php echo $cart_item['id']; ?>"><i class="fa-solid fa-plus"></i></a>
            </td>
            <td><?php echo number_format($cart_item['giasp'], 0, ',', '.') . ' VNĐ'; ?></td>
            <td><?php echo number_format($thanhtien, 0, ',', '.') . ' VNĐ'; ?></td>
        </tr>

        <?php
            }
        ?>
        <!-- Hiển thị tổng tiền và các liên kết nếu giỏ hàng có sản phẩm -->
        <tr>
            <td colspan="8">
                <div style="clear: both; text-align: center; margin: 20px 0;">
                    <?php
                    if (isset($_SESSION['user'])) {
                        // Kiểm tra nếu đã có thông tin vận chuyển
                        if ($count_get_vanchuyen > 0) {
                    ?>
                            <a href="index.php?quanly=thongtinthanhtoan" class="btn btn-success btn-lg" style="padding: 10px 30px; font-size: 16px;">
                                <i class="fas fa-credit-card me-2"></i>Tiến hành thanh toán
                            </a>
                    <?php
                        } else {
                            echo '<div class="alert alert-warning" style="max-width: 500px; margin: 0 auto;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Vui lòng nhập thông tin vận chuyển trước khi thanh toán
                                  </div>';
                        }
                    } else {
                        echo '<div class="alert alert-info" style="max-width: 500px; margin: 0 auto;">
                                <i class="fas fa-info-circle me-2"></i>
                                Vui lòng <a href="index.php?quanly=dangnhap" class="alert-link">đăng nhập</a> để thanh toán
                              </div>';
                    }
                    ?>
                </div>
            </td>
            </tr>
            <?php
            } else {
                echo 'Giỏ hàng trống';
            }
            ?>
        </table>
</div>
