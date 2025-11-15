<p>Chi tiết sản phẩm</p>

<?php
  $sql_chitiet = "SELECT * FROM tbl_sanpham,tbl_danhmuc 
  WHERE tbl_sanpham.id_danhmuc=tbl_danhmuc.id_danhmuc 
  AND tbl_sanpham.id_sanpham='$_GET[id]' 
  --  mục đích dùng getid là để truy vấn tới sản phẩm mà mình đang đề cập 
  LIMIT 1";

    $query_chitiet = mysqli_query($mysqli, $sql_chitiet);
    while ($row_chitiet = mysqli_fetch_array($query_chitiet)) {

?>

<div class="wapper_chitiet">
    <div class="hinhanh_sanpham">
        <img width="100%" src="admincp/modules/quanlysp/uploads/<?php echo $row_chitiet['hinhanh']; ?>" alt="Hình ảnh sản phẩm">
</div>
<form method="post" action="pages/main/themgiohang.php?idsanpham=<?php echo $row_chitiet['id_sanpham']?>">
<div class="chitiet_sanpham">
    <p style="font-size: 30px ;"> <?php echo $row_chitiet['tensanpham']; ?></p>
    <p>Mã sản phẩm : <?php echo $row_chitiet['masp']; ?></p>
    <p class="product_price_cu" ><?php echo number_format($row_chitiet['giaspcu'], 0, ',', '.'); ?> ₫</p>
    <p style="font-size: 20px ;color: brown;">Giá ưu đãi : <strong><?php echo number_format($row_chitiet['giasp'], 0, ',', '.'); ?> ₫</p></strong>
    <p> <?php echo $row_chitiet['tomtat']; ?></p>
    <p><input type="submit" value="Thêm vào giỏ hàng "></p>
</div> 
<div class="clear">
<div class="tabs">
  <ul id="tabs-nav">
    <li><a href="#tab2">Chi tiết </a></li>
  </ul> 
  <div id="tabs-content">
    <div id="tab2" class="tab-content">
        <p> <?php echo $row_chitiet['noidung']; ?></p>
    </div>
    
  </div> 
</form>
</div>

<?php
    }
?>