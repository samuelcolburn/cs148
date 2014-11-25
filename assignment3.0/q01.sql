SELECT DISTINCT fldCourseName , fldGrade
FROM tblCourses,tblEnrolls
WHERE pmkCourseId = fnkCourseId
AND fldGrade = 100