<?php
//this is a  page to display a product.
include "top.php";



$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";


//get username
if (isset($_GET["id"])) {

    //sanitize username
    $pmkProductID = htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");

    // @@@@ SQL @@@
    //Select product from tblProducts with the given productID
    $data = array($pmkProductID);

    $query = "SELECT pmkProductID , fldProductName , fldDescription , fldDateSubmitted , fldPrice , fldImage, fldCategoryName as 'fldCategory' FROM tblProducts,tblCategories WHERE pmkProductID = ? AND pmkCategoryID = fnkCategoryID ";

    //@@@ STORE  results
    $results = $thisDatabase->select($query, $data);
    //@@@ STORE  results
    $results = $thisDatabase->select($query, $data);
    $ProductName = $results[0]["fldProductName"];
    $Description = $results[0]["fldDescription"];
    $DateSubmitted = $results[0]["fldDateSubmitted"];
    $Price = $results[0]["fldPrice"];
    $Image = $results[0]["fldImage"];
    $CategoryID = $results[0]["fnkCategoryID"];
    $CategoryName = $results[0]["fldCategoryName"];

    if ($debug) {

        print "<p>Product:</p>";
        print_r($data);
        print $query;
        print_r($results);
    }

    //basic tags
    print "<article id=main>";

    //title of the page is the name of the user
    print "<h2>" . $ProductName . "</h2>";
    // print edit and delte buttons only if user or admin 
    if ($_SESSION["admin"]) {
        print"<p><a href = addproduct.php?id=" . $pmkProductID . ">Edit</a></p>";

        print"<p><a href = deleteuser.php?user=" . $pmkProductID . ">DELETE</a></p>";
    }



    if ($debug) {
        print "<p>pmk = " . $ProductID . "</p>";
    }




    //@@@@ DISPLAY RESULTS @@@@
    // check if their username is the same as the person logged in, or if admin.
    // only display account information if its the user or the admin.



    print "<table class = userinfo>";
    foreach ($results as $row) {
        foreach ($row as $field => $value) {

            if (!is_int($field)) {
                print "<tr>";
                $field = preg_replace(' /(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', substr($field, 3));

                print "<td>" . $field /*  comment out added spaces . "&emsp;&emsp;" */ . "</td>";
                print "<td>" . $value . "</td>";
                print"</tr>\n";
            }
        }
    }

    print"</table>";

//@@@@@@@@ PRODUCT COMMENTS @@@@@@@@@
    print"<h3>Comments</h3>\n";

    //create data array
    $data = array($pmkProductID);

    //build query
    $query = 'SELECT  fldRating , fldText , fldUsername  ,  fldDateSubmitted ';
    $query .= " FROM tblComments, tblUsers ";
    $query .= " WHERE fnkUserID = pmkUserId ";
    $query .= "AND fnkProductID = ? ";
    $query .=" ORDER BY fldDateSubmitted ";

    //execute query
    $results = $thisDatabase->select($query, $data);

    if(!empty($results)){
    if ($debug) {

        print "<p>Product:</p>";
        print_r($data);
        print "<p>query:" . $query . "</p>";
        print_r($results);
    }
    //Display results
    print "<table>\n";
    $firstTime = true;
    foreach ($results as $row) {       
        if ($firstTime) {
            print '<thead><tr id = "tableheader">';
            $keys = array_keys($row);
            foreach ($keys as $key) {

                if (!is_int($key)) {
                    $key = preg_replace(' /(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', substr($key, 3));
                    print "<th>" . $key . "</th>";
                }
            }
            print "</tr>";
            print "</thead>";
            $firstTime = false;
        }
        
        print "<tr>\n";
        foreach ($row as $field => $value) {


            if (!is_int($field)) {
                // print "<tr>\n";
                $field = preg_replace(' /(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', substr($field, 3));

                print "<td>" . $value . "</td>\n";
                // print"</tr>\n";
            }
        }
        print "</tr>\n";
    }

    print"</table>\n";
    }else{
        print"<p>No comments yet!</p>";
    }
    if ($_SESSION["user"]) {
        include "comment.php";
    }
    else{
        print"<p>Login or Register to Comment!</p>";
        print"<p><a href='register.php'>Register</a></p>";
        print"<p><a href='login.php'>Login</a></p>";
    }
    
} else {
    print "<p>Sorry, that product cannot be found.</p>";
}
//@@ FOOTER @@
include "footer.php";

if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</article>
</body>
</html>