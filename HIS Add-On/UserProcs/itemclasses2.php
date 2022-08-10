<?php


function onGridColumnTextDraw($tablename,$column,$row,&$label) {
	global $objConnection;
	switch ($tablename) {
		case "T1":
			if ($column=="u_section") {
				$objRs = new recordset(null,$objConnection);
				$objRs->queryopen("select name from u_hissections where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			}
			break;
	}
}

?> 

