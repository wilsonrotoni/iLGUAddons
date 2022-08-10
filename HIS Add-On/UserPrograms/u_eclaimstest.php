<?php
	/*header("Date: Tue, 02 Feb 2016 16:04:44 GMT")
	header("Content-MD5: 1B2M2Y8AsgTpgAmY7PhCfg==")
	header("Authorization: APIAuth-HMAC-SHA256 b6LVL-cin-PLKZ9fKXZj-VCELMWqnIoW:1kBmPJCXWtl+UVulAg3mFTHlMrSrGsgnfJWXeLK0LQE=");
	*/
	
/*	curl_setopt_array($ch, array(
		CURLOPT_HTTPHEADER  => array('Date: Tue, 15 Jan 2019 05:18:08 GMT',
		                      'Content-MD5: 1B2M2Y8AsgTpgAmY7PhCfg==',
							  'Authorization: APIAuth-HMAC-SHA256 b6LVL-cin-PLKZ9fKXZj-VCELMWqnIoW:1kBmPJCXWtl+UVulAg3mFTHlMrSrGsgnfJWXeLK0LQE='),
		CURLOPT_RETURNTRANSFER  =>true,
		CURLOPT_VERBOSE     => 1
	));*/
/*
	$uri = 'https://eclaims-staging.ttsibpo.com/api/v1/claims/1.json';
	$ch = curl_init($uri);
	var_dump( $ch);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Date: Tue, 15 Jan 2019 06:10:08 GMT',
		                      'Content-MD5: 1B2M2Y8AsgTpgAmY7PhCfg==',
							  'Authorization: APIAuth-HMAC-SHA256 b6LVL-cin-PLKZ9fKXZj-VCELMWqnIoW:1kBmPJCXWtl+UVulAg3mFTHlMrSrGsgnfJWXeLK0LQE='));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_GET, 1);
	$out = curl_exec($ch);
	
	if ($out === false) {
    	$info = curl_getinfo($ch);
    	curl_close($curl);
   		 die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}

	curl_close($ch);
	// echo response output
	var_dump( $out);
*/

$uri = 'http://localhost:81/php/distribution/udp.php?objectcode=u_curltest';
	$ch = curl_init($uri);
	//header('Content-Type: application/json'); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Date: Tue, 15 Jan 2019 05:30:08 GMT',
		                      'Content-MD5: 1B2M2Y8AsgTpgAmY7PhCfg==',
							  'Authorization: APIAuth-HMAC-SHA256 b6LVL-cin-PLKZ9fKXZj-VCELMWqnIoW:1kBmPJCXWtl+UVulAg3mFTHlMrSrGsgnfJWXeLK0LQE='));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_GET, 1);
	$out = curl_exec($ch);
	curl_close($ch);
	// echo response output
	var_dump($out);
	//echo json_decode($out);

?>
