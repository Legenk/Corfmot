<?php

@include 'config.php';

session_start();

//$user_id = $_SESSION['user_id'];

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
    <title>Corfmot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <!-- Заголовок + заставка-->
    <div class="page">
        <div class="wrapper wrapper-light">
            <?php require_once ("php/header.php") ?>
            <div class="main">
                <div class="background">
                    <img src = "img/Background_room.png">
                </div>
                <div class="banner">
                    <h1 class="banner-first">Качественная мебель только для Вас</h1>  
                    <p class="banner-second"> Наша мель создана из материалов высшего качества, подходящих для дома Вашей мечты</p>
                    <a class="banner-btn" href="catalog.php">Перейти к товарам</a>
                </div>

            </div>
        </div>
    </div>
    <!-- Фичи -->
    <div class="page">
        <div class="wrapper">
            <div class="feature">
                <div class="feature-item">
                    <img src="img/trophy.png" class="feature-item-pic" width="40" height="40" alt="Трофей">
                    <div class="feature-item-text">
                        <h3 class = "feature-item-text-1">Высокое Качество</h3>
                        <p class = "feature-item-text-2">Сделано из лучших материалов</p>
                    </div>
                </div>
                <div class="feature-item">
                    <img src="img/guarantee.png" class="feature-item-pic" width="40" height="40" alt="Гарантия">
                    <div class="feature-item-text">
                        <h3 class = "feature-item-text-1">Гарантия</h3>
                        <p class = "feature-item-text-2">От двух лет</p>
                    </div>
                </div>
                <div class="feature-item">
                    <img src="img/shipping.png" class="feature-item-pic" width="40" height="40" alt="Доставка">
                    <div class="feature-item-text">
                        <h3 class = "feature-item-text-1">Бесплатная Доставка</h3>
                        <p class = "feature-item-text-2">На заказы от 500 BYN</p>
                    </div>
                </div>
                <div class="feature-item">
                    <img src="img/customer-support.png" class="feature-item-pic" width="40" height="40" alt="Поддержка">
                    <div class="feature-item-text">
                        <h3 class = "feature-item-text-1">Поддержка 24/7</h3>
                        <p class = "feature-item-text-2">Всегда с Вами</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Список товаров -->
    <div class="page">
        <div class="wrapper wrapper-alter">
            <h2 class = "title-h2">Наши товары</h2> 
            <div class = "product">
                <?php

                $select = mysqli_query($conn, "SELECT * FROM products LIMIT 0,8");

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
                    <!--    <p class = "product-description">На складе:&nbsp<?//php// echo $row['amount']; ?> </p> -->
                    </div>
                </a>



                <?php }?>

                

            </div>
            <div class="SeeMore">
                <a class="SeeMore-btn" href="catalog.php">Показать все</a>
            </div>
        </div>
    </div>
    <!-- Подвал -->
    <?php require_once ("php/footer.php") ?>
</body>
</html>
