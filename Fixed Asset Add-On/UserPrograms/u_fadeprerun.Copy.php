<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_fadeprerun";
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/masterdatalinesschema.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./classes/journalvouchers.php");
	include_once("./classes/journalvoucheritems.php");
	include_once("./sls/brancheslist.php");
	include_once("./sls/enumyear.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./utils/trxlog.php");
	
	unset($enumyear["-"]);
	
	$page->objectcode = $progid;


	function loadenummonth($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enummonth;
		reset($enummonth);
		while (list($key, $val) = each($enummonth)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}

	
	function onFormAction($action,$opt="") {
		global $objConnection;
		global $objGrid;
		global $page;
		global $filter;
		$actionReturn = true;
		
		if(strtolower($action)=="u_post") {
			$obju_FA = new masterdataschema(NULL,$objConnection,"u_fa");
			//$obju_FADepreScheds = new masterdatalinesschema(NULL,$objConnection,"u_fadeprescheds");
			$obju_FADepreDepts = new masterdatalinesschema(NULL,$objConnection,"u_fadepredepts");
			
			$objJvHdr = new journalvouchers(null,$objConnection);
			$objJvDtl = new journalvoucheritems(null,$objConnection);
			
			$objRs = new recordset(null,$objConnection);
			
			$objConnection->beginwork();
			
			$settings = getBusinessObjectSettings("JOURNALVOUCHER");
	
			$objJvHdr->prepareadd();
			$objJvHdr->objectcode = "JOURNALVOUCHER";
			$objJvHdr->sbo_post_flag = $settings["autopost"];
			$objJvHdr->jeposting = $settings["jeposting"];
			$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
			$objJvHdr->docdate = $page->getitemdate("docdate");
			$objJvHdr->docduedate = $objJvHdr->docdate;
			$objJvHdr->taxdate = $objJvHdr->docdate;
			$objJvHdr->docseries = getDefaultSeries($objJvHdr->objectcode,$objConnection);
			$objJvHdr->docno = getNextSeriesNoByBranch($objJvHdr->objectcode,$objJvHdr->docseries,$objJvHdr->docdate,$objConnection);
			$objJvHdr->currency = $_SESSION["currency"];
			$objJvHdr->docstatus = "C";
			$objJvHdr->reference1 = $objTable->docno;
			$objJvHdr->remarks = "Fixed Assets Depreciation";
			
			$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
						
			for($i=0;$i<= $objGrid->recordcount;$i++) {
				if($page->getitemstring("chxboxT1r".$i) == "1") {
					if ($obju_FA->getbykey($page->getcolumnstring("T1",$i,"facode"))) {
						if ($page->privatedata["fadeprescheds"]=="M") {
							$actionReturn = $objRs->executesql("update u_fadeprescheds set u_posted=1, u_jvno='$objJvHdr->docno', u_jvdate='$objJvHdr->docdate' where code='$obju_FA->code' and u_year='".$page->getitemstring("year")."' and u_month='".$page->getitemstring("month")."' and u_posted=0",true);
						} elseif ($page->privatedata["fadeprescheds"]=="Q") {
							$actionReturn = $objRs->executesql("update u_fadeprescheds set u_posted=1, u_jvno='$objJvHdr->docno', u_jvdate='$objJvHdr->docdate' where code='$obju_FA->code' and u_year='".$page->getitemstring("year")."' and u_month>='".iif($page->getitemstring("month")==3,1,iif($page->getitemstring("month")==6,4,iif($page->getitemstring("month")==9,7,10)))."' and u_month<='".$page->getitemstring("month")."' and u_posted=0",true);
						} else {
							$actionReturn = $objRs->executesql("update u_fadeprescheds set u_posted=1, u_jvno='$objJvHdr->docno', u_jvdate='$objJvHdr->docdate' where code='$obju_FA->code' and u_year='".$page->getitemstring("year")."' and u_month>='".iif($page->getitemstring("month")=="6",1,7)."' and u_month<='".$page->getitemstring("month")."' and u_posted=0",true);
						}
						/*if ($obju_FADepreScheds->getbysql("CODE='$obju_FA->code' AND U_YEAR='".$page->getitemstring("year")."' AND U_MONTH='".$page->getitemstring("month")."' AND U_POSTED=0")) {
							$obju_FADepreScheds->setudfvalue("u_posted",1);
							$obju_FADepreScheds->setudfvalue("u_jvno",$objJvHdr->docno);
							$obju_FADepreScheds->setudfvalue("u_jvdate",$objJvHdr->docdate);
							$actionReturn = $obju_FADepreScheds->update($obju_FADepreScheds->code,$obju_FADepreScheds->lineid,$obju_FADepreScheds->rcdversion);*/
							if ($actionReturn) {
								if ($obju_FA->getudfvalue("u_deptweightperc")==0) {
									$objJvDtl->prepareadd();
									$objJvDtl->docid = $objJvHdr->docid;
									$objJvDtl->objectcode = $objJvHdr->objectcode;
									$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
									$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
									$objJvDtl->department = $obju_FA->getudfvalue("u_department");
									$objJvDtl->profitcenter = $obju_FA->getudfvalue("u_profitcenter");
									$objJvDtl->projcode = $obju_FA->getudfvalue("u_projcode");
									$objJvDtl->itemtype = "A";
									$objJvDtl->itemno = $obju_FA->getudfvalue("u_depreacct");
									$objJvDtl->itemname = getchartofaccountname($obju_FA->getudfvalue("u_depreacct"));
									//$objJvDtl->debit = $obju_FADepreScheds->getudfvalue("u_amount");
									$objJvDtl->debit = $page->getcolumndecimal("T1",$i,"amount");
									$objJvDtl->grossamount = $objJvDtl->debit;
									$objJvHdr->totaldebit += $objJvDtl->debit ;
									$objJvHdr->totalcredit += $objJvDtl->credit ;
									$objJvDtl->privatedata["header"] = $objJvHdr;
									$actionReturn = $objJvDtl->add();
								} else {
									$totalamount = 0;
									$totalperc = 0;
									$obju_FADepreDepts->queryopen($obju_FADepreDepts->selectstring() . " WHERE CODE='".$obju_FA->code."'");
									while ($obju_FADepreDepts->queryfetchrow()) {
										$totalperc += $obju_FADepreDepts->fields["U_WEIGHTPERC"];
										if ($totalperc!=100) {
											//$amount = $obju_FADepreScheds->getudfvalue("u_amount") * ($obju_FADepreDepts->fields["U_WEIGHTPERC"]/100);
											$amount = $page->getcolumndecimal("T1",$i,"amount") * ($obju_FADepreDepts->fields["U_WEIGHTPERC"]/100);
										} else {
											//$amount = $obju_FADepreScheds->getudfvalue("u_amount") - $totalamount;
											$amount = $page->getcolumndecimal("T1",$i,"amount") - $totalamount;
										}	
										$totalamount += $amount;
										$objJvDtl->prepareadd();
										$objJvDtl->docid = $objJvHdr->docid;
										$objJvDtl->objectcode = $objJvHdr->objectcode;
										$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
										$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
										if ($obju_FADepreDepts->fields["U_DEPARTMENT"]!="") $objJvDtl->department = $obju_FADepreDepts->fields["U_DEPARTMENT"];
										else $objJvDtl->department = $obju_FA->getudfvalue("u_department");
										if ($obju_FADepreDepts->fields["U_PROFITCENTER"]!="") $objJvDtl->profitcenter = $obju_FADepreDepts->fields["U_PROFITCENTER"];
										else $objJvDtl->profitcenter = $obju_FA->getudfvalue("u_profitcenter");
										if ($obju_FADepreDepts->fields["U_PROJCODE"]!="") $objJvDtl->projcode = $obju_FADepreDepts->fields["U_PROJCODE"];
										else $objJvDtl->projcode = $obju_FA->getudfvalue("u_projcode");
										$objJvDtl->itemtype = "A";
										if ($obju_FADepreDepts->fields["U_GLACCTNO"]!="") {
											$objJvDtl->itemno = $obju_FADepreDepts->fields["U_GLACCTNO"];
											$objJvDtl->itemname = getchartofaccountname($obju_FADepreDepts->fields["U_GLACCTNO"]);
										} else {
											$objJvDtl->itemno = $obju_FA->getudfvalue("u_depreacct");
											$objJvDtl->itemname = getchartofaccountname($obju_FA->getudfvalue("u_depreacct"));
										}	
										$objJvDtl->debit = $amount;
										$objJvDtl->grossamount = $objJvDtl->debit;
										$objJvHdr->totaldebit += $objJvDtl->debit ;
										$objJvHdr->totalcredit += $objJvDtl->credit ;
										$objJvDtl->privatedata["header"] = $objJvHdr;
										$actionReturn = $objJvDtl->add();
										if (!$actionReturn) break;
									}
								}	
							}
							if ($actionReturn) {
								$objJvDtl->prepareadd();
								$objJvDtl->docid = $objJvHdr->docid;
								$objJvDtl->objectcode = $objJvHdr->objectcode;
								$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
								$objJvDtl->refbranch = $obju_FA->getudfvalue("u_branch");
								//$objJvDtl->department = $obju_FA->getudfvalue("u_department");
								//$objJvDtl->profitcenter = $obju_FA->getudfvalue("u_profitcenter");
								//$objJvDtl->projcode = $obju_FA->getudfvalue("u_projcode");
								$objJvDtl->itemtype = "A";
								$objJvDtl->itemno = $obju_FA->getudfvalue("u_accumdepreacct");
								$objJvDtl->itemname = getchartofaccountname($obju_FA->getudfvalue("u_accumdepreacct"));
								//$objJvDtl->credit = $obju_FADepreScheds->getudfvalue("u_amount");
								$objJvDtl->credit = $page->getcolumndecimal("T1",$i,"amount");
								$objJvDtl->grossamount = $objJvDtl->credit;
								$objJvHdr->totaldebit += $objJvDtl->debit ;
								$objJvHdr->totalcredit += $objJvDtl->credit ;
								$objJvDtl->privatedata["header"] = $objJvHdr;
								$actionReturn = $objJvDtl->add();
							}				
							if ($actionReturn) {
								//$obju_FA->setudfvalue("u_accumdepre",floatval($obju_FA->getudfvalue("u_accumdepre")) + floatval($obju_FADepreScheds->getudfvalue("u_amount")));
								//$obju_FA->setudfvalue("u_bookvalue",floatval($obju_FA->getudfvalue("u_bookvalue")) - floatval($obju_FADepreScheds->getudfvalue("u_amount")));
								$obju_FA->setudfvalue("u_accumdepre",floatval($obju_FA->getudfvalue("u_accumdepre")) + $page->getcolumndecimal("T1",$i,"amount"));
								$obju_FA->setudfvalue("u_bookvalue",floatval($obju_FA->getudfvalue("u_bookvalue")) - $page->getcolumndecimal("T1",$i,"amount"));
								$obju_FA->setudfvalue("u_remainlife",floatval($obju_FA->getudfvalue("u_remainlife")) - 1);
							}			
						//} else $actionReturn = raiseError("Unable to retrieve Fixed Asset Depreciation Schedule [".$page->getitemstring("year")."/".$page->getitemstring("month")."] for Asset Code [".$obju_FA->code."]");
						if ($actionReturn) $actionReturn = $obju_FA->update($obju_FA->code,$obju_FA->rcdversion);
					} else $actionReturn = raiseError("Unable to retrieve Fixed Asset [".$page->getcolumnstring("T1",$i,"facode")."].");	
				}
				if (!$actionReturn) break;
			}
			if ($actionReturn) $actionReturn = $objJvHdr->add();
			//if ($actionReturn) raiseError("onFormAction");
			if ($actionReturn) $objConnection->commit();
			else $objConnection->rollback();
			
			onFormAction("edit",$filter);
		}
		return $actionReturn;
	}
	$filter="";
	
	$objGrid = new grid("T1",$httpVars,true);
	$objGrid->addcolumn("chxbox");
	$objGrid->addcolumn("fabranch");
	$objGrid->addcolumn("facode");
	$objGrid->addcolumn("faname");
	$objGrid->addcolumn("itemcode");
	$objGrid->addcolumn("itemdesc");
	$objGrid->addcolumn("amount");
	$objGrid->columntitle("fabranch","Branch");
	$objGrid->columnwidth("fabranch",20);
	$objGrid->columntitle("facode","Asset Code");
	$objGrid->columnwidth("facode",20);
	$objGrid->columntitle("faname","Asset Name");
	$objGrid->columnwidth("faname",20);
	$objGrid->columntitle("itemcode","Item Code");
	$objGrid->columnwidth("itemcode",15);
	$objGrid->columntitle("itemdesc","Item Description");
	$objGrid->columnwidth("itemdesc",20);
	$objGrid->columntitle("amount","Cost");
	$objGrid->columnwidth("amount",12);

	$objGrid->columnalignment("cost","right");

	$objGrid->columninput("chxbox","type","checkbox");
	$objGrid->columninput("chxbox","value",1);
	$objGrid->columnattributes("chxbox","enable");
	$objGrid->columnwidth("chxbox",2);
	$objGrid->columntitle("chxbox","");
	$objGrid->dataentry = false;
	$objGrid->automanagecolumnwidth = false;

	$schema["fabranch"] = createSchema("fabranch");
	$schema["faclass"] = createSchema("faclass");
	$schema["year"] = createSchema("year");
	$schema["month"] = createSchema("month");
	$schema["docdate"] = createSchemaDate("docdate");
	
	$objRs = new recordset(null,$objConnection);
	
	$objRs->queryopen("select u_famgmnt, u_fadeprescheds from companies where companycode='".$_SESSION["company"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["famgmnt"] = $objRs->fields["u_famgmnt"]; 
		$page->privatedata["fadeprescheds"] = $objRs->fields["u_fadeprescheds"]; 
	}
	
	if ($page->privatedata["fadeprescheds"]=="M") {
		for($i=1;$i<=12;$i++) {
			$enummonth[$i] = date('F',mktime(0,0,0,$i,1,date('Y')));
		}
		if (!isset($httpVars["df_month"])) {
			$page->setitem("month",date('m'));
		}
	} elseif ($page->privatedata["fadeprescheds"]=="Q") {
		$enummonth[3] = "March";
		$enummonth[6] = "June";
		$enummonth[9] = "September";
		$enummonth[12] = "December";
		if (!isset($httpVars["df_month"])) {
			if (date('m')<=3) $page->setitem("month",3);
			elseif (date('m')<=6) $page->setitem("month",6);
			elseif (date('m')<=9) $page->setitem("month",9);
			else $page->setitem("month",12);
		}
	} else {
		$enummonth[6] = "June";
		$enummonth[12] = "December";
		if (!isset($httpVars["df_month"])) {
			if (date('m')<=6) $page->setitem("month",6);
			else $page->setitem("month",12);
		}
	}

	if (!isset($httpVars["df_year"])) {
		$page->setitem("year",date('Y'));
		
	}

	
	if ($page->privatedata["famgmnt"]=="BR") {
		$page->setitem("fabranch",$_SESSION["branch"]);
		$schema["fabranch"]["attributes"] = "disabled";
	}
	
	
	require("./inc/formactions.php");
	
	
	$filterExp = "";
	$filterExp = genSQLFilterString("A.U_BRANCH",$filterExp,$page->getitemstring("fabranch"));
	$filterExp = genSQLFilterString("A.U_FACLASS",$filterExp,$page->getitemstring("faclass"));
	$filterExp = genSQLFilterNumeric("C.U_YEAR",$filterExp,$page->getitemstring("year"));
	if ($page->privatedata["fadeprescheds"]=="M") {
		$filterExp = genSQLFilterNumeric("C.U_MONTH",$filterExp,$page->getitemstring("month"));
	} elseif ($page->privatedata["fadeprescheds"]=="Q") {
		$filterExp = genSQLFilterNumeric("C.U_MONTH",$filterExp,iif($page->getitemstring("month")==3,1,iif($page->getitemstring("month")==6,4,iif($page->getitemstring("month")==9,7,10))),$page->getitemstring("month"));
	} else {
		$filterExp = genSQLFilterNumeric("C.U_MONTH",$filterExp,iif($page->getitemstring("month")==6,1,7),$page->getitemstring("month"));
	}
	
	if ($filterExp !="") $filterExp = " and " . $filterExp;
	
	if ($_SESSION["errormessage"]=="") {
		$objGrid->clear();
		$objRs->setdebug();
		$objRs->queryopen("select A.U_BRANCH AS U_BRANCH,A.CODE AS U_FACODE, A.NAME AS U_FANAME, A.U_FACLASS, A.U_ITEMCODE, A.U_ITEMDESC, SUM(C.U_AMOUNT) AS U_AMOUNT from U_FA A, U_FADEPRESCHEDS C where A.U_BOOKVALUE>0 AND C.CODE=A.CODE and C.U_POSTED=0  $filterExp GROUP BY A.CODE");
		//var_dump($objRs->sqls);
		while ($objRs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			$objGrid->setitem(null,"chxbox",1);
			$objGrid->setitem(null,"fabranch",$objRs->fields["U_BRANCH"]);
			$objGrid->setitem(null,"facode",$objRs->fields["U_FACODE"]);
			$objGrid->setitem(null,"faname",$objRs->fields["U_FANAME"]);
			$objGrid->setitem(null,"itemcode",$objRs->fields["U_ITEMCODE"]);
			$objGrid->setitem(null,"itemdesc",$objRs->fields["U_ITEMDESC"]);
			$objGrid->setitem(null,"amount",formatNumericAmount($objRs->fields["U_AMOUNT"]));
		}
	}	
	
	//$page->resize->addgrid("T1",20,100,false);
	//$schema["gdate"]["attributes"]="disabled";
	//$httpVars["df_gdate"] = date("m/d/Y");
	$page->resize->addgridobject($objGrid,10,190,false);
	
	
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>
</head>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatebusinesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="lnkbtns/salesdeliveries.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onElementClick(element,column,table,row) {
		var result;
		switch(table) {
			case "T1":
				switch (column) {
					case "chxbox":
						if (isTableInputChecked(table,column,row)) {
							//focusTableInput("T1","depredate",row,500);
						} else {
						}
				}
				break;
			default:
				break;
		}					
		return true;
	}	

	function onElementValidate(element,column,table,row) {
		return true;
	}	


	function onFormSubmit(action) {
		switch (action) {
			case "u_post":
				if (isInputEmpty("year")) return false;
				if (isInputEmpty("month")) return false;
				if (isInputEmpty("docdate")) return false;
				
				if(getTableRowCount("T1")==0) { 
					setStatusMsg("No Item(s) to process!"); 
					return false;
				}
				
				
				var none=true;
				if(getTableRowCount("T1")>0) {
					for(i=1;i<=getTableRowCount("T1");i++) {
						if(isTableInputChecked("T1","chxbox",i)){
						 	none = false; 
						}
					}
					if(none) {
						setStatusMsg("Please select item(s) to process."); 
						return false;				
					}
				}
				break;
			case "check":
				if(getTableRowCount("T1")>0) {
					if(getInput("chxstat")=="0") setInput("chxstat","1");
					else if(getInput("chxstat")=="1") setInput("chxstat","0");
					
					for(i=1;i<=getTableRowCount("T1");i++) {
						if(getInput("chxstat")=="1") {
							checkedTableInput("T1","chxbox",i,true); 
						}else if(getInput("chxstat")=="0") {
							uncheckedTableInput("T1","chxbox",i,true); 
						}
						onElementClick(getTableInput("T1","chxbox",i),"chxbox","T1",i);
					}
				}
				return false;
				break;
			default:	
				break;
		}
				
		return true;
	}	
	
	function onElementCFLGetParams(element) {
		var params = new Array();
		switch (element.id) {	
			case "df_suppno": 
				params["params"] = "BPTYPE:S";
				break;
		}	
		return params;
	}
	
	function onSelectRow(table,row) {
		var params = new Array();
		switch (table) {
			case "T1":
				params["focus"] = false;
				/*if (isTableInputChecked(table,"chxbox",row)) {
					focusTableInput(table,"depredate",row,500);				
				}*/	
				break;
		}		
		return params;
	}
	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td class="labelPageHeader" >&nbsp;<?php echo @$pageHeader ?>&nbsp;</td></tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr>
  <td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	  <!--DWLayoutTable-->
		<tr>
		 <td ><label <?php genCaptionHtml($schema["fabranch"],"") ?> >Ref Branch</label></td>
			<td align=left valign="bottom"><select <?php genSelectHtml($schema["fabranch"],array("loadudflinktable","branches:branchcode:branchname",":[All]")) ?> ></select></td>
		  <td align=left><!--DWLayoutEmptyCell-->&nbsp;</td>
		  </tr>
		<tr>
		 <td ><label <?php genCaptionHtml($schema["faclass"],"") ?> >Asset Class</label></td>
			<td align=left valign="bottom"><select <?php genSelectHtml($schema["faclass"],array("loadudflinktable","u_faclass:code:name",":[All]")) ?> ></select></td>
		  <td align=left><!--DWLayoutEmptyCell-->&nbsp;</td>
		  </tr>
		<tr>
		  <td ><label <?php genCaptionHtml($schema["year"],"") ?> >Year</label></td>
		  <td align=left valign="bottom"><select <?php genSelectHtml($schema["year"],array("loadenumyear",$httpVars['year'],"")) ?> ></select></td>
		  <td align=left><!--DWLayoutEmptyCell-->&nbsp;</td>
		  </tr>
		<tr>
		  <td ><label <?php genCaptionHtml($schema["month"],"") ?> >Month</label></td>
		  <td align=left valign="bottom"><select <?php genSelectHtml($schema["month"],array("loadenummonth",$httpVars['month'],"")) ?> ></select></td>
		  <td align=left><!--DWLayoutEmptyCell-->&nbsp;</td>
		  </tr>
		<tr>
		  <td ><label <?php genCaptionHtml($schema["docdate"],"") ?> >Posting Date</label></td>
		  <td align=left valign="bottom"><input type="text" <?php genInputTextHtml($schema["docdate"]) ?> /></td>
		  <td align=left><!--DWLayoutEmptyCell-->&nbsp;</td>
		  </tr>
		
		<tr>
		  <td>&nbsp;</td>
		  <td align=left><!--DWLayoutEmptyCell-->&nbsp;</td>
		  <td align=left></td>
		  </tr>
		<tr> 
			<td width="150">&nbsp;</td>
			<td width="805" align=left><a class="button" href="" onClick="<?php echo @$toolbarframe ?>formEntry();return false;">Retrieve</a></td>
			<td width="805" align=left></td>
		</tr>
		
		<tr class="fillerRow5px"><td></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
	</table></td>
</tr>
<tr>
<td>
	<?php $objGrid->draw(true) ?>
</td>
</tr>
<tr class="fillerRow10px">
	<td width="100%" align=left><a class="button" href="" onClick="<?php echo @$toolbarframe ?>formSubmit('check');return false;">Check / Uncheck All</a></td>
	<input type="hidden" readonly="readonly" size=5 name="df_chxstat" id="df_chxstat" value="1" />
</tr>
<?php if ($requestorId == "") { ?>
<tr><td>
<?php //require(getUserProgramFilePath("u_MotorRegBatchPostingToolbar.php"));  ?>
</td></tr>
<?php } ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>

<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_facreateplandepreToolbar.php");
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
