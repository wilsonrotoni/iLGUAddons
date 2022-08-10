<?php
	error_reporting(E_ALL);
	$serverDateTime ="";
	if(isset($_POST['Submit'])){
		try{
			$client = new SoapClient('https://210.4.103.170/SOAP'); 
			$serverDateTime = $client->GetServerDateTime();
		}catch(SoapFault $fault){
			die($fault->faultstring);   
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
	<h2>Test GetSeverDateTime function</h2>
	<form name="form" method="post">
		<input type="submit"value="Submit"name="Submit"/><br/>
	</form>
	Server Date/Time: <b><?php echo $serverDateTime; ?></b><br/>
</body>
</html>
