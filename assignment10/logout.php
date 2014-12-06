<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Assignment 10</title>
        <meta charset="utf-8">
        <meta name="author" content="Sam Colburn">
        <meta name="description" content="product website">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="style.css" type="text/css" media="screen">

        <?php
        // Start the session
        session_start();
        $debug = false;



// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
// Finally, destroy the session.
session_destroy();

//Redirect back to home page
//header("Location: https://smcolbur.w3.uvm.edu/cs148/assignment10/home.php");




//exit;

?>

<script type="text/javascript">
<!--
function delayer(){
    window.location = "home.php"
}
//-->
</script>
</head>
<body onLoad="setTimeout('delayer()', 0000)">
<h2>You have been logged out.</h2>
<p>You should be redirected to the home page. If not, click this link: <a href ='home.php'> home</a>.</p>

<?php

include "footer.php";

if ($debug)
    print "<p>END OF PROCESSING</p>";

?>
</article>
</body>
</html>
<?php
die();
?>