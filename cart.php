<?php

@include 'config.php';

session_start();

if(isset($message)){
    foreach($message as $message){
        echo '<span class="message">'.$message.'</span>';
    }
}

if(!isset($_SESSION['user_id'])){
    header('location:index.php');
}

//Увеличить число товаров +
if(isset($_GET['add'])){
    $id = $_GET['add'];
    $update = "UPDATE cart SET amount = amount + 1 WHERE id = $id";
    $upload = mysqli_query($conn,$update);
    header('location:cart.php');
}

//Уменьшить число товаров +
if(isset($_GET['remove'])){
    $id = $_GET['remove'];
    //Не уменьшать, если 0
    $cart_amounts = mysqli_query($conn,"SELECT * FROM cart WHERE id = $id");
    $cart_amount = mysqli_fetch_assoc($cart_amounts);
    if($cart_amount['amount'] > 0){
        $update = "UPDATE cart SET amount = amount - 1 WHERE id = $id";
        $upload = mysqli_query($conn,$update);
    }

    header('location:cart.php');
}

//Удаление 
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = $id");
    header('location:cart.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
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
            <div class = "product">
            <?php

                $final_price = 0;
                $select = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");
                while($row = mysqli_fetch_assoc($select)){
                $product_id = $row['product_id'];
                $fetch_products = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
                $fetch_product = mysqli_fetch_assoc($fetch_products);
                ?>

                    <div class="product-item">
                        <img src = "uploaded_img/<?php echo $fetch_product['image']; ?>" height = "285" alt ="<?php echo $fetch_product['image']?>">
                        <p class = "product-title"><?php echo $fetch_product['name']; ?></p>
                        <p class = "product-description"><?php echo $fetch_product['description']; ?></p>
                        <p class = "product-price">BYN&nbsp<?php echo $fetch_product['price']; ?></p>
                        <p class = "product-description">На складе:&nbsp<?php echo $fetch_product['amount']; ?> </p>
                        <p class = product-description>    
                            <a href="cart.php?add=<?php echo $row['id']; ?>" class = "product-increment"> +</a>
                            В корзине:&nbsp<?php echo $row['amount']; ?>  
                            <a href="cart.php?remove=<?php echo $row['id']; ?>" class = "product-increment"> -</a>
                        </p>
                        <button  class="product-act" onclick="window.location.href='cart.php?delete=<?php echo $row['id']; ?>'">Убрать из корзины </a></button>
                    </div>

                    <?php $final_price += $fetch_product['price'] * $row['amount'] ?>
    
            <?php }?>
            <h1>Итоговая цена:  <?php echo $final_price?> BYN</h1>
            <button  class="user-button" onclick="window.location.href='order.php?order'">Заказать </a></button>

        </div>
        </div>
    </div>
   
    <!-- Footer-->
    <?php require_once ("php/footer.php") ?>
</body>
</html>