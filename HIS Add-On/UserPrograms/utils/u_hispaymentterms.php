<?php 
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	
	function getu_hispaymentterms($code,$p_fields) {
		global $objConnection;
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select " . $p_fields . " from U_HISPAYMENTTERMS WHERE CODE='$code'");
		if ($obj->recordcount() > 0) {
			if ($obj->queryfetchrow("NAME")) {
				$data = $obj->fields;
			} else {
				$data = "";
			}
		}
		$obj->queryclose();
		return $data;
	}

	
?>