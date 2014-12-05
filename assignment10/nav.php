<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
        /* HOME */
        if ($path_parts['filename'] == "home") {
            print '<li><a href="home.php">Home</a></li>';
        } else {
            print '<li><a href="home.php">Home</a></li>';
        }

        /* PRODUCT LIST PAGE */
        if ($path_parts['filename'] == "products") {
            print '<li><a href="products.php">Products</a></li>';
        } else {
            print '<li><a href="products.php">Products</a></li>';
        }
        /* ADMIN PAGE */
                if ($path_parts['filename'] == "admin") {
            print '<li><a href="admin.php">Admin</a></li>';
        } else {
            print '<li><a href="admin.php">Admin</a></li>';
        }
        ?>
    </ol>
</nav>