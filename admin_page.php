<?php

@include 'config.php';

//Добавление объекта +


if(isset($_POST['add_product'])){

    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_amount = $_POST['product_amount'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/'.$product_image;

    $insert = "INSERT INTO products(name, description, price, image, amount) VALUES('$product_name', '$product_description', '$product_price', '$product_image', '$product_amount')";
    $upload = mysqli_query($conn,$insert);

    if($upload){
        move_uploaded_file($product_image_tmp_name, $product_image_folder);
        $message[] = 'Товар успешно добавлен';
    }else{
        $message[] = 'Не получилось добавить товар';
    }
    header('location:admin_page.php');
}

//Увеличить число товаров
if(isset($_GET['add'])){
    $id = $_GET['add'];
    $update = "UPDATE products SET amount = amount + 1 WHERE id = $id";
    $upload = mysqli_query($conn,$update);
    header('location:admin_page.php');
}

//Уменьшить число товаров
if(isset($_GET['remove'])){
    $id = $_GET['remove'];
    $update = "UPDATE products SET amount = amount - 1 WHERE id = $id";
    $upload = mysqli_query($conn,$update);
    header('location:admin_page.php');
}

//Удаление +


if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header('location:admin_page.php');
};

if(isset($_GET['accept_order'])){
    $id = $_GET['accept_order'];
    $update = "UPDATE orders SET is_approved = 1 WHERE id = $id";
    $upload = mysqli_query($conn,$update);
    header('location:admin_page.php');
}

if(isset($_GET['decline_order'])){
    $id = $_GET['decline_order'];

    //Вернуть товары
    $orders_for_removal = mysqli_query($conn, "SELECT * FROM orders WHERE id = $id");
    $order_for_removal = mysqli_fetch_assoc($orders_for_removal);
    $order_product = $order_for_removal['product_id'];
    $retuning_amount = $order_for_removal['amount'];
    mysqli_query($conn,"UPDATE products SET amount = amount + $retuning_amount WHERE id = $order_product");

    //Удалить заказ
    $update = "DELETE FROM orders WHERE id = $id";
    $upload = mysqli_query($conn,$update);
    header('location:admin_page.php');
}





?>


<!DOCTYPE ! html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php

if(isset($message)){
    foreach($message as $message){
        echo '<span class="message">'.$message.'</span>';
    }


}

?>



<!-- Добавить товар -  форма + -->

    <div class="page">
        <div class="wrapper wrapper-light">
            <h2 class="title-h2 title-h2-noMargin">Добавить товар</h2>
            <div class="user">
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">  
                    <div class="user-container">   
                        <input  class = "user-field" type="text" placeholder="Введите название товара" name="product_name" required>
                        <input  class = "user-field" type="text" placeholder="Введите описание товара" name="product_description" required>    
                        <input class = "user-field" type="number" step = '0.01' placeholder="Введите цену товара (BYN)" name="product_price" required>
                        <input class = "user-field" type="number" step = '1' placeholder="Введите количество товара на складе" name="product_amount" required>    
                        <input type="file" accept="image/png,image/jpeg, image/jpg" name="product_image" class="user-field" required>
                        <input type ="submit" class="user-button" name="add_product" value = "Добавить товар">    
                    </div>   
                </form>     
            </div>
          
        </div>
    </div>  

 


    <div class="page">
        <div class="wrapper wrapper-alter">
