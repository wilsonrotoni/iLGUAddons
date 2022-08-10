<?php

$u_HISSectionsList = array();

function initArrayu_HISSectionsListGPSHIS() { 
	global $objConnection;
	global $u_HISSectionsList;
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select code, name from u_hissections");
	while ($objRs->queryfetchrow()) {
		$u_HISSectionsList[$objRs->fields[0]] = $objRs->fields[1];
	}
}


?> 

