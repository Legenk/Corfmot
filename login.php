<?php

@include 'config.php';

session_start();

if(isset($_POST['authorize'])){

    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $select = mysqli_query($conn, "SELECT * FROM accounts WHERE name = '$name' AND password = '$password'");

    
    if(mysqli_num_rows($select) > 0){
        $row = mysqli_fetch_assoc($select);
        $_SESSION['user_id'] = $row['id'];
        header('location:index.php');
    }else{
        $message[] = 'Неверный логин или пароль';
        //Вывести оповещением
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
    <title>Вход</title>
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
            <h2 class="title-h2 title-h2-noMargin">Авторизация</h2>
            <div class="user">
            <?php
                         
                         if(isset($message)){
                            foreach($message as $message){
                                echo '<span class="message">'.$message.'</span>';
                            }
                        }
                         
                         ?>  

                <form action = "" method = "post">  
                    <div class="user-container">   
                        <label>Имя пользователя : </label>   
                        <input  class = "user-field" type="text" placeholder="Имя пользователя" name="username" required>  
                        <label>Пароль : </label>   
                        <input class = "user-field" type="password" placeholder="Пароль" name="password" required>
                        <!--<button class="user-button" type="submit">Войти</button> -->  
                        <input type ="submit" class="user-button" name="authorize" value = "Войти">    
                        Нет аккаунта? <a href="register.php"> Зарегистрироваться </a>   
                    </div>   
                </form>     
            </div>
          
        </div>
    </div>  
    <!-- Footer-->
    <?php require_once ("php/footer.php") ?>
</body>
</html>