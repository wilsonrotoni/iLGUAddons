<?php

	include_once("../common/classes/recordset.php");

	function loadu_hisdoctorservicetypes($p_args = array(),$p_selected="") {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select B.ITEMCODE, B.ITEMDESC from U_HISDOCTORSERVICETYPES A, ITEMS B where B.ITEMCODE=A.U_SERVICETYPE AND A.CODE = '$p_args[0]' order by B.ITEMDESC");
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$obj->queryclose();

		echo @$_html;
	}
	/*
	function slgetdisplayu_projectactivityitems($p_selected) {
		global $objConnection;
		$data = "[unknown]";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select NAME from U_PROJECTACTIVITIES where CODE = '$p_selected'");
		if ($obj->recordcount() > 0) {
			if ($obj->queryfetchrow()) {
				$data = $obj->fields[0];
			} else {
				$data = $p_selected;
			}
		}
		$obj->queryclose();
		return $data;
	}
	*/

	
?>