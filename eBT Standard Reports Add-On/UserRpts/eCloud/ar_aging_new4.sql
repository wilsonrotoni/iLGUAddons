DELIMITER $$

DROP PROCEDURE IF EXISTS `ar_aging_new4` $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ar_aging_new4`(
    IN pi_company VARCHAR(30),
    IN pi_branch VARCHAR(30),
    IN pi_custgroup VARCHAR(30),
    IN pi_accttype VARCHAR(30),
    IN pi_ctrlacct VARCHAR(30),
    IN pi_custno VARCHAR(30),
    IN pi_date VARCHAR(30),
    IN pi_output VARCHAR(30),
    IN pi_salesperson VARCHAR(50),
    IN pi_currency VARCHAR(30)
 )
BEGIN
  DECLARE v_companyname VARCHAR(100);
  DECLARE v_branchname VARCHAR(100);
  DECLARE v_currency VARCHAR(10);
  DECLARE v_advms DATE;
  DECLARE v_advme DATE;
  DECLARE v_30ms DATE;
  DECLARE v_60ms DATE;
  DECLARE v_90ms DATE;
  DECLARE v_30me DATE;
  DECLARE v_60me DATE;
  DECLARE v_90me DATE;
  DECLARE v_MONTH_END INT;
  
  SELECT CURRENCY INTO v_currency from COMPANIES where companycode=pi_company;

  SET v_30ms = date(concat(substring(pi_date,1,7),'-01'));
  SET v_advms = v_30ms + interval 1 month;
  SET v_60ms = v_30ms - interval 1 month;
  SET v_90ms = v_30ms - interval 2 month;
  SET v_30me = date(pi_date);
  SET v_advme = last_day(v_30me + interval 1 month);
  SET v_60me = last_day(v_30me - interval 1 month);
  SET v_90me = last_day(v_30me - interval 2 month);
  SET v_MONTH_END = concat('-',substring(v_60me,9,2)+1);
  SELECT UPPER(co.companyname), UPPER(br.branchname) into v_companyname, v_branchname from companies co, branches br where co.companycode = pi_company and br.companycode = pi_company and br.branchcode = pi_branch;
DROP TEMPORARY TABLE IF EXISTS `pdcpayments`;
CREATE TEMPORARY TABLE  `pdcpayments` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `BPCODE` varchar(30) NULL default '',
    `ACCTNO` varchar(30) NULL default '',
    `PDCAMOUNT` NUMERIC(18,6) NULL default '0',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`ACCTNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO pdcpayments (COMPANY,BRANCH,BPCODE,ACCTNO,PDCAMOUNT)
      SELECT a.company,a.branch,a.bpcode,a.refno, sum(a.rebate + a.amount + a.discamount) as pdcamount
        from collectionsinvoices a, collectionscheques b, collections c
        where b.company = a.company and b.branch = a.branch
        and b.docno = a.docno
        and b.company = c.company
        and b.branch = c.branchcode
        and b.docno = c.docno
        and c.pdc=1
        and a.company = pi_company and a.branch = pi_branch
        and b.checkdate >= pi_date
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.refno;
DROP TEMPORARY TABLE IF EXISTS `balances`;
CREATE TEMPORARY TABLE  `balances` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `OBJECTCODE` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `BALANCE` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`OBJECTCODE`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
DROP TEMPORARY TABLE IF EXISTS `settlements`;
CREATE TEMPORARY TABLE  `settlements` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `OBJECTCODE` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `BALANCE` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    `PAYDOCNO` varchar(30) NULL default '',
    `TYPE` varchar(30) NULL default '',
    INDEX `Index_Docno` (`COMPANY`,`BRANCH`,`OBJECTCODE`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
call sp_processtime('start','');
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('A/R Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company, branch, objectcode, docno, BALANCE, DOCDATE, paydocno, 'BANKING'
      from (SELECT a.company,a.branch,a.objectcode, a.docno, sum(b.amount - b.penaltypaid + b.discamount) as BALANCE, c.VALUEDATE AS DOCDATE, c.docno as paydocno
              from arinvoices a, collectionsinvoices b, collections c
                where b.company = a.company
                  and b.branch = a.branch
                  and b.refno = a.docno
                  and b.reftype = a.objectcode
                  and c.company = b.company
                  and c.branchcode = b.branch
                  and c.docno = b.docno
                  and a.company = pi_company
                  and a.branch = pi_branch
                  AND a.accttype=''
                  and a.docstatus in ('O','C')
                  and a.docdate <= pi_date
                  and c.cleared <> 0
                  and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
                  and c.valuedate <= pi_date and c.valuedate<>'0000-00-00'
                  and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
                group by a.DOCNO, c.docno
            union all
              SELECT a.company,a.branch,a.objectcode, a.docno, sum(b.amount*-1) as BALANCE, c.DOCDATE, c.docno as paydocno
                from arinvoices a, paymentinvoices b, payments c
                  where b.company = a.company
                    and b.branch = a.branch
                    and b.refno = a.docno
                    and b.reftype = a.objectcode
                    and c.company = b.company
                    and c.branchcode = b.branch
                    and c.docno = b.docno
                    and a.company = pi_company
                    and a.branch = pi_branch
                    and a.docstatus in ('O','C')
                    and a.docdate <= pi_date
                    and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
                    and c.valuedate <= pi_date
                    and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
                  group by a.DOCNO, c.docno
            ) a;





INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company, branch, objectcode, docno, BALANCE, DOCDATE, paydocno, 'BANKING'
      from (SELECT a.company,a.branch,a.objectcode, a.docno, sum(b.amount - b.penaltypaid + b.discamount) as BALANCE, c.VALUEDATE AS DOCDATE, c.docno as paydocno
              from ardownpaymentinvoices a, collectionsinvoices b, collections c
                where b.company = a.company
                  and b.branch = a.branch
                  and b.refno = a.docno
                  and b.reftype = a.objectcode
                  and c.company = b.company
                  and c.branchcode = b.branch
                  and c.docno = b.docno
                  and a.company = pi_company
                  and a.branch = pi_branch
                  AND a.accttype=''
                  and a.docstatus in ('O','C')
                  and a.docdate <= pi_date
                  and c.cleared <> 0
                  and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
                  and c.valuedate <= pi_date and c.valuedate<>'0000-00-00'
                  and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
                group by a.DOCNO, c.docno
            union all
              SELECT a.company,a.branch,a.objectcode, a.docno, sum(b.amount*-1) as BALANCE, c.DOCDATE, c.docno as paydocno
                from ardownpaymentinvoices a, paymentinvoices b, payments c
                  where b.company = a.company
                    and b.branch = a.branch
                    and b.refno = a.docno
                    and b.reftype = a.objectcode
                    and c.company = b.company
                    and c.branchcode = b.branch
                    and c.docno = b.docno
                    and a.company = pi_company
                    and a.branch = pi_branch
                    and a.docstatus in ('O','C')
                    and a.docdate <= pi_date
                    and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
                    and c.valuedate <= pi_date
                    and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
                  group by a.DOCNO, c.docno
            ) a;








call sp_processtime('A/R Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('Journal Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company, branch, objectcode, docno, sum(BALANCE), DOCDATE, paydocno, 'BANKING'
      from (SELECT a.company,a.branch,a.objectcode,d.docno, sum(b.amount - b.penaltypaid + b.discamount) as BALANCE, c.VALUEDATE AS DOCDATE, c.docno as paydocno
              from journalvouchers a USE INDEX (Index_aging2), journalvoucheritems d USE INDEX (Index_aging), collectionsinvoices b USE INDEX (IDX_COLLECTIONSINVOICES), collections c USE INDEX (UIDX_COLLECTIONS)
              where b.company = a.company
                and b.branch = a.branch
                and b.refno = d.docno
                and b.reftype = a.objectcode
                and c.company = b.company
                and c.branchcode = b.branch
                and c.docno = b.docno
                and c.cleared <> 0
                and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date and c.valuedate<>'0000-00-00'
                and a.company = pi_company
                and a.branch = pi_branch
                and a.docdate <= pi_date
                and a.docstatus in ('O','C')
                and d.company = a.company
                and d.branch = a.branch
                and d.docid = a.docid
                and d.itemtype = 'C'
                and d.reftype = ''
                and d.accttype=''
                and (pi_custno='' or (pi_custno<>'' and d.itemno=pi_custno))
              group by d.DOCNO, c.docno
        union all
          SELECT a.company,a.branch,a.objectcode,d.docno, sum(b.amount*-1) as BALANCE, c.DOCDATE, c.docno as paydocno
            from journalvouchers a USE INDEX (Index_aging2), journalvoucheritems d USE INDEX (Index_aging), paymentinvoices b USE INDEX (IDX_PAYMENTINVOICES), payments c USE INDEX (UIDX_COLLECTIONS)
            where b.company = a.company
              and b.branch = a.branch
              and b.refno = d.docno
              and b.reftype = a.objectcode
              and c.company = b.company
              and c.branchcode = b.branch
              and c.docno = b.docno
              and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
              and c.valuedate <= pi_date
              and a.company = pi_company
              and a.branch = pi_branch
              and a.docdate <= pi_date
              and a.docstatus in ('O','C')
              and d.company = a.company
              and d.branch = a.branch
              and d.docid = a.docid
              and d.itemtype = 'C'
              AND d.reftype = ''
              AND d.accttype=''
              and (pi_custno='' or (pi_custno<>'' and d.itemno=pi_custno))
            group by d.DOCNO, c.docno
          ) a group by docno, paydocno;
call sp_processtime('Journal Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Journal Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company, branch, objectcode, docno, sum(BALANCE), DOCDATE, paydocno, 'BANKING'
      from (SELECT a.company,a.branch,a.objectcode,d.docno, sum(b.amount - b.penaltypaid + b.discamount) as BALANCE, c.VALUEDATE AS DOCDATE, c.docno as paydocno
              from journalvouchers a, journalvoucheritems d, collectionsinvoices b, collections c
              where b.company = a.company
                and b.branch = a.branch
                and b.refno = d.docno
                and b.reftype = a.objectcode
                and c.company = b.company
                and c.branchcode = b.branch
                and c.docno = b.docno
                and c.cleared <> 0
                and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date and c.valuedate<>'0000-00-00'
                and a.company = pi_company
                and a.branch = pi_branch
                and a.docdate <= pi_date
                and a.docstatus in ('O','C')
                and d.company = a.company
                and d.branch = a.branch
                and d.docid = a.docid
                and d.itemtype = 'C'
                and d.reftype='OUTDOWNPAYMENT' and d.refno=''
                and d.accttype=''
                and (pi_custno='' or (pi_custno<>'' and d.itemno=pi_custno))
              group by d.DOCNO, c.docno
        union all
          SELECT a.company,a.branch,a.objectcode,d.docno, sum(b.amount*-1) as BALANCE, c.DOCDATE, c.docno as paydocno
            from journalvouchers a, journalvoucheritems d, paymentinvoices b, payments c
            where b.company = a.company
              and b.branch = a.branch
              and b.refno = d.docno
              and b.reftype = a.objectcode
              and c.company = b.company
              and c.branchcode = b.branch
              and c.docno = b.docno
              and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
              and c.valuedate <= pi_date
              and a.company = pi_company
              and a.branch = pi_branch
              and a.docdate <= pi_date
              and a.docstatus in ('O','C')
              and d.company = a.company
              and d.branch = a.branch
              and d.docid = a.docid
              and d.itemtype = 'C'
              AND d.reftype='OUTDOWNPAYMENT' and d.refno=''
              AND d.accttype=''
              and (pi_custno='' or (pi_custno<>'' and d.itemno=pi_custno))
            group by d.DOCNO, c.docno
          ) a group by docno, paydocno;
call sp_processtime('Advances Journal Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('A/R CM Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company,branch,objectcode,docno, sum(BALANCE), DOCDATE, paydocno, 'BANKING'
      from (SELECT a.company,a.branch,a.objectcode,a.docno, sum(b.amount - b.penaltypaid + b.discamount)*-1 as BALANCE, c.VALUEDATE AS DOCDATE, c.docno as paydocno
              from arcreditmemos a, collectionsinvoices b, collections c
              where b.company = a.company
                and b.branch = a.branch
                and b.refno = a.docno
                and b.reftype = a.objectcode
                and c.company = b.company
                and c.branchcode = b.branch
                and c.docno = b.docno
                and a.company = pi_company
                and a.branch = pi_branch
                AND a.accttype=''
                and a.docdate <= pi_date
                and a.docstatus in ('O','C')
                and c.cleared <> 0
                and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date and c.valuedate<>'0000-00-00'
                and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
              group by a.DOCNO, c.docno
          union all
            SELECT a.company,a.branch,a.objectcode,a.docno, sum(b.amount) as BALANCE, c.DOCDATE, c.docno as paydocno
              from arcreditmemos a, paymentinvoices b, payments c
              where b.company = a.company
                and b.branch = a.branch
                and b.refno = a.docno
                and b.reftype = a.objectcode
                and c.company = b.company
                and c.branchcode = b.branch
                and c.docno = b.docno
                and a.company = pi_company
                and a.branch = pi_branch
                AND a.accttype=''
                and a.docdate <= pi_date
                and a.docstatus in ('O','C')
                and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date
                and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
              group by a.DOCNO, c.docno
        ) a group by DOCNO, paydocno;
call sp_processtime('A/R CM Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('DP/RS Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company,branchcode,objectcode,docno, sum(BALANCE), DOCDATE, PAYDOCNO, 'BANKING'
      from (SELECT a.company,a.branchcode,a.objectcode,a.docno, if(a.collfor = 'DP',sum(b.amount - b.penaltypaid + b.discamount)*-1, sum(b.amount - b.penaltypaid)) as BALANCE, c.VALUEDATE AS DOCDATE, c.docno as paydocno
              from collections a, collectionsinvoices b, collections c
              where b.company = a.company
                and b.branch = a.branchcode
                and b.refno = a.docno
                and b.reftype in ('DEPOSIT','DOWNPAYMENT')
                and c.company = b.company
                and c.branchcode = b.branch
                and c.docno = b.docno
                and a.company = pi_company
                and a.branchcode = pi_branch
                AND a.collfor in('DP','RS')
                and a.doctype='c'
                and a.valuedate <= pi_date
                and a.docstat in ('O','C')
                and c.cleared <> 0
                and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date and c.valuedate<>'0000-00-00'
                and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
              group by a.DOCNO, c.docno
         union all
           SELECT a.company,a.branchcode,a.objectcode,a.docno, sum(b.amount) as BALANCE, c.DOCDATE, c.docno as paydocno
              from collections a, paymentinvoices b, payments c
              where b.company = a.company
                and b.branch = a.branchcode
                and b.refno = a.docno
                and b.reftype in ('DEPOSIT','DOWNPAYMENT')
                and c.company = b.company
                and c.branchcode = b.branch
                and c.docno = b.docno
                and a.company = pi_company
                and a.branchcode = pi_branch
                AND a.collfor in('DP','RS')
                and  a.doctype='c' 
                and a.valuedate <= pi_date
                and a.docstat in ('O','C')
                and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date
                and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
              group by a.DOCNO, c.docno
        ) a group by docno, paydocno;
call sp_processtime('DP/RS Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company,branchcode,objectcode,docno, sum(BALANCE), DOCDATE, PAYDOCNO, 'BANKING'
      from (SELECT a.company,a.branchcode,a.objectcode,a.docno, sum(b.amount - b.penaltypaid + b.discamount) as BALANCE, c.DOCDATE, c.docno as PAYDOCNO
              from payments a, collectionsinvoices b, collections c
              where b.company = a.company
                and b.branch = a.branchcode
                and b.refno = a.docno
                and b.reftype in ('OUTDOWNPAYMENT')
                and c.company = b.company
                and c.branchcode = b.branch
                and c.docno = b.docno
                and a.company = pi_company
                and a.branchcode = pi_branch
                AND a.collfor in('DP')
                and  a.doctype='c' 
                and a.docdate <= pi_date
                and a.docstat in ('O','C')
                and a.doctype = 'C'
                and c.cleared <> 0
                and (c.docstat not in ('CN','D') or (c.docstat = 'CN' and c.cancelleddate > pi_date))
                and c.docdate <= pi_date
                and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
              group by a.DOCNO, c.docno
         union all
           SELECT a.company,a.branchcode,a.objectcode,a.docno, sum(b.amount)*-1 as BALANCE, c.DOCDATE, c.docno as PAYDOCNO
              from payments a, paymentinvoices b, payments c
              where b.company = a.company
                and b.branch = a.branchcode
                and b.refno = a.docno
                and b.reftype in ('OUTDOWNPAYMENT')
                and c.company = b.company
                and c.branchcode = b.branch
                and c.docno = b.docno
                and a.company = pi_company
                and a.branchcode = pi_branch
                AND a.collfor in('DP')
                and  a.doctype='c' 
                and a.docdate <= pi_date
                and a.docstat in ('O','C')
                and a.doctype = 'C' 
                and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date
                and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
              group by a.DOCNO, c.docno
        ) a group by docno, paydocno;
call sp_processtime('Advances Payments','end');
end if;
INSERT
  INTO balances (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE)
     select COMPANY,BRANCH,OBJECTCODE,DOCNO,sum(BALANCE),DOCDATE from settlements where type='BANKING' group by docno;
DROP TEMPORARY TABLE IF EXISTS `journalpayments`;
CREATE TEMPORARY TABLE  `journalpayments` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `BALANCE` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('A/R Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branch,a.objectcode,a.docno, sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as BALANCE, c.DOCDATE, c.docno as paydocno, 'JV'
        from arinvoices a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docdate <= pi_date
        and a.docstatus in ('O','C')
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO, c.docno;




  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branch,a.objectcode,a.docno, sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as BALANCE, c.DOCDATE, c.docno as paydocno, 'JV'
        from ardownpaymentinvoices a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docdate <= pi_date
        and a.docstatus in ('O','C')
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO, c.docno;








call sp_processtime('A/R Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('Journal Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branch,d.objectcode,d.docno, sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as BALANCE, c.DOCDATE, c.docno AS paydocno, 'JV'
        from journalvouchers a, journalvoucheritems d, journalvoucheritems b, journalvouchers c
        where b.company = d.company
        and b.branch = d.branch
        and b.refno = d.docno
        and b.reftype = d.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and a.company = pi_company
        and a.branch = pi_branch
        and a.docdate <= pi_date
        and a.docstatus in ('O','C')
        and d.company = a.company
        and d.branch = a.branch
        and d.docid = a.docid
        and d.itemtype = 'C'
        AND d.reftype = ''
        AND d.accttype=''
        and (pi_custno='' or (pi_custno<>'' and d.itemno=pi_custno))
        group by d.DOCNO, c.docno;
call sp_processtime('Journal Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Journal Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branch,d.objectcode,d.docno, sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as BALANCE, c.DOCDATE, c.docno as paydocno, 'JV'
        from journalvouchers a, journalvoucheritems d, journalvoucheritems b, journalvouchers c
        where b.company = d.company
        and b.branch = d.branch
        and b.refno = d.docno
        and b.reftype = d.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and a.company = pi_company
        and a.branch = pi_branch
        and a.docdate <= pi_date
        and a.docstatus in ('O','C')
        and d.company = a.company
        and d.branch = a.branch
        and d.docid = a.docid
        and d.itemtype = 'C'
        AND d.reftype='OUTDOWNPAYMENT' and d.refno=''
        AND d.accttype=''
        and (pi_custno='' or (pi_custno<>'' and d.itemno=pi_custno))
        group by d.DOCNO, c.docno;
call sp_processtime('Advances Journal Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('A/R CM Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branch,a.objectcode,a.docno, sum(if(b.currency = v_currency, b.debit - b.credit, b.debit_fc - b.credit_fc)) as BALANCE, c.DOCDATE, c.docno as paydocno, 'JV'
        from arcreditmemos a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docstatus in ('O','C')
        and a.docdate <= pi_date
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO, c.docno;
call sp_processtime('A/R CM Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('DP/RS Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branchcode,a.objectcode,a.docno, sum(if(b.currency = v_currency, b.debit - b.credit, b.debit_fc - b.credit_fc)) as BALANCE, c.DOCDATE, c.docno as paydocno, 'JV'
        from collections a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branchcode
        and b.refno = a.docno
        and b.reftype in ('DEPOSIT','DOWNPAYMENT')
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branchcode = pi_branch
        AND a.collfor in('DP','RS')
        and  a.doctype='c' 
        and a.docstat in ('O','C')
        and a.valuedate <= pi_date
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        and a.doctype = 'C'
        group by a.DOCNO, c.docno;
call sp_processtime('DP/RS Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branchcode,a.objectcode,a.docno, sum(if(b.currency = v_currency, b.debit - b.credit, b.debit_fc - b.credit_fc))*-1 as BALANCE, c.DOCDATE, c.docno as paydocno, 'JV'
        from payments a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branchcode
        and b.refno = a.docno
        and b.reftype in ('OUTDOWNPAYMENT')
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branchcode = pi_branch
        AND a.collfor in('DP')
        and a.docstat in ('O','C')
        and a.docdate <= pi_date
        and a.doctype = 'C' 
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO, c.docno;
call sp_processtime('Advances Journals','end');
end if;
INSERT
  INTO journalpayments (COMPANY,BRANCH,DOCNO,BALANCE,DOCDATE)
     select COMPANY,BRANCH,DOCNO,sum(BALANCE),DOCDATE from settlements where type='JV' group by docno;
DROP TEMPORARY TABLE IF EXISTS `sale_return`;
CREATE TEMPORARY TABLE  `sale_return` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `BALANCE` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('Sales Returns','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
       SELECT a.company,a.branch,a.objectcode,a.docno, sum(b.BASEAMOUNT), b.DOCDATE, b.DOCNO as paydocno, 'ARCM'
        from arinvoices a, arcreditmemos b
        where b.company = a.company
        and b.branch = a.branch
        and (b.BASEDOCNO = a.docno)
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docdate <= pi_date
        and a.docstatus in ('O','C')
        and b.docdate <= pi_date
        and b.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO, b.DOCNO;



  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
       SELECT a.company,a.branch,a.objectcode,a.docno, sum(b.AMOUNT), a.DOCDATE, b.REFNO as paydocno, 'ARCM'
        from arinvoices a, arinvoicedownpaymentitems b
        where b.company = a.company
        and b.branch = a.branch
        and (b.docid = a.docid)
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docdate <= pi_date
        and a.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;



  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
       SELECT a.company,a.branch,a.objectcode,a.docno, sum(b.BASEAMOUNT), b.DOCDATE, b.DOCNO as paydocno, 'ARCM'
        from ardownpaymentinvoices a, arcreditmemos b
        where b.company = a.company
        and b.branch = a.branch
        and (b.BASEDOCNO = a.docno)
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docdate <= pi_date
        and a.docstatus in ('O','C')
        and b.docdate <= pi_date
        and b.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO, b.DOCNO;




call sp_processtime('Sales Returns','end');
end if;
INSERT
  INTO sale_return (COMPANY,BRANCH,DOCNO,BALANCE,DOCDATE)
     select COMPANY,BRANCH,DOCNO,sum(BALANCE),DOCDATE from settlements where type='ARCM' group by docno;
DROP TEMPORARY TABLE IF EXISTS `adv`;
CREATE TEMPORARY TABLE  `adv` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `OBJECTCODE` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `advancepayment` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`OBJECTCODE`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('A/R Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.objectcode,a.docno, 0 - sum(b.amount - b.penaltypaid + b.discamount) as advancepayment, c.VALUEDATE AS DOCDATE
        from arinvoices a, collectionsinvoices b, collections c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docstatus in ('O','C')
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;



  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.objectcode,a.docno, 0 - sum(b.amount - b.penaltypaid + b.discamount) as advancepayment, c.VALUEDATE AS DOCDATE
        from ardownpaymentinvoices a, collectionsinvoices b, collections c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docstatus in ('O','C')
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;







call sp_processtime('A/R Advance Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('Journal Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.objectcode,d.docno, 0 - sum(b.amount - b.penaltypaid + b.discamount) as advancepayment, c.VALUEDATE AS DOCDATE
        from journalvouchers a, journalvoucheritems d, collectionsinvoices b, collections c
        where d.company = a.company
        and d.branch = a.branch
        and d.docid = a.docid
        and b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and d.company = pi_company
        and d.branch = pi_branch
        and d.itemtype = 'C'
        AND d.reftype = ''
        AND d.accttype=''
        and a.docstatus in ('O','C')
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_custno='' or (pi_custno<>'' and d.itemno=pi_custno))
        group by d.DOCNO;
call sp_processtime('Journal Advance Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Journal Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.objectcode,d.docno, 0 - sum(b.amount - b.penaltypaid + b.discamount) as advancepayment, c.VALUEDATE AS DOCDATE
        from journalvouchers a, journalvoucheritems d, collectionsinvoices b, collections c
        where d.company = a.company
        and d.branch = a.branch
        and d.docid = a.docid
        and b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and d.company = pi_company
        and d.branch = pi_branch
        and d.itemtype = 'C'
        AND d.reftype='OUTDOWNPAYMENT' and d.refno=''
        AND d.accttype=''
        and a.docstatus in ('O','C')
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_custno='' or (pi_custno<>'' and d.itemno=pi_custno))
        group by d.DOCNO;
call sp_processtime('Advances Journal Advance Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('A/R CM Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.objectcode,a.docno, 0 - sum(b.amount - b.penaltypaid + b.discamount) as advancepayment, c.VALUEDATE AS DOCDATE
        from arcreditmemos a, collectionsinvoices b, collections c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docstatus in ('O','C')
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;
call sp_processtime('A/R CM Advance Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('DP/RS Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branchcode,a.objectcode,a.docno, 0 - sum(b.amount - b.penaltypaid + b.discamount) as advancepayment, c.VALUEDATE AS DOCDATE
        from collections a, collectionsinvoices b, collections c
        where b.company = a.company
        and b.branch = a.branchcode
        and b.refno = a.docno
        and b.reftype in ('DEPOSIT','DOWNPAYMENT')
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and a.company = pi_company
        and a.branchcode = pi_branch
        AND a.collfor in('DP','RS')
        and  a.doctype='c' 
        and a.docstat in ('O','C')
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;
call sp_processtime('DP/RS Advance Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branchcode,a.objectcode,a.docno, sum(b.amount - b.penaltypaid + b.discamount) as advancepayment, c.VALUEDATE AS DOCDATE
        from payments a, collectionsinvoices b, collections c
        where b.company = a.company
        and b.branch = a.branchcode
        and b.refno = a.docno
        and b.reftype in ('DOWNPAYMENT')
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and a.company = pi_company
        and a.branchcode = pi_branch
        AND a.collfor in('DP')
        and a.docstat in ('O','C')
        and a.doctype = 'C' 
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;
call sp_processtime('Advances Advance Payments','end');
end if;
DROP TEMPORARY TABLE IF EXISTS `advjv`;
CREATE TEMPORARY TABLE  `advjv` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `advancepayment` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('A/R Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.docno, 0 - sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as advancepayment, c.DOCDATE
        from arinvoices a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docstatus in ('O','C')
        and c.docdate > pi_date
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;




  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.docno, 0 - sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as advancepayment, c.DOCDATE
        from ardownpaymentinvoices a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docstatus in ('O','C')
        and c.docdate > pi_date
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;








call sp_processtime('A/R Advance Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('Journal Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,d.docno, 0 - sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as advancepayment, c.DOCDATE
        from journalvouchers a, journalvoucheritems d, journalvoucheritems b, journalvouchers c
        where b.company = d.company
        and b.branch = d.branch
        and b.refno = d.docno
        and b.reftype = d.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and c.docdate > pi_date
        and a.company = pi_company
        and a.branch = pi_branch
        and a.docstatus in ('O','C')
        and d.company = a.company
        and d.branch = a.branch
        and d.docid = a.docid
        and d.itemtype = 'C'
        AND d.reftype = ''
        AND d.accttype=''
        and (pi_custno='' or (pi_custno<>'' and d.itemno=pi_custno))
        group by d.DOCNO;
call sp_processtime('Journal Advance Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then 
call sp_processtime('Advances Journal Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,d.docno, 0 - sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as advancepayment, c.DOCDATE
        from journalvouchers a, journalvoucheritems d, journalvoucheritems b, journalvouchers c
        where b.company = d.company
        and b.branch = d.branch
        and b.refno = d.docno
        and b.reftype = d.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and c.docdate > pi_date
        and a.company = pi_company
        and a.branch = pi_branch
        and a.docstatus in ('O','C')
        and d.company = a.company
        and d.branch = a.branch
        and d.docid = a.docid
        and d.itemtype = 'C'
        AND d.reftype='OUTDOWNPAYMENT' and d.refno=''
        AND d.accttype=''
        and (pi_custno='' or (pi_custno<>'' and d.itemno=pi_custno))
        group by d.DOCNO;
call sp_processtime('Advances Journal Advance Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then 
call sp_processtime('A/R CM Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.docno, 0 - sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as advancepayment, c.DOCDATE
        from arcreditmemos a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docstatus in ('O','C')
        and c.docdate > pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;
call sp_processtime('A/R CM Advance Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then 
call sp_processtime('DP/RS Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branchcode,a.docno, 0 - sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as advancepayment, c.DOCDATE
        from collections a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branchcode
        and b.refno = a.docno
        and b.reftype in ('DEPOSIT','DOWNPAYMENT')
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branchcode = pi_branch
        AND a.collfor in('DP','RS')
        and  a.doctype='c' 
        and a.docstat in ('O','C')
        and c.docdate > pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;
call sp_processtime('DP/RS Advance Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then 
call sp_processtime('Advances Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branchcode,a.docno, 0 - sum(if(b.currency = v_currency, b.credit - b.debit, b.credit_fc - b.debit_fc)) as advancepayment, c.DOCDATE
        from payments a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branchcode
        and b.refno = a.docno
        and b.reftype in ('DOWNPAYMENT')
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branchcode = pi_branch
        AND a.collfor in('DP')
        and a.docstat in ('O','C')
        and a.doctype = 'C' 
        and c.docdate > pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;
call sp_processtime('Advances Advance Journals','end');
end if;
DROP TEMPORARY TABLE IF EXISTS `adv_sales_return`;
CREATE TEMPORARY TABLE  `adv_sales_return` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `advancepayment` NUMERIC(18,6) NULL default '0',
    `DOCDATE` varchar(10) NULL default '',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
if (pi_accttype='' or pi_accttype='ARACCT') then 
call sp_processtime('Advance Sales Returns','start');
  INSERT
    INTO adv_sales_return (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
       SELECT a.company,a.branch,a.docno, sum(0 - b.BASEAMOUNT), b.DOCDATE
        from arinvoices a, arcreditmemos b
        where b.company = a.company
        and b.branch = a.branch
        and (b.BASEDOCNO = a.docno)
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docdate <= pi_date
        and a.docstatus in ('O','C')
        and b.docdate > pi_date
        and b.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;



  INSERT
    INTO adv_sales_return (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
       SELECT a.company,a.branch,a.docno, sum(0 - b.BASEAMOUNT), b.DOCDATE
        from ardownpaymentinvoices a, arcreditmemos b
        where b.company = a.company
        and b.branch = a.branch
        and (b.BASEDOCNO = a.docno)
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docdate <= pi_date
        and a.docstatus in ('O','C')
        and b.docdate > pi_date
        and b.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;







call sp_processtime('Advance Sales Returns','end');
end if;
DROP TEMPORARY TABLE IF EXISTS `concatfield`;
CREATE TEMPORARY TABLE  `concatfield` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `OBJECTCODE` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `DUEAMOUNT` NUMERIC(18,6) NULL default '0',
    `advpay` NUMERIC(18,6) NULL default '0',
    `current` NUMERIC(18,6) NULL default '0',
    `D7` NUMERIC(18,6) NULL default '0',
    `D15` NUMERIC(18,6) NULL default '0',
    `D30` NUMERIC(18,6) NULL default '0',
    `UP30` NUMERIC(18,6) NULL default '0',
    `AMOUNT` NUMERIC(18,6) NULL default '0',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`OBJECTCODE`,`DOCNO`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
if (pi_accttype='' or pi_accttype='ARACCT') then 
call sp_processtime('A/R 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE,DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT arinvoices.COMPANY, arinvoices.BRANCH, arinvoices.OBJECTCODE, arinvoices.DOCNO,
        (arinvoices.totalamount - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(arinvoices.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then (arinvoices.totalamount - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if((arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        (arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if((arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then (arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if((arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then (arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if((arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then (arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if((arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d')) >= 91
        then (arinvoices.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        arinvoices.totalamount
        FROM arinvoices
        LEFT OUTER JOIN departments ON arinvoices.department = departments.department
        LEFT OUTER JOIN pdcpayments ON arinvoices.COMPANY = pdcpayments.COMPANY AND arinvoices.BRANCH = pdcpayments.BRANCH AND arinvoices.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON arinvoices.COMPANY = balances.COMPANY AND arinvoices.BRANCH = balances.BRANCH AND arinvoices.DOCNO = balances.DOCNO AND arinvoices.OBJECTCODE = balances.OBJECTCODE
        LEFT OUTER JOIN journalpayments ON arinvoices.COMPANY = journalpayments.COMPANY AND arinvoices.BRANCH = journalpayments.BRANCH AND arinvoices.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON arinvoices.COMPANY = adv.COMPANY AND arinvoices.BRANCH = adv.BRANCH AND arinvoices.OBJECTCODE = adv.OBJECTCODE AND arinvoices.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON arinvoices.COMPANY = advjv.COMPANY AND arinvoices.BRANCH = advjv.BRANCH AND arinvoices.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON arinvoices.COMPANY = sale_return.COMPANY AND arinvoices.BRANCH = sale_return.BRANCH AND arinvoices.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON arinvoices.COMPANY = adv_sales_return.COMPANY AND arinvoices.BRANCH = adv_sales_return.BRANCH AND arinvoices.DOCNO = adv_sales_return.DOCNO
        WHERE arinvoices.trxtype<>'POS' and arinvoices.accttype='' and arinvoices.docdate <= pi_date
        AND arinvoices.COMPANY = pi_company AND arinvoices.BRANCH = pi_branch
        and (pi_custno='' or (pi_custno<>'' and arinvoices.bpcode=pi_custno))
        GROUP BY arinvoices.DOCNO
        ORDER BY arinvoices.docno ASC;




INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE,DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT arinvoices.COMPANY, arinvoices.BRANCH, arinvoices.OBJECTCODE, arinvoices.DOCNO,
        (arinvoices.dpamount - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(arinvoices.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then (arinvoices.dpamount - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if((arinvoices.dpamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        (arinvoices.dpamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if((arinvoices.dpamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then (arinvoices.dpamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if((arinvoices.dpamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then (arinvoices.dpamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if((arinvoices.dpamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then (arinvoices.dpamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if((arinvoices.dpamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arinvoices.DOCDUEDATE, '%y-%m-%d')) >= 91
        then (arinvoices.dpamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        (arinvoices.dpamount) totalamount
        FROM ardownpaymentinvoices arinvoices
        LEFT OUTER JOIN departments ON arinvoices.department = departments.department
        LEFT OUTER JOIN pdcpayments ON arinvoices.COMPANY = pdcpayments.COMPANY AND arinvoices.BRANCH = pdcpayments.BRANCH AND arinvoices.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON arinvoices.COMPANY = balances.COMPANY AND arinvoices.BRANCH = balances.BRANCH AND arinvoices.DOCNO = balances.DOCNO AND arinvoices.OBJECTCODE = balances.OBJECTCODE
        LEFT OUTER JOIN journalpayments ON arinvoices.COMPANY = journalpayments.COMPANY AND arinvoices.BRANCH = journalpayments.BRANCH AND arinvoices.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON arinvoices.COMPANY = adv.COMPANY AND arinvoices.BRANCH = adv.BRANCH AND arinvoices.OBJECTCODE = adv.OBJECTCODE AND arinvoices.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON arinvoices.COMPANY = advjv.COMPANY AND arinvoices.BRANCH = advjv.BRANCH AND arinvoices.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON arinvoices.COMPANY = sale_return.COMPANY AND arinvoices.BRANCH = sale_return.BRANCH AND arinvoices.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON arinvoices.COMPANY = adv_sales_return.COMPANY AND arinvoices.BRANCH = adv_sales_return.BRANCH AND arinvoices.DOCNO = adv_sales_return.DOCNO
        WHERE arinvoices.trxtype<>'POS' and arinvoices.accttype='' and arinvoices.docdate <= pi_date
        AND arinvoices.COMPANY = pi_company AND arinvoices.BRANCH = pi_branch
        and (pi_custno='' or (pi_custno<>'' and arinvoices.bpcode=pi_custno))
        GROUP BY arinvoices.DOCNO
        ORDER BY arinvoices.docno ASC;












call sp_processtime('A/R 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('Journal 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE,DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT journalvoucheritems.COMPANY, journalvoucheritems.BRANCH, journalvoucheritems.OBJECTCODE, journalvoucheritems.DOCNO,
        ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(journalvouchers.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if(((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if(((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if(((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if(((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if(((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) >= 91
        then ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        (if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc))
        FROM journalvouchers USE INDEX (Index_aging)
        INNER JOIN journalvoucheritems USE INDEX (Index_aging)
        LEFT OUTER JOIN departments on journalvoucheritems.department = departments.department
        LEFT OUTER JOIN pdcpayments ON journalvouchers.COMPANY = pdcpayments.COMPANY AND journalvouchers.BRANCH = pdcpayments.BRANCH AND journalvoucheritems.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON journalvouchers.COMPANY = balances.COMPANY AND journalvouchers.BRANCH = balances.BRANCH AND journalvouchers.OBJECTCODE = balances.OBJECTCODE AND journalvoucheritems.DOCNO = balances.DOCNO
        LEFT OUTER JOIN journalpayments ON journalvouchers.COMPANY = journalpayments.COMPANY AND journalvouchers.BRANCH = journalpayments.BRANCH AND journalvoucheritems.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON journalvouchers.COMPANY = adv.COMPANY AND journalvouchers.BRANCH = adv.BRANCH AND journalvoucheritems.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON journalvouchers.COMPANY = advjv.COMPANY AND journalvouchers.BRANCH = advjv.BRANCH AND journalvouchers.OBJECTCODE = adv.OBJECTCODE AND journalvoucheritems.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON journalvouchers.COMPANY = sale_return.COMPANY AND journalvouchers.BRANCH = sale_return.BRANCH AND journalvoucheritems.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON journalvouchers.COMPANY = adv_sales_return.COMPANY AND journalvouchers.BRANCH = adv_sales_return.BRANCH AND journalvoucheritems.DOCNO = adv_sales_return.DOCNO
        WHERE journalvouchers.company = pi_company 
          AND journalvouchers.branch = pi_branch 
          AND journalvouchers.docdate <= pi_date
          AND journalvoucheritems.COMPANY = journalvouchers.company 
          AND journalvoucheritems.BRANCH = journalvouchers.branch
          AND journalvoucheritems.docid = journalvouchers.docid 
          AND journalvoucheritems.itemtype = 'C'
          AND journalvoucheritems.reftype = ''
          AND journalvoucheritems.accttype=''
          and (pi_custno='' or (pi_custno<>'' and journalvoucheritems.itemno=pi_custno))
        GROUP BY journalvoucheritems.DOCNO
        ORDER BY journalvoucheritems.DOCNO ASC;
call sp_processtime('Journal 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then 
call sp_processtime('Advances Journal 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE,DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT journalvoucheritems.COMPANY, journalvoucheritems.BRANCH, journalvoucheritems.OBJECTCODE, journalvoucheritems.DOCNO,
        ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(journalvouchers.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if(((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if(((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if(((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if(((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if(((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) >= 91
        then ((if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc)) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        (if(journalvoucheritems.currency = v_currency, journalvoucheritems.debit-journalvoucheritems.credit, journalvoucheritems.debit_fc-journalvoucheritems.credit_fc))
        FROM journalvoucheritems
        INNER JOIN journalvouchers ON journalvoucheritems.company = journalvouchers.company AND journalvoucheritems.branch = journalvouchers.branch AND journalvoucheritems.docid = journalvouchers.docid AND journalvouchers.docdate <= pi_date
        LEFT OUTER JOIN departments on journalvoucheritems.department = departments.department
        LEFT OUTER JOIN pdcpayments ON journalvouchers.COMPANY = pdcpayments.COMPANY AND journalvouchers.BRANCH = pdcpayments.BRANCH AND journalvoucheritems.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON journalvouchers.COMPANY = balances.COMPANY AND journalvouchers.BRANCH = balances.BRANCH AND journalvouchers.OBJECTCODE = balances.OBJECTCODE AND journalvoucheritems.DOCNO = balances.DOCNO
        LEFT OUTER JOIN journalpayments ON journalvouchers.COMPANY = journalpayments.COMPANY AND journalvouchers.BRANCH = journalpayments.BRANCH AND journalvoucheritems.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON journalvouchers.COMPANY = adv.COMPANY AND journalvouchers.BRANCH = adv.BRANCH AND journalvoucheritems.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON journalvouchers.COMPANY = advjv.COMPANY AND journalvouchers.BRANCH = advjv.BRANCH AND journalvouchers.OBJECTCODE = adv.OBJECTCODE AND journalvoucheritems.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON journalvouchers.COMPANY = sale_return.COMPANY AND journalvouchers.BRANCH = sale_return.BRANCH AND journalvoucheritems.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON journalvouchers.COMPANY = adv_sales_return.COMPANY AND journalvouchers.BRANCH = adv_sales_return.BRANCH AND journalvoucheritems.DOCNO = adv_sales_return.DOCNO
        WHERE journalvoucheritems.itemtype = 'C'
          AND journalvoucheritems.reftype='OUTDOWNPAYMENT' and journalvoucheritems.refno=''
          AND journalvoucheritems.accttype=''
          AND journalvoucheritems.COMPANY = pi_company AND journalvoucheritems.BRANCH = pi_branch
          and (pi_custno='' or (pi_custno<>'' and journalvoucheritems.itemno=pi_custno))
        GROUP BY journalvoucheritems.DOCNO
        ORDER BY journalvoucheritems.DOCNO ASC;
call sp_processtime('Advances Journal 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then 
call sp_processtime('A/R CM 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE,DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT arcreditmemos.COMPANY, arcreditmemos.BRANCH, arcreditmemos.OBJECTCODE, arcreditmemos.DOCNO,
        (arcreditmemos.totalamount - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(arcreditmemos.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then (arcreditmemos.totalamount - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if((arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        (arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if((arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then (arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if((arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then (arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if((arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then (arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if((arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(arcreditmemos.DOCDUEDATE, '%y-%m-%d')) >= 91
        then (arcreditmemos.totalamount -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        arcreditmemos.totalamount
        FROM arcreditmemos
        LEFT OUTER JOIN departments ON arcreditmemos.department = departments.department
        LEFT OUTER JOIN pdcpayments ON arcreditmemos.COMPANY = pdcpayments.COMPANY AND arcreditmemos.BRANCH = pdcpayments.BRANCH AND arcreditmemos.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON arcreditmemos.COMPANY = balances.COMPANY AND arcreditmemos.BRANCH = balances.BRANCH AND arcreditmemos.OBJECTCODE = balances.OBJECTCODE AND arcreditmemos.DOCNO = balances.DOCNO
        LEFT OUTER JOIN journalpayments ON arcreditmemos.COMPANY = journalpayments.COMPANY AND arcreditmemos.BRANCH = journalpayments.BRANCH AND arcreditmemos.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON arcreditmemos.COMPANY = adv.COMPANY AND arcreditmemos.BRANCH = adv.BRANCH AND arcreditmemos.OBJECTCODE = adv.OBJECTCODE AND arcreditmemos.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON arcreditmemos.COMPANY = advjv.COMPANY AND arcreditmemos.BRANCH = advjv.BRANCH AND arcreditmemos.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON arcreditmemos.COMPANY = sale_return.COMPANY AND arcreditmemos.BRANCH = sale_return.BRANCH AND arcreditmemos.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON arcreditmemos.COMPANY = adv_sales_return.COMPANY AND arcreditmemos.BRANCH = adv_sales_return.BRANCH AND arcreditmemos.DOCNO = adv_sales_return.DOCNO
        WHERE arcreditmemos.totalamount > 0 AND arcreditmemos.accttype = ''
          AND arcreditmemos.docdate <= pi_date
          AND arcreditmemos.COMPANY = pi_company AND arcreditmemos.BRANCH = pi_branch
          and (pi_custno='' or (pi_custno<>'' and arcreditmemos.bpcode=pi_custno))
        GROUP BY arcreditmemos.DOCNO
        ORDER BY arcreditmemos.docno ASC;
call sp_processtime('A/R CM 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then 
call sp_processtime('DP/RS 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT collections.COMPANY, collections.BRANCHCODE, collections.OBJECTCODE, collections.DOCNO,
        (collections.PAIDAMOUNT - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(collections.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then (collections.PAIDAMOUNT - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if((collections.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        (collections.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if((collections.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then (collections.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if((collections.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then (collections.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if((collections.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then (collections.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if((collections.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d')) >= 91
        then (collections.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        collections.PAIDAMOUNT
        FROM collections
        LEFT OUTER JOIN pdcpayments ON collections.COMPANY = pdcpayments.COMPANY AND collections.BRANCHCODE = pdcpayments.BRANCH AND collections.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON collections.COMPANY = balances.COMPANY AND collections.BRANCHCODE = balances.BRANCH AND collections.OBJECTCODE = balances.OBJECTCODE AND collections.DOCNO = balances.DOCNO
        LEFT OUTER JOIN journalpayments ON collections.COMPANY = journalpayments.COMPANY AND collections.BRANCHCODE = journalpayments.BRANCH AND collections.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON collections.COMPANY = adv.COMPANY AND collections.BRANCHCODE = adv.BRANCH AND collections.OBJECTCODE = adv.OBJECTCODE AND collections.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON collections.COMPANY = advjv.COMPANY AND collections.BRANCHCODE = advjv.BRANCH AND collections.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON collections.COMPANY = sale_return.COMPANY AND collections.BRANCHCODE = sale_return.BRANCH AND collections.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON collections.COMPANY = adv_sales_return.COMPANY AND collections.BRANCHCODE = adv_sales_return.BRANCH AND collections.DOCNO = adv_sales_return.DOCNO
        WHERE collections.PAIDAMOUNT > 0
AND collections.cleared<>0 
and collections.collfor in('DP')
and (collections.docstat not in ('CN','BC','D') or (collections.docstat = 'CN' and collections.pdc=0 and collections.cancelleddate > pi_date) or (collections.docstat = 'BC' and collections.cancelleddate > pi_date) )
        AND collections.valuedate <= pi_date AND collections.valuedate <> '0000-00-00'
        AND collections.COMPANY = pi_company AND collections.BRANCHCODE = pi_branch
          and (pi_custno='' or (pi_custno<>'' and collections.bpcode=pi_custno))
        GROUP BY collections.DOCNO
        ORDER BY collections.docno ASC;
call sp_processtime('DP/RS 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT payments.COMPANY, payments.BRANCHCODE, payments.OBJECTCODE, payments.DOCNO,
        (payments.PAIDAMOUNT - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment)) AS advpay,
        if(payments.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then (payments.PAIDAMOUNT - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) else
        if((payments.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE))<0,
        (payments.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)),0) end
        ,0) as 'current',
        if((payments.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE))>0,
        case when datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then (payments.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) else 0.00 end,
        0) as 'D7',
        if((payments.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE))>0,
        case when datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then (payments.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) else 0.00 end,
        0) as 'D15',
        if((payments.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE))>0,
        case when datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then (payments.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) else 0.00 end,
        0) as 'D30',
        if((payments.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE))>0,
        case when datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d')) >= 91
        then (payments.PAIDAMOUNT -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) else 0.00 end,
        0) as 'UP30',
        payments.PAIDAMOUNT
        FROM payments
        LEFT OUTER JOIN pdcpayments ON payments.COMPANY = pdcpayments.COMPANY AND payments.BRANCHCODE = pdcpayments.BRANCH AND payments.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON payments.COMPANY = balances.COMPANY AND payments.BRANCHCODE = balances.BRANCH AND payments.OBJECTCODE = balances.OBJECTCODE AND payments.DOCNO = balances.DOCNO
        LEFT OUTER JOIN journalpayments ON payments.COMPANY = journalpayments.COMPANY AND payments.BRANCHCODE = journalpayments.BRANCH AND payments.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON payments.COMPANY = adv.COMPANY AND payments.BRANCHCODE = adv.BRANCH AND payments.OBJECTCODE = adv.OBJECTCODE AND payments.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON payments.COMPANY = advjv.COMPANY AND payments.BRANCHCODE = advjv.BRANCH AND payments.DOCNO = advjv.DOCNO 
        WHERE payments.PAIDAMOUNT > 0
and payments.collfor in('DP')
and (payments.docstat not in ('BC','D') or (payments.docstat = 'CN' and payments.cancelleddate > pi_date))
        AND payments.docdate<= pi_date
        AND payments.doctype = 'C'
        AND payments.COMPANY = pi_company AND payments.BRANCHCODE = pi_branch
          and (pi_custno='' or (pi_custno<>'' and payments.bpcode=pi_custno))
        GROUP BY payments.DOCNO
        ORDER BY payments.docno ASC;
call sp_processtime('Advances 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then 
call sp_processtime('Payments as Advances 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT collections.COMPANY, collections.BRANCHCODE, collections.OBJECTCODE, collections.DOCNO,
        sum(collectionsinvoices.amount - collectionsinvoices.penaltypaid + collectionsinvoices.discamount) as DUEAMOUNT,
        0 AS advpay,
        sum(collectionsinvoices.amount - collectionsinvoices.penaltypaid + collectionsinvoices.discamount) as 'current',
        0 as 'D7',
        0 as 'D15',
        0 as 'D30',
        0 as 'UP30',
        sum(collectionsinvoices.amount + collectionsinvoices.discamount)
        FROM collections, collectionsinvoices, arinvoices
        WHERE collectionsinvoices.company = arinvoices.company
          and collectionsinvoices.branch = arinvoices.branch
          and collectionsinvoices.refno = arinvoices.docno
          and collectionsinvoices.reftype = arinvoices.objectcode
          AND arinvoices.accttype=''
          and arinvoices.docstatus in ('O','C')
          and arinvoices.docdate > pi_date
          and collectionsinvoices.company=collections.company
          and collectionsinvoices.branch=collections.branchcode
          and collectionsinvoices.docno=collections.docno
          and collections.collfor in('SI')
          and (collections.docstat not in ('CN','BC','D') or (collections.docstat = 'CN' and collections.pdc=0 and collections.cancelleddate > pi_date) or (collections.docstat = 'BC' and collections.cancelleddate > pi_date) )
        AND collections.valuedate <= pi_date and collections.valuedate<>'0000-00-00'
        AND collections.cleared = 1
        AND collections.COMPANY = pi_company AND collections.BRANCHCODE = pi_branch
          and (pi_custno='' or (pi_custno<>'' and collections.bpcode=pi_custno))
        GROUP BY collections.DOCNO
        ORDER BY collections.docno ASC;




INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT collections.COMPANY, collections.BRANCHCODE, collections.OBJECTCODE, collections.DOCNO,
        sum(collectionsinvoices.amount - collectionsinvoices.penaltypaid + collectionsinvoices.discamount) as DUEAMOUNT,
        0 AS advpay,
        sum(collectionsinvoices.amount - collectionsinvoices.penaltypaid + collectionsinvoices.discamount) as 'current',
        0 as 'D7',
        0 as 'D15',
        0 as 'D30',
        0 as 'UP30',
        sum(collectionsinvoices.amount + collectionsinvoices.discamount)
        FROM collections, collectionsinvoices, ardownpaymentinvoices arinvoices
        WHERE collectionsinvoices.company = arinvoices.company
          and collectionsinvoices.branch = arinvoices.branch
          and collectionsinvoices.refno = arinvoices.docno
          and collectionsinvoices.reftype = arinvoices.objectcode
          AND arinvoices.accttype=''
          and arinvoices.docstatus in ('O','C')
          and arinvoices.docdate > pi_date
          and collectionsinvoices.company=collections.company
          and collectionsinvoices.branch=collections.branchcode
          and collectionsinvoices.docno=collections.docno
          and collections.collfor in('SI')
          and (collections.docstat not in ('CN','BC','D') or (collections.docstat = 'CN' and collections.pdc=0 and collections.cancelleddate > pi_date) or (collections.docstat = 'BC' and collections.cancelleddate > pi_date) )
        AND collections.valuedate <= pi_date and collections.valuedate<>'0000-00-00'
        AND collections.cleared = 1
        AND collections.COMPANY = pi_company AND collections.BRANCHCODE = pi_branch
          and (pi_custno='' or (pi_custno<>'' and collections.bpcode=pi_custno))
        GROUP BY collections.DOCNO
        ORDER BY collections.docno ASC;










call sp_processtime('Payments as Advances 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then 
call sp_processtime('A/R Journals as Advances 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT c.COMPANY, c.BRANCH, c.OBJECTCODE, c.DOCNO,
        sum(if(b.currency = v_currency, b.debit - b.credit, b.debit_fc - b.credit_fc)) as DUEAMOUNT,
        0 AS advpay,
        sum(if(b.currency = v_currency, b.debit - b.credit, b.debit_fc - b.credit_fc)) as 'current',
        0 as 'D7',
        0 as 'D15',
        0 as 'D30',
        0 as 'UP30',
        sum(if(b.currency = v_currency, b.debit , b.debit_fc))
    from arinvoices a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docdate > pi_date
        and a.docstatus in ('O','C')
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by c.DOCNO;




INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT c.COMPANY, c.BRANCH, c.OBJECTCODE, c.DOCNO,
        sum(if(b.currency = v_currency, b.debit - b.credit, b.debit_fc - b.credit_fc)) as DUEAMOUNT,
        0 AS advpay,
        sum(if(b.currency = v_currency, b.debit - b.credit, b.debit_fc - b.credit_fc)) as 'current',
        0 as 'D7',
        0 as 'D15',
        0 as 'D30',
        0 as 'UP30',
        sum(if(b.currency = v_currency, b.debit, b.debit_fc))
    from ardownpaymentinvoices a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branch
        and b.refno = a.docno
        and b.reftype = a.objectcode
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branch = pi_branch
        AND a.accttype=''
        and a.docdate > pi_date
        and a.docstatus in ('O','C')
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by c.DOCNO;








call sp_processtime('A/R Journals as Advances 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('Advances Journals as Advances','start');
  INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
        SELECT a.company,a.branchcode, c.OBJECTCODE,c.docno, sum(if(b.currency = v_currency, b.debit - b.credit, b.debit_fc - b.credit_fc)) as DUEAMOUNT,0 AS advpay,
        sum(if(b.currency = v_currency, b.debit - b.credit, b.debit_fc - b.credit_fc)) as 'current',
        0 as 'D7',
        0 as 'D15',
        0 as 'D30',
        0 as 'UP30',
        sum(if(b.currency = v_currency, b.debit, b.debit_fc))
        from payments a, journalvoucheritems b, journalvouchers c
        where b.company = a.company
        and b.branch = a.branchcode
        and b.refno = a.docno
        and b.reftype in ('OUTDOWNPAYMENT')
        and c.company = b.company
        and c.branch = b.branch
        and c.docid = b.docid
        and a.company = pi_company
        and a.branchcode = pi_branch
        AND a.collfor in('DP')
        and a.docstat in ('O','C')
        and a.docdate > pi_date
        and a.doctype = 'C' 
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_custno='' or (pi_custno<>'' and a.bpcode=pi_custno))
        group by a.DOCNO;
call sp_processtime('Advances Journals as Advances','end');
end if;
DROP TEMPORARY TABLE IF EXISTS `mainfield`;
CREATE TEMPORARY TABLE  `mainfield` (
    `COMPANY` varchar(100) NULL default '',
    `BRANCH` varchar(100) NULL default '',
    `BRANCHNAME` varchar(100) NULL default '',
    `BPCODE` varchar(100) NULL default '',
    `BPNAME` varchar(500) NULL default '',
    `CURRENCY` varchar(30) NULL default '',
    `CURRENCYRATE` NUMERIC(18,6) NULL default '1',
    `BILLTOADDRESS` varchar(1000) NULL default '',
    `ITEMCODE` varchar(100) NULL default '',
    `OBJECTCODE` varchar(30) NULL default '',
    `TRXTYPE` varchar(30) NULL default '',
    `DOCNO` varchar(100) NULL default '',
    `BPREFNO` varchar(100) NULL default '',
    `PPFNO` varchar(30) NULL default '',
    `PPFNO2` varchar(30) NULL default '',
    `DOCDATE` DATE NULL,
    `DOCDUEDATE` DATE NULL,
    `DUEAMOUNT` NUMERIC(18,6) NULL default '0',
    `aging` NUMERIC(18,6) NULL default '0',
    `fisrtmonth` DATE NULL,
    `secondndmonth` DATE NULL,
    `thirdmonth` DATE NULL,
    `advpay` NUMERIC(18,6) NULL default '0',
    `current` NUMERIC(18,6) NULL default '0',
    `D7` NUMERIC(18,6) NULL default '0',
    `D15` NUMERIC(18,6) NULL default '0',
    `D30` NUMERIC(18,6) NULL default '0',
    `UP30` NUMERIC(18,6) NULL default '0',
    `PDC` NUMERIC(18,6) NULL default '0',
    `cname` varchar(100) NULL default '',
    `AMOUNT` NUMERIC(18,6) NULL default '0',
    `SEQ` INT NULL default '0',
    `PAYDOCNO` varchar(30) NULL default '',
    `PAYDOCDATE` DATE NULL,
    INDEX `Index_ObjectCode` (`OBJECTCODE`,`SEQ`),
    INDEX `Index_Docno` (`COMPANY`,`BRANCH`,`OBJECTCODE`,`DOCNO`,`SEQ`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('A/R Aging 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,CURRENCYRATE,BILLTOADDRESS,ITEMCODE,OBJECTCODE,TRXTYPE,DOCNO,BPREFNO,PPFNO,PPFNO2,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT arinvoices.COMPANY,
        arinvoices.BRANCH,
        v_branchname as BRANCHNAME,
        arinvoices.BPCODE,
        arinvoices.BPNAME,
        arinvoices.CURRENCY,
        arinvoices.CURRENCYRATE,
        arinvoices.BILLTOADDRESS,
        arinvoiceitems.ITEMCODE,
        arinvoices.OBJECTCODE,
        arinvoices.TRXTYPE,
        arinvoices.DOCNO,
        arinvoices.BPREFNO,
        arinvoices.PPFNO,
        arinvoices.PPFNO2,
        date(if(arinvoices.DOCDATE is null, now(), arinvoices.DOCDATE)) as DOCDATE,
        date(if(arinvoices.DOCDUEDATE is null, now(), arinvoices.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(arinvoices.docdate, INTERVAL 0 MONTH)),1), '%y-%m-%d')) AS aging,
        date(pi_date) - interval 0 month as 1month,
        date(pi_date) - interval 1 month as 2month,
        date(pi_date) - interval 2 month as 3month,
        concatfield.advpay,
        concatfield.current,
        concatfield.D7 as '7',
        concatfield.D15 as '15',
        concatfield.D30 as '30',
        concatfield.UP30 as 'UP30',
        if (pdcpayments.pdcamount is null, 0, pdcpayments.pdcamount) AS 'PDC',
        v_companyname as cname,
        concatfield.AMOUNT
        FROM arinvoices
        LEFT OUTER JOIN arinvoiceitems ON arinvoices.BRANCH = arinvoiceitems.BRANCH AND arinvoices.COMPANY = arinvoiceitems.COMPANY AND arinvoices.DOCID = arinvoiceitems.DOCID
        LEFT OUTER JOIN departments ON arinvoices.department = departments.department
        LEFT OUTER JOIN pdcpayments ON arinvoices.COMPANY = pdcpayments.COMPANY AND arinvoices.BRANCH = pdcpayments.BRANCH AND arinvoices.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON arinvoices.COMPANY = concatfield.COMPANY AND arinvoices.BRANCH = concatfield.BRANCH
                        AND arinvoices.OBJECTCODE = concatfield.OBJECTCODE AND arinvoices.DOCNO = concatfield.DOCNO
        WHERE arinvoices.trxtype<>'POS'
          and arinvoices.accttype=''
          and arinvoices.docdate <= pi_date
          AND arinvoices.COMPANY = pi_company
          AND arinvoices.BRANCH = pi_branch
          and arinvoices.docstatus in ('O','C')
          AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          and (pi_custno='' or (pi_custno<>'' and arinvoices.bpcode=pi_custno))
          and (pi_currency='' or (pi_currency<>'' and arinvoices.currency=pi_currency))
        GROUP BY arinvoices.DOCNO
        ORDER BY arinvoices.docno ASC;




INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,CURRENCYRATE,BILLTOADDRESS,ITEMCODE,OBJECTCODE,TRXTYPE,DOCNO,BPREFNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT arinvoices.COMPANY,
        arinvoices.BRANCH,
        v_branchname as BRANCHNAME,
        arinvoices.BPCODE,
        arinvoices.BPNAME,
        arinvoices.CURRENCY,
        arinvoices.CURRENCYRATE,
        arinvoices.BILLTOADDRESS,
        arinvoiceitems.ITEMCODE,
        arinvoices.OBJECTCODE,
        arinvoices.TRXTYPE,
        arinvoices.DOCNO,
        arinvoices.BPREFNO,
        date(if(arinvoices.DOCDATE is null, now(), arinvoices.DOCDATE)) as DOCDATE,
        date(if(arinvoices.DOCDUEDATE is null, now(), arinvoices.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(arinvoices.docdate, INTERVAL 0 MONTH)),1), '%y-%m-%d')) AS aging,
        date(pi_date) - interval 0 month as 1month,
        date(pi_date) - interval 1 month as 2month,
        date(pi_date) - interval 2 month as 3month,
        concatfield.advpay,
        concatfield.current,
        concatfield.D7 as '7',
        concatfield.D15 as '15',
        concatfield.D30 as '30',
        concatfield.UP30 as 'UP30',
        if (pdcpayments.pdcamount is null, 0, pdcpayments.pdcamount) AS 'PDC',
        v_companyname as cname,
        concatfield.AMOUNT
        FROM ardownpaymentinvoices arinvoices
        LEFT OUTER JOIN ardownpaymentinvoiceitems arinvoiceitems ON arinvoices.BRANCH = arinvoiceitems.BRANCH AND arinvoices.COMPANY = arinvoiceitems.COMPANY AND arinvoices.DOCID = arinvoiceitems.DOCID
        LEFT OUTER JOIN departments ON arinvoices.department = departments.department
        LEFT OUTER JOIN pdcpayments ON arinvoices.COMPANY = pdcpayments.COMPANY AND arinvoices.BRANCH = pdcpayments.BRANCH AND arinvoices.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON arinvoices.COMPANY = concatfield.COMPANY AND arinvoices.BRANCH = concatfield.BRANCH
                        AND arinvoices.OBJECTCODE = concatfield.OBJECTCODE AND arinvoices.DOCNO = concatfield.DOCNO
        WHERE arinvoices.trxtype<>'POS'
          and arinvoices.accttype=''
          and arinvoices.docdate <= pi_date
          AND arinvoices.COMPANY = pi_company
          AND arinvoices.BRANCH = pi_branch
          and arinvoices.docstatus in ('O','C')
          AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          and (pi_custno='' or (pi_custno<>'' and arinvoices.bpcode=pi_custno))
          and (pi_currency='' or (pi_currency<>'' and arinvoices.currency=pi_currency))
        GROUP BY arinvoices.DOCNO
        ORDER BY arinvoices.docno ASC;








call sp_processtime('A/R Aging 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('A/R CM 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,CURRENCYRATE,BILLTOADDRESS,ITEMCODE,OBJECTCODE,TRXTYPE,DOCNO,BPREFNO,PPFNO,PPFNO2,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT arcreditmemos.COMPANY,
        arcreditmemos.BRANCH,
        v_branchname as BRANCHNAME,
        arcreditmemos.BPCODE,
        arcreditmemos.BPNAME,
        arcreditmemos.CURRENCY,
        arcreditmemos.CURRENCYRATE,
        arcreditmemos.BILLTOADDRESS,
        arcreditmemoitems.ITEMCODE,
        arcreditmemos.OBJECTCODE,
        arcreditmemos.TRXTYPE,
        arcreditmemos.DOCNO,
        arcreditmemos.BPREFNO,
        arcreditmemos.PPFNO,
        arcreditmemos.PPFNO2,
        date(if(arcreditmemos.DOCDATE is null, now(), arcreditmemos.DOCDATE)) as DOCDATE,
        date(if(arcreditmemos.DOCDUEDATE is null, now(), arcreditmemos.DOCDUEDATE)) as DOCDUEDATE,
        0-(concatfield.DUEAMOUNT),
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(arcreditmemos.docdate, INTERVAL 0 MONTH)),1), '%y-%m-%d')) AS aging,
        date(pi_date) - interval 0 month as 1month,
        date(pi_date) - interval 1 month as 2month,
        date(pi_date) - interval 2 month as 3month,
        0-(concatfield.advpay),
        0-(concatfield.current),
        0-(concatfield.D7) as '7',
        0-(concatfield.D15) as '15',
        0-(concatfield.D30) as '30',
        0-(concatfield.UP30) as 'UP30',
        0-(if (pdcpayments.pdcamount is null, 0, pdcpayments.pdcamount)) AS 'PDC',
        v_companyname as cname,
        0-(concatfield.AMOUNT)
        FROM arcreditmemos
        LEFT OUTER JOIN arcreditmemoitems ON arcreditmemos.BRANCH = arcreditmemoitems.BRANCH AND arcreditmemos.COMPANY = arcreditmemoitems.COMPANY AND arcreditmemos.DOCID = arcreditmemoitems.DOCID
        LEFT OUTER JOIN departments ON arcreditmemos.department = departments.department
        LEFT OUTER JOIN pdcpayments ON arcreditmemos.COMPANY = pdcpayments.COMPANY AND arcreditmemos.BRANCH = pdcpayments.BRANCH AND arcreditmemos.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON arcreditmemos.COMPANY = concatfield.COMPANY AND arcreditmemos.BRANCH = concatfield.BRANCH
                        AND arcreditmemos.OBJECTCODE = concatfield.OBJECTCODE AND arcreditmemos.DOCNO = concatfield.DOCNO
        WHERE arcreditmemos.totalamount > 0 AND arcreditmemos.accttype = ''
        AND arcreditmemos.docdate <= pi_date
        AND arcreditmemos.COMPANY = pi_company
        AND arcreditmemos.BRANCH = pi_branch
        and arcreditmemos.docstatus in ('O','C')
        AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          and (pi_custno='' or (pi_custno<>'' and arcreditmemos.bpcode=pi_custno))
          and (pi_currency='' or (pi_currency<>'' and arcreditmemos.currency=pi_currency))
        GROUP BY arcreditmemos.DOCNO
        ORDER BY arcreditmemos.docno ASC;
call sp_processtime('A/R CM 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('Journal 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,CURRENCYRATE,BILLTOADDRESS,ITEMCODE,OBJECTCODE,TRXTYPE,DOCNO,BPREFNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT journalvouchers.COMPANY,
        journalvouchers.BRANCH,
        v_branchname as BRANCHNAME,
        journalvoucheritems.itemno as BPCODE,
        journalvoucheritems.itemname as BPNAME,
        journalvoucheritems.CURRENCY,
        journalvoucheritems.CURRENCYRATE,
        '' as BILLTOADDRESS,
        '' as ITEMCODE,
        journalvoucheritems.OBJECTCODE,
        '' AS TRXTYPE,
        journalvouchers.DOCNO,
        journalvoucheritems.DOCNO,
        date(if(journalvouchers.DOCDATE is null, now(), journalvouchers.DOCDATE)) as DOCDATE,
        date(if(journalvouchers.DOCDUEDATE is null, now(), journalvouchers.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(journalvouchers.docdate, INTERVAL 0 MONTH)),1), '%y-%m-%d')) AS aging,
        date(pi_date) - interval 0 month as 1month,
        date(pi_date) - interval 1 month as 2month,
        date(pi_date) - interval 2 month as 3month,
        concatfield.advpay,
        concatfield.current,
        concatfield.D7 as '7',
        concatfield.D15 as '15',
        concatfield.D30 as '30',
        concatfield.UP30 as 'UP30',
        if (pdcpayments.pdcamount is null, 0, pdcpayments.pdcamount) AS 'PDC',
        v_companyname as cname,
        concatfield.AMOUNT
        FROM journalvoucheritems
        INNER JOIN journalvouchers ON journalvoucheritems.company = journalvouchers.company
        AND journalvoucheritems.branch = journalvouchers.branch
        AND journalvoucheritems.docid = journalvouchers.docid
        AND journalvouchers.docdate <= pi_date
        AND journalvouchers.docstatus in ('O','C')
        LEFT OUTER JOIN departments on journalvoucheritems.department = departments.department
        LEFT OUTER JOIN pdcpayments ON journalvouchers.COMPANY = pdcpayments.COMPANY AND journalvouchers.BRANCH = pdcpayments.BRANCH AND journalvouchers.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON journalvouchers.COMPANY = concatfield.COMPANY AND journalvouchers.BRANCH = concatfield.BRANCH
                        AND journalvoucheritems.OBJECTCODE = concatfield.OBJECTCODE AND journalvoucheritems.DOCNO = concatfield.DOCNO
        WHERE journalvoucheritems.itemtype = 'C'
           AND journalvoucheritems.reftype = ''
          AND journalvoucheritems.accttype=''
          AND journalvoucheritems.COMPANY = pi_company AND journalvoucheritems.BRANCH = pi_branch
        and (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          and (pi_custno='' or (pi_custno<>'' and journalvoucheritems.itemno=pi_custno))
          and (pi_currency='' or (pi_currency<>'' and journalvoucheritems.currency=pi_currency))
        GROUP BY journalvoucheritems.DOCNO
        ORDER BY journalvoucheritems.docno ASC;
call sp_processtime('Journal 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Journal 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,CURRENCYRATE,BILLTOADDRESS,ITEMCODE,OBJECTCODE,TRXTYPE,DOCNO,BPREFNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT journalvouchers.COMPANY,
        journalvouchers.BRANCH,
        v_branchname as BRANCHNAME,
        journalvoucheritems.itemno as BPCODE,
        journalvoucheritems.itemname as BPNAME,
        journalvoucheritems.CURRENCY,
        journalvoucheritems.CURRENCYRATE,
        '' as BILLTOADDRESS,
        '' as ITEMCODE,
        journalvoucheritems.OBJECTCODE,
        '' AS TRXTYPE,
        journalvouchers.DOCNO,
        journalvoucheritems.DOCNO,
        date(if(journalvouchers.DOCDATE is null, now(), journalvouchers.DOCDATE)) as DOCDATE,
        date(if(journalvouchers.DOCDUEDATE is null, now(), journalvouchers.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(journalvouchers.docdate, INTERVAL 0 MONTH)),1), '%y-%m-%d')) AS aging,
        date(pi_date) - interval 0 month as 1month,
        date(pi_date) - interval 1 month as 2month,
        date(pi_date) - interval 2 month as 3month,
        concatfield.advpay,
        concatfield.current,
        concatfield.D7 as '7',
        concatfield.D15 as '15',
        concatfield.D30 as '30',
        concatfield.UP30 as 'UP30',
        if (pdcpayments.pdcamount is null, 0, pdcpayments.pdcamount) AS 'PDC',
        v_companyname as cname,
        concatfield.AMOUNT
        FROM journalvoucheritems
        INNER JOIN journalvouchers ON journalvoucheritems.company = journalvouchers.company
        AND journalvoucheritems.branch = journalvouchers.branch
        AND journalvoucheritems.docid = journalvouchers.docid
        AND journalvouchers.docdate <= pi_date
        AND journalvouchers.docstatus in ('O','C')
        LEFT OUTER JOIN departments on journalvoucheritems.department = departments.department
        LEFT OUTER JOIN pdcpayments ON journalvouchers.COMPANY = pdcpayments.COMPANY AND journalvouchers.BRANCH = pdcpayments.BRANCH AND journalvouchers.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON journalvouchers.COMPANY = concatfield.COMPANY AND journalvouchers.BRANCH = concatfield.BRANCH
                        AND journalvoucheritems.OBJECTCODE = concatfield.OBJECTCODE AND journalvoucheritems.DOCNO = concatfield.DOCNO
        WHERE journalvoucheritems.itemtype = 'C'
           AND journalvoucheritems.reftype='OUTDOWNPAYMENT' and journalvoucheritems.refno=''
          AND journalvoucheritems.accttype=''
          AND journalvoucheritems.COMPANY = pi_company AND journalvoucheritems.BRANCH = pi_branch
        and (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          and (pi_custno='' or (pi_custno<>'' and journalvoucheritems.itemno=pi_custno))
          and (pi_currency='' or (pi_currency<>'' and journalvoucheritems.currency=pi_currency))
        GROUP BY journalvoucheritems.DOCNO
        ORDER BY journalvoucheritems.docno ASC;
call sp_processtime('Advances Journal 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('DP/RS 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,CURRENCYRATE,BILLTOADDRESS,ITEMCODE,OBJECTCODE,TRXTYPE,DOCNO,BPREFNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT collections.COMPANY,
        collections.BRANCHCODE,
        v_branchname as BRANCHNAME,
        collections.BPCODE,
        collections.BPNAME,
        collections.CURRENCY,
        collections.CURRENCYRATE,
        collections.ADDRESS,
        "",
        collections.OBJECTCODE,
        '',
        collections.DOCNO,
        collections.REFNO,
        date(if(collections.DOCDATE is null, now(), collections.DOCDATE)) as DOCDATE,
        date(if(collections.DOCDUEDATE is null, now(), collections.DOCDUEDATE)) as DOCDUEDATE,
        0-(concatfield.DUEAMOUNT),
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(collections.docdate, INTERVAL 0 MONTH)),1), '%y-%m-%d')) AS aging,
        date(pi_date) - interval 0 month as 1month,
        date(pi_date) - interval 1 month as 2month,
        date(pi_date) - interval 2 month as 3month,
        0-(concatfield.advpay),
        0-(concatfield.current),
        0-(concatfield.D7) as '7',
        0-(concatfield.D15) as '15',
        0-(concatfield.D30) as '30',
        0-(concatfield.UP30) as 'UP30',
        0-(if (pdcpayments.pdcamount is null, 0, pdcpayments.pdcamount)) AS 'PDC',
        v_companyname as cname,
        0-(concatfield.AMOUNT)
        FROM collections
        LEFT OUTER JOIN pdcpayments ON collections.COMPANY = pdcpayments.COMPANY AND collections.BRANCHCODE = pdcpayments.BRANCH AND collections.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON collections.COMPANY = concatfield.COMPANY AND collections.BRANCHCODE = concatfield.BRANCH
                        AND collections.OBJECTCODE = concatfield.OBJECTCODE AND collections.DOCNO = concatfield.DOCNO
        WHERE collections.PAIDAMOUNT > 0 AND collections.cleared<>0 AND collections.collfor in('DP')
         and (collections.docstat not in ('CN','BC','D') or (collections.docstat = 'CN' and collections.pdc=0 and collections.cancelleddate > pi_date) or (collections.docstat = 'BC' and collections.cancelleddate > pi_date) )
        AND collections.valuedate <= pi_date AND collections.valuedate <> '0000-00-00'
        AND collections.COMPANY = pi_company
        and  collections.doctype='c' 
        AND collections.BRANCHCODE = pi_branch
         AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          and (pi_custno='' or (pi_custno<>'' and collections.bpcode=pi_custno))
          and (pi_currency='' or (pi_currency<>'' and collections.currency=pi_currency))
        GROUP BY collections.DOCNO
        ORDER BY collections.docno ASC;
call sp_processtime('DP/RS 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,CURRENCYRATE,BILLTOADDRESS,ITEMCODE,OBJECTCODE,TRXTYPE,DOCNO,BPREFNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT payments.COMPANY,
        payments.BRANCHCODE,
        v_branchname as BRANCHNAME,
        payments.BPCODE,
        payments.BPNAME,
        payments.CURRENCY,
        payments.CURRENCYRATE,
        payments.ADDRESS,
        "",
        payments.OBJECTCODE,
        '',
        payments.DOCNO,
        payments.REFNO,
        date(if(payments.DOCDATE is null, now(), payments.DOCDATE)) as DOCDATE,
        date(if(payments.DOCDUEDATE is null, now(), payments.DOCDUEDATE)) as DOCDUEDATE,
        (concatfield.DUEAMOUNT),
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(payments.docdate, INTERVAL 0 MONTH)),1), '%y-%m-%d')) AS aging,
        date(pi_date) - interval 0 month as 1month,
        date(pi_date) - interval 1 month as 2month,
        date(pi_date) - interval 2 month as 3month,
        (concatfield.advpay),
        (concatfield.current),
        (concatfield.D7) as '7',
        (concatfield.D15) as '15',
        (concatfield.D30) as '30',
        (concatfield.UP30) as 'UP30',
        (if (pdcpayments.pdcamount is null, 0, pdcpayments.pdcamount)) AS 'PDC',
        v_companyname as cname,
        (concatfield.AMOUNT)
        FROM payments
        LEFT OUTER JOIN pdcpayments ON payments.COMPANY = pdcpayments.COMPANY AND payments.BRANCHCODE = pdcpayments.BRANCH AND payments.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON payments.COMPANY = concatfield.COMPANY AND payments.BRANCHCODE = concatfield.BRANCH
                        AND payments.OBJECTCODE = concatfield.OBJECTCODE AND payments.DOCNO = concatfield.DOCNO
        WHERE payments.PAIDAMOUNT > 0 AND payments.collfor in('DP')
         and (payments.docstat not in ('CN','D') or (payments.docstat = 'CN' and payments.cancelleddate > pi_date) or (payments.docstat = 'BC' and payments.cancelleddate > pi_date) )
        AND payments.docdate <= pi_date
        AND payments.doctype = 'C'
        AND payments.COMPANY = pi_company
        AND payments.BRANCHCODE = pi_branch
         AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          and (pi_custno='' or (pi_custno<>'' and payments.bpcode=pi_custno))
          and (pi_currency='' or (pi_currency<>'' and payments.currency=pi_currency))
        GROUP BY payments.DOCNO
        ORDER BY payments.docno ASC;
call sp_processtime('Advances 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then 
call sp_processtime('Payments as Advances 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,CURRENCYRATE,BILLTOADDRESS,ITEMCODE,OBJECTCODE,TRXTYPE,DOCNO,BPREFNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT collections.COMPANY,
        collections.BRANCHCODE,
        v_branchname as BRANCHNAME,
        collections.BPCODE,
        collections.BPNAME,
        collections.CURRENCY,
        collections.CURRENCYRATE,
        collections.ADDRESS,
        "",
        collections.OBJECTCODE,
        '',
        collections.DOCNO,
        collections.REFNO,
        date(if(collections.DOCDATE is null, now(), collections.DOCDATE)) as DOCDATE,
        date(if(collections.DOCDUEDATE is null, now(), collections.DOCDUEDATE)) as DOCDUEDATE,
        0-(concatfield.DUEAMOUNT),
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(collections.docdate, INTERVAL 0 MONTH)),1), '%y-%m-%d')) AS aging,
        date(pi_date) - interval 0 month as 1month,
        date(pi_date) - interval 1 month as 2month,
        date(pi_date) - interval 2 month as 3month,
        0-(concatfield.advpay),
        0-(concatfield.current),
        0-(concatfield.D7) as '7',
        0-(concatfield.D15) as '15',
        0-(concatfield.D30) as '30',
        0-(concatfield.UP30) as 'UP30',
        0 AS 'PDC',
        v_companyname as cname,
        0-(concatfield.AMOUNT)
        FROM collections
        LEFT OUTER JOIN concatfield ON collections.COMPANY = concatfield.COMPANY AND collections.BRANCHCODE = concatfield.BRANCH
                        AND collections.OBJECTCODE = concatfield.OBJECTCODE AND collections.DOCNO = concatfield.DOCNO
        WHERE collections.collfor in ('SI')
        AND collections.valuedate <= pi_date and collections.valuedate<>'0000-00-00'
        AND collections.COMPANY = pi_company
        AND collections.BRANCHCODE = pi_branch
        and (collections.docstat not in ('CN','BC','D') or (collections.docstat = 'CN' and collections.pdc=0 and collections.cancelleddate > pi_date) or (collections.docstat = 'BC' and collections.cancelleddate > pi_date) )
         AND concatfield.DUEAMOUNT<>0
        and (pi_custno='' or (pi_custno<>'' and collections.bpcode=pi_custno))
          and (pi_currency='' or (pi_currency<>'' and collections.currency=pi_currency))
        GROUP BY collections.DOCNO
        ORDER BY collections.docno ASC;
call sp_processtime('Payments as Advances 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then 
call sp_processtime('Journals as Advances 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,CURRENCYRATE,BILLTOADDRESS,ITEMCODE,OBJECTCODE,TRXTYPE,DOCNO,BPREFNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT journalvouchers.COMPANY,
        journalvouchers.BRANCH,
        v_branchname as BRANCHNAME,
        journalvoucheritems.itemno as BPCODE,
        journalvoucheritems.itemname as BPNAME,
        journalvoucheritems.CURRENCY,
        journalvoucheritems.CURRENCYRATE,
        '' as BILLTOADDRESS,
        '' as ITEMCODE,
        journalvouchers.OBJECTCODE,
        '',
        journalvouchers.DOCNO,
        '',
        date(if(journalvouchers.DOCDATE is null, now(), journalvouchers.DOCDATE)) as DOCDATE,
        date(if(journalvouchers.DOCDUEDATE is null, now(), journalvouchers.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(journalvouchers.docdate, INTERVAL 0 MONTH)),1), '%y-%m-%d')) AS aging,
        date(pi_date) - interval 0 month as 1month,
        date(pi_date) - interval 1 month as 2month,
        date(pi_date) - interval 2 month as 3month,
        concatfield.advpay,
        concatfield.current,
        concatfield.D7 as '7',
        concatfield.D15 as '15',
        concatfield.D30 as '30',
        concatfield.UP30 as 'UP30',
        0 AS 'PDC',
        v_companyname as cname,
        concatfield.AMOUNT
        FROM journalvoucheritems USE INDEX (Index_aging)
        INNER JOIN journalvouchers USE INDEX (Index_aging2) 
        LEFT OUTER JOIN departments on journalvoucheritems.department = departments.department
        LEFT OUTER JOIN concatfield ON concatfield.COMPANY = journalvouchers.COMPANY  AND concatfield.BRANCH = journalvouchers.BRANCH AND concatfield.OBJECTCODE = journalvouchers.OBJECTCODE  AND concatfield.DOCNO = journalvouchers.DOCNO  
        WHERE journalvouchers.company = pi_company
          AND journalvouchers.branch = pi_branch
          AND journalvouchers.docdate <= pi_date
          AND journalvouchers.docstatus in ('O','C')
          AND journalvoucheritems.COMPANY = journalvouchers.company 
          AND journalvoucheritems.BRANCH = journalvouchers.branch 
          AND journalvoucheritems.docid = journalvouchers.docid
          AND journalvoucheritems.itemtype = 'C'
          AND (journalvoucheritems.reftype not in ('') and journalvoucheritems.refno not in (''))
          AND journalvoucheritems.accttype=''
          and (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          and (pi_custno='' or (pi_custno<>'' and journalvoucheritems.itemno=pi_custno))
          and (pi_currency='' or (pi_currency<>'' and journalvoucheritems.currency=pi_currency))
        GROUP BY journalvoucheritems.DOCNO
        ORDER BY journalvoucheritems.docno ASC;
call sp_processtime('Journals as Advances 2/2','end');
end if;
call sp_processtime('end','');
if pi_output='' then

    SELECT
      a.COMPANY,
      a.cname AS 'COMPANYNAME',
      a.BRANCH,
      a.BRANCHNAME,
      a.BPCODE,
      a.BPNAME,
      concat(a.BPNAME,' (',a.BPCODE,')') as BPNAMECODE,
      a.CURRENCY,
      a.BILLTOADDRESS,
      a.DOCNO,
      a.PPFNO,
      a.PPFNO2,
      a.DOCDATE,
      a.DOCDUEDATE,
      a.AMOUNT,
      a.DUEAMOUNT,
      a.AGING,
      if(a.DUEAMOUNT < 0, a.DUEAMOUNT, a.current) as current,
      if(a.DUEAMOUNT < 0, 0, a.D7) as 'D7',
      if(a.DUEAMOUNT < 0, 0, a.D15) as 'D15',
      if(a.DUEAMOUNT < 0, 0, a.D30) as 'D30',
      if(a.DUEAMOUNT < 0, 0, a.UP30) as 'UP30',
      a.advpay,
      a.PDC,
      date(pi_date) as date1,
      Last_day(date(pi_date) - interval 1 month) date2,
      Last_day(date(pi_date) - interval 2 month) date3,
      pt.paymenttermNAME as term,
      ct.CREDITLIMIT as creditlimit,
      ct.phone1,
      sp.salespersonname
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
         LEFT OUTER JOIN salespersons sp ON ct.salesperson = sp.salesperson
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
          and (pi_salesperson='' or (pi_salesperson<>'' and sp.salesperson = pi_salesperson))
          and (pi_currency='' or (pi_currency<>'' and a.currency=pi_currency))
    ORDER BY DOCDATE, DOCNO;

  elseif pi_output='paymentdetails' then
DROP TEMPORARY TABLE IF EXISTS `mainfield2`;
CREATE TEMPORARY TABLE  `mainfield2` (
    `COMPANY` varchar(100) NULL default '',
    `BRANCH` varchar(100) NULL default '',
    `BRANCHNAME` varchar(100) NULL default '',
    `BPCODE` varchar(100) NULL default '',
    `BPNAME` varchar(500) NULL default '',
    `CURRENCY` varchar(30) NULL default '',
    `BILLTOADDRESS` varchar(1000) NULL default '',
    `ITEMCODE` varchar(100) NULL default '',
    `OBJECTCODE` varchar(30) NULL default '',
    `DOCNO` varchar(100) NULL default '',
    `PPFNO` varchar(30) NULL default '',
    `PPFNO2` varchar(30) NULL default '',
    `DOCDATE` DATE NULL,
    `DOCDUEDATE` DATE NULL,
    `DUEAMOUNT` NUMERIC(18,6) NULL default '0',
    `aging` NUMERIC(18,6) NULL default '0',
    `fisrtmonth` DATE NULL,
    `secondndmonth` DATE NULL,
    `thirdmonth` DATE NULL,
    `advpay` NUMERIC(18,6) NULL default '0',
    `current` NUMERIC(18,6) NULL default '0',
    `D7` NUMERIC(18,6) NULL default '0',
    `D15` NUMERIC(18,6) NULL default '0',
    `D30` NUMERIC(18,6) NULL default '0',
    `UP30` NUMERIC(18,6) NULL default '0',
    `PDC` NUMERIC(18,6) NULL default '0',
    `cname` varchar(100) NULL default '',
    `AMOUNT` NUMERIC(18,6) NULL default '0',
    `SEQ` INT NULL default '0',
    `PAYDOCNO` varchar(30) NULL default '',
    `PAYDOCDATE` DATE NULL,
    INDEX `Index_ObjectCode` (`OBJECTCODE`,`SEQ`),
    INDEX `Index_Docno` (`COMPANY`,`BRANCH`,`OBJECTCODE`,`DOCNO`,`SEQ`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT
    INTO mainfield2 (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,OBJECTCODE,DOCNO,PPFNO,PPFNO2,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE)
    SELECT mf.COMPANY,
        mf.BRANCH,
        mf.BRANCHNAME,
        mf.BPCODE,
        mf.BPNAME,
        mf.CURRENCY,
        mf.OBJECTCODE,
        mf.DOCNO,
        mf.PPFNO,
        mf.PPFNO2,
        mf.DOCDATE,
        mf.DOCDUEDATE,
        mf.aging,
        mf.fisrtmonth,
        mf.secondndmonth,
        mf.thirdmonth,
        mf.cname,
        (settlements.balance*-1) as AMOUNT,
        1,
        settlements.PAYDOCNO,
        date(settlements.DOCDATE) as DOCDATE
        FROM mainfield mf
        INNER JOIN settlements ON mf.COMPANY = settlements.COMPANY AND mf.BRANCH = settlements.BRANCH
                        AND mf.OBJECTCODE = settlements.OBJECTCODE AND mf.DOCNO = settlements.DOCNO
           WHERE mf.objectcode='ARINVOICE' and mf.SEQ=0;
INSERT
    INTO mainfield2 (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,OBJECTCODE,DOCNO,PPFNO,PPFNO2,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE)
    SELECT mf.COMPANY,
        mf.BRANCH,
        mf.BRANCHNAME,
        mf.BPCODE,
        mf.BPNAME,
        mf.CURRENCY,
        mf.OBJECTCODE,
        mf.DOCNO,
        mf.PPFNO,
        mf.PPFNO2,
        mf.DOCDATE,
        mf.DOCDUEDATE,
        mf.aging,
        mf.fisrtmonth,
        mf.secondndmonth,
        mf.thirdmonth,
        mf.cname,
       (settlements.balance) as AMOUNT,
        1,
        settlements.PAYDOCNO,
        date(settlements.DOCDATE) as DOCDATE
         FROM mainfield mf
        INNER JOIN settlements ON mf.COMPANY = settlements.COMPANY AND mf.BRANCH = settlements.BRANCH
                        AND mf.OBJECTCODE = settlements.OBJECTCODE AND mf.DOCNO = settlements.DOCNO
           WHERE mf.objectcode='ARCREDITMEMO' and mf.SEQ=0;
INSERT
    INTO mainfield2 (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,OBJECTCODE,DOCNO,PPFNO,PPFNO2,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE)
    SELECT mf.COMPANY,
        mf.BRANCH,
        mf.BRANCHNAME,
        mf.BPCODE,
        mf.BPNAME,
        mf.CURRENCY,
        mf.OBJECTCODE,
        mf.DOCNO,
        mf.PPFNO,
        mf.PPFNO2,
        mf.DOCDATE,
        mf.DOCDUEDATE,
        mf.aging,
        mf.fisrtmonth,
        mf.secondndmonth,
        mf.thirdmonth,
        mf.cname,
        (settlements.balance*-1) as AMOUNT,
        1,
        settlements.PAYDOCNO,
        date(settlements.DOCDATE) as DOCDATE
        FROM mainfield mf
        INNER JOIN settlements ON mf.COMPANY = settlements.COMPANY AND mf.BRANCH = settlements.BRANCH
                        AND mf.OBJECTCODE = settlements.OBJECTCODE AND mf.DOCNO = settlements.DOCNO
           WHERE mf.objectcode='JOURNALVOUCHER' and mf.SEQ=0;
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,OBJECTCODE,DOCNO,PPFNO,PPFNO2,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE)
     select COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,CURRENCY,OBJECTCODE,DOCNO,PPFNO,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE from mainfield2;
    SELECT
      a.COMPANY,
      a.cname AS 'COMPANYNAME',
      a.BRANCH,
      a.BRANCHNAME,
      a.BPCODE,
      a.BPNAME,
      a.CURRENCY,
      a.BILLTOADDRESS,
      a.DOCNO,
      a.PPFNO,
      a.PPFNO2,
      a.DOCDATE,
      a.DOCDUEDATE,
      a.AMOUNT,
      a.DUEAMOUNT,
      a.AGING,
      if(a.DUEAMOUNT < 0, a.DUEAMOUNT, a.current) as current,
      if(a.DUEAMOUNT < 0, 0, a.D7) as 'D7',
      if(a.DUEAMOUNT < 0, 0, a.D15) as 'D15',
      if(a.DUEAMOUNT < 0, 0, a.D30) as 'D30',
      if(a.DUEAMOUNT < 0, 0, a.UP30) as 'UP30',
      a.advpay,
      a.PDC,
      date(pi_date) as date1,
      pt.paymenttermNAME as term,
      ct.CREDITLIMIT as creditlimit,SEQ,PAYDOCNO,PAYDOCDATE,
      ct.phone1,
      sp.salespersonname
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
         LEFT OUTER JOIN salespersons sp ON ct.salesperson = sp.salesperson
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
          and (pi_salesperson='' or (pi_salesperson<>'' and sp.salesperson = pi_salesperson))
    ORDER BY DOCDATE, DOCNO, SEQ, PAYDOCDATE, DOCNO;
  elseif pi_output='EB' then
    SELECT  date(pi_date) as date1,
      sum(a.DUEAMOUNT),
      sum(a.PDC) as PDC,
      SUM(a.DUEAMOUNT-(a.D7+a.D15+a.D30+a.UP30)) as current,
      sum(a.D7) as 'D7',
      sum(a.D15) as 'D15',
      sum(a.D30) as 'D30',
      sum(a.UP30) as 'UP30'
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct));
  elseif pi_output='CDB' then
    SELECT a.DOCNO, a.BPCODE, a.DUEAMOUNT
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct));
  elseif pi_output='CDB2' then
    SELECT a.DOCDATE, a.DOCNO, a.PPFNO, a.PPFNO2, a.BPCODE, a.BPNAME, a.CURRENCY, a.CURRENCYRATE, a.DUEAMOUNT
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct));
  elseif pi_output='CB' then
    SELECT a.BPCODE, sum(a.DUEAMOUNT) as DUEAMOUNT
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
    where (pi_custno='' or (pi_custno<>'' and (a.BPCODE=pi_custno)))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
       group by a.BPCODE HAVING DUEAMOUNT<>0;
  elseif pi_output='SOA' then
    SELECT (date(pi_date) + interval 1 day) as SOADATE,date(pi_date) as SOAPERIOD, a.BPCODE, a.BPNAME, a.CURRENCY, concat(b.STREET) as ADDRESS, sum(a.DUEAMOUNT) as DUEAMOUNT
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
         left outer join addresses b on b.company=ct.company and b.branch=ct.branch and b.reftype='CUSTOMER' and b.refid=ct.custno and b.addressname=ct.dfltbillto and b.addresstype=0
    where (pi_custno='' or (pi_custno<>'' and (a.BPCODE=pi_custno)))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
       group by a.BPCODE HAVING DUEAMOUNT<>0;
  elseif pi_output='CB.rank.high.dueamount' then
    SELECT
      a.COMPANY,
      a.cname AS 'COMPANYNAME',
      a.BRANCH,
      a.BRANCHNAME,
      a.BPCODE,
      a.BPNAME,
      a.BILLTOADDRESS,
      sum(a.AMOUNT) as AMOUNT,
      sum(a.DUEAMOUNT) as DUEAMOUNT,
      sum(a.PDC) as PDC,
      SUM(a.DUEAMOUNT-(a.D7+a.D15+a.D30+a.UP30)) as current,
      sum(a.D7) as 'D7',
      sum(a.D15) as 'D15',
      sum(a.D30) as 'D30',
      sum(a.UP30) as 'UP30',
      date(pi_date) as date1,
      pt.paymenttermNAME as term,
      ct.CREDITLIMIT as creditlimit,
      ct.phone1,
      sp.salespersonname
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
         LEFT OUTER JOIN salespersons sp ON ct.salesperson = sp.salesperson
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
          and (pi_salesperson='' or (pi_salesperson<>'' and sp.salesperson = pi_salesperson))
    group by a.BPCODE ORDER BY DUEAMOUNT DESC;
  elseif pi_output='CB.rank.high.age0' then
    SELECT
      a.COMPANY,
      a.cname AS 'COMPANYNAME',
      a.BRANCH,
      a.BRANCHNAME,
      a.BPCODE,
      a.BPNAME,
      a.BILLTOADDRESS,
      sum(a.AMOUNT) as AMOUNT,
      sum(a.DUEAMOUNT) as DUEAMOUNT,
      sum(a.PDC) as PDC,
      SUM(a.DUEAMOUNT-(a.D7+a.D15+a.D30+a.UP30)) as current,
      sum(a.D7) as 'D7',
      sum(a.D15) as 'D15',
      sum(a.D30) as 'D30',
      sum(a.UP30) as 'UP30',
      date(pi_date) as date1,
      pt.paymenttermNAME as term,
      ct.CREDITLIMIT as creditlimit,
      ctphone1,
      sp.salespersonname
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
         LEFT OUTER JOIN salespersons sp ON ct.salesperson = sp.salesperson
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
          and (pi_salesperson='' or (pi_salesperson<>'' and sp.salesperson = pi_salesperson))
    group by a.BPCODE ORDER BY CURRENT DESC;
  elseif pi_output='CB.rank.high.age1' then
    SELECT
      a.COMPANY,
      a.cname AS 'COMPANYNAME',
      a.BRANCH,
      a.BRANCHNAME,
      a.BPCODE,
      a.BPNAME,
      a.BILLTOADDRESS,
      sum(a.AMOUNT) as AMOUNT,
      sum(a.DUEAMOUNT) as DUEAMOUNT,
      sum(a.PDC) as PDC,
      SUM(a.DUEAMOUNT-(a.D7+a.D15+a.D30+a.UP30)) as current,
      sum(a.D7) as 'D7',
      sum(a.D15) as 'D15',
      sum(a.D30) as 'D30',
      sum(a.UP30) as 'UP30',
      date(pi_date) as date1,
      pt.paymenttermNAME as term,
      ct.CREDITLIMIT as creditlimit,
      ct.phone1,
      sp.salespersonname
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
         LEFT OUTER JOIN salespersons sp ON ct.salesperson = sp.salesperson
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
          and (pi_salesperson='' or (pi_salesperson<>'' and sp.salesperson = pi_salesperson))
    group by a.BPCODE ORDER BY D7 DESC, CURRENT DESC;
  elseif pi_output='CB.rank.high.age16' then
    SELECT
      a.COMPANY,
      a.cname AS 'COMPANYNAME',
      a.BRANCH,
      a.BRANCHNAME,
      a.BPCODE,
      a.BPNAME,
      a.BILLTOADDRESS,
      sum(a.AMOUNT) as AMOUNT,
      sum(a.DUEAMOUNT) as DUEAMOUNT,
      sum(a.PDC) as PDC,
      SUM(a.DUEAMOUNT-(a.D7+a.D15+a.D30+a.UP30)) as current,
      sum(a.D7) as 'D7',
      sum(a.D15) as 'D15',
      sum(a.D30) as 'D30',
      sum(a.UP30) as 'UP30',
      date(pi_date) as date1,
      pt.paymenttermNAME as term,
      ct.CREDITLIMIT as creditlimit,
      ct.phone1,
      sp.salespersonname
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
         LEFT OUTER JOIN salespersons sp ON ct.salesperson = sp.salesperson
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
          and (pi_salesperson='' or (pi_salesperson<>'' and sp.salesperson = pi_salesperson))
    group by a.BPCODE ORDER BY D15 DESC, D7 DESC, CURRENT DESC;
  elseif pi_output='CB.rank.high.age31' then
    SELECT
      a.COMPANY,
      a.cname AS 'COMPANYNAME',
      a.BRANCH,
      a.BRANCHNAME,
      a.BPCODE,
      a.BPNAME,
      a.BILLTOADDRESS,
      sum(a.AMOUNT) as AMOUNT,
      sum(a.DUEAMOUNT) as DUEAMOUNT,
      sum(a.PDC) as PDC,
      SUM(a.DUEAMOUNT-(a.D7+a.D15+a.D30+a.UP30)) as current,
      sum(a.D7) as 'D7',
      sum(a.D15) as 'D15',
      sum(a.D30) as 'D30',
      sum(a.UP30) as 'UP30',
      date(pi_date) as date1,
      pt.paymenttermNAME as term,
      ct.CREDITLIMIT as creditlimit,
      ct.phone1,
      sp.salespersonname
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
         LEFT OUTER JOIN salespersons sp ON ct.salesperson = sp.salesperson
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
          and (pi_salesperson='' or (pi_salesperson<>'' and sp.salesperson = pi_salesperson))
    group by a.BPCODE ORDER BY D30 DESC, D15 DESC, D7 DESC, CURRENT DESC;
  elseif pi_output='CB.rank.high.age46' then
    SELECT
      a.COMPANY,
      a.cname AS 'COMPANYNAME',
      a.BRANCH,
      a.BRANCHNAME,
      a.BPCODE,
      a.BPNAME,
      a.BILLTOADDRESS,
      sum(a.AMOUNT) as AMOUNT,
      sum(a.DUEAMOUNT) as DUEAMOUNT,
      sum(a.PDC) as PDC,
      SUM(a.DUEAMOUNT-(a.D7+a.D15+a.D30+a.UP30)) as current,
      sum(a.D7) as 'D7',
      sum(a.D15) as 'D15',
      sum(a.D30) as 'D30',
      sum(a.UP30) as 'UP30',
      date(pi_date) as date1,
      pt.paymenttermNAME as term,
      ct.CREDITLIMIT as creditlimit,
      ct.phone1,
      sp.salespersonname
    FROM mainfield a
         LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
         LEFT OUTER JOIN salespersons sp ON ct.salesperson = sp.salesperson
    where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
          and (pi_salesperson='' or (pi_salesperson<>'' and sp.salesperson = pi_salesperson))
    group by a.BPCODE ORDER BY UP30 DESC, D30 DESC, D15 DESC, D7 DESC, CURRENT DESC;
  elseif pi_output='table.balances' then
    SELECT * from balances order by docno;
  elseif pi_output='table.journalpayments' then
    SELECT * from journalpayments order by docno;
  elseif pi_output='table.concatfields' then
    SELECT * from concatfield order by docno;
  elseif pi_output='table.settlements' then
    SELECT * from settlements order by docno;
  elseif pi_output='table.pdcpayments' then
    SELECT * from pdcpayments order by bpcode, acctno;
  elseif pi_output='copytobi' then
    insert into ebtbi.araging (`COMPANY`,`BRANCH`,`BRANCHNAME`,`BPCODE`,`BPNAME`,`BILLTOADDRESS`,`ITEMCODE`,`OBJECTCODE`,`DOCNO`,`DOCDATE`,`DOCDUEDATE`,`DUEAMOUNT`,`aging`,`fisrtmonth`,`secondndmonth`,`thirdmonth`,`advpay`,`current`,`D7`,`D15`,`D30`,`UP30`,`PDC`,`cname`,`AMOUNT`,`SEQ`,`PAYDOCNO`,`PAYDOCDATE`,`CTRLACCT`)
   SELECT a.`COMPANY`,a.`BRANCH`,a.`BRANCHNAME`,a.`BPCODE`,a.`BPNAME`,a.`BILLTOADDRESS`,a.`ITEMCODE`,a.`OBJECTCODE`,a.`DOCNO`,a.`DOCDATE`,a.`DOCDUEDATE`,a.`DUEAMOUNT`,a.`aging`,a.`fisrtmonth`,a.`secondndmonth`,a.`thirdmonth`,a.`advpay`,a.`current`,a.`D7`,a.`D15`,a.`D30`,a.`UP30`,a.`PDC`,a.`cname`,a.`AMOUNT`,a.`SEQ`,a.`PAYDOCNO`,a.`PAYDOCDATE`,ct.DEBTPAYACCTNO from mainfield a LEFT OUTER JOIN customers ct ON ct.company=a.COMPANY and ct.branch=a.BRANCH and ct.custno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
          and (pi_custgroup='' or (pi_custgroup<>'' and ct.CUSTGROUP=pi_custgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct));
     select count(*) as COUNT from ebtbi.araging;
  elseif pi_output='Xbal' then
DROP TEMPORARY TABLE IF EXISTS `x_customerbalances`;
CREATE TEMPORARY TABLE  `x_customerbalances` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `BPCODE` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `DUEAMOUNT` NUMERIC(18,6) NULL default '0',
    `AGING` NUMERIC(18,6) NULL default '0'
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO x_customerbalances()
    SELECT
      a.COMPANY,
      a.BRANCH,
      a.BPCODE,
      a.DOCNO,
      a.DUEAMOUNT,
      a.AGING
    FROM mainfield a
        where (pi_custno='' or (pi_custno<>'' and a.BPCODE=pi_custno))
    ORDER BY DOCDATE, DOCNO;
  elseif pi_output='PROCESSTIME' then
    call sp_processtime('show','');
  end if;
END $$

DELIMITER ;