<?php

@include 'config.php';

session_start();

$id = $_GET['update'];

if(isset($_POST['update_profile'])){

    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $npassword = mysqli_real_escape_string($conn, $_POST['npassword']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $real_name = mysqli_real_escape_string($conn, $_POST['real_name']);

    $image = $_FILES['acc_image']['name'];
    $image_tmp_name = $_FILES['acc_image']['tmp_name'];
    $image_folder = 'uploaded_img_acc/'.$image;



    $select = mysqli_query($conn, "SELECT * FROM accounts WHERE id = '$id' ");


    $user = mysqli_fetch_assoc($select);
    if($user['password'] != $password){
        $message[] = 'Пароли не совпадают!';
    }else{
        // Определяет, обновлять ли пароль и/или изображение
        if($image == NULL){
            if ($npassword == NULL){
                $update = "UPDATE accounts SET name = '$name', email = '$email', country = '$country', city = '$city', address = '$address', phone = '$phone',
                 real_name = '$real_name' WHERE id = $id";
            }else{
                $update = "UPDATE accounts SET name = '$name', email = '$email', country = '$country', city = '$city', address = '$address',
                phone = '$phone', real_name = '$real_name', password = '$npassword' WHERE id = $id";
            }
        }else{
            if ($npassword == NULL){
                $update = "UPDATE accounts SET name = '$name', email = '$email', country = '$country', city = '$city', address = '$address', phone = '$phone',
                 real_name = '$real_name', image = '$image' WHERE id = $id";
            }else{
                $update = "UPDATE accounts SET name = '$name', email = '$email', country = '$country', city = '$city', address = '$address',
                phone = '$phone', real_name = '$real_name', password = '$npassword', image = '$image' WHERE id = $id";
            }

        }


        

        $upload = mysqli_query($conn,$update);

        if($upload){
            if($image != 'Empty_profile.png'){
                move_uploaded_file($image_tmp_name,  $image_folder);
            }
            $message[] = 'Аккаунт успешно отредактирован';
        }else{
            $message[] = 'Не получилось изменить аккаунт';
        }
        header('location:profile.php');
    }

    


}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование аккаунта</title>
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
        <div class="wrapper wrapper-light">
            <h2 class="title-h2 title-h2-noMargin">Редактировать профиль</h2>           
            <div class="user">
                <form action = "" method = "POST" enctype="multipart/form-data">  
                    <div class="user-container">
                         <?php
                         
                         if(isset($message)){
                            foreach($message as $message){
                                echo '<span class="message">'.$message.'</span>';
                            }
                        }

                        $select = mysqli_query($conn, "SELECT * FROM accounts WHERE id = $id");
                        $user = mysqli_fetch_assoc($select);
                         
                         ?>  
                        <label>Имя пользователя : </label>   
                        <input  class = "user-field" type="text" value = "<?php echo $user['name']?>" laceholder="Имя пользователя" name="username" required>  
                        <label>Пароль : </label>   
                        <input class = "user-field" type="password" placeholder="Подтвердите пароль" name="password" required>
                        <label>Новый пароль : </label>   
                        <input class = "user-field" type="password" placeholder="Новый пароль" name="npassword"> 
                        <label>Настоящее имя: </label>   
                        <input  class = "user-field" type="text" value = "<?php echo $user['real_name']?>" placeholder="Имя" name="real_name" >   
                        <label>Место проживания : </label>   
                        <input  class = "user-field" type="text" value = "<?php echo $user['country']?>" placeholder="Страна" name="country" required>  
                        <input  class = "user-field" type="text" value = "<?php echo $user['city']?>" placeholder="Населённый пункт" name="city" required>
                        <input  class = "user-field" type="text" value = "<?php echo $user['address']?>" placeholder="Адрес" name="address" required>
                        <label>Телефон : </label>   
                        <input  class = "user-field" type="text"  value = "<?php echo $user['phone']?>" placeholder="Телефон"  name="phone"> 
                        <label>Электронная почта : </label>   
                        <input  class = "user-field" type="text" value = "<?php echo $user['email']?>" placeholder="Электронная почта" name="email" required>
                        <label>Иконка: </label>
                        <input type="file" accept="image/png,image/jpeg, image/jpg" name="acc_image" class="user-field">       
                        <button class="user-button" name = "update_profile" type="submit">Редактировать</button>       
                        <button class = "backBtn" type="button" onclick="javascript:history.back()">Назад</button>
                    </div>   
                </form>     
            </div>
          
        </div>
    </div>  
    <!-- Footer-->
    <?php require_once ("php/footer.php") ?>
</body>
</html>