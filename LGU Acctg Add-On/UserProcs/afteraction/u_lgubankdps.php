<?php

$objRs = new recordset(null,$objConnection);

$objRs->queryopen("SELECT U_BANKDEPOSITENCODER, U_BANKDEPOSITAPPROVER FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
if ($objRs->queryfetchrow("NAME")) {
	$page->privatedata["encoder"] = $objRs->fields["U_BANKDEPOSITENCODER"];
	$page->privatedata["approver"] = $objRs->fields["U_BANKDEPOSITAPPROVER"];
}

if (isEditMode()) {
	if ($page->getitemstring("u_jevauto")=="1") {
		if ($page->getitemstring("docstatus")!="D") {
			$page->businessobject->items->seteditable("u_date",false);
			$page->businessobject->items->seteditable("u_bank",false);
			$page->businessobject->items->seteditable("u_bankacctno",false);
			$page->businessobject->items->seteditable("u_amount",false);
			$page->businessobject->items->seteditable("u_jevseries",false);
			$page->businessobject->items->seteditable("u_jevno",false);
			$page->businessobject->items->seteditable("u_cashglacctno",false);
			$page->businessobject->items->seteditable("u_remarks",false);
			$deleteoption = false;
			
			if (($page->getitemstring("docstatus")=="O" && $page->privatedata["approver"]==1)) {
				$movetodraft = true;
				$canceloption = true;
			}
			
		} else {
			$addoptions=true;
		}
	}	
} else {
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT U_PROVINCE, U_MUNICIPALITY, U_ASSISTANT, U_ACCOUNTANT, U_TREASURER, U_MAYOR, U_BANKDPJEV, U_BANKDPJEVSERIES FROM COMPANIES");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_province",$objRs->fields["U_PROVINCE"]);
		$page->setitem("u_municipality",$objRs->fields["U_MUNICIPALITY"]);
		$page->setitem("u_assistant",$objRs->fields["U_ASSISTANT"]);
		$page->setitem("u_accountant",$objRs->fields["U_ACCOUNTANT"]);
		$page->setitem("u_treasurer",$objRs->fields["U_TREASURER"]);
		$page->setitem("u_mayor",$objRs->fields["U_MAYOR"]);
		$page->setitem("u_jevauto",$objRs->fields["U_BANKDPJEV"]);
		$page->setitem("u_jevseries",$objRs->fields["U_BANKDPJEVSERIES"]);
	}
	
}

$page->businessobject->items->setvisible("u_jevseries",false);
$page->businessobject->items->setvisible("u_jevno",false);
if ($page->getitemstring("u_jevauto")=="1") {
	$page->businessobject->items->setvisible("u_jevseries",true);
	$page->businessobject->items->setvisible("u_jevno",true);
}

?>