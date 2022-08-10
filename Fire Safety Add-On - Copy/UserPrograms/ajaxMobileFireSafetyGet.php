<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./utils/login.php");
	include_once("./classes/masterdataschema_br.php");

	$actionReturn = true;
	$docno=$_GET["docno"];
	if ($docno!="") $docnoExp = " and a.docno='$docno'";
	
	switch ($_GET["type"]) {
		case "FSIns":
			$objs["fsins"] = array();
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select docno, u_inspectdate, u_gi_businessname, u_recommendations, u_inspectbystatus from u_fsins where company='LGU' and branch='main' and docstatus='O' and docno = '".$_GET["docno"]."'"); 
			if ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["docno"] = $objRs->fields["docno"];
				if (!isset($objRs->fields["u_inspectdate"])) {
					$data["inspectdate"] = "";
				} else {
					$data["inspectdate"] = formatDateToHttp($objRs->fields["u_inspectdate"]);
				}
				$data["gi_businessname"] = $objRs->fields["u_gi_businessname"];
				$data["recommendations"] = $objRs->fields["u_recommendations"];
				$data["inspectbystatus"] = $objRs->fields["u_inspectbystatus"];
				array_push($objs["fsins"],$data);
 			}
			echo json_encode($objs); 
			break;
		case "FireSafetyInspections":
			$objs["firesafetyinspections"] = array();
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select a.docno, a.u_inspectdate, a.u_gi_businessname, a.u_recommendations, a.u_inspectbystatus from u_fsins a inner join u_fsinspectors b on b.code=a.u_inspector and b.u_userid='".$_GET["userid"]."' where a.company='lgu' and a.branch='main' and a.docstatus='O' $docnoExp"); 
			while ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["docno"] = $objRs->fields["docno"];
				if (!isset($objRs->fields["u_inspectdate"])) {
					$data["inspectdate"] = "";
				} else {
					$data["inspectdate"] = formatDateToHttp($objRs->fields["u_inspectdate"]);
				}
				$data["gi_businessname"] = $objRs->fields["u_gi_businessname"];
				$data["recommendations"] = $objRs->fields["u_recommendations"];
				$data["inspectbystatus"] = $objRs->fields["u_inspectbystatus"];
				array_push($objs["firesafetyinspections"],$data);
 			}
			echo json_encode($objs); 
			break;
		default:
			$actionReturn = raiseError("Invalid Type [".$_GET["type"]."].");
			break;	
	}
	
	
?>
