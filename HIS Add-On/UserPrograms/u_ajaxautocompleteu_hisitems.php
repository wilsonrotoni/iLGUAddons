<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");

	if(isset($_GET['byname']) && isset($_GET['letters'])){
		$letters = $_GET['letters'];
		$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("select name from u_hisitems where name like '%".trim($letters)."%' order by name limit 25");
		while ($objRs->queryfetchrow("NAME")) {
			echo $objRs->fields["name"]."###".$objRs->fields["name"]."|";
		}
	}
	
?>
