<?php

$dir = "./classes/";

if (is_dir($dir)){
	if ($dh = opendir($dir)){
		while (($file = readdir($dh)) !== false) {
			$fileinfo = pathinfo($file);
			if($fileinfo["extension"]=="php") generateEventFile($fileinfo["filename"]);
		}
		closedir($dh);
	}
}

function generateEventFile($eventname) {
	$dir = "../Addons/GPS/Audit Trail Add-On/UserProcs/events/";
	$filename = $dir.$eventname.".php";
	
	if (file_exists($filename)) unlink($filename);
	
	if (!$handle = fopen($filename, 'x')) {
		echo "Cannot open file ($filename) <br>";
		return;
    }
	
	$content = '<?php
 
	include_once("../Addons/GPS/Audit Trail Add-On/UserProcs/inc/u_audittrail.php");
	
	function onBeforeAddEvent'.$eventname.'GPSAuditTrail(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($actionReturn) $actionReturn = addAudittrailGPSAuditTrail("Add",$objTable);			
		return $actionReturn;
	}
	
	function onBeforeUpdateEvent'.$eventname.'GPSAuditTrail(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($actionReturn) $actionReturn = addAudittrailGPSAuditTrail("Update",$objTable);			
		return $actionReturn;
	}
	
	function onBeforeDeleteEvent'.$eventname.'GPSAuditTrail(&$objTable) {
		global $httpVars;
		global $objConnection;
		global $page;
		
		$actionReturn = true;
		
		if ($actionReturn) $actionReturn = addAudittrailGPSAuditTrail("Delete",$objTable);			
		return $actionReturn;
	}
	
?>';
	
	if (fwrite($handle, $content) === FALSE) {
        echo "Cannot write to file ($filename) <br>";
        return;
    }
	
	echo "Success, $filename <br>";
	
	fclose($handle);
}

?>