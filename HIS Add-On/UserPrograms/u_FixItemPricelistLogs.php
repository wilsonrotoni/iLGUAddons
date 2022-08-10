<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/itempricelistlogs.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objRs = new recordset (NULL,$objConnection);
	$objItemPriceListLogs = new itempricelistlogs(null,$objConnection);

	$ctr=0;
	
	$objItemPriceListLogs->queryopen($objItemPriceListLogs->selectstring());
	while ($objItemPriceListLogs->queryfetchrow()) {
		$objRs->queryopen("select price from itempricelistlogs where itemcode='$objItemPriceListLogs->itemcode' and pricelist='$objItemPriceListLogs->pricelist' and id<'$objItemPriceListLogs->id' order by id desc");
		if ($objRs->queryfetchrow()) {
			$objItemPriceListLogs->oldprice = $objRs->fields[0];
			$actionReturn = $objItemPriceListLogs->update($objItemPriceListLogs->id,$objItemPriceListLogs->rcdversion);
		}
		if (!$actionReturn) break;
	}
	if ($actionReturn) {
		$objConnection->commit();
		printf("Records where successfully updated.");
	} else { 	
		$objConnection->rollback();
		echo $_SESSION["errormessage"];
	}
	

	
?>
