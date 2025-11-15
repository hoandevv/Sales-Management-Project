<p class="page-title-add-product">Hi đây là trang thêm</p>
<table class="product-form-table" border="1" width="100%" style="border-collapse: collapse;">
  <form method="POST" action="modules/quanlysp/xuly.php" enctype="multipart/form-data">
    <tr>
      <td class="form-label">Tên sản phẩm</td>
      <td class="td_them"><input type="text" name="tensanpham" class="form-input-field" required></td>
    </tr>
    <tr>
      <td class="form-label">Mã sản phẩm</td>
      <td class="td_them"><input type="text" name="masp" class="form-input-field" required></td>
    </tr>

    <tr>
      <td class="form-label">Giá cũ</td>
      <td class="td_them"><input type="text" name="giaspcu" class="form-input-field" required></td>
    </tr>
    <tr>
      <td class="form-label">Giá sản phẩm</td>
      <td class="td_them"><input type="text" name="giasp" class="form-input-field" required></td>
    </tr>
    <tr>
    <tr>
      <td class="form-label">Số lượng</td>
      <td class="td_them"><input type="text" name="soluong" class="form-input-field" required></td>
    </tr>
    <tr>
      <td class="form-label">Hình ảnh</td>
      <td class="td_them"><input type="file" name="hinhanh" class="form-input-field" required></td>
    </tr>
    <tr>
      <td class="form-label">Tóm tắt</td>
      <td class="td_them"><textarea id="tomtat" name="tomtat" class="form-textarea" style="resize: none;" required></textarea></td>
    </tr>
    <tr>
      <td class="form-label">Nội dung</td>
      <td class="td_them"><textarea id="noidung" name="noidung" class="form-textarea" style="resize: none;" required></textarea></td>
    </tr>

    <!-- Danh mục sản phẩm -->
    <tr>
      <td class="form-label">Danh mục sản phẩm</td>
      <td>
        <select name="id_danhmuc" class="form-select-field">
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

    <!-- Tình trạng sản phẩm -->
    <tr>
      <td class="form-label">Tình trạng sản phẩm</td>
      <td>
        <select name="tinhtrang" class="form-select-field">
          <option value="1">Kích hoạt</option>
          <option value="0">Ẩn</option>
        </select>
      </td>
    </tr>

    <tr>
      <td colspan="2"><input type="submit" name="themsanpham" value="Thêm sản phẩm" class="submit-product-btn"></td>
    </tr>
  </form>
</table>
