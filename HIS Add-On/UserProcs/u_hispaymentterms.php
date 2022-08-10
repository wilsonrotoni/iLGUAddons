<?php
	
	$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSProjects");
	
function onDrawGridColumnLabelGPSProjects($tablename,$column,$row,&$label) {
	global $objRs;
	switch ($tablename) {
		case "T1":
			if ($column=="u_prepaid") {
				$label = slgetdisplayudfenums("u_hispaymentterms","prepaid",$label);
			}
			if ($column=="u_specialprice") {
				$label = slgetdisplayudfenums("u_hispaymentterms","specialprice",$label);
			}
			break;
	}
}
	
	//unset($enumdocstatus["CN"]);
	$objGrid->columnwidth("u_specialprice",12);
	$objGrid->columnwidth("u_prepaid",10);
	$objGrid->columnvisibility("name",false);
	$objGrid->automanagecolumnwidth = false;
?>