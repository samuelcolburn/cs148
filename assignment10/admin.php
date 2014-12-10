<?php
//this is a  page to display a user's profile.
include "top.php";



$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";

$message = '<h2> ACCESS DENIED</h2>';

print '<article>';

if ($_SESSION["admin"]) {

    $message = "<p><a href = 'userlist.php'>User List</a></p>";
    $message .= " <p><a href = 'products.php'>Product List</a></p>";
 //   $message .="<p><a href = 'addproduct.php'>Add a Product</a></p>";
 //   $message .="<p><a href = 'addcategory.php'>Add a Product Category</a></p>";
    $message .="<p><a href = 'categorylist.php'>Product Categories</a></p>";
}//ending if admin else clause
//
//~~~~~~ print relevant message
print $message;

//@@ FOOTER @@


include "footer.php";

if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</article>
</body>
</html>
