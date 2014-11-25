SELECT DISTINCT pmkCourseId , fldCourseName
FROM tblCourses , tblTeachers, tblSections
WHERE pmkCourseId = fnkCourseId
AND pmkNetId = fnkTeacherNetId
AND fldFirstName like '%Robert%'
AND fldLastName like '%Erickson%'