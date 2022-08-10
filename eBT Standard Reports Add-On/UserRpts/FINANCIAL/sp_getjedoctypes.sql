DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_getjedoctypes` $$
CREATE PROCEDURE `sp_getjedoctypes`()
BEGIN
	DROP TEMPORARY TABLE IF EXISTS jedoctypes;
	CREATE TEMPORARY TABLE jedoctypes(
		`CODE` varchar(30) NULL default '',
		`NAME` varchar(100) NULL default '',
		PRIMARY KEY (`CODE`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        
	INSERT INTO jedoctypes(CODE, NAME) VALUES('AR','A/R Invoices');
	INSERT INTO jedoctypes(CODE, NAME) VALUES('NR','N/R Invoices'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('CS','Cash Sales'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('CM','A/R Credit Memos'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('DN','Deliveries'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('RT','Returns'); 
        
	INSERT INTO jedoctypes(CODE, NAME) VALUES('AP','A/P Invoices'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('ACM','A/P Credit Memos'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('PDN','Goods Receipt POs'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('PRT','Goods Returns'); 

	INSERT INTO jedoctypes(CODE, NAME) VALUES('GR','Goods Reciepts'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('GI','Goods Issues'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('GA','Stock Adjustments'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('SR','Stock Revaluations'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('GT','Stock Transfers'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('TI','Transfer In'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('TO','Transfer Out'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('GTL','Transfer Recon'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('POR','Production Receipts'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('POI','Production Issues'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('RPCK','Repacking'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('IQ','Initial Quantities'); 
	INSERT INTO jedoctypes(CODE, NAME) VALUES('JO','Job Order'); 

	INSERT INTO jedoctypes(CODE, NAME) VALUES('JV','Journal Voucher'); 

	INSERT INTO jedoctypes(CODE, NAME) VALUES('RC','Receipts'); 

	INSERT INTO jedoctypes(CODE, NAME) VALUES('PY','Payments'); 

	INSERT INTO jedoctypes(CODE, NAME) VALUES('BD','Bank Deposits'); 

	INSERT INTO jedoctypes(CODE, NAME) VALUES('PCV','Funds Voucher');
END $$

DELIMITER ;