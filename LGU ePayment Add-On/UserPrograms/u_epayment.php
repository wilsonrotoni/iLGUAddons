
<?php

        if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/documentschema_br.php");
	include_once("./sls/enumdocstatus.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	//include_once("./inc/formaccess.php");
        

$docno = $httpVars['docno'];
$amount = 0;

$objrs = new recordset(null,$objConnection);
$objConnection->beginwork();
$objrs->queryopen("select * FROM U_LGUBILLS WHERE DOCNO = '$docno' AND COMPANY = '$company' AND BRANCH = '$branch'");
while ($objrs->queryfetchrow("NAME")) {
    $amount = $objrs->fields["U_DUEAMOUNT"];
    $tnxid = $objrs->fields["DOCNO"];
}


$cSession = curl_init();
//curl_setopt($cSession,CURLOPT_URL,"https://institution.multipay.ph/api/v1/transactions/generate");
curl_setopt($cSession,CURLOPT_URL,"https://pgi-ws-staging.multipay.ph/api/v1/transactions/generate");
curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
curl_setopt($cSession,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($cSession,CURLOPT_HEADER,false); 
curl_setopt($cSession,CURLOPT_HTTPHEADER,
            array("X-MultiPay-Token:2c1816316e65dbfcb0c34a25f3d6fe5589aef65d",
                  "X-MultiPay-Code:MSYS_TEST_BILLER"
//        array("X-MultiPay-Token:d3b38470618492c95582c860f1b44c5913c53e35",
//              "X-MultiPay-Code:CLSE"
)); 
$payload = Array(
        'amount' => $amount,
        'txnid' => $tnxid,
        'digest' => "fea3908891c9b86d9cb5f27cfc344d62d1359ff8",
        'callback_url' => 'https://localhost/lgu/success.php'
);

curl_setopt($cSession,CURLOPT_POST,true);
curl_setopt($cSession,CURLOPT_POSTFIELDS,http_build_query($payload));
$result=curl_exec($cSession);
curl_close($cSession);
$json_decode = json_decode($result,true);
var_dump($result);
//$long_url = $json_decode['data']['url'];
//header('Location:'.$long_url);
?>


<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/mainheader.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/standard.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/tblbox.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/deobjs.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>
</head>


</html>







