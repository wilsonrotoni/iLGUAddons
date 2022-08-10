<?php

	include_once("../common/classes/recordset.php");

	function loadu_bplkinds($p_args = array(),$p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$_link = 0;
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select U_BPLKINDCHARLINK from U_LGUSETUP");
		if ($obj->queryfetchrow()) {
			$_link = $obj->fields[0];
		}
		if ($_link==0) $obj->queryopen("select CODE from U_BPLKINDS order by CODE");
		else $obj->queryopen("select U_KIND from U_BPLCHARACTERKINDS where CODE = '$p_args[0]' order by U_KIND");
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[0] . "</option>";
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