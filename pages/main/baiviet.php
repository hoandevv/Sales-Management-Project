<?php 
   // Lấy thông tin bài viết theo ID
   $sql_bv = "SELECT * FROM tbl_baiviet WHERE tbl_baiviet.id='$_GET[id]' LIMIT 1";
   $query_bv = mysqli_query($mysqli, $sql_bv);
   $row_bv_title = mysqli_fetch_array($query_bv);

   // Lấy thông tin danh mục của bài viết
   $sql_cate = "SELECT * FROM tbl_danhmucbaiviet WHERE id_baiviet='$_GET[id]' LIMIT 1";
   $query_cate = mysqli_query($mysqli, $sql_cate);
   $row_cate = mysqli_fetch_array($query_cate);
?>

<h3 class="post-header"><?php echo $row_bv_title['tenbaiviet']; ?></h3>
<div class="post-container">
    <div class="post-item">
        <h4 class="post-title"><?php echo $row_bv_title['tenbaiviet']; ?></h4>
        <div class="post-image-wrapper">
            <img class="post-image" src="admincp/modules/quanlybaiviet/uploads/<?php echo $row_bv_title['hinhanh']; ?>" alt="Hình ảnh bài viết">
        </div>
        <p class="post-summary"><strong>Tóm tắt:</strong> <?php echo $row_bv_title['tomtat']; ?></p>
        <p class="post-content"><strong>Nội dung:</strong> <?php echo $row_bv_title['noidung']; ?></p>
    </div>
</div>
