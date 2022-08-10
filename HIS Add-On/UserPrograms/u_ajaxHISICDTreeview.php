<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	
	$itemid = isset($_REQUEST['catid'])?$_REQUEST['catid']:0;
	if ($itemid=="0") $itemid = "";
	$objRs = new recordset(null,$objConnection);
	if ($itemid=="") $objRs->queryopen("SELECT * FROM U_HISICDS where U_LEVEL = '1' ORDER BY CODE asc");
	else {
		$objRs->queryopen("SELECT * FROM U_HISICDS where CODE = '$itemid'");
		if ($objRs->queryfetchrow("NAME")) {
			switch ($objRs->fields["U_LEVEL"]) {
				case "1":
					$objRs->queryopen("SELECT * FROM U_HISICDS where U_CHAPTER = '$itemid' AND U_LEVEL=2 ORDER BY CODE asc");
					break;
				case "2":
					$objRs->queryopen("SELECT * FROM U_HISICDS where U_BLOCK = '$itemid' AND U_LEVEL>2 ORDER BY CODE asc");
					break;
			}
		}
	}	
	$i = 0;			
	while( $objRs->queryfetchrow("NAME")) {
		if ($objRs->fields['CODE']=="") $arr[$i]['id'] = "0";
		else $arr[$i]['id'] = $objRs->fields['CODE'];
		
		$arr[$i]['name'] = $objRs->fields['CODE'] . " - " . $objRs->fields['NAME'];
		
		if ($objRs->fields['U_LEVEL']=="1") $arr[$i]['pid'] = "0";
		elseif ($objRs->fields['U_LEVEL']=="2") $arr[$i]['pid'] = $objRs->fields['U_CHAPTER'];
		else $arr[$i]['pid'] = $objRs->fields['U_BLOCK'];
		
		if ($objRs->fields['U_LEVEL']>2)$arr[$i]['haschild'] = false;
		else $arr[$i]['haschild'] = true;
		
		$i ++ ;
	}
	$arrReturn['data'] = $arr;
	$arrReturn['id'] = @$_REQUEST['id'];
	$arrReturn['value'] = $itemid;
	$jsonstring = json_encode($arrReturn);
	echo $jsonstring;

?>