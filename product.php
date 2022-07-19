<?php

@include 'config.php';

if(isset($_GET['view'])){
    $id = $_GET['view'];
}


session_start();

if(isset($message)){
    foreach($message as $message){
        echo '<span class="message">'.$message.'</span>';
    }
}

if(isset($_GET['add_to_cart'])){
    
    $product_id = $_GET['add_to_cart'];

    if(!isset($_SESSION['user_id'])){
    //Попросить войти в акк
    }
    else{
        $user_id = $_SESSION['user_id'];
        $amount = 1;

        $select = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    //Удалить, если есть. Добавить, если нет
        if(mysqli_num_rows($select) > 0){
            $row = mysqli_fetch_assoc($select);
            $toDelete_id = $row['id'];
            mysqli_query($conn, "DELETE FROM cart WHERE id = $toDelete_id");
        }else{
            mysqli_query($conn,"INSERT INTO cart(user_id, product_id, amount) VALUES($user_id, $product_id, $amount)");
        }

    }
    header('location:product.php?view='.(string)$product_id);
    


    


    
};

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Товар</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header-->
    <div class="page">
        <div class="wrapper wrapper-light">
        <?php require_once ("php/header.php") ?>
        </div>
    </div>
    <!-- Main-->
    <div class="page">
        <div class="wrapper wrapper-alter">
            <div class="productView">
                <?php
                
                if(isset($_SESSION['user_id'])){
                    $user_id = $_SESSION['user_id'];
                }else $user_id = 0;
                //Изменить стиль (для фона), если есть в корзине
                $select = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $id");
                if(mysqli_num_rows($select) > 0){
                    $product_style = "product-item-chosen";
                }else{
                    $product_style = "product-item";
                }
                

                    $select = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
                    while($row = mysqli_fetch_assoc($select)){
                ?>

                    <div class="<?php echo $product_style?>">
                        <img src = "uploaded_img/<?php echo $row['image']; ?>" height = "285" alt ="echo $row['image']">
                        <p class = "product-title"><?php echo $row['name']; ?></p>
                        <p class = "product-description"><?php echo $row['description']; ?></p>
                        <p class = "product-price">BYN&nbsp<?php echo $row['price']; ?></p>
                        <p class = "product-description">На складе:&nbsp<?php echo $row['amount']; ?> </p>
                        <?php if(!isset($_SESSION['user_id'])){?>
                            <p class = "product-description" style = "color:red">Чтобы добавить товар в корзину, войдите в аккаунт</p>
                        <?php }?>



                        <button onclick = "window.location.href='product.php?add_to_cart=<?php echo $row['id']; ?>'" class = "user-button" >Добавить в корзину</button>
                        <button class = "backBtn" type="button" onclick="window.location.href='catalog.php'">Назад в каталог</button>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>

    <!-- Footer-->
    <?php require_once ("php/footer.php") ?>
</body>
</html>