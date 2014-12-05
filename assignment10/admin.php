<?php
//this is a  page to display a user's profile.
include "top.php";



$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";
?>


<article>
    <p><a href = 'userlist.php'>User List</a></p>
    <p><a href = 'products.php'>Product List</a></p>


<?php
//@@ FOOTER @@
include "footer.php";

if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</article>
</body>
</html>
