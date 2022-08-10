<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_hismergeaccts";
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/customers.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./sls/enumdocstatus.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	$post = false;
	$validate = false;
	$page->objectcode = $progid;
	$httpVars["df_errormessages"] = "";

	unset($enumdocstatus["D"],$enumdocstatus["CN"],$enumdocstatus["O"],$enumdocstatus["C"]);
	$enumdocstatus["IP"] = "In-Patients";
	$enumdocstatus["OP"] = "Out-Patients";

		
	function onFormAction($action) {
		global $httpVars;
		global $objConnection;
		global $branch;
		global $branchdata;
		global $page;
		global $company;
		global $branch;
		
		$actionReturn = true;
		
		if ($action!="u_process") return true;
		
		$objRs = new recordset(null,$objConnection);
		$objConnection->beginwork();

		$objCustomers = new customers(null,$objConnection);
		$obju_HISRequests = new documentschema_br(null,$objConnection,"u_hisrequests");
		$obju_HISCharges = new documentschema_br(null,$objConnection,"u_hischarges");
		$obju_HISCredits = new documentschema_br(null,$objConnection,"u_hiscredits");
		$obju_HISPos = new documentschema_br(null,$objConnection,"u_hispos");
		$obju_HISLabTests = new documentschema_br(null,$objConnection,"u_hislabtests");
		
		if ($page->getitemstring("reftypefr")=="IP") {
			$obju_HISFrRegs = new documentschema_br(null,$objConnection,"u_hisips");
			$obju_HISFrRegTrxs = new documentlinesschema_br(null,$objConnection,"u_hisiptrxs");
		} else {
			$obju_HISFrRegs = new documentschema_br(null,$objConnection,"u_hisops");
			$obju_HISFrRegTrxs = new documentlinesschema_br(null,$objConnection,"u_hisoptrxs");
		}

		if ($page->getitemstring("reftypeto")=="IP") {
			$obju_HISToRegs = new documentschema_br(null,$objConnection,"u_hisips");
			$obju_HISToRegTrxs = new documentlinesschema_br(null,$objConnection,"u_hisiptrxs");
		} else {
			$obju_HISToRegs = new documentschema_br(null,$objConnection,"u_hisops");
			$obju_HISToRegTrxs = new documentlinesschema_br(null,$objConnection,"u_hisoptrxs");
		}

		if ($obju_HISFrRegs->getbykey($page->getitemstring("refnofr"))) {
			if ($obju_HISToRegs->getbykey($page->getitemstring("refnoto"))) {
				$u_reqcnt=0;
				$u_chrgcnt=0;
				$u_cmcnt=0;
				$u_totalcharges=0;
				$obju_HISFrRegTrxs->queryopen($obju_HISFrRegTrxs->selectstring(). " AND DOCID = '".$obju_HISFrRegs->docid."'");
				while ($obju_HISFrRegTrxs->queryfetchrow()) {
					$obju_HISToRegTrxs->prepareadd();
					$obju_HISToRegTrxs->docid = $obju_HISToRegs->docid;
					$obju_HISToRegTrxs->lineid = getNextIdByBranch("u_hisiptrxs",$objConnection);
					$obju_HISToRegTrxs->setudfvalue("u_docdate",$obju_HISFrRegTrxs->getudfvalue("u_docdate"));
					$obju_HISToRegTrxs->setudfvalue("u_doctime",$obju_HISFrRegTrxs->getudfvalue("u_doctime"));
					$obju_HISToRegTrxs->setudfvalue("u_docno",$obju_HISFrRegTrxs->getudfvalue("u_docno"));
					$obju_HISToRegTrxs->setudfvalue("u_refno",$obju_HISFrRegTrxs->getudfvalue("u_refno"));
					$obju_HISToRegTrxs->setudfvalue("u_department",$obju_HISFrRegTrxs->getudfvalue("u_department"));
					$obju_HISToRegTrxs->setudfvalue("u_doctype",$obju_HISFrRegTrxs->getudfvalue("u_doctype"));
					$obju_HISToRegTrxs->setudfvalue("u_docdesc",$obju_HISFrRegTrxs->getudfvalue("u_docdesc"));
					$obju_HISToRegTrxs->setudfvalue("u_amount",$obju_HISFrRegTrxs->getudfvalue("u_amount"));
					$obju_HISToRegTrxs->setudfvalue("u_docstatus",$obju_HISFrRegTrxs->getudfvalue("u_docstatus"));
					$obju_HISToRegTrxs->setudfvalue("u_reftype",$obju_HISFrRegTrxs->getudfvalue("u_reftype"));
					$obju_HISToRegTrxs->setudfvalue("u_balance",$obju_HISFrRegTrxs->getudfvalue("u_balance"));
					$obju_HISToRegTrxs->privatedata["header"] = $obju_HISToRegs;
					$actionReturn = $obju_HISToRegTrxs->add();
					if (!$actionReturn) break;
					$actionReturn = $obju_HISFrRegTrxs->delete();
					if (!$actionReturn) break;
				}
				if ($actionReturn) {
					if ($objCustomers->getbykey($obju_HISFrRegs->getudfvalue("u_patientid"))) {
						$objCustomers->balance = $objCustomers->balance - $obju_HISFrRegs->getudfvalue("u_totalcharges");
						$actionReturn = $objCustomers->update($objCustomers->custno,$objCustomers->rcdversion);
					} else return raiseError("Unable to retrieve customer [".$obju_HISFrRegs->getudfvalue("u_patientname")."] record.");	
				}
				if ($actionReturn) {
					if ($objCustomers->getbykey($obju_HISToRegs->getudfvalue("u_patientid"))) {
						$objCustomers->balance = $objCustomers->balance + $obju_HISFrRegs->getudfvalue("u_totalcharges");
						$actionReturn = $objCustomers->update($objCustomers->custno,$objCustomers->rcdversion);
					} else return raiseError("Unable to retrieve customer [".$obju_HISToRegs->getudfvalue("u_patientname")."] record.");	
				}
				if ($actionReturn) {
					$obju_HISToRegs->setudfvalue("u_reqcnt",$obju_HISToRegs->getudfvalue("u_reqcnt")+$obju_HISFrRegs->getudfvalue("u_reqcnt"));
					$obju_HISToRegs->setudfvalue("u_chrgcnt",$obju_HISToRegs->getudfvalue("u_chrgcnt")+$obju_HISFrRegs->getudfvalue("u_chrgcnt"));
					$obju_HISToRegs->setudfvalue("u_cmcnt",$obju_HISToRegs->getudfvalue("u_cmcnt")+$obju_HISFrRegs->getudfvalue("u_cmcnt"));
					$obju_HISToRegs->setudfvalue("u_totalcharges",$obju_HISToRegs->getudfvalue("u_totalcharges")+$obju_HISFrRegs->getudfvalue("u_totalcharges"));
					$obju_HISToRegs->setudfvalue("u_availablecreditlimit",$obju_HISToRegs->getudfvalue("u_creditlimit") - $obju_HISToRegs->getudfvalue("u_totalcharges"));
					$obju_HISToRegs->setudfvalue("u_availablecreditperc",100-((objutils::Divide($obju_HISToRegs->getudfvalue("u_totalcharges"),$obju_HISToRegs->getudfvalue("u_creditlimit")))*100));
					
					$actionReturn = $obju_HISToRegs->update($obju_HISToRegs->docno,$obju_HISToRegs->rcdversion);
				}	
				if ($actionReturn) {
					$obju_HISFrRegs->setudfvalue("u_reqcnt",0);
					$obju_HISFrRegs->setudfvalue("u_chrgcnt",0);
					$obju_HISFrRegs->setudfvalue("u_cmcnt",0);
					$obju_HISFrRegs->setudfvalue("u_totalcharges",0);
					$obju_HISFrRegs->setudfvalue("u_availablecreditlimit",$obju_HISFrRegs->getudfvalue("u_creditlimit") - $obju_HISFrRegs->getudfvalue("u_totalcharges"));
					$obju_HISFrRegs->setudfvalue("u_availablecreditperc",100-((objutils::Divide($obju_HISFrRegs->getudfvalue("u_totalcharges"),$obju_HISFrRegs->getudfvalue("u_creditlimit")))*100));
					
					$actionReturn = $obju_HISFrRegs->update($obju_HISFrRegs->docno,$obju_HISFrRegs->rcdversion);
				}	
				
			} else return raiseError("Unable to retrieve Registration [".$page->getitemstring("refnoto")."] record.");	
		} else return raiseError("Unable to retrieve Registration [".$page->getitemstring("refnofr")."] record.");	

		if ($actionReturn) {
			$obju_HISLabTests->queryopen($obju_HISLabTests->selectstring()." and U_REFTYPE='".$page->getitemstring("reftypefr")."' AND U_REFNO='".$page->getitemstring("refnofr")."' AND U_PATIENTID='".$page->getitemstring("patientidfr")."'");
			while ($obju_HISLabTests->queryfetchrow()) {
				$obju_HISLabTests->setudfvalue("u_reftype",$page->getitemstring("reftypeto"));
				$obju_HISLabTests->setudfvalue("u_refno",$page->getitemstring("refnoto"));
				$obju_HISLabTests->setudfvalue("u_patientid",$page->getitemstring("patientidto"));
				$obju_HISLabTests->setudfvalue("u_patientname",$page->getitemstring("patientnameto"));
				$actionReturn = $obju_HISLabTests->update($obju_HISLabTests->docno,$obju_HISLabTests->rcdversion);
				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) {
			$obju_HISRequests->queryopen($obju_HISRequests->selectstring()." and U_REFTYPE='".$page->getitemstring("reftypefr")."' AND U_REFNO='".$page->getitemstring("refnofr")."' AND U_PATIENTID='".$page->getitemstring("patientidfr")."'");
			while ($obju_HISRequests->queryfetchrow()) {
				//var_dump($obju_HISRequests->docno);
				$obju_HISRequests->setudfvalue("u_reftype",$page->getitemstring("reftypeto"));
				$obju_HISRequests->setudfvalue("u_refno",$page->getitemstring("refnoto"));
				$obju_HISRequests->setudfvalue("u_patientid",$page->getitemstring("patientidto"));
				$obju_HISRequests->setudfvalue("u_patientname",$page->getitemstring("patientnameto"));
				$actionReturn = $obju_HISRequests->update($obju_HISRequests->docno,$obju_HISRequests->rcdversion);
				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) {
			$obju_HISCharges->queryopen($obju_HISCharges->selectstring()." and U_REFTYPE='".$page->getitemstring("reftypefr")."' AND U_REFNO='".$page->getitemstring("refnofr")."' AND U_PATIENTID='".$page->getitemstring("patientidfr")."'");
			while ($obju_HISCharges->queryfetchrow()) {
				//var_dump($obju_HISCharges->docno);
				$obju_HISCharges->setudfvalue("u_reftype",$page->getitemstring("reftypeto"));
				$obju_HISCharges->setudfvalue("u_refno",$page->getitemstring("refnoto"));
				$obju_HISCharges->setudfvalue("u_patientid",$page->getitemstring("patientidto"));
				$obju_HISCharges->setudfvalue("u_patientname",$page->getitemstring("patientnameto"));
				$actionReturn = $obju_HISCharges->update($obju_HISCharges->docno,$obju_HISCharges->rcdversion);
				$docno = $obju_HISCharges->docno;
				$slacctno = $page->getitemstring("patientidfr");
				$patientid = $page->getitemstring("patientidto");
				$patientname = $page->getitemstring("patientnameto");
				if ($actionReturn) $actionReturn = $objRs->executesql("update salesdeliveries set bpcode='$patientid', bpname='$patientname' where company='$company' and branch='$branch' and docno='$docno'",false);
				if ($actionReturn) $actionReturn = $objRs->executesql("update salesreturns set bpcode='$patientid', bpname='$patientname' where company='$company' and branch='$branch' and docno='$docno'",false);
				if ($actionReturn) $actionReturn = $objRs->executesql("update arinvoices set bpcode='$patientid', bpname='$patientname' where company='$company' and branch='$branch' and docno='$docno'",false);
				if ($actionReturn) $actionReturn = $objRs->executesql("update arcreditmemos set bpcode='$patientid', bpname='$patientname' where company='$company' and branch='$branch' and docno='$docno'",false);
				if ($actionReturn) $actionReturn = $objRs->executesql("update journalentryitems set slacctno='$patientid', slacctname='$patientname' where company='$company' and branch='$branch' and docno='$docno' and slacctno='$slacctno'  and doctype in ('AR','CS','CM','RT')",false);
				
				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) {
			$obju_HISCredits->queryopen($obju_HISCredits->selectstring()." and U_REFTYPE='".$page->getitemstring("reftypefr")."' AND U_REFNO='".$page->getitemstring("refnofr")."' AND U_PATIENTID='".$page->getitemstring("patientidfr")."'");
			while ($obju_HISCredits->queryfetchrow()) {
				//var_dump($obju_HISCredits->docno);
				$obju_HISCredits->setudfvalue("u_reftype",$page->getitemstring("reftypeto"));
				$obju_HISCredits->setudfvalue("u_refno",$page->getitemstring("refnoto"));
				$obju_HISCredits->setudfvalue("u_patientid",$page->getitemstring("patientidto"));
				$obju_HISCredits->setudfvalue("u_patientname",$page->getitemstring("patientnameto"));
				$actionReturn = $obju_HISCredits->update($obju_HISCredits->docno,$obju_HISCredits->rcdversion);
				$docno = $obju_HISCredits->docno;
				$slacctno = $page->getitemstring("patientidfr");
				$patientid = $page->getitemstring("patientidto");
				$patientname = $page->getitemstring("patientnameto");
				if ($actionReturn) $actionReturn = $objRs->executesql("update arcreditmemos set bpcode='$patientid', bpname='$patientname' where company='$company' and branch='$branch' and docno='$docno'",false);
				if ($actionReturn) $actionReturn = $objRs->executesql("update journalentryitems set slacctno='$patientid', slacctname='$patientname' where company='$company' and branch='$branch' and docno='$docno' and slacctno='$slacctno' and doctype in ('CM')",false);

				if (!$actionReturn) break;
			}
		}
		if ($actionReturn) {
			$obju_HISPos->queryopen($obju_HISPos->selectstring()." and U_REFTYPE='".$page->getitemstring("reftypefr")."' AND U_REFNO='".$page->getitemstring("refnofr")."' AND U_PATIENTID='".$page->getitemstring("patientidfr")."'");
			while ($obju_HISPos->queryfetchrow()) {
				//var_dump($obju_HISPos->docno);
				$obju_HISPos->setudfvalue("u_reftype",$page->getitemstring("reftypeto"));
				$obju_HISPos->setudfvalue("u_refno",$page->getitemstring("refnoto"));
				$obju_HISPos->setudfvalue("u_patientid",$page->getitemstring("patientidto"));
				$obju_HISPos->setudfvalue("u_patientname",$page->getitemstring("patientnameto"));
				$actionReturn = $obju_HISPos->update($obju_HISPos->docno,$obju_HISPos->rcdversion);

				$docno = $obju_HISPos->docno;
				$slacctno = $page->getitemstring("patientidfr");
				$patientid = $page->getitemstring("patientidto");
				$patientname = $page->getitemstring("patientnameto");
				if ($actionReturn) $actionReturn = $objRs->executesql("update arinvoices set bpcode='$patientid', bpname='$patientname' where company='$company' and branch='$branch' and docno='$docno'",false);
				if ($actionReturn) $actionReturn = $objRs->executesql("update collections set bpcode='$patientid', bpname='$patientname' where company='$company' and branchcode='$branch' and docno='$docno'",false);
				if ($actionReturn) $actionReturn = $objRs->executesql("update journalentryitems set slacctno='$patientid', slacctname='$patientname' where company='$company' and branch='$branch' and docno='$docno' and slacctno='$slacctno' and doctype in ('AR','CS','RC')",false);
				if (!$actionReturn) break;
			}
		}

		//var_dump($objRs->sqls);
		//var_dump($objRs2->sqls);
		//var_dump($datefr);
		//var_dump($dateto);
		//if ($actionReturn) $actionReturn = raiseError("onFormAction()");
		if ($actionReturn) $objConnection->commit();
		else $objConnection->rollback();


		return $actionReturn;
	}
	
	function onFormDefault() {
		global $page;
		global $branchdata;
		
	}
	
	$page->reportlayouts = true;
	$page->settings->load();

	//var_dump($branchdata);
	
	$objrs = new recordset(NULL,$objConnection);
	$objrs2 = new recordset(NULL,$objConnection);
	$objrs->logtrx = false;
	
	$actionReturn=true;
	
		
	
	require("./inc/formactions.php");
	
	$schema_batchpost["reftypefr"] = createSchema("reftypefr");
	$schema_batchpost["reftypeto"] = createSchema("reftypeto");
	$schema_batchpost["refnofr"] = createSchema("refnofr");
	$schema_batchpost["refnoto"] = createSchema("refnoto");
	$schema_batchpost["patientidfr"] = createSchema("patientidfr");
	$schema_batchpost["patientidto"] = createSchema("patientidto");
	$schema_batchpost["patientnamefr"] = createSchema("patientnamefr");
	$schema_batchpost["patientnameto"] = createSchema("patientnameto");

	$schema_batchpost["refnofr"]["cfl"] = "OpenCFLfs()";
	$schema_batchpost["refnoto"]["cfl"] = "OpenCFLfs()";
	
	$schema_batchpost["patientidfr"]["attributes"] = "disabled";
	$schema_batchpost["patientidto"]["attributes"] = "disabled";
	$schema_batchpost["patientnamefr"]["attributes"] = "disabled";
	$schema_batchpost["patientnameto"]["attributes"] = "disabled";
	//$schema_batchpost["datefr"]["cfl"] = "Calendar";	
	//$schema_batchpost["dateto"]["cfl"] = "Calendar";	

	$schema_batchpost["retypefr"]["required"] = true;
	$schema_batchpost["retypeto"]["required"] = true;
	$schema_batchpost["refnofr"]["required"] = true;
	$schema_batchpost["refnoto"]["required"] = true;
	
	saveErrorMsg();
	//var_dump($sql);
	//var_dump($objGrid->recordcount);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo $page->theme ; ?>.css">
<STYLE>
</STYLE>
</head>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatebusinesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/directories.js"></SCRIPT>
<script type="text/javascript">
document.write('<style type="text/css">.tabber{display:none;}<\/style>');
var tabberOptions = {
  'manualStartup':true,
  'onLoad': function(argsObj) {},
  'onClick': function(argsObj) {return true;},
  'addLinkId': true
};
</script>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>

<script>

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}
	
	function onPageLoad() {
	}
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "datefr":
				setInput("dateto",getInput("datefr"));
				break;
		}
	}
	
	function onFormSubmit(action) {
		if(action=="u_process") {
			if (isInputEmpty("reftypefr")) return false; 
			if (isInputEmpty("refnofr")) return false; 
			if (isInputEmpty("reftypeto")) return false; 
			if (isInputEmpty("refnoto")) return false; 
			if (getInput("patientnamefr")==getInput("patientnameto")) {
				if (window.confirm("Are you sure to merge different patient name records. Continue?")==false)	return false;
			}
		}
		showAjaxProcess();
		return true;
	}	

	function onFormSubmitReturn(action,sucess,error) {
		if (sucess)	alert('Process ended successfully.');
		else alert(error);
	}

	function onElementValidate(element,column,table,row) {
		switch(table) {
			default:	
				switch(column) {
					case "refnofr":
						if (getInput(column)!="") {
							if (getInput("reftypefr")=="IP") result = page.executeFormattedQuery("select u_patientid, u_patientname from u_hisips where (u_billing=1 or u_bedno<>'') and u_billno='' and docstatus not in ('Cancelled') and docno='"+utils.addslashes(getInput(column))+"'");	 
							else result = page.executeFormattedQuery("select u_patientid, u_patientname from u_hisops where u_billing=1 and u_billno='' and docstatus not in ('Cancelled') and docno='"+utils.addslashes(getInput(column))+"'");	 
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("patientidfr",result.childNodes.item(0).getAttribute("u_patientid"));
									setInput("patientnamefr",result.childNodes.item(0).getAttribute("u_patientname"));
								} else {
									setInput("patientidfr","");
									setInput("patientnamefr","");
									page.statusbar.showError("Invalid Registration No.");	
									return false;
								}
							} else {
								setInput("patientidfr","");
								setInput("patientnamefr","");
								page.statusbar.showError("Error retrieving item. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setInput("patientidfr","");
							setInput("patientnamefr","");
						}
						break;
					case "refnoto":
						if (getInput(column)!="") {
							if (getInput("reftypeto")=="IP") result = page.executeFormattedQuery("select u_patientid, u_patientname from u_hisips where (u_billing=1 or u_bedno<>'') and u_billno='' and docstatus not in ('Cancelled') and docno='"+utils.addslashes(getInput(column))+"'");	 
							else result = page.executeFormattedQuery("select u_patientid, u_patientname from u_hisops where u_billing=1 and u_billno='' and docstatus not in ('Cancelled') and docno='"+utils.addslashes(getInput(column))+"'");	  
							if (result.getAttribute("result")!= "-1") {
								if (parseInt(result.getAttribute("result"))>0) {
									setInput("patientidto",result.childNodes.item(0).getAttribute("u_patientid"));
									setInput("patientnameto",result.childNodes.item(0).getAttribute("u_patientname"));
								} else {
									setInput("patientidto","");
									setInput("patientnameto","");
									page.statusbar.showError("Invalid Registration No.");	
									return false;
								}
							} else {
								setInput("patientidto","");
								setInput("patientnameto","");
								page.statusbar.showError("Error retrieving item. Try Again, if problem persists, check the connection.");	
								return false;
							}
						} else {
							setInput("patientidto","");
							setInput("patientnameto","");
						}
						break;
				}
				break;
		}
		return true;
	}
	
	function onElementCFLGetParams(element) {
		var params = new Array();
		switch (element.id) {
			case "df_refnofr":
				if (getInput("reftypefr")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where (u_billing=1 or u_bedno<>'') and u_billno='' and docstatus not in ('Cancelled')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where u_billing=1 and u_billno='' and docstatus not in ('Cancelled')")); 
				}
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
				params["params"] += "&cflsortby=u_patientname";
				break;
			case "df_refnoto":
				if (getInput("reftypeto")=="IP") {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisips where (u_billing=1 or u_bedno<>'') and u_billno='' and docstatus not in ('Cancelled')")); 
				} else {
					params["params"] = "&cflquery="+utils.replaceSpecialChar(Base64.encode("select docno,u_patientname from u_hisops where u_billing=1 and u_billno='' and docstatus not in ('Cancelled')")); 
				}
				params["params"] += "&cfltitles="+utils.replaceSpecialChar(Base64.encode("No.`Patient Name")); 			
				params["params"] += "&cflwidths="+utils.replaceSpecialChar(Base64.encode("15`50")); 			
				params["params"] += "&cflformats="+utils.replaceSpecialChar(Base64.encode("``")); 			
				params["params"] += "&cflsortby=u_patientname";
				break;
		}
		return params;
	}	
	
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<input type="hidden" id="batchpostingmode" name="batchpostingmode" value="<?php echo $companydata["BATCHPOSTINGMODE"];  ?>">	
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td class="labelPageHeader" >&nbsp;<?php echo @$pageHeader ?>&nbsp;</td></tr>
</table></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	


	<tr>
	  <td align=left><label class="lblobjs"><b>From:</b></label></td>
	  <td align=left colspan="2">&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left width="168"><label <?php genCaptionHtml($schema_batchpost["refnofr"],"") ?> >Ref Type/No.:</label></td>
	  <td align=left colspan="2">&nbsp;<select <?php genSelectHtml($schema_batchpost["reftypefr"],array("loadenumdocstatus","","")) ?> /></select>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["refnofr"]) ?> /></td>
	  		<td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left width="168"><label <?php genCaptionHtml($schema_batchpost["patientidfr"],"") ?> >Patient ID/Name</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["patientidfr"]) ?> />&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema_batchpost["patientnamefr"]) ?> /></td>
	  		<td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left>&nbsp;</td>
	  <td align=left colspan="2">&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left><label class="lblobjs"><b>To:</b></label></td>
	  <td align=left colspan="2">&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left width="168"><label <?php genCaptionHtml($schema_batchpost["refnoto"],"") ?> >Ref Type/No.:</label></td>
	  <td align=left colspan="2">&nbsp;<select <?php genSelectHtml($schema_batchpost["reftypeto"],array("loadenumdocstatus","","")) ?> /></select>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["refnoto"]) ?> /></td>
	  		<td >&nbsp;</td>
	  </tr>
	<tr>
	  <td align=left width="168"><label <?php genCaptionHtml($schema_batchpost["patientidto"],"") ?> >Patient ID/Name</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["patientidto"]) ?> />&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema_batchpost["patientnameto"]) ?> /></td>
	  		<td >&nbsp;</td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td colspan="2">&nbsp;<a class="button" href="" onClick="formSubmit('u_process');return false;">Execute</a></td>
	  <td >&nbsp;</td>
	  </tr>
	
</table></td></tr>			
<?php if ($requestorId == "") { ?>
	<tr><td>&nbsp;</td></tr>
	<?php //require("./sboBatchPostingToolbar.php");  ?>
<?php } ?>    
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php require("./bofrms/ajaxprocess.php"); ?>
<?php $page->writePopupMenuHTML();?>
</body>

</html>
<?php $page->writeEndScript(); ?>
<?php	
	restoreErrorMsg();

	//$parentref = "parent.";
	//$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_DataMigrationToolbar.php");
?>
<?php 
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
