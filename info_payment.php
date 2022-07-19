<?php

@include 'config.php';

session_start();

if(isset($message)){
    foreach($message as $message){
        echo '<span class="message">'.$message.'</span>';
    }


}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <!-- Header!-->

    <div class="page">
        <div class="wrapper wrapper-light">
        <?php require_once ("php/header.php") ?>
        </div>
    </div>
    <!-- main -->
    <div class="page">
        <div class="wrapper wrapper-light">
            <div class="product">
            <?php
                $select = mysqli_query($conn, "SELECT * FROM payment_method");
                while($row = mysqli_fetch_assoc($select)){ ?>
                        <div class="product-item">
                            <p>Способ <?php echo $row['id']?></p>
                            <p><?php echo $row['description']?></p>
                            <p><?php echo $row['extra']?></p>
                        </div>   
                <?php }?>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php require_once ("php/footer.php") ?>
</body>
</html>