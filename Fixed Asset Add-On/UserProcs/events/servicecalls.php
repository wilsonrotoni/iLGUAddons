<?php
	require_once("./utils/branches.php");
	
	function onBeforeAddEventservicecallsGPSFixedAsset(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventservicecallsGPSFixedAsset");
		return $actionReturn;	
	}

	function onBeforeUpdateEventservicecallsGPSFixedAsset(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($actionReturn && $objTable->scstatus!=$objTable->fields["SCSTATUS"] && $objTable->scstatus == -2 && $objTable->sctype=="BR") {
			$objRs = new recordset(null,$objConnection);
			$obju_FAXMTAL = new masterdatalinesschema_br(null,$objConnection,"u_faxmtal");
			$costs=0;
			$glacctno = getBranchGLAcctNo($_SESSION["branch"],"U_FASERVICECLEARACCT");
			
			if ($glacctno["formatcode"]=="") return raiseError("Fixed Asset Service Clearing Account is not maintained.");
			
			$objRs->queryopen("select emprefno from customerequipmentcard where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and insid='$objTable->insid'");
			if (!$objRs->queryfetchrow()) return raiseError("Unable to retrieve Customr Equipment Card [$objTable->insid].");
			$fa = $objRs->fields[0];
			if ($fa=="") return raiseError("Customr Equipment Card - Control No is not maintained for Fixed Asset posting.");
			
			$objRs->queryopen("select sum(quantity*itemcost) as cost from servicecallitemparts where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and callid='$objTable->callid'");
			if ($objRs->queryfetchrow()) $costs += $objRs->fields[0];
			$objRs->queryopen("select sum(linetotal) as cost from servicecalllabor where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and callid='$objTable->callid'");
			if ($objRs->queryfetchrow()) $costs += $objRs->fields[0];
			
			if ($costs>0) {
				$obju_FAXMTAL->prepareadd();
				$obju_FAXMTAL->lineid = getNextIdByBranch("u_faxmtal",$objConnection);
				$obju_FAXMTAL->setudfvalue("u_itemcode","");
				$obju_FAXMTAL->setudfvalue("u_itemdesc",$objTable->scsubject);
				$obju_FAXMTAL->setudfvalue("u_srcdoc",$objTable->callid);
				$obju_FAXMTAL->setudfvalue("u_srcname","Service");
				$obju_FAXMTAL->setudfvalue("u_srcline",0);
				$obju_FAXMTAL->setudfvalue("u_srcwhs","");
				$obju_FAXMTAL->setudfvalue("u_acquidate",$objTable->closedate);
				$obju_FAXMTAL->setudfvalue("u_amount",$costs);
				$obju_FAXMTAL->setudfvalue("u_cost",$costs);
				$obju_FAXMTAL->setudfvalue("u_contraglacctno",$glacctno["formatcode"]);
				$obju_FAXMTAL->setudfvalue("u_assettype","C");
				$obju_FAXMTAL->setudfvalue("u_assetcode",$fa);
				$actionReturn = $obju_FAXMTAL->add();			
			}	
		}	
		
		//if ($actionReturn) $actionReturn = raiseError("onUpdateEventservicecallsGPSFixedAsset");
		return $actionReturn;
	}

	function onBeforeDeleteEventservicecallsGPSFixedAsset($objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		return $actionReturn;
	}


?>