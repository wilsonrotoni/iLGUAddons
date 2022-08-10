<?php
 


function onBeforeAddEventmasterdataschemaGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fa":
			if (($objTable->getudfvalue("u_remainlife")!=0 && $objTable->getudfvalue("u_depredate")!="" && $objTable->getudfvalue("u_bookvalue")!=0)) {
				$actionReturn = onCustomEventmasterdataschemaCreateDepreSchedGPSFixedAsset($objTable);
			}
			break;
	}
	return $actionReturn;
}


/*
function onAddEventmasterdataschemaGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_faclass":
			break;
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventmasterdataschemaGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fa":
			if (($objTable->getudfvalue("u_remainlife")!=$objTable->fields["U_REMAINLIFE"] && $objTable->getudfvalue("u_accumdepre")==$objTable->fields["U_ACCUMDEPRE"]) || ($objTable->getudfvalue("u_cost")>$objTable->fields["U_COST"])) {
				$actionReturn = onCustomEventmasterdataschemaCreateDepreSchedGPSFixedAsset($objTable);
			}
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventmasterdataschemaGPSFixedAsset");
	return $actionReturn;
}

/*
function onUpdateEventmasterdataschemaGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_faclass":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventmasterdataschemaGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_faclass":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventmasterdataschemaGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_faclass":
			break;
	}
	return $actionReturn;
}
*/

