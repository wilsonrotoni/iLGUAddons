DELIMITER $$

DROP PROCEDURE IF EXISTS `soa` $$
CREATE PROCEDURE `soa`(IN pi_company VARCHAR(30),
                       IN pi_branch VARCHAR(30),
                       IN bpcode VARCHAR(10),
                       IN pi_date1 VARCHAR(10),
                       IN pi_date2 VARCHAR(10))
BEGIN
  DECLARE v_advms DATE;
  DECLARE v_advme DATE;
  DECLARE v_30ms DATE;
  DECLARE v_60ms DATE;
  DECLARE v_90ms DATE;
  DECLARE v_30me DATE;
  DECLARE v_60me DATE;
  DECLARE v_90me DATE;
  DECLARE v_MONTH_END INT;

  SET v_30ms = date(concat(substring(pi_date2,1,7),'-01'));
  SET v_advms = v_30ms + interval 1 month;
  SET v_60ms = v_30ms - interval 1 month;
  SET v_90ms = v_30ms - interval 2 month;
  SET v_30me = date(pi_date2);
  SET v_advme = last_day(v_30me + interval 1 month);
  SET v_60me = last_day(v_30me - interval 1 month);
  SET v_90me = last_day(v_30me - interval 2 month);
  SET v_MONTH_END = concat('-',substring(v_60me,9,2)+1);

