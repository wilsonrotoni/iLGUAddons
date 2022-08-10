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
                  
            if ($action=="subdivide") {
                $objRpfaas1Old = new documentschema_br(null,$objConnection,"u_rpfaas1");
                $objRpfaas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
                $objRpfaas1a = new documentschema_br(null,$objConnection,"u_rpfaas1a");
                $objRpfaas1b = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
                $objRpfaas1c = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");
                
                $objConnection->beginwork();
                $objRs = new recordset(null,$objConnection);
                $objRs->queryopen("select b.u_arpno,B.u_pin,b.u_tdno,b.u_ownername,b.u_ownertin as Tin,b.u_sqm from u_rpsubdivision a inner join u_rpsubdivisionitems b on a.docid = b.docid where a.docno = '".$page->getitemstring("docno")."' ");
                $count = $objRs->recordcount();
             
                    while ($objRs->queryfetchrow("NAME")) {
                       
                        $objRpfaas1->prepareadd();
                        $objRpfaas1->docno = getNextNoByBranch("u_rpfaas1","",$objConnection);
                        $objRpfaas1->docid = getNextIdByBranch("u_rpfaas1",$objConnection);
                        $objRpfaas1->docseries = -1;
                        $objRpfaas1->docstatus = "Encoding";
                        $objRpfaas1->setudfvalue("u_trxcode","SG");
                        $objRpfaas1->setudfvalue("u_varpno",$objRs->fields["u_arpno"]) ;
                        $objRpfaas1->setudfvalue("u_pin",$objRs->fields["u_pin"]);
                        $objRpfaas1->setudfvalue("u_tdno",$objRs->fields["u_tdno"]);
                        $objRpfaas1->setudfvalue("u_ownertype","C");
                        $objRpfaas1->setudfvalue("u_ownercompanyname",$objRs->fields["u_ownername"]);
                        $objRpfaas1->setudfvalue("u_ownername",$objRs->fields["u_ownername"]);
                        $objRpfaas1->setudfvalue("u_ownertin",$objRs->fields["Tin"]);
                        
                        if ($objRpfaas1Old->getbykey($page->getitemstring("u_arpno"))) {
                            $objRpfaas1->setudfvalue("u_prevarpno",$objRpfaas1Old->docno);
                            $objRpfaas1->setudfvalue("u_prevpin",$objRpfaas1Old->getudfvalue("u_pin"));
                            $objRpfaas1->setudfvalue("u_prevtdno",$objRpfaas1Old->getudfvalue("u_tdno"));
                            $objRpfaas1->setudfvalue("u_prevowner",$objRpfaas1Old->getudfvalue("u_ownercompanyname"));
                            $objRpfaas1->setudfvalue("u_prevvalue",formatNumericAmount($objRpfaas1Old->getudfvalue("u_assvalue")));
                            $objRpfaas1->setudfvalue("u_prevarpno2",$objRpfaas1Old->getudfvalue("u_varpno"));
                            $objRpfaas1->setudfvalue("u_preveffdate",$objRpfaas1Old->getudfvalue("u_effdate"));
                            $objRpfaas1->setudfvalue("u_prevrecordedby",$objRpfaas1Old->getudfvalue("u_recordedby"));
                            $objRpfaas1->setudfvalue("u_prevrecordeddate",$objRpfaas1Old->getudfvalue("u_recordeddate"));
                        }
                        
                        $objRsLguSetup = new recordset(null,$objConnection);
                        $objRsLguSetup->queryopen("select u_province,u_municipality,u_city from u_lgusetup");
                            while ($objRsLguSetup->queryfetchrow("NAME")) {
                                $objRpfaas1->setudfvalue("u_city",$objRsLguSetup->fields["u_city"]);
                                $objRpfaas1->setudfvalue("u_municipality",$objRsLguSetup->fields["u_municipality"]);
                                $objRpfaas1->setudfvalue("u_province",$objRsLguSetup->fields["u_province"]);
                            }
                            
                        $objRsRpfaas1a = new recordset(null,$objConnection);
                        $objRsRpfaas1a->queryopen("select b.U_GRYEAR,b.DOCID,b.U_CLASS,b.U_BASEVALUE,B.U_MARKETVALUE,B.U_ASSLVL,b.U_ADJVALUE,b.U_ACTUALUSE,b.U_ASSVALUE,b.U_SQM from u_rpfaas1 a left join u_rpfaas1a b on a.docno = b.u_arpno and a.company = b.company and a.branch = b.branch where a.docno = '".$page->getitemstring("u_arpno")."'");
                        while ($objRsRpfaas1a->queryfetchrow("NAME")) {
                            
                                $objRpfaas1a->prepareadd();
                                $objRpfaas1a->docno = getNextNoByBranch("u_rpfaas1a","",$objConnection);
                                $objRpfaas1a->docid = getNextIdByBranch("u_rpfaas1a",$objConnection);
                                $objRpfaas1a->setudfvalue("u_arpno",$objRpfaas1->docno) ;
                                $objRpfaas1a->setudfvalue("u_class",$objRsRpfaas1a->fields["U_CLASS"]);
                                $objRpfaas1a->setudfvalue("u_gryear",$objRsRpfaas1a->fields["U_GRYEAR"]);
                               
                                    $objRs3 = new recordset(null,$objConnection);
                                    $objRs3->queryopen("SELECT B.U_UNIT,B.U_DESCRIPTION,B.U_SUBCLASS,B.U_SQM,B.U_UNITVALUE,B.U_BASEVALUE,B.U_CLASS FROM U_RPFAAS1A A INNER JOIN U_RPFAAS1B B ON A.DOCID = B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY WHERE B.DOCID = '".$objRsRpfaas1a->fields["DOCID"]."' ");
                                    while ($objRs3->queryfetchrow("NAME")) {  
                                        $objRpfaas1b->prepareadd();
                                        $objRpfaas1b->docid = $objRpfaas1a->docid;
                                        $objRpfaas1b->lineid = getNextIdByBranch("u_rpfaas1b",$objConnection);
                                        $objRpfaas1b->setudfvalue("u_unit",$objRs3->fields["U_UNIT"]) ;
                                        $objRpfaas1b->setudfvalue("u_description",$objRs3->fields["U_DESCRIPTION"]) ;
                                        $objRpfaas1b->setudfvalue("u_subclass",$objRs3->fields["U_SUBCLASS"]) ;
                                        $objRpfaas1b->setudfvalue("u_sqm",$objRs3->fields["U_SQM"]/$count);
                                        $objRpfaas1b->setudfvalue("u_unitvalue",$objRs3->fields["U_UNITVALUE"]);
                                        $objRpfaas1b->setudfvalue("u_basevalue",$objRs3->fields["U_BASEVALUE"]/$count);
                                        $objRpfaas1b->setudfvalue("u_class",$objRs3->fields["U_CLASS"]);
                                        $objRpfaas1b->privatedata["header"] = $objRpfaas1a;
                                        $actionReturn = $objRpfaas1b->add();
                                    if (!$actionReturn) break;
                                    }
                                    
                                    $objRs4 = new recordset(null,$objConnection);
                                    $objRs4->queryopen("SELECT B.U_ADJFACTOR,B.U_ADJPERC,B.U_ADJVALUE,B.U_ADJTYPE FROM U_RPFAAS1A A INNER JOIN U_RPFAAS1C B ON A.DOCID = B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY WHERE B.DOCID = '".$objRsRpfaas1a->fields["DOCID"]."' ");
                                    while ($objRs4->queryfetchrow("NAME")) {  
                                        $objRpfaas1c->prepareadd();
                                        $objRpfaas1c->docid = $objRpfaas1a->docid;
                                        $objRpfaas1c->lineid = getNextIdByBranch("u_rpfaas1c",$objConnection);
                                        $objRpfaas1c->setudfvalue("u_adjfactor",$objRs4->fields["U_ADJFACTOR"]) ;
                                        $objRpfaas1c->setudfvalue("u_adjperc",$objRs4->fields["U_ADJPERC"]);
                                        $objRpfaas1c->setudfvalue("u_adjvalue",$objRs4->fields["U_ADJVALUE"]/$count);
                                        $objRpfaas1c->setudfvalue("u_adjtype",$objRs4->fields["U_ADJTYPE"]);
                                        $objRpfaas1c->privatedata["header"] = $objRpfaas1a;
                                        $actionReturn = $objRpfaas1c->add();
                                    if (!$actionReturn) break;
                                    }
                                    
                                $objRpfaas1a->setudfvalue("u_basevalue",$objRsRpfaas1a->fields["U_BASEVALUE"]/$count);
                                $objRpfaas1a->setudfvalue("u_adjvalue",$objRsRpfaas1a->fields["U_ADJVALUE"]/$count);
                                $objRpfaas1a->setudfvalue("u_marketvalue",$objRsRpfaas1a->fields["U_MARKETVALUE"]/$count);
                                $objRpfaas1a->setudfvalue("u_actualuse",$objRsRpfaas1a->fields["U_ACTUALUSE"]);
                                $objRpfaas1a->setudfvalue("u_asslvl",$objRsRpfaas1a->fields["U_ASSLVL"]) ;
                                $objRpfaas1a->setudfvalue("u_assvalue",$objRsRpfaas1a->fields["U_ASSVALUE"]/$count) ;
                                $objRpfaas1a->setudfvalue("u_sqm",$objRsRpfaas1a->fields["U_SQM"]/$count) ;
                                $actionReturn = $objRpfaas1a->add();
                            if (!$actionReturn) break;
                            }
                        $actionReturn = $objRpfaas1->add();      
                        if (!$actionReturn) break;
                    }
                    
            if($actionReturn) $objRs->executesql("UPDATE u_rpsubdivision set docstatus = 'C' where docno = '".$page->getitemstring("docno")."' and company = 'lgu' and branch = 'main' ",true); 
           
            }
            
        if ($actionReturn) {
              $objConnection->commit();
        }else{
              $objConnection->rollback();
        }
       
        return true;
}

