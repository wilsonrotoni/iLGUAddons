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
    var latlng = new google.maps.LatLng(14.9350752, 120.49385010000003);
    var mapOptions = {
      zoom: 11,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.SATELLITE
    }
    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	
	//alert('1');
	//  var ctaLayer = new google.maps.KmlLayer({
  //  url: 'http://localhost:81/php/try.kml',
  //  map: map
 // });
  
       var kmlPath = "http://ecloudgt.ddns.net:81/kml/try.kmz";
    // Add unique number to this url - as with images - to avoid caching issues during development
    var urlSuffix = (new Date).getTime().toString();
  
   var layer = new google.maps.KmlLayer(kmlPath + '?' + urlSuffix );
    layer.setMap(map);
	

// map.setCenter(latlng);
 //alert('3');
	//setAddress();
	//codeAddress();
  }
google.maps.event.addDomListener(window, 'load', initialize);

	function openKML() {
    // var kmlPath = 'http://localhost:81/php/try.kml';
	  var kmlPath = "http://ecloudgt.ddns.net:81/kml/try.kml";
	// var kmlPath = "http://web-apprentice-demo.craic.com/assets/maps/fremont.kml";
    // Add unique number to this url - as with images - to avoid caching issues during development
    var urlSuffix = (new Date).getTime().toString();
  
   var layer = new google.maps.KmlLayer(kmlPath + '?' + urlSuffix );
    layer.setMap(map);
		alert('2');
	}
	
  function codeAddress() {
    var address = document.getElementById("address").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location,
			draggable: true
        });
		var infowindow = new google.maps.InfoWindow({
			content: 'Address '+ results[0].address_components[0].long_name + '<br>Latitude: ' + results[0].geometry.location.lat() + '<br>Longitude: ' + results[0].geometry.location.lng()
		  });
		  infowindow.open(map,marker);
		window.opener.setTableInput(window.opener.mode,"u_latitude",results[0].geometry.location.lat());
		window.opener.setTableInput(window.opener.mode,"u_longitude",results[0].geometry.location.lng());
 
		google.maps.event.addListener(marker, 'dragend', function(marker){
			var latLng = marker.latLng;
			window.opener.setTableInput(window.opener.mode,"u_latitude",latLng.lat());
			window.opener.setTableInput(window.opener.mode,"u_longitude",latLng.lng());
		});
 
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
  
  function setAddress() {
	  	var addres = "";
		if (window.opener.getTableInput(window.opener.mode,"street")!="") addres = window.opener.getTableInput(window.opener.mode,"street");
		if (window.opener.getTableInput(window.opener.mode,"barangay")!="") addres += ", " + window.opener.getTableInput(window.opener.mode,"barangay");
		if (window.opener.getTableInput(window.opener.mode,"city")!="") addres += ", " + window.opener.getTableInput(window.opener.mode,"city");
		if (window.opener.getTableInput(window.opener.mode,"zip")!="") addres += " " + window.opener.getTableInput(window.opener.mode,"zip");
		if (window.opener.getTableInputSelectedText(window.opener.mode,"province")!="[Select]") addres += ", " + window.opener.getTableInputSelectedText(window.opener.mode,"province");
		if (window.opener.getTableInputSelectedText(window.opener.mode,"country")!="[Select]") addres += ", " + window.opener.getTableInputSelectedText(window.opener.mode,"country");
	 	document.getElementById("address").value = addres
	 }
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


