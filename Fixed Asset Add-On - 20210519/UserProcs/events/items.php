<?php

	function onBeforeAddEventitemsGPSFixedAsset(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($objTable->isinventory==1 && $objTable->getudfvalue("u_isfixedasset")==1) {
			return raiseError("Fixed Asset cannot be inventory item at the same time.");
		}	
		
		//$actionReturn = onCustomEventCheckPriceitemsGPSFixedAsset($objTable);

		//if ($actionReturn) $actionReturn = raiseError("onBeforeAddEventitemsGPSFixedAsset");
		return $actionReturn;	
	}

	function onUpdateEventitemsGPSFixedAsset(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		if ($objTable->isinventory==1 && $objTable->getudfvalue("u_isfixedasset")==1) {
			return raiseError("Fixed Asset cannot be inventory item at the same time.");
		}	
		
		//$actionReturn = onCustomEventCheckPriceitemsGPSFixedAsset($objTable);
							
		//if ($actionReturn) $actionReturn = raiseError("onUpdateEventitemsGPSFixedAsset");
		return $actionReturn;
	}

	function onBeforeDeleteEventitemsGPSFixedAsset($objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;

		//$actionReturn = onCustomEventCheckPriceitemsGPSFixedAsset($objTable);
							
		//if ($actionReturn) $actionReturn = raiseError("onBeforeDeleteEventitemsGPSFixedAsset");
		return $actionReturn;
	}


?>