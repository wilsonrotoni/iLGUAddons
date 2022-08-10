DELIMITER $$

DROP PROCEDURE IF EXISTS `customer_ledger` $$
CREATE PROCEDURE `customer_ledger`(IN pi_company VARCHAR(30),
                                    IN pi_branch VARCHAR(30),
                                    IN pi_bpcode VARCHAR(30),
                                    IN pi_date1 VARCHAR(30),
                                    IN pi_date2 VARCHAR(30))
BEGIN



DROP TEMPORARY TABLE IF EXISTS `trans_opening`;
CREATE TEMPORARY TABLE  `trans_opening` (
    `POSTDATE` DATE NULL,
    `DOCDATE` DATE NULL,
    `DOCNO` varchar(30) NULL default '',
    `DOCSTATUS` varchar(30) NULL default '',
    `DEBIT` NUMERIC(18,6) NOT NULL default '0',
    `CREDIT` NUMERIC(18,6) NOT NULL default '0',
    `OBJECTCODE` varchar(30) NULL default '',
    `TRXTYPE` varchar(30) NULL default ''
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
    select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.TOTALAMOUNT AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.TRXTYPE
           from ARINVOICES A
                WHERE A.COMPANY=pi_company AND A.BRANCH=pi_branch AND A.TRXTYPE<>'POS' AND A.DOCSTATUS<>'D'
                      and A.BPCODE = pi_bpcode
                      and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.TOTALAMOUNT AS DEBIT, A.TOTALAMOUNT AS CREDIT, A.OBJECTCODE, A.TRXTYPE
           from ARINVOICES A
                WHERE A.COMPANY=pi_company AND A.BRANCH=pi_branch AND A.TRXTYPE='POS' AND A.DOCSTATUS<>'D'
                      and A.BPCODE = pi_bpcode
                      and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.DUEDATE, A.DUEDATE, B.REFNO, '' AS DOCSTATUS, A.AMOUNT AS DEBIT, 0 AS CREDIT, 'PENALTY', ''
               from NOTESRECEIVABLES A, ACCOUNTS B
               WHERE b.company=a.company and b.branch=a.branch and b.acctno=a.acctno and
                     a.rectype IN ('P') AND A.COMPANY=pi_company AND A.BRANCH=pi_branch
                     and B.CUSTNO = pi_bpcode
                     and DATE_FORMAT(A.DUEDATE, '%Y-%m-%d') < pi_date1;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.DOCDATE, A.DOCNO, '', 0, A.TOTALAMOUNT + A.BASEAMOUNT, A.OBJECTCODE, A.TRXTYPE
               from ARCREDITMEMOS A
               WHERE A.COMPANY=pi_company AND A.BRANCH=pi_branch AND A.DOCSTATUS<>'D'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.DOCDATE, A.DOCNO, '', SUM(IF(B.DEBIT>0,B.GROSSAMOUNT,0)), SUM(IF(B.CREDIT>0,B.GROSSAMOUNT,0)), A.OBJECTCODE, ''
               from JOURNALVOUCHERS A, JOURNALVOUCHERITEMS B
               WHERE B.COMPANY=A.COMPANY
                    AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND A.COMPANY=pi_company AND A.BRANCH=pi_branch AND B.ITEMTYPE='C'
                    AND B.REFTYPE<>'OUTDOWNPAYMENT'
                    AND A.DOCSTATUS<>'D'
                    and B.ITEMNO = pi_bpcode
                    and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.DOCDATE, A.DOCNO, '', SUM(CASE WHEN B.AMOUNT>0 THEN B.AMOUNT ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS CREDIT, A.OBJECTCODE, A.TRXTYPE
               from PAYMENTS A, PAYMENTINVOICES B
               WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO
                     AND A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND B.REFTYPE NOT IN ('DEPOSIT')
                     AND A.CLEARED IN (1,-1) AND A.DOCSTAT<>'D'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN', SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN B.AMOUNT ELSE 0 END) AS CREDIT, A.OBJECTCODE, A.TRXTYPE
               from PAYMENTS A, PAYMENTINVOICES B
               WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO
                     AND A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND B.REFTYPE NOT IN ('DEPOSIT')
                     AND A.CLEARED IN (-1) AND A.DOCSTAT<>'D'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') < pi_date1
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.VALUEDATE, A.DOCNO, '', SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE (ABS(B.AMOUNT) + B.REBATE) END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN (B.AMOUNT + B.REBATE) ELSE 0 END) AS CREDIT, A.OBJECTCODE, A.TRXTYPE
               from COLLECTIONS A, COLLECTIONSINVOICES B
               WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO
                     AND A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND B.REFTYPE NOT IN ('DEPOSIT')
                     AND A.CLEARED IN (1,-99) AND A.DOCSTAT<>'D' AND A.VALUEDATE<>'0000-00-00'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.VALUEDATE, '%Y-%m-%d') < pi_date1
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN', SUM(CASE WHEN B.AMOUNT>0 THEN (B.AMOUNT + B.REBATE) ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE (ABS(B.AMOUNT) + B.REBATE) END) AS CREDIT, A.OBJECTCODE, A.TRXTYPE
               from COLLECTIONS A, COLLECTIONSINVOICES B
               WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO
                     AND A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND B.REFTYPE NOT IN ('DEPOSIT')
                     AND A.CLEARED IN (-99)
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') < pi_date1
                     AND A.PDC=0 AND A.DOCSTAT='CN'
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'BC', SUM(CASE WHEN B.AMOUNT>0 THEN (B.AMOUNT + B.REBATE) ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE (ABS(B.AMOUNT) + B.REBATE) END) AS CREDIT, A.OBJECTCODE, A.TRXTYPE
               from COLLECTIONS A, COLLECTIONSINVOICES B
               WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO
                     AND A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND B.REFTYPE NOT IN ('DEPOSIT')
                     AND A.CLEARED IN (-99)
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') < pi_date1
                     AND A.DOCSTAT='BC'
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.VALUEDATE, A.DOCNO, '', 0 AS DEBIT, SUM(A.PAIDAMOUNT) AS CREDIT, A.OBJECTCODE, A.TRXTYPE
               from COLLECTIONS A
               WHERE A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND A.COLLFOR='DP'
                     AND A.CLEARED IN (1,-99) AND A.DOCSTAT<>'D' AND A.VALUEDATE<>'0000-00-00'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.VALUEDATE, '%Y-%m-%d') < pi_date1
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN', SUM(A.PAIDAMOUNT) AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.TRXTYPE
               from COLLECTIONS A
               WHERE A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND A.COLLFOR='DP'
                     AND A.CLEARED IN (-99)
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') < pi_date1
                     AND A.PDC=0 AND A.DOCSTAT='CN'
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'BC', SUM(A.PAIDAMOUNT) AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.TRXTYPE
               from COLLECTIONS A
               WHERE A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND A.COLLFOR='DP'
                     AND A.CLEARED IN (-99)
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') < pi_date1
                     AND A.DOCSTAT='BC'
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.DOCDATE, A.DOCNO, '', SUM(A.PAIDAMOUNT) AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.TRXTYPE
               from PAYMENTS A
               WHERE A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND A.COLLFOR='DP'
                     AND A.CLEARED IN (1,-99) AND A.DOCSTAT<>'D'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans_opening (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, '', 0 AS DEBIT, SUM(A.PAIDAMOUNT) AS CREDIT, A.OBJECTCODE, A.TRXTYPE
               from PAYMENTS A
               WHERE A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND A.COLLFOR='DP'
                     AND A.CLEARED IN (-99) AND A.DOCSTAT='CN'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') < pi_date1
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;

DROP TEMPORARY TABLE IF EXISTS `trans_opening_sum`;
CREATE TEMPORARY TABLE  `trans_opening_sum` (
    `bpcode` varchar(30) NULL default '',
    `total` NUMERIC(18,6) NOT NULL default '0'
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO trans_opening_sum (bpcode,total)
    select pi_bpcode, if(sum(x.debit-x.credit)is null,0,sum(x.debit-x.credit)) from trans_opening x;


DROP TEMPORARY TABLE IF EXISTS `trans`;
CREATE TEMPORARY TABLE  `trans` (
    `POSTDATE` DATE NULL,
    `DOCDATE` DATE NULL,
    `DOCNO` varchar(30) NULL default '',
    `DOCSTATUS` varchar(30) NULL default '',
    `DEBIT` NUMERIC(18,6) NOT NULL default '0',
    `CREDIT` NUMERIC(18,6) NOT NULL default '0',
    `OBJECTCODE` varchar(30) NULL default '',
    `TRXTYPE` varchar(30) NULL default '',
    `bpcode` varchar(30) NULL default '',
    so_no varchar(5000) default '', dr_no varchar(5000) default ''
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode, so_no, dr_no)
    select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.TOTALAMOUNT AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode,
           a.bprefno AS 'so_no', '' AS 'dr_no'
           from ARINVOICES A
                WHERE A.COMPANY=pi_company AND A.BRANCH=pi_branch AND A.TRXTYPE<>'POS' AND A.DOCSTATUS<>'D'
                      and A.BPCODE = pi_bpcode
                      and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode, so_no, dr_no)
		select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.TOTALAMOUNT AS DEBIT, A.TOTALAMOUNT AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode,
           a.bprefno AS 'so_no', '' AS 'dr_no'
           from ARINVOICES A
                WHERE A.COMPANY=pi_company AND A.BRANCH=pi_branch AND A.TRXTYPE='POS' AND A.DOCSTATUS<>'D'
                      and A.BPCODE = pi_bpcode
                      and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode)
		select A.DUEDATE, A.DUEDATE, B.REFNO, '' AS DOCSTATUS, A.AMOUNT AS DEBIT, 0 AS CREDIT, 'PENALTY', '',
           pi_bpcode
               from NOTESRECEIVABLES A, ACCOUNTS B
               WHERE b.company=a.company and b.branch=a.branch and b.acctno=a.acctno and
                     a.rectype IN ('P') AND A.COMPANY=pi_company AND A.BRANCH=pi_branch
                     and B.CUSTNO = pi_bpcode
                     and DATE_FORMAT(A.DUEDATE, '%Y-%m-%d') between pi_date1 and pi_date2;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode, so_no)
		select A.POSTDATE, A.DOCDATE, A.DOCNO, '', 0, A.TOTALAMOUNT + A.BASEAMOUNT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode,
           a.bprefno AS 'so_no'
               from ARCREDITMEMOS A
               WHERE A.COMPANY=pi_company AND A.BRANCH=pi_branch AND A.DOCSTATUS<>'D'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode)
		select A.POSTDATE, A.DOCDATE, A.DOCNO, '', SUM(IF(B.DEBIT>0,B.GROSSAMOUNT,0)), SUM(IF(B.CREDIT>0,B.GROSSAMOUNT,0)), A.OBJECTCODE, '',
           pi_bpcode
               from JOURNALVOUCHERS A, JOURNALVOUCHERITEMS B
               WHERE B.COMPANY=A.COMPANY
                    AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND A.COMPANY=pi_company AND A.BRANCH=pi_branch AND B.ITEMTYPE='C'
                    AND B.REFTYPE<>'OUTDOWNPAYMENT'
                    AND A.DOCSTATUS<>'D'
                    and B.ITEMNO = pi_bpcode
                    and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode)
		select A.POSTDATE, A.DOCDATE, A.DOCNO, '', SUM(CASE WHEN B.AMOUNT>0 THEN B.AMOUNT ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode
               from PAYMENTS A, PAYMENTINVOICES B
               WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO
                     AND A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND B.REFTYPE NOT IN ('DEPOSIT')
                     AND B.ISADVANCES=0 AND A.CLEARED IN (1,-1) AND A.DOCSTAT<>'D'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN', SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN B.AMOUNT ELSE 0 END) AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode
               from PAYMENTS A, PAYMENTINVOICES B
               WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO
                     AND A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND B.REFTYPE NOT IN ('DEPOSIT')
                     AND A.CLEARED IN (-1) AND A.DOCSTAT<>'D'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') between pi_date1 and pi_date2
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode, so_no, dr_no)
		select A.POSTDATE, A.VALUEDATE, A.DOCNO, '', SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE (ABS(B.AMOUNT) + B.REBATE) END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN (B.AMOUNT + B.REBATE) ELSE 0 END) AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode, GROUP_CONCAT(ari.bprefno), '' AS 'dr_no'
               from COLLECTIONS A, COLLECTIONSINVOICES B
               LEFT JOIN arinvoices AS ari ON ari.company = b.company AND ari.branch = b.branch AND ari.docno = b.refno
               WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO
                     AND A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND B.REFTYPE NOT IN ('DEPOSIT')
                     AND A.CLEARED IN (1,-99) AND A.DOCSTAT<>'D'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.VALUEDATE, '%Y-%m-%d') between pi_date1 and pi_date2
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode, so_no, dr_no)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN', SUM(CASE WHEN B.AMOUNT>0 THEN (B.AMOUNT + B.REBATE) ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE (ABS(B.AMOUNT) + B.REBATE) END) AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode, GROUP_CONCAT(ari.bprefno), '' AS 'dr_no'
               from COLLECTIONS A, COLLECTIONSINVOICES B
               LEFT JOIN arinvoices AS ari ON ari.company = b.company AND ari.branch = b.branch AND ari.docno = b.refno
               WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO
                     AND A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND B.REFTYPE NOT IN ('DEPOSIT')
                     AND A.CLEARED IN (-99)
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') between pi_date1 and pi_date2
                     AND A.PDC=0 AND A.DOCSTAT='CN'
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode, so_no, dr_no)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'BC', SUM(CASE WHEN B.AMOUNT>0 THEN (B.AMOUNT + B.REBATE) ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE (ABS(B.AMOUNT) + B.REBATE) END) AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode, GROUP_CONCAT(ari.bprefno), '' AS 'dr_no'
               from COLLECTIONS A, COLLECTIONSINVOICES B
               LEFT JOIN arinvoices AS ari ON ari.company = b.company AND ari.branch = b.branch AND ari.docno = b.refno
               WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO
                     AND A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND B.REFTYPE NOT IN ('DEPOSIT')
                     AND A.CLEARED IN (-99)
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') between pi_date1 and pi_date2
                     AND A.DOCSTAT='BC'
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode, so_no, dr_no)
		select A.POSTDATE, A.VALUEDATE, A.DOCNO, '', 0 AS DEBIT, SUM(A.PAIDAMOUNT) AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode, '', '' AS 'dr_no'
               from COLLECTIONS A
               WHERE A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND A.COLLFOR='DP'
                     AND A.CLEARED IN (1,-99) AND A.DOCSTAT<>'D'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.VALUEDATE, '%Y-%m-%d') between pi_date1 and pi_date2
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode, so_no, dr_no)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN', SUM(A.PAIDAMOUNT) AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode, '', '' AS 'dr_no'
               from COLLECTIONS A
               WHERE A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND A.COLLFOR='DP'
                     AND A.CLEARED IN (-99)
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') between pi_date1 and pi_date2
                     AND A.PDC=0 AND A.DOCSTAT='CN'
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode, so_no, dr_no)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'BC', SUM(A.PAIDAMOUNT) AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode, '', '' AS 'dr_no'
               from COLLECTIONS A
               WHERE A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND A.COLLFOR='DP'
                     AND A.CLEARED IN (-99)
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') between pi_date1 and pi_date2
                     AND A.DOCSTAT='BC'
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode)
		select A.POSTDATE, A.DOCDATE, A.DOCNO, '', SUM(A.PAIDAMOUNT) AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode
               from PAYMENTS A
               WHERE A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND A.COLLFOR='DP'
                     AND A.CLEARED IN (1,-99) AND A.DOCSTAT<>'D'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;
  INSERT
    INTO trans (POSTDATE,DOCDATE,DOCNO,DOCSTATUS,DEBIT,CREDIT,OBJECTCODE,TRXTYPE,bpcode)
		select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, '', 0 AS DEBIT, SUM(A.PAIDAMOUNT) AS CREDIT, A.OBJECTCODE, A.TRXTYPE,
           pi_bpcode
               from PAYMENTS A
               WHERE A.COMPANY=pi_company AND A.BRANCHCODE=pi_branch AND A.DOCTYPE='C' AND A.COLLFOR='DP'
                     AND A.CLEARED IN (-99) AND A.DOCSTAT='CN'
                     and A.BPCODE = pi_bpcode
                     and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') between pi_date1 and pi_date2
               GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE;


