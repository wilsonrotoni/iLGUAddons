<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./utils/login.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./classes/marketingdocuments.php");
	include_once("./classes/marketingdocumentitems.php");
	include_once("./classes/productionorders.php");
	include_once("./classes/productionorderitems.php");
	include_once("./classes/branches.php");
	include_once("./classes/brancheslist.php");
	include_once("./classes/users.php");
	
	$actionReturn = true;
	
	$mode="eBT";
	
	$objConnection->beginwork();
	$corid = $_GET["corid"];
	$_SESSION["userid"] = $_GET["userid"];
	
	switch ($_GET["type"]) {
		case "ClearanceInspectionPictures":
			$objs["result"] = array();
			$datas = array();
			$result = array();
			$_SESSION["company"]="LGU";
			$_SESSION["branch"]="MAIN";
			$company = $_SESSION["company"];
			$branch = $_SESSION["branch"];
			
			$obj = json_decode($_GET["data"],true);
			file_put_contents("c:\users\SanitaryInspectionPictures.txt",serialize($obj));
			
			foreach($obj['data'] as $data) {
				$binary = base64_decode($data["bitmap"]);
	
				// binary, utf-8 bytes
				header("Content-Type: bitmap; charset=utf-8");
	
				mkdirr("..//Images/$company/$branch/U_SANITARYINS/" . $data["docno"] ."/Attachments/");
				$file = fopen("..//Images/$company/$branch/U_SANITARYINS/" . $data["docno"] ."/Attachments/". $data["name"], "wb"); // 
				fwrite($file, $binary);
	
				fclose($file);  
			}
						
			if ($actionReturn) $result["returnstatus"] = "Upload Successful";
			else $result["returnstatus"] = $_SESSION["errormessage"];
			array_push($objs["result"],$result);
			echo json_encode($objs); 
 			break;			
		case "ClearanceInspectionSignatures":
			$objs["result"] = array();
			$datas = array();
			$result = array();
			$_SESSION["company"]="LGU";
			$_SESSION["branch"]="MAIN";
			$company = $_SESSION["company"];
			$branch = $_SESSION["branch"];
			
			$obj = json_decode($_GET["data"],true);
			file_put_contents("c:\users\SanitaryInspectionSignatures.txt",serialize($obj));
			
			foreach($obj['data'] as $data) {
				$binary = base64_decode($data["bitmap"]);
	
				// binary, utf-8 bytes
				header("Content-Type: bitmap; charset=utf-8");
	
				mkdirr("..//Images/$company/$branch/U_SANITARYINS/" . $data["docno"] ."/Signatures/");
				$file = fopen("..//Images/$company/$branch/U_SANITARYINS/" . $data["docno"] ."/Signatures/". $data["name"], "wb"); // 
				fwrite($file, $binary);
	
				fclose($file);  
			}
						
			if ($actionReturn) $result["returnstatus"] = "Upload Successful";
			else $result["returnstatus"] = $_SESSION["errormessage"];
			array_push($objs["result"],$result);
			echo json_encode($objs); 
 			break;			
		case "ClearanceInspections":
			$objs["result"] = array();
			$datas = array();
			$result = array();
			$_SESSION["company"]="LGU";
			$_SESSION["branch"]="MAIN";
			$company = $_SESSION["company"];
			$branch = $_SESSION["branch"];
			
			$obj = json_decode($_GET["data"],true);
			//file_put_contents("c:\users\data.txt",serialize($obj));

			$obju_FSIns = new documentschema_br(null,$objConnection,"u_sanitaryins");
			
			foreach($obj['data'] as $data) {
				if ($obju_FSIns->getbykey($data["docno"])) {
					if ($data["inspectdate"]!="") {
						$obju_FSIns->setudfvalue("u_inspectdate",formatDateToDB($data["inspectdate"]));
					} else {
						$obju_FSIns->setudfvalue("u_inspectdate","");
					}	
					if ($data["inspecttime"]!="") {
						$obju_FSIns->setudfvalue("u_inspecttime",formatTimeToDB($data["inspecttime"]));
					} else {
						$obju_FSIns->setudfvalue("u_inspecttime","");
					}	
					//$obju_FSIns->setudfvalue("u_businessname",$data["businessname"]);
					//$obju_FSIns->setudfvalue("u_category",$data["category"]);
					$obju_FSIns->setudfvalue("u_inspectorremarks",$data["inspectorremarks"]);
					$obju_FSIns->setudfvalue("u_inspectbystatus",$data["inspectbystatus"]);
					/*$obju_FSIns->setudfvalue("u_totaldemerits",$data["totaldemerits"]);
					$obju_FSIns->setudfvalue("u_ratingperc",$data["ratingperc"]);
					$obju_FSIns->setudfvalue("u_sf1", $data["sf1"]);
					$obju_FSIns->setudfvalue("u_sf2", $data["sf2"]);
					$obju_FSIns->setudfvalue("u_sf3", $data["sf3"]);
					$obju_FSIns->setudfvalue("u_oth1", $data["oth1"]);
					$obju_FSIns->setudfvalue("u_oth2", $data["oth2"]);
					$obju_FSIns->setudfvalue("u_oth3", $data["oth3"]);
					$obju_FSIns->setudfvalue("u_cop", $data["cop"]);
					$obju_FSIns->setudfvalue("u_mop", $data["mop"]);
					$obju_FSIns->setudfvalue("u_tp", $data["tp"]);
					$obju_FSIns->setudfvalue("u_hf", $data["hf"]);
					$obju_FSIns->setudfvalue("u_ws", $data["ws"]);
					$obju_FSIns->setudfvalue("u_lwm", $data["lwm"]);
					$obju_FSIns->setudfvalue("u_swm", $data["swm"]);
					$obju_FSIns->setudfvalue("u_wof", $data["wof"]);
					$obju_FSIns->setudfvalue("u_pof", $data["pof"]);
					$obju_FSIns->setudfvalue("u_vc", $data["vc"]);
					$obju_FSIns->setudfvalue("u_cnt", $data["cnt"]);
					$obju_FSIns->setudfvalue("u_pc", $data["pc"]);
					$obju_FSIns->setudfvalue("u_ham", $data["ham"]);
					$obju_FSIns->setudfvalue("u_coau", $data["coau"]);
					$obju_FSIns->setudfvalue("u_scoau", $data["scoau"]);
					$obju_FSIns->setudfvalue("u_dc", $data["dc"]);
					$obju_FSIns->setudfvalue("u_mic", $data["mic"]);
					$obju_FSIns->setudfvalue("u_oth1rcm", $data["oth1rcm"]);
					$obju_FSIns->setudfvalue("u_oth2rcm", $data["oth2rcm"]);
					$obju_FSIns->setudfvalue("u_oth3rcm", $data["oth3rcm"]);
					$obju_FSIns->setudfvalue("u_coprcm", $data["coprcm"]);
					$obju_FSIns->setudfvalue("u_moprcm", $data["moprcm"]);
					$obju_FSIns->setudfvalue("u_tprcm", $data["tprcm"]);
					$obju_FSIns->setudfvalue("u_hfrcm", $data["hfrcm"]);
					$obju_FSIns->setudfvalue("u_wsrcm", $data["wsrcm"]);
					$obju_FSIns->setudfvalue("u_lwmrcm", $data["lwmrcm"]);
					$obju_FSIns->setudfvalue("u_swmrcm", $data["swmrcm"]);
					$obju_FSIns->setudfvalue("u_wofrcm", $data["wofrcm"]);
					$obju_FSIns->setudfvalue("u_pofrcm", $data["pofrcm"]);
					$obju_FSIns->setudfvalue("u_vcrcm", $data["vcrcm"]);
					$obju_FSIns->setudfvalue("u_cntrcm", $data["cntrcm"]);
					$obju_FSIns->setudfvalue("u_pcrcm", $data["pcrcm"]);
					$obju_FSIns->setudfvalue("u_hamrcm", $data["hamrcm"]);
					$obju_FSIns->setudfvalue("u_coaurcm", $data["coaurcm"]);
					$obju_FSIns->setudfvalue("u_scoaurcm", $data["scoaurcm"]);
					$obju_FSIns->setudfvalue("u_dcrcm", $data["dcrcm"]);
					$obju_FSIns->setudfvalue("u_micrcm", $data["micrcm"]);*/
					$obju_FSIns->setudfvalue("u_updatefrommobileapp",1);
					
					if ($actionReturn) $actionReturn = $obju_FSIns->update($obju_FSIns->docno,$obju_FSIns->rcdversion);
				} else $actionReturn = raiseError("Unable to retrieve inspection record [".$data["docno"]."].");
				if (!$actionReturn) break;
			}
			
			//if ($actionReturn) $actionReturn = raiseError("rey");
			if ($actionReturn) $result["returnstatus"] = "Save Successful";
			else $result["returnstatus"] = $_SESSION["errormessage"];
			array_push($objs["result"],$result);
			echo json_encode($objs); 
 			break;	
		default:
			$actionReturn = raiseError("Invalid Type [".$_GET["type"]."].");
			if ($actionReturn) echo "Save Successful";
			else echo $_SESSION["errormessage"];
			break;	
	}
	
	if ($actionReturn) $objConnection->commit();
	else $objConnection->rollback();
?>
