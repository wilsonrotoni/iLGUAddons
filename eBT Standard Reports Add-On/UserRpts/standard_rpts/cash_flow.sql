DELIMITER $$

DROP PROCEDURE IF EXISTS `cash_flow` $$
CREATE PROCEDURE `cash_flow`(IN pi_company VARCHAR(30), IN pi_branch VARCHAR(30), IN pi_date1 VARCHAR(30), IN pi_date2 VARCHAR(30))
BEGIN
DECLARE v_branch VARCHAR(100);
DECLARE gl_year VARCHAR(50);
SET v_branch = concat('%-', pi_branch,'-%');
  IF month(pi_date1) = 1 then
     set gl_year = year(pi_date1) - 1;
  ELSEIF month(pi_date1) <> 1 then
     set gl_year = year(pi_date1);
  end if;
CREATE TEMPORARY TABLE  `je` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `GLACCTNO` varchar(100) NULL default '',
    `GLACCTNAME` varchar(100) NULL default '',
    `GLDEBIT` NUMERIC(18,6) NULL default '0',
    `GLCREDIT` NUMERIC(18,6) NULL default '0'
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO je (COMPANY,
             BRANCH,
             GLACCTNO,
             GLACCTNAME,
             GLDEBIT,
             GLCREDIT)
      SELECT a.COMPANY, a.BRANCH, b.GLACCTNO, b.GLACCTNAME,
             b.GLDEBIT as GLDEBIT,
             b.GLCREDIT as GLCREDIT
             FROM journalentries a
             left outer join journalentryitems b
             on  b.COMPANY = a.COMPANY
             and b.BRANCH = a.BRANCH
             and b.docid = a.docid
             where a.COMPANY = pi_company and a.BRANCH = pi_branch and a.SBO_POST_FLAG=1
             and DATE_FORMAT(a.DOCDATE, '%y-%m') <= DATE_FORMAT(pi_date2, '%y-%m')
             and (b.GLACCTNO like '1%');
  INSERT
    INTO je (COMPANY,
             BRANCH,
             GLACCTNO,
             GLACCTNAME,
             GLDEBIT,
             GLCREDIT)
      SELECT a.COMPANY, a.BRANCH, b.GLACCTNO, b.GLACCTNAME,
             b.GLDEBIT as GLDEBIT,
             b.GLCREDIT as GLCREDIT
             FROM journalentries a
             left outer join journalentryitems b
             on  b.COMPANY = a.COMPANY
             and b.BRANCH = a.BRANCH
             and b.docid = a.docid
             where a.COMPANY = pi_company and a.BRANCH = pi_branch and a.SBO_POST_FLAG=1
             and DATE_FORMAT(a.DOCDATE, '%y-%m') <= DATE_FORMAT(pi_date2, '%y-%m')
             and (b.GLACCTNO like '2%');
  INSERT
    INTO je (COMPANY,
             BRANCH,
             GLACCTNO,
             GLACCTNAME,
             GLDEBIT,
             GLCREDIT)
      SELECT a.COMPANY, a.BRANCH, b.GLACCTNO, b.GLACCTNAME,
             b.GLDEBIT as GLDEBIT,
             b.GLCREDIT as GLCREDIT
             FROM journalentries a
             left outer join journalentryitems b
             on  b.COMPANY = a.COMPANY
             and b.BRANCH = a.BRANCH
             and b.docid = a.docid
             where a.COMPANY = pi_company and a.BRANCH = pi_branch and a.SBO_POST_FLAG=1
             and DATE_FORMAT(a.DOCDATE, '%y-%m') between DATE_FORMAT(pi_date1, '%y-%m')
             and DATE_FORMAT(pi_date2, '%y-%m')
             and (b.GLACCTNO like '6%');
SELECT upper(c.COMPANYNAME) as Company,
       pi_branch as BRANCH,
       UPPER(b.BRANCHNAME) AS BRANCHNAME,
if((A.GLACCTNO like '2%'), 0 - (if (sum(a.GLDEBIT) is null, 0, sum(a.GLDEBIT))), (if (sum(a.GLDEBIT) is null, 0, sum(a.GLDEBIT)))) AS debit,
if((A.GLACCTNO like '2%'), 0 - (if (sum(a.GLCREDIT) is null, 0, sum(a.GLCREDIT))), (if (sum(a.GLCREDIT) is null, 0, sum(a.GLCREDIT)))) AS credit,
case when A.GLACCTNO like '1%' then 1
     when A.GLACCTNO like '2%' then 2
     when A.GLACCTNO like '6%' then 6
else 0
end as drawer_no,
c3.acctcode as level_2_no,
c3.acctname as level_2_name,
c2.acctcode AS level0_code,
c2.acctname AS level0_name,
a.GLACCTNO AS acct_code,
a.GLACCTNAME AS acct_name,
upper(case when A.GLACCTNO like '1%' then 'Receivables'
     when A.GLACCTNO like '2%' then 'Creditors'
     when A.GLACCTNO like '6%' then 'Expenses'
else ''
end) as drawer,
date(pi_date1) as date1,
date(pi_date2) as date2,
date(pi_date1)- interval 2 month as date3
FROM je a
left outer join chartofaccounts c1 on a.GLACCTNO = c1.formatcode
left outer join chartofaccounts c2 on c1.parentacct = c2.acctcode
left outer join chartofaccounts c3 on c2.parentacct = c3.acctcode
left outer join branches b on b.BRANCHCODE = pi_branch
left outer join companies c on c.COMPANYCODE = pi_company
Group by a.GLACCTNO
Order by A.GLACCTNO;
END $$

DELIMITER ;