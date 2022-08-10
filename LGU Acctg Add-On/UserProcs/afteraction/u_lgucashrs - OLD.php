<?php

if (isEditMode()) {
	if ($page->getitemstring("docstatus")!="D") {
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_advbpcode",false);
		$page->businessobject->items->seteditable("u_advall",false);
		$page->businessobject->items->seteditable("u_bpcode",false);
		$page->businessobject->items->seteditable("u_bpname",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_jevseries",false);
		$page->businessobject->items->seteditable("u_jevno",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_datefrom",false);
		$page->businessobject->items->seteditable("u_dateto",false);
		$page->businessobject->items->seteditable("u_cashglacctno",false);
		$page->businessobject->items->seteditable("u_cashamount",false);
		
		$objGrids[2]->columnattributes("u_tf","disabled");
		
		$objGrids[0]->setaction("add",false);
		$objGrids[0]->setaction("edit",false);
		$objGrids[0]->setaction("delete",false);
		$objGrids[1]->setaction("add",false);
		$objGrids[1]->setaction("edit",false);
		$objGrids[1]->setaction("delete",false);
		$deleteoption = false;
		$canceloption = true;
		
		if (($page->getitemstring("docstatus")=="O")) {
			$movetodraft = true;
		}
		
	}
} else {
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("SELECT U_PROVINCE, U_MUNICIPALITY, U_ASSISTANT, U_ACCOUNTANT, U_TREASURER, U_MAYOR, U_BANKDPJEVSERIES FROM COMPANIES");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_province",$objRs->fields["U_PROVINCE"]);
		$page->setitem("u_municipality",$objRs->fields["U_MUNICIPALITY"]);
		$page->setitem("u_assistant",$objRs->fields["U_ASSISTANT"]);
		$page->setitem("u_accountant",$objRs->fields["U_ACCOUNTANT"]);
		$page->setitem("u_treasurer",$objRs->fields["U_TREASURER"]);
		$page->setitem("u_mayor",$objRs->fields["U_MAYOR"]);
		$page->setitem("u_jevseries",$objRs->fields["U_BANKDPJEVSERIES"]);
	}
	
}

?>