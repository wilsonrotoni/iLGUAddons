<?php
 


//$page->businessobject->events->add->customAction("onCustomActionGPSHIS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSHIS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSHIS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSHIS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSHIS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSHIS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSHIS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSHIS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSHIS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSHIS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSHIS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSHIS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSHIS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSHIS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSHIS");

$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSHIS");

$postdata = array();

function onCustomActionGPSHIS($action) {
	return true;
}

function onBeforeDefaultGPSHIS() { 
	global $postdata;
	global $page;
	if ($page->getitemstring("u_inscode")!="") {
		$postdata["u_billno"] = $page->getitemstring("u_billno");
		$postdata["u_reftype"] = $page->getitemstring("u_reftype");
		$postdata["u_refno"] = $page->getitemstring("u_refno");
		$postdata["u_patientid"] = $page->getitemstring("u_patientid");
		$postdata["u_patientname"] = $page->getitemstring("u_patientname");
		$postdata["u_inscode"] = $page->getitemstring("u_inscode");
		$postdata["u_hmo"] = $page->getitemstring("u_hmo");
		$postdata["u_memberid"] = $page->getitemstring("u_memberid");
		$postdata["u_membername"] = $page->getitemstring("u_membername");
		$postdata["u_membertype"] = $page->getitemstring("u_membertype");
		$postdata["u_icdcode"] = $page->getitemstring("u_icdcode");
		$postdata["u_icddesc"] = $page->getitemstring("u_icddesc");
		$postdata["u_rvscode"] = $page->getitemstring("u_rvscode");
		$postdata["u_rvsdesc"] = $page->getitemstring("u_rvsdesc");
		$postdata["u_rvu"] = $page->getitemstring("u_rvu");
		$postdata["u_casetype"] = $page->getitemstring("u_casetype");
		$postdata["u_docdate"] = $page->getitemstring("u_docdate");
		$postdata["u_pkgcode"] = $page->getitemstring("u_pkgcode");
		$postdata["u_pkgdesc"] = $page->getitemstring("u_pkgdesc");
		$postdata["u_pkgamount"] = $page->getitemstring("u_pkgamount");
		$postdata["u_age"] = $page->getitemstring("u_age");
		$postdata["u_gender"] = $page->getitemstring("u_gender");
		$postdata["u_startdate"] = $page->getitemstring("u_startdate");
		$postdata["u_enddate"] = $page->getitemstring("u_enddate");
	}
	return true;
}

