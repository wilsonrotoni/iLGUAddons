<?php

require_once ("../common/classes/recordset.php");
require_once ("./utils/taxes.php");

$page->dtw->events->add->onInit("onBusinessObjectOnInitGPSTDCHI");

$page->dtw->events->add->beforeUpload("onBusinessObjectBeforeUploadGPSTDCHI");

$page->dtw->events->add->prepareData("onBusinessObjectPrepareDataGPSTDCHI");

$page->dtw->events->add->setData("onBusinessObjectSetDataGPSTDCHI");

$page->dtw->events->add->beforeAdd("onBusinessObjectBeforeAddGPSTDCHI");
//$page->dtw->events->add->afterAdd("onBusinessObjectAfterAddGPSTDCHI");

function onBusinessObjectOnInitGPSTDCHI($objectcode,$dtw) {
}

function onBusinessObjectBeforeUploadGPSTDCHI($objectcode,$dtw) {
}

function onBusinessObjectPrepareDataGPSTDCHI($objectcode,$sheetname,$fieldname,&$value,$obj,$dtw) {
	global $objConnection;
}

function onBusinessObjectSetDataGPSTDCHI($objectcode,$sheetname,$fieldname,$value,$obj,$dtw,$data) {
	global $objConnection;
	$actionReturn = true;
	switch ($objectcode) {
		case "u_hisips":
			switch ($fieldname) {
				case "docno":
					$obj->docno = str_pad( $value, 7, "0", STR_PAD_LEFT);
					break;
			}		
			break;	
		case "u_hismedrecs":
			switch ($fieldname) {
				case "u_refno":
					$obj->setudfvalue("u_refno",str_pad( $value, 7, "0", STR_PAD_LEFT));
					break;
			}		
			break;	
		case "u_hisconsultancyfees":
			switch ($fieldname) {
				case "u_refno":
					$obj->setudfvalue("u_refno",str_pad( $value, 7, "0", STR_PAD_LEFT));
					break;
			}		
			break;	
	}		
	return $actionReturn;
}


function onBusinessObjectBeforeAddGPSTDCHI($objectcode,$sheetname,$obj,$dtw) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	//if ($actionReturn) $actionReturn = raiseError("onBusinessObjectBeforeAddGPSTDCHI()");
	return $actionReturn;
}
?> 

