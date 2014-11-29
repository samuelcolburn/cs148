<?php
/* the purpose of this page is to display a form to allow a person to register
 * the form will be sticky meaning if there is a mistake the data previously 
 * entered will be displayed again. Once a form is submitted (to this same page)
 * we first sanitize our data by replacing html codes with the html character.
 * then we check to see if the data is valid. if data is valid enter the data 
 * into the table and we send and dispplay a confirmation email message. 
 * 
 * if the data is incorrect we flag the errors.
 * 
 * Written By: Sam Colburn samuel.colburn@uvm.edu
 * Last updated on: October 22, 2014
 * 
 * 
*/

include "top.php";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
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


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form
$email = "samuel.colburn@uvm.edu";
$Username = '';
$password = '';

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$emailERROR = false;
$UsernameERROR = false;
$passwordERROR = false;


//ERROR CONSTANTS

//Username
$MIN_USERNAME_LENGTH = 6;
$MAX_USERNAME_LENGTH = 15;

//Password
$MIN_PASSWORD_LENGTH = 6;
$MAX_PASSWORD_LENGTH = 15;
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// used for building email message to be sent and displayed
$mailed = false;
$messageA = "";
$messageB = "";
$messageC = "";

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2a Security
///

    if (!securityCheck(true)) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2b Sanitize (clean) data
// remove any potential JavaScript or html code from users input on the
// form. Note it is best to follow the same order as declared in section 1c.
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);

    $Username = htmlentities($_POST["txtUsername"], ENT_QUOTES, "UTF-8");

    $password  =  htmlentities($_POST["Password"], ENT_QUOTES, "UTF-8");

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2c Validation
//
// Validation section. Check each value for possible errors, empty or
// not what we expect. You will need an IF block for each element you will
// check (see above section 1c and 1d). The if blocks should also be in the
// order that the elements appear on your form so that the error messages
// will be in the order they appear. errorMsg will be displayed on the form
// see section 3b. The error flag ($emailERROR) will be used in section 3c.

    //~~~~~~~~~~~EMAIL VALIDATION~~~~~~~~~~
    if ($email == "") {
        $errorMsg[] = "Please enter your email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    }

    
    //~~~~~~~~~~~~~USERNAME VALIDATION~~~~~~~~~~~
    $usernamecheck = "SELECT fldUsername FROM tblUsers WHERE fldUsername = ? ";
    $data = array($Username);
    $username_check_results = $thisDatabase->select($usernamecheck, $data);

    if ($Username == "") {
        $errorMsg[] = "Please enter a username";
        $UsernameERROR = true;              
    } 
    
    elseif (strlen($Username)<$MIN_USERNAME_LENGTH){
        $errorMsg[] = "Username must be longer than 6 characters";
        $UsernameERROR = true;
    }
    elseif (strlen($Username)>$MAX_USERNAME_LENGTH){
        $errorMsg[] = "Username can be no longer than 15 characters";
        $UsernameERROR = true;
    }
    elseif (!verifyAlphaNum2($Username)) {
        $errorMsg[] = "Invalid Username";
        $UsernameERROR = true;
    }
    
    elseif (!empty($username_check_results)) {
        $errorMsg[] = 'That username is already in use. Please choose a different username.';
        $UsernameERROR = true;
    }

    //~~~~~~PASSWORD VALIDATION~~~~~~~~~~~
    if ($password == ''){
        $errorMsg[] = "Please enter a password";
        $passwordERROR = true;
        
    }
    elseif (strlen($password)<$MIN_PASSWORD_LENGTH){
        $errorMsg[] = "Password must be longer than 6 characters";
        $passwordERROR = true;
    }
    elseif (strlen($password)>$MAX_PASSWORD_LENGTH){
        $errorMsg[] = "Password can be no longer than 15 characters";
        $passwordERROR = true;
    }
    elseif (!verifyAlphaNum2($password)){
        $errorMsg[] = "Invalid Password";
        $passwordERROR = true;
    }
  
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2d Process Form - Passed Validation
//
// Process for when the form passes validation (the errorMsg array is empty)
//
    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2e Save Data
        //

        $primaryKey = "";
        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();
            $query = "INSERT INTO tblUsers SET fldEmail = ? , fldUsername = ? , fldPassword = ? ";
            $data = array($email, $Username , $password);
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }
            $results = $thisDatabase->insert($query, $data);


            $primaryKey = $thisDatabase->lastInsert();
            if ($debug)
                print "<p>pmk= " . $primaryKey;

