<?php
//	$progid = "u_bplonlineapps";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	
	$_SESSION["userid"] = "bplcommon";
	
	var_dump($_SESSION["userid"]);
	header ('Location: udo.php?objectcode=u_bplapps'); 

?>