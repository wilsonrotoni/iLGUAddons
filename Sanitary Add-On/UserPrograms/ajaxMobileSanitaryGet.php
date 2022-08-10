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
		case "ClearanceInspections":
			$objs["inspections"] = array();
			//$objs["sanitarycategories"] = array();
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select a.docno, a.u_inspectdate, ifnull(a.u_inspecttime,'') as u_inspecttime, a.u_businessname, a.u_address, a.u_category, a.u_recommendation, a.u_inspectorremarks, a.u_inspectbystatus, a.u_inspectorremarkhistory, c.u_owner, c.u_manager from u_sanitaryins a inner join u_sanitaryinspectors b on b.code=a.u_inspector and b.u_userid='".$_GET["userid"]."' inner join u_sanitaryapps c on c.company=a.company and c.branch=a.branch and c.docno=a.u_appno where a.company='LGU' and a.branch='main' and a.docstatus='O' $docnoExp"); 
			//, a.u_totaldemerits, round(a.u_ratingperc,0) as u_ratingperc, a.u_sf1, a.u_sf2, a.u_sf3, a.u_oth1, a.u_oth2, a.u_oth3, a.u_cop, a.u_mop, a.u_tp, a.u_hf, a.u_ws, a.u_lwm, a.u_swm, a.u_wof, a.u_pof, a.u_vc, a.u_cnt, a.u_pc, a.u_ham, a.u_coau, a.u_scoau, a.u_dc, a.u_mic, a.u_oth1rcm, a.u_oth2rcm, a.u_oth3rcm, a.u_coprcm, a.u_moprcm, a.u_tprcm, a.u_hfrcm, a.u_wsrcm, a.u_lwmrcm, a.u_swmrcm, a.u_wofrcm, a.u_pofrcm, a.u_vcrcm, a.u_cntrcm, a.u_pcrcm, a.u_hamrcm, a.u_coaurcm, a.u_scoaurcm, a.u_dcrcm, a.u_micrcm
			if ($objRs->queryfetchrow("NAME")) {
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
				$data["ownercontactno"] = "";
				$data["authrep"] = $objRs->fields["u_manager"];
				$data["authrepcontactno"] = "";
				$data["recommendation"] = $objRs->fields["u_recommendation"];
				//$data["category"] = $objRs->fields["u_category"];
				$data["inspectorremarks"] = $objRs->fields["u_inspectorremarks"];
				$data["inspectorremarkhistory"] = $objRs->fields["u_inspectorremarkhistory"];
				$data["inspectbystatus"] = $objRs->fields["u_inspectbystatus"];
				/*$data["totaldemerits"] = $objRs->fields["u_totaldemerits"];
				$data["ratingperc"] = $objRs->fields["u_ratingperc"];
				$data["sf1"] = $objRs->fields["u_sf1"];
				$data["sf2"] = $objRs->fields["u_sf2"];
				$data["sf3"] = $objRs->fields["u_sf3"];
				$data["oth1"] = $objRs->fields["u_oth1"];
				$data["oth2"] = $objRs->fields["u_oth2"];
				$data["oth3"] = $objRs->fields["u_oth3"];
				$data["cop"] = $objRs->fields["u_cop"];
				$data["mop"] = $objRs->fields["u_mop"];
				$data["tp"] = $objRs->fields["u_tp"];
				$data["hf"] = $objRs->fields["u_hf"];
				$data["ws"] = $objRs->fields["u_ws"];
				$data["lwm"] = $objRs->fields["u_lwm"];
				$data["swm"] = $objRs->fields["u_swm"];
				$data["wof"] = $objRs->fields["u_wof"];
				$data["pof"] = $objRs->fields["u_pof"];
				$data["vc"] = $objRs->fields["u_vc"];
				$data["cnt"] = $objRs->fields["u_cnt"];
				$data["pc"] = $objRs->fields["u_pc"];
				$data["ham"] = $objRs->fields["u_ham"];
				$data["coau"] = $objRs->fields["u_coau"];
				$data["scoau"] = $objRs->fields["u_scoau"];
				$data["dc"] = $objRs->fields["u_dc"];
				$data["mic"] = $objRs->fields["u_mic"];
				$data["oth1rcm"] = $objRs->fields["u_oth1rcm"];
				$data["oth2rcm"] = $objRs->fields["u_oth2rcm"];
				$data["oth3rcm"] = $objRs->fields["u_oth3rcm"];
				$data["coprcm"] = $objRs->fields["u_coprcm"];
				$data["moprcm"] = $objRs->fields["u_moprcm"];
				$data["tprcm"] = $objRs->fields["u_tprcm"];
				$data["hfrcm"] = $objRs->fields["u_hfrcm"];
				$data["wsrcm"] = $objRs->fields["u_wsrcm"];
				$data["lwmrcm"] = $objRs->fields["u_lwmrcm"];
				$data["swmrcm"] = $objRs->fields["u_swmrcm"];
				$data["wofrcm"] = $objRs->fields["u_wofrcm"];
				$data["pofrcm"] = $objRs->fields["u_pofrcm"];
				$data["vcrcm"] = $objRs->fields["u_vcrcm"];
				$data["cntrcm"] = $objRs->fields["u_cntrcm"];
				$data["pcrcm"] = $objRs->fields["u_pcrcm"];
				$data["hamrcm"] = $objRs->fields["u_hamrcm"];
				$data["coaurcm"] = $objRs->fields["u_coaurcm"];
				$data["scoaurcm"] = $objRs->fields["u_scoaurcm"];
				$data["dcrcm"] = $objRs->fields["u_dcrcm"];
				$data["micrcm"] = $objRs->fields["u_micrcm"];*/
				array_push($objs["inspections"],$data);
 			}
			/*if ($docno=="") {
				$objRs->queryopen("select code, name, u_sf1, u_sf2, u_sf3 from u_sanitarycategories order by name"); 
				while ($objRs->queryfetchrow("NAME")) {
					$data = array();
					$data["code"] = $objRs->fields["code"];
					$data["name"] = $objRs->fields["name"];
					$data["sf1"] = $objRs->fields["u_sf1"];
					$data["sf2"] = $objRs->fields["u_sf2"];
					$data["sf3"] = $objRs->fields["u_sf3"];
					array_push($objs["sanitarycategories"],$data);
				}
			}*/	
			echo json_encode($objs); 
			break;
		default:
			$actionReturn = raiseError("Invalid Type [".$_GET["type"]."].");
			break;	
	}
	
	
?>
