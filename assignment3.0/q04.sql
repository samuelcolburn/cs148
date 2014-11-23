SELECT fldFirstName , fldLastName , fnkCourseId , fldCourseName ,fldDepartment , fldCourseNumber
FROM tblStudents , tblEnrolls ,tblCourses
WHERE fnkStudentId = pmkStudentId
AND fnkCourseId = pmkCourseId
ANd fnkCourseId = 377