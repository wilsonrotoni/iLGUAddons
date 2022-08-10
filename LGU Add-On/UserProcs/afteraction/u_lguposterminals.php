<?php
//        $objRs1 = new recordset(null,$objConnection);
//        
//        $objRs1->queryopen("select u_requiredreceiptopeningregister from U_LGUSETUP");
//	if ($objRs1->queryfetchrow("NAME")) {
//		$page->privatedata["requiredreceiptopeningregister"] = $objRs1->fields["u_requiredreceiptopeningregister"];
//	}
//        $page->toolbar->setaction("update",false);
	if (isEditMode()) {
                $page->toolbar->addbutton("u_addposseries","Add Official Receipt","u_AddPosSeriresGPSLGUAddon()","left");
	}

?> 
