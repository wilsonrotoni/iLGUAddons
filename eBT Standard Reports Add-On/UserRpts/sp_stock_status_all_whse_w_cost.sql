DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_stock_status_all_whse_w_cost` $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_stock_status_all_whse_w_cost`(
IN pi_company VARCHAR(30),
IN pi_branch VARCHAR(30),
IN pi_whse VARCHAR(30),
IN pi_supplier VARCHAR(30),
IN pi_brandcode VARCHAR(30),
IN pi_itemgroup VARCHAR(30),
IN pi_itemclass VARCHAR(30),
IN pi_itemsubclass VARCHAR(30),
IN pi_itemcode VARCHAR(30),
IN pi_date1 VARCHAR(30),
IN pi_output VARCHAR(10))
BEGIN
  DECLARE v_sdate DATE;
  DECLARE v_edate DATE;
  SET v_sdate = date(concat(substring(pi_date1,1,7),'-01'));
  SET v_edate = date(pi_date1);
  
DROP TEMPORARY TABLE IF EXISTS `itemref`;
CREATE TEMPORARY TABLE  `itemref` (
    `ITEMCODE` varchar(30) NULL default '',
    `ITEMDESC` varchar(500) NULL default '',
    `ITEMCLASSNAME` varchar(100) NULL default '',
    `ITEMCLASS` varchar(30) NULL default '',
    `ITEMSUBCLASSNAME` varchar(100) NULL default '',
    `ITEMSUBCLASS` varchar(30) NULL default '',
    `ITEMGROUPNAME` varchar(100) NULL default '',
    `ITEMGROUP` varchar(30) NULL default '',
    `SUPPNAME` varchar(100) NULL default '',
    `SUPPNO` varchar(30) NULL default '',
    `MAKE` varchar(30) NULL default '',
    `MAKENAME` varchar(100) NULL default '',
     PRIMARY KEY  (`ITEMCODE`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO itemref (ITEMCODE,ITEMDESC,SUPPNO,SUPPNAME,MAKE,MAKENAME,ITEMGROUP,ITEMGROUPNAME,ITEMCLASS,ITEMCLASSNAME,ITEMSUBCLASS,ITEMSUBCLASSNAME)
      SELECT a.ITEMCODE, a.ITEMDESC,a.PREFERREDSUPPNO, e.SUPPNAME, a.MAKE, f.MAKENAME, a.ITEMGROUP, c.ITEMGROUPNAME, a.ITEMCLASS, b.ITEMCLASSNAME, a.ITEMSUBCLASS, d.ITEMSUBCLASSNAME
        from items a
        left outer join itemclasses b on a.ITEMCLASS = b.ITEMCLASS
        left outer join itemsubclasses d on d.ITEMCLASS='' and a.ITEMSUBCLASS = d.ITEMSUBCLASS
        left outer join itemgroups c on a.ITEMGROUP = c.ITEMGROUP
        left outer join suppliers e on a.PREFERREDSUPPNO = e.SUPPNO
        left outer join makes f on a.MAKE = f.MAKE
      WHERE (pi_supplier='' or (pi_supplier<>'' and a.PREFERREDSUPPNO = pi_supplier))
        AND (pi_brandcode='' or (pi_brandcode<>'' and a.MAKE = pi_brandcode))
        AND (pi_itemgroup='' or (pi_itemgroup<>'' and a.ITEMGROUP = pi_itemgroup))
        AND (pi_itemclass='' or (pi_itemclass<>'' and a.ITEMCLASS = pi_itemclass))
        AND (pi_itemsubclass='' or (pi_itemsubclass<>'' and a.ITEMSUBCLASS = pi_itemsubclass))
        AND (pi_itemcode='' or (pi_itemcode<>'' and a.ITEMCODE = pi_itemcode));

DROP TEMPORARY TABLE IF EXISTS `mergetables`;
      CREATE TEMPORARY TABLE  `mergetables` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(500) NULL default '',
    `REFDATE` DATE NULL,
    `SUPPNO` varchar(30) NULL default '',
    `ITEMCODE` varchar(30) NULL default '',
    `BEGINQTY` NUMERIC(18,6) NULL default '0',
    `SALES` NUMERIC(18,6) NULL default '0',
    `BEGINCOST` NUMERIC(18,6) NULL default '0',
    `INQTY` NUMERIC(18,6) NULL default '0',
    `INCOST` NUMERIC(18,6) NULL default '0',
    `INQTYPURCH` NUMERIC(18,6) NULL default '0',
    `INCOSTPURCH` NUMERIC(18,6) NULL default '0',
    `INQTYSALES` NUMERIC(18,6) NULL default '0',
    `INCOSTSALES` NUMERIC(18,6) NULL default '0',
    `INQTYINV` NUMERIC(18,6) NULL default '0',
    `INCOSTINV` NUMERIC(18,6) NULL default '0',
    `OUTQTY` NUMERIC(18,6) NULL default '0',
    `OUTCOST` NUMERIC(18,6) NULL default '0',
    `OUTQTYPURCH` NUMERIC(18,6) NULL default '0',
    `OUTCOSTPURCH` NUMERIC(18,6) NULL default '0',
    `OUTQTYSALES` NUMERIC(18,6) NULL default '0',
    `OUTCOSTSALES` NUMERIC(18,6) NULL default '0',
    `OUTQTYINV` NUMERIC(18,6) NULL default '0',
    `OUTCOSTINV` NUMERIC(18,6) NULL default '0',
    `WAREHOUSE` varchar(500) NULL default ''
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  INSERT
    INTO mergetables (COMPANY,BRANCH,REFDATE,SUPPNO,ITEMCODE,BEGINQTY,BEGINCOST,
      INQTY,INCOST,INQTYPURCH,INCOSTPURCH,INQTYSALES,INCOSTSALES,INQTYINV,INCOSTINV,
      OUTQTY,OUTCOST,OUTQTYPURCH,OUTCOSTPURCH,OUTQTYSALES,OUTCOSTSALES,OUTQTYINV,OUTCOSTINV,WAREHOUSE)
      SELECT a.COMPANY,a.BRANCH,a.REFDATE,b.SUPPNO, a.ITEMCODE,
        sum(IF (a.refdate < v_sdate, a.QTY,0)),
        sum(IF (a.refdate < v_sdate, a.TOTALCOST,0)),
        sum(IF (a.refdate >= v_sdate and QTY>0, a.QTY,0)),
        sum(IF (a.refdate >= v_sdate and QTY>0, a.TOTALCOST,0)),
        sum(IF (a.refdate >= v_sdate and QTY>0 and a.reftype in ('PDN','AP'), a.QTY,0)),
        sum(IF (a.refdate >= v_sdate and QTY>0 and a.reftype in ('PDN','AP'), a.TOTALCOST,0)),
        sum(IF (a.refdate >= v_sdate and QTY>0 and a.reftype in ('RT','CM'), a.QTY,0)),
        sum(IF (a.refdate >= v_sdate and QTY>0 and a.reftype in ('RT','CM'), a.TOTALCOST,0)),
        sum(IF (a.refdate >= v_sdate and QTY>0 and a.reftype not in ('PDN','AP','RT','CM'), a.QTY,0)),
        sum(IF (a.refdate >= v_sdate and QTY>0 and a.reftype not in ('PDN','AP','RT','CM'), a.TOTALCOST,0)),
        sum(IF (a.refdate >= v_sdate and QTY<0, a.QTY,0)),
        sum(IF (a.refdate >= v_sdate and QTY<0, a.TOTALCOST,0)),
        sum(IF (a.refdate >= v_sdate and QTY<0 and a.reftype in ('PRT','ACM'), a.QTY,0)),
        sum(IF (a.refdate >= v_sdate and QTY<0 and a.reftype in ('PRT','ACM'), a.TOTALCOST,0)),
        sum(IF (a.refdate >= v_sdate and QTY<0 and a.reftype in ('DN','SI'), a.QTY,0)),
        sum(IF (a.refdate >= v_sdate and QTY<0 and a.reftype in ('DN','SI'), a.TOTALCOST,0)),
        sum(IF (a.refdate >= v_sdate and QTY<0 and a.reftype not in ('PRT','ACM','DN','SI'), a.QTY,0)),
        sum(IF (a.refdate >= v_sdate and QTY<0 and a.reftype not in ('PRT','ACM','DN','SI'), a.TOTALCOST,0)),
        a.warehouse AS WAREHOUSE
        from stockcard a, itemref b
        WHERE b.itemcode=a.itemcode
        AND a.company = pi_company
        AND (pi_branch='' or (pi_branch<>'' and a.BRANCH = pi_branch))
        AND a.refdate <= v_edate
        AND (pi_whse='' or (pi_whse<>'' AND a.warehouse = pi_whse))
        AND REFTYPE NOT IN ('SR')
        group by a.REFDATE, a.ITEMCODE, a.warehouse;

  

  INSERT
    INTO mergetables (COMPANY,BRANCH,REFDATE, SUPPNO,ITEMCODE,BEGINQTY,BEGINCOST,INQTY,INCOST,INQTYINV,INCOSTINV,OUTQTY,OUTCOST,OUTQTYINV,OUTCOSTINV,WAREHOUSE)
      SELECT a.COMPANY,a.BRANCH,a.REFDATE,b.SUPPNO,a.ITEMCODE,
        0,
        sum(IF (a.refdate < v_sdate, a.TOTALCOST,0)),
        0,
        sum(IF (a.refdate >= v_sdate and QTY>0, a.TOTALCOST,0)),
        0,
        sum(IF (a.refdate >= v_sdate and QTY>0, a.TOTALCOST,0)),
        0,
        sum(IF (a.refdate >= v_sdate and QTY<0, a.TOTALCOST,0)),
        0,
        sum(IF (a.refdate >= v_sdate and QTY<0, a.TOTALCOST,0)),
        a.warehouse AS WAREHOUSE
        from stockcard a, itemref b
        WHERE b.itemcode=a.itemcode
        AND a.company = pi_company
        AND (pi_branch='' or (pi_branch<>'' and a.BRANCH = pi_branch))
        AND a.REFDATE <= v_edate
        AND (pi_whse='' or (pi_whse<>'' AND a.warehouse = pi_whse))
        AND REFTYPE IN ('SR')
        group by a.REFDATE, a.ITEMCODE, a.warehouse;



if (pi_output='') then
SELECT
       c.companyname as COMPANYNAME, a.BRANCH, d.BRANCHNAME as BRANCHNAME,
       upper(j.warehousename) as INVENTORY_TYPE,
       date(pi_date1) as DATE1,
       a.ITEMCODE,
       b.ITEMDESC,
       b.MAKE,
       b.MAKENAME,
       b.SUPPNO,
       b.SUPPNAME,
       b.ITEMGROUP,
       b.ITEMGROUPNAME,
       b.ITEMCLASS,
       b.ITEMCLASSNAME,
       b.ITEMSUBCLASS,
       b.ITEMSUBCLASSNAME,
       sum(a.BEGINQTY+a.INQTY+a.OUTQTY) as ONSTOCK,
       sum(a.BEGINCOST+a.INCOST+a.OUTCOST) as TOTALCOST,
       sum(a.BEGINCOST+a.INCOST+a.OUTCOST) / sum(a.BEGINQTY+a.INQTY+a.OUTQTY) as COST
FROM mergetables a
LEFT OUTER JOIN itemref b ON a.ITEMCODE = b.ITEMCODE
LEFT OUTER JOIN companies c on a.company = c.companycode
LEFT OUTER JOIN branches d ON d.COMPANYCODE = a.COMPANY and d.BRANCHCODE = a.BRANCH
LEFT OUTER JOIN warehouses j on j.company=a.company and j.branch=a.branch and j.warehouse = a.warehouse
WHERE a.company = pi_company
      group by a.ITEMCODE, a.BRANCH, a.warehouse  order by a.ITEMCODE, a.BRANCH;

elseif (pi_output='withbegin') then
SELECT
       c.companyname as COMPANYNAME, a.BRANCH, d.BRANCHNAME as BRANCHNAME,
       upper(j.warehousename) as WAREHOUSE,
       date(pi_date1) as DATE1,
       a.ITEMCODE,
       b.ITEMDESC,
       b.MAKE,
       b.MAKENAME,
       b.SUPPNO,
       b.SUPPNAME,
       b.ITEMGROUP,
       b.ITEMGROUPNAME,
       b.ITEMCLASS,
       b.ITEMCLASSNAME,
       b.ITEMSUBCLASS,
       b.ITEMSUBCLASSNAME,
       ifnull( sum(a.BEGINQTY), 0) as BEGINQTY,
       round(ifnull( sum(a.BEGINCOST), 0),2) as BEGINVALUE,
       round(ifnull(if (sum(a.BEGINCOST) is null, 0, sum(a.BEGINCOST)) / if (sum(a.BEGINQTY) is null, 0, sum(a.BEGINQTY)),0),6) as BEGINCOST,
       ifnull( sum(a.INQTY), 0) as INQTY,
       round(if (sum(a.INCOST) is null, 0, sum(a.INCOST)),2) as INVALUE,
       round(ifnull(if (sum(a.INCOST) is null, 0, sum(a.INCOST)) / if (sum(a.INQTY) is null, 0, sum(a.INQTY)),0),6) as INCOST,
       ifnull(sum(a.OUTQTY), 0)*-1 as OUTQTY,
       round(ifnull(sum(a.OUTCOST), 0),2)*-1 as OUTVALUE,
       round(ifnull(if (sum(a.OUTCOST) is null, 0, sum(a.OUTCOST)) / if (sum(a.OUTQTY) is null, 0, sum(a.OUTQTY)),0),6) as OUTCOST,
       ifnull( sum(a.BEGINQTY), 0) + ifnull( sum(a.INQTY), 0) + ifnull(sum(a.OUTQTY), 0) as ENDQTY,
       round(ifnull( sum(a.BEGINCOST), 0) + ifnull( sum(a.INCOST), 0) + ifnull(sum(a.OUTCOST), 0),2) as ENDVALUE,
       round(ifnull((ifnull( sum(a.BEGINCOST), 0) + ifnull( sum(a.INCOST), 0) + ifnull(sum(a.OUTCOST), 0)) / (ifnull( sum(a.BEGINQTY), 0) + ifnull( sum(a.INQTY), 0) + ifnull(sum(a.OUTQTY), 0)),0),6) as ENDCOST,
       ifnull( sum(a.INQTYPURCH), 0) as INQTYPURCH,
       round(if (sum(a.INCOSTPURCH) is null, 0, sum(a.INCOSTPURCH)),2) as INVALUEPURCH,
       round(ifnull(if (sum(a.INCOSTPURCH) is null, 0, sum(a.INCOSTPURCH)) / if (sum(a.INQTYPURCH) is null, 0, sum(a.INQTYPURCH)),0),6) as INCOSTPURCH,
       ifnull( sum(a.INQTYSALES), 0) as INQTYSALES,
       round(if (sum(a.INCOSTSALES) is null, 0, sum(a.INCOSTSALES)),2) as INVALUESALES,
       round(ifnull(if (sum(a.INCOSTSALES) is null, 0, sum(a.INCOSTSALES)) / if (sum(a.INQTYSALES) is null, 0, sum(a.INQTYSALES)),0),6) as INCOSTSALES,
       ifnull( sum(a.INQTYINV), 0) as INQTYINV,
       round(if (sum(a.INCOSTINV) is null, 0, sum(a.INCOSTINV)),2) as INVALUEINV,
       round(ifnull(if (sum(a.INCOSTINV) is null, 0, sum(a.INCOSTINV)) / if (sum(a.INQTYINV) is null, 0, sum(a.INQTYINV)),0),6) as INCOSTINV,
       ifnull(sum(a.OUTQTYPURCH), 0)*-1 as OUTQTYPURCH,
       round(ifnull(sum(a.OUTCOSTPURCH), 0),2)*-1 as OUTVALUEPURCH,
       round(ifnull(if (sum(a.OUTCOSTPURCH) is null, 0, sum(a.OUTCOSTPURCH)) / if (sum(a.OUTQTYPURCH) is null, 0, sum(a.OUTQTYPURCH)),0),6) as OUTCOSTPURCH,
       ifnull(sum(a.OUTQTYSALES), 0)*-1 as OUTQTYSALES,
       round(ifnull(sum(a.OUTCOSTSALES), 0),2)*-1 as OUTVALUESALES,
       round(ifnull(if (sum(a.OUTCOSTSALES) is null, 0, sum(a.OUTCOSTSALES)) / if (sum(a.OUTQTYSALES) is null, 0, sum(a.OUTQTYSALES)),0),6) as OUTCOSTSALES,
       ifnull(sum(a.OUTQTYINV), 0)*-1 as OUTQTYINV,
       round(ifnull(sum(a.OUTCOSTINV), 0),2)*-1 as OUTVALUEINV,
       round(ifnull(if (sum(a.OUTCOSTINV) is null, 0, sum(a.OUTCOSTINV)) / if (sum(a.OUTQTYINV) is null, 0, sum(a.OUTQTYINV)),0),6) as OUTCOSTINV

FROM mergetables a
LEFT OUTER JOIN itemref b ON a.ITEMCODE = b.ITEMCODE
LEFT OUTER JOIN companies c on a.company = c.companycode
LEFT OUTER JOIN branches d ON d.COMPANYCODE = a.COMPANY and d.BRANCHCODE = a.BRANCH
LEFT OUTER JOIN warehouses j on j.company=a.company and j.branch=a.branch and j.warehouse = a.warehouse
WHERE a.company = pi_company
      group by a.ITEMCODE, a.BRANCH, a.warehouse
      HAVING SUM(a.BEGINQTY) <> 0 or SUM(a.INQTY) <> 0 or SUM(a.OUTQTY) <> 0 order by a.ITEMCODE, a.BRANCH;
elseif (pi_output='totalcost') then
  SELECT sum(a.BEGINCOST) + sum(a.INCOST) + sum(a.OUTCOST) as TOTALCOST
    FROM mergetables a
      LEFT OUTER JOIN itemref b ON a.ITEMCODE = b.ITEMCODE
      LEFT OUTER JOIN companies c on a.company = c.companycode
      LEFT OUTER JOIN branches d ON d.COMPANYCODE = a.COMPANY and d.BRANCHCODE = a.BRANCH
      LEFT OUTER JOIN warehouses j on j.company=a.company and j.branch=a.branch and j.warehouse = a.warehouse
    WHERE a.company = pi_company;
ELSEIF (PI_OUTPUT='totalcost2') then
select sum(round(totalcost,2)) as totalcost from (
SELECT
       c.companyname as COMPANYNAME, a.BRANCH, d.BRANCHNAME as BRANCHNAME,
       upper(j.warehousename) as INVENTORY_TYPE,
       date(pi_date1) as DATE1,
       a.ITEMCODE,
       b.ITEMDESC,
       b.MAKE,
       b.MAKENAME,
       b.SUPPNO,
       b.SUPPNAME,
       b.ITEMGROUP,
       b.ITEMGROUPNAME,
       b.ITEMCLASS,
       b.ITEMCLASSNAME,
       b.ITEMSUBCLASS,
       b.ITEMSUBCLASSNAME,
       sum(a.BEGINQTY+a.INQTY+a.OUTQTY) as ONSTOCK,
       sum(a.BEGINCOST+a.INCOST+a.OUTCOST) as TOTALCOST,
       sum(a.BEGINCOST+a.INCOST+a.OUTCOST) / sum(a.BEGINQTY+a.INQTY+a.OUTQTY) as COST
FROM mergetables a
LEFT OUTER JOIN itemref b ON a.ITEMCODE = b.ITEMCODE
LEFT OUTER JOIN companies c on a.company = c.companycode
LEFT OUTER JOIN branches d ON d.COMPANYCODE = a.COMPANY and d.BRANCHCODE = a.BRANCH
LEFT OUTER JOIN warehouses j on j.company=a.company and j.branch=a.branch and j.warehouse = a.warehouse
WHERE a.company = pi_company
      group by a.ITEMCODE, a.BRANCH, a.warehouse ) as x ORDER BY BRANCH, WAREHOUSE, ITEMDESC;

end if;

END $$

DELIMITER ;