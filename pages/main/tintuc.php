
<h4 style="text-align: center;"> <strong>TOÀN BỘ TIN TỨC</h4>
<?php 
   $sql_bv = "SELECT * FROM tbl_baiviet WHERE tinhtrang= 1  ORDER BY id DESC";
    $query_bv = mysqli_query($mysqli, $sql_bv);
?>
<div class="news-container">
    <?php
    while ($row_bv = mysqli_fetch_array($query_bv)) {
    ?>
    <div class="news-item">
         <a href="index.php?quanly=baiviet&id=<?php echo $row_bv['id']; ?>">
            <img class="news-image" src="admincp/modules/quanlybaiviet/uploads/<?php echo ($row_bv['hinhanh']); ?>" alt="Hình ảnh bài viết">
            <p class="news-heading"><?php echo $row_bv['tenbaiviet']; ?></p>
             </a>
        <p class="news-heading"><?php echo $row_bv['tomtat']; ?></p>
    </div>
    <?php
    } 
    ?>
</div>
