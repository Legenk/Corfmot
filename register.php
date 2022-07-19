<?php

@include 'config.php';

if(isset($_POST['register'])){


    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $real_name = mysqli_real_escape_string($conn, $_POST['real_name']);

    $image = $_FILES['acc_image']['name'];
    $image_tmp_name = $_FILES['acc_image']['tmp_name'];
    $image_folder = 'uploaded_img_acc/'.$image;

    
    if($image == NULL){
        $image = 'Empty_profile.png';
    }
    


    $select = mysqli_query($conn, "SELECT * FROM accounts WHERE name = '$name' ");



    
    
    if(mysqli_num_rows($select) > 0){
        $message[] = 'Аккаунт уже существует!';
        header('location:register.php');
    }else if ($password != $cpassword){
        $message[] = 'Пароли не совпадают!';
        header('location:register.php');
    }else{
        if($image != 'Empty_profile.png'){
            move_uploaded_file($image_tmp_name,  $image_folder);
        }
        mysqli_query($conn, "INSERT into accounts(name, email, password, real_name, country, city, address, phone, image)
         VALUES ('$name', '$email', '$password', '$real_name', '$country', '$city', '$address', '$phone', '$image')");
        $message[] = 'Аккаунт успешно зарегистрирован';
        header('location:login.php');
    }





}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
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
            <h2 class="title-h2 title-h2-noMargin">Регистрация</h2>           
            <div class="user">
                <form action = "register.php" method = "POST" enctype="multipart/form-data" >  
                    <div class="user-container">
                         <?php
                         
                         if(isset($message)){
                            foreach($message as $message){
                                echo '<span class="message">'.$message.'</span>';
                            }
                        }
                         
                         ?>  
                         <br>
                        <label>Имя пользователя : </label>   
                        <input  class = "user-field" type="text" placeholder="Имя пользователя" name="username" required>  
                        <label>Пароль : </label>   
                        <input class = "user-field" type="password" placeholder="Пароль" name="password" required>
                        <input class = "user-field" type="password" placeholder="Подтвердите пароль" name="cpassword" required> 
                        <label>Настоящее имя: </label>   
                        <input  class = "user-field" type="text" placeholder="Имя" name="real_name" >   
                        <label>Место проживания : </label>   
                        <input  class = "user-field" type="text" placeholder="Страна" name="country" required>  
                        <input  class = "user-field" type="text" placeholder="Населённый пункт" name="city" required>
                        <input  class = "user-field" type="text" placeholder="Адрес" name="address" required>
                        <label>Телефон : </label>   
                        <input  class = "user-field" type="text"  placeholder="Телефон"  name="phone"> 
                        <label>Электронная почта : </label>   
                        <input  class = "user-field" type="text" placeholder="Электронная почта" name="email" required>
                        <label>Иконка: </label>
                        <input type="file" accept="image/png,image/jpeg, image/jpg" name="acc_image" class="user-field">     
                        <button class="user-button" name = "register" type="submit">Зарегистрироваться</button>       
                        Уже зарегистрированы? <a href="login.php"> Войти </a>   
                    </div>   
                </form>     
            </div>
          
        </div>
    </div>  
    <!-- Footer-->
    <?php require_once ("php/footer.php") ?>
</body>
</html>