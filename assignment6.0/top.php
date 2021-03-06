<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Assignment 6.0</title>
        <meta charset="utf-8">
        <meta name="author" content="Sam Colburn">
        <meta name="description" content="sql form">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="style.css" type="text/css" media="screen">

        <?php
        
        // START SESSION
        session_start();
        
 
        $debug = false;

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// PATH SETUP
//
//  $domain = "https://www.uvm.edu" or http://www.uvm.edu;

        $domain = "http://";
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS']) {
                $domain = "https://";
            }
        }

        $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, "UTF-8");

        $domain .= $server;

        $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

        $path_parts = pathinfo($phpSelf);

        if ($debug) {
            print "<p>Domain" . $domain;
            print "<p>php Self" . $phpSelf;
            print "<p>Path Parts<pre>";
            print_r($path_parts);
            print "</pre>";
        }

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// inlcude all libraries
//

        require_once('../bin/lib/security.php');



        include "../bin/lib/validation_functions.php";
        include "../bin/lib/mailMessage.php";


        require_once('../bin/myDatabase.php');

        $dbUserName = get_current_user() . '_writer';
        $whichPass = "w"; //flag for which one to use.
        $dbName = strtoupper(get_current_user()) . '_Register';


        $thisDatabase = new myDatabase($dbUserName, $whichPass, $dbName);
        
        
       // CHECK USER SESSION
        if (!empty($_SESSION["user"])){
            
            $data = array($_SESSION["user"]);
            
            $query = "SELECT FROM tblUsers fldUsername , pmkUserId WHERE fldUsername = ? ";
            
            $results = $thisDatabase->select($query);
            
            $session_username = $results[0]['fldUsername'];
            $session_pmkUserID = $results[0]['fldUserId'];
            
            if ($debug){
                print_r($results);
            }
                  
            
        }
        
        ?>	

    </head>
    <!-- ################ body section ######################### -->

    <?php
    print '<body id="' . $path_parts['filename'] . '">';

    include "header.php";
    include "nav.php";
    ?>