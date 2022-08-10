<?php

	include_once("../common/classes/recordset.php");

	function loadu_hisbilldoctors($p_args = array(),$p_selected="") {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select C.CODE, C.NAME from U_HISBILLS A, U_HISBILLCONSULTANCYFEES B, U_HISDOCTORS C where C.CODE=B.U_DOCTORID AND B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO = '$p_args[0]' group by C.CODE order by C.NAME");
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