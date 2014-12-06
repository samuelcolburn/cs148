<!-- ######################     Page header   ############################## -->
<header>
    <div class =login>
        <?php
        if ($_SESSION["user"]){
            print'<span> <a href = "user.php?user='.$session_username.' ">'.$session_username.'</a></span>';
            print'<span><a href = "logout.php">Logout</a></span>';
        }
        else{ ?>
                <span><a href ='register.php'>Register</a></span>
        <span><a href ='login.php'>Login</a></span>
            
            <?php
        }       ?>

    </div>
    <h1><a href = 'home.php'>Assignment 10</a></h1>
</header>