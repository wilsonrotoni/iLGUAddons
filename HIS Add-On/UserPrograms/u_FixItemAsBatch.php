<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./utils/stockcard.php");
	include_once("./series.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objRs = new recordset (NULL,$objConnection);
	
	//$objRs3->shareddatatype = "branch";
	//$objRs3->shareddatacode = $objRs->fields["BRANCH"];
	$ctr=0;

	if ($actionReturn){
		$objRs->queryopen("select a.docdate, a.docno, b.lineid, a.bpcode, a.bpname, b.itemcode, c.itemdesc, b.whscode, b.quantity from apinvoices a inner join apinvoiceitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.itemcode='".$_GET["itemcode"]."' and b.itemmanageby='0' inner join items c on c.itemcode=b.itemcode"); 
		while ($objRs->queryfetchrow("NAME")) {
			$batch = array();
			$batch["refdate"] = $objRs->fields["docdate"];
			$batch["reftype"] = "AP";
			$batch["refno"] = $objRs->fields["docno"];
			$batch["reflineid"] = $objRs->fields["lineid"];
			$batch["bpcode"] = $objRs->fields["bpcode"];;
			$batch["bpname"] = $objRs->fields["bpname"];
			
			$batch["itemcode"] = $objRs->fields["itemcode"];
			$batch["itemname"] = $objRs->fields["itemdesc"];
			$batch["batch"] = "NONE";
			$batch["warehouse"] = $objRs->fields["whscode"];
			
			$batch["qty"] = $objRs->fields["quantity"];
			
			$batch["bin"] = "";
			$batch["allocdoctype"] = "";
			$batch["allocdocno"] = "";
			$batch["alloclineid"] = 0;
		
			$actionReturn = updatebatchtrxs($batch);
			
			if (!$actionReturn) break;
		}	
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
