<?php
/* This is the login page. Users and admins can login here,
 * their status will be stored as a session variable until they either logout
 * or they close the browser.
 * Login is a form to submit username and password. 
 * Username and password are passed to a sql statement to check the database for
 * a username  and password that match. If they match, session user is set to true.
 * If their permission level is > 4,  admin is set to true. 
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
//@@ LOGIN DATA @@
$Username = '';
$password = '';

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
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

    $Username = htmlentities($_POST["txtUsername"], ENT_QUOTES, "UTF-8");

    $password = htmlentities($_POST["Password"], ENT_QUOTES, "UTF-8");


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
    // Query as validation to check correct username  and password
    $query = "SELECT  fldUsername , fldPassword , fldPermissionLevel FROM tblUsers WHERE fldUsername = ? ";
    $data = array($Username);
    if ($debug) {
        print "<p>sql " . $query;
        print"<p><pre>";
        print_r($data);
        print"</pre></p>";
    }
    $results = $thisDatabase->select($query, $data);
    $username_check = $results[0]['fldUsername'];
    $password_check = $results[0]['fldPassword'];
    $permission = $results[0]['fldPermissionLevel'];
    
    if($debug){
    print $permission;
    }
    //~~~~~~~~~~~~~USERNAME VALIDATION~~~~~~~~~~~

    if ($Username == "") {
        $errorMsg[] = "Please enter a username";
        $UsernameERROR = true;
    } elseif ($Username !== $username_check) {
        $errorMsg[] = "Username or password is incorrect.";
    }

    //~~~~~~PASSWORD VALIDATION~~~~~~~~~~~
    if ($password == '') {
        $errorMsg[] = "Please enter a password";
        $passwordERROR = true;
    } elseif ($password!== $password_check) {
        $errorMsg[] = "Username or password is incorrect.";
        $passwordERROR = true;
    }

    if ($debug) {
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
    print '<p> You are now logged in.</p>';
    
    $_SESSION["user"] = $Username; 
    
    if($debug){
    print  $_SESSION["user"];
    }
    
    if($permission == '4'){
       $_SESSION["admin"] = true;   
    }
    
    if($debug){
        print $_SESSION["admin"];
    }
    
?>

     <!--- Javascript to redirect to the homepage after logins -->
   <script type="text/javascript">
<!--
function delayer(){
    window.location = "home.php"
}
//-->
</script>
<!--TIMER -->
<body onLoad="setTimeout('delayer()', 0000)"> 
<h2>You are now logged in.</h2>
<p>You should be redirected to the home page. If not, click this link: <a href ='home.php'> home</a>.</p>

    
<?php
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
              id="frmLogin">
            <fieldset class="wrapper">

                <legend>Login</legend>
                <!-- Start User Form -->
                <fieldset class="wrapperTwo">
                    <legend></legend>
                    <fieldset class="contact">
                        <legend></legend>
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


                    </fieldset>
                    <!-- ends User Form -->
                </fieldset> 

                <!-- ends wrapper Two -->

                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Login" tabindex="900" class="button">
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