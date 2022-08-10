<?php
  
include_once("./sls/enumyear.php");

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUZoning");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUZoning");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUZoning");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUZoning");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUZoning");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUZoning");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUZoning");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUZoning");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUZoning");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUZoning");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUZoning");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUZoning");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUZoning");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUZoning");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUZoning");

$appdata= array();

function onCustomActionGPSLGUZoning($action) {
	return true;
}

function onBeforeDefaultGPSLGUZoning() { 
        global $page;
	global $appdata;
	$appdata["u_bpno"] = $page->getitemstring("u_bpno");
	$appdata["u_apptype"] = $page->getitemstring("u_apptype");
	$appdata["u_businessname"] = $page->getitemstring("u_businessname");
	return true;
}

function onAfterDefaultGPSLGUZoning() { 
        global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
        
        $page->setitem("u_year",date('Y'));
        $page->setitem("u_docdate",currentdate());
        $page->setitem("u_encodeddate",currentdate());
        $page->setitem("u_appnature","New Construction");
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
            $objRs->queryopen("select code, name, u_amount,u_seqno from u_locationalfees order by u_seqno asc, name asc");
            while ($objRs->queryfetchrow("NAME")) {
                    $objGrids[0]->addrow();
                    $objGrids[0]->setitem(null,"u_year",date('Y'));
                    $objGrids[0]->setitem(null,"u_feecode",$objRs->fields["code"]);
                    $objGrids[0]->setitem(null,"u_feedesc",$objRs->fields["name"]);
                    $objGrids[0]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
                    if ($objRs->fields["code"] == "0421")  $objGrids[0]->setitem(null,"u_quantity",2);
                    else  $objGrids[0]->setitem(null,"u_quantity",1);    
                    $objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                    $objGrids[0]->setitem(null,"u_linetotal",formatNumericAmount($objRs->fields["u_amount"]));
                    $plantotal+=$objRs->fields["u_amount"];
            }
        $page->setitem("u_totalamount",formatNumericAmount($plantotal));
        }
	
        
	return true;
}

function onPrepareAddGPSLGUZoning(&$override) { 
	return true;
}

function onBeforeAddGPSLGUZoning() { 
	return true;
}

function onAfterAddGPSLGUZoning() { 
	return true;
}

function onPrepareEditGPSLGUZoning(&$override) { 
	return true;
}

function onBeforeEditGPSLGUZoning() { 
	return true;
}

function onAfterEditGPSLGUZoning() { 
	return true;
}

function onPrepareUpdateGPSLGUZoning(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUZoning() { 
	return true;
}

function onAfterUpdateGPSLGUZoning() { 
	return true;
}

function onPrepareDeleteGPSLGUZoning(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUZoning() { 
	return true;
}

function onAfterDeleteGPSLGUZoning() { 
	return true;
}

$page->businessobject->items->setcfl("u_assesseddate","Calendar");
$page->businessobject->items->setcfl("u_encodeddate","Calendar");
$page->businessobject->items->setcfl("u_approveddate","Calendar");
$page->businessobject->items->setcfl("u_docdate","Calendar");
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
$objGrids[0]->columnvisibility("u_seqno",false);
$objGrids[0]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[0]->columncfl("u_feedesc","OpenCFLfs()");
$objGrids[0]->columndataentry("u_year","type","select");
$objGrids[0]->columndataentry("u_year","options","loadenumyear");
$objGrids[0]->columnwidth("u_year",8);
//$objGrids[0]->dataentry = false;
$deleteoption = false;
$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);

?> 

