<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/collections.php");
	include_once("./series.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objCollections = new collections (NULL,$objConnection);
	//$objRs3->shareddatatype = "branch";
	//$objRs3->shareddatacode = $objRs->fields["BRANCH"];
	$ctr=0;
	
	if ($actionReturn) {
		$objCollections->queryopen($objCollections->selectstring()." AND COLLFOR='SI'");
		while ($objCollections->queryfetchrow()) {
			if ($actionReturn && $objCollections->docstat!="D" && $objCollections->cleared==1 && $objCollections->collfor=="SI") {
				$actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('inpay',$objCollections);
				if (!$actionReturn) break;
				$ctr++;
			}
		
			echo $objCollections->docno."<br>";
		}
	}	
	
	
	if ($actionReturn) {
		$objConnection->commit();
		printf("Records where successfully updated.",$ctr);
	} else { 	
		$objConnection->rollback();
		echo $_SESSION["errormessage"];
	}

	
?>
