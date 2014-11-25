
SELECT fldLastName , pmkNetId , SUM(fldNumStudents) as total
FROM tblTeachers , tblSections
WHERE pmkNetId = fnkTeacherNetId
GROUP BY pmkNetId
HAVING SUM(fldNumStudents) BETWEEN 190 AND 200