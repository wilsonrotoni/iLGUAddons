<?php

include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
 

//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSHIS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSHIS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

$page->businessobject->events->add->prepareChildEdit("onPrepareChildEditGPSHIS");
$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

$u_trxtype="";
$companydata = array();
$postdata = array();
function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $u_trxtype;
	global $companydata;
	global $postdata;
	$u_trxtype = $page->getitemstring("u_trxtype");
	$postdata["u_replenish"] = $page->getitemstring("u_replenish");
	$postdata["u_itemclass"] = $page->getitemstring("u_itemclass");
	$postdata["u_fromdepartment"] = $page->getitemstring("u_fromdepartment");
	$postdata["u_todepartment"] = $page->getitemstring("u_todepartment");
	
	$companydata = getcurrentcompanydata("PRSUPPNO,PURCHASINGPRICELIST");
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $page;
	global $userdepartment;
	global $userreftype;
	global $u_trxtype;
	global $objGrids;
	global $company;
	global $branch;
	global $companydata;
	global $postdata;
		
	$u_hissetupdata = getu_hissetup("U_DFLTIPD,U_DFLTOPD,U_DFLTPHARMADEPT,U_DFLTLABDEPT,U_DFLTCSRDEPT,U_DFLTHEARTSTATIONDEPT,U_DFLTDIETARYDEPT,U_DFLTHOUSEKEEPINGDEPT,U_DFLTWAREHOUSEDEPT");
	
	$page->setitem("u_docdate",currentdate());
	$page->setitem("u_reqdate",currentdate());
	$page->setitem("u_trxtype",$u_trxtype);
	if ($postdata["u_fromdepartment"]!="") {
		$page->setitem("u_replenish",$postdata["u_replenish"]);
		$page->setitem("u_itemclass",$postdata["u_itemclass"]);
		$page->setitem("u_fromdepartment",$postdata["u_fromdepartment"]);
		$page->setitem("u_todepartment",$postdata["u_todepartment"]);
	} else {	
		if ($userdepartment!="") $page->setitem("u_fromdepartment",$userdepartment);
		elseif ($page->getitemstring("u_trxtype")=="IP") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTIPD"]);
		elseif ($page->getitemstring("u_trxtype")=="OP") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTOPD"]);
		elseif ($page->getitemstring("u_trxtype")=="PHARMACY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTPHARMADEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="LABORATORY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTLABDEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="CSR") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTCSRDEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="DIETARY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTDIETARYDEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="HEARTSTATION") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTHEARTSTATIONDEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="HOUSEKEEPING") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTHOUSEKEEPINGDEPT"]);
		$page->setitem("u_todepartment",$u_hissetupdata["U_DFLTWAREHOUSEDEPT"]);
	}
			
	/*
	if ($page->getitemstring("u_trxtype")=="PHARMACY" || $page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="CSR") {
		$page->setitem("u_todepartment",$page->getitemstring("u_fromdepartment"));
		$page->setitem("u_prepaid",1);
	}
	*/
	
	$objGrids[0]->clear();
	$u_totalamount=0;	
	$objRs = new recordset(null,$objConnection);
	$itemclassExp = "";
	if ($page->getitemstring("u_itemclass")!="") $itemclassExp = "and i.u_class='".$page->getitemstring("u_itemclass")."'";
	//$objRs->setdebug();
	$sql = "select u_itemdesc, u_itemcode, i.u_uom, pl.price, i.u_preferredsuppno, s.suppname as u_preferredsuppname, sum(u_trqty) as u_trqty, sum(u_prqty) as u_prqty, sum(u_poqty) as u_poqty, sum(u_minqty) as u_minqty, sum(u_maxqty) as u_maxqty, sum(u_instock) as u_instock from (
	select b.u_itemcode, b.u_itemdesc, b.u_quantity as u_trqty, 0 as u_prqty, 0 as u_poqty, 0 as u_minqty, 0 as u_maxqty, 0 as u_instock, b.u_uom from u_hismedsupstockrequests a, u_hismedsupstockrequestitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and a.u_todepartment='".$page->getitemstring("u_fromdepartment")."' and a.docstatus='O'
		union all
	select b.itemcode as u_itemcode, b.itemdesc as u_itemdesc, 0 as u_trqty, b.openquantity as u_prqty, 0 as u_poqty, 0 as u_minqty, 0 as u_maxqty, 0 as u_instock, b.uom from purchaserequests a, purchaserequestitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and b.whscode='".$page->getitemstring("u_fromdepartment")."' and a.docstatus='O' and b.openquantity>0
		union all
	select b.itemcode as u_itemcode, b.itemdesc as u_itemdesc, 0 as u_trqty, 0 as u_prqty, b.openquantity as u_poqty, 0 as u_minqty, 0 as u_maxqty, 0 as u_instock, b.uom from purchaseorders a, purchaseorderitems b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and b.whscode='".$page->getitemstring("u_fromdepartment")."' and a.docstatus='O' and b.openquantity>0 ";
	if ($page->getitemstring("u_replenish")=="1") $sql .= " union all
	select a.code as u_itemcode, a.name as u_itemdesc, 0 as u_trqty, 0 as u_prqty, 0 as u_poqty, b.u_minqty as u_minqty, b.u_maxqty as u_maxqty, 0 as u_instock, a.u_uom from u_hisitems a, u_hisitemsections b where b.code=a.code and b.u_section='".$page->getitemstring("u_fromdepartment")."' and b.u_requestdoctype='Transfer' and (b.u_maxqty>0 or b.u_minqty>0) ";
	$sql .= " union all
	select a.itemcode as u_itemcode, b.name as u_itemdesc, 0 as u_trqty, 0 as u_prqty, 0 as u_poqty, 0 as u_minqty, 0 as u_maxqty, a.instockqty as u_instock, b.u_uom from stockcardsummary a, u_hisitems b where b.code=a.itemcode and a.company='$company' and a.branch='$branch' and a.warehouse='".$page->getitemstring("u_fromdepartment")."' and a.instockqty >0
		) x inner join u_hisitems i on i.code=x.u_itemcode and i.u_active=1 $itemclassExp 
			left join itempricelists pl on pl.itemcode=x.u_itemcode and pl.pricelist='". $companydata["PURCHASINGPRICELIST"]."' 
			left join suppliers s on s.suppno=i.u_preferredsuppno group by u_itemdesc, u_itemcode";
			
	$objRs->queryopen($sql);
	//var_dump($objRs->sqls);		
	while ($objRs->queryfetchrow("NAME")) {
		$u_quantity = $objRs->fields["u_instock"] + $objRs->fields["u_prqty"] + $objRs->fields["u_poqty"] - $objRs->fields["u_trqty"];
		$u_netqty = $u_quantity;


		//if ($u_quantity<$objRs->fields["u_maxqty"]) 
		if ($objRs->fields["u_maxqty"]>0) $u_quantity = ($u_quantity-$objRs->fields["u_maxqty"])*-1;
		else $u_quantity = ($u_quantity-$objRs->fields["u_minqty"])*-1;
		if ($u_quantity>0) {
			$objGrids[0]->addrow();
			$objGrids[0]->setitem(null,"u_itemcode",$objRs->fields["u_itemcode"]);
			$objGrids[0]->setitem(null,"u_itemdesc",$objRs->fields["u_itemdesc"]);
			$objGrids[0]->setitem(null,"u_uom",$objRs->fields["u_uom"]);
			$objGrids[0]->setitem(null,"u_trqty",formatNumericQuantity($objRs->fields["u_trqty"]));
			$objGrids[0]->setitem(null,"u_prqty",formatNumericQuantity($objRs->fields["u_prqty"]));
			$objGrids[0]->setitem(null,"u_poqty",formatNumericQuantity($objRs->fields["u_poqty"]));
			$objGrids[0]->setitem(null,"u_instock",formatNumericQuantity($objRs->fields["u_instock"]));
			$objGrids[0]->setitem(null,"u_minqty",formatNumericQuantity($objRs->fields["u_minqty"]));
			$objGrids[0]->setitem(null,"u_maxqty",formatNumericQuantity($objRs->fields["u_maxqty"]));
		
			$objGrids[0]->setitem(null,"u_netqty",formatNumericQuantity($u_netqty));
			$objGrids[0]->setitem(null,"u_quantity",formatNumericQuantity($u_quantity));
			$objGrids[0]->setitem(null,"u_unitprice",formatNumericPrice($objRs->fields["price"]));
			$objGrids[0]->setitem(null,"u_linetotal",formatNumericAmount($u_quantity*$objRs->fields["price"]));

			$u_totalamount+=($u_quantity*$objRs->fields["price"]);	
		}		
	}	
	$page->setitem("u_totalamount",formatNumericAmount($u_totalamount));
		
	return true;
}

