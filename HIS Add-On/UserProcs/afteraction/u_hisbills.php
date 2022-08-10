<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_docdate",false);
		$page->businessobject->items->seteditable("u_doctime",false);
		if ($page->getitemstring("docstatus")=="O") {
			$deleteoption=false;
			$canceloption=true;
		} elseif ($page->getitemstring("docstatus")=="C") {
			$deleteoption=false;
			$canceloption=true;
		} elseif ($page->getitemstring("docstatus")=="CN") {
			$page->businessobject->items->setvisible("u_jvcndocno",true);
			$page->businessobject->items->setvisible("u_cancelledby",true);
			$page->businessobject->items->setvisible("u_cancelledreason",true);
			$page->businessobject->items->setvisible("u_cancelledremarks",true);
			$deleteoption=false;
			$canceloption=false;
			$page->toolbar->setaction("update",false);
		}	
		$objGrids[6]->addbutton("u_delete","[Delete]","u_deleteHealthBenefitsGPSHIS()","right");
		
		$objGrids[7]->addbutton("u_phic","[Philhealth Claim]","u_phicHealthBenefitsGPSHIS()","left");
		$objGrids[7]->addbutton("u_pkg","[Add Package]","u_pkgHealthBenefitsGPSHIS()","right");

		if ($page->getitemstring("docstatus")=="O" || $page->getitemstring("docstatus")=="C") {
			$objGrids[7]->addbutton("u_bt","[Balance Transfer]","u_btGPSHIS()","left");
		}	
		
	} else {
		$objGrids[6]->addbutton("u_delete","[Delete]","u_deleteHealthBenefitsGPSHIS()","right");
		$objGrids[10]->addbutton("u_chrg","[Add Charges]","u_addchrgGPSHIS()","left");
		$objGrids[10]->addbutton("u_adj","[Price Adjustment]","u_addadjGPSHIS()","left");
	}
	
	if ($page->getvarstring("formType")=="lnkbtn") {
		$page->toolbar->setaction("find",false);
		$page->toolbar->setaction("navigation",false);
		$page->toolbar->setaction("new",false);
		$page->toolbar->setaction("update",false);
		$page->setvar("formAccess","R");
	}

	$objRs = new recordset(null,$objConnection); 
	$objRs->queryopen("select A.CODE, IFNULL((B.NEXTID-1),0) AS U_QUEUENO from U_HISQUETERMINALS A LEFT JOIN DOCIDS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND DOCTYPE='u_hisque2".date('Ymd')."' where A.BRANCH='".$_SESSION["branch"]."' AND A.NAME='".$_SERVER['REMOTE_ADDR']."' AND A.U_QUEGROUP='2'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->setitem("u_terminalid",$objRs->fields["CODE"]);
		$page->setitem("u_autoqueue",0);
		$page->setitem("u_queue", $objRs->fields["U_QUEUENO"]);
		$page->businessobject->items->setvisible("u_autoqueue",false);
	}
	
		
	$pageHeaderStatusText = slgetdisplayenumdocstatus($page->getitemstring("docstatus"));
	$pageHeaderStatusAlignment = "left";	
?>