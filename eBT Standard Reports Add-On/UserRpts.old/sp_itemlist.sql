DELIMITER $$

DROP PROCEDURE IF EXISTS `sbodemo_uk`.`sp_itemlist` $$
CREATE PROCEDURE sbodemo_uk.`sp_itemlist`(IN pi_itemcode_fr VARCHAR(30),
IN pi_itemcode_to VARCHAR(30), IN pi_itemgroup VARCHAR(30))
BEGIN

  select itemcode,itemdesc,itemgroup from items where (pi_itemgroup='' or (pi_itemgroup<>'' and pi_itemgroup=items.itemgroup));
END $$

DELIMITER ;