function onAfterDefaultGPSHIS() { 
	global $postdata;
	global $page;
	global $objGrids;
	if ($postdata["u_inscode"]!="") {
		$page->setitem("u_billno",$postdata["u_billno"]);
		$page->setitem("u_reftype",$postdata["u_reftype"]);
		$page->setitem("u_refno",$postdata["u_refno"]);
		$page->setitem("u_patientid",$postdata["u_patientid"]);
		$page->setitem("u_patientname",$postdata["u_patientname"]);
		$page->setitem("u_docdate",$postdata["u_docdate"]);
		$page->setitem("u_inscode",$postdata["u_inscode"]);
		$page->setitem("u_hmo",$postdata["u_hmo"]);
		$page->setitem("u_rvscode",$postdata["u_rvscode"]);
		$page->setitem("u_rvsdesc",$postdata["u_rvsdesc"]);
		$page->setitem("u_rvu",$postdata["u_rvu"]);
		$page->setitem("u_casetype",$postdata["u_casetype"]);
		$page->setitem("u_pkgcode",$postdata["u_pkgcode"]);
		$page->setitem("u_pkgdesc",$postdata["u_pkgdesc"]);
		$page->setitem("u_pkgamount",$postdata["u_pkgamount"]);
		$page->setitem("u_startdate",$postdata["u_startdate"]);
		$page->setitem("u_enddate",$postdata["u_enddate"]);
		$page->setitem("u_age",$postdata["u_age"]);
		$page->setitem("u_memberid",$postdata["u_memberid"]);
		$page->setitem("u_membername",$postdata["u_membername"]);
		$page->setitem("u_membertype",$postdata["u_membertype"]);
		$page->setitem("u_gender",$postdata["u_gender"]);
		$inscode = $postdata["u_inscode"];
		$billno = $postdata["u_billno"];
		$rvu = $postdata["u_rvu"];
		$hmo = $postdata["u_hmo"];
		$casetype = $postdata["u_casetype"];
		$objGrids[0]->clear();
		$objGrids[1]->clear();
		$objGrids[2]->clear();
		$objGrids[3]->clear();
		$objGrids[4]->clear();
		$objGrids[5]->clear();
		$objRs = new recordset(null,$objConnection);
		$objRs2 = new recordset(null,$objConnection);
		$totalamount = 0;
		$totalthisamount = 0;
		$scdisc = 0;
		if ($postdata["u_reftype"]=="IP") {
			$objRs->queryopen("select u_icdcode, u_icddesc, u_casetype, u_rvscode, u_rvsdesc, u_rvu from u_hisips where docno='".$postdata["u_refno"]."'");
		} else {
			$objRs->queryopen("select u_icdcode, u_icddesc, u_casetype, u_rvscode, u_rvsdesc, u_rvu from u_hisops where docno='".$postdata["u_refno"]."'");
		}	
		if ($objRs->queryfetchrow("NAME")) {
			$page->setitem("u_icdcode",$objRs->fields["u_icdcode"]);
			$page->setitem("u_icddesc",$objRs->fields["u_icddesc"]);
			$page->setitem("u_casetype",$objRs->fields["u_casetype"]);
			$page->setitem("u_rvscode",$objRs->fields["u_rvscode"]);
			$page->setitem("u_rvsdesc",$objRs->fields["u_rvsdesc"]);
			$page->setitem("u_rvu",$objRs->fields["u_rvu"]);
			$rvu = $objRs->fields["u_rvu"];
			$casetype = $objRs->fields["u_casetype"];
		}
		if ($rvu>0 || $hmo!=0) {
			$objRs->setdebug();
			$objRs->queryopen("select u_scdisc from u_hishealthins where code='$inscode'");
			if ($objRs->queryfetchrow("NAME")) {
				$scdisc = $objRs->fields["u_scdisc"];
			}
			$objRs->queryopen("select code from u_hishealthincts where u_inscode='$inscode' and (u_rvufr=0 or (u_rvufr>0 and '$rvu'>=u_rvufr)) and (u_rvuto=0 or (u_rvuto>0 and '$rvu'<=u_rvuto)) order by u_rvufr");
			if ($objRs->queryfetchrow("NAME")) {
				if ($casetype=="") $casetype=$objRs->fields["code"];
				else $casetype=max($casetype,$objRs->fields["code"]);
				$page->setitem("u_casetype",$casetype);
				
			}
		}		 
		//$objRs->setdebug();
		$objRs->queryopen("select a.u_chrgcode, a.u_daily, a.u_days, a.u_limit, a.u_globalperc from u_hishealthinhfs a, u_hishealthincts b where b.code=a.code and b.u_inscode='$inscode' and a.code='$casetype' and (a.u_rvufr=0 or (a.u_rvufr>0 and '$rvu'>=a.u_rvufr)) and (a.u_rvuto=0 or (a.u_rvuto>0 and '$rvu'<=a.u_rvuto))");
		//var_dump($objRs->sqls);
		while ($objRs->queryfetchrow("NAME")) {
			$objGrids[0]->addrow();
			$objGrids[0]->setitem(null,"u_chrgcode",$objRs->fields["u_chrgcode"]);
			$objGrids[0]->setitem(null,"u_daily",formatNumericAmount($objRs->fields["u_daily"]));
			$objGrids[0]->setitem(null,"u_days",$objRs->fields["u_days"]);
			$objGrids[0]->setitem(null,"u_rvu",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_rvux",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_perc",formatNumericAmount($objRs->fields["u_globalperc"]));
			$objGrids[0]->setitem(null,"u_limit",formatNumericAmount($objRs->fields["u_limit"]));
			$objGrids[0]->setitem(null,"u_grossamount",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_amount",formatNumericAmount(0));
			$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount(0));
			$globalperc = $objRs->fields["u_globalperc"];
			$daily = $objRs->fields["u_daily"];
			$days = $objRs->fields["u_days"];
			$limit = $objRs->fields["u_limit"];
			$amount =0;
			$grossamount =0;
			$thislimit=0;
			
			$discountExp=$globalperc;
			if ($hmo==2) $discountExp="if(c.u_billdiscount=1,".$globalperc.",0)";
			if ($hmo==2 && $scdisc==1) $discountExp2="c.u_scdiscamount";
			else $discountExp2="0";
			//var_dump($discountExp);
			switch ($objRs->fields["u_chrgcode"]) {
				case "ROOM":
					$objRs2->setdebug();
					$objRs2->queryopen("select b.lineid,b.u_roomno,b.u_bedno,b.u_roomdesc, b.u_isroomshared,((b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount))/b.u_quantity) as u_rate,b.u_rateuom,b.u_quantity,b.u_amount as u_grossamount, b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc from u_hisbills a, u_hisbillrooms b, u_hisroomtypes c left outer join u_hisroomtypeins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_roomtype and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' order by lineid");
					if ($objRs2->recordcount()==0) {
						$objRs2->queryopen("select b.lineid,b.u_roomno,b.u_bedno,b.u_roomdesc ,b.u_isroomshared,((b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount))/b.u_quantity) as u_rate,b.u_rateuom,b.u_quantity,b.u_amount as u_grossamount, b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc from u_hisbills a, u_hisbillrooms b left outer join u_hisitems c on c.code=b.u_roomtype left outer join u_hisitemins e on e.code=b.u_roomtype and e.u_inscode='$inscode' where b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' order by lineid");
					}
					while ($objRs2->queryfetchrow("NAME")) {
						$thisamount=0;
						$objGrids[1]->addrow();
						$objGrids[1]->setitem(null,"u_lineid",$objRs2->fields["lineid"]);
						$objGrids[1]->setitem(null,"u_roomdesc",$objRs2->fields["u_roomdesc"]);
						$objGrids[1]->setitem(null,"u_roomno",$objRs2->fields["u_roomno"]);
						$objGrids[1]->setitem(null,"u_bedno",$objRs2->fields["u_bedno"]);
						$objGrids[1]->setitem(null,"u_isroomshared",$objRs2->fields["u_isroomshared"]);
						$objGrids[1]->setitem(null,"u_rate",formatNumericPrice($objRs2->fields["u_rate"]));
						$objGrids[1]->setitem(null,"u_rateuom",$objRs2->fields["u_rateuom"]);
						$objGrids[1]->setitem(null,"u_quantity",$objRs2->fields["u_quantity"]);
						$objGrids[1]->setitem(null,"u_grossamount",formatNumericAmount($objRs2->fields["u_grossamount"]));
						$objGrids[1]->setitem(null,"u_amount",formatNumericAmount($objRs2->fields["u_amount"]));
						$objGrids[1]->setitem(null,"u_otheramount",formatNumericAmount(0));
						$amount += $objRs2->fields["u_amount"];
						$grossamount += $objRs2->fields["u_grossamount"];
						if ($objRs2->fields["u_benefitamount"]!=0 || $objRs2->fields["u_benefitperc"]!=0) {
							$rate = $objRs2->fields["u_rate"];
							if ($objRs2->fields["u_benefitamount"]!=0) $rate = min($rate,$objRs2->fields["u_benefitamount"]);
							else $rate = $rate*($objRs2->fields["u_benefitperc"]/100);

							if ($days>0) {
								if ($daily>0) {
									if ($objRs2->fields["u_quantity"]>=$days) {
										$thisamount+=$days*min($daily,$rate);
										$days=0;
									} elseif ($days>$objRs2->fields["u_quantity"]) {
										$thisamount+=$objRs2->fields["u_quantity"]*min($daily,$rate);
										$days-=$objRs2->fields["u_quantity"];
									}
								} else {
									if ($objRs2->fields["u_quantity"]>=$days) {
										$thisamount+=$days*$rate;
										$days=0;
									} elseif ($days>$objRs2->fields["u_quantity"]) {
										$thisamount+=$objRs2->fields["u_quantity"]*$rate;
										$days-=$objRs2->fields["u_quantity"];
									}
								}	
							} else {
								$thisamount+=$objRs2->fields["u_quantity"]*$rate;
							}
						}	
						$objGrids[1]->setitem(null,"u_thisamount",formatNumericAmount($thisamount));
						$objGrids[1]->setitem(null,"u_netamount",formatNumericAmount($objRs2->fields["u_amount"]-$thisamount));
						$thislimit+=roundAmount($thisamount);
					}
					$objGrids[0]->setitem(null,"u_grossamount",formatNumericAmount($grossamount));
					$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($amount));
					$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount($thislimit));
					$totalamount += $amount;
					$totalthisamount += $thislimit;
					break;
/*				case "MED":
					$objRs2->queryopen("select b.lineid,b.u_refno,b.u_itemcode,b.u_itemdesc,b.u_quantity,((b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount))/b.u_quantity) as u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc from u_hisbills a, u_hisbillmedsups b, u_hisitems c left outer join u_hisitemins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_itemcode and c.u_disctype='MED' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' order by b.lineid");
					while ($objRs2->queryfetchrow("NAME")) {
						$thisamount=0;
						$objGrids[2]->addrow();
						$objGrids[2]->setitem(null,"u_lineid",$objRs2->fields["lineid"]);
						$objGrids[2]->setitem(null,"u_refno",$objRs2->fields["u_refno"]);
						$objGrids[2]->setitem(null,"u_itemcode",$objRs2->fields["u_itemcode"]);
						$objGrids[2]->setitem(null,"u_itemdesc",$objRs2->fields["u_itemdesc"]);
						$objGrids[2]->setitem(null,"u_quantity",formatNumericQuantity($objRs2->fields["u_quantity"]));
						$objGrids[2]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["u_price"]));
						$objGrids[2]->setitem(null,"u_grossamount",formatNumericAmount($objRs2->fields["u_grossamount"]));
						$objGrids[2]->setitem(null,"u_amount",formatNumericAmount($objRs2->fields["u_amount"]));
						$objGrids[2]->setitem(null,"u_otheramount",formatNumericAmount(0));
						$amount += $objRs2->fields["u_amount"];
						$grossamount += $objRs2->fields["u_grossamount"];
						if ($objRs2->fields["u_benefitamount"]!=0 || $objRs2->fields["u_benefitperc"]!=0) {
							$price = $objRs2->fields["u_price"];
							$quantity = $objRs2->fields["u_quantity"];
							if ($objRs2->fields["u_benefitamount"]!=0) $price = min($price,$objRs2->fields["u_benefitamount"]);
							else $price = $price*($objRs2->fields["u_benefitperc"]/100);
							$thisamount+=$quantity*$price;
						}	
						$objGrids[2]->setitem(null,"u_thisamount",formatNumericAmount($thisamount));
						$objGrids[2]->setitem(null,"u_netamount",formatNumericAmount($objRs2->fields["u_amount"]-$thisamount));
						$thislimit+=roundAmount($thisamount);
					}
					$objGrids[0]->setitem(null,"u_grossamount",formatNumericAmount($grossamount));
					$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($amount));
					$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount($thislimit));
					
					if ($limit!=0 && $thislimit>$limit) {
						$prorate = ($limit/$thislimit);
						$thislimit = 0;
						$row=0;
						for ($i = 1; $i <= $objGrids[2]->recordcount; $i++) {
							if ($objGrids[2]->getitemdecimal($i,"u_thisamount")>0) {
								$row=$i;
								$thisamount=round($objGrids[2]->getitemdecimal($i,"u_thisamount")*$prorate,2);
								$objGrids[2]->setitem($i,"u_thisamount",formatNumericAmount($thisamount));
								$objGrids[2]->setitem($i,"u_netamount",formatNumericAmount($objGrids[2]->getitemdecimal($i,"u_amount")-$thisamount));
				
								$thislimit+=roundAmount($thisamount);
							}
						}
						if ($thislimit!=$limit && $row!=0) {
							$objGrids[2]->setitem($row,"u_thisamount",formatNumericAmount($objGrids[2]->getitemdecimal($i,"u_thisamount")+($limit-$thislimit)));
							$thislimit = $limit;
						}
						$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount($thislimit));
					}
					$totalamount += $amount;
					$totalthisamount += $thislimit;
					
					break;					*/
				case "MED":	
				case "LAB":
				case "EXAM":
				case "SUP":
				case "MISC":
					if ($objRs->fields["u_chrgcode"]=="LAB") {
						$objRs2->queryopen("select b.lineid,'LAB' as u_reftype, b.u_refno,b.u_type as u_itemcode,c.name as u_itemdesc,1 as u_quantity,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,0) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc, 0 as u_vatable from u_hisbills a, u_hisbilllabtests b, u_hislabtesttypes c left outer join u_hislabtesttypeins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_type and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' 	UNION ALL select b.lineid,'LAB' as u_reftype, b.u_refno,b.u_type as u_itemcode,c.name as u_itemdesc,1 as u_quantity,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,0) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc, c.u_vatable from u_hisbills a, u_hisbilllabtests b, u_hisitems c left outer join u_hisitemins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_type and c.u_group in ('LAB','XLO','XRY') and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' 	UNION ALL select b.lineid,'SPLROOM' as u_reftype, b.u_refno,b.u_bedno as u_itemcode, c.name as u_itemdesc, b.u_quantity, ((b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount))/b.u_quantity) as u_price,b.u_amount as u_grossamount, (b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount, ifnull(e.u_amount,0) as u_benefitamount,ifnull(e.u_perc,0) as u_benefitperc, 0 as u_vatable from u_hisbills a, u_hisbillsplrooms b, u_hisrooms c, u_hisroomtypes d left outer join u_hisroomtypeins e on e.code=d.code and e.u_inscode='$inscode' where d.code=c.u_roomtype and c.code=b.u_roomno and c.u_roomtype<>'OR' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' UNION ALL select b.lineid,'SUP' as u_reftype,b.u_refno,b.u_itemcode,b.u_itemdesc,b.u_quantity,b.u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,0) as u_benefitamount,ifnull(e.u_perc,0) as u_benefitperc, c.u_vatable from u_hisbills a, u_hisbillmedsups b, u_hisitems c left outer join u_hisitemins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_itemcode and c.u_group='SUP' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' UNION ALL select b.lineid,'MISC' as u_reftype,b.u_refno,b.u_itemcode,b.u_itemdesc,b.u_quantity,b.u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,0 as u_benefitamount,ifnull(e.u_perc,0) as u_benefitperc, c.u_vatable from u_hisbills a, u_hisbillmiscs b, u_hisservices c left outer join u_hisserviceins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_itemcode and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' order by u_reftype, lineid"
						);
					} elseif ($objRs->fields["u_chrgcode"]=="EXAM") {
						$objRs2->queryopen("select b.lineid,'LAB' as u_reftype, 'LAB' as u_billtype, b.u_refno,b.u_type as u_itemcode,c.name as u_itemdesc,1 as u_quantity,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc, 0 as u_vatable from u_hisbills a, u_hisbilllabtests b, u_hisitems c left outer join u_hisitemins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_type and c.u_disctype='EXAM' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' union all select b.lineid,'LAB' as u_reftype, 'MISC' as u_billtype, b.u_refno,b.u_itemcode, b.u_itemdesc, b.u_quantity,b.u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc, 0 as u_vatable from u_hisbills a, u_hisbillmiscs b, u_hisitems c left outer join u_hisitemins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_itemcode and c.u_disctype='EXAM' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno'");
					} elseif ($objRs->fields["u_chrgcode"]=="MED") {
						$objRs2->queryopen("select b.lineid,'MED' as u_reftype, 'MED' as u_billtype,b.u_refno,b.u_itemcode,b.u_itemdesc,b.u_quantity,b.u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc, c.u_vatable from u_hisbills a, u_hisbillmedsups b, u_hisitems c left outer join u_hisitemins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_itemcode and c.u_disctype='MED' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' union all select b.lineid,'MED' as u_reftype, 'MISC' as u_billtype,b.u_refno,b.u_itemcode,b.u_itemdesc,b.u_quantity,b.u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc, c.u_vatable from u_hisbills a, u_hisbillmiscs b, u_hisitems c left outer join u_hisitemins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_itemcode and c.u_disctype='MED' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno'");
					} elseif ($objRs->fields["u_chrgcode"]=="SUP") {
						$objRs2->queryopen("select b.lineid,'SUP' as u_reftype, 'SUP' as u_billtype,b.u_refno,b.u_itemcode,b.u_itemdesc,b.u_quantity,b.u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc, c.u_vatable from u_hisbills a, u_hisbillmedsups b, u_hisitems c left outer join u_hisitemins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_itemcode and c.u_disctype='SUP' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' union all select b.lineid,'SUP' as u_reftype, 'MISC' as u_billtype,b.u_refno,b.u_itemcode,b.u_itemdesc,b.u_quantity,b.u_price,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc, c.u_vatable from u_hisbills a, u_hisbillmiscs b, u_hisitems c left outer join u_hisitemins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_itemcode and c.u_disctype='SUP' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno'");
					} elseif ($objRs->fields["u_chrgcode"]=="MISC") {
						$objRs2->queryopen("select b.lineid,'SPLROOM' as u_reftype, 'SPLROOM' as u_billtype, b.u_refno,b.u_bedno as u_itemcode, c.name as u_itemdesc, b.u_quantity, ((b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount))/b.u_quantity) as u_price,b.u_amount as u_grossamount, (b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount, ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc, 0 as u_vatable from u_hisbills a, u_hisbillsplrooms b, u_hisroomtypes c left outer join u_hisroomtypeins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_roomtype and c.code=b.u_roomno and b.u_roomtype<>'OR' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' union all select b.lineid,'MISC' as u_reftype, 'MISC' as u_billtype,b.u_refno,b.u_itemcode,b.u_itemdesc,b.u_quantity,b.u_price,b.u_amount as u_grossamount, (b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc, c.u_vatable from u_hisbills a, u_hisbillmiscs b, u_hisitems c left outer join u_hisitemins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_itemcode and c.u_disctype='MISC' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' order by u_reftype, lineid"
						);
					}
					while ($objRs2->queryfetchrow("NAME")) {
						$thisamount=0;
						switch ($objRs2->fields["u_reftype"]) {
							case "MED":
								$objGrids[2]->addrow();
								$objGrids[2]->setitem(null,"u_lineid",$objRs2->fields["lineid"]);
								$objGrids[2]->setitem(null,"u_reftype",$objRs2->fields["u_reftype"]);
								$objGrids[2]->setitem(null,"u_billtype",$objRs2->fields["u_billtype"]);
								$objGrids[2]->setitem(null,"u_refno",$objRs2->fields["u_refno"]);
								$objGrids[2]->setitem(null,"u_itemcode",$objRs2->fields["u_itemcode"]);
								$objGrids[2]->setitem(null,"u_itemdesc",$objRs2->fields["u_itemdesc"]);
								$objGrids[2]->setitem(null,"u_quantity",formatNumericQuantity($objRs2->fields["u_quantity"]));
								$objGrids[2]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["u_price"]));
								$objGrids[2]->setitem(null,"u_grossamount",formatNumericAmount($objRs2->fields["u_grossamount"]));
								$objGrids[2]->setitem(null,"u_amount",formatNumericAmount($objRs2->fields["u_amount"]));
								$objGrids[2]->setitem(null,"u_otheramount",formatNumericAmount(0));
								break;
							default:
								$objGrids[3]->addrow();
								$objGrids[3]->setitem(null,"u_lineid",$objRs2->fields["lineid"]);
								$objGrids[3]->setitem(null,"u_reftype",$objRs2->fields["u_reftype"]);
								$objGrids[3]->setitem(null,"u_billtype",$objRs2->fields["u_billtype"]);
								$objGrids[3]->setitem(null,"u_refno",$objRs2->fields["u_refno"]);
								$objGrids[3]->setitem(null,"u_itemcode",$objRs2->fields["u_itemcode"]);
								$objGrids[3]->setitem(null,"u_itemdesc",$objRs2->fields["u_itemdesc"]);
								$objGrids[3]->setitem(null,"u_quantity",formatNumericQuantity($objRs2->fields["u_quantity"]));
								$objGrids[3]->setitem(null,"u_price",formatNumericPrice($objRs2->fields["u_price"]));
								$objGrids[3]->setitem(null,"u_grossamount",formatNumericAmount($objRs2->fields["u_grossamount"]));
								$objGrids[3]->setitem(null,"u_amount",formatNumericAmount($objRs2->fields["u_amount"]));
								$objGrids[3]->setitem(null,"u_otheramount",formatNumericAmount(0));
								break;
						}	
						$amount += $objRs2->fields["u_amount"];
						$grossamount += $objRs2->fields["u_grossamount"];
						
						if ($objRs2->fields["u_benefitamount"]!=0 || $objRs2->fields["u_benefitperc"]!=0) {
							$price = $objRs2->fields["u_amount"]/$objRs2->fields["u_quantity"];
							$quantity = $objRs2->fields["u_quantity"];
							if ($objRs2->fields["u_benefitamount"]!=0) $price = min($price,$objRs2->fields["u_benefitamount"]);
							else {
								//if ($objRs2->fields["u_vatable"]==1) {
								//	$price = ($price/1.12)*($objRs2->fields["u_benefitperc"]/100);
								//} else 
								$price = $price*($objRs2->fields["u_benefitperc"]/100);
							}	
							$thisamount+=$quantity*$price;
						}	
						switch ($objRs2->fields["u_reftype"]) {
							case "MED":
								$objGrids[2]->setitem(null,"u_thisamount",formatNumericAmount($thisamount));
								$objGrids[2]->setitem(null,"u_netamount",formatNumericAmount($objRs2->fields["u_amount"]-$thisamount));
								break;
							default:
								$objGrids[3]->setitem(null,"u_thisamount",formatNumericAmount($thisamount));
								$objGrids[3]->setitem(null,"u_netamount",formatNumericAmount($objRs2->fields["u_amount"]-$thisamount));
								break;	
						}		
						$thislimit+=roundAmount($thisamount);
					}
					$objGrids[0]->setitem(null,"u_grossamount",formatNumericAmount($grossamount));
					$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($amount));
					$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount($thislimit));
					
					if ($limit!=0 && $thislimit>$limit) {
						$prorate = ($limit/$thislimit);
						$thislimit = 0;
						$row=0;
						
						switch ($objRs2->fields["u_reftype"]) {
							case "MED":
								for ($i = 1; $i <= $objGrids[2]->recordcount; $i++) {
									if ($objGrids[2]->getitemdecimal($i,"u_thisamount")>0) {
										$row=$i;
										$thisamount=round($objGrids[2]->getitemdecimal($i,"u_thisamount")*$prorate,2);
										$objGrids[2]->setitem($i,"u_thisamount",formatNumericAmount($thisamount));
										$objGrids[2]->setitem($i,"u_netamount",formatNumericAmount($objGrids[2]->getitemdecimal($i,"u_amount")-$thisamount));
						
										$thislimit+=roundAmount($thisamount);
									}
								}
								if ($thislimit!=$limit && $row!=0) {
									$objGrids[2]->setitem($row,"u_thisamount",formatNumericAmount($objGrids[2]->getitemdecimal($i,"u_thisamount")+($limit-$thislimit)));
									$thislimit = $limit;
								}
								break;
							default:
								for ($i = 1; $i <= $objGrids[3]->recordcount; $i++) {
									if ($objGrids[3]->getitemdecimal($i,"u_thisamount")>0) {
										$row=$i;
										$thisamount=round($objGrids[3]->getitemdecimal($i,"u_thisamount")*$prorate,2);
										$objGrids[3]->setitem($i,"u_thisamount",formatNumericAmount($thisamount));
										$objGrids[3]->setitem($i,"u_netamount",formatNumericAmount($objGrids[3]->getitemdecimal($i,"u_amount")-$thisamount));
						
										$thislimit+=roundAmount($thisamount);
									}
								}
								if ($thislimit!=$limit && $row!=0) {
									$objGrids[3]->setitem($row,"u_thisamount",formatNumericAmount($objGrids[3]->getitemdecimal($i,"u_thisamount")+($limit-$thislimit)));
									$thislimit = $limit;
								}
								break;
						}			
						$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount($thislimit));
					}
					
					$totalamount += $amount;
					$totalthisamount += $thislimit;

					break;			
				case "OR":
					$objRs2->setdebug();
					$objRs2->queryopen("select b.lineid,b.u_refno,b.u_roomno,b.u_bedno,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount))  as u_rate,b.u_rateuom,b.u_quantity,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount,ifnull(e.u_amount,$discountExp2) as u_benefitamount,ifnull(e.u_perc,$discountExp) as u_benefitperc from u_hisbills a, u_hisbillsplrooms b, u_hisroomtypes c left outer join u_hisroomtypeins e on e.code=c.code and e.u_inscode='$inscode' where c.code=b.u_roomtype and c.code=b.u_roomno and b.u_roomtype='OR' and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno' order by b.lineid");
					//var_dump($objRs2->sqls);
					while ($objRs2->queryfetchrow("NAME")) {												
						$thisamount=0;
						$objGrids[4]->addrow();
						$objGrids[4]->setitem(null,"u_lineid",$objRs2->fields["lineid"]);
						$objGrids[4]->setitem(null,"u_refno",$objRs2->fields["u_refno"]);
						$objGrids[4]->setitem(null,"u_roomno",$objRs2->fields["u_roomno"]);
						$objGrids[4]->setitem(null,"u_bedno",$objRs2->fields["u_bedno"]);
						$objGrids[4]->setitem(null,"u_rate",formatNumericPrice($objRs2->fields["u_rate"]));
						$objGrids[4]->setitem(null,"u_rateuom",$objRs2->fields["u_rateuom"]);
						$objGrids[4]->setitem(null,"u_quantity",formatNumericQuantity($objRs2->fields["u_quantity"]));
						$objGrids[4]->setitem(null,"u_grossamount",formatNumericAmount($objRs2->fields["u_grossamount"]));
						$objGrids[4]->setitem(null,"u_amount",formatNumericAmount($objRs2->fields["u_amount"]));
						$objGrids[4]->setitem(null,"u_otheramount",formatNumericAmount(0));
						$amount += $objRs2->fields["u_amount"];
						$grossamount += $objRs2->fields["u_grossamount"];
						//var_dump($objRs2->fields["u_benefitperc"]);
						if ($objRs2->fields["u_benefitamount"]!=0 || $objRs2->fields["u_benefitperc"]!=0) {
							$rate = $objRs2->fields["u_amount"];
							//var_dump($rate);
							$quantity = 1;//$objRs2->fields["u_quantity"];
							if ($objRs2->fields["u_benefitamount"]!=0) $rate = min($rate,$objRs2->fields["u_benefitamount"]);
							else $rate = $rate*($objRs2->fields["u_benefitperc"]/100);
							$thisamount+=$quantity*$rate;
						}	
						$objGrids[4]->setitem(null,"u_thisamount",formatNumericAmount($thisamount));
						$objGrids[4]->setitem(null,"u_netamount",formatNumericAmount($objRs2->fields["u_amount"]-$thisamount));
						$thislimit+=roundAmount($thisamount);
					}
					$objGrids[0]->setitem(null,"u_grossamount",formatNumericAmount($grossamount));
					$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($amount));
					$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount($thislimit));
					
					if ($limit!=0 && $thislimit>$limit) {
						$prorate = ($limit/$thislimit);
						$thislimit = 0;
						$row=0;
						for ($i = 1; $i <= $objGrids[4]->recordcount; $i++) {
							if ($objGrids[4]->getitemdecimal($i,"u_thisamount")>0) {
								$row=$i;
								$thisamount=round($objGrids[4]->getitemdecimal($i,"u_thisamount")*$prorate,2);
								$objGrids[4]->setitem($i,"u_thisamount",formatNumericAmount($thisamount));
								$objGrids[4]->setitem($i,"u_netamount",formatNumericAmount($objGrids[4]->getitemdecimal($i,"u_amount")-$thisamount));
				
								$thislimit+=roundAmount($thisamount);
							}
						}
						if ($thislimit!=$limit && $row!=0) {
							$objGrids[4]->setitem($row,"u_thisamount",formatNumericAmount($objGrids[4]->getitemdecimal($i,"u_thisamount")+($limit-$thislimit)));
							$thislimit = $limit;
						}
						$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount($thislimit));
					}
					$totalamount += $amount;
					$totalthisamount += $thislimit;
					
					break;
			}
		}
		if ($hmo==0) {
			$objRs->queryopen("select b.u_doctorid, b.u_doctortype, e.u_daily, e.u_rvu, e.u_rvux, e.u_perc, e.u_limit from u_hisbills a, u_hisbillconsultancyfees b, u_hisdoctors c, u_hishealthincts d, u_hishealthinpfs e where e.u_type=b.u_doctortype and e.code=d.code and d.code='$casetype' and d.u_inscode='$inscode' and c.code=b.u_doctorid and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.docno='$billno'  group by b.u_doctorid, b.u_doctortype");
			while ($objRs->queryfetchrow("NAME")) {
				$objGrids[0]->addrow();
				$objGrids[0]->setitem(null,"u_chrgcode",$objRs->fields["u_doctorid"].";".$objRs->fields["u_doctortype"]);
				$objGrids[0]->setitem(null,"u_doctorid",$objRs->fields["u_doctorid"]);
				$objGrids[0]->setitem(null,"u_doctortype",$objRs->fields["u_doctortype"]);
				$objGrids[0]->setitem(null,"u_daily",formatNumericAmount($objRs->fields["u_daily"]));
				$objGrids[0]->setitem(null,"u_days",0);
				$objGrids[0]->setitem(null,"u_rvu",formatNumericAmount($objRs->fields["u_rvu"]));
				$objGrids[0]->setitem(null,"u_rvux",formatNumericAmount($objRs->fields["u_rvux"]));
				$objGrids[0]->setitem(null,"u_perc",formatNumericAmount($objRs->fields["u_perc"]));
				$objGrids[0]->setitem(null,"u_limit",formatNumericAmount($objRs->fields["u_limit"]));
				$objGrids[0]->setitem(null,"u_amount",formatNumericAmount(0));
				$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount(0));	
				$doctorid = $objRs->fields["u_doctorid"];	
				$doctortype = $objRs->fields["u_doctortype"];	
				$daily = $objRs->fields["u_daily"];
				$days = $objRs->fields["u_days"];
				$rvuamount = $objRs->fields["u_rvu"] * $rvu * $objRs->fields["u_rvux"];
				$perc = $objRs->fields["u_perc"];
				$limit = $objRs->fields["u_limit"];
				$thislimit=0;
				$amount=0;
				$grossamount=0;
				$objRs2->queryopen("select b.lineid,b.u_refno,b.u_itemcode,b.u_itemdesc,b.u_surgeonfee,b.u_amount as u_grossamount,(b.u_amount-(b.u_discamount+b.u_insamount+b.u_hmoamount)) as u_amount from u_hisbills a, u_hisbillconsultancyfees b where b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_doctorid='$doctorid' and b.u_doctortype='$doctortype' and b.u_itemcode<>'' and a.docno='$billno' order by b.lineid");
				while ($objRs2->queryfetchrow("NAME")) {
					$thisamount=0;
					$objGrids[5]->addrow();
					$objGrids[5]->setitem(null,"u_lineid",$objRs2->fields["lineid"]);
					$objGrids[5]->setitem(null,"u_doctorid",$doctorid);
					$objGrids[5]->setitem(null,"u_doctortype",$doctortype);
					$objGrids[5]->setitem(null,"u_refno",$objRs2->fields["u_refno"]);
					$objGrids[5]->setitem(null,"u_itemcode",$objRs2->fields["u_itemcode"]);
					$objGrids[5]->setitem(null,"u_itemdesc",$objRs2->fields["u_itemdesc"]);
					$objGrids[5]->setitem(null,"u_grossamount",formatNumericAmount($objRs2->fields["u_grossamount"]));
					$objGrids[5]->setitem(null,"u_amount",formatNumericAmount($objRs2->fields["u_amount"]));
					$objGrids[5]->setitem(null,"u_otheramount",formatNumericAmount(0));
					$amount += $objRs2->fields["u_amount"];
					$grossamount += $objRs2->fields["u_grossamount"];
					
					if ($daily>0) {
						$thisamount+=min($objRs2->fields["u_amount"],$daily);
					} elseif ($rvuamount>0) {
						$thisamount+=min($objRs2->fields["u_amount"],$rvuamount);
					} elseif ($perc>0) {
						$thisamount+=$objRs2->fields["u_surgeonfee"]*($perc/100);
					}	
					$objGrids[5]->setitem(null,"u_thisamount",formatNumericAmount($thisamount));
					$objGrids[5]->setitem(null,"u_netamount",formatNumericAmount($objRs2->fields["u_amount"]-$thisamount));
					$thislimit+=roundAmount($thisamount);
				}
				$objGrids[0]->setitem(null,"u_grossamount",formatNumericAmount($grossamount));
				$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($amount));
				$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount($thislimit));
	
				if ($limit!=0 && $thislimit>$limit) {
					$prorate = ($limit/$thislimit);
					$thislimit = 0;
					$row=0;
					for ($i = 1; $i <= $objGrids[5]->recordcount; $i++) {
						if ($objGrids[5]->getitemdecimal($i,"u_thisamount")>0 && $objGrids[5]->getitemstring($i,"u_doctorid")==$doctorid) {
							$row=$i;
							$thisamount=round($objGrids[5]->getitemdecimal($i,"u_thisamount")*$prorate,2);
							$objGrids[5]->setitem($i,"u_thisamount",formatNumericAmount($thisamount));
							$objGrids[5]->setitem($i,"u_netamount",formatNumericAmount($objGrids[5]->getitemdecimal($i,"u_amount")-$thisamount));
							$thislimit+=roundAmount($thisamount);
						}
					}
					if ($thislimit!=$limit && $row!=0) {
						$objGrids[5]->setitem($row,"u_thisamount",formatNumericAmount($objGrids[5]->getitemdecimal($i,"u_thisamount")+($limit-$thislimit)));
						$thislimit = $limit;
					}
					$objGrids[0]->setitem(null,"u_insamount",formatNumericAmount($thislimit));
				}			
				$totalamount += $amount;
				$totalthisamount += $thislimit;
			}	
		}
	}
	$page->setitem("u_amount",formatNumericAmount($totalamount));
	$page->setitem("u_thisamount",formatNumericAmount($totalthisamount));
	$page->setitem("u_netamount",formatNumericAmount($totalamount-$totalthisamount));
	return true;
}

