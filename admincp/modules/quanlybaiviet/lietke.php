<?php
// Truy vấn danh sách bài viết kết hợp với danh mục bài viết
$sql_lietke_baiviet = "
SELECT b.*, d.tendanhmuc_baiviet 
FROM tbl_baiviet b
LEFT JOIN tbl_danhmucbaiviet d ON b.id_danhmuc = d.id_baiviet
ORDER BY b.id DESC";

// Thực thi truy vấn
$query_lietke_baiviet = mysqli_query($mysqli, $sql_lietke_baiviet);

// Kiểm tra lỗi truy vấn
if (!$query_lietke_baiviet) {
    die("Lỗi truy vấn: " . mysqli_error($mysqli));
}
?>

<!-- Hiển thị danh sách bài viết -->
<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên bài viết</th>
            <th>Hình ảnh</th>
            <th>Tóm tắt</th>
            <th>Danh mục</th>
            <th>Tình trạng</th>
            <th>Quản lý</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Duyệt qua danh sách bài viết và hiển thị thông tin
        while ($row = mysqli_fetch_array($query_lietke_baiviet)) {
        ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['tenbaiviet']; ?></td>
                <td><img src="modules/quanlybaiviet/uploads/<?php echo $row['hinhanh']; ?>" style="width: 100px; height: 100px; object-fit: cover;" alt="Hình ảnh sản phẩm"></td>
                <td><?php echo$row['tomtat']; ?></td>
                <td><?php echo $row['tendanhmuc_baiviet']; ?></td>
                <td><?php echo $row['tinhtrang'] == 1 ? 'Kích hoạt' : 'Chưa kích hoạt'; ?></td>
                <td>
                    <!-- Các liên kết để sửa và xóa bài viết -->
                    <a href="index.php?action=quanlybaiviet&query=sua&id=<?php echo urlencode($row['id']); ?>">Sửa</a>
|
                    <a href="javascript:void(0);" onclick="if(confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) window.location='modules/quanlybaiviet/xuly.php?id=<?php echo $row['id']; ?>';">Xóa</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<?php
// Kết thúc file
?>