function onPrepareAddGPSHIS(&$override) { 
	return true;
}

function onBeforeAddGPSHIS() { 
	return true;
}

function onAfterAddGPSHIS() { 
	return true;
}

function onPrepareEditGPSHIS(&$override) { 
	return true;
}

function onBeforeEditGPSHIS() { 
	return true;
}

function onAfterEditGPSHIS() { 
	global $page;
	global $objGrids;
	/*
  	$dirname = "../Images/" . $_SESSION["company"] ."/" . $_SESSION["branch"] . "/HIS/Laboratory Tests/".$page->getitemstring("docno")."/Attachments/";
	if (is_dir($dirname)) {
		$dir_handle = opendir($dirname);
		if ($dir_handle) {
			while($file = readdir($dir_handle)) {
				if ($file != "." && $file != "..") {
					if (is_file($dirname.$file)) {
						$path_parts = pathinfo($dirname.$file);
						if (strtolower($path_parts["extension"])=="gif" || strtolower($path_parts["extension"])=="jpg" || strtolower($path_parts["extension"])=="png") {			
							$objGrids[1]->addrow();
							$objGrids[1]->setitem(null,"u_picturename",substr($path_parts["basename"],0,strlen($path_parts["basename"])-4));
							$objGrids[1]->setitem(null,"u_filepath",$dirname.$file);
							$objGrids[1]->setitem(null,"rowstat","E");
						}
					}
			  }
			}
			closedir($dir_handle);
		}	
	}
	*/		   	
	return true;
}

