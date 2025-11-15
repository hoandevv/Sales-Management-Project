<main id="main">
        <?php
        include ('sidebar/sidebar.php');
        ?>

            <div class="maincontent">
                <?php
                if(isset($_GET['quanly'])){
                    $tam = $_GET['quanly'];  
                } else {
                    $tam = '';  
                }
                if($tam == 'danhmucsanpham'){
                    include ('main/danhmuc.php');

                } elseif($tam == 'giohang'){
                    include ('main/giohang.php');

                } elseif($tam == 'danhmucbaiviet'){
                    include ('main/danhmucbaiviet.php');

                } elseif($tam == 'baiviet'){
                    include ('main/baiviet.php'); 

                } elseif($tam == 'tintuc'){
                    include ('main/tintuc.php');

                } elseif($tam == 'lienhe'){
                    include ('main/lienhe.php');

                } elseif($tam == 'sanpham'){
                    include ('main/sanpham.php');

                } elseif($tam == 'dangki'){
                    include ('main/dangki.php');

                } elseif($tam == 'dangnhap'){
                    include ('main/dangnhap.php');

                } elseif($tam == 'timkiem'){
                    include ('main/timkiem.php');

                } elseif($tam == 'camon'){
                    include ('main/camon.php');

                } elseif($tam == 'thaydoimatkhau'){
                    include ('main/thaydoimatkhau.php');

                } elseif($tam == 'vanchuyen'){
                    include ('main/vanchuyen.php');

                } elseif($tam == 'thongtinthanhtoan'){
                    include ('main/thongtinthanhtoan.php');

                } elseif($tam == 'donhangdadat'){
                    include ('main/donhangdadat.php');
                
                } elseif($tam == 'lichsudonhang'){
                    include ('main/lichsudonhang.php');
                    
                } elseif($tam == 'xemdonhang'){
                    include ('main/xemdonhang.php');
                }
                elseif($tam == 'xacnhanotp'){
                    include ('main/xacnhanotp.php');
                }
                elseif($tam == 'adanhsachyeuthich'){
                    include ('main/adanhsachyeuthich.php');

                } elseif($tam == 'quenmatkhau'){
                    include ('main/quenmatkhau.php');

                } elseif($tam == 'doimatkhau'){
                    include ('main/doimatkhau.php');

                } else {
                    include ('main/index.php');
                }
                ?>
            </div>
        </main>
        <!-- Mục đích chính của đoạn mã là giúp ứng dụng 
        có thể tải các trang khác nhau tùy theo tham số URL mà không cần phải viết mã điều hướng phức tạp. -->