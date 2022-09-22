<?php
 
include_once("./sls/enumyear.php");

//$page->businessobject->events->add->customAction("onCustomActionGPSFireSafety");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSFireSafety");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSFireSafety");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSFireSafety");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSFireSafety");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSFireSafety");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSFireSafety");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSFireSafety");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSFireSafety");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSFireSafety");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSFireSafety");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSFireSafety");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSFireSafety");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSFireSafety");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSFireSafety");

function onCustomActionGPSFireSafety($action) {
	return true;
}

function onBeforeDefaultGPSFireSafety() { 
	return true;
}

function onAfterDefaultGPSFireSafety() { 
        global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
        
        $page->setitem("u_year",date('Y'));
        $page->setitem("u_docdate",currentdate());
        $page->setitem("u_ordate",currentdate());
        $page->setitem("u_encodeddate",currentdate());
        $page->setitem("u_encodedby",$_SESSION["userid"]);
        
        $objRs_uLGUSetup = new recordset(null,$objConnection);
        $objRs_uLGUSetup->queryopen("select A.u_MUNICIPALITY,A.U_CITY, A.u_province from U_LGUSETUP A");
            if ($objRs_uLGUSetup->queryfetchrow("NAME")) {
                if($objRs_uLGUSetup->fields["u_MUNICIPALITY"] !=""){
                    $page->setitem("u_bcity",$objRs_uLGUSetup->fields["u_MUNICIPALITY"]);
                }else{
                    $page->setitem("u_bcity",$objRs_uLGUSetup->fields["U_CITY"]);
                }
                $page->setitem("u_bprovince",$objRs_uLGUSetup->fields["u_province"]);
            }
            
            $plantotal=0;
        $objRs = new recordset(null,$objConnection);
        if ($appdata["u_bpno"]!="") {
		if ($appdata["u_apptype"]=="RENEW") {
			$page->setitem("u_appnature","Renewal");
                        $page->businessobject->items->seteditable("u_businessname",false);
		}
                
		$obju_ZoningApps = new documentschema_br(null,$objConnection,"u_zoningclrapps");
		if ($obju_ZoningApps->getbykey($appdata["u_bpno"])) {
			$page->setitem("u_natureofbusiness",$obju_ZoningApps->getudfvalue("u_natureofbusiness"));
			$page->setitem("u_address",$obju_ZoningApps->getudfvalue("u_address"));
			$page->setitem("u_lastname",$obju_ZoningApps->getudfvalue("u_lastname"));
			$page->setitem("u_firstname",$obju_ZoningApps->getudfvalue("u_firstname"));
			$page->setitem("u_middlename",$obju_ZoningApps->getudfvalue("u_middlename"));
			$page->setitem("u_owneraddress",$obju_ZoningApps->getudfvalue("u_owneraddress"));
			$page->setitem("u_authrep",$obju_ZoningApps->getudfvalue("u_authrep"));
			$page->setitem("u_gender",$obju_ZoningApps->getudfvalue("u_gender"));
			$page->setitem("u_contactno",$obju_ZoningApps->getudfvalue("u_contactno"));
			$page->setitem("u_istup",$obju_ZoningApps->getudfvalue("u_istup"));
			$page->setitem("u_totalamount",formatNumericAmount($obju_ZoningApps->getudfvalue("u_totalamount")));
			$page->setitem("u_businessname",$obju_ZoningApps->getudfvalue("u_businessname"));
                        $page->setitem("u_bbrgy",$obju_ZoningApps->getudfvalue("u_bbrgy"));
                        $page->setitem("u_bbldgno",$obju_ZoningApps->getudfvalue("u_bbldgno"));
                        $page->setitem("u_bblock",$obju_ZoningApps->getudfvalue("u_bblock"));
                        $page->setitem("u_blotno",$obju_ZoningApps->getudfvalue("u_blotno"));
                        $page->setitem("u_bvillage",$obju_ZoningApps->getudfvalue("u_bvillage"));
                        $page->setitem("u_bstreet",$obju_ZoningApps->getudfvalue("u_bstreet"));
                        $page->setitem("u_bphaseno",$obju_ZoningApps->getudfvalue("u_bphaseno"));
                        $page->setitem("u_baddressno",$obju_ZoningApps->getudfvalue("u_baddressno"));
                        $page->setitem("u_bbldgname",$obju_ZoningApps->getudfvalue("u_bbldgname"));
                        $page->setitem("u_bunitno",$obju_ZoningApps->getudfvalue("u_bunitno"));
                        $page->setitem("u_bfloorno",$obju_ZoningApps->getudfvalue("u_bfloorno"));
                        $page->setitem("u_bcity",$obju_ZoningApps->getudfvalue("u_bcity"));
                        $page->setitem("u_bprovince",$obju_ZoningApps->getudfvalue("u_bprovince"));
                        $page->setitem("u_btelno",$obju_ZoningApps->getudfvalue("u_btelno"));
                        $page->setitem("u_acctno",$obju_ZoningApps->getudfvalue("u_acctno"));
			if ($obju_ZoningApps->getudfvalue("u_istup") == 1)$objRs->queryopen("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_TUPRENEWAMOUNT AS U_AMOUNT,U_SEQNO FROM U_ZONINGFEES A where A.U_TUPRENEWAMOUNT <> 0");
                        else $objRs->queryopen("SELECT  A.CODE AS U_ITEMCODE, A.NAME AS U_ITEMDESC, A.U_RENEWAMOUNT AS U_AMOUNT,U_SEQNO FROM U_ZONINGFEES A where A.U_RENEWAMOUNT <>0 ");
			while ($objRs->queryfetchrow("NAME")) {
				$objGrids[0]->addrow();
                                $objGrids[0]->setitem(null,"u_year",date('Y'));
				$objGrids[0]->setitem(null,"u_feecode",$objRs->fields["U_ITEMCODE"]);
				$objGrids[0]->setitem(null,"u_feedesc",$objRs->fields["U_ITEMDESC"]);
				$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["U_AMOUNT"]));
				$objGrids[0]->setitem(null,"u_seqno",$objRs->fields["U_SEQNO"]);
                                $plantotal+=$objRs->fields["U_AMOUNT"];
                        }
                        $page->setitem("u_totalamount",formatNumericAmount($plantotal));
		}
        }else {
            $objRs->queryopen("select code, name, u_amount, u_check,u_seqno from u_fsfees order by u_seqno asc, name asc");
            while ($objRs->queryfetchrow("NAME")) {
                    $objGrids[0]->addrow();
                    $objGrids[0]->setitem(null,"u_check",$objRs->fields["u_check"]);
                    $objGrids[0]->setitem(null,"u_year",date('Y'));
                    $objGrids[0]->setitem(null,"u_feecode",$objRs->fields["code"]);
                    $objGrids[0]->setitem(null,"u_feedesc",$objRs->fields["name"]);
                    $objGrids[0]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
                    $objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                    if ($objRs->fields["u_check"] == 1)  $plantotal+=$objRs->fields["u_amount"];
                   
            }
            
//            $objRs->queryopen("select code, name from u_fsreqs  order by u_seq asc");
//            while ($objRs->queryfetchrow("NAME")) {
//                    $objGrids[1]->addrow();
//                    $objGrids[1]->setitem(null,"u_reqcode",$objRs->fields["code"]);
//                    $objGrids[1]->setitem(null,"u_reqdesc",$objRs->fields["name"]);
//            }
            
        $page->setitem("u_totalamount",formatNumericAmount($plantotal));
        }
            
	return true;
}

