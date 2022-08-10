<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdataschema.php");
	require_once("./classes/smsoutlog.php");

	$actionReturn = true;
	$msg = "";
	$id = array();
	if(isset($_GET['terminalid'])){
		$id=explode(".",$_GET['terminalid']);
		$objRs = new recordset(null,$objConnection);
		if (count($id)==4) {
			file_put_contents("c:\\users\\u_ajaxGetNextQueueNo.Input.txt",serialize($_GET));
			$objRs->queryopen("select companycode,branchcode from branches order by branchtype desc limit 1");
			if ($objRs->queryfetchrow("NAME")) {
				$_SESSION["company"]=$objRs->fields["companycode"];
				$_SESSION["branch"]=$objRs->fields["branchcode"];
			}
		}
		
		$obju_QueSetup = new masterdataschema_br(null,$objConnection,"u_quesetup");
		$obju_Que = new documentschema_br(null,$objConnection,"u_que");
		$obju_QueTerminals = new masterdataschema_br(null,$objConnection,"u_queterminals");
		$obju_QueGroups = new masterdataschema_br(null,$objConnection,"u_quegroups");
		$objSMSOutLog = new smsoutlog(null,$objConnection);
		$queueinggenlink = 0;
		if ($obju_QueSetup->getbykey('setup')) {
			$queueinggenlink = $obju_QueSetup->getudfvalue("u_genlink");
		}
		//$queueinggenlink = 1;
		$getnextno = true;
		if ($obju_QueTerminals->getbysql("CODE='".$_GET['terminalid']."' OR NAME='".$_GET['terminalid']."'")) {
			if ($obju_QueGroups->getbykey($obju_QueTerminals->getudfvalue("u_quegroup"))) {
				if ($queueinggenlink==1){
					//var_dump("select IFNULL(A.NEXTID,0) AS QUEUENO, IFNULL(B.NEXTID,1) AS TAGNO from DOCIDS A, DOCIDS B where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCTYPE='u_lguque".$obju_LGUQueGroups->code.date('Ymd')."' AND B.COMPANY='".$_SESSION["company"]."' AND B.BRANCH='".$_SESSION["branch"]."' AND B.DOCTYPE='u_lguquetags".$obju_LGUQueGroups->code.date('Ymd')."'");
					$objRs->queryopen("select IFNULL(A.NEXTID,0) AS QUEUENO, IFNULL(B.NEXTID,0) AS TAGNO from DOCIDS A LEFT JOIN DOCIDS B ON B.COMPANY='".$_SESSION["company"]."' AND B.BRANCH='".$_SESSION["branch"]."' AND B.DOCTYPE='u_quetags".$obju_QueGroups->code.date('Ymd')."' where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCTYPE='u_que".$obju_QueGroups->code.date('Ymd')."'");
					if ($objRs->queryfetchrow("NAME")) {
						//var_dump($objRs->fields);
						if ($objRs->fields["QUEUENO"]>=$objRs->fields["TAGNO"]) {
							$getnextno = false;
						}	
					}
				}
				if ($getnextno) {
					$objConnection->beginwork();
					$obju_Que->prepareadd();
					$obju_Que->docno = getNextNoByBranch("u_que","",$objConnection);
					$obju_Que->docid = getNextIdByBranch("u_que",$objConnection);
					$obju_Que->setudfvalue("u_date",currentdateDB());
					$obju_Que->setudfvalue("u_ctr",$obju_QueTerminals->code);
					$obju_Que->setudfvalue("u_group",$obju_QueTerminals->getudfvalue("u_quegroup"));
					$obju_Que->setudfvalue("u_ref",getNextIdByBranch("u_que".$obju_QueTerminals->getudfvalue("u_quegroup").date('Ymd'),$objConnection));
					if ($actionReturn) $actionReturn = $obju_Que->add();
					if ($actionReturn) {
						if ($obju_Que->getudfvalue("u_ref")>=$obju_QueGroups->getudfvalue("u_maxno") && $obju_QueGroups->getudfvalue("u_maxno")>0) {
							$actionReturn = $obju_QueGroups->executesql("delete from docids where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='u_que".$obju_QueTerminals->getudfvalue("u_quegroup").date('Ymd')."'",false);
						}
					}
					if ($actionReturn) {
						$objRs->queryopen("select a.U_MOBILENO, a.U_TAGNO, b.NAME as U_GROUPNAME from u_quetags a inner join u_quegroups b on b.code=a.u_group where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_group='".$obju_QueTerminals->getudfvalue("u_quegroup")."' and a.u_date='".currentdateDB()."' and a.u_tagno>".$obju_Que->getudfvalue("u_ref")." and (mod(a.u_tagno-".$obju_Que->getudfvalue("u_ref").",b.u_notifyevery)=0 or a.u_tagno-".$obju_Que->getudfvalue("u_ref")."=b.u_notifyon or a.u_tagno-1=b.u_notifyon-2)");
						while ($objRs->queryfetchrow("NAME")) {
							$objSMSOutLog->prepareadd();
							$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objCon);
							$objSMSOutLog->deviceid = "sun";
							$objSMSOutLog->mobilenumber = $objRs->fields["U_MOBILENO"];
							$objSMSOutLog->remark = "";
							$objSMSOutLog->message = "Now serving ".$objRs->fields["U_GROUPNAME"]." Queing No. " . $obju_Que->getudfvalue("u_ref") . ". You are at No. " . $objRs->fields["U_TAGNO"] . ".";
							$actionReturn = $objSMSOutLog->add();
							if (!$actionReturn) break;
						}
					}
					if ($actionReturn) {
						$objConnection->commit();
						$msg = $obju_Que->getudfvalue("u_ref");
					} else $objConnection->rollback();
				} else $msg = "Last generated number was reached.";	
			} else $msg = "Invalid queing group [".$obju_QueTerminals->getudfvalue("u_quegroup")."]";	
		} else $msg = "Invalid Terminal ID. [".$_GET['terminalid']."]";	
	} else $msg = "Unknown Terminal ID";
	if ($actionReturn) {
		if (count($id)==4) file_put_contents("c:\\users\\u_ajaxGetNextQueueNo.Output.txt",$msg);
		echo $msg;
	} else {
		if (count($id)==4) file_put_contents("c:\\users\\u_ajaxGetNextQueueNo.Output.txt",$_SESSION["errormessage"]);
		echo $_SESSION["errormessage"];
	}			
	
?>
