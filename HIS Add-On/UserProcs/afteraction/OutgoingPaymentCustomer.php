<?php
	
	if (isEditMode()) {
	} else {
		$page->setitem("u_doctime",currenttime());
		$page->popup->deleteitem("popupCopyDocumentFrom","Credit Decisions");
		$page->popup->additem("popupCopyDocumentFrom","Cash Advances","popupCopyDocumentFromu_HISCashAdvances()");
		
		$objRs = new recordset(null,$objConnection);
		if ($u_trxtype=="") {
			$objRs->queryopen("select u_outpayseries from users where userid='".$_SESSION["userid"]."' and u_outpayseries<>''");
			if ($objRs->queryfetchrow("NAME")) {
				$page->setitem("docseries", $objRs->fields["u_outpayseries"]);
				$page->setitem("docno", getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("docseries"),formatDateToDB(currentdate()),$objConnection,false));
			}	
		}
		
	}
?>