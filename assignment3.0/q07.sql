SELECT fldFirstName, fldPhone , fldSalary 
FROM tblTeachers
WHERE fldSalary < (SELECT AVG(fldSalary)
FROM tblTeachers