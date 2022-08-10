<?php
	include_once("../common/classes/recordset.php");
	include_once("./classes/masterdataschema_br.php");
	require_once("./classes/smsoutlog.php");
	require_once("./series.php");
	require_once("../Addons/GPS/Queueing Add-On/UserProcs/utils/u_queueingprinter.php"); 
	$actionReturn = true;
	$tagno = 0;
	$msg = "";
	if(isset($_GET['group'])){
		$objRs = new recordset(null,$objConnection); 
		$objRs->queryopen("select companycode,branchcode from branches order by branchtype desc limit 1");
		if ($objRs->queryfetchrow("NAME")) {
			$_SESSION["company"]=$objRs->fields["companycode"];
			$_SESSION["branch"]=$objRs->fields["branchcode"];
		}
		$obju_QueGroups = new masterdataschema_br(null,$objConnection,"u_quegroups");
		$obju_QueTags = new masterdataschema_br(null,$objConnection,"u_quetags");
		$objSMSOutLog = new smsoutlog(null,$objConnection);
		if ($obju_QueGroups->getbykey($_GET['group'])) {
			$objConnection->beginwork();
			$tagno = getNextIdByBranch("u_quetags".$obju_QueGroups->code.date('Ymd'),$objConnection);
			if ($tagno>0 && trim($_GET['mobileno'])!="") {
				$obju_QueTags->prepareadd();
				$obju_QueTags->code = getNextIdByBranch("u_quetags",$objConnection);
				$obju_QueTags->setudfvalue("u_group",$obju_QueGroups->code);
				$obju_QueTags->setudfvalue("u_tagno",$tagno);
				$obju_QueTags->setudfvalue("u_date",date('Ymd'));
				$obju_QueTags->setudfvalue("u_mobileno",trim($_GET['mobileno']));
				$actionReturn = $obju_QueTags->add();
				if ($actionReturn) {
					$queno = 0;
					$objRs->queryopen("select IFNULL((NEXTID-1),0) AS U_QUEUENO from DOCIDS where COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND DOCTYPE='u_que".$obju_QueGroups->code.date('Ymd')."'");
					if ($objRs->queryfetchrow("NAME")) {
						$queno = $objRs->fields["U_QUEUENO"];
					}
				
					$objSMSOutLog->prepareadd();
					$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objCon);
					$objSMSOutLog->deviceid = "sun";
					$objSMSOutLog->mobilenumber = trim($_GET['mobileno']);
					$objSMSOutLog->remark = "";
					$objSMSOutLog->message = "You are now registered for ".$obju_QueGroups->name." Queing sms notification at No. " . $tagno . ". " . "Now serving No. " . $queno;
					$actionReturn = $objSMSOutLog->add();
				}
				//if ($actionReturn) $tagno .= "\t" . trim($_GET['mobileno']);
			}
			if ($tagno>0 && $actionReturn) {
				printQueueNo($obju_QueGroups->name,$tagno,$_GET['mobileno']);
				$objConnection->commit();
			} else $objConnection->rollback();
		} else $msg = "Invalid Queing Group [".$_GET['group']."]";	
	} else $msg = "Unknown Terminal ID";

	if ($msg=="") echo $tagno;
	else echo $msg;	
		
?>
