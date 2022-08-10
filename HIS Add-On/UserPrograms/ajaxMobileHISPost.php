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
		case "DoctorsDiagnosis":
			$objs["result"] = array();
			$datas = array();
			$result = array();
			$_SESSION["company"]="HIS";
			$_SESSION["branch"]="HO";
			$company = $_SESSION["company"];
			$branch = $_SESSION["branch"];
			
			$obj = json_decode($_GET["data"],true);
			//file_put_contents("c:\users\data.txt",serialize($obj));
			$objRs = new recordset(null,$objConnection);
			if ($_GET["regtype"]=="In-Patient") {
				$obju_Reg = new documentschema_br(null,$objConnection,"u_hisips");
			} else {
				$obju_Reg = new documentschema_br(null,$objConnection,"u_hisops");
			}	
			
			foreach($obj['data'] as $data) {
				if ($obju_Reg->getbykey($data["docno"])) {
					if ($_GET["regtype"]=="In-Patient") {
						$actionReturn = $objRs->executesql("update u_hisips set u_historyillness='".addslashes($data["historyillness"])."', u_pastmedhistory='".addslashes($data["pastmedhistory"])."', u_complaint='".addslashes($data["complaint"])."' where company='$company' and branch='$branch' and docno='".$data["docno"]."'",false);
					} else {
						$actionReturn = $objRs->executesql("update u_hisops set u_historyillness='".addslashes($data["historyillness"])."', u_pastmedhistory='".addslashes($data["pastmedhistory"])."', u_complaint='".addslashes($data["complaint"])."' where company='$company' and branch='$branch' and docno='".$data["docno"]."'",false);
					}	
					//$obju_Reg->setudfvalue("u_historyillness",$data["historyillness"]);
					//$obju_Reg->setudfvalue("u_pastmedhistory",$data["pastmedhistory"]);
					//$obju_Reg->setudfvalue("u_complaint",$data["complaint"]);
					//if ($actionReturn) $actionReturn = $obju_Reg->update($obju_Reg->docno,$obju_Reg->rcdversion);
				} else $actionReturn = raiseError("Unable to retrieve registration record [".$data["docno"]."].");
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
