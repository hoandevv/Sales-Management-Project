<?php

if (isset($_GET['idsanpham']) && !empty($_GET['idsanpham'])) {
    $id_sanpham = $_GET['idsanpham'];


    $stmt = $mysqli->prepare("SELECT * FROM tbl_sanpham WHERE id_sanpham = ? LIMIT 1");
    $stmt->bind_param("i", $id_sanpham); 
    $stmt->execute();
    $query_sua_sp = $stmt->get_result();

    // Kiểm tra lỗi truy vấn
    if ($query_sua_sp->num_rows == 0) {
        echo "Không tìm thấy sản phẩm với ID: " . ($id_sanpham);
        exit(); 
    }
} else {
    echo "Không có ID sản phẩm được chỉ định.";
    exit();
}
?>

<p>Đây là trang sửa</p>
<table border="1" width="100%" style="border-collapse: collapse;">
  <?php
  // Hiển thị thông tin sản phẩm để sửa
  while ($row = mysqli_fetch_array($query_sua_sp)) {
  ?>
  <form method="POST" action="modules/quanlysp/xuly.php?idsanpham=<?php echo $row['id_sanpham']?>" enctype="multipart/form-data">
    <tr>
      <td>Tên sản phẩm</td>
      <td><input type="text" name="tensanpham" value="<?php echo ($row['tensanpham']); ?>" required></td>
    </tr>
    <tr>
      <td>Mã sản phẩm</td>
      <td><input type="text" name="masp" value="<?php echo ($row['masp']); ?>" required></td>
    </tr>
    <tr>
      <td>Giá sản cũ</td>
      <td><input type="text" name="giaspcu" value="<?php echo ($row['giaspcu']); ?>" required></td>
    </tr>
    <tr>
      <td>Giá sản phẩm</td>
      <td><input type="text" name="giasp" value="<?php echo ($row['giasp']); ?>" required></td>
    </tr>
    <tr>
      <td>Số lượng</td>
      <td><input type="text" name="soluong" value="<?php echo ($row['soluong']); ?>" required></td>
    </tr>
    <tr>
      <td>Hình ảnh</td>
      <td>
        <input type="file" name="hinhanh">
        <?php if (!empty($row['hinhanh'])) { ?>
          <img src="modules/quanlysp/uploads/<?php echo ($row['hinhanh']); ?>" alt="Hình ảnh sản phẩm" width="50" height="50">
        <?php } ?>
      </td>
    </tr>
    <tr>
      <td>Tóm tắt</td>
      <td><textarea name="tomtat" rows="10" cols="20" style="resize: none;" required><?php echo ($row['tomtat']); ?></textarea></td>
    </tr>
    <tr>
      <td>Nội dung</td>
      <td><textarea name="noidung" rows="10" cols="20" style="resize: none;" required><?php echo ($row['noidung']); ?></textarea></td>
    </tr>
    <tr>
    <tr>
      <td>Danh mục sản phẩm</td>
      <td>
        <select name="id_danhmuc">
          <?php
          // Truy vấn dữ liệu danh mục
          $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
          $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);

          // Lặp qua từng danh mục và hiển thị trong select
          while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
          ?>
            <option value="<?php echo $row_danhmuc['id_danhmuc']; ?>">
              <?php echo $row_danhmuc['tendanhmuc']; ?>
            </option>
          <?php 
          }
          ?>
        </select>
      </td>
    </tr>
      <td>Tình trạng</td>
      <td>
        <select name="tinhtrang">
          <option value="1" <?php if ($row['tinhtrang'] == 1) echo 'selected'; ?>>Kích hoạt</option>
          <option value="0" <?php if ($row['tinhtrang'] == 0) echo 'selected'; ?>>Ẩn</option>
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <input type="submit" name="suasanpham" value="Cập nhật sản phẩm">
      </td>
    </tr>
  </form>
  <?php
  }
  ?>
</table>
