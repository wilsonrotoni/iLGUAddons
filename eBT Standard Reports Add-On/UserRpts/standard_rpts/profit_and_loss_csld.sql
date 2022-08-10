DELIMITER $$

DROP PROCEDURE IF EXISTS `profit_and_loss_csld` $$
CREATE PROCEDURE `profit_and_loss_csld`(
   IN pi_company VARCHAR(50),
   IN pi_branch VARCHAR(50),
   IN pi_date1 VARCHAR(50),
   IN pi_date2 VARCHAR(50),
   IN pi_zero VARCHAR(50))

BEGIN
  DECLARE v_CompanyCode       varchar(50);
  DECLARE v_CompanyName       varchar(255);
  DECLARE v_BranchCode        varchar(50);
  DECLARE v_BranchName        varchar(255);
  DECLARE v_Done              integer;
  DECLARE v_Record_Found      numeric(10,0);

  DECLARE v_Temp_CompanyCode  varchar(50);
  DECLARE v_Temp_BranchType   varchar(10);
  DECLARE v_Temp_BranchCode   varchar(50);
  DECLARE v_Temp_BranchName   varchar(255);

  DECLARE v_Curr_seqno        numeric(10,0);
  DECLARE v_Temp_curr_branch  numeric(10,0);
  DECLARE v_Temp_acctcode     varchar(100);
  DECLARE v_Temp_level        numeric(10,0);
  DECLARE v_Temp_l1_acctcode  varchar(100);
  DECLARE v_Temp_l1_acctname  varchar(255);
  DECLARE v_Temp_l1_level     numeric(10,0);
  DECLARE v_Temp_l1_HD        varchar(50);
  DECLARE v_Temp_l2_acctcode  varchar(100);
  DECLARE v_Temp_l2_acctname  varchar(255);
  DECLARE v_Temp_l2_level     numeric(10,0);
  DECLARE v_Temp_l2_HD        varchar(50);
  DECLARE v_Temp_l3_acctcode  varchar(100);
  DECLARE v_Temp_l3_acctname  varchar(255);
  DECLARE v_Temp_l3_level     numeric(10,0);
  DECLARE v_Temp_l3_HD        varchar(50);
  DECLARE v_Temp_l4_acctcode  varchar(100);
  DECLARE v_Temp_l4_acctname  varchar(255);
  DECLARE v_Temp_l4_level     numeric(10,0);
  DECLARE v_Temp_l4_HD        varchar(50);
  DECLARE v_Temp_l5_acctcode  varchar(100);
  DECLARE v_Temp_l5_acctname  varchar(255);
  DECLARE v_Temp_l5_level     numeric(10,0);
  DECLARE v_Temp_l5_HD        varchar(50);
  DECLARE v_Temp_GLDEBIT      numeric(20,2);
  DECLARE v_Temp_GLCREDIT     numeric(20,2);
  DECLARE v_Temp_branchcode01 varchar(100);
  DECLARE v_Temp_branchname01 varchar(255);
  DECLARE v_Temp_GLDEBIT01    numeric(20,2);
  DECLARE v_Temp_GLCREDIT01   numeric(20,2);
  DECLARE v_Temp_branchcode02 varchar(100);
  DECLARE v_Temp_branchname02 varchar(255);
  DECLARE v_Temp_GLDEBIT02    numeric(20,2);
  DECLARE v_Temp_GLCREDIT02   numeric(20,2);
  DECLARE v_Temp_branchcode03 varchar(100);
  DECLARE v_Temp_branchname03 varchar(255);
  DECLARE v_Temp_GLDEBIT03    numeric(20,2);
  DECLARE v_Temp_GLCREDIT03   numeric(20,2);
  DECLARE v_Temp_branchcode04 varchar(100);
  DECLARE v_Temp_branchname04 varchar(255);
  DECLARE v_Temp_GLDEBIT04    numeric(20,2);
  DECLARE v_Temp_GLCREDIT04   numeric(20,2);
  DECLARE v_Temp_branchcode05 varchar(100);
  DECLARE v_Temp_branchname05 varchar(255);
  DECLARE v_Temp_GLDEBIT05    numeric(20,2);
  DECLARE v_Temp_GLCREDIT05   numeric(20,2);
  DECLARE v_Temp_branchcode06 varchar(100);
  DECLARE v_Temp_branchname06 varchar(255);
  DECLARE v_Temp_GLDEBIT06    numeric(20,2);
  DECLARE v_Temp_GLCREDIT06   numeric(20,2);
  DECLARE v_Temp_branchcode07 varchar(100);
  DECLARE v_Temp_branchname07 varchar(255);
  DECLARE v_Temp_GLDEBIT07    numeric(20,2);
  DECLARE v_Temp_GLCREDIT07   numeric(20,2);
  DECLARE v_Temp_branchcode08 varchar(100);
  DECLARE v_Temp_branchname08 varchar(255);
  DECLARE v_Temp_GLDEBIT08    numeric(20,2);
  DECLARE v_Temp_GLCREDIT08   numeric(20,2);
  DECLARE v_Temp_branchcode09 varchar(100);
  DECLARE v_Temp_branchname09 varchar(255);
  DECLARE v_Temp_GLDEBIT09    numeric(20,2);
  DECLARE v_Temp_GLCREDIT09   numeric(20,2);
  DECLARE v_Temp_branchcode10 varchar(100);
  DECLARE v_Temp_branchname10 varchar(255);
  DECLARE v_Temp_GLDEBIT10    numeric(20,2);
  DECLARE v_Temp_GLCREDIT10   numeric(20,2);
  DECLARE v_Temp_branchcode11 varchar(100);
  DECLARE v_Temp_branchname11 varchar(255);
  DECLARE v_Temp_GLDEBIT11    numeric(20,2);
  DECLARE v_Temp_GLCREDIT11   numeric(20,2);
  DECLARE v_Temp_branchcode12 varchar(100);
  DECLARE v_Temp_branchname12 varchar(255);
  DECLARE v_Temp_GLDEBIT12    numeric(20,2);
  DECLARE v_Temp_GLCREDIT12   numeric(20,2);
  DECLARE v_Temp_branchcode13 varchar(100);
  DECLARE v_Temp_branchname13 varchar(255);
  DECLARE v_Temp_GLDEBIT13    numeric(20,2);
  DECLARE v_Temp_GLCREDIT13   numeric(20,2);
  DECLARE v_Temp_branchcode14 varchar(100);
  DECLARE v_Temp_branchname14 varchar(255);
  DECLARE v_Temp_GLDEBIT14    numeric(20,2);
  DECLARE v_Temp_GLCREDIT14   numeric(20,2);
  DECLARE v_Temp_branchcode15 varchar(100);
  DECLARE v_Temp_branchname15 varchar(255);
  DECLARE v_Temp_GLDEBIT15    numeric(20,2);
  DECLARE v_Temp_GLCREDIT15   numeric(20,2);
  DECLARE v_Temp_branchcode16 varchar(100);
  DECLARE v_Temp_branchname16 varchar(255);
  DECLARE v_Temp_GLDEBIT16    numeric(20,2);
  DECLARE v_Temp_GLCREDIT16   numeric(20,2);
  DECLARE v_Temp_branchcode17 varchar(100);
  DECLARE v_Temp_branchname17 varchar(255);
  DECLARE v_Temp_GLDEBIT17    numeric(20,2);
  DECLARE v_Temp_GLCREDIT17   numeric(20,2);
  DECLARE v_Temp_branchcode18 varchar(100);
  DECLARE v_Temp_branchname18 varchar(255);
  DECLARE v_Temp_GLDEBIT18    numeric(20,2);
  DECLARE v_Temp_GLCREDIT18   numeric(20,2);
  DECLARE v_Temp_branchcode19 varchar(100);
  DECLARE v_Temp_branchname19 varchar(255);
  DECLARE v_Temp_GLDEBIT19    numeric(20,2);
  DECLARE v_Temp_GLCREDIT19   numeric(20,2);
  DECLARE v_Temp_branchcode20 varchar(100);
  DECLARE v_Temp_branchname20 varchar(255);
  DECLARE v_Temp_GLDEBIT20    numeric(20,2);
  DECLARE v_Temp_GLCREDIT20   numeric(20,2);
  DECLARE v_Temp_branchcode21 varchar(100);
  DECLARE v_Temp_branchname21 varchar(255);
  DECLARE v_Temp_GLDEBIT21    numeric(20,2);
  DECLARE v_Temp_GLCREDIT21   numeric(20,2);
  DECLARE v_Temp_branchcode22 varchar(100);
  DECLARE v_Temp_branchname22 varchar(255);
  DECLARE v_Temp_GLDEBIT22    numeric(20,2);
  DECLARE v_Temp_GLCREDIT22   numeric(20,2);
  DECLARE v_Temp_branchcode23 varchar(100);
  DECLARE v_Temp_branchname23 varchar(255);
  DECLARE v_Temp_GLDEBIT23    numeric(20,2);
  DECLARE v_Temp_GLCREDIT23   numeric(20,2);
  DECLARE v_Temp_branchcode24 varchar(100);
  DECLARE v_Temp_branchname24 varchar(255);
  DECLARE v_Temp_GLDEBIT24    numeric(20,2);
  DECLARE v_Temp_GLCREDIT24   numeric(20,2);
  DECLARE v_Temp_branchcode25 varchar(100);
  DECLARE v_Temp_branchname25 varchar(255);
  DECLARE v_Temp_GLDEBIT25    numeric(20,2);
  DECLARE v_Temp_GLCREDIT25   numeric(20,2);
  DECLARE v_Temp_branchcode26 varchar(100);
  DECLARE v_Temp_branchname26 varchar(255);
  DECLARE v_Temp_GLDEBIT26    numeric(20,2);
  DECLARE v_Temp_GLCREDIT26   numeric(20,2);
  DECLARE v_Temp_branchcode27 varchar(100);
  DECLARE v_Temp_branchname27 varchar(255);
  DECLARE v_Temp_GLDEBIT27    numeric(20,2);
  DECLARE v_Temp_GLCREDIT27   numeric(20,2);
  DECLARE v_Temp_branchcode28 varchar(100);
  DECLARE v_Temp_branchname28 varchar(255);
  DECLARE v_Temp_GLDEBIT28    numeric(20,2);
  DECLARE v_Temp_GLCREDIT28   numeric(20,2);
  DECLARE v_Temp_branchcode29 varchar(100);
  DECLARE v_Temp_branchname29 varchar(255);
  DECLARE v_Temp_GLDEBIT29    numeric(20,2);
  DECLARE v_Temp_GLCREDIT29   numeric(20,2);
  DECLARE v_Temp_branchcode30 varchar(100);
  DECLARE v_Temp_branchname30 varchar(255);
  DECLARE v_Temp_GLDEBIT30    numeric(20,2);
  DECLARE v_Temp_GLCREDIT30   numeric(20,2);

  DECLARE Cursor_Temp_balance_sheet0 CURSOR FOR
   SELECT acctcode, level, l1_acctcode, l1_acctname, l1_level, l1_HD, l2_acctcode, l2_acctname, l2_level, l2_HD, l3_acctcode, l3_acctname, l3_level, l3_HD, l4_acctcode, l4_acctname, l4_level, l4_HD, l5_acctcode, l5_acctname, l5_level, l5_HD
     FROM Temp_balance_sheet0
  ORDER BY acctcode ;

  DECLARE Cursor_Branches CURSOR FOR
   SELECT CompanyCode, BranchType, BranchCode, BranchName
     FROM Branches
    WHERE CompanyCode = pi_company
  ORDER BY BranchType DESC, Branchcode ;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_Done = 1 ;

  -- Company
  SELECT UPPER(CompanyCode), UPPER(CompanyName)
    INTO v_CompanyCode, v_CompanyName
    FROM Companies
   WHERE CompanyCode = pi_company ;

  -- Branch
  SELECT UPPER(BranchCode), UPPER(BranchName)
    INTO v_BranchCode, v_BranchName
    FROM Branches
   WHERE CompanyCode = pi_company and BranchCode = pi_branch ;

  DROP TEMPORARY TABLE IF EXISTS Temp_balance_sheet0 ;
  CREATE TEMPORARY TABLE Temp_balance_sheet0 (
     acctcode      varchar(100),
     level         numeric(10,0),
     l1_acctcode   varchar(100),
     l1_acctname   varchar(255),
     l1_level      numeric(10,0),
     l1_HD         varchar(50),
     l2_acctcode   varchar(100),
     l2_acctname   varchar(255),
     l2_level      numeric(10,0),
     l2_HD         varchar(50),
     l3_acctcode   varchar(100),
     l3_acctname   varchar(255),
     l3_level      numeric(10,0),
     l3_HD         varchar(50),
     l4_acctcode   varchar(100),
     l4_acctname   varchar(255),
     l4_level      numeric(10,0),
     l4_HD         varchar(50),
     l5_acctcode   varchar(100),
     l5_acctname   varchar(255),
     l5_level      numeric(10,0),
     l5_HD         varchar(50)) ;

  DROP TEMPORARY TABLE IF EXISTS Temp_balance_sheet ;
  CREATE TEMPORARY TABLE Temp_balance_sheet (
     seqno         numeric(10,0),
     COMPANY       varchar(50),
     BRANCHNAME    varchar(255),
     level         numeric(10,0),
     l1_acctcode   varchar(100),
     l1_acctname   varchar(255),
     l1_level      numeric(10,0),
     l1_HD         varchar(50),
     l2_acctcode   varchar(100),
     l2_acctname   varchar(255),
     l2_level      numeric(10,0),
     l2_HD         varchar(50),
     l3_acctcode   varchar(100),
     l3_acctname   varchar(255),
     l3_level      numeric(10,0),
     l3_HD         varchar(50),
     l4_acctcode   varchar(100),
     l4_acctname   varchar(255),
     l4_level      numeric(10,0),
     l4_HD         varchar(50),
     l5_acctcode   varchar(100),
     l5_acctname   varchar(255),
     l5_level      numeric(10,0),
     l5_HD         varchar(50),
     branchcode01  varchar(100),
     branchname01  varchar(255),
     GLDEBIT01     numeric(20,2),
     GLCREDIT01    numeric(20,2),
     branchcode02  varchar(100),
     branchname02  varchar(255),
     GLDEBIT02     numeric(20,2),
     GLCREDIT02    numeric(20,2),
     branchcode03  varchar(100),
     branchname03  varchar(255),
     GLDEBIT03     numeric(20,2),
     GLCREDIT03    numeric(20,2),
     branchcode04  varchar(100),
     branchname04  varchar(255),
     GLDEBIT04     numeric(20,2),
     GLCREDIT04    numeric(20,2),
     branchcode05  varchar(100),
     branchname05  varchar(255),
     GLDEBIT05     numeric(20,2),
     GLCREDIT05    numeric(20,2),
     branchcode06  varchar(100),
     branchname06  varchar(255),
     GLDEBIT06     numeric(20,2),
     GLCREDIT06    numeric(20,2),
     branchcode07  varchar(100),
     branchname07  varchar(255),
     GLDEBIT07     numeric(20,2),
     GLCREDIT07    numeric(20,2),
     branchcode08  varchar(100),
     branchname08  varchar(255),
     GLDEBIT08     numeric(20,2),
     GLCREDIT08    numeric(20,2),
     branchcode09  varchar(100),
     branchname09  varchar(255),
     GLDEBIT09     numeric(20,2),
     GLCREDIT09    numeric(20,2),
     branchcode10  varchar(100),
     branchname10  varchar(255),
     GLDEBIT10     numeric(20,2),
     GLCREDIT10    numeric(20,2),
     branchcode11  varchar(100),
     branchname11  varchar(255),
     GLDEBIT11     numeric(20,2),
     GLCREDIT11    numeric(20,2),
     branchcode12  varchar(100),
     branchname12  varchar(255),
     GLDEBIT12     numeric(20,2),
     GLCREDIT12    numeric(20,2),
     branchcode13  varchar(100),
     branchname13  varchar(255),
     GLDEBIT13     numeric(20,2),
     GLCREDIT13    numeric(20,2),
     branchcode14  varchar(100),
     branchname14  varchar(255),
     GLDEBIT14     numeric(20,2),
     GLCREDIT14    numeric(20,2),
     branchcode15  varchar(100),
     branchname15  varchar(255),
     GLDEBIT15     numeric(20,2),
     GLCREDIT15    numeric(20,2),
     branchcode16  varchar(100),
     branchname16  varchar(255),
     GLDEBIT16     numeric(20,2),
     GLCREDIT16    numeric(20,2),
     branchcode17  varchar(100),
     branchname17  varchar(255),
     GLDEBIT17     numeric(20,2),
     GLCREDIT17    numeric(20,2),
     branchcode18  varchar(100),
     branchname18  varchar(255),
     GLDEBIT18     numeric(20,2),
     GLCREDIT18    numeric(20,2),
     branchcode19  varchar(100),
     branchname19  varchar(255),
     GLDEBIT19     numeric(20,2),
     GLCREDIT19    numeric(20,2),
     branchcode20  varchar(100),
     branchname20  varchar(255),
     GLDEBIT20     numeric(20,2),
     GLCREDIT20    numeric(20,2),
     branchcode21  varchar(100),
     branchname21  varchar(255),
     GLDEBIT21     numeric(20,2),
     GLCREDIT21    numeric(20,2),
     branchcode22  varchar(100),
     branchname22  varchar(255),
     GLDEBIT22     numeric(20,2),
     GLCREDIT22    numeric(20,2),
     branchcode23  varchar(100),
     branchname23  varchar(255),
     GLDEBIT23     numeric(20,2),
     GLCREDIT23    numeric(20,2),
     branchcode24  varchar(100),
     branchname24  varchar(255),
     GLDEBIT24     numeric(20,2),
     GLCREDIT24    numeric(20,2),
     branchcode25  varchar(100),
     branchname25  varchar(255),
     GLDEBIT25     numeric(20,2),
     GLCREDIT25    numeric(20,2),
     branchcode26  varchar(100),
     branchname26  varchar(255),
     GLDEBIT26     numeric(20,2),
     GLCREDIT26    numeric(20,2),
     branchcode27  varchar(100),
     branchname27  varchar(255),
     GLDEBIT27     numeric(20,2),
     GLCREDIT27    numeric(20,2),
     branchcode28  varchar(100),
     branchname28  varchar(255),
     GLDEBIT28     numeric(20,2),
     GLCREDIT28    numeric(20,2),
     branchcode29  varchar(100),
     branchname29  varchar(255),
     GLDEBIT29     numeric(20,2),
     GLCREDIT29    numeric(20,2),
     branchcode30  varchar(100),
     branchname30  varchar(255),
     GLDEBIT30     numeric(20,2),
     GLCREDIT30    numeric(20,2)) ;

  INSERT INTO Temp_balance_sheet0 (acctcode, level, l1_acctcode, l1_acctname, l1_level, l1_HD, l2_acctcode, l2_acctname, l2_level, l2_HD, l3_acctcode, l3_acctname, l3_level, l3_HD, l4_acctcode, l4_acctname, l4_level, l4_HD, l5_acctcode, l5_acctname, l5_level, l5_HD)
