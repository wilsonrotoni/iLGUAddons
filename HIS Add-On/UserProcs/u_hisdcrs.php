<?php

	include_once("./sls/countries.php");
	include_once("./sls/banks.php");
	include_once("./sls/housebankaccounts.php"); 
 

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

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

$postdata = array();

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $page;
	global $postdata;
	
	if ($page->getitemstring("u_closedate")!="" && $page->getitemstring("u_closetime")!="") {
		$postdata["u_opendate"] = $page->getitemstring("u_opendate");
		$postdata["u_opentime"] = $page->getitemstring("u_opentime");
		$postdata["u_closedate"] = $page->getitemstring("u_closedate");
		$postdata["u_closetime"] = $page->getitemstring("u_closetime");
		$postdata["u_mode"] = $page->getitemstring("u_mode");
		$postdata["u_openamount"] = $page->getitemdecimal("u_openamount");
	}
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $postdata;
	
	$objRs = new recordset(null,$objConnection);
	$objRs2 = new recordset(null,$objConnection);
	
	if ($postdata["u_closetime"]!="") {
		$page->setitem("u_opendate",$postdata["u_opendate"]);
		$page->setitem("u_opentime",$postdata["u_opentime"]);
		$page->setitem("u_closedate",$postdata["u_closedate"]);
		$page->setitem("u_closetime",$postdata["u_closetime"]);
		$page->setitem("u_mode",$postdata["u_mode"]);
		$page->setitem("u_openamount",formatNumericAmount($postdata["u_openamount"]));
		
		getRecordsGPSHIS();
		
		$objRs->queryopen("select U_DENOMINATION from U_HISCASHDENOMINATIONS ORDER BY U_DENOMINATION DESC");
		while ($objRs->queryfetchrow("NAME")) {
			$objGrids[0]->addrow();
			$objGrids[0]->setitem(null,"u_denomination",formatNumericAmount($objRs->fields["U_DENOMINATION"]));
			$objGrids[0]->setitem(null,"u_count",0);
	
			$objGrids[1]->addrow();
			$objGrids[1]->setitem(null,"u_denomination",formatNumericAmount($objRs->fields["U_DENOMINATION"]));
			$objGrids[1]->setitem(null,"u_count",0);
		}

		$objGrids[1]->addrow();
		$objGrids[1]->setitem(null,"u_denomination","0");
		$objGrids[1]->setitem(null,"u_count",0);
		
	} else {
		$page->setitem("u_mode",1);
		
		//$objRs->queryopen("select CODE from U_POSTERMINALS where BRANCH='".$_SESSION["branch"]."' AND NAME='".$_SERVER['REMOTE_ADDR']."'");
		//if ($objRs->queryfetchrow("NAME")) {
			$terminalid = $_SERVER['REMOTE_ADDR'];
			$objRs2->queryopen("select DOCNO,U_CASHIERID from U_HISDCRS where COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND  U_CASHIERID='".$_SESSION["userid"]."' and U_STATUS='O'");
			if ($objRs2->queryfetchrow("NAME")) {
				$page->setkey($objRs2->fields["DOCNO"]);
				modeEdit();
				return false;
				/*} else {
					header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=Cash Register is currently opened by user ['.$objRs2->fields["U_CASHIERID"].'].'); 
				}*/	
			} else {
				$page->setitem("u_terminalid",$terminalid);
				$page->setitem("u_cashierid",$_SESSION["userid"]);
				$page->setitem("u_opendate",currentdate());
				$page->setitem("u_opentime",currenttime());
			}	
		//} else {
		//	header ('Location: accessdenied.php?&requestorId='.$page->getvarstring("requestorId").'&messageText=No Terminal maintained for IP Address ['.$_SERVER['REMOTE_ADDR'].'].'); 
		//}
		$objGrids[0]->clear();
		$objGrids[1]->clear();
		$objRs->queryopen("select U_DENOMINATION from U_HISCASHDENOMINATIONS ORDER BY U_DENOMINATION DESC");
		while ($objRs->queryfetchrow("NAME")) {
			$objGrids[0]->addrow();
			$objGrids[0]->setitem(null,"u_denomination",formatNumericAmount($objRs->fields["U_DENOMINATION"]));
			$objGrids[0]->setitem(null,"u_count",0);
	
			$objGrids[1]->addrow();
			$objGrids[1]->setitem(null,"u_denomination",formatNumericAmount($objRs->fields["U_DENOMINATION"]));
			$objGrids[1]->setitem(null,"u_count",0);
		}

		$objGrids[1]->addrow();
		$objGrids[1]->setitem(null,"u_denomination","0");
		$objGrids[1]->setitem(null,"u_count",0);
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
	if ($page->getitemstring("u_mode")=="0") getRecordsGPSHIS();
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

function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T2":
			if ($column=="u_denomination") {
				if ($label=="0") $label = "bag of coins";
			}	
			break;
		case "T7":
			if ($column=="u_userid") {
				$objRs->queryopen("select username from users where userid='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["username"];
			}	
			break;
	}
}

