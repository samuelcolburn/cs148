<?php
/* the purpose of this page is to display a form to allow a person to update their profile
 * The form will have their previous information input into the form for them. 
 * IF the user didn't create a profile when they registered, this will automatically create one
 * and input the new values.
 * Once a form is submitted (to this same page)
 * we first sanitize our data by replacing html codes with the html character.
 * then we check to see if the data is valid. if data is valid enter the data 
 * into the table. 
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
//@@ USER DATA @@

if (isset($_GET["user"])) {

    //sanitize username
    $username = htmlentities($_GET["user"], ENT_QUOTES, "UTF-8");

    // USER ACCOUNT DATA VARIALBE SET
    $data = array($username);

    $query = "SELECT fldUsername , fldPassword, fldEmail , fldDateJoined , fldPermissionLevel ,pmkUserId FROM tblUsers WHERE fldUsername = ?";

    //@@@ STORE  results
    $results = $thisDatabase->select($query, $data);
    $Username = $results[0]["fldUsername"];
    $UserID = $results[0]["pmkUserId"];
    $email = $results[0]["fldEmail"];
    $password = $results[0]["fldPassword"];
    $PermissionLevel = $results[0]["fldPermissionLevel"];

    //PROFILE DATA VARIABLE  SET
    $query = "SELECT fldFirstName , fldLastName , fldGender , fldAge ,fldAboutMe FROM tblProfile WHERE fnkUserId = ?";
    $data = array($UserID);

    $results = $thisDatabase->select($query, $data);

    $firstName = $results[0]["fldFirstName"];
    $lastName = $results[0]["fldLastName"];
    $gender = $results[0]["fldGender"];
    $age = $results[0]["fldAge"];
    $AboutMe = $results[0]["fldAboutMe"];
    /* Comment to remove insert set to true. Using REPLACE instead of INSERT vs UPDATE
      if (!empty($results)) {
      $insert = true;
      }
     */
    if ($debug) {

        print "<p>Profile results:</p>";
        print "<p>Insert = " . $insert;
        print_r($data);
        print $query;
        print_r($results);
        print $lastName;
        print $firstName;
        print $gender;
    }
} else {
    $email = "samuel.colburn@uvm.edu";
    $Username = '';
    $password = '';
    $PermissionLevel = '';

// @@ PROFILE DATA @@
    $firstName = "";

    $lastName = "";

    $gender = "";

    $age = "";

    $AboutMe = "";
}
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$emailERROR = false;
$UsernameERROR = false;
$passwordERROR = false;
$PermissionLevelERROR = false;

//PROFILE ERROR FLAGS
$firstNameERROR = false;
$lastNameERROR = false;
$genderERROR = false;
$ageERROR = false;
$AboutMeERROR = false;

//ERROR CONSTANTS
//Username
$MIN_USERNAME_LENGTH = 6;
$MAX_USERNAME_LENGTH = 15;

//Password
$MIN_PASSWORD_LENGTH = 6;
$MAX_PASSWORD_LENGTH = 15;

