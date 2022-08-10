<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/collections.php");
	include_once("./classes/marketingdocuments.php");
	include_once("./classes/marketingdocumentcashcards.php");
	include_once("./series.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objRs3 = new recordset (NULL,$objConnection);
	$objRs2 = new recordset (NULL,$objConnection);

	$obju_HISPos = new documentschema_br(null,$objConnection,"u_hispos");

	$obju_HISCredits = new documentschema_br(null,$objConnection,"u_hiscredits");

	$objARCreditMemos = new marketingdocuments(null,$objConnection,"arcreditmemos");

	$objJVHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
	//$objRs3->shareddatatype = "branch";
	//$objRs3->shareddatacode = $objRs3->fields["BRANCH"];
	
	$ctr=0;
	
	if ($actionReturn) {
		$objRs3->queryopen("select docno, u_arcmdocno, u_jvcndocno from u_hispos where docstatus='CN' and u_trxtype='CM' and u_cancelledby='' and u_automanage=1");
		while ($objRs3->queryfetchrow("NAME")) {
			//echo $objRs3->fields["u_jvdocno"] . "<br>";
			var_dump($objRs3->fields);
			echo "<br>";
			if ($obju_HISPos->getbykey($objRs3->fields["docno"])) {
				if ($objARCreditMemos->getbykey($objRs3->fields["u_arcmdocno"])) {
					if ($objJVHdr->getbykey($objRs3->fields["u_jvcndocno"])) {
						if ($actionReturn) {
							if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='JV' and docno='$objJVHdr->docno'",false);
							if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='JV' and docno='$objJVHdr->docno'",false);
							if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='CM' and docno='".$objRs3->fields["u_arcmdocno"]."'",false);
							if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='CM' and docno='".$objRs3->fields["u_arcmdocno"]."'",false);
							if ($actionReturn) $actionReturn = $objRs2->executesql("delete from arcreditmemoitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='".$objARCreditMemos->docid."'",false);
							if ($actionReturn) $actionReturn = $objRs2->executesql("delete from arcreditmemos where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno='".$objRs3->fields["u_arcmdocno"]."'",false);
						}
						//var_dump(array($actionReturn,$_SESSION["errormessage"]));
						if ($actionReturn) {
							$objJvDtl->queryopen($objJvDtl->selectstring()." AND DOCID='$objJVHdr->docid'");
							while ($objJvDtl->queryfetchrow()) {
								$objJvDtl->privatedata["header"] = $objJVHdr;
								$actionReturn = $objJvDtl->delete();
								if (!$actionReturn) break;
							}
						}	
						if ($actionReturn) $actionReturn = $objJVHdr->delete();
						if ($actionReturn) {
							$obju_HISPos->setudfvalue("u_arcmdocno","");
							$obju_HISPos->setudfvalue("u_jvcndocno","");
							$obju_HISPos->docstatus = "C";
							$actionReturn = $obju_HISPos->update($obju_HISPos->docno,$obju_HISPos->rcdversion);					
						}	
						/*if ($actionReturn) {
							if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='JV' and docno='$objJVHdr->docno'",false);
							if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='JV' and docno='$objJVHdr->docno'",false);
							$objJVHdr->sbo_post_flag = 0;
							if ($actionReturn) $actionReturn = $objJVHdr->update($objJVHdr->docno,$objJVHdr->rcdversion);
						}*/	
					} else $actionReturn = raiseError("Unable to find cm journal [".$objRs3->fields["u_jvcndocno"]."]");
				} else $actionReturn = raiseError("Unable to find a/r cm [".$objRs3->fields["u_arcmdocno"]."]");		
			} else $actionReturn = raiseError("Unable to find cm [".$objRs3->fields["docno"]."]");
			if (!$actionReturn) break;
		}			
	}	
	
	if ($actionReturn) {
		//$objConnection->rollback();
		$objConnection->commit();
		printf("%1 Records where successfully updated.",$ctr);
	} else { 	
		$objConnection->rollback();
		echo $_SESSION["errormessage"];
	}

	
?>
