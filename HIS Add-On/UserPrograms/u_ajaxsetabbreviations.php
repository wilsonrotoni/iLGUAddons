<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./series.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/masterdatalinesschema.php");

	$actionReturn = true;
	$msg = "";
	if(isset($_GET['key'])){
		$obju_HISAbbreviations = new masterdataschema(null,$objConnection,"u_hisabbreviations");
		$obju_HISAbbreviationItems = new masterdatalinesschema(null,$objConnection,"u_hisabbreviationitems");
		$objConnection->beginwork();
		if (!$obju_HISAbbreviations->getbykey($_GET['key'])) {
			$obju_HISAbbreviations->prepareadd();
			$obju_HISAbbreviations->code = $_GET['key'];
			$obju_HISAbbreviations->name = $_GET['key'];
		}
		if (!$obju_HISAbbreviationItems->getbysql("CODE='". $_GET['key']."' AND U_ABBREV='".$_GET['abbrev']."'")) {
			$obju_HISAbbreviationItems->prepareadd();
			$obju_HISAbbreviationItems->code = $_GET['key'];
			$obju_HISAbbreviationItems->lineid = getNextId('u_hisabbreviationitems',$objConnection);
			$obju_HISAbbreviationItems->setudfvalue("u_abbrev",$_GET['abbrev']);
		}
		$obju_HISAbbreviationItems->setudfvalue("u_value",$_GET['value']);
		$obju_HISAbbreviationItems->privatedata["header"] = $obju_HISAbbreviations;
		if ($obju_HISAbbreviationItems->rowstat=="N") $actionReturn = $obju_HISAbbreviationItems->add();
		else $actionReturn = $obju_HISAbbreviationItems->update($obju_HISAbbreviationItems->code,$obju_HISAbbreviationItems->lineid, $obju_HISAbbreviationItems->rcdversion);
		if ($actionReturn) {
			if ($obju_HISAbbreviations->rowstat=="N") $actionReturn = $obju_HISAbbreviations->add();
			else $actionReturn = $obju_HISAbbreviations->update($obju_HISAbbreviations->code,$obju_HISAbbreviations->rcdversion);
		}		
		if ($actionReturn) {
			$objConnection->commit();
			$msg = "Sucessfully save keyword";
		} else $objConnection->rollback();
	} else $msg = "Cannot save unknown keyword";

	if ($actionReturn) echo $msg;
	else echo $_SESSION["errormessage"];		
	
?>
