<?php

	/*$objRs->queryopen("select A.U_BPLENCODER, A.U_BPLASSESSOR, A.U_BPLAPPROVER from USERS A WHERE USERID='".$_SESSION["userid"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["bplencoder"] = $objRs->fields["U_BPLENCODER"];
		$page->privatedata["bplassessor"] = $objRs->fields["U_BPLASSESSOR"];
		$page->privatedata["bplapprover"] = $objRs->fields["U_BPLAPPROVER"];
	}*/
	
	//if ($page->privatedata["bplencoder"]=="1" || $page->privatedata["bplassessor"]=="1" || $page->privatedata["bplapprover"]=="1") {
		$page->toolbar->addbutton("u_submit","Save","formSubmit()","left");
	//}

	/*if ($page->privatedata["bplencoder"]=="1" && $page->getitemstring("u_preassbill")=="0" && $page->getitemstring("docstatus")=="Encoding") {
		$page->toolbar->addbutton("u_presassbill","Geberate Pre-Assessment Bill","u_preAssBillGPSBPLS()","left");
	}*/
	/*
	$photopath ="";
	if (isEditMode()) {	
		$objRs->queryopen("select DOCNO from U_BPLAPPS A WHERE COMPANY='".$_SESSION["company"]."' and BRANCH='".$_SESSION["branch"]."' and U_BUSINESSNAME='".$page->getitemstring("u_businessname")."' and U_APPDATE<'".$page->getitemdate("u_appdate")."' AND DOCSTATUS in ('Approved','Paid') ORDER BY U_APPDATE DESC");
		if ($objRs->queryfetchrow("NAME")) {
			$page->setitem("prevappno",$objRs->fields["DOCNO"]);
		}
	
		if ($page->getitemstring("u_preassbill")=="1") {
			$objGrids[2]->columninput("u_amount","type","label");
			$objGrids[3]->columninput("u_amount","type","label");
		}
		
		if ($page->getitemstring("docstatus")=="Encoding") {
			if ($page->privatedata["bplencoder"]=="1") $page->toolbar->addbutton("forassessment","For Assessment","u_forAssessmentGPSBPLS()","left");
		} elseif ($page->getitemstring("docstatus")=="Assessing") { 
			if ($page->privatedata["bplassessor"]=="1") $page->toolbar->addbutton("forapproval","For Approval","u_forApprovalGPSBPLS()","left");
		} elseif ($page->getitemstring("docstatus")=="Assessed") { 
			if ($page->privatedata["bplapprover"]=="1") {
				$page->toolbar->addbutton("approve","Approve","u_approveGPSBPLS()","left");
				$page->toolbar->addbutton("disapprove","Disapprove","u_disapproveGPSBPLS()","left");
				$page->businessobject->items->seteditable("u_approverremarks",true);
			}	
		} elseif ($page->getitemstring("docstatus")=="Approved" || $page->getitemstring("docstatus")=="Disapproved" ) { 
			$objGrids[0]->dataentry = false;
			$objGrids[4]->dataentry = false;
			if ($page->privatedata["bplapprover"]=="1") {
				$page->toolbar->addbutton("re-asses","Re-Assessment","u_reassessGPSBPLS()","left");
				$page->businessobject->items->seteditable("u_approverremarks",true);
			}	
		}
		
		if ($page->getitemstring("docstatus")=="Printing" || $page->getitemstring("docstatus")=="Paid" ) { 
			$page->setvar("formAccess","R");
			//$page->getitemstring("docstatus")=="Approved" ||  $page->getitemstring("docstatus")=="Disapproved" || 
		}
		
		$photopath = "../Images/".$_SESSION["company"]."/".$_SESSION["mainbranch"]."/BPLS/".$page->getitemstring("docno")."/photo";
		$page->privatedata["photopath"] = "../Images/".$_SESSION["company"]."/".$_SESSION["mainbranch"]."/BPLS/".$page->getitemstring("docno");
		//var_dump($page->privatedata["photopath"]);
		if (file_exists($photopath . ".png")) $photopath .= ".png?version=".date('YmdHis');
		elseif (file_exists($photopath . ".jpg")) $photopath .= ".jpg?version=".date('YmdHis');
		elseif (file_exists($photopath . ".gif")) $photopath .= ".gif?version=".date('YmdHis');
		elseif (file_exists($photopath . ".bmp")) $photopath .= ".bmp?version=".date('YmdHis');
		else $photopath = "./imgs/photo.jpg";
	}

	$page->privatedata["photodata"] = "";
	
	//$page->toolbar->setaction("print",false);
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select A.U_BPLKINDCHARLINK, A.U_BPLCATEGSANITARYPERMITLINK, A.U_BPLCATEGFIREINSFEELINK, A.U_ANNUALTAX, D.NAME AS U_ANNUALTAXDESC, A.U_GARBAGEFEE, B.NAME AS U_GARBAGEFEEDESC, A.U_MAYORSPERMIT, C.NAME AS U_MAYORSPERMITDESC, A.U_SANITARYINSPECTIONFEE, E.NAME AS U_SANITARYINSPECTIONFEEDESC, A.U_BPLSANITARYPERMIT, F.NAME AS U_BPLSANITARYPERMITDESC, A.U_BPLFIREINSFEE, G.NAME AS U_BPLFIREINSFEEDESC, A.U_BPLPFOFEE, H.NAME AS U_BPLPFOFEEDESC, A.U_BPLSFHEFEE, I.NAME AS U_BPLSFHEFEEDESC, A.U_BPLPLATEFEE, J.NAME AS U_BPLPLATEFEEDESC
						from U_LGUSETUP A 
							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_GARBAGEFEE
							LEFT JOIN U_LGUFEES C ON C.CODE=A.U_MAYORSPERMIT
							LEFT JOIN U_LGUFEES D ON D.CODE=A.U_ANNUALTAX
							LEFT JOIN U_LGUFEES E ON E.CODE=A.U_SANITARYINSPECTIONFEE
							LEFT JOIN U_LGUFEES F ON F.CODE=A.U_BPLSANITARYPERMIT
							LEFT JOIN U_LGUFEES G ON G.CODE=A.U_BPLFIREINSFEE
							LEFT JOIN U_LGUFEES H ON H.CODE=A.U_BPLPFOFEE
							LEFT JOIN U_LGUFEES I ON I.CODE=A.U_BPLSFHEFEE
							LEFT JOIN U_LGUFEES J ON J.CODE=A.U_BPLPLATEFEE
							");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["bplkindcharlink"] = $objRs->fields["U_BPLKINDCHARLINK"];
		$page->privatedata["bplcategsanitarypermitlink"] = $objRs->fields["U_BPLCATEGSANITARYPERMITLINK"];
		$page->privatedata["bplcategfireinsfeelink"] = $objRs->fields["U_BPLCATEGFIREINSFEELINK"];
		$page->privatedata["annualtaxcode"] = $objRs->fields["U_ANNUALTAX"];
		$page->privatedata["annualtaxdesc"] = $objRs->fields["U_ANNUALTAXDESC"];
		$page->privatedata["garbagefeecode"] = $objRs->fields["U_GARBAGEFEE"];
		$page->privatedata["garbagefeedesc"] = $objRs->fields["U_GARBAGEFEEDESC"];
		$page->privatedata["mayorspermitcode"] = $objRs->fields["U_MAYORSPERMIT"];
		$page->privatedata["mayorspermitdesc"] = $objRs->fields["U_MAYORSPERMITDESC"];
		$page->privatedata["sanitaryinspectionfeecode"] = $objRs->fields["U_SANITARYINSPECTIONFEE"];
		$page->privatedata["sanitaryinspectionfeedesc"] = $objRs->fields["U_SANITARYINSPECTIONFEEDESC"];
		$page->privatedata["sanitarypermitcode"] = $objRs->fields["U_BPLSANITARYPERMIT"];
		$page->privatedata["sanitarypermitdesc"] = $objRs->fields["U_BPLSANITARYPERMITDESC"];
		$page->privatedata["fireinspectionfeecode"] = $objRs->fields["U_BPLFIREINSFEE"];
		$page->privatedata["fireinspectionfeedesc"] = $objRs->fields["U_BPLFIREINSFEEDESC"];
		$page->privatedata["pfofeecode"] = $objRs->fields["U_BPLPFOFEE"];
		$page->privatedata["pfofeedesc"] = $objRs->fields["U_BPLPFOFEEDESC"];
		$page->privatedata["sfhefeecode"] = $objRs->fields["U_BPLSFHEFEE"];
		$page->privatedata["sfhefeedesc"] = $objRs->fields["U_BPLSFHEFEEDESC"];
		$page->privatedata["platefeecode"] = $objRs->fields["U_BPLPLATEFEE"];
		$page->privatedata["platefeedesc"] = $objRs->fields["U_BPLPLATEFEEDESC"];
	}*/

	$objMaster->reportaction = "QS";
	//$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

