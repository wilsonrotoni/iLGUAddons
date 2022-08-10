<?php
        $objRs1 = new recordset(null,$objConnection);
        $objRs1->queryopen("select A.U_ITDEPT from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs1->queryfetchrow("NAME")) {
		$page->privatedata["itdept"] = $objRs1->fields["U_ITDEPT"];
                $page->privatedata["userid"] = $_SESSION["userid"];
	}
        
        $objRs2 = new recordset(null,$objConnection);
        $objRs2->queryopen("SELECT A.U_CTCBASICFEE,B.NAME AS U_CTCBASICNAME,B.U_AMOUNT AS U_CTCBASICAMOUNT,A.U_CTCGROSSFEE,C.NAME AS U_CTCGROSSFEENAME,B.U_PENALTYCODE AS U_CTCPENFEECODE,B.U_PENALTYDESC AS U_CTCPENFEENAME
                                  ,A.U_TRANSFERTAXFEE,D.NAME AS U_TRANSFERTAXFEENAME,D.U_PENALTYCODE AS U_TRANSFERTAXPENFEECODE,D.U_PENALTYDESC AS U_TRANSFERTAXPENFEENAME
                                  ,A.U_TRANSFERTAXINTFEE,E.NAME AS U_TRANSFERTAXINTFEENAME
                                  ,A.U_FRANCHISETAXFEE,G.NAME AS U_FRANCHISETAXFEENAME,G.U_PENALTYCODE AS U_FRANCHISETAXPENFEECODE,G.U_PENALTYDESC AS U_FRANCHISETAXPENFEENAME
                                  ,A.U_FRANCHISETAXINTFEE,H.NAME AS U_FRANCHISETAXINTFEENAME
                                  ,A.U_PROCESSINGFEE,F.NAME AS U_PROCESSINGFEENAME,F.U_AMOUNT AS U_PROCESSINGFEEAMOUNT
                                FROM U_LGUSETUP A     
                                LEFT JOIN U_LGUFEES B ON B.CODE=A.U_CTCBASICFEE
                                LEFT JOIN U_LGUFEES C ON C.CODE=A.U_CTCGROSSFEE
                                LEFT JOIN U_LGUFEES D ON D.CODE=A.U_TRANSFERTAXFEE
                                LEFT JOIN U_LGUFEES E ON E.CODE=A.U_TRANSFERTAXINTFEE
                                LEFT JOIN U_LGUFEES F ON F.CODE=A.U_PROCESSINGFEE
                                LEFT JOIN U_LGUFEES G ON G.CODE=A.U_FRANCHISETAXFEE
                                LEFT JOIN U_LGUFEES H ON H.CODE=A.U_FRANCHISETAXINTFEE ");
        
	if ($objRs2->queryfetchrow("NAME")) {
		$page->privatedata["ctcbasicfeecode"] = $objRs2->fields["U_CTCBASICFEE"];
		$page->privatedata["ctcbasicfeename"] = $objRs2->fields["U_CTCBASICNAME"];
		$page->privatedata["ctcbasicfeeamount"] = $objRs2->fields["U_CTCBASICAMOUNT"];
		$page->privatedata["ctcpenfeecode"] = $objRs2->fields["U_CTCPENFEECODE"];
		$page->privatedata["ctcpenfeename"] = $objRs2->fields["U_CTCPENFEENAME"];
		$page->privatedata["ctcgrossfeecode"] = $objRs2->fields["U_CTCGROSSFEE"];
		$page->privatedata["ctcgrossfeename"] = $objRs2->fields["U_CTCGROSSFEENAME"];
                $page->privatedata["year"] = date('Y');
    	}
        
	if (isEditMode()) {
                $objGrids[0]->dataentry = false;
                $page->businessobject->items->seteditable("docstatus",false);
                $page->businessobject->items->seteditable("u_docseries",false);
                $page->businessobject->items->seteditable("u_gross",false);
                $page->businessobject->items->seteditable("u_ischeque",false);
                $page->businessobject->items->seteditable("u_checkno",false);
                $page->businessobject->items->seteditable("u_checkbank",false);
                $page->businessobject->items->seteditable("u_checkdate",false);
		$page->businessobject->items->seteditable("u_isonlinepayment",false);
	} else {
             $page->toolbar->addbutton("u_submit","F4 - Post Transaction","PostTransaction()","left");
        }

	$objMaster->reportaction = "QS";
//	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

