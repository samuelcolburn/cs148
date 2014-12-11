<?php
/*
 *  SCHEMA 
 * 
 * 
 * @@@@@@@ TABLE USERS @@@@@@@@@@
 
CREATE TABLE IF NOT EXISTS `tblUsers` ( 
`pmkUserId` int(11) NOT NULL AUTO_INCREMENT, 
`fldEmail` varchar(65) DEFAULT NULL, 
`fldUsername` varchar(15) DEFAULT NULL, 
`fldPassword` varchar(15) DEFAULT NULL, 
`fldDateJoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, 
`fldPermissionLevel` tinyint(5) NOT NULL DEFAULT '0', 
`fldPostCount` int(10) NOT NULL DEFAULT '0', 
PRIMARY KEY (`pmkUserId`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1


@@@@@@@@@@@  TABLE PROFILE @@@@@@@@@@

  CREATE TABLE IF NOT EXISTS `tblProfile` (
  `fnkUserId` int(11) NOT NULL AUTO_INCREMENT,
  `fldFirstName` varchar(65) DEFAULT NULL,
  `fldLastName` varchar(15) DEFAULT NULL,
  `fldAboutMe` varchar(200) DEFAULT NULL,
  `fldProfilePicture` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fldSignature` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fnkUserId`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

@@@@@@@@@  TABLE PRODUCTS @@@@@@@@@@
  CREATE TABLE IF NOT EXISTS `tblProducts` (
  `pmkProductID` int(11) NOT NULL AUTO_INCREMENT,
  `fldProductName` varchar(65) DEFAULT NULL,
  `fldDescription` varchar(500) DEFAULT NULL,
  `fldDateSubmitted` varchar(15) DEFAULT NULL,
  `fldCommentCount` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fnkCategoryID` int(11) NOT NULL,
  PRIMARY KEY (`pmkProductID`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


@@@@@@@@@  TABLE CATEGORIES @@@@@
  CREATE TABLE IF NOT EXISTS `tblCategories` (
  `pmkCategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `fldCategoryName` varchar(65) DEFAULT NULL,
  PRIMARY KEY (`pmkCategoryID`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


@@@@@@@@@  TABLE COMMENTS @@@@@@@@@@@
 
 
    CREATE TABLE IF NOT EXISTS `tblComments` (
  `fnkUserID` int(11) NOT NULL,
  `fnkProductID` int(11) NOT NULL,
  `fldText` varchar(250) DEFAULT NULL,
  `fldRating` tinyint(5) DEFAULT NULL,
  `fldDateSubmitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`fnkProductID` , `fnkUserID`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;


*/
?>