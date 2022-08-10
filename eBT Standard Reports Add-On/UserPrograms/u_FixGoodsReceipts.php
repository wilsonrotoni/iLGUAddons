<?php
require_once ("../common/classes/recordset.php");
require_once ("./classes/marketingdocuments.php");
require_once ("./classes/marketingdocumentitems.php");

$objGoodsReceipts = new marketingdocuments(null,$objConnection,"GOODSRECEIPTS");
$objGoodsReceiptItems = new marketingdocumentitems(null,$objConnection,"GOODSRECEIPTITEMS");

$actionReturn=true;

$objConnection->beginwork();

if ($objGoodsReceipts->getbykey("OMAIN-GR00000087")) {
	
	$actionReturn = $objGoodsReceiptItems->executesql("delete from stockcard where refno='".$objGoodsReceipts->docno."' and QTY=0",false);
	if ($actionReturn) $actionReturn = $objGoodsReceiptItems->executesql("update goodsreceiptitems set ITEMMANAGEBY='0',NUMPERUOM=1, UOM='tin' WHERE COMPANY='TF' AND BRANCH='MAIN' AND LINEID=268",false);
	;
	if ($actionReturn) {
		$objGoodsReceiptItems->queryopen($objGoodsReceiptItems->selectstring() . " AND DOCID='".$objGoodsReceipts->docid."' and ITEMMANAGEBY=''");
		while ($objGoodsReceiptItems->queryfetchrow()) {
			$objGoodsReceiptItems->itemmanageby = "0";
			$objGoodsReceiptItems->numperuom = 1;
			$objGoodsReceiptItems->uom = "tin";
			$objGoodsReceiptItems->privatedata["header"] = $objGoodsReceipts;
			$actionReturn = onCustomEventmarketingdocumentitemsUpdateItems("N",$objGoodsReceiptItems);
			if (!$actionReturn) break;
			$actionReturn = $objGoodsReceiptItems->executesql("update goodsreceiptitems  set itemmanageby='0',numperuom=1,uom='tin' where company='$objGoodsReceiptItems->company' and branch='$objGoodsReceiptItems->branch' and lineid='$objGoodsReceiptItems->lineid'");
			if (!$actionReturn) break;
		}
	}	
}

//if ($actionReturn) $actionReturn = raiseError("here");	

if ($actionReturn) {
	$objConnection->commit();
	echo "Operation Ended Successfully.";
} else {
	echo $_SESSION["errormessage"];
	$objConnection->rollback();	
}	

//var_dump($objRs2->sqls);
//var_dump($objRs4->sqls);




?>