function onPrepareUpdateGPSHIS(&$override) { 
	return true;
}

function onBeforeUpdateGPSHIS() { 
	return true;
}

function onAfterUpdateGPSHIS() { 
	return true;
}

function onPrepareDeleteGPSHIS(&$override) { 
	return true;
}

function onBeforeDeleteGPSHIS() { 
	return true;
}

function onAfterDeleteGPSHIS() { 
	return true;
}

function onPrepareChildEditGPSHIS($objGrid,$objTable,$tablename,&$override,&$filerExp) {
	switch ($tablename) {
		case "T1":	
			$filerExp = " ORDER BY U_ITEMDESC, U_ITEMCODE";
			break;
			
	}
}

function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T1":
			if ($column=="u_reqtype") {
				switch ($label) {
					case "GI": $label = "For Expense"; break;
					case "TF": $label = "For Stock"; break;
				}
			}
			break;
		default:
			break;
	}
}


$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_reqdate","Calendar");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_totalamount",false);

$objGrids[0]->columnwidth("u_itemcode",10);
$objGrids[0]->columnwidth("u_itemdesc",25);
$objGrids[0]->columnwidth("u_whscode",15);
$objGrids[0]->columnwidth("u_trqty",8);
$objGrids[0]->columnwidth("u_prqty",8);
$objGrids[0]->columnwidth("u_poqty",8);
$objGrids[0]->columnwidth("u_minqty",8);
$objGrids[0]->columnwidth("u_maxqty",8);
$objGrids[0]->columnwidth("u_instock",8);
$objGrids[0]->columnwidth("u_instockref",15);
$objGrids[0]->columnwidth("u_netqty",8);
$objGrids[0]->columnwidth("u_quantity",8);
$objGrids[0]->columnwidth("u_uom",5);
$objGrids[0]->columnwidth("u_reqtype",11);
$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
//$objGrids[0]->columncfl("u_itemcode","OpenCFLitems()");
//$objGrids[0]->columncfl("u_itemdesc","OpenCFLitems()");
$objGrids[0]->columninput("u_quantity","type","text");
//$objGrids[0]->columninput("u_reqtype","type","select");
$objGrids[0]->columnattributes("u_netqty","disabled");
$objGrids[0]->columnattributes("u_minqty","disabled");
$objGrids[0]->columnattributes("u_maxqty","disabled");
$objGrids[0]->columnattributes("u_trqty","disabled");
$objGrids[0]->columnattributes("u_prqty","disabled");
$objGrids[0]->columnattributes("u_poqty","disabled");
$objGrids[0]->columnattributes("u_instock","disabled");
$objGrids[0]->columnattributes("u_uom","disabled");
//$objGrids[0]->columnattributes("u_itemdesc","disabled");
$objGrids[0]->columnattributes("u_linetotal","disabled");
$objGrids[0]->columnvisibility("u_whscode",false);
$objGrids[0]->automanagecolumnwidth = false;

/*
$objGrids[1]->width = 300;
$objGrids[1]->columnwidth("u_picturename",30);
$objGrids[1]->columnwidth("u_filepath",10);
$objGrids[1]->columnvisibility("u_filepath",false);
$objGrids[1]->automanagecolumnwidth = false;
$objGrids[1]->setaction("reset",false);
*/

$objrs = new recordset(null,$objConnection);
$userdepartment = "";
$userreftype = "";
/*
$objrs->queryopen("select A.U_SECTION,B.U_TYPE FROM EMPLOYEES A, U_HISSECTIONS B WHERE B.CODE=A.U_SECTION AND A.USERID='".$_SESSION["userid"]."'");
*/
$objrs->queryopen("select A.ROLEID AS U_SECTION,B.U_TYPE FROM USERS A, U_HISSECTIONS B WHERE B.CODE=A.ROLEID AND A.USERID='".$_SESSION["userid"]."'");
if ($objrs->queryfetchrow("NAME")) {
	$userdepartment = $objrs->fields["U_SECTION"];
	$userreftype = $objrs->fields["U_TYPE"];
	if ($userdepartment!="") $page->businessobject->items->seteditable("u_fromdepartment",false);
	if ($userreftype!="") $page->businessobject->items->seteditable("u_reftype",false);
}


$addoptions = false;
$deleteoption = false;
$canceloption = true;
?> 

