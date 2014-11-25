<!DOCTYPE html>
<html lang="en">
<head>
<title>CS 148 Tables</title>
<meta charset="utf-8">
<meta name="author" content="Sam Colburn">
<meta name="description" content="index page for assignment two">


<!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
<![endif]-->
    
</head>


<?php

$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
$path_parts = pathinfo($phpSelf);
print '<body id="' . $path_parts['filename'] . '">';

?>

<p>Assignment 2.0</p>

<p>q01. <a href="q01.php">SQL:</a> SELECT pmkNetId FROM tblTeachers</p>

<p>q02. <a href="q02.php">SQL:</a> SELECT fldDepartment FROM tblCourses wHERE fldCourseName = "Elementary"</p>

<p>q03. <a href="q03.php">SQL:</a> SELECT * FROM tblSections WHERE fldBuilding = "Kalkin" AND fldStart = "15:00:00"</p>

<p>q04. <a href="q04.php">SQL:</a> SELECT * FROM tblCourses WHERE fldDepartment like '%CS%' AND fldCourseNumber = 148</p>

<p>q05. <a href="q05.php">SQL:</a> SELECT fldFirstName, fldLastName, pmkNetId FROM tblTeachers WHERE pmkNetId like "r%o"</p>

<p>q06. <a href="q06.php">SQL:</a> SELECT fldCourseName FROM tblCourses WHERE (fldCourseName like "%data%") AND fldDepartment != "CS"</p>

<p>q07. <a href="q07.php">SQL:</a> SELECT DISTINCT fldDepartment FROM tblCourses</p>

<p>q08. <a href="q08.php">SQL:</a> SELECT fldBuilding , COUNT(*) AS num_sections  FROM tblSections GROUP BY fldBuilding</p>

<p>q09. <a href="q09.php">SQL:</a> SELECT fldBuilding , COUNT(*) AS num_sections  FROM tblSections GROUP BY fldBuilding</p>

<p>q10. <a href="q10.php">SQL:</a> SELECT * FROM tblTeachers</p>

<p>q11. <a href="q11.php">SQL:</a> SELECT * FROM tblTeachers</p>

<p>q12. <a href="q12.php">SQL:</a> SELECT * FROM tblTeachers</p>

</body>
</html>
