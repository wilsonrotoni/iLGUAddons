<?php
	
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		//$page->businessobject->items->seteditable("u_startdate",false);
		//$page->businessobject->items->seteditable("u_starttime",false);
		$page->businessobject->items->seteditable("u_type",false);
		$page->businessobject->items->seteditable("u_department",false);
		$page->businessobject->items->seteditable("u_amount",false);
		if ($page->getitemstring("docstatus")=="C") {
			$page->businessobject->items->seteditable("u_remarks",false);
			$page->businessobject->items->seteditable("u_doctorid",false);
			$page->businessobject->items->seteditable("u_testby",false);
			$page->businessobject->items->seteditable("u_reqdate",false);
			$page->businessobject->items->seteditable("u_reqtime",false);
			$page->businessobject->items->seteditable("u_enddate",false);
			$page->businessobject->items->seteditable("u_endtime",false);
			$objGrids[0]->columninput("u_result","type","label");
			$objGrids[0]->columninput("u_remarks","type","label");
			$objGrids[1]->setaction("add",false);
			$page->toolbar->setaction("update",false);
			$closeoption = false;
			
			$page->toolbar->addbutton("reopen","Re-Open","showPopupFrame('popupFrameReOpen',true)","right");
			
		} else {
			$objGrid->setaction("add",true);
			$objGrids[1]->addbutton("delete","[Delete]","deleteAttachmentGPSHIS()","right");
			if ($edtopt=="testpreview") {
				$closeoption = false;
				//$page->toolbar->setaction("add",false);
				//$page->toolbar->setaction("update",false);
			} else {	
				$page->toolbar->addbutton("sltemplate","Notes Template","u_sltemplateGPSHIS()","left");
			}	
		}
		
		if ($page->getitemstring("u_pfapno")!="") {
			$page->businessobject->items->seteditable("u_amount",false);
			$page->businessobject->items->seteditable("u_isstat",false);
			$page->businessobject->items->seteditable("u_statperc",false);
			$page->businessobject->items->seteditable("u_statamount",false);
			$page->businessobject->items->seteditable("u_pfperc",false);
			$page->businessobject->items->seteditable("u_pfamount",false);
			$page->businessobject->items->seteditable("u_pfdiscperc",false);
			$page->businessobject->items->seteditable("u_pfdiscamount",false);
		}
		
		if ($page->getvarstring("formType")=="lnkbtn") {
			//$page->toolbar->setaction("print",false);
			$page->toolbar->setaction("new",false);
			$page->toolbar->setaction("navigation",false);
			$page->toolbar->setaction("find",false);
		}
	}
	
	if ($page->getitemstring("u_trxtype")=="XRAY" || $page->getitemstring("u_trxtype")=="CTSCAN" || $page->getitemstring("u_trxtype")=="ULTRASOUND" || $page->getitemstring("u_trxtype")=="RADIOLOGY") {
		$page->businessobject->items->setcaption("u_testby","Radiologic Technologist");
		$page->businessobject->items->setcaption("u_doctorid2","Radiologist");
		$page->businessobject->items->setvisible("u_specimen",false);
		$page->businessobject->items->setvisible("u_specimendate",false);
		$page->businessobject->items->setvisible("u_specimentime",false);
	} elseif ($page->getitemstring("u_trxtype")=="HEARTSTATION") {
		$page->businessobject->items->setcaption("u_testby","Cardiac Sonographer");
		$page->businessobject->items->setcaption("u_doctorid2","Cardiologist");
		$page->businessobject->items->setvisible("u_specimendate",false);
		$page->businessobject->items->setvisible("u_specimentime",false);
	}
	//$deleteoption=true;
?>