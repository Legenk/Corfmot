<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
    header('location:index.php');
}

if(isset($_GET['logout'])){
    unset($user_id);
    session_destroy();
    header('location:index.php');
}
                         



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
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
            <?php
            
            $select_user = mysqli_query($conn, "SELECT * FROM accounts WHERE id = '$user_id'") or die('query failed');
            if(mysqli_num_rows($select_user) > 0){
                $fetch_user = mysqli_fetch_assoc($select_user);
            }
  
            ?>

            <div class="user">
            <?php
            if(isset($message)){
                foreach($message as $message){
            echo '<span class="message">'.$message.'</span>';
            }}?>

                <div class="user-container">
                    <p class = "user-text">Имя пользователя:&nbsp <?php echo $fetch_user['name'];?><p>
                    <p class = "user-text">Почта:&nbsp <?php echo $fetch_user['email'];?><p>
                    <p><a href = "profile_update.php?update=<?php echo $user_id; ?>"
                    class = "user-buttonA">Редактировать аккаунт</a></p>
                    <p><a href = "profile.php?logout=<?php echo $user_id; ?>" onclick = "return confirm('Вы точно хотите выйти?')"
                    class = "user-buttonB">Выйти из аккаунта</a></p>
                </div>
            </div>
                     
        </div>
    </div>  
    <!-- Footer-->
    <?php require_once ("php/footer.php") ?>
</body>
</html>