SELECT DISTINCT pmkStudentId
FROM tblStudents , tblEnrolls , tblSections
WHERE pmkStudentId = fnkStudentId
AND tblSections.fnkCourseId = tblEnrolls.fnkCourseId
AND fnkTeacherNetId = 'rerickso'