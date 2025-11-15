<?php
// Kiểm tra nếu giỏ hàng đã bị xóa hoặc thanh toán đã hoàn thành
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
 echo '<script>alert("Giỏ hàng của bạn trống. Vui lòng thêm sản phẩm vào giỏ hàng!"); window.location.href = "index.php";</script>';
}
?>
<div class="container">
    <!-- Hiển thị các bước của quá trình mua hàng -->
    <div class="arrow-steps clearfix">
        <div class="step done"><span><a href="index.php?quanly=giohang">Giỏ hàng</a></span></div>
        <div class="step done"><span><a href="index.php?quanly=vanchuyen">Vận chuyển</a></span></div>
        <div class="step current"><span><a href="index.php?quanly=thongtinthanhtoan">Thanh toán</a></span></div>
    </div>

    <form action="pages/main/xulythanhtoan.php" method="POST" id="paymentForm">
        <style>
            .form-check {
                margin-bottom: 10px;
            }
            .form-check img {
                height: 40px;
                width: 40px;
                margin-right: 10px;
            }
            #paymentButton {
                margin-top: 20px;
                display: none; /* Ẩn nút mặc định */
                padding: 10px 20px;
                background-color:rgb(146, 155, 164);
                color: white;
                border: none;
                cursor: pointer;
                font-size: 16px;
                border-radius: 5px;
            }
            #paymentButton:hover {
                background-color:rgb(33, 132, 162);
            }
        </style>

             <div class="col-md-6">
                <?php
                // Kiểm tra đăng nhập
                if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
                    echo '<script>alert("Vui lòng đăng nhập để thanh toán"); window.location.href = "index.php?quanly=dangnhap";</script>';
                    exit();
                }
                
                // Lấy thông tin vận chuyển từ cơ sở dữ liệu
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
                <p>Thông tin vận chuyển giỏ hàng</p>
                <ul>
                    <li>Họ và Tên: <b><?php echo $name; ?></b></li>
                    <li>Số điện thoại: <b><?php echo $phone; ?></b></li>
                    <li>Địa chỉ giao hàng: <b><?php echo $address; ?></b></li>
                    <li>Ghi chú: <b><?php echo $note; ?></b></li>
                    
                </ul>
            </div>
        </div>

        <div >
            <!-- Danh sách sản phẩm -->
            <div class="row" >
                <table style="width:100%; text-align:center; border-collapse: collapse;" border="1">
                    <tr>
                        <th>ID</th>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Số lượng</th>
                        <th>Giá sản phẩm</th>
                        <th>Thành tiền</th>
                    </tr>
                    <?php if (isset($_SESSION['cart'])) {
                        $i = 0;
                        $tongtien = 0;
                        foreach ($_SESSION['cart'] as $cart_item) {
                            $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
                            $tongtien += $thanhtien;
                            $i++; ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $cart_item['masp']; ?></td>
                                <td><?php echo $cart_item['tensanpham']; ?></td>
                                <td><img src="admincp/modules/quanlysp/uploads/<?php echo $cart_item['hinhanh']; ?>" width="80px" /></td>
                                <td><?php echo $cart_item['soluong']; ?></td>
                                <td><?php echo number_format($cart_item['giasp'], 0, ',', '.') . ' VNĐ'; ?></td>
                                <td><?php echo number_format($thanhtien, 0, ',', '.') . ' VNĐ'; ?></td>
                               
                            </tr>
                    <?php }
                    } else {
                        echo '<tr><td colspan="7">Giỏ hàng trống</td></tr>';
                    } ?>
                </table>
            </div>

        <!-- Phương thức thanh toán -->
        <div class="col-md-6">
            <p>Chọn phương thức thanh toán</p>
            <div class="form-check">
                <input type="radio" name="payment" id="cash" value="Tienmat" onchange="showPaymentButton()">
                <label for="cash"><img src="https://img.pikbest.com/origin/09/24/60/87kpIkbEsTCSg.png!f305cw" alt="Tiền mặt">Tiền mặt</label>
            </div>
            <div class="form-check">
                <input type="radio" name="payment" id="transfer" value="Chuyenkhoan" onchange="showPaymentButton()">
                <label for="transfer"><img src="https://img.pikbest.com/png-images/qianku/black-money-logo_2390442.png!f305cw" alt="Chuyển khoản">Chuyển khoản</label>
            </div>
            <div class="form-check">
                <input type="radio" name="payment" id="momo" value="Momo" onchange="showPaymentButton()">
                <label for="momo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSnV4cUM7jBauINof35Yn_unOz976Iz5okV8A&s" alt="MOMO">MOMO</label>
            </div>
        </div>

        
        <!-- Tổng tiền --> 
         <p style="font-size: 18px; color: red;">Tổng tiền thanh toán: <?php echo number_format($tongtien, 0, ',', '.') . ' VNĐ'; ?></p>
        <button type="submit" id="paymentButton" class="btn btn-primary">Thanh toán ngay</button>
    </form>
</div>

<script>
    // Hiển thị nút thanh toán khi chọn phương thức
    function showPaymentButton() {
        document.getElementById('paymentButton').style.display = 'block';
    }
</script>
