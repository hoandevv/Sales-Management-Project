<?php

// Truy vấn danh sách sản phẩm với LEFT JOIN để lấy danh mục
$stmt = $mysqli->prepare("SELECT tbl_sanpham.*, tbl_danhmuc.tendanhmuc 
                          FROM tbl_sanpham 
                          LEFT JOIN tbl_danhmuc 
                          ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc 
                          ORDER BY tbl_sanpham.id_sanpham DESC");
$stmt->execute();
$query_lietke_sp = $stmt->get_result();



?>

<p>Liệt kê danh mục sản phẩm</p>
<table style="width: 100%; border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Sản Phẩm</th>
            <th>Hình Ảnh</th>
            <th>Giá cũ</th>
            <th>Giá sản phẩm</th>
            <th>Số lượng</th>
            <th>Danh mục</th>
            <th>Tình trạng</th>
            <th>Quản lý</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Duyệt qua tất cả các dòng dữ liệu trả về
        while ($row = mysqli_fetch_array($query_lietke_sp)) {
        ?>
        <tr>
            <td><?php echo ($row['id_sanpham']); ?></td>
            <td><?php echo ($row['tensanpham']); ?></td>
            <td><img src="modules/quanlysp/uploads/<?php echo ($row['hinhanh']); ?>" style="width: 100px; height: 100px; object-fit: cover;" alt="Hình ảnh sản phẩm"></td>
            <td><?php echo number_format($row['giaspcu'], 0, ',', '.'); ?> VND</td>
            <td><?php echo number_format($row['giasp'], 0, ',', '.'); ?> VND</td>
            <td><?php echo ($row['soluong']); ?></td>
            <td><?php echo isset($row['tendanhmuc']) ? ($row['tendanhmuc']) : "Không có danh mục"; ?></td>
            <td><?php echo $row['tinhtrang'] == 1 ? 'Kích hoạt' : 'Ẩn'; ?></td>
            <td>
                <a href="index.php?action=quanlysp&query=sua&idsanpham=<?php echo $row['id_sanpham']; ?>">Sửa</a>
                | 
                <a href="javascript:void(0);" onclick="if(confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) window.location='modules/quanlysp/xuly.php?idsanpham=<?php echo $row['id_sanpham']; ?>';">Xóa</a>
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>

