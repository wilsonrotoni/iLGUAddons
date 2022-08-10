<?php 

	include_once("./classes/projectgroups.php");
	include_once("./classes/projects.php");
	include_once("./classes/employees.php");
	include_once("./classes/items.php");
	include_once("./classes/customers.php");
	include_once("./utils/companies.php");
	include_once("./utils/sdk.php");

	function onBeforeAddEventemployeesGPSLGUAcctg($objTable) {
		global $objConnection;
		$actionReturn = true;
		
		if ($objTable->lastname!="") $objTable->fullname = $objTable->lastname;
		if ($objTable->firstname!="") $objTable->fullname .= ", " . $objTable->firstname;
		if ($objTable->middlename!="") $objTable->fullname .= " " . $objTable->middlename;
		
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("select custgroup from customergroups where groupname='Employee'");
		if (!$objRs->queryfetchrow("NAME")) {
			return raiseError("Customer group Employee must be define.");
		}
		$custgroup = $objRs->fields["custgroup"];

		$gldata = array();
		$gldata["formatcode"] = $objTable->getudfvalue("u_glacctno");
		if ($objTable->getudfvalue("u_glacctno")=="") {
			$gldata= getBranchGLAcctNo($thisbranch,"U_ADVOEACCT");
			if ($gldata["formatcode"]=="") {
				return raiseError("G/L Acct [Advance to OE] is not maintained.");
			}				
		}					
		$objCustomers = new customers(null,$objConnection);
		if (!$objCustomers->getbykey("AOE-".$objTable->empid)) {
			$objCustomers->prepareadd();
			$objCustomers->docid = getNextIdByBranch('customers',$objConnection);
			$objCustomers->custno = "AOE-".$objTable->empid;
			$objCustomers->custclass = "C";
			$objCustomers->currency = $_SESSION["currency"];
			$objCustomers->custgroup = $custgroup;
			$objCustomers->paymentterm = 1;
			$objCustomers->debtpayacctno = $gldata["formatcode"];
			$objCustomers->advanceacctno = $gldata["formatcode"];
		}	
		$objCustomers->custname = $objTable->fullname;
		$objCustomers->isvalid = iif($objTable->empstatus=='Active',1,0);

		if ($objCustomers->rowstat=="N") $actionReturn = $objCustomers->add();
		else $actionReturn = $objCustomers->update($objCustomers->custno,$objCustomers->rcdversion);
		//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventemployeesGPSLGUAcctg");
		return $actionReturn;
	}

	function onBeforeUpdateEventemployeesGPSLGUAcctg($objTable) {
		global $objConnection;
		$actionReturn = true;
		
		if ($objTable->lastname!="") $objTable->fullname = $objTable->lastname;
		if ($objTable->firstname!="") $objTable->fullname .= ", " . $objTable->firstname;
		if ($objTable->middlename!="") $objTable->fullname .= " " . $objTable->middlename;
		
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("select custgroup from customergroups where groupname='Employee'");
		if (!$objRs->queryfetchrow("NAME")) {
			return raiseError("Customer group Employee must be define.");
		}
		$custgroup = $objRs->fields["custgroup"];

		$gldata = array();
		$gldata["formatcode"] = $objTable->getudfvalue("u_glacctno");
		if ($objTable->getudfvalue("u_glacctno")=="") {
			$gldata= getBranchGLAcctNo($thisbranch,"U_ADVOEACCT");
			if ($gldata["formatcode"]=="") {
				return raiseError("G/L Acct [Advances to OE] is not maintained.");
			}				
		}					
		$objCustomers = new customers(null,$objConnection);
		if (!$objCustomers->getbykey("AOE-".$objTable->empid)) {
			$objCustomers->prepareadd();
			$objCustomers->docid = getNextIdByBranch('customers',$objConnection);
			$objCustomers->custno = "AOE-".$objTable->empid;
			$objCustomers->custclass = "C";
			$objCustomers->currency = $_SESSION["currency"];
			$objCustomers->custgroup = $custgroup;
			$objCustomers->paymentterm = 1;
			$objCustomers->debtpayacctno = $gldata["formatcode"];
			$objCustomers->advanceacctno = $gldata["formatcode"];
		}	
		$objCustomers->custname = $objTable->fullname;
		$objCustomers->isvalid = iif($objTable->empstatus=='Active',1,0);

		if ($objCustomers->rowstat=="N") $actionReturn = $objCustomers->add();
		else $actionReturn = $objCustomers->update($objCustomers->custno,$objCustomers->rcdversion);

		//if ($actionReturn)  $actionReturn = raiseError("onBeforeUpdateEventemployeesGPSLGUAcctg");
		return $actionReturn;
	}


?>
