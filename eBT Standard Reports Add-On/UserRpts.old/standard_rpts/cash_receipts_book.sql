DELIMITER $$

DROP PROCEDURE IF EXISTS `cash_receipts_book` $$
CREATE PROCEDURE `cash_receipts_book`(IN pi_company VARCHAR(30),
                         IN pi_branch VARCHAR(30),
                         IN pi_date1 VARCHAR(30), IN pi_date2 VARCHAR(30))
BEGIN
CREATE TEMPORARY TABLE  `cashcards2` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCID` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `CASHCARD` varchar(30) NULL default '',
    `REFNO` varchar(30) NULL default '',
    `AMOUNT` NUMERIC(18,6) NULL default '0',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCID`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO cashcards2 (COMPANY,BRANCH,DOCID,DOCNO,CASHCARD,REFNO,AMOUNT)
      SELECT a.COMPANY,a.BRANCH,a.DOCID,a.DOCNO,a.CASHCARD,a.REFNO,a.AMOUNT as AMOUNT
        from collectionscashcards a
        where a.company = pi_company and a.branch = pi_branch;
CREATE TEMPORARY TABLE  `creditcards2` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCID` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `CREDITCARDNO` varchar(30) NULL default '',
    `CARDEXPIRETEXT` varchar(30) NULL default '',
    `creditcard` varchar(30) NULL default '',
    `AMOUNT` NUMERIC(18,6) NULL default '0',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCID`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO creditcards2 (COMPANY,BRANCH,DOCID,DOCNO,CREDITCARDNO,CARDEXPIRETEXT,creditcard,AMOUNT)
      SELECT a.COMPANY,a.BRANCH,a.DOCID,a.DOCNO,a.CREDITCARDNO,a.CARDEXPIRETEXT,a.creditcard,a.AMOUNT
        from collectionscreditcards a
        where a.company = pi_company and a.branch = pi_branch;
