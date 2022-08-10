<?php
	include_once("../common/classes/recordset.php");
	include_once("./classes/masterdataschema_br.php");
	require_once("./classes/smsoutlog.php");
	require_once("../Addons/GPS/HIS Add-On/UserProcs/utils/u_queueingprinter.php"); 
	$actionReturn = true;
	$tagno = 0;
	$msg = "";
	if(isset($_GET['group'])){
		$_SESSION["company"] = "HIS";
		$_SESSION["branch"] = "HO";
		$obju_HISQueGroups = new masterdataschema_br(null,$objConnection,"u_hisquegroups");
		$obju_HISQueTags = new masterdataschema_br(null,$objConnection,"u_hisquetags");
		$objSMSOutLog = new smsoutlog(null,$objConnection);
		$objRs = new recordset(null,$objConnection); 
		if ($obju_HISQueGroups->getbykey($_GET['group'])) {
			$objConnection->beginwork();
			$tagno = getNextIdByBranch("u_hisquetags".$obju_HISQueGroups->code.date('Ymd'),$objConnection);
			if ($tagno>0 && trim($_GET['mobileno'])!="") {
				$obju_HISQueTags->prepareadd();
				$obju_HISQueTags->code = getNextIdByBranch("u_hisquetags",$objConnection);
				$obju_HISQueTags->setudfvalue("u_group",$obju_HISQueGroups->code);
				$obju_HISQueTags->setudfvalue("u_tagno",$tagno);
				$obju_HISQueTags->setudfvalue("u_date",date('Ymd'));
				$obju_HISQueTags->setudfvalue("u_mobileno",trim($_GET['mobileno']));
				$actionReturn = $obju_HISQueTags->add();
				if ($actionReturn) {
					$queno = 0;
					$objRs->queryopen("select IFNULL((NEXTID-1),0) AS U_QUEUENO from DOCIDS where COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND DOCTYPE='u_hisque".$obju_HISQueGroups->code.date('Ymd')."'");
					if ($objRs->queryfetchrow("NAME")) {
						$queno = $objRs->fields["U_QUEUENO"];
					}
				
					$objSMSOutLog->prepareadd();
					$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objCon);
					$objSMSOutLog->deviceid = "sun";
					$objSMSOutLog->mobilenumber = trim($_GET['mobileno']);
					$objSMSOutLog->remark = "";
					$objSMSOutLog->message = "You are now registered for ".$obju_HISQueGroups->name." Queing sms notification at No. " . $tagno . ". " . "Now serving No. " . $queno;
					$actionReturn = $objSMSOutLog->add();
				}
				//if ($actionReturn) $tagno .= "\t" . trim($_GET['mobileno']);
			}
			if ($tagno>0 && $actionReturn) {
				printQueueNo($obju_HISQueGroups->name,$tagno,$_GET['mobileno']);
				$objConnection->commit();
			} else $objConnection->rollback();
		} else $msg = "Invalid Queing Group [".$_GET['group']."]";	
	} else $msg = "Unknown Terminal ID";

	if ($msg=="") echo $tagno;
	else echo $msg;	
		
?>