DROP TEMPORARY TABLE IF EXISTS `trans_sum`;
CREATE TEMPORARY TABLE  `trans_sum` (
    `bpcode` varchar(30) NULL default '',
    `totaldebit` NUMERIC(18,6) NOT NULL default '0',
    `totalcredit` NUMERIC(18,6) NOT NULL default '0',
    `total` NUMERIC(18,6) NOT NULL default '0'
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO trans_sum (bpcode,totaldebit,totalcredit,total)
    select pi_bpcode, sum(x.debit), sum(x.credit), if(sum(x.debit-x.credit)is null,0,sum(x.debit-x.credit))
           from trans x
    group by pi_bpcode;
select
      upper(c.companyname) as COMPANY,
      upper(concat(d.BRANCHCODE, ' - ',d.BRANCHNAME)) as BRANCH,
      upper(ct.custno) as BPCODE,
      upper(ct.custname) as BPNAME,
      (select upper(concat(if(ax.BARANGAY is null or ax.BARANGAY = '','' , concat(ax.BARANGAY,', '))
                 , if(ax.STREET is null or ax.STREET = '','' , concat(ax.STREET,', '))
                 , if(ax.ZIP is null or ax.ZIP = '','' , concat(ax.ZIP,', '))
                 , if(ax.CITY is null or ax.CITY = '','' , concat(ax.CITY,', '))
                 , if(ax3.PROVINCENAME is null or ax3.PROVINCENAME = '','' , concat(ax3.PROVINCENAME,', '))
                 , if(ax2.COUNTRYNAME is null or ax2.COUNTRYNAME = '','' , ax2.COUNTRYNAME)))
           from addresses ax
                LEFT OUTER JOIN countries ax2 on ax.country = ax2.country
                LEFT OUTER JOIN provinces ax3 on ax.province = ax3.province
     where ax.refid = a.BPCODE and ax.reftype = 'CUSTOMER' and ax.addresstype = 0 limit 1) as BPADDRESS,
      upper(sp.SALESPERSONNAME) as SALESPERSON,
      ct.CREDITLIMIT as CREDITLIMIT,
      date(pi_date1) as date1,
      date(pi_date2) as date2,
      a.POSTDATE,
      a.DOCDATE,
      a.DOCNO,
      a.DOCSTATUS,
      b.total as OB,
      if(a.DEBIT is null,0,a.DEBIT) as DEBIT,
      if(a.CREDIT is null,0,a.CREDIT) as CREDIT,
      if(ax.totaldebit is null,0,ax.totaldebit) as totaldebit,
      if(ax.totalcredit is null,0,ax.totalcredit*-1) as totalcredit,
      b.total + if(ax.total is null,0,ax.total) as total,
      a.OBJECTCODE,
      a.TRXTYPE,
      a.so_no AS 'so_no', a.dr_no AS 'dr_no'
from customers ct
     LEFT OUTER JOIN companies c ON c.companycode = pi_company
     LEFT OUTER JOIN branches d ON d.BRANCHCODE = pi_branch
     LEFT OUTER JOIN salespersons sp on ct.SALESPERSON = sp.SALESPERSON
     LEFT OUTER JOIN trans a ON ct.custno = a.bpcode
     LEFT OUTER JOIN trans_opening_sum b ON ct.custno = b.bpcode
     LEFT OUTER JOIN trans_sum ax ON ct.custno = ax.bpcode
WHERE ct.custno = pi_bpcode
	order by DOCDATE, POSTDATE, DOCNO;
END $$

DELIMITER ;