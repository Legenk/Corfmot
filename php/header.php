    <header class="header" id = "header">
        <a href="index.php">
            <img src = "img/Corfmot.png">
        </a>
        <nav>
            <ul class = "nav"> 
                <li class = "nav-item"><a class = "nav-link" href="catalog.php">Продукты</a></li>
                <!-- <li class = "nav-item"><a class = "nav-link" href="">Комнаты</a></li>
                <li class = "nav-item"><a class = "nav-link" href="">Вдохновения</a></li> !-->
            </ul>
        </nav>
        <form action = "catalog.php" method = "post">
            <div class = "searchbar">
                <input class = "searchbar-field" name = "searchtext" type = "text" placeholder = "Поиск">
                <button name = "search" type = "submit" class=searchbar-button onclick="window.location.href='admin_page.php'" ><img src="img/Search.svg" style = "position: relative; top:4px"></button>
            </div>
        </form>
        <div class = "heart">
        <!--     <img src = "img/Heart.svg">!-->
        </div>
        <div class = "cart">

            <a href="cart.php">
                <img src="img/Cart.svg">
            </a>



        </div>
        <div class="user_icon">

            <?php    if(isset($_SESSION['user_id'])){    
            $user_id = $_SESSION['user_id'];
            $select_user = mysqli_query($conn, "SELECT * FROM accounts WHERE id = '$user_id'") or die('query failed');
            if(mysqli_num_rows($select_user) > 0){
                $fetch_user = mysqli_fetch_assoc($select_user);
            }
            ?>

            <a href="profile.php">
                <img src = "uploaded_img_acc/<?php echo $fetch_user['image']; ?>" height = "40" alt ="<?php echo $fetch_user['image']?>" title = "<?php echo $fetch_user['name']?>">
            </a>

            <?php  }else{?>
            <a href="login.php">
                <img src = "img/Empty_profile.png" title = "Войти" width = 40px>
            </a>
            <?php } ?>
        </div>
    </header>