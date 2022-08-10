<?php
	
	if (isEditMode()) {
		$page->toolbar->addbutton("print","Print RTF Notes","printNotesGPSHIS()","left");
		
		$page->businessobject->items->seteditable("u_isstat",false);
		$page->businessobject->items->seteditable("u_reftype",false);
		$page->businessobject->items->seteditable("u_refno",false);
		$page->businessobject->items->seteditable("u_requestno",false);
		$page->businessobject->items->seteditable("u_startdate",false);
		$page->businessobject->items->seteditable("u_starttime",false);
		$page->businessobject->items->seteditable("u_type",false);
		$page->businessobject->items->seteditable("u_department",false);
		$page->businessobject->items->seteditable("u_amount",false);
		$page->businessobject->items->seteditable("u_disccode",false);
		$page->businessobject->items->seteditable("u_pricelist",false);
		$page->resize->addgridobject($objGrids[3],30,270);
		//$objGrids[3]->columninput("u_quantity","type","label");
		//$objGrids[3]->columninput("u_remarks","type","label");
		//$objGrids[3]->dataentry = false;

		$objGrids[0]->columnvisibility("u_selected",false);
		$objGrids[0]->columninput("u_selected","type","label");

		$objGrids[3]->columnvisibility("u_selected",false);
		$objGrids[3]->columninput("u_selected","type","label");
		$objGrids[3]->columninput("u_isstat","type","label");
		$objGrids[3]->columninput("u_quantity","type","label");
		$objGrids[3]->columninput("u_remarks","type","label");		
		
		$objGrids[4]->columnvisibility("u_selected",false);
		$objGrids[4]->columninput("u_selected","type","label");
		
		$objGrids[3]->dataentry = false;
		$objGrids[4]->dataentry = false;
		
		if ($page->getitemstring("docstatus")=="C" || $page->getitemstring("docstatus")=="CN") $canceloption = false; 
		
		if (!$canceloption) {
			$page->toolbar->setaction("update",false);
		}
		
		
		if (($page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="XRAY" || $page->getitemstring("u_trxtype")=="CTSCAN" || $page->getitemstring("u_trxtype")=="ULTRASOUND" || $page->getitemstring("u_trxtype")=="RADIOLOGY") && $page->getitemstring("u_enddate")!="") {
			$page->businessobject->items->seteditable("u_specimen",false);
			$page->businessobject->items->seteditable("u_specimendate",false);
			$page->businessobject->items->seteditable("u_specimentime",false);
			$page->businessobject->items->seteditable("u_testtype",false);
			$page->businessobject->items->seteditable("u_template",false);
			$page->businessobject->items->seteditable("u_remarks",false);
			$page->businessobject->items->seteditable("u_doctorid",false);
			$page->businessobject->items->seteditable("u_doctorid2",false);
			$page->businessobject->items->seteditable("u_testby",false);
			$page->businessobject->items->seteditable("u_reqdate",false);
			$page->businessobject->items->seteditable("u_reqtime",false);
			$page->businessobject->items->seteditable("u_enddate",false);
			$page->businessobject->items->seteditable("u_endtime",false);
			$objGrids[0]->columnattributes("u_print","disabled");
			$objGrids[0]->columninput("u_result","type","label");
			$objGrids[0]->columninput("u_remarks","type","label");
			$objGrids[1]->setaction("add",false);
			$page->toolbar->addbutton("reopen","Re-Open","showreopenGPSHIS()","right");
		} elseif ($page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="XRAY" || $page->getitemstring("u_trxtype")=="CTSCAN" || $page->getitemstring("u_trxtype")=="ULTRASOUND" || $page->getitemstring("u_trxtype")=="RADIOLOGY") {
			if ($page->getitemstring("docno")!="-1") {
				$page->toolbar->addbutton("release","Release","releaseGPSHIS()","right");
				$page->toolbar->setaction("update",true);
				$page->toolbar->setaction("new",false);
			}	
			$objGrid->setaction("add",true);
			$objGrids[1]->addbutton("delete","[Delete]","deleteAttachmentGPSHIS()","right");
			if ($edtopt=="testpreview") {
				//$closeoption = false;
				//$page->toolbar->setaction("add",false);
				//$page->toolbar->setaction("update",false);
			} else {	
				$page->toolbar->addbutton("sltemplate","Notes","u_sltemplateGPSHIS()","left");
				$page->toolbar->addbutton("slrtftemplate","RTF Notes","u_slrtftemplateGPSHIS()","left");
			}	
		} else {
			$page->businessobject->items->seteditable("u_doctorid",false);
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
		
		/*if ($page->getitemstring("u_enddate")=="" && $page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="HEARTSTATION" || $page->getitemstring("u_trxtype")=="RADIOLOGY") {
			$page->toolbar->setaction("update",true);
		}*/
		
	} else {
		$page->resize->addgridobject($objGrids[3],30,345);
		$page->toolbar->addbutton("templates","Templates","u_templatesGPSHIS()","left");
		$page->toolbar->addbutton("packages","Packages","u_packagesGPSHIS()","left");
		
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
	
	switch ($page->getitemstring("u_trxtype")) {
		case "LABORATORY": $pageHeader = "Laboratory - Charge/Render"; break;
		case "RADIOLOGY": $pageHeader = "Radiology - Charge/Render"; break;
		case "HEARTSTATION": $pageHeader = "Heart Station - Charge/Render"; break;
		default: $pageHeader = "Charge/Render"; break;
	}		
	
	//$deleteoption=true;
	$pageHeaderStatusText = slgetdisplayenumdocstatus($page->getitemstring("docstatus"));
	$pageHeaderStatusAlignment = "left";
?>