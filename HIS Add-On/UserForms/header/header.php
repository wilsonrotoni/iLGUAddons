<?php

	include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
	
	$u_hissetupdata = getu_hissetup("U_MEDSUPCHRGPHARMA,U_MEDSUPCHRGCSR,U_MEDSUPCHRGLAB");
	
	if ($u_hissetupdata["U_MEDSUPCHRGPHARMA"]==1) {
		array_push($excludemenucmd,'u_hismedsuppharmatfs');
		array_push($excludemenucmd,'u_hismedsuppharmatfins');
	}	

	if ($u_hissetupdata["U_MEDSUPCHRGCSR"]==1) {
		array_push($excludemenucmd,'u_hismedsupcsrtfs');
		array_push($excludemenucmd,'u_hismedsupcsrtfins');
	}	

	if ($u_hissetupdata["U_MEDSUPCHRGLAB"]==1) {
		array_push($excludemenucmd,'u_hismedsuplabtfs');
		array_push($excludemenucmd,'u_hismedsuplabtfins');
	}	
	
	if ($u_hissetupdata["U_MEDSUPCHRGPHARMA"]==1 && $u_hissetupdata["U_MEDSUPCHRGCSR"]==1 && $u_hissetupdata["U_MEDSUPCHRGLAB"]==1) {
		array_push($excludemenucmd,'u_hismedsupiptfs');
		array_push($excludemenucmd,'u_hismedsupiptfins');
		array_push($excludemenucmd,'u_hismedsupips');
		array_push($excludemenucmd,'u_hismedsupoptfs');
		array_push($excludemenucmd,'u_hismedsupoptfins');
		array_push($excludemenucmd,'u_hismedsupops');
	}
	
	array_push($excludemenucmd,'u_hispharmapos');
	
	/*$menucmd["HISSTOCKPHARMA"]["title"] = "Stocks";
	if ($_SESSION["menunavigator"]=="sidenav" || ($_SESSION["menunavigator"]!="sidenav" && $_SESSION["groupid"]!="SYSADMIN")) {
		array_push($excludemenucmd,'HISSTOCK');
		$menucmd["HISSTOCKPHARMA"]["title"] = "Stocks";
	}*/	
	

?>
