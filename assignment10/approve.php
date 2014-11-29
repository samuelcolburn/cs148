<?php
/* the purpose of this page is to accept the hashed date joined and primary key  
 * as passed into this page in the GET format.
 * 
 * I retrieve the date joined from the table for this person and verify that 
 * they are the same. After which i update the confirmed field and acknowlege 
 * to the user they were successful. Then i send an email to the system admin 
 * to approve their membership 
 * 

 * 
 * 
 */

include "top.php";

print '<article id="main">';


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = false;
if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = false;
}
if ($debug)
    print "<p>DEBUG MODE IS ON</p>";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%

$adminEmail = "samuel.colburn@uvm.edu";
$message = "<p>I am sorry but this project cannot be confirmed at this time. Please call  0118 999 881 999 119 7253  for help in resolving this matter.</p>";


//##############################################################
//
// SECTION: 2 
// 
// process request

if (isset($_GET["q"])) {



    $key1 = htmlentities($_GET["q"], ENT_QUOTES, "UTF-8");
    $key2 = htmlentities($_GET["w"], ENT_QUOTES, "UTF-8");


    $data = array($key2);
    //##############################################################
    // get the membership record 

    $query = "SELECT fldUsername , fldDateJoined, fldEmail, pmkUserId FROM tblUsers WHERE pmkUserId = ? ";

    $results = $thisDatabase->select($query, $data);




    $dateSubmitted = $results[0]["fldDateJoined"];
    $email = $results[0]["fldEmail"];
    $registerID = $results[0]["pmkUserId"];
    $Username = $results[0]["fldUsername"];

    //$key1crypt = sha1($key1);
    //$k1 = sha1($registerID);

    $k1 = sha1($dateSubmitted);
            
    if ($debug) {
        print $key2;
        print_r($data);
        print "<p>Date: " . $dateSubmitted;
        print "<p>email: " . $email;
        print "<p><pre>";
        print_r($results);
        print "</pre></p>";
        print "<p>k1: " . $k1;
        print "<p>key1crypt:    " . $key1crypt;
        print "<p>q : " . $key1;
    }
    //##############################################################
    // update confirmed
    if ($key1 == $k1) {
        if ($debug)
            print "<h1>Approved</h1>";

        $query = "UPDATE tblUsers set fldPermissionLevel = 2 WHERE pmkUserId = ? ";
        $results = $thisDatabase->update($query, $data);

        if ($debug) {
            print "<p>Query: " . $query;
            print "<p><pre>";
            print_r($results);
            print_r($data);
            print "</pre></p>";
        }


        // notify user
        $to = $email;
        $cc = "";
        $bcc = "";
        $from = "Assignment 10 <noreply@yoursite.com>";
        $subject = "Your Registration has been Approved";
        $message = '<p>You are now officially a member of Assignment 10! To access all of our great content, head over to <a href="' . $domain . $path_parts["dirname"] . '/home.php"> Assignment 10 </a> or if you would like to see our special offer on powdered donuts, please look under your seat.</p>';
        $message .="<p>pringles in uncomfortable places.</p>";
        
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);

           
        print"<h2>Approved</h2>";
        print"<p>User:   ".$Username."</p>";
        print"<p>Email:   ".$email."</p>";
        print"<p>Registration ID:   ".$registerID."</p>";
        
        print"<p><a href = 'memberlist.php'>Full Approved Member List</a></p>";








        // notify admin
        /*
          $message = '<h2>The following Registration has been Approved:</h2>';
          $message .= '<p>Username:   ' . $Username . '</p>';
          $message .= '<p>Email: ' . $email . '</p>';
          $message .='<p>Registration ID: ' . $registerID . '</p>';
          $message .= "<p>To view the current member list click here: ";
          $message .= '<a href="' . $domain . $path_parts["dirname"] . '/memberlist.php">Member List</a></p>';


          print $message;

          //notify admin
          $to = $adminEmail;
          $cc = "";
          $bcc = "";
          $from = "Assignment 6.0 <noreply@yoursite.com>";
          $subject = "Member Approved:    " . $Username;
          $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);

          if ($debug) {
          print "<p>";
          if (!$mailed) {
          print "NOT ";
          }
          print "mailed to admin " . $to . ".</p>";
          }

          // notify user
          $to = $email;
          $cc = "";
          $bcc = "";
          $from = "Assignment 6.0 <noreply@yoursite.com>";
          $subject = "Your Registration has been Approved";
          $message = '<p>You are now officially a member of Assignment 6.0! To access all of our great content, head over to <a href="' . $domain . $path_parts["dirname"] . '/home.php"> Assignment 6.0 </a> or if you would like to see our special offer on powdered donuts, please look under your seat.</p>';

          $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);


          if ($debug) {
          print $message;
          print "<p>";
          if (!$mailed) {
          print "NOT ";
          }
          print "<p>TO:" . $to . "</p>";
          print "<p>cc:" . $cc . "</p>";
          print "<p>bcc:" . $bcc . "</p>";
          print "<p>from:" . $from . "</p>";
          print "<p>subject:" . $subject . "</p>";
          print "<p>message:" . $message . "</p>";

          print "mailed to member: " . $to . ".</p>";
         * 
         */
    }
} else {
    print $message;


    // }
} // ends isset get q
?>

</article>


<?php
include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</body>
</html>