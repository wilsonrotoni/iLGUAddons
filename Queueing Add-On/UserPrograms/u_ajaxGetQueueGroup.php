<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/masterdataschema_br.php");

	$msg = "";
	file_put_contents("c:\\users\\u_ajaxGetQueueGroup.Input-".str_replace(".","",$_GET['terminalid']).".txt",serialize($_GET));
	if(isset($_GET['terminalid'])){
		$id=explode(".",$_GET['terminalid']);
		//var_dump(count($id));
		$objRs = new recordset(null,$objConnection);
		if (count($id)==4) {
			$objRs->queryopen("select companycode,branchcode from branches order by branchtype desc limit 1");
			if ($objRs->queryfetchrow("NAME")) {
				$_SESSION["company"]=$objRs->fields["companycode"];
				$_SESSION["branch"]=$objRs->fields["branchcode"];
			}
		}
		$obju_QueTerminals = new masterdataschema_br(null,$objConnection,"u_queterminals");
		//$obju_QueTerminals->setdebug();
		$obju_QueGroups = new masterdataschema_br(null,$objConnection,"u_quegroups");
		if ($obju_QueTerminals->getbysql("CODE='".$_GET['terminalid']."' OR NAME='".$_GET['terminalid']."'")) {
			if ($obju_QueGroups->getbykey($obju_QueTerminals->getudfvalue("u_quegroup"))) {
				$objRs->queryopen("select IFNULL(MAX(CONVERT(B.U_REF, SIGNED)),IFNULL(A.NEXTID-1,0)) AS QUEUENO from DOCIDS A LEFT JOIN U_QUE B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.U_DATE='".date('Ymd')."' AND B.U_CTR='".$obju_QueTerminals->code."' where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCTYPE='u_que".$obju_QueGroups->code.date('Ymd')."' ");
				if ($objRs->queryfetchrow("NAME")) {
					$msg = $obju_QueGroups->name." ".$obju_QueTerminals->code."`".$objRs->fields["QUEUENO"];
				} else $msg = $obju_QueGroups->name." ".$obju_QueTerminals->code."`0";	
				
			} else $msg = "Invalid Queing Group [".$obju_QueTerminals->getudfvalue("u_quegroup")."]`?";	
		} else $msg = "Invalid Terminal ID. [".$_GET['terminalid']."]`?";	
		//var_dump($obju_QueTerminals->sqls);
	} else $msg = "Unknown Terminal ID`?";
	file_put_contents("c:\\users\\u_ajaxGetQueueGroup.Output-".str_replace(".","",$_GET['terminalid']).".txt",$msg);
	echo $msg;
?>
