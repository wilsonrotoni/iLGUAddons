<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./utils/login.php");
	include_once("./classes/masterdataschema_br.php");

	$actionReturn = true;
	$docno=$_GET["docno"];
	if ($docno!="") $docnoExp = " and a.docno='$docno'";
	echo "select a.docno, c.u_btelno,a.u_inspectdate, concat(c.u_lastname,', ',c.u_firstname,' ',c.u_middlename) as u_owner, ifnull(a.u_inspecttime,'') as u_inspecttime, a.u_businessname, a.u_address, a.u_recommendation, a.u_inspectorremarks, a.u_inspectbystatus, a.u_inspectorremarkhistory, c.u_authrep, c.u_contactno from u_zoningclrins a inner join u_zoningclrapps c on c.company=a.company and c.branch=a.branch and c.docno=a.u_appno inner join u_zoningclrinspectors b on b.code=a.u_inspector and b.u_userid='".$_GET["userid"]."' where a.company='LGU' and a.branch='main' and a.docstatus='O' $docnoExp";
	switch ($_GET["type"]) {
		case "ClearanceInspections":
			$objs["inspections"] = array();
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select a.docno, c.u_btelno,a.u_inspectdate, concat(c.u_lastname,', ',c.u_firstname,' ',c.u_middlename) as u_owner, ifnull(a.u_inspecttime,'') as u_inspecttime, a.u_businessname, a.u_address, a.u_recommendation, a.u_inspectorremarks, a.u_inspectbystatus, a.u_inspectorremarkhistory, c.u_authrep, c.u_contactno from u_zoningclrins a inner join u_zoningclrapps c on c.company=a.company and c.branch=a.branch and c.docno=a.u_appno inner join u_zoningclrinspectors b on b.code=a.u_inspector and b.u_userid='".$_GET["userid"]."' where a.company='LGU' and a.branch='main' and a.docstatus='O' $docnoExp"); 
			while ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["docno"] = $objRs->fields["docno"];
				if (!isset($objRs->fields["u_inspectdate"])) {
					$data["inspectdate"] = "";
				} else {
					$data["inspectdate"] = formatDateToHttp($objRs->fields["u_inspectdate"]);
				}
				$data["inspecttime"] = $objRs->fields["u_inspecttime"];
				$data["businessname"] = $objRs->fields["u_businessname"];
				$data["address"] = $objRs->fields["u_address"];
				$data["owner"] = $objRs->fields["u_owner"];
				$data["ownercontactno"] = $objRs->fields["u_contactno"];
				$data["authrep"] = $objRs->fields["u_authrep"];
				$data["authrepcontactno"] = $objRs->fields["u_btelno"];
				$data["recommendation"] = $objRs->fields["u_recommendation"];
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
