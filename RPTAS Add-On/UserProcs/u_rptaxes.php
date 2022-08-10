<?php
 include_once("../Addons/GPS/LGU Add-On/UserProcs/sls/u_lguposterminalseries.php"); 

//$page->businessobject->events->add->customAction("onCustomActionGPSRPTAS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSRPTAS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSRPTAS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSRPTAS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSRPTAS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSRPTAS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSRPTAS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSRPTAS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSRPTAS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSRPTAS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSRPTAS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSRPTAS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSRPTAS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSRPTAS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSRPTAS");

function onGridColumnHeaderDraw($table, $column , &$ownerdraw) {
	global $page;
	global $objGrids;
	if ($table=="T1" && $column=="u_selected" && $page->getitemstring("docstatus")=="D") {
		$checked["name"] = $column;
		$checked["attributes"] = "";
		$checked["input"]["value"] = 1;
		echo "<input type=\"checkbox\"" . $objGrids[0]->genInputCheckBoxHtml($checked,$table) . "/>";
		$ownerdraw = true;
	}
        if ($table=="T3" && $column=="u_selected" && $page->getitemstring("docstatus")=="D") {  
		$checked["name"] = $column;
		$checked["attributes"] = "";
		$checked["input"]["value"] = 1;
		echo "<input type=\"checkbox\"" . $objGrids[2]->genInputCheckBoxHtml($checked,$table) . "/>";
		$ownerdraw = true;
	}
//        if ($table=="T4" && $column=="u_selected" && $page->getitemstring("docstatus")=="D") {
//		$checked["name"] = $column;
//		$checked["attributes"] = "";
//		$checked["input"]["value"] = 1;
//		echo "<input type=\"checkbox\"" . $objGrids[3]->genInputCheckBoxHtml($checked,$table) . "/>";
//		$ownerdraw = true;
//	}
}

function onCustomActionGPSRPTAS($action) {
	return true;
}

function onBeforeDefaultGPSRPTAS() { 
	return true;
}

