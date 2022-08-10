<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");

	if(isset($_GET['userid']) && isset($_GET['password'])){
	
		$password = addslashes(replaceSpecialChar($_GET["password"]));	

		$objRs = new recordset(null,$objConnection);
		if ($objRs->queryopen("select username from users where userid='".$_GET['userid']."' and hpwd='".md5($password)."'")) {
			if (!$objRs->queryfetchrow()) {
				echo "Invalid user or password.";
			} else echo "ok";
		} else echo "Database Error: Unable to connect";	
		
	} else echo "Incomplete User/Password";
?>
