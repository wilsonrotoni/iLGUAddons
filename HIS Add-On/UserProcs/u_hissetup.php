<?php
	
	//unset($enumdocstatus["CN"]);
	$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
	
	function onBeforeDefaultGPSHIS() {
		global $objMaster;
		global $httpVars;
		global $page;
		if ($objMaster->getbykey("SETUP"))	{
			$page->setkey("SETUP");
			modeEdit();
			return false;
		} else {
			$objMaster->code = "SETUP";
			$objMaster->name = "SETUP";
			return true;
		}	
	}

	$page->businessobject->items->setcfl("u_doctorpayablecutoff","Calendar");
	
	$addoptions = false;
	$deleteoption = false;
		 
	$page->toolbar->setaction("new",false);
	$page->toolbar->setaction("find",false);
	$page->toolbar->setaction("navigation",false);
	$page->toolbar->setaction("print",false);
?>