<?php

$objRs = new recordset(null,$objConnection);

$objRs->queryopen("SELECT U_OBRENCODER, U_OBRAPPROVER FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
if ($objRs->queryfetchrow("NAME")) {
	$page->privatedata["encoder"] = $objRs->fields["U_OBRENCODER"];
	$page->privatedata["approver"] = $objRs->fields["U_OBRAPPROVER"];
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
		$page->businessobject->items->seteditable("u_pono",false);
		$page->businessobject->items->seteditable("u_billno",false);
		$page->businessobject->items->seteditable("u_adjobrno",false);
		$page->businessobject->items->seteditable("u_expclass",false);
		$page->businessobject->items->seteditable("u_date",false);
		//$page->businessobject->items->seteditable("u_checkbank",false);
		//$page->businessobject->items->seteditable("u_checkbankacctno",false);
		//$page->businessobject->items->seteditable("u_checkno",false);
		$page->businessobject->items->seteditable("u_checkamount",false);
		//$page->businessobject->items->seteditable("u_jevseries",false);
		//$page->businessobject->items->seteditable("u_jevno",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_vatable",false);
		$page->businessobject->items->seteditable("u_lvatperc",false);
		$page->businessobject->items->seteditable("u_levatperc",false);
		$page->businessobject->items->seteditable("u_lvatamount",false);
		$page->businessobject->items->seteditable("u_levatamount",false);
		//$page->businessobject->items->seteditable("u_tf",false);
		//$page->businessobject->items->seteditable("u_tfbank",false);
		//$page->businessobject->items->seteditable("u_tfbankacctno",false);
		$objGrids[0]->setaction("add",false);
		$objGrids[0]->setaction("edit",false);
		$objGrids[0]->setaction("delete",false);
		$deleteoption = false;
		
		if (($page->getitemstring("docstatus")=="O" && $page->privatedata["approver"]==1)) {
			$canceloption = true;
			$movetodraft = true;
		}

	} else {
		//$objGrids[1]->columnattributes("u_selected","");
		//$objGrids[2]->columnattributes("u_selected","");
	
		$objGrids[0]->addbutton("u_copy","[Copy]","u_copyGLGPSLGUAcctg()","left");
		//if ($page->getitemnumeric("u_tf")==1) {
		//	$page->businessobject->items->seteditable("u_tfbank",true);
		//	$page->businessobject->items->seteditable("u_tfbankacctno",true);
		//}
	}

	$objRs->queryopen("SELECT B.MIGRATEDATE FROM COMPANIES A INNER JOIN BRANCHES B ON B.COMPANYCODE=A.COMPANYCODE AND B.BRANCHCODE='".$_SESSION["branch"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		//$page->privatedata["migratedate"] = $objRs->fields["MIGRATEDATE"];
	}
	
} else {
	$objGrids[0]->addbutton("u_copy","[Copy]","u_copyGLGPSLGUAcctg()","left");

	$objRs->queryopen("SELECT A.U_PROVINCE, A.U_MUNICIPALITY, A.U_ASSISTANT, A.U_ACCOUNTANT, A.U_TREASURER, A.U_MAYOR, B.MIGRATEDATE FROM COMPANIES A INNER JOIN BRANCHES B ON B.COMPANYCODE=A.COMPANYCODE AND B.BRANCHCODE='".$_SESSION["branch"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		//$page->privatedata["migratedate"] = $objRs->fields["MIGRATEDATE"];
		$page->setitem("u_province",$objRs->fields["U_PROVINCE"]);
		$page->setitem("u_municipality",$objRs->fields["U_MUNICIPALITY"]);
		$page->setitem("u_assistant",$objRs->fields["U_ASSISTANT"]);
		$page->setitem("u_accountant",$objRs->fields["U_ACCOUNTANT"]);
		$page->setitem("u_treasurer",$objRs->fields["U_TREASURER"]);
		$page->setitem("u_mayor",$objRs->fields["U_MAYOR"]);
	}
}


?>