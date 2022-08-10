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
	
	$objRs = new recordset(null,$objConnection);
	$objRs->setdebug();
	$profitcenter = "";
	$glacctno = "";
	$glacctname = "";
	$budget = 100000;
	$earmarked = 20000;
	$committed = 10000;
	$utilized = 50000;
	$available = 20000;
	
	foreach($json as $key => $data2) {
		$ctr++;
		switch ($key) {
			case "branch": $_SESSION["branch"] = $data2; break;
			case "userid": $_SESSION["userid"] = $data2; break;
			case "jwt": 
				$jwt = $data2;
				$authReturn = validateJWT($_SESSION["userid"], $jwt);
				if (!$authReturn) $actionReturn = false;
				break;
			case "profitcenter":
				$profitcenter = trim($data2);
				$objRs->queryopen("select profitcentername from profitcenters where profitcenter='$profitcenter'");
				if ($objRs->queryfetchrow("NAME")) {
					$glacctname = $objRs->fields["acctname"];
				} else $actionReturn = raiseError("Invalid Profit Center");
				break;
			case "glacctno":
				$glacctno = trim($data2);
				$objRs->queryopen("select acctname from chartofaccounts where formatcode='$glacctno' and postable=1");
				if ($objRs->queryfetchrow("NAME")) {
					$glacctname = $objRs->fields["acctname"];
				} else $actionReturn = raiseError("Invalid G/L Account No.");
				break;
			case "refno":
				$refno = trim($data2);
				break;
		}	
			//file_put_contents("c:/users/jv_post".$ctr.".json",serialize($data2));
		if (!$actionReturn) break;
	}
	
	if ($actionReturn && $authReturn) {
		if ($actionReturn && $profitcenter=="") $actionReturn = raiseError("Profit Center was not passed.");
		if ($actionReturn && $glacctno=="") $actionReturn = raiseError("G/L Account No. was not passed.");
	}
	
	if ($actionReturn && $authReturn) {
		$documents = array();
		/*$objRs->setdebug();
		$objRs->queryopen("select 'ARINVOICE' as doctype, docno, docdate, docduedate as duedate, bprefno as refno, totalamount, dueamount as balance from arinvoices where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and bpcode='$bpcode' and docstatus='O' union all select 'ARCREDITMEMO' as doctype, docno, docdate, docduedate as duedate, bprefno as refno, totalamount*-1 as totalamount, dueamount*-1 as balance from arcreditmemos where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and bpcode='$bpcode' and docstatus='O' ");
		if ($objRs->queryfetchrow("NAME")) {
			$objRs->fields["docdate"] = formatDateToHttp($objRs->fields["docdate"]);
			$objRs->fields["duedate"] = formatDateToHttp($objRs->fields["duedate"]);
			$objRs->fields["totalamount"] = floatval($objRs->fields["totalamount"]);
			$objRs->fields["balance"] = floatval($objRs->fields["balance"]);
			array_push($documents,$objRs->fields);
		}*/
		//array_push($documents,$objRs->fields);
		if ($actionReturn) {
			http_response_code(200);
			echo json_encode(array("resultcode" => $actionReturn, "resultmessage" => "", "profitcenter" => $profitcenter, "glacctno" => $glacctno, "glacctname" => $glacctname, "budget" => $budget, "earmarked" => $earmarked, "committed" => $committed, "utilized" => $utilized, "available" => $available),JSON_FORCE_OBJECT);
		} else {
			http_response_code(406);
			echo json_encode(array("resultcode" => $actionReturn,  "resultmessage" => $_SESSION["errormessage"], "profitcenter" => $profitcenter, "glacctno" => $glacctno, "glacctname" => $glacctname, "budget" => 0, "earmarked" => 0, "committed" => 0, "utilized" => 0, "available" => 0),JSON_FORCE_OBJECT);
		}
	} else {
		if (!$authReturn) http_response_code(401);
		else http_response_code(400);
		echo json_encode(array("resultcode" => $actionReturn,  "resultmessage" => $_SESSION["errormessage"], "profitcenter" => $profitcenter, "glacctno" => $glacctno, "glacctname" => $glacctname, "budget" => 0, "earmarked" => 0, "committed" => 0, "utilized" => 0, "available" => 0),JSON_FORCE_OBJECT);
	}


	//file_put_contents("c:/users/jv_postDTW.json",var_export($data));
	

?>
