<!DOCTYPE html>
<html lang="en">
<head>
<title>CS 148 Tables</title>
<meta charset="utf-8">
<meta name="author" content="Sam Colburn">
<meta name="description" content="index page for assignment three">


<!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
<![endif]-->
    
</head>


<?php

$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
$path_parts = pathinfo($phpSelf);
print '<body id="' . $path_parts['filename'] . '">';

?>

<p>Assignment 3.0</p>

<p>q01. <a href="q01.php">SQL:</a> SELECT DISTINCT tblCourses.fldCourseName FROM tblCourses,tblEnrolls WHERE pmkCourseId = fnkCourseId AND fldGrade = 100</p>

<p>q02. <a href="q02.php">SQL:</a> SELECT DISTINCT fldStart, fldDays FROM tblSections , tblTeachers WHERE pmkNetId = fnkTeacherNetId AND fldFirstName like '%Robert%' AND fldLastName like '%Erickson%' AND fldStart NOT like'%00:00:00%'</p>

<p>q03. <a href="q03.php">SQL:</a> SELECT DISTINCT pmkCourseId , fldCourseName
FROM tblCourses , tblTeachers, tblSections
WHERE pmkCourseId = fnkCourseId
AND pmkNetId = fnkTeacherNetId
AND fldFirstName like '%Robert%'
AND fldLastName like '%Erickson%'</p>

<p>q04. <a href="q04.php">SQL:</a> SELECT fldFirstName , fldLastName , fnkCourseId , fldCourseName ,fldDepartment , fldCourseNumber
FROM tblStudents , tblEnrolls ,tblCourses
WHERE fnkStudentId = pmkStudentId
AND fnkCourseId = pmkCourseId
ANd fnkCourseId = 377</p>

<p>q05. <a href="q05.php">SQL:</a> 
SELECT fldLastName , pmkNetId , SUM(fldNumStudents) as total
FROM tblTeachers , tblSections
WHERE pmkNetId = fnkTeacherNetId
GROUP BY pmkNetId
HAVING SUM(fldNumStudents) BETWEEN 190 AND 200</p>

<p>q06. <a href="q06.php">SQL:</a>SELECT fldFirstName , fldLastName , Count(fnkSectionId)
FROM tblStudents , tblEnrolls
WHERE fnkStudentId = pmkStudentId
GROUP BY pmkStudentId
HAVING Count(fnkSectionId) > 2 </p>

<p>q07. <a href="q07.php">SQL:</a>SELECT fldFirstName, fldPhone , fldSalary 
FROM tblTeachers
WHERE fldSalary < (SELECT AVG(fldSalary)
FROM tblTeachers)</p>

<p>q08. <a href="q08.php">SQL:</a> SELECT DISTINCT fldCourseName
FROM tblCourses, tblEnrolls
WHERE pmkCourseId = fnkCourseId
AND fldGrade = 100</p>

<p>q09. <a href="q09.php">SQL:</a> SELECT DISTINCT pmkStudentId
FROM tblStudents , tblEnrolls , tblSections
WHERE pmkStudentId = fnkStudentId
AND tblSections.fnkCourseId = tblEnrolls.fnkCourseId
AND fnkTeacherNetId = 'rerickso'</p>

<p>q10. <a href="q10.php">SQL:</a> SELECT * FROM tblTeachers</p>

</body>
</html>
