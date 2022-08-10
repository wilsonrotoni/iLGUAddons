<?php
        $objRs1 = new recordset(null,$objConnection);
        
        $objRs1->queryopen("select A.SUSER from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs1->queryfetchrow("NAME")) {
		$page->privatedata["suser"] = $objRs1->fields["SUSER"];
	}
        
	if (isEditMode()) {
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("select docstatus from u_rpfaas1 where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno='".$page->getitemstring("u_arpno")."'");
		if ($objRs->queryfetchrow("NAME")) {
			if ($objRs->fields["docstatus"]!="Encoding" && $page->privatedata["suser"] == "N") $page->setvar("formAccess","R");
		}
		
	}
	
	$page->toolbar->setaction("print",false);
	
?>