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
        <div class="wrapper wrapper-alter">
            <div class = "product">
                <?php if(isset($_POST['search'])){
                        $data = $_POST['searchtext'];

                        $select = mysqli_query($conn, "SELECT * FROM products where name like '%$data%'");

                    }else{
                        $select = mysqli_query($conn, "SELECT * FROM products");
                    }

            while($row = mysqli_fetch_assoc($select)){

                if(isset($_SESSION['user_id'])){
                   $user_id = $_SESSION['user_id'];
               }else $user_id = 0;
                //Изменить стиль (для фона), если есть в корзине
                $product_id = $row['id'];
                $selectForStyle = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
                if(mysqli_num_rows($selectForStyle) > 0){
                    $product_style = "product-item-chosen";
                }else{
                   $product_style = "product-item";
                }

            ?>
                <a href="product.php?view=<?php echo $row['id']; ?>">
                    <div class="<?php echo $product_style?>">
                        <img src = "uploaded_img/<?php echo $row['image']; ?>" height = "285" alt ="echo $row['image']">
                        <p class = "product-title"><?php echo $row['name']; ?></p>
                        <p class = "product-description"><?php echo $row['description']; ?></p>
                        <p class = "product-price">BYN&nbsp<?php echo $row['price']; ?></p>
                    </div>
                </a>



            <?php }?>

        </div>
    </div>
    <!-- footer -->
    <?php require_once ("php/footer.php") ?>
</body>
</html>