function onCustomEventmasterdataschemaCreateDepreSchedGPSFixedAsset($objTable) {
	global $objConnection;
	$actionReturn = true;
	$objRs = new recordset(null,$objConnection);
	$obju_FADepreScheds = new masterdatalinesschema(NULL,$objConnection,"u_fadeprescheds");
	$usedlife=0;

	/*$deprescheds = "M";
	$objRs->queryopen("select u_fadeprescheds from companies where companycode='".$_SESSION["company"]."'");
	if ($objRs->queryfetchrow()) {
		$deprescheds = $objRs->fields[0];
	} else return raiseError("Unable to retrieve depreciation schedule in general settings.");
*/
	$objRs->queryopen("select a.migratedate, b.u_fadeprescheds from branches a inner join companies b on b.companycode=a.companycode where a.companycode='".$_SESSION["company"]."' and a.branchcode='".$_SESSION["branch"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$migratedate = dateadd("d",-1,$objRs->fields["migratedate"]);
		$fadeprescheds = $objRs->fields["u_fadeprescheds"];
	
	} else return raiseError("Unable to retrieve General Settings [Fixed Asset Depreciation Schedule].");
	
	$objRs->queryopen("select count(*) from u_fadeprescheds where code='".$objTable->code."' and u_posted=1");
	if ($objRs->queryfetchrow()) {
		$usedlife = $objRs->fields[0];
		/*switch ($deprescheds) {
			case "S": $usedlife * 6; break;
		}*/
		if ($usedlife > $objTable->getudfvalue("u_remainlife")) {
			return raiseError("New Life [".$objTable->getudfvalue("u_remainlife")."] cannot be less than use life [".$usedlife."].");
		}
	} else return raiseError("Unable to retrieve Asset used life.");

	if (!$obju_FADepreScheds->executesql("delete from u_fadeprescheds where code='".$objTable->code."' and u_posted=0",false)) {
		return raiseError("Error reseting Fix Asset depreciation schedules.");
	}
	
	$day=intval(date('d',strtotime($objTable->getudfvalue("u_depredate"))));
	$remainlife = $objTable->getudfvalue("u_remainlife") - $usedlife;
	//var_dump($objTable->getudfvalue("u_bookvalue"));
	$cost = roundAmount($objTable->getudfvalue("u_bookvalue") / $remainlife);
	$delta =  roundAmount($objTable->getudfvalue("u_bookvalue") - ($cost * $remainlife));
	//var_dump($page->getcolumnstring("T1",$i,"depredate"));
	if ($day!=1) {
		$days1 = 30 - datedifference("d",getmonthstartDB($objTable->getudfvalue("u_depredate")),$objTable->getudfvalue("u_depredate"));
		if ($days1<30) {
			$cost1 = roundAmount(($cost/30) * $days1);
			$cost2 = ($cost - $cost1) + $delta;
			$remainlife++;
		} else $day=1;	
	}	
	$date = getmonthstartDB($objTable->getudfvalue("u_depredate"));
	//var_dump($objTable->getudfvalue("u_depredate"));
	/*switch ($deprescheds) {
		case "S": 
			//var_dump(array($remainlife,$date));
			//echo "<br>";
			$i=0;
			while ($i<$remainlife) {
				if ($i==0) {
					$month = date('m',strtotime($objTable->getudfvalue("u_depredate")));
					if ($month<6) $i = 6-$month;
					elseif ($month<12) $i = 12-$month;
					$amount = $cost*($i+1);
				} else {
					$i+=6;
					$amount = $cost*6;
				}	
				if ($i==$remainlife) $amount += $delta;
				//var_dump($i);
				//echo "<br>";
				$date = dateadd("m",$i,$objTable->getudfvalue("u_depredate"));
				$year = date('Y',strtotime($date));
				$month = date('m',strtotime($date));
				//var_dump($date);
				//echo "<br>";
				$obju_FADepreScheds->prepareadd();
				$obju_FADepreScheds->code = $objTable->code;
				$obju_FADepreScheds->lineid = getNextId($obju_FADepreScheds->dbtable,$objConnection);
				$obju_FADepreScheds->setudfvalue("u_year",$year);
				$obju_FADepreScheds->setudfvalue("u_month",$month);
				$obju_FADepreScheds->setudfvalue("u_amount",$amount);
				$obju_FADepreScheds->privatedata["header"] = $objTable;
				//var_dump(array($year,$month,$amount));
				$actionReturn = $obju_FADepreScheds->add();
				
				if (!$actionReturn) break;
			}			
			
			break;
		default:
		*/	
		//var_dump($remainlife);
		for($i=0;$i<$remainlife;$i++) {
			if ($day==1) {
				if ($i==0) {
					$year = date('Y',strtotime($date));
					$month = date('m',strtotime($date));
					$amount = $cost;
				} else {
					$date = dateadd("m",1,$date);
					if (($i+1)==$remainlife) $cost += $delta;
					$year = date('Y',strtotime($date));
					$month = date('m',strtotime($date));
					$amount = $cost;
				}
			} else {
				if ($i==0) {
					$year = date('Y',strtotime($date));
					$month = date('m',strtotime($date));
					$amount = $cost1;
				} elseif (($i+1)==$remainlife) {
					$date = dateadd("m",1,$date);
					$year = date('Y',strtotime($date));
					$month = date('m',strtotime($date));
					$amount = $cost2;
				} else {
					$date = dateadd("m",1,$date);
					$year = date('Y',strtotime($date));
					$month = date('m',strtotime($date));
					$amount = $cost;
				}
			}
			$obju_FADepreScheds->prepareadd();
			$obju_FADepreScheds->code = $objTable->code;
			$obju_FADepreScheds->lineid = getNextId($obju_FADepreScheds->dbtable,$objConnection);
			$obju_FADepreScheds->setudfvalue("u_year",$year);
			$obju_FADepreScheds->setudfvalue("u_month",$month);
			$obju_FADepreScheds->setudfvalue("u_amount",$amount);
			//var_dump(array($date,$migratedate,$date<=$migratedate));
			//echo "<br>";
			if ($date<=$migratedate) {
				$obju_FADepreScheds->setudfvalue("u_posted",1);
				if ($fadeprescheds=="M") $obju_FADepreScheds->setudfvalue("u_jvdate",getmonthendDB($date));
				elseif ($fadeprescheds=="S") {
					if ($month<=6) {
						$obju_FADepreScheds->setudfvalue("u_jvdate",getmonthendDB($year."-06-01"));
					} else {
						$obju_FADepreScheds->setudfvalue("u_jvdate",getmonthendDB($year."-12-01"));
					}
				} elseif ($fadeprescheds=="Q") {
					if ($month<=3) {
						$obju_FADepreScheds->setudfvalue("u_jvdate",getmonthendDB($year."-03-01"));
					} elseif ($month<=6) {
						$obju_FADepreScheds->setudfvalue("u_jvdate",getmonthendDB($year."-06-01"));
					} elseif ($month<=9) {
						$obju_FADepreScheds->setudfvalue("u_jvdate",getmonthendDB($year."-09-01"));
					} else {
						$obju_FADepreScheds->setudfvalue("u_jvdate",getmonthendDB($year."-12-01"));
					}
				}
				$objTable->setudfvalue("u_accumdepre",floatval($objTable->getudfvalue("u_accumdepre")) + $amount);
				$objTable->setudfvalue("u_bookvalue",floatval($objTable->getudfvalue("u_bookvalue")) - $amount);
				$objTable->setudfvalue("u_remainlife",floatval($objTable->getudfvalue("u_remainlife")) - 1);
					
			}
			$obju_FADepreScheds->privatedata["header"] = $objTable;
			//var_dump(array($year,$month,$amount,$date<=$migratedate));
			//echo "<br>";
			$actionReturn = $obju_FADepreScheds->add();
			if (!$actionReturn) break;
		}			
	/*		break;
	}
	*/			
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventmasterdataschemaCreateDepreSchedGPSFixedAsset");
	return $actionReturn;
}

?>