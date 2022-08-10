<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./utils/stockcard.php");
	include_once("./classes/masterdataschema.php");
	include_once("./series.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objRs = new recordset (NULL,$objConnection);
	$obju_HISMDRPs = new masterdataschema(null,$objConnection,"u_hismdrps");
	
	//$objRs3->shareddatatype = "branch";
	//$objRs3->shareddatacode = $objRs->fields["BRANCH"];

	$objRs->queryopen("select code from u_hismdrps");
	while ($objRs->queryfetchrow("NAME")) {
		if ($obju_HISMDRPs->getbykey($objRs->fields["code"])) {
			$actionReturn = $obju_HISMDRPs->update($obju_HISMDRPs->code,$obju_HISMDRPs->rcdversion);
		}
		if (!$actionReturn) break;
	}

	if ($actionReturn) {
		///$objConnection->rollback();
		$objConnection->commit();
		printf("%1 Records where successfully updated.",$ctr);
	} else { 	
		$objConnection->rollback();
		echo $_SESSION["errormessage"];
	}

	
?>
