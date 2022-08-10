<?php
 
include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
require_once ("../common/classes/excel.php");	

$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

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

$u_trxtype="";
$postdata = array();
$companydata = array();

function getCellAddress($col,$row=0) {
	$ch = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
				'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
				'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
				'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
				'DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ',
				'EA','EB','EC','ED','EE','EF','EG','EH','EI','EJ','EK','EL','EM','EN','EO','EP','EQ','ER','ES','ET','EU','EV','EW','EX','EY','EZ',
				'FA','FB','FC','FD','FE','FF','FG','FH','FI','FJ','FK','FL','FM','FN','FO','FP','FQ','FR','FS','FT','FU','FV','FW','FX','FY','FZ',
				'GA','GB','GC','GD','GE','GF','GG','GH','GI','GJ','GK','GL','GM','GN','GO','GP','GQ','GR','GS','GT','GU','GV','GW','GX','GY','GZ',
				'HA','HB','HC','HD','HE','HF','HG','HH','HI','HJ','HK','HL','HM','HN','HO','HP','HQ','HR','HS','HT','HU','HV','HW','HX','HY','HZ',
				'IA','IB','IC','ID','IE','IF','IG','IH','II','IJ','IK','IL','IM','IN','IO','IP','IQ','IR','IS','IT','IU','IV','IW','IX','IY','IZ',
				'JA','JB','JC','JD','JE','JF','JG','JH','JI','JJ','JK','JL','JM','JN','JO','JP','JQ','JR','JS','JT','JU','JV','JW','JX','JY','JZ',
				'KA','KB','KC','KD','KE','KF','KG','KH','KI','KJ','KK','KL','KM','KN','KO','KP','KQ','KR','KS','KT','KU','KV','KW','KX','KY','KZ',
				'LA','LB','LC','LD','LE','LF','LG','LH','LI','LJ','LK','LL','LM','LN','LO','LP','LQ','LR','LS','LT','LU','LV','LW','LX','LY','LZ');
	/*
	$c1 = intval($col / 26);
	$c2 = $col -($c1*26);
	if ($c1>0 && $c2==0) $c2 = 1;
	if ($c1>0) return $ch[$c1-1] . $ch[$c2-1] . iif($row>0,$row,"");
	else return $ch[$c2-1] . iif($row>0,$row,"");
	
	*/
	 return $ch[$col-1] . iif($row>0,$row,"");
}


