<?php
	$page->popup->events->add->beforeAddItem("OnPagePopupBeforeAddItemGPS");
	$page->popup->events->add->afterAddItem("OnPagePopupAfterAddItemGPS");
	$page->popup->events->add->beforeDeleteItem("OnPagePopupBeforeDeleteItemGPS");
	$page->popup->events->add->afterDeleteItem("OnPagePopupAfterDeleteItemGPS");
	
	function OnPagePopupBeforeAddItemGPS(&$group,&$item,&$onclick) {
		//echo "OnPagePopupBeforeAddItem";
		switch ($item) {
			case "Export to Excel": 
				//return false;
				break;
		}
		return true;
	}

	function OnPagePopupAfterAddItemGPS($group,$item) {
		//echo "OnPagePopupAfterAddItemGPS($group,$item)";
	}

	function OnPagePopupBeforeDeleteItemGPS($group,$item) {
		//echo "OnPagePopupBeforeDeleteItemGPS($group,$item)";
		return true;
	}

	function OnPagePopupAfterDeleteItemGPS($group,$item) {
		//echo "OnPagePopupAfterDeleteItemGPS($group,$item)";
	}
	
?>