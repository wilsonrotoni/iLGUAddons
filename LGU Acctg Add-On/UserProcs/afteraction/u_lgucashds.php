<?php

$objRs = new recordset(null,$objConnection);

$objRs->queryopen("SELECT U_CASHDENCODER, U_CASHDAPPROVER FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
if ($objRs->queryfetchrow("NAME")) {
	$page->privatedata["encoder"] = $objRs->fields["U_CASHDENCODER"];
	$page->privatedata["approver"] = $objRs->fields["U_CASHDAPPROVER"];
}

if (isEditMode()) {
	if ($page->getitemstring("docstatus")!="D") {
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_bptype",false);
		$page->businessobject->items->seteditable("u_bpcode",false);
		$page->businessobject->items->seteditable("u_bpname",false);
		$page->businessobject->items->seteditable("u_profitcenter",false);
		$page->businessobject->items->seteditable("u_profitcentername",false);
		$page->businessobject->items->seteditable("u_osno",false);
		$page->businessobject->items->seteditable("u_refundglacctno",false);
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_jevseries",false);
		$page->businessobject->items->seteditable("u_jevno",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_autonegatedvalues",false);
		$page->businessobject->items->seteditable("u_apbpcode",false);
		$page->businessobject->items->seteditable("u_advall",false);
		$objGrids[0]->setaction("add",false);
		$objGrids[0]->setaction("edit",false);
		$objGrids[0]->setaction("delete",false);

		$objGrids[3]->setaction("add",false);
		$objGrids[3]->setaction("edit",false);
		$objGrids[3]->setaction("delete",false);

		$deleteoption = false;
		
		if (($page->getitemstring("docstatus")=="O" && $page->privatedata["approver"]==1)) {
			$canceloption = true;
			$movetodraft = true;
		}
		
	} else {
		$objGrids[1]->columnattributes("u_selected","");
		$objGrids[2]->columnattributes("u_selected","");

	}
} else {
	$objRs->queryopen("SELECT U_PROVINCE, U_MUNICIPALITY, U_ASSISTANT, U_ACCOUNTANT, U_TREASURER, U_MAYOR FROM COMPANIES");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_province",$objRs->fields["U_PROVINCE"]);
		$page->setitem("u_municipality",$objRs->fields["U_MUNICIPALITY"]);
		$page->setitem("u_assistant",$objRs->fields["U_ASSISTANT"]);
		$page->setitem("u_accountant",$objRs->fields["U_ACCOUNTANT"]);
		$page->setitem("u_treasurer",$objRs->fields["U_TREASURER"]);
		$page->setitem("u_mayor",$objRs->fields["U_MAYOR"]);
	}
	
}

?>