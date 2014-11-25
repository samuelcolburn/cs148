<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
        /* This sets the current page to not be a link. Repeat this if block for
         *  each menu item */
        if ($path_parts['filename'] == "home") {
            print '<li><a href="home.php">Home</a></li>';
        } else {
            print '<li><a href="home.php">Home</a></li>';
        }

        /* example of repeating */
        if ($path_parts['filename'] == "crud") {
            print '<li><a href="crud.php">Register</a></li>';
        } else {
            print '<li><a href="crud.php">Register</a></li>';
        }
        ?>
    </ol>
</nav>