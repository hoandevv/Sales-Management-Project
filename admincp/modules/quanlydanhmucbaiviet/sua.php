<?php

// Lấy ID danh mục cần sửa
if (isset($_GET['idbaiviet'])) {
    $id_danhmucbaiviet = $_GET['idbaiviet'];
    $sql_sua_danhmucbv = "SELECT * FROM tbl_danhmucbaiviet WHERE id_baiviet = '$id_danhmucbaiviet' LIMIT 1";
    $query_sua_danhmucbv = mysqli_query($mysqli, $sql_sua_danhmucbv);
    $row_sua_danhmucbv = mysqli_fetch_assoc($query_sua_danhmucbv);
} else {
    echo "<p class='text-center text-danger'>ID danh mục không hợp lệ!</p>";
    exit;
}
?>

<p>Hi đây là sửa danh mục bài viết</p>
<table border="1" width="50%" style="border-collapse: collapse;">
  <form method="POST" action="modules/quanlydanhmucbaiviet/xuly.php?idbaiviet=<?php echo $id_danhmucbaiviet; ?>">
    <tr>
      <td><label for="tendanhmuc">Tên danh mục bài viết</label></td>
      <td><input type="text" id="tendanhmuc" name="tendanhmucbaiviet" value="<?php echo htmlspecialchars($row_sua_danhmucbv['tendanhmuc_baiviet']); ?>" required></td>
    </tr>
    <tr>
      <td><label for="thutu">Thứ tự</label></td>
      <td><input type="number" id="thutu" name="thutu" value="<?php echo $row_sua_danhmucbv['thutu']; ?>" required></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;">
        <button type="submit" name="suadanhmucbaiviet">Sửa danh mục bài viết</button>
      </td>
    </tr>
  </form>
</table>

