<?php

	include_once("../common/classes/recordset.php");

	function loadu_hislabtesttypenotes($p_args = array(),$p_selected="") {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		//$obj->queryopen("select U_ITEMCODE, U_ITEMDESC from U_PROJECTACTIVITYITEMS where CODE = '$p_args[0]' order by U_ITEMDESC");
		$obj->queryopen("select CODE, NAME from U_HISLABTESTTYPENOTES where u_type = '$p_args[0]' and u_format='normal' and u_doctorid in ('$p_args[1]','')  order by NAME");
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
	function slgetdisplayu_hislabtesttypenotes($p_selected) {
		global $objConnection;
		$data = "[unknown]";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select NAME from U_HISLABTESTTYPENOTES where CODE = '$p_selected'");
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