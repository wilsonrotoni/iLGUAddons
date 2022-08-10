DELIMITER $$

DROP PROCEDURE IF EXISTS `sales_book` $$
CREATE PROCEDURE `sales_book`(IN comp_id varchar(200),
                             IN branch_no varchar(200),
                             IN date_fm varchar(200),
                             IN date_to varchar(200))
BEGIN
DECLARE v_companyname varchar(100);
DECLARE v_branchname varchar(100);
CREATE TEMPORARY TABLE  `arinvoiceotherchargesitems_TEMP` (
    `COMPANY` varchar(100) NOT NULL default '',
    `BRANCH` varchar(100) NOT NULL default '',
    `DOCID` varchar(100) NOT NULL default '',
    `AMOUNT` NUMERIC(18,6) NOT NULL default '0'
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT
    INTO arinvoiceotherchargesitems_TEMP (COMPANY,BRANCH,DOCID,AMOUNT)
      SELECT a.COMPANY,a.BRANCH,a.DOCID, sum(a.AMOUNT) as AMOUNT
        from arinvoiceotherchargesitems a
        where a.company = comp_id and a.branch = branch_no
        group by a.DOCID;
CREATE TEMPORARY TABLE  `arcreditmemootherchargesitems_TEMP` (
    `COMPANY` varchar(100) NOT NULL default '',
    `BRANCH` varchar(100) NOT NULL default '',
    `DOCID` varchar(100) NOT NULL default '',
    `AMOUNT` NUMERIC(18,6) NOT NULL default '0'
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT
    INTO arcreditmemootherchargesitems_TEMP (COMPANY,BRANCH,DOCID,AMOUNT)
      SELECT a.COMPANY,a.BRANCH,a.DOCID, sum(a.AMOUNT) as AMOUNT
        from arcreditmemootherchargesitems a
        where a.company = comp_id and a.branch = branch_no
        group by a.DOCID;
CREATE TEMPORARY TABLE  `main` (
    `company` varchar(100) NULL default '',
    `branchname` varchar(100) NULL default '',
    `departmentname` varchar(100) NULL default '',
    `ownertype` varchar(100) NULL default '',
    `datefm` DATE NULL,
    `dateto` DATE NULL,
    `docdate` DATE NULL,
    `docno` varchar(100) NULL default '',
    `u_zone` varchar(100) NULL default '',
    `u_fgroup` varchar(100) NULL default '',
    `bpcode` varchar(100) NULL default '',
    `bpname` varchar(500) NULL default '',
    `itemcode` varchar(100) NULL default '',
    `whscode` varchar(100) NULL default '',
    `itemdesc` varchar(500) NULL default '',
    `serialno` varchar(5000) NULL default '',
    `docduedate` DATE NULL,
    `paymentterm` NUMERIC(18,6) NULL default '0',
    `quantity` NUMERIC(18,6) NULL default '0',
    `itemcost` NUMERIC(18,6) NULL default '0',
    `itemcost2` NUMERIC(18,6) NULL default '0',
    `vatcode` varchar(100) NULL default '',
    `linetotal` NUMERIC(18,6) NULL default '0',
    `vatamount` NUMERIC(18,6) NULL default '0',
    `DownF.Charge` NUMERIC(18,6) NULL default '0',
    `PNValue` NUMERIC(18,6) NULL default '0',
    `gpamount` NUMERIC(18,6) NULL default '0',
    `percentage` NUMERIC(18,6) NULL default '0',
    `fowner` varchar(100) NULL default '',
    `ACCTMA` NUMERIC(18,6) NULL default '0',
    `ACCTDPAMOUNT` NUMERIC(18,6) NULL default '0',
    `ACCTLCP` NUMERIC(18,6) NULL default '0',
    `ACCTREBATE` NUMERIC(18,6) NULL default '0',
    `UFI1` NUMERIC(18,6) NULL default '0',
    `UFI2` NUMERIC(18,6) NULL default '0',
    `UFI3` NUMERIC(18,6) NULL default '0',
    `UFI4` NUMERIC(18,6) NULL default '0',
    `s_gl_code` varchar(100) NULL default '',
    `s_code_desc` varchar(500) NULL default '',
    `s_amount` NUMERIC(18,6) NULL default '0',
    `orderx` NUMERIC(18,6) NULL default '0',
    `unitprice` NUMERIC(18,6) NULL default '0',
    `unit` varchar(100) NULL default '',
    INDEX IDX_1 (`orderx`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
select upper(a.companyname), upper(concat(b.branchcode, ' - ', b.branchname)) into v_companyname, v_branchname from companies a, branches b  where b.companycode=a.companycode and a.companycode=comp_id and b.branchcode=branch_no;
INSERT
    INTO main (
    company,
    branchname,
    departmentname,
    ownertype,
    datefm,
    dateto,
    docdate,
    docno,
    u_zone,
    u_fgroup,
    bpcode,
    bpname,
    itemcode,
    whscode,
    itemdesc,
    serialno,
    docduedate,
    paymentterm,
    quantity,
    itemcost,
    itemcost2,
    vatcode,
    linetotal,
    vatamount,
    `DownF.Charge` ,
    PNValue,
    gpamount,
    percentage,
    fowner,
    ACCTMA,
    ACCTDPAMOUNT,
    ACCTLCP,
    ACCTREBATE,
    UFI1,
    UFI2,
    UFI3,
    UFI4,
    s_gl_code,
    s_code_desc,
    s_amount,orderx,unitprice,unit)
SELECT
  v_companyname as company,
  v_branchname as branchname,
  upper(t3.departmentname) as departmentname,
  '' as ownertype,
  DATE(date_fm) as datefm,
  DATE(date_to) as dateto,
  t0.docdate,
  t0.docno,
  '',
  '',
  t0.bpcode,
  t0.bpname,
  if(t1.doctype = 'S', t1.GLACCTNO, t1.itemcode),
  t1.whscode,
  t1.itemdesc,
  concat('SN: ',replace(mid(t1.sbnids,locate('|',t1.sbnids)+1,locate('|',t1.sbnids,locate('|',t1.sbnids)+1)-locate('|',t1.sbnids)-1),'`',','), ' ',
        'CH: ', replace(mid(sbnids, locate('|',sbnids,locate('|',sbnids)+1)+1,locate('|',sbnids,locate('|',sbnids,locate('|',sbnids)+1)+1)-locate('|',sbnids,locate('|',sbnids)+1)-1),'`',',')) as serialno,
  t0.docduedate,
  0 as paymentterm,
  sum(t1.quantity),
  sum(t1.itemcost * t1.quantity),
  0,
  t1.vatcode,
  sum(t1.linetotal) + IF(tx.amount IS NULL, 0, tx.amount) as linetotal,
  t1.vatamount,
  0,
  0 as 'PNValue',
  0 as gpamount,
  0 as percentage,
  pt.PAYMENTTERMNAME as fowner,
  0,
  0 as ACCTDPAMOUNT,
  0 as ACCTLCP,
  0 as ACCTREBATE,
  0 as UFI1,
  0 as UFI2,
  0 as UFI3,
  0 as UFI4,
  '' s_gl_code,
  '' s_code_desc,
  0 s_amount, 1, t1.unitprice, i.uomsa
FROM arinvoices t0
  left outer join arinvoiceitems t1 ON t0.company = t1.company and t0.branch = t1.branch and t0.docid = t1.docid
  left outer join arinvoiceotherchargesitems_TEMP tx ON t0.company = tx.company and t0.branch = tx.branch and t0.docid = tx.docid
  left outer join departments t3 ON t0.department = t3.department
  LEFT OUTER JOIN paymentterms pt ON t0.PAYMENTTERM = pt.PAYMENTTERM
  LEFT OUTER JOIN items I ON t1.itemcode = i.itemcode
WHERE t0.company = comp_id
      AND t0.branch = branch_no
      AND t0.docdate between date_fm and date_to
      AND t0.DOCSTATUS NOT IN('D')
  Group by t0.DOCNO, t1.itemcode;
INSERT
    INTO main (
    company,
    branchname,
    departmentname,
    ownertype,
    datefm,
    dateto,
    docdate,
    docno,
    u_zone,
    u_fgroup,
    bpcode,
    bpname,
    itemcode,
    whscode,
    itemdesc,
    serialno,
    docduedate,
    paymentterm,
    quantity,
    itemcost,
    itemcost2,
    vatcode,
    linetotal,
    vatamount,
    `DownF.Charge` ,
    PNValue,
    gpamount,
    percentage,
    fowner,
    ACCTMA,
    ACCTDPAMOUNT,
    ACCTLCP,
    ACCTREBATE,
    UFI1,
    UFI2,
    UFI3,
    UFI4,
    s_gl_code,
    s_code_desc,
    s_amount,orderx,unitprice,unit)
SELECT
  v_companyname as company,
  v_branchname as branchname,
  upper(t3.departmentname) as departmentname,
  '' as ownertype,
  DATE(date_fm) as datefm,
  DATE(date_to) as dateto,
  t0.docdate,
  t0.docno,
  '',
  '',
  t0.bpcode,
  t0.bpname,
  if(t1.doctype = 'S', t1.GLACCTNO, t1.itemcode),
  t1.whscode,
  t1.itemdesc,
  concat('SN: ',replace(mid(t1.sbnids,locate('|',t1.sbnids)+1,locate('|',t1.sbnids,locate('|',t1.sbnids)+1)-locate('|',t1.sbnids)-1),'`',','), ' ',
        'CH: ', replace(mid(sbnids, locate('|',sbnids,locate('|',sbnids)+1)+1,locate('|',sbnids,locate('|',sbnids,locate('|',sbnids)+1)+1)-locate('|',sbnids,locate('|',sbnids)+1)-1),'`',',')) as serialno,
  t0.docduedate,
  0 as paymentterm,
  0-(sum(t1.quantity)),
  0-(sum(t1.itemcost * t1.quantity)) AS itemcost,
  0,
  t1.vatcode,
  0-(sum(t1.linetotal) + IF(tx.amount IS NULL, 0, tx.amount)) as linetotal,
  0-(t1.vatamount),
  0 'DownF.Charge',
  0 as 'PNValue',
  (sum(t1.linetotal) - t1.itemcost) as gpamount,
  0 as percentage,
  pt.PAYMENTTERMNAME as fowner,
  0 as ACCTMA,
  0 as ACCTDPAMOUNT,
  0 as ACCTLCP,
  0 as ACCTREBATE,
  0 as UFI1,
  0 as UFI2,
  0 as UFI3,
  0 as UFI4,
  '' s_gl_code,
  '' s_code_desc,
  0 s_amount, 4,
  t1.unitprice, i.uomsa
FROM arcreditmemos t0
  left outer join arcreditmemoitems t1 ON t0.company = t1.company and t0.branch = t1.branch and t0.docid = t1.docid
  left outer join arcreditmemootherchargesitems_TEMP tx ON t0.company = tx.company and t0.branch = tx.branch and t0.docid = tx.docid
  left outer join departments t3 ON t0.department = t3.department
  LEFT OUTER JOIN paymentterms pt ON t0.PAYMENTTERM = pt.PAYMENTTERM
  LEFT OUTER JOIN items I ON t1.itemcode = i.itemcode
WHERE t0.company = comp_id
      AND t0.branch = branch_no
      AND t0.docdate between date_fm and date_to
      AND t0.DOCSTATUS NOT IN('D')
 Group by t0.DOCNO, t1.itemcode;
select * from main order by orderx;
END $$

DELIMITER ;