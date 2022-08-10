<?php
	include_once("../common/classes/connection.php");
	require_once("./dtw/customergroups.php");

	if (!function_exists('http_response_code'))
	{
		function http_response_code($newcode = NULL)
		{
			static $code = 200;
			if($newcode !== NULL)
			{
				header('X-PHP-Response-Code: '.$newcode, true, $newcode);
				if(!headers_sent())
					$code = $newcode;
			}       
			return $code;
		}
	}

	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

	
	$objDTWCustomerGroups = new dtw_customergroups();
	$data = array();
	
	$json = json_decode(file_get_contents("php://input"));
	//$data["Customer Groups"] = $json["Customer Groups"];
	//file_put_contents("c:/users/customergroups.json",file_get_contents("php://input"));
	//foreach($obj as $data2) {
	//	file_put_contents("c:/users/customergroups.txt", serialize($json->CustomerGroups));
	//}
	$data["Customer Groups"] = $json->CustomerGroups;
	
	$actionReturn = $objDTWCustomerGroups->upload($data);
	if ($actionReturn) {
		http_response_code(201);
        echo json_encode(array("resultcode" => $actionReturn));
	} else {
		http_response_code(503);
        echo json_encode(array("resultcode" => $actionReturn,  "resultmessage" => $_SESSION["errormessage"]));
	}
	
/*
	$actionReturn = true;
	$msg = "OK";
	if(isset($_GET['mobilenos'])){
		$mobilenos = explode(",",$_GET['mobilenos']);
		$obju_Sims = new masterdataschema(null,$objConnection,"u_sims");
		$objConnection->beginwork();
		foreach ($mobilenos as $mobile) {
			if ($obju_Sims->getbysql("U_MOBILENO='$mobile' and U_LOAD=1")) {
				$obju_Sims->setudfvalue("u_ok",1);
				$obju_Sims->setudfvalue("u_okdatetime",date('Y-m-d H:i:s'));
				$obju_Sims->setudfvalue("u_okdate",date('Y-m-d'));
				$obju_Sims->setudfvalue("u_okby",$_GET['userid']);
				$obju_Sims->setudfvalue("u_okmessage","Confirmed");
				if ($actionReturn) $actionReturn = $obju_Sims->update($obju_Sims->code,$obju_Sims->rcdversion);
			}	
		}		
		if ($actionReturn) $objConnection->commit();
		else $objConnection->rollback();
	} else $msg = "Cannot update to loaded, unknown mobile no/s";

	if ($actionReturn) echo $msg;
	else echo $_SESSION["errormessage"];		
	*/
?>
