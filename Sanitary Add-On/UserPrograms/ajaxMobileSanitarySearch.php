<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./utils/login.php");
	include_once("./classes/masterdataschema_br.php");

	$actionReturn = true;
	$corid=$_GET["corid"];

	switch ($_GET["type"]) {
		case "ClearanceInspections":
			$objs["inspections"] = array();
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select a.docno, a.u_businessname from u_sanitaryins a inner join u_sanitaryinspectors b on b.code=a.u_inspector and b.u_userid='".$_GET["userid"]."' where a.company='LGU' and a.branch='main' and a.docstatus='O'");
			while ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["docno"] = $objRs->fields["docno"];
				$data["businessname"] = $objRs->fields["u_businessname"];
				array_push($objs["inspections"],$data);
 			}
			echo json_encode($objs); 
			break;
		default:
			$actionReturn = raiseError("Invalid Type [".$_GET["type"]."].");
			break;	
	}
	
	
?>