function onBeforeDefaultGPSRPTAS() { 
	return true;
}

function onAfterDefaultGPSRPTAS() { 
	global $objConnection;
	global $page;
      
           
        
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



$page->objectdoctype = "u_rpsubdivision";

//$page->businessobject->items->setcfl("u_tdno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_pin","OpenCFLfs()");


$page->businessobject->items->seteditable("u_ownername",false);
$page->businessobject->items->seteditable("u_sqm",false);
$page->businessobject->items->seteditable("u_assvalue",false);



$objGrids[0]->dataentry = false;
$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->columnwidth("u_pin",30);
$objGrids[0]->columnwidth("u_arpno",15);
$objGrids[0]->columnwidth("u_tdno",20);
$objGrids[0]->columnwidth("u_ownername",30);
$objGrids[0]->columnwidth("u_ownertin",20);
$objGrids[0]->columnwidth("u_sqm",15);
$objGrids[0]->columnwidth("u_assvalue",20);
//$objGrids[0]->columnwidth("u_prevassvalue",40);

$objGrids[0]->columnvisibility("u_prevarpno",false);
$objGrids[0]->columnvisibility("u_trxcode",false);
$objGrids[0]->columnvisibility("u_prevpin",false);
$objGrids[0]->columnvisibility("u_prevtd",false);
$objGrids[0]->columnvisibility("u_prevassvalue",false);

$objGrids[0]->columntitle("u_ownername","Declared Owner");
$objGrids[0]->columntitle("u_ownertin","Tin");
$objGrids[0]->columntitle("u_pin","PIN");
$objGrids[0]->columntitle("u_arpno","Arp No");
//$objGrids[0]->columntitle("u_yrfr","From");
//$objGrids[0]->columntitle("u_yrto","To");
$objGrids[0]->columnlnkbtn("u_pin","openupdpays()");
//$objGrids[0]->columntitle("u_billdate","Bill Date");
//$objGrids[0]->columntitle("u_paymode","Payment Mode");


$addoptions = false;
$deleteoption = false;
$objMaster->reportaction = "QS";
?> 

