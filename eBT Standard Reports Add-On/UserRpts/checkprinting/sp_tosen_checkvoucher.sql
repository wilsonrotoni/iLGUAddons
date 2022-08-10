-- Check voucher
-- by Ralph
-- Feb 13, 2012, 11:39pm

DROP PROCEDURE IF EXISTS sp_tosen_checkvoucher;
CREATE PROCEDURE sp_tosen_checkvoucher(
	IN pi_company varchar(30), IN pi_branch varchar(30), 
	IN pi_docno varchar(30))
BEGIN
	SELECT 
		#pang-group
		p.docno,
		#header
		p.bpname AS 'Payee', p.docdate AS 'Date', p.paidamount AS 'Pesos'
	FROM payments AS p
		LEFT JOIN paymentcheques AS pc ON pc.company = p.company AND pc.branch = p.branchcode AND pc.docid = p.docid
		LEFT JOIN journalentries AS je ON je.company = p.company AND je.branch = p.branchcode AND je.docno = p.docno
			LEFT JOIN journalentryitems AS jei ON jei.company = je.company AND jei.branch = je.branch AND jei.docid = je.docid
		LEFT JOIN users AS u ON u.userid = p.createdby
	WHERE p.company = pi_company AND p.branchcode = pi_branch AND p.docno = pi_docno;
END;

CALL sp_tosen_checkvoucher('tfi', 'main', 'main-pv00000001');