<?php
include "top.php";


$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";

print "<article id=main>\n";

print "<h2>Product Categories</h2>\n";

// if the admin is viewing the product list, have 'add a product' link at the top of the page
if ($_SESSION["admin"]) {
    print "<p><a href = 'addcategory.php'>Add a Product Category</a></p>";


$yourURL = $domain . $phpSelf;


//Get all product data to be displayed
$query = "SELECT fldCategoryName as 'Name' , pmkCategoryID as 'Category ID' FROM tblCategories ORDER BY fldCategoryName ";
$data = array();
$results = $thisDatabase->select($query, $data);




if ($debug)
    print $query;
print $username;

// gets the field names from the associative array
$keys = array_keys($row);


$numberRecords = count($results);

print "<h3>Total Categories: " . $numberRecords . "</h3> \n";

// PRINT TABLE
print "<table>\n";

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
 print "<th>Edit</th>";
    print "<th>Delete</th>";
 
      print "</tr>\n";
         print "</thead>\n";
        $firstTime = false;
    }
   
  
    
    /* display the data, the array is both associative and index so we are
     *  skipping the index otherwise records are doubled up */

    print " <tr>\n";

    foreach ($row as $field => $value) {
        if (!is_int($field)) {

            print "<td>" . $value . "</td>\n";
        }
    }

    print '<td><a href="addcategory.php?id=' . $row[1] . '">[Edit]</a></td> ';
    print '<td><a href="delete.php?id=' . $row[1] .'&amp;table=tblCategories">[Delete]</a></td> ';
    print "</tr>\n";
}
print "</table>\n";
}
else{
    print "<h2>ACCESS DENIED</h2>";
}
include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</article>
</body>
</html>
