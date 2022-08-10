DELIMITER $$

DROP PROCEDURE IF EXISTS `mcsquare3`.`trial_balance` $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `trial_balance`(IN pi_company VARCHAR(30), IN pi_branch VARCHAR(30), IN pi_docid VARCHAR(30), IN pi_docdate_from VARCHAR(30), IN pi_type VARCHAR(20), IN pi_mode VARCHAR(20), IN pi_status VARCHAR(15), IN pi_info VARCHAR(254))
BEGIN
  DECLARE v_statusdesc VARCHAR(50);
  DECLARE v_subtitle2 VARCHAR(50);
  DECLARE gl_year VARCHAR(50);
  IF pi_mode = "DAILY" then
    set v_subtitle2 = DATE_FORMAT(pi_docdate_from, '%M %e, %Y');
  ELSEIF pi_mode = "ASOF" then
    set v_subtitle2 = concat("As of : ",DATE_FORMAT(pi_docdate_from, '%M %Y'));
  ELSEIF pi_mode = "RANGE" then
    set v_subtitle2 = concat(DATE_FORMAT(pi_docdate_from, '%M %e, %Y')," - ",DATE_FORMAT(pi_docdate_from, '%M %e, %Y'));
  end if;
  IF month(pi_docdate_from) = 1 then
     set gl_year = year(pi_docdate_from) - 1;
  ELSEIF month(pi_docdate_from) <> 1 then
     set gl_year = year(pi_docdate_from);
  end if;
CREATE TEMPORARY TABLE  `trial_balance` (
    `BRANCHNAME` varchar(30) NULL default '',
    `COMPANYNAME` varchar(500) NULL default '',
    `DOCDATE` DATE NULL,
    `GLACCTNO` varchar(100) NULL default '',
    `GLACCTNAME` varchar(500) NULL default '',
    `GLDEBIT` NUMERIC(18,6) NULL default '0',
    `GLCREDIT` NUMERIC(18,6) NULL default '0',
    `SUBTITLE` varchar(500) NULL default '',
    `SUBTITLE2` varchar(500) NULL default '',
    `INFO` varchar(100) NULL default ''
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO trial_balance (BRANCHNAME,COMPANYNAME,DOCDATE,GLACCTNO,GLACCTNAME,GLDEBIT,GLCREDIT,SUBTITLE,SUBTITLE2,INFO)
    SELECT UPPER(CONCAT(C.BRANCHCODE,' - ',C.BRANCHNAME)) AS BRANCHNAME,
           UPPER(D.COMPANYNAME) AS COMPANYNAME,
           A.DOCDATE,
           A.GLACCTNO,
           A.GLACCTNAME,
           sum(A.GLDEBIT),
           sum(A.GLCREDIT),
           v_statusdesc AS SUBTITLE,
           v_subtitle2 AS SUBTITLE2,
           pi_info AS INFO
    from JOURNALENTRYITEMS A
         left outer join JOURNALENTRIES B on A.COMPANY=B.COMPANY AND A.BRANCH=B.BRANCH AND A.DOCID = B.DOCID and A.SBO_POST_FLAG = B.SBO_POST_FLAG
         left outer join BRANCHES C on A.COMPANY=C.COMPANYCODE AND A.BRANCH=C.BRANCHCODE
         left outer join COMPANIES D on A.COMPANY=D.COMPANYCODE
      where A.COMPANY = pi_company and A.BRANCH=pi_branch
            AND A.SBO_POST_FLAG=1
            AND A.DOCDATE <= pi_docdate_from
    Group by A.GLACCTNO;
SELECT BRANCHNAME,
       COMPANYNAME,
       DOCDATE,
       GLACCTNO,
       GLACCTNAME,
       GLDEBIT,
       GLCREDIT,
       SUBTITLE,
       SUBTITLE2,
       INFO
from trial_balance
Order by GLACCTNO,DOCDATE;
END $$

DELIMITER ;