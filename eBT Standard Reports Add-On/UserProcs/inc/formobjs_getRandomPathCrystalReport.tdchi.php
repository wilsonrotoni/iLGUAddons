<?php 
function getRandomPathCrystalReport() {
	global $page;
	global $httpVars;
	//if ($_SESSION["userid"]=="manager") $page->reportport=3307;
	
	$pathcrystalreport = $_SESSION['pathcrystalreport'];
	//$page->console->insertVar($httpVars["reportid"]);
	switch ($httpVars["reportid"])
	{
		case "hms_dl": $pathcrystalreport = "http://192.168.1.227:83/ebt_rpts"; break;
		case "hms_prl": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
		case "hms_lablist": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
		default:
			switch ($_SESSION["userid"]) {
			case "160003": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
			default:
				switch ($_SESSION["groupid"]) {
					case "FIN-ACCT": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "HIS-RECORDS": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "FIN-PURCH": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "FIN-BILLING": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "NSG-OPD": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "HIS-ADMITTING": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "RAD": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "RAD-CT-SCAN": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "RAD-ULTRASOUND": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "RAD-X-RAY": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "SYSADMIN": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "FIN-PHIC": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "FIN-PHIC2": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "FIN-CASHIER": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "PHARMACY": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "PHA": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "FIN-CSR": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "LAB": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "LAB2": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "PULM": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
					case "SYSADMIN": $pathcrystalreport = "http://192.168.1.228:83/ebt_rpts"; break;
				}
				
		}
	}		
		if ($pathcrystalreport=="http://192.168.1.228:83/ebt_rpts") {
			$crport=intval(rand ( 1000 , 4000)/1000);
			//var_dump(array("melvin",$crport));
			switch ($crport) {
				case 2: return str_replace(":83",":8300",$pathcrystalreport); break;
				case 3: return str_replace(":83",":8301",$pathcrystalreport); break;
				case 4: return str_replace(":83",":8302",$pathcrystalreport); break;
				default: return $pathcrystalreport; break;
			}
		} else return $pathcrystalreport;
}


?>