function getRecordsGPSHIS() { 
	global $objConnection;
	global $page;
	global $objGrids;
	global $company;
	global $branch;
	
	$objRs = new recordset(null,$objConnection);
	if ($page->getitemstring("u_status")!="C") {
			//$page->setitem("u_closedate",currentdate());
			//$page->setitem("u_closetime",currenttime());
		//}	
		$u_startseqno="";
		$u_endseqno="";
		$u_seqnocount=0;
		$u_cncount=0;
		$u_mscount=0;
		$u_rfcount=0;
		$u_cashamount=0;
		$u_incashamount=0;
		$u_outcashamount=0;
		
		$u_checkamount=0;
		$u_creditcardamount=0;
		$paytypes = array();
		if ($page->getitemstring("u_closedate")!="") {
			if ($page->getitemstring("u_mode")=="0") {
				$whereclause = " AND A.U_CASHIERID='".$page->getitemstring("u_cashierid")."' AND CONCAT(A.DOCDATE,' ',A.U_DOCTIME) BETWEEN '".$page->getitemdate("u_opendate").' '.formatTimeToDB($page->getitemstring("u_opentime"))."' AND '".$page->getitemdate("u_closedate").' '.formatTimeToDB($page->getitemstring("u_closetime"),true)."'";
				$whereclause2 = " AND B.U_CASHIERID='".$page->getitemstring("u_cashierid")."' AND CONCAT(B.U_DOCDATE,' ',B.U_DOCTIME) BETWEEN '".$page->getitemdate("u_opendate").' '.formatTimeToDB($page->getitemstring("u_opentime"))."' AND '".$page->getitemdate("u_closedate").' '.formatTimeToDB($page->getitemstring("u_closetime"),true)."'";
				$whereclause3 = " AND D.U_CASHIERID='".$page->getitemstring("u_cashierid")."' AND CONCAT(D.U_DOCDATE,' ',D.U_DOCTIME) BETWEEN '".$page->getitemdate("u_opendate").' '.formatTimeToDB($page->getitemstring("u_opentime"))."' AND '".$page->getitemdate("u_closedate").' '.formatTimeToDB($page->getitemstring("u_closetime"),true)."'";
			} else {
				//AND A.U_CASHIERID<>'' 
				$whereclause = " AND CONCAT(A.DOCDATE,' ',A.U_DOCTIME) BETWEEN '".$page->getitemdate("u_opendate").' '.formatTimeToDB($page->getitemstring("u_opentime"))."' AND '".$page->getitemdate("u_closedate").' '.formatTimeToDB($page->getitemstring("u_closetime"),true)."'";
				$whereclause2 = " AND B.U_CASHIERID<>'' AND CONCAT(B.U_DOCDATE,' ',B.U_DOCTIME) BETWEEN '".$page->getitemdate("u_opendate").' '.formatTimeToDB($page->getitemstring("u_opentime"))."' AND '".$page->getitemdate("u_closedate").' '.formatTimeToDB($page->getitemstring("u_closetime"),true)."'";
				$whereclause3 = " AND D.U_CASHIERID<>'' AND CONCAT(D.U_DOCDATE,' ',D.U_DOCTIME) BETWEEN '".$page->getitemdate("u_opendate").' '.formatTimeToDB($page->getitemstring("u_opentime"))."' AND '".$page->getitemdate("u_closedate").' '.formatTimeToDB($page->getitemstring("u_closetime"),true)."'";
			}	
			//var_dump($whereclause);
			$sql = "SELECT 'CSH' AS PAYTYPE, A.DOCNO, 'RC' as DOCTYPE, A.DOCDATE, A.CASHAMOUNT AS AMOUNT, A.BPNAME, 
						CONCAT(IF(B.U_PREPAID=2,'Partial Payments','Bill Payments'),' (',IF(B.U_REFTYPE='IP','In-Patient','Out-Patient'),')') AS DEPARTMENT, A.DOCSTAT AS DOCSTATUS, A.CANCELLEDBY AS U_CANCELLEDBY, IFNULL(B.U_CANCELLEDREMARKS,A.OTHERREMARKS) AS U_CANCELLEDREMARKS,
						'' AS U_REFTYPE, '0' AS U_PREPAID, '' AS BANK, '' AS BANKNAME, '' AS CHECKNO,
						'' AS CREDITCARD, '' AS CREDITCARDNO, '' AS CREDITCARDNAME, '' AS CARDEXPIRETEXT, '' AS APPROVALNO,
						'' AS U_HMO  
					FROM COLLECTIONS A
						LEFT JOIN U_HISPOS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCHCODE AND B.DOCNO=A.DOCNO 
					WHERE A.COMPANY = '$company' AND A.BRANCHCODE = '$branch' AND A.PCTYPE='' AND A.CASHAMOUNT>0 ".$whereclause." 
					
					UNION ALL 
					
					SELECT 'CSH' AS PAYTYPE, A.DOCNO, 'PY' as DOCTYPE, A.DOCDATE, A.CASHAMOUNT * -1 AS AMOUNT, A.BPNAME, 
						if(A.DOCTYPE='A','Expenses','Refunds') as DEPARTMENT, A.DOCSTAT AS DOCSTATUS, A.CANCELLEDBY AS U_CANCELLEDBY, A.OTHERREMARKS AS U_CANCELLEDREMARKS, 
						'' AS U_REFTYPE, '0' AS U_PREPAID, '' AS BANK, '' AS BANKNAME, '' AS CHECKNO,
						'' AS CREDITCARD, '' AS CREDITCARDNO, '' AS CREDITCARDNAME, '' AS CARDEXPIRETEXT, '' AS APPROVALNO,
						'' AS U_HMO  
					FROM PAYMENTS A 
					WHERE A.COMPANY = '$company' AND A.BRANCHCODE = '$branch' AND A.PCTYPE='' AND A.CASHAMOUNT>0 AND A.DOCSTAT NOT IN ('CN','D') ".$whereclause. " 
					
					UNION ALL 
					
					SELECT 'CSH' AS PAYTYPE, A.DOCNO, 'CS' AS DOCTYPE, A.DOCDATE, A.CASHAMOUNT AS AMOUNT, A.BPNAME, 	
						IFNULL(C.NAME,IF(B.U_REFTYPE='IP','In-Patient','Out-Patient')) AS DEPARTMENT, B.DOCSTATUS, B.U_CANCELLEDBY, B.U_CANCELLEDREMARKS,
						'' AS U_REFTYPE, '0' AS U_PREPAID, '' AS BANK, '' AS BANKNAME, '' AS CHECKNO,
						'' AS CREDITCARD, '' AS CREDITCARDNO, '' AS CREDITCARDNAME, '' AS CARDEXPIRETEXT, '' AS APPROVALNO,
						'' AS U_HMO      
					FROM U_HISPOS B 
						INNER JOIN ARINVOICES A ON A.COMPANY=B.COMPANY AND A.BRANCH=B.BRANCH AND A.DOCNO=B.DOCNO 
						LEFT JOIN U_HISSECTIONS C ON C.CODE=B.U_DEPARTMENT 
					WHERE B.COMPANY = '$company' AND B.BRANCH = '$branch' AND A.CASHAMOUNT>0 ".$whereclause2."
					
					UNION ALL
					
					SELECT 'CHQ' AS PAYTYPE, A.DOCNO, 'RC' AS DOCTYPE, A.DOCDATE, B.AMOUNT, A.BPNAME, 
									C.U_DEPARTMENT, A.DOCSTAT AS DOCSTATUS, A.CANCELLEDBY AS U_CANCELLEDBY, A.OTHERREMARKS AS U_CANCELLEDREMARKS, 
									C.U_REFTYPE, C.U_PREPAID, B.BANK, D.BANKNAME, B.CHECKNO,
									'' AS CREDITCARD, '' AS CREDITCARDNO, '' AS CREDITCARDNAME, '' AS CARDEXPIRETEXT, '' AS APPROVALNO,
									E.U_HMO    
								FROM COLLECTIONS A 
									INNER JOIN COLLECTIONSCHEQUES B ON B.COMPANY = A.COMPANY AND B.BRANCH = A.BRANCHCODE AND B.DOCNO = A.DOCNO 
									LEFT JOIN U_HISPOS C ON C.COMPANY=A.COMPANY AND C.BRANCH=A.BRANCHCODE AND C.DOCNO=A.DOCNO 
									LEFT JOIN BANKS D ON D.BANK=B.BANK 
									LEFT JOIN U_HISHEALTHINS E ON E.CODE=A.BPCODE
								WHERE A.COMPANY = '$company' AND A.BRANCHCODE = '$branch' AND B.DEPOSITED=0 AND B.CLEARED=0 AND A.DOCSTAT NOT IN ('CN','BC','D') ".$whereclause."
								
					UNION ALL 	
							
					SELECT 'CRC' AS PAYTYPE, A.DOCNO, 'RC' AS DOCTYPE, A.DOCDATE, B.AMOUNT AS AMOUNT, A.BPNAME,
									CONCAT(IF(D.U_PREPAID=2,'Partial Payments','Bill Payments'),' (',IF(D.U_REFTYPE='IP','In-Patient','Out-Patient'),')') AS DEPARTMENT, A.DOCSTAT AS DOCSTATUS, A.CANCELLEDBY AS U_CANCELLEDBY, IFNULL(D.U_CANCELLEDREMARKS,A.OTHERREMARKS) AS U_CANCELLEDREMARKS, 
									'' AS U_REFTYPE, '0' AS U_PREPAID, '' AS BANK, '' AS BANKNAME, '' AS CHECKNO, 
									B.CREDITCARD, B.CREDITCARDNO, C.CREDITCARDNAME, B.CARDEXPIRETEXT, B.APPROVALNO,
									'' AS U_HMO   
								FROM COLLECTIONS A 
									INNER JOIN COLLECTIONSCREDITCARDS B ON B.COMPANY = A.COMPANY AND B.BRANCH = A.BRANCHCODE AND B.DOCNO = A.DOCNO 
									INNER JOIN CREDITCARDS C ON C.CREDITCARD=B.CREDITCARD 
									LEFT JOIN U_HISPOS D ON D.COMPANY=A.COMPANY AND D.BRANCH=A.BRANCHCODE AND D.DOCNO=A.DOCNO 
								WHERE A.COMPANY = '$company' AND A.BRANCHCODE = '$branch' ".$whereclause3." 
								
								UNION ALL 
								
								SELECT 'CRC' AS PAYTYPE, A.DOCNO, 'CS' AS DOCTYPE, A.DOCDATE, B.AMOUNT AS AMOUNT, A.BPNAME, 
									IFNULL(E.NAME,IF(D.U_REFTYPE='IP','In-Patient','Out-Patient')) AS DEPARTMENT, D.DOCSTATUS, D.U_CANCELLEDBY, D.U_CANCELLEDREMARKS, 
									'' AS U_REFTYPE, '0' AS U_PREPAID, '' AS BANK, '' AS BANKNAME, '' AS CHECKNO, 
									B.CREDITCARD, B.CREDITCARDNO, C.CREDITCARDNAME, B.CARDEXPIRETEXT, B.APPROVALNO,
									'' AS U_HMO  
								FROM U_HISPOS D 
									INNER JOIN ARINVOICES A ON A.COMPANY=D.COMPANY AND A.BRANCH=D.BRANCH AND A.DOCNO=D.DOCNO 
									INNER JOIN ARINVOICECREDITCARDS B ON B.COMPANY = A.COMPANY AND B.BRANCH = A.BRANCH AND B.DOCID = A.DOCID 
									INNER JOIN CREDITCARDS C ON C.CREDITCARD=B.CREDITCARD LEFT JOIN U_HISSECTIONS E ON E.CODE=D.U_DEPARTMENT  
								WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' ".$whereclause3."";
			//$sql .= " UNION ALL SELECT A.DOCNO, 'SR' AS DOCTYPE, 'Sales Return' as DOCTYPEDESC, A.DOCDATE, A.BPCODE, A.BPNAME, A.CASHAMOUNT*-1 AS AMOUNT, A.DEPARTMENT, -1 AS GLACCTNO, A.CURRENCY, A.CURRENCYRATE FROM ARCREDITMEMOS A WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND TRXTYPE='POS' AND A.DOCSTATUS NOT IN ('D') AND A.CASHAMOUNT<>0 AND A.DEPOSITED=0 ".$whereclause;
			$sql .= " ORDER BY DOCNO";		
			
			//var_dump($sql);
			
			$objRs->queryopen($sql);
			while ($objRs->queryfetchrow("NAME")) {
				//if ($objRs->fields["DOCNO"]=="OR324554") continue;
				
				if ($objRs->fields["DOCTYPE"]=="RC" || $objRs->fields["DOCTYPE"]=="CS") {
					if ($u_startseqno=="") $u_startseqno=substr($objRs->fields["DOCNO"],2);
					else {
						if ($u_endseqno+1<substr($objRs->fields["DOCNO"],2)) {
							for ($i=($u_endseqno+1);$i<substr($objRs->fields["DOCNO"],2);$i++) {
								$u_mscount++;
								$objGrids[6]->addrow();
								$objGrids[6]->setitem(null,"u_refno","OR".str_pad( $i, strlen($objRs->fields["DOCNO"])-2, "0", STR_PAD_LEFT));			
								$objGrids[6]->setitem(null,"u_payee","");			
								$objGrids[6]->setitem(null,"u_paytype","");			
								$objGrids[6]->setitem(null,"u_amount",formatNumeric(0));			
							}
						}	
					}
					$u_endseqno=substr($objRs->fields["DOCNO"],2);
					$u_seqnocount++;
					if ($objRs->fields["DOCSTATUS"]=="CN" || $objRs->fields["DOCSTATUS"]=="BC" || $objRs->fields["DOCSTATUS"]=="D") {
						$u_cncount++;
					}
				}
				switch ($objRs->fields["PAYTYPE"]) {
					case "CSH":
						if (!$paytypes[$objRs->fields["DEPARTMENT"]]) $paytypes[$objRs->fields["DEPARTMENT"]] = 0;
						
						if ($objRs->fields["DOCSTATUS"]!="CN" && $objRs->fields["DOCSTATUS"]!="BC" && $objRs->fields["DOCSTATUS"]!="D") {
							switch ($objRs->fields["DOCTYPE"]) {
								case "RC":
								case "CS":
									$u_incashamount+=$objRs->fields["AMOUNT"];
									$u_cashamount+=$objRs->fields["AMOUNT"];
									$paytypes[$objRs->fields["DEPARTMENT"]] += $objRs->fields["AMOUNT"];
									break;
								default:	
									$u_rfcount++;
									$u_outcashamount-=$objRs->fields["AMOUNT"];
									$u_cashamount+=$objRs->fields["AMOUNT"];
									$paytypes[$objRs->fields["DEPARTMENT"]] += $objRs->fields["AMOUNT"];
									break;
							}		
							$objGrids[3]->addrow();
							$objGrids[3]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);			
							$objGrids[3]->setitem(null,"u_payee",$objRs->fields["BPNAME"]);			
							$objGrids[3]->setitem(null,"u_paytype",$objRs->fields["DEPARTMENT"]);			
							$objGrids[3]->setitem(null,"u_amount",formatNumeric($objRs->fields["AMOUNT"],"amount"));			
						} else {
							$objGrids[6]->addrow();
							$objGrids[6]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);			
							$objGrids[6]->setitem(null,"u_payee",$objRs->fields["BPNAME"]);			
							$objGrids[6]->setitem(null,"u_paytype",$objRs->fields["DEPARTMENT"]);			
							$objGrids[6]->setitem(null,"u_userid",$objRs->fields["U_CANCELLEDBY"]);			
							if ($objRs->fields["DOCSTATUS"]=="D") $objGrids[6]->setitem(null,"u_remarks","Draft");	
							else $objGrids[6]->setitem(null,"u_remarks",$objRs->fields["U_CANCELLEDREMARKS"]);			
							
							$objGrids[6]->setitem(null,"u_amount",formatNumeric($objRs->fields["AMOUNT"],"amount"));			
						}	
						break;
					case "CHQ":
						if ($objRs->fields["U_HMO"]!="") {
							switch ($objRs->fields["U_HMO"]) {
								case 0:
									$department = "PHIC";
									break;
								default:
									$department = "HMO/LGU/CO";
									break;
							}		
						} else {
							$department = $objRs->fields["DEPARTMENT"];
							if ($department=="") {
								if ($department=="" && $objRs->fields["U_PREPAID"]=="3") $department = "Bill Payments";
								if ($department=="") $department = "Partial Payments";
								if ($objRs->fields["U_REFTYPE"]!="") {
									$department .= iif($objRs->fields["U_REFTYPE"]=="IP"," (In-Patient)"," (Out-Patient)");
								}
							}	
						}	
						if ($objRs->fields["DOCSTATUS"]!="CN" && $objRs->fields["DOCSTATUS"]!="BC" && $objRs->fields["DOCSTATUS"]!="D") {
						
							$u_checkamount+=$objRs->fields["AMOUNT"];
							if (!isset($paytypes[$department])) $paytypes[$department]=0;
							$paytypes[$department] += $objRs->fields["AMOUNT"];
			
							$objGrids[4]->addrow();
							$objGrids[4]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);			
							$objGrids[4]->setitem(null,"u_payee",$objRs->fields["BPNAME"]);			
							$objGrids[4]->setitem(null,"u_paytype",$department);			
							$objGrids[4]->setitem(null,"u_bank",$objRs->fields["BANK"]);			
							$objGrids[4]->setitem(null,"u_bankname",$objRs->fields["BANKNAME"]);			
							$objGrids[4]->setitem(null,"u_checkno",$objRs->fields["CHECKNO"]);			
							$objGrids[4]->setitem(null,"u_amount",formatNumeric($objRs->fields["AMOUNT"],"amount"));			
						} else {
							$objGrids[6]->addrow();
							$objGrids[6]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);			
							$objGrids[6]->setitem(null,"u_payee",$objRs->fields["BPNAME"]);			
							$objGrids[6]->setitem(null,"u_paytype",$department);			
							
							if ($objRs->fields["DOCSTATUS"]=="D") $objGrids[6]->setitem(null,"u_remarks","Draft");	
							else $objGrids[6]->setitem(null,"u_remarks",$objRs->fields["U_CANCELLEDREMARKS"]);			
							
							$objGrids[6]->setitem(null,"u_amount",formatNumeric($objRs->fields["AMOUNT"],"amount"));			
						}	
						break;	
					case "CRC":
						if ($objRs->fields["DOCSTATUS"]!="CN" && $objRs->fields["DOCSTATUS"]!="BC" && $objRs->fields["DOCSTATUS"]!="D") {
						
							$u_creditcardamount+=$objRs->fields["AMOUNT"];
							if ($objRs->fields["DEPARTMENT"]!="") $paytypes[$objRs->fields["DEPARTMENT"]] += $objRs->fields["AMOUNT"];
							else $paytypes[$objRs->fields["BPNAME"]] += $objRs->fields["AMOUNT"];
			
							$objGrids[5]->addrow();
							$objGrids[5]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);			
							$objGrids[5]->setitem(null,"u_payee",$objRs->fields["BPNAME"]);			
							$objGrids[5]->setitem(null,"u_paytype",iif($objRs->fields["DEPARTMENT"]!="",$objRs->fields["DEPARTMENT"],$objRs->fields["BPNAME"]));			
							$objGrids[5]->setitem(null,"u_creditcard",$objRs->fields["CREDITCARD"]);			
							$objGrids[5]->setitem(null,"u_creditcardno",$objRs->fields["CREDITCARDNO"]);			
							$objGrids[5]->setitem(null,"u_creditcardname",$objRs->fields["CREDITCARDNAME"]);			
							$objGrids[5]->setitem(null,"u_expiredate",$objRs->fields["CARDEXPIRETEXT"]);			
							$objGrids[5]->setitem(null,"u_approvalno",$objRs->fields["APPROVALNO"]);			
							$objGrids[5]->setitem(null,"u_amount",formatNumeric($objRs->fields["AMOUNT"],"amount"));			
						} else {
							$objGrids[6]->addrow();
							$objGrids[6]->setitem(null,"u_refno",$objRs->fields["DOCNO"]);			
							$objGrids[6]->setitem(null,"u_payee",$objRs->fields["BPNAME"]);			
							$objGrids[6]->setitem(null,"u_paytype",iif($objRs->fields["DEPARTMENT"]!="",$objRs->fields["DEPARTMENT"],$objRs->fields["BPNAME"]));			
							
							if ($objRs->fields["DOCSTATUS"]=="D") $objGrids[6]->setitem(null,"u_remarks","Draft");	
							else $objGrids[6]->setitem(null,"u_remarks",$objRs->fields["U_CANCELLEDREMARKS"]);			
							
							$objGrids[6]->setitem(null,"u_amount",formatNumeric($objRs->fields["AMOUNT"],"amount"));			
						}					
						break;	
				}		
				
			}
			$objRs->queryclose();
		}	
		
		$page->setitem("u_startseqno",$u_startseqno);
		$page->setitem("u_endseqno",$u_endseqno);
		$page->setitem("u_seqnocount",$u_seqnocount);
		$page->setitem("u_rfcount",$u_rfcount);
		$page->setitem("u_cncount",$u_cncount);
		$page->setitem("u_mscount",$u_mscount);
		//$u_cncount=0;
		//$u_mscount=0;

		$page->setitem("u_cashamount",formatNumericAmount($u_cashamount));
		$page->setitem("u_cashamount",formatNumericAmount($u_cashamount));
		$page->setitem("u_incashamount",formatNumericAmount($u_incashamount));
		$page->setitem("u_outcashamount",formatNumericAmount($u_outcashamount));
		$page->setitem("u_cashvariance",formatNumericAmount($page->getitemdecimal("u_closeamount")-($page->getitemdecimal("u_openamount")+$page->getitemdecimal("u_incashamount")-$page->getitemdecimal("u_outcashamount"))));


		$page->setitem("u_checkamount",formatNumericAmount($u_checkamount));
		$page->setitem("u_creditcardamount",formatNumericAmount($u_creditcardamount));

		$page->setitem("u_totalamount",formatNumericAmount($page->getitemdecimal("u_cashamount")+$page->getitemdecimal("u_checkamount")+$page->getitemdecimal("u_creditcardamount")));

		//$page->setitem("u_cashafterbankdp",formatNumericAmount($page->getitemdecimal("u_cashamount")-$page->getitemdecimal("u_bankdpamount")));
		$page->setitem("u_checkafterbankdp",formatNumericAmount($page->getitemdecimal("u_checkamount")-$page->getitemdecimal("u_bankdp2amount")));
		
		$objGrids[2]->clear();
		ksort($paytypes);
		foreach ($paytypes as $key => $value) {
			$objGrids[2]->addrow();
			$objGrids[2]->setitem(null,"u_paytype",$key);
			$objGrids[2]->setitem(null,"u_amount",formatNumericAmount($value));
			
		}	
	
	}	
	return true;
}


