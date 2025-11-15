<h5>Quản lý thông tin liên hệ của web </h5>
<?php
    $sql_lienhe= "SELECT * FROM tbl_lienhe where id = 1 ";
    $query_lienhe = mysqli_query($mysqli,$sql_lienhe);
?>

 <?php 
   while ($dong = mysqli_fetch_array($query_lienhe)) {
    ?>
    <p> <?php echo $dong['thongtinlienhe']; ?> </p>

    <?php 
   }
   ?>
  </form>

