<?php 
function getRandomPathCrystalReport() {
	global $page;
	global $httpVars;
	/*if (!isset($GLOBALS["reportport"])) $GLOBALS["reportport"] = 3306;
	if ($GLOBALS["reportport"] == 3306) $GLOBALS["reportport"] = 3307;
	else $GLOBALS["reportport"] = 3306;
	
	$page->reportport = $GLOBALS["reportport"];
		*/
	
		return $_SESSION['pathcrystalreport'];
		/*
		$crport=intval(rand ( 1000 , 16000)/1000);
		switch ($crport) {
			case 2: return str_replace(":83",":8300",$_SESSION['pathcrystalreport']); break;
			case 3: return str_replace(":83",":8301",$_SESSION['pathcrystalreport']); break;
			case 4: return str_replace(":83",":8302",$_SESSION['pathcrystalreport']); break;
			case 5: return str_replace(":83",":8303",$_SESSION['pathcrystalreport']); break;
			case 6: return str_replace(":83",":8304",$_SESSION['pathcrystalreport']); break;
			case 7: return str_replace(":83",":8305",$_SESSION['pathcrystalreport']); break;
			case 8: return str_replace(":83",":8306",$_SESSION['pathcrystalreport']); break;
			case 9: return str_replace(":83",":8307",$_SESSION['pathcrystalreport']); break;
			case 10: $page->reportport = "3307"; return str_replace(":83",":8308",$_SESSION['pathcrystalreport']); break;
			case 11: $page->reportport = "3307"; return str_replace(":83",":8309",$_SESSION['pathcrystalreport']); break;
			case 12: $page->reportport = "3307"; return str_replace(":83",":8310",$_SESSION['pathcrystalreport']); break;
			case 13: $page->reportport = "3307"; return str_replace(":83",":8311",$_SESSION['pathcrystalreport']); break;
			case 14: $page->reportport = "3307"; return str_replace(":83",":8312",$_SESSION['pathcrystalreport']); break;
			case 15: $page->reportport = "3307"; return str_replace(":83",":8313",$_SESSION['pathcrystalreport']); break;
			case 16: $page->reportport = "3307"; return str_replace(":83",":8314",$_SESSION['pathcrystalreport']); break;
			default: return $_SESSION['pathcrystalreport']; break;
		}
		*/
}


?>