SELECT 
      a.acctcode,
      a.level,

       case when a.level = 5 then e.acctcode
            when a.level = 4 then d.acctcode
            when a.level = 3 then c.acctcode
            when a.level = 2 then b.acctcode end as l1_acctcode,
       case when a.level = 5 then e.acctname
            when a.level = 4 then d.acctname
            when a.level = 3 then c.acctname
            when a.level = 2 then b.acctname end as l1_acctname,
       case when a.level = 5 then e.level
            when a.level = 4 then d.level
            when a.level = 3 then c.level
            when a.level = 2 then b.level end as l1_level,

       case when a.level = 5 then if(e.postable = 1, 'Detail', 'Header')
               when a.level = 4 then if(d.postable = 1, 'Detail', 'Header')
               when a.level = 3 then if(c.postable = 1, 'Detail', 'Header')
               when a.level = 2 then if(b.postable = 1, 'Detail', 'Header') end as l1_HD,
        

       case when a.level = 5 then d.acctcode
            when a.level = 4 then c.acctcode
            when a.level = 3 then b.acctcode
            when a.level = 2 then a.acctcode end as l2_acctcode,
       case when a.level = 5 then d.acctname
            when a.level = 4 then c.acctname
            when a.level = 3 then b.acctname
            when a.level = 2 then a.acctname end as l2_acctname,
       case when a.level = 5 then d.level
            when a.level = 4 then c.level
            when a.level = 3 then b.level
            when a.level = 2 then a.level end as l2_level,

       case when a.level = 5 then if(d.postable = 1, 'Detail', 'Header')
               when a.level = 4 then if(c.postable = 1, 'Detail', 'Header')
               when a.level = 3 then if(b.postable = 1, 'Detail', 'Header')
               when a.level = 2 then if(a.postable = 1, 'Detail', 'Header') end as l2_HD,


       case when a.level = 5 then c.acctcode
            when a.level = 4 then b.acctcode
            when a.level = 3 then a.acctcode end as l3_acctcode,
       case when a.level = 5 then c.acctname
            when a.level = 4 then b.acctname
            when a.level = 3 then a.acctname end as l3_acctname,
       case when a.level = 5 then c.level
            when a.level = 4 then b.level
            when a.level = 3 then a.level end as l3_level,

       case when a.level = 5 then if(c.postable = 1, 'Detail', 'Header')
               when a.level = 4 then if(b.postable = 1, 'Detail', 'Header')
               when a.level = 3 then if(a.postable = 1, 'Detail', 'Header') end as l3_HD,


       case when a.level = 5 then b.acctcode
            when a.level = 4 then a.acctcode end as l4_acctcode,
       case when a.level = 5 then b.acctname
            when a.level = 4 then a.acctname end as l4_acctname,
       case when a.level = 5 then b.level
            when a.level = 4 then a.level end as l4_level,

       case when a.level = 5 then if(b.postable = 1, 'Detail', 'Header')
               when a.level = 4 then if(a.postable = 1, 'Detail', 'Header') end as l4_HD,

       case when a.level = 5 then a.acctcode end as l5_acctcode,
       case when a.level = 5 then a.acctname end as l5_acctname,
       case when a.level = 5 then a.level end as l5_level,

       case when a.level = 5 then if(a.postable = 1, 'Detail', 'Header') end as l5_HD
