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
//If the id is set, it will be a product edit. Get previous values for the product, and store them in the form for editing.
if (isset($_GET["id"])) {

    //sanitize id
    $pmkProductID = htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");

    // Store the id in the data array for the select
    $data = array($pmkProductID);

    // select the product from the product table
    $query = "SELECT pmkProductID , fldProductName , fldDescription , fldDateSubmitted , fldCommentCount , fldPrice , fldImage, fnkCategoryID FROM tblProducts WHERE pmkProductID = ?";

    //@@@ STORE  results
    $results = $thisDatabase->select($query, $data);
    $ProductName = $results[0]["fldProductName"];
    $Description = $results[0]["fldDescription"];
    $DateSubmitted = $results[0]["fldDateSubmitted"];
    $CommentCount = $results[0]["fldCommentCount"];
    $Price = $results[0]["fldPrice"];
    $Image = $results[0]["fldImage"];
    $CategoryID = $results[0]["fnkCategoryID"];

    //Using the category ID, get the category for the product
    $query = "SELECT fldCategoryName FROM tblCategories WHERE pmkCategoryID = ?";
    $data = array($CategoryID);

    //get the category name
    $results = $thisDatabase->select($query, $data);

    //store the category in  $Category
    $Category = $results[0]["fldCategoryName"];

    if ($debug) {

        print "<p>Product:</p>";
        print_r($data);
        print $query;
        print_r($results);
    }
} else {
    // Product variables
    $pmkProductID = -1;
    $ProductName = "";
    $Description = '';
    $Price = "";
    $Image = "";

// Category Variable
    $Category = "Any";
}

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
//Product Flags
$ProductNameERROR = false;
$DescrptionERROR = false;
$PriceERROR = false;
$ImageERROR = false;

//Category Flag
$CategoryERROR = false;

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
    $pmkProductID = htmlentities($_POST["hidProductID"], ENT_QUOTES, "UTF-8");

    if ($pmkProductID > 0) {
        $update = true;
    }

    $ProductName = htmlentities($_POST["txtProductName"], ENT_QUOTES, "UTF-8");

    $Description = htmlentities($_POST["txtDescription"], ENT_QUOTES, "UTF-8");

    $Price = htmlentities($_POST["numPrice"], ENT_QUOTES, "UTF-8");




    //--- CATEGORY SANITIZE ---
    $Category = htmlentities($_POST["lstCategory"], ENT_QUOTES, "UTF-8");

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
    //~~~~~~~~~~~PRODUCT NAME VALIDATION~~~~~~~~~~
    if ($ProductName == "") {
        $errorMsg[] = "Please enter a name for the Product";
        $ProductNameERROR = true;
    } elseif (!verifyAlphaNum($ProductName)) {
        $errorMsg[] = "Your product name invalid. Be sure to only use basic characters.";
        $ProductNameERROR = true;
    }


    //~~~~~~~~~~~~~DESCRIPTION VALIDATION~~~~~~~~~~~
    if ($Description == "") {
        $errorMsg[] = "Please enter a description for the product";
        $DescriptionERROR = true;
    } elseif (!verifyAlphaNum($Description)) {
        $errorMsg[] = "Your description is invalid. Be sure to only use basic characters.";
        $DescriptionERROR = true;
    }

    //~~~~~~PRICE VALIDATION~~~~~~~~~~~
    if ($Price == "") {
        $errorMsg[] = "Please enter a price";
        $PriceERROR = true;
    } elseif (!verifyNumeric($Price)) {
        $errorMsg[] = "Please enter a valid price eg. 42.50";
        $PriceERROR = true;
    }

    //~~~~~~~~ IMAGE VALIDATION ~~~~~~~~~~~~~~
    //
    //
    //++++++ CATEGORY VALIDATION ++++++++++
    if ($Category == "") {
        $errorMsg[] = "Please enter a Category";
        $CategoryError = true;
    } elseif (!verifyAlphaNum($Category)) {
        $errorMsg[] = "Please enter a valid category";
        $CategoryERROR = true;
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

        //@@@@@@ 
        //SQL To get category ID to insert into product target
        $data = array($Category);
        
        $query = "SELECT  pmkCategoryID from tblCategories WHERE fldCategoryName = ? ";
        
        $results = $thisDatabase->select($query, $data); 
        $CategoryID = $results[0]['pmkCategoryID'];
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // Product DATA SQL
        //
        $data = array($ProductName, $Description, $Price , $CategoryID );

        $primaryKey = "";
        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();

            if ($update) {
                $query = "UPDATE tblProducts SET ";
            } else {
                $query = "INSERT INTO tblProducts SET ";
            }
            $query .= " fldProductName = ? , fldDescription = ? , fldPrice = ? , fnkCategoryID = ? ";

            if ($update) {
                $query = " WHERE pmkProductID = ? ";
                $data[] = $pmkProductID;
                $results = $thisDatabase->update($query, $data);
            } else {
                $results = $thisDatabase->insert($query, $data);
                
                $primaryKey = $thisDatabase->lastInsert();
                if ($debug)
                    print "<p>pmk= " . $primaryKey;
            }


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
        print "<h2>Product ";
        if($update){
            print" updated</h2>";
        }
        else{
           print" added</h2>";
                }
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

                <legend><?php$ProductName?></legend>
                <!-- Start User Form -->
                <fieldset class="wrapperTwo">
                    <legend>Required Information</legend>
                    <fieldset class="contact">
                        <legend></legend>
                            <input type="hidden" id="hidProductID" name="hidProductID"
                               value="<?php print $ProductID; ?>"
                               >
                        <label for="txtProductName" class="required">Product Name
                            <input type="text" id="txtProductName" name="txtProductName"
                                   value="<?php print $ProductName; ?>"
                                   tabindex="100" maxlength="16" placeholder="Enter a product name"
                                   <?php if ($ProductNameERROR) print 'class="mistake"'; ?>
                                   >

                        </label>

                        <label for="numPrice" class="required">Price
                            <input type="quantity" id="numPrice" name="numPrice"
                                   value=""
                                   tabindex="110" maxlength="16" placeholder="Enter a price"
                                   min ="0" max ="99999"
                                   <?php if ($PriceERROR) print 'class="mistake"'; ?>
                                   >

                        </label>


                        <label for="txtDescription" class="required">Description
                            <textarea id="txtDescription" name="txtDescription"
                                   tabindex="120" maxlength="500" rows="10"
                                   
                                   <?php 
                                   if ($DescriptionERROR) {
                                       print 'class="mistake"';                                  
                                }
                                ?>
                                   >
                            <?php
                            print $Description;
                            ?>
                            </textarea>
                        </label>
                       <!-- START Listbox -->
                        <label id="lstCategory">Category</label>
                        <?php
                        $query = "SELECT DISTINCT fldCategoryName FROM tblCategories ORDER BY fldCategoryName ";
                        $data = array();
                        $results = $thisDatabase->select($query, $data);
                        echo "<select name='lstCategory'> \n";
                        foreach ($results as $row) {
                            $row = array_shift($row);
                            if (!empty($row)) {
                                print "<option value='";
                                echo $row;
                                print "'>";
                                echo $row;
                                print "</option> \n";
                            } else {
                                print "<option value=''>Any</option> \n";
                            }
                        }
                        echo "</select>";
                        ?>

                        <!-- End ListBox -->
                    </fieldset>
                    <!-- ends User Form -->
                </fieldset> 

                <!-- ends wrapper Two -->

   
                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="
                        <?php
                        if($update){
                            print "Update";
                        }else{
                            print "Submit";
                        }
                    ?>" tabindex="900" class="button">
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