// all sql statements are done so lets commit to our changes
            $dataEntered = $thisDatabase->db->commit();
            $dataEntered = true;
            if ($debug)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabase->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accpeting your data please contact us directly.";
        }
        // If the transaction was successful, give success message
        if ($dataEntered) {
            if ($debug)
                print "<p>data entered now prepare keys ";
            //#################################################################
            // create a key value for confirmation

            $query = "SELECT fldDateJoined FROM tblUsers WHERE pmkUserId=" . $primaryKey;
            $results = $thisDatabase->select($query);


            $dateSubmitted = $results[0]["fldDateJoined"];

            $key1 = sha1($dateSubmitted);
            $key2 = $primaryKey;

            if ($debug)
                print "<p>key 1: " . $key1;
            if ($debug)
                print "<p>key 2: " . $key2;


            //#################################################################
            //
            //Put forms information into a variable to print on the screen
            //

            $messageA = '<h2>Thank you for registering.</h2>';

            $messageB = "<p>Click this link to confirm your registration: ";
            $messageB .= '<a href="' . $domain . $path_parts["dirname"] . '/confirmation.php?q=' . $key1 . '&amp;w=' . $key2 . '">Confirm Registration</a></p>';
            $messageB .= "<p>or copy and paste this url into a web browser: ";
            $messageB .= $domain . $path_parts["dirname"] . '/confirmation.php?q=' . $key1 . '&amp;w=' . $key2 . "</p>";
            $messageB .= "<p><b>Username:</b>   " . $Username . "</p>";
            $messageB .= "<p><b>Password:</b>   " . $password . "</p>";
            
            $messageC .= "<p><b>Email Address:</b>  " . $email . "</p>";



            //##############################################################
            //
            // email the form's information
            //
            $to = $email; // the person who filled out the form
            $cc = "";
            $bcc = "";
            $from = "Assignment 10 <samuel.colburn@uvm.edu>";
            $subject = "Thank you for Registering at Assignment10!";

            $mailed = sendMail($to, $cc, $bcc, $from, $subject, $messageA . $messageB . $messageC);
        } //data entered  
    } // end form is valid
} // ends if form was submitted.
//#############################################################################
//
// SECTION 3 Display Form
//
?>
<article id="main">
    <?php
//####################################
//
// SECTION 3a.
//
//
//
//
// If its the first time coming to the form or there are errors we are going
// to display the form.
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print "<h2>Your Request has ";
        if (!$mailed) {
            print "not ";
        }
        print "been processed</h2>";
        print "<p>An email with your registration information has ";
        if (!$mailed) {
            print "not ";
        }
        print "been sent";
        print " to " . $email . " requesting confirmation. Please check your email to confirm your registration.</p>";
    } else {
//####################################
//
// SECTION 3b Error Messages
//
// display any error messages before we print out the form
        if ($errorMsg) {
            print '<div id="errors">';
            print "<ol>\n";
            foreach ($errorMsg as $err) {
                print "<li>" . $err . "</li>\n";
            }
            print "</ol>\n";
            print '</div>';
        }
//####################################
//
// SECTION 3c html Form
//
        /* Display the HTML form. note that the action is to this same page. $phpSelf
          is defined in top.php
          NOTE the line:
          value="<?php print $email; ?>
          this makes the form sticky by displaying either the initial default value (line 35)
          or the value they typed in (line 84)
          NOTE this line:
          <?php if($emailERROR) print 'class="mistake"'; ?>
          this prints out a css class so that we can highlight the background etc. to
          make it stand out that a mistake happened here.
         */
        ?>
        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">
            <fieldset class="wrapper">
                <legend>Register Today</legend>
                <fieldset class="wrapperTwo">
                    <legend>Please complete the following form</legend>
                    <fieldset class="contact">
                        <legend></legend>
                        <label for="txtUsername" class="required">Username
                            <input type="text" id="txtUsername" name="txtUsername"
                                   value="<?php print $Username; ?>"
                                   tabindex="220" maxlength="16" placeholder="Enter a username"
                                   <?php if ($UsernameERROR) print 'class="mistake"'; ?>
                                   >

                        </label>
                        
                        <label for="Password" class="required">Password
                            <input type="password" id="Password" name="Password"
                                   value=""
                                   tabindex="220" maxlength="16" placeholder="Enter a password"
                                   <?php if ($passwordERROR) print 'class="mistake"'; ?>
                                   >

                        </label>
                                                
                        
                        <label for="txtEmail" class="required">Email
                            <input type="text" id="txtEmail" name="txtEmail"
                                   value="<?php print $email; ?>"
                                   tabindex="120" maxlength="45" placeholder="Enter a valid email address"
                                   <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   >
                        </label>

                    </fieldset> <!-- ends contact -->
                </fieldset> <!-- ends wrapper Two -->
                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="900" class="button">
                </fieldset> <!-- ends buttons -->
            </fieldset> <!-- Ends Wrapper -->
        </form>
        <?php
    } // end body submit
    ?>
</article>



<?php
include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</body>
</html>