//About Me
$ABOUTME_MAX_LENGTH = 200;
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
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2b Sanitize (clean) data
// remove any potential JavaScript or html code from users input on the
// form. Note it is best to follow the same order as declared in section 1c.

    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);

    $Username = htmlentities($_POST["txtUsername"], ENT_QUOTES, "UTF-8");

    $password = htmlentities($_POST["Password"], ENT_QUOTES, "UTF-8");

    // Only pass through permission level if admin
    if ($_SESSION["admin"]) {
        $PermissionLevel = htmlentities($_POST["numPermissionLevel"], ENT_QUOTES, "UTF-8");
    } else {
        $PermissionLevel = htmlentities($_POST["hidPermissionLevel"], ENT_QUOTES, "UTF-8");
    }

    //--- PROFILE SANATIZE ---

    $firstName = htmlentities($_POST["txtfirstName"], ENT_QUOTES, "UTF-8");

    $lastName = htmlentities($_POST["txtlastName"], ENT_QUOTES, "UTF-8");

    $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");

    $age = htmlentities($_POST["lstAge"], ENT_QUOTES, "UTF-8");

    $AboutMe = htmlentities($_POST["AboutMe"], ENT_QUOTES, "UTF-8");

    // --- HIDDEN INPUTS
    $UserID = htmlentities($_POST["hidUserID"], ENT_QUOTES, "UTF-8");
    $username = htmlentities($_POST["hidusername"], ENT_QUOTES, "UTF-8");


    if ($debug) {
        print"<p>sanitize pass</p>";
    }
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
    $username_check = $username_check_results[0]["fldUsername"];

    if ($Username == "") {
        $errorMsg[] = "Please enter a username";
        $UsernameERROR = true;
    } elseif (strlen($Username) < $MIN_USERNAME_LENGTH) {
        $errorMsg[] = "Username must be longer than 6 characters";
        $UsernameERROR = true;
    } elseif (strlen($Username) > $MAX_USERNAME_LENGTH) {
        $errorMsg[] = "Username can be no longer than 15 characters";
        $UsernameERROR = true;
    } elseif (!verifyAlphaNum2($Username)) {
        $errorMsg[] = "Invalid Username";
        $UsernameERROR = true;
    } elseif ($username_check == $username) {
        $UsernameERROR = false;
    } elseif (!empty($username_check_results)) {
        $errorMsg[] = 'That username is already in use. Please choose a different username.';
        $UsernameERROR = true;
    }


    if ($debug) {
        print "<p> usercheck = " . $username_check . "</p>";
        print "<p> username =" . $username . "</p>";
    }

    //~~~~~~PASSWORD VALIDATION~~~~~~~~~~~
    if ($password == '') {
        $errorMsg[] = "Please enter a password";
        $passwordERROR = true;
    } elseif (strlen($password) < $MIN_PASSWORD_LENGTH) {
        $errorMsg[] = "Password must be longer than 6 characters";
        $passwordERROR = true;
    } elseif (strlen($password) > $MAX_PASSWORD_LENGTH) {
        $errorMsg[] = "Password can be no longer than 15 characters";
        $passwordERROR = true;
    } elseif (!verifyAlphaNum2($password)) {
        $errorMsg[] = "Invalid Password";
        $passwordERROR = true;
    }

    //++++++ PROFILE VALIDATION ++++++++++
    // because profile entries are optional, don't check for empty
    //~~~~~~~~~~ FIRST NAME ~~~~~~~~~~~~
    if (!verifyAlphaNum2($firstName)) {
        $errorMsg[] = "Your first name appears to not be a name!";
        $firstNameERROR = true;
    }

    //~~~~~~~~~~ LAST NAME ~~~~~~~~~~~~
    if (!verifyAlphaNum2($lastName)) {
        $errorMsg[] = "Your last name appears to not be a name!";
        $lastNameERROR = true;
    }


    //~~~~~~~~~~ ABOUT ME ~~~~~~~~~~~~

    if (strlen($AboutMe) > $ABOUTME_MAX_LENGTH) {
        $errorMsg[] = "Your description is too long!";
        $AboutMeERROR = true;
    } elseif ($AboutMe == '') {
        $AboutMeERROR = false;
    } elseif (!verifyAlphaNum($AboutMe)) {
        $errorMsg[] = "Your personal description appears to contain malicious characters. Please make sure to only use basic text.";
        $AboutMeERROR = true;
    }




    if ($debug) {
        print "<p> about me = " . $AboutMe . "</p>";
        print"<p>validation pass</p>";
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
        // USER DATA SQL
        //

        $primaryKey = "";
        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();
            $query = "UPDATE tblUsers SET ";
            $query .= "fldEmail = ? , fldUsername = ? , fldPassword = ?  , fldPermissionLevel = ? ";

            $query .= "WHERE pmkUserID = ?";
            $data = array($email, $Username, $password, $PermissionLevel, $UserID);
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }
            $results = $thisDatabase->update($query, $data);


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

        //@@@@@@@@@@@ PROFILE DATA SQL @@@@@@@@@@@@@@@@
        try {
            $thisDatabase->db->beginTransaction();
//comments remove if insert functionilty, replaced with REPLACE INTO sql statement.
            //if ($insert) {
            //    $query = "INSERT INTO";
            //} else {
            $query = "REPLACE INTO";
            //}
            $query .= " tblProfile SET  fldFirstName = ? , fldLastName = ? , fldGender = ? , fldAge = ? ,fldAboutMe = ?";
// comments remove if insert functionilty, replaced with REPLACE INTO sql statement.
            //if ($insert) {
            //   $query .= " , fnkUserId = ? ";
            //} else {
            $query .=' , fnkUserId = ? ';
            //}

            $data = array($firstName, $lastName, $gender, $age, $AboutMe, $UserID);
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }


            $resultsProfile = $thisDatabase->update($query, $data);