DROP TEMPORARY TABLE IF EXISTS `pdcpayments`;
CREATE TEMPORARY TABLE  `pdcpayments` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `ACCTNO` varchar(30) NULL default '',
    `PDCAMOUNT` NUMERIC(18,6) NULL default '0',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`ACCTNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO pdcpayments (COMPANY,BRANCH,ACCTNO,PDCAMOUNT)
      SELECT a.company,a.branch,a.refno, sum(a.rebate + a.amount) as pdcamount
        from collectionsinvoices a, collectionscheques b, collections c
        where b.company = a.company and b.branch = a.branch
        and b.docno = a.docno
        and b.company = c.company
        and b.branch = c.branchcode
        and b.docno = c.docno
        and c.pdc=1
        and a.company = pi_company and a.branch = pi_branch
        and b.checkdate >= pi_date2
        and (bpcode='' or (bpcode<>'' AND c.CUSTNO = bpcode))
        group by a.refno;

DROP TEMPORARY TABLE IF EXISTS `balances`;
CREATE TEMPORARY TABLE  `balances` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `BALANCE` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO balances (COMPANY,BRANCH,DOCNO,BALANCE,DOCDATE)
        SELECT a.company,a.branch,a.docno, sum(b.amount - b.penaltypaid) as BALANCE, c.DOCDATE
        from arinvoices a, collectionsinvoices b, collections c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype='' and a.docdate <= pi_date2
        and (c.docdate <= pi_date2)
        and (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
        group by a.DOCNO;
  INSERT
    INTO balances (COMPANY,BRANCH,DOCNO,BALANCE,DOCDATE)
        SELECT a.company,a.branch,d.docno, sum(b.amount - b.penaltypaid) as BALANCE, c.DOCDATE
        from journalvouchers a, journalvoucheritems d, collectionsinvoices b, collections c
        where d.company = a.company
        and d.branch = a.branch
        and d.docid = a.docid
        and b.company = a.company
        and b.branch = a.branch
        and b.refno = d.docno
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and d.company = pi_company
        and d.branch = pi_branch
        and d.itemtype = 'C'
        AND d.reftype = ''
        AND d.accttype='' and a.docdate <= pi_date2
        and (c.docdate <= pi_date2)
        and (bpcode='' or (bpcode<>'' AND d.ITEMNO = bpcode))
        group by d.DOCNO;
  INSERT
    INTO balances (COMPANY,BRANCH,DOCNO,BALANCE,DOCDATE)
        SELECT a.company,a.branch,a.docno, sum(b.amount - b.penaltypaid) as BALANCE, c.DOCDATE
        from arcreditmemos a, collectionsinvoices b, collections c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype='' and a.docdate <= pi_date2
        and (c.docdate <= pi_date2)
        and (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
        group by a.DOCNO;

DROP TEMPORARY TABLE IF EXISTS `journalpayments`;
CREATE TEMPORARY TABLE  `journalpayments` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `BALANCE` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO journalpayments (COMPANY,BRANCH,DOCNO,BALANCE,DOCDATE)
        SELECT a.company,a.branch,a.docno, sum(b.credit - b.debit) as BALANCE, c.DOCDATE
        from arinvoices a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype='' and a.docdate <= pi_date2
        and c.docdate <= pi_date2
        and (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
        group by a.DOCNO;
  INSERT
    INTO journalpayments (COMPANY,BRANCH,DOCNO,BALANCE,DOCDATE)
        SELECT a.company,a.branch,d.docno, sum(b.credit - b.debit) as BALANCE, c.DOCDATE
        from journalvouchers a, journalvoucheritems d, journalvoucheritems b, journalvouchers c
        where d.company = a.company
        and d.branch = a.branch
        and d.docid = a.docid
        and b.company = a.company
        and b.branch = a.branch
        and b.refno = d.docno
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and d.company = pi_company
        and d.branch = pi_branch
        and d.itemtype = 'C'
        AND d.reftype = ''
        AND d.accttype='' and a.docdate <= pi_date2
        and c.docdate <= pi_date2
        and (bpcode='' or (bpcode<>'' AND d.ITEMNO = bpcode))
        group by d.DOCNO;
  INSERT
    INTO journalpayments (COMPANY,BRANCH,DOCNO,BALANCE,DOCDATE)
        SELECT a.company,a.branch,a.docno, sum(b.debit - b.credit) as BALANCE, c.DOCDATE
        from arcreditmemos a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype='' and a.docdate <= pi_date2
        and c.docdate <= pi_date2
        and (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
        group by a.DOCNO;

DROP TEMPORARY TABLE IF EXISTS `sale_return`;
CREATE TEMPORARY TABLE  `sale_return` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `BALANCE` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO sale_return (COMPANY,BRANCH,DOCNO,BALANCE,DOCDATE)
       SELECT a.company,a.branch,a.docno, sum(b.BASEAMOUNT), b.DOCDATE
        from arinvoices a, arcreditmemos b
        where b.company = a.company
        and b.branch = a.branch
        and (b.BASEDOCNO = a.docno)
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype='' and a.docdate <= pi_date2
        and b.docdate <= pi_date2
        and (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
        group by a.DOCNO;

DROP TEMPORARY TABLE IF EXISTS `adv`;
CREATE TEMPORARY TABLE  `adv` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `advancepayment` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO adv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.docno, 0 - sum(b.amount - b.penaltypaid) as advancepayment, c.DOCDATE
        from arinvoices a, collectionsinvoices b, collections c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and c.valuedate > pi_date2
        and (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
        group by a.DOCNO;
  INSERT
    INTO adv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,d.docno, 0 - sum(b.amount - b.penaltypaid) as advancepayment, c.DOCDATE
        from journalvouchers a, journalvoucheritems d, collectionsinvoices b, collections c
        where d.company = a.company
        and d.branch = a.branch
        and d.docid = a.docid
        and b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and d.company = pi_company
        and d.branch = pi_branch
        and d.itemtype = 'C'
        AND d.reftype = ''
        AND d.accttype=''
        and c.valuedate > pi_date2
        and (bpcode='' or (bpcode<>'' AND d.ITEMNO = bpcode))
        group by d.DOCNO;
  INSERT
    INTO adv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.docno, 0 - sum(b.amount - b.penaltypaid) as advancepayment, c.DOCDATE
        from arcreditmemos a, collectionsinvoices b, collections c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and c.valuedate > pi_date2
        and (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
        group by a.DOCNO;

DROP TEMPORARY TABLE IF EXISTS `advjv`;
CREATE TEMPORARY TABLE  `advjv` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `advancepayment` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.docno, 0 - sum(b.credit - b.debit) as advancepayment, c.DOCDATE
        from arinvoices a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and c.docdate > pi_date2
        and (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
        group by a.DOCNO;
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,d.docno, 0 - sum(b.credit - b.debit) as advancepayment, c.DOCDATE
        from journalvouchers a, journalvoucheritems d, journalvoucheritems b, journalvouchers c
        where d.company = a.company
        and d.branch = a.branch
        and d.docid = a.docid
        and b.company = a.company
        and b.branch = a.branch
        and b.refno = d.docno
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and d.company = pi_company
        and d.branch = pi_branch
        and d.itemtype = 'C'
        AND d.reftype = ''
        AND d.accttype=''
        and c.docdate > pi_date2
        and (bpcode='' or (bpcode<>'' AND d.ITEMNO = bpcode))
        group by d.DOCNO;
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.docno, 0 - sum(b.credit - b.debit) as advancepayment, c.DOCDATE
        from arcreditmemos a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and c.docdate > pi_date2
        and (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
        group by a.DOCNO;

DROP TEMPORARY TABLE IF EXISTS `adv_sales_return`;
CREATE TEMPORARY TABLE  `adv_sales_return` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `advancepayment` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO adv_sales_return (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
       SELECT a.company,a.branch,a.docno, sum(0 - b.BASEAMOUNT), b.DOCDATE
        from arinvoices a, arcreditmemos b
        where b.company = a.company
        and b.branch = a.branch
        and (b.BASEDOCNO = a.docno)
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype='' and a.docdate <= pi_date2
        and b.docdate > pi_date2
        and (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
        group by a.DOCNO;

DROP TEMPORARY TABLE IF EXISTS `concatfield`;
CREATE TEMPORARY TABLE  `concatfield` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `DUEAMOUNT` NUMERIC(18,6) NULL default '0',
    `advpay` NUMERIC(18,6) NULL default '0',
    `amount` NUMERIC(18,6) NULL default '0',
    `current` NUMERIC(18,6) NULL default '0',
    `next_months` NUMERIC(18,6) NULL default '0',
    `D7` NUMERIC(18,6) NULL default '0',
    `D15` NUMERIC(18,6) NULL default '0',
    `D30` NUMERIC(18,6) NULL default '0',
    `UP30` NUMERIC(18,6) NULL default '0',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT
    INTO concatfield (COMPANY,BRANCH,DOCNO,DUEAMOUNT,advpay,amount,current,next_months,D7,D15,D30)
    SELECT arinvoices.COMPANY, arinvoices.BRANCH, arinvoices.DOCNO,
        (arinvoices.totalamount - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,


        arinvoices.totalamount,


        case when datediff(pi_date2, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d')) = 0
        then (arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'current',


        case when datediff(pi_date2, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d')) < 0
        then (arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'next',


        case when datediff(pi_date2, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 15 then (arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'D7',


        case when datediff(pi_date2, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 16 and 30 then (arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'D15',


        case when datediff(pi_date2, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d'))
        >= 31 then (arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'D30'


        FROM arinvoices
        LEFT OUTER JOIN departments on if(arinvoices.saletype = '', arinvoices.department = departments.department, arinvoices.saletype = departments.department)
        LEFT OUTER JOIN pdcpayments ON arinvoices.COMPANY = pdcpayments.COMPANY AND arinvoices.BRANCH = pdcpayments.BRANCH AND arinvoices.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON arinvoices.COMPANY = balances.COMPANY AND arinvoices.BRANCH = balances.BRANCH AND arinvoices.DOCNO = balances.DOCNO
        LEFT OUTER JOIN journalpayments ON arinvoices.COMPANY = journalpayments.COMPANY AND arinvoices.BRANCH = journalpayments.BRANCH AND arinvoices.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON arinvoices.COMPANY = adv.COMPANY AND arinvoices.BRANCH = adv.BRANCH AND arinvoices.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON arinvoices.COMPANY = advjv.COMPANY AND arinvoices.BRANCH = advjv.BRANCH AND arinvoices.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON arinvoices.COMPANY = sale_return.COMPANY AND arinvoices.BRANCH = sale_return.BRANCH AND arinvoices.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON arinvoices.COMPANY = adv_sales_return.COMPANY AND arinvoices.BRANCH = adv_sales_return.BRANCH AND arinvoices.DOCNO = adv_sales_return.DOCNO
        WHERE arinvoices.trxtype<>'POS' and arinvoices.accttype='' and arinvoices.docdate <= pi_date2
        AND arinvoices.COMPANY = pi_company AND arinvoices.BRANCH = pi_branch
        AND (bpcode='' or (bpcode<>'' AND arinvoices.BPCODE = bpcode))
        GROUP BY arinvoices.DOCNO
        ORDER BY arinvoices.docno ASC;


INSERT
    INTO concatfield (COMPANY,BRANCH,DOCNO,DUEAMOUNT,advpay,amount,current,next_months,D7,D15,D30)
    SELECT journalvoucheritems.COMPANY, journalvoucheritems.BRANCH, journalvoucheritems.DOCNO,
        (journalvoucheritems.grossamount - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,


        journalvoucheritems.grossamount,


        case when datediff(pi_date2, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) = 0
        then (journalvoucheritems.grossamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'current',


        case when datediff(pi_date2, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) < 0
        then (journalvoucheritems.grossamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'next',


        case when datediff(pi_date2, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 15 then (journalvoucheritems.grossamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'D7',


        case when datediff(pi_date2, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 16 and 30 then (journalvoucheritems.grossamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'D15',


        case when datediff(pi_date2, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        >= 31 then (journalvoucheritems.grossamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'D30'


        FROM journalvoucheritems
        INNER JOIN journalvouchers ON journalvoucheritems.company = journalvouchers.company AND journalvoucheritems.branch = journalvouchers.branch AND journalvoucheritems.docid = journalvouchers.docid AND journalvouchers.docdate <= pi_date2
        LEFT OUTER JOIN departments on journalvoucheritems.department = departments.department
        LEFT OUTER JOIN pdcpayments ON journalvouchers.COMPANY = pdcpayments.COMPANY AND journalvouchers.BRANCH = pdcpayments.BRANCH AND journalvoucheritems.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON journalvouchers.COMPANY = balances.COMPANY AND journalvouchers.BRANCH = balances.BRANCH AND journalvoucheritems.DOCNO = balances.DOCNO
        LEFT OUTER JOIN journalpayments ON journalvouchers.COMPANY = journalpayments.COMPANY AND journalvouchers.BRANCH = journalpayments.BRANCH AND journalvoucheritems.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON journalvouchers.COMPANY = adv.COMPANY AND journalvouchers.BRANCH = adv.BRANCH AND journalvoucheritems.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON journalvouchers.COMPANY = advjv.COMPANY AND journalvouchers.BRANCH = advjv.BRANCH AND journalvoucheritems.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON journalvouchers.COMPANY = sale_return.COMPANY AND journalvouchers.BRANCH = sale_return.BRANCH AND journalvoucheritems.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON journalvouchers.COMPANY = adv_sales_return.COMPANY AND journalvouchers.BRANCH = adv_sales_return.BRANCH AND journalvoucheritems.DOCNO = adv_sales_return.DOCNO
        WHERE journalvoucheritems.itemtype = 'C'
          AND journalvoucheritems.reftype = ''
          AND journalvoucheritems.accttype=''
          AND journalvoucheritems.COMPANY = pi_company AND journalvoucheritems.BRANCH = pi_branch
          AND (bpcode='' or (bpcode<>'' AND journalvoucheritems.ITEMNO = bpcode))
        GROUP BY journalvoucheritems.DOCNO
        ORDER BY journalvoucheritems.DOCNO ASC;

INSERT
    INTO concatfield (COMPANY,BRANCH,DOCNO,DUEAMOUNT,advpay,amount,current,next_months,D7,D15,D30)
    SELECT arcreditmemos.COMPANY, arcreditmemos.BRANCH, arcreditmemos.DOCNO,
        (arcreditmemos.totalamount - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,


        arcreditmemos.totalamount,


        case when datediff(pi_date2, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d')) = 0
        then (arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'current',


        case when datediff(pi_date2, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d')) < 0
        then (arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'next',


        case when datediff(pi_date2, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 15 then (arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'D7',


        case when datediff(pi_date2, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d'))
        between 16 and 30 then (arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'D15',


        case when datediff(pi_date2, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d'))
        >= 31 then (arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end as 'D30'


        FROM arcreditmemos
        LEFT OUTER JOIN departments on if(arcreditmemos.saletype = '', arcreditmemos.department = departments.department, arcreditmemos.saletype = departments.department)
        LEFT OUTER JOIN pdcpayments ON arcreditmemos.COMPANY = pdcpayments.COMPANY AND arcreditmemos.BRANCH = pdcpayments.BRANCH AND arcreditmemos.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON arcreditmemos.COMPANY = balances.COMPANY AND arcreditmemos.BRANCH = balances.BRANCH AND arcreditmemos.DOCNO = balances.DOCNO
        LEFT OUTER JOIN journalpayments ON arcreditmemos.COMPANY = journalpayments.COMPANY AND arcreditmemos.BRANCH = journalpayments.BRANCH AND arcreditmemos.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON arcreditmemos.COMPANY = adv.COMPANY AND arcreditmemos.BRANCH = adv.BRANCH AND arcreditmemos.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON arcreditmemos.COMPANY = advjv.COMPANY AND arcreditmemos.BRANCH = advjv.BRANCH AND arcreditmemos.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON arcreditmemos.COMPANY = sale_return.COMPANY AND arcreditmemos.BRANCH = sale_return.BRANCH AND arcreditmemos.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON arcreditmemos.COMPANY = adv_sales_return.COMPANY AND arcreditmemos.BRANCH = adv_sales_return.BRANCH AND arcreditmemos.DOCNO = adv_sales_return.DOCNO
        WHERE arcreditmemos.totalamount > 0 AND arcreditmemos.accttype = ''
        AND arcreditmemos.docdate <= pi_date2
        AND arcreditmemos.COMPANY = pi_company AND arcreditmemos.BRANCH = pi_branch
        AND (bpcode='' or (bpcode<>'' AND arcreditmemos.BPCODE = bpcode))
        GROUP BY arcreditmemos.DOCNO
        ORDER BY arcreditmemos.docno ASC;

DROP TEMPORARY TABLE IF EXISTS `mainfield`;
CREATE TEMPORARY TABLE  `mainfield` (
    `COMPANY` varchar(100) NULL default '',
    `BRANCH` varchar(100) NULL default '',
    `BRANCHNAME` varchar(100) NULL default '',
    `BPCODE` varchar(100) NULL default '',
    `BPNAME` varchar(500) NULL default '',
    `BILLTOADDRESS` varchar(1000) NULL default '',
    `ITEMCODE` varchar(100) NULL default '',
    `DOCNO` varchar(100) NULL default '',
    `DOCDUEDATE` DATE NULL,
    `DUEAMOUNT` NUMERIC(18,6) NULL default '0',
    `aging` NUMERIC(18,6) NULL default '0',
    `fisrtmonth` DATE NULL,
    `secondndmonth` DATE NULL,
    `thirdmonth` DATE NULL,
    `advpay` NUMERIC(18,6) NULL default '0',
    `amount` NUMERIC(18,6) NULL default '0',
    `current` NUMERIC(18,6) NULL default '0',
    `next_months` NUMERIC(18,6) NULL default '0',
    `D7` NUMERIC(18,6) NULL default '0',
    `D15` NUMERIC(18,6) NULL default '0',
    `D30` NUMERIC(18,6) NULL default '0',
    `UP30` NUMERIC(18,6) NULL default '0',
    `PDC` NUMERIC(18,6) NULL default '0',
    `cname` varchar(100) NULL default ''
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,DOCNO,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,amount,current,next_months,D7,D15,D30,UP30,
    PDC,cname)
    SELECT arinvoices.COMPANY,
        arinvoices.BRANCH,
        upper(branches.BRANCHNAME) as BRANCHNAME,
        arinvoices.BPCODE,
        arinvoices.BPNAME,
        arinvoices.BILLTOADDRESS,
        arinvoiceitems.ITEMCODE,
        arinvoices.DOCNO,
        date(if(arinvoices.DOCDUEDATE is null, now(), arinvoices.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date2, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d')) AS aging,
        date(pi_date2) - interval 0 month as 1month,
        date(pi_date2) - interval 1 month as 2month,
        date(pi_date2) - interval 2 month as 3month,
        concatfield.advpay,
        concatfield.amount,
        concatfield.current,
        concatfield.next_months,
        concatfield.D7 as '7',
        concatfield.D15 as '15',
        concatfield.D30 as '30',
        concatfield.UP30 as 'UP30',
        if (pdcpayments.pdcamount is null, 0, pdcpayments.pdcamount) AS 'PDC',
        upper(companies.companyname) as cname
        FROM arinvoices
        LEFT OUTER JOIN branches ON arinvoices.BRANCH = branches.BRANCHCODE
        LEFT OUTER JOIN arinvoiceitems ON arinvoices.BRANCH = arinvoiceitems.BRANCH AND arinvoices.COMPANY = arinvoiceitems.COMPANY AND arinvoices.DOCID = arinvoiceitems.DOCID
        LEFT OUTER JOIN companies on arinvoices.company = companies.companycode
        LEFT OUTER JOIN departments on if(arinvoices.saletype = '', arinvoices.department = departments.department, arinvoices.saletype = departments.department)
        LEFT OUTER JOIN pdcpayments ON arinvoices.COMPANY = pdcpayments.COMPANY AND arinvoices.BRANCH = pdcpayments.BRANCH AND arinvoices.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON arinvoices.COMPANY = concatfield.COMPANY AND arinvoices.BRANCH = concatfield.BRANCH
                        AND arinvoices.DOCNO = concatfield.DOCNO
        WHERE arinvoices.trxtype<>'POS' and arinvoices.accttype='' and arinvoices.docdate <= pi_date2
        AND arinvoices.COMPANY = pi_company AND arinvoices.BRANCH = pi_branch
        AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
        AND (bpcode='' or (bpcode<>'' AND arinvoices.BPCODE = bpcode))
        GROUP BY arinvoices.DOCNO, branches.BRANCHNAME
        ORDER BY arinvoices.docno ASC;
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,DOCNO,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,amount,current,next_months,D7,D15,D30,UP30,
    PDC,cname)
    SELECT arcreditmemos.COMPANY,
        arcreditmemos.BRANCH,
        upper(branches.BRANCHNAME) as BRANCHNAME,
        arcreditmemos.BPCODE,
        arcreditmemos.BPNAME,
        arcreditmemos.BILLTOADDRESS,
        arcreditmemoitems.ITEMCODE,
        arcreditmemos.DOCNO,
        date(if(arcreditmemos.DOCDUEDATE is null, now(), arcreditmemos.DOCDUEDATE)) as DOCDUEDATE,
        0-(concatfield.DUEAMOUNT),
        datediff(pi_date2, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d')) AS aging,
        date(pi_date2) - interval 0 month as 1month,
        date(pi_date2) - interval 1 month as 2month,
        date(pi_date2) - interval 2 month as 3month,
        0-(concatfield.advpay),
        0-(concatfield.amount),
        0-(concatfield.current),
        0-(concatfield.next_months),
        0-(concatfield.D7) as '7',
        0-(concatfield.D15) as '15',
        0-(concatfield.D30) as '30',
        0-(concatfield.UP30) as 'UP30',
        0-(if (pdcpayments.pdcamount is null, 0, pdcpayments.pdcamount)) AS 'PDC',
        upper(companies.companyname) as cname
        FROM arcreditmemos
        LEFT OUTER JOIN branches ON arcreditmemos.BRANCH = branches.BRANCHCODE
        LEFT OUTER JOIN arcreditmemoitems ON arcreditmemos.BRANCH = arcreditmemoitems.BRANCH AND arcreditmemos.COMPANY = arcreditmemoitems.COMPANY AND arcreditmemos.DOCID = arcreditmemoitems.DOCID
        LEFT OUTER JOIN companies on arcreditmemos.company = companies.companycode
        LEFT OUTER JOIN departments on if(arcreditmemos.saletype = '', arcreditmemos.department = departments.department, arcreditmemos.saletype = departments.department)
        LEFT OUTER JOIN pdcpayments ON arcreditmemos.COMPANY = pdcpayments.COMPANY AND arcreditmemos.BRANCH = pdcpayments.BRANCH AND arcreditmemos.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON arcreditmemos.COMPANY = concatfield.COMPANY AND arcreditmemos.BRANCH = concatfield.BRANCH
                        AND arcreditmemos.DOCNO = concatfield.DOCNO
        WHERE arcreditmemos.totalamount > 0 AND arcreditmemos.accttype = ''
        AND arcreditmemos.docdate <= pi_date2
        AND arcreditmemos.COMPANY = pi_company AND arcreditmemos.BRANCH = pi_branch
        AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
        AND (bpcode='' or (bpcode<>'' AND arcreditmemos.BPCODE = bpcode))
        GROUP BY arcreditmemos.DOCNO, branches.BRANCHNAME
        ORDER BY arcreditmemos.docno ASC;
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,DOCNO,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,amount,current,next_months,D7,D15,D30,UP30,
    PDC,cname)
    SELECT journalvouchers.COMPANY,
        journalvouchers.BRANCH,
        upper(branches.BRANCHNAME) as BRANCHNAME,
        journalvoucheritems.itemno as BPCODE,
        journalvoucheritems.itemname as BPNAME,
        '' as BILLTOADDRESS,
        '' as ITEMCODE,
        journalvoucheritems.DOCNO,
        date(if(journalvouchers.DOCDUEDATE is null, now(), journalvouchers.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date2, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) AS aging,
        date(pi_date2) - interval 0 month as 1month,
        date(pi_date2) - interval 1 month as 2month,
        date(pi_date2) - interval 2 month as 3month,
        concatfield.advpay,
        concatfield.amount,
        concatfield.current,
        concatfield.next_months,
        concatfield.D7 as '7',
        concatfield.D15 as '15',
        concatfield.D30 as '30',
        concatfield.UP30 as 'UP30',
        if (pdcpayments.pdcamount is null, 0, pdcpayments.pdcamount) AS 'PDC',
        upper(companies.companyname) as cname
        FROM journalvoucheritems
        INNER JOIN journalvouchers ON journalvoucheritems.company = journalvouchers.company AND journalvoucheritems.branch = journalvouchers.branch AND journalvoucheritems.docid = journalvouchers.docid AND journalvouchers.docdate <= pi_date2
        LEFT OUTER JOIN branches ON journalvouchers.BRANCH = branches.BRANCHCODE
        LEFT OUTER JOIN companies on journalvouchers.company = companies.companycode
        LEFT OUTER JOIN departments on journalvoucheritems.department = departments.department
        LEFT OUTER JOIN pdcpayments ON journalvouchers.COMPANY = pdcpayments.COMPANY AND journalvouchers.BRANCH = pdcpayments.BRANCH AND journalvouchers.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON journalvouchers.COMPANY = concatfield.COMPANY AND journalvouchers.BRANCH = concatfield.BRANCH
                        AND journalvoucheritems.DOCNO = concatfield.DOCNO
        WHERE journalvoucheritems.itemtype = 'C'
          AND journalvoucheritems.reftype = ''
          AND journalvoucheritems.accttype=''
          AND journalvoucheritems.COMPANY = pi_company AND journalvoucheritems.BRANCH = pi_branch
        and (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
        AND (bpcode='' or (bpcode<>'' AND journalvoucheritems.itemno = bpcode))
        GROUP BY journalvoucheritems.DOCNO, branches.BRANCHNAME
        ORDER BY journalvoucheritems.docno ASC;


  SELECT
    a.COMPANY,
    a.BRANCH,
    a.cname as COMPANY_NAME,
    a.BRANCHNAME as BRANCH_NAME,

    upper(concat(if(e.BARANGAY is null or e.BARANGAY = '','' , concat(e.BARANGAY,', '))
                 , if(e.STREET is null or e.STREET = '','' , concat(e.STREET,', '))
                 , if(e.ZIP is null or e.ZIP = '','' , concat(e.ZIP,', '))
                 , if(e.CITY is null or e.CITY = '','' , concat(e.CITY,', '))
                 , if(pro.PROVINCENAME is null or pro.PROVINCENAME = '','' , concat(pro.PROVINCENAME,', '))
                 , if(ctx.COUNTRYNAME is null or ctx.COUNTRYNAME = '','' , ctx.COUNTRYNAME))) as COMPANY_ADDRESS,
    '' as COMPANY_CONTACTS,

    date(pi_date1) as DATE1,
    date(pi_date2) as DATE2,
    a.BPCODE,
    a.BPNAME,

    (select upper(concat(if(ax.BARANGAY is null or ax.BARANGAY = '','' , concat(ax.BARANGAY,', '))
                 , if(ax.STREET is null or ax.STREET = '','' , concat(ax.STREET,', '))
                 , if(ax.ZIP is null or ax.ZIP = '','' , concat(ax.ZIP,', '))
                 , if(ax.CITY is null or ax.CITY = '','' , concat(ax.CITY,', '))
                 , if(ax3.PROVINCENAME is null or ax3.PROVINCENAME = '','' , concat(ax3.PROVINCENAME,', '))
                 , if(ax2.COUNTRYNAME is null or ax2.COUNTRYNAME = '','' , ax2.COUNTRYNAME)))
           from addresses ax
                LEFT OUTER JOIN countries ax2 on ax.country = ax2.country
                LEFT OUTER JOIN provinces ax3 on ax.province = ax3.province
     where ax.refid = a.BPCODE and ax.reftype = 'CUSTOMER' and ax.addresstype = 0 limit 1) as BP_ADDRESS,

    (select upper(concat(if(salutation is null or salutation = '','' , concat(salutation,' ')), name))
            from customercontacts where custno = a.BPCODE limit 1) as BP_CONTACTS,

    pt.paymenttermNAME as TERM,
    ct.CREDITLIMIT as CREDITLIMIT,
    a.DOCDUEDATE,
    a.DOCNO,
    a.amount as AMOUNT,
    a.amount - a.DUEAMOUNT as PAYMENT,
    a.DUEAMOUNT as OUTSTANDING_BALANCE,
    a.current as CURRENT,
    a.next_months as NEXT_MONTHS,
    a.D7 as '1-15',
    a.D15 as '16-30',
    a.D30 as 'OVER30'

  FROM mainfield a
       LEFT OUTER JOIN customers ct ON ct.custno = a.BPCODE
       LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
       LEFT OUTER JOIN branches e on e.BRANCHCODE = pi_branch
       LEFT OUTER JOIN countries ctx on e.country = ctx.country
       LEFT OUTER JOIN provinces pro on e.province = pro.province

  WHERE (bpcode='' or (bpcode<>'' AND a.BPCODE = bpcode))
  ORDER BY a.DOCDUEDATE, a.DOCNO;


END $$

DELIMITER ;