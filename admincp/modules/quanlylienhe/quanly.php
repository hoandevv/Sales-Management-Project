    <h5>Quản lý thông tin liên hệ của web </h5>
    <?php
        $sql_lienhe = "SELECT * FROM tbl_lienhe WHERE id = 1";
        $query_lienhe = mysqli_query($mysqli, $sql_lienhe);
    ?>


    <table class="product-form-table" border="1" width="100%" style="border-collapse: collapse;">
    <?php 
    while ($dong = mysqli_fetch_array($query_lienhe)) {
        ?>
    <form method="POST" action="modules/quanlylienhe/xuly.php?id=<?php echo $dong['id']  ?>" enctype="multipart/form-data">
        <!-- cố định một id -->
    <tr>
        <td class="form-label">Thông tin liên hệ</td>
        <td class="td_them"><textarea name="thongtinlienhe" id=""><?php echo $dong['thongtinlienhe']; ?></textarea></td>
        </tr>

        <tr>
        <td colspan="2"><input type="submit" name="submitlienhe" value="Cập nhật liên hệ " class="submit-product-btn"></td>
        
        </tr>
        <?php 
    }
    ?>
    </form>
    </table>
