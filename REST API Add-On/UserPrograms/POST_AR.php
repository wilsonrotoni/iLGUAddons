<?php

	include_once("../common/classes/connection.php");
	require_once("./dtw/incomingpayments.php");
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
	$rawdata = file_get_contents("php://input");
	if ($rawdata!="") file_put_contents("c:/users/post_ar.raw",file_get_contents("php://input"));
	else $rawdata = file_get_contents("c:/users/post_ar.raw");
	$json = json_decode($rawdata,true);
	file_put_contents("c:/users/post_ar.json",serialize($json));
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
	$objDTWOutgoingPayments = new dtw_incomingpayments();
	
	$checkdate = "";
	$checkbank = "";
	$checkbankacctno = "";
	$checkno = "";
	$checkamount = 0;
	
	$data = array();
	$data["Incoming Payments"] =  array();
	$data["Incoming Payment - Documents"] =  array();
	$data["Incoming Payment - Checks"] =  array();
	array_push($data["Incoming Payments"],array("Document No.", "Document Series", "Posting Date", "Business Partner Type", "Business Partner Code", "Business Partner Name", "Reference No.", "Remarks", "Cash Amount"));
	array_push($data["Incoming Payment - Documents"],array("Document No.", "Reference Type", "Reference No.", "Amount"));
	array_push($data["Incoming Payment - Checks"],array("Document No.", "Check Date", "Bank", "Check No.", "Amount"));
	
	foreach($json as $key => $data2) {
		$ctr++;
		if ($key=="incomingpayments") {
			if ($jwt!="") {
				foreach($data2 as $journalvouchers) {
					$ctr++;
					$hdr = array();
					foreach($journalvouchers as $jvkey => $jvdata) {
						if ($jvkey=="documents") {
							foreach($jvdata as $journalvoucheritems) {
								$dtl = array();
								$dtl[0] = $hdr[0];
								foreach($journalvoucheritems as $jvitemkey => $jvitemdata) {
									switch ($jvitemkey) {
										case "reftype": $dtl[1] = $jvitemdata; break;
										case "refno": $dtl[2] = $jvitemdata; break;
										case "amount": $dtl[3] = $jvitemdata; break;
										
									}
								}
								array_push($data["Incoming Payment - Documents"],$dtl);				
								//$ctr++;
								//file_put_contents("c:/users/jv_post".$ctr.".json",serialize($journalvoucheritems));
							}
						} else {
							switch ($jvkey) {
								case "docno": $hdr[0] = $jvdata; break;
								case "docseries": $hdr[1] = $jvdata; break;
								case "postdate": $hdr[2] = $jvdata; break;
								case "doctype": $hdr[3] = $jvdata; break;
								case "bpcode": $hdr[4] = $jvdata; break;
								case "bpname": $hdr[5] = $jvdata; break;
								case "refno": $hdr[6] = $jvdata; break;
								case "remarks": $hdr[7] = $jvdata; break;
								case "cashamount": $hdr[8] = $jvdata; break;
								case "checkdate": $checkdate = $jvdata; break;
								case "checkbank": $checkbank = $jvdata; break;
								case "checkbankacctno": $checkbankacctno = $jvdata; break;
								case "checkno": $checkno = $jvdata; break;
								case "checkamount": $checkamount = $jvdata; break;				
								
							}
							$ctr++;
							//file_put_contents("c:/users/jv_post".$ctr.".json",serialize(array($jvkey,$jvdata)));
						}	
						//file_put_contents("c:/users/jv_post".$ctr.".json",serialize($data3));
					}
					array_push($data["Incoming Payments"],$hdr);
				}		
			} else {
				$actionReturn = raiseError("JWT was not passed or it was passed after the incomingpayments data.");
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
	
	if ($actionReturn) {
		getBranchSettings();
		if ($checkamount!=0) {
			$dtl = array();
			$dtl[0] = $hdr[0];
			$dtl[1] = $checkdate;
			$dtl[2] = $checkbank;
			$dtl[3] = $checkno;
			$dtl[4] = $checkamount;
			array_push($data["Incoming Payment - Checks"],$dtl);
		}	
	}	
	if ($actionReturn && $authReturn) {
		$actionReturn = $objDTWOutgoingPayments->upload($data);
		if ($actionReturn) {
			http_response_code(200);
			echo json_encode(array("resultcode" => $actionReturn, "resultmessage" => "", "docnos" => $objDTWOutgoingPayments->getIDs()),JSON_FORCE_OBJECT);
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
