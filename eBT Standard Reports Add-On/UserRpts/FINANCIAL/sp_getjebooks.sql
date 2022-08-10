DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_getjebooks` $$
CREATE PROCEDURE `sp_getjebooks`()
BEGIN
	DROP TEMPORARY TABLE IF EXISTS jebooks;
	CREATE TEMPORARY TABLE jebooks(
		`CODE` varchar(30) NULL default '',
		`NAME` varchar(100) NULL default '',
		PRIMARY KEY (`CODE`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;
	INSERT INTO jebooks(CODE, NAME) VALUES('SB','Sales');
	INSERT INTO jebooks(CODE, NAME) VALUES('PB','Purchases'); 
END $$

DELIMITER ;