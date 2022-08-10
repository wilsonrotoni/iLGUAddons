<?php
 

$page->businessobject->events->add->customAction("onCustomActionGPSRPTAS");

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
//	if ($table=="T1" && $column=="u_selected" && $page->getitemstring("docstatus")=="D") {
//		$checked["name"] = $column;
//		$checked["attributes"] = "";
//		$checked["input"]["value"] = 1;
//		echo "<input type=\"checkbox\"" . $objGrids[0]->genInputCheckBoxHtml($checked,$table) . "/>";
//		$ownerdraw = true;
//	}
}

function onCustomActionGPSRPTAS($action) {
        global $objConnection;
	global $page;
	global $objGrids;
        $actionReturn = true;
                  
            if ($action=="executegr") {
                if ($page->getitemstring("u_kind") == 1) {
                    $objRpfaas1Old = new documentschema_br(null,$objConnection,"u_rpfaas1");
                    $objRpfaasNOA = new documentschema_br(null,$objConnection,"u_rpnotice");
                    $objRpfaasNOAItems = new documentlinesschema_br(null,$objConnection,"u_rpnoticeitems");
                    $objRpfaas1New = new documentschema_br(null,$objConnection,"u_rpfaas1");
                    $objRpfaas1aNew = new documentschema_br(null,$objConnection,"u_rpfaas1a");
                    $objRpfaas1bNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
                    $objRpfaas1cNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");
                    $objRpfaas1dNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas1d");
                    $objRpfaas1eNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas1e");
                    $objRpfaas1pNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas1p"); 
                    
                    $objConnection->beginwork();
                    $objRs = new recordset(null,$objConnection);
                    $objRs->queryopen("SELECT docno FROM U_RPFAAS1 WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' AND U_CANCELLED = 0 AND U_REVISIONYEAR = '".$page->getitemstring("u_revisionyearfrom")."' AND U_OLDBARANGAY = '".$page->getitemstring("u_oldbarangay")."'");
                    while ($objRs->queryfetchrow("NAME")) {
                            if ($objRpfaas1Old->getbykey($objRs->fields["docno"])) {
                                    $objRpfaas1New->prepareadd();
                                    $objRpfaas1New->docno = getNextNoByBranch("u_rpfaas1","",$objConnection);
                                    $objRpfaas1New->docid = getNextIdByBranch("u_rpfaas1",$objConnection);
                                    $objRpfaas1New->docseries = -1;
                                    $objRpfaas1New->docstatus = "Encoding";
                                    $objRpfaas1New->setudfvalue("u_pin",$objRpfaas1Old->getudfvalue("u_pin")) ;
                                    $objRpfaas1New->setudfvalue("u_claimant",$objRpfaas1Old->getudfvalue("u_claimant"));
                                    $objRpfaas1New->setudfvalue("u_tctno",$objRpfaas1Old->getudfvalue("u_tctno"));
                                    $objRpfaas1New->setudfvalue("u_tctdate",$objRpfaas1Old->getudfvalue("u_tctdate"));
                                    $objRpfaas1New->setudfvalue("u_trxcode","GR");
                                    $objRpfaas1New->setudfvalue("u_tdno","");
                                    $objRpfaas1New->setudfvalue("u_varpno","");
                                    $objRpfaas1New->setudfvalue("u_revisionyear",$page->getitemstring("u_revisionyearto"));
                                    $objRpfaas1New->setudfvalue("u_block",$objRpfaas1Old->getudfvalue("u_block"));
                                    $objRpfaas1New->setudfvalue("u_lotno",$objRpfaas1Old->getudfvalue("u_lotno")) ;
                                    $objRpfaas1New->setudfvalue("u_cadlotno",$objRpfaas1Old->getudfvalue("u_cadlotno")) ;
                                    $objRpfaas1New->setudfvalue("u_surveyno",$objRpfaas1Old->getudfvalue("u_surveyno")) ;
                                    $objRpfaas1New->setudfvalue("u_titleno",$objRpfaas1Old->getudfvalue("u_titleno")) ;
                                    $objRpfaas1New->setudfvalue("u_phase",$objRpfaas1Old->getudfvalue("u_phase")) ;
                                    $objRpfaas1New->setudfvalue("u_ownercompanyname",$objRpfaas1Old->getudfvalue("u_ownercompanyname")) ;
                                    $objRpfaas1New->setudfvalue("u_ownername",$objRpfaas1Old->getudfvalue("u_ownername")) ;
                                    $objRpfaas1New->setudfvalue("u_owneraddress",$objRpfaas1Old->getudfvalue("u_owneraddress")) ;
                                    $objRpfaas1New->setudfvalue("u_email",$objRpfaas1Old->getudfvalue("u_email")) ;
                                    $objRpfaas1New->setudfvalue("u_ownertelno",$objRpfaas1Old->getudfvalue("u_ownertelno")) ;
                                    $objRpfaas1New->setudfvalue("u_ownertin",$objRpfaas1Old->getudfvalue("u_ownertin")) ;
                                    $objRpfaas1New->setudfvalue("u_ownerkind",$objRpfaas1Old->getudfvalue("u_ownerkind")) ;
                                    $objRpfaas1New->setudfvalue("u_adminname",$objRpfaas1Old->getudfvalue("u_adminname")) ;
                                    $objRpfaas1New->setudfvalue("u_adminlastname",$objRpfaas1Old->getudfvalue("u_adminlastname")) ;
                                    $objRpfaas1New->setudfvalue("u_adminfirstname",$objRpfaas1Old->getudfvalue("u_adminfirstname")) ;
                                    $objRpfaas1New->setudfvalue("u_adminmiddlename",$objRpfaas1Old->getudfvalue("u_adminmiddlename")) ;
                                    $objRpfaas1New->setudfvalue("u_adminaddress",$objRpfaas1Old->getudfvalue("u_adminaddress")) ;
                                    $objRpfaas1New->setudfvalue("u_admintelno",$objRpfaas1Old->getudfvalue("u_admintelno")) ;
                                    $objRpfaas1New->setudfvalue("u_admintin",$objRpfaas1Old->getudfvalue("u_admintin")) ;
                                    $objRpfaas1New->setudfvalue("u_street",$objRpfaas1Old->getudfvalue("u_street")) ;
                                    $objRpfaas1New->setudfvalue("u_municipality",$objRpfaas1Old->getudfvalue("u_municipality")) ;
                                    $objRpfaas1New->setudfvalue("u_city",$objRpfaas1Old->getudfvalue("u_city")) ;
                                    $objRpfaas1New->setudfvalue("u_province",$objRpfaas1Old->getudfvalue("u_province")) ;
                                    $objRpfaas1New->setudfvalue("u_oldbarangay",$objRpfaas1Old->getudfvalue("u_oldbarangay")) ;
                                    $objRpfaas1New->setudfvalue("u_barangay",$objRpfaas1Old->getudfvalue("u_barangay")) ;
                                    $objRpfaas1New->setudfvalue("u_location",$objRpfaas1Old->getudfvalue("u_location")) ;
                                    $objRpfaas1New->setudfvalue("u_subdivision",$objRpfaas1Old->getudfvalue("u_subdivision")) ;
                                    $objRpfaas1New->setudfvalue("u_north",$objRpfaas1Old->getudfvalue("u_north")) ;
                                    $objRpfaas1New->setudfvalue("u_south",$objRpfaas1Old->getudfvalue("u_south")) ;
                                    $objRpfaas1New->setudfvalue("u_east",$objRpfaas1Old->getudfvalue("u_east")) ;
                                    $objRpfaas1New->setudfvalue("u_west",$objRpfaas1Old->getudfvalue("u_west")) ;
                                    $objRpfaas1New->setudfvalue("u_memoranda","") ;
                                    $objRpfaas1New->setudfvalue("u_annotation","") ;
                                    
                                    $u_month = intval(date('m',strtotime($page->getitemstring("u_effdate"))));
                                    $u_year = intval(date('Y',strtotime($page->getitemstring("u_effdate"))));
                                    $objRpfaas1New->setudfvalue("u_taxable",$objRpfaas1Old->getudfvalue("u_taxable")) ;
                                    $objRpfaas1New->setudfvalue("u_effdate",$page->getitemstring("u_effdate")) ;
                                    $objRpfaas1New->setudfvalue("u_effyear",$u_year) ;
                                    $objRpfaas1New->setudfvalue("u_effqtr",ceil($u_month/3)) ;
                                    
                                    $objRpfaas1New->setudfvalue("u_assvalue",$objRpfaas1Old->getudfvalue("u_assvalue")) ;
                                    $objRpfaas1New->setudfvalue("u_totalareasqm",$objRpfaas1Old->getudfvalue("u_totalareasqm")) ;
                                    $objRpfaas1New->setudfvalue("u_marketvalue",$objRpfaas1Old->getudfvalue("u_marketvalue")) ;
                                    
                                    $objRpfaas1New->setudfvalue("u_approvedby",$page->getitemstring("u_approvedby"));
                                    $objRpfaas1New->setudfvalue("u_approveddate",formatDateToDb($page->getitemstring("u_approveddate")));
                                    $objRpfaas1New->setudfvalue("u_recommendby",$page->getitemstring("u_recommendby"));
                                    $objRpfaas1New->setudfvalue("u_recommenddate",formatDateToDb($page->getitemstring("u_recommenddate")));
                                    $objRpfaas1New->setudfvalue("u_assessedby",$page->getitemstring("u_assessedby"));
                                    $objRpfaas1New->setudfvalue("u_assesseddate",formatDateToDb($page->getitemstring("u_assesseddate")));
                                    $objRpfaas1New->setudfvalue("u_recordeddate",formatDateToDb($page->getitemstring("u_recordeddate"))) ;
                                    $objRpfaas1New->setudfvalue("u_recordedby",$page->getitemstring("u_recordedby")) ;
                                    
                                    $objRpfaas1New->setudfvalue("u_prevarpno",$objRpfaas1Old->docno);
                                    $objRpfaas1New->setudfvalue("u_prevpin",$objRpfaas1Old->getudfvalue("u_pin"));
                                    $objRpfaas1New->setudfvalue("u_prevowner",$objRpfaas1Old->getudfvalue("u_ownercompanyname"));
                                    $objRpfaas1New->setudfvalue("u_preveffdate",$objRpfaas1Old->getudfvalue("u_effdate"));
                                    $objRpfaas1New->setudfvalue("u_prevtdno",$objRpfaas1Old->getudfvalue("u_tdno"));
                                    $objRpfaas1New->setudfvalue("u_prevarpno2",$objRpfaas1Old->getudfvalue("u_varpno"));
                                    $objRpfaas1New->setudfvalue("u_prevvalue",$objRpfaas1Old->getudfvalue("u_assvalue"));
                                    $objRpfaas1New->setudfvalue("u_prevrecordedby",$objRpfaas1Old->getudfvalue("u_recordedby"));
                                    $objRpfaas1New->setudfvalue("u_prevrecordeddate",$objRpfaas1Old->getudfvalue("u_recordeddate"));
                                    
                                    $objRpfaas1pNew->prepareadd();
                                    $objRpfaas1pNew->docid = $objRpfaas1New->docid;
                                    $objRpfaas1pNew->lineid = getNextIdByBranch("u_rpfaas1p",$objConnection);
                                    $objRpfaas1pNew->setudfvalue("u_prevarpno",$objRpfaas1Old->docno);
                                    $objRpfaas1pNew->setudfvalue("u_prevpin",$objRpfaas1Old->getudfvalue("u_pin"));
                                    $objRpfaas1pNew->setudfvalue("u_prevowner",$objRpfaas1Old->getudfvalue("u_ownercompanyname"));
                                    $objRpfaas1pNew->setudfvalue("u_preveffdate",$objRpfaas1Old->getudfvalue("u_effdate"));
                                    $objRpfaas1pNew->setudfvalue("u_prevtdno",$objRpfaas1Old->getudfvalue("u_tdno"));
                                    $objRpfaas1pNew->setudfvalue("u_prevarpno2",$objRpfaas1Old->getudfvalue("u_varpno"));
                                    $objRpfaas1pNew->setudfvalue("u_prevvalue",$objRpfaas1Old->getudfvalue("u_assvalue"));
                                    $objRpfaas1pNew->setudfvalue("u_prevrecordedby",$objRpfaas1Old->getudfvalue("u_recordedby"));
                                    $objRpfaas1pNew->setudfvalue("u_prevrecordeddate",$objRpfaas1Old->getudfvalue("u_recordeddate"));
                                    $actionReturn = $objRpfaas1pNew->add();
                                    
                                    $objRpfaasNOA->prepareadd();
                                    $objRpfaasNOA->docno = getNextNoByBranch("u_rpnotice","",$objConnection);
                                    $objRpfaasNOA->docid = getNextIdByBranch("u_rpnotice",$objConnection);
                                    $objRpfaasNOA->docseries = -1;
                                    $objRpfaasNOA->docstatus = "O";
                                    $objRpfaasNOA->setudfvalue("u_tin",$objRpfaas1New->getudfvalue("u_ownertin")) ;
                                    $objRpfaasNOA->setudfvalue("u_declaredowner",$objRpfaas1New->getudfvalue("u_ownercompanyname"));
                                    $objRpfaasNOA->setudfvalue("u_year",$u_year);
                                    $objRpfaasNOA->setudfvalue("u_totalmarketvalue",$objRpfaas1New->getudfvalue("u_marketvalue"));
                                    $objRpfaasNOA->setudfvalue("u_totalassvalue",$objRpfaas1New->getudfvalue("u_assvalue"));
                                    $objRpfaasNOA->setudfvalue("u_date",formatDateToDb($page->getitemstring("u_approveddate")));
                                    
                                     
                                    $objRpfaasNOAItems->prepareadd();
                                    $objRpfaasNOAItems->docid = $objRpfaasNOA->docid;
                                    $objRpfaasNOAItems->lineid = getNextIdByBranch("u_rpnoticeitems",$objConnection);
                                    $objRpfaasNOAItems->setudfvalue("u_selected",1);
                                    $objRpfaasNOAItems->setudfvalue("u_docno",$objRpfaas1New->docno);
                                    $objRpfaasNOAItems->setudfvalue("u_trxcode",$objRpfaas1New->getudfvalue("u_trxcode"));
                                    $objRpfaasNOAItems->setudfvalue("u_tdno",$objRpfaas1New->getudfvalue("u_varpno"));
                                    $objRpfaasNOAItems->setudfvalue("u_pin",$objRpfaas1New->getudfvalue("u_pin"));
                                    $objRpfaasNOAItems->setudfvalue("u_location",$objRpfaas1New->getudfvalue("u_location"));
                                    $objRpfaasNOAItems->setudfvalue("u_class",$objRpfaas1New->getudfvalue("u_class"));
                                    $objRpfaasNOAItems->setudfvalue("u_kind","L");
                                    $objRpfaasNOAItems->setudfvalue("u_marketvalue",$objRpfaas1New->getudfvalue("u_marketvalue"));
                                    $objRpfaasNOAItems->setudfvalue("u_assvalue",$objRpfaas1New->getudfvalue("u_assvalue"));
                                    $actionReturn = $objRpfaasNOAItems->add();
                                    
                                    $objRpfaas1New->setudfvalue("u_noticeno",$objRpfaasNOA->docno);
                                    
                                    if (formatDateToDb($objRpfaas1New->getudfvalue("u_effdate"))>$objRpfaas1Old->getudfvalue("u_effdate")) {
                                            $u_expdate = dateadd("d",-1,formatDateToDb($objRpfaas1New->getudfvalue("u_effdate")));
                                            $u_month = intval(date('m',strtotime($u_expdate)));
                                            $u_year = intval(date('Y',strtotime($u_expdate)));
                                            $objRpfaas1Old->setudfvalue("u_expdate",$u_expdate);
                                            $objRpfaas1Old->setudfvalue("u_expqtr",ceil($u_month/3));
                                            $objRpfaas1Old->setudfvalue("u_expyear",$u_year);
                                            $objRpfaas1Old->setudfvalue("u_cancelled",1);
                                            $actionReturn = $objRpfaas1Old->update($objRpfaas1Old->docno,$objRpfaas1Old->rcdversion);
                                    } else return raiseError("New assessment [".$objRpfaas1New->getudfvalue("u_effdate")."] cannot be same or earlier than previous assessment [".$objRpfaas1Old->getudfvalue("u_effdate")."].");
                                    $actionReturn = $objRpfaas1New->add();
                                    $actionReturn = $objRpfaasNOA->add();
                            }
                    }
                    if ($actionReturn) {
                        $objConnection->commit();
                    } else {
                        $objConnection->rollback();
                    }
                } else  if ($page->getitemstring("u_kind") == 2) {
                  
                    $objRpfaas2Old = new documentschema_br(null,$objConnection,"u_rpfaas2");
                    
                    $objRpfaasNOA = new documentschema_br(null,$objConnection,"u_rpnotice");
                    $objRpfaasNOAItems = new documentlinesschema_br(null,$objConnection,"u_rpnoticeitems");
                    
                    $objRpfaas2New = new documentschema_br(null,$objConnection,"u_rpfaas2");
                    $objRpfaas2pNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas2p"); 
                    
                    $objConnection->beginwork();
                    $objRs = new recordset(null,$objConnection);
                    
                    $objRs->queryopen("SELECT docno FROM U_RPFAAS2 WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' AND U_CANCELLED = 0 AND U_REVISIONYEAR = '".$page->getitemstring("u_revisionyearfrom")."' AND U_OLDBARANGAY = '".$page->getitemstring("u_oldbarangay")."'");
                    while ($objRs->queryfetchrow("NAME")) {
                            if ($objRpfaas2Old->getbykey($objRs->fields["docno"])) {
                                   
                                    $objRpfaas2New->prepareadd();
                                    $objRpfaas2New->docno = getNextNoByBranch("u_rpfaas2","",$objConnection);
                                    $objRpfaas2New->docid = getNextIdByBranch("u_rpfaas2",$objConnection);
                                    $objRpfaas2New->docseries = -1;
                                    $objRpfaas2New->docstatus = "Encoding";
                                    
                                    $objRpfaas2New->setudfvalue("u_prefix",$objRpfaas2Old->getudfvalue("u_prefix")) ;
                                    $objRpfaas2New->setudfvalue("u_suffix",$objRpfaas2Old->getudfvalue("u_suffix")) ;
                                    $objRpfaas2New->setudfvalue("u_pin",$objRpfaas2Old->getudfvalue("u_pin")) ;
                                    $objRpfaas2New->setudfvalue("u_tctno",$objRpfaas2Old->getudfvalue("u_tctno"));
                                    $objRpfaas2New->setudfvalue("u_trxcode","GR");
                                    $objRpfaas2New->setudfvalue("u_tdno","");
                                    $objRpfaas2New->setudfvalue("u_varpno","");
                                    $objRpfaas2New->setudfvalue("u_revisionyear",$page->getitemstring("u_revisionyearto"));
                                    $objRpfaas2New->setudfvalue("u_block",$objRpfaas2Old->getudfvalue("u_block"));
                                    $objRpfaas2New->setudfvalue("u_lotno",$objRpfaas2Old->getudfvalue("u_lotno")) ;
                                    $objRpfaas2New->setudfvalue("u_cadlotno",$objRpfaas2Old->getudfvalue("u_cadlotno")) ;
                                    $objRpfaas2New->setudfvalue("u_surveyno",$objRpfaas2Old->getudfvalue("u_surveyno")) ;
                                    $objRpfaas2New->setudfvalue("u_ownercompanyname",$objRpfaas2Old->getudfvalue("u_ownercompanyname")) ;
                                    $objRpfaas2New->setudfvalue("u_ownername",$objRpfaas2Old->getudfvalue("u_ownername")) ;
                                    $objRpfaas2New->setudfvalue("u_owneraddress",$objRpfaas2Old->getudfvalue("u_owneraddress")) ;
                                    $objRpfaas2New->setudfvalue("u_email",$objRpfaas2Old->getudfvalue("u_email")) ;
                                    $objRpfaas2New->setudfvalue("u_ownertelno",$objRpfaas2Old->getudfvalue("u_ownertelno")) ;
                                    $objRpfaas2New->setudfvalue("u_ownertin",$objRpfaas2Old->getudfvalue("u_ownertin")) ;
                                    $objRpfaas2New->setudfvalue("u_ownerkind",$objRpfaas2Old->getudfvalue("u_ownerkind")) ;
                                    $objRpfaas2New->setudfvalue("u_landowner",$objRpfaas2Old->getudfvalue("u_landowner")) ;
                                    $objRpfaas2New->setudfvalue("u_landtdno",$objRpfaas2Old->getudfvalue("u_landtdno")) ;
                                    $objRpfaas2New->setudfvalue("u_adminname",$objRpfaas2Old->getudfvalue("u_adminname")) ;
                                    $objRpfaas2New->setudfvalue("u_adminlastname",$objRpfaas2Old->getudfvalue("u_adminlastname")) ;
                                    $objRpfaas2New->setudfvalue("u_adminfirstname",$objRpfaas2Old->getudfvalue("u_adminfirstname")) ;
                                    $objRpfaas2New->setudfvalue("u_adminmiddlename",$objRpfaas2Old->getudfvalue("u_adminmiddlename")) ;
                                    $objRpfaas2New->setudfvalue("u_adminaddress",$objRpfaas2Old->getudfvalue("u_adminaddress")) ;
                                    $objRpfaas2New->setudfvalue("u_admintelno",$objRpfaas2Old->getudfvalue("u_admintelno")) ;
                                    $objRpfaas2New->setudfvalue("u_admintin",$objRpfaas2Old->getudfvalue("u_admintin")) ;
                                    $objRpfaas2New->setudfvalue("u_street",$objRpfaas2Old->getudfvalue("u_street")) ;
                                    $objRpfaas2New->setudfvalue("u_municipality",$objRpfaas2Old->getudfvalue("u_municipality")) ;
                                    $objRpfaas2New->setudfvalue("u_city",$objRpfaas2Old->getudfvalue("u_city")) ;
                                    $objRpfaas2New->setudfvalue("u_province",$objRpfaas2Old->getudfvalue("u_province")) ;
                                    $objRpfaas2New->setudfvalue("u_oldbarangay",$objRpfaas2Old->getudfvalue("u_oldbarangay")) ;
                                    $objRpfaas2New->setudfvalue("u_barangay",$objRpfaas2Old->getudfvalue("u_barangay")) ;
                                    $objRpfaas2New->setudfvalue("u_location",$objRpfaas2Old->getudfvalue("u_location")) ;
                                    $objRpfaas2New->setudfvalue("u_subdivision",$objRpfaas2Old->getudfvalue("u_subdivision")) ;
                                    $objRpfaas2New->setudfvalue("u_memoranda","") ;
                                    $objRpfaas2New->setudfvalue("u_annotation","") ;
                                    
                                    
                                    $objRpfaas2New->setudfvalue("u_building",$objRpfaas2Old->getudfvalue("u_building")) ;
                                    $objRpfaas2New->setudfvalue("u_structuretype",$objRpfaas2Old->getudfvalue("u_structuretype")) ;
                                    $objRpfaas2New->setudfvalue("u_bldgname",$objRpfaas2Old->getudfvalue("u_bldgname")) ;
                                    $objRpfaas2New->setudfvalue("u_bpdate",$objRpfaas2Old->getudfvalue("u_bpdate")) ;
                                    $objRpfaas2New->setudfvalue("u_bpno",$objRpfaas2Old->getudfvalue("u_bpno")) ;
                                    $objRpfaas2New->setudfvalue("u_startyear",$objRpfaas2Old->getudfvalue("u_startyear")) ;
                                    $objRpfaas2New->setudfvalue("u_endyear",$objRpfaas2Old->getudfvalue("u_endyear")) ;
                                    $objRpfaas2New->setudfvalue("u_occyear",$objRpfaas2Old->getudfvalue("u_occyear")) ;
                                    $objRpfaas2New->setudfvalue("u_renyear",$objRpfaas2Old->getudfvalue("u_renyear")) ;
                                    $objRpfaas2New->setudfvalue("u_cct",$objRpfaas2Old->getudfvalue("u_cct")) ;
                                    $objRpfaas2New->setudfvalue("u_ccidate",$objRpfaas2Old->getudfvalue("u_ccidate")) ;
                                    $objRpfaas2New->setudfvalue("u_coidate",$objRpfaas2Old->getudfvalue("u_coidate")) ;
                                    $objRpfaas2New->setudfvalue("u_floorcount",$objRpfaas2Old->getudfvalue("u_floorcount")) ;
                                    
                                    $u_month = intval(date('m',strtotime($page->getitemstring("u_effdate"))));
                                    $u_year = intval(date('Y',strtotime($page->getitemstring("u_effdate"))));
                                    $objRpfaas2New->setudfvalue("u_taxable",$objRpfaas2Old->getudfvalue("u_taxable")) ;
                                    $objRpfaas2New->setudfvalue("u_effdate",$page->getitemstring("u_effdate")) ;
                                    $objRpfaas2New->setudfvalue("u_effyear",$u_year) ;
                                    $objRpfaas2New->setudfvalue("u_effqtr",ceil($u_month/3)) ;
                                    
                                    $objRpfaas2New->setudfvalue("u_assvalue",$objRpfaas2Old->getudfvalue("u_assvalue")) ;
                                    $objRpfaas2New->setudfvalue("u_totalareasqm",$objRpfaas2Old->getudfvalue("u_totalareasqm")) ;
                                    $objRpfaas2New->setudfvalue("u_marketvalue",$objRpfaas2Old->getudfvalue("u_marketvalue")) ;
                                    
                                    $objRpfaas2New->setudfvalue("u_approvedby",$page->getitemstring("u_approvedby"));
                                    $objRpfaas2New->setudfvalue("u_approveddate",formatDateToDb($page->getitemstring("u_approveddate")));
                                    $objRpfaas2New->setudfvalue("u_recommendby",$page->getitemstring("u_recommendby"));
                                    $objRpfaas2New->setudfvalue("u_recommenddate",formatDateToDb($page->getitemstring("u_recommenddate")));
                                    $objRpfaas2New->setudfvalue("u_assessedby",$page->getitemstring("u_assessedby"));
                                    $objRpfaas2New->setudfvalue("u_assesseddate",formatDateToDb($page->getitemstring("u_assesseddate")));
                                    $objRpfaas2New->setudfvalue("u_recordeddate",formatDateToDb($page->getitemstring("u_recordeddate"))) ;
                                    $objRpfaas2New->setudfvalue("u_recordedby",$page->getitemstring("u_recordedby")) ;
                                   
                                    $objRpfaas2New->setudfvalue("u_prevarpno",$objRpfaas2Old->docno);
                                    $objRpfaas2New->setudfvalue("u_prevpin",$objRpfaas2Old->getudfvalue("u_pin"));
                                    $objRpfaas2New->setudfvalue("u_prevowner",$objRpfaas2Old->getudfvalue("u_ownercompanyname"));
                                    $objRpfaas2New->setudfvalue("u_preveffdate",$objRpfaas2Old->getudfvalue("u_effdate"));
                                    $objRpfaas2New->setudfvalue("u_prevtdno",$objRpfaas2Old->getudfvalue("u_tdno"));
                                    $objRpfaas2New->setudfvalue("u_prevarpno2",$objRpfaas2Old->getudfvalue("u_varpno"));
                                    $objRpfaas2New->setudfvalue("u_prevvalue",$objRpfaas2Old->getudfvalue("u_assvalue"));
                                    $objRpfaas2New->setudfvalue("u_prevrecordedby",$objRpfaas2Old->getudfvalue("u_recordedby"));
                                    $objRpfaas2New->setudfvalue("u_prevrecordeddate",$objRpfaas2Old->getudfvalue("u_recordeddate"));
                                    
                                    if ($actionReturn){
                                        $objRpfaas2pNew->prepareadd();
                                        $objRpfaas2pNew->docid = $objRpfaas2New->docid;
                                        $objRpfaas2pNew->lineid = getNextIdByBranch("u_rpfaas2p",$objConnection);
                                        $objRpfaas2pNew->setudfvalue("u_prevarpno",$objRpfaas2Old->docno);
                                        $objRpfaas2pNew->setudfvalue("u_prevpin",$objRpfaas2Old->getudfvalue("u_pin"));
                                        $objRpfaas2pNew->setudfvalue("u_prevowner",$objRpfaas2Old->getudfvalue("u_ownercompanyname"));
                                        $objRpfaas2pNew->setudfvalue("u_preveffdate",$objRpfaas2Old->getudfvalue("u_effdate"));
                                        $objRpfaas2pNew->setudfvalue("u_prevtdno",$objRpfaas2Old->getudfvalue("u_tdno"));
                                        $objRpfaas2pNew->setudfvalue("u_prevarpno2",$objRpfaas2Old->getudfvalue("u_varpno"));
                                        $objRpfaas2pNew->setudfvalue("u_prevvalue",$objRpfaas2Old->getudfvalue("u_assvalue"));
                                        $objRpfaas2pNew->setudfvalue("u_prevrecordedby",$objRpfaas2Old->getudfvalue("u_recordedby"));
                                        $objRpfaas2pNew->setudfvalue("u_prevrecordeddate",$objRpfaas2Old->getudfvalue("u_recordeddate"));
                                        $actionReturn = $objRpfaas2pNew->add();
                                    }
                                    if ($actionReturn){
                                        $objRpfaasNOA->prepareadd();
                                        $objRpfaasNOA->docno = getNextNoByBranch("u_rpnotice","",$objConnection);
                                        $objRpfaasNOA->docid = getNextIdByBranch("u_rpnotice",$objConnection);
                                        $objRpfaasNOA->docseries = -1;
                                        $objRpfaasNOA->docstatus = "O";
                                        $objRpfaasNOA->setudfvalue("u_tin",$objRpfaas2New->getudfvalue("u_ownertin")) ;
                                        $objRpfaasNOA->setudfvalue("u_declaredowner",$objRpfaas2New->getudfvalue("u_ownercompanyname"));
                                        $objRpfaasNOA->setudfvalue("u_year",$u_year);
                                        $objRpfaasNOA->setudfvalue("u_totalmarketvalue",$objRpfaas2New->getudfvalue("u_marketvalue"));
                                        $objRpfaasNOA->setudfvalue("u_totalassvalue",$objRpfaas2New->getudfvalue("u_assvalue"));
                                        $objRpfaasNOA->setudfvalue("u_date",formatDateToDb($page->getitemstring("u_approveddate")));

                                        $objRpfaasNOAItems->prepareadd();
                                        $objRpfaasNOAItems->docid = $objRpfaasNOA->docid;
                                        $objRpfaasNOAItems->lineid = getNextIdByBranch("u_rpnoticeitems",$objConnection);
                                        $objRpfaasNOAItems->setudfvalue("u_selected",1);
                                        $objRpfaasNOAItems->setudfvalue("u_docno",$objRpfaas2New->docno);
                                        $objRpfaasNOAItems->setudfvalue("u_trxcode",$objRpfaas2New->getudfvalue("u_trxcode"));
                                        $objRpfaasNOAItems->setudfvalue("u_tdno",$objRpfaas2New->getudfvalue("u_varpno"));
                                        $objRpfaasNOAItems->setudfvalue("u_pin",$objRpfaas2New->getudfvalue("u_pin"));
                                        $objRpfaasNOAItems->setudfvalue("u_location",$objRpfaas2New->getudfvalue("u_location"));
                                        $objRpfaasNOAItems->setudfvalue("u_class",$objRpfaas2New->getudfvalue("u_class"));
                                        $objRpfaasNOAItems->setudfvalue("u_kind","B");
                                        $objRpfaasNOAItems->setudfvalue("u_marketvalue",$objRpfaas2New->getudfvalue("u_marketvalue"));
                                        $objRpfaasNOAItems->setudfvalue("u_assvalue",$objRpfaas2New->getudfvalue("u_assvalue"));
                                        $actionReturn = $objRpfaasNOAItems->add();
                                        $actionReturn = $objRpfaasNOA->add();
                                    }
                                   
                                    $objRpfaas2New->setudfvalue("u_noticeno",$objRpfaasNOA->docno);
                                    
                                    if (formatDateToDb($objRpfaas2New->getudfvalue("u_effdate"))>$objRpfaas2Old->getudfvalue("u_effdate")) {
                                            $u_expdate = dateadd("d",-1,formatDateToDb($objRpfaas2New->getudfvalue("u_effdate")));
                                            $u_month = intval(date('m',strtotime($u_expdate)));
                                            $u_year = intval(date('Y',strtotime($u_expdate)));
                                            $objRpfaas2Old->setudfvalue("u_expdate",$u_expdate);
                                            $objRpfaas2Old->setudfvalue("u_expqtr",ceil($u_month/3));
                                            $objRpfaas2Old->setudfvalue("u_expyear",$u_year);
                                            $objRpfaas2Old->setudfvalue("u_cancelled",1);
                                            $actionReturn = $objRpfaas2Old->update($objRpfaas2Old->docno,$objRpfaas2Old->rcdversion);
                                    } else return raiseError("New assessment [".$objRpfaas2New->getudfvalue("u_effdate")."] cannot be same or earlier than previous assessment [".$objRpfaas2Old->getudfvalue("u_effdate")."].");
                                    $actionReturn = $objRpfaas2New->add();
                            }
                    }
                    
                    if ($actionReturn) {
                        $objConnection->commit();
                    } else {
                        $objConnection->rollback();
                    }
                } else  if ($page->getitemstring("u_kind") == 3) {
                    
                    $objRpfaas3Old = new documentschema_br(null,$objConnection,"u_rpfaas3");
                    $objRpfaasNOA = new documentschema_br(null,$objConnection,"u_rpnotice");
                    $objRpfaasNOAItems = new documentlinesschema_br(null,$objConnection,"u_rpnoticeitems");
                    $objRpfaas3New = new documentschema_br(null,$objConnection,"u_rpfaas3");
                    $objRpfaas3pNew = new documentlinesschema_br(null,$objConnection,"u_rpfaas3p"); 
                    $objConnection->beginwork();
                    $objRs = new recordset(null,$objConnection);
                    
                    $objRs->queryopen("SELECT docno FROM U_RPFAAS3 WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."' AND U_CANCELLED = 0 AND U_REVISIONYEAR = '".$page->getitemstring("u_revisionyearfrom")."' AND U_OLDBARANGAY = '".$page->getitemstring("u_oldbarangay")."'");
                    while ($objRs->queryfetchrow("NAME")) {
                            if ($objRpfaas3Old->getbykey($objRs->fields["docno"])) {
                                   
                                    $objRpfaas3New->prepareadd();
                                    $objRpfaas3New->docno = getNextNoByBranch("u_rpfaas3","",$objConnection);
                                    $objRpfaas3New->docid = getNextIdByBranch("u_rpfaas3",$objConnection);
                                    $objRpfaas3New->docseries = -1;
                                    $objRpfaas3New->docstatus = "Encoding";
                                    
                                    $objRpfaas3New->setudfvalue("u_prefix",$objRpfaas3Old->getudfvalue("u_prefix")) ;
                                    $objRpfaas3New->setudfvalue("u_suffix",$objRpfaas3Old->getudfvalue("u_suffix")) ;
                                    $objRpfaas3New->setudfvalue("u_pin",$objRpfaas3Old->getudfvalue("u_pin")) ;
                                    $objRpfaas3New->setudfvalue("u_trxcode","GR");
                                    $objRpfaas3New->setudfvalue("u_tdno","");
                                    $objRpfaas3New->setudfvalue("u_varpno","");
                                    $objRpfaas3New->setudfvalue("u_revisionyear",$page->getitemstring("u_revisionyearto"));
                                    $objRpfaas3New->setudfvalue("u_lotno",$objRpfaas3Old->getudfvalue("u_lotno")) ;
                                    $objRpfaas3New->setudfvalue("u_cadlotno",$objRpfaas3Old->getudfvalue("u_cadlotno")) ;
                                    $objRpfaas3New->setudfvalue("u_surveyno",$objRpfaas3Old->getudfvalue("u_surveyno")) ;
                                    $objRpfaas3New->setudfvalue("u_ownercompanyname",$objRpfaas3Old->getudfvalue("u_ownercompanyname")) ;
                                    $objRpfaas3New->setudfvalue("u_ownername",$objRpfaas3Old->getudfvalue("u_ownername")) ;
                                    $objRpfaas3New->setudfvalue("u_owneraddress",$objRpfaas3Old->getudfvalue("u_owneraddress")) ;
                                    $objRpfaas3New->setudfvalue("u_email",$objRpfaas3Old->getudfvalue("u_email")) ;
                                    $objRpfaas3New->setudfvalue("u_ownertelno",$objRpfaas3Old->getudfvalue("u_ownertelno")) ;
                                    $objRpfaas3New->setudfvalue("u_ownertin",$objRpfaas3Old->getudfvalue("u_ownertin")) ;
                                    $objRpfaas3New->setudfvalue("u_ownerkind",$objRpfaas3Old->getudfvalue("u_ownerkind")) ;
                                    $objRpfaas3New->setudfvalue("u_landowner",$objRpfaas3Old->getudfvalue("u_landowner")) ;
                                    $objRpfaas3New->setudfvalue("u_landtdno",$objRpfaas3Old->getudfvalue("u_landtdno")) ;
                                    $objRpfaas3New->setudfvalue("u_adminname",$objRpfaas3Old->getudfvalue("u_adminname")) ;
                                    $objRpfaas3New->setudfvalue("u_adminlastname",$objRpfaas3Old->getudfvalue("u_adminlastname")) ;
                                    $objRpfaas3New->setudfvalue("u_adminfirstname",$objRpfaas3Old->getudfvalue("u_adminfirstname")) ;
                                    $objRpfaas3New->setudfvalue("u_adminmiddlename",$objRpfaas3Old->getudfvalue("u_adminmiddlename")) ;
                                    $objRpfaas3New->setudfvalue("u_adminaddress",$objRpfaas3Old->getudfvalue("u_adminaddress")) ;
                                    $objRpfaas3New->setudfvalue("u_admintelno",$objRpfaas3Old->getudfvalue("u_admintelno")) ;
                                    $objRpfaas3New->setudfvalue("u_admintin",$objRpfaas3Old->getudfvalue("u_admintin")) ;
                                    $objRpfaas3New->setudfvalue("u_street",$objRpfaas3Old->getudfvalue("u_street")) ;
                                    $objRpfaas3New->setudfvalue("u_municipality",$objRpfaas3Old->getudfvalue("u_municipality")) ;
                                    $objRpfaas3New->setudfvalue("u_city",$objRpfaas3Old->getudfvalue("u_city")) ;
                                    $objRpfaas3New->setudfvalue("u_province",$objRpfaas3Old->getudfvalue("u_province")) ;
                                    $objRpfaas3New->setudfvalue("u_oldbarangay",$objRpfaas3Old->getudfvalue("u_oldbarangay")) ;
                                    $objRpfaas3New->setudfvalue("u_barangay",$objRpfaas3Old->getudfvalue("u_barangay")) ;
                                    $objRpfaas3New->setudfvalue("u_location",$objRpfaas3Old->getudfvalue("u_location")) ;
                                    $objRpfaas3New->setudfvalue("u_subdivision",$objRpfaas3Old->getudfvalue("u_subdivision")) ;
                                    $objRpfaas3New->setudfvalue("u_memoranda","") ;
                                    $objRpfaas3New->setudfvalue("u_annotation","") ;
                                    
                                    
                                    $u_month = intval(date('m',strtotime($page->getitemstring("u_effdate"))));
                                    $u_year = intval(date('Y',strtotime($page->getitemstring("u_effdate"))));
                                    $objRpfaas3New->setudfvalue("u_taxable",$objRpfaas3Old->getudfvalue("u_taxable")) ;
                                    $objRpfaas3New->setudfvalue("u_effdate",$page->getitemstring("u_effdate")) ;
                                    $objRpfaas3New->setudfvalue("u_effyear",$u_year) ;
                                    $objRpfaas3New->setudfvalue("u_effqtr",ceil($u_month/3)) ;
                                    
                                    $objRpfaas3New->setudfvalue("u_assvalue",$objRpfaas3Old->getudfvalue("u_assvalue")) ;
                                    $objRpfaas3New->setudfvalue("u_marketvalue",$objRpfaas3Old->getudfvalue("u_marketvalue")) ;
                                    
                                    $objRpfaas3New->setudfvalue("u_approvedby",$page->getitemstring("u_approvedby"));
                                    $objRpfaas3New->setudfvalue("u_approveddate",formatDateToDb($page->getitemstring("u_approveddate")));
                                    $objRpfaas3New->setudfvalue("u_recommendby",$page->getitemstring("u_recommendby"));
                                    $objRpfaas3New->setudfvalue("u_recommenddate",formatDateToDb($page->getitemstring("u_recommenddate")));
                                    $objRpfaas3New->setudfvalue("u_assessedby",$page->getitemstring("u_assessedby"));
                                    $objRpfaas3New->setudfvalue("u_assesseddate",formatDateToDb($page->getitemstring("u_assesseddate")));
                                    $objRpfaas3New->setudfvalue("u_recordeddate",formatDateToDb($page->getitemstring("u_recordeddate"))) ;
                                    $objRpfaas3New->setudfvalue("u_recordedby",$page->getitemstring("u_recordedby")) ;
                                   
                                    $objRpfaas3New->setudfvalue("u_prevarpno",$objRpfaas3Old->docno);
                                    $objRpfaas3New->setudfvalue("u_prevpin",$objRpfaas3Old->getudfvalue("u_pin"));
                                    $objRpfaas3New->setudfvalue("u_prevowner",$objRpfaas3Old->getudfvalue("u_ownercompanyname"));
                                    $objRpfaas3New->setudfvalue("u_preveffdate",$objRpfaas3Old->getudfvalue("u_effdate"));
                                    $objRpfaas3New->setudfvalue("u_prevtdno",$objRpfaas3Old->getudfvalue("u_tdno"));
                                    $objRpfaas3New->setudfvalue("u_prevarpno2",$objRpfaas3Old->getudfvalue("u_varpno"));
                                    $objRpfaas3New->setudfvalue("u_prevvalue",$objRpfaas3Old->getudfvalue("u_assvalue"));
                                    $objRpfaas3New->setudfvalue("u_prevrecordedby",$objRpfaas3Old->getudfvalue("u_recordedby"));
                                    $objRpfaas3New->setudfvalue("u_prevrecordeddate",$objRpfaas3Old->getudfvalue("u_recordeddate"));
                                    
                                    if ($actionReturn) {
                                        $objRpfaas3pNew->prepareadd();
                                        $objRpfaas3pNew->docid = $objRpfaas3New->docid;
                                        $objRpfaas3pNew->lineid = getNextIdByBranch("u_rpfaas2p",$objConnection);
                                        $objRpfaas3pNew->setudfvalue("u_prevarpno",$objRpfaas3Old->docno);
                                        $objRpfaas3pNew->setudfvalue("u_prevpin",$objRpfaas3Old->getudfvalue("u_pin"));
                                        $objRpfaas3pNew->setudfvalue("u_prevowner",$objRpfaas3Old->getudfvalue("u_ownercompanyname"));
                                        $objRpfaas3pNew->setudfvalue("u_preveffdate",$objRpfaas3Old->getudfvalue("u_effdate"));
                                        $objRpfaas3pNew->setudfvalue("u_prevtdno",$objRpfaas3Old->getudfvalue("u_tdno"));
                                        $objRpfaas3pNew->setudfvalue("u_prevarpno2",$objRpfaas3Old->getudfvalue("u_varpno"));
                                        $objRpfaas3pNew->setudfvalue("u_prevvalue",$objRpfaas3Old->getudfvalue("u_assvalue"));
                                        $objRpfaas3pNew->setudfvalue("u_prevrecordedby",$objRpfaas3Old->getudfvalue("u_recordedby"));
                                        $objRpfaas3pNew->setudfvalue("u_prevrecordeddate",$objRpfaas3Old->getudfvalue("u_recordeddate"));
                                        $actionReturn = $objRpfaas3pNew->add();
                                    }
                                    if ($actionReturn) {
                                        $objRpfaasNOA->prepareadd();
                                        $objRpfaasNOA->docno = getNextNoByBranch("u_rpnotice","",$objConnection);
                                        $objRpfaasNOA->docid = getNextIdByBranch("u_rpnotice",$objConnection);
                                        $objRpfaasNOA->docseries = -1;
                                        $objRpfaasNOA->docstatus = "O";
                                        $objRpfaasNOA->setudfvalue("u_tin",$objRpfaas3New->getudfvalue("u_ownertin")) ;
                                        $objRpfaasNOA->setudfvalue("u_declaredowner",$objRpfaas3New->getudfvalue("u_ownercompanyname"));
                                        $objRpfaasNOA->setudfvalue("u_year",$u_year);
                                        $objRpfaasNOA->setudfvalue("u_totalmarketvalue",$objRpfaas3New->getudfvalue("u_marketvalue"));
                                        $objRpfaasNOA->setudfvalue("u_totalassvalue",$objRpfaas3New->getudfvalue("u_assvalue"));
                                        $objRpfaasNOA->setudfvalue("u_date",formatDateToDb($page->getitemstring("u_approveddate")));

                                        $objRpfaasNOAItems->prepareadd();
                                        $objRpfaasNOAItems->docid = $objRpfaasNOA->docid;
                                        $objRpfaasNOAItems->lineid = getNextIdByBranch("u_rpnoticeitems",$objConnection);
                                        $objRpfaasNOAItems->setudfvalue("u_selected",1);
                                        $objRpfaasNOAItems->setudfvalue("u_docno",$objRpfaas3New->docno);
                                        $objRpfaasNOAItems->setudfvalue("u_trxcode",$objRpfaas3New->getudfvalue("u_trxcode"));
                                        $objRpfaasNOAItems->setudfvalue("u_tdno",$objRpfaas3New->getudfvalue("u_varpno"));
                                        $objRpfaasNOAItems->setudfvalue("u_pin",$objRpfaas3New->getudfvalue("u_pin"));
                                        $objRpfaasNOAItems->setudfvalue("u_location",$objRpfaas3New->getudfvalue("u_location"));
                                        $objRpfaasNOAItems->setudfvalue("u_class",$objRpfaas3New->getudfvalue("u_class"));
                                        $objRpfaasNOAItems->setudfvalue("u_kind","M");
                                        $objRpfaasNOAItems->setudfvalue("u_marketvalue",$objRpfaas3New->getudfvalue("u_marketvalue"));
                                        $objRpfaasNOAItems->setudfvalue("u_assvalue",$objRpfaas3New->getudfvalue("u_assvalue"));
                                        $actionReturn = $objRpfaasNOAItems->add();
                                        $actionReturn = $objRpfaasNOA->add();
                                    }
                                   
                                    $objRpfaas3New->setudfvalue("u_noticeno",$objRpfaasNOA->docno);
                                    
                                    if (formatDateToDb($objRpfaas3New->getudfvalue("u_effdate"))>$objRpfaas3Old->getudfvalue("u_effdate")) {
                                            $u_expdate = dateadd("d",-1,formatDateToDb($objRpfaas3New->getudfvalue("u_effdate")));
                                            $u_month = intval(date('m',strtotime($u_expdate)));
                                            $u_year = intval(date('Y',strtotime($u_expdate)));
                                            $objRpfaas3Old->setudfvalue("u_expdate",$u_expdate);
                                            $objRpfaas3Old->setudfvalue("u_expqtr",ceil($u_month/3));
                                            $objRpfaas3Old->setudfvalue("u_expyear",$u_year);
                                            $objRpfaas3Old->setudfvalue("u_cancelled",1);
                                            $actionReturn = $objRpfaas3Old->update($objRpfaas3Old->docno,$objRpfaas3Old->rcdversion);
                                    } else return raiseError("New assessment [".$objRpfaas3New->getudfvalue("u_effdate")."] cannot be same or earlier than previous assessment [".$objRpfaas3Old->getudfvalue("u_effdate")."].");
                                    $actionReturn = $objRpfaas3New->add();
                            }
                    }
                    
                    if ($actionReturn) {
                        $objConnection->commit();
                    } else {
                        $objConnection->rollback();
                    }
                }
                    
            }
       
        return true;
}

function onBeforeDefaultGPSRPTAS() { 
	return true;
}

function onAfterDefaultGPSRPTAS() { 
	global $objConnection;
	global $page;
      
        $page->setitem("u_approveddate",currentdate());
	$page->setitem("u_assesseddate",currentdate());
	$page->setitem("u_recommenddate",currentdate());
	$page->setitem("u_recordeddate",currentdate());
        
        $objRs = new recordset(null,$objConnection);
        $objRs->queryopen("SELECT USERNAME FROM USERS WHERE USERID = '".$_SESSION["userid"]."' ");
            while ($objRs->queryfetchrow("NAME")) {
                $page->setitem("u_recordedby",$objRs->fields["USERNAME"]);
            }
     
        
//	$page->setitem("u_rate",formatNumericPercent(1));
//	$page->setitem("u_idlelandrate",formatNumericPercent(5));
//	$page->setitem("u_sefrate",formatNumericPercent(1));
//	$page->setitem("u_discperc",formatNumericPercent(10));
//	$page->setitem("u_1stqtrdiscperc",formatNumericPercent(17.5));
//	$page->setitem("u_advancediscperc",formatNumericPercent(20));
//	$page->setitem("u_year",date('Y'));
//	$page->setitem("u_assdate",currentdate());
//	$page->setitem("u_paymode","A");
//	$page->setitem("docstatus","D");
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
$page->businessobject->items->seteditable("u_status",false);
$page->businessobject->items->seteditable("u_recordedby",false);
$page->businessobject->items->seteditable("u_recordeddate",false);
$deleteoption = false;
$addoptions = false;

$page->businessobject->items->setcfl("u_assesseddate","Calendar");
$page->businessobject->items->setcfl("u_approveddate","Calendar");
$page->businessobject->items->setcfl("u_recommenddate","Calendar");
$page->businessobject->items->setcfl("u_recordeddate","Calendar");
$page->businessobject->items->setcfl("u_effdate","Calendar");

$page->toolbar->setaction("add",true);
$page->toolbar->setaction("new",true);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
$objMaster->reportaction = "QS";
?> 

