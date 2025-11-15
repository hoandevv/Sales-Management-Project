<nav class="navbar navbar-expand-lg navbar-light bg-light style="background-color:rgb(57, 62, 66);"">
  <a class="navbar-brand" href="index.php?quanly=dashboard">Trang chủ của ADMIN</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="index.php?action=quanlydonhang&query=lietke">Quản lý đơn hàng <span class="sr-only"></span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?action=quanlylienhe&query=capnhat">Liên hệ</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?action=quanlybaiviet&query=them">Bài viết </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Các thư mục khác
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="index.php?action=quanlydanhmucsanpham&query=them">Quản lý danh mục sản phẩm</a>
          <a class="dropdown-item" href="index.php?action=quanlysp&query=them">Quản lý sản phẩm</a>
          <a class="dropdown-item" href="index.php?action=quanlydanhmucbaiviet&query=them">Quản lý danh mục bài viết </a>
          <a class="dropdown-item" href="index.php?action=quanlymk&query=thaydoimatkhau">Thay đổi thông mật khẩu</a>
          <a class="dropdown-item" style="color: brown ;" href="login.php?action=dangxuat">Đăng xuất: <?php if(isset($_SESSION['dangnhap'])){ echo $_SESSION['dangnhap'];  }?></a>
        </div>
        </div>
      </li>
    </ul>
  </div>
</nav>

