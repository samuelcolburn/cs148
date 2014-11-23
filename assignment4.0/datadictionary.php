<!DOCTYPE html>
<html lang="en">
<head>
<title>CS 148 Tables</title>
<meta charset="utf-8">
<meta name="author" content="Sam Colburn">
<meta name="description" content="index page for cs148">
<link rel="stylesheet" href="../bin/style.css" type="text/css" media="screen">


<!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
<![endif]-->
    
</head>
<body>
    <article>
 <?php  
    $debug = false;

if(isset($_GET["debug"])){
    $debug = true;
}

$myFileName="datadictionary";

$fileExt=".csv";

$filename = $myFileName . $fileExt;

if ($debug) print "\n\n<p>filename is " . $filename;

$file=fopen($filename, "r");
/* ~~~~~~~~~~~~~ OPEN CODE END ~~~~~~~~~~~~~~~~~~*/

/* ~~~~~~~~~~~~~~~~ READ AND CLOSE ~~~~~~~~~~~~~~~*/
/* the variable $file will be empty or false if the file does not open */
if($file){
    if($debug) print "<p>File Opened</p>\n";
}
/* the variable $file will be empty or false if the file does not open */
if($file){
    
    if($debug) print "<p>Begin reading data into an array.</p>\n";

    /* This reads the first row which in our case is the column headers */
    $headers=fgetcsv($file);
    
    if($debug) {
        print "<p>Finished reading headers.</p>\n";
        print "<p>My header array<p><pre> "; print_r($headers); print "</pre></p>";
    }
    /* the while (similiar to a for loop) loop keeps executing until we reach 
     * the end of the file at which point it stops. the resulting variable 
     * $records is an array with all our data.
     */
    while(!feof($file)){
        $records[]=fgetcsv($file);
    }
    
    //closes the file
    fclose($file);
    
    if($debug) {
        print "<p>Finished reading data. File closed.</p>\n";
        print "<p>My data array<p><pre> "; print_r($records); print "</pre></p>";
    }
} // ends if file was opened  

/* ~~~~~~~~~~~~~~~~ READ AND CLOSE ~~~~~~~~~~~~~~~*/


/* ~~~~~~~~~~~~~~~~ DISPLAY ~~~~~~~~~~~~~~~*/
/* display the data */


print "<h1>Data Dictionary</h1>";
print "<table>\n";
print '<tr>';
print '<th scope="col">Field</th>';
print '<th scope="col">Type</th>';
print '</tr>';
 foreach ($records as $oneRecord){
         /*   if ($oneRecord[0] != "") {  //the eof would be a ""
             }*/
       print "<tr>\n";
        print "<td>" .$oneRecord[0]."</td>\n";
       
        print  "<td>".$oneRecord[1]."</td>\n";
        print "</tr>\n";    

        }
        
print "</table>\n\t";    



        ?>
    
    </article> 
</body>
</html>
