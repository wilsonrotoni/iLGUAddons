<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./utils/login.php");
	include_once("./classes/masterdataschema_br.php");

	$actionReturn = true;

	switch ($_GET["type"]) {
		case "ZoningClearanceInspections":
			$objs["inspections"] = array();
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select a.docno, a.u_inspectdate, a.u_businessname, a.u_address, a.u_inspectorremarks, a.u_inspectbystatus from u_zoningclrins a inner join u_zoningclrinspectors b on b.code=a.u_inspector and b.u_userid='".$_GET["userid"]."' where a.company='lgu' and a.branch='main' and a.docstatus='O'"); 
			while ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["docno"] = $objRs->fields["docno"];
				if (!isset($objRs->fields["u_inspectdate"])) {
					$data["inspectdate"] = "";
				} else {
					$data["inspectdate"] = formatDateToHttp($objRs->fields["u_inspectdate"]);
				}
				$data["businessname"] = $objRs->fields["u_businessname"];
				$data["address"] = $objRs->fields["u_address"];
				$data["inspectorremarks"] = $objRs->fields["u_inspectorremarks"];
				$data["inspectbystatus"] = $objRs->fields["u_inspectbystatus"];
				array_push($objs["inspections"],$data);
 			}
			echo json_encode($objs); 
			break;
		default:
			$actionReturn = raiseError("Invalid Type [".$_GET["type"]."].");
			break;	
	}
	
	function iifnull($bool,$value) {
		if (!isset($bool)) return $value;
		else return $bool;
	}
	
	
?>
