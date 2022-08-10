<?php

	include_once("../common/classes/connection.php");
	require_once("./dtw/journalvouchers.php");

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

	
	getCompanySettings();
	
	$objDTWJournalVouchers = new dtw_journalvouchers();
	$data = array();
	
	$json = json_decode(file_get_contents("php://input"));
	//$data["Customer Groups"] = $json["Customer Groups"];
	//file_put_contents("c:/users/journalvouchers.json",file_get_contents("php://input"));
	//foreach($obj as $data2) {
	//	file_put_contents("c:/users/customergroups.txt", serialize($json->CustomerGroups));
	//}
	$_SESSION["branch"] = $json->Branch;
	$data["Journal Vouchers"] = $json->JournalVouchers;
	$data["Journal Voucher - Items"] = $json->JournalVoucherItems;
	//file_put_contents("c:/users/journalvouchers.txt", serialize($data));
	
	$actionReturn = $objDTWJournalVouchers->upload($data);
	if ($actionReturn) {
		http_response_code(201);
        echo json_encode(array("resultcode" => $actionReturn, "docno" => $objDTWJournalVouchers->getLastID()));
	} else {
		http_response_code(503);
        echo json_encode(array("resultcode" => $actionReturn,  "resultmessage" => $_SESSION["errormessage"]));
	}
?>
