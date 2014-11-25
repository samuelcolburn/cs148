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
        if ($path_parts['filename'] == "form") {
            print '<li><a href="form.php">Form</a></li>';
        } else {
            print '<li><a href="form.php">Form</a></li>';
        }
        ?>
    </ol>
</nav>