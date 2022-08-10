DELIMITER $$

DROP PROCEDURE IF EXISTS `sales_analysis` $$
CREATE PROCEDURE `sales_analysis`(IN comp_id varchar(200),
                             IN branch_no varchar(200),
                             IN date_fm varchar(200),
                             IN date_to varchar(200))
BEGIN
DECLARE v_companyname varchar(100);
DECLARE v_branchname varchar(100);
CREATE TEMPORARY TABLE  `main` (
    `company` varchar(100) NULL default '',
    `branchname` varchar(100) NULL default '',
    `datefm` DATE NULL,
    `dateto` DATE NULL,
    `docdate` DATE NULL,
    `docduedate` DATE NULL,
    `docno` varchar(100) NULL default '',
    `bpcode` varchar(100) NULL default '',
    `bpname` varchar(500) NULL default '',
    `salesperson` varchar(500) NULL default '',
    `itemcode` varchar(100) NULL default '',
    `itemdesc` varchar(500) NULL default '',
    `whscode` varchar(100) NULL default '',
    `serialno` varchar(5000) NULL default '',
    `unit` varchar(100) NULL default '',
    `quantity` NUMERIC(18,6) NULL default '0',
    `unitprice` NUMERIC(18,6) NULL default '0',
    `itemcost` NUMERIC(18,6) NULL default '0',
    `vatcode` varchar(100) NULL default '',
    `vatamount` NUMERIC(18,6) NULL default '0',
    `totalcost` NUMERIC(18,6) NULL default '0',
    `linetotal` NUMERIC(18,6) NULL default '0',
    `gpamount` NUMERIC(18,6) NULL default '0',
    `percentage` NUMERIC(18,6) NULL default '0',
    `orderx` NUMERIC(18,6) NULL default '0',
    INDEX IDX_1 (`orderx`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
select upper(a.companyname), upper(concat(b.branchcode, ' - ', b.branchname)) into v_companyname, v_branchname from companies a, branches b  where b.companycode=a.companycode and a.companycode=comp_id and b.branchcode=branch_no;
INSERT
    INTO main (
    company,
    branchname,
    datefm,
    dateto,
    docdate,
    docduedate,
    docno,
    bpcode,
    bpname,
    salesperson,
    itemcode,
    itemdesc,
    whscode,
    serialno,
    unit,
    quantity,
    unitprice,
    itemcost,
    vatcode,
    vatamount,
    totalcost,
    linetotal,
    gpamount,
    percentage,
    orderx)
SELECT
  v_companyname as company,
  v_branchname as branchname,
  DATE(date_fm) as datefm,
  DATE(date_to) as dateto,
  t0.docdate,
  t0.docduedate,
  t0.docno,
  t0.bpcode,
  t0.bpname,
  sp.SALESPERSONNAME,
  if(t1.doctype = 'S', t1.GLACCTNO, t1.itemcode),
  t1.itemdesc,
  t1.whscode,
  concat('SN: ',replace(mid(t1.sbnids,locate('|',t1.sbnids)+1,locate('|',t1.sbnids,locate('|',t1.sbnids)+1)-locate('|',t1.sbnids)-1),'`',','), ' ',
        'CH: ', replace(mid(sbnids, locate('|',sbnids,locate('|',sbnids)+1)+1,locate('|',sbnids,locate('|',sbnids,locate('|',sbnids)+1)+1)-locate('|',sbnids,locate('|',sbnids)+1)-1),'`',',')) as serialno,
  i.uomsa,
  sum(t1.quantity),
  t1.unitprice,
  t1.itemcost,
  t1.vatcode,
  t1.vatamount,
  sum(t1.itemcost * t1.quantity),
  if(t1.doctype = 'S', sum(t1.unitprice), sum(t1.unitprice * t1.quantity)),
  if(t1.doctype = 'S', 0, (sum(t1.unitprice * t1.quantity)- sum(t1.itemcost * t1.quantity))) as gpamount,
  if(t1.doctype = 'S', 0, if((((sum(t1.unitprice * t1.quantity)- sum(t1.itemcost * t1.quantity)) / (sum(t1.itemcost * t1.quantity))) * 100) is null, 0,
  (((sum(t1.unitprice * t1.quantity)- sum(t1.itemcost * t1.quantity)) / (sum(t1.itemcost * t1.quantity))) * 100))) as percentage,
  1
FROM arinvoices t0
  left outer join arinvoiceitems t1 ON t0.company = t1.company and t0.branch = t1.branch and t0.docid = t1.docid
  left outer join departments t3 ON t0.department = t3.department
  LEFT OUTER JOIN paymentterms pt ON t0.PAYMENTTERM = pt.PAYMENTTERM
  LEFT OUTER JOIN items I ON t1.itemcode = i.itemcode
  LEFT OUTER JOIN customers f on t0.BPCODE = f.CUSTNO
  LEFT OUTER JOIN salespersons sp on f.SALESPERSON = sp.SALESPERSON
WHERE t0.company = comp_id
      AND t0.branch = branch_no
      AND t0.docdate between date_fm and date_to
      AND t0.DOCSTATUS NOT IN('D')
  Group by t0.DOCNO, t1.itemcode;
INSERT
    INTO main (
    company,
    branchname,
    datefm,
    dateto,
    docdate,
    docduedate,
    docno,
    bpcode,
    bpname,
    salesperson,
    itemcode,
    itemdesc,
    whscode,
    serialno,
    unit,
    quantity,
    unitprice,
    itemcost,
    vatcode,
    vatamount,
    totalcost,
    linetotal,
    gpamount,
    percentage,
    orderx)
SELECT
  v_companyname as company,
  v_branchname as branchname,
  DATE(date_fm) as datefm,
  DATE(date_to) as dateto,
  t0.docdate,
  t0.docduedate,
  t0.docno,
  t0.bpcode,
  t0.bpname,
  sp.SALESPERSONNAME,
  if(t1.doctype = 'S', t1.GLACCTNO, t1.itemcode),
  t1.itemdesc,
  t1.whscode,
  concat('SN: ',replace(mid(t1.sbnids,locate('|',t1.sbnids)+1,locate('|',t1.sbnids,locate('|',t1.sbnids)+1)-locate('|',t1.sbnids)-1),'`',','), ' ',
        'CH: ', replace(mid(sbnids, locate('|',sbnids,locate('|',sbnids)+1)+1,locate('|',sbnids,locate('|',sbnids,locate('|',sbnids)+1)+1)-locate('|',sbnids,locate('|',sbnids)+1)-1),'`',',')) as serialno,
  i.uomsa,
  0-(sum(t1.quantity)),
  t1.unitprice,
  t1.itemcost,
  t1.vatcode,
  0-(t1.vatamount),
  0-(sum(t1.itemcost * t1.quantity)),
  0-(if(t1.doctype = 'S', sum(t1.unitprice), sum(t1.unitprice * t1.quantity))),
  if(t1.doctype = 'S', 0, 0-((sum(t1.unitprice * t1.quantity)- sum(t1.itemcost * t1.quantity)))) as gpamount,
  if(t1.doctype = 'S', 0, if(0-(((sum(t1.unitprice * t1.quantity)- sum(t1.itemcost * t1.quantity)) / (sum(t1.itemcost * t1.quantity))) * 100) is null, 0,
  0-(((sum(t1.unitprice * t1.quantity)- sum(t1.itemcost * t1.quantity)) / (sum(t1.itemcost * t1.quantity))) * 100))) as percentage,
  2
FROM arcreditmemos t0
  left outer join arcreditmemoitems t1 ON t0.company = t1.company and t0.branch = t1.branch and t0.docid = t1.docid
  left outer join departments t3 ON t0.department = t3.department
  LEFT OUTER JOIN paymentterms pt ON t0.PAYMENTTERM = pt.PAYMENTTERM
  LEFT OUTER JOIN items I ON t1.itemcode = i.itemcode
  LEFT OUTER JOIN customers f on t0.BPCODE = f.CUSTNO
  LEFT OUTER JOIN salespersons sp on f.SALESPERSON = sp.SALESPERSON
WHERE t0.company = comp_id
      AND t0.branch = branch_no
      AND t0.docdate between date_fm and date_to
      AND t0.DOCSTATUS NOT IN('D')
 Group by t0.DOCNO, t1.itemcode;
select * from main order by orderx;
END $$

DELIMITER ;