<?php
include "top.php";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
require_once('../bin/myDatabase.php');

$dbUserName = get_current_user() . '_reader';
$whichPass = "r"; //flag for which one to use.
$dbName = strtoupper(get_current_user()) . '_UVM_Courses';

$thisDatabase = new myDatabase($dbUserName, $whichPass, $dbName);

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
$Start = "";
$Building = "Any";
$Department = "";
$CourseNumber = "";
$LastName = "";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$StartERROR = false;
$BuildingError = false;
$DepartmentError = false;
$CourseNumberError = false;
$LastNameError = false;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// array used to hold form values that will be written to a CSV file
$dataRecord = array();

$mailed = false; // have we mailed the information to the user?
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2a Security
    // 
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

    $Start = htmlentities($_POST["txtStart"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $Start;

    $Building = htmlentities($_POST["lstBuilding"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $Building;

    $Department = htmlentities($_POST["txtDepartment"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $Department;

    $CourseNumber = htmlentities($_POST["txtCourseNumber"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $CourseNumber;

    $LastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $LastName;

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
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    //
 
    // SECTION: 2d Process Form - Passed Validation
    //
    // Process for when the form passes validation (the errorMsg array is empty)
    //
    //if (!$errorMsg) {
    if ($debug)
        print "<p>Form is valid</p>";

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
        // SECTION: 2e Prepare Query

    $query = "SELECT ";
    $query .="CONCAT(fldDepartment , ' ' , fldCourseNumber ) as 'Course' ,";
    $query .="  fldCRN as CRN ,";
    $query .=" CONCAT(fldFirstName , ' ' , fldLastName) as 'Professor' ,";
    $query .=" (fldMaxStudents - fldNumStudents) as 'Seats Available' ,";
    $query .="  fldSection as Section ,";
    $query .="  fldType as Type ,";
    $query .="  fldStart as Start ,";
    $query .="  fldStop as Stop ,";
    $query .="  fldDays as Days ,";
    $query .="  fldBuilding as Building ,";
    $query .="   fldRoom as Room ";

    $query .= "FROM tblCourses , tblSections , tblTeachers";
    $query .= ' WHERE pmkCourseID = fnkCourseID';
    $query .= ' AND pmkNetID = fnkTeacherNetID';
    $query .= ' AND fldStart like "' . $Start . '%"';
    $query .= ' AND fldBuilding like "' . $Building . '%"';
    $query .= ' AND fldDepartment like "%' . $Department . '%"';
    $query .= ' AND fldCourseNumber like "%' . $CourseNumber . '%"';
    $query .= ' AND fldLastName like "%' . $LastName . '%"';

    $data = array($Start, $Building, $Department, $CourseNumber, $LastName);



    $results = $thisDatabase->select($query, $data);


    /////////// CALC COUNT AND DISPLAY ////////////
    $numberRecords = count($results);

    print "<h2>Total Records: " . $numberRecords . "</h2>";

    $keys = array_keys($row);


//////////
    //////// TABLE CONSTRUCTION ///////////////
    print "<table>";

    $firstTime = true;

    /* since it is associative array display the field names */
    foreach ($results as $row) {
        if ($firstTime) {
            print '<thead><tr id = "tableheader">';
            $keys = array_keys($row);
            foreach ($keys as $key) {

                preg_replace(' /(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', substr($key, 3));

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


    /* old table structure
      print "<table>\n";
      print '<tr>';
      print '<th scope="col">CRN</th>';
      print '<th scope="col">Section</th>';
      print '<th scope="col">Room</th>';
      print '<th scope="col">Days</th>';
      print '<th scope="col">Max Students</th>';
      print '<th scope="col">Start Time</th>';
      print '<th scope="col">Stop Time</th>';


      print '</tr>';
      foreach ($results as $row) {
      print "<tr>\n";
      print "<td>" . $row["fldCRN"] . "</td>\n";
      print "<td>" . $row["fldSection"] . "</td>\n";
      print "<td>" . $row["fldRoom"] . "</td>\n";
      print "<td>" . $row["fldDays"] . "</td>\n";
      print "<td>" . $row["fldMaxStudents"] . "</td>\n";
      print "<td>" . $row["fldStart"] . "</td>\n";
      print "<td>" . $row["fldStop"] . "</td>\n";
      print"</tr>";
      //}
      } // end form is valid
      print "</table>\n\t";
     * 
     * 
     */
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
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {

// closing of if marked with: end body submit
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
                <legend>Class Search</legend>

                <fieldset class="wrapperTwo">
                    <legend>Search Options</legend>

                    <fieldset class="contact">
                        <legend></legend>

                        <label id="labelStart">Start Time
                            <input type="text" id="txtStart" name="txtStart"
                                   value="<?php print $Start; ?>"
                                   tabindex="100" maxlength="45" placeholder="3:00"
                                   <?php if ($StartERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>


                        <!-- START Listbox -->
                        <label id="lstBuilding">Building</label>
                        <?php
                        $query = "SELECT DISTINCT fldBuilding FROM tblSections ORDER BY fldBuilding ";
                        $data = array();
                        $results = $thisDatabase->select($query, $data);
                        echo "<select name='lstBuilding'> \n";
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


                        <label id="labelDepartment">Department
                            <input type="text" id="txtDepartment" name="txtDepartment"
                                   value="<?php print $Department; ?>"
                                   tabindex="300" maxlength="45" placeholder="CS"
                                   <?php if ($DepartmentERROR) print 'class="mistake"'; ?>>
                        </label>
                        <label id="labelCourseNumber">Course Number
                            <input type="text" id="txtCourseNumber" name="txtCourseNumber"
                                   value="<?php print $CourseNumber; ?>"
                                   tabindex="400" maxlength="45" placeholder="148"
                                   <?php if ($CourseNumberERROR) print 'class="mistake"'; ?>>
                        </label>

                        <label id="labelLastName">Professor
                            <input type="text" id="txtLastName" name="txtLastName"
                                   value="<?php print $LastName; ?>"
                                   tabindex="500" maxlength="45" placeholder="Erickson"
                                   <?php if ($LastNameERROR) print 'class="mistake"'; ?>>
                        </label>

                    </fieldset> <!-- ends contact -->
                    <fieldset class="buttons">
                        <legend></legend>				
                        <input type="submit" id="btnSubmit" name="btnSubmit" value="Search" tabindex="900" class="button">
                    </fieldset> <!-- ends buttons -->
                </fieldset> <!-- ends wrapper Two -->


            </fieldset>
            <!-- Ends Wrapper -->

        </form>

        <?php
    } // end body submit
    ?>

</article>

<?php include "footer.php";
?>

</body>
</html>