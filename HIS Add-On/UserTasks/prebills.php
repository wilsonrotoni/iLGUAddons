<?php
	require_once("../common/classes/recordset.php");
	require_once("./classes/masterdataschema.php");
	require_once("./classes/masterdataschema_br.php");
	require_once("./classes/documentschema_br.php");
	require_once("./classes/documentlinesschema_br.php");
	require_once("./classes/users.php");
	require_once("./classes/usermsgs.php");
	require_once("./series.php");
	include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");

	function executeTaskprebillsGPSHIS() {
		global $objConnection;
		$result = array(1,"");
		$actionReturn = true;
		
		$objRs = new recordset(null,$objConnection);
		$objRs2 = new recordset(null,$objConnection);
		$objRs->queryopen("select docid from u_hisbills where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_reftype='IP' and docstatus='D'");
		while ($objRs->queryfetchrow("NAME")) {
			$actionReturn = $objRs2->executesql("delete from u_hisbillrooms where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbilllabtests where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbillconsultancyfees where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbillmedsups where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbillmiscs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbillsplrooms where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbillins where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbillpns where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbillsections where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbillfees where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbilltrxs where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from u_hisbills where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objRs->fields["docid"]."'",false);
			if (!$actionReturn) break;
		}
		
		if ($actionReturn) {
			$objRs->queryopen("select docno from u_hisips where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_billno='' and docstatus not in ('Cancelled','Discharged') and (U_BILLING=1 OR U_FORCEBILLING=1 OR U_BEDNO<>'')");
			while ($objRs->queryfetchrow("NAME")) {
				//var_dump($objRs->fields["docno"]);
				$actionReturn = executeTaskpostprebillGPSHIS('IP',$objRs->fields["docno"]);		
				if (!$actionReturn) break;
				//break;
			}
		}
		/*if ($actionReturn) {
			$objRs->queryopen("select docno from u_hisops where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_billno='' and docstatus not in ('Cancelled','Discharged') and (U_BILLING=1 OR U_FORCEBILLING=1)");
			while ($objRs->queryfetchrow("NAME")) {
				$actionReturn = executeTaskpostprebillGPSHIS('IP',$objRs->fields["docno"]);		
				if (!$actionReturn) break;
			}
		}*/
		//if ($actionReturn) $actionReturn = raiseError("here");
		
		if (!$actionReturn) {
			$result[0] = 0;
			$result[1] = $_SESSION["errormessage"];
		}
		//var_dump($result);
		return $result;
	}
	
	function executeTaskpostprebillGPSHIS($reftype, $refno) {
		global $objConnection;
		$u_hissetupdata = getu_hissetup("U_BILLASONE");
		
		$obju_HISBills = new documentschema_br(null,$objConnection,"u_hisbills");
		$obju_HISBillRooms = new documentlinesschema_br(null,$objConnection,"u_hisbillrooms");
		$obju_HISBillLabTests = new documentlinesschema_br(null,$objConnection,"u_hisbilllabtests");
		$obju_HISBillConsultancyFees = new documentlinesschema_br(null,$objConnection,"u_hisbillconsultancyfees");
		$obju_HISBillMedSups = new documentlinesschema_br(null,$objConnection,"u_hisbillmedsups");
		$obju_HISBillMiscs = new documentlinesschema_br(null,$objConnection,"u_hisbillmiscs");
		$obju_HISBillSplRooms = new documentlinesschema_br(null,$objConnection,"u_hisbillsplrooms");
		$obju_HISBillIns = new documentlinesschema_br(null,$objConnection,"u_hisbillins");
		$obju_HISBillPNs = new documentlinesschema_br(null,$objConnection,"u_hisbillpns");
		$obju_HISBillSections = new documentlinesschema_br(null,$objConnection,"u_hisbillsections");
		$obju_HISBillFees = new documentlinesschema_br(null,$objConnection,"u_hisbillfees");
		$obju_HISBillTrxs = new documentlinesschema_br(null,$objConnection,"u_hisbilltrxs");
		
		$obju_HISBills->prepareadd();
		$obju_HISBills->docid = getNextIDByBranch("u_hisbills",$objConnection);
		$obju_HISBills->docstatus='D';
		$obju_HISBills->setudfvalue("u_billasone",$u_hissetupdata["U_BILLASONE"]);
		$obju_HISBills->setudfvalue("u_docdate",currentdateDB());
		$obju_HISBills->setudfvalue("u_doctime",date('H:i:s'));
		
		$sectiondata = array();
		$feedata = array();
		$trxdata = array();
		
		if ($reftype!="") {
			$obju_HISBills->setudfvalue("u_reftype",$reftype);
			$obju_HISBills->setudfvalue("u_refno",$refno);
			$obju_HISBills->docseries = -1;
			
			/*
hisbillrooms
hisbilllabtests
hisbillconsultancyfees
hisbillmedsups
hisbillmiscs
hisbillsplrooms
hisbillins
hisbillpns
hisbillsections
hisbillfees
hisbilltrxs

			*/
			
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
			
			if ($reftype=="IP") {
				$objRs->queryopen("select * from u_hisips where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno='".$refno."'");
				if ($objRs->queryfetchrow("NAME")) {
					$obju_HISBills->setudfvalue("u_patientid",$objRs->fields["U_PATIENTID"]);
					$obju_HISBills->setudfvalue("u_patientname",$objRs->fields["U_PATIENTNAME"]);
					$obju_HISBills->setudfvalue("u_age",$objRs->fields["U_AGE_Y"]);
					$obju_HISBills->setudfvalue("u_gender",$objRs->fields["U_GENDER"]);
					$obju_HISBills->setudfvalue("u_mgh",$objRs->fields["U_MGH"]);
					$obju_HISBills->setudfvalue("u_icdcode",$objRs->fields["U_ICDCODE"]);
					$obju_HISBills->setudfvalue("u_billcount",$objRs->fields["U_BILLCOUNT"]+1);
					$obju_HISBills->setudfvalue("u_startdate",$objRs->fields["U_STARTDATE"]);
					$obju_HISBills->setudfvalue("u_treatment",$objRs->fields["U_TREATMENT"]);
					$obju_HISBills->setudfvalue("u_phicmemberid",$objRs->fields["U_PHICMEMBERID"]);
					$obju_HISBills->docno = $reftype . "-" . $refno."-". str_pad( $obju_HISBills->getudfvalue("u_billcount"), 2, "0", STR_PAD_LEFT);
					if ($objRs->fields["U_ENDDATE"]!="") $obju_HISBills->setudfvalue("u_docdate",$objRs->fields["U_ENDDATE"]);
						
					$docid = $objRs->fields["DOCID"];
					$u_disccode = $objRs->fields["U_DISCCODE"];
				}
			} else {
				$objRs->queryopen("select * from u_hisops where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno='".$refno."'");
				if ($objRs->queryfetchrow("NAME")) {
					$obju_HISBills->setudfvalue("u_patientid",$objRs->fields["U_PATIENTID"]);
					$obju_HISBills->setudfvalue("u_patientname",$objRs->fields["U_PATIENTNAME"]);
					$obju_HISBills->setudfvalue("u_age",$objRs->fields["U_AGE_Y"]);
					$obju_HISBills->setudfvalue("u_gender",$objRs->fields["U_GENDER"]);
					$obju_HISBills->setudfvalue("u_icdcode",$objRs->fields["U_ICDCODE"]);
					$obju_HISBills->setudfvalue("u_billcount",$objRs->fields["U_BILLCOUNT"]+1);
					$obju_HISBills->setudfvalue("u_startdate",$objRs->fields["U_STARTDATE"]);
					$obju_HISBills->docno = $reftype . "-" . $refno."-". str_pad( $obju_HISBills->getudfvalue("u_billcount"), 2, "0", STR_PAD_LEFT);
					if ($objRs->fields["U_ENDDATE"]!="") $obju_HISBills->setudfvalue("u_docdate",$objRs->fields["U_ENDDATE"]);
					
					$docid = $objRs->fields["DOCID"];
					$u_disccode = $objRs->fields["U_DISCCODE"];		
				}	
			} 
			
			$objRs->queryopen("select 'CHRG' as U_DOCTYPE, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE,a.U_STARTTIME,a.DOCNO,a.U_DISCONBILL,a.U_ISSTAT,a.U_DEPARTMENT, a.DOCSTATUS, 0 as U_AMOUNT, 0 as U_DUEAMOUNT from u_hischarges a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$reftype."' and a.u_refno='".$refno."' and a.u_prepaid in (0,2) and a.u_billno='' union all select 'ADJ' as U_DOCTYPE, '' AS U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,a.DOCNO,0 AS U_DISCONBILL, 0 AS U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_AMOUNT, a.U_AMOUNT AS U_DUEAMOUNT from u_hispriceadjs a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$reftype."' and a.u_refno='".$refno."' union all select 'CM' as U_DOCTYPE, a.U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_STARTDATE, a.U_STARTTIME,a.DOCNO,a.U_DISCONBILL, a.U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, if(a.U_PREPAID=1,a.U_AMOUNT,a.U_AMOUNTBEFDISC) as U_AMOUNT, a.U_BALANCE AS U_DUEAMOUNT from u_hiscredits a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$reftype."' and a.u_refno='".$refno."' union all select 'DP' as U_DOCTYPE, '' as U_REQUESTTYPE, a.U_PREPAID, a.DOCID,a.U_DOCDATE AS U_STARTDATE,'' AS U_STARTTIME,a.DOCNO,a.U_DISCONBILL,0 AS  U_ISSTAT, a.U_DEPARTMENT, a.DOCSTATUS, a.U_DUEAMOUNT AS U_AMOUNT, a.U_BALANCE AS U_DUEAMOUNT from u_hispos a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$reftype."' and a.u_refno='".$refno."' and u_prepaid in (2) and docstatus not in ('CN') and u_balance<>0 union all select 'BP' as U_DOCTYPE, '' as U_REQUESTTYPE, 0 as U_PREPAID, a.DOCID,a.U_DOCDATE AS U_STARTDATE,'' AS U_STARTTIME,a.DOCNO, 1 as U_DISCONBILL,0 AS  U_ISSTAT, '' as U_DEPARTMENT, a.DOCSTATUS, a.U_BALANCE AS U_AMOUNT, a.U_BALANCE AS U_DUEAMOUNT from u_hisbills a where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_reftype='".$reftype."' and a.u_refno='".$refno."' and docstatus in ('CN') and u_balance<>0");
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
									$obju_HISBillMedSups->prepareadd();
									$obju_HISBillMedSups->docid = $obju_HISBills->docid;
									$obju_HISBillMedSups->lineid = getNextIdByBranch("u_hisbillmedsups",$objConnection);
									$obju_HISBillMedSups->setudfvalue("u_date",$objRs->fields["U_STARTDATE"]);
									$obju_HISBillMedSups->setudfvalue("u_refno",$objRs->fields["DOCNO"]);
									$obju_HISBillMedSups->setudfvalue("u_itemcode",$objRs2->fields["U_ITEMCODE"]);
									$obju_HISBillMedSups->setudfvalue("u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
									$obju_HISBillMedSups->setudfvalue("u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
									$obju_HISBillMedSups->setudfvalue("u_quantity",($objRs2->fields["U_NETQTY"]));
								}	
								$comdisc=0;
								if ($objRs->fields["U_DISCONBILL"]=="0") {
									if ($valid) {
										$obju_HISBillMedSups->setudfvalue("u_comdisc",($objRs2->fields["U_DISCAMOUNT"]));
										$obju_HISBillMedSups->setudfvalue("u_price",($objRs2->fields["U_ADJPRICE"]));
									}	
									$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
									$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
									$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
								} else {
									if ($valid) $obju_HISBillMedSups->setudfvalue("u_comdisc",0);
									if ($objRs2->fields["U_ISSTAT"]=="0") {
										if ($valid) $obju_HISBillMedSups->setudfvalue("u_price",($objRs2->fields["U_ADJUNITPRICE"]));
										$netamount = roundAmount($objRs2->fields["U_ADJUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
										$amount = roundAmount($objRs2->fields["U_UNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
									} else {
										if ($valid)$obju_HISBillMedSups->setudfvalue("u_price",($objRs2->fields["U_ADJSTATUNITPRICE"]));
										$netamount = roundAmount($objRs2->fields["U_ADJSTATUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
										$amount = roundAmount($objRs2->fields["U_STATUNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
									}	
								}
								if ($valid) {
									$obju_HISBillMedSups->setudfvalue("u_amount",($netamount));
									$obju_HISBillMedSups->setudfvalue("u_discamount",0);
									$obju_HISBillMedSups->setudfvalue("u_insamount",0);
									$obju_HISBillMedSups->setudfvalue("u_hmoamount",0);
									$obju_HISBillMedSups->setudfvalue("u_netamount",($netamount));
									$obju_HISBillMedSups->privatedata["header"] = $obju_HISBills;
									$actionReturn = $obju_HISBillMedSups->add();
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
									$obju_HISBillLabTests->prepareadd();
									$obju_HISBillLabTests->docid = $obju_HISBills->docid;
									$obju_HISBillLabTests->lineid = getNextIdByBranch("u_hisbilllabtests",$objConnection);
									$obju_HISBillLabTests->setudfvalue("u_date",$objRs->fields["U_STARTDATE"]);
									$obju_HISBillLabTests->setudfvalue("u_refno",$objRs->fields["DOCNO"]);
									$obju_HISBillLabTests->setudfvalue("u_type",$objRs2->fields["U_ITEMCODE"]);
									$obju_HISBillLabTests->setudfvalue("u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
								}	
								$comdisc=0;
								if ($objRs->fields["U_DISCONBILL"]=="0") {
									if ($valid) {
										$obju_HISBillLabTests->setudfvalue("u_comdisc",($objRs2->fields["U_DISCAMOUNT"]));
										//$obju_HISBillLabTests->setudfvalue("u_price",($objRs2->fields["U_ADJPRICE"]));
									}	
									$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
									$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
									$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
								} else {
									if ($valid) $obju_HISBillLabTests->setudfvalue("u_comdisc",0);
									if ($objRs2->fields["U_ISSTAT"]=="0") {
										//if ($valid) $obju_HISBillLabTests->setudfvalue("u_price",($objRs2->fields["U_ADJUNITPRICE"]));
										$amount = roundAmount($objRs2->fields["U_UNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
										$netamount = roundAmount($objRs2->fields["U_ADJUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
									} else {
										//if ($valid) $obju_HISBillLabTests->setudfvalue("u_price",($objRs2->fields["U_ADJSTATUNITPRICE"]));
										$amount = roundAmount($objRs2->fields["U_STATUNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
										$netamount = roundAmount($objRs2->fields["U_ADJSTATUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
									}	
								}
								if ($valid) {
									$obju_HISBillLabTests->setudfvalue("u_amount",($netamount));
									$obju_HISBillLabTests->setudfvalue("u_discamount",0);
									$obju_HISBillLabTests->setudfvalue("u_insamount",0);
									$obju_HISBillLabTests->setudfvalue("u_hmoamount",0);
									$obju_HISBillLabTests->setudfvalue("u_netamount",($netamount));
									$obju_HISBillLabTests->privatedata["header"] = $obju_HISBills;
									$actionReturn = $obju_HISBillLabTests->add();
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
											$obju_HISBillRooms->prepareadd();
											$obju_HISBillRooms->docid = $obju_HISBills->docid;
											$obju_HISBillRooms->lineid = getNextIdByBranch("u_hisbillrooms",$objConnection);
											$obju_HISBillRooms->setudfvalue("u_startdate",$objRs->fields["U_STARTDATE"]);
											$obju_HISBillRooms->setudfvalue("u_enddate",$objRs->fields["U_STARTDATE"]);
											$obju_HISBillRooms->setudfvalue("u_roomno","");
											$obju_HISBillRooms->setudfvalue("u_roomtype",$objRs2->fields["U_ITEMCODE"]);
											$obju_HISBillRooms->setudfvalue("u_isroomshared",0);
											$obju_HISBillRooms->setudfvalue("u_roomdesc",$objRs2->fields["U_ITEMDESC"]);
											$obju_HISBillRooms->setudfvalue("u_comdisc",($comdisc));
											$obju_HISBillRooms->setudfvalue("u_rate",($objRs2->fields["U_ADJPRICE"]));
											$obju_HISBillRooms->setudfvalue("u_rateuom","");
											$obju_HISBillRooms->setudfvalue("u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
											$obju_HISBillRooms->setudfvalue("u_quantity",(1));
											$obju_HISBillRooms->setudfvalue("u_amount",($objRs2->fields["U_ADJPRICE"]));
											$obju_HISBillRooms->setudfvalue("u_discamount",0);
											$obju_HISBillRooms->setudfvalue("u_insamount",0);
											$obju_HISBillRooms->setudfvalue("u_hmoamount",0);
											$obju_HISBillRooms->setudfvalue("u_netamount",($objRs2->fields["U_ADJPRICE"]));
											$obju_HISBillRooms->privatedata["header"] = $obju_HISBills;
											$actionReturn = $obju_HISBillRooms->add();
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
											$obju_HISBillSplRooms->prepareadd();
											$obju_HISBillSplRooms->docid = $obju_HISBills->docid;
											$obju_HISBillSplRooms->lineid = getNextIdByBranch("u_hisbillsplrooms",$objConnection);
											$obju_HISBillSplRooms->setudfvalue("u_date",$objRs->fields["U_STARTDATE"]);
											$obju_HISBillSplRooms->setudfvalue("u_refno",$objRs->fields["DOCNO"]);
											$obju_HISBillSplRooms->setudfvalue("u_roomno",substr($objRs2->fields["U_CLASS"],4));
											$obju_HISBillSplRooms->setudfvalue("u_roomdesc",$objRs2->fields["ITEMCLASSNAME"]);
											$obju_HISBillSplRooms->setudfvalue("u_roomtype",substr($objRs2->fields["U_CLASS"],4));
											$obju_HISBillSplRooms->setudfvalue("u_bedno",substr($objRs2->fields["U_CLASS"],4));
											$obju_HISBillSplRooms->setudfvalue("u_itemcode",$objRs2->fields["U_ITEMCODE"]);
											$obju_HISBillSplRooms->setudfvalue("u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
											$obju_HISBillSplRooms->setudfvalue("u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
											$obju_HISBillSplRooms->setudfvalue("u_rateuom","");
											$obju_HISBillSplRooms->setudfvalue("u_quantity",($objRs2->fields["U_NETQTY"]));
										}	
										$comdisc=0;

										if ($objRs->fields["U_DISCONBILL"]=="0") {
											if ($valid) $obju_HISBillSplRooms->setudfvalue("u_comdisc",($objRs2->fields["U_DISCAMOUNT"]));
											if ($valid) $obju_HISBillSplRooms->setudfvalue("u_rate",($objRs2->fields["U_ADJPRICE"]));
											$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
											$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
											$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
										} else {
											if ($valid) $obju_HISBillSplRooms->setudfvalue("u_comdisc",0);
											if ($objRs2->fields["U_ISSTAT"]=="0") {
												if ($valid) $obju_HISBillSplRooms->setudfvalue("u_rate",($objRs2->fields["U_ADJPRICE"]));
												$amount = roundAmount($objRs2->fields["U_UNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
												$netamount = roundAmount($objRs2->fields["U_ADJUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
											} else {
												if ($valid) $obju_HISBillSplRooms->setudfvalue("u_rate",($objRs2->fields["U_ADJSTATUNITPRICE"]));
												$amount = roundAmount($objRs2->fields["U_STATUNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
												$netamount = roundAmount($objRs2->fields["U_ADJSTATUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
											}	
										}
										
										if ($valid) {
											$obju_HISBillSplRooms->setudfvalue("u_amount",($netamount));
											$obju_HISBillSplRooms->setudfvalue("u_discamount",0);
											$obju_HISBillSplRooms->setudfvalue("u_insamount",0);
											$obju_HISBillSplRooms->setudfvalue("u_hmoamount",0);
											$obju_HISBillSplRooms->setudfvalue("u_netamount",($netamount));
											$obju_HISBillSplRooms->privatedata["header"] = $obju_HISBills;
											$actionReturn = $obju_HISBillSplRooms->add();
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
									case "PRF":
										if ($valid) {
											$obju_HISBillConsultancyFees->prepareadd();
											$obju_HISBillConsultancyFees->docid = $obju_HISBills->docid;
											$obju_HISBillConsultancyFees->lineid = getNextIdByBranch("u_hisbillconsultancyfees",$objConnection);
											$obju_HISBillConsultancyFees->setudfvalue("u_date",$objRs->fields["U_STARTDATE"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_refno",$objRs->fields["DOCNO"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_doctorid",$objRs2->fields["U_DOCTORID"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_itemcode",$objRs2->fields["U_ITEMCODE"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_surgeonfee",($objRs->fields["U_SURGEONFEE"]));
											$obju_HISBillConsultancyFees->setudfvalue("u_comdisc",($objRs2->fields["U_DISCAMOUNT"]));
										}	
										$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
										$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
										$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
										if ($valid) {
											$obju_HISBillConsultancyFees->setudfvalue("u_amount",($netamount));
											$obju_HISBillConsultancyFees->setudfvalue("u_discamount",0);
											$obju_HISBillConsultancyFees->setudfvalue("u_insamount",0);
											$obju_HISBillConsultancyFees->setudfvalue("u_hmoamount",0);
											$obju_HISBillConsultancyFees->setudfvalue("u_netamount",($netamount));
											$obju_HISBillConsultancyFees->privatedata["header"] = $obju_HISBills;
											$actionReturn = $obju_HISBillConsultancyFees->add();
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
											$obju_HISBillConsultancyFees->prepareadd();
											$obju_HISBillConsultancyFees->docid = $obju_HISBills->docid;
											$obju_HISBillConsultancyFees->lineid = getNextIdByBranch("u_hisbillrooms",$objConnection);
											$obju_HISBillConsultancyFees->setudfvalue("u_date",$objRs->fields["U_STARTDATE"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_refno",$objRs->fields["DOCNO"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_doctorid",$objRs2->fields["U_DOCTORID"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_itemcode",$objRs2->fields["U_ITEMCODE"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
											$obju_HISBillConsultancyFees->setudfvalue("u_surgeonfee",0);
											$obju_HISBillConsultancyFees->setudfvalue("u_comdisc",($objRs2->fields["U_DISCAMOUNT"]));
										}	
										$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
										$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
										$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
										if ($valid) {
											$obju_HISBillConsultancyFees->setudfvalue("u_amount",($netamount));
											$obju_HISBillConsultancyFees->setudfvalue("u_discamount",0);
											$obju_HISBillConsultancyFees->setudfvalue("u_insamount",0);
											$obju_HISBillConsultancyFees->setudfvalue("u_hmoamount",0);
											$obju_HISBillConsultancyFees->setudfvalue("u_netamount",($netamount));
											$obju_HISBillConsultancyFees->privatedata["header"] = $obju_HISBills;
											$actionReturn = $obju_HISBillConsultancyFees->add();
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
											$obju_HISBillMiscs->prepareadd();
											$obju_HISBillMiscs->docid = $obju_HISBills->docid;
											$obju_HISBillMiscs->lineid = getNextIdByBranch("u_hisbillmiscs",$objConnection);
											$obju_HISBillMiscs->setudfvalue("u_date",$objRs->fields["U_STARTDATE"]);
											$obju_HISBillMiscs->setudfvalue("u_refno",$objRs->fields["DOCNO"]);
											$obju_HISBillMiscs->setudfvalue("u_itemcode",$objRs2->fields["U_ITEMCODE"]);
											$obju_HISBillMiscs->setudfvalue("u_itemdesc",$objRs2->fields["U_ITEMDESC"]);
											$obju_HISBillMiscs->setudfvalue("u_billdiscount",$objRs2->fields["U_BILLDISCOUNT"]);
											$obju_HISBillMiscs->setudfvalue("u_quantity",($objRs2->fields["U_NETQTY"]));
										}	
										$comdisc=0;
										if ($objRs->fields["U_DISCONBILL"]=="0") {
											if ($valid) {
												$obju_HISBillMiscs->setudfvalue("u_comdisc",($objRs2->fields["U_DISCAMOUNT"]));
												$obju_HISBillMiscs->setudfvalue("u_price",($objRs2->fields["U_ADJPRICE"]));
											}	
											$amount = roundAmount($objRs2->fields["U_PRICE"]*$objRs2->fields["U_QUANTITY"]);
											$netamount = roundAmount($objRs2->fields["U_ADJPRICE"]*$objRs2->fields["U_NETQTY"]);
											$comdisc = roundAmount($objRs2->fields["U_DISCAMOUNT"]*$objRs2->fields["U_QUANTITY"]);
										} else {
											if ($valid) $obju_HISBillMiscs->setudfvalue("u_comdisc",0);
											if ($objRs2->fields["U_ISSTAT"]=="0") {
												if ($valid) $obju_HISBillMiscs->setudfvalue("u_price",($objRs2->fields["U_ADJUNITPRICE"]));
												$amount = roundAmount($objRs2->fields["U_UNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
												$netamount = roundAmount($objRs2->fields["U_ADJUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
											} else {
												if ($valid) $obju_HISBillMiscs->setudfvalue("u_price",($objRs2->fields["U_ADJSTATUNITPRICE"]));
												$amount = roundAmount($objRs2->fields["U_STATUNITPRICE"]*$objRs2->fields["U_QUANTITY"]);
												$netamount = roundAmount($objRs2->fields["U_ADJSTATUNITPRICE"]*$objRs2->fields["U_NETQTY"]);
											}	
										}
										
										if ($valid) {
											$obju_HISBillMiscs->setudfvalue("u_amount",($netamount));
											$obju_HISBillMiscs->setudfvalue("u_discamount",0);
											$obju_HISBillMiscs->setudfvalue("u_insamount",0);
											$obju_HISBillMiscs->setudfvalue("u_hmoamount",0);
											$obju_HISBillMiscs->setudfvalue("u_netamount",($netamount));
											$obju_HISBillMiscs->privatedata["header"] = $obju_HISBills;
											$actionReturn = $obju_HISBillMiscs->add();
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
						if (!$actionReturn) break;

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
				if (!$actionReturn) break;
			}
			
			if ($actionReturn) {						
				if ($u_disccode!="") {
					$objRs->queryopen("select * from u_hishealthins where code='$u_disccode' and u_priority=1");
					while ($objRs->queryfetchrow("NAME")) {
						$obju_HISBillIns->prepareadd();
						$obju_HISBillIns->docid = $obju_HISBills->docid;
						$obju_HISBillIns->lineid = getNextIdByBranch("u_hisbillins",$objConnection);
						$obju_HISBillIns->setudfvalue("u_inscode",$objRs->fields["CODE"]);
						$obju_HISBillIns->setudfvalue("u_hmo",$objRs->fields["U_HMO"]);
						$obju_HISBillIns->setudfvalue("u_scdisc",$objRs->fields["U_SCDISC"]);
						$obju_HISBillIns->setudfvalue("u_amount",0);
						$obju_HISBillIns->setudfvalue("u_status","");
						$obju_HISBillIns->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillIns->add();
						if (!$actionReturn) break;
					}			
				}
			}
				
			if ($actionReturn) {						
				if ($reftype=="IP") {
					$objRs->queryopen("select * from u_hisipins where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$docid' order by lineid");
				} else {
					$objRs->queryopen("select * from u_hisopins where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$docid' order by lineid");
				}	
				while ($objRs->queryfetchrow("NAME")) {
		
					$obju_HISBillIns->prepareadd();
					$obju_HISBillIns->docid = $obju_HISBills->docid;
					$obju_HISBillIns->lineid = getNextIdByBranch("u_hisbillins",$objConnection);
					$obju_HISBillIns->setudfvalue("u_inscode",$objRs->fields["U_INSCODE"]);
					$obju_HISBillIns->setudfvalue("u_hmo",$objRs->fields["U_HMO"]);
					$obju_HISBillIns->setudfvalue("u_scdisc",$objRs->fields["U_SCDISC"]);
					$obju_HISBillIns->setudfvalue("u_memberid",$objRs->fields["U_MEMBERID"]);
					$obju_HISBillIns->setudfvalue("u_membername",$objRs->fields["U_MEMBERNAME"]);
					$obju_HISBillIns->setudfvalue("u_membertype",$objRs->fields["U_MEMBERTYPE"]);
					$obju_HISBillIns->setudfvalue("u_amount",0);
					$obju_HISBillIns->setudfvalue("u_status","");
					//$obju_HISBillIns->setudfvalue("u_priority",$objRs->fields["U_PRIORITY"]);
					$obju_HISBillIns->privatedata["header"] = $obju_HISBills;
					$actionReturn = $obju_HISBillIns->add();
					if (!$actionReturn) break;
				}			
			}
						
			if ($actionReturn) {						
				if ($u_disccode!="") {
					$objRs->queryopen("select * from u_hishealthins where code='$u_disccode' and u_priority=0");
					while ($objRs->queryfetchrow("NAME")) {
						$obju_HISBillIns->prepareadd();
						$obju_HISBillIns->docid = $obju_HISBills->docid;
						$obju_HISBillIns->lineid = getNextIdByBranch("u_hisbillins",$objConnection);
						$obju_HISBillIns->setudfvalue("u_inscode",$objRs->fields["CODE"]);
						$obju_HISBillIns->setudfvalue("u_hmo",$objRs->fields["U_HMO"]);
						$obju_HISBillIns->setudfvalue("u_scdisc",$objRs->fields["U_SCDISC"]);
						$obju_HISBillIns->setudfvalue("u_amount",0);
						$obju_HISBillIns->setudfvalue("u_status","");
						$obju_HISBillIns->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillIns->add();
						if (!$actionReturn) break;
					}			
				}
			}	
			$obju_HISBills->setudfvalue("u_amount",($tamount));
			$obju_HISBills->setudfvalue("u_discamount",($tdiscamount));
			$obju_HISBills->setudfvalue("u_insamount",($tinsamount));
			$obju_HISBills->setudfvalue("u_hmoamount",($thmoamount));
			$obju_HISBills->setudfvalue("u_netamount",($tnetamount));
			$obju_HISBills->setudfvalue("u_dpamount",($tdpamount));
			$obju_HISBills->setudfvalue("u_dueamount",($tamount-$dpamt));
	
			$obju_HISBills->setudfvalue("u_dpamt",($dpamt));
			$obju_HISBills->setudfvalue("u_dpcr",($dpcr));
			$obju_HISBills->setudfvalue("u_dpbal",($dpbal));
			
		}
		
		if ($actionReturn) {
			foreach ($sectiondata as $key => $value) {
				$obju_HISBillSections->prepareadd();
				$obju_HISBillSections->docid = $obju_HISBills->docid;
				$obju_HISBillSections->lineid = getNextIdByBranch("u_hisbillsections",$objConnection);
				$obju_HISBillSections->setudfvalue("u_department",$key);
				$obju_HISBillSections->setudfvalue("u_amount",($value));	
				$obju_HISBillSections->setudfvalue("u_runbal",0);	
				$obju_HISBillSections->privatedata["header"] = $obju_HISBills;
				$actionReturn = $obju_HISBillSections->add();
				if (!$actionReturn) break;
			}	
		}
			
		if ($actionReturn) {
			$lineno=0;
			foreach ($feedata as $key => $fees) {
				if ($key=="Credits/Partial Payments") continue;
				if ($key=="Hospital Fees") {
					$lineno++;
					$obju_HISBillFees->prepareadd();
					$obju_HISBillFees->docid = $obju_HISBills->docid;
					$obju_HISBillFees->lineid = getNextIdByBranch("u_hisbillfees",$objConnection);
					$obju_HISBillFees->setudfvalue("u_doctorid","");
					$obju_HISBillFees->setudfvalue("u_feetype","Hospital Fees");	
					$obju_HISBillFees->setudfvalue("u_amount",($fees["amount"]));	
					$obju_HISBillFees->setudfvalue("u_discamount",0);	
					$obju_HISBillFees->setudfvalue("u_insamount",0);	
					$obju_HISBillFees->setudfvalue("u_caserate1",0);	
					$obju_HISBillFees->setudfvalue("u_caserate2",0);	
					$obju_HISBillFees->setudfvalue("u_hmoamount",0);	
					$obju_HISBillFees->setudfvalue("u_pnamount",0);	
					$obju_HISBillFees->setudfvalue("u_netamount",($fees["amount"]));	
					$obju_HISBillFees->setudfvalue("u_suffix","/".$lineno);
					$obju_HISBillFees->setudfvalue("u_paidamount",0);	
					$obju_HISBillFees->privatedata["header"] = $obju_HISBills;
					$actionReturn = $obju_HISBillFees->add();
				} else {
					if ($fees["amount"]>0) {
						$lineno++;
						$obju_HISBillFees->prepareadd();
						$obju_HISBillFees->docid = $obju_HISBills->docid;
						$obju_HISBillFees->lineid = getNextIdByBranch("u_hisbillfees",$objConnection);
						$obju_HISBillFees->setudfvalue("u_doctorid",$key);
						$obju_HISBillFees->setudfvalue("u_feetype","Professional Fees");	
						$obju_HISBillFees->setudfvalue("u_amount",($fees["amount"]));
						$obju_HISBillFees->setudfvalue("u_discamount",0);	
						$obju_HISBillFees->setudfvalue("u_insamount",0);	
						$obju_HISBillFees->setudfvalue("u_caserate1",0);	
						$obju_HISBillFees->setudfvalue("u_caserate2",0);	
						$obju_HISBillFees->setudfvalue("u_hmoamount",0);	
						$obju_HISBillFees->setudfvalue("u_pnamount",0);	
						$obju_HISBillFees->setudfvalue("u_netamount",($fees["amount"]));	
						$obju_HISBillFees->setudfvalue("u_suffix","/".$lineno);
						$obju_HISBillFees->setudfvalue("u_paidamount",0);	
						$obju_HISBillFees->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillFees->add();
					}		
					if ($fees["amount2"]>0) {
						$lineno++;
						$obju_HISBillFees->prepareadd();
						$obju_HISBillFees->docid = $obju_HISBills->docid;
						$obju_HISBillFees->lineid = getNextIdByBranch("u_hisbillfees",$objConnection);
						$obju_HISBillFees->setudfvalue("u_doctorid",$key);
						$obju_HISBillFees->setudfvalue("u_feetype","Professional Materials");	
						$obju_HISBillFees->setudfvalue("u_amount",($fees["amount2"]));
						$obju_HISBillFees->setudfvalue("u_discamount",0);	
						$obju_HISBillFees->setudfvalue("u_insamount",0);	
						$obju_HISBillFees->setudfvalue("u_caserate1",0);	
						$obju_HISBillFees->setudfvalue("u_caserate2",0);	
						$obju_HISBillFees->setudfvalue("u_hmoamount",0);	
						$obju_HISBillFees->setudfvalue("u_pnamount",0);	
						$obju_HISBillFees->setudfvalue("u_netamount",($fees["amount2"]));	
						$obju_HISBillFees->setudfvalue("u_suffix","/".$lineno);
						$obju_HISBillFees->setudfvalue("u_paidamount",0);	
						$obju_HISBillFees->privatedata["header"] = $obju_HISBills;
						$actionReturn = $obju_HISBillFees->add();
					}		
				}
				if (!$actionReturn) break;
			}	
		}	
		if ($actionReturn) {
			foreach ($feedata as $key => $fees) {
				if ($key!="Credits/Partial Payments") continue;
				$lineno++;
				$obju_HISBillFees->prepareadd();
				$obju_HISBillFees->docid = $obju_HISBills->docid;
				$obju_HISBillFees->lineid = getNextIdByBranch("u_hisbillfees",$objConnection);
				$obju_HISBillFees->setudfvalue("u_doctorid","");
				$obju_HISBillFees->setudfvalue("u_feetype","Credits/Partial Payments");	
				$obju_HISBillFees->setudfvalue("u_amount",($fees["amount"]));	
				$obju_HISBillFees->setudfvalue("u_discamount",0);	
				$obju_HISBillFees->setudfvalue("u_insamount",0);	
				$obju_HISBillFees->setudfvalue("u_caserate1",0);	
				$obju_HISBillFees->setudfvalue("u_caserate2",0);	
				$obju_HISBillFees->setudfvalue("u_hmoamount",0);	
				$obju_HISBillFees->setudfvalue("u_pnamount",0);	
				$obju_HISBillFees->setudfvalue("u_netamount",($fees["amount"]));	
				$obju_HISBillFees->setudfvalue("u_suffix","/".$lineno);
				$obju_HISBillFees->setudfvalue("u_paidamount",0);	
				$obju_HISBillFees->privatedata["header"] = $obju_HISBills;
				$actionReturn = $obju_HISBillFees->add();
				if (!$actionReturn) break;
			}	
		}		
		if ($actionReturn) {
			foreach ($trxdata as $key => $value) {
				$obju_HISBillTrxs->prepareadd();
				$obju_HISBillTrxs->docid = $obju_HISBills->docid;
				$obju_HISBillTrxs->lineid = getNextIdByBranch("u_hisbilltrxs",$objConnection);
				$obju_HISBillTrxs->setudfvalue("u_docdate",$value["docdate"]);
				$obju_HISBillTrxs->setudfvalue("u_doctime",$value["doctime"]);
				$obju_HISBillTrxs->setudfvalue("u_docno",$value["docno"]);
				$obju_HISBillTrxs->setudfvalue("u_department",$value["department"]);
				$obju_HISBillTrxs->setudfvalue("u_doctype",$value["doctype"]);
				$obju_HISBillTrxs->setudfvalue("u_docdesc",$value["docdesc"]);
				$obju_HISBillTrxs->setudfvalue("u_docstatus",$value["docstatus"]);
				$obju_HISBillTrxs->setudfvalue("u_comdisc",($value["comdisc"]));	
				$obju_HISBillTrxs->setudfvalue("u_amount",($value["amount"]));	
				$obju_HISBillTrxs->setudfvalue("u_balance",($value["balance"]));	
				$obju_HISBillTrxs->setudfvalue("u_runbal",0);	
				$obju_HISBillTrxs->privatedata["header"] = $obju_HISBills;
				$actionReturn = $obju_HISBillTrxs->add();
				if (!$actionReturn) break;
			}	
		}	
		
		if ($actionReturn) $actionReturn = $obju_HISBills->add();
		//if ($actionReturn) $actionReturn = raiseError("executeTaskpostprebillGPSHIS");
		return $actionReturn;
	}
	
?>