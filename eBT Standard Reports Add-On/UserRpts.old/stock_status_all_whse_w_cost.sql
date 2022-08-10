DELIMITER $$

DROP PROCEDURE IF EXISTS `stock_status_all_whse_w_cost` $$
CREATE PROCEDURE `stock_status_all_whse_w_cost`(IN pi_company VARCHAR(30),
IN pi_branch VARCHAR(30), IN pi_whse VARCHAR(30),
IN pi_brandcode VARCHAR(30), IN pi_itemgroup VARCHAR(30), IN pi_itemcode VARCHAR(30), IN pi_date1 VARCHAR(30))
BEGIN
CREATE TEMPORARY TABLE  `itemref` (
    `ITEMCODE` varchar(30) NULL default '',
    `ITEMDESC` varchar(500) NULL default '',
    `ITEMCLASSNAME` varchar(30) NULL default '',
    `ITEMCLASS` varchar(30) NULL default '',
    `ITEMGROUPNAME` varchar(30) NULL default '',
    `ITEMGROUP` varchar(30) NULL default '',
    `MAKE` varchar(30) NULL default '',
     PRIMARY KEY  (`ITEMCODE`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  INSERT
    INTO itemref (ITEMCODE,ITEMDESC,ITEMCLASSNAME,ITEMGROUPNAME,MAKE,ITEMGROUP,ITEMCLASS)
      SELECT a.ITEMCODE, a.ITEMDESC, b.ITEMCLASSNAME, c.ITEMGROUPNAME, a.MAKE, a.ITEMGROUP , a.ITEMCLASS
        from items a
        left outer join itemclasses b on a.ITEMCLASS = b.ITEMCLASS
        left outer join itemgroups c on a.ITEMGROUP = c.ITEMGROUP;
CREATE TEMPORARY TABLE  `mergetables` (
    `COMPANY` varchar(30) NULL default '',
    `BRANCH` varchar(500) NULL default '',
    `REFDATE` DATE NULL,
    `ITEMCODE` varchar(30) NULL default '',
    `QTY` NUMERIC(18,6) NULL default '0',
    `COST` NUMERIC(18,6) NULL default '0',
    `WAREHOUSE` varchar(500) NULL default ''
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  INSERT
    INTO mergetables (COMPANY,BRANCH,REFDATE,ITEMCODE,QTY,COST,WAREHOUSE)
      SELECT a.COMPANY,a.BRANCH,a.REFDATE,a.ITEMCODE, sum(a.QTY*-1) as QTY,
        sum((a.QTY*a.COSTPRICE)*-1) as COST, a.warehouse AS WAREHOUSE
        from stockcard a
        WHERE a.company = pi_company AND a.BRANCH = pi_branch AND a.refdate > pi_date1
        AND (pi_whse='' or (pi_whse<>'' AND a.warehouse = pi_whse))
        AND REFTYPE IN ('GR','IQ+','GT+','GA+','PDN','AP','RT','CM','POR')
        AND (pi_itemcode='' or (pi_itemcode<>'' and a.ITEMCODE = pi_itemcode))
        group by a.REFDATE, a.ITEMCODE, a.warehouse;

  INSERT
    INTO mergetables (COMPANY,BRANCH,REFDATE,ITEMCODE,QTY,COST,WAREHOUSE)
      SELECT a.COMPANY,a.BRANCH,a.REFDATE,a.ITEMCODE, sum(a.QTY*-1) as QTY,
        sum(a.QTY*a.COSTPRICE*-1) as COST, a.warehouse AS WAREHOUSE
        from stockcard a
        WHERE a.company = pi_company AND a.BRANCH = pi_branch AND a.REFDATE > pi_date1
        AND (pi_whse='' or (pi_whse<>'' AND a.warehouse = pi_whse))
        AND REFTYPE IN ('GI','IQ-','GT-','GA-','DN','SI','IN','PRT','ACM','POI')
        AND (pi_itemcode='' or (pi_itemcode<>'' and a.ITEMCODE = pi_itemcode))
        group by a.REFDATE, a.ITEMCODE, a.warehouse;

  INSERT
    INTO mergetables (COMPANY,BRANCH,REFDATE,ITEMCODE,QTY,COST,WAREHOUSE)
      SELECT a.COMPANY,a.BRANCH,a.REFDATE,a.ITEMCODE,
        sum(a.QTY) as QTY, sum(round(a.STOCKVALUE/a.QTY,6)*a.QTY) as COST, a.warehouse AS WAREHOUSE
        from stockcardcosting a
        WHERE a.company = pi_company AND a.BRANCH = pi_branch
        AND (pi_whse='' or (pi_whse<>'' AND a.warehouse = pi_whse))
        AND (pi_itemcode='' or (pi_itemcode<>'' and a.ITEMCODE = pi_itemcode))
        AND QTY<>0
        group by a.REFDATE, a.ITEMCODE, a.warehouse;


SELECT
       upper(c.companyname) as COMPANY, a.BRANCH, upper(d.BRANCHNAME) as BRANCHNAME, UPPER(b.ITEMGROUPNAME) as DEPARTMENT,
       upper(j.warehousename) as INVENTORY_TYPE, date(pi_date1) as DATE1, upper(i.MAKENAME) as BRANDCODE, a.ITEMCODE as ITEMCODE,
       b.ITEMDESC as ITEMDESC,
       if (sum(a.QTY) is null, 0, sum(a.QTY)) as ONSTOCK,
       round(if (sum(a.COST) is null, 0, sum(a.COST)),2) as TOTALCOST,
       round(if (sum(a.COST) is null, 0, sum(a.COST)) / if (sum(a.QTY) is null, 0, sum(a.QTY)),6) as COST,
       upper(b.ITEMCLASSNAME) as ITEMGROUP
FROM mergetables a
LEFT OUTER JOIN itemref b ON a.ITEMCODE = b.ITEMCODE
LEFT OUTER JOIN companies c on a.company = c.companycode
LEFT OUTER JOIN branches d ON d.COMPANYCODE = a.COMPANY and d.BRANCHCODE = a.BRANCH
LEFT OUTER JOIN makes i on b.MAKE = i.MAKE
LEFT OUTER JOIN warehouses j on j.company=a.company and j.branch=a.branch and j.warehouse = a.warehouse
WHERE a.company = pi_company AND a.BRANCH = pi_branch
      AND (pi_brandcode='' or (pi_brandcode<>'' and b.MAKE = pi_brandcode))
      AND (pi_itemgroup='' or (pi_itemgroup<>'' and b.ITEMGROUP = pi_itemgroup))
      AND (pi_itemcode='' or (pi_itemcode<>'' and a.ITEMCODE = pi_itemcode))
      group by a.ITEMCODE, a.warehouse
      HAVING SUM(a.QTY) <> 0 order by a.ITEMCODE;
END $$


DELIMITER ;