CREATE TEMPORARY TABLE  `cheques` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(30) NULL default '',
    `DOCID` varchar(30) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `BANKBRANCH` varchar(30) NULL default '',
    `BANK` varchar(30) NULL default '',
    `CHECKNO` varchar(30) NULL default '',
    `bankacctno` varchar(30) NULL default '',
    `checkdate` DATE NULL,
    `AMOUNT` NUMERIC(18,6) NULL default '0',
    PRIMARY KEY  (`COMPANY`,`BRANCH`,`DOCID`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO cheques (COMPANY,BRANCH,DOCID,DOCNO,BANK,BANKBRANCH,CHECKNO,checkdate,AMOUNT,bankacctno)
      SELECT a.COMPANY,a.BRANCH,a.DOCID,a.DOCNO,a.BANK,a.BANKBRANCH,a.CHECKNO,a.checkdate,a.AMOUNT,a.bankacctno
        from collectionscheques a
        where a.company = pi_company and a.branch = pi_branch;
CREATE TEMPORARY TABLE  `dccr` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCHCODE` varchar(30) NULL default '',
    `BPCODE` varchar(30) NULL default '',
    `BPNAME` varchar(500) NULL default '',
    `ADDRESS` varchar(500) NULL default '',
    `DOCNO` varchar(30) NULL default '',
    `DOCDATE` DATE NULL,
    `REMARKS` varchar(1000) NULL default '',
    `LASTUPDATEDBY` varchar(30) NULL default '',
    `REFNO` varchar(50) NULL default '',
    `AMOUNT` NUMERIC(18,6) NULL default '0',
    `reftype` varchar(100) NULL default '',
    `BRANCHNAME` varchar(30) NULL default '',
    `rebate` NUMERIC(18,6) NULL default '0',
    `penalty` NUMERIC(18,6) NULL default '0',
    `bankname` varchar(500) NULL default '',
    `accountno` varchar(30) NULL default '',
    `checkno` varchar(30) NULL default '',
    `checkdate` DATE NULL,
    `accountname` varchar(30) NULL default '',
    `chequesamount` NUMERIC(18,6) NULL default '0',
    `ccname` varchar(30) NULL default '',
    `ccno` varchar(30) NULL default '',
    `ccexpiry` varchar(30) NULL default '',
    `creditcardsamount` NUMERIC(18,6) NULL default '0',
    `ccard` varchar(30) NULL default '',
    `ccardrefno` varchar(30) NULL default '',
    `ccardamount` NUMERIC(18,6) NULL default '0',
    `valuedate` DATE NULL,
    `pdc` varchar(30) NULL default '',
    `pdc_applied` varchar(30) NULL default '',
    `pdc_ref` varchar(30) NULL default '',
    `arrangement` varchar(30) NULL default '',
    `xxx` varchar(100) NULL default '',
    `column1` varchar(30) NULL default '',
    `column2` varchar(30) NULL default '',
    `date1` DATE NULL,
    `date2` DATE NULL,
    `si` varchar(30) NULL default '0',
    `date3` DATE NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
 INSERT
    INTO dccr (COMPANY,
    BRANCHCODE,
    BPCODE,
    BPNAME,
    ADDRESS,
    DOCNO,
    DOCDATE,
    REMARKS,
    LASTUPDATEDBY,
    REFNO,
    AMOUNT,
    reftype,
    BRANCHNAME,
    rebate,
    penalty,
    bankname,
    accountno,
    checkno,
    checkdate,
    accountname,
    chequesamount,
    ccname,
    ccno,
    ccexpiry,
    creditcardsamount,
    ccard,
    ccardrefno,
    ccardamount,
    valuedate,
    pdc,
    pdc_applied,
    pdc_ref,
    arrangement,
    xxx,
    column1,
    column2,
    date1,
    date2,
    si,
    date3)
SELECT upper(f.COMPANYNAME) as COMPANY,
        collections.BRANCHCODE,
        collections.BPCODE,
        if(collections.BPNAME = '', collections.ADDRESS, collections.BPNAME) as BPNAME,
        collections.ADDRESS,
        collections.DOCNO,
        collections.DOCDATE,
        collections.REMARKS,
        collections.LASTUPDATEDBY,
        case when collections.collfor='RS' then ''
              when collections.collfor='WU' then collections.REFNO
              when collections.collfor='SI' then
              if (collectionsinvoices.reftype like 'NR%', accountslist.REFNO, collectionsinvoices.REFNO)
        else IF(collectionsaccounts.subsidiary = '', collectionsaccounts.REMARKS, 'EXPENSES') end
        as REFNO,
        case when collections.collfor='WU' then collections.WUNETAMOUNT
             when collections.collfor='' then collectionsaccounts.amount
        else (case when collectionsinvoices.AMOUNT is null
        then collections.PAIDAMOUNT else collectionsinvoices.AMOUNT - collectionsinvoices.penaltypaid
        end) end
        as 'AMOUNT',
        IF(collections.pdc = '1', CONCAT(CONVERT(cheques.checkdate,CHAR), ' ', collectionsinvoices.reftype) ,case
              when collections.collfor='RS' then concat(collections.DEPARTMENT , ' - CUSTOMER DEPOSIT')
              when collections.collfor='WU' then 'OTHER CASH PAYMENTS'
              when collections.collfor='SI' then collectionsinvoices.reftype
        else IF(collectionsaccounts.SUBSIDIARY = '', collectionsaccounts.GLACCTNAME, chartofaccountsubsidiaries.SUBSIDIARYNAME) end) as 'reftype',
        upper(branches.BRANCHNAME) as BRANCHNAME, if (collectionsinvoices.rebate is null, 0, collectionsinvoices.rebate) as rebate,
        if (collectionsinvoices.penaltypaid is null, 0, collectionsinvoices.penaltypaid) as penalty,
        if (cheques.DOCNO is null, '', concat(cheques.BANK,' - ',cheques.BANKBRANCH)) as bankname,
        if (cheques.DOCNO is null, '', cheques.bankacctno) as accountno,
        if (cheques.DOCNO is null, '', cheques.CHECKNO) as checkno, cheques.CHECKDATE as checkdate,
        if (cheques.DOCNO is null, '', '') as accountname,
        if (cheques.DOCNO is null, 0, cheques.AMOUNT) as chequesamount,
        if (creditcards2.DOCNO is null, '', creditcards.creditcardname) as ccname,
        if (creditcards2.DOCNO is null, '', creditcards2.CREDITCARDNO) as ccno,
        if (creditcards2.DOCNO is null, '', creditcards2.CARDEXPIRETEXT) as ccexpiry,
        if (creditcards2.DOCNO is null, 0, creditcards2.AMOUNT) as creditcardsamount,
        '' as ccard,
        '' as ccardrefno,
        0 as ccardamount,
        date(if(collections.valuedate = '0000-00-00', null, collections.valuedate)) as valuedate,
        collections.pdc as pdc,
        if(collections.valuedate between pi_date1 and pi_date2 and collections.pdc=1, 1, 0) as pdc_applied,
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then 'APPLIED PDC'
        when collections.pdc = '1' and collections.valuedate = '0000-00-00' then 'UNAPPLIED PDC'
        else 'CRB'
        end as pdc_ref,
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then 2
        when collections.pdc = '1' and collections.valuedate = '0000-00-00' then 3
        else 1
        end as arrangement,
        j.DEPARTMENTNAME as xxx,
        '' as column1,
        case when collectionsinvoices.reftype like 'AR%' then 'AR'
             when collectionsinvoices.reftype like 'NR%' then 'NR'
        else ''
        end as column2,
        DATE(pi_date1) as date1,
        DATE(pi_date2) as date2,
        if(collections.collfor='SI',0,1),
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then collections.valuedate
        else collections.DOCDATE
        end
        FROM collections
        LEFT OUTER JOIN collectionsinvoices ON collections.COMPANY = collectionsinvoices.COMPANY
        AND collections.DOCNO = collectionsinvoices.DOCNO AND collections.BRANCHCODE = collectionsinvoices.BRANCH
        LEFT OUTER JOIN collectionsaccounts ON collections.COMPANY = collectionsaccounts.COMPANY
        AND collections.DOCNO = collectionsaccounts.DOCNO AND collections.BRANCHCODE = collectionsaccounts.BRANCH
        LEFT OUTER JOIN chartofaccountsubsidiaries ON collectionsaccounts.SUBSIDIARY = chartofaccountsubsidiaries.SUBSIDIARY
        LEFT OUTER JOIN customers ON collections.BPCODE = customers.CUSTNO
        AND collections.COMPANY = customers.COMPANY AND collections.BRANCHCODE = customers.BRANCH
        LEFT OUTER JOIN accountslist ON
        collectionsinvoices.REFNO = accountslist.acctno and collectionsinvoices.refbranch = accountslist.branchcode
        LEFT OUTER JOIN accounts ON collectionsinvoices.COMPANY = accounts.COMPANY
        AND collectionsinvoices.REFNO = accounts.acctno AND collectionsinvoices.BRANCH = accounts.BRANCH
        LEFT OUTER JOIN cheques ON collections.COMPANY = cheques.COMPANY
        AND collections.DOCNO = cheques.DOCNO AND collections.BRANCHCODE = cheques.BRANCH
        LEFT OUTER JOIN creditcards2 ON collections.COMPANY = creditcards2.COMPANY
        AND collections.DOCNO = creditcards2.DOCNO AND collections.BRANCHCODE = creditcards2.BRANCH
        LEFT OUTER JOIN creditcards ON creditcards.creditcard = creditcards2.creditcard
        LEFT OUTER JOIN branches ON collections.BRANCHCODE = branches.BRANCHCODE
        LEFT OUTER JOIN companies f ON f.COMPANYCODE = pi_company
        LEFT OUTER JOIN arinvoices ON collectionsinvoices.COMPANY = arinvoices.COMPANY
        AND collectionsinvoices.BRANCH = arinvoices.BRANCH
        AND collectionsinvoices.REFNO = arinvoices.DOCNO
        LEFT OUTER JOIN departments j
                        ON (collections.DEPARTMENT = j.DEPARTMENT or arinvoices.DEPARTMENT = j.DEPARTMENT or accounts.loantype = j.DEPARTMENT or accountslist.loantype = j.DEPARTMENT)
        WHERE collections.COMPANY = pi_company
              AND collections.BRANCHCODE = pi_branch
              AND (collections.docdate between pi_date1 and pi_date2
                  OR (collections.pdc = 1 and collections.valuedate between pi_date1 and pi_date2))
              AND collections.TRXTYPE NOT IN('CM')
              AND collections.DOCSTAT NOT IN('D')
       ORDER BY collections.DOCDATE, collections.DOCNO;
 INSERT
    INTO dccr (COMPANY,
    BRANCHCODE,
    BPCODE,
    BPNAME,
    ADDRESS,
    DOCNO,
    DOCDATE,
    REMARKS,
    LASTUPDATEDBY,
    REFNO,
    AMOUNT,
    reftype,
    BRANCHNAME,
    rebate,
    penalty,
    bankname,
    accountno,
    checkno,
    checkdate,
    accountname,
    chequesamount,
    ccname,
    ccno,
    ccexpiry,
    creditcardsamount,
    ccard,
    ccardrefno,
    ccardamount,
    valuedate,
    pdc,
    pdc_applied,
    pdc_ref,
    arrangement,
    xxx,
    column1,
    column2,
    date1,
    date2,
    si,
    date3)
SELECT upper(f.COMPANYNAME) as COMPANY,
        collections.BRANCHCODE,
        collections.BPCODE,
        if(collections.BPNAME = '', collections.ADDRESS, collections.BPNAME) as BPNAME,
        collections.ADDRESS,
        collections.DOCNO,
        collections.DOCDATE,
        collections.REMARKS,
        collections.LASTUPDATEDBY,
        case when collections.collfor='RS' then ''
              when collections.collfor='WU' then collections.REFNO
              when collections.collfor='SI' then
              if (collectionsinvoices.reftype like 'NR%', accountslist.REFNO, collectionsinvoices.REFNO)
        else IF(collectionsaccounts.subsidiary = '', collectionsaccounts.REMARKS, 'EXPENSES') end
        as REFNO,
        if(collections.collfor='WU', collections.WUCHARGESAMOUNT,
        case when collectionsinvoices.AMOUNT is null
        then collections.PAIDAMOUNT else collectionsinvoices.AMOUNT - collectionsinvoices.penaltypaid
        end) as 'AMOUNT',
        IF(collections.pdc = '1', CONCAT(CONVERT(cheques.checkdate,CHAR), ' ', collectionsinvoices.reftype) ,case
              when collections.collfor='RS' then concat(collections.DEPARTMENT , ' - CUSTOMER DEPOSIT')
              when collections.collfor='WU' then 'OTHER CASH PAYMENTS'
              when collections.collfor='SI' then collectionsinvoices.reftype
        else IF(collectionsaccounts.SUBSIDIARY = '', collectionsaccounts.GLACCTNAME, chartofaccountsubsidiaries.SUBSIDIARYNAME) end) as 'reftype',
        upper(branches.BRANCHNAME) as BRANCHNAME, if (collectionsinvoices.rebate is null, 0, collectionsinvoices.rebate) as rebate,
        if (collectionsinvoices.penaltypaid is null, 0, collectionsinvoices.penaltypaid) as penalty,
        if (cheques.DOCNO is null, '', concat(cheques.BANK,' - ',cheques.BANKBRANCH)) as bankname,
        if (cheques.DOCNO is null, '', cheques.bankacctno) as accountno,
        if (cheques.DOCNO is null, '', cheques.CHECKNO) as checkno, cheques.CHECKDATE as checkdate,
        if (cheques.DOCNO is null, '', '') as accountname,
        if (cheques.DOCNO is null, 0, cheques.AMOUNT) as chequesamount,
        if (creditcards2.DOCNO is null, '', creditcards.creditcardname) as ccname,
        if (creditcards2.DOCNO is null, '', creditcards2.CREDITCARDNO) as ccno,
        if (creditcards2.DOCNO is null, '', creditcards2.CARDEXPIRETEXT) as ccexpiry,
        if (creditcards2.DOCNO is null, 0, creditcards2.AMOUNT) as creditcardsamount,
        '' as ccard,
        '' as ccardrefno,
        0 as ccardamount,
        date(if(collections.valuedate = '0000-00-00', null, collections.valuedate)) as valuedate,
        collections.pdc as pdc,
        if(collections.valuedate between pi_date1 and pi_date2 and collections.pdc=1, 1, 0) as pdc_applied,
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then 'APPLIED PDC'
        when collections.pdc = '1' and collections.valuedate = '0000-00-00' then 'UNAPPLIED PDC'
        else 'CRB'
        end as pdc_ref,
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then 2
        when collections.pdc = '1' and collections.valuedate = '0000-00-00' then 3
        else 1
        end as arrangement,
        j.DEPARTMENTNAME as xxx,
        '' as column1,
        case when collectionsinvoices.reftype like 'AR%' then 'AR'
             when collectionsinvoices.reftype like 'NR%' then 'NR'
        else ''
        end as column2,
        DATE(pi_date1) as date1,
        DATE(pi_date2) as date2,
        if(collections.collfor='SI',0,1),
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then collections.valuedate
        else collections.DOCDATE
        end
        FROM collections
        LEFT OUTER JOIN collectionsinvoices ON collections.COMPANY = collectionsinvoices.COMPANY
        AND collections.DOCNO = collectionsinvoices.DOCNO AND collections.BRANCHCODE = collectionsinvoices.BRANCH
        LEFT OUTER JOIN collectionsaccounts ON collections.COMPANY = collectionsaccounts.COMPANY
        AND collections.DOCNO = collectionsaccounts.DOCNO AND collections.BRANCHCODE = collectionsaccounts.BRANCH
        LEFT OUTER JOIN chartofaccountsubsidiaries ON collectionsaccounts.SUBSIDIARY = chartofaccountsubsidiaries.SUBSIDIARY
        LEFT OUTER JOIN customers ON collections.BPCODE = customers.CUSTNO
        AND collections.COMPANY = customers.COMPANY AND collections.BRANCHCODE = customers.BRANCH
        LEFT OUTER JOIN accountslist ON
        collectionsinvoices.REFNO = accountslist.acctno and collectionsinvoices.refbranch = accountslist.branchcode
        LEFT OUTER JOIN accounts ON collectionsinvoices.COMPANY = accounts.COMPANY
        AND collectionsinvoices.REFNO = accounts.acctno AND collectionsinvoices.BRANCH = accounts.BRANCH
        LEFT OUTER JOIN cheques ON collections.COMPANY = cheques.COMPANY
        AND collections.DOCNO = cheques.DOCNO AND collections.BRANCHCODE = cheques.BRANCH
        LEFT OUTER JOIN creditcards2 ON collections.COMPANY = creditcards2.COMPANY
        AND collections.DOCNO = creditcards2.DOCNO AND collections.BRANCHCODE = creditcards2.BRANCH
        LEFT OUTER JOIN creditcards ON creditcards.creditcard = creditcards2.creditcard
        LEFT OUTER JOIN branches ON collections.BRANCHCODE = branches.BRANCHCODE
        LEFT OUTER JOIN companies f ON f.COMPANYCODE = pi_company
        LEFT OUTER JOIN arinvoices ON collectionsinvoices.COMPANY = arinvoices.COMPANY
        AND collectionsinvoices.BRANCH = arinvoices.BRANCH
        AND collectionsinvoices.REFNO = arinvoices.DOCNO
        LEFT OUTER JOIN departments j
                        ON (collections.DEPARTMENT = j.DEPARTMENT or arinvoices.DEPARTMENT = j.DEPARTMENT or accounts.loantype = j.DEPARTMENT or accountslist.loantype = j.DEPARTMENT)
        WHERE collections.COMPANY = pi_company
              AND collections.BRANCHCODE = pi_branch
              AND (collections.docdate between pi_date1 and pi_date2
                  OR (collections.pdc = 1 and collections.valuedate between pi_date1 and pi_date2))
              AND collections.TRXTYPE NOT IN('CM') AND collections.collfor='WU'
              AND collections.DOCSTAT NOT IN('D')
       ORDER BY collections.DOCDATE, collections.DOCNO;
 INSERT
    INTO dccr (COMPANY,
    BRANCHCODE,
    BPCODE,
    BPNAME,
    ADDRESS,
    DOCNO,
    DOCDATE,
    REMARKS,
    LASTUPDATEDBY,
    REFNO,
    AMOUNT,
    reftype,
    BRANCHNAME,
    rebate,
    penalty,
    bankname,
    accountno,
    checkno,
    checkdate,
    accountname,
    chequesamount,
    ccname,
    ccno,
    ccexpiry,
    creditcardsamount,
    ccard,
    ccardrefno,
    ccardamount,
    valuedate,
    pdc,
    pdc_applied,
    pdc_ref,
    arrangement,
    xxx,
    column1,
    column2,
    date1,
    date2,
    si,
    date3)
SELECT upper(f.COMPANYNAME) as COMPANY,
        collections.BRANCHCODE,
        collections.BPCODE,
        if(collections.BPNAME = '', collections.ADDRESS, collections.BPNAME) as BPNAME,
        collections.ADDRESS,
        collections.DOCNO,
        collections.DOCDATE,
        collections.REMARKS,
        collections.LASTUPDATEDBY,
        case when collections.collfor='RS' then ''
              when collections.collfor='WU' then collections.REFNO
              when collections.collfor='SI' then
              if (collectionsinvoices.reftype like 'NR%', accountslist.REFNO, collectionsinvoices.REFNO)
        else IF(collectionsaccounts.subsidiary = '', collectionsaccounts.REMARKS, 'EXPENSES') end
        as REFNO,
        0 as 'AMOUNT',
        IF(collections.pdc = '1', CONCAT(CONVERT(cheques.checkdate,CHAR), ' ', collectionsinvoices.reftype) ,case when collections.collfor='RS' then 'CUSTOMER DEPOSIT'
              when collections.collfor='WU' then 'OTHERS CASH PAYMENTS'
              when collections.collfor='SI' then collectionsinvoices.reftype
        else IF(collectionsaccounts.SUBSIDIARY = '', collectionsaccounts.GLACCTNAME, chartofaccountsubsidiaries.SUBSIDIARYNAME) end) as 'reftype',
        upper(branches.BRANCHNAME) as BRANCHNAME, 0 as rebate,
        0 as penalty,
        '' as bankname,
        '' as accountno,
        '' as checkno, null as checkdate,
        '' as accountname,
        0 as chequesamount,
        '' as ccname,
        '' as ccno,
        '' as ccexpiry,
        0 as creditcardsamount,
        Upper(if (cashcards2.DOCNO is null, '', cashcards.CASHCARDNAME)) as ccard,
        if (cashcards2.DOCNO is null, '', cashcards2.REFNO) as ccardrefno,
        if (cashcards2.DOCNO is null, 0, cashcards2.AMOUNT) as ccardamount,
        date(if(collections.valuedate = '0000-00-00', null, collections.valuedate)) as valuedate,
        collections.pdc as pdc,
        if(collections.valuedate between pi_date1 and pi_date2 and collections.pdc=1, 1, 0) as pdc_applied,
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then 'APPLIED PDC'
        when collections.pdc = '1' and collections.valuedate = '0000-00-00' then 'UNAPPLIED PDC'
        else 'CRB'
        end as pdc_ref,
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then 2
        when collections.pdc = '1' and collections.valuedate = '0000-00-00' then 3
        else 1
        end as arrangement,
        j.DEPARTMENTNAME as xxx,
        '' as column1,
        case when collectionsinvoices.reftype like 'AR%' then 'AR'
             when collectionsinvoices.reftype like 'NR%' then 'NR'
        else ''
        end as column2,
        DATE(pi_date1) as date1,
        DATE(pi_date2) as date2,
        if(collections.collfor='SI',0,1),
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then collections.valuedate
        else collections.DOCDATE
        end
        FROM collections
        LEFT OUTER JOIN collectionsinvoices ON collections.COMPANY = collectionsinvoices.COMPANY
        AND collections.DOCNO = collectionsinvoices.DOCNO AND collections.BRANCHCODE = collectionsinvoices.BRANCH
        LEFT OUTER JOIN collectionsaccounts ON collections.COMPANY = collectionsaccounts.COMPANY
        AND collections.DOCNO = collectionsaccounts.DOCNO AND collections.BRANCHCODE = collectionsaccounts.BRANCH
        LEFT OUTER JOIN chartofaccountsubsidiaries ON collectionsaccounts.SUBSIDIARY = chartofaccountsubsidiaries.SUBSIDIARY
        LEFT OUTER JOIN customers ON collections.BPCODE = customers.CUSTNO
        AND collections.COMPANY = customers.COMPANY AND collections.BRANCHCODE = customers.BRANCH
        LEFT OUTER JOIN accountslist ON
        collectionsinvoices.REFNO = accountslist.acctno and collectionsinvoices.refbranch = accountslist.branchcode
        LEFT OUTER JOIN accounts ON collectionsinvoices.COMPANY = accounts.COMPANY
        AND collectionsinvoices.REFNO = accounts.acctno AND collectionsinvoices.BRANCH = accounts.BRANCH
        LEFT OUTER JOIN cheques ON collections.COMPANY = cheques.COMPANY
        AND collections.DOCNO = cheques.DOCNO AND collections.BRANCHCODE = cheques.BRANCH
        LEFT OUTER JOIN creditcards2 ON collections.COMPANY = creditcards2.COMPANY
        AND collections.DOCNO = creditcards2.DOCNO AND collections.BRANCHCODE = creditcards2.BRANCH
        LEFT OUTER JOIN creditcards ON creditcards.creditcard = creditcards2.creditcard
        LEFT OUTER JOIN cashcards2 ON collectionsinvoices.COMPANY = cashcards2.COMPANY
        AND collectionsinvoices.DOCNO = cashcards2.DOCNO AND collectionsinvoices.BRANCH = cashcards2.BRANCH
        AND collectionsinvoices.REFNO = cashcards2.REFNO
        LEFT OUTER JOIN cashcards ON cashcards2.cashcard = cashcards.cashcard
        LEFT OUTER JOIN branches ON collections.BRANCHCODE = branches.BRANCHCODE
        LEFT OUTER JOIN companies f ON f.COMPANYCODE = pi_company
        LEFT OUTER JOIN arinvoices ON collectionsinvoices.COMPANY = arinvoices.COMPANY
        AND collectionsinvoices.BRANCH = arinvoices.BRANCH
        AND collectionsinvoices.REFNO = arinvoices.DOCNO
        LEFT OUTER JOIN departments j
                        ON (collections.DEPARTMENT = j.DEPARTMENT or arinvoices.DEPARTMENT = j.DEPARTMENT or accounts.loantype = j.DEPARTMENT or accountslist.loantype = j.DEPARTMENT)
        WHERE collections.COMPANY = pi_company
              AND collections.BRANCHCODE = pi_branch
              AND (collections.docdate between pi_date1 and pi_date2
                  OR (collections.pdc = 1 and collections.valuedate between pi_date1 and pi_date2))
              AND collections.TRXTYPE NOT IN('CM')
              AND collections.DOCSTAT NOT IN('D')
       ORDER BY collections.DOCDATE, collections.DOCNO;
 INSERT
    INTO dccr (COMPANY,
    BRANCHCODE,
    BPCODE,
    BPNAME,
    ADDRESS,
    DOCNO,
    DOCDATE,
    REMARKS,
    LASTUPDATEDBY,
    REFNO,
    AMOUNT,
    reftype,
    BRANCHNAME,
    rebate,
    penalty,
    bankname,
    accountno,
    checkno,
    checkdate,
    accountname,
    chequesamount,
    ccname,
    ccno,
    ccexpiry,
    creditcardsamount,
    ccard,
    ccardrefno,
    ccardamount,
    valuedate,
    pdc,
    pdc_applied,
    pdc_ref,
    arrangement,
    xxx,
    column1,
    column2,
    date1,
    date2,
    si,
    date3)
SELECT upper(f.COMPANYNAME) as COMPANY,
        collections.BRANCHCODE,
        collections.BPCODE,
        if(collections.BPNAME = '', collections.ADDRESS, collections.BPNAME) as BPNAME,
        collections.ADDRESS,
        collections.DOCNO,
        collections.DOCDATE,
        collections.REMARKS,
        collections.LASTUPDATEDBY,
        collectionsinvoices.REFNO as REFNO,
        collectionsinvoices.AMOUNT as 'AMOUNT',
        UPPER(othercharges.CHRGNAME) as 'reftype',
        upper(branches.BRANCHNAME) as BRANCHNAME, 0 as rebate,
        0 as penalty,
        '' as bankname,
        '' as accountno,
        '' as checkno, null as checkdate,
        '' as accountname,
        0 as chequesamount,
        '' as ccname,
        '' as ccno,
        '' as ccexpiry,
        0 as creditcardsamount,
        '' as ccard,
        '' as ccardrefno,
        0 as ccardamount,
        date(if(collections.valuedate = '0000-00-00', null, collections.valuedate)) as valuedate,
        collections.pdc as pdc,
        if(collections.valuedate between pi_date1 and pi_date2 and collections.pdc=1, 1, 0) as pdc_applied,
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then 'APPLIED PDC'
        when collections.pdc = '1' and collections.valuedate = '0000-00-00' then 'UNAPPLIED PDC'
        else 'CRB'
        end as pdc_ref,
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then 2
        when collections.pdc = '1' and collections.valuedate = '0000-00-00' then 3
        else 1
        end as arrangement,
        j.DEPARTMENTNAME as xxx,
        '' as column1,
        '' as column2,
        DATE(pi_date1) as date1,
        DATE(pi_date2) as date2,
        1,
        case
        when collections.pdc = '1' and collections.valuedate between pi_date1 and pi_date2 then collections.valuedate
        else collections.DOCDATE
        end
        FROM collections
        LEFT OUTER JOIN collectionsothercharges as collectionsinvoices ON collections.COMPANY = collectionsinvoices.COMPANY
        AND collections.DOCNO = collectionsinvoices.DOCNO AND collections.BRANCHCODE = collectionsinvoices.BRANCH
        LEFT OUTER JOIN othercharges ON collectionsinvoices.CHRGCODE = othercharges.CHRGCODE
        LEFT OUTER JOIN collectionsaccounts ON collections.COMPANY = collectionsaccounts.COMPANY
        AND collections.DOCNO = collectionsaccounts.DOCNO AND collections.BRANCHCODE = collectionsaccounts.BRANCH
        LEFT OUTER JOIN chartofaccountsubsidiaries ON collectionsaccounts.SUBSIDIARY = chartofaccountsubsidiaries.SUBSIDIARY
        LEFT OUTER JOIN customers ON collections.BPCODE = customers.CUSTNO
        AND collections.COMPANY = customers.COMPANY AND collections.BRANCHCODE = customers.BRANCH
        LEFT OUTER JOIN accountslist ON
        collectionsinvoices.REFNO = accountslist.acctno and collectionsinvoices.refbranch = accountslist.branchcode
        LEFT OUTER JOIN accounts ON collectionsinvoices.COMPANY = accounts.COMPANY
        AND collectionsinvoices.REFNO = accounts.acctno AND collectionsinvoices.BRANCH = accounts.BRANCH
        LEFT OUTER JOIN branches ON collections.BRANCHCODE = branches.BRANCHCODE
        LEFT OUTER JOIN companies f ON f.COMPANYCODE = pi_company
        LEFT OUTER JOIN arinvoices ON collectionsinvoices.COMPANY = arinvoices.COMPANY
        AND collectionsinvoices.BRANCH = arinvoices.BRANCH
        AND collectionsinvoices.REFNO = arinvoices.DOCNO
        LEFT OUTER JOIN departments j
                        ON (collectionsinvoices.DEPARTMENT = j.DEPARTMENT)
        WHERE collections.COMPANY = pi_company
              AND collections.BRANCHCODE = pi_branch
              AND (collections.docdate between pi_date1 and pi_date2
                  OR (collections.pdc = 1 and collections.valuedate between pi_date1 and pi_date2))
              AND collections.TRXTYPE NOT IN('CM')
              AND collections.DOCSTAT NOT IN('D') and collectionsinvoices.AMOUNT <> 0
       ORDER BY collections.DOCDATE, collections.DOCNO;
 INSERT
    INTO dccr (COMPANY,
    BRANCHCODE,
    BPCODE,
    BPNAME,
    ADDRESS,
    DOCNO,
    DOCDATE,
    REMARKS,
    LASTUPDATEDBY,
    REFNO,
    AMOUNT,
    reftype,
    BRANCHNAME,
    rebate,
    penalty,
    bankname,
    accountno,
    checkno,
    checkdate,
    accountname,
    chequesamount,
    ccname,
    ccno,
    ccexpiry,
    creditcardsamount,
    ccard,
    ccardrefno,
    ccardamount,
    valuedate,
    pdc,
    pdc_applied,
    pdc_ref,
    arrangement,
    xxx,
    column1,
    column2,
    date1,
    date2,
    si,
    date3)
SELECT upper(f.COMPANYNAME) as COMPANY,
        docnosx.BRANCH,
        '',
        '' as BPNAME,
        '',
        docnosx.DOCNO,
        docnosx.DOCDATE,
        docnosx.REMARKS,
        docnosx.LASTUPDATEDBY,
        '' as REFNO,
        0 as 'AMOUNT',
        'CANCELLED' as 'reftype',
        upper(branches.BRANCHNAME) as BRANCHNAME, 0 as rebate,
        0 as penalty,
        '' as bankname,
        '' as accountno,
        '' as checkno, null as checkdate,
        '' as accountname,
        0 as chequesamount,
        '' as ccname,
        '' as ccno,
        '' as ccexpiry,
        0 as creditcardsamount,
        '' as ccard,
        '' as ccardrefno,
        0 as ccardamount,
        null as valuedate,
        '' as pdc,
        0 as pdc_applied,
        'CRB' as pdc_ref,
        1 as arrangement,
        '' as xxx,
        '' as column1,
        '' as column2,
        DATE(pi_date1) as date1,
        DATE(pi_date2) as date2,
        0,
        docnosx.DOCDATE
        FROM docnosx
        LEFT OUTER JOIN branches ON docnosx.BRANCH = branches.BRANCHCODE
        LEFT OUTER JOIN companies f ON f.COMPANYCODE = pi_company
        WHERE docnosx.COMPANY = pi_company
              AND docnosx.BRANCH = pi_branch
              AND docnosx.docdate between pi_date1 and pi_date2
              AND docnosx.DOCTYPE IN('OFFICIALRECEIPT', 'INCOMINGPAYMENT')
       ORDER BY docnosx.DOCDATE, docnosx.DOCNO;
Select * from dccr ORDER BY DOCDATE, DOCNO;
END $$

DELIMITER ;