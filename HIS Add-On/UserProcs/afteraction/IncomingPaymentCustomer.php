<?php
	
	if (isEditMode()) {
	} else {
		$page->setitem("u_doctime",currenttime());
		
		$objRs = new recordset(null,$objConnection);
		if ($u_trxtype=="") {
			$objRs->queryopen("select u_inpayseries from users where userid='".$_SESSION["userid"]."' and u_inpayseries<>''");
			if ($objRs->queryfetchrow("NAME")) {
				$page->setitem("docseries", $objRs->fields["u_inpayseries"]);
				$page->setitem("docno", getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("docseries"),formatDateToDB(currentdate()),$objConnection,false));
			}	
		}
		
	}
?>