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
		case "FireSafetyInspectionPictures":
			$objs["result"] = array();
			$datas = array();
			$result = array();
			$_SESSION["company"]="LGU";
			$_SESSION["branch"]="MAIN";
			$company = $_SESSION["company"];
			$branch = $_SESSION["branch"];
			
			$obj = json_decode($_GET["data"],true);
			file_put_contents("c:\users\FireSafetyInspectionPictures.txt",serialize($obj));
			
			foreach($obj['data'] as $data) {
				$binary = base64_decode($data["bitmap"]);
	
				// binary, utf-8 bytes
				header("Content-Type: bitmap; charset=utf-8");
	
				mkdirr("..//Images/$company/$branch/U_FSINS/" . $data["docno"] ."/Attachments/");
				$file = fopen("..//Images/$company/$branch/U_FSINS/" . $data["docno"] ."/Attachments/". $data["name"], "wb"); // 
				fwrite($file, $binary);
	
				fclose($file);  
			}
						
			if ($actionReturn) $result["returnstatus"] = "Upload Successful";
			else $result["returnstatus"] = $_SESSION["errormessage"];
			array_push($objs["result"],$result);
			echo json_encode($objs); 
 			break;			
		case "FireSafetyInspections":
			$objs["result"] = array();
			$datas = array();
			$result = array();
			$_SESSION["company"]="LGU";
			$_SESSION["branch"]="MAIN";
			$company = $_SESSION["company"];
			$branch = $_SESSION["branch"];
			
			$obj = json_decode($_GET["data"],true);
			//file_put_contents("c:\users\data.txt",serialize($obj));

			$obju_FSIns = new documentschema_br(null,$objConnection,"u_fsins");
			
			foreach($obj['data'] as $data) {
				if ($obju_FSIns->getbykey($data["docno"])) {
					if ($data["inspectdate"]!="") {
						$obju_FSIns->setudfvalue("u_inspectdate",formatDateToDB($data["inspectdate"]));
					} else {
						$obju_FSIns->setudfvalue("u_inspectdate","");
					}	
					$obju_FSIns->setudfvalue("u_gi_businessname",$data["gi_businessname"]);
					$obju_FSIns->setudfvalue("u_recommendations",$data["recommendations"]);
					$obju_FSIns->setudfvalue("u_inspectbystatus",$data["inspectbystatus"]);
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
