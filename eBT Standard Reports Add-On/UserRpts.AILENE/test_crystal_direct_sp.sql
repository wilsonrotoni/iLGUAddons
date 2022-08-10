DELIMITER $$

DROP PROCEDURE IF EXISTS `test` $$
CREATE PROCEDURE `test`(IN pi_account varchar(200))
BEGIN

select * from accounts where (pi_account='' or (pi_account<>'' AND acctno = pi_account));

END $$

DELIMITER ;