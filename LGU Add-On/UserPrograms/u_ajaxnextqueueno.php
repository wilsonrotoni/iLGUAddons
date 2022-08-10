

<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/masterdataschema_br.php");

	$actionReturn = true;
	$msg = "";
	if(isset($_GET['terminalid'])){
		$obju_LGUSetup = new masterdataschema_br(null,$objConnection,"u_lgusetup");
		$obju_LGUQue = new documentschema_br(null,$objConnection,"u_lguque");
		$obju_LGUPOSTerminals = new masterdataschema_br(null,$objConnection,"u_lguposterminals");
		$obju_LGUQueGroups = new masterdataschema_br(null,$objConnection,"u_lguquegroups");
		$objSMSOutLog = new smsoutlog(null,$objConnection);
		$objRs = new recordset(null,$objConnection);
		$queueinggenlink = 0;
		if ($obju_LGUSetup->getbykey('setup')) {
			$queueinggenlink = $obju_LGUSetup->getudfvalue("u_queueinggenlink");
		}
		$getnextno = true;
		if ($obju_LGUPOSTerminals->getbykey($_GET['terminalid'])) {
			if ($obju_LGUQueGroups->getbykey($obju_LGUPOSTerminals->getudfvalue("u_quegroup"))) {
				if ($queueinggenlink==1){
					//var_dump("select IFNULL(A.NEXTID,0) AS QUEUENO, IFNULL(B.NEXTID,1) AS TAGNO from DOCIDS A, DOCIDS B where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCTYPE='u_lguque".$obju_LGUQueGroups->code.date('Ymd')."' AND B.COMPANY='".$_SESSION["company"]."' AND B.BRANCH='".$_SESSION["branch"]."' AND B.DOCTYPE='u_lguquetags".$obju_LGUQueGroups->code.date('Ymd')."'");
					$objRs->queryopen("select IFNULL(A.NEXTID,0) AS QUEUENO, IFNULL(B.NEXTID,0) AS TAGNO from DOCIDS A LEFT JOIN DOCIDS B ON B.COMPANY='".$_SESSION["company"]."' AND B.BRANCH='".$_SESSION["branch"]."' AND B.DOCTYPE='u_lguquetags".$obju_LGUQueGroups->code.date('Ymd')."' where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCTYPE='u_lguque".$obju_LGUQueGroups->code.date('Ymd')."'");
					if ($objRs->queryfetchrow("NAME")) {
						//var_dump($objRs->fields);
						if ($objRs->fields["QUEUENO"]>$objRs->fields["TAGNO"]) {
							$getnextno = false;
						}	
					}
				}
				if ($getnextno) {
					$objConnection->beginwork();
					$obju_LGUQue->prepareadd();
					$obju_LGUQue->docno = getNextNoByBranch("u_lguque","",$objConnection);
					$obju_LGUQue->docid = getNextIdByBranch("u_lguque",$objConnection);
					$obju_LGUQue->setudfvalue("u_date",currentdateDB());
					$obju_LGUQue->setudfvalue("u_ctr",$_GET['terminalid']);
					$obju_LGUQue->setudfvalue("u_group",$obju_LGUPOSTerminals->getudfvalue("u_quegroup"));
                                        $obju_LGUQue->setudfvalue("u_monitor",$obju_LGUQueGroups->getudfvalue("u_monitor"));
                                        $obju_LGUQue->setudfvalue("u_groupcount",$obju_LGUQueGroups->getudfvalue("u_groupcount"));
					$obju_LGUQue->setudfvalue("u_ref",getNextIdByBranch("u_lguque".$obju_LGUPOSTerminals->getudfvalue("u_quegroup").date('Ymd'),$objConnection));
					if ($actionReturn) $actionReturn = $obju_LGUQue->add();
					if ($actionReturn) {
						if ($obju_LGUQue->getudfvalue("u_ref")>=$obju_LGUQueGroups->getudfvalue("u_maxno")) {
							$actionReturn = $obju_LGUQueGroups->executesql("delete from docids where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype='u_lguque".$obju_LGUPOSTerminals->getudfvalue("u_quegroup").date('Ymd')."'",false);
						}
					}
					if ($actionReturn) {
						$objRs->queryopen("select a.U_MOBILENO, a.U_TAGNO, b.NAME as U_GROUPNAME from u_lguquetags a inner join u_lguquegroups b on b.code=a.u_group where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_group='".$obju_LGUPOSTerminals->getudfvalue("u_quegroup")."' and a.u_date='".currentdateDB()."' and a.u_tagno>".$obju_LGUQue->getudfvalue("u_ref")." and (mod(a.u_tagno-".$obju_LGUQue->getudfvalue("u_ref").",b.u_notifyevery)=0 or a.u_tagno-".$obju_LGUQue->getudfvalue("u_ref")."=b.u_notifyon or a.u_tagno-".$obju_LGUQue->getudfvalue("u_ref")."=b.u_notifyon-2)");
						while ($objRs->queryfetchrow("NAME")) {
							$objSMSOutLog->prepareadd();
							$objSMSOutLog->trxid = getNextIDByBranch("smsoutlog",$objCon);
							$objSMSOutLog->deviceid = "sun";
							$objSMSOutLog->mobilenumber = $objRs->fields["U_MOBILENO"];
							$objSMSOutLog->remark = "";
							$objSMSOutLog->message = "Now serving ".$objRs->fields["U_GROUPNAME"]." Queing No. " . $obju_LGUQue->getudfvalue("u_ref") . ". You are at No. " . $objRs->fields["U_TAGNO"] . ".";
							$actionReturn = $objSMSOutLog->add();
							if (!$actionReturn) break;
						}
					}
					if ($actionReturn) {
						$objConnection->commit();
						$msg = $obju_LGUQue->getudfvalue("u_ref");
					} else $objConnection->rollback();
				} else $msg = "Last Generated No. Reached";	
			} else $msg = "Invalid Queing Group [".$obju_LGUPOSTerminals->getudfvalue("u_quegroup")."]";	
		} else $msg = "Invalid Terminal ID. [".$_GET['terminalid']."]";	
	} else $msg = "Unknown Terminal ID";

	if ($actionReturn) echo $msg;
	else echo $_SESSION["errormessage"];		
	
?>