// all sql statements are done so lets commit to our changes
            $dataEntered = $thisDatabase->db->commit();
            $dataEntered = true;
            if ($debug)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabase->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accepting your data please contact us directly.";
        }
        // If the transaction was successful, give success message
        if ($dataEntered) {

            if ($debug)
                print "<p>key 1: " . $key1;
            if ($debug)
                print "<p>key 2: " . $key2;
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
    print "<h2>Your Profile Has been updated!";
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
                <!-- Start User Form -->
                <fieldset class="wrapperTwo">
                    <legend>Required Information</legend>
                    <fieldset class="contact">
                        <legend></legend>

                        <input type="hidden" id="hidUserID" name="hidUserID"
                               value="<?php print $UserID; ?>"
                               >
                        <input type="hidden" id="hidusername" name="hidusername"
                               value="<?php print $username; ?>"
                               >
                        <label for="txtUsername" class="required">Username
                            <input type="text" id="txtUsername" name="txtUsername"
                                   value="<?php print $Username; ?>"
                                   tabindex="100" maxlength="16" placeholder="Enter a username"
    <?php if ($UsernameERROR) print 'class="mistake"'; ?>
                                   >

                        </label>

                        <label for="Password" class="required">Password
                            <input type="password" id="Password" name="Password"
                                   value=""
                                   tabindex="110" maxlength="16" placeholder="Enter a password"
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
                                   <?php
                                   if ($_SESSION["admin"]) {
                                       print'<label for="numPermissionLevel">Permission Level';
                                       print'<input type="number" id="numPermissionLevel" name="numPermissionLevel" min="0" max="4" value="' . $PermissionLevel . '"> ';
                                       print'</label>';
                                   } else {
                                       print '<input type="hidden" id="hidPermissionLevel" name="hidPermissionLevel" value="' . $PermissionLevel . '">';
                                   }
                                   ?>
                    </fieldset>
                    <!-- ends User Form -->
                </fieldset> 

                <!-- ends wrapper Two -->

                <!-- Profile Form -->
                <fieldset class ="Profile">
                    <legend>Optional Profile</legend>

                    <label for="txtfirstName" class="required">First Name
                        <input type="text" id="txtFirstName" name="txtfirstName"
                               value="<?php print $firstName; ?>"
                               tabindex="200" maxlength="45" placeholder="Billy"
    <?php if ($firstNameERROR) print 'class="mistake"'; ?>

                               >
                    </label>

                    <label for="txtlastName" class="required">Last Name
                        <input type="text" id="txtLastName" name="txtlastName"
                               value="<?php print $lastName; ?>"
                               tabindex="210" maxlength="45" placeholder="Bob"
    <?php if ($lastNameERROR) print 'class="mistake"'; ?>

                               >
                    </label>

                    <fieldset class="radio"
                              >   <!-- START gender radio -->
                        <legend>Gender</legend>
                        <label  <?php
    if ($genderERROR)
        print 'class="mistake"';
    ?>><input type="radio" 
                                id="radGenderMale" 
                                name="radGender" 
                                value="Male"
                            <?php if ($gender == "Male") print 'checked="checked"'; ?>
                                tabindex="210">Male</label>
                        <label <?php
                        if ($genderERROR)
                            print 'class="mistake"';
                        ?>><input type="radio" 
                                id="radGenderFemale" 
                                name="radGender" 
                                value="Female"
                            <?php if ($gender == "Female") print 'checked="checked"' ?>
                                tabindex="220">Female</label>
                        <label <?php
                    if ($genderERROR)
                        print 'class="mistake"';
                    ?>><input type="radio" 
                                id="radGenderOther" 
                                name="radGender" 
                                value="Other"
                            <?php if ($gender == "Other") print 'checked="checked"'; ?>
                                tabindex="230">Other</label>
                    </fieldset> <!-- end gender radio -->
                    <label id="lstAge">Age</label>               
                    <select id="lstAge" 
                            name="lstAge" 
                            tabindex="420" >
                        <option <?php if ($age == "Under18") print " selected "; ?>
                            value="Under10">Under 18</option>

                        <option <?php if ($age == "18-24") print " selected "; ?>
                            value="18-24" >18-24</option>

                        <option <?php if ($age == "25-35") print " selected "; ?>
                            value="25-35" >25-35</option>

                        <option <?php if ($age == "36-50") print " selected "; ?>
                            value="36-50" >36-50</option>

                        <option <?php if ($age == "Over51") print " selected "; ?>
                            value="Over51" >Over 51</option>

                    </select>
                    <label id ="AboutMe" >About Me</label>
                    <textarea id=tAboutMe name=AboutMe rows=5 maxlength= <?php
                        print "'$ABOUTME_MAX_LENGTH'";
                        if ($AboutMeERROR) {
                            print 'class = "mistake"';
                        }
                            ?>><?php
                    print$AboutMe;
                    ?></textarea>
                </fieldset> <!-- End Profile -->
                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Update" tabindex="900" class="button">
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