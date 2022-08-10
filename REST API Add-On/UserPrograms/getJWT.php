<?php
	$userId = '';
	$password = '';

	if (isset($_GET['userid'])) {$userId = $_GET['userid'];}
	if (isset($_GET['password'])) {$password = $_GET['password'];}

	if (($userId == 'manager') && ($password == 'manager')) {

		require_once('../Addons/GPS/REST API Add-On/UserPrograms/jwt.php');

		// $nbf = strtotime('2021-01-01 00:00:01');
		// $exp = strtotime('2021-01-01 00:00:01');

		$serverKey = '5f2b5cdbe5194f10b3241568fe4e2b24';

		$payloadArray = array();
		$payloadArray['userId'] = $userId;
		if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
		if (isset($exp)) {$payloadArray['exp'] = $exp;}
		$token = JWT::encode($payloadArray, $serverKey);

		$returnArray = array('resultcode' => true, 'resultmessage' => '', 'jwt' => $token);
		$jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
		echo $jsonEncodedReturnArray;

	} 
	else {
		$returnArray = array('resultcode' => false, 'resultmessage' => 'Invalid user ID or password.', 'userid' => $userId, 'password' => $password);
		$jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
		echo $jsonEncodedReturnArray;
	}
	
?>