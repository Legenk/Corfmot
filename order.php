<?php

@include 'config.php';

session_start();


if(isset($_POST['confirm'])){
    //Убрать корзину, добавить заказы
    $user_id = $_SESSION['user_id'];
    $payment_method_id = $_POST['payment'];
    $select = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");
    while($row = mysqli_fetch_assoc($select)){

        $product_id = $row['product_id'];
        //Изменить кол-во доступных товаров
        $fetch_products = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
        $fetch_product = mysqli_fetch_assoc($fetch_products);
        $new_amount = $fetch_product['amount'] - $row['amount'];
        mysqli_query($conn,"UPDATE products SET amount = $new_amount WHERE id = $product_id");
        //Добавить в заказы
        $amount = $row['amount'];
        $insert = "INSERT INTO orders(user_id, product_id, amount, payment_method_id, date) VALUES($user_id, $product_id, $amount, $payment_method_id, CURRENT_TIMESTAMP)";
        $upload = mysqli_query($conn,$insert);
        $cart_id = $row['id'];
        mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id");

    }

    header('location:index.php');
}




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
            <?php
                $is_overOrdered = 0;
                $final_price = 0;
                $select = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");

                while($row = mysqli_fetch_assoc($select)){
                    $product_id = $row['product_id'];
                    $fetch_products = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
                    $fetch_product = mysqli_fetch_assoc($fetch_products);
                    if($fetch_product['amount'] < $row['amount']){
                        $is_overOrdered = 1;
                    }
                ?>

                    <div class="product-item">
                        <img src = "uploaded_img/<?php echo $fetch_product['image']; ?>" height = "285" alt ="<?php echo $fetch_product['image']?>">
                        <p class = "product-title"><?php echo $fetch_product['name']; ?></p>
                        <p class = "product-description"><?php echo $fetch_product['description']; ?></p>
                        <p class = "product-price">BYN&nbsp<?php echo $fetch_product['price']; ?></p>
                        <p class = "product-description">На складе:&nbsp<?php echo $fetch_product['amount']; ?> </p>
                        <p class = product-description>    
                            В корзине:&nbsp<?php echo $row['amount']; ?>  
                        </p>
                    </div>

                    <?php $final_price += $fetch_product['price'] * $row['amount'] ?>
    
            <?php }?>
            <h1>Итоговая цена:  <?php echo $final_price?> BYN</h1>
            <?php if($is_overOrdered){?>
                <h2 style = "color:red">Внимание: будут заказаны товары, отсутствующие на складе</h2>
            <?php }?>

            <!-- Выбор доставки. Муторнее, чем ожидалось-->
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                <label for="payment">Способ оплаты:</label>
                <select name="payment" id="payment">

                <?php
                $select = mysqli_query($conn, "SELECT * FROM payment_method");
                while($row = mysqli_fetch_assoc($select)){ ?>

                    <option value="<?php echo $row['id']?>"><?php echo $row['description']?></option>
                <?php }?>	            
                </select>
                    <button type="submit" class="user-button" name="confirm">Подтвердить </a></button>
            </form>



            <!--<button  class="user-button" onclick="window.location.href='order.php?confirm=1'">Подтвердить </a></button>-->
            <button  class="backBtn" onclick="window.location.href='cart.php'">Назад </a></button>

        </div>
        </div>
    </div>
    <!-- footer -->
    <?php require_once ("php/footer.php") ?>
</body>
</html>
