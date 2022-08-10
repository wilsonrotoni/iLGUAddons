<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
		
	$data="0";
	if ($_SESSION["roleid"]!="") {
		
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select count(*) from U_HISMEDSUPSTOCKREQUESTS A, U_HISMEDSUPSTOCKREQUESTITEMS B WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND  A.U_TODEPARTMENT IN ('".$_SESSION["roleid"]."') AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCSTATUS NOT IN ('C','D','CN')");
		if ($obj->recordcount() > 0) {
			$obj->queryfetchrow();
			$data = $obj->fields[0];
		}
		$obj->queryclose();
		
	}
			
	echo $data;
?>