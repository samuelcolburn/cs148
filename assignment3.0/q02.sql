SELECT DISTINCT fldStart, fldDays
FROM tblSections , tblTeachers
WHERE pmkNetId = fnkTeacherNetId
AND fldFirstName like '%Robert%'
AND fldLastName like '%Erickson%'
AND fldStart NOT like'%00:00:00%'