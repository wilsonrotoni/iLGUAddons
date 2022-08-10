<?php
	$progid = "u_caturephoto";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./inc/formutils.php");
	//include_once("./inc/formaccess.php");
?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>
</head>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="320">
<video id="video" width="320" height="240" autoplay border="1"></video>
</td>
<td width="10">&nbsp;
</td>

<td width="320">
<canvas id="canvas" width="320" height="240" border="1"></canvas>
</td>
<td>
</td>
</tr>
<tr>
<td align="center">
<a class="button" href="" onClick="snapPhoto();return false;" >SNAP PHOTO</a>
</td>
<td>
</td>
<td align="center">
<a class="button" href="" onClick="setPhoto();return false;" >OK</a>
<a class="button" href="" onClick="window.close();return false;" >CANCEL</a>
</td>
<td>
</td>
</tr>
<script>

var canvas;
var snap = false;

function setPhoto() {
	if (snap) {
		window.opener.setPhotoGPSHIS(canvas.toDataURL());
		window.close();
	} else {
		alert('Please press Snap Photo before pressing Ok button.');
	}		
}

function snapPhoto() {
	canvas = document.getElementById("canvas");
	var context = canvas.getContext("2d"),
		video = document.getElementById("video");
		
	context.drawImage(video, 0, 0, 320, 240);
	snap = true;
}

// Put event listeners into place
window.addEventListener("DOMContentLoaded", function() {
	// Grab elements, create settings, etc.
	var canvas = document.getElementById("canvas"),
		context = canvas.getContext("2d"),
		video = document.getElementById("video"),
		videoObj = { "video": true },
		errBack = function(error) {
			console.log("Video capture error: ", error.code); 
		};

	// Put video listeners into place
	if(navigator.getUserMedia) { // Standard
		navigator.getUserMedia(videoObj, function(stream) {
			//video.src = stream;
			video.srcObject  = stream;
			video.play();
		}, errBack);
	} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
		navigator.webkitGetUserMedia(videoObj, function(stream){
			video.src = window.webkitURL.createObjectURL(stream);
			video.play();
		}, errBack);
	}
	else if(navigator.mozGetUserMedia) { // Firefox-prefixed
		navigator.mozGetUserMedia(videoObj, function(stream){
		try {
		  	video.srcObject  = stream;
		} catch (error) {
  			video.srcObject  = window.URL.createObjectURL(stream);
		}

			//video.src = window.URL.createObjectURL(stream);
			video.play();
		}, errBack);
	}
	
	

}, false);


</script>
</html>