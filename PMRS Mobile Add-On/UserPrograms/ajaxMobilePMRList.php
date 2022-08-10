<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./utils/login.php");
	include_once("./classes/masterdataschema_br.php");

	$actionReturn = true;
	//$corid=$_GET["corid"];
	file_put_contents("c:/users/ajaxMobilePMRList.txt",serialize($_GET));
	switch ($_GET["type"]) {
		case "Download":
			$objs["ambulantvendors"] = array();
			$objs["cashtickets"] = array();
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select code, name from u_pmrambulantvendors"); // and name like '".$_GET["customername"]."'
			while ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["vendorcode"] = $objRs->fields["code"];
				$data["vendorname"] = $objRs->fields["name"];
				array_push($objs["ambulantvendors"],$data);
 			}
			$objRs->queryopen("select u_denomination from u_pmrcashtickets"); // and name like '".$_GET["customername"]."'
			while ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["denomination"] = $objRs->fields["u_denomination"];
				array_push($objs["cashtickets"],$data);
 			}
			file_put_contents("c:/users/ajaxMobilePMRListResponse.txt",serialize($objs));
			echo json_encode($objs); 
			break;
		default:
			$actionReturn = raiseError("Invalid Type [".$_GET["type"]."].");
			echo json_encode($_SESSION["errormessage"]); 
			break;	
	}
	
	function iifnull($bool,$value) {
		if (!isset($bool)) return $value;
		else return $bool;
	}
	
	
?>
