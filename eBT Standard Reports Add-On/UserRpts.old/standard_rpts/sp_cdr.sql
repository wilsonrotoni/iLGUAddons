DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_cdr` $$
CREATE PROCEDURE `sp_cdr`(
	IN pi_company varchar(30), IN pi_branch varchar(30),
	IN pi_date_fr varchar(15), IN pi_date_to varchar(15))
BEGIN
	DECLARE branch_name varchar(100);
	
	SET branch_name = (
		SELECT b.branchname
		FROM branches AS b
		WHERE b.branchcode = pi_branch);

	

	
	SELECT branch_name, YEAR(p.docdate) AS 'year', MONTH(p.docdate) AS 'month',
		p.docdate AS 'doc_date', p.docno AS 'rc_no', p.doctype,
		pc.checkdate AS 'check_date', pc.checkno AS 'check_no',
		
		p.address AS 'payee', p.remarks AS 'particulars',
		jed.glacctname AS 'journal_entry', jed.gldebit AS 'debit', jed.glcredit AS 'credit',
		CASE p.approvalstatus 
			WHEN 'a' THEN 'Approved'
			WHEN 'd' THEN 'Not Approved'
			ELSE ''
			END AS 'remarks'
	FROM payments AS p
		
		LEFT JOIN paymentcheques AS pc ON p.company = pc.company AND p.branchcode = pc.branch AND p.docno = pc.docno
		
		LEFT JOIN journalentries AS je ON p.company = je.company AND p.branchcode = je.branch AND p.docno = je.docno
			LEFT JOIN journalentryitems AS jed ON je.company = jed.company AND je.branch = jed.branch 
				AND je.docid = jed.docid
	WHERE 
		p.sbo_post_flag = 1
		
		#AND (p.approvalstatus = 'a' OR (p.approvalstatus = '' AND YEAR(p.docdate) < 2011))
		AND p.company = pi_company AND p.branchcode = pi_branch
		AND p.docdate BETWEEN pi_date_fr AND pi_date_to
	ORDER BY p.docdate, p.docno;
END $$

DELIMITER ;