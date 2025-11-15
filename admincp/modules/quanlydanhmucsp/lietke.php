<?php
// Kết nối cơ sở dữ liệu
$sql_lietke_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY thutu DESC";
$query_lietke_danhmuc = mysqli_query($mysqli, $sql_lietke_danhmuc);

?>

<p class="page-title">Liệt kê danh mục sản phẩm</p>
<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Thứ tự</th>
            <th>Quản lý</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        while ($row = mysqli_fetch_array($query_lietke_danhmuc)) {
            $i++;
        ?>
            <tr>
                <td class="center"><?php echo $i; ?></td>
                <td><?php echo ($row['tendanhmuc']); ?></td>
                <td class="center"><?php echo intval($row['thutu']); ?></td>
                <td class="center">
                    <a href="modules/quanlydanhmucsp/xuly.php?action=xoa&iddanhmuc=<?php echo urlencode($row['id_danhmuc']); ?>" 
                       onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">Xóa</a> 
                    | 
                    <a href="index.php?action=quanlydanhmucsanpham&query=sua&iddanhmuc=<?php echo urlencode($row['id_danhmuc']); ?>">Sửa</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
