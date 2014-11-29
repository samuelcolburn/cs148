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
 */

include "top.php";

print '<article id="main">';

print '<h2>Registration Confirmed</h2>';

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = true;
if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}
if ($debug)
    print "<p>DEBUG MODE IS ON</p>";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%

$adminEmail = "samuel.colburn@uvm.edu";
$message = "<p>I am sorry but this user cannot be confirmed at this time. Please call (802) 656-1234 for help in resolving this matter.</p>";


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

    $Selectquery = "SELECT fldDateJoined, fldEmail , pmkUserId , fldPermissionLevel , fldUsername FROM tblUsers WHERE pmkUserId = ? ";

    $results = $thisDatabase->select($Selectquery, $data);




    $dateSubmitted = $results[0]["fldDateJoined"];
    $email = $results[0]["fldEmail"];
    $registerID = $results[0]["pmkUserId"];
    $Username = $results[0]["fldUsername"];


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
        print "<p>q : " . $key1;
    }
    //##############################################################
    // update confirmed
    if ($key1 == $k1) {
        if ($debug)
            print "<h1>Confirmed</h1>";

        $UpdateQuery = "UPDATE tblUsers set fldPermissionLevel=1 WHERE pmkUserId = ? ";
        $UpdateResults = $thisDatabase->update($UpdateQuery, $data);

        if ($debug) {
            print "<p>UpdateQuery: " . $UpdateQuery;
            print "<p><pre>";
            print_r($UpdateResults);
            print_r($data);
            print "</pre></p>";
            
            


        }

        

        // notify admin
        $message = '<h2>The following Registration has been confirmed:</h2>';
        $message .= '<p>Username:   ' . $Username . '</p>';
        $message .= '<p>Email:  ' . $email . '</p>';
        $message .='<p>Registration ID: ' . $registerID . '</p>';

        $message .= "<p>Click this link to approve this registration: ";
        $message .= '<a href="' . $domain . $path_parts["dirname"] . '/approve.php?q=' . $key1 . '&amp;w=' . $key2 .'">Approve Registration</a></p>';


        if ($debug)
            print "<p>" . $message;

        $to = $adminEmail;
        $cc = "";
        $bcc = "";
        $from = "Assignment10 <".$adminEmail.";>";
        $subject = "Assignment 10 User Approval:" . $email;
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
        $from = "Assignment 10 <".$adminEmail.">";
        $subject = "Your Registration has been Confirmed";
        $message = "<p>Thank you for taking the time to confirm your registration. Your membership will be reviewed by our site administrator for approval. You will be notified when your account is approved. If you'd like see our special offer on powdered donuts, please look under your seat.</p>";

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);

        print $message;
        if ($debug) {
            print "<p>";
            if (!$mailed) {
                print "NOT ";
            }
            print "mailed to member: " . $to . ".</p>";
        }
    } else {
        print $message;
    }
} // ends isset get q
?>



<?php
include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</article>
</body>
</html>