<?php

require_once ("../common/classes/recordset.php");
require_once ("./utils/taxes.php");

$page->dtw->events->add->onInit("onBusinessObjectOnInitGPSHIS");

$page->dtw->events->add->beforeUpload("onBusinessObjectBeforeUploadGPSHIS");

$page->dtw->events->add->prepareData("onBusinessObjectPrepareDataGPSHIS");

$page->dtw->events->add->setData("onBusinessObjectSetDataGPSHIS");

$page->dtw->events->add->beforeAdd("onBusinessObjectBeforeAddGPSHIS");
//$page->businessobject->events->add->afterAdd("onBusinessObjectAfterAddGPSHIS");

function onBusinessObjectOnInitGPSHIS($objectcode,$dtw) {
}

function onBusinessObjectBeforeUploadGPSHIS($objectcode,$dtw) {
}

function onBusinessObjectPrepareDataGPSHIS($objectcode,$sheetname,$fieldname,&$value,$obj,$dtw) {
	global $objConnection;
}

function onBusinessObjectSetDataGPSHIS($objectcode,$sheetname,$fieldname,$value,$obj,$dtw,$data) {
	global $objConnection;
	$actionReturn = true;
	//var_dump(array($objectcode,$sheetname,$fieldname));
	switch ($objectcode) {
		case "u_hisitems":
			switch($sheetname) {
				case "Sections":
					switch ($fieldname) {
						case "u_section":
							$code = $obj->code;
							$u_section = $value;
							if (!$obj->getbysql("CODE='$obj->code' AND U_SECTION='$value'")) {
								$obj->prepareadd();
								$obj->code = $code;
								$obj->setudfvalue("u_section",$u_section);
							}// else var_dump(array($code,$u_section));
							break;
					}		
					break;
			}		
			break;
		case "u_hisrooms":
			switch ($fieldname) {
				case "u_isshared":
					if ($value=="Yes" || $value=="Y" || $value=="1") $obj->setudfvalue("u_isshared",1);
					else $obj->setudfvalue("u_isshared",0);
					break;
				case "u_pricelist":
					if ($value!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select PRICELIST from pricelists where pricelist='$value' or pricelistname='$value'");
						if ($objRs->queryfetchrow("NAME")) {
							$obj->setudfvalue("u_pricelist",$objRs->fields["PRICELIST"]);
						} else return raiseError("Invalid Price List[$value]");
					}	
					break;
			}
			break;
		case "u_hispatients":
			switch ($fieldname) {
				case "u_civilstatus":
					if ($value!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select CODE from u_hismigratecivilstatus where name='$value'");
						if ($objRs->queryfetchrow("NAME")) {
							$obj->setudfvalue("u_civilstatus",$objRs->fields["CODE"]);
						} else return raiseError("Invalid MAP Civil Status [$value]");
					}	
					break;
			}		
			break;	
		case "u_hisips":
			if ($sheetname=="In-Patients") {
				switch ($fieldname) {
					case "u_department":
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select CODE from u_hismigratesections where name='$value'");
						if ($objRs->queryfetchrow("NAME")) {
							$obj->setudfvalue("u_department",$objRs->fields["CODE"]);
						} else return raiseError("Invalid MAP Section [$value]");
						break;
					case "u_suspendby":
					case "u_billby":
					case "u_mghby":
					case "u_admittedby":
					case "u_dischargedby":
						if ($value!="") {
							$objRs = new recordset(null,$objConnection);
							$objRs->queryopen("select USERID from users where u_legacycode='$value'");
							if ($objRs->queryfetchrow("NAME")) {
								$obj->setudfvalue($fieldname,$objRs->fields["USERID"]);
							} else $obj->setudfvalue($fieldname,"");//return raiseError("Invalid User Profile Legacy Code [$value]");
						}	
						break;
					case "u_bedno":
						if ($value!="") {
							$objRs = new recordset(null,$objConnection);
							$objRs->queryopen("select a.U_PRICELIST, a.CODE from u_hisrooms a, u_hisroombeds b where b.code=a.code and b.u_bedno='$value'");
							if ($objRs->queryfetchrow("NAME")) {
								$obj->setudfvalue("u_roomno",$objRs->fields["CODE"]);
								$obj->setudfvalue("u_pricelist",$objRs->fields["U_PRICELIST"]);
							} 
						}	
						break;
				}		
			} elseif ($sheetname=="Rooms") {	
				switch ($fieldname) {
					/*
					case "u_department":
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select CODE from u_hismigratesections where name='$value'");
						if ($objRs->queryfetchrow("NAME")) {
							$obj->setudfvalue("u_department",$objRs->fields["CODE"]);
						} else return raiseError("Invalid MAP Section [$value]");
						break;*/
					case "u_bedno":
						if ($value!="") {
							$objRs = new recordset(null,$objConnection);
							$objRs->setdebug();
							$objRs->queryopen("select a.U_PRICELIST, a.CODE, a.U_ISSHARED, a.U_CHARGINGUOM, a.U_DEPARTMENT from u_hisrooms a, u_hisroombeds b where b.code=a.code and b.u_bedno='$value'");
							if ($objRs->queryfetchrow("NAME")) {
								$obj->setudfvalue("u_roomno",$objRs->fields["CODE"]);
								$obj->setudfvalue("u_pricelist",$objRs->fields["U_PRICELIST"]);
								$obj->setudfvalue("u_isroomshared",$objRs->fields["U_ISSHARED"]);
								$obj->setudfvalue("u_rateuom",$objRs->fields["U_CHARGINGUOM"]);
								$obj->setudfvalue("u_department",$objRs->fields["U_DEPARTMENT"]);
							} 
						}	
						break;
				}		
			}
			break;	
	}		
	return $actionReturn;
}


function onBusinessObjectBeforeAddGPSHIS($objectcode,$sheetname,$obj,$dtw) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	//if ($actionReturn) $actionReturn = raiseError("onBusinessObjectBeforeAddGPSHIS()");
	return $actionReturn;
}
?> 

