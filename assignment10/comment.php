<?php
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
//If the id is set, it will be a product edit. Get previous values for the product, and store them in the form for editing.
 if (($_SESSION["user"])) {
$UserID = $session_pmkUserID;
$ProductID = $pmkProductID;
$text = "";
$rating = 0;
   if($debug){
       print "<p>pmkUseriD =".$session_pmkUserID."</p>";
        print "<p>pmkUseriD =".$UserID."</p>";
    }

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
//Product Flags
$textERROR = false;
$ratingERROR = false;

$TEXT_MAX_LENGTH = 200;
//ERROR CONSTANTS
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// used for building email message to be sent and displayed
/* NO MAILING FOR PRODUCTS
  $mailed = false;
  $messageA = "";
  $messageB = "";
  $messageC = "";
 */
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2a Security
///
    /* REMOVED SECURITY CHECK
      if (!securityCheck(true)) {
      $msg = "<p>Sorry you cannot access this page. ";
      $msg.= "Security breach detected and reported</p>";
      die($msg);
      }
     */


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2b Sanitize (clean) data
// remove any potential JavaScript or html code from users input on the
// form. Note it is best to follow the same order as declared in section 1c.
    // - --- PRODUCT SANITIZE
    $UserID = htmlentities($_POST["hidUserID"], ENT_QUOTES, "UTF-8");
    
    if($debug){
        print "<p>pmkUseriD =".$UserID."</p>";
    }

    $ProductID = htmlentities($_POST["hidProductID"], ENT_QUOTES, "UTF-8");

    $text = htmlentities($_POST["txtText"], ENT_QUOTES, "UTF-8");

    $rating = htmlentities($_POST["numRating"], ENT_QUOTES, "UTF-8");



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
    //~~~~~~~~~~ TEXT ~~~~~~~~~~~~

    if (strlen($text) > $TEXT_MAX_LENGTH) {
        $errorMsg[] = "Your comment is too long!";
        $textERROR = true;
    } elseif ($text == '') {
        $errorMsg[] = "Please enter a comment";
        $textERROR = true;
    } elseif (!verifyAlphaNum($text)) {
        $errorMsg[] = "Your comment appears to contain malicious characters. Please make sure to only use basic text.";
        $textERROR = true;
    }
    //~~~~~~RATING VALIDATION~~~~~~~~~~~
    if ($rating == "") {
        $errorMsg[] = "Please enter a rating";
        $ratingERROR = true;
    } elseif (!verifyNumeric($rating)) {
        $errorMsg[] = "Please enter a rating from 1 to 5";
        $ratingERROR = true;
    }
    elseif($rating > 5 or $rating < 1){
        $errorMsg[] = "Please enter a rating from 1 to 5";
        $ratingERROR = true;
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


        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // Category DATA SQL
        //
        $data = array($UserID,$ProductID,$text,$rating);

        $primaryKey = "";
        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();


            $query = "INSERT INTO tblComments SET ";

            $query .= " fnkUserID = ? , fnkProductID = ? , fldText = ? , fldRating = ? ";


            $results = $thisDatabase->insert($query, $data);




            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }





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
        print "<h2>Comment ";


        print" Submitted!</h2>";
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
        <form action="<?php print $phpSelf."?id=".$ProductID; ?>"
              method="post"
              id="frmComment">
            <fieldset class="wrapper">

                <legend></legend>
                <!-- Start User Form -->
                <fieldset class="wrapperTwo">
                    <legend>Leave a comment!</legend>
                    <fieldset class="contact">
                        <legend></legend>
                        <!-- Hidden variables -->
                        <input type="hidden" id="hidProductID" name="hidProductID"
                               value="<?php print $ProductID; ?>"
                               >
                        
                        <input type="hidden" id="hidUserID" name="hidUserID"
                               value="<?php print $UserID; ?>"
                               >
                        
                        <!-- Comment box -->
                        <label id ="txtText" >Comment</label>
                        <textarea id=txtText name=txtText rows=5 onfocus="this.select()" maxlength= 
                        <?php
                        print "'$TEXT_MAX_LENGTH'";
                        if ($textMeERROR) {
                            print 'class = "mistake"';
                        }
                        ?>><?php
                                      print$text;
                                      ?></textarea>
                        
                        <!-- Rating -->
                             <label  class="required">Rating
                            <input type="quantity" id="numRating" name="numRating"
                                   value="<?php print $rating;?>"
                                   tabindex="110"  placeholder="Enter a rating"
                                   min ="1" max ="5"  
                                   <?php if ($ratingERROR) print 'class="mistake"'; ?>
                                   >

                        </label>

                    </fieldset>
                    <!-- ends User Form -->
                </fieldset> 

                <!-- ends wrapper Two -->


                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Comment" tabindex="900" class="button">
                </fieldset> <!-- ends buttons -->
            </fieldset> <!-- Ends Wrapper -->
        </form>
     <?php 
    } // end body submit
 }else{
     print"<p>Login to leave comments!</p>";
 }
    ?>
