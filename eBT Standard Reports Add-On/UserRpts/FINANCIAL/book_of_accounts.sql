DELIMITER $$

DROP PROCEDURE IF EXISTS `book_of_accounts` $$
CREATE PROCEDURE `book_of_accounts`(
	IN pi_company VARCHAR(30), IN pi_branch VARCHAR(30),
	IN pi_doctype VARCHAR(30), 
	IN pi_date1 VARCHAR(30),
  IN pi_date2 VARCHAR(30),
  IN pi_book VARCHAR(30),
  IN pi_glacctno VARCHAR(30),
  IN pi_dep VARCHAR(30),
  IN pi_pc VARCHAR(30))
BEGIN
	DROP TEMPORARY TABLE IF EXISTS je;
	CREATE TEMPORARY TABLE je(
		`COMPANY` varchar(30) NULL default '',
		`BRANCH` varchar(30) NULL default '',
		`DUEDATE` DATE NULL,
		`DOCDATE` DATE NULL,
		`GLACCTNO` varchar(100) NULL default '',
		`GLACCTNAME` varchar(500) NULL default '',
		`GLDEBIT` NUMERIC(18,6) NULL default 0,
		`GLCREDIT` NUMERIC(18,6) NULL default 0,
		`SLACCTNO` varchar(100) NULL default '',
		`SLACCTNAME` varchar(500) NULL default '',
		`SLDEBIT` NUMERIC(18,6) NULL default 0,
		`SLCREDIT` NUMERIC(18,6) NULL default 0,
		`DOCTYPE` varchar(100) NULL default '',
		`DOCTYPEDESC` varchar(100) NULL default '',
		`DOCNO` varchar(100) NULL default '',
		`REFNO` varchar(100) NULL default '',
		`REMARKS` varchar(5000) NULL default '',
		`LINEID` varchar(100) NULL default '',
		`DOCSTATUS` varchar(100) NULL default '',
		`SLTYPE` varchar(100) NULL default '',
		`profitcenter` varchar(100) NULL default '',
		`profitcentername` varchar(500) NULL default '',
		`department` varchar(100) NULL default '',
		`departmentname` varchar(500) NULL default ''
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CALL sp_getjedoctypes();
CALL sp_getjebooks();

	INSERT INTO je()

		SELECT a.COMPANY, a.BRANCH, a.DOCDUEDATE, a.DOCDATE,
			b.GLACCTNO, b.GLACCTNAME,
			IF (b.GLDEBIT IS NULL, 0, b.GLDEBIT), IF (b.GLCREDIT IS NULL, 0, b.GLCREDIT),
			CASE WHEN b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND coa.CASHINBANKACCT = 1 THEN b.BANKACCTNO
				WHEN b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND coa.CASHINBANKACCT = 0 THEN b.SUBSIDIARY
				ELSE b.SLACCTNO END,
			CASE WHEN b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND coa.CASHINBANKACCT = 1 THEN b.BANK
				WHEN b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND coa.CASHINBANKACCT = 0 THEN q.SUBSIDIARYNAME
				ELSE IF (b.REFERENCE2 = '' , concat(b.SLACCTNAME, ' ', b.REFERENCE1), concat(b.SLACCTNAME, ' ', b.REFERENCE2)) END,
			CASE WHEN b.SLDEBIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND coa.CASHINBANKACCT = 1 THEN IF (b.GLDEBIT IS NULL, 0, b.GLDEBIT)
				WHEN b.SLDEBIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND coa.CASHINBANKACCT = 0 THEN IF (b.GLDEBIT IS NULL, 0, b.GLDEBIT)
				ELSE IF(b.SLDEBIT = 0,IF (b.GLDEBIT IS NULL, 0, b.GLDEBIT),IF (b.SLDEBIT IS NULL, 0, b.SLDEBIT)) END,
			CASE
				WHEN b.SLCREDIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY = '' AND coa.CASHINBANKACCT = 1 THEN
				IF (b.GLCREDIT IS NULL, 0, b.GLCREDIT)
				WHEN b.SLCREDIT = 0 AND b.SLACCTNO = '' AND b.SUBSIDIARY <> '' AND coa.CASHINBANKACCT = 0 THEN
				IF (b.GLCREDIT IS NULL, 0, b.GLCREDIT)
				ELSE IF(b.GLCREDIT = 0,IF (b.GLCREDIT IS NULL, 0, b.GLCREDIT), IF (b.SLCREDIT IS NULL, 0, b.SLCREDIT)) END,
			b.DOCTYPE, jte.NAME, a.DOCNO, b.REFERENCE1, a.DOCREMARKS, b.LINEID,
			IF(a.SBO_POST_FLAG<>0, 'POSTED', 'UNPOSTED'),
			CASE WHEN b.SLTYPE = 'C' THEN ' - CUSTOMER' WHEN b.SLTYPE = 'S' THEN ' - SUPPLIER' ELSE '' END,
      pc.profitcenter,
      pc.profitcentername,
      dp.department,
      dp.departmentname

		FROM journalentries a
			LEFT OUTER JOIN journalentryitems b on b.COMPANY = a.COMPANY AND b.BRANCH = a.BRANCH AND b.docid = a.docid
			LEFT OUTER JOIN chartofaccountsubsidiaries q on b.SUBSIDIARY = q.SUBSIDIARY
      LEFT OUTER JOIN chartofaccounts coa on coa.formatcode = b.glacctno
      LEFT OUTER JOIN jedoctypes jte on jte.CODE = b.DOCTYPE
      LEFT OUTER JOIN profitcenters pc on b.profitcenter = pc.profitcenter
      LEFT OUTER JOIN departments dp on b.department = dp.department

		where a.COMPANY = pi_company
      AND (pi_branch='' or (pi_branch<>'' AND a.BRANCH = pi_branch))
			AND a.DOCDATE BETWEEN pi_date1 AND pi_date2
			AND (pi_doctype='' or (pi_doctype<>'' AND b.DOCTYPE = pi_doctype))
      AND (pi_book='' or (pi_book='SB' AND b.DOCTYPE in ('AR','NR','CS','CM','DN','RT')) or (pi_book='PB' AND b.DOCTYPE in ('AP','ACM','PDN','PRT')))
			AND (pi_glacctno='' or (pi_glacctno<>'' AND b.GLACCTNO = pi_glacctno))
			AND (pi_pc ='' or (pi_pc <>'' and b.profitcenter = pi_pc))
			AND (pi_dep ='' or (pi_dep <>'' and b.department = pi_dep));

	SELECT upper(c.COMPANYNAME) as COMPANY,
		if(pi_branch='', 'ALL', b.BRANCHCODE) as BRANCH,
		if(pi_branch='', 'ALL', UPPER(CONCAT(b.BRANCHCODE, ' - ', b.BRANCHNAME))) AS BRANCHNAME,
		Date(a.DOCDATE) as POSTINGDDATE,
		Date(a.DUEDATE) as DUEDATE,
		DATE(pi_date1) AS DATE1,
		DATE(pi_date2) DATE2,
		IF(pi_doctype = '', 'ALL', UPPER(d.NAME)) as DOCTYPENAME,
		IF(pi_book = '', 'ALL', UPPER(e.NAME)) as BOOKNAME,
		IF(pi_glacctno = '', 'ALL', UPPER(f.ACCTNAME)) as GLACCTNAME,
		a.DOCNO,
		a.DOCTYPE,
		a.DOCTYPEDESC,
		a.REFNO,
		a.GLACCTNO AS ACCTCODE,
		a.GLACCTNAME AS ACCTNAME,
		IF (a.GLDEBIT IS NULL, 0, a.GLDEBIT) AS DEBIT,
		IF (a.GLCREDIT IS NULL, 0, a.GLCREDIT) AS CREDIT,
		a.SLACCTNO AS SLACCTCODE,
		a.SLACCTNAME AS SLACCTNAME,
		IF (a.SLDEBIT IS NULL, 0, a.SLDEBIT) AS SLDEBIT,
		IF (a.SLCREDIT IS NULL, 0, a.SLCREDIT) AS SLCREDIT,
		UPPER(a.REMARKS) AS REMARKS,
		a.DOCSTATUS,
		a.SLTYPE,
    a.profitcenter,
    a.profitcentername,
    a.department,
    a.departmentname,
 case
          when a.doctype = 'ACM' then IFNULL(concat(if(apcm.bpcode = '', '',concat(apcm.bpcode,' - ')),apcm.bpname), '')
          when a.doctype = 'AP' then IFNULL(concat(if(ap.bpcode = '', '',concat(ap.bpcode,' - ')),ap.bpname), '')
          when a.doctype = 'AR' then IFNULL(concat(if(ar.bpcode = '', '',concat(ar.bpcode,' - ')),ar.bpname), '')
          when a.doctype = 'CM' then IFNULL(concat(if(cm.bpcode = '', '',concat(cm.bpcode,' - ')),cm.bpname), '')
          when a.doctype = 'CS' then IFNULL(concat(if(ar.bpcode = '', '',concat(ar.bpcode,' - ')),ar.bpname), '')
          when a.doctype = 'GI' then IFNULL(concat(if(gi.bpcode = '', '',concat(gi.bpcode,' - ')),gi.bpname), '')
          when a.doctype = 'GR' then IFNULL(concat(if(gr.bpcode = '', '',concat(gr.bpcode,' - ')),gr.bpname), '')
          when a.doctype = 'GT' then IFNULL(concat(if(st.bpcode = '', '',concat(st.bpcode,' - ')),st.bpname), '')
          when a.doctype = 'PDN' then IFNULL(concat(if(pd.bpcode = '', '',concat(pd.bpcode,' - ')),pd.bpname), '')
          when a.doctype = 'PY' then IFNULL(concat(if(pay.bpcode = '', '',concat(pay.bpcode,' - ')),pay.bpname), '')
          when a.doctype = 'RC' then IFNULL(concat(if(col.bpcode = '', '',concat(col.bpcode,' - ')),col.bpname), '')
          else ''
      end payee,

      case
          when a.doctype = 'ACM' then apcm.bpcode
          when a.doctype = 'AP' then ap.bpcode
          when a.doctype = 'AR' then ar.bpcode
          when a.doctype = 'CM' then cm.bpcode
          when a.doctype = 'CS' then ar.bpcode
          when a.doctype = 'GI' then gi.bpcode
          when a.doctype = 'GR' then gr.bpcode
          when a.doctype = 'GT' then st.bpcode
          when a.doctype = 'PDN' then pd.bpcode
          when a.doctype = 'PY' then pay.bpcode
          when a.doctype = 'RC' then col.bpcode
          else ''
      end payee2


	FROM je a
		LEFT OUTER JOIN branches b on b.BRANCHCODE = a.branch
		LEFT OUTER JOIN companies c on c.COMPANYCODE = a.company
    LEFT OUTER JOIN jedoctypes d on d.CODE = pi_doctype AND pi_doctype<>''
    LEFT OUTER JOIN jebooks e on e.CODE = pi_book AND pi_book<>''
    LEFT OUTER JOIN chartofaccounts f on f.FORMATCODE = pi_glacctno AND pi_glacctno<>''
    LEFT OUTER JOIN arinvoices ar ON ar.company = a.company AND ar.branch = a.branch AND ar.docno = a.docno AND ar.docdate BETWEEN pi_date1 AND pi_date2
    LEFT OUTER JOIN arcreditmemos cm ON cm.company = a.company AND cm.branch = a.branch AND cm.docno = a.docno AND cm.docdate BETWEEN pi_date1 AND pi_date2
    LEFT OUTER JOIN apinvoices ap ON ap.company = a.company AND ap.branch = a.branch AND ap.docno = a.docno AND ap.docdate BETWEEN pi_date1 AND pi_date2
    LEFT OUTER JOIN apcreditmemos apcm ON apcm.company = a.company AND apcm.branch = a.branch AND apcm.docno = a.docno AND apcm.docdate BETWEEN pi_date1 AND pi_date2
    LEFT OUTER JOIN purchasedeliveries pd ON pd.company = a.company AND pd.branch = a.branch AND pd.docno = a.docno AND pd.docdate BETWEEN pi_date1 AND pi_date2
    LEFT OUTER JOIN collections col ON col.company = a.company AND col.branchcode = a.branch AND col.docno = a.docno AND col.valuedate BETWEEN pi_date1 AND pi_date2
    LEFT OUTER JOIN payments pay ON pay.company = a.company AND pay.branchcode = a.branch AND pay.docno = a.docno AND pay.valuedate BETWEEN pi_date1 AND pi_date2
    LEFT OUTER JOIN goodsreceipts gr ON gr.company = a.company AND gr.branch = a.branch AND gr.docno = a.docno AND gr.docdate BETWEEN pi_date1 AND pi_date2
    LEFT OUTER JOIN goodsissues gi ON gi.company = a.company AND gi.branch = a.branch AND gi.docno = a.docno AND gi.docdate BETWEEN pi_date1 AND pi_date2
    LEFT OUTER JOIN stocktransfers st ON st.company = a.company AND st.branch = a.branch AND st.docno = a.docno AND st.docdate BETWEEN pi_date1 AND pi_date2
;
END $$

DELIMITER ;