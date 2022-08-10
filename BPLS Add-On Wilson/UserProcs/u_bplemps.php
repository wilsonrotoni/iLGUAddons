<?php
	
	//unset($enumdocstatus["CN"]);
	$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSBPLS");
	
	function onBeforeDefaultGPSBPLS() {
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

	$addoptions = false;
	$deleteoption = false;
		 
	$page->toolbar->setaction("new",false);
	$page->toolbar->setaction("find",false);
	$page->toolbar->setaction("navigation",false);
	$page->toolbar->setaction("print",false);
?>