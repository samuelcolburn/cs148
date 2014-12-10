<?php

include "top.php";


$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";

print "<article id=main>";

print "<h2>Products</h2>";

// if the admin is viewing the product list, have 'add a product' link at the top of the page
if ($_SESSION["admin"]) {
    print "<p><a href = 'addproduct.php'>Add a Product</a></p>";
}

$yourURL = $domain . $phpSelf;


//Get all product data to be displayed
$query = "SELECT pmkProductID as 'ID' , fldProductName as 'Name' , fldPrice as 'Price' , fldDescription as 'Description' , fldCategoryName as 'Category' FROM tblProducts,tblCategories WHERE fnkCategoryID = pmkCategoryID ";
$data = array();
$results = $thisDatabase->select($query, $data);


 
 
if ($debug)
    print $query;
    print $username;
    
// gets the field names from the associative array
$keys = array_keys($row);


    $numberRecords = count($results);
  
    print "<h3>Total Products: " . $numberRecords . "</h3>";
    
// PRINT TABLE
print "<table>";

$firstTime = true;

/* since it is associative array display the field names */
foreach ($results as $row) {
    if ($firstTime) {
        print '<thead><tr id = "tableheader">';
        $keys = array_keys($row);
        foreach ($keys as $key) {

            if (!is_int($key)) {
                print "<th>" . $key . "</th>";
            }
        }
        print "</tr>";
        print "</thead>";
        $firstTime = false;
    
    }

    /* display the data, the array is both associative and index so we are
     *  skipping the index otherwise records are doubled up */
    $ProductID = $row[0];
        
    print ' <tr class = "tablerow" onclick="location.href= ';
    print " 'product.php?id=".$ProductID."' ";
    print ' " >';
    
    foreach ($row as $field => $value) {
        if (!is_int($field)) {
            if($field == "fldPrice"){
                if($debug){
                    print $field;
                }
                print "<td>$" . $value . "</td>\n";
            }else{
            print "<td>" . $value . "</td>\n";
            }
        }
    }
    print "</tr>\n";
}
print "</table>\n";

include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</article>
</body>
</html>
