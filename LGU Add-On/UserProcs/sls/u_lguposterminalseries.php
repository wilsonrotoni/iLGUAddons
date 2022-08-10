<?php

	include_once("../common/classes/recordset.php");

	function loadu_lguposterminalseries($p_args = array(),$p_selected,$p_profitcenter) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$_link = 0;
                
		$obj = new recordset(NULL,$objConnection);
                if($p_args[1]!=""){
                    $obj->queryopen(" SELECT B.U_DOCSERIES,CONCAT(C.NAME,' [',B.U_STARTNO,'-',B.U_LASTNO,']') AS SERIESNAME
                                        FROM U_LGUPOSREGISTERS A
                                        INNER JOIN U_TERMINALSERIES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.CODE = B.U_REGISTERID
                                        INNER JOIN U_LGUFORMS C ON C.CODE = B.U_DOCSERIESNAME
                                        INNER JOIN U_LGUPROFITCENTERS D ON B.U_DOCSERIESNAME = D.U_DOCSERIES AND D.CODE = '$p_args[1]'
                                        WHERE A.BRANCH='".$_SESSION["branch"]."' AND A.COMPANY = '".$_SESSION["company"]."' AND A.U_USERID='$p_args[0]' AND B.U_NEXTNO <= B.U_LASTNO AND A.U_STATUS = 'O' ORDER BY B.U_ISDEFAULT DESC");
                } else {
                    $obj->queryopen(" SELECT U_DOCSERIES,CONCAT(C.NAME,' [',B.U_STARTNO,'-',B.U_LASTNO,']') AS SERIESNAME
                                        FROM U_LGUPOSREGISTERS A
                                        INNER JOIN U_TERMINALSERIES B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.CODE = B.U_REGISTERID
                                        INNER JOIN U_LGUFORMS C ON C.CODE = B.U_DOCSERIESNAME
                                        WHERE A.BRANCH='".$_SESSION["branch"]."' AND A.COMPANY = '".$_SESSION["company"]."' AND A.U_USERID='$p_args[0]' AND B.U_NEXTNO <= B.U_LASTNO AND A.U_STATUS = 'O' ORDER BY B.U_ISDEFAULT DESC");
                }
//              $obj->queryopen("SELECT DOCNO,CONCAT(B.NAME,' [',A.U_STARTNO,'-',A.U_LASTNO,']') AS SERIESNAME  FROM U_TERMINALSERIES A INNER JOIN U_LGUFORMS B ON B.CODE = A.U_DOCSERIESNAME INNER JOIN U_LGUPROFITCENTERS C ON C.U_DOCSERIES = A.U_DOCSERIESNAME WHERE  C.CODE = '$p_profitcenter' AND A.U_CASHIER ='$p_args[0]' AND A.U_NEXTNO <= A.U_LASTNO ORDER BY A.U_ISDEFAULT DESC");
////		else $obj->queryopen("SELECT A.U_DOCSERIESNAME,B.NAME  FROM U_TERMINALSERIES A INNER JOIN U_LGUFORMS B ON B.CODE = A.U_DOCSERIESNAME WHERE A.U_TERMINALID = '$optfiller[0]' AND A.U_CASHIER = '$optfiller[1]' ORDER BY A.U_ISDEFAULT DESC");
//		else $obj->queryopen("SELECT DOCNO,CONCAT(B.NAME,' [',A.U_STARTNO,'-',A.U_LASTNO,']') AS SERIESNAME  FROM U_TERMINALSERIES A INNER JOIN U_LGUFORMS B ON B.CODE = A.U_DOCSERIESNAME WHERE  A.U_CASHIER = '$p_args[0]' AND U_NEXTNO <= U_LASTNO ORDER BY A.U_ISDEFAULT DESC");
		
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