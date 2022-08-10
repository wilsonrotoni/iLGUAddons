<?php

	$token = null;
	
	if (isset($_GET['jwt'])) {$token = $_GET['jwt'];}

	if (!is_null($token)) {

		 require_once('../Addons/GPS/REST API Add-On/UserPrograms/jwt.php');

		$serverKey = '5f2b5cdbe5194f10b3241568fe4e2b24';

		try {
			$payload = JWT::decode($token, $serverKey, array('HS256'));
			$returnArray = array('resultcode' => true, 'userid' => $payload->userId);
			if (isset($payload->exp)) {
				$returnArray['expiration'] = date(DateTime::ISO8601, $payload->exp);;
			}
		}
		catch(Exception $e) {
			$returnArray = array('resultcode' => false, 'resultmessage' => $e->getMessage());
		}
	} 
	else {
		$returnArray = array('resultcode' => false, 'resultmessage' => 'You are not logged in with a valid jwt.');
	}
	
	$jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
	echo $jsonEncodedReturnArray;

?>