$page->businessobject->items->seteditable("u_terminalid",false);
$page->businessobject->items->seteditable("u_cashierid",false);
//$page->businessobject->items->seteditable("u_date",false);
//$page->businessobject->items->seteditable("u_time",false);
$page->businessobject->items->seteditable("u_status",false);
$page->businessobject->items->seteditable("u_openamount",false);
$page->businessobject->items->seteditable("u_closeamount",false);
$page->businessobject->items->seteditable("u_salesamount",false);
$page->businessobject->items->seteditable("u_cashvariance",false);
$page->businessobject->items->seteditable("u_incashamount",false);
$page->businessobject->items->seteditable("u_outcashamount",false);
$page->businessobject->items->seteditable("u_cashamount",false);
$page->businessobject->items->seteditable("u_checkamount",false);
$page->businessobject->items->seteditable("u_creditcardamount",false);
$page->businessobject->items->seteditable("u_insamount",false);
$page->businessobject->items->seteditable("u_discamount",false);
$page->businessobject->items->seteditable("u_creditamount",false);
$page->businessobject->items->seteditable("u_wtaxamount",false);
$page->businessobject->items->seteditable("u_totalamount",false);
$page->businessobject->items->seteditable("u_bankdpamount",false);
$page->businessobject->items->seteditable("u_cashafterbankdp",false);
$page->businessobject->items->seteditable("u_bankdp2amount",false);
$page->businessobject->items->seteditable("u_checkafterbankdp",false);


