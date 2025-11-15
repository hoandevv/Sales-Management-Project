<?php 
   $sql_bv = "SELECT * FROM tbl_baiviet WHERE tbl_baiviet.id_danhmuc='$_GET[id]' ORDER BY id DESC";
    $query_bv = mysqli_query($mysqli, $sql_bv);
    $sql_cate = "SELECT * FROM tbl_danhmucbaiviet WHERE tbl_danhmucbaiviet.id_baiviet ='$_GET[id]' LIMIT 1";
    $query_cate = mysqli_query($mysqli, $sql_cate);
    $row_title = mysqli_fetch_array($query_cate);
?>
<h3 class="news-title">Danh sách bài viết: <?php echo $row_title['tendanhmuc_baiviet']; ?></h3>
<div class="news-container">
    <?php
    while ($row_bv = mysqli_fetch_array($query_bv)) {
    ?>
    <div class="news-item">
         <a href="index.php?quanly=baiviet&id=<?php echo $row_bv['id']; ?>">
            <img class="news-image" src="admincp/modules/quanlybaiviet/uploads/<?php echo $row_bv['hinhanh']; ?>" alt="Hình ảnh bài viết">
            <p class="news-heading"><?php echo $row_bv['tenbaiviet']; ?></p>
             </a>
        <p class="news-heading"><?php echo $row_bv['tomtat']; ?></p>
    </div>
    <?php
    } 
    ?>
</div>
