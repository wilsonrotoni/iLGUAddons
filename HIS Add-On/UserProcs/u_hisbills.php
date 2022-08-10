<?php
 include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
 include_once("../Addons/GPS/HIS Add-On/UserProcs/sls/u_hishealthins.php");
 
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
$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

$postdata = array();

$page->privatedata["reftypetobill"] = "";
$page->privatedata["refnotobill"] = "";


$trxrunbal=0;
$sectionrunbal=0;

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $postdata;
	if ($page->getitemstring("u_docdate")!=""){
		$postdata["u_docdate"] = $page->getitemstring("u_docdate");
		$postdata["u_doctime"] = $page->getitemstring("u_doctime");
	} else {
		$postdata["u_docdate"] = currentdate();
		$postdata["u_doctime"] = currenttime();
	}	
	$postdata["u_reftype"] = $page->getitemstring("u_reftype");
	$postdata["u_refno"] = $page->getitemstring("u_refno");
	if ($postdata["u_refno"]!="") {
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("select DOCNO from u_hisbills where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_reftype='".$postdata["u_reftype"]."' and u_refno='".$postdata["u_refno"]."' and docstatus not in ('CN')");	
		if ($objRs->queryfetchrow("NAME")) {
			$page->setkey($objRs->fields["DOCNO"]);
			modeEdit();
			return false;	
		}
	}		
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $objConnection;
	global $page;
	global $postdata;
	global $objGrids;
	global $objGridSummaryBySection;
	$u_hissetupdata = getu_hissetup("U_BILLASONE");
	$page->setitem("u_billasone",$u_hissetupdata["U_BILLASONE"]);
	$page->setitem("u_docdate",currentdate());
	$page->setitem("u_doctime",currenttime());
	
	$sectiondata = array();
	$feedata = array();
	$trxdata = array();
	
	$page->privatedata["docstatus"] = "";
	
	$page->privatedata["reftypetobill"] = "";
	$page->privatedata["refnotobill"] = "";
	
	if ($postdata["u_reftype"]!="") {
		if ($postdata["u_docdate"]!="") $page->setitem("u_docdate",$postdata["u_docdate"]);
		if ($postdata["u_doctime"]!="") $page->setitem("u_doctime",$postdata["u_doctime"]);
		$page->setitem("u_reftype",$postdata["u_reftype"]);
		$page->setitem("u_refno",$postdata["u_refno"]);
		$page->setitem("docseries","-1");
		
		$objGrids[0]->clear();
		$objGrids[1]->clear();
		$objGrids[2]->clear();
		$objGrids[3]->clear();
		$objGrids[4]->clear();
		$objGrids[5]->clear();
		$objGrids[6]->clear();
		$objGrids[7]->clear();
		$tdpamount=0;
		$tamount=0;
		$tdiscamount=0;
		$tinsamount=0;
		$thmoamount=0;
		$tnetamount=0;
		$tdpamount=0;
		$tdueamount=0;
		$dpamt=0;
		$dpcr=0;
		$dpbal=0;
		$objRs = new recordset(null,$objConnection);
		$objRs2 = new recordset(null,$objConnection);
		
		retrievePatientPendingItemsGPSHIS($postdata["u_reftype"],$postdata["u_refno"]);
		
		/*$objRs->queryopen("select sum(u_totalamount) from u_hispos where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_reftype='".$postdata["u_reftype"]."' and u_refno='".$postdata["u_refno"]."' and u_payreftype='RS' and docstatus not in ('CN')");
		if ($objRs->queryfetchrow()) $tdpamount = $objRs->fields[0];*/
		
		if ($postdata["u_reftype"]=="IP") {
			$objRs->queryopen("select * from u_hisips where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno='".$postdata["u_refno"]."'");
			if ($objRs->queryfetchrow("NAME")) {
				$page->setitem("u_patientid",$objRs->fields["U_PATIENTID"]);
				$page->setitem("u_patientname",$objRs->fields["U_PATIENTNAME"]);
				$page->setitem("u_age",$objRs->fields["U_AGE_Y"]);
				$page->setitem("u_gender",$objRs->fields["U_GENDER"]);
				$page->setitem("u_mgh",$objRs->fields["U_MGH"]);
				$page->setitem("u_icdcode",$objRs->fields["U_ICDCODE"]);
				$page->setitem("u_billcount",$objRs->fields["U_BILLCOUNT"]+1);
				$page->setitem("u_startdate",formatDateToHttp($objRs->fields["U_STARTDATE"]));
				$page->setitem("u_treatment",$objRs->fields["U_TREATMENT"]);
				$page->setitem("u_phicmemberid",$objRs->fields["U_PHICMEMBERID"]);
				$page->setitem("docno",$postdata["u_reftype"] . "-" . $postdata["u_refno"]."-". str_pad( $page->getitemstring("u_billcount"), 2, "0", STR_PAD_LEFT));
				if ($objRs->fields["U_ENDDATE"]!="") $page->setitem("u_docdate",formatDateToHttp($objRs->fields["U_ENDDATE"]));
					
				$docid = $objRs->fields["DOCID"];
				$u_disccode = $objRs->fields["U_DISCCODE"];
				// rooms
				/*
				$objRs->queryopen("select A.U_QUANTITY, A.U_AMOUNT, A.U_ENDDATE, A.U_STARTDATE, A.U_STARTTIME, A.U_ENDDATE, A.U_ENDTIME, A.U_RATEUOM, A.U_RATE, A.U_BEDNO, A.U_ROOMNO, A.U_ISROOMSHARED, B.U_ROOMTYPE from u_hisiprooms A, u_hisrooms B where B.CODE=A.U_ROOMNO and A.company='".$_SESSION["company"]."' and A.branch='".$_SESSION["branch"]."' and A.docid='$docid'");
				while ($objRs->queryfetchrow("NAME")) {
					$quantity = $objRs->fields["U_QUANTITY"];
					$amount = $objRs->fields["U_AMOUNT"];
					if ($objRs->fields["U_ENDDATE"]==0) {
						$startdate = $objRs->fields["U_STARTDATE"];
						$starttime = $objRs->fields["U_STARTTIME"];
						$enddate = $objRs->fields["U_ENDDATE"];
						$endtime = $objRs->fields["U_ENDTIME"];
						if ($enddate=="") {
							$enddate= formatDateToDB($postdata["u_docdate"]);
							$endtime= date('h:i');
						}
						if ($objRs->fields["U_RATEUOM"]=="Hour") {
							$objRs2->queryopen("select CEIL((((TIME_TO_SEC(timediff('".$enddate." ".$endtime."','".$startdate." ".$starttime."'))/60)/60)))");
							if ($objRs2->queryfetchrow()) {
								$quantity = $objRs2->fields[0];
								$amount = $quantity*$objRs->fields["U_RATE"];
							}
						} else {
							//$objRs2->queryopen("select CEIL((((TIME_TO_SEC(timediff('".$enddate." ".$endtime."','".$startdate." ".$starttime."'))/60)/60)/24))");
							$quantity = datedifference("d", $startdate,$enddate);
							$amount = $quantity*$objRs->fields["U_RATE"];
						}	
					} else {
						$startdate = $objRs->fields["U_STARTDATE"];
						$enddate = $objRs->fields["U_ENDDATE"];
					}
					if ($quantity==0) continue;
					$objGrids[0]->addrow();
					$objGrids[0]->setitem(null,"u_startdate",formatDateToHttp($startdate));
					$objGrids[0]->setitem(null,"u_enddate",formatDateToHttp($enddate));
					$objGrids[0]->setitem(null,"u_roomno",$objRs->fields["U_ROOMNO"]);
					$objGrids[0]->setitem(null,"u_roomtype",$objRs->fields["U_ROOMTYPE"]);
					$objGrids[0]->setitem(null,"u_isroomshared",$objRs->fields["U_ISROOMSHARED"]);
					$objGrids[0]->setitem(null,"u_bedno",$objRs->fields["U_BEDNO"]);
					$objGrids[0]->setitem(null,"u_rate",formatNumericPrice($objRs->fields["U_RATE"]));
					$objGrids[0]->setitem(null,"u_rateuom",$objRs->fields["U_RATEUOM"]);
					$objGrids[0]->setitem(null,"u_quantity",formatNumericQuantity($quantity));
					$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($amount));
					$objGrids[0]->setitem(null,"u_discamount",formatNumericAmount(0));
					$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount(0));
					$objGrids[0]->setitem(null,"u_hmoamount",formatNumericAmount(0));
					$objGrids[0]->setitem(null,"u_netamount",formatNumericAmount($amount));
					
					//if (!isset($sectiondata[$objRs->fields["U_DEPARTMENT"]])) $sectiondata[$objRs->fields["U_DEPARTMENT"]]=0;
					//$sectiondata[$objRs->fields["U_DEPARTMENT"]] += $amount;
					if (!isset($sectiondata["Room & Board Charges"])) $sectiondata["Room & Board Charges"]=0;
					$sectiondata["Room & Board Charges"] += $amount;
					if (!isset($feedata["Hospital Fees"])) {
						$feedata["Hospital Fees"]["feetype"] = "HF";
						$feedata["Hospital Fees"]["amount"] = 0;
					}
					$feedata["Hospital Fees"]["amount"] += $amount;
					
					$tamount+=$amount;
					$tdiscamount=0;
					$tinsamount=0;
					$thmoamount=0;
					$tnetamount+=$amount;				
				}
				*/
			}
		} else {
			$objRs->queryopen("select * from u_hisops where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno='".$postdata["u_refno"]."'");
			if ($objRs->queryfetchrow("NAME")) {
				$page->setitem("u_patientid",$objRs->fields["U_PATIENTID"]);
				$page->setitem("u_patientname",$objRs->fields["U_PATIENTNAME"]);
				$page->setitem("u_age",$objRs->fields["U_AGE_Y"]);
				$page->setitem("u_gender",$objRs->fields["U_GENDER"]);
				$page->setitem("u_icdcode",$objRs->fields["U_ICDCODE"]);
				$page->setitem("u_billcount",$objRs->fields["U_BILLCOUNT"]+1);
				$page->setitem("u_startdate",formatDateToHttp($objRs->fields["U_STARTDATE"]));
				$page->setitem("docno",$postdata["u_reftype"] . "-" . $postdata["u_refno"]."-". str_pad( $page->getitemstring("u_billcount"), 2, "0", STR_PAD_LEFT));
				if ($objRs->fields["U_ENDDATE"]!="") $page->setitem("u_docdate",formatDateToHttp($objRs->fields["U_ENDDATE"]));
				
				$docid = $objRs->fields["DOCID"];
				$u_disccode = $objRs->fields["U_DISCCODE"];		
			}	
		} //reftype='".$postdata["u_reftype"]."' and a.u_refno='".$postdata["u_refno"]."'
			//union all select 'CM' as U_DOCTYPE, a.DOCID,a.U_STARTDATE,a.U_STARTTIME,a.DOCNO,a.U_DISCONBILL,a.U_ISSTAT,a.U_DEPARTMENT, a.DOCSTATUS, 0 as U_DUEAMOUNT from u_hiscredits a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$postdata["u_reftype"]."' and a.u_refno='".$postdata["u_refno"]."' and u_prepaid in (0,2) and docstatus not in ('CN') 
		$objRs->queryopen("select 'CHRG' as U_DOCTYPE, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE,a.U_STARTTIME,a.DOCNO,a.U_DISCONBILL,a.U_ISSTAT,a.U_DEPARTMENT, a.DOCSTATUS, 0 as U_AMOUNT, 0 as U_DUEAMOUNT from u_hischarges a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$postdata["u_reftype"]."' and a.u_refno='".$postdata["u_refno"]."' and a.u_prepaid in (0,2) and a.u_billno='' union all select 'ADJ' as U_DOCTYPE, '' AS U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,a.DOCNO,0 AS U_DISCONBILL, 0 AS U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_AMOUNT, a.U_AMOUNT AS U_DUEAMOUNT from u_hispriceadjs a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$postdata["u_reftype"]."' and a.u_refno='".$postdata["u_refno"]."' union all select 'CM' as U_DOCTYPE, a.U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,a.DOCNO,a.U_DISCONBILL, a.U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, if(a.U_PREPAID=1,a.U_AMOUNT,a.U_AMOUNTBEFDISC) as U_AMOUNT, a.U_BALANCE AS U_DUEAMOUNT from u_hiscredits a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$postdata["u_reftype"]."' and a.u_refno='".$postdata["u_refno"]."' union all select 'DP' as U_DOCTYPE, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_DOCDATE AS U_STARTDATE,'' AS U_STARTTIME,a.DOCNO,a.U_DISCONBILL,0 AS  U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_DUEAMOUNT AS U_AMOUNT, a.U_BALANCE AS U_DUEAMOUNT from u_hispos a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$postdata["u_reftype"]."' and a.u_refno='".$postdata["u_refno"]."' and u_prepaid in (2) and docstatus not in ('CN') and u_balance<>0 union all select 'BP' as U_DOCTYPE, '' as U_REQUESTTYPE, 0 as U_PREPAID, a.DOCID,a.U_DOCDATE AS U_STARTDATE,'' AS U_STARTTIME,a.DOCNO, 1 as U_DISCONBILL,0 AS  U_ISSTAT, '' as U_DEPARTMENT, a.DOCSTATUS, a.U_BALANCE AS U_AMOUNT, a.U_BALANCE AS U_DUEAMOUNT from u_hisbills a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$postdata["u_reftype"]."' and a.u_refno='".$postdata["u_refno"]."' and docstatus in ('CN') and u_balance<>0");
		while ($objRs->queryfetchrow("NAME")) {
			$valid = $objRs->fields["DOCSTATUS"]!="CN";
			if ($objRs->fields["U_DOCTYPE"]=="CHRG") {
				$objRs2->queryopen("select b.U_TYPE, ifnull(b.U_GROUP,'ROOM') as U_GROUP, b.U_CLASS, a.U_ISSTAT,c.ITEMCLASSNAME, a.U_ITEMCODE,a.U_ITEMDESC, ifnull(b.U_BILLDISCOUNT,d.U_BILLDISCOUNT) as U_BILLDISCOUNT, a.U_QUANTITY,a.U_QUANTITY-a.U_RTQTY as U_NETQTY,a.U_UNITPRICE,a.U_UNITPRICE+a.U_ADJPRICE as U_ADJUNITPRICE, a.U_STATUNITPRICE, a.U_STATUNITPRICE+a.U_ADJPRICE as U_ADJSTATUNITPRICE, a.U_DISCAMOUNT, a.U_PRICE, a.U_PRICE+a.U_ADJPRICE as U_ADJPRICE,b.U_DEPARTMENT,a.U_DOCTORID from u_hischargeitems a left join u_hisitems b on b.code=a.u_itemcode left join u_hisroomtypes d on d.code=a.u_itemcode left join itemclasses c on c.itemclass=b.u_class where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='".$objRs->fields["DOCID"]."'");
				while ($objRs2->queryfetchrow("NAME")) {
					$valid = $objRs->fields["DOCSTATUS"]!="CN";
					if ($valid && $objRs2->fields["U_NETQTY"]==0) $valid=false;
					/*if ($objRs->fields["DOCNO"]=="CI000040") {
						var_dump(array($valid,$objRs2->fields["U_ITEMDESC"],$objRs2->fields["U_NETQTY"]));
					}*/
					if ($objRs->fields["U_DISCONBILL"]=="0" || $objRs2->fields["U_GROUP"]=="PRF" || $objRs2->fields["U_GROUP"]=="PRM") {
						$objRs2->fields["U_BILLDISCOUNT"] = 0;
					}
					switch ($objRs2->fields["U_TYPE"]) {
						case "MEDSUP":
							if ($valid) {
								$objGrids[3]->addrow();
								$objGrids[3]->setitem(null,"u_date",formatDateToHttp($objRs->fields["U_STARTDATE"]));
								$objGrids[3]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);
								$objGrids[3]->setitem(null,"u_itemcode",$objRs2->fields["U_ITEMCODE"]);
								$objGrids[3]->setitem(null,"u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
								$objGrids[3]->setitem(null,"u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
								$objGrids[3]->setitem(null,"u_quantity",formatNumericAmount($objRs2->fields["U_NETQTY"]));
							}	
							$comdisc=0;
							if ($objRs->fields["U_DISCONBILL"]=="0") {
								if ($valid) {
									$objGrids[3]->setitem(null,"u_comdisc",formatNumericAmount($objRs2->fields["U_DISCAMOUNT"]));
									$objGrids[3]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_ADJPRICE"]));
								}	
								$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
								$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
								$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
							} else {
								if ($valid) $objGrids[3]->setitem(null,"u_comdisc",formatNumericAmount(0));
								if ($objRs2->fields["U_ISSTAT"]=="0") {
									if ($valid) $objGrids[3]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_ADJUNITPRICE"]));
									$netamount = roundAmount($objRs2->fields["U_ADJUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
									$amount = roundAmount($objRs2->fields["U_UNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
								} else {
									if ($valid)$objGrids[3]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_ADJSTATUNITPRICE"]));
									$netamount = roundAmount($objRs2->fields["U_ADJSTATUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
									$amount = roundAmount($objRs2->fields["U_STATUNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
								}	
							}
							if ($valid) {
								$objGrids[3]->setitem(null,"u_amount",formatNumericAmount($netamount));
								$objGrids[3]->setitem(null,"u_discamount",formatNumericAmount(0));
								$objGrids[3]->setitem(null,"u_insamount",formatNumericAmount(0));
								$objGrids[3]->setitem(null,"u_hmoamount",formatNumericAmount(0));
								$objGrids[3]->setitem(null,"u_netamount",formatNumericAmount($netamount));
							}
							if (!isset($trxdata["CHRG:".$objRs->fields["DOCNO"]])) {
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctype"] = "CHRG";
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdesc"] = "Charges" . iif($objRs->fields["DOCSTATUS"]=="CN"," - Cancelled","");
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docstatus"] = $objRs->fields["DOCSTATUS"];
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] = 0;
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] = 0;
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
							}
							if ($objRs->fields["DOCNO"]=="CI000777") {
								$page->console->insertVar(array($objRs2->fields["U_GROUP"],$objRs2->fields["U_ITEMDESC"],$amount));
							}
							$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] += $amount;
							if ($valid) $trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] += $netamount;
							$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] += $comdisc;
							
							if ($valid) {
								if (!isset($sectiondata[$objRs->fields["U_DEPARTMENT"].";".$objRs2->fields["U_DEPARTMENT"]])) $sectiondata[$objRs->fields["U_DEPARTMENT"].";".$objRs2->fields["U_DEPARTMENT"]]=0;
								$sectiondata[$objRs->fields["U_DEPARTMENT"].";".$objRs2->fields["U_DEPARTMENT"]] += $netamount;
								
								if (!isset($feedata["Hospital Fees"])) {
									$feedata["Hospital Fees"]["feetype"] = "HF";
									$feedata["Hospital Fees"]["amount"] = 0;
								}
								$feedata["Hospital Fees"]["amount"] += $netamount;
							}	
							break;
						case "EXAM":
							if ($valid) {
								$objGrids[1]->addrow();
								$objGrids[1]->setitem(null,"u_date",formatDateToHttp($objRs->fields["U_STARTDATE"]));
								$objGrids[1]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);
								$objGrids[1]->setitem(null,"u_type",$objRs2->fields["U_ITEMCODE"]);
								$objGrids[1]->setitem(null,"u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
							}	
							$comdisc=0;
							if ($objRs->fields["U_DISCONBILL"]=="0") {
								if ($valid) {
									$objGrids[1]->setitem(null,"u_comdisc",formatNumericAmount($objRs2->fields["U_DISCAMOUNT"]));
									$objGrids[1]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_ADJPRICE"]));
								}	
								$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
								$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
								$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
							} else {
								if ($valid) $objGrids[1]->setitem(null,"u_comdisc",formatNumericAmount(0));
								if ($objRs2->fields["U_ISSTAT"]=="0") {
									if ($valid) $objGrids[1]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_ADJUNITPRICE"]));
									$amount = roundAmount($objRs2->fields["U_UNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
									$netamount = roundAmount($objRs2->fields["U_ADJUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
								} else {
									if ($valid) $objGrids[1]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_ADJSTATUNITPRICE"]));
									$amount = roundAmount($objRs2->fields["U_STATUNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
									$netamount = roundAmount($objRs2->fields["U_ADJSTATUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
								}	
							}
							if ($valid) {
								$objGrids[1]->setitem(null,"u_amount",formatNumericAmount($netamount));
								$objGrids[1]->setitem(null,"u_discamount",formatNumericAmount(0));
								$objGrids[1]->setitem(null,"u_insamount",formatNumericAmount(0));
								$objGrids[1]->setitem(null,"u_hmoamount",formatNumericAmount(0));
								$objGrids[1]->setitem(null,"u_netamount",formatNumericAmount($netamount));
							}							
							if ($objRs->fields["DOCNO"]=="CI000777") {
								$page->console->insertVar(array($objRs2->fields["U_GROUP"],$objRs2->fields["U_ITEMDESC"],$amount));
							}
							if (!isset($trxdata["CHRG:".$objRs->fields["DOCNO"]])) {
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctype"] = "CHRG";
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdesc"] = "Charges" . iif($objRs->fields["DOCSTATUS"]=="CN"," - Cancelled","");
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docstatus"] = $objRs->fields["DOCSTATUS"];
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] = 0;
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] = 0;
								$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
							}
							$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] += $amount;
							if ($valid) $trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] += $netamount;
							$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] += $comdisc;
							
							if ($valid) {
								if (!isset($sectiondata[$objRs->fields["U_DEPARTMENT"]])) $sectiondata[$objRs->fields["U_DEPARTMENT"]]=0;
								$sectiondata[$objRs->fields["U_DEPARTMENT"]] += $netamount;
								if (!isset($feedata["Hospital Fees"])) {
									$feedata["Hospital Fees"]["feetype"] = "HF";
									$feedata["Hospital Fees"]["amount"] = 0;
								}
								$feedata["Hospital Fees"]["amount"] += $netamount;
							}	
							break;
						default:
							
							switch ($objRs2->fields["U_GROUP"]) {
								case "ROOM":
									$amount = $objRs2->fields["U_PRICE"];
									$comdisc = 0;
									$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
									if ($valid) {
										$objGrids[0]->addrow();
										$objGrids[0]->setitem(null,"u_startdate",formatDateToHttp($objRs->fields["U_STARTDATE"]));
										$objGrids[0]->setitem(null,"u_enddate",formatDateToHttp($objRs->fields["U_STARTDATE"]));
										$objGrids[0]->setitem(null,"u_roomno","");
										$objGrids[0]->setitem(null,"u_roomtype",$objRs2->fields["U_ITEMCODE"]);
										$objGrids[0]->setitem(null,"u_isroomshared",0);
										$objGrids[0]->setitem(null,"u_roomdesc",$objRs2->fields["U_ITEMDESC"]);
										$objGrids[0]->setitem(null,"u_comdisc",formatNumericAmount($comdisc));
										$objGrids[0]->setitem(null,"u_rate",formatNumericPrice($objRs2->fields["U_ADJPRICE"]));
										$objGrids[0]->setitem(null,"u_rateuom","");
										$objGrids[0]->setitem(null,"u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
										$objGrids[0]->setitem(null,"u_quantity",formatNumericQuantity(1));
										$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs2->fields["U_ADJPRICE"]));
										$objGrids[0]->setitem(null,"u_discamount",formatNumericAmount(0));
										$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount(0));
										$objGrids[0]->setitem(null,"u_hmoamount",formatNumericAmount(0));
										$objGrids[0]->setitem(null,"u_netamount",formatNumericAmount($objRs2->fields["U_ADJPRICE"]));
									}
									//if (!isset($sectiondata[$objRs->fields["U_DEPARTMENT"]])) $sectiondata[$objRs->fields["U_DEPARTMENT"]]=0;
									//$sectiondata[$objRs->fields["U_DEPARTMENT"]] += $amount;
									
									if (!isset($trxdata["CHRG:".$objRs->fields["DOCNO"]])) {
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctype"] = "CHRG";
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdesc"] = "Charges" . iif($objRs->fields["DOCSTATUS"]=="CN"," - Cancelled","");
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docstatus"] = $objRs->fields["DOCSTATUS"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] = 0;
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] = 0;
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
									}
									$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] += $amount;
									if ($valid) $trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] += $netamount;
									$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] += $comdisc;
									
									if ($valid) {
										if (!isset($sectiondata["Room & Board Charges"])) $sectiondata["Room & Board Charges"]=0;
										$sectiondata["Room & Board Charges"] += $netamount;
										if (!isset($feedata["Hospital Fees"])) {
											$feedata["Hospital Fees"]["feetype"] = "HF";
											$feedata["Hospital Fees"]["amount"] = 0;
										}
										$feedata["Hospital Fees"]["amount"] += $netamount;
									}
									break;
								case "PRC":
									if ($valid) {
										$objGrids[5]->addrow();
										$objGrids[5]->setitem(null,"u_date",formatDateToHttp($objRs->fields["U_STARTDATE"]));
										$objGrids[5]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);
										$objGrids[5]->setitem(null,"u_roomno",substr($objRs2->fields["U_CLASS"],4));
										$objGrids[5]->setitem(null,"u_roomdesc",$objRs2->fields["ITEMCLASSNAME"]);
										$objGrids[5]->setitem(null,"u_roomtype",substr($objRs2->fields["U_CLASS"],4));
										$objGrids[5]->setitem(null,"u_bedno",substr($objRs2->fields["U_CLASS"],4));
										$objGrids[5]->setitem(null,"u_itemcode",$objRs2->fields["U_ITEMCODE"]);
										$objGrids[5]->setitem(null,"u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
										$objGrids[5]->setitem(null,"u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
										$objGrids[5]->setitem(null,"u_rateuom","");
										$objGrids[5]->setitem(null,"u_quantity",formatNumericQuantity($objRs2->fields["U_NETQTY"]));
									}	
									$comdisc=0;
									/*
									if ($objRs->fields["U_DISCONBILL"]=="0") {
										if ($valid) {
											$objGrids[4]->setitem(null,"u_comdisc",formatNumericAmount($objRs2->fields["U_DISCAMOUNT"]));
											$objGrids[4]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_PRICE"]));
										}	
										$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
										$netamount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_NETQTY"]);
										$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
									} else {
										if ($valid) $objGrids[4]->setitem(null,"u_comdisc",formatNumericAmount(0));
										if ($objRs->fields["U_ISSTAT"]=="0") {
											if ($valid) $objGrids[4]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_UNITPRICE"]));
											$amount = roundAmount($objRs2->fields["U_UNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
											$netamount = roundAmount($objRs2->fields["U_UNITPRICE"]*$objRs2->fields["U_NETQTY"]);
										} else {
											if ($valid) $objGrids[4]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_STATUNITPRICE"]));
											$amount = roundAmount($objRs2->fields["U_STATUNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
											$netamount = roundAmount($objRs2->fields["U_STATUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
										}	
									}
									
									*/
									if ($objRs->fields["U_DISCONBILL"]=="0") {
										if ($valid) $objGrids[5]->setitem(null,"u_comdisc",formatNumericAmount($objRs2->fields["U_DISCAMOUNT"]));
										if ($valid) $objGrids[5]->setitem(null,"u_rate",formatNumericPrice($objRs2->fields["U_ADJPRICE"]));
										$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
										$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
										$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
									} else {
										if ($valid) $objGrids[5]->setitem(null,"u_comdisc",formatNumericAmount(0));
										if ($objRs2->fields["U_ISSTAT"]=="0") {
											if ($valid) $objGrids[5]->setitem(null,"u_rate",formatNumericPrice($objRs2->fields["U_ADJPRICE"]));
											$amount = roundAmount($objRs2->fields["U_UNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
											$netamount = roundAmount($objRs2->fields["U_ADJUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
										} else {
											if ($valid) $objGrids[5]->setitem(null,"u_rate",formatNumericPrice($objRs2->fields["U_ADJSTATUNITPRICE"]));
											$amount = roundAmount($objRs2->fields["U_STATUNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
											$netamount = roundAmount($objRs2->fields["U_ADJSTATUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
										}	
									}
									
									if ($valid) {
										$objGrids[5]->setitem(null,"u_amount",formatNumericAmount($netamount));
										$objGrids[5]->setitem(null,"u_discamount",formatNumericAmount(0));
										$objGrids[5]->setitem(null,"u_insamount",formatNumericAmount(0));
										$objGrids[5]->setitem(null,"u_hmoamount",formatNumericAmount(0));
										$objGrids[5]->setitem(null,"u_netamount",formatNumericAmount($netamount));
									}
									if (!isset($trxdata["CHRG:".$objRs->fields["DOCNO"]])) {
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctype"] = "CHRG";
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdesc"] = "Charges" . iif($objRs->fields["DOCSTATUS"]=="CN"," - Cancelled","");
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docstatus"] = $objRs->fields["DOCSTATUS"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] = 0;
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] = 0;
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
									}
									
									if ($objRs->fields["DOCNO"]=="CI000777") {
										$page->console->insertVar(array($objRs2->fields["U_GROUP"],$objRs2->fields["U_ITEMDESC"],$amount));
									}									
									
									$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] += $amount;
									if ($valid) $trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] += $netamount;
									$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] += $comdisc;
									
									if ($valid) {
										if (!isset($sectiondata[$objRs->fields["U_DEPARTMENT"]])) $sectiondata[$objRs->fields["U_DEPARTMENT"]]=0;
										$sectiondata[$objRs->fields["U_DEPARTMENT"]] += $netamount;
										if (!isset($feedata["Hospital Fees"])) {
											$feedata["Hospital Fees"]["feetype"] = "HF";
											$feedata["Hospital Fees"]["amount"] = 0;
										}
										$feedata["Hospital Fees"]["amount"] += $netamount;
									}	
									break;
								case "PRF":
									if ($valid) {
										$objGrids[2]->addrow();
										$objGrids[2]->setitem(null,"u_date",formatDateToHttp($objRs->fields["U_STARTDATE"]));
										$objGrids[2]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);
										$objGrids[2]->setitem(null,"u_doctorid",$objRs2->fields["U_DOCTORID"]);
										$objGrids[2]->setitem(null,"u_itemcode",$objRs2->fields["U_ITEMCODE"]);
										$objGrids[2]->setitem(null,"u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
										$objGrids[2]->setitem(null,"u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
										$objGrids[2]->setitem(null,"u_surgeonfee",formatNumericAmount($objRs->fields["U_SURGEONFEE"]));
										$objGrids[2]->setitem(null,"u_comdisc",formatNumericAmount($objRs2->fields["U_DISCAMOUNT"]));
									}	
									$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
									$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
									$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
									if ($valid) {
										$objGrids[2]->setitem(null,"u_amount",formatNumericAmount($netamount));
										$objGrids[2]->setitem(null,"u_discamount",formatNumericAmount(0));
										$objGrids[2]->setitem(null,"u_insamount",formatNumericAmount(0));
										$objGrids[2]->setitem(null,"u_hmoamount",formatNumericAmount(0));
										$objGrids[2]->setitem(null,"u_netamount",formatNumericAmount($netamount));
									}					
									if (!isset($trxdata["CHRG:".$objRs->fields["DOCNO"]])) {
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctype"] = "CHRG";
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdesc"] = "Charges" . iif($objRs->fields["DOCSTATUS"]=="CN"," - Cancelled","");
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docstatus"] = $objRs->fields["DOCSTATUS"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] = 0;
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] = 0;
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
									}
									$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] += $amount;
									if ($valid) $trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] += $netamount;
									$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] += $comdisc;
					
									if ($valid) {				
										if (!isset($sectiondata[$objRs->fields["U_DEPARTMENT"]])) $sectiondata[$objRs->fields["U_DEPARTMENT"]]=0;
										$sectiondata[$objRs->fields["U_DEPARTMENT"]] += $netamount;
										if (!isset($feedata[$objRs->fields["U_DOCTORID"]])) {
											$feedata[$objRs->fields["U_DOCTORID"]]["feetype"] = "PF";
											$feedata[$objRs->fields["U_DOCTORID"]]["amount"] = 0;
											$feedata[$objRs->fields["U_DOCTORID"]]["amount2"] = 0;
										}
										$feedata[$objRs2->fields["U_DOCTORID"]]["amount"] += $netamount;
									}	
									break;
								case "PRM":
									if ($valid) {
										$objGrids[2]->addrow();
										$objGrids[2]->setitem(null,"u_date",formatDateToHttp($objRs->fields["U_STARTDATE"]));
										$objGrids[2]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);
										$objGrids[2]->setitem(null,"u_doctorid",$objRs2->fields["U_DOCTORID"]);
										$objGrids[2]->setitem(null,"u_itemcode",$objRs2->fields["U_ITEMCODE"]);
										$objGrids[2]->setitem(null,"u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
										$objGrids[2]->setitem(null,"u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
										$objGrids[2]->setitem(null,"u_surgeonfee",formatNumericAmount(0));
										$objGrids[2]->setitem(null,"u_comdisc",formatNumericAmount($objRs2->fields["U_DISCAMOUNT"]));
									}	
									$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
									$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
									$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
									if ($valid) {
										$objGrids[2]->setitem(null,"u_amount",formatNumericAmount($netamount));
										$objGrids[2]->setitem(null,"u_discamount",formatNumericAmount(0));
										$objGrids[2]->setitem(null,"u_insamount",formatNumericAmount(0));
										$objGrids[2]->setitem(null,"u_hmoamount",formatNumericAmount(0));
										$objGrids[2]->setitem(null,"u_netamount",formatNumericAmount($netamount));
									}	
									
									if (!isset($trxdata["CHRG:".$objRs->fields["DOCNO"]])) {
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctype"] = "CHRG";
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdesc"] = "Charges" . iif($objRs->fields["DOCSTATUS"]=="CN"," - Cancelled","");
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docstatus"] = $objRs->fields["DOCSTATUS"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] = 0;
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] = 0;
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
									}
									$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] += $amount;
									if ($valid) $trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] += $netamount;
									$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] += $comdisc;
									
									if ($valid) {
										if (!isset($sectiondata[$objRs->fields["U_DEPARTMENT"]])) $sectiondata[$objRs->fields["U_DEPARTMENT"]]=0;
										$sectiondata[$objRs->fields["U_DEPARTMENT"]] += $netamount;
										$feedata[$objRs2->fields["U_DOCTORID"]]["amount2"] += $netamount;
									}	
									break;
								default:		
									if ($valid) {
										$objGrids[4]->addrow();
										$objGrids[4]->setitem(null,"u_date",formatDateToHttp($objRs->fields["U_STARTDATE"]));
										$objGrids[4]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);
										$objGrids[4]->setitem(null,"u_itemcode",$objRs2->fields["U_ITEMCODE"]);
										$objGrids[4]->setitem(null,"u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
										$objGrids[4]->setitem(null,"u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
										$objGrids[4]->setitem(null,"u_quantity",formatNumericAmount($objRs2->fields["U_NETQTY"]));
									}	
									$comdisc=0;
									if ($objRs->fields["U_DISCONBILL"]=="0") {
										if ($valid) {
											$objGrids[4]->setitem(null,"u_comdisc",formatNumericAmount($objRs2->fields["U_DISCAMOUNT"]));
											$objGrids[4]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_ADJPRICE"]));
										}	
										$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
										$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
										$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
									} else {
										if ($valid) $objGrids[4]->setitem(null,"u_comdisc",formatNumericAmount(0));
										if ($objRs2->fields["U_ISSTAT"]=="0") {
											if ($valid) $objGrids[4]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_ADJUNITPRICE"]));
											$amount = roundAmount($objRs2->fields["U_UNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
											$netamount = roundAmount($objRs2->fields["U_ADJUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
										} else {
											if ($valid) $objGrids[4]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["U_ADJSTATUNITPRICE"]));
											$amount = roundAmount($objRs2->fields["U_STATUNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
											$netamount = roundAmount($objRs2->fields["U_ADJSTATUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
										}	
									}
									if ($objRs->fields["DOCNO"]=="CI000777") {
										$page->console->insertVar(array($objRs2->fields["U_GROUP"],$objRs2->fields["U_ITEMDESC"],$amount));
									}
									
									if ($valid) {
										$objGrids[4]->setitem(null,"u_amount",formatNumericAmount($netamount));
										$objGrids[4]->setitem(null,"u_discamount",formatNumericAmount(0));
										$objGrids[4]->setitem(null,"u_insamount",formatNumericAmount(0));
										$objGrids[4]->setitem(null,"u_hmoamount",formatNumericAmount(0));
										$objGrids[4]->setitem(null,"u_netamount",formatNumericAmount($netamount));
									}
										
									if (!isset($trxdata["CHRG:".$objRs->fields["DOCNO"]])) {
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["doctype"] = "CHRG";
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docdesc"] = "Charges" . iif($objRs->fields["DOCSTATUS"]=="CN"," - Cancelled","");
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["docstatus"] = $objRs->fields["DOCSTATUS"];
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] = 0;
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] = 0;
										$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
									}
									$trxdata["CHRG:".$objRs->fields["DOCNO"]]["amount"] += $amount;
									if ($valid) $trxdata["CHRG:".$objRs->fields["DOCNO"]]["balance"] += $netamount;
									$trxdata["CHRG:".$objRs->fields["DOCNO"]]["comdisc"] += $comdisc;
									
									if ($valid) {
										if (!isset($sectiondata[$objRs->fields["U_DEPARTMENT"]])) $sectiondata[$objRs->fields["U_DEPARTMENT"]]=0;
										$sectiondata[$objRs->fields["U_DEPARTMENT"]] += $netamount;							
										if (!isset($feedata["Hospital Fees"])) {
											$feedata["Hospital Fees"]["feetype"] = "HF";
											$feedata["Hospital Fees"]["amount"] = 0;
										}
										$feedata["Hospital Fees"]["amount"] += $netamount;
									}	
									break;
							}
							break;	
					}		
					
					if ($valid) $tamount+=$netamount;
					$tdiscamount=0;
					$tinsamount=0;
					$thmoamount=0;
					if ($valid) $tnetamount+=$netamount;
					
				}				
			} elseif ($objRs->fields["U_DOCTYPE"]=="ADJ") {
				$amount = roundAmount($objRs->fields["U_AMOUNT"]);
				if (!isset($trxdata["ADJ:".$objRs->fields["DOCNO"]])) {
					$trxdata["ADJ:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
					$trxdata["ADJ:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
					$trxdata["ADJ:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
					$trxdata["ADJ:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
					$trxdata["ADJ:".$objRs->fields["DOCNO"]]["doctype"] = "ADJ";
					$trxdata["ADJ:".$objRs->fields["DOCNO"]]["docdesc"] = "Adjustment";		
					$trxdata["ADJ:".$objRs->fields["DOCNO"]]["amount"] = 0;
					$trxdata["ADJ:".$objRs->fields["DOCNO"]]["balance"] = 0;
					$trxdata["ADJ:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
				}
				$trxdata["ADJ:".$objRs->fields["DOCNO"]]["amount"] += $amount;
				//if ($objRs->fields["DOCSTATUS"]!="CN") $trxdata["ADJ:".$objRs->fields["DOCNO"]]["balance"] += $amount;
				
				//$tamount+=$amount;
				//$tnetamount+=$amount;
				
			} elseif ($objRs->fields["U_DOCTYPE"]=="CM") {
				if ($objRs->fields["U_PREPAID"]=="2" && $objRs->fields["U_REQUESTTYPE"]=="REQ") {
					continue;
				}
				
				if ($objRs->fields["U_PREPAID"]=="1") {
					if($objRs->fields["U_DUEAMOUNT"]==0) continue;
					$amount = roundAmount($objRs->fields["U_DUEAMOUNT"]);
					$balance = roundAmount($objRs->fields["U_DUEAMOUNT"]);
				} else {	
					$amount = roundAmount($objRs->fields["U_AMOUNT"]);
					$balance = roundAmount($objRs->fields["U_DUEAMOUNT"]);
				}
				if (!isset($trxdata["CM:".$objRs->fields["DOCNO"]])) {
					$trxdata["CM:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
					$trxdata["CM:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
					$trxdata["CM:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
					$trxdata["CM:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
					$trxdata["CM:".$objRs->fields["DOCNO"]]["doctype"] = "CM";
					if ($objRs->fields["U_REQUESTTYPE"]=="CHRG") {
						if ($objRs->fields["U_PREPAID"]=="1") $trxdata["CM:".$objRs->fields["DOCNO"]]["docdesc"] = "Returns/Credit";
						else $trxdata["CM:".$objRs->fields["DOCNO"]]["docdesc"] = "Returns";
					} else $trxdata["CM:".$objRs->fields["DOCNO"]]["docdesc"] = "Credit";		
					$trxdata["CM:".$objRs->fields["DOCNO"]]["amount"] = 0;
					$trxdata["CM:".$objRs->fields["DOCNO"]]["balance"] = 0;
					$trxdata["CM:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
				}
				$trxdata["CM:".$objRs->fields["DOCNO"]]["amount"] += $amount*-1;
				$trxdata["CM:".$objRs->fields["DOCNO"]]["balance"] += $balance*-1;
				
				if ($balance!=0) {
					if (!isset($feedata["Credits/Partial Payments"])) {
						$feedata["Credits/Partial Payments"]["feetype"] = "DP";
						$feedata["Credits/Partial Payments"]["amount"] = 0;
					}
					$feedata["Credits/Partial Payments"]["amount"] += $balance*-1;				
					
					$dpamt += $balance;
					$dpbal += $balance;
				}	
					
			} elseif ($objRs->fields["U_DOCTYPE"]=="DP") {
				$amount = roundAmount($objRs->fields["U_DUEAMOUNT"]);
				$balance = roundAmount($objRs->fields["U_DUEAMOUNT"]);
				if (!isset($trxdata["DP:".$objRs->fields["DOCNO"]])) {
					$trxdata["DP:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
					$trxdata["DP:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
					$trxdata["DP:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
					$trxdata["DP:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
					$trxdata["DP:".$objRs->fields["DOCNO"]]["doctype"] = "DP";
					$trxdata["DP:".$objRs->fields["DOCNO"]]["docdesc"] = "Partial Payment";
					$trxdata["DP:".$objRs->fields["DOCNO"]]["amount"] = 0;
					$trxdata["DP:".$objRs->fields["DOCNO"]]["balance"] = 0;
					$trxdata["DP:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
				}
				$trxdata["DP:".$objRs->fields["DOCNO"]]["amount"] += $amount*-1;
				$trxdata["DP:".$objRs->fields["DOCNO"]]["balance"] += $balance*-1;

				if ($balance!=0) {
					if (!isset($feedata["Credits/Partial Payments"])) {
						$feedata["Credits/Partial Payments"]["feetype"] = "DP";
						$feedata["Credits/Partial Payments"]["amount"] = 0;
					}
					$feedata["Credits/Partial Payments"]["amount"] += $balance*-1;				
					
					$dpamt += $balance;
					$dpbal += $balance;
				}	
			} elseif ($objRs->fields["U_DOCTYPE"]=="BP") {
				$amount = roundAmount($objRs->fields["U_DUEAMOUNT"]);
				$balance = roundAmount($objRs->fields["U_DUEAMOUNT"]);
				if (!isset($trxdata["BP:".$objRs->fields["DOCNO"]])) {
					$trxdata["BP:".$objRs->fields["DOCNO"]]["docno"] = $objRs->fields["DOCNO"];
					$trxdata["BP:".$objRs->fields["DOCNO"]]["department"] = $objRs->fields["U_DEPARTMENT"];
					$trxdata["BP:".$objRs->fields["DOCNO"]]["docdate"] = $objRs->fields["U_STARTDATE"];
					$trxdata["BP:".$objRs->fields["DOCNO"]]["doctime"] = $objRs->fields["U_STARTTIME"];
					$trxdata["BP:".$objRs->fields["DOCNO"]]["doctype"] = "BP";
					$trxdata["BP:".$objRs->fields["DOCNO"]]["docdesc"] = "Payment";
					$trxdata["BP:".$objRs->fields["DOCNO"]]["amount"] = 0;
					$trxdata["BP:".$objRs->fields["DOCNO"]]["balance"] = 0;
					$trxdata["BP:".$objRs->fields["DOCNO"]]["comdisc"] = 0;
				}
				$trxdata["BP:".$objRs->fields["DOCNO"]]["amount"] += $amount*-1;
				$trxdata["BP:".$objRs->fields["DOCNO"]]["balance"] += $balance*-1;

				if ($balance!=0) {
					if (!isset($feedata["Credits/Partial Payments"])) {
						$feedata["Credits/Partial Payments"]["feetype"] = "DP";
						$feedata["Credits/Partial Payments"]["amount"] = 0;
					}
					$feedata["Credits/Partial Payments"]["amount"] += $balance*-1;				
					
					$dpamt += $balance;
					$dpbal += $balance;
				}	
					
			}	

		}
								
		if ($u_disccode!="") {
			$objRs->queryopen("select * from u_hishealthins where code='$u_disccode' and u_priority=1");
			while ($objRs->queryfetchrow("NAME")) {
				$objGrids[6]->addrow();
				$objGrids[6]->setitem(null,"u_inscode",$objRs->fields["CODE"]);
				$objGrids[6]->setitem(null,"u_hmo",$objRs->fields["U_HMO"]);
				$objGrids[6]->setitem(null,"u_scdisc",$objRs->fields["U_SCDISC"]);
				$objGrids[6]->setitem(null,"u_amount",formatNumericAmount(0));
				$objGrids[6]->setitem(null,"u_status","");
			}			
		}

		if ($postdata["u_reftype"]=="IP") {
			$objRs->queryopen("select * from u_hisipins where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$docid' order by lineid");
		} else {
			$objRs->queryopen("select * from u_hisopins where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$docid' order by lineid");
		}	
		while ($objRs->queryfetchrow("NAME")) {

			$objGrids[6]->addrow();
			$objGrids[6]->setitem(null,"u_inscode",$objRs->fields["U_INSCODE"]);
			$objGrids[6]->setitem(null,"u_hmo",$objRs->fields["U_HMO"]);
			$objGrids[6]->setitem(null,"u_scdisc",$objRs->fields["U_SCDISC"]);
			$objGrids[6]->setitem(null,"u_memberid",$objRs->fields["U_MEMBERID"]);
			$objGrids[6]->setitem(null,"u_membername",$objRs->fields["U_MEMBERNAME"]);
			$objGrids[6]->setitem(null,"u_membertype",$objRs->fields["U_MEMBERTYPE"]);
			$objGrids[6]->setitem(null,"u_amount",formatNumericAmount(0));
			$objGrids[6]->setitem(null,"u_status","");
			$objGrids[6]->setitem(null,"u_priority",$objRs->fields["U_PRIORITY"]);
		}			
		
		if ($u_disccode!="") {
			$objRs->queryopen("select * from u_hishealthins where code='$u_disccode' and u_priority=0");
			while ($objRs->queryfetchrow("NAME")) {
				$objGrids[6]->addrow();
				$objGrids[6]->setitem(null,"u_inscode",$objRs->fields["CODE"]);
				$objGrids[6]->setitem(null,"u_hmo",$objRs->fields["U_HMO"]);
				$objGrids[6]->setitem(null,"u_scdisc",$objRs->fields["U_SCDISC"]);
				$objGrids[6]->setitem(null,"u_amount",formatNumericAmount(0));
				$objGrids[6]->setitem(null,"u_status","");
			}			
		}

		$page->setitem("u_amount",formatNumericAmount($tamount));
		$page->setitem("u_discamount",formatNumericAmount($tdiscamount));
		$page->setitem("u_insamount",formatNumericAmount($tinsamount));
		$page->setitem("u_hmoamount",formatNumericAmount($thmoamount));
		$page->setitem("u_netamount",formatNumericAmount($tnetamount));
		$page->setitem("u_dpamount",formatNumericAmount($tdpamount));
		$page->setitem("u_dueamount",formatNumericAmount($tamount-$dpamt));

		$page->setitem("u_dpamt",formatNumericAmount($dpamt));
		$page->setitem("u_dpcr",formatNumericAmount($dpcr));
		$page->setitem("u_dpbal",formatNumericAmount($dpbal));
		
	} else {
		$page->privatedata["reftypetobill"] = $page->getvarstring("reftypetobill");
		$page->privatedata["refnotobill"] = $page->getvarstring("refnotobill");
	}
	

	$objGrids[8]->clear();
	foreach ($sectiondata as $key => $value) {
		$objGrids[8]->addrow();
		$objGrids[8]->setitem(null,"u_department",$key);
		$objGrids[8]->setitem(null,"u_amount",formatNumericAmount($value));	
		$objGrids[8]->setitem(null,"u_runbal",0);	
	}	

	$objGrids[9]->clear();
	$lineno=0;
	foreach ($feedata as $key => $fees) {
		if ($key=="Credits/Partial Payments") continue;
		if ($key=="Hospital Fees") {
			$lineno++;
			$objGrids[9]->addrow();
			$objGrids[9]->setitem(null,"u_doctorid","");
			$objGrids[9]->setitem(null,"u_feetype","Hospital Fees");	
			$objGrids[9]->setitem(null,"u_amount",formatNumericAmount($fees["amount"]));	
			$objGrids[9]->setitem(null,"u_discamount",formatNumericAmount(0));	
			$objGrids[9]->setitem(null,"u_insamount",formatNumericAmount(0));	
			$objGrids[9]->setitem(null,"u_caserate1",formatNumericAmount(0));	
			$objGrids[9]->setitem(null,"u_caserate2",formatNumericAmount(0));	
			$objGrids[9]->setitem(null,"u_hmoamount",formatNumericAmount(0));	
			$objGrids[9]->setitem(null,"u_pnamount",formatNumericAmount(0));	
			$objGrids[9]->setitem(null,"u_netamount",formatNumericAmount($fees["amount"]));	
			$objGrids[9]->setitem(null,"u_suffix","/".$lineno);
			$objGrids[9]->setitem(null,"u_paidamount",formatNumericAmount(0));	
		} else {
			if ($fees["amount"]>0) {
				$lineno++;
				$objGrids[9]->addrow();
				$objGrids[9]->setitem(null,"u_doctorid",$key);
				$objGrids[9]->setitem(null,"u_feetype","Professional Fees");	
				$objGrids[9]->setitem(null,"u_amount",formatNumericAmount($fees["amount"]));
				$objGrids[9]->setitem(null,"u_discamount",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_insamount",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_caserate1",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_caserate2",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_hmoamount",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_pnamount",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_netamount",formatNumericAmount($fees["amount"]));	
				$objGrids[9]->setitem(null,"u_suffix","/".$lineno);
				$objGrids[9]->setitem(null,"u_paidamount",formatNumericAmount(0));	
			}		
			if ($fees["amount2"]>0) {
				$lineno++;
				$objGrids[9]->addrow();
				$objGrids[9]->setitem(null,"u_doctorid",$key);
				$objGrids[9]->setitem(null,"u_feetype","Professional Materials");	
				$objGrids[9]->setitem(null,"u_amount",formatNumericAmount($fees["amount2"]));
				$objGrids[9]->setitem(null,"u_discamount",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_insamount",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_caserate1",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_caserate2",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_hmoamount",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_pnamount",formatNumericAmount(0));	
				$objGrids[9]->setitem(null,"u_netamount",formatNumericAmount($fees["amount2"]));	
				$objGrids[9]->setitem(null,"u_suffix","/".$lineno);
				$objGrids[9]->setitem(null,"u_paidamount",formatNumericAmount(0));	
				
			}		
		}	
	}	
	foreach ($feedata as $key => $fees) {
		if ($key!="Credits/Partial Payments") continue;
		$lineno++;
		$objGrids[9]->addrow();
		$objGrids[9]->setitem(null,"u_doctorid","");
		$objGrids[9]->setitem(null,"u_feetype","Credits/Partial Payments");	
		$objGrids[9]->setitem(null,"u_amount",formatNumericAmount($fees["amount"]));	
		$objGrids[9]->setitem(null,"u_discamount",formatNumericAmount(0));	
		$objGrids[9]->setitem(null,"u_insamount",formatNumericAmount(0));	
		$objGrids[9]->setitem(null,"u_caserate1",formatNumericAmount(0));	
		$objGrids[9]->setitem(null,"u_caserate2",formatNumericAmount(0));	
		$objGrids[9]->setitem(null,"u_hmoamount",formatNumericAmount(0));	
		$objGrids[9]->setitem(null,"u_pnamount",formatNumericAmount(0));	
		$objGrids[9]->setitem(null,"u_netamount",formatNumericAmount($fees["amount"]));	
		$objGrids[9]->setitem(null,"u_suffix","/".$lineno);
		$objGrids[9]->setitem(null,"u_paidamount",formatNumericAmount(0));	
	}	
	
	$objGrids[10]->clear();
	foreach ($trxdata as $key => $value) {
		$objGrids[10]->addrow();
		$objGrids[10]->setitem(null,"u_docdate",formatDateToHttp($value["docdate"]));
		$objGrids[10]->setitem(null,"u_doctime",formatTimeToHttp($value["doctime"]));
		$objGrids[10]->setitem(null,"u_docno",$value["docno"]);
		$objGrids[10]->setitem(null,"u_department",$value["department"]);
		$objGrids[10]->setitem(null,"u_doctype",$value["doctype"]);
		$objGrids[10]->setitem(null,"u_docdesc",$value["docdesc"]);
		$objGrids[10]->setitem(null,"u_docstatus",$value["docstatus"]);
		$objGrids[10]->setitem(null,"u_comdisc",formatNumericAmount($value["comdisc"]));	
		$objGrids[10]->setitem(null,"u_amount",formatNumericAmount($value["amount"]));	
		$objGrids[10]->setitem(null,"u_balance",formatNumericAmount($value["balance"]));	
		$objGrids[10]->setitem(null,"u_runbal",0);	
	}	
	
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
	$page->privatedata["billage"] = abs(datedifference("n",date('Y-m-d H:i'),formatDateToDB($page->getitemstring("u_docdate"))." ".$page->getitemstring("u_doctime")));
	$page->privatedata["docstatus"] = $page->getitemstring("docstatus");
	retrievePatientPendingItemsGPSHIS($page->getitemstring("u_reftype"),$page->getitemstring("u_refno"));
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
	global $page;
	$page->setitem("u_docdate",currentdate());
	$page->setitem("u_doctime",currenttime());	
	return true;
}

function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	global $sectionrunbal;
	switch ($tablename) {
		case "T1":
			if ($column=="u_billdiscount") {
				//$label = iif($label=="1","Y","");
			}	
			break;
		case "T2":
			if ($column=="u_type") {
				$objRs->queryopen("select name from u_hislabtesttypes where code='$label' union all select name from u_hisitems where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			} elseif ($column=="u_billdiscount") {
				//$label = iif($label=="1","Y","");
			}	
			break;
		case "T3":
			if ($column=="u_doctorid") {
				$objRs->queryopen("select name from u_hisdoctors where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			} elseif ($column=="u_billdiscount") {
				//$label = iif($label=="1","Y","");
			}	
			break;
		case "T4":
			if ($column=="u_billdiscount") {
				//$label = iif($label=="1","Y","");
			}	
			break;
		case "T5":
			if ($column=="u_billdiscount") {
				//$label = iif($label=="1","Y","");
			}	
			break;
		case "T6":
			if ($column=="u_billdiscount") {
				//$label = iif($label=="1","Y","");
			}	
			break;
		case "T7":
			if ($column=="u_inscode") {
				$objRs->queryopen("select name from u_hishealthins where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			} elseif ($column=="u_membertype") {
				$objRs->queryopen("select name from u_hishealthinmemtypes where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}	
			break;
		case "T8":
			if ($column=="u_guarantorcode") {
				$objRs->queryopen("select name from u_hishealthins where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			} elseif ($column=="u_doctorid" && $label!="") {
				$objRs->queryopen("select name from u_hisdoctors where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			} elseif ($column=="u_status") {
				switch ($label) {
					case "O": $label = "Open"; break;
					case "CN": $label = "Cancelled"; break;
				}
			}
			break;
		case "T9":
			if ($column=="u_department") {
				$tmp=explode(";",$label);
				if (count($tmp)>1 && $tmp[0]!=$tmp[1]) {
					$objRs->queryopen("select name from u_hissections where code='".$tmp[0]."'");
					if ($objRs->queryfetchrow("NAME")) $label = $objRs->fields["name"];
					$objRs->queryopen("select name from u_hissections where code='".$tmp[1]."'");
					if ($objRs->queryfetchrow("NAME")) $label .= " (". $objRs->fields["name"].")";
					else $label .= " (Undefined)";
				} else {
					$objRs->queryopen("select name from u_hissections where code='".$tmp[0]."'");
					if ($objRs->queryfetchrow("NAME")) $label = $objRs->fields["name"];
				}	
			} elseif ($column=="u_amount") {
				$sectionrunbal+=formatNumericToDB($label);
			} elseif ($column=="u_runbal") {
				$label = formatNumericAmount($sectionrunbal);
			}
			break;
		case "T10":
			if ($column=="u_doctorid" && $label!="") {
				$objRs->queryopen("select name from u_hisdoctors where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}	
			break;
		case "T11":
			if ($column=="u_department") {
				$objRs->queryopen("select name from u_hissections where code='$label'");
				if ($objRs->queryfetchrow("NAME")) $label = $objRs->fields["name"];
			} elseif ($column=="u_balance") {
				$trxrunbal+=formatNumericToDB($label);
			} elseif ($column=="u_runbal") {
				$label = formatNumericAmount($trxrunbal);
			}
			
			break;
		default:
			if (setGridColumnLabelPatientPendingItemsGPSHIS($tablename,$column,$row,$label)) break;	
			break;
	}
}

$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->setcfl("u_docduedate","Calendar");
$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");

$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_startdate",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_amount",false);
$page->businessobject->items->seteditable("u_discamount",false);
$page->businessobject->items->seteditable("u_insamount",false);
$page->businessobject->items->seteditable("u_hmoamount",false);
$page->businessobject->items->seteditable("u_pnamount",false);
$page->businessobject->items->seteditable("u_netamount",false);

$page->businessobject->items->setvisible("u_jvcndocno",false);
$page->businessobject->items->setvisible("u_cancelledby",false);
$page->businessobject->items->setvisible("u_cancelledreason",false);
$page->businessobject->items->setvisible("u_cancelledremarks",false);
$page->businessobject->items->setvisible("u_dpcr",false);
$page->businessobject->items->setvisible("u_dpbal",false);

//$page->businessobject->items->seteditable("u_docdate",false);
//$page->businessobject->items->seteditable("u_doctime",false);


$objGrids[0]->dataentry = false;
$objGrids[0]->columnwidth("u_roomno",10);
$objGrids[0]->columnwidth("u_roomdesc",20);
$objGrids[0]->columntitle("u_comdisc","TP Disc");
$objGrids[0]->columnwidth("u_comdisc",10);
$objGrids[0]->columnwidth("u_amount",13);
$objGrids[0]->columnwidth("u_insamount",15);
$objGrids[0]->columnwidth("u_billdiscount",1);
$objGrids[0]->columntitle("u_billdiscount","*");
$objGrids[0]->columnvisibility("u_roomno",false);
$objGrids[0]->columnvisibility("u_bedno",false);
$objGrids[0]->columnvisibility("u_isroomshared",false);
$objGrids[0]->automanagecolumnwidth = false;

$objGrids[1]->columnwidth("u_refno",12);
$objGrids[1]->columntitle("u_comdisc","TP Disc");
$objGrids[1]->columnwidth("u_comdisc",10);
$objGrids[1]->columnwidth("u_amount",10);
$objGrids[1]->columnwidth("u_discamount",10);
$objGrids[1]->columnwidth("u_insamount",10);
$objGrids[1]->columnwidth("u_hmoamount",10);
$objGrids[1]->columnwidth("u_netamount",10);
$objGrids[1]->columnwidth("u_billdiscount",1);
$objGrids[1]->columntitle("u_billdiscount","*");
$objGrids[1]->columntitle("u_amount","Charges");
$objGrids[1]->automanagecolumnwidth = false;
$objGrids[1]->dataentry = false;

$objGrids[2]->columnwidth("u_refno",12);
$objGrids[2]->columnwidth("u_doctorid",30);
$objGrids[2]->columnwidth("u_itemcode",15);
$objGrids[2]->columnwidth("u_itemdesc",30);
$objGrids[2]->columntitle("u_comdisc","TP Disc");
$objGrids[2]->columnwidth("u_comdisc",10);
$objGrids[2]->columnwidth("u_amount",10);
$objGrids[2]->columnwidth("u_discamount",10);
$objGrids[2]->columnwidth("u_insamount",10);
$objGrids[2]->columnwidth("u_hmoamount",10);
$objGrids[2]->columnwidth("u_netamount",10);
$objGrids[2]->columnwidth("u_billdiscount",1);
$objGrids[2]->columntitle("u_billdiscount","*");
$objGrids[2]->columntitle("u_amount","Charges");
$objGrids[2]->automanagecolumnwidth = false;
$objGrids[2]->columnvisibility("u_doctortype",false);
$objGrids[2]->columnvisibility("u_surgeonfee",false);
$objGrids[2]->dataentry = false;

$objGrids[3]->columnwidth("u_refno",12);
$objGrids[3]->columnwidth("u_itemcode",10);
$objGrids[3]->columnwidth("u_itemdesc",30);
$objGrids[3]->columnwidth("u_quantity",8);
$objGrids[3]->columntitle("u_comdisc","TP Disc");
$objGrids[3]->columnwidth("u_comdisc",10);
$objGrids[3]->columnwidth("u_amount",10);
$objGrids[3]->columnwidth("u_discamount",10);
$objGrids[3]->columnwidth("u_insamount",10);
$objGrids[3]->columnwidth("u_hmoamount",10);
$objGrids[3]->columnwidth("u_netamount",10);
$objGrids[3]->columnwidth("u_billdiscount",1);
$objGrids[3]->columntitle("u_billdiscount","*");
$objGrids[3]->columntitle("u_amount","Charges");
$objGrids[3]->automanagecolumnwidth = false;
$objGrids[3]->dataentry = false;

$objGrids[4]->columnwidth("u_refno",12);
$objGrids[4]->columnwidth("u_itemcode",10);
$objGrids[4]->columnwidth("u_itemdesc",30);
$objGrids[4]->columnwidth("u_quantity",8);
$objGrids[4]->columntitle("u_comdisc","TP Disc");
$objGrids[4]->columnwidth("u_comdisc",10);
$objGrids[4]->columnwidth("u_amount",10);
$objGrids[4]->columnwidth("u_discamount",10);
$objGrids[4]->columnwidth("u_insamount",10);
$objGrids[4]->columnwidth("u_hmoamount",10);
$objGrids[4]->columnwidth("u_netamount",10);
$objGrids[4]->columnwidth("u_billdiscount",1);
$objGrids[4]->columntitle("u_billdiscount","*");
$objGrids[4]->columntitle("u_amount","Charges");
$objGrids[4]->automanagecolumnwidth = false;
$objGrids[4]->dataentry = false;

$objGrids[5]->columnwidth("u_refno",12);
$objGrids[5]->columnwidth("u_roomno",10);
$objGrids[5]->columnwidth("u_roomdesc",15);
$objGrids[5]->columnwidth("u_bedno",10);
$objGrids[5]->columnwidth("u_itemcode",10);
$objGrids[5]->columnwidth("u_itemdesc",30);
$objGrids[5]->columntitle("u_comdisc","TP Disc");
$objGrids[5]->columnwidth("u_comdisc",10);
$objGrids[5]->columnwidth("u_amount",10);
$objGrids[5]->columnwidth("u_discamount",10);
$objGrids[5]->columnwidth("u_insamount",10);
$objGrids[5]->columnwidth("u_hmoamount",10);
$objGrids[5]->columnwidth("u_netamount",10);
$objGrids[5]->columnwidth("u_quantity",8);
$objGrids[5]->columnwidth("u_rate",10);
$objGrids[5]->columnwidth("u_rateuom",5);
$objGrids[5]->columnwidth("u_billdiscount",1);
$objGrids[5]->columntitle("u_billdiscount","*");
$objGrids[5]->columntitle("u_amount","Charges");
$objGrids[5]->columnvisibility("u_roomno",false);
$objGrids[5]->columnvisibility("u_bedno",false);
$objGrids[5]->automanagecolumnwidth = false;
$objGrids[5]->dataentry = false;

$objGrids[6]->columnattributes("u_claimno","disabled");
$objGrids[6]->columnattributes("u_status","disabled");
$objGrids[6]->columnattributes("u_amount","disabled");
$objGrids[6]->columntitle("u_inscode","Health Benefit");
$objGrids[6]->columntitle("u_claimno","Reference No.");
$objGrids[6]->columntitle("u_comdisc","TP Disc");
$objGrids[6]->columnwidth("u_comdisc",10);
$objGrids[6]->columnwidth("u_inscode",25);
$objGrids[6]->columnwidth("u_claimno",15);
$objGrids[6]->columnwidth("u_memberid",15);
$objGrids[6]->columnwidth("u_membername",30);
$objGrids[6]->columnwidth("u_membertype",25);
$objGrids[6]->columnvisibility("u_hmo",false);
$objGrids[6]->columndataentry("u_inscode","options",array("loadu_hishealthins3","",":"));
$objGrids[6]->automanagecolumnwidth = false;
//$objGrids[6]->dataentry = false;

$objGrids[7]->columntitle("u_guarantorcode","Health Benefit");
$objGrids[7]->columntitle("u_pnno","Reference No.");
$objGrids[7]->columnwidth("u_pnno",12);
$objGrids[7]->columnwidth("u_claimno",12);
$objGrids[7]->columnwidth("u_type",15);
$objGrids[7]->columnwidth("u_feetype",20);
$objGrids[7]->columnwidth("u_caserate",12);
$objGrids[7]->columnlnkbtn("u_claimno","OpenLnkBtnClaimNo()");
$objGrids[7]->columnvisibility("u_caserate",false);
$objGrids[7]->setaction("reset",false);
$objGrids[7]->automanagecolumnwidth = false;

$objGrids[8]->dataentry = false;
$objGrids[8]->columnwidth("u_runbal",15);
$objGrids[8]->automanagecolumnwidth = false;


$objGrids[9]->columnwidth("u_feetype",21);
$objGrids[9]->columnwidth("u_discamount",10);
$objGrids[9]->columnwidth("u_insamount",10);
$objGrids[9]->columnwidth("u_caserate1",10);
$objGrids[9]->columnwidth("u_caserate2",10);
$objGrids[9]->columnwidth("u_hmoamount",10);
$objGrids[9]->columnwidth("u_pnamount",10);
$objGrids[9]->columnwidth("u_netamount",10);
$objGrids[9]->automanagecolumnwidth = false;
$objGrids[9]->dataentry = false;

$objGrids[10]->columnwidth("u_docdate",10);
$objGrids[10]->columnwidth("u_docno",12);
$objGrids[10]->columnwidth("u_docdesc",17);
$objGrids[10]->columnwidth("u_comdisc",9);
$objGrids[10]->columntitle("u_comdisc","TP Disc");
$objGrids[10]->columntitle("u_runbal","Running Bal");
$objGrids[10]->columnlnkbtn("u_docno","OpenLnkBtnTrxNo()");
$objGrids[10]->columnvisibility("u_comdisc",false);
$objGrids[10]->dataentry = true;
$objGrids[10]->setaction("add",false);
$objGrids[10]->setaction("reset",false);

$objGrids[10]->automanagecolumnwidth = false;


include_once("../Addons/GPS/HIS Add-On/UserProcs/utils/u_hispatientpendingitems.php");


$objGridSummaryBySection  = new grid("T150");
$objGridSummaryBySection->addcolumn("department");
$objGridSummaryBySection->addcolumn("amount");

$page->toolbar->setaction("new",false);



?> 

