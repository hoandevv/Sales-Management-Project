<?php

// Lấy ID danh mục cần sửa
if (isset($_GET['iddanhmuc'])) {
    $id_danhmuc = intval($_GET['iddanhmuc']);
    $sql_sua_danhmuc = "SELECT * FROM tbl_danhmuc WHERE id_danhmuc = '$id_danhmuc' LIMIT 1";
    $query_sua_danhmucsp = mysqli_query($mysqli, $sql_sua_danhmuc);

    if (!$query_sua_danhmucsp || mysqli_num_rows($query_sua_danhmucsp) == 0) {
        echo "<p class='text-center text-danger'>Danh mục không tồn tại!</p>";
        exit;
    }
} else {
    echo "<p class='text-center text-danger'>ID danh mục không hợp lệ!</p>";
    exit;
}
?>

<p>Đây là trang sửa danh mục</p>
<table border="1" width="50%" style="border-collapse: collapse;">
    <form method="POST" action="modules/quanlydanhmucsp/xuly.php?iddanhmuc=<?php echo $id_danhmuc; ?>">
        <?php while ($dong = mysqli_fetch_array($query_sua_danhmucsp)) { ?>
            <tr>
                <td>Tên danh mục</td>
                <td><input type="text" value="<?php echo $dong['tendanhmuc']; ?>" name="tendanhmuc" required></td>
            </tr>
            <tr>
                <td>Thứ tự</td>
                <td><input type="number" value="<?php echo intval($dong['thutu']); ?>" name="thutu" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="suadanhmuc" value="Sửa danh mục sản phẩm"></td>
            </tr>
        <?php } ?>
    </form>
</table>
