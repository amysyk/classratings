CREATE TABLE `rating` (
  `ratingId` int(11) NOT NULL AUTO_INCREMENT,
  `classId` int(11) NOT NULL,
  `rating` double DEFAULT NULL,
  `reviewText` varchar(1500) DEFAULT NULL,
  `lastUpdatedBy` varchar(100) NOT NULL,
  `lastUpdatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ratingId`)
) ENGINE=MyISAM AUTO_INCREMENT=472 DEFAULT CHARSET=latin1 AUTO_INCREMENT=472 ;
