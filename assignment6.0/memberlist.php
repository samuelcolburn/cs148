<?php

include "top.php";

print "<article id=main>";
print "<h2>Member List</h2>";

$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;

    
$query = "SELECT fldUsername as 'Username' , fldPassword as 'Password' , fldEmail as 'Email' , pmkRegisterId as 'Registration ID', fldDateJoined as 'Date Joined' FROM tblRegister WHERE fldApproved = 1";
$data = array();
$results = $thisDatabase->select($query, $data);

if ($debug)
    print $query;
// gets the field names from the associative array
$keys = array_keys($row);

preg_replace(' /(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', substr($key, 3));

    $numberRecords = count($results);

    print "<h3>Total Members: " . $numberRecords . "</h3>";
    
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
        $firstTime = false;
    }

    /* display the data, the array is both associative and index so we are
     *  skipping the index otherwise records are doubled up */
    print "<tr>";
    foreach ($row as $field => $value) {
        if (!is_int($field)) {
            print "<td>" . $value . "</td>\n";
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