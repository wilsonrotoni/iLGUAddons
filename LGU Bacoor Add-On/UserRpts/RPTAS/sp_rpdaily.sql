DELIMITER $$

DROP PROCEDURE IF EXISTS `aringay2`.`sp_rpdaily` $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_rpdaily`(
                                      IN pi_company VARCHAR(30),
                                      IN pi_branch VARCHAR(30),
				                              IN pi_date1 VARCHAR(30),
				                              IN pi_date2 VARCHAR(30))
BEGIN


DROP TEMPORARY TABLE IF EXISTS `tmp_current`;
CREATE TEMPORARY TABLE  `tmp_current` (
    `company` varchar(250) NULL default '',
    `branch` varchar(250) NULL default '',
    `arpno` varchar(250) NULL default '',
    `ornumber` varchar(250) NULL default '',
    `taxdue` numeric(18,6) NULL default '0',
    `discount` numeric(18,6) NULL default '0',
    `penalty` numeric(18,6) NULL default '0',
    `totaltax` numeric(18,6) NULL default '0'

  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TEMPORARY TABLE IF EXISTS `tmp_preceeding`;
CREATE TEMPORARY TABLE  `tmp_preceeding` (
    `company` varchar(250) NULL default '',
    `branch` varchar(250) NULL default '',
    `arpno` varchar(250) NULL default '',
    `ornumber` varchar(250) NULL default '',
    `taxdue` numeric(18,6) NULL default '0',
    `discount` numeric(18,6) NULL default '0',
    `penalty` numeric(18,6) NULL default '0',
    `totaltax` numeric(18,6) NULL default '0'

  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TEMPORARY TABLE IF EXISTS `tmp_prev`;
CREATE TEMPORARY TABLE  `tmp_prev` (
    `company` varchar(250) NULL default '',
    `branch` varchar(250) NULL default '',
    `arpno` varchar(250) NULL default '',
    `ornumber` varchar(250) NULL default '',
    `taxdue` numeric(18,6) NULL default '0',
    `discount` numeric(18,6) NULL default '0',
    `penalty` numeric(18,6) NULL default '0',
    `totaltax` numeric(18,6) NULL default '0'

  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO tmp_current(company,branch,arpno,ornumber,taxdue,discount,penalty,totaltax)
SELECT
a.company,
a.branch,
d.u_arpno,
a.docno,
sum(d.u_taxdue),
sum(d.u_taxdisc + d.u_taxdiscadj),
sum(d.u_penalty + d.u_penaltyadj),
sum((d.u_taxdue + d.u_penalty + d.u_penaltyadj) - (d.u_taxdisc + d.u_taxdiscadj))
FROM u_lgupos a
INNER join u_lgubills b on a.u_billno = b.docno and a.company = b.company and a.branch = b.branch
INNER join u_rptaxes as c on b.u_appno = c.docno and a.company = c.company and a.branch = c.branch
inner join u_rptaxarps as d on c.docid = d.docid and d.u_yrfr >= year(curdate()) and d.u_selected = 1
where a.u_profitcenter = 'RP' and A.u_status not in('CN','D') and a.u_date between pi_date1 and pi_date2 and a.u_partialpay = 0
and a.company = pi_company and a.branch = pi_branch group by d.u_arpno;


INSERT INTO tmp_preceeding(company,branch,arpno,ornumber,taxdue,discount,penalty,totaltax)
SELECT
a.company,
a.branch,
d.u_arpno,
a.docno,
sum(d.u_taxdue),
sum(d.u_taxdisc + d.u_taxdiscadj),
sum(d.u_penalty + d.u_penaltyadj),
sum((d.u_taxdue + d.u_penalty + d.u_penaltyadj) - (d.u_taxdisc + d.u_taxdiscadj))
FROM u_lgupos a
INNER join u_lgubills b on a.u_billno = b.docno and a.company = b.company and a.branch = b.branch
INNER join u_rptaxes as c on b.u_appno = c.docno and a.company = c.company and a.branch = c.branch
inner join u_rptaxarps as d on c.docid = d.docid and d.u_yrfr = (year(curdate())-1) and d.u_selected = 1
where a.u_profitcenter = 'RP' and A.u_status not in('CN','D') and a.u_date between pi_date1 and pi_date2 and a.u_partialpay = 0
and a.company = pi_company and a.branch = pi_branch group by d.u_arpno;

INSERT INTO tmp_prev(company,branch,arpno,ornumber,taxdue,discount,penalty,totaltax)
SELECT
a.company,
a.branch,
d.u_arpno,
a.docno,
sum(d.u_taxdue),
sum(d.u_taxdisc + d.u_taxdiscadj),
sum(d.u_penalty + d.u_penaltyadj),
sum((d.u_taxdue + d.u_penalty + d.u_penaltyadj) - (d.u_taxdisc + d.u_taxdiscadj))
FROM u_lgupos a
INNER join u_lgubills b on a.u_billno = b.docno and a.company = b.company and a.branch = b.branch
INNER join u_rptaxes as c on b.u_appno = c.docno and a.company = c.company and a.branch = c.branch
INNER join u_rptaxarps as d on c.docid = d.docid and d.u_yrfr < (year(curdate())-1) and d.u_selected = 1
WHERE a.u_profitcenter = 'RP' and A.u_status not in('CN','D') and a.u_date between pi_date1 and pi_date2 and a.u_partialpay = 0
and a.company = pi_company and a.branch = pi_branch group by d.u_arpno;




SELECT
A.DOCNO
,A.U_DATE
,D.U_ARPNO
,IF(D.U_KIND = 'LAND',E.U_BARANGAY,IF(D.U_KIND = 'BUILDING',E2.U_BARANGAY,E3.U_BARANGAY)) AS BARANGAY
,IF(D.U_KIND = 'LAND',IF(E.U_OWNERCOMPANYNAME = '',CONCAT(E.U_OWNERLASTNAME,', ',E.U_OWNERFIRSTNAME,' ',E.U_OWNERMIDDLENAME),E.U_OWNERCOMPANYNAME),IF(D.U_KIND = 'BUILDING',IF(E2.U_OWNERCOMPANYNAME = '',CONCAT(E2.U_OWNERLASTNAME,', ',E2.U_OWNERFIRSTNAME,' ',E2.U_OWNERMIDDLENAME),E3.U_OWNERCOMPANYNAME),IF(E3.U_OWNERCOMPANYNAME = '',CONCAT(E3.U_OWNERLASTNAME,', ',E3.U_OWNERFIRSTNAME,' ',E3.U_OWNERMIDDLENAME),E3.U_OWNERCOMPANYNAME))) AS OWNERNAME
,IF(D.U_KIND = 'LAND',F.U_CLASS,IF(D.U_KIND = 'BUILDING',F2.U_CLASS,F3.U_ACTUALUSE)) AS CLASS
,IF(D.U_KIND = 'LAND',G.NAME,IF(D.U_KIND = 'BUILDING',G2.NAME,G3.NAME)) AS CLASS1
,TC.TAXDUE AS CURRENT_TAXDUE
,TC.DISCOUNT AS CURRENT_DISCOUNT
,TC.PENALTY AS CURRENT_PENALTY
,TC.TOTALTAX AS CURRENT_TOTALTAX
,TPG.TAXDUE AS PRECEEDING_TAXDUE
,TPG.PENALTY AS PRECEEDING_PENALTY
,TPG.TOTALTAX AS PRECEEDING_TOTALTAX
,TPV.TAXDUE AS PREV_TAXDUE
,TPV.PENALTY AS PREV_PENALTY
,TPV.TOTALTAX AS PREV_TOTALTAX
,(TPG.TOTALTAX + TPV.TOTALTAX + TC.TOTALTAX) AS TOTALCOLLECT
FROM u_lgupos a
INNER join u_lgubills b on a.u_billno = b.docno and a.company = b.company and a.branch = b.branch
INNER join u_rptaxes as c on b.u_appno = c.docno and a.company = c.company and a.branch = c.branch
INNER join u_rptaxarps as d on c.docid = d.docid and d.u_selected = 1 and D.company = c.company and D.branch = c.branch
LEFT JOIN U_RPFAAS1 E ON D.U_ARPNO = E.DOCNO and D.company = E.company and D.branch = E.branch
LEFT JOIN U_RPFAAS2 E2 ON D.U_ARPNO = E2.DOCNO and D.company = E2.company and D.branch = E2.branch
LEFT JOIN U_RPFAAS3 E3 ON D.U_ARPNO = E3.DOCNO and D.company = E3.company and D.branch = E3.branch
LEFT JOIN U_RPFAAS1A F ON F.U_ARPNO = E.DOCNO and F.company = E.company and F.branch = E.branch
LEFT JOIN U_RPFAAS2A F2 ON F2.U_ARPNO = E2.DOCNO and F2.company = E2.company and F2.branch = E2.branch
LEFT JOIN U_RPFAAS3A F3 ON F3.U_ARPNO = E3.DOCNO and F3.company = E3.company and F3.branch = E3.branch
LEFT JOIN U_RPLANDS G ON F.U_CLASS = G.CODE
LEFT JOIN U_RPLANDS G2 ON F2.U_CLASS = G2.CODE
LEFT JOIN U_RPACTUSES G3 ON F3.U_ACTUALUSE = G3.CODE
LEFT JOIN TMP_CURRENT TC ON TC.ORNUMBER = A.DOCNO AND TC.ARPNO = D.U_ARPNO AND TC.COMPANY = pi_company AND TC.BRANCH = pi_branch
LEFT JOIN TMP_PRECEEDING TPG ON TPG.ORNUMBER = A.DOCNO AND TPG.ARPNO = D.U_ARPNO AND TPG.COMPANY = pi_company AND TPG.BRANCH = pi_branch
LEFT JOIN TMP_PREV TPV ON TPV.ORNUMBER = A.DOCNO AND TPV.ARPNO = D.U_ARPNO AND TPV.COMPANY = pi_company AND TPV.BRANCH = pi_branch
WHERE a.u_profitcenter = 'RP' and A.u_status not in('CN','D') and a.u_date between pi_date1 and pi_date2 and a.u_partialpay = 0
and a.company = pi_company and a.branch = pi_branch GROUP BY D.U_ARPNO;












END $$

DELIMITER ;