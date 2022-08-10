<!--
<!DOCTYPE html>
<html >
<head>

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


<script src="http://institution-staging.multipay.ph"></script>

<form id="pay-form" action="http://institution-staging.multipay.ph/api/v1/transactions/generate">
  <input type="hidden" name="komojuToken"/>

  <button id="customButton">Pay Now</button>
</form>

<script>
 var payForm = document.getElementById("pay-form")

 var handler = Komoju.multipay.configure({
   key: "your_publishable_key",
   token: function(token) {
     payForm.komojuToken.value = token.id;
     payForm.submit();
   }
 });

 document.getElementById("customButton").addEventListener("click", function(e) {
   handler.open({
     amount:       1000,
//     endpoint:     "http://institution-staging.multipay.ph",
//     locale:       "en",
//     currency:     "JPY",
//     title:        "Product Name",
//     description:  "Product Description",
//     methods: [
//       "credit_card","konbini","bank_transfer","pay_easy"
//     ]
   });

   e.preventDefault();
 });
</script>

</html>

<script>
var xmlhttp = new XMLHttpRequest();
var url = "http://institution-staging.multipay.ph/api/v1/transactions/generate";
//var myHeaders = request.headers;
//var params = 'refno=""';

xmlhttp.open("POST", url, true);
//xmlhttp.setRequestHeader('Content-type', 'application/json');
//xmlhttp.setRequestHeader('X-MultiPay-Token', '6a5b5a9436478d45fb171a21a2b5f58e3d983279');
//xmlhttp.setRequestHeader('X-MultiPay-Code', 'CSSE_DEV');

xmlhttp.onreadystatechange = function() {
        alert(xmlhttp.getResponseHeader("Content-Type"));
        alert(xmlhttp.getResponseHeader("X-MultiPay-Token"));
        alert(xmlhttp.getResponseHeader("X-MultiPay-Code"));
  if (this.readyState == 4 && this.status == 200) {
        var myArr = this.responseText;
       
        myFunction(myArr);
    }else{
//        console.log(this.responseText)
        var myArr = this.responseText;
        myFunction(myArr);
    }
};

xmlhttp.send();

function myFunction(arr) {
    var out = "";
    var i;
    for(i = 0; i < arr.length; i++) {
        out += '<a href="' + arr[i].url + '">' + arr[i].display + '</a><br>';
    }
    document.getElementById("id01").innerHTML = arr;
    console.log(arr);
}

</script>
</head>

<body >
<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
<tr><td>
<div id="id01"></div>
 </td></tr>
 </table>
</body>

</html>
-->

<?php
//step1
$cSession = curl_init(); 
//step2
curl_setopt($cSession,CURLOPT_URL,"http://institution-staging.multipay.ph/api/v1/transactions/search");
curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
curl_setopt($cSession,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($cSession,CURLOPT_HEADER,false); 
curl_setopt($cSession,CURLOPT_HTTPHEADER,
        array("X-MultiPay-Token:6a5b5a9436478d45fb171a21a2b5f58e3d983279",
              "X-MultiPay-Code:CSSE_DEV"
)); 
$payload = Array(
        'refno' => '1234567'
);
//step3
curl_setopt($cSession,CURLOPT_POST,true);
curl_setopt($cSession,CURLOPT_POSTFIELDS,http_build_query($payload));
$result=curl_exec($cSession);
//step4
curl_close($cSession);
//step5
$json_decode = json_decode($result,true);
$long_url = $json_decode['data']['url'];
echo $result;
//header('Location:'.$long_url);
?>

