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
<script src="https://www.google.com/jsapi?key=AIzaSyBfkk6uJOYgeiKC06ekw3jGdhAHhy5okaM"></script>

<script>
      google.load('earth','1', {'other_params':'sensor=false'});

      var ge = null;

      function init() {
        google.earth.createInstance('map3d', initCallback, failureCallback);
      }

      function initCallback(pluginInstance) {
        ge = pluginInstance;
        ge.getWindow().setVisibility(true);
		
	  // Earth is ready, we can add features to it
	  addKmlFromUrl('http://ecloudgt.ddns.net:81/kml/try.kmz');
		
      }

      function failureCallback() {
        // we can do something here if there's an error
      }
	  
	  function addKmlFromUrl(kmlUrl) {
		  var link = ge.createLink('');
		  link.setHref(kmlUrl);
		
		  var networkLink = ge.createNetworkLink('');
		  networkLink.setLink(link);
		  networkLink.setFlyToView(true);
		
		  ge.getFeatures().appendChild(networkLink);
		}
    </script>

</head>

<body onload="init()" id="body">
<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
<tr><td>
 <div id="map3d" style="width: 780px; height: 580px;"></div>
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


