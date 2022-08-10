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
			$objRs->queryopen("select docno, u_inspectdate, u_inspecttime, u_gi_address, u_gi_businessname, u_gi_owneroccupantname, u_gi_representativename, u_gi_owneroccupantno, u_gi_representativeno, u_inspectorremarks, u_inspectbystatus, u_inspectorremarkhistory from u_fsins where company='LGU' and branch='main' and docstatus='O' and docno = '".$_GET["docno"]."'"); 
			if ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["docno"] = $objRs->fields["docno"];
				if (!isset($objRs->fields["u_inspectdate"])) {
					$data["inspectdate"] = "";
				} else {
					$data["inspectdate"] = formatDateToHttp($objRs->fields["u_inspectdate"]);
				}
				$data["inspecttime"] = $objRs->fields["u_inspecttime"];
				$data["gi_businessname"] = $objRs->fields["u_gi_businessname"];
				$data["address"] = $objRs->fields["u_gi_address"];
				$data["gi_owner"] = $objRs->fields["u_gi_owneroccupantname"];
				$data["gi_authrep"] = $objRs->fields["u_gi_representativename"];
				$data["gi_ownercontactno"] = $objRs->fields["u_gi_owneroccupantno"];
				$data["gi_authrepcontactno"] = $objRs->fields["u_gi_representativeno"];
				$data["inspectorremarks"] = $objRs->fields["u_inspectorremarks"];
				$data["inspectorremarkhistory"] = $objRs->fields["u_inspectorremarkhistory"];
				$data["inspectbystatus"] = $objRs->fields["u_inspectbystatus"];
				array_push($objs["fsins"],$data);
 			}
			echo json_encode($objs); 
			break;
		case "FireSafetyInspections":
			$objs["firesafetyinspections"] = array();
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select a.docno, a.u_inspectdate, a.u_inspecttime, a.u_gi_address, a.u_gi_businessname, a.u_gi_owneroccupantname, a.u_gi_representativename, a.u_gi_owneroccupantno, a.u_gi_representativeno, a.u_inspectorremarks, a.u_inspectbystatus, a.u_inspectorremarkhistory,a.u_recommendation from u_fsins a inner join u_fsinspectors b on b.code=a.u_inspector and b.u_userid='".$_GET["userid"]."' where a.company='lgu' and a.branch='main' and a.docstatus='O' $docnoExp"); 
			while ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["docno"] = $objRs->fields["docno"];
				if (!isset($objRs->fields["u_inspectdate"])) {
					$data["inspectdate"] = "";
				} else {
					$data["inspectdate"] = formatDateToHttp($objRs->fields["u_inspectdate"]);
				}
				$data["inspecttime"] = $objRs->fields["u_inspecttime"];
				$data["gi_businessname"] = $objRs->fields["u_gi_businessname"];
				$data["address"] = $objRs->fields["u_gi_address"];
				$data["gi_owner"] = $objRs->fields["u_gi_owneroccupantname"];
				$data["gi_authrep"] = $objRs->fields["u_gi_representativename"];
				$data["gi_ownercontactno"] = $objRs->fields["u_gi_owneroccupantno"];
				$data["gi_authrepcontactno"] = $objRs->fields["u_gi_representativeno"];
				$data["inspectorremarks"] = $objRs->fields["u_inspectorremarks"];
				$data["inspectorremarkhistory"] = $objRs->fields["u_inspectorremarkhistory"];
				$data["inspectbystatus"] = $objRs->fields["u_inspectbystatus"];
				array_push($objs["firesafetyinspections"],$data);
 			}
			echo json_encode($objs); 
			break;
                case "ClearanceInspections":
			$objs["inspections"] = array();
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select a.docno, a.u_inspectdate, a.u_inspecttime, a.u_address, a.u_gi_businessname, a.u_gi_owneroccupantname, a.u_gi_representativename, a.u_gi_owneroccupantno, a.u_gi_representativeno, a.u_inspectorremarks, a.u_inspectbystatus, a.u_inspectorremarkhistory,a.u_recommendations from u_fsins a inner join u_fsinspectors b on b.code=a.u_inspector and b.u_userid='".$_GET["userid"]."' where a.company='lgu' and a.branch='main' and a.docstatus='O' $docnoExp"); 
			while ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["docno"] = $objRs->fields["docno"];
				if (!isset($objRs->fields["u_inspectdate"])) {
					$data["inspectdate"] = "";
				} else {
					$data["inspectdate"] = formatDateToHttp($objRs->fields["u_inspectdate"]);
				}
                                $data["inspecttime"] = $objRs->fields["u_inspecttime"];
				$data["businessname"] = $objRs->fields["u_gi_businessname"];
				$data["address"] = $objRs->fields["u_address"];
				$data["owner"] = $objRs->fields["u_gi_owneroccupantname"];
				$data["authrep"] = $objRs->fields["u_gi_representativename"];
				$data["ownercontactno"] = $objRs->fields["u_gi_owneroccupantno"];
				$data["authrepcontactno"] = $objRs->fields["u_gi_representativeno"];
                                $data["recommendation"] = $objRs->fields["u_recommendations"];
				$data["inspectorremarks"] = $objRs->fields["u_inspectorremarks"];
				$data["inspectorremarkhistory"] = $objRs->fields["u_inspectorremarkhistory"];
				$data["inspectbystatus"] = $objRs->fields["u_inspectbystatus"];
				array_push($objs["inspections"],$data);
 			}
			echo json_encode($objs); 
			break;
		default:
			$actionReturn = raiseError("Invalid Type [".$_GET["type"]."].");
			break;	
	}
	
	
?>
