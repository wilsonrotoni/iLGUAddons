<?php
 
$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSProjects");
 
$objRs = new recordset(null,$objConnection); 

function onDrawGridColumnLabelGPSProjects($tablename,$column,$row,&$label) {
	global $objRs;
	switch ($tablename) {
		case "T1":
			if ($column=="u_chapter") {
				$objRs->queryopen("select name from u_hisicdchapters where code='".$label."'");
				if ($objRs->queryfetchrow()) $label .= " - " . $objRs->fields[0];
			}	
			break;
	}
}

$objGrid->columnwidth("code",9);
$objGrid->columnwidth("u_code2",7);
$objGrid->columnwidth("name",80);
$objGrid->columnwidth("u_chapter",50);
$objGrid->columnvisibility("u_code2",false);
?> 

