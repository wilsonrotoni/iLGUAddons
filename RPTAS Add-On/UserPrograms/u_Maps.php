<?php
	include_once("./inc/formutils.php");
	include_once("../common/classes/recordset.php");

	//$objLicMgr = new objlicensemanager();
	
	//$objLicMgr->getLicense();
	
	
	if ($_SESSION["theme"]=="sp" || $_SESSION["theme"]=="sf") { 
		$csspath = "../Addons/GPS/PayRoll Add-On/UserPrograms/";
	}
	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
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
<style> #mymap { width:90%; height:600px; }</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBfkk6uJOYgeiKC06ekw3jGdhAHhy5okaM"></script>
<script>
	var geocoder;
  	var map;
	
  	function initialize() {
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(0, 0);
		var mapOptions = {
		  	zoom: 11,
		  	center: latlng,
		  	mapTypeId: google.maps.MapTypeId.SATELLITE
		}
		map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
		
	
	  
		var kmlPath = "http://ecloudgt.ddns.net:81/kml/try.kmz";
		// Add unique number to this url - as with images - to avoid caching issues during development
		var urlSuffix = (new Date).getTime().toString();
	  
	   	var layer = new google.maps.KmlLayer(kmlPath + '?' + urlSuffix );
		layer.setMap(map);

  	}
  
	google.maps.event.addDomListener(window, 'load', initialize);



</script>
</head>

<body >
<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
<tr><td>
 <div id="map-canvas" style="width: 780px; height: 580px;"></div>
 </td></tr>
 </table>
</body>
</html>
<?php 
	$page->writeEndScript(); 
	$htmltoolbarbottom = "./Blank.php";
	$requestorId = "FTCmdCntr01";
	
	include("setbottomtoolbar.php"); 
	
?>


