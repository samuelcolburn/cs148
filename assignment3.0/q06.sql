SELECT fldFirstName , fldLastName , Count(fnkSectionId)
FROM tblStudents , tblEnrolls
WHERE fnkStudentId = pmkStudentId
GROUP BY pmkStudentId
HAVING Count(fnkSectionId) > 2