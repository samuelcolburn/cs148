<?php
//this is a  page to display a user's profile.
include "top.php";



$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";


//get username
if (isset($_GET["user"])) {

    //sanitize username
    $username = htmlentities($_GET["user"], ENT_QUOTES, "UTF-8");

    //basic tags
    print "<article id=main>";

    //title of the page is the name of the user
    print "<h2>" . $username . "</h2>";


    if ($debug) {
        print "<p>username = " . $username . "</p>";
    }

    // @@@@ SQL @@@
    //Select user from tblUsers with the given username
    $data = array($username);

    $query = "SELECT fldUsername , fldPassword, fldEmail , fldDateJoined , fldPermissionLevel ,pmkUserId FROM tblUsers WHERE fldUsername = ?";

    //@@@ STORE  results
    $results = $thisDatabase->select($query, $data);
    $UserID = $results[0]["pmkUserId"];
    
    if($debug){
        print "<p>pmk=". $UserID."</p>";
        print "<p>query = ".$query."</p>";
        print_r($data);
         print_r($results);
    }
    


    //@@@@ DISPLAY RESULTS @@@@
    print "<h3> Account Information </h3>";

   // print "<table class = userinfo>";
    foreach ($results as $row) {
        foreach ($row as $field => $value) {
           // print "<tr>";
            if (!is_int($field)) {

                $field = preg_replace(' /(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', substr($field, 3));

                    print "<p>" . $field. "&emsp;&emsp;";
                print $value . "</p>\n";
            }
           // print"</tr>";
        }
    }
   // print"</table>";

    print"<p><a href = edituser.php?user=".$username.">Edit</a></p>";



    //@@@ Select information from tblProfile connected to the user @@@
    

    $data = array($UserID);


    $query = "SELECT fldFirstName, fldLastName , fldGender , fldAge , fldAboutMe  FROM tblProfile WHERE fnkUserId = ? ";

    //@@@ STORE  query results @@@
    $results = $thisDatabase->select($query, $data);

    // @@@@ DISPLAY PROFILE RESULTS @@@
    print "<h3>Personal Profile</h3>";
    
    if($debug){
        print "<p>pmk=". $UserID."</p>";
        print "<p>query = ".$query."</p>";
        print_r($data);
         print_r($results);
    }
   
    print "<table class = personalinfo>";

    foreach ($results as $row) {
        foreach ($row as $field => $value) {
            print "<tr>";
            if (!is_int($field)) {

                $field = preg_replace(' /(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', substr($field, 3));

                print "<td>" . $field . "</td>";
                print "<td>" . $value . "</td>\n";
            }
            print"</tr>";
        }
    }
    print"</table>";
    
    print"<p><a href = editprofile.php?user=".$username.">Edit</a></p>";
    
    print"<p><a href = deleteuser.php?user=".$username.">DELETE ACCOUNT</a></p>";
}

//@@ FOOTER @@
include "footer.php";

if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</article>
</body>
</html>