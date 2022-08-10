

<?php
        $objRs1 = new recordset(null,$objConnection);
        $objRs = new recordset(null,$objConnection);
        
        $objRs1->queryopen("select u_requiredreceiptopeningregister from U_LGUSETUP");
	if ($objRs1->queryfetchrow("NAME")) {
		$page->privatedata["requiredreceiptopeningregister"] = $objRs1->fields["u_requiredreceiptopeningregister"];
	}
        $page->toolbar->setaction("update",false);
	if (isEditMode()) {
		if ($page->getitemstring("u_status")=="O") {
                        $page->toolbar->addbutton("u_addposseries","Add Official Receipt","u_AddPosSeriresGPSLGUAddon()","left");
			$page->toolbar->addbutton("u_closeregister","Close Register","u_closeRegisterGPSPOSAsddon()","right");
			$objGrids[1]->columninput("u_count","type","text");
		} else 	{
                        if ($page->getitemstring("u_isremitted") == 0) {
                            $objRs->queryopen("SELECT CODE,U_STATUS FROM U_LGUPOSREGISTERS WHERE U_USERID = '".$_SESSION['userid']."' ORDER BY DATECREATED DESC LIMIT 1 ");
                            if ($objRs->queryfetchrow("NAME")) {
                                if($objRs->fields["U_STATUS"] == "C" &&  $objRs->fields["CODE"] == $page->getitemstring("code")){
                                    $page->toolbar->addbutton("u_reopenregister","Re-Open  Register","u_reopenRegisterGPSPOSAsddon()","right");
                                }
                            }
                            $page->toolbar->addbutton("u_posttransactions","Post Transactions","u_PostTransactionGPSPOSAsddon()","left");
                        }
			$page->toolbar->setaction("update",false);
		}
	} else {
		$page->toolbar->addbutton("u_closeregister","Open Register","u_openRegisterGPSPOSAsddon()","right");
		$objGrids[0]->columninput("u_count","type","text");
	}

?> 

