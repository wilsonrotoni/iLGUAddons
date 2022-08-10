<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/collections.php");
	include_once("./classes/marketingdocuments.php");
	include_once("./classes/marketingdocumentitems.php");
	include_once("./series.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objRs = new recordset (NULL,$objConnection);
	$objRs2 = new recordset (NULL,$objConnection);
	
	$obju_HISIPs = new documentschema_br (NULL,$objConnection,"u_hisips");
	$obju_HISOPs = new documentschema_br (NULL,$objConnection,"u_hisops");
	//$objRs3->shareddatatype = "branch";
	//$objRs3->shareddatacode = $objRs->fields["BRANCH"];
	$ctr=0;

	if ($actionReturn){
		$objRs->queryopen("select A.DOCNO from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND (A.U_BILLING=1 OR A.U_FORCEBILLING=1 OR A.U_BEDNO<>'') AND A.DOCSTATUS='Active' AND A.U_BILLNO=''"); 
		while ($objRs->queryfetchrow("NAME")) {
			if ($obju_HISIPs->getbykey($objRs->fields["DOCNO"])) {
				$objRs2->queryopen("select sum(U_DUEAMOUNT) from (
	select SUM(IF(a.U_DISCONBILL=0,(b.U_QUANTITY-b.U_RTQTY)*b.U_PRICE,IF(b.U_ISSTAT=1,(b.U_QUANTITY-b.U_RTQTY)*b.U_STATUNITPRICE,(b.U_QUANTITY-b.U_RTQTY)*b.U_UNITPRICE))) as U_DUEAMOUNT from u_hischarges a inner join u_hischargeitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='$company' and a.branch='$branch' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and a.u_prepaid in (0,2) and docstatus not in ('CN') 
	 union all 
	select a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hiscredits a where a.company='$company' and a.branch='$branch' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and a.U_PREPAID<>2 
	 union all 
	select a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hispos a where a.company='$company' and a.branch='$branch' and a.u_reftype='IP' and a.u_refno='".$objRs->fields["DOCNO"]."' and u_prepaid in (2) and docstatus not in ('CN') and u_balance<>0) as x");
				if ($objRs2->queryfetchrow()) {
					if ($objRs2->fields[0]!=$obju_HISIPs->getudfvalue("u_totalcharges")) {
						$obju_HISIPs->setudfvalue("u_totalcharges",$objRs2->fields[0]);
						$obju_HISIPs->setudfvalue("u_availablecreditlimit",$obju_HISIPs->getudfvalue("u_creditlimit") - $obju_HISIPs->getudfvalue("u_totalcharges"));
						$obju_HISIPs->setudfvalue("u_availablecreditperc",100-((objutils::Divide($obju_HISIPs->getudfvalue("u_totalcharges"),$obju_HISIPs->getudfvalue("u_creditlimit")))*100));
						
						$actionReturn = $obju_HISIPs->update($obju_HISIPs->docno,$obju_HISIPs->rcdversion);
						if ($actionReturn) echo $objRs->fields["DOCNO"] . " = " . $obju_HISIPs->getudfvalue("u_totalcharges") . "<br>";
					} //else echo $objRs->fields["DOCNO"] . "<br>";
				}
			} else $actionReturn = raiseError("Unable to find In-Patient [".$objRs->fields["DOCNO"]."] record.");	
			if (!$actionReturn) break;
		}	
	} 

	if ($actionReturn){
		$objRs->queryopen("select A.DOCNO from U_HISOPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND (A.U_BILLING=1 OR A.U_FORCEBILLING=1) AND A.DOCSTATUS='Active' AND A.U_BILLNO=''"); 
		while ($objRs->queryfetchrow("NAME")) {
			if ($obju_HISOPs->getbykey($objRs->fields["DOCNO"])) {
				$objRs2->queryopen("select sum(U_DUEAMOUNT) from (
	select SUM(IF(a.U_DISCONBILL=0,(b.U_QUANTITY-b.U_RTQTY)*b.U_PRICE,IF(b.U_ISSTAT=1,(b.U_QUANTITY-b.U_RTQTY)*b.U_STATUNITPRICE,(b.U_QUANTITY-b.U_RTQTY)*b.U_UNITPRICE))) as U_DUEAMOUNT from u_hischarges a inner join u_hischargeitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='$company' and a.branch='$branch' and a.u_reftype='OP' and a.u_refno='".$objRs->fields["DOCNO"]."' and a.u_prepaid in (0,2) and docstatus not in ('CN') 
	 union all 
	select a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hiscredits a where a.company='$company' and a.branch='$branch' and a.u_reftype='OP' and a.u_refno='".$objRs->fields["DOCNO"]."' and a.U_PREPAID<>2 and a.U_REQUESTTYPE<>'REQ' 
	 union all 
	select a.U_BALANCE*-1 AS U_DUEAMOUNT from u_hispos a where a.company='$company' and a.branch='$branch' and a.u_reftype='OP' and a.u_refno='".$objRs->fields["DOCNO"]."' and u_prepaid in (2) and docstatus not in ('CN') and u_balance<>0) as x");
				if ($objRs2->queryfetchrow()) {
					if ($objRs2->fields[0]!=$obju_HISOPs->getudfvalue("u_totalcharges")) {
						$obju_HISOPs->setudfvalue("u_totalcharges",$objRs2->fields[0]);
						$obju_HISOPs->setudfvalue("u_availablecreditlimit",$obju_HISOPs->getudfvalue("u_creditlimit") - $obju_HISOPs->getudfvalue("u_totalcharges"));
						$obju_HISOPs->setudfvalue("u_availablecreditperc",100-((objutils::Divide($obju_HISOPs->getudfvalue("u_totalcharges"),$obju_HISOPs->getudfvalue("u_creditlimit")))*100));
						
						$actionReturn = $obju_HISOPs->update($obju_HISOPs->docno,$obju_HISOPs->rcdversion);
						if ($actionReturn) echo $objRs->fields["DOCNO"] . " = " . $obju_HISOPs->getudfvalue("u_totalcharges") . "<br>";
					}
				}
			} else $actionReturn = raiseError("Unable to find Out-Patient [".$objRs->fields["DOCNO"]."] record.");	
			if (!$actionReturn) break;
		}	
	} 
	
	if ($actionReturn) {
		$objConnection->commit();
		printf("%1 Records where successfully updated.",$ctr);
	} else { 	
		$objConnection->rollback();
		echo $_SESSION["errormessage"];
	}

	
?>