function onAfterDefaultGPSRPTAS() { 
	global $objConnection;
	global $page;
       
	global $objGrids;
	
	$autoseries = 0;
	$autopost = 0;
	$objRs = new recordset(null,$objConnection);
	$objRs2 = new recordset(null,$objConnection);
        $objRs2->queryopen("select CODE,U_TERMINALID,U_USERID from U_LGUPOSREGISTERS where BRANCH='".$_SESSION["branch"]."' AND  U_USERID ='".$_SESSION["userid"]."' and U_STATUS='O'");
        if ($objRs2->queryfetchrow("NAME")) {
        } else {
                header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=Cash Register is currently closed.'); 
                return;
        }
        $objRs->queryopen("SELECT A.CODE, B.U_AUTOSERIES,B.U_ISSUEDOCNO,B.DOCNO AS U_SERIESDOCNO,B.U_DOCSERIES,B.U_DOCSERIESNAME  FROM U_LGUPOSREGISTERS A
                                INNER JOIN U_TERMINALSERIES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.CODE = B.U_REGISTERID
                                INNER JOIN U_LGUPROFITCENTERS C ON B.U_DOCSERIESNAME = C.U_DOCSERIES AND C.CODE = 'RP'
                                WHERE A.BRANCH='".$_SESSION["branch"]."' AND A.COMPANY = '".$_SESSION["company"]."' AND A.U_USERID='".$_SESSION['userid']."' AND A.U_STATUS = 'O'  AND B.U_NEXTNO <= B.U_LASTNO ORDER BY B.U_ISDEFAULT DESC ");

	if ($objRs->queryfetchrow("NAME")) {
//		$terminalid = $objRs->fields["CODE"];
		$autoseries = $objRs->fields["U_AUTOSERIES"];
//		$autopost = $objRs->fields["U_AUTOPOST"];
                $seriesdocno = $objRs->fields["U_SERIESDOCNO"];
		$series = $objRs->fields["U_DOCSERIESNAME"];
		$docseries = $objRs->fields["U_DOCSERIES"];
                $issuedocno = $objRs->fields["U_ISSUEDOCNO"];
//		$objRs2->queryopen("select CODE,U_TERMINALID,U_USERID from U_LGUPOSREGISTERS where BRANCH='".$_SESSION["branch"]."' AND  U_USERID ='".$_SESSION["userid"]."' and U_STATUS='O'");
//		if ($objRs2->queryfetchrow("NAME")) {
//			if ($objRs2->fields["U_USERID"]==$_SESSION["userid"]) {
//				$page->setitem("u_terminalid",$objRs2->fields["U_TERMINALID"]);
//                                
//			} else {
//				header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=Cash Register is currently opened by user ['.$objRs2->fields["U_USERID"].'].'); 
//				return;
//			}	
//		} else {
//			header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=Cash Register is currently closed.'); 
//			return;
//		}	
	} else {
		header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=No Terminal/Series maintained for USER ['.$_SESSION['userid'].'].'); 
		return;
	}
        
        if ($autoseries==1) {
		$obju_POSTerminals = new masterdataschema_br(null,$objConnection,"u_lguposterminals");
                $obju_TerminalSeries = new documentschema_br(null,$objConnection,"u_terminalseries");
		if ($obju_TerminalSeries->getbykey($seriesdocno)) {
			$numlen = $obju_TerminalSeries->getudfvalue("u_numlen");
			$prefix = $obju_TerminalSeries->getudfvalue("u_prefix");
			$suffix = $obju_TerminalSeries->getudfvalue("u_suffix");
			$nextno = $obju_TerminalSeries->getudfvalue("u_nextno");	
			
			$prefix = str_replace("{POS}", $page->getitemstring("u_terminalid"), $prefix);
			$suffix = str_replace("{POS}", $page->getitemstring("u_terminalid"), $suffix);
			$prefix = str_replace("[m]", str_pad( date('m'), 2, "0", STR_PAD_LEFT), $prefix);
			$suffix = str_replace("[m]", str_pad( date('m'), 2, "0", STR_PAD_LEFT), $suffix);
			$prefix = str_replace("[Y]", date('Y'), $prefix);
			$suffix = str_replace("[Y]", date('Y'), $suffix);
			$prefix = str_replace("[y]", str_pad(substr(date('Y'),2), 2, "0", STR_PAD_LEFT), $prefix);
			$suffix = str_replace("[y]", str_pad(substr(date('Y'),2), 2, "0", STR_PAD_LEFT), $suffix);
			
			$docno = $prefix . str_pad( $nextno, $numlen, "0", STR_PAD_LEFT) . $suffix;
			$page->setitem("u_seriesdocno",$series);
			$page->setitem("u_docseries",$docseries);
			$page->setitem("u_issuedocno",$issuedocno);
			$page->setitem("u_ornumber",$docno);
		}
	} else {
		$page->setitem("u_docseries",-1);
		$page->setitem("u_ornumber","");
		$page->setitem("u_ordate","");
	}
      
           
        
	$page->setitem("u_withfaas",1);
	$page->setitem("u_rate",formatNumericPercent(1));
	$page->setitem("u_idlelandrate",formatNumericPercent(5));
	$page->setitem("u_sefrate",formatNumericPercent(1));
	$page->setitem("u_discperc",formatNumericPercent(10));
	$page->setitem("u_1stqtrdiscperc",formatNumericPercent(17.5));
	$page->setitem("u_advancediscperc",formatNumericPercent(20));
	$page->setitem("u_year",date('Y'));
	$page->setitem("u_ordate",currentdate());
	$page->setitem("u_curdate",currentdate());
	$page->setitem("u_assdate",currentdate());
//	$page->setitem("u_assdate",currentdate());
	$page->setitem("u_paymode","A");
	$page->setitem("u_noofadvanceyear","1");
	$page->setitem("docstatus","D");
        
       
	return true;
}

function onPrepareAddGPSRPTAS(&$override) { 
	return true;
}

function onBeforeAddGPSRPTAS() { 
	return true;
}

function onAfterAddGPSRPTAS() { 
	return true;
}

function onPrepareEditGPSRPTAS(&$override) { 
	return true;
}

function onBeforeEditGPSRPTAS() { 
	return true;
}

function onAfterEditGPSRPTAS() { 
	return true;
}

function onPrepareUpdateGPSRPTAS(&$override) { 
	return true;
}

function onBeforeUpdateGPSRPTAS() { 
	return true;
}

function onAfterUpdateGPSRPTAS() { 
	return true;
}

function onPrepareDeleteGPSRPTAS(&$override) { 
	return true;
}

function onBeforeDeleteGPSRPTAS() { 
	return true;
}

function onAfterDeleteGPSRPTAS() { 
	return true;
}

$schema["iscurdate"] = createSchemaUpper("iscurdate");
$schema["withoutfaas"] = createSchemaUpper("withoutfaas");
$schema["terminalid"] = createSchemaUpper("terminalid");
$schema["arpno"] = createSchemaUpper("arpno");


$page->objectdoctype = "U_LGUBILLS";

$page->businessobject->items->setcfl("u_tdno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_tin","OpenCFLfs()");
$page->businessobject->items->setcfl("u_pin","OpenCFLfs()");
$page->businessobject->items->setcfl("u_cashsalesno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_arpno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_assdate","Calendar");
$page->businessobject->items->setcfl("u_ordate","Calendar");

$page->businessobject->items->seteditable("u_docseries",false);
$page->businessobject->items->seteditable("u_ornumber",false);
$page->businessobject->items->seteditable("u_yearfrom",false);
$page->businessobject->items->seteditable("u_arpno",false);
//$page->businessobject->items->seteditable("u_tdno",false);
$page->businessobject->items->seteditable("u_lotno",false);
$page->businessobject->items->seteditable("u_tctno",false);
$page->businessobject->items->seteditable("u_declaredowner",false);
$page->businessobject->items->seteditable("u_location",false);
$page->businessobject->items->seteditable("u_rate",false);
$page->businessobject->items->seteditable("u_sefrate",false);
$page->businessobject->items->seteditable("u_seftax",false);
$page->businessobject->items->seteditable("u_taxidleland",false);
$page->businessobject->items->seteditable("u_assvalue",false);
$page->businessobject->items->seteditable("u_tax",false);
$page->businessobject->items->seteditable("u_totaltaxbefdisc",false);
$page->businessobject->items->seteditable("u_epsftotal",false);
$page->businessobject->items->seteditable("u_totaltaxamount",false);
$page->businessobject->items->seteditable("u_changeamount",false);
$page->businessobject->items->seteditable("u_q1totaltax",false);
$page->businessobject->items->seteditable("u_q2totaltax",false);
$page->businessobject->items->seteditable("u_q3totaltax",false);
$page->businessobject->items->seteditable("u_q4totaltax",false);
$page->businessobject->items->seteditable("u_s1totaltax",false);
$page->businessobject->items->seteditable("u_s2totaltax",false);
$page->businessobject->items->seteditable("u_prevyeartaxdue",false);
$page->businessobject->items->seteditable("u_prevyearpenalty",false);
$page->businessobject->items->seteditable("u_tax",false);
$page->businessobject->items->seteditable("u_sefpenalty",false);
$page->businessobject->items->seteditable("u_penalty",false);
$page->businessobject->items->seteditable("u_discamount",false);
$page->businessobject->items->seteditable("u_sefdiscamount",false);
$page->businessobject->items->seteditable("u_kind",false);


$objGrids[0]->dataentry = false;
$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->automanagecolumn = true;
$objGrids[0]->columnwidth("u_selected",1);
$objGrids[0]->columnwidth("u_yrfr",8);
$objGrids[0]->columnwidth("u_yrto",8);
$objGrids[0]->columnwidth("u_sefdisc",6);
$objGrids[0]->columnwidth("u_kind",10);
$objGrids[0]->columnwidth("u_class",15);
$objGrids[0]->columnwidth("u_ownername",45);
$objGrids[0]->columnwidth("u_pinno",20);
$objGrids[0]->columnwidth("u_arpno",10);
$objGrids[0]->columnwidth("u_tdno",16);
$objGrids[0]->columnwidth("u_noofyrs",8);
$objGrids[0]->columnwidth("u_penalty",8);
$objGrids[0]->columnwidth("u_sef",8);
$objGrids[0]->columnwidth("u_epsf",8);
$objGrids[0]->columnwidth("u_taxdue",8);
$objGrids[0]->columnwidth("u_taxdisc",6);
$objGrids[0]->columnwidth("u_sefdisc",6);
$objGrids[0]->columnwidth("u_sefpenalty",8);
$objGrids[0]->columnwidth("u_discperc",8);
$objGrids[0]->columnwidth("u_taxtotal",8);
$objGrids[0]->columnwidth("u_seftotal",8);
$objGrids[0]->columnwidth("u_penaltyadj",8);
$objGrids[0]->columnwidth("u_sefpenaltyadj",11);
$objGrids[0]->columnwidth("u_taxdiscadj",9);
$objGrids[0]->columnwidth("u_sefdiscadj",9);
$objGrids[0]->columnwidth("u_billdate",8);
$objGrids[0]->columnwidth("u_paymode",30);
$objGrids[0]->columnsortable("u_tdno",true);
$objGrids[0]->columnsortable("u_pinno",true);
$objGrids[0]->columnvisibility("u_ownername",true);
$objGrids[0]->columnvisibility("u_taxdisc",true);
$objGrids[0]->columnvisibility("u_sefdisc",true);
$objGrids[0]->columnvisibility("u_penaltyadj",true);
$objGrids[0]->columnvisibility("u_sefpenaltyadj",true);
$objGrids[0]->columnvisibility("u_taxdiscadj",true);
$objGrids[0]->columnvisibility("u_sefdiscadj",true);
$objGrids[0]->columnvisibility("u_billdate",true);
$objGrids[0]->columnvisibility("u_isbalance",false);
$objGrids[0]->columnvisibility("u_balrefno",false);
$objGrids[0]->columnvisibility("u_penaltyadj",false);
$objGrids[0]->columnvisibility("u_sefpenaltyadj",false);
$objGrids[0]->columnvisibility("u_taxdiscadj",false);
$objGrids[0]->columnvisibility("u_sefdiscadj",false);
$objGrids[0]->columnattributes("u_billno","disabled");
$objGrids[0]->columnattributes("u_noofyrs","disabled");
$objGrids[0]->columntitle("u_noofyrs","Years");
$objGrids[0]->columntitle("u_yrfr","From");
$objGrids[0]->columntitle("u_yrto","To");
$objGrids[0]->columntitle("u_arpno","Ref No.");
$objGrids[0]->columnlnkbtn("u_pinno","openupdpays()");
$objGrids[0]->columntitle("u_billdate","Bill Date");
$objGrids[0]->columntitle("u_paymode","Payment Mode");

if ($lookupSortBy == "") {
		$lookupSortBy = "u_pinno";
	} else	$lookupSortBy = strtolower($lookupSortBy);
$objGrids[0]->setsort($lookupSortBy,$lookupSortAs);

$objGrids[1]->dataentry = false;
$objGrids[1]->columnwidth("u_kind",10);
$objGrids[1]->columnwidth("u_pin",21);
$objGrids[1]->columnwidth("u_arpno",13);
$objGrids[1]->columnwidth("u_tdno",15);
$objGrids[1]->height = 100;
$objGrids[1]->width = 600;
//$addoptions = false;
$objGrids[2]->dataentry = true;
$objGrids[2]->showactionbar = true;
$objGrids[2]->setaction("reset",false);
$objGrids[2]->setaction("add",false);
$objGrids[2]->columnwidth("u_selected",1);
$objGrids[2]->columnwidth("u_year",4);
$objGrids[2]->columnwidth("u_tdno",10);
$objGrids[2]->columnwidth("u_orrefno",10);
$objGrids[2]->columnwidth("u_apprefno",10);
$objGrids[2]->columnwidth("u_tin",10);
$objGrids[2]->columndataentry("u_selected","type","label");
$objGrids[2]->columndataentry("u_year","type","label");
$objGrids[2]->columndataentry("u_tdno","type","label");
$objGrids[2]->columndataentry("u_taxdue","type","label");
$objGrids[2]->columndataentry("u_penalty","type","label");
$objGrids[2]->columndataentry("u_sef","type","label");
$objGrids[2]->columndataentry("u_sefpenalty","type","label");
$objGrids[2]->columndataentry("u_tin","type","label");
$objGrids[2]->columndataentry("u_apprefno","type","label");
$objGrids[2]->columndataentry("u_orrefno","type","label");

//$objGrids[3]->dataentry = false;
//$objGrids[3]->columnwidth("u_selected",1);
//$objGrids[3]->columnwidth("u_yrto",6);
//$objGrids[3]->columnwidth("u_yrfr",6);
//$objGrids[3]->columnwidth("u_class",15);
//$objGrids[3]->columnwidth("u_tdno",10);
//$objGrids[3]->columnwidth("u_tin",10);
//$objGrids[3]->columnwidth("u_arpno",10);
//$objGrids[3]->columnwidth("u_orrefno",10);
//$objGrids[3]->columnwidth("u_apprefno",20);

$objMaster->reportaction = "QS";
?> 

