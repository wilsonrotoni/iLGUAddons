<?php // 
//	include_once("../common/classes/recordset.php");
//	include_once("./classes/masterdataschema_br.php");
//	require_once("./classes/smsoutlog.php");
//	require_once("./series.php");
//
//	$actionReturn = true;
//	$tagno = 0;
//	$msg = "";
//	if(isset($_GET['group'])){
//		$_SESSION["company"] = "LGU";
//		$_SESSION["branch"] = "MAIN";
//		$obju_LGUQueGroups = new masterdataschema_br(null,$objConnection,"u_lguquegroups");
//		$obju_LGUQueTags = new masterdataschema_br(null,$objConnection,"u_lguquetags");
//		$objSMSOutLog = new smsoutlog(null,$objConnection);
//		$objRs = new recordset(null,$objConnection); 
//		if ($obju_LGUQueGroups->getbykey($_GET['group'])) {
//			$objConnection->beginwork();
//			$tagno = getNextIdByBranch("u_lguquetags".$obju_LGUQueGroups->code.date('Ymd'),$objConnection);
//			if ($tagno>0 && trim($_GET['mobileno'])!="") {
//				$obju_LGUQueTags->prepareadd();
//				$obju_LGUQueTags->code = getNextIdByBranch("u_lguquetags",$objConnection);
//				$obju_LGUQueTags->setudfvalue("u_group",$obju_LGUQueGroups->code);
//				$obju_LGUQueTags->setudfvalue("u_tagno",$tagno);
//				$obju_LGUQueTags->setudfvalue("u_date",date('Ymd'));
//				$obju_LGUQueTags->setudfvalue("u_mobileno",trim($_GET['mobileno']));
//				$actionReturn = $obju_LGUQueTags->add();
//				if ($actionReturn) {
//					$queno = 0;
//					$objRs->queryopen("select IFNULL((NEXTID-1),0) AS U_QUEUENO from DOCIDS where COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND DOCTYPE='u_lguque".$obju_LGUQueGroups->code.date('Ymd')."'");
//					if ($objRs->queryfetchrow("NAME")) {
//						$queno = $objRs->fields["U_QUEUENO"];
//					}
//				
//					$objSMSOutLog->prepareadd();
//					$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objCon);
//					$objSMSOutLog->deviceid = "sun";
//					$objSMSOutLog->mobilenumber = trim($_GET['mobileno']);
//					$objSMSOutLog->remark = "";
//					$objSMSOutLog->message = "You are now registered for ".$obju_LGUQueGroups->name." Queing sms notification at No. " . $tagno . ". " . "Now serving No. " . $queno;
//					$actionReturn = $objSMSOutLog->add();
//				}
//				//if ($actionReturn) $tagno .= "\t" . trim($_GET['mobileno']);
//			}
//			if ($tagno>0 && $actionReturn) {
//				$objConnection->commit();
//			} else {
//				$msg = $_SESSION["errormessage"];
//				$objConnection->rollback();
//			}	
//		} else $msg = "Invalid Queing Group [".$_GET['group']."]";	
//	} else $msg = "Unknown Terminal ID";
//
//	if ($msg=="") echo $tagno;
//	else echo $msg;	

        include_once("../common/classes/recordset.php");
	include_once("./classes/masterdataschema_br.php");
	require_once("./classes/smsoutlog.php");
	require_once("./series.php");
 	require_once("../Addons/GPS/LGU Add-On/UserProcs/utils/u_queueingprinter.php"); 

	$actionReturn = true;
	$tagno = 0;
	$msg = "";
	if(isset($_GET['group'])){
		$_SESSION["company"] = "LGU";
		$_SESSION["branch"] = "MAIN";
		$obju_LGUQueGroups = new masterdataschema_br(null,$objConnection,"u_lguquegroups");
		$obju_LGUQueTags = new masterdataschema_br(null,$objConnection,"u_lguquetags");
		$objSMSOutLog = new smsoutlog(null,$objConnection);
		$objRs = new recordset(null,$objConnection); 
		if ($obju_LGUQueGroups->getbykey($_GET['group'])) {
			$objConnection->beginwork();
			$tagno = getNextIdByBranch("u_lguquetags".$obju_LGUQueGroups->code.date('Ymd'),$objConnection);
			if ($tagno>0 && trim($_GET['mobileno'])!="") {
				$obju_LGUQueTags->prepareadd();
				$obju_LGUQueTags->code = getNextIdByBranch("u_lguquetags",$objConnection);
				$obju_LGUQueTags->setudfvalue("u_group",$obju_LGUQueGroups->code);
				$obju_LGUQueTags->setudfvalue("u_tagno",$tagno);
				$obju_LGUQueTags->setudfvalue("u_date",date('Ymd'));
				$obju_LGUQueTags->setudfvalue("u_mobileno",trim(str_replace("-","",$_GET['mobileno'])));
				$actionReturn = $obju_LGUQueTags->add();
				if ($actionReturn) {
					$queno = 0;
					$objRs->queryopen("select IFNULL((NEXTID-1),0) AS U_QUEUENO from DOCIDS where COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND DOCTYPE='u_lguque".$obju_LGUQueGroups->code.date('Ymd')."'");
					if ($objRs->queryfetchrow("NAME")) {
						$queno = $objRs->fields["U_QUEUENO"];
					}
				
					$objSMSOutLog->prepareadd();
					$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objCon);
					$objSMSOutLog->deviceid = "sun";
					$objSMSOutLog->mobilenumber = trim(str_replace("-","",$_GET['mobileno']));
					$objSMSOutLog->remark = "";
					$objSMSOutLog->message = "You are now registered for ".$obju_LGUQueGroups->name." Queing sms notification at No. " . $tagno . ". " . "Now serving No. " . $queno;
					$actionReturn = $objSMSOutLog->add();
				}
				//if ($actionReturn) $tagno .= "\t" . trim($_GET['mobileno']);
			}
			if ($tagno>0 && $actionReturn) {
				printQueueNo($obju_LGUQueGroups->name,$tagno,$_GET['mobileno']);
				$objConnection->commit();
			} else {
				$msg = $_SESSION["errormessage"];
				$objConnection->rollback();
			}	
		} else $msg = "Invalid Queing Group [".$_GET['group']."]";	
	} else $msg = "Unknown Terminal ID";

	if ($msg=="") echo $tagno;
	else echo $msg;	
		
?>