function onPrepareAddGPSFireSafety(&$override) { 
	return true;
}

function onBeforeAddGPSFireSafety() { 
	return true;
}

function onAfterAddGPSFireSafety() { 
	return true;
}

function onPrepareEditGPSFireSafety(&$override) { 
	return true;
}

function onBeforeEditGPSFireSafety() { 
	return true;
}

function onAfterEditGPSFireSafety() { 
	return true;
}

function onPrepareUpdateGPSFireSafety(&$override) { 
	return true;
}

function onBeforeUpdateGPSFireSafety() { 
	return true;
}

function onAfterUpdateGPSFireSafety() { 
	return true;
}

function onPrepareDeleteGPSFireSafety(&$override) { 
	return true;
}

function onBeforeDeleteGPSFireSafety() { 
	return true;
}

function onAfterDeleteGPSFireSafety() { 
	return true;
}

$page->businessobject->items->setcfl("u_ordate","Calendar");
$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_bpno","OpenCFLfs()");
$page->businessobject->items->seteditable("docstatus",false);


$page->businessobject->items->setcfl("u_assesseddate","Calendar");
$page->businessobject->items->setcfl("u_encodeddate","Calendar");
$page->businessobject->items->setcfl("u_approveddate","Calendar");
$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_receiveddate","Calendar");
//$page->businessobject->items->setcfl("u_landtdno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_bldgtdno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_applicantno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_bstreet","OpenCFLfs()");
$page->businessobject->items->setcfl("u_bvillage","OpenCFLfs()");

$page->businessobject->items->seteditable("u_year",false);
$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_totalamount",false);
$page->businessobject->items->seteditable("u_bplappno",false);
$page->businessobject->items->seteditable("u_acctno",false);
$page->businessobject->items->seteditable("u_encodedby",false);
$page->businessobject->items->seteditable("u_encodeddate",false);
$page->businessobject->items->seteditable("u_decisionno",false);
$page->businessobject->items->seteditable("u_fsecno",false);
$page->businessobject->items->seteditable("u_fsicno",false);

$objGrids[0]->columndataentry("u_check","type","checkbox");
$objGrids[0]->columninput("u_check","type","checkbox");
$objGrids[0]->columndataentry("u_check","value",1);
$objGrids[0]->columninput("u_check","value",1);
$objGrids[0]->columninput("u_amount","type","text");
$objGrids[0]->columnvisibility("u_seqno",false);
$objGrids[0]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[0]->columncfl("u_feedesc","OpenCFLfs()");
$objGrids[0]->columndataentry("u_year","type","select");
$objGrids[0]->columndataentry("u_year","options","loadenumyear");
$objGrids[0]->columnwidth("u_year",8);
$objGrids[0]->dataentry = false;

$objGrids[1]->columnwidth("u_check",2);
$objGrids[1]->columnwidth("u_reqdesc",80);
$objGrids[1]->columnwidth("u_remarks",50);
$objGrids[1]->columninput("u_check","type","checkbox");
$objGrids[1]->columninput("u_check","value",1);
$objGrids[1]->columninput("u_remarks","type","text");
$objGrids[1]->columnvisibility("u_reqcode",false);
//$objGrids[1]->columnvisibility("u_remarks",false);
$objGrids[1]->dataentry = false;

$objGrids[1]->automanagecolumnwidth = true;

$deleteoption = false;
$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);

$objMaster->reportaction = "QS";

?> 