<!--            <h2 class = "title-h2">Имеющиеся товары</h2> -->
            <div class = "product">
                <?php

                //Вывод объектов

                
                $select = mysqli_query($conn, "SELECT * FROM products");//LIMIT 0,8
                $is_overOrdered = 0;
                while($row = mysqli_fetch_assoc($select)){
                    if($row['amount'] < 0 ){
                        $is_overOrdered = 1;
                    }


                ?>


                    <div class="product-item">
                        <img src = "uploaded_img/<?php echo $row['image']; ?>" height = "285" alt ="<?php echo $row['image']?>">
                        <p class = "product-title"><?php echo $row['name']; ?></p>
                        <p class = "product-description"><?php echo $row['description']; ?></p>
                        <p class = "product-price">BYN&nbsp<?php echo $row['price']; ?></p>
                        <!--<p class=" product-description">-->
                        <p class = product-description>    
                            <a href="admin_page.php?add=<?php echo $row['id']; ?>" class = "product-increment"> +</a>
                            На складе:&nbsp<?php echo $row['amount']; ?>  
                            <a href="admin_page.php?remove=<?php echo $row['id']; ?>" class = "product-increment"> -</a>
                        </p>
                        <p>
                            <a href="admin_update.php?edit=<?php echo $row['id']; ?>" class = "product-act"> Изменить</a>
                            <a href="admin_page.php?delete=<?php echo $row['id']; ?>" class="product-act"> Удалить </a>
                        </p>


                    </div>


                <?php }
                if($is_overOrdered){?>
                    <h2 style = "color:red">Заказаны товары, отсутствующие на складе</h2>
                <?php }?>

            </div>
        </div>
    </div>
    <!--Заказы-->
    <div class="page">
        <div class="wrapper wrapper-alter">
            <div class = "product">
                <h1>Заказы</h1>
            <?php           
                $select = mysqli_query($conn, "SELECT * FROM orders");
                while($order_row = mysqli_fetch_assoc($select)){
                    if($order_row['is_approved'] == 0){

                        $product_id = $order_row['product_id'];
                        $fetch_products = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
                        $fetch_product = mysqli_fetch_assoc($fetch_products);

                        $user_id = $order_row['user_id'];
                        $fetch_users = mysqli_query($conn, "SELECT * FROM accounts WHERE id = $user_id");
                        $fetch_user = mysqli_fetch_assoc($fetch_users);

                        $method_id = $order_row['payment_method_id'];
                        $fetch_methods = mysqli_query($conn, "SELECT * FROM payment_method WHERE id = $method_id");
                        $fetch_method = mysqli_fetch_assoc($fetch_methods);

                        ?>
        
                            <div class="product-item">
                                <img src = "uploaded_img/<?php echo $fetch_product['image']; ?>" height = "285" alt ="<?php echo $fetch_product['image']?>">
                                <p class = "product-title"><?php echo $fetch_product['name']; ?></p>
                                <!-- <p class = "product-description"><?//php echo $fetch_product['description']; ?></p>-->
                                <p class = "product-price">BYN&nbsp<?php echo $fetch_product['price']; ?></p>
                                <!--<p class = "product-description">На складе:&nbsp<?//php echo $fetch_product['amount']; ?> </p>-->
                                <p class = product-price>    
                                    Заказано:&nbsp<?php echo $order_row['amount']; ?>  
                                </p>
                                <p class = "product-description">Имя заказчика: <?php echo $fetch_user['real_name']; ?></p>
                                <p class = "product-description">Почта: <?php echo $fetch_user['email']; ?></p>
                                <p class = "product-description">Телефон: <?php echo $fetch_user['phone']; ?></p>
                                <p class = "product-description">Адресс:</p>
                                <p class = "product-description"><?php echo $fetch_user['country']; ?>, <?php echo $fetch_user['city']; ?>, <?php echo $fetch_user['address']; ?> </p>
                                <p class = "product-description"><?php echo $fetch_method['description']?> </p>
                                <p class = product-description><?php echo $order_row['date']; ?></p>


                                <button  class="product-act" onclick="window.location.href='admin_page.php?accept_order=<?php echo $order_row['id']; ?>'">Принять заказ</a></button>
                                <button  class="product-act" onclick="window.location.href='admin_page.php?decline_order=<?php echo $order_row['id']; ?>'">Отменить заказ</a></button>
                            </div>
            

                    <?php }?>

            <?php }?>

        </div>
        </div>
    </div>



</body>
</html>


