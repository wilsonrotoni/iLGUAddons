<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./inc/formutils.php");
		
	$data="0";
	if ($_SESSION["roleid"]!="") {
		
		$obj = new recordset(NULL,$objConnection);
		
		$obj->queryopen("select A.ROLEID AS DEPARTMENT, B.U_TYPE AS TYPE FROM USERS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.ROLEID WHERE A.USERID='".$_SESSION["userid"]."'");
		if ($obj->queryfetchrow("NAME")) {
			if ($obj->fields["TYPE"]=="NURSE") {
				$schema["u_department"]["attributes"] = "disabled";
				$userdepartment = $obj->fields["DEPARTMENT"];
			}	
		}

		$filterExp = "";
		$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$userdepartment);
		
		if ($filterExp !="") $filterExp = " AND " . $filterExp;
		
		
		$obj->queryopen("select count(*) from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.U_PREDISCHARGE=1 AND A.U_MGH=0 $filterExp");
		if ($obj->recordcount() > 0) {
			$obj->queryfetchrow();
			$data = $obj->fields[0];
		}
		$obj->queryclose();
		
	}
			
	echo $data;
?>