$page->businessobject->items->seteditable("u_startseqno",false);
$page->businessobject->items->seteditable("u_endseqno",false);
$page->businessobject->items->seteditable("u_seqnocount",false);


$objGrids[0]->columndataentry("u_count","type","label");
$objGrids[0]->columnattributes("u_denomination","disabled");
$objGrids[0]->columnwidth("u_denomination",15);

$objGrids[1]->columndataentry("u_count","type","label");
$objGrids[1]->columnattributes("u_denomination","disabled");
$objGrids[1]->columnwidth("u_denomination",15);

$objGrids[2]->columndataentry("u_paytype","type","label");
$objGrids[2]->columnattributes("u_amount","disabled");
$objGrids[2]->columnwidth("u_paytype",26);
$objGrids[2]->columnwidth("u_amount",15);

//$objGrids[0]->dataentry = false;
$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->setaction("add",false);
$objGrids[0]->setaction("reset",false);
$objGrids[0]->width = 200;

$objGrids[1]->automanagecolumnwidth = false;
$objGrids[1]->columnwidth("u_denomination",12);
$objGrids[1]->columnwidth("u_count",9);
$objGrids[1]->setaction("add",false);
$objGrids[1]->setaction("reset",false);
$objGrids[1]->width = 200;

$objGrids[2]->automanagecolumnwidth = false;
$objGrids[2]->setaction("add",false);
$objGrids[2]->setaction("reset",false);
$objGrids[2]->width = 360;
$objGrids[2]->dataentry = false;


