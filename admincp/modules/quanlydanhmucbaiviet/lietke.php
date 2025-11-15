<?php

// Truy vấn danh mục bài viết
$sql_lietke_danhmucbv = "SELECT * FROM tbl_danhmucbaiviet ORDER BY id_baiviet DESC";
$query_lietke_danhmucbv = mysqli_query($mysqli, $sql_lietke_danhmucbv);

if (!$query_lietke_danhmucbv) {
    die("Lỗi truy vấn: " . mysqli_error($mysqli));
}
?>

<p class="page-title">Liệt kê danh mục bài viết</p>
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
        while ($row = mysqli_fetch_array($query_lietke_danhmucbv)) {
            $i++;
        ?>
            <tr>
                <td class="center"><?php echo $i; ?></td>
                <td><?php echo ($row['tendanhmuc_baiviet']); ?></td>
                <td class="center"><?php echo intval($row['thutu']); ?></td>
                <td class="center">
                    <a href="modules/quanlydanhmucbaiviet/xuly.php?idbaiviet=<?php echo urlencode($row['id_baiviet']); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">Xóa</a> 
                    | 
                    <a href="index.php?action=quanlydanhmucbaiviet&query=sua&idbaiviet=<?php echo urlencode($row['id_baiviet']); ?>">Sửa</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
        
