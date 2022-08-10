<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
		
	$data="0";
	if ($_SESSION["roleid"]!="") {
		
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select count(*) from U_HISIPS A WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.U_NURSED=0 AND A.DOCSTATUS NOT IN ('Cancelled') AND A.U_DEPARTMENT='".$_SESSION["roleid"]."'");
		if ($obj->recordcount() > 0) {
			$obj->queryfetchrow();
			$data = $obj->fields[0];
		}
		$obj->queryclose();
		
	}
			
	echo $data;
?>