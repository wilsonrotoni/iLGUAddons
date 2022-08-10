DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_soa` $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_soa`(
    IN pi_company VARCHAR(30),
    IN pi_branch VARCHAR(30),
    IN pi_custno VARCHAR(30),
    IN pi_date VARCHAR(30),
    IN pi_currency VARCHAR(30)

)
BEGIN
	call ar_aging_new4(pi_company,pi_branch,'','','',pi_custno,pi_date,'x','',pi_currency);

  SELECT (date(pi_date) + interval 1 day) as SOADATE,date(pi_date) as SOAPERIOD, a.BPCODE, a.BPNAME, a.CURRENCY, concat(b.STREET) as ADDRESS, sum(a.DUEAMOUNT) as DUEAMOUNT
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
         left outer join addresses b on b.company=ct.company and b.branch=ct.branch and b.reftype='CUSTOMER' and b.refid=ct.custno and b.addressname=ct.dfltbillto and b.addresstype=0
    where (pi_custno='' or (pi_custno<>'' and (a.BPCODE=pi_custno)))
       group by a.BPCODE HAVING DUEAMOUNT<>0;

END $$

DELIMITER ;