FROM chartofaccounts a
    LEFT OUTER JOIN chartofaccounts b on a.parentacct = b.acctcode and
                    case when a.level = 5 then b.level = 4
                         when a.level = 4 then b.level = 3
                         when a.level = 3 then b.level = 2
                         when a.level = 2 then b.level = 1 end

    LEFT OUTER JOIN chartofaccounts c on b.parentacct = c.acctcode and
                    case when b.level = 5 then c.level = 4
                         when b.level = 4 then c.level = 3
                         when b.level = 3 then c.level = 2
                         when b.level = 2 then c.level = 1 end

    LEFT OUTER JOIN chartofaccounts d on c.parentacct = d.acctcode and
                    case when c.level = 5 then d.level = 4
                         when c.level = 4 then d.level = 3
                         when c.level = 3 then d.level = 2
                         when c.level = 2 then d.level = 1 end

    LEFT OUTER JOIN chartofaccounts e on d.parentacct = e.acctcode and
                    case when d.level = 5 then e.level = 4
                         when d.level = 4 then e.level = 3
                         when d.level = 3 then e.level = 2
                         when d.level = 2 then e.level = 1 end
WHERE a.postable = 1
GROUP BY a.acctcode
ORDER BY a.acctcode ;

  SET v_Curr_seqno = 0 ;
  SET v_Done = 0 ;
  BLOCK0: BEGIN
  OPEN Cursor_Temp_balance_sheet0 ;
  REPEAT
    FETCH Cursor_Temp_balance_sheet0 INTO v_Temp_acctcode, v_Temp_level, v_Temp_l1_acctcode, v_Temp_l1_acctname, v_Temp_l1_level, v_Temp_l1_HD, v_Temp_l2_acctcode, v_Temp_l2_acctname, v_Temp_l2_level, v_Temp_l2_HD, v_Temp_l3_acctcode, v_Temp_l3_acctname, v_Temp_l3_level, v_Temp_l3_HD, v_Temp_l4_acctcode, v_Temp_l4_acctname, v_Temp_l4_level, v_Temp_l4_HD, v_Temp_l5_acctcode, v_Temp_l5_acctname, v_Temp_l5_level, v_Temp_l5_HD ;
    IF NOT v_Done THEN

       SET v_Temp_branchcode01 = '' ;
       SET v_Temp_branchname01 = '' ;
       SET v_Temp_GLDEBIT01  = 0 ;
       SET v_Temp_GLCREDIT01 = 0 ;
       SET v_Temp_branchcode02 = '' ;
       SET v_Temp_branchname02 = '' ;
       SET v_Temp_GLDEBIT02  = 0 ;
       SET v_Temp_GLCREDIT02 = 0 ;
       SET v_Temp_branchcode03 = '' ;
       SET v_Temp_branchname03 = '' ;
       SET v_Temp_GLDEBIT03  = 0 ;
       SET v_Temp_GLCREDIT03 = 0 ;
       SET v_Temp_branchcode04 = '' ;
       SET v_Temp_branchname04 = '' ;
       SET v_Temp_GLDEBIT04  = 0 ;
       SET v_Temp_GLCREDIT04 = 0 ;
       SET v_Temp_branchcode05 = '' ;
       SET v_Temp_branchname05 = '' ;
       SET v_Temp_GLDEBIT05  = 0 ;
       SET v_Temp_GLCREDIT05 = 0 ;
       SET v_Temp_branchcode06 = '' ;
       SET v_Temp_branchname06 = '' ;
       SET v_Temp_GLDEBIT06  = 0 ;
       SET v_Temp_GLCREDIT06 = 0 ;
       SET v_Temp_branchcode07 = '' ;
       SET v_Temp_branchname07 = '' ;
       SET v_Temp_GLDEBIT07  = 0 ;
       SET v_Temp_GLCREDIT07 = 0 ;
       SET v_Temp_branchcode08 = '' ;
       SET v_Temp_branchname08 = '' ;
       SET v_Temp_GLDEBIT08  = 0 ;
       SET v_Temp_GLCREDIT08 = 0 ;
       SET v_Temp_branchcode09 = '' ;
       SET v_Temp_branchname09 = '' ;
       SET v_Temp_GLDEBIT09  = 0 ;
       SET v_Temp_GLCREDIT09 = 0 ;
       SET v_Temp_branchcode10 = '' ;
       SET v_Temp_branchname10 = '' ;
       SET v_Temp_GLDEBIT10  = 0 ;
       SET v_Temp_GLCREDIT10 = 0 ;
       SET v_Temp_branchcode11 = '' ;
       SET v_Temp_branchname11 = '' ;
       SET v_Temp_GLDEBIT11  = 0 ;
       SET v_Temp_GLCREDIT11 = 0 ;
       SET v_Temp_branchcode12 = '' ;
       SET v_Temp_branchname12 = '' ;
       SET v_Temp_GLDEBIT12  = 0 ;
       SET v_Temp_GLCREDIT12 = 0 ;
       SET v_Temp_branchcode13 = '' ;
       SET v_Temp_branchname13 = '' ;
       SET v_Temp_GLDEBIT13  = 0 ;
       SET v_Temp_GLCREDIT13 = 0 ;
       SET v_Temp_branchcode14 = '' ;
       SET v_Temp_branchname14 = '' ;
       SET v_Temp_GLDEBIT14  = 0 ;
       SET v_Temp_GLCREDIT14 = 0 ;
       SET v_Temp_branchcode15 = '' ;
       SET v_Temp_branchname15 = '' ;
       SET v_Temp_GLDEBIT15  = 0 ;
       SET v_Temp_GLCREDIT15 = 0 ;
       SET v_Temp_branchcode16 = '' ;
       SET v_Temp_branchname16 = '' ;
       SET v_Temp_GLDEBIT16  = 0 ;
       SET v_Temp_GLCREDIT16 = 0 ;
       SET v_Temp_branchcode17 = '' ;
       SET v_Temp_branchname17 = '' ;
       SET v_Temp_GLDEBIT17  = 0 ;
       SET v_Temp_GLCREDIT17 = 0 ;
       SET v_Temp_branchcode18 = '' ;
       SET v_Temp_branchname18 = '' ;
       SET v_Temp_GLDEBIT18  = 0 ;
       SET v_Temp_GLCREDIT18 = 0 ;
       SET v_Temp_branchcode19 = '' ;
       SET v_Temp_branchname19 = '' ;
       SET v_Temp_GLDEBIT19  = 0 ;
       SET v_Temp_GLCREDIT19 = 0 ;
       SET v_Temp_branchcode20 = '' ;
       SET v_Temp_branchname20 = '' ;
       SET v_Temp_GLDEBIT20  = 0 ;
       SET v_Temp_GLCREDIT20 = 0 ;
       SET v_Temp_branchcode21 = '' ;
       SET v_Temp_branchname21 = '' ;
       SET v_Temp_GLDEBIT21  = 0 ;
       SET v_Temp_GLCREDIT21 = 0 ;
       SET v_Temp_branchcode22 = '' ;
       SET v_Temp_branchname22 = '' ;
       SET v_Temp_GLDEBIT22  = 0 ;
       SET v_Temp_GLCREDIT22 = 0 ;
       SET v_Temp_branchcode23 = '' ;
       SET v_Temp_branchname23 = '' ;
       SET v_Temp_GLDEBIT23  = 0 ;
       SET v_Temp_GLCREDIT23 = 0 ;
       SET v_Temp_branchcode24 = '' ;
       SET v_Temp_branchname24 = '' ;
       SET v_Temp_GLDEBIT24  = 0 ;
       SET v_Temp_GLCREDIT24 = 0 ;
       SET v_Temp_branchcode25 = '' ;
       SET v_Temp_branchname25 = '' ;
       SET v_Temp_GLDEBIT25  = 0 ;
       SET v_Temp_GLCREDIT25 = 0 ;
       SET v_Temp_branchcode26 = '' ;
       SET v_Temp_branchname26 = '' ;
       SET v_Temp_GLDEBIT26  = 0 ;
       SET v_Temp_GLCREDIT26 = 0 ;
       SET v_Temp_branchcode27 = '' ;
       SET v_Temp_branchname27 = '' ;
       SET v_Temp_GLDEBIT27  = 0 ;
       SET v_Temp_GLCREDIT27 = 0 ;
       SET v_Temp_branchcode28 = '' ;
       SET v_Temp_branchname28 = '' ;
       SET v_Temp_GLDEBIT28  = 0 ;
       SET v_Temp_GLCREDIT28 = 0 ;
       SET v_Temp_branchcode29 = '' ;
       SET v_Temp_branchname29 = '' ;
       SET v_Temp_GLDEBIT29  = 0 ;
       SET v_Temp_GLCREDIT29 = 0 ;
       SET v_Temp_branchcode30 = '' ;
       SET v_Temp_branchname30 = '' ;
       SET v_Temp_GLDEBIT30  = 0 ;
       SET v_Temp_GLCREDIT30 = 0 ;

       SET v_Temp_curr_branch = 0 ;
       SET v_Done = 0 ;
       BLOCK1: BEGIN
       OPEN Cursor_Branches ;
       REPEAT
         FETCH Cursor_Branches INTO v_Temp_CompanyCode, v_Temp_BranchType, v_Temp_BranchCode, v_Temp_BranchName ;
         IF NOT v_Done THEN
            SET v_Temp_curr_branch = v_Temp_curr_branch + 1 ;

            SELECT sum(if (je.GLDEBIT is null, 0, je.GLDEBIT)), sum(if (je.GLCREDIT is null, 0, je.GLCREDIT))
              INTO v_Temp_GLDEBIT, v_Temp_GLCREDIT
              FROM journalentryitems je
             WHERE je.SBO_POST_FLAG=1
               and je.glacctno = v_Temp_acctcode 
               and je.company = pi_company 
               and je.branch = v_Temp_BranchCode 
               and DATE_FORMAT(je.docdate,'%y-%m') between DATE_FORMAT(pi_date1,'%y-%m') and DATE_FORMAT(pi_date2,'%y-%m');

            IF v_Temp_GLDEBIT IS NULL THEN
               SET v_Temp_GLDEBIT = 0 ;
            END IF ;
            IF v_Temp_GLCREDIT IS NULL THEN
               SET v_Temp_GLCREDIT = 0 ;
            END IF ;

            IF     v_Temp_curr_branch = 1 THEN
               SET v_Temp_branchcode01 = v_Temp_BranchCode ;
               SET v_Temp_branchname01 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT01  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT01 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 2 THEN
               SET v_Temp_branchcode02 = v_Temp_BranchCode ;
               SET v_Temp_branchname02 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT02  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT02 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 3 THEN
               SET v_Temp_branchcode03 = v_Temp_BranchCode ;
               SET v_Temp_branchname03 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT03  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT03 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 4 THEN
               SET v_Temp_branchcode04 = v_Temp_BranchCode ;
               SET v_Temp_branchname04 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT04  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT04 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 5 THEN
               SET v_Temp_branchcode05 = v_Temp_BranchCode ;
               SET v_Temp_branchname05 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT05  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT05 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 6 THEN
               SET v_Temp_branchcode06 = v_Temp_BranchCode ;
               SET v_Temp_branchname06 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT06  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT06 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 7 THEN
               SET v_Temp_branchcode07 = v_Temp_BranchCode ;
               SET v_Temp_branchname07 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT07  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT07 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 8 THEN
               SET v_Temp_branchcode08 = v_Temp_BranchCode ;
               SET v_Temp_branchname08 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT08  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT08 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 9 THEN
               SET v_Temp_branchcode09 = v_Temp_BranchCode ;
               SET v_Temp_branchname09 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT09  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT09 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 10 THEN
               SET v_Temp_branchcode10 = v_Temp_BranchCode ;
               SET v_Temp_branchname10 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT10  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT10 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 11 THEN
               SET v_Temp_branchcode11 = v_Temp_BranchCode ;
               SET v_Temp_branchname11 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT11  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT11 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 12 THEN
               SET v_Temp_branchcode12 = v_Temp_BranchCode ;
               SET v_Temp_branchname12 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT12  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT12 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 13 THEN
               SET v_Temp_branchcode13 = v_Temp_BranchCode ;
               SET v_Temp_branchname13 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT13  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT13 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 14 THEN
               SET v_Temp_branchcode14 = v_Temp_BranchCode ;
               SET v_Temp_branchname14 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT14  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT14 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 15 THEN
               SET v_Temp_branchcode15 = v_Temp_BranchCode ;
               SET v_Temp_branchname15 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT15  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT15 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 16 THEN
               SET v_Temp_branchcode16 = v_Temp_BranchCode ;
               SET v_Temp_branchname16 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT16  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT16 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 17 THEN
               SET v_Temp_branchcode17 = v_Temp_BranchCode ;
               SET v_Temp_branchname17 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT17  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT17 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 18 THEN
               SET v_Temp_branchcode18 = v_Temp_BranchCode ;
               SET v_Temp_branchname18 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT18  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT18 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 19 THEN
               SET v_Temp_branchcode19 = v_Temp_BranchCode ;
               SET v_Temp_branchname19 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT19  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT19 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 20 THEN
               SET v_Temp_branchcode20 = v_Temp_BranchCode ;
               SET v_Temp_branchname20 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT20  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT20 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 21 THEN
               SET v_Temp_branchcode21 = v_Temp_BranchCode ;
               SET v_Temp_branchname21 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT21  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT21 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 22 THEN
               SET v_Temp_branchcode22 = v_Temp_BranchCode ;
               SET v_Temp_branchname22 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT22  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT22 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 23 THEN
               SET v_Temp_branchcode23 = v_Temp_BranchCode ;
               SET v_Temp_branchname23 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT23  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT23 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 24 THEN
               SET v_Temp_branchcode24 = v_Temp_BranchCode ;
               SET v_Temp_branchname24 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT24  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT24 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 25 THEN
               SET v_Temp_branchcode25 = v_Temp_BranchCode ;
               SET v_Temp_branchname25 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT25  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT25 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 26 THEN
               SET v_Temp_branchcode26 = v_Temp_BranchCode ;
               SET v_Temp_branchname26 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT26  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT26 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 27 THEN
               SET v_Temp_branchcode27 = v_Temp_BranchCode ;
               SET v_Temp_branchname27 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT27  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT27 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 28 THEN
               SET v_Temp_branchcode28 = v_Temp_BranchCode ;
               SET v_Temp_branchname28 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT28  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT28 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 29 THEN
               SET v_Temp_branchcode29 = v_Temp_BranchCode ;
               SET v_Temp_branchname29 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT29  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT29 = v_Temp_GLCREDIT ;
            ELSEIF v_Temp_curr_branch = 30 THEN
               SET v_Temp_branchcode30 = v_Temp_BranchCode ;
               SET v_Temp_branchname30 = v_Temp_BranchName ;
               SET v_Temp_GLDEBIT30  = v_Temp_GLDEBIT ;
               SET v_Temp_GLCREDIT30 = v_Temp_GLCREDIT ;
            END IF ;

            SET v_Done = 0 ; -- Must reset it manually
         END IF ;
       UNTIL v_Done END REPEAT ;
       CLOSE Cursor_Branches ;
       END BLOCK1 ;

       SET v_Curr_seqno = v_Curr_seqno + 1 ;

       INSERT INTO Temp_balance_sheet (seqno, COMPANY, BRANCHNAME, level, l1_acctcode, l1_acctname, l1_level, l1_HD, l2_acctcode, l2_acctname, l2_level, l2_HD, l3_acctcode, l3_acctname, l3_level, l3_HD, l4_acctcode, l4_acctname, l4_level, l4_HD, l5_acctcode, l5_acctname, l5_level, l5_HD, branchcode01, branchname01, GLDEBIT01, GLCREDIT01, branchcode02, branchname02, GLDEBIT02, GLCREDIT02, branchcode03, branchname03, GLDEBIT03, GLCREDIT03, branchcode04, branchname04, GLDEBIT04, GLCREDIT04, branchcode05, branchname05, GLDEBIT05, GLCREDIT05, branchcode06, branchname06, GLDEBIT06, GLCREDIT06, branchcode07, branchname07, GLDEBIT07, GLCREDIT07, branchcode08, branchname08, GLDEBIT08, GLCREDIT08, branchcode09, branchname09, GLDEBIT09, GLCREDIT09, branchcode10, branchname10, GLDEBIT10, GLCREDIT10, branchcode11, branchname11, GLDEBIT11, GLCREDIT11, branchcode12, branchname12, GLDEBIT12, GLCREDIT12, branchcode13, branchname13, GLDEBIT13, GLCREDIT13, branchcode14, branchname14, GLDEBIT14, GLCREDIT14, branchcode15, branchname15, GLDEBIT15, GLCREDIT15, branchcode16, branchname16, GLDEBIT16, GLCREDIT16, branchcode17, branchname17, GLDEBIT17, GLCREDIT17, branchcode18, branchname18, GLDEBIT18, GLCREDIT18, branchcode19, branchname19, GLDEBIT19, GLCREDIT19, branchcode20, branchname20, GLDEBIT20, GLCREDIT20, branchcode21, branchname21, GLDEBIT21, GLCREDIT21, branchcode22, branchname22, GLDEBIT22, GLCREDIT22, branchcode23, branchname23, GLDEBIT23, GLCREDIT23, branchcode24, branchname24, GLDEBIT24, GLCREDIT24, branchcode25, branchname25, GLDEBIT25, GLCREDIT25, branchcode26, branchname26, GLDEBIT26, GLCREDIT26, branchcode27, branchname27, GLDEBIT27, GLCREDIT27, branchcode28, branchname28, GLDEBIT28, GLCREDIT28, branchcode29, branchname29, GLDEBIT29, GLCREDIT29, branchcode30, branchname30, GLDEBIT30, GLCREDIT30)
       VALUES (v_Curr_seqno, v_CompanyName, v_BranchName, v_Temp_level, v_Temp_l1_acctcode, v_Temp_l1_acctname, v_Temp_l1_level, v_Temp_l1_HD, v_Temp_l2_acctcode, v_Temp_l2_acctname, v_Temp_l2_level, v_Temp_l2_HD, v_Temp_l3_acctcode, v_Temp_l3_acctname, v_Temp_l3_level, v_Temp_l3_HD, v_Temp_l4_acctcode, v_Temp_l4_acctname, v_Temp_l4_level, v_Temp_l4_HD, v_Temp_l5_acctcode, v_Temp_l5_acctname, v_Temp_l5_level, v_Temp_l5_HD, v_Temp_branchcode01, v_Temp_branchname01, v_Temp_GLDEBIT01, v_Temp_GLCREDIT01, v_Temp_branchcode02, v_Temp_branchname02, v_Temp_GLDEBIT02, v_Temp_GLCREDIT02, v_Temp_branchcode03, v_Temp_branchname03, v_Temp_GLDEBIT03, v_Temp_GLCREDIT03, v_Temp_branchcode04, v_Temp_branchname04, v_Temp_GLDEBIT04, v_Temp_GLCREDIT04, v_Temp_branchcode05, v_Temp_branchname05, v_Temp_GLDEBIT05, v_Temp_GLCREDIT05, v_Temp_branchcode06, v_Temp_branchname06, v_Temp_GLDEBIT06, v_Temp_GLCREDIT06, v_Temp_branchcode07, v_Temp_branchname07, v_Temp_GLDEBIT07, v_Temp_GLCREDIT07, v_Temp_branchcode08, v_Temp_branchname08, v_Temp_GLDEBIT08, v_Temp_GLCREDIT08, v_Temp_branchcode09, v_Temp_branchname09, v_Temp_GLDEBIT09, v_Temp_GLCREDIT09, v_Temp_branchcode10, v_Temp_branchname10, v_Temp_GLDEBIT10, v_Temp_GLCREDIT10, v_Temp_branchcode11, v_Temp_branchname11, v_Temp_GLDEBIT11, v_Temp_GLCREDIT11, v_Temp_branchcode12, v_Temp_branchname12, v_Temp_GLDEBIT12, v_Temp_GLCREDIT12, v_Temp_branchcode13, v_Temp_branchname13, v_Temp_GLDEBIT13, v_Temp_GLCREDIT13, v_Temp_branchcode14, v_Temp_branchname14, v_Temp_GLDEBIT14, v_Temp_GLCREDIT14, v_Temp_branchcode15, v_Temp_branchname15, v_Temp_GLDEBIT15, v_Temp_GLCREDIT15, v_Temp_branchcode16, v_Temp_branchname16, v_Temp_GLDEBIT16, v_Temp_GLCREDIT16, v_Temp_branchcode17, v_Temp_branchname17, v_Temp_GLDEBIT17, v_Temp_GLCREDIT17, v_Temp_branchcode18, v_Temp_branchname18, v_Temp_GLDEBIT18, v_Temp_GLCREDIT18, v_Temp_branchcode19, v_Temp_branchname19, v_Temp_GLDEBIT19, v_Temp_GLCREDIT19, v_Temp_branchcode20, v_Temp_branchname20, v_Temp_GLDEBIT20, v_Temp_GLCREDIT20, v_Temp_branchcode21, v_Temp_branchname21, v_Temp_GLDEBIT21, v_Temp_GLCREDIT21, v_Temp_branchcode22, v_Temp_branchname22, v_Temp_GLDEBIT22, v_Temp_GLCREDIT22, v_Temp_branchcode23, v_Temp_branchname23, v_Temp_GLDEBIT23, v_Temp_GLCREDIT23, v_Temp_branchcode24, v_Temp_branchname24, v_Temp_GLDEBIT24, v_Temp_GLCREDIT24, v_Temp_branchcode25, v_Temp_branchname25, v_Temp_GLDEBIT25, v_Temp_GLCREDIT25, v_Temp_branchcode26, v_Temp_branchname26, v_Temp_GLDEBIT26, v_Temp_GLCREDIT26, v_Temp_branchcode27, v_Temp_branchname27, v_Temp_GLDEBIT27, v_Temp_GLCREDIT27, v_Temp_branchcode28, v_Temp_branchname28, v_Temp_GLDEBIT28, v_Temp_GLCREDIT28, v_Temp_branchcode29, v_Temp_branchname29, v_Temp_GLDEBIT29, v_Temp_GLCREDIT29, v_Temp_branchcode30, v_Temp_branchname30, v_Temp_GLDEBIT30, v_Temp_GLCREDIT30) ;

       SET v_Done = 0 ; -- Must reset it manually
    END IF ;
  UNTIL v_Done END REPEAT ;
  CLOSE Cursor_Temp_balance_sheet0 ;
  END BLOCK0 ;

  SELECT pi_company, pi_branch, pi_date1, pi_date2, pi_zero, COMPANY, BRANCHNAME, level, l1_acctcode, l1_acctname, l1_level, l1_HD, l2_acctcode, l2_acctname, l2_level, l2_HD, l3_acctcode, l3_acctname, l3_level, l3_HD, l4_acctcode, l4_acctname, l4_level, l4_HD, l5_acctcode, l5_acctname, l5_level, l5_HD, branchcode01, branchname01, GLDEBIT01, GLCREDIT01, branchcode02, branchname02, GLDEBIT02, GLCREDIT02, branchcode03, branchname03, GLDEBIT03, GLCREDIT03, branchcode04, branchname04, GLDEBIT04, GLCREDIT04, branchcode05, branchname05, GLDEBIT05, GLCREDIT05, branchcode06, branchname06, GLDEBIT06, GLCREDIT06, branchcode07, branchname07, GLDEBIT07, GLCREDIT07, branchcode08, branchname08, GLDEBIT08, GLCREDIT08, branchcode09, branchname09, GLDEBIT09, GLCREDIT09, branchcode10, branchname10, GLDEBIT10, GLCREDIT10, branchcode11, branchname11, GLDEBIT11, GLCREDIT11, branchcode12, branchname12, GLDEBIT12, GLCREDIT12, branchcode13, branchname13, GLDEBIT13, GLCREDIT13, branchcode14, branchname14, GLDEBIT14, GLCREDIT14, branchcode15, branchname15, GLDEBIT15, GLCREDIT15, branchcode16, branchname16, GLDEBIT16, GLCREDIT16, branchcode17, branchname17, GLDEBIT17, GLCREDIT17, branchcode18, branchname18, GLDEBIT18, GLCREDIT18, branchcode19, branchname19, GLDEBIT19, GLCREDIT19, branchcode20, branchname20, GLDEBIT20, GLCREDIT20, branchcode21, branchname21, GLDEBIT21, GLCREDIT21, branchcode22, branchname22, GLDEBIT22, GLCREDIT22, branchcode23, branchname23, GLDEBIT23, GLCREDIT23, branchcode24, branchname24, GLDEBIT24, GLCREDIT24, branchcode25, branchname25, GLDEBIT25, GLCREDIT25, branchcode26, branchname26, GLDEBIT26, GLCREDIT26, branchcode27, branchname27, GLDEBIT27, GLCREDIT27, branchcode28, branchname28, GLDEBIT28, GLCREDIT28, branchcode29, branchname29, GLDEBIT29, GLCREDIT29, branchcode30, branchname30, GLDEBIT30, GLCREDIT30
    FROM Temp_balance_sheet
  ORDER BY seqno ;

END $$

DELIMITER ;