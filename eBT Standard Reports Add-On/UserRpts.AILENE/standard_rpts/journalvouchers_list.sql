DELIMITER $$

DROP PROCEDURE IF EXISTS `journalvouchers_list` $$
CREATE PROCEDURE `journalvouchers_list`(IN pi_company VARCHAR(30), IN pi_branch VARCHAR(30), IN pi_doctype VARCHAR(30), IN pi_date1 VARCHAR(30), IN pi_date2 VARCHAR(30), IN pi_status VARCHAR(30))
BEGIN
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
    INTO je (COMPANY, BRANCH, DUEDATE, DOCDATE, GLACCTNO, GLACCTNAME, GLDEBIT, GLCREDIT, SLACCTNO,
             SLACCTNAME, SLDEBIT, SLCREDIT, DOCTYPE, DOCNO, REFNO, REMARKS, LINEID, DOCSTATUS, SLTYPE)
      SELECT a.COMPANY, a.BRANCH, a.DOCDUEDATE, a.DOCDATE,
             b.ITEMNO, b.ITEMNAME,
             if (b.DEBIT is null, 0, b.DEBIT), if (b.CREDIT is null, 0, b.CREDIT),
             CASE
             WHEN b.SUBSIDIARY = '' AND b.BANKACCTNO <> '' THEN
             b.BANKACCTNO
             WHEN b.SUBSIDIARY <> '' AND b.BANKACCTNO = '' THEN
             b.SUBSIDIARY
             ELSE '' END,
             CASE
             WHEN b.SUBSIDIARY = '' AND b.BANKACCTNO <> '' THEN
             b.BANK
             WHEN b.SUBSIDIARY <> '' AND b.BANKACCTNO = '' THEN
             q.SUBSIDIARYNAME
             ELSE '' END,
             if (b.DEBIT is null, 0, b.DEBIT),
             if (b.CREDIT is null, 0, b.CREDIT) ,
             '', a.DOCNO, b.REFERENCE3, a.REMARKS, b.LINEID,
             IF(a.SBO_POST_FLAG<>0, 'POSTED', 'UNPOSTED'),
             CASE WHEN b.ITEMTYPE = 'C' THEN ' - CUSTOMER' WHEN b.ITEMTYPE = 'S' THEN ' - SUPPLIER' ELSE '' END
             FROM journalvouchers a
             LEFT OUTER JOIN journalvoucheritems b
             on  b.COMPANY = a.COMPANY
             and b.BRANCH = a.BRANCH
             and b.docid = a.docid
             LEFT OUTER JOIN chartofaccountsubsidiaries q on b.SUBSIDIARY = q.SUBSIDIARY
             where a.COMPANY = pi_company and a.BRANCH = pi_branch
             and a.DOCDATE BETWEEN pi_date1 AND pi_date2
             AND (pi_doctype='' or (pi_doctype<>'' AND a.DOCGROUP = pi_doctype));
SELECT upper(c.COMPANYNAME) as COMPANY,
pi_branch as BRANCH,
UPPER(CONCAT(pi_branch, ' - ', b.BRANCHNAME)) AS BRANCHNAME,
Date(a.DOCDATE) as POSTINGDDATE,
Date(a.DUEDATE) as DUEDATE,
DATE(pi_date1) AS DATE1,
DATE(pi_date2) DATE2,
IF(pi_doctype = '', 'ALL', UPPER(d.DOCGROUPNAME)) as DOCTYPENAME,
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
LEFT OUTER JOIN companies c on c.COMPANYCODE = pi_company
LEFT OUTER JOIN docgroups d on d.DOCGROUP = pi_doctype
WHERE (pi_status='' or (pi_status<>'' AND a.DOCSTATUS = pi_status));
END $$

DELIMITER ;