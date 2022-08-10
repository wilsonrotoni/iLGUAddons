<?php
	
	//unset($enumdocstatus["CN"]);
	$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUPurchasing");
	
	function onBeforeDefaultGPSLGUPurchasing() {
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
        $pageHeader = "Settings";
        
        $page->businessobject->items->setcfl("u_stockallocglacctno","OpenCFLfs()");
	$page->toolbar->setaction("new",false);
	$page->toolbar->setaction("find",false);
	$page->toolbar->setaction("navigation",false);
	$page->toolbar->setaction("print",false);
?>