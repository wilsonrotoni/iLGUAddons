DELIMITER $$

DROP PROCEDURE IF EXISTS `subsidiary_ledger` $$
CREATE PROCEDURE `subsidiary_ledger`(IN pi_company VARCHAR(30),
                                     IN pi_branch VARCHAR(30),
                                     IN pi_acctno VARCHAR(30),
                                     IN pi_date1 VARCHAR(30))
BEGIN
  DECLARE v_statusdesc VARCHAR(50);
  DECLARE v_subtitle2 VARCHAR(50);
  DECLARE gl_year VARCHAR(50);
  IF month(pi_date1) = 1 then
     set gl_year = year(pi_date1) - 1;
  ELSEIF month(pi_date1) <> 1 then
     set gl_year = year(pi_date1);
  end if;
CREATE TEMPORARY TABLE  `je` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DUEDATE` DATE NULL,
    `DOCDATE` DATE NULL,
    `GLACCTNO` varchar(100) NULL default '',
    `GLACCTNAME` varchar(500) NULL default '',
    `GLDEBIT` NUMERIC(18,6) NULL default '0',
    `GLCREDIT` NUMERIC(18,6) NULL default '0',
    `SLACCTNO` varchar(100) NULL default '',
    `SLACCTNAME` varchar(100) NULL default '',
    `SLDEBIT` NUMERIC(18,6) NULL default '0',
    `SLCREDIT` NUMERIC(18,6) NULL default '0',
    `DOCTYPE` varchar(100) NULL default '',
    `DOCNO` varchar(100) NULL default '',
    `REFNO` varchar(100) NULL default '',
    `REMARKS` varchar(1000) NULL default '',
    `LINEID` varchar(100) NULL default '',
    `DOCSTATUS` varchar(100) NULL default '',
    `SLTYPE` varchar(100) NULL default ''
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO je (COMPANY, BRANCH, DUEDATE, DOCDATE, GLACCTNO, GLACCTNAME, GLDEBIT, GLCREDIT,
             SLACCTNO, SLACCTNAME, SLDEBIT, SLCREDIT, DOCTYPE, DOCNO, REFNO, REMARKS, LINEID, DOCSTATUS, SLTYPE)
      SELECT a.COMPANY, a.BRANCH, a.DOCDUEDATE, a.DOCDATE,
             b.GLACCTNO, b.GLACCTNAME,
             if (b.GLDEBIT is null, 0, b.GLDEBIT), if (b.GLCREDIT is null, 0, b.GLCREDIT),
             CASE
             WHEN b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND b.BANKACCTNO <> '' THEN
             b.BANKACCTNO
             WHEN b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND b.BANKACCTNO = '' THEN
             b.SUBSIDIARY
             ELSE b.SLACCTNO END,
             CASE
             WHEN b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND b.BANKACCTNO <> '' THEN
             b.BANK
             WHEN b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND b.BANKACCTNO = '' THEN
             q.SUBSIDIARYNAME
             ELSE if (b.REFERENCE2 = '' , concat(b.SLACCTNAME, ' ', b.REFERENCE1), concat(b.SLACCTNAME, ' ', b.REFERENCE2)) END,
             CASE
             WHEN b.SLDEBIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND b.BANKACCTNO <> '' THEN
             if (b.GLDEBIT is null, 0, b.GLDEBIT)
             WHEN b.SLDEBIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND b.BANKACCTNO = '' THEN
             if (b.GLDEBIT is null, 0, b.GLDEBIT)
             ELSE if(b.SLDEBIT = 0,if (b.GLDEBIT is null, 0, b.GLDEBIT),if (b.SLDEBIT is null, 0, b.SLDEBIT)) END,
             CASE
             WHEN b.SLCREDIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND b.BANKACCTNO <> '' THEN
             if (b.GLCREDIT is null, 0, b.GLCREDIT)
             WHEN b.SLCREDIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND b.BANKACCTNO = '' THEN
             if (b.GLCREDIT is null, 0, b.GLCREDIT)
             ELSE if(b.GLCREDIT = 0,if (b.GLCREDIT is null, 0, b.GLCREDIT), if (b.SLCREDIT is null, 0, b.SLCREDIT)) END,
             b.DOCTYPE, a.DOCNO, b.REFERENCE1, a.REMARKS, b.LINEID,
             IF(a.SBO_POST_FLAG<>0, 'POSTED', 'UNPOSTED'),
             CASE WHEN b.SLTYPE = 'C' THEN ' - CUSTOMER' WHEN b.SLTYPE = 'S' THEN ' - SUPPLIER' ELSE '' END
             FROM journalentries a
             LEFT OUTER JOIN journalentryitems b
             on  b.COMPANY = a.COMPANY
             and b.BRANCH = a.BRANCH
             and b.docid = a.docid
             LEFT OUTER JOIN chartofaccountsubsidiaries q on b.SUBSIDIARY = q.SUBSIDIARY
             where a.COMPANY = pi_company and a.BRANCH = pi_branch
             AND A.DOCDATE <= pi_date1
             AND ((b.GLACCTNO like '1%') or (b.GLACCTNO like '2%') or (b.GLACCTNO like '3%'))
             AND (pi_acctno='' or (pi_acctno<>'' AND b.GLACCTNO = pi_acctno));
  INSERT
    INTO je (COMPANY, BRANCH, DUEDATE, DOCDATE, GLACCTNO, GLACCTNAME, GLDEBIT, GLCREDIT,
             SLACCTNO, SLACCTNAME, SLDEBIT, SLCREDIT, DOCTYPE, DOCNO, REFNO, REMARKS, LINEID, DOCSTATUS, SLTYPE)
      SELECT a.COMPANY, a.BRANCH, a.DOCDUEDATE, a.DOCDATE,
             b.GLACCTNO, b.GLACCTNAME,
             if (b.GLDEBIT is null, 0, b.GLDEBIT), if (b.GLCREDIT is null, 0, b.GLCREDIT),
             CASE
             WHEN b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND b.BANKACCTNO <> '' THEN
             b.BANKACCTNO
             WHEN b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND b.BANKACCTNO = '' THEN
             b.SUBSIDIARY
             ELSE b.SLACCTNO END,
             CASE
             WHEN b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND b.BANKACCTNO <> '' THEN
             b.BANK
             WHEN b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND b.BANKACCTNO = '' THEN
             q.SUBSIDIARYNAME
             ELSE if (b.REFERENCE2 = '' , concat(b.SLACCTNAME, ' ', b.REFERENCE1), concat(b.SLACCTNAME, ' ', b.REFERENCE2)) END,
             CASE
             WHEN b.SLDEBIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND b.BANKACCTNO <> '' THEN
             if (b.GLDEBIT is null, 0, b.GLDEBIT)
             WHEN b.SLDEBIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND b.BANKACCTNO = '' THEN
             if (b.GLDEBIT is null, 0, b.GLDEBIT)
             ELSE if(b.SLDEBIT = 0,if (b.GLDEBIT is null, 0, b.GLDEBIT),if (b.SLDEBIT is null, 0, b.SLDEBIT)) END,
             CASE
             WHEN b.SLCREDIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND b.BANKACCTNO <> '' THEN
             if (b.GLCREDIT is null, 0, b.GLCREDIT)
             WHEN b.SLCREDIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND b.BANKACCTNO = '' THEN
             if (b.GLCREDIT is null, 0, b.GLCREDIT)
             ELSE if(b.GLCREDIT = 0,if (b.GLCREDIT is null, 0, b.GLCREDIT), if (b.SLCREDIT is null, 0, b.SLCREDIT)) END,
             b.DOCTYPE, a.DOCNO, b.REFERENCE1, a.REMARKS, b.LINEID,
             IF(a.SBO_POST_FLAG<>0, 'POSTED', 'UNPOSTED'),
             CASE WHEN b.SLTYPE = 'C' THEN ' - CUSTOMER' WHEN b.SLTYPE = 'S' THEN ' - SUPPLIER' ELSE '' END
             FROM journalentries a
             LEFT OUTER JOIN journalentryitems b
             on  b.COMPANY = a.COMPANY
             and b.BRANCH = a.BRANCH
             and b.docid = a.docid
             LEFT OUTER JOIN chartofaccountsubsidiaries q on b.SUBSIDIARY = q.SUBSIDIARY
             where a.COMPANY = pi_company and a.BRANCH = pi_branch
             AND A.DOCDATE between date(concat(gl_year,'-02-01')) AND pi_date1
             AND ((b.GLACCTNO like '4%') or (b.GLACCTNO like '5%')
                or (b.GLACCTNO like '6%') or (b.GLACCTNO like '7%'))
             AND (pi_acctno='' or (pi_acctno<>'' AND b.GLACCTNO = pi_acctno));
SELECT upper(c.COMPANYNAME) as COMPANY,
pi_branch as BRANCH,
UPPER(CONCAT(pi_branch, ' - ', b.BRANCHNAME)) AS BRANCHNAME,
Date(a.DOCDATE) as POSTINGDDATE,
Date(a.DUEDATE) as DUEDATE,
DATE(pi_date1) AS DATE1,
if(pi_acctno = '', 'All', concat(a.GLACCTNO, ' - ',a.GLACCTNAME)) as DOCTYPENAME,
a.DOCNO,
a.DOCTYPE,
a.REFNO,
a.GLACCTNO AS ACCTCODE,
a.GLACCTNAME AS ACCTNAME,
if (a.GLDEBIT is null, 0, a.GLDEBIT) AS DEBIT,
if (a.GLCREDIT is null, 0, a.GLCREDIT) AS CREDIT,
a.SLACCTNO AS SLACCTCODE,
a.SLACCTNAME AS SLACCTNAME,
if (a.SLDEBIT is null, 0, a.SLDEBIT) AS SLDEBIT,
if (a.SLCREDIT is null, 0, a.SLCREDIT) AS SLCREDIT,
UPPER(a.REMARKS) AS REMARKS,
a.DOCSTATUS,
a.SLTYPE
FROM je a
LEFT OUTER JOIN branches b on b.BRANCHCODE = pi_branch
LEFT OUTER JOIN companies c on c.COMPANYCODE = pi_company;
END $$

DELIMITER ;