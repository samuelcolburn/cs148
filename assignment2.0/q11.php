<?php
$debug = false;
//############################################################################
//
// This page displays the results of a query that is located in a text file.
//
//############################################################################
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CS 148 Select</title>
        <meta charset="utf-8">
        <meta name="author" content="Sam Colburn">
        <meta name="description" content="Select queries for assignment 2.0">
        <style type="text/css">

            table{
                border: medium #000080 solid;
                border-collapse: collapse;
                width: 90%;
                margin: auto;
                max-width: 600px;
            }

            td, th {
                border: thin #000080 solid;
                border-collapse: collapse;
            }

            tr:nth-child(even) {
                background-color: lightcyan;
            }

            tr:nth-child(odd){
                background-color: whitesmoke;
            }
        </style>

        <!--[if lt IE 9]>
            <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
        <![endif]-->

    </head>


    <?php
    
    /* ##### Step one 
     * 
     * create your database object using the appropriate database username

    */
    require_once('../bin/myDatabase.php');

    $dbUserName = get_current_user() . '_reader';
    $whichPass = "r"; //flag for which one to use.
    $dbName = strtoupper(get_current_user()) . '_UVM_Courses';

    $thisDatabase = new myDatabase($dbUserName, $whichPass, $dbName);


    /* ##### html setup */
    
    $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
    $path_parts = pathinfo($phpSelf);
    print '<body id="' . $path_parts['filename'] . '">';

    
    /* ##### Step two 
     * 
     * open the file that contains the query

    */
    $myfile = fopen("q11.sql", "r") or die("Unable to open file!");
    $query = fread($myfile, filesize("q10.sql"));


    /* ##### Step three
     * Execute the query

     *      */
    $results = $thisDatabase->select($query);

    
     /* ##### Step four
     * prepare output and loop through array

     *      */
    $numberRecords = count($results);

    print "<h2>Total Records: " . $numberRecords . "</h2>";
    print "<h3>SQL: " . $query . "</h3>";

    print "<table>";

    $firstTime = true;

    /* since it is associative array display the field names */
    foreach ($results as $row) {
        if ($firstTime) {
            print "<thead><tr>";
            $keys = array_keys($row);
            foreach ($keys as $key) {
                if (!is_int($key)) {
                    print "<th>" . $key . "</th>";
                }
            }
            print "</tr>";
            $firstTime = false;
        }
        
        /* display the data, the array is both associative and index so we are
         *  skipping the index otherwise records are doubled up */
        print "<tr>";
        foreach ($row as $field => $value) {
            if (!is_int($field)) {
                print "<td>" . $value . "</td>";
            }
        }
        print "</tr>";
    }
    print "</table>";
    ?>
</body>
</html>
