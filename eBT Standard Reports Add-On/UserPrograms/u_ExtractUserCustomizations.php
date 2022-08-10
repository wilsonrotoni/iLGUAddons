<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	//$progid = "u_ExtractInitDataFromSBO";
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./inc/formutils.php");
	include_once("./classes/sbotrxsync.php");
	//include_once("./inc/formaccess.php");

	$strExport = "";
	$objRs = new recordset(null,$objConnection);
	$objRs2 = new recordset(null,$objConnection);

	$objRs->queryopen("select * from udps order by datecreated");
	while ($objRs->queryfetchrow("NAME")) {
		//var_dump($objRs->fields);
		$strExport .= "evaltrx:addUDP('".$objRs->fields["PROGID"]."','".$objRs->fields["PROGNAME"]."','".$objRs->fields["SUSER"]."','".$objRs->fields["BRANCHTYPE"]."');\r\n"; 
	}

	$strExport .= "\r\n"; 

	$objRs->queryopen("select * from udts");
	while ($objRs->queryfetchrow("NAME")) {
		//var_dump($objRs->fields);
		$strExport .= "evaltrx:addUDT('".$objRs->fields["TABLENAME"]."','".addslashes($objRs->fields["TABLEDESC"])."','".$objRs->fields["TABLETYPE"]."','".$objRs->fields["DFLTFORM"]."');\r\n"; 
	}

	$strExport .= "\r\n"; 

	$objRs->queryopen("select * from udos");
	while ($objRs->queryfetchrow("NAME")) {
		//var_dump($objRs->fields);
		$strExport .= "evaltrx:addUDO('".$objRs->fields["UDOID"]."','".$objRs->fields["UDONAME"]."','".$objRs->fields["TABLENAME"]."','".$objRs->fields["DFLTFORM"]."');\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from udolines order by udoid, seq");
	while ($objRs->queryfetchrow("NAME")) {
		//var_dump($objRs->fields);
		$strExport .= "evaltrx:addUDOChild('".$objRs->fields["UDOID"]."','".$objRs->fields["TABLENAME"]."','".$objRs->fields["TABLEDESC"]."','".$objRs->fields["TABLETYPE"]."');\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from udofindcols order by udoid, seq");
	while ($objRs->queryfetchrow("NAME")) {
		//var_dump($objRs->fields);
		$strExport .= "evaltrx:addUDOFindColumn('".$objRs->fields["UDOID"]."','".$objRs->fields["FIELDNAME"]."','".$objRs->fields["SEQ"]."');\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from udfs order by TABLENAME, SEQ, FIELDNAME");
	while ($objRs->queryfetchrow("NAME")) {
		//var_dump($objRs->fields);
		switch ($objRs->fields["TABLENAME"]) {
			case "apinvoices";
			case "arcreditmemos";
			case "apcreditmemos";
			case "salesorders";
			case "salesquotations";
			case "purchaserequests";
			case "purchaseorders";
			case "purchasedeliveries";
			case "purchasereturns";
			case "salesdeliveries";
			case "salesreturns";
			case "goodsreceipts";
			case "goodsissues";
			case "productionreceipts";
			case "productionissues";
			case "stocktransfers";
			case "repacking";
			case "xwarranties";
			case "ardownpaymentinvoices";
			case "apdownpaymentinvoices";
			case "stocktransferrequests";
			case "apinvoiceitems";
			case "arcreditmemoitems";
			case "apcreditmemoitems";
			case "salesorderitems";
			case "salesquotationitems";
			case "purchaserequestitems";
			case "purchaseorderitems";
			case "purchasedeliveryitems";
			case "purchasereturnitems";
			case "salesdeliveryitems";
			case "salesreturnitems";
			case "goodsreceiptitems";
			case "goodsissueitems";
			case "productionreceiptitems";
			case "productionissueitems";
			case "stocktransferitems";
			case "repackingitems";
			case "xwarrantyitems";
			case "ardownpaymentinvoiceitems";
			case "apdownpaymentinvoiceitems";
			case "stocktransferrequestitems";
			case "payments";
			case "paymentcheques";
			case "paymentinvoices";
			case "paymentcreditcards";
			case "paymentcashcards";
			case "paymentaccounts";
			case "paymentothercharges";
			case "journalentries";
			case "recurringpostings";
			case "journalentryitems";
			case "recurringpostingitems";
			case "serialtrxs";
			case "documentserials";
			case "batchtrxs";
			case "documentbatches";
			case "suppliers";
			case "prospects";
			case "accountslist";
			case "suppliercatalogs";
			case "companydepartments";
			case "branchwarehouses";
			case "branchwarehouseitemgroups";
			case "companyitemgroups";
			case "companywarehouses";
			case "companywarehouseitemgroups";
			case "itemwarehouses";
				break;
			default:
				if ($objRs->fields["TABLENAME"]=="branches") {
					$objRs2->queryopen("select 1 from udfs where TABLENAME='branchdepartments' and FIELDNAME='".$objRs->fields["FIELDNAME"]."'");
					if ($objRs2->queryfetchrow()) break;
				} 
				if ($objRs->fields["TABLENAME"]=="companies") {
					$objRs2->queryopen("select 1 from udfs where TABLENAME='branchdepartments' and FIELDNAME='".$objRs->fields["FIELDNAME"]."'");
					if ($objRs2->queryfetchrow()) break;
				} 
				if ($objRs->fields["TABLENAME"]=="items") {
					$objRs2->queryopen("select 1 from udfs where TABLENAME='branchitemgroups' and FIELDNAME='".$objRs->fields["FIELDNAME"]."'");
					if ($objRs2->queryfetchrow()) break;
				} 
				$strExport .= "evaltrx:addUDF('".$objRs->fields["TABLENAME"]."','".$objRs->fields["FIELDNAME"]."','".addslashes($objRs->fields["FIELDDESC"])."','".$objRs->fields["FORMAT"]."',".$objRs->fields["FIELDWIDTH"].",".$objRs->fields["SEQ"].",".iif($objRs->fields["SETDEFAULTVALUE"]==1,"'".addslashes($objRs->fields["DEFAULTVALUE"])."'","null").",".$objRs->fields["REQUIRED"].",".iif($objRs->fields["SETLINKTABLE"]==1,"'".addslashes($objRs->fields["LINKTABLE"])."'","null").",".$objRs->fields["SBO_POST_FLAG"].",".$objRs->fields["CREATEINDEX"].",".$objRs->fields["VIEWABLE"].",".$objRs->fields["EDITABLE"].",".$objRs->fields["VISIBLE"].",".$objRs->fields["ENABLED"].");\r\n"; 
				break;
		}		
	}
	
	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from udfvalues order by TABLENAME, FIELDNAME");
	while ($objRs->queryfetchrow("NAME")) {
		//var_dump($objRs->fields);
		switch ($objRs->fields["TABLENAME"]) {
			case "apinvoices";
			case "arcreditmemos";
			case "apcreditmemos";
			case "salesorders";
			case "salesquotations";
			case "purchaserequests";
			case "purchaseorders";
			case "purchasedeliveries";
			case "purchasereturns";
			case "salesdeliveries";
			case "salesreturns";
			case "goodsreceipts";
			case "goodsissues";
			case "productionreceipts";
			case "productionissues";
			case "stocktransfers";
			case "repacking";
			case "xwarranties";
			case "ardownpaymentinvoices";
			case "apdownpaymentinvoices";
			case "stocktransferrequests";
			case "apinvoiceitems";
			case "arcreditmemoitems";
			case "apcreditmemoitems";
			case "salesorderitems";
			case "salesquotationitems";
			case "purchaserequestitems";
			case "purchaseorderitems";
			case "purchasedeliveryitems";
			case "purchasereturnitems";
			case "salesdeliveryitems";
			case "salesreturnitems";
			case "goodsreceiptitems";
			case "goodsissueitems";
			case "productionreceiptitems";
			case "productionissueitems";
			case "stocktransferitems";
			case "repackingitems";
			case "xwarrantyitems";
			case "ardownpaymentinvoiceitems";
			case "apdownpaymentinvoiceitems";
			case "stocktransferrequestitems";
			case "payments";
			case "paymentcheques";
			case "paymentinvoices";
			case "paymentcreditcards";
			case "paymentcashcards";
			case "paymentaccounts";
			case "paymentothercharges";
			case "journalentries";
			case "recurringpostings";
			case "journalentryitems";
			case "recurringpostingitems";
			case "serialtrxs";
			case "documentserials";
			case "batchtrxs";
			case "documentbatches";
			case "suppliers";
			case "prospects";
			case "accountslist";
			case "suppliercatalogs";
			case "companydepartments";
			case "branchwarehouses";
			case "branchwarehouseitemgroups";
			case "companyitemgroups";
			case "companywarehouses";
			case "companywarehouseitemgroups";
			case "itemwarehouses";
				break;
			default:
				if ($objRs->fields["TABLENAME"]=="branches") {
					$objRs2->queryopen("select 1 from udfs where TABLENAME='branchdepartments' and FIELDNAME='".$objRs->fields["FIELDNAME"]."'");
					if ($objRs2->queryfetchrow()) break;
				} 
				if ($objRs->fields["TABLENAME"]=="companies") {
					$objRs2->queryopen("select 1 from udfs where TABLENAME='branchdepartments' and FIELDNAME='".$objRs->fields["FIELDNAME"]."'");
					if ($objRs2->queryfetchrow()) break;
				} 
				if ($objRs->fields["TABLENAME"]=="items") {
					$objRs2->queryopen("select 1 from udfs where TABLENAME='branchitemgroups' and FIELDNAME='".$objRs->fields["FIELDNAME"]."'");
					if ($objRs2->queryfetchrow()) break;
				} 		
				$strExport .= "evaltrx:addUDFValue('".$objRs->fields["TABLENAME"]."','".$objRs->fields["FIELDNAME"]."','".$objRs->fields["FIELDVALUE"]."','".addslashes($objRs->fields["FIELDVALUEDESC"])."');\r\n"; 
				break;
		}		
	}
	
	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from objectfss where objectcode not like 'urpt_%' and objectcode not like 'sysRpt%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:addFormattedSearch('".$objRs->fields["OBJECTCODE"]."','".$objRs->fields["FIELDNAME"]."','".$objRs->fields["FSTYPE"]."','".$objRs->fields["FSSOURCE"]."','".iif($objRs->fields["FSSOURCE"]=="Q",$objRs->fields["FSQUERYVALUE"],$objRs->fields["FSQUERYCODE"])."','".$objRs->fields["REFRESHTYPE"]."',".$objRs->fields["VALIDATE"].");\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from objectfstriggers where objectcode not like 'urpt_%' and objectcode not like 'sysRpt%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:addFormattedSearchTrigger('".$objRs->fields["OBJECTCODE"]."','".$objRs->fields["FIELDNAME"]."','".$objRs->fields["FIELDTRIGGER"]."');\r\n"; 
	}
	
	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from objectcaptions where objectcode not like 'urpt_%' and objectcode not like 'sysRpt%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:setFieldCaption('".$objRs->fields["OBJECTCODE"]."','".$objRs->fields["FIELDNAME"]."','".addslashes($objRs->fields["FIELDDESC"])."');\r\n"; 
	}

	$strExport .= "\r\n"; 
	

	$objRs->queryopen("select * from objectaccesssettings ORDER BY OBJECTCODE, ACCESSID, SEQID, FIELDNAME");
	if ($objRs->recordcount()>0) $strExport .= "evaltrx:resetFormSettings();\r\n"; 
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:setFormSettings('".$objRs->fields["OBJECTCODE"]."','".$objRs->fields["ACCESSID"]."','".$objRs->fields["FIELDNAME"]."',".$objRs->fields["VISIBLE"].",".$objRs->fields["ENABLED"].",".$objRs->fields["SEQID"].");\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	
	
	header('Content-Type: application/txt'); 
	header('Content-Length: '.strlen($strExport));
	header('Content-Disposition: inline; filename="'.$_SESSION["dbname"] .'_usercustomizations.txt"'); 
	echo $strExport;
	die();
?>