$objGrids[3]->columnwidth("u_refno",12);
$objGrids[3]->columnwidth("u_payee",25);
$objGrids[3]->columnwidth("u_paytype",26);
$objGrids[3]->dataentry = false;

$objGrids[4]->columnwidth("u_refno",12);
$objGrids[4]->columnwidth("u_payee",25);
$objGrids[4]->columnwidth("u_paytype",26);
$objGrids[4]->columnwidth("u_bankname",20);
$objGrids[4]->columnwidth("u_checkno",10);
$objGrids[4]->columnvisibility("u_bank",false);
$objGrids[4]->dataentry = false;

$objGrids[5]->columnwidth("u_refno",12);
$objGrids[5]->columnwidth("u_payee",25);
$objGrids[5]->columnwidth("u_paytype",26);
$objGrids[5]->columnwidth("u_creditcardno",16);
$objGrids[5]->columnwidth("u_creditcardname",20);
$objGrids[5]->columnwidth("u_approvalno",12);
$objGrids[5]->columnvisibility("u_creditcard",false);
$objGrids[5]->automanagecolumnwidth = false;
$objGrids[5]->dataentry = false;

$objGrids[6]->columnwidth("u_refno",12);
$objGrids[6]->columnwidth("u_payee",25);
$objGrids[6]->columnwidth("u_paytype",26);
$objGrids[6]->columnwidth("u_remarks",30);
$objGrids[6]->columntitle("u_userid","Cancelled By");
$objGrids[6]->dataentry = false;

