DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_processtime` $$
CREATE PROCEDURE `sp_processtime`(
    IN pi_desc VARCHAR(100),
    IN pi_mode VARCHAR(10)
 )
BEGIN

if (pi_desc='start')  then
DROP TEMPORARY TABLE IF EXISTS `ttbl_processtime`;
CREATE TEMPORARY TABLE  `ttbl_processtime` (
    `ID` INTEGER NOT NULL auto_increment,
    `DESC` varchar(100) NULL default '',
    `START` DATETIME NULL,
    `END` DATETIME NULL,
    `LAPS` TIME NULL,
    PRIMARY KEY  (`ID`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
    INSERT INTO ttbl_processtime (`DESC`,`START`) VALUES ('Process Time',now());
elseif (pi_mode='start') then
    INSERT INTO ttbl_processtime (`DESC`,`START`) VALUES (pi_desc,now());
elseif (pi_mode='end') then
   update ttbl_processtime set `END` = now(), `LAPS` = TIMEDIFF(NOW(), START) WHERE `DESC`=pi_desc;
elseif (pi_desc='end')  then
   update ttbl_processtime set `END` = now(), `LAPS` = TIMEDIFF(NOW(), START) WHERE `DESC`='Process Time';
elseif (pi_desc='show')  then
   SELECT * from ttbl_processtime order by id;
end if;

END $$

DELIMITER ;