function onPrepareAddGPSHIS(&$override) { 
	return true;
}

function onBeforeAddGPSHIS() { 
	return true;
}

function onAfterAddGPSHIS() { 
	return true;
}

function onPrepareEditGPSHIS(&$override) { 
	return true;
}

function onBeforeEditGPSHIS() { 
	return true;
}

function onAfterEditGPSHIS() { 
	return true;
}

function onPrepareUpdateGPSHIS(&$override) { 
	return true;
}

function onBeforeUpdateGPSHIS() { 
	return true;
}

function onAfterUpdateGPSHIS() { 
	return true;
}

function onPrepareDeleteGPSHIS(&$override) { 
	return true;
}

function onBeforeDeleteGPSHIS() { 
	return true;
}

function onAfterDeleteGPSHIS() { 
	return true;
}

$objRs = new recordset(null,$objConnection);

function onDrawGridColumnLabelGPSHIS($tablename,$column,$row,&$label) {
	global $objRs;
	switch ($tablename) {
		case "T1":
			if ($column=="u_chrgcode") {
				switch ($label) {
					case "ROOM": $label = "Room & Board"; break;
					case "MED": $label = "Drugs & Medicines"; break;
					case "LAB": $label = "X-Ray, Labs & Others"; break;
					case "OR": $label = "Operating Room"; break;
					case "EXAM": $label = "Examinations"; break;
					case "MISC": $label = "Miscellaneous"; break;
					case "SUP": $label = "Medical Supplies"; break;
					default:
						$tmp=explode(";",$label);
						$objRs->queryopen("select name from u_hisdoctors where code='".$tmp[0]."'");
						if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
						else $label = $tmp[0];
						switch ($tmp[1]) {
							case "ANE": $label .= ' (Anesthesiologist)'; break;
							case "GEN": $label .= ' (General Practitioner)';
							case "SPL": $label .= ' (Specialist)'; break;
							case "SUR": $label .= ' (Surgeon)'; break;
						}
						break;
				}
			}	
			break;
		case "T6":
			if ($column=="u_doctorid") {
				$objRs->queryopen("select name from u_hisdoctors where code='$label'");
				if ($objRs->queryfetchrow("NAME"))	$label = $objRs->fields["name"];
			} elseif ($column=="u_doctortype") {	
				switch ($label) {
					case "ANE": $label = 'Anesthesiologist'; break;
					case "GEN": $label = 'General Practitioner';
					case "SPL": $label = 'Specialist'; break;
					case "SUR": $label = 'Surgeon'; break;
				}
			}
			break;
	}
}

$page->businessobject->items->setcfl("u_refno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_rvscode","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_icdcode","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_pkgcode","OpenCFLfs()");

$page->businessobject->items->seteditable("u_billno",false);
$page->businessobject->items->seteditable("u_reftype",false);
$page->businessobject->items->seteditable("u_refno",false);
$page->businessobject->items->seteditable("u_patientid",false);
$page->businessobject->items->seteditable("u_patientname",false);
$page->businessobject->items->seteditable("u_inscode",false);
$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_amount",false);
$page->businessobject->items->seteditable("u_thisamount",false);
$page->businessobject->items->seteditable("u_otheramount",false);
$page->businessobject->items->seteditable("u_netamount",false);
$page->businessobject->items->seteditable("u_casetype",false);
$page->businessobject->items->seteditable("u_icdcode",false);
$page->businessobject->items->seteditable("u_icddesc",false);
$page->businessobject->items->seteditable("u_rvscode",false);
$page->businessobject->items->seteditable("u_rvsdesc",false);
$page->businessobject->items->seteditable("u_rvu",false);

$page->businessobject->items->seteditable("u_xmtalno",false);
$page->businessobject->items->seteditable("u_xmtaldate",false);

$page->businessobject->items->seteditable("u_jvdocno",false);
$page->businessobject->items->seteditable("u_jvcndocno",false);
$page->businessobject->items->seteditable("u_postedby",false);
$page->businessobject->items->seteditable("u_cancelledby",false);
$page->businessobject->items->seteditable("u_cancelledreason",false);
$page->businessobject->items->seteditable("u_cancelledremarks",false);


$objGrids[0]->columntitle("u_chrgcode","Hospital/Prof. Fees");
$objGrids[0]->columnwidth("u_daily",8);
$objGrids[0]->columnwidth("u_days",9);
$objGrids[0]->columnwidth("u_rvu",8);
$objGrids[0]->columnwidth("u_rvux",8);
$objGrids[0]->columnwidth("u_perc",6);
$objGrids[0]->columnwidth("u_limit",9);
$objGrids[0]->columnwidth("u_chrgcode",17);
$objGrids[0]->columnwidth("u_grossamount",12);
$objGrids[0]->columnwidth("u_amount",12);
$objGrids[0]->columnwidth("u_insamount",12);
$objGrids[0]->columnwidth("u_netamount",12);
$objGrids[0]->columntitle("u_amount","This Charges");
//$objGrids[0]->columnvisibility("u_doctorid",true);
//$objGrids[0]->columnvisibility("u_doctortype",true);
$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->dataentry = false;

$objGrids[1]->columnwidth("u_roomno",10);
$objGrids[1]->columnwidth("u_bedno",13);
$objGrids[1]->columnwidth("u_roomdesc",20);
$objGrids[1]->columnwidth("u_amount",13);
$objGrids[1]->columnwidth("u_thisamount",13);
$objGrids[1]->columnwidth("u_otheramount",13);
$objGrids[1]->columntitle("u_amount","This Charges");
$objGrids[1]->columnvisibility("u_lineid",false);
$objGrids[1]->columnvisibility("u_isroomshared",false);
$objGrids[1]->columnvisibility("u_otheramount",false);
$objGrids[1]->columnvisibility("u_roomno",false);
$objGrids[1]->columnvisibility("u_bedno",false);
$objGrids[1]->columninput("u_thisamount","type","text");
$objGrids[1]->automanagecolumnwidth = false;
$objGrids[1]->dataentry = false;

$objGrids[2]->columnwidth("u_refno",12);
$objGrids[2]->columnwidth("u_itemcode",10);
$objGrids[2]->columnwidth("u_itemdesc",25);
$objGrids[2]->columnwidth("u_quantity",7);
$objGrids[2]->columnwidth("u_price",12);
$objGrids[2]->columnwidth("u_grossamount",9);
$objGrids[2]->columnwidth("u_amount",8);
$objGrids[2]->columnwidth("u_thisamount",8);
$objGrids[2]->columnwidth("u_otheramount",8);
$objGrids[2]->columnwidth("u_netamount",8);
$objGrids[2]->columntitle("u_grossamount","Gross");
$objGrids[2]->columntitle("u_thisamount","Benefit");
$objGrids[2]->columntitle("u_amount","Net");
$objGrids[2]->columntitle("u_netamount","Balance");
$objGrids[2]->columnvisibility("u_lineid",false);
$objGrids[2]->columnvisibility("u_otheramount",false);
$objGrids[2]->columninput("u_thisamount","type","text");
$objGrids[2]->automanagecolumnwidth = false;
$objGrids[2]->dataentry = false;

$objGrids[3]->columnwidth("u_refno",12);
$objGrids[3]->columnwidth("u_itemcode",10);
$objGrids[3]->columnwidth("u_itemdesc",25);
$objGrids[3]->columnwidth("u_quantity",9);
$objGrids[3]->columnwidth("u_price",12);

$objGrids[3]->columnwidth("u_grossamount",9);
$objGrids[3]->columnwidth("u_amount",8);
$objGrids[3]->columnwidth("u_thisamount",8);
$objGrids[3]->columnwidth("u_otheramount",8);
$objGrids[3]->columnwidth("u_netamount",8);
$objGrids[3]->columntitle("u_grossamount","Gross");
$objGrids[3]->columntitle("u_thisamount","Benefit");
$objGrids[3]->columntitle("u_amount","Net");
$objGrids[3]->columntitle("u_netamount","Balance");

$objGrids[3]->columnvisibility("u_lineid",false);
$objGrids[3]->columnvisibility("u_reftype",false);
$objGrids[3]->columnvisibility("u_otheramount",false);
$objGrids[3]->columninput("u_thisamount","type","text");
$objGrids[3]->automanagecolumnwidth = false;
$objGrids[3]->dataentry = false;

$objGrids[4]->columnwidth("u_refno",15);
$objGrids[4]->columnwidth("u_roomno",10);
$objGrids[4]->columnwidth("u_bedno",13);
$objGrids[4]->columnwidth("u_grossamount",9);
$objGrids[4]->columnwidth("u_amount",8);
$objGrids[4]->columnwidth("u_thisamount",8);
$objGrids[4]->columnwidth("u_otheramount",8);
$objGrids[4]->columnwidth("u_netamount",8);
$objGrids[4]->columntitle("u_grossamount","Gross");
$objGrids[4]->columntitle("u_thisamount","Benefit");
$objGrids[4]->columntitle("u_amount","Net");
$objGrids[4]->columntitle("u_netamount","Balance");
$objGrids[4]->columnvisibility("u_lineid",false);
$objGrids[4]->columnvisibility("u_otheramount",false);
$objGrids[4]->columninput("u_thisamount","type","text");
$objGrids[4]->automanagecolumnwidth = false;
$objGrids[4]->dataentry = false;

$objGrids[5]->columnwidth("u_refno",15);
$objGrids[5]->columnwidth("u_doctorid",30);
$objGrids[5]->columnwidth("u_itemcode",15);
$objGrids[5]->columnwidth("u_itemdesc",30);
$objGrids[5]->columnwidth("u_amount",13);
$objGrids[5]->columnwidth("u_thisamount",13);
$objGrids[5]->columnwidth("u_otheramount",13);
$objGrids[5]->columnvisibility("u_lineid",false);
$objGrids[5]->columnvisibility("u_otheramount",false);
//$objGrids[5]->columnvisibility("u_doctortype",true);
$objGrids[5]->columninput("u_thisamount","type","text");
$objGrids[5]->automanagecolumnwidth = false;
$objGrids[5]->dataentry = false;


$addoptions = false;
$deleteoption = false;

$page->toolbar->setaction("find",false);
$page->toolbar->setaction("navigation",false);
?> 

