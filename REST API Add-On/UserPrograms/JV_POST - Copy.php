<?php

	include_once("../common/classes/connection.php");
	require_once("./dtw/journalvouchers.php");
	require_once("../Addons/GPS/REST API Add-On/UserPrograms/validateJWT.php");

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
	
	//file_put_contents("c:/users/jv_post.json",file_get_contents("php://input"));
	$json = json_decode(file_get_contents("php://input"),true);
	file_put_contents("c:/users/jv_post.json",serialize($json));
	$ctr=0;
	/*
	foreach($json as $key => $data2) {
		$ctr++;
		if ($key=="journalvouchers") {
			foreach($data2 as $key3 => $data3) {
				$ctr++;
				if ($key3=="journalvoucheritems") {
					foreach($data3 as $key4 => $data4) {
						$ctr++;
						file_put_contents("c:/users/jv_post".$ctr.".json",serialize($data4));
					}
				} else {	
					file_put_contents("c:/users/jv_post".$ctr.".json",serialize($data3));
				}	
			}
		} else {
			file_put_contents("c:/users/jv_post".$ctr.".json",serialize($data2));
		}	
	}*/
	$actionReturn = true;
	$authReturn = false;
	$objDTWJournalVouchers = new dtw_journalvouchers();
	$data = array();
	$data["Journal Vouchers"] =  array();
	$data["Journal Voucher - Items"] =  array();
	array_push($data["Journal Vouchers"],array("Document No.", "Document Series", "Posting Date", "Reference 1", "Remarks"));
	array_push($data["Journal Voucher - Items"],array("Document No.", "Item Type", "Item No.", "Item Name", "Debit", "Credit"));
	
	foreach($json as $key => $data2) {
		$ctr++;
		if ($key=="journalvouchers") {
			if ($jwt!="") {
				foreach($data2 as $journalvouchers) {
					$ctr++;
					$hdr = array();
					foreach($journalvouchers as $jvkey => $jvdata) {
						if ($jvkey=="journalvoucheritems") {
							foreach($jvdata as $journalvoucheritems) {
								$dtl = array();
								$dtl[0] = $hdr[0];
								foreach($journalvoucheritems as $jvitemkey => $jvitemdata) {
									switch ($jvitemkey) {
										case "itemtype": $dtl[1] = $jvitemdata; break;
										case "itemno": $dtl[2] = $jvitemdata; break;
										case "itemname": $dtl[3] = $jvitemdata; break;
										case "debit": $dtl[4] = $jvitemdata; break;
										case "credit": $dtl[5] = $jvitemdata; break;
										
									}
								}
								array_push($data["Journal Voucher - Items"],$dtl);				
								//$ctr++;
								//file_put_contents("c:/users/jv_post".$ctr.".json",serialize($journalvoucheritems));
							}
						} else {
							switch ($jvkey) {
								case "docno": $hdr[0] = $jvdata; break;
								case "docseries": $hdr[1] = $jvdata; break;
								case "docdate": $hdr[2] = $jvdata; break;
								case "reference1": $hdr[3] = $jvdata; break;
								case "remarks": $hdr[4] = $jvdata; break;
								
							}
							$ctr++;
							//file_put_contents("c:/users/jv_post".$ctr.".json",serialize(array($jvkey,$jvdata)));
						}	
						//file_put_contents("c:/users/jv_post".$ctr.".json",serialize($data3));
					}
					array_push($data["Journal Vouchers"],$hdr);
				}		
			} else {
				$actionReturn = raiseError("JWT was not passed or it was passed after the journalvouchers data.");
			}	
		} else {
			switch ($key) {
				case "branch": $_SESSION["branch"] = $data2; break;
				case "userid": $_SESSION["userid"] = $data2; break;
				case "jwt": 
					$jwt = $data2;
					$actionReturn = validateJWT($_SESSION["userid"], $jwt);
					$authReturn = validateJWT($_SESSION["userid"], $jwt);
					if (!$authReturn) $actionReturn = false;
					break;
			}	
			//file_put_contents("c:/users/jv_post".$ctr.".json",serialize($data2));
		}
		if (!$actionReturn) break;
	}
	
	if ($actionReturn && $authReturn) {
		$actionReturn = $objDTWJournalVouchers->upload($data);
		if ($actionReturn) {
			http_response_code(200);
			echo json_encode(array("resultcode" => $actionReturn, "resultmessage" => "", "docnos" => $objDTWJournalVouchers->getIDs()),JSON_FORCE_OBJECT);
		} else {
			http_response_code(406);
			echo json_encode(array("resultcode" => $actionReturn,  "resultmessage" => $_SESSION["errormessage"], "docnos" => array() ),JSON_FORCE_OBJECT);
		}
	} else {
		if (!$authReturn) http_response_code(401);
		else http_response_code(400);
		echo json_encode(array("resultcode" => $actionReturn,  "resultmessage" => $_SESSION["errormessage"], "docnos" => array() ),JSON_FORCE_OBJECT);
	}


	//file_put_contents("c:/users/jv_postDTW.json",var_export($data));
	

?>
