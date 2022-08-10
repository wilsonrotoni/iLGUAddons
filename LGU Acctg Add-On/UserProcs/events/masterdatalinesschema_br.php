<?php 

	include_once("./classes/masterdataschema_br.php");
	include_once("./series.php");

	function onAddEventmasterdatalinesschema_brGPSLGUAcctg($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_lgubudgetgls":
				if ($objTable->getudfvalue("u_rl")>0) {
					$obju_LGuBudgetReleaseLogs = new masterdataschema_br(NULL,$objConnection,"u_lgubudgetreleaselogs");
					$obju_LGuBudgetReleaseLogs->prepareadd();
					$obju_LGuBudgetReleaseLogs->code = str_pad( getNextId("u_lgubudgetreleaselogs",$objConnection), 7, "0", STR_PAD_LEFT);
					$obju_LGuBudgetReleaseLogs->name = $obju_LGuBudgetReleaseLogs->code;
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_yr",$objTable->privatedata["header"]->getudfvalue("u_yr"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_profitcenter",$objTable->privatedata["header"]->getudfvalue("u_profitcenter"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_glacctno",$objTable->getudfvalue("u_glacctno"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_glacctname",$objTable->getudfvalue("u_glacctname"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_slcode",$objTable->getudfvalue("u_slcode"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_sldesc",$objTable->getudfvalue("u_sldesc"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq1",$objTable->getudfvalue("u_rlq1"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq2",$objTable->getudfvalue("u_rlq2"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq3",$objTable->getudfvalue("u_rlq3"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq4",$objTable->getudfvalue("u_rlq4"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rl",$objTable->getudfvalue("u_rl"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlamt",$objTable->getudfvalue("u_rlamt"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_date",currentdateDB());
					$actionReturn = $obju_LGuBudgetReleaseLogs->add();
				}
				break;
		}	
		//if ($actionReturn) $actionReturn = raiseError("onAddEventmasterdatalinesschema_brFTWheeltek");		
		return $actionReturn;
	}
	
	function onBeforeUpdateEventmasterdatalinesschema_brGPSLGUAcctg($objTable) {
		global $objConnection;
		$actionReturn = true;
		switch ($objTable->dbtable) {
			case "u_lgubudgetgls":
				if (intval($objTable->fields["U_RLQ1"])!=intval($objTable->getudfvalue("u_rlq1")) || intval($objTable->fields["U_RLQ2"])!=intval($objTable->getudfvalue("u_rlq2")) || intval($objTable->fields["U_RLQ3"])!=intval($objTable->getudfvalue("u_rlq3")) || intval($objTable->fields["U_RLQ4"])!=intval($objTable->getudfvalue("u_rlq4")) || intval($objTable->fields["U_RL"])!=intval($objTable->getudfvalue("u_rl")) || intval($objTable->fields["U_RLAMT"])!=intval($objTable->getudfvalue("u_rlamt"))) {
					$obju_LGuBudgetReleaseLogs = new masterdataschema_br(NULL,$objConnection,"u_lgubudgetreleaselogs");
					$obju_LGuBudgetReleaseLogs->prepareadd();
					$obju_LGuBudgetReleaseLogs->code = str_pad( getNextId("u_lgubudgetreleaselogs",$objConnection), 7, "0", STR_PAD_LEFT);
					$obju_LGuBudgetReleaseLogs->name = $obju_LGuBudgetReleaseLogs->code;
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_yr",$objTable->privatedata["header"]->getudfvalue("u_yr"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_profitcenter",$objTable->privatedata["header"]->getudfvalue("u_profitcenter"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_glacctno",$objTable->getudfvalue("u_glacctno"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_glacctname",$objTable->getudfvalue("u_glacctname"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_slcode",$objTable->getudfvalue("u_slcode"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_sldesc",$objTable->getudfvalue("u_sldesc"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq1",$objTable->getudfvalue("u_rlq1"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq2",$objTable->getudfvalue("u_rlq2"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq3",$objTable->getudfvalue("u_rlq3"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq4",$objTable->getudfvalue("u_rlq4"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rl",$objTable->getudfvalue("u_rl"));
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlamt",$objTable->getudfvalue("u_rlamt") - $objTable->fields["U_RLAMT"]);
					if ($objTable->getudfvalue("u_lstrldate")!="") {
						$obju_LGuBudgetReleaseLogs->setudfvalue("u_date",$objTable->getudfvalue("u_lstrldate"));
					} else {
						$obju_LGuBudgetReleaseLogs->setudfvalue("u_date",currentdateDB());
					}	
					$actionReturn = $obju_LGuBudgetReleaseLogs->add();
				}
				break;				
		}		

		return $actionReturn;
	}
	
	function onDeleteEventmasterdatalinesschema_brGPSLGUAcctg($objTable) {
		global $objConnection;
		$actionReturn = true;
		//if ($actionReturn) $actionReturn = raiseError("onDeleteEventmasterdatalinesschema_brFTWheeltek");		
		return $actionReturn;
	}	
?>