$objGrids[7]->columnwidth("u_bank",20);
$objGrids[7]->columnwidth("u_bankacctno",20);
//$objGrids[1]->columninput("u_amount","type","text");
$objGrids[7]->columndataentry("u_bank","type","select");
$objGrids[7]->columndataentry("u_bank","options",array("loadhousebanksbycountry",$_SESSION["branch"].":PH",":"));
$objGrids[7]->columndataentry("u_bankacctno","type","select");
$objGrids[7]->columndataentry("u_bankacctno","options",array("loadhousebankaccounts","",":"));
$objGrids[7]->width = 450;

$objGrids[8]->columnwidth("u_bank",20);
$objGrids[8]->columnwidth("u_bankacctno",20);
//$objGrids[1]->columninput("u_amount","type","text");
$objGrids[8]->columndataentry("u_bank","type","select");
$objGrids[8]->columndataentry("u_bank","options",array("loadhousebanksbycountry",$_SESSION["branch"].":PH",":"));
$objGrids[8]->columndataentry("u_bankacctno","type","select");
$objGrids[8]->columndataentry("u_bankacctno","options",array("loadhousebankaccounts","",":"));
$objGrids[8]->width = 450;

$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("new",false);
$page->toolbar->setaction("update",false);
//$page->toolbar->setaction("print",false);
//$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);

$page->businessobject->resettabindex();
$page->businessobject->items->settabindex("u_opendate");
$page->businessobject->items->settabindex("u_opentime");
$page->businessobject->items->settabindex("u_closedate");
$page->businessobject->items->settabindex("u_closetime");


//$page->setvar("AddonGoBack","u_Home");
?> 

