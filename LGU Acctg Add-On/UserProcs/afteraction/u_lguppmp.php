<?php

$objRs = new recordset(null,$objConnection);

$objRs->queryopen("SELECT U_BUDGETENCODER, U_BUDGETAPPROVER FROM USERS WHERE USERID='".$_SESSION["userid"]."'");
if ($objRs->queryfetchrow("NAME")) {
	$page->privatedata["encoder"] = $objRs->fields["U_BUDGETENCODER"];
	$page->privatedata["approver"] = $objRs->fields["U_BUDGETAPPROVER"];
}
 
if (isEditMode()) {
	$page->businessobject->items->seteditable("u_yr",false);
	$page->businessobject->items->seteditable("u_profitcenter",false);
	$page->businessobject->items->seteditable("u_projcode",false);
	
	if ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" || $page->getitemstring("docstatus")=="For Approval") {
		$deleteoption = false;
		$objGrids[0]->columninput("u_yr","type","label");
		$objGrids[0]->columninput("u_m1","type","label");
		$objGrids[0]->columninput("u_m2","type","label");
		$objGrids[0]->columninput("u_m3","type","label");
		$objGrids[0]->columninput("u_m4","type","label");
		$objGrids[0]->columninput("u_m5","type","label");
		$objGrids[0]->columninput("u_m6","type","label");
		$objGrids[0]->columninput("u_m7","type","label");
		$objGrids[0]->columninput("u_m8","type","label");
		$objGrids[0]->columninput("u_m9","type","label");
		$objGrids[0]->columninput("u_m10","type","label");
		$objGrids[0]->columninput("u_m11","type","label");
		$objGrids[0]->columninput("u_m12","type","label");	
		
		$objGrids[0]->dataentry=false;	

		$objGrids[1]->columninput("u_yr","type","label");
		$objGrids[1]->columninput("u_m1","type","label");
		$objGrids[1]->columninput("u_m2","type","label");
		$objGrids[1]->columninput("u_m3","type","label");
		$objGrids[1]->columninput("u_m4","type","label");
		$objGrids[1]->columninput("u_m5","type","label");
		$objGrids[1]->columninput("u_m6","type","label");
		$objGrids[1]->columninput("u_m7","type","label");
		$objGrids[1]->columninput("u_m8","type","label");
		$objGrids[1]->columninput("u_m9","type","label");
		$objGrids[1]->columninput("u_m10","type","label");
		$objGrids[1]->columninput("u_m11","type","label");
		$objGrids[1]->columninput("u_m12","type","label");	
		
		$objGrids[1]->dataentry=false;	
		//if (($page->getitemstring("u_status")=="O")) {
		//	$movetodraft = true;
		//}
		
		$page->businessobject->items->seteditable("u_type",false);

		$page->businessobject->items->seteditable("u_preparedby",false);
		$page->businessobject->items->seteditable("u_reviewdby",false);
		$page->businessobject->items->seteditable("u_approvedby",false);
		
		$page->businessobject->items->seteditable("u_remarks",false);

		if ($page->getitemstring("docstatus")=="For Approval") {
			if ($page->privatedata["approver"]==1) {
				$page->toolbar->addbutton("u_approved","Approved","u_approvedGPSLGUAcctg()","right");
				$page->toolbar->addbutton("u_disapproved","Disapproved","u_disapprovedGPSLGUAcctg()","right");
				$page->toolbar->addbutton("u_ammend","Revised","u_revisedGPSLGUAcctg()","right");
			}			
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("update",false);
		}
		
		$deleteoption = false;
	} elseif ($page->getitemstring("docstatus")=="Draft" || $page->getitemstring("docstatus")=="For Revision") {
		$page->toolbar->setaction("new",false);
		//$page->toolbar->setaction("update",false);
		$deleteoption = false;
		if ($page->getitemstring("docstatus")=="Draft") {
			if ($_SESSION["suser"]=="Y") $deleteoption = true;
		}
		
		if ($page->privatedata["encoder"]==1 || $page->privatedata["approver"]==1) {
			$page->toolbar->addbutton("u_forapproval","For Approval","u_forApprovalGPSLGUAcctg()","right");
		}	
	}	
}

?> 