function onCustomActionGPSHIS($action) {
	global $page;
	global $company;
	global $branch;
	global $companydata;
	if ($action=="generateinstock") {
		$objRs = new recordset(null,$objConnection);
		
		$companydata = getcurrentcompanydata("PRSUPPNO,PURCHASINGPRICELIST");
		
		$objExcel = new Excel;
		$sheet="Initial Quantities";
		$sourcepath = realpath("../Addons/GPS/HIS Add-On/UserPrograms/Templates/");
		$objExcel->openxl("Initial Quantities",$sourcepath,$sheet);
		
		$objExcel->writerange($sheet,getCellAddress(3,2).":".getCellAddress(3,2)  ,array($page->getitemstring("u_docdate")));
		$objExcel->writerange($sheet,getCellAddress(4,2).":".getCellAddress(4,2)  ,array($page->getitemstring("u_todepartment")));
		$objExcel->writerange($sheet,getCellAddress(5,2).":".getCellAddress(5,2)  ,array("Stock Adjustment ".$page->getitemstring("u_todepartment")." (".$page->getitemstring("u_cosstartdate")." - ".$page->getitemstring("u_cosenddate").")"));
		
		$sql = "select u_itemdesc, u_itemcode, i.u_uom, i.u_manageby, pl.price, i.u_numperuompu, sum(u_quantity) as u_quantity, sum(u_instock) as u_instock from (
		select b.u_itemcode, b.u_itemdesc, 0 as u_instock, b.u_quantity, b.u_uom from u_hischarges a, u_hischargeitems b, u_hisitems c where c.code=b.u_itemcode and c.u_type='MEDSUP' and c.u_isstock=1 and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and a.u_department='".$page->getitemstring("u_fromdepartment")."' and a.u_startdate>='".$page->getitemdate("u_cosstartdate")."' and a.u_startdate<='".$page->getitemdate("u_cosenddate")."' and a.docstatus not in ('CN')  union all select b.u_itemcode, b.u_itemdesc, 0 as u_instock, b.u_quantity, b.u_uom from u_hischarges a, u_hischargeitempacks b, u_hisitems c where c.code=b.u_itemcode and c.u_type='MEDSUP' and c.u_isstock=1 and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and a.u_department='".$page->getitemstring("u_fromdepartment")."' and a.u_startdate>='".$page->getitemdate("u_cosstartdate")."' and a.u_startdate<='".$page->getitemdate("u_cosenddate")."' and a.docstatus not in ('CN') union all select b.u_itemcode, b.u_itemdesc, 0 as u_instock, b.u_quantity*-1 as u_quantity, b.u_uom from u_hiscredits a, u_hiscredititems b, u_hisitems c where c.code=b.u_itemcode and c.u_type='MEDSUP' and c.u_isstock=1 and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and a.u_department='".$page->getitemstring("u_fromdepartment")."' and a.u_startdate>='".$page->getitemdate("u_cosstartdate")."' and a.u_startdate<='".$page->getitemdate("u_cosenddate")."' and a.docstatus not in ('CN') union all select b.u_itemcode, b.u_itemdesc, 0 as u_instock, b.u_quantity*-1 as u_quantity, b.u_uom from u_hiscredits a, u_hiscredititempacks b, u_hisitems c where c.code=b.u_itemcode and c.u_type='MEDSUP' and c.u_isstock=1 and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and a.u_department='".$page->getitemstring("u_fromdepartment")."' and a.u_startdate>='".$page->getitemdate("u_cosstartdate")."' and a.u_startdate<='".$page->getitemdate("u_cosenddate")."' and a.docstatus not in ('CN')";
		
		$sql .= " union all
	select a.itemcode as u_itemcode, b.name as u_itemdesc, a.instockqty as u_instock, 0 as u_quantity, b.u_uom from stockcardsummary a, u_hisitems b where b.code=a.itemcode and a.company='$company' and a.branch='$branch' and a.warehouse='".$page->getitemstring("u_fromdepartment")."' and a.instockqty >0
		) x inner join u_hisitems i on i.code=x.u_itemcode and i.u_active=1 
			left join itempricelists pl on pl.itemcode=x.u_itemcode and pl.pricelist='". $companydata["PURCHASINGPRICELIST"]."' group by x.u_itemcode order by x.u_itemdesc";
		
		//$objRs->setdebug();	
		$objRs->queryopen($sql);
		//var_dump($objRs->sqls);
		$row0 = 2;
		$row1 = 2;
		
		while ($objRs->queryfetchrow("NAME")) {
			if ($objRs->fields["u_quantity"]!=0 && $objRs->fields["u_quantity"]>$objRs->fields["u_instock"]) {
				if ($objRs->fields["u_manageby"]=="0") {
					$objExcel->writerange("Initial Quantity - Items",getCellAddress(1,$row0).":".getCellAddress(9,$row0)  ,
						array("1",
							   $objRs->fields["u_itemcode"],
							   $objRs->fields["u_itemdesc"],
							   $objRs->fields["u_uom"],
							   $objRs->fields["price"]/$objRs->fields["u_numperuompu"],
							   0,
							   $objRs->fields["u_quantity"] - $objRs->fields["u_instock"],
							   ($objRs->fields["price"]/$objRs->fields["u_numperuompu"]) * ($objRs->fields["u_quantity"] - $objRs->fields["u_instock"]),
							   "7210400"
							  )
										);
					$row0++;
				} else {
					$objExcel->writerange("Initial Quantity - Batches",getCellAddress(1,$row1).":".getCellAddress(11,$row1)  ,
						array("1",
							   $objRs->fields["u_itemcode"],
							   $objRs->fields["u_itemdesc"],
							   $objRs->fields["u_uom"],
							   $objRs->fields["price"]/$objRs->fields["u_numperuompu"],
							   0,
							    $objRs->fields["u_quantity"] - $objRs->fields["u_instock"],
							   ($objRs->fields["price"]/$objRs->fields["u_numperuompu"]) * ($objRs->fields["u_quantity"] - $objRs->fields["u_instock"]),
							   "NONE",
							   "7210400",
							   ""
							  )
										);
					$row1++;
				}
			}
		}		
		
		
		$uploadpath = realpath("../Addons/GPS/HIS Add-On/UserPrograms/");
		$uploadpath .= "Export\\";
		mkdirr($uploadpath);
		$objExcel->saveas($uploadpath .session_id() ,"","xls") ; 
			
		$objExcel->closeXL();
		unset($objExcel);		
		$_SESSION["dbmodified"] = 1;
		
	}
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $u_trxtype;
	global $postdata;
	global $companydata;
	$page->objectdoctype = "GOODSISSUE";
	$u_trxtype = $page->getitemstring("u_trxtype");
	$postdata["u_fromdepartment"] = $page->getitemstring("u_fromdepartment");
	$postdata["u_todepartment"] = $page->getitemstring("u_todepartment");
	$postdata["u_type"] = $page->getitemstring("u_type");
	$postdata["u_cos"] = $page->getitemstring("u_cos");
	$postdata["u_cosstartdate"] = $page->getitemstring("u_cosstartdate");
	$postdata["u_cosenddate"] = $page->getitemstring("u_cosenddate");
	$postdata["u_docdate"] = $page->getitemstring("u_docdate");
	
	$companydata = getcurrentcompanydata("PRSUPPNO,PURCHASINGPRICELIST");
	/*
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select u_stocklink from u_hissetup");
	if ($objRs->queryfetchrow()) {
		if ($objRs->fields[0]==0) {
			header ('Location: accessdenied.php?&requestorId=$requestorId&messageText=This page is only available if Link to Stock was check in the settings.'); 
		}
	}
	*/
	
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $objConnection;
	global $page;
	global $userdepartment;
	global $userreftype;
	global $u_trxtype;
	global $postdata;
	global $company;
	global $branch;
	global $objGrids;
	global $companydata;
	$u_hissetupdata = getu_hissetup("U_DFLTPHARMADEPT, U_DFLTCSRDEPT, U_DFLTLABDEPT, U_DFLTRADIODEPT, U_DFLTPHARMATFS, U_DFLTWAREHOUSEDEPT, U_DFLTIPD, U_DFLTOPD, U_DFLTHOUSEKEEPINGDEPT, U_DFLTHEARTSTATIONDEPT, U_DFLTDIETARYDEPT, U_STOCKLINK");
	
	$page->setitem("u_docdate",currentdate());
	$page->setitem("u_trxtype",$u_trxtype);
	if ($postdata["u_type"]) {
		$page->setitem("u_docdate",$postdata["u_docdate"]);
		$page->setitem("u_fromdepartment",$postdata["u_fromdepartment"]);
		$page->setitem("u_todepartment",$postdata["u_todepartment"]);
		$page->setitem("u_cos",$postdata["u_cos"]);
		$page->setitem("u_type",$postdata["u_type"]);
		$page->setitem("u_cosstartdate",$postdata["u_cosstartdate"]);
		$page->setitem("u_cosenddate",$postdata["u_cosenddate"]);
	} else {	
		if ($userdepartment!="") $page->setitem("u_fromdepartment",$userdepartment);
		elseif ($page->getitemstring("u_trxtype")=="PHARMACY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTPHARMADEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="LABORATORY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTLABDEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="RADIOLOGY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTRADIODEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="HEARTSTATION") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTHEARTSTATIONDEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="CSR") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTCSRDEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="IP") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTIPD"]);
		elseif ($page->getitemstring("u_trxtype")=="OP") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTOPD"]);
		elseif ($page->getitemstring("u_trxtype")=="WAREHOUSE") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTWAREHOUSEDEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="DIETARY") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTDIETARYDEPT"]);
		elseif ($page->getitemstring("u_trxtype")=="HOUSEKEEPING") $page->setitem("u_fromdepartment",$u_hissetupdata["U_DFLTHOUSEKEEPINGDEPT"]);
	
		if ($page->getitemstring("u_trxtype")!="WAREHOUSE") {
			$page->setitem("u_todepartment",$page->getitemstring("u_fromdepartment"));
		}	
	}
	
	$objGrids[0]->clear();
	$u_totalamount=0;	
	$objRs = new recordset(null,$objConnection);
	$itemclassExp = "";
	if ($page->getitemstring("u_cos")=="1") {
	$objRs->setdebug();
	$sql = "select u_itemdesc, u_itemcode, i.u_uom, i.u_manageby, pl.price, i.u_numperuompu, sum(u_quantity) as u_quantity, sum(u_instock) as u_instock from (
	select b.u_itemcode, b.u_itemdesc, 0 as u_instock, b.u_quantity, b.u_uom from u_hischarges a, u_hischargeitems b, u_hisitems c where c.code=b.u_itemcode and c.u_type='MEDSUP' and c.u_isstock=1 and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and a.u_department='".$page->getitemstring("u_fromdepartment")."' and a.u_startdate>='".$page->getitemdate("u_cosstartdate")."' and a.u_startdate<='".$page->getitemdate("u_cosenddate")."' and a.docstatus not in ('CN')  union all select b.u_itemcode, b.u_itemdesc, 0 as u_instock, b.u_quantity, b.u_uom from u_hischarges a, u_hischargeitempacks b, u_hisitems c where c.code=b.u_itemcode and c.u_type='MEDSUP' and c.u_isstock=1 and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and a.u_department='".$page->getitemstring("u_fromdepartment")."' and a.u_startdate>='".$page->getitemdate("u_cosstartdate")."' and a.u_startdate<='".$page->getitemdate("u_cosenddate")."' and a.docstatus not in ('CN') union all select b.u_itemcode, b.u_itemdesc, 0 as u_instock, b.u_quantity*-1 as u_quantity, b.u_uom from u_hiscredits a, u_hiscredititems b, u_hisitems c where c.code=b.u_itemcode and c.u_type='MEDSUP' and c.u_isstock=1 and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and a.u_department='".$page->getitemstring("u_fromdepartment")."' and a.u_startdate>='".$page->getitemdate("u_cosstartdate")."' and a.u_startdate<='".$page->getitemdate("u_cosenddate")."' and a.docstatus not in ('CN') union all select b.u_itemcode, b.u_itemdesc, 0 as u_instock, b.u_quantity*-1 as u_quantity, b.u_uom from u_hiscredits a, u_hiscredititempacks b, u_hisitems c where c.code=b.u_itemcode and c.u_type='MEDSUP' and c.u_isstock=1 and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and a.u_department='".$page->getitemstring("u_fromdepartment")."' and a.u_startdate>='".$page->getitemdate("u_cosstartdate")."' and a.u_startdate<='".$page->getitemdate("u_cosenddate")."' and a.docstatus not in ('CN')";
	
		$sql .= " union all
	select a.itemcode as u_itemcode, b.name as u_itemdesc, a.instockqty as u_instock, 0 as u_quantity, b.u_uom from stockcardsummary a, u_hisitems b where b.code=a.itemcode and a.company='$company' and a.branch='$branch' and a.warehouse='".$page->getitemstring("u_fromdepartment")."' and a.instockqty >0
		) x inner join u_hisitems i on i.code=x.u_itemcode and i.u_active=1 
			left join itempricelists pl on pl.itemcode=x.u_itemcode and pl.pricelist='". $companydata["PURCHASINGPRICELIST"]."' group by x.u_itemcode order by x.u_itemdesc";
			
		$objRs->queryopen($sql);
		
		while ($objRs->queryfetchrow("NAME")) {
			if ($objRs->fields["u_quantity"]!=0) {
				$price = $objRs->fields["price"]/$objRs->fields["u_numperuompu"];
				$objGrids[0]->addrow();
				$objGrids[0]->setitem(null,"u_itemcode",$objRs->fields["u_itemcode"]);
				$objGrids[0]->setitem(null,"u_itemdesc",$objRs->fields["u_itemdesc"]);
				$objGrids[0]->setitem(null,"u_uom",$objRs->fields["u_uom"]);
				$objGrids[0]->setitem(null,"u_todepartment",$page->getitemstring("u_todepartment"));
				$objGrids[0]->setitem(null,"u_reqqty",formatNumericQuantity(0));
				$objGrids[0]->setitem(null,"u_quantity",formatNumericQuantity($objRs->fields["u_quantity"]));
				$objGrids[0]->setitem(null,"u_instock",formatNumericQuantity($objRs->fields["u_instock"]));
			
				$objGrids[0]->setitem(null,"u_unitprice",formatNumericPrice($price));
				$objGrids[0]->setitem(null,"u_price",formatNumericPrice($price));
				$objGrids[0]->setitem(null,"u_linetotal",formatNumericAmount($objRs->fields["u_quantity"]*$price));
				$u_totalamount+=($objRs->fields["u_quantity"]*$price);	
			}
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

$page->objectdoctype = "GOODSISSUE";

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_cosstartdate","Calendar");
$page->businessobject->items->setcfl("u_cosenddate","Calendar");
$page->businessobject->items->setcfl("u_requestno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_totalamount",false);

$objGrids[0]->columntitle("u_todepartment","To Section");
$objGrids[0]->columntitle("u_qtypu","Qty (PU)");
$objGrids[0]->columnwidth("u_itemcode",15);
$objGrids[0]->columnwidth("u_itemdesc",30);
$objGrids[0]->columnwidth("u_whscode",15);
$objGrids[0]->columnwidth("u_quantity",8);
$objGrids[0]->columnwidth("u_qtypu",8);
$objGrids[0]->columnwidth("u_numperuompu",8);
$objGrids[0]->columnwidth("u_uom",5);
$objGrids[0]->columnwidth("u_uompu",5);
$objGrids[0]->columnwidth("u_todepartment",20);
$objGrids[0]->columncfl("u_itemcode","OpenCFLfs()");
$objGrids[0]->columncfl("u_itemdesc","OpenCFLfs()");
$objGrids[0]->columndataentry("u_todepartment","options",array("loadudflinktable",iif($page->getitemstring("u_trxtype")=="WAREHOUSE","warehouses:warehouse:warehousename","warehouses:warehouse:warehousename:u_type='".$page->getitemstring("u_trxtype")."'"),":"));
//$objGrids[0]->columncfl("u_itemcode","OpenCFLitems()");
//$objGrids[0]->columncfl("u_itemdesc","OpenCFLitems()");
$objGrids[0]->columninput("u_quantity","type","text");
$objGrids[0]->columninput("u_qtypu","type","text");
$objGrids[0]->columnattributes("u_uom","disabled");
//$objGrids[0]->columnattributes("u_itemdesc","disabled");
$objGrids[0]->columnattributes("u_instock","disabled");
$objGrids[0]->columnattributes("u_reqqty","disabled");
$objGrids[0]->columnattributes("u_linetotal","disabled");
$objGrids[0]->columnattributes("u_todepartment","disabled");
$objGrids[0]->columnvisibility("u_price",false);
$objGrids[0]->columnvisibility("u_whscode",false);
//$objGrids[0]->columnvisibility("u_todepartment",false);

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
	if ($userdepartment!="") $page->businessobject->items->seteditable("u_fromdepartment",false);
}


$addoptions = false;
$deleteoption = false;
$closeoption = false;
$canceloption = false;
?> 

