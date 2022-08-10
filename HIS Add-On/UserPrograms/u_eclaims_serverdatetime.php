<?php
	error_reporting(E_ALL);
	$serverDateTime ="";
	if(isset($_POST['Submit'])){
		try{
/*
stream_context_set_default(array(
            'ssl'                => array(
            'peer_name'          => 'generic-server',
            'verify_peer'        => FALSE,
            'verify_peer_name'   => FALSE,
            'allow_self_signed'  => TRUE
             )));
*/

			//$wsdl = file_get_contents('https://210.4.103.170/SOAP');
			//echo $wsdl;
//    'cache_wsdl' => WSDL_CACHE_NONE,
ini_set('default_socket_timeout', 5000);
$options = array(
    'cache_wsdl' => WSDL_CACHE_BOTH,
	'location' => 'https://210.4.103.170:8077/SOAP',
    'trace' => 1,
	'connection_timeout' => 5000,
    'keep_alive' => false,
    'compression'   => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
    'stream_context' => stream_context_create(array(
          'ssl' => array(
	       'verify_peer' => false,
                'verify_peer_name' => false,
          )
    )));
//                'allow_self_signed' => true

			$client = new SoapClient('https://210.4.103.170:8077/SOAP',$options); 
			//var_dump($client->__getFunctions());
			//while (openssl_error_string()) {}
			//$serverDateTime = $client->GetServerDateTime();
			//$serverDateTime = $client->GetServerVersion();
			$serverDateTime = $client->GetMemberPIN(":DUMMYSCERT970830", "", "970830", "LAYSON", 	"RENANTE", "DIANGSON", "", "10-14-1974");
			//$serverDateTime = $client->GetMemberPIN(":DUMMYSCERT970830", "", "970830", "LAYSON", 	"IMELDA", "DIANGSON", "", "10-14-1974");
			//$serverDateTime = $client->SearchHospital(":DUMMYSCERT970830", "", "970830", "MEDICAL");
			//$serverDateTime = $client->SearchCaseRate (":DUMMYSCERT970830", "", "970830", "", "", "DENGUE FEVER");
			$data = json_decode($serverDateTime);
			//var_dump($data[2]);
			//foreach ($data as $key => $str) {
			//	var_dump($key);
			//var_dump(base64_decode($str));
			//}
			//var_dump(base64_decode($data["doc"]));
		}catch(SoapFault $fault){
			var_dump($fault->faultstring);   
		var_dump($client->__getLastRequestHeaders());
		var_dump($client->__getLastResponseHeaders());
//var_dump($client->__getLastRequest());
//var_dump($client->__getLastResponse());
		}		
	}
?>
<!DOCTYPEhtmlPUBLIC"-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<metahttp-equiv="Content-Type" content="text/html" charset=utf-8"/>
</head>
<body>
	<h1>Test e-Claims Web Services using PHP</h1>
	<h2>Tes/h2>
	<form name="form" method="post">
		<input type="submit"value="Submit"name="Submit"/><br/>
	</form>
	Server Date/Time: <b><?php echo $serverDateTime; ?></b><br/>
</body>
</html>
