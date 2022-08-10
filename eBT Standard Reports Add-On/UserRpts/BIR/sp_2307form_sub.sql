DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_2307form_sub` $$
CREATE PROCEDURE `sp_2307form_sub`(IN pi_company VARCHAR(50),
                           IN pi_branch VARCHAR(50),
                           IN pi_docno VARCHAR(50))
BEGIN
SELECT b.wtaxcode as wtaxcode,
       c.taxableamount AS 'Tax Base',
       c.wtaxrate as RATE,
       b.wtaxamount AS 'WTAXAMOUNT',
       h.u_nop AS 'Nature of Payment',
       IF(MONTH(b.docdate) IN (1,4,7,10),SUM(c.taxableamount),0) AS '1st month amount',
       IF(MONTH(b.docdate) IN (2,5,8,11),SUM(c.taxableamount),0) AS '2nd month amount',
       IF(MONTH(b.docdate) IN (3,6,9,12),SUM(c.taxableamount),0) AS '3rd month amount'
FROM apinvoices b
LEFT JOIN apinvoicewtaxitems c ON c.company=b.company and c.branch=b.branch and c.docid=b.docid
LEFT JOIN wtaxes h ON h.wtaxcode = b.wtaxcode
WHERE b.company = pi_company
  AND b.branch = pi_branch
  AND b.docno = pi_docno
;
END $$

DELIMITER ;