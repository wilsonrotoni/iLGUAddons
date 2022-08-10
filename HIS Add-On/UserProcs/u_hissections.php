<?php
	
	//unset($enumdocstatus["CN"]);
$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSProjects");
	
function onDrawGridColumnLabelGPSProjects($tablename,$column,$row,&$label) {
	global $objRs;
	switch ($tablename) {
		case "T1":
			if ($column=="u_disconbill") {
				switch ($label) {
					case "1": $label = "Bill"; break;
					case "0": $label = "Price"; break;
				}
			}
			break;
	}
}
	$objGrid->columntitle("u_disconbill","Apply Discount On");	
	$objGrid->columnwidth("u_disconbill",15);	

	$objGrid->columnwidth("u_showallitems",13);	
	$objGrid->columndataentry("u_showallitems","type","checkbox");
	$objGrid->columndataentry("u_showallitems","value",1);
	$objGrid->columnalignment("u_showallitems","center");
	
	$objGrid->columntitle("u_allowintsecreq","Inter-Section Requests");	
	$objGrid->columnwidth("u_allowintsecreq",21);	
	$objGrid->columndataentry("u_allowintsecreq","type","checkbox");
	$objGrid->columndataentry("u_allowintsecreq","value",1);
	$objGrid->columnalignment("u_allowintsecreq","center");

	$objGrid->columntitle("u_allowintsecpos","Inter-Section Cash Sales");	
	$objGrid->columnwidth("u_allowintsecpos",24);	
	$objGrid->columndataentry("u_allowintsecpos","type","checkbox");
	$objGrid->columndataentry("u_allowintsecpos","value",1);
	$objGrid->columnalignment("u_allowintsecpos","center");

	$objGrid->columntitle("u_prediscalert","Pre-Discharge Alert");	
	$objGrid->columnwidth("u_prediscalert",24);	
	$objGrid->columndataentry("u_prediscalert","type","checkbox");
	$objGrid->columndataentry("u_prediscalert","value",1);
	$objGrid->columnalignment("u_prediscalert","center");

	$objGrid->columntitle("u_stocktfreqalert","Stock TF Request Alert");	
	$objGrid->columnwidth("u_stocktfreqalert",24);	
	$objGrid->columndataentry("u_stocktfreqalert","type","checkbox");
	$objGrid->columndataentry("u_stocktfreqalert","value",1);
	$objGrid->columnalignment("u_stocktfreqalert","center");

	$objGrid->columnwidth("u_stocklink",15);	
	$objGrid->columndataentry("u_stocklink","type","checkbox");
	$objGrid->columndataentry("u_stocklink","value",1);
	$objGrid->columnalignment("u_stocklink","center");
	
?>