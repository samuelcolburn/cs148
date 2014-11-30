<?php
//this is a  page to display a user's profile.
include "top.php";



$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";


if (isset($_GET["user"])) {

    $username = htmlentities($_GET["user"], ENT_QUOTES, "UTF-8");

    print "<article id=main>";
    
    print "<h2>".$username."</h2>";
    if ($debug) {
        print "<p>username = " . $username . "</p>";
    }

    $data = array($username);

    $query = "SELECT * FROM tblUsers WHERE fldUsername = ?";

    $results = $thisDatabase->select($query, $data);

    print"<p>";
    print_r($results);
    print "</p>";

    $UserID = $results[0]["pmkUserId"];

    $data = array($UserID);

    $query = "SELECT * FROM tblProfile WHERE fnkUserId = ? ";

    $results = $thisDatabase->select($query, $data);

    print"<p>";
    print $query;
    print_r($results);
    print "</p>";
}
include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</article>
</body>
</html>