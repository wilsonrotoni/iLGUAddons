<?php


function generateDocumentApprovalGPSHIS(&$data,&$override,$objTable) {
	switch ($data["objectcode"]) {
		case "u_hiscashadvances":
			$data["bpcode"] =  $objTable->getudfvalue("u_empid");
			$data["bpname"] =  $objTable->getudfvalue("u_empname");
			$data["docdate"] =  $objTable->getudfvalue("u_docdate");
			$data["remarks"] =  $objTable->getudfvalue("u_purpose");
			break;
	}
	return true;
}

if ($usergetfields["u_gpshis"]==1) {
	$objRs = new recordset(NULL,$objConnection);
	//var_dump($usergetfields);
	$userpostfields["statperc"] = 0;
	$userpostfields["statamount"] = 0;
	$userpostfields["scdiscamount"] = 0;
	$userpostfields["scstatdiscamount"] = 0;
	
	//$objRs->setdebug();
	//if ($usergetfields["u_disccode"]!="") {
		switch ($usergetfields["u_chrgcode"]) {
/*			case "EXAM":
				$objRs->queryopen("select a.code, a.name, u_allowdiscount, u_billdiscount, a.u_statperc, a.u_statamount, if(a.u_allowdiscount=1,ifnull(b.u_globalperc,0),0) as u_globalperc, if(a.u_allowdiscount=1,a.u_scdiscamount,0) as u_scdiscamount, if(a.u_allowdiscount=1,a.u_scstatdiscamount,0) as u_scstatdiscamount from u_hisitems a left join u_hishealthinhfs b on b.code='".$usergetfields["u_disccode"]."' and b.u_chrgcode in ('LAB','EXAM') where a.code='".$itemcode."' and a.u_active=1");
				break;
			case "MISC":
				$objRs->queryopen("select a.code, a.name, u_allowdiscount, u_billdiscount, a.u_statperc, a.u_statamount, if(a.u_allowdiscount=1,ifnull(b.u_globalperc,0),0) as u_globalperc, if(a.u_allowdiscount=1,a.u_scdiscamount,0) as u_scdiscamount, if(a.u_allowdiscount=1,a.u_scstatdiscamount,0) as u_scstatdiscamount from u_hisitems a left join u_hishealthinhfs b on b.code='".$usergetfields["u_disccode"]."' and b.u_chrgcode in ('MISC') where a.code='".$itemcode."' and a.u_active=1");
				break;
			case "PF":
				//$objRs->queryopen("select a.code, a.name, a.u_statperc, a.u_statamount, if(a.u_allowdiscount=1,ifnull(b.u_globalperc,0),0) as u_globalperc, if(a.u_allowdiscount=1,a.u_scdiscamount,0) as u_scdiscamount, if(a.u_allowdiscount=1,a.u_scstatdiscamount,0) as u_scstatdiscamount from u_hisitems a left join u_hishealthinhfs b on b.code='".$usergetfields["u_disccode"]."' and b.u_chrgcode in ('PF') where a.code='".$itemcode."' and a.u_active=1");
				$objRs->queryopen("select a.code, a.name, u_allowdiscount, u_billdiscount, a.u_statperc, a.u_statamount, ifnull(b.u_globalperc,0) as u_globalperc, a.u_scdiscamount, if(a.u_allowdiscount=1,a.u_scstatdiscamount,0) as u_scstatdiscamount from u_hisitems a left join u_hishealthinhfs b on b.code='".$usergetfields["u_disccode"]."' and b.u_chrgcode in ('PF') where a.code='".$itemcode."' and a.u_active=1");
				break;*/
			default:
				$objRs->queryopen("select a.code, a.name, u_allowdiscount, u_billdiscount, a.u_statperc, a.u_statamount, if(a.u_allowdiscount=1,ifnull(b.u_globalperc,0),0) as u_globalperc, if(a.u_allowdiscount=1,a.u_scdiscamount,0) as u_scdiscamount, if(a.u_allowdiscount=1,a.u_scstatdiscamount,0) as u_scstatdiscamount from u_hisitems a join itemgroups c on c.itemgroup=a.u_group left join u_hishealthinhfs b on b.code='".$usergetfields["u_disccode"]."' and b.u_chrgcode=c.u_discgroup where a.code='".$itemcode."' and a.u_active=1");
				break;	
		}		
	//var_dump($objRs->sqls);
		if ($objRs->queryfetchrow("NAME")) {
			$userpostfields["statperc"] = $objRs->fields["u_statperc"];
			$userpostfields["statamount"] = $objRs->fields["u_statamount"];
			$discperc = $objRs->fields["u_globalperc"];
			$userpostfields["scdiscamount"] = $objRs->fields["u_scdiscamount"];
			$userpostfields["scstatdiscamount"] = $objRs->fields["u_scstatdiscamount"];
			$userpostfields["iscashdisc"] = $objRs->fields["u_allowdiscount"];
			$userpostfields["isbilldisc"] = $objRs->fields["u_billdiscount"];
			//var_dump($objRs->fields);
		}
	//}	

	//var_dump($discperc);
	//var_dump($userpostfields);
	if ($usergetfields["u_pricing"]=="-1") {
		$unitprice = floatval($usergetfields["u_unitprice"]);
		if ($usergetfields["u_isautodisc"]=="1") {
			$price = $unitprice * (1-($discperc/100));
		} else {
			$price = floatval($usergetfields["u_price"]);
		}	
		$discamount = $unitprice - $price;
	} else {
		if (floatval($userpostfields["statperc"])!=0) $userpostfields["statunitprice"] = $unitprice * (1+($userpostfields["statperc"]/100));
		else $userpostfields["statunitprice"] = $unitprice + $userpostfields["statamount"];
		
		if ($usergetfields["u_scdisc"]=="1") {
			if ($usergetfields["u_isstat"]=="1") {
				$price = $userpostfields["statunitprice"] - $userpostfields["scstatdiscamount"];
				$discamount = $userpostfields["statunitprice"] - $price;
			} else {
				$price = $unitprice - $userpostfields["scdiscamount"];
				$discamount = $unitprice - $price;
			}	
		} else {
			if ($usergetfields["u_isstat"]=="1") {
				$price = $userpostfields["statunitprice"] * (1-($discperc/100));
				$discamount = $userpostfields["statunitprice"] - $price;
			} else {
				$price = $unitprice * (1-($discperc/100));
				$discamount = $unitprice - $price;
			}	
		}
	}	
	//var_dump(array($unitprice,$userpostfields["scdiscamount"],$price));
}

?>
