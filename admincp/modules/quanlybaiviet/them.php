<p class="page-title-add-product">Hi đây là trang thêm bài viết </p>
<table class="product-form-table" border="1" width="100%" style="border-collapse: collapse;">
  <form method="POST" action="modules/quanlybaiviet/xuly.php" enctype="multipart/form-data">
    <tr>
      <td class="form-label">Tên bài viết</td>
      <td class="td_them"><input type="text" name="tenbaiviet" class="form-input-field" required></td>
    </tr>
    <tr>
      <td class="form-label">Hình ảnh</td>
      <td class="td_them"><input type="file" name="hinhanh" class="form-input-field" required></td>
    </tr>
    <tr>
      <td class="form-label">Tóm tắt</td>
      <td class="td_them"><textarea id="tomtat"  name="tomtat"  class="form-textarea" style="resize: none;" required></textarea></td>
    </tr> 
    <tr>
      <td class="form-label">Nội dung</td>
      <td class="td_them"><textarea id="noidung" name="noidung"  class="form-textarea" style="resize: none;" required></textarea></td>
    </tr>
    
    <!-- Danh mục bài viết -->
    <tr>
      <td class="form-label">Danh mục bài viết</td>
      <td>
        <select name="id_danhmuc" class="form-select-field">
          <?php
          // Truy vấn dữ liệu danh mục
          $sql_danhmuc = "SELECT * FROM tbl_danhmucbaiviet ORDER BY id_baiviet DESC";
          $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);

          // Lặp qua từng danh mục và hiển thị trong select
          while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
          ?>
            <option value="<?php echo $row_danhmuc['id_baiviet']; ?>">
              <?php echo $row_danhmuc['tendanhmuc_baiviet']; ?>
            </option>
          <?php 
          }
          ?>
        </select>
      </td>
    </tr>

    <!-- Tình trạng bài viết -->
    <tr>
      <td class="form-label">Tình trạng bài viết</td>
      <td>
        <select name="tinhtrang" class="form-select-field">
          <option value="1">Kích hoạt</option>
          <option value="0">Ẩn</option>
        </select>
      </td>
    </tr>

    <tr>
      <td colspan="2"><input type="submit" name="thembaiviet" value="Thêm bài viết" class="submit-product-btn"></td>
    </tr>
  </form>
</table>
