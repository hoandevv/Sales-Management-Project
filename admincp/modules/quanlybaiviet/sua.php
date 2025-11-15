<?php 


$sql_sua_bv = "SELECT * FROM tbl_baiviet WHERE id='$_GET[id]' LIMIT 1";
$query_sua_bv = mysqli_query($mysqli, $sql_sua_bv);
?>
<p>Sửa bài viết </p>
<table border="1" width="100%" style="border-collapse: collapse;"> 
  <?php 
  while ($row = mysqli_fetch_array($query_sua_bv)) {
  ?>
  <form method="POST" action="modules/quanlybaiviet/xuly.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    <tr>
      <td class="form-label">Tên bài viết</td>
      <td class="td_them"><input type="text" value="<?php echo $row['tenbaiviet']; ?>" name="tenbaiviet" class="form-input" required></td>
    </tr>
    <tr>
      <td>Hình ảnh</td>
      <td class="td_them">
        <input type="file" name="hinhanh" class="form-input">
        <img src="modules/quanlybaiviet/uploads/<?php echo $row['hinhanh']; ?>" width="150" height="150"> 
      </td>                                                                  
    </tr>
    <tr>
      <td class="form-label">Nội dung</td>
      <td class="td_them">
        <textarea name="noidung" class="form-input" required><?php echo $row['noidung']; ?></textarea>
      </td>
    </tr>
    <tr>
      <td class="form-label">Tóm tắt</td>
      <td class="td_them">
        <textarea name="tomtat" class="form-input" required><?php echo $row['tomtat']; ?></textarea>
      </td>
    </tr>
    <tr>
      <td class="form-label">Danh mục bài viết</td>
      <td class="td_them">
        <select name="danhmuc" class="form-input">
          <?php
          $sql_danhmuc = "SELECT * FROM tbl_danhmucbaiviet ORDER BY id_baiviet DESC";
          $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
          while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
            if ($row_danhmuc['id_baiviet'] == $row['id_danhmuc']) {
              echo '<option selected value="'.$row_danhmuc['id_baiviet'].'">'.$row_danhmuc['tendanhmuc_baiviet'].'</option>';
            } else {
              echo '<option value="'.$row_danhmuc['id_baiviet'].'">'.$row_danhmuc['tendanhmuc_baiviet'].'</option>';
            }
          }
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td class="form-label">Trạng thái</td>
      <td class="td_them">
        <select name="tinhtrang" class="form-input">
          <?php
          if ($row['tinhtrang'] == 0) {
            echo '<option selected value="0">Ẩn</option>';
            echo '<option value="1">Hiển thị</option>';
          } else {
            echo '<option value="0">Ẩn</option>';
            echo '<option selected value="1">Hiển thị</option>';
          }
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="2"><input type="submit" name="suabaiviet" value="Sửa bài viết" class="submit-product-btn"></td>
    </tr>
  </form>
  <?php
  }
  ?>
</table>