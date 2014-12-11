<?php
/* This is a form to delete data from databases on the site
  it checks if the user is an admin.
 * It gets the table name and the pmk from the link
 * it displays this record, and has a delete button
 * data is deleted when form is submitted
 */

include "top.php";



$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";

// basic message to print if admin is false
$message = '<h2> ACCESS DENIED</h2>';

print '<article>';

//check for admin in the session variable
if ($_SESSION["admin"]) {


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
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
// 
//If the id is set, get it, santize it, store it
    if (isset($_GET["id"])) {

        //sanitize id
        $id = htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");

        // Store the id in the data array for the select
        $data = array($id);
    }
    if ($debug) {
        print "<p>id:" . $id . "</p>";
    }

//if the table is set, get it, santize it, store it
    if (isset($_GET["table"])) {
        //sanitize table
        $table = htmlentities($_GET["table"], ENT_QUOTES, "UTF-8");

        if ($debug) {
            print "<p>table:" . $table . "</p>";
        }
    }

// build query depending on the table, to display to the user

    $query = "SELECT ";

    if ($table == 'tblProducts') {

        $query .= " pmkProductID , fldProductName , fldDescription , fldDateSubmitted , fldPrice , fldImage, fldCategoryName ";
        $query .= "FROM tblProducts , tblCategories ";
        $query .= " WHERE pmkProductID = ? AND pmkCategoryID = fnkCategoryID ";
    } elseif ($table == 'tblUsers') {
        $query .=" pmkUserId , fldDateJoined , fldEmail , fldPassword , fldPermissionLevel , fldUsername ";
        $query .="FROM tblUsers ";
        $query .="WHERE pmkUserId = ? ";
    } elseif ($table == 'tblCategories') {
        $query .=" pmkCategoryID , fldCategoryName ";
        $query .="FROM tblCategories ";
        $query .=" WHERE pmkCategoryID = ? ";
    }

    //@@@ EXECUTE and store query  @@@
    $results = $thisDatabase->select($query, $data);

    if ($debug) {

        print "<p>Item:</p>";
        print_r($data);
        print "<p>sql: " . $query . "</p>";
        print_r($results);
    }

    //@@ DISPLAY record
    /* since it is associative array display the field names */
    print"<table>";
    $firstTime = true;
    foreach ($results as $row) {
        if ($firstTime) {
            print '<thead><tr id = "tableheader">';
            $keys = array_keys($row);
            foreach ($keys as $key) {

                if (!is_int($key)) {
                    print "<th>" . $key . "</th>";
                }
            }

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
        print "</tr>\n";
    }
    print"</table>";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
//Product Flags
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

        $id = htmlentities($_POST["hidID"], ENT_QUOTES, "UTF-8");

        $table = htmlentities($_POST["hidTable"], ENT_QUOTES, "UTF-8");




        if ($debug) {
            print "<p>santize id = ".$id."</p>";
            print "<p>table = ".$table."</p>";
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
        $data = array($id);

            $dataEntered = false;
            try {
                $thisDatabase->db->beginTransaction();

                $query = "DELETE FROM ";

                if ($table == 'tblProducts') {

                    $query .= " tblProducts ";
                    $query .= " WHERE pmkProductID = ? ";
                } elseif ($table == 'tblUsers') {
                    $query .=" tblUsers ";
                    $query .="WHERE pmkUserId = ? ";
                } elseif ($table == 'tblCategories') {
                    $query .=" tblCategories ";
                    $query .=" WHERE pmkCategoryID = ? ";
                }


                if ($debug) {
                    print "<p>sql " . $query;
                    print"<p><pre>";
                    print_r($data);
                    print"</pre></p>";
                }


                $results = $thisDatabase->delete($query, $data);



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
            print "<h2>Item Deleted </h2>";
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
            <form action="<?php print $phpSelf . "?id=" . $id . "&amp;table=" . $table; ?>"
                  method="post"
                  id="frmDelete">
                
                    <input type="hidden" id="hidID" name="hidID"
                               value="<?php print $id; ?>"
                               >
                    <input type="hidden" id="hidTable" name="hidTable"
                               value="<?php print $table; ?>"
                               >
                    
                    
                <fieldset class="buttons">
                    <legend>DELETING RECORDS IS PERMANENT</legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="DELETE" tabindex="900" class="button">
                </fieldset> <!-- ends buttons -->

            </form>
            <?php
        } // end body submit
    } else {
        print"<h2>ACCESS DENIED</h2>";
    }
    ?>
</article>



<?php
include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</body>
</html>
