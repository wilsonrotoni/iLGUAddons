<?php
 

$page->businessobject->events->add->customAction("onCustomActionGPSLGUBarangay");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUBarangay");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUBarangay");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUBarangay");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUBarangay");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUBarangay");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUBarangay");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUBarangay");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUBarangay");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUBarangay");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUBarangay");
$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUBarangay");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUBarangay");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUBarangay");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUBarangay");
$appdata= array();
function onCustomActionGPSLGUBarangay($action) {
	return true;
}

function onBeforeDefaultGPSLGUBarangay() { 
        global $page;
	global $appdata;
        $appdata["docno"] = $page->getitemstring("docno");
	$appdata["u_apptype"] = $page->getitemstring("u_apptype");
  

	return true;
}

function onAfterDefaultGPSLGUBarangay() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
	
	$page->setitem("u_date",currentdate());
	$page->setitem("u_dateofbirth",currentdate());	
	$page->setitem("docstatus","Encoding");
	$page->setitem("u_year",date('Y'));
        $page->setitem("u_apptype","I");
	
	$objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.u_MUNICIPALITY, A.u_province from U_LGUSETUP A");
	if ($objRs_uLGUSetup->queryfetchrow("NAME")) {
		//$page->setitem("u_city",$objRs_uLGUSetup->fields["u_MUNICIPALITY"]);
		//$page->setitem("u_province",$objRs_uLGUSetup->fields["u_province"]);
	}	
	
	$objRs = new recordset(null,$objConnection);
	
//	if ($appdata["docno"]!="") {
//     
//      $page->setitem("docno",getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("docseries"),$page->getitemdate("u_date"),$objConnection,false));
//       if ($appdata["u_apptype"]=="C") {
//			$page->setitem("u_apptype","C");
//			}else {
//			 $page->setitem("u_apptype",$appdata["u_apptype"]);
//        }
//			
//      $obju_CtcApps = new documentschema_br(null,$objConnection,"u_ctcapps");
//		
//		if ($obju_CtcApps->getbykey($appdata["docno"])) {
//      
//			$page->setitem("u_icrno",$obju_CtcApps->getudfvalue("u_regno"));
//			$page->setitem("u_date",formatDateToHttp($obju_CtcApps->getudfvalue("u_regdate")));
//			$page->setitem("u_year",$obju_CtcApps->getudfvalue("u_orgtype"));
//			$page->setitem("u_tin",$obju_CtcApps->getudfvalue("u_tin"));
//			$page->setitem("u_placeofbrith",$obju_CtcApps->getudfvalue("u_businessname"));
//			$page->setitem("u_dateofbirth",$obju_CtcApps->getudfvalue("u_tradename"));
//			$page->setitem("u_lastname",$obju_CtcApps->getudfvalue("u_lastname"));
//			$page->setitem("u_firstname",$obju_CtcApps->getudfvalue("u_firstname"));
//			$page->setitem("u_middlename",$obju_CtcApps->getudfvalue("u_middlename"));
//			$page->setitem("u_gender",$obju_CtcApps->getudfvalue("u_gender"));
//			$page->setitem("u_address",$obju_CtcApps->getudfvalue("u_street"));
//			$page->setitem("u_city",$obju_CtcApps->getudfvalue("u_city"));
//			$page->setitem("u_province",$obju_CtcApps->getudfvalue("u_province"));
//			$page->setitem("u_weight",$obju_CtcApps->getudfvalue("u_telno"));
//			$page->setitem("u_height",$obju_CtcApps->getudfvalue("u_email"));
//			$page->setitem("u_apptype",$appdata["u_apptype"]);
//			
//		
//		
//			
//			//dito yung nag aautomatic yung fields base sa dropdown na baragay
//			//if ($obju_BPLApps->getudfvalue("u_bbrgy")!="") {
//			//	$objRs->queryopen("select u_garbagefeec, u_garbagefeer, u_electricalcert from u_barangays where code='".$obju_BPLApps->getudfvalue("u_bbrgy")."'");
//				//if ($objRs->queryfetchrow("NAME")) {
//				//	$page->setitem("u_garbagefeec",formatNumericAmount($objRs->fields["u_garbagefeec"]));
//			//		$page->setitem("u_garbagefeer",formatNumericAmount($objRs->fields["u_garbagefeer"]));
//			//	}	
//			//}	
//			//dito yung pag load sa grid
//			// $objRs->queryopen("select u_businessline, u_unitcount, u_nonessential, u_essential from u_bplapplines where company='$obju_BPLApps->company' and branch='$obju_BPLApps->branch' and docid='$obju_BPLApps->docid'");
//			//while ($objRs->queryfetchrow("NAME")) {
//			//	$objGrids[0]->addrow();
//			//	$objGrids[0]->setitem(null,"u_businessline",$objRs->fields["u_businessline"]);
//			//	$objGrids[0]->setitem(null,"u_unitcount",$objRs->fields["u_unitcount"]);
//			//	$objGrids[0]->setitem(null,"u_capital",formatNumericAmount(0));
//			//	$objGrids[0]->setitem(null,"u_essential",formatNumericAmount(0));
//			//	$objGrids[0]->setitem(null,"u_nonessential",formatNumericAmount(0));
//			//	$objGrids[0]->setitem(null,"u_lastyrgrsales",formatNumericAmount($objRs->fields["u_nonessential"]+$objRs->fields["u_essential"]));
//			//}
//		} 
//	} 
	//elseif ($appdata["u_businessname"]!="") {
	//	$page->setitem("u_businessname",$appdata["u_businessname"]);

	//}	

	$objRs = new recordset(null,$objConnection);
	
	
	$objGrids[0]->clear();

	$total=0;	
//	$objRs->queryopen("select code, name, u_amount from u_ctcfees order by u_seqno asc, name asc");
//	while ($objRs->queryfetchrow("NAME")) {
//		$objGrids[0]->addrow();
//		$objGrids[0]->setitem(null,"u_feecode",$objRs->fields["code"]);
//		$objGrids[0]->setitem(null,"u_feedesc",$objRs->fields["name"]);
//		$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
//		$objGrids[0]->setitem(null,"u_common",1);
//		$total+=$objRs->fields["u_amount"];
//	}
	
	$page->setitem("u_asstotal",formatNumericAmount($total));
	return true;
}

function onPrepareAddGPSLGUBarangay(&$override) { 
	return true;
}

function onBeforeAddGPSLGUBarangay() { 
	return true;
}

function onAfterAddGPSLGUBarangay() { 
	return true;
}

function onPrepareEditGPSLGUBarangay(&$override) { 
	return true;
}

function onBeforeEditGPSLGUBarangay() { 
	return true;
}

function onAfterEditGPSLGUBarangay() { 
	return true;
}

function onPrepareUpdateGPSLGUBarangay(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUBarangay() { 
	return true;
}

function onAfterUpdateGPSLGUBarangay() { 
	return true;
}

function onPrepareDeleteGPSLGUBarangay(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUBarangay() { 
	return true;
}

function onAfterDeleteGPSLGUBarangay() { 
	return true;
}

$objGrids[0]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[0]->columncfl("u_feedesc","OpenCFLfs()");

$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_dateofbirth","Calendar");
$page->businessobject->items->setcfl("u_paiddate","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
$page->businessobject->items->seteditable("u_dueamount",false);
$page->businessobject->items->seteditable("u_changeamount",false);

//$addoptions = false;
//$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);



?> 

