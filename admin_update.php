<?php

@include 'config.php';

$id = $_GET['edit'];

//редактировать
if(isset($_POST['update_product'])){

    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_amount = $_POST['product_amount'];
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/'.$product_image;
    if($product_image == NULL){
        $update = "UPDATE products SET name = '$product_name', description = '$product_description', price = '$product_price', amount = $product_amount
        WHERE id = $id";
    }
    else {
        $update = "UPDATE products SET name = '$product_name', description = '$product_description', price = '$product_price', image = '$product_image', amount = $product_amount
    WHERE id = $id";
    }
    $upload = mysqli_query($conn,$update);

    if($upload){
        move_uploaded_file($product_image_tmp_name, $product_image_folder);
        $message[] = 'Товар успешно изменён';
    }else{
        $message[] = 'Не получилось изменить товар';
    }
    header('location:admin_page.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin_update</title>
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
<div class="page">
    <div class="wrapper wrapper-light">
        <div class="user">

        <?php
    
        $select = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
        while($row = mysqli_fetch_assoc($select)){
    
        ?>
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <div class="user-container">
                    <h3>Редактировать информацию</h3>
                    <input type="text" placeholder="enter product name" value = "<?php echo $row['name']?>" name="product_name" class="user-field" required>
                    <input type="text" placeholder="enter product name" value = "<?php echo $row['description']?>" name="product_description" class="user-field" required>
                    <input type="number" placeholder="enter product name" step = "0.01" value = "<?php echo $row['price']?>" name="product_price" class="user-field" required>
                    <input class = "user-field" type="number" placeholder="Введите количество товара на складе" value = "<?php echo $row['amount']?>" name="product_amount" required>   
                    <input type="file" accept="image/png,image/jpeg, image/jpg" name="product_image" class="user-field">
                    <input type ="submit" class="user-button" name="update_product" value = "Редактировать">
                    <button class = "backBtn" type="button" onclick="javascript:history.back()">Назад</button>

                </div>

                </form>
            </div>
        <?php }; ?>
    </div>
</div>



</body>


</html>