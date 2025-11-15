<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Web bán bàn phím </title>
    <meta charset="utf-8">
    <link rel="icon" href="https://png.pngtree.com/element_our/20190528/ourlarge/pngtree-flat-keyboard-image_1174880.jpg" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/giohang.css">
</head>
<body>  
    <div class="wrapper">
    <?php
     include ('admincp/config/config.php');
     include ('pages/menu.php');
     include ('pages/header.php');
     include ('pages/main.php');
     include ('pages/footer.php');
     ?>

    </div>
</body>
</html>
