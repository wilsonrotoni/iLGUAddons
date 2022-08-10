<?php
	$progid = "u_rplist";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./inc/formutils.php");
//	include_once("./inc/formaccess.php");

		$dir = "C:/Users/rog/";
		
	if (is_dir($dir)){
	  if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
				if ($file != "." && $file != "..") {
					if (is_file($dir .$file)){
						echo $dir .$file . "<br>";
					}	
				}	
			}
		}
	}			

?>