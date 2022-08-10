DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_lguboa_pan11` $$
CREATE PROCEDURE `sp_lguboa_pan11`(IN pi_company VARCHAR(30), IN pi_branch VARCHAR(30), IN pi_date1 VARCHAR(30), IN pi_date2 VARCHAR(30), IN pi_mode VARCHAR(30), IN pi_mode2 VARCHAR(30))
BEGIN
DECLARE v1 INT DEFAULT 0;
DECLARE v_doctype varchar(10) default '';
DECLARE v_reference2 varchar(50);

if (pi_mode='crj') then
    SELECT c.branchcode, c.branchname, d.u_province, d.u_municipality, month(docdate) as docmonth, docdate, docno, remarks, u_boaindex, x.u_accountant,
      sum(debit01) as debit01,
      sum(credit01) as credit01,
      sum(credit02) as credit02,
      sum(credit03) as credit03,
      sum(credit04) as credit04,
      sum(credit05) as credit05,
      sum(credit06) as credit06,
      sum(credit07) as credit07,
      sum(credit08) as credit08,
      sum(credit09) as credit09,
      sum(debitbd) as debitbd,
      sum(creditbd) as creditbd,
      sum(debitsundry) as debitsundry,
      sum(creditsundry) as creditsundry from (
    SELECT a.docdate, a.docno, a.remarks, a.u_accountant, if (b.profitcenter4='',b.profitcenter5,0) as u_boaindex,
        if(b.profitcenter4='' and b.itemno='1-01-01-010',b.debit - b.credit,0) as debit01,
        if(b.profitcenter4='' and b.itemno='1-03-01-020',b.credit - b.debit,0) as credit01,
        if(b.profitcenter4='' and b.itemno='2-02-01-070',b.credit - b.debit,0) as credit02,
        if(b.profitcenter4='' and b.itemno='4-01-03-030',b.credit - b.debit,0) as credit03,
        if(b.profitcenter4='' and b.itemno='4-01-01-050',b.credit - b.debit,0) as credit04,
        if(b.profitcenter4='' and b.itemno='4-01-02-040',b.credit - b.debit,0) as credit05,
        if(b.profitcenter4='' and b.itemno='4-01-05-020',b.credit - b.debit,0) as credit06,
        if(b.profitcenter4='' and b.itemno='4-02-01-010',b.credit - b.debit,0) as credit07,
        if(b.profitcenter4='' and b.itemno='4-02-01-020',b.credit - b.debit,0) as credit08,
        if(b.profitcenter4='' and b.itemno='4-02-01-040',b.credit - b.debit,0) as credit09,
        if(b.profitcenter4='BD DEBIT',b.debit - b.credit,0) as debitbd,
        if(b.profitcenter4='BD CREDIT',b.credit - b.debit,0) as creditbd,
        if(b.profitcenter4 in ('','BD DEBIT','BD CREDIT') and b.itemno in ('1-01-01-010','1-03-01-020','2-02-01-070','4-01-03-030','4-01-01-050','4-01-02-040','4-01-05-020','4-02-01-010','4-02-01-020','4-02-01-040','1-01-02-010'),0,b.debit) as debitsundry,
        if(b.profitcenter4 in ('','BD DEBIT','BD CREDIT') and b.itemno in ('1-01-01-010','1-03-01-020','2-02-01-070','4-01-03-030','4-01-01-050','4-01-02-040','4-01-05-020','4-02-01-010','4-02-01-020','4-02-01-040','1-01-02-010'),0,b.credit) as creditsundry FROM journalvouchers a
      inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
      where a.company='lgu' and a.branch='100' and a.reference2='Cash Receipt' and a.docdate between pi_date1 and pi_date2) as x
        left join branches c on c.companycode=pi_company and c.branchcode=pi_branch
        left join companies d on d.companycode=pi_company
      group by docdate, docno, u_boaindex;
elseif (pi_mode='cashdj') then
    SELECT c.branchcode, c.branchname, d.u_province, d.u_municipality, month(docdate) as docmonth, docdate, docno, obno, dvno, remarks, x.u_accountant,
      sum(credit01) as c01,
      sum(credit02) as c02,
      sum(credit03) as c03,
      sum(creditsundry) as creditsundry,
      sum(debit01) as d01,
      sum(debit02) as d02,
      sum(debit03) as d03,
      sum(debitsundry) as debitsundry
      from (
    SELECT a.docdate, a.docno, c.u_osno as obno, a.reference1 as dvno, c.u_remarks as remarks, a.u_accountant,
        if(b.glacctno='1-01-01-020',b.glcredit - b.gldebit,0) as credit01,
        if(b.glacctno='1-03-05-020',b.glcredit - b.gldebit,0) as credit02,
        if(b.glacctno='2-02-01-010',b.glcredit - b.gldebit,0) as credit03,
        if(b.glacctno in ('1-01-01-020','1-03-05-020','2-02-01-010','5-02-99-990','5-02-14-030','5-02-99-080'),0,b.glcredit)as creditsundry,
        if(b.glacctno='5-02-99-990',b.gldebit - b.glcredit,0) as debit01,
        if(b.glacctno='5-02-14-030',b.gldebit - b.glcredit,0) as debit02,
        if(b.glacctno='5-02-99-080',b.gldebit - b.glcredit,0) as debit03,
        if(b.glacctno in ('1-01-01-020','1-03-05-020','2-02-01-010','5-02-99-990','5-02-14-030','5-02-99-080'),0,b.gldebit) as debitsundry
    FROM journalvouchers a
      inner join u_lgucashds c on c.company=a.company and c.branch=a.branch and c.u_jevno=a.docno and c.docstatus<>'D'
      inner join journalentryitems b on b.company=a.company and b.branch=a.branch and b.docno=a.docno and b.doctype='JV' and b.profitcenter4=pi_mode2
      where a.company=pi_company and a.branch=pi_branch and a.reference2='Cash Disbursement' and a.docdate between pi_date1 and pi_date2) as x
        left join branches c on c.companycode=pi_company and c.branchcode=pi_branch
        left join companies d on d.companycode=pi_company
      group by docdate, docno;
elseif (pi_mode='checkdj') then
    SELECT c.branchcode, c.branchname, d.u_province, d.u_municipality, month(docdate) as docmonth, docdate, docno, obno, checkno, pvno, remarks, x.u_accountant,
      sum(credit01) as c01,
      sum(credit02) as c02,
      sum(debit01) as d01,
      sum(debit02) as d02,
      sum(debit03) as d03,
      sum(debit04) as d04,
      sum(debitsundry) as debitsundry,
      sum(creditsundry) as creditsundry from (
    SELECT a.docdate, a.docno, '' as obno, '' as checkno, '' as pvno, a.bpname as remarks, a.u_accountant,
        if(b.glacctno='1-01-02-010',b.glcredit - b.gldebit,0) as credit01,
        if(b.glacctno='2-02-01-010',b.glcredit - b.gldebit,0) as credit02,
        if(b.glacctno='5-01-01-010',b.gldebit - b.glcredit,0) as debit01,
        if(b.glacctno='5-02-14-030',b.gldebit - b.glcredit,0) as debit02,
        if(b.glacctno='5-02-99-080',b.gldebit - b.glcredit,0) as debit03,
        if(b.glacctno='5-02-99-990',b.gldebit - b.glcredit,0) as debit04,
        if(b.glacctno in ('1-01-02-010','2-02-01-010','5-01-01-010','5-02-14-030','5-02-99-080','5-02-99-990'),0,b.gldebit) as debitsundry,
        if(b.glacctno in ('1-01-02-010','2-02-01-010','5-01-01-010','5-02-14-030','5-02-99-080','5-02-99-990'),0,b.glcredit) as creditsundry
      FROM payments a
        inner join journalentryitems b on b.company=a.company and b.branch=a.branchcode and b.docno=a.docno and b.doctype = 'PY'
      where a.company=pi_company and a.branchcode=pi_branch and a.docdate between pi_date1 and pi_date2  union all
    SELECT a.docdate, a.docno, c.u_osno as obno, c.u_checkno as checkno, a.reference1 as pvno, concat(c.u_bpname,' / ',c.u_remarks) as remarks, a.u_accountant,
        if(b.glacctno='1-01-02-010',b.glcredit - b.gldebit,0) as credit01,
        if(b.glacctno='2-02-01-010',b.glcredit - b.gldebit,0) as credit02,
        if(b.glacctno='5-01-01-010',b.gldebit - b.glcredit,0) as debit01,
        if(b.glacctno='5-02-14-030',b.gldebit - b.glcredit,0) as debit02,
        if(b.glacctno='5-02-99-080',b.gldebit - b.glcredit,0) as debit03,
        if(b.glacctno='5-02-99-990',b.gldebit - b.glcredit,0) as debit04,
        if(b.glacctno in ('1-01-02-010','2-02-01-010','5-01-01-010','5-02-14-030','5-02-99-080','5-02-99-990'),0,b.gldebit) as debitsundry,
        if(b.glacctno in ('1-01-02-010','2-02-01-010','5-01-01-010','5-02-14-030','5-02-99-080','5-02-99-990'),0,b.glcredit) as creditsundry
      FROM journalvouchers a
        inner join u_lgucheckds c on c.company=a.company and c.branch=a.branch and c.u_jevno=a.docno and c.docstatus<>'D'
        inner join journalentryitems b on b.company=a.company and b.branch=a.branch and b.docno=a.docno and b.doctype = 'JV'
      where a.company=pi_company and a.branch=pi_branch and a.reference2='Check Disbursement'  and a.docdate between pi_date1 and pi_date2
    ) as x
        left join branches c on c.companycode=pi_company and c.branchcode=pi_branch
        left join companies d on d.companycode=pi_company
      group by docdate, docno;




end if;

END $$

DELIMITER ;