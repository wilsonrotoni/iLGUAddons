<?php

if ($usergetfields["u_gpshis"]==1) {

	if (!isset($usergetfields["u_packagedprice"])) $usergetfields["u_packagedprice"] = "0";
	
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
	
	$objRs->queryopen("select a.u_price, b.u_scdiscperc from u_hismdrps a left join u_hissetup b on b.code='setup' where a.code='".$itemcode."'");
	if ($objRs->queryfetchrow("NAME")) {
		$usergetfields["u_pricing"] = 0;
		$userpostfields["statperc"] = 0;
		$userpostfields["statamount"] = 0;
		//$discperc = $objRs->fields["u_scdiscperc"];
		$userpostfields["scdiscamount"] = ($objRs->fields["u_scdiscperc"]/100) * $objRs->fields["u_price"];
		$userpostfields["scstatdiscamount"] = 0;
		if ($usergetfields["u_scdisc"]==1) {
			$userpostfields["iscashdisc"] = 1;
			$userpostfields["isbilldisc"] = 1;
		} else {
			$userpostfields["iscashdisc"] = 0;
			$userpostfields["isbilldisc"] = 0;
		}	
		$unitprice = $objRs->fields["u_price"];
		//var_dump($objRs->fields);
	}
	
	$userpostfields["pricing"] = $usergetfields["u_pricing"];
	if ($unitprice==0) $userpostfields["pricing"]="-1";
	
	if ($usergetfields["u_packagedprice"]=="1") {
		$userpostfields["pricing"]="-1";
	}
	
	//var_dump($discperc);
	//var_dump($userpostfields);
	if ($userpostfields["pricing"]=="-1") {
		$unitprice = round(floatval($usergetfields["u_unitprice"]),2);
		if ($usergetfields["u_isautodisc"]=="1") {
			$price = round($unitprice * (1-($discperc/100)),2);
		} else {
			$price = round(floatval($usergetfields["u_price"]),2);
		}	
		$discamount = $unitprice - $price;
	} else {
		$unitprice = round($unitprice,2);
		if (floatval($userpostfields["statperc"])!=0) $userpostfields["statunitprice"] = round($unitprice * (1+($userpostfields["statperc"]/100)),2);
		else $userpostfields["statunitprice"] = round($unitprice + $userpostfields["statamount"],2);
		
		/*if ($usergetfields["u_scdisc"]=="1") {
			if ($usergetfields["u_isstat"]=="1") {
				$price = round($userpostfields["statunitprice"] - $userpostfields["scstatdiscamount"],2);
				$discamount = $userpostfields["statunitprice"] - $price;
			} else {
				$price = round($unitprice - $userpostfields["scdiscamount"],2);
				$discamount = $unitprice - $price;
			}	
		} else {*/
			$scvat = 0;
			if ($usergetfields["u_isstat"]=="1") {
				if ($usergetfields["u_scdisc"]=="1") {
					$scvat = $userpostfields["statunitprice"] - round($userpostfields["statunitprice"]/1.12,2);
					$price = round(round($userpostfields["statunitprice"]/1.12,2) * (1-($discperc/100)),2);
				} else {
					$price = round($userpostfields["statunitprice"] * (1-($discperc/100)),2);
				}	
				$discamount = $userpostfields["statunitprice"] - $price;
			} else {
				if ($usergetfields["u_scdisc"]=="1" || $usergetfields["u_nonvatcs"]=="1") {
					if ($usergetfields["u_prepaid"]=="1" && $usergetfields["u_trxtype"]=="PHARMACY" && $usergetfields["phavatpos"]=="1") {
						$scvat = $unitprice - round($unitprice/1.12,2);
						$price = round(round($unitprice/1.12,2) * (1-($discperc/100)),2);
					} else {
						$price = round($unitprice * (1-($discperc/100)),2);
					}	
				} else {
					$price = round($unitprice * (1-($discperc/100)),2);
				}	
				$discamount = $unitprice - ($price+$scvat);
			}	
		/*}*/
	}	
	//var_dump(array($unitprice,$userpostfields["scdiscamount"],$price));
}

?> 

