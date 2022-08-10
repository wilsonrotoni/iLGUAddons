DELIMITER $$

DROP PROCEDURE IF EXISTS `supplier_ledger` $$
CREATE PROCEDURE `supplier_ledger`(IN pi_company VARCHAR(30),
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
    `BPREFNO` varchar(30) NULL default '',
    `DEBIT` NUMERIC(18,6) NOT NULL default '0',
    `CREDIT` NUMERIC(18,6) NOT NULL default '0',
    `OBJECTCODE` varchar(30) NULL default ''
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  INSERT
    INTO trans_opening ()

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.BPREFNO AS REFNO, 0 AS DEBIT,  A.TOTALAMOUNT AS CREDIT,A.OBJECTCODE
      from APINVOICES A
      WHERE A.COMPANY=pi_company
          AND A.BRANCH=pi_branch
          and A.DOCSTATUS NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.BPREFNO AS REFNO, A.TOTALAMOUNT + A.BASEAMOUNT AS DEBIT,0 AS CREDIT, A.OBJECTCODE
      from APCREDITMEMOS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCH=pi_branch
          and A.DOCSTATUS NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.REFERENCE1 AS REFNO, SUM(IF(B.DEBIT>0,B.GROSSAMOUNT,0)) AS DEBIT, SUM(IF(B.CREDIT>0,B.GROSSAMOUNT,0)) AS CREDIT, A.OBJECTCODE
       from JOURNALVOUCHERS A, JOURNALVOUCHERITEMS B
       WHERE B.COMPANY=A.COMPANY
           AND B.BRANCH=A.BRANCH
           AND B.DOCID=A.DOCID
           AND A.COMPANY=pi_company
           AND A.BRANCH=pi_branch
           AND B.ITEMTYPE='S'
           and B.ITEMNO = pi_bpcode
           and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1
       GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.REFNO AS REFNO, SUM(CASE WHEN B.AMOUNT>0 THEN B.AMOUNT ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS CREDIT, A.OBJECTCODE
      from PAYMENTS A, PAYMENTINVOICES B
      WHERE B.COMPANY=A.COMPANY
          AND B.BRANCH=A.BRANCHCODE
          AND B.DOCNO=A.DOCNO
          AND B.REFBRANCH=pi_branch
          AND A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND B.REFTYPE NOT IN ('DEPOSIT')
          AND A.CLEARED IN (1,-1)
          and A.DOCSTAT NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN' AS DOCSTATUS, A.REFNO AS REFNO, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE B.AMOUNT  END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN ABS(B.AMOUNT) ELSE 0 END) AS CREDIT, A.OBJECTCODE
      from PAYMENTS A, PAYMENTINVOICES B
      WHERE B.COMPANY=A.COMPANY
          AND B.BRANCH=A.BRANCHCODE
          AND B.DOCNO=A.DOCNO
          AND B.REFBRANCH=pi_branch
          AND A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND B.REFTYPE NOT IN ('DEPOSIT')
          AND A.CLEARED IN (-1)
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') < pi_date1
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.REFNO AS REFNO, A.PAIDAMOUNT AS DEBIT, 0 AS CREDIT, A.OBJECTCODE
      from PAYMENTS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND A.COLLFOR = 'DP'
          AND A.CLEARED IN (1,-1)
          and A.DOCSTAT NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN' AS DOCSTATUS, A.REFNO AS REFNO, 0 AS DEBIT, A.PAIDAMOUNT AS CREDIT, A.OBJECTCODE
      from PAYMENTS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND A.COLLFOR = 'DP'
          AND A.CLEARED IN (-1)
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') < pi_date1
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, B.REFTYPE AS DOCSTATUS, A.REFNO AS REFNO, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN ABS(B.AMOUNT) ELSE 0 END) AS CREDIT, A.OBJECTCODE
      from COLLECTIONS A, COLLECTIONSINVOICES B
      WHERE B.COMPANY=A.COMPANY
          AND B.BRANCH=A.BRANCHCODE
          AND B.DOCNO=A.DOCNO
          AND A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND B.REFTYPE NOT IN ('DEPOSIT')
          AND A.CLEARED IN (1,-99)
          and A.DOCSTAT NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE


        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, 'CN' AS DOCSTATUS, A.REFNO AS REFNO, SUM(CASE WHEN B.AMOUNT>0 THEN B.AMOUNT ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS CREDIT, A.OBJECTCODE
      from COLLECTIONS A, COLLECTIONSINVOICES B
      WHERE B.COMPANY=A.COMPANY
          AND B.BRANCH=A.BRANCHCODE
          AND B.DOCNO=A.DOCNO
          AND A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND B.REFTYPE NOT IN ('DEPOSIT')
          AND A.CLEARED IN (-99)
          AND A.DOCSTAT='CN'
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, 'BC' AS DOCSTATUS, A.REFNO AS REFNO, SUM(CASE WHEN B.AMOUNT>0 THEN B.AMOUNT ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS CREDIT, A.OBJECTCODE
      from COLLECTIONS A, COLLECTIONSINVOICES B
      WHERE B.COMPANY=A.COMPANY
          AND B.BRANCH=A.BRANCHCODE
          AND B.DOCNO=A.DOCNO
          AND A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND B.REFTYPE NOT IN ('DEPOSIT')
          AND A.CLEARED IN (-99)
          AND A.DOCSTAT='BC'
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.REFNO AS REFNO, 0 AS DEBIT, A.PAIDAMOUNT AS CREDIT, A.OBJECTCODE
      from COLLECTIONS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND A.COLLFOR = 'DP'
          AND A.CLEARED IN (1,-99)
          and A.DOCSTAT NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') < pi_date1
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN' AS DOCSTATUS, A.REFNO AS REFNO, A.PAIDAMOUNT AS DEBIT, 0 AS CREDIT, A.OBJECTCODE
      from COLLECTIONS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND A.COLLFOR = 'DP'
          AND A.CLEARED IN (-99)
          AND A.DOCSTAT='CN'
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') < pi_date1
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'BC' AS DOCSTATUS, A.REFNO AS REFNO, A.PAIDAMOUNT AS DEBIT, 0 AS CREDIT, A.OBJECTCODE
      from COLLECTIONS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND A.COLLFOR = 'DP'
          AND A.CLEARED IN (-99)
          AND A.DOCSTAT='BC'
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') < pi_date1;





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
    `BPREFNO` varchar(30) NULL default '',
    `DEBIT` NUMERIC(18,6) NOT NULL default '0',
    `CREDIT` NUMERIC(18,6) NOT NULL default '0',
    `OBJECTCODE` varchar(30) NULL default '',
    `REMARKS` varchar(1000) NULL default '',
    `bpcode` varchar(30) NULL default ''
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  INSERT
    INTO trans ()

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.BPREFNO AS REFNO, 0 AS DEBIT,  A.TOTALAMOUNT AS CREDIT,A.OBJECTCODE, A.REMARKS, pi_bpcode
      from APINVOICES A
      WHERE A.COMPANY=pi_company
          AND A.BRANCH=pi_branch
          and A.DOCSTATUS NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.BPREFNO AS REFNO, A.TOTALAMOUNT + A.BASEAMOUNT AS DEBIT,0 AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from APCREDITMEMOS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCH=pi_branch
          and A.DOCSTATUS NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.REFERENCE1 AS REFNO, SUM(IF(B.DEBIT>0,B.GROSSAMOUNT,0)) AS DEBIT, SUM(IF(B.CREDIT>0,B.GROSSAMOUNT,0)) AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
       from JOURNALVOUCHERS A, JOURNALVOUCHERITEMS B
       WHERE B.COMPANY=A.COMPANY
           AND B.BRANCH=A.BRANCH
           AND B.DOCID=A.DOCID
           AND A.COMPANY=pi_company
           AND A.BRANCH=pi_branch
           AND B.ITEMTYPE='S'
           and B.ITEMNO = pi_bpcode
           and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2
       GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.REFNO AS REFNO, SUM(CASE WHEN B.AMOUNT>0 THEN B.AMOUNT ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from PAYMENTS A, PAYMENTINVOICES B
      WHERE B.COMPANY=A.COMPANY
          AND B.BRANCH=A.BRANCHCODE
          AND B.DOCNO=A.DOCNO
          AND B.REFBRANCH=pi_branch
          AND A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND B.REFTYPE NOT IN ('DEPOSIT')
          AND A.CLEARED IN (1,-1)
          and A.DOCSTAT NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN' AS DOCSTATUS, A.REFNO AS REFNO, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE B.AMOUNT  END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN ABS(B.AMOUNT) ELSE 0 END) AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from PAYMENTS A, PAYMENTINVOICES B
      WHERE B.COMPANY=A.COMPANY
          AND B.BRANCH=A.BRANCHCODE
          AND B.DOCNO=A.DOCNO
          AND B.REFBRANCH=pi_branch
          AND A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND B.REFTYPE NOT IN ('DEPOSIT')
          AND A.CLEARED IN (-1)
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') between pi_date1 and pi_date2
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.REFNO AS REFNO, A.PAIDAMOUNT AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from PAYMENTS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND A.COLLFOR = 'DP'
          AND A.CLEARED IN (1,-1)
          and A.DOCSTAT NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN' AS DOCSTATUS, A.REFNO AS REFNO, 0 AS DEBIT, A.PAIDAMOUNT AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from PAYMENTS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND A.COLLFOR = 'DP'
          AND A.CLEARED IN (-1)
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') between pi_date1 and pi_date2
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, B.REFTYPE AS DOCSTATUS, A.REFNO AS REFNO, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN ABS(B.AMOUNT) ELSE 0 END) AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from COLLECTIONS A, COLLECTIONSINVOICES B
      WHERE B.COMPANY=A.COMPANY
          AND B.BRANCH=A.BRANCHCODE
          AND B.DOCNO=A.DOCNO
          AND A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND B.REFTYPE NOT IN ('DEPOSIT')
          AND A.CLEARED IN (1,-99)
          and A.DOCSTAT NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE


        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, 'CN' AS DOCSTATUS, A.REFNO AS REFNO, SUM(CASE WHEN B.AMOUNT>0 THEN B.AMOUNT ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from COLLECTIONS A, COLLECTIONSINVOICES B
      WHERE B.COMPANY=A.COMPANY
          AND B.BRANCH=A.BRANCHCODE
          AND B.DOCNO=A.DOCNO
          AND A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND B.REFTYPE NOT IN ('DEPOSIT')
          AND A.CLEARED IN (-99)
          AND A.DOCSTAT='CN'
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, 'BC' AS DOCSTATUS, A.REFNO AS REFNO, SUM(CASE WHEN B.AMOUNT>0 THEN B.AMOUNT ELSE 0 END) AS DEBIT, SUM(CASE WHEN B.AMOUNT>0 THEN 0 ELSE ABS(B.AMOUNT) END) AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from COLLECTIONS A, COLLECTIONSINVOICES B
      WHERE B.COMPANY=A.COMPANY
          AND B.BRANCH=A.BRANCHCODE
          AND B.DOCNO=A.DOCNO
          AND A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND B.REFTYPE NOT IN ('DEPOSIT')
          AND A.CLEARED IN (-99)
          AND A.DOCSTAT='BC'
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.DOCDATE, A.DOCNO, '' AS DOCSTATUS, A.REFNO AS REFNO, 0 AS DEBIT, A.PAIDAMOUNT AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from COLLECTIONS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND A.COLLFOR = 'DP'
          AND A.CLEARED IN (1,-99)
          and A.DOCSTAT NOT IN ('D')
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.DOCDATE, '%Y-%m-%d') between pi_date1 and pi_date2
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'CN' AS DOCSTATUS, A.REFNO AS REFNO, A.PAIDAMOUNT AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from COLLECTIONS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND A.COLLFOR = 'DP'
          AND A.CLEARED IN (-99)
          AND A.DOCSTAT='CN'
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') between pi_date1 and pi_date2
      GROUP BY A.POSTDATE, A.DOCDATE, A.DOCNO, A.OBJECTCODE

        union all

select A.POSTDATE, A.CANCELLEDDATE, A.DOCNO, 'BC' AS DOCSTATUS, A.REFNO AS REFNO, A.PAIDAMOUNT AS DEBIT, 0 AS CREDIT, A.OBJECTCODE, A.REMARKS, pi_bpcode
      from COLLECTIONS A
      WHERE A.COMPANY=pi_company
          AND A.BRANCHCODE=pi_branch
          AND A.DOCTYPE='S'
          AND A.COLLFOR = 'DP'
          AND A.CLEARED IN (-99)
          AND A.DOCSTAT='BC'
          and A.BPCODE = pi_bpcode
          and DATE_FORMAT(A.CANCELLEDDATE, '%Y-%m-%d') between pi_date1 and pi_date2;






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
      upper(ct.suppno) as BPCODE,
      upper(ct.suppname) as BPNAME,
      (select upper(concat(if(ax.BARANGAY is null or ax.BARANGAY = '','' , concat(ax.BARANGAY,', '))
                 , if(ax.STREET is null or ax.STREET = '','' , concat(ax.STREET,', '))
                 , if(ax.ZIP is null or ax.ZIP = '','' , concat(ax.ZIP,', '))
                 , if(ax.CITY is null or ax.CITY = '','' , concat(ax.CITY,', '))
                 , if(ax3.PROVINCENAME is null or ax3.PROVINCENAME = '','' , concat(ax3.PROVINCENAME,', '))
                 , if(ax2.COUNTRYNAME is null or ax2.COUNTRYNAME = '','' , ax2.COUNTRYNAME)))
           from addresses ax
                LEFT OUTER JOIN countries ax2 on ax.country = ax2.country
                LEFT OUTER JOIN provinces ax3 on ax.province = ax3.province
     where ax.refid = a.BPCODE and ax.reftype = 'SUPPLIER' and ax.addresstype = 0 limit 1) as BPADDRESS,
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
      a.OBJECTCODE
from suppliers ct
     LEFT OUTER JOIN companies c ON c.companycode = pi_company
     LEFT OUTER JOIN branches d ON d.BRANCHCODE = pi_branch 
     LEFT OUTER JOIN salespersons sp on ct.SALESPERSON = sp.SALESPERSON
     LEFT OUTER JOIN trans a ON ct.suppno = a.bpcode
     LEFT OUTER JOIN trans_opening_sum b ON ct.suppno = b.bpcode
     LEFT OUTER JOIN trans_sum ax ON ct.suppno = ax.bpcode
where ct.suppno = pi_bpcode
order by POSTDATE, DOCNO;
END $$

DELIMITER ;