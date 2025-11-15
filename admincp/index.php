<?php 
session_start();
if (!isset($_SESSION['dangnhap'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <title>Admin Cp</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="https://png.pngtree.com/element_our/20190528/ourlarge/pngtree-flat-keyboard-image_1174880.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adcss/mainad.css">
</head>
<body>  
    <div class="wrapper">
    <?php

        include ('./config/config.php');
        include ('modules/header.php');
        include ('modules/menu.php');
        include ('modules/main.php');
        include ('modules/footer.php'); 
        ?>

    </div>
    <script src="//cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        CKEDITOR.replace('tomtat'); 
        CKEDITOR.replace('noidung'); 
        CKEDITOR.replace('thongtinlienhe');

    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
