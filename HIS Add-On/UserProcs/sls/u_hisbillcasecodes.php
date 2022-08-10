<?php

	include_once("../common/classes/recordset.php");

	function loadu_hisbillcasecodes($p_args = array(),$p_selected="") {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("select CODE from (select C.U_ICDCODE AS CODE from U_HISBILLS A, U_HISMEDRECS B, U_HISMEDRECICDS C where C.COMPANY=B.COMPANY AND C.BRANCH=B.BRANCH AND C.DOCID=B.DOCID AND B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.U_REFTYPE=A.U_REFTYPE AND B.U_REFNO=A.U_REFNO AND A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO = '$p_args[0]' union all select C.U_RVSCODE AS CODE from U_HISBILLS A, U_HISMEDRECS B, U_HISMEDRECIRVS C where C.COMPANY=B.COMPANY AND C.BRANCH=B.BRANCH AND C.DOCID=B.DOCID AND B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.U_REFTYPE=A.U_REFTYPE AND B.U_REFNO=A.U_REFNO AND A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO = '$p_args[0]') as X group by CODE");
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