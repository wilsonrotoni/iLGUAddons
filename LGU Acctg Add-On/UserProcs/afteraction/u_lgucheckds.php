<?php

$objRs = new recordset(null,$objConnection);

$objRs->queryopen("SELECT U_CHECKDENCODER, U_CHECKDAPPROVER FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
if ($objRs->queryfetchrow("NAME")) {
	$page->privatedata["encoder"] = $objRs->fields["U_CHECKDENCODER"];
	$page->privatedata["approver"] = $objRs->fields["U_CHECKDAPPROVER"];
}

$objRs->queryopen("SELECT CITY FROM BRANCHES WHERE COMPANYCODE='".$_SESSION["company"]."' AND BRANCHCODE='".$_SESSION["branch"]."'");
if ($objRs->queryfetchrow("NAME")) {
	$page->privatedata["city"] = $objRs->fields["CITY"];
}


if (isEditMode()) {
	if ($page->getitemstring("docstatus")!="D") {
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_osno",false);
		$page->businessobject->items->seteditable("u_bptype",false);
		$page->businessobject->items->seteditable("u_bpcode",false);
		$page->businessobject->items->seteditable("u_bpname",false);
		$page->businessobject->items->seteditable("u_profitcenter",false);
		$page->businessobject->items->seteditable("u_profitcentername",false);
		$page->businessobject->items->seteditable("u_date",false);
		$page->businessobject->items->seteditable("u_checkbank",false);
		$page->businessobject->items->seteditable("u_checkbankacctno",false);
		$page->businessobject->items->seteditable("u_paytype",false);
		$page->businessobject->items->seteditable("u_tfno",false);
		$page->businessobject->items->seteditable("u_checkno",false);
		$page->businessobject->items->seteditable("u_checkamount",false);
		$page->businessobject->items->seteditable("u_jevseries",false);
		$page->businessobject->items->seteditable("u_jevno",false);
		$page->businessobject->items->seteditable("u_remarks",false);
		$page->businessobject->items->seteditable("u_vatable",false);
		$page->businessobject->items->seteditable("u_lvatperc",false);
		$page->businessobject->items->seteditable("u_levat2perc",false);
		$page->businessobject->items->seteditable("u_lvat2perc",false);
		$page->businessobject->items->seteditable("u_levatperc",false);
		$page->businessobject->items->seteditable("u_lretperc",false);
		$page->businessobject->items->seteditable("u_lbtperc1",false);
		$page->businessobject->items->seteditable("u_lbtperc2",false);
		$page->businessobject->items->seteditable("u_lvatamount",false);
		$page->businessobject->items->seteditable("u_levatamount",false);
		$page->businessobject->items->seteditable("u_levat2amount",false);
		$page->businessobject->items->seteditable("u_lretamount",false);
		$page->businessobject->items->seteditable("u_lbtamount",false);
		$page->businessobject->items->seteditable("u_tf",false);
		$page->businessobject->items->seteditable("u_tfbank",false);
		$page->businessobject->items->seteditable("u_tfbankacctno",false);
		$page->businessobject->items->seteditable("u_refundglacctno",false);
		$page->businessobject->items->seteditable("u_refunddate",false);
		$page->businessobject->items->seteditable("u_refundno",false);
		$page->businessobject->items->seteditable("u_refundamount",false);
		$page->businessobject->items->seteditable("u_apbpcode",false);
		$page->businessobject->items->seteditable("u_advall",false);
		$page->businessobject->items->seteditable("u_city",false);
		$page->businessobject->items->seteditable("u_taxdeduction",false);

		$objGrids[0]->setaction("add",false);
		$objGrids[0]->setaction("edit",false);
		$objGrids[0]->setaction("delete",false);

		$objGrids[3]->setaction("add",false);
		$objGrids[3]->setaction("edit",false);
		$objGrids[3]->setaction("delete",false);
		$objGrids[4]->setaction("add",false);
		$objGrids[4]->setaction("edit",false);
		$objGrids[4]->setaction("delete",false);
		
		$deleteoption = false;
		
		if (($page->getitemstring("docstatus")=="O" && $page->privatedata["approver"]==1)) {
			$canceloption = true;
			$movetodraft = true;
		}

	} else {
		$objGrids[1]->columnattributes("u_selected","");
		$objGrids[2]->columnattributes("u_selected","");
	
		$objGrids[0]->addbutton("u_copy","[Copy]","u_copyGLGPSLGUAcctg()","left");
		if ($page->getitemnumeric("u_tf")==1) {
			$page->businessobject->items->seteditable("u_tfbank",true);
			$page->businessobject->items->seteditable("u_tfbankacctno",true);
		}
	}

	$objRs->queryopen("SELECT B.MIGRATEDATE FROM COMPANIES A INNER JOIN BRANCHES B ON B.COMPANYCODE=A.COMPANYCODE AND B.BRANCHCODE='".$_SESSION["branch"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["migratedate"] = $objRs->fields["MIGRATEDATE"];
	}
	
} else {
	$objGrids[0]->addbutton("u_copy","[Copy]","u_copyGLGPSLGUAcctg()","left");

	$objRs->queryopen("SELECT A.U_PROVINCE, A.U_MUNICIPALITY, A.U_ASSISTANT, A.U_ACCOUNTANT, A.U_TREASURER, A.U_MAYOR, U_APWTAXCATEGORY, B.MIGRATEDATE, A.U_CHECKDJEVSERIES FROM COMPANIES A INNER JOIN BRANCHES B ON B.COMPANYCODE=A.COMPANYCODE AND B.BRANCHCODE='".$_SESSION["branch"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["migratedate"] = $objRs->fields["MIGRATEDATE"];
		$page->setitem("u_apwtaxcategory",$objRs->fields["U_APWTAXCATEGORY"]);
		$page->setitem("u_province",$objRs->fields["U_PROVINCE"]);
		$page->setitem("u_municipality",$objRs->fields["U_MUNICIPALITY"]);
		$page->setitem("u_assistant",$objRs->fields["U_ASSISTANT"]);
		$page->setitem("u_accountant",$objRs->fields["U_ACCOUNTANT"]);
		$page->setitem("u_treasurer",$objRs->fields["U_TREASURER"]);
		$page->setitem("u_mayor",$objRs->fields["U_MAYOR"]);
		$page->setitem("u_jevseries",$objRs->fields["U_CHECKDJEVSERIES"]);
	}
}


?>