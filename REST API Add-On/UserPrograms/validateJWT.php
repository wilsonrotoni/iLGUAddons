<?php

	$jwt="";
	
	function validateJWT($userid, $token = null) {
		$actionReturn = true;
		if (!is_null($token)) {
	
			 require_once('../Addons/GPS/REST API Add-On/UserPrograms/jwt.php');
	
			$serverKey = '5f2b5cdbe5194f10b3241568fe4e2b24';
	
			try {
				$payload = JWT::decode($token, $serverKey, array('HS256'));
				if ($userid!=$payload->userId) {
					$actionReturn = raiseError('Mismatch User ID with the jwt.');
				}
			}
			catch(Exception $e) {
				$actionReturn = raiseError($e->getMessage());
			}
		} 
		else {
			$actionReturn = raiseError('You are not logged in with a valid jwt.');
		}
		
		return $actionReturn;
	}

?>
