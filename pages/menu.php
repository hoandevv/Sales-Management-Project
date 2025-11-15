<?php

// Kiểm tra xem có tồn tại action là 'dangxuat' trong URL
if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
    unset($_SESSION['user']);
    unset($_SESSION['dangki']);
    echo '<script>window.location.href = "index.php";</script>';
    exit();
}
?>

<div class="contaner_nav">
<nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
 
  <div class="container">
   
     
    <button
      data-mdb-collapse-init
      class="navbar-toggler"
      type="button"
      data-mdb-target="#navbarButtonsExample"
      aria-controls="navbarButtonsExample"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <i class="fas fa-bars"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarButtonsExample">
      <ul class="navbar-nav me-auto mb-3 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="#">GearShop</a>
        </li>
      </ul>

      <div class="d-flex align-items-center">
        <button data-mdb-ripple-init type="button" class="btn btn-link px-3 me-3">
        <?php
        if (isset($_SESSION['user'])) {
        ?>   
            <a href="index.php?dangxuat=1" class="btn btn-secondary me-2">Đăng xuất (<?php echo htmlspecialchars($_SESSION['user']['name']); ?>)</a>
        <?php
        } else {
        ?>
            <a href="index.php?quanly=dangnhap" class="btn btn-secondary me-2">Đăng nhập</a>
            <a href="index.php?quanly=dangki" class="btn btn-outline-secondary me-2">Đăng ký</a>
        <?php
        }
        ?>
        </button>
        
        <a
          data-mdb-ripple-init
          class="btn btn-dark px-3"
          href="index.php?quanly=giohang"
          role="button"
          ><i class="fa-solid fa-cart-shopping"></i>
        </a>
      </div>
    </div>
  </div>
</nav>


<!-- Menu -->
<div class="menu">
    <ul class="list_menu">
        
    <li><a href="index.php"><i class="fa-solid fa-house"></i>Trang chủ</a></li>
        <li><a href="index.php?quanly=tintuc"><i class="fa-regular fa-newspaper" style="margin-right: 12px;"></i>Tin tức</a></li>
         <li><a href="index.php?quanly=lienhe"><i class="fa-solid fa-envelope" style="margin-right: 12px;"></i>Liên hệ</a></li>
        <?php
        if (isset($_SESSION['user'])) {
        ?>
            <li><a href="index.php?quanly=lichsudonhang"><i class="fa-solid fa-layer-group" style="margin-right: 12px;"></i>Lịch sử đơn hàng</a></li>
        <?php
        } else {
        ?>
            <li><a href="index.php?quanly=dangki"><i class="fa-solid fa-user-plus" style="margin-right: 12px;"></i>Đăng ký</a></li>
        <?php
        }
        ?>
        <li><a href="index.php?quanly=thaydoimatkhau"><i class="fa-solid fa-lock" style="margin-right: 12px;"></i>Thay đổi mật khẩu</a></li> 
        <li><a href="index.php?quanly=adanhsachyeuthich"><i class="fa-solid fa-heart" style="margin-right: 12px;"></i>Danh sách yêu thích</a></li> 


   
</div>

