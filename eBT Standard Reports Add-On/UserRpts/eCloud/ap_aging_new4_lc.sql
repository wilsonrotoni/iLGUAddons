DELIMITER $$

DROP PROCEDURE IF EXISTS `ap_aging_new4_lc` $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ap_aging_new4_lc`(
    IN pi_company VARCHAR(30),
    IN pi_branch VARCHAR(30),
    IN pi_suppgroup VARCHAR(30),
    IN pi_accttype VARCHAR(30),
    IN pi_ctrlacct VARCHAR(30),
    IN pi_suppno VARCHAR(30),
    IN pi_date VARCHAR(30),
    IN pi_output VARCHAR(30))
BEGIN
  DECLARE v_companyname VARCHAR(100);
  DECLARE v_branchname VARCHAR(100);
  DECLARE v_cur VARCHAR(10);
  DECLARE v_advms DATE;
  DECLARE v_advme DATE;
  DECLARE v_30ms DATE;
  DECLARE v_60ms DATE;
  DECLARE v_90ms DATE;
  DECLARE v_30me DATE;
  DECLARE v_60me DATE;
  DECLARE v_90me DATE;
  DECLARE v_MONTH_END INT;
  SET v_30ms = date(concat(substring(pi_date,1,7),'-01'));
  SET v_advms = v_30ms + interval 1 month;
  SET v_60ms = v_30ms - interval 1 month;
  SET v_90ms = v_30ms - interval 2 month;
  SET v_30me = date(pi_date);
  SET v_advme = last_day(v_30me + interval 1 month);
  SET v_60me = last_day(v_30me - interval 1 month);
  SET v_90me = last_day(v_30me - interval 2 month);
  SET v_MONTH_END = concat('-',substring(v_60me,9,2)+1);
  SELECT UPPER(co.companyname), co.currency, UPPER(br.branchname) into v_companyname, v_cur, v_branchname from companies co, branches br where co.companycode = pi_company and br.companycode = pi_company and br.branchcode = pi_branch;
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
      SELECT a.company,a.branch,a.bpcode,a.refno,

      sum(
      (a.rebate*(if(a.currency <> v_cur,a.currencyrate,1))) +
      (a.amount*(if(a.currency <> v_cur,a.currencyrate,1)))
      ) as pdcamount

        from paymentinvoices a, paymentcheques b, payments c
        where b.company = a.company and b.branch = a.branch
        and b.docno = a.docno
        and b.company = c.company
        and b.branch = c.branchcode
        and b.docno = c.docno
        and c.pdc=1
        and a.company = pi_company and a.branch = pi_branch
        and b.checkdate >= pi_date
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
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
      from (SELECT a.company,a.branch,a.objectcode, a.docno,

      sum(
      (b.amount*(if(b.currency <> v_cur,b.currencyrate,1))) -
      (b.penaltypaid*(if(b.currency <> v_cur,b.currencyrate,1)))
      )*-1 as BALANCE,

      c.VALUEDATE AS DOCDATE, c.docno as paydocno
              from apinvoices a, collectionsinvoices b, collections c
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
                  and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
                group by a.DOCNO
            union all
              SELECT a.company,a.branch,a.objectcode, a.docno,

              sum(
              (b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
              ) as BALANCE,

              c.DOCDATE, c.docno as paydocno
                from apinvoices a, paymentinvoices b, payments c
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
                    and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
                  group by a.DOCNO
            ) a;

call sp_processtime('A/R Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('Journal Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company, branch, objectcode, docno, sum(BALANCE), DOCDATE, paydocno, 'BANKING'
      from (SELECT a.company,a.branch,a.objectcode,d.docno,

      sum(
      (b.amount*(if(b.currency <> v_cur,b.currencyrate,1))) -
      (b.penaltypaid*(if(b.currency <> v_cur,b.currencyrate,1)))
      )*-1 as BALANCE,

      c.VALUEDATE AS DOCDATE, c.docno as paydocno
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
                and d.itemtype = 'S'
                and d.reftype = ''
                and d.accttype=''
                and (pi_suppno='' or (pi_suppno<>'' and d.itemno=pi_suppno))
              group by d.DOCNO
        union all
          SELECT a.company,a.branch,a.objectcode,d.docno,

          sum(
          (b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
          ) as BALANCE,

          c.DOCDATE, c.docno as paydocno
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
              and d.itemtype = 'S'
              AND d.reftype = ''
              AND d.accttype=''
              and (pi_suppno='' or (pi_suppno<>'' and d.itemno=pi_suppno))
            group by d.DOCNO
          ) a group by docno;
call sp_processtime('Journal Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Journal Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company, branch, objectcode, docno, sum(BALANCE), DOCDATE, paydocno, 'BANKING'
      from (SELECT a.company,a.branch,a.objectcode,d.docno,

      sum(
      (b.amount*(if(b.currency <> v_cur,b.currencyrate,1))) -
      (b.penaltypaid*(if(b.currency <> v_cur,b.currencyrate,1)))
      )*-1 as BALANCE,

      c.VALUEDATE AS DOCDATE, c.docno as paydocno
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
                and d.itemtype = 'S'
                and d.reftype='OUTDOWNPAYMENT' and d.refno=''
                and d.accttype=''
                and (pi_suppno='' or (pi_suppno<>'' and d.itemno=pi_suppno))
              group by d.DOCNO
        union all
          SELECT a.company,a.branch,a.objectcode,d.docno,

          sum(
          (b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
          ) as BALANCE,

          c.DOCDATE, c.docno as paydocno
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
              and d.itemtype = 'S'
              AND d.reftype='OUTDOWNPAYMENT' and d.refno=''
              AND d.accttype=''
              and (pi_suppno='' or (pi_suppno<>'' and d.itemno=pi_suppno))
            group by d.DOCNO
          ) a group by docno;
call sp_processtime('Advances Journal Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('A/R CM Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company,branch,objectcode,docno, sum(BALANCE), DOCDATE, paydocno, 'BANKING'
      from (SELECT a.company,a.branch,a.objectcode,a.docno,

      sum(
      (b.amount*(if(b.currency <> v_cur,b.currencyrate,1))) -
      (b.penaltypaid*(if(b.currency <> v_cur,b.currencyrate,1)))
      ) as BALANCE,

      c.VALUEDATE AS DOCDATE, c.docno as paydocno
              from apcreditmemos a, collectionsinvoices b, collections c
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
                and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
              group by a.DOCNO
          union all
            SELECT a.company,a.branch,a.objectcode,a.docno,

            sum(
            (b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
            )*-1 as BALANCE,

            c.DOCDATE, c.docno as paydocno
              from apcreditmemos a, paymentinvoices b, payments c
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
                and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
              group by a.DOCNO
        ) a group by DOCNO;
call sp_processtime('A/R CM Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ARACCT') then
call sp_processtime('DP/RS Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company,branchcode,objectcode,docno, sum(BALANCE), DOCDATE, PAYDOCNO, 'BANKING'
      from (
            SELECT a.company,a.branchcode,a.objectcode,a.docno, if(a.collfor = 'DP',

            sum(
            (b.amount*(if(b.currency <> v_cur,b.currencyrate,1))) -
            (b.penaltypaid*(if(b.currency <> v_cur,b.currencyrate,1)))
            )*-1,
            sum(
            (b.amount*(if(b.currency <> v_cur,b.currencyrate,1))) -
            (b.penaltypaid*(if(b.currency <> v_cur,b.currencyrate,1)))
            )) as BALANCE,

            c.VALUEDATE AS DOCDATE, a.docno as paydocno
              from payments a, collectionsinvoices b, collections c
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
                and a.valuedate <= pi_date
                and a.docstat in ('O','C')
                and c.cleared <> 0
                and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date and c.valuedate<>'0000-00-00'
                and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
                and a.doctype = 'S'
              group by a.DOCNO

         union all
           SELECT a.company,a.branchcode,a.objectcode,a.docno,

           sum(
           (b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
           )*-1 as BALANCE,

           c.DOCDATE, c.docno as paydocno
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
                and a.valuedate <= pi_date
                and a.docstat in ('O','C')
                and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date
                and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
                and a.doctype = 'S'
              group by a.DOCNO

         union all
           SELECT a.company,a.branchcode,a.objectcode,a.docno,

           sum(
           (b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
           )*-1 as BALANCE,

           c.DOCDATE, c.docno as paydocno
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
                and a.valuedate <= pi_date
                and a.docstat in ('O','C')
                and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date
                and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
                and a.doctype = 'S'
              group by a.DOCNO

          union all
           SELECT a.company,a.branchcode,a.objectcode,a.docno,

           sum(
           (b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
           )*-1 as BALANCE,

           c.DOCDATE, c.docno as paydocno
              from payments a, paymentinvoices b, payments c
              where b.company = a.company
                and b.branch = a.branchcode
                and b.refno = a.docno
                and b.reftype in ('DEPOSIT','DOWNPAYMENT','OUTDOWNPAYMENT')
                and c.company = b.company
                and c.branchcode = b.branch
                and c.docno = b.docno
                and a.company = pi_company
                and a.branchcode = pi_branch
                AND a.collfor in('DP','RS')
                and a.valuedate <= pi_date
                and a.docstat in ('O','C')
                and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date
                and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
                and a.doctype = 'S'
              group by a.DOCNO
        ) a group by docno;
call sp_processtime('DP/RS Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Payments','start');
INSERT
  INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
    SELECT company,branchcode,objectcode,docno, sum(BALANCE), DOCDATE, PAYDOCNO, 'BANKING'
      from (SELECT a.company,a.branchcode,a.objectcode,a.docno,

      sum(
      (b.amount*(if(b.currency <> v_cur,b.currencyrate,1))) -
      (b.penaltypaid*(if(b.currency <> v_cur,b.currencyrate,1)))
      ) as BALANCE,

      c.DOCDATE, c.docno as PAYDOCNO
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
                and a.docdate <= pi_date
                and a.docstat in ('O','C')
                and a.doctype = 'S'
                and c.cleared <> 0
                and (c.docstat not in ('CN','D') or (c.docstat = 'CN' and c.cancelleddate > pi_date))
                and c.docdate <= pi_date
                and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
              group by a.DOCNO
         union all
           SELECT a.company,a.branchcode,a.objectcode,a.docno,

           sum(
           (b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
           )*-1 as BALANCE,

           c.DOCDATE, c.docno as PAYDOCNO
              from collections a, paymentinvoices b, payments c
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
                and a.docdate <= pi_date
                and a.docstat in ('O','C')
                and a.doctype = 'S'
                and (c.docstat not in ('CN','D') or (c.docstat in ('CN') and c.cancelleddate > pi_date) )
                and c.valuedate <= pi_date
                and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
              group by a.DOCNO
        ) a group by docno;
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
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branch,a.objectcode,a.docno,

        sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        ))*-1 as BALANCE,

        c.DOCDATE, c.docno as paydocno, 'JV'
        from apinvoices a, journalvoucheritems b, journalvouchers c
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
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by a.DOCNO;
call sp_processtime('A/P Journals','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Journal Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branch,d.objectcode,d.docno,

        sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        ))*-1 as BALANCE,

        c.DOCDATE, c.docno AS paydocno, 'JV'
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
        and d.itemtype = 'S'
        AND d.reftype = ''
        AND d.accttype=''
        and (pi_suppno='' or (pi_suppno<>'' and d.itemno=pi_suppno))
        group by d.DOCNO;
call sp_processtime('Journal Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then 
call sp_processtime('Advances Journal Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branch,d.objectcode,d.docno,

        sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        ))*-1 as BALANCE,

        c.DOCDATE, c.docno as paydocno, 'JV'
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
        and d.itemtype = 'S'
        AND d.reftype='OUTDOWNPAYMENT' and d.refno=''
        AND d.accttype=''
        and (pi_suppno='' or (pi_suppno<>'' and d.itemno=pi_suppno))
        group by d.DOCNO;
call sp_processtime('Advances Journal Journals','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P CM Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branch,a.objectcode,a.docno,

        sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        ))*-1 as BALANCE,

        c.DOCDATE, c.docno as paydocno, 'JV'
        from apcreditmemos a, journalvoucheritems b, journalvouchers c
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
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by a.DOCNO;
call sp_processtime('A/P CM Journals','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('DP/RS Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branchcode,a.objectcode,a.docno,

        sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        ))*-1 as BALANCE,

        c.DOCDATE, c.docno as paydocno, 'JV'
        from payments a, journalvoucheritems b, journalvouchers c
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
        and a.docstat in ('O','C')
        and a.valuedate <= pi_date
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by a.DOCNO;
call sp_processtime('DP/RS Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Journals','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
        SELECT a.company,a.branchcode,a.objectcode,a.docno,

        sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        ))*-1 as BALANCE,

        c.DOCDATE, c.docno as paydocno, 'JV'
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
        and a.doctype = 'S'
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by a.DOCNO;
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
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Sales Returns','start');
  INSERT
    INTO settlements (COMPANY,BRANCH,OBJECTCODE,DOCNO,BALANCE,DOCDATE,PAYDOCNO,TYPE)
       SELECT a.company,a.branch,a.objectcode,a.docno,

       sum((b.BASEAMOUNT*(if(b.currency <> v_cur,b.currencyrate,1)))),

       b.DOCDATE, b.DOCNO as paydocno, 'APCM'
        from apinvoices a, apcreditmemos b
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
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by a.DOCNO;
call sp_processtime('Sales Returns','end');
end if;
INSERT
  INTO sale_return (COMPANY,BRANCH,DOCNO,BALANCE,DOCDATE)
     select COMPANY,BRANCH,DOCNO,sum(BALANCE),DOCDATE from settlements where type='APCM' group by docno;

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
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.objectcode,a.docno,

        0 - sum((b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
        ) as advancepayment,

        c.VALUEDATE AS DOCDATE
        from apinvoices a, paymentinvoices b, payments c
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
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by a.DOCNO;
call sp_processtime('A/P Advance Payments','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Journal Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.objectcode,d.docno,

        0 - sum((b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
        ) as advancepayment,

        c.VALUEDATE AS DOCDATE
        from journalvouchers a, journalvoucheritems d, paymentinvoices b, payments c
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
        and d.itemtype = 'S'
        AND d.reftype = ''
        AND d.accttype=''
        and a.docstatus in ('O','C')
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_suppno='' or (pi_suppno<>'' and d.itemno=pi_suppno))
        group by d.DOCNO;
call sp_processtime('Journal Advance Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then 
call sp_processtime('Advances Journal Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.objectcode,d.docno,

        0 - sum((b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
        ) as advancepayment,

        c.VALUEDATE AS DOCDATE
        from journalvouchers a, journalvoucheritems d, paymentinvoices b, payments c
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
        and d.itemtype = 'S'
        AND d.reftype='OUTDOWNPAYMENT' and d.refno=''
        AND d.accttype=''
        and a.docstatus in ('O','C')
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_suppno='' or (pi_suppno<>'' and d.itemno=pi_suppno))
        group by d.DOCNO;
call sp_processtime('Advances Journal Advance Payments','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P CM Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.objectcode,a.docno,

        0 - sum((b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
        ) as advancepayment,

        c.VALUEDATE AS DOCDATE
        from apcreditmemos a, paymentinvoices b, payments c
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
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by a.DOCNO;
call sp_processtime('A/P CM Advance Payments','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('DP/RS Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branchcode,a.objectcode,a.docno,

        0 - sum(
        (b.amount*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.penaltypaid*(if(b.currency <> v_cur,b.currencyrate,1)))
        ) as advancepayment,

        c.VALUEDATE AS DOCDATE
        from payments a, collectionsinvoices b, collections c
        where b.company = a.company
        and b.branch = a.branchcode
        and b.refno = a.docno
        and b.reftype in ('DEPOSIT','OUTDOWNPAYMENT')
        and c.company = b.company
        and c.branchcode = b.branch
        and c.docno = b.docno
        and a.company = pi_company
        and a.branchcode = pi_branch
        AND a.collfor in('DP','RS')
        and a.docstat in ('O','C')
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        and a.doctype = 'S'
        group by a.DOCNO;
call sp_processtime('DP/RS Advance Payments','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances Advance Payments','start');
  INSERT
    INTO adv (COMPANY,BRANCH,OBJECTCODE,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branchcode,a.objectcode,a.docno,

        sum((b.amount*(if(b.currency <> v_cur,b.currencyrate,1)))
        ) as advancepayment,

        c.VALUEDATE AS DOCDATE
        from payments a, paymentinvoices b, payments c
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
        and a.doctype = 'S'
        and c.cleared <> 0
        and (c.docstat not in ('CN','BC','D') or (c.docstat = 'CN' and c.pdc=0 and c.cancelleddate > pi_date) or (c.docstat = 'BC' and c.cancelleddate > pi_date) )
        and c.valuedate > pi_date and c.valuedate<>'0000-00-00'
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
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
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.docno,

        0 - sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        )) as advancepayment,

        c.DOCDATE
        from apinvoices a, journalvoucheritems b, journalvouchers c
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
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by a.DOCNO;
call sp_processtime('A/P Advance Journals','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Journal Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,d.docno,

        0 - sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        )) as advancepayment,

        c.DOCDATE
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
        and d.itemtype = 'S'
        AND d.reftype = ''
        AND d.accttype=''
        and (pi_suppno='' or (pi_suppno<>'' and d.itemno=pi_suppno))
        group by d.DOCNO;
call sp_processtime('Journal Advance Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then 
call sp_processtime('Advances Journal Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,d.docno,

        0 - sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        )) as advancepayment,

        c.DOCDATE
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
        and d.itemtype = 'S'
        AND d.reftype='OUTDOWNPAYMENT' and d.refno=''
        AND d.accttype=''
        and (pi_suppno='' or (pi_suppno<>'' and d.itemno=pi_suppno))
        group by d.DOCNO;
call sp_processtime('Advances Journal Advance Journals','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P CM Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branch,a.docno,

        0 - sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        )) as advancepayment,

        c.DOCDATE
        from apcreditmemos a, journalvoucheritems b, journalvouchers c
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
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by a.DOCNO;
call sp_processtime('A/P CM Advance Journals','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('DP/RS Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branchcode,a.docno,

        0 - sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        )) as advancepayment,

        c.DOCDATE
        from payments a, journalvoucheritems b, journalvouchers c
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
        and a.docstat in ('O','C')
        and c.docdate > pi_date
        and c.docstatus in ('O','C')
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by a.DOCNO;
call sp_processtime('DP/RS Advance Journals','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then 
call sp_processtime('Advances Advance Journals','start');
  INSERT
    INTO advjv (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
        SELECT a.company,a.branchcode,a.docno,

        0 - sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        )) as advancepayment,

        c.DOCDATE
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
        and a.doctype = 'S'
        and c.docdate > pi_date
        and c.docstatus in ('O','C')
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
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
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Advance Sales Returns','start');
  INSERT
    INTO adv_sales_return (COMPANY,BRANCH,DOCNO,advancepayment,DOCDATE)
       SELECT a.company,a.branch,a.docno,

       0 - sum((b.BASEAMOUNT*(if(b.currency <> v_cur,b.currencyrate,1)))),

       b.DOCDATE
        from apinvoices a, apcreditmemos b
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
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
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
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE,DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT apinvoices.COMPANY, apinvoices.BRANCH, apinvoices.OBJECTCODE, apinvoices.DOCNO,
        (apinvoices.totalamount_lc-
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)
        ) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(apinvoices.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(apinvoices.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(apinvoices.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then (apinvoices.totalamount_lc - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if((apinvoices.totalamount_lc -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        (apinvoices.totalamount_lc -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if((apinvoices.totalamount_lc -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(apinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then (apinvoices.totalamount_lc -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if((apinvoices.totalamount_lc -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(apinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then (apinvoices.totalamount_lc -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if((apinvoices.totalamount_lc -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(apinvoices.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then (apinvoices.totalamount_lc -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if((apinvoices.totalamount_lc -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(apinvoices.DOCDUEDATE, '%y-%m-%d')) >= 91
        then (apinvoices.totalamount_lc -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        apinvoices.totalamount_lc
        FROM apinvoices
        LEFT OUTER JOIN departments on if(apinvoices.saletype = '', apinvoices.department = departments.department, apinvoices.saletype = departments.department)
        LEFT OUTER JOIN pdcpayments ON apinvoices.COMPANY = pdcpayments.COMPANY AND apinvoices.BRANCH = pdcpayments.BRANCH AND apinvoices.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON apinvoices.COMPANY = balances.COMPANY AND apinvoices.BRANCH = balances.BRANCH AND apinvoices.DOCNO = balances.DOCNO AND apinvoices.OBJECTCODE = balances.OBJECTCODE
        LEFT OUTER JOIN journalpayments ON apinvoices.COMPANY = journalpayments.COMPANY AND apinvoices.BRANCH = journalpayments.BRANCH AND apinvoices.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON apinvoices.COMPANY = adv.COMPANY AND apinvoices.BRANCH = adv.BRANCH AND apinvoices.OBJECTCODE = adv.OBJECTCODE AND apinvoices.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON apinvoices.COMPANY = advjv.COMPANY AND apinvoices.BRANCH = advjv.BRANCH AND apinvoices.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON apinvoices.COMPANY = sale_return.COMPANY AND apinvoices.BRANCH = sale_return.BRANCH AND apinvoices.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON apinvoices.COMPANY = adv_sales_return.COMPANY AND apinvoices.BRANCH = adv_sales_return.BRANCH AND apinvoices.DOCNO = adv_sales_return.DOCNO
        WHERE apinvoices.trxtype<>'POS' and apinvoices.accttype='' and apinvoices.docdate <= pi_date
        AND apinvoices.COMPANY = pi_company AND apinvoices.BRANCH = pi_branch
        and (pi_suppno='' or (pi_suppno<>'' and apinvoices.bpcode=pi_suppno))
        GROUP BY apinvoices.DOCNO
        ORDER BY apinvoices.docno ASC;
call sp_processtime('A/P 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Journal 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE,DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT journalvoucheritems.COMPANY, journalvoucheritems.BRANCH, journalvoucheritems.OBJECTCODE, journalvoucheritems.DOCNO,
        ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(journalvouchers.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if(((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if(((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if(((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if(((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if(((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) >= 91
        then ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        (if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1)))))
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
          AND journalvoucheritems.itemtype = 'S'
          AND journalvoucheritems.reftype = ''
          AND journalvoucheritems.accttype=''
          and (pi_suppno='' or (pi_suppno<>'' and journalvoucheritems.itemno=pi_suppno))
        GROUP BY journalvoucheritems.DOCNO
        ORDER BY journalvoucheritems.DOCNO ASC;
call sp_processtime('Journal 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then 
call sp_processtime('Advances Journal 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE,DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT journalvoucheritems.COMPANY, journalvoucheritems.BRANCH, journalvoucheritems.OBJECTCODE, journalvoucheritems.DOCNO,
        ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(journalvouchers.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if(((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if(((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if(((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if(((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if(((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(journalvouchers.DOCDUEDATE, '%y-%m-%d')) >= 91
        then ((if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        (if(journalvoucheritems.currency = v_cur, journalvoucheritems.credit - journalvoucheritems.debit, (journalvoucheritems.credit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1))) -
(journalvoucheritems.debit_fc*(if(journalvoucheritems.currency <> v_cur,journalvoucheritems.currencyrate,1)))))
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
        WHERE journalvoucheritems.itemtype = 'S'
          AND journalvoucheritems.reftype='OUTDOWNPAYMENT' and journalvoucheritems.refno=''
          AND journalvoucheritems.accttype=''
          AND journalvoucheritems.COMPANY = pi_company AND journalvoucheritems.BRANCH = pi_branch
          and (pi_suppno='' or (pi_suppno<>'' and journalvoucheritems.itemno=pi_suppno))
        GROUP BY journalvoucheritems.DOCNO
        ORDER BY journalvoucheritems.DOCNO ASC;
call sp_processtime('Advances Journal 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P CM 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE,DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT apcreditmemos.COMPANY, apcreditmemos.BRANCH, apcreditmemos.OBJECTCODE, apcreditmemos.DOCNO,
        ((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(apcreditmemos.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(apcreditmemos.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(apcreditmemos.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then ((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if(((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        ((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if(((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(apcreditmemos.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then ((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if(((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(apcreditmemos.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then ((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if(((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(apcreditmemos.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then ((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if(((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(apcreditmemos.DOCDUEDATE, '%y-%m-%d')) >= 91
        then ((apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        (apcreditmemos.totalamount*(if(apcreditmemos.currency <> v_cur,apcreditmemos.currencyrate,1)))
        FROM apcreditmemos
        LEFT OUTER JOIN departments on if(apcreditmemos.saletype = '', apcreditmemos.department = departments.department, apcreditmemos.saletype = departments.department)
        LEFT OUTER JOIN pdcpayments ON apcreditmemos.COMPANY = pdcpayments.COMPANY AND apcreditmemos.BRANCH = pdcpayments.BRANCH AND apcreditmemos.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON apcreditmemos.COMPANY = balances.COMPANY AND apcreditmemos.BRANCH = balances.BRANCH AND apcreditmemos.OBJECTCODE = balances.OBJECTCODE AND apcreditmemos.DOCNO = balances.DOCNO
        LEFT OUTER JOIN journalpayments ON apcreditmemos.COMPANY = journalpayments.COMPANY AND apcreditmemos.BRANCH = journalpayments.BRANCH AND apcreditmemos.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON apcreditmemos.COMPANY = adv.COMPANY AND apcreditmemos.BRANCH = adv.BRANCH AND apcreditmemos.OBJECTCODE = adv.OBJECTCODE AND apcreditmemos.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON apcreditmemos.COMPANY = advjv.COMPANY AND apcreditmemos.BRANCH = advjv.BRANCH AND apcreditmemos.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON apcreditmemos.COMPANY = sale_return.COMPANY AND apcreditmemos.BRANCH = sale_return.BRANCH AND apcreditmemos.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON apcreditmemos.COMPANY = adv_sales_return.COMPANY AND apcreditmemos.BRANCH = adv_sales_return.BRANCH AND apcreditmemos.DOCNO = adv_sales_return.DOCNO
        WHERE 
              apcreditmemos.accttype = ''
          AND apcreditmemos.docdate <= pi_date
          AND apcreditmemos.COMPANY = pi_company AND apcreditmemos.BRANCH = pi_branch
          and (pi_suppno='' or (pi_suppno<>'' and apcreditmemos.bpcode=pi_suppno))
        GROUP BY apcreditmemos.DOCNO
        ORDER BY apcreditmemos.docno ASC;
call sp_processtime('A/P CM 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('DP/RS 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT collections.COMPANY, collections.BRANCHCODE, collections.OBJECTCODE, collections.DOCNO,
        ((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment) +
        if (adv_sales_return.advancepayment is null, 0, adv_sales_return.advancepayment)) AS advpay,
        if(collections.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then ((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else
        if(((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))<0,
        ((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)),0) end
        ,0) as 'current',
        if(((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then ((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D7',
        if(((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then ((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D15',
        if(((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then ((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'D30',
        if(((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE))>0,
        case when datediff(pi_date, date_format(collections.DOCDUEDATE, '%y-%m-%d')) >= 91
        then ((collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE) -
        if (sale_return.BALANCE is null, 0, sale_return.BALANCE)) else 0.00 end,
        0) as 'UP30',
        (collections.PAIDAMOUNT*(if(collections.currency <> v_cur,collections.currencyrate,1)))
        FROM collections
        LEFT OUTER JOIN pdcpayments ON collections.COMPANY = pdcpayments.COMPANY AND collections.BRANCHCODE = pdcpayments.BRANCH AND collections.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN balances ON collections.COMPANY = balances.COMPANY AND collections.BRANCHCODE = balances.BRANCH AND collections.OBJECTCODE = balances.OBJECTCODE AND collections.DOCNO = balances.DOCNO
        LEFT OUTER JOIN journalpayments ON collections.COMPANY = journalpayments.COMPANY AND collections.BRANCHCODE = journalpayments.BRANCH AND collections.DOCNO = journalpayments.DOCNO
        LEFT OUTER JOIN adv ON collections.COMPANY = adv.COMPANY AND collections.BRANCHCODE = adv.BRANCH AND collections.OBJECTCODE = adv.OBJECTCODE AND collections.DOCNO = adv.DOCNO
        LEFT OUTER JOIN advjv ON collections.COMPANY = advjv.COMPANY AND collections.BRANCHCODE = advjv.BRANCH AND collections.DOCNO = advjv.DOCNO
        LEFT OUTER JOIN sale_return ON collections.COMPANY = sale_return.COMPANY AND collections.BRANCHCODE = sale_return.BRANCH AND collections.DOCNO = sale_return.DOCNO
        LEFT OUTER JOIN adv_sales_return ON collections.COMPANY = adv_sales_return.COMPANY AND collections.BRANCHCODE = adv_sales_return.BRANCH AND collections.DOCNO = adv_sales_return.DOCNO
        WHERE collections.PAIDAMOUNT > 0 AND
collections.cleared<>0
and collections.collfor in('DP')
and collections.doctype = 'S'
and (collections.docstat not in ('CN','BC','D') or (collections.docstat = 'CN' and collections.pdc=0 and collections.cancelleddate > pi_date) or (collections.docstat = 'BC' and collections.cancelleddate > pi_date) )
        AND collections.valuedate <= pi_date AND collections.valuedate <> '0000-00-00'
        AND collections.COMPANY = pi_company AND collections.BRANCHCODE = pi_branch
          and (pi_suppno='' or (pi_suppno<>'' and collections.bpcode=pi_suppno))
        GROUP BY collections.DOCNO
        ORDER BY collections.docno ASC;
call sp_processtime('DP/RS 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then
call sp_processtime('Advances 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT payments.COMPANY, payments.BRANCHCODE, payments.OBJECTCODE, payments.DOCNO,
        (((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) as DUEAMOUNT,
        (if (adv.advancepayment is null, 0, adv.advancepayment) +
        if (advjv.advancepayment is null, 0, advjv.advancepayment)) AS advpay,
        if(payments.DOCDUEDATE <= v_advme,
        case when datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d')) <=0
        and datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d')) > v_MONTH_END
        then (((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) - if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) else
        if((((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE))<0,
        (((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)),0) end
        ,0) as 'current',
        if((((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE))>0,
        case when datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d'))
        between 1 and 30 then (((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) else 0.00 end,
        0) as 'D7',
        if((((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE))>0,
        case when datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d'))
        between 31 and 60 then (((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) else 0.00 end,
        0) as 'D15',
        if((((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE))>0,
        case when datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d'))
        between 61 and 90 then (((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) else 0.00 end,
        0) as 'D30',
        if((((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE))>0,
        case when datediff(pi_date, date_format(payments.DOCDUEDATE, '%y-%m-%d')) >= 91
        then (((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) -
        if (balances.BALANCE is null, 0, balances.BALANCE) -
        if (journalpayments.BALANCE is null, 0, journalpayments.BALANCE)) else 0.00 end,
        0) as 'UP30',
        ((payments.PAIDAMOUNT-payments.DUEAMOUNT)*(if(payments.currency <> v_cur,payments.currencyrate,1))) PAIDAMOUNT
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
        AND payments.doctype = 'S'
        AND payments.COMPANY = pi_company AND payments.BRANCHCODE = pi_branch
          and (pi_suppno='' or (pi_suppno<>'' and payments.bpcode=pi_suppno))
        GROUP BY payments.DOCNO
        ORDER BY payments.docno ASC;
call sp_processtime('Advances 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Payments as Advances 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT payments.COMPANY, payments.BRANCHCODE, payments.OBJECTCODE, payments.DOCNO,

        sum((paymentinvoices.amount*(if(paymentinvoices.currency <> v_cur,paymentinvoices.currencyrate,1)))) as DUEAMOUNT,

        0 AS advpay,

        sum((paymentinvoices.amount*(if(paymentinvoices.currency <> v_cur,paymentinvoices.currencyrate,1)))) as 'current',

        0 as 'D7',
        0 as 'D15',
        0 as 'D30',
        0 as 'UP30',

        sum((paymentinvoices.amount*(if(paymentinvoices.currency <> v_cur,paymentinvoices.currencyrate,1))))

        FROM payments, paymentinvoices, apinvoices
        WHERE paymentinvoices.company = apinvoices.company
          and paymentinvoices.branch = apinvoices.branch
          and paymentinvoices.refno = apinvoices.docno
          and paymentinvoices.reftype = apinvoices.objectcode
          AND apinvoices.accttype=''
          and apinvoices.docstatus in ('O','C')
          and apinvoices.docdate > pi_date
          and paymentinvoices.company=payments.company
          and paymentinvoices.branch=payments.branchcode
          and paymentinvoices.docno=payments.docno
          and payments.collfor in('SI')
          and (payments.docstat not in ('CN','BC','D') or (payments.docstat = 'CN' and payments.pdc=0 and payments.cancelleddate > pi_date) or (payments.docstat = 'BC' and payments.cancelleddate > pi_date) )
        AND payments.valuedate <= pi_date and payments.valuedate<>'0000-00-00'
        AND payments.cleared = 1
        AND payments.COMPANY = pi_company AND payments.BRANCHCODE = pi_branch
          and (pi_suppno='' or (pi_suppno<>'' and payments.bpcode=pi_suppno))
        GROUP BY payments.DOCNO
        ORDER BY payments.docno ASC;
call sp_processtime('Payments as Advances 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P Journals as Advances 1/2','start');
INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
    SELECT c.COMPANY, c.BRANCH, c.OBJECTCODE, c.DOCNO,

        sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1))))) as DUEAMOUNT,

        0 AS advpay,

        sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1))))) as 'current',

        0 as 'D7',
        0 as 'D15',
        0 as 'D30',
        0 as 'UP30',

        sum(if(b.currency = v_cur, b.debit,
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        ))

    from apinvoices a, journalvoucheritems b, journalvouchers c
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
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
        group by c.DOCNO;
call sp_processtime('A/P Journals as Advances 1/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Advances Journals as Advances','start');
  INSERT
    INTO concatfield (COMPANY,BRANCH,OBJECTCODE, DOCNO,DUEAMOUNT,advpay,current,D7,D15,D30,UP30,AMOUNT)
        SELECT a.company,a.branchcode, c.OBJECTCODE,c.docno,

        sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        )) as DUEAMOUNT,

        0 AS advpay,

        sum(if(b.currency = v_cur, b.credit - b.debit,
        (b.credit_fc*(if(b.currency <> v_cur,b.currencyrate,1))) -
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        )) as 'current',

        0 as 'D7',
        0 as 'D15',
        0 as 'D30',
        0 as 'UP30',

        sum(if(b.currency = v_cur, b.debit,
        (b.debit_fc*(if(b.currency <> v_cur,b.currencyrate,1)))
        ))

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
        and a.doctype = 'S'
        and c.docdate <= pi_date
        and c.docstatus in ('O','C')
        and (pi_suppno='' or (pi_suppno<>'' and a.bpcode=pi_suppno))
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
    `BILLTOADDRESS` varchar(1000) NULL default '',
    `ITEMCODE` varchar(100) NULL default '',
    `OBJECTCODE` varchar(30) NULL default '',
    `DOCNO` varchar(100) NULL default '',
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
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P Aging 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT apinvoices.COMPANY,
        apinvoices.BRANCH,
        v_branchname as BRANCHNAME,
        apinvoices.BPCODE,
        apinvoices.BPNAME,
        apinvoices.BILLTOADDRESS,
        apinvoiceitems.ITEMCODE,
        apinvoices.OBJECTCODE,
        apinvoices.DOCNO,
        date(if(apinvoices.DOCDATE is null, now(), apinvoices.DOCDATE)) as DOCDATE,
        date(if(apinvoices.DOCDUEDATE is null, now(), apinvoices.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(apinvoices.docdate, INTERVAL 0 MONTH)),3), '%y-%m-%d')) AS aging,
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
        FROM apinvoices
        LEFT OUTER JOIN apinvoiceitems ON apinvoices.BRANCH = apinvoiceitems.BRANCH AND apinvoices.COMPANY = apinvoiceitems.COMPANY AND apinvoices.DOCID = apinvoiceitems.DOCID
        LEFT OUTER JOIN departments on if(apinvoices.saletype = '', apinvoices.department = departments.department, apinvoices.saletype = departments.department)
        LEFT OUTER JOIN pdcpayments ON apinvoices.COMPANY = pdcpayments.COMPANY AND apinvoices.BRANCH = pdcpayments.BRANCH AND apinvoices.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON apinvoices.COMPANY = concatfield.COMPANY AND apinvoices.BRANCH = concatfield.BRANCH
                        AND apinvoices.OBJECTCODE = concatfield.OBJECTCODE AND apinvoices.DOCNO = concatfield.DOCNO
        WHERE apinvoices.trxtype<>'POS'
          and apinvoices.accttype=''
          and apinvoices.docdate <= pi_date
          AND apinvoices.COMPANY = pi_company
          AND apinvoices.BRANCH = pi_branch
          and apinvoices.docstatus in ('O','C')
          
          AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          AND (pi_suppno='' or (pi_suppno<>'' and apinvoices.bpcode=pi_suppno))
        GROUP BY apinvoices.DOCNO
        ORDER BY apinvoices.docno ASC;
call sp_processtime('A/P Aging 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('A/P CM 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT apcreditmemos.COMPANY,
        apcreditmemos.BRANCH,
        v_branchname as BRANCHNAME,
        apcreditmemos.BPCODE,
        apcreditmemos.BPNAME,
        apcreditmemos.BILLTOADDRESS,
        apcreditmemoitems.ITEMCODE,
        apcreditmemos.OBJECTCODE,
        apcreditmemos.DOCNO,
        date(if(apcreditmemos.DOCDATE is null, now(), apcreditmemos.DOCDATE)) as DOCDATE,
        date(if(apcreditmemos.DOCDUEDATE is null, now(), apcreditmemos.DOCDUEDATE)) as DOCDUEDATE,
        0-(concatfield.DUEAMOUNT),
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(apcreditmemos.docdate, INTERVAL 0 MONTH)),3), '%y-%m-%d')) AS aging,
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
        FROM apcreditmemos
        LEFT OUTER JOIN apcreditmemoitems ON apcreditmemos.BRANCH = apcreditmemoitems.BRANCH AND apcreditmemos.COMPANY = apcreditmemoitems.COMPANY AND apcreditmemos.DOCID = apcreditmemoitems.DOCID
        LEFT OUTER JOIN departments on if(apcreditmemos.saletype = '', apcreditmemos.department = departments.department, apcreditmemos.saletype = departments.department)
        LEFT OUTER JOIN pdcpayments ON apcreditmemos.COMPANY = pdcpayments.COMPANY AND apcreditmemos.BRANCH = pdcpayments.BRANCH AND apcreditmemos.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON apcreditmemos.COMPANY = concatfield.COMPANY AND apcreditmemos.BRANCH = concatfield.BRANCH
                        AND apcreditmemos.OBJECTCODE = concatfield.OBJECTCODE AND apcreditmemos.DOCNO = concatfield.DOCNO
        WHERE 
              apcreditmemos.accttype = ''
        AND apcreditmemos.docdate <= pi_date
        AND apcreditmemos.COMPANY = pi_company
        AND apcreditmemos.BRANCH = pi_branch
        and apcreditmemos.docstatus in ('O','C')
        
          AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          AND (pi_suppno='' or (pi_suppno<>'' and apcreditmemos.bpcode=pi_suppno))
        GROUP BY apcreditmemos.DOCNO
        ORDER BY apcreditmemos.docno ASC;
call sp_processtime('A/P CM 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Journal 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT journalvouchers.COMPANY,
        journalvouchers.BRANCH,
        v_branchname as BRANCHNAME,
        journalvoucheritems.itemno as BPCODE,
        journalvoucheritems.itemname as BPNAME,
        '' as BILLTOADDRESS,
        '' as ITEMCODE,
        journalvoucheritems.OBJECTCODE,
        journalvoucheritems.DOCNO,
        date(if(journalvouchers.DOCDATE is null, now(), journalvouchers.DOCDATE)) as DOCDATE,
        date(if(journalvouchers.DOCDUEDATE is null, now(), journalvouchers.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(journalvouchers.docdate, INTERVAL 0 MONTH)),3), '%y-%m-%d')) AS aging,
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
        WHERE journalvoucheritems.itemtype = 'S'
           AND journalvoucheritems.reftype = ''
          AND journalvoucheritems.accttype=''
          AND journalvoucheritems.COMPANY = pi_company AND journalvoucheritems.BRANCH = pi_branch
        
          AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          AND (pi_suppno='' or (pi_suppno<>'' and journalvoucheritems.itemno=pi_suppno))
        GROUP BY journalvoucheritems.DOCNO
        ORDER BY journalvoucheritems.docno ASC;
call sp_processtime('Journal 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then 
call sp_processtime('Advances Journal 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT journalvouchers.COMPANY,
        journalvouchers.BRANCH,
        v_branchname as BRANCHNAME,
        journalvoucheritems.itemno as BPCODE,
        journalvoucheritems.itemname as BPNAME,
        '' as BILLTOADDRESS,
        '' as ITEMCODE,
        journalvoucheritems.OBJECTCODE,
        journalvoucheritems.DOCNO,
        date(if(journalvouchers.DOCDATE is null, now(), journalvouchers.DOCDATE)) as DOCDATE,
        date(if(journalvouchers.DOCDUEDATE is null, now(), journalvouchers.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(journalvouchers.docdate, INTERVAL 0 MONTH)),3), '%y-%m-%d')) AS aging,
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
        WHERE journalvoucheritems.itemtype = 'S'
           AND journalvoucheritems.reftype='OUTDOWNPAYMENT' and journalvoucheritems.refno=''
          AND journalvoucheritems.accttype=''
          AND journalvoucheritems.COMPANY = pi_company AND journalvoucheritems.BRANCH = pi_branch
        
          AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          AND (pi_suppno='' or (pi_suppno<>'' and journalvoucheritems.itemno=pi_suppno))
        GROUP BY journalvoucheritems.DOCNO
        ORDER BY journalvoucheritems.docno ASC;
call sp_processtime('Advances Journal 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('DP/RS 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT collections.COMPANY,
        collections.BRANCHCODE,
        v_branchname as BRANCHNAME,
        collections.BPCODE,
        collections.BPNAME,
        collections.ADDRESS,
        "",
        collections.OBJECTCODE,
        collections.DOCNO,
        date(if(collections.DOCDATE is null, now(), collections.DOCDATE)) as DOCDATE,
        date(if(collections.DOCDUEDATE is null, now(), collections.DOCDUEDATE)) as DOCDUEDATE,
        (concatfield.DUEAMOUNT),
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(collections.docdate, INTERVAL 0 MONTH)),3), '%y-%m-%d')) AS aging,
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
        FROM collections
        LEFT OUTER JOIN pdcpayments ON collections.COMPANY = pdcpayments.COMPANY AND collections.BRANCHCODE = pdcpayments.BRANCH AND collections.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON collections.COMPANY = concatfield.COMPANY AND collections.BRANCHCODE = concatfield.BRANCH
                        AND collections.OBJECTCODE = concatfield.OBJECTCODE AND collections.DOCNO = concatfield.DOCNO
        WHERE collections.PAIDAMOUNT > 0 AND collections.cleared<>0 AND collections.collfor in('DP')
        and (collections.docstat not in ('CN','BC','D') or (collections.docstat = 'CN' and collections.pdc=0 and collections.cancelleddate > pi_date) or (collections.docstat = 'BC' and collections.cancelleddate > pi_date) )
        AND collections.valuedate <= pi_date AND collections.valuedate <> '0000-00-00'
        AND collections.COMPANY = pi_company
        AND collections.BRANCHCODE = pi_branch
        
          AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          AND (pi_suppno='' or (pi_suppno<>'' and collections.bpcode=pi_suppno))
        and collections.doctype = 'S'
        GROUP BY collections.DOCNO
        ORDER BY collections.docno ASC;
call sp_processtime('DP/RS 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='ADVACCT') then 
call sp_processtime('Advances 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT payments.COMPANY,
        payments.BRANCHCODE,
        v_branchname as BRANCHNAME,
        payments.BPCODE,
        payments.BPNAME,
        payments.ADDRESS,
        "",
        payments.OBJECTCODE,
        payments.DOCNO,
        date(if(payments.DOCDATE is null, now(), payments.DOCDATE)) as DOCDATE,
        date(if(payments.DOCDUEDATE is null, now(), payments.DOCDUEDATE)) as DOCDUEDATE,
        0-(concatfield.DUEAMOUNT),
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(payments.docdate, INTERVAL 0 MONTH)),3), '%y-%m-%d')) AS aging,
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
        FROM payments
        LEFT OUTER JOIN pdcpayments ON payments.COMPANY = pdcpayments.COMPANY AND payments.BRANCHCODE = pdcpayments.BRANCH AND payments.DOCNO = pdcpayments.ACCTNO
        LEFT OUTER JOIN concatfield ON payments.COMPANY = concatfield.COMPANY AND payments.BRANCHCODE = concatfield.BRANCH
                        AND payments.OBJECTCODE = concatfield.OBJECTCODE AND payments.DOCNO = concatfield.DOCNO
        WHERE payments.PAIDAMOUNT > 0 AND payments.collfor in('DP')
         and (payments.docstat not in ('CN','D') or (payments.docstat = 'CN' and payments.cancelleddate > pi_date) or (payments.docstat = 'BC' and payments.cancelleddate > pi_date) )
        AND payments.docdate <= pi_date
        AND payments.doctype = 'S'
        AND payments.COMPANY = pi_company
        AND payments.BRANCHCODE = pi_branch
         
          AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          AND (pi_suppno='' or (pi_suppno<>'' and payments.bpcode=pi_suppno))
        GROUP BY payments.DOCNO
        ORDER BY payments.docno ASC;
call sp_processtime('Advances 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Payments as Advances 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT payments.COMPANY,
        payments.BRANCHCODE,
        v_branchname as BRANCHNAME,
        payments.BPCODE,
        payments.BPNAME,
        payments.ADDRESS,
        "",
        payments.OBJECTCODE,
        payments.DOCNO,
        date(if(payments.DOCDATE is null, now(), payments.DOCDATE)) as DOCDATE,
        date(if(payments.DOCDUEDATE is null, now(), payments.DOCDUEDATE)) as DOCDUEDATE,
        0-(concatfield.DUEAMOUNT),
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(payments.docdate, INTERVAL 0 MONTH)),3), '%y-%m-%d')) AS aging,
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
        FROM payments
        LEFT OUTER JOIN concatfield ON payments.COMPANY = concatfield.COMPANY AND payments.BRANCHCODE = concatfield.BRANCH
                        AND payments.OBJECTCODE = concatfield.OBJECTCODE AND payments.DOCNO = concatfield.DOCNO
        WHERE payments.collfor in ('SI')
        AND payments.valuedate <= pi_date and payments.valuedate<>'0000-00-00'
        AND payments.COMPANY = pi_company
        AND payments.BRANCHCODE = pi_branch
        and (payments.docstat not in ('CN','BC','D') or (payments.docstat = 'CN' and payments.pdc=0 and payments.cancelleddate > pi_date) or (payments.docstat = 'BC' and payments.cancelleddate > pi_date) )
        
          AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          AND (pi_suppno='' or (pi_suppno<>'' and payments.bpcode=pi_suppno))
        GROUP BY payments.DOCNO
        ORDER BY payments.docno ASC;
call sp_processtime('Payments as Advances 2/2','end');
end if;
if (pi_accttype='' or pi_accttype='APACCT') then
call sp_processtime('Journals as Advances 2/2','start');
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,BILLTOADDRESS,ITEMCODE,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,
    DUEAMOUNT,aging,fisrtmonth,secondndmonth,thirdmonth,advpay,current,D7,D15,D30,UP30,
    PDC,cname,AMOUNT)
    SELECT journalvouchers.COMPANY,
        journalvouchers.BRANCH,
        v_branchname as BRANCHNAME,
        journalvoucheritems.itemno as BPCODE,
        journalvoucheritems.itemname as BPNAME,
        '' as BILLTOADDRESS,
        '' as ITEMCODE,
        journalvouchers.OBJECTCODE,
        journalvouchers.DOCNO,
        date(if(journalvouchers.DOCDATE is null, now(), journalvouchers.DOCDATE)) as DOCDATE,
        date(if(journalvouchers.DOCDUEDATE is null, now(), journalvouchers.DOCDUEDATE)) as DOCDUEDATE,
        concatfield.DUEAMOUNT,
        datediff(pi_date, date_format(ADDDATE(LAST_DAY(SUBDATE(journalvouchers.docdate, INTERVAL 0 MONTH)),3), '%y-%m-%d')) AS aging,
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
          AND journalvoucheritems.itemtype = 'S'
          AND (journalvoucheritems.reftype not in ('') and journalvoucheritems.refno not in (''))
          AND journalvoucheritems.accttype=''
          
          AND (concatfield.DUEAMOUNT<>0 or (concatfield.DUEAMOUNT=0 and concatfield.advpay<>0))
          AND (pi_suppno='' or (pi_suppno<>'' and journalvoucheritems.itemno=pi_suppno))
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
      ct.suppname BPNAME,
      a.BILLTOADDRESS,
      a.DOCNO,
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
      ct.CREDITLIMIT as creditlimit
      , ct.phone1 as phone1
    FROM mainfield a
         INNER JOIN suppliers ct ON ct.suppno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
    where (pi_suppno='' or (pi_suppno<>'' and a.BPCODE=pi_suppno))
          and (pi_suppgroup='' or (pi_suppgroup<>'' and ct.SUPPGROUP=pi_suppgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
    ORDER BY DOCDATE, DOCNO;
  elseif pi_output='paymentdetails' then
DROP TEMPORARY TABLE IF EXISTS `mainfield2`;
CREATE TEMPORARY TABLE  `mainfield2` (
    `COMPANY` varchar(100) NULL default '',
    `BRANCH` varchar(100) NULL default '',
    `BRANCHNAME` varchar(100) NULL default '',
    `BPCODE` varchar(100) NULL default '',
    `BPNAME` varchar(500) NULL default '',
    `BILLTOADDRESS` varchar(1000) NULL default '',
    `ITEMCODE` varchar(100) NULL default '',
    `OBJECTCODE` varchar(30) NULL default '',
    `DOCNO` varchar(100) NULL default '',
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
    INTO mainfield2 (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE)
    SELECT mf.COMPANY,
        mf.BRANCH,
        mf.BRANCHNAME,
        mf.BPCODE,
        mf.BPNAME,
        mf.OBJECTCODE,
        mf.DOCNO,
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
         INNER JOIN suppliers ct ON ct.suppno = MF.BPCODE
           WHERE mf.objectcode='APINVOICE' and mf.SEQ=0;
INSERT
    INTO mainfield2 (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE)
    SELECT mf.COMPANY,
        mf.BRANCH,
        mf.BRANCHNAME,
        mf.BPCODE,
        mf.BPNAME,
        mf.OBJECTCODE,
        mf.DOCNO,
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
         INNER JOIN suppliers ct ON ct.suppno = MF.BPCODE
           WHERE mf.objectcode='APCREDITMEMO' and mf.SEQ=0;
INSERT
    INTO mainfield2 (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE)
    SELECT mf.COMPANY,
        mf.BRANCH,
        mf.BRANCHNAME,
        mf.BPCODE,
        mf.BPNAME,
        mf.OBJECTCODE,
        mf.DOCNO,
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
         INNER JOIN suppliers ct ON ct.suppno = MF.BPCODE
           WHERE mf.objectcode='OUTGOINGPAYMENT' and mf.SEQ=0;
INSERT
    INTO mainfield2 (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE)
    SELECT mf.COMPANY,
        mf.BRANCH,
        mf.BRANCHNAME,
        mf.BPCODE,
        mf.BPNAME,
        mf.OBJECTCODE,
        mf.DOCNO,
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
         INNER JOIN suppliers ct ON ct.suppno = MF.BPCODE
           WHERE mf.objectcode='JOURNALVOUCHER' and mf.SEQ=0;
INSERT
    INTO mainfield (COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE)
     select COMPANY,BRANCH,BRANCHNAME,BPCODE,BPNAME,OBJECTCODE,DOCNO,DOCDATE,DOCDUEDATE,aging,fisrtmonth,secondndmonth,thirdmonth,cname,AMOUNT,SEQ,PAYDOCNO,PAYDOCDATE from mainfield2;
    SELECT
      a.COMPANY,
      a.cname AS 'COMPANYNAME',
      a.BRANCH,
      a.BRANCHNAME,
      a.BPCODE,
      ct.suppname BPNAME,
      concat(ct.suppname,' (',BPCODE,')') as BPNAMECODE,
      a.BILLTOADDRESS,
      a.DOCNO,
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
      ct.CREDITLIMIT as creditlimit,SEQ,PAYDOCNO,PAYDOCDATE , ct.phone1 as phone1
    FROM mainfield a
         INNER JOIN suppliers ct ON ct.suppno = a.BPCODE
         LEFT OUTER JOIN paymentterms pt ON pt.paymentterm = ct.paymentterm
    where (pi_suppno='' or (pi_suppno<>'' and a.BPCODE=pi_suppno))
          and (pi_suppgroup='' or (pi_suppgroup<>'' and ct.SUPPGROUP=pi_suppgroup))
          and (pi_ctrlacct='' or (pi_ctrlacct<>'' and ct.DEBTPAYACCTNO=pi_ctrlacct))
    ORDER BY DOCDATE, DOCNO, SEQ, PAYDOCDATE, DOCNO;
end if;
END $$

DELIMITER ;