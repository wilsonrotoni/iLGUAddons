DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_2307form` $$
CREATE PROCEDURE `sp_2307form`(IN pi_company VARCHAR(50),
                           IN pi_branch VARCHAR(50),
                           IN pi_docno VARCHAR(50))
BEGIN
SELECT b.docno,
       date_add(date_add(LAST_DAY(b.docdate),interval 1 DAY),interval -1 MONTH) AS 'FirstDAy',
       LAST_DAY(b.docdate) AS 'DATE',
       d.taxid AS 'TIN',
       SUBSTRING_INDEX(SUBSTRING_INDEX(d.taxid,'-',1),'-',-1) AS 'TIN1',
       SUBSTRING_INDEX(SUBSTRING_INDEX(d.taxid,'-',2),'-',-1) AS 'TIN2',
       SUBSTRING_INDEX(SUBSTRING_INDEX(d.taxid,'-',3),'-',-1) AS 'TIN3',
       SUBSTRING_INDEX(SUBSTRING_INDEX(d.taxid,'-',4),'-',-1) AS 'TIN4',
       d.suppname AS 'Registered Name',
       b.wtaxcode as wtaxcode,
       1 as wtaxliable,
       c.taxableamount AS 'Tax Base',
       c.wtaxrate as RATE,
       c.wtaxamount AS 'WTAXAMOUNT',
       h.u_nop AS 'Nature of Payment',
       co.companyname AS 'COMPANYNAME',
       br.taxid AS 'COMPANYTIN',
       SUBSTRING_INDEX(SUBSTRING_INDEX(br.taxid,'-',1),'-',-1) AS 'COMPANYTIN1',
       SUBSTRING_INDEX(SUBSTRING_INDEX(br.taxid,'-',2),'-',-1) AS 'COMPANYTIN2',
       SUBSTRING_INDEX(SUBSTRING_INDEX(br.taxid,'-',3),'-',-1) AS 'COMPANYTIN3',
       SUBSTRING_INDEX(SUBSTRING_INDEX(br.taxid,'-',4),'-',-1) AS 'COMPANYTIN4',
       CONCAT(br.street,' ',br.city) AS 'COMPANYADDRESS',
       br.zip AS 'ZIP',
       CONCAT(replace(b.billtoaddress, '\r\n',', ')) AS 'ADDRESS',
       IF(MONTH(b.docdate) IN (1,4,7,10),c.taxableamount,0) AS '1st month amount',
       IF(MONTH(b.docdate) IN (2,5,8,11),c.taxableamount,0) AS '2nd month amount',
       IF(MONTH(b.docdate) IN (3,6,9,12),c.taxableamount,0) AS '3rd month amount',
       MONTHNAME(b.docdate) AS 'MONTHSNAME',
       d.suppno as bpcode

FROM apinvoices b
LEFT JOIN apinvoicewtaxitems c ON c.company=b.company and c.branch=b.branch and c.docid=b.docid
LEFT JOIN suppliers d ON d.suppno = b.bpcode
LEFT JOIN wtaxes h ON h.wtaxcode = b.wtaxcode
LEFT JOIN companies co ON co.companycode = b.company
LEFT JOIN branches br ON br.companycode = co.companycode and br.branchcode = b.branch

WHERE b.company = pi_company
  AND b.branch = pi_branch
  AND b.docno = pi_docno
  AND b.wtaxamount > 0

;
END $$

DELIMITER ;