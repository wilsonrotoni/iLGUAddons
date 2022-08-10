<?php
 

$page->businessobject->events->add->customAction("onCustomActionGPSRPTAS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSRPTAS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSRPTAS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSRPTAS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSRPTAS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSRPTAS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSRPTAS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSRPTAS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSRPTAS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSRPTAS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSRPTAS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSRPTAS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSRPTAS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSRPTAS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSRPTAS");

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSRPTAS");

$appdata= array();

function onDrawGridColumnLabelGPSRPTAS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T103":
			//if ($column=="edit") {
			//	$label = "[Edit]";
			//}
			break;
	}
}


function onCustomActionGPSRPTAS($action) {
        global $objConnection;
	global $page;
	global $objGrids;
        $actionReturn = true;
        	if ($action=="consolidate") {
                    $objRpfaas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
                    $objRpfaas1a = new documentschema_br(null,$objConnection,"u_rpfaas1a");
                    $objRpfaas1aOld = new documentschema_br(null,$objConnection,"u_rpfaas1a");
                    
                    $objRpfaas1b = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
                    $objRpfaas1bOld = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
                    
                    $objRpfaas1c = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");
                    $objRpBldgAffected = new documentlinesschema_br(null,$objConnection,"u_rpbldgmachaffected");
                    $objRpfaas1->getbysql("DOCNO = '".$page->getitemstring("docno")."'");
                    $objConnection->beginwork();
                    $actionReturn = $objRpfaas1->executesql("DELETE FROM u_rpbldgmachaffected WHERE COMPANY='$objRpfaas1->company' AND BRANCH='$objRpfaas1->branch' AND DOCID='$objRpfaas1->docid'",false);
                        while(true){
                            if ($objRpfaas1a->getbysql("U_ARPNO='".$page->getitemstring("docno")."'")) {
                                    $actionReturn = $objRpfaas1a->executesql("DELETE FROM U_RPFAAS1B WHERE COMPANY='$objRpfaas1a->company' AND BRANCH='$objRpfaas1a->branch' AND DOCID='$objRpfaas1a->docid'",false);
                                    $actionReturn = $objRpfaas1a->executesql("DELETE FROM U_RPFAAS1C WHERE COMPANY='$objRpfaas1a->company' AND BRANCH='$objRpfaas1a->branch' AND DOCID='$objRpfaas1a->docid'",false);
                                    if ($actionReturn) $actionReturn = $objRpfaas1a->delete();
                            } else {
                                    break;
                            }
                               if(!$actionReturn)  break;
                        }
                   
                    $objRs = new recordset(null,$objConnection);
                    if($actionReturn)$objRs->queryopen("select b.U_PREVPIN,B.U_PREVARPNO from u_rpfaas1 a inner join u_rpfaas1p b on a.docid = b.docid where a.docno = '".$page->getitemstring("docno")."' ");
                        while ($objRs->queryfetchrow("NAME")) {
                                    $objRs2 = new recordset(null,$objConnection);
                                    $objRs3 = new recordset(null,$objConnection);
                                    $objRs2->queryopen("SELECT B.DOCID,B.U_CLASS,B.U_BASEVALUE,B.U_ADJVALUE,B.U_MARKETVALUE,B.U_ACTUALUSE,B.U_ASSLVL,B.U_ASSVALUE,B.U_SQM FROM U_RPFAAS1 A INNER JOIN U_RPFAAS1A B ON A.DOCNO = B.U_ARPNO AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY WHERE A.DOCNO = '".$objRs->fields["U_PREVARPNO"]."' ");
                                         while ($objRs2->queryfetchrow("NAME")) {
                                                    if($objRpfaas1aOld->getbysql("U_ARPNO = '".$page->getitemstring("docno")."' AND U_CLASS = '".$objRs2->fields["U_CLASS"]."'")){
                                                        $objRpfaas1aOld->setudfvalue("u_basevalue",$objRpfaas1aOld->getudfvalue("u_basevalue") + $objRs2->fields["U_BASEVALUE"]);
                                                        $objRpfaas1aOld->setudfvalue("u_adjvalue",$objRpfaas1aOld->getudfvalue("u_adjvalue") + $objRs2->fields["U_ADJVALUE"]);
                                                        $objRpfaas1aOld->setudfvalue("u_marketvalue",$objRpfaas1aOld->getudfvalue("u_marketvalue") + $objRs2->fields["U_MARKETVALUE"]);
                                                        $objRpfaas1aOld->setudfvalue("u_assvalue",$objRpfaas1aOld->getudfvalue("u_assvalue") + $objRs2->fields["U_ASSVALUE"]);
                                                        $objRpfaas1aOld->setudfvalue("u_sqm",$objRpfaas1aOld->getudfvalue("u_sqm") + $objRs2->fields["U_SQM"]);
                                                        $objRs3->queryopen("SELECT B.U_SUBCLASS,B.U_SQM,B.U_UNITVALUE,B.U_BASEVALUE,B.U_CLASS FROM U_RPFAAS1A A INNER JOIN U_RPFAAS1B B ON A.DOCID = B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY WHERE B.DOCID = '".$objRs2->fields["DOCID"]."' ");
                                                            while ($objRs3->queryfetchrow("NAME")) { 
                                                                if($objRpfaas1bOld->getbysql("DOCID = '".$objRpfaas1aOld->docid ."' AND U_SUBCLASS = '".$objRs3->fields["U_SUBCLASS"]."' AND U_CLASS = '".$objRs3->fields["U_CLASS"]."' ")){
                                                                     $objRpfaas1bOld->setudfvalue("u_basevalue",$objRpfaas1bOld->getudfvalue("u_basevalue") + $objRs3->fields["U_BASEVALUE"]);
                                                                     $objRpfaas1bOld->setudfvalue("u_sqm",$objRpfaas1bOld->getudfvalue("u_sqm") + $objRs3->fields["U_SQM"]);
                                                                     $actionReturn = $objRpfaas1bOld->update($objRpfaas1bOld->docid,$objRpfaas1bOld->lineid,$objRpfaas1bOld->rcdversion);
                                                                }else{
                                                                    $objRpfaas1b->prepareadd();
                                                                    $objRpfaas1b->docid = $objRpfaas1aOld->docid;
                                                                    $objRpfaas1b->lineid = getNextIdByBranch("u_rpfaas1b",$objConnection);
                                                                    $objRpfaas1b->setudfvalue("u_subclass",$objRs3->fields["U_SUBCLASS"]) ;
                                                                    $objRpfaas1b->setudfvalue("u_sqm",$objRs3->fields["U_SQM"]);
                                                                    $objRpfaas1b->setudfvalue("u_unitvalue",$objRs3->fields["U_UNITVALUE"]);
                                                                    $objRpfaas1b->setudfvalue("u_basevalue",$objRs3->fields["U_BASEVALUE"]);
                                                                    $objRpfaas1b->setudfvalue("u_class",$objRs3->fields["U_CLASS"]);
                                                                    $objRpfaas1b->privatedata["header"] = $objRpfaas1aOld;
                                                                    $actionReturn = $objRpfaas1b->add();
                                                                }
                                                            if (!$actionReturn) break;  
                                                            }
                                                       
                                                        $actionReturn = $objRpfaas1aOld->update($objRpfaas1aOld->docno,$objRpfaas1aOld->rcdversion);
                                                    }else{
                                                        $objRpfaas1a->prepareadd();
                                                        $objRpfaas1a->docno = getNextNoByBranch("u_rpfaas1a","",$objConnection);
                                                        $objRpfaas1a->docid = getNextIdByBranch("u_rpfaas1a",$objConnection);
                                                        $objRpfaas1a->setudfvalue("u_arpno",$page->getitemstring("docno")) ;
                                                        $objRpfaas1a->setudfvalue("u_class",$objRs2->fields["U_CLASS"]);
                                                        $objRpfaas1a->setudfvalue("u_basevalue",$objRs2->fields["U_BASEVALUE"]);
                                                        $objRpfaas1a->setudfvalue("u_adjvalue",$objRs2->fields["U_ADJVALUE"]);
                                                        $objRpfaas1a->setudfvalue("u_marketvalue",$objRs2->fields["U_MARKETVALUE"]);
                                                        $objRpfaas1a->setudfvalue("u_actualuse",$objRs2->fields["U_ACTUALUSE"]);
                                                        $objRpfaas1a->setudfvalue("u_asslvl",$objRs2->fields["U_ASSLVL"]) ;
                                                        $objRpfaas1a->setudfvalue("u_assvalue",$objRs2->fields["U_ASSVALUE"]) ;
                                                        $objRpfaas1a->setudfvalue("u_sqm",$objRs2->fields["U_SQM"]) ;
                                                        $objRs3->queryopen("SELECT B.U_SUBCLASS,B.U_SQM,B.U_UNITVALUE,B.U_BASEVALUE,B.U_CLASS FROM U_RPFAAS1A A INNER JOIN U_RPFAAS1B B ON A.DOCID = B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY WHERE B.DOCID = '".$objRs2->fields["DOCID"]."' ");
                                                        while ($objRs3->queryfetchrow("NAME")) {  
                                                                $objRpfaas1b->prepareadd();
                                                                $objRpfaas1b->docid = $objRpfaas1a->docid;
                                                                $objRpfaas1b->lineid = getNextIdByBranch("u_rpfaas1b",$objConnection);
                                                                $objRpfaas1b->setudfvalue("u_subclass",$objRs3->fields["U_SUBCLASS"]) ;
                                                                $objRpfaas1b->setudfvalue("u_sqm",$objRs3->fields["U_SQM"]);
                                                                $objRpfaas1b->setudfvalue("u_unitvalue",$objRs3->fields["U_UNITVALUE"]);
                                                                $objRpfaas1b->setudfvalue("u_basevalue",$objRs3->fields["U_BASEVALUE"]);
                                                                $objRpfaas1b->setudfvalue("u_class",$objRs3->fields["U_CLASS"]);
                                                                $objRpfaas1b->privatedata["header"] = $objRpfaas1a;
                                                                $actionReturn = $objRpfaas1b->add();
                                                            if (!$actionReturn) break;
                                                            }
                                                        $actionReturn = $objRpfaas1a->add();
                                                    }
                                                    
//                                                      var_dump($actionReturn);
                                            if (!$actionReturn) break;
                                         }
                                    
                                    
                                $objRs1 = new recordset(null,$objConnection);
                                $objRs1->queryopen("select DOCNO,U_VARPNO,U_TDNO,U_OWNERNAME,U_PIN from u_rpfaas2 where u_pin like '%".$objRs->fields["U_PREVPIN"]."%' union all select DOCNO,U_VARPNO,U_TDNO,U_OWNERNAME,U_PIN from u_rpfaas3 where u_pin like '%".$objRs->fields["U_PREVPIN"]."%' ");

                                    while ($objRs1->queryfetchrow("NAME")) {   
                                        $objRpBldgAffected->prepareadd();
                                        $objRpBldgAffected->docid = $objRpfaas1->docid;
                                        $objRpBldgAffected->lineid = getNextIdByBranch("u_rpbldgmachaffected",$objConnection);
                                        $objRpBldgAffected->setudfvalue("u_arpno",$objRs1->fields["DOCNO"]) ;
                                        $objRpBldgAffected->setudfvalue("u_varpno",$objRs1->fields["U_VARPNO"]);
                                        $objRpBldgAffected->setudfvalue("u_tdno",$objRs1->fields["U_TDNO"]);
                                        $objRpBldgAffected->setudfvalue("u_ownername",$objRs1->fields["U_OWNERNAME"]);
                                        $objRpBldgAffected->setudfvalue("u_pin",$objRs1->fields["U_PIN"]) ;
                                        $objRpBldgAffected->privatedata["header"] = $objRpfaas1;
                                        $actionReturn = $objRpBldgAffected->add();
        //                                var_dump($actionReturn);
                                        if (!$actionReturn) break;

                                  }
                     if (!$actionReturn) break;
                     }
                }
               if ($actionReturn) {
                   $objConnection->commit(); modeEdit();
                }else{
                   $objConnection->rollback();
               }
                   
	return true;
}

function onBeforeDefaultGPSRPTAS() { 
	global $page;
	global $appdata;
	$page->privatedata["getprevarpno"] = $page->getitemstring("getprevarpno");
//        echo $page->getitemstring("getprevarpno");
	return true;
}

function onAfterDefaultGPSRPTAS() { 
	global $objConnection;
	global $page;
	$page->setitem("docstatus","Encoding");
	$page->setitem("u_approveddate",currentdate());
	$page->setitem("u_assesseddate",currentdate());
	$page->setitem("u_recommenddate",currentdate());
	$page->setitem("u_recordeddate",currentdate());
	$page->setitem("u_recordedby",$_SESSION["username"]);

        $objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select U_MUNICIPALITY, U_CITY, U_PROVINCE from U_LGUSETUP");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_municipality",$objRs->fields["U_MUNICIPALITY"]);
		$page->setitem("u_city",$objRs->fields["U_CITY"]);
		$page->setitem("u_province",$objRs->fields["U_PROVINCE"]);
	}
        
        $objRs->queryopen("select code,u_default from U_RPASSESSORS");
	while ($objRs->queryfetchrow("NAME")) {
                if($objRs->fields["u_default"]=="2")$page->setitem("u_approvedby",$objRs->fields["code"]);
                else if($objRs->fields["u_default"]=="1")$page->setitem("u_recommendby",$objRs->fields["code"]);
	}
        
        $objRs_uGRYear = new recordset(null,$objConnection);
	$objRs_uGRYear->queryopen("select A.CODE from U_GRYEARS A WHERE A.U_ISACTIVE = 1");
	if ($objRs_uGRYear->queryfetchrow("NAME")) {
		$page->setitem("u_revisionyear",$objRs_uGRYear->fields["CODE"]);
	}
	
	
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
	global $page;
	global $objConnection;
	global $objGridA;
	global $objGridB;
	global $objGridC;
	global $objGridD;
	
	$objGridA->clear();
	$objGridB->clear();
	$objGridC->clear();
	$objGridD->clear();
	
	$objRs = new recordset(null,$objConnection);
//	$objRs->setdebug();
	$objRs->queryopen("SELECT A.U_TAXABLE,C.NAME AS U_CLASS, UPPER(B.U_CLASS) AS U_CLASSLEVEL, UPPER(B.U_SUBCLASS) AS U_SUBCLASS,UPPER(E.U_STRIP) AS U_STRIP, IFNULL(B.U_SQM,0) + IFNULL(E.U_SQM,0) AS U_SQM, IFNULL(B.U_UNITVALUE,0) + IFNULL(E.U_ADJUNITVALUE,0) AS U_UNITVALUE, IFNULL(B.U_BASEVALUE,0)  + IFNULL(E.U_BASEVALUE,0) AS U_BASEVALUE 
                                    FROM U_RPFAAS1A A
                                    LEFT JOIN U_RPFAAS1B B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID
                                    LEFT JOIN U_rPFAAS1E E ON E.COMPANY=A.COMPANY AND E.BRANCH=A.BRANCH AND E.DOCID=A.DOCID
                                    INNER JOIN U_RPLANDS C ON C.CODE=A.U_CLASS WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
//	var_dump($objRs->sqls);
	$sqm=0;
	$basevalue=0;
	while ($objRs->queryfetchrow("NAME")) {
		$objGridA->addrow();
		$objGridA->setitem(null,"u_taxable",$objRs->fields["U_TAXABLE"]);
		$objGridA->setitem(null,"u_class",$objRs->fields["U_CLASS"]);
		$objGridA->setitem(null,"u_subclass",$objRs->fields["U_SUBCLASS"]."  ".$objRs->fields["U_CLASSLEVEL"]. "  ". $objRs->fields["U_STRIP"]);
		$objGridA->setitem(null,"u_sqm",formatNumericMeasure($objRs->fields["U_SQM"]));
		$objGridA->setitem(null,"u_unitvalue",formatNumericPrice($objRs->fields["U_UNITVALUE"]));
		$objGridA->setitem(null,"u_basevalue",formatNumericAmount($objRs->fields["U_BASEVALUE"]));
		$sqm+=$objRs->fields["U_SQM"];
		$basevalue+=$objRs->fields["U_BASEVALUE"];
	}
        $objGridA->setdefaultvalue("u_sqm",formatNumericMeasure($sqm));
	$objGridA->setdefaultvalue("u_basevalue",formatNumericAmount($basevalue));
        
        $totalnumber=0;
	$basevalue2=0;
        $objRs->queryopen("SELECT A.U_TAXABLE,B.U_PLANTTYPE, B.U_CLASS AS U_CLASSLEVEL, B.U_TOTALCOUNT, B.U_UNITVALUE, B.U_MARKETVALUE FROM U_RPFAAS1A A INNER JOIN U_RPFAAS1D B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
        while ($objRs->queryfetchrow("NAME")) {
		$objGridD->addrow();
		$objGridD->setitem(null,"u_kind",$objRs->fields["U_PLANTTYPE"]." - ".$objRs->fields["U_CLASSLEVEL"] );
		$objGridD->setitem(null,"u_totalnumber",formatNumericMeasure($objRs->fields["U_TOTALCOUNT"]));
		$objGridD->setitem(null,"u_unitvalue",formatNumericPrice($objRs->fields["U_UNITVALUE"]));
		$objGridD->setitem(null,"u_basevalue",formatNumericAmount($objRs->fields["U_MARKETVALUE"]));
		$totalnumber+=$objRs->fields["U_TOTALCOUNT"];
		$basevalue2+=$objRs->fields["U_MARKETVALUE"];
	}
	$objGridD->setdefaultvalue("u_totalnumber",formatNumericMeasure($totalnumber));
	$objGridD->setdefaultvalue("u_basevalue",formatNumericAmount($basevalue2));
	
	$objRs->queryopen("SELECT A.U_BASEVALUE, B.U_ADJTYPE, B.U_ADJPERC, B.U_ADJVALUE FROM U_RPFAAS1A A INNER JOIN U_RPFAAS1C B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."'");
	$adjvalue=0;
//	$adjmarketvalue=0;
	while ($objRs->queryfetchrow("NAME")) {
		$objGridB->addrow();
		$objGridB->setitem(null,"u_basevalue",formatNumericAmount($objRs->fields["U_BASEVALUE"]));
		$objGridB->setitem(null,"u_adjfactor",$objRs->fields["U_ADJTYPE"]);
		$objGridB->setitem(null,"u_adjperc",formatNumericPercent($objRs->fields["U_ADJPERC"]));
		$objGridB->setitem(null,"u_adjvalue",formatNumericAmount($objRs->fields["U_ADJVALUE"]));
		$objGridB->setitem(null,"u_marketvalue",formatNumericAmount($objRs->fields["U_BASEVALUE"]+$objRs->fields["U_ADJVALUE"]));
		$adjvalue+=$objRs->fields["U_ADJVALUE"];
//		$adjmarketvalue+=formatNumericAmount($objRs->fields["U_BASEVALUE"]+$objRs->fields["U_ADJVALUE"]);
	}
	$objGridB->setdefaultvalue("u_adjvalue",formatNumericAmount($adjvalue));
//	$objGridB->setdefaultvalue("u_marketvalue",formatNumericAmount($adjmarketvalue));
	
	$objRs->queryopen("SELECT A.U_TAXABLE,A.DOCNO, C.NAME AS U_ACTUALUSE, A.U_MARKETVALUE, A.U_ASSLVL, A.U_ASSVALUE FROM U_RPFAAS1A A LEFT JOIN U_RPACTUSES C ON C.CODE=A.U_ACTUALUSE WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.U_ARPNO='".$page->getitemstring("docno")."' ");
	$marketvalue=0;
        $value = 0;
	$assvalue=0;
	while ($objRs->queryfetchrow("NAME")) {
		$objGridC->addrow();
		$objGridC->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridC->setitem(null,"u_actualuse",$objRs->fields["U_ACTUALUSE"]);
		$objGridC->setitem(null,"u_marketvalue",formatNumericAmount($objRs->fields["U_MARKETVALUE"]));
		$objGridC->setitem(null,"u_asslvl",formatNumericPercent($objRs->fields["U_ASSLVL"]));
                if($objRs->fields["U_TAXABLE"]==1){
                    $value = $objRs->fields["U_ASSVALUE"];
                }else{
                    $value = 0;
                }
		$objGridC->setitem(null,"u_assvalue",formatNumericAmount($objRs->fields["U_ASSVALUE"]));
		$marketvalue+=$objRs->fields["U_MARKETVALUE"];
		$assvalue+=$value;
	}
	$objGridC->setdefaultvalue("u_marketvalue",formatNumericAmount($marketvalue));
	$objGridC->setdefaultvalue("u_assvalue",formatNumericAmount($assvalue));
	
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

$schema["lastupdatedby"] = createSchemaUpper("lastupdatedby");
$schema["tdseries"] = createSchemaUpper("tdseries");

$page->businessobject->items->seteditable("u_municipality",false);
$page->businessobject->items->seteditable("u_city",false);
$page->businessobject->items->seteditable("u_province",false);

$page->businessobject->items->seteditable("u_isauction",false);
$page->businessobject->items->seteditable("u_cancelled",false);
$page->businessobject->items->seteditable("u_expqtr",false);
$page->businessobject->items->seteditable("u_expyear",false);
$page->businessobject->items->seteditable("u_bilyear",false);
$page->businessobject->items->seteditable("u_lastpaymode",false);
$page->businessobject->items->seteditable("u_recordedby",false);
$page->businessobject->items->seteditable("u_recordeddate",false);

$page->businessobject->items->setvisible("u_pinchanged",false);

$page->businessobject->items->setcfl("u_ownertin","OpenCFLfs()");
$page->businessobject->items->setcfl("u_assesseddate","Calendar");
$page->businessobject->items->setcfl("u_approveddate","Calendar");
$page->businessobject->items->setcfl("u_releaseddate","Calendar");
$page->businessobject->items->setcfl("u_recommenddate","Calendar");
$page->businessobject->items->setcfl("u_recordeddate","Calendar");
$page->businessobject->items->setcfl("u_prevrecordeddate","Calendar");
$page->businessobject->items->setcfl("u_tctdate","Calendar");

$objGrids[0]->columncfl("u_prevarpno","OpenCFLfs()");
$objGrids[0]->columncfl("u_prevtdno","OpenCFLfs()");

$objGrids[0]->columnwidth("u_prevvalue",15);
$objGrids[0]->columnwidth("u_prevpin",20);
$objGrids[0]->columnwidth("u_prevarpno",20);
$objGrids[0]->columnwidth("u_prevtdno",18);
$objGrids[0]->columnlnkbtn("u_prevarpno","openfaas1()");
$objGrids[0]->columntitle("u_prevarpno","ID No");
$objGrids[0]->columntitle("u_prevarpno2","ARP No");
$objGrids[0]->height = 150;


$objGrids[1]->height = 100;
$objGrids[1]->columntitle("u_arpno","ID No");
$objGrids[1]->columntitle("u_tdno","Tax Declaration No");
$objGrids[1]->columntitle("u_varpno","Arp No");
//$objGrids[1]->columnlnkbtn("u_pin","openfaas()");
$objGrids[1]->dataentry = false;


$objGridA = new grid("T101");
$objGridA->addcolumn("u_taxable");
$objGridA->addcolumn("u_class");
$objGridA->addcolumn("u_subclass");
$objGridA->addcolumn("u_sqm");
$objGridA->addcolumn("u_unitvalue");
$objGridA->addcolumn("u_basevalue");
$objGridA->addcolumn("docno");
$objGridA->columntitle("u_taxable","Taxable");
$objGridA->columntitle("u_class","Classification");
$objGridA->columntitle("u_subclass","Sub-Classification - Class Level");
$objGridA->columntitle("u_sqm","Area (sqm.)");
$objGridA->columntitle("u_unitvalue","Unit Value/ sqm.");
$objGridA->columntitle("u_basevalue","Base Market Value");
$objGridA->columnwidth("u_taxable",5);
$objGridA->columnwidth("u_class",20);
$objGridA->columnwidth("u_subclass",30);
$objGridA->columnwidth("u_sqm",11);
$objGridA->columnwidth("u_unitvalue",16);
$objGridA->columnwidth("u_basevalue",17);
$objGridA->columnalignment("u_taxable","left");
$objGridA->columnalignment("u_sqm","right");
$objGridA->columnalignment("u_unitvalue","right");
$objGridA->columnalignment("u_basevalue","right");
$objGridA->columninput("u_taxable","type","checkbox");
$objGridA->columndataentry("u_taxable","type","label");
$objGridA->columndataentry("u_class","type","label");
$objGridA->columndataentry("u_subclass","type","label");
$objGridA->columndataentry("u_sqm","type","label");
$objGridA->columndataentry("u_unitvalue","type","label");
$objGridA->columndataentry("u_basevalue","type","label");
$objGridA->columnvisibility("docno",false);
$objGridA->dataentry = true;
$objGridA->showactionbar = false;
$objGridA->setaction("reset",false);
$objGridA->setaction("add",false);
$objGridA->height = 90;

$objGridD = new grid("T104");
$objGridD->addcolumn("u_kind");
$objGridD->addcolumn("u_totalnumber");
$objGridD->addcolumn("u_unitvalue");
$objGridD->addcolumn("u_basevalue");
$objGridD->addcolumn("docno");
$objGridD->columntitle("u_kind","Kind");
$objGridD->columntitle("u_totalnumber","Total Number");
$objGridD->columntitle("u_unitvalue","Unit Value/ sqm.");
$objGridD->columntitle("u_basevalue","Base Market Value");
$objGridD->columnwidth("u_kind",30);
$objGridD->columnwidth("u_totalnumber",11);
$objGridD->columnwidth("u_unitvalue",16);
$objGridD->columnwidth("u_basevalue",17);
$objGridD->columnalignment("u_totalnumber","right");
$objGridD->columnalignment("u_unitvalue","right");
$objGridD->columnalignment("u_basevalue","right");
$objGridD->columndataentry("u_kind","type","label");
$objGridD->columndataentry("u_totalnumber","type","label");
$objGridD->columndataentry("u_unitvalue","type","label");
$objGridD->columndataentry("u_basevalue","type","label");
$objGridD->columnvisibility("docno",false);
$objGridD->dataentry = true;
$objGridD->showactionbar = false;
$objGridD->setaction("reset",false);
$objGridD->setaction("add",false);
$objGridD->height = 60;

$objGridB = new grid("T102");
$objGridB->addcolumn("u_basevalue");
$objGridB->addcolumn("u_adjfactor");
$objGridB->addcolumn("u_adjperc");
$objGridB->addcolumn("u_adjvalue");
$objGridB->addcolumn("u_marketvalue");
$objGridB->columntitle("u_basevalue","Base Market Value");
$objGridB->columntitle("u_adjfactor","Adjustment Factor");
$objGridB->columntitle("u_adjperc","% Adjustment");
$objGridB->columntitle("u_adjvalue","Value Adjustment");
$objGridB->columntitle("u_marketvalue","Market Value");
$objGridB->columnwidth("u_basevalue",20);
$objGridB->columnwidth("u_adjfactor",20);
$objGridB->columnwidth("u_adjperc",11);
$objGridB->columnwidth("u_adjvalue",16);
$objGridB->columnwidth("u_marketvalue",12);
$objGridB->columnalignment("u_basevalue","right");
$objGridB->columnalignment("u_adjperc","right");
$objGridB->columnalignment("u_adjvalue","right");
$objGridB->columnalignment("u_marketvalue","right");
$objGridB->columndataentry("u_basevalue","type","label");
$objGridB->columndataentry("u_adjfactor","type","label");
$objGridB->columndataentry("u_adjperc","type","label");
$objGridB->columndataentry("u_adjvalue","type","label");
$objGridB->columndataentry("u_marketvalue","type","label");
$objGridB->dataentry = true;
$objGridB->showactionbar = false;
$objGridB->setaction("reset",false);
$objGridB->setaction("add",false);
$objGridB->height = 60;

$objGridC = new grid("T103");
$objGridC->addcolumn("u_actualuse");
$objGridC->addcolumn("u_marketvalue");
$objGridC->addcolumn("u_asslvl");
$objGridC->addcolumn("u_assvalue");
$objGridC->addcolumn("docno");
$objGridC->addcolumn("edit");
$objGridC->columntitle("u_actualuse","Actual Use");
$objGridC->columntitle("u_marketvalue","Market Value");
$objGridC->columntitle("u_asslvl","Assessment Level");
$objGridC->columntitle("u_assvalue","Assessed Value");
$objGridC->columntitle("edit","");
$objGridC->columnwidth("u_actualuse",20);
$objGridC->columnwidth("u_marketvalue",20);
$objGridC->columnwidth("u_asslvl",16);
$objGridC->columnwidth("u_assvalue",16);
$objGridC->columnwidth("edit",5);
$objGridC->columnalignment("u_marketvalue","right");
$objGridC->columnalignment("u_asslvl","right");
$objGridC->columnalignment("u_assvalue","right");
$objGridC->columndataentry("u_actualuse","type","label");
$objGridC->columndataentry("u_marketvalue","type","label");
$objGridC->columndataentry("u_asslvl","type","label");
$objGridC->columndataentry("u_assvalue","type","label");
$objGridC->columnvisibility("docno",false);
$objGridC->columnvisibility("edit",false);
$objGridC->dataentry = true;
$objGridC->showactionbar = false;
$objGridC->setaction("reset",false);
$objGridC->setaction("add",false);
$objGridC->height = 60;
$objGridC->columninput("edit","type","link");
$objGridC->columninput("edit","caption","[Edit]");
$objGridC->columninput("edit","dataentrycaption","[Add]");

$objMaster->reportaction = "QS";
$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("update",false);

?> 

