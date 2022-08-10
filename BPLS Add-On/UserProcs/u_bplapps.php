
<?php
 
include_once("../Addons/GPS/BPLS Add-On/UserProcs/sls/u_bplkinds.php");
include_once("./sls/enumyear.php");

//$page->businessobject->events->add->customAction("onCustomActionGPSBPLS");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSBPLS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSBPLS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSBPLS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSBPLS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSBPLS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSBPLS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSBPLS");
$page->businessobject->events->add->afterEdit("onAfterEditGPSBPLS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSBPLS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSBPLS");
$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSBPLS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSBPLS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSBPLS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSBPLS");
$page->businessobject->events->add->drawGridColumnLabel("onDrawGridColumnLabelGPSBPLS");

$page->businessobject->events->add->prepareChildEdit("onPrepareChildEditGPSBPLS");

$appdata= array();

function onPrepareChildEditGPSBPLS($objGrid,$objTable,$tablename,&$override,&$filerExp) {
	switch ($tablename) {
		case "T5":	
			$filerExp = " ORDER BY u_year,u_seqno";
			break;
		case "T9":	
			$filerExp = " ORDER BY u_seqno";
			break;
	}
        return true;
}

function onDrawGridColumnLabelGPSBPLS($tablename,$column,$row,&$label) {
	global $objRs;
	global $trxrunbal;
	switch ($tablename) {
		case "T1":
			if ($column=="u_businessline") {
				$objRs->queryopen("select name from u_bpllines where code='".$label."'");
				if ($objRs->queryfetchrow()) $label = $objRs->fields[0];
			}	
			if ($column=="u_taxclass") {
				$objRs->queryopen("select name from u_bpltaxclasses where code='".$label."'");
				if ($objRs->queryfetchrow()) $label = $objRs->fields[0];
			}	
	}
}

function onCustomActionGPSBPLS($action) {
	return true;
}

function onBeforeDefaultGPSBPLS() { 
	global $page;
	global $appdata;
	$appdata["u_bpno"] = $page->getitemstring("u_bpno");
	$appdata["u_apptype"] = $page->getitemstring("u_apptype");
	$appdata["u_businessname"] = $page->getitemstring("u_businessname");

	return true;
}

function onAfterDefaultGPSBPLS() { 
    	global $objConnection;
	global $page;
	global $objGrids;
	global $objGridA;
	global $appdata;
        
        $objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.u_MUNICIPALITY,A.U_CITY, A.u_province from U_LGUSETUP A");
	if ($objRs_uLGUSetup->queryfetchrow("NAME")) {
                if($objRs_uLGUSetup->fields["u_MUNICIPALITY"] !=""){
                    $page->setitem("u_bcity",$objRs_uLGUSetup->fields["u_MUNICIPALITY"]);
                }else{
                    $page->setitem("u_bcity",$objRs_uLGUSetup->fields["U_CITY"]);
                }
		$page->setitem("u_bprovince",$objRs_uLGUSetup->fields["u_province"]);
	}
        $page->setitem("u_year",date('Y'));
        $page->setitem("u_assyearto",date('Y'));
        
        $page->setitem("billingexist",0);
	$page->setitem("u_appdate",currentdate());
	$page->setitem("docstatus","Encoding");
	$page->setitem("u_apptype","NEW");
	$page->setitem("u_paymode","A");
	$page->setitem("u_orgtype","SINGLE");
	$page->setitem("u_envpaymode","A");
        $page->businessobject->items->seteditable("u_appno",false);
        
	if($_SESSION["userid"] == 'bplcommon'){
            $page->setitem("docstatus","Common");
            $page->setitem("u_apptype","NEW");
            $page->businessobject->items->seteditable("u_apptype",false);
        }
	$objRs = new recordset(null,$objConnection);
	$objRs_CntYear = new recordset(null,$objConnection);
	if ($appdata["u_bpno"]!="") {
		$page->setitem("u_bpno",$appdata["u_bpno"]);
		if ($appdata["u_apptype"]=="RENEW") {
			$page->setitem("u_apptype","RENEW");
                        $page->businessobject->items->seteditable("u_businessname",false);
//			if (date('m')=="12") {
//				$page->setitem("u_appdate",formatDateToHttp((date("Y")+1)."-01-01"));
//				$page->setitem("docno",getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("docseries"),$page->getitemdate("u_appdate"),$objConnection,false));
//			}
		}
                if ($appdata["u_apptype"]=="RETIRED") {
			$page->setitem("u_apptype","RETIRED");
                }
		$obju_BPLApps = new documentschema_br(null,$objConnection,"u_bplapps");
		$obju_BPLMDs = new masterdataschema_br(null,$objConnection,"u_bplmds");
		if ($obju_BPLApps->getbykey($appdata["u_bpno"])) {
                    
                        $page->setitem("u_lastname",$obju_BPLApps->getudfvalue("u_lastname"));
			$page->setitem("u_firstname",$obju_BPLApps->getudfvalue("u_firstname"));
			$page->setitem("u_middlename",$obju_BPLApps->getudfvalue("u_middlename"));
			$page->setitem("u_telno",$obju_BPLApps->getudfvalue("u_telno"));
			$page->setitem("u_email",$obju_BPLApps->getudfvalue("u_email"));
                        
			$page->setitem("u_owneraddress",$obju_BPLApps->getudfvalue("u_owneraddress"));
			$page->setitem("u_tradename",$obju_BPLApps->getudfvalue("u_tradename"));
			$page->setitem("u_regno",$obju_BPLApps->getudfvalue("u_regno"));
			$page->setitem("u_regdate",formatDateToHttp($obju_BPLApps->getudfvalue("u_regdate")));
			$page->setitem("u_orgtype",$obju_BPLApps->getudfvalue("u_orgtype"));
			$page->setitem("u_appno",$obju_BPLApps->getudfvalue("u_appno"));
			$page->setitem("u_tlastname",$obju_BPLApps->getudfvalue("u_tlastname"));
			$page->setitem("u_tfirstname",$obju_BPLApps->getudfvalue("u_tfirstname"));
			$page->setitem("u_tmiddlename",$obju_BPLApps->getudfvalue("u_tmiddlename"));
                        
			$page->setitem("u_businessarea",$obju_BPLApps->getudfvalue("u_businessarea"));
			$page->setitem("u_mempcount",$obju_BPLApps->getudfvalue("u_mempcount"));
			$page->setitem("u_fempcount",$obju_BPLApps->getudfvalue("u_fempcount"));
			$page->setitem("u_empcount",$obju_BPLApps->getudfvalue("u_empcount"));
			$page->setitem("u_emplgucount",$obju_BPLApps->getudfvalue("u_emplgucount"));
			$page->setitem("u_alertmobileno",$obju_BPLApps->getudfvalue("u_alertmobileno"));
                        
                        $page->setitem("u_bbrgy",$obju_BPLApps->getudfvalue("u_bbrgy"));
			$page->setitem("u_bbldgno",$obju_BPLApps->getudfvalue("u_bbldgno"));
                        $page->setitem("u_bblock",$obju_BPLApps->getudfvalue("u_bblock"));
                        $page->setitem("u_blotno",$obju_BPLApps->getudfvalue("u_blotno"));
			$page->setitem("u_bstreet",$obju_BPLApps->getudfvalue("u_bstreet"));
			$page->setitem("u_bphaseno",$obju_BPLApps->getudfvalue("u_bphaseno"));
                        $page->setitem("u_baddressno",$obju_BPLApps->getudfvalue("u_baddressno"));
			$page->setitem("u_btelno",$obju_BPLApps->getudfvalue("u_btelno"));
                        
                        $page->setitem("u_bunittype",$obju_BPLApps->getudfvalue("u_bunittype"));
                        $page->setitem("u_bvillage",$obju_BPLApps->getudfvalue("u_bvillage"));
			$page->setitem("u_bbldgname",$obju_BPLApps->getudfvalue("u_bbldgname"));
			$page->setitem("u_bunitno",$obju_BPLApps->getudfvalue("u_bunitno"));
			$page->setitem("u_bfloorno",$obju_BPLApps->getudfvalue("u_bfloorno"));
			$page->setitem("u_bcity",$obju_BPLApps->getudfvalue("u_bcity"));
			$page->setitem("u_bprovince",$obju_BPLApps->getudfvalue("u_bprovince"));
			$page->setitem("u_bemail",$obju_BPLApps->getudfvalue("u_bemail"));
                        $page->setitem("u_blandmark",$obju_BPLApps->getudfvalue("u_blandmark"));
                        
                        $page->setitem("u_corpname",$obju_BPLApps->getudfvalue("u_corpname"));
			$page->setitem("u_secregno",formatDateToHttp($obju_BPLApps->getudfvalue("u_secregno")));
			$page->setitem("u_secregdate",$obju_BPLApps->getudfvalue("u_secregdate"));
			$page->setitem("u_businessname",$obju_BPLApps->getudfvalue("u_businessname"));
                        $page->setitem("u_llastname",$obju_BPLApps->getudfvalue("u_llastname"));
			$page->setitem("u_lfirstname",$obju_BPLApps->getudfvalue("u_lfirstname"));
			$page->setitem("u_lmiddlename",$obju_BPLApps->getudfvalue("l_lmiddlename"));
                        $page->setitem("u_monthlyrental",formatNumericAmount($obju_BPLApps->getudfvalue("u_monthlyrental")));
			$page->setitem("u_ltelno",$obju_BPLApps->getudfvalue("u_ltelno"));
                        
			$page->setitem("u_lessoraddress",$obju_BPLApps->getudfvalue("u_lessoraddress"));
			$page->setitem("u_bldgname",$obju_BPLApps->getudfvalue("u_bldgname"));
			$page->setitem("u_unitno",$obju_BPLApps->getudfvalue("u_unitno"));
			$page->setitem("u_street",$obju_BPLApps->getudfvalue("u_street"));
			$page->setitem("u_village",$obju_BPLApps->getudfvalue("u_village"));
			$page->setitem("u_brgy",$obju_BPLApps->getudfvalue("u_brgy"));
			$page->setitem("u_city",$obju_BPLApps->getudfvalue("u_city"));
			$page->setitem("u_province",$obju_BPLApps->getudfvalue("u_province"));

			$page->setitem("u_tin",$obju_BPLApps->getudfvalue("u_tin"));
			$page->setitem("u_taxinc",$obju_BPLApps->getudfvalue("u_taxinc"));
			$page->setitem("u_taxincname",$obju_BPLApps->getudfvalue("u_taxincname"));
                        $page->setitem("u_gender",$obju_BPLApps->getudfvalue("u_gender"));
			$page->setitem("u_sameasowneraddr",$obju_BPLApps->getudfvalue("u_sameasowneraddr"));
			$page->setitem("u_bldgno",$obju_BPLApps->getudfvalue("u_bldgno"));
			$page->setitem("u_lbldgno",$obju_BPLApps->getudfvalue("u_lbldgno"));
			$page->setitem("u_lstreet",$obju_BPLApps->getudfvalue("u_lstreet"));
			$page->setitem("u_lvillage",$obju_BPLApps->getudfvalue("u_lvillage"));
			$page->setitem("u_lbrgy",$obju_BPLApps->getudfvalue("u_lbrgy"));
			$page->setitem("u_lcity",$obju_BPLApps->getudfvalue("u_lcity"));
			$page->setitem("u_lprovince",$obju_BPLApps->getudfvalue("u_lprovince"));
			$page->setitem("u_lemail",$obju_BPLApps->getudfvalue("u_lemail"));
			$page->setitem("u_contactpercon",$obju_BPLApps->getudfvalue("u_contactpercon"));
			$page->setitem("u_contacttelno",$obju_BPLApps->getudfvalue("u_contacttelno"));
			$page->setitem("u_prevbtaxamount",$obju_BPLApps->getudfvalue("u_btaxamount"));
			$page->setitem("u_businesschar",$obju_BPLApps->getudfvalue("u_businesschar"));
			$page->setitem("u_businesskind",$obju_BPLApps->getudfvalue("u_businesskind"));
			$page->setitem("u_remarks",$obju_BPLApps->getudfvalue("u_remarks"));
                        //$page->setitem("u_asstotal",$obju_BPLApps->getudfvalue("u_asstotal"));
                       // $page->setitem("u_fireasstotal",$obju_BPLApps->getudfvalue("u_fireasstotal"));
//			$page->setitem("u_taxclass",$obju_BPLApps->getudfvalue("u_taxclass"));
			$page->setitem("u_businesscategory",$obju_BPLApps->getudfvalue("u_businesscategory"));
//			$page->setitem("u_garbagetax",$obju_BPLApps->getudfvalue("u_garbagetax"));
//			$page->setitem("u_weightstax",$obju_BPLApps->getudfvalue("u_weightstax"));
			
//			if ($obju_BPLApps->getudfvalue("u_bbrgy")!="") {
//				$objRs->queryopen("select u_garbagefeec, u_garbagefeer, u_electricalcert from u_barangays where code='".$obju_BPLApps->getudfvalue("u_bbrgy")."'");
//				if ($objRs->queryfetchrow("NAME")) {
//					$page->setitem("u_garbagefeec",formatNumericAmount($objRs->fields["u_garbagefeec"]));
//					$page->setitem("u_garbagefeer",formatNumericAmount($objRs->fields["u_garbagefeer"]));
//				}	
//			}	
                        
                        //FOR UNPAID BILLS
                           
                        $total=0;	
                        $firefee=0;	
                        // check lastpaidyear
                        $lastpayyear = 0;
                        $lastpayqtrtax = 0;
                        $lastpayqtrenvi = 0;
                        $cntpayyear = 0;
                        $objRs_CntYear->queryopen("SELECT COUNT(U_PAYYEAR) U_PAYYEAR FROM (SELECT U_PAYYEAR FROM U_BPLLEDGER WHERE company='$obju_BPLApps->company' and branch='$obju_BPLApps->branch' AND U_ACCTNO = '".$obju_BPLApps->getudfvalue("u_appno")."' AND U_ISCANCELLED <> 1 GROUP BY U_PAYYEAR) AS X");
                            if ($objRs_CntYear->queryfetchrow("NAME")) {
                                 $cntpayyear = $objRs_CntYear->fields["U_PAYYEAR"];
                            }
                        $objRs->queryopen("SELECT MAX(U_PAYYEAR) as U_PAYYEAR FROM U_BPLLEDGER  WHERE company='$obju_BPLApps->company' and branch='$obju_BPLApps->branch' AND U_ACCTNO = '".$obju_BPLApps->getudfvalue("u_appno")."' AND U_ISCANCELLED <> 1");
                        if ($objRs->queryfetchrow("NAME")) {
                            $lastpayyear = $objRs->fields["U_PAYYEAR"];
                            $objRs->queryopen("SELECT MAX(U_QUARTER) as U_PAYQTR FROM U_BPLLEDGER  WHERE COMPANY='$obju_BPLApps->company' and BRANCH='$obju_BPLApps->branch' AND U_ACCTNO = '".$obju_BPLApps->getudfvalue("u_appno")."' AND U_ISCANCELLED <> 1 AND U_PAYYEAR = '".$lastpayyear."' and U_FEEID IN ('1','5','0001') ");
                                if ($objRs->queryfetchrow("NAME")) {
                                              $lastpayqtrtax = $objRs->fields["U_PAYQTR"];
                                }
                            $objRs->queryopen("SELECT MAX(U_QUARTER) as U_PAYQTR FROM U_BPLLEDGER  WHERE COMPANY='$obju_BPLApps->company' and BRANCH='$obju_BPLApps->branch' AND U_ACCTNO = '".$obju_BPLApps->getudfvalue("u_appno")."' AND U_ISCANCELLED <> 1 AND U_PAYYEAR = '".$lastpayyear."' and U_FEEID IN ('2','0005') ");
                                if ($objRs->queryfetchrow("NAME")) {
                                              $lastpayqtrenvi = $objRs->fields["U_PAYQTR"];
                                }
                        }
                        //FOR UNPAID QUARTERS TAX
                        if ($lastpayqtrtax != 0 && $lastpayqtrtax != 4 && $lastpayyear < date('Y') && $obju_BPLApps->getudfvalue("u_apptype") != "NEW" && $cntpayyear > 1) {
//                                $lastpayqtrtax = $lastpayqtrtax + 1;
                                $objRs->queryopen("select u_businessline, u_unitcount, u_taxclass, sum(u_capital) as u_capital, sum(u_nonessential) as u_nonessential, u_btaxlinetotal, sum(u_essential) as u_essential from u_bplapplines where company='$obju_BPLApps->company' and branch='$obju_BPLApps->branch' and docid='$obju_BPLApps->docid' group by u_businessline");
                                while ($objRs->queryfetchrow("NAME")) {
                                    $grosssales1 = 0; $baseperc1=100; $taxamount1=0; $taxrate1=0; $fromamount1 = 0; $excessamount1 = 0; $compbased = ''; $annualtaxamount = 0;
                                    
                                    $objRs_Line = new recordset(null,$objConnection);
                                    $objRs_TaxClass = new recordset(null,$objConnection);
                                    $objRs_Gross = new recordset(null,$objConnection);
                                    
                                    $objRs_Gross->queryopen("SELECT U_TAXBASE FROM U_BPLLEDGER  WHERE COMPANY='$obju_BPLApps->company' and BRANCH='$obju_BPLApps->branch' AND U_ACCTNO = '".$obju_BPLApps->getudfvalue("u_appno")."' AND U_ISCANCELLED <> 1 AND U_PAYYEAR = '".$lastpayyear."' and U_FEEID IN ('1','5','0001') and U_BUSINESSLINEID = '".$objRs->fields["u_businessline"]."' ORDER BY U_QUARTER DESC LIMIT 1");
                                    while ($objRs_Gross->queryfetchrow("NAME")) {
                                        $grosssales1 = floatval($objRs_Gross->fields["U_TAXBASE"]);
                                    }
                                    $objRs_Line->queryopen("select u_compbased,u_fixedamount from u_bpllines where code = '".$objRs->fields["u_businessline"]."'");
                                        while ($objRs_Line->queryfetchrow("NAME")) {
                                            if ($objRs_Line->fields["u_compbased"] == 1) { 
                                                $taxamount1 = floatval($objRs_Line->fields["u_fixedamount"]);
                                            } else {
                                                $objRs_TaxClass->queryopen("select b.u_excessamount,a.u_baseperc, b.u_amount, b.u_rate,b.u_from from u_bpltaxclasses a, u_bpltaxclasstaxes b where b.code=a.code and ".$grosssales1." >= b.u_from and (".$grosssales1." <=b.u_to or b.u_to=0) and a.code = '".$objRs->fields["u_taxclass"]."'");
                                                    while ($objRs_TaxClass->queryfetchrow("NAME")) {
                                                        $baseperc1 = $objRs_TaxClass->fields["u_baseperc"];
                                                        $taxamount1 = $objRs_TaxClass->fields["u_amount"];
                                                        $taxrate1 = $objRs_TaxClass->fields["u_rate"];
                                                        $excessamount1 = $objRs_TaxClass->fields["u_excessamount"];
                                                        $fromamount1 = $objRs_TaxClass->fields["u_from"];
                                                    }
                                            }
                                            if ($taxamount1>0 && $excessamount1==0 && $taxrate1==0) {
                                                $annualtaxamount = $taxamount1*($baseperc1/100);
                                            }else if($taxamount1==0 && $excessamount1==0 && $taxrate1>0 ) {
                                                $annualtaxamount = $taxrate1*$grosssales1*($baseperc1/100);
                                            }else if($taxamount1>0 && $excessamount1>0 && $taxrate1>0 ){
                                                $annualtaxamount = (((($grosssales1 - $fromamount1) / $excessamount1) * $taxrate1 ) + $taxamount1)*($baseperc1/100);
                                            }
                                            if($annualtaxamount<220) $annualtaxamount = 220;
                                            $annualtaxamount = floatval(($annualtaxamount / 4) * (4 - $lastpayqtrtax));
                                    }
                                    
                                    //Business Tax
                                    $objGrids[4]->addrow();
                                    $objGrids[4]->setitem(null,"u_year",$lastpayyear);
                                    $objGrids[4]->setitem(null,"u_feecode","0001");
                                    $objGrids[4]->setitem(null,"u_feedesc","Business Tax");
                                    $objGrids[4]->setitem(null,"u_amount",formatNumericAmount($annualtaxamount));
                                    $objGrids[4]->setitem(null,"u_interest",formatNumericAmount(0));
                                    $objGrids[4]->setitem(null,"u_surcharge",formatNumericAmount(0));
                                    $objGrids[4]->setitem(null,"u_taxbase",formatNumericAmount($grosssales1));
                                    $objGrids[4]->setitem(null,"u_businessline",$objRs->fields["u_businessline"]);
                                    $objGrids[4]->setitem(null,"u_common",1);
                                    $objGrids[4]->setitem(null,"u_quantity",1);
                                    $objGrids[4]->setitem(null,"u_regulatory",0);
                                    $objGrids[4]->setitem(null,"u_seqno",0);
                                    $total+=$annualtaxamount;
                                    
                                    // Business Line
                                    $objGrids[0]->addrow();
                                    $objGrids[0]->setitem(null,"u_year",$lastpayyear);
                                    $objGrids[0]->setitem(null,"u_businessline",$objRs->fields["u_businessline"]);
                                    $objGrids[0]->setitem(null,"u_unitcount",$objRs->fields["u_unitcount"]);
                                    $objGrids[0]->setitem(null,"u_btaxlinetotal",formatNumericAmount($annualtaxamount));
                                    $objGrids[0]->setitem(null,"u_taxclass",$objRs->fields["u_taxclass"]);
                                    $objGrids[0]->setitem(null,"u_capital",formatNumericAmount(0));
                                    $objGrids[0]->setitem(null,"u_essential",formatNumericAmount(0));
                                    $objGrids[0]->setitem(null,"u_nonessential",formatNumericAmount($grosssales1));
                                    $objGrids[0]->setitem(null,"u_surcharge",formatNumericAmount(0));
                                    $objGrids[0]->setitem(null,"u_interest",formatNumericAmount(0));
                                    $objGrids[0]->setitem(null,"u_lastyrgrsales",formatNumericAmount(0));
                                
                                    
                                }
                            
                        }
                        
                        //FOR UNPAID QUARTERS Environmental
                        if ($lastpayqtrenvi != 0 && $lastpayqtrenvi != 4 && $lastpayyear < date('Y') && $obju_BPLApps->getudfvalue("u_apptype") != "NEW" && $cntpayyear > 1) {
                            $objRs->queryopen("SELECT SUM(U_AMOUNTPAID) as U_AMOUNTPAID FROM U_BPLLEDGER  WHERE COMPANY='$obju_BPLApps->company' and BRANCH='$obju_BPLApps->branch' AND U_ACCTNO = '".$obju_BPLApps->getudfvalue("u_appno")."' AND U_ISCANCELLED <> 1 AND U_PAYYEAR = '".$lastpayyear."' and U_FEEID IN ('2','0005') group by U_ACCTNO");
                                while ($objRs->queryfetchrow("NAME")) {
                                    $totalevni= floatval($objRs->fields["U_AMOUNTPAID"] / $lastpayqtrenvi) * (4 - $lastpayqtrenvi);
                                    //Environmental Tax
                                    $objGrids[4]->addrow();
                                    $objGrids[4]->setitem(null,"u_year",$lastpayyear);
                                    $objGrids[4]->setitem(null,"u_feecode","0005");
                                    $objGrids[4]->setitem(null,"u_feedesc","Environmental Fee");
                                    $objGrids[4]->setitem(null,"u_amount",formatNumericAmount($totalevni));
                                    $objGrids[4]->setitem(null,"u_interest",formatNumericAmount(0));
                                    $objGrids[4]->setitem(null,"u_surcharge",formatNumericAmount(0));
                                    $objGrids[4]->setitem(null,"u_taxbase",formatNumericAmount(0));
                                    $objGrids[4]->setitem(null,"u_businessline","");
                                    $objGrids[4]->setitem(null,"u_common",1);
                                    $objGrids[4]->setitem(null,"u_quantity",1);
                                    $objGrids[4]->setitem(null,"u_regulatory",1);
                                    $objGrids[4]->setitem(null,"u_seqno",30);
                                    $firefee+=$objRs->fields["u_amount"];
                                    $total+=$annualtaxamount;
                                }
                        }
                        
                        //FOR CURRENT YEAR 
                        if ($lastpayyear > 0 )  $lastpayyear = $lastpayyear + 1;
                        else $lastpayyear = date('Y');
                        for ($ctr = $lastpayyear ; $ctr <= date('Y') ; $ctr++){ // include unrenewed years
                                
                                $objRs->queryopen("select u_businessline, u_unitcount, u_taxclass, sum(u_capital) as u_capital, sum(u_nonessential) as u_nonessential, u_btaxlinetotal, sum(u_essential) as u_essential from u_bplapplines where company='$obju_BPLApps->company' and branch='$obju_BPLApps->branch' and docid='$obju_BPLApps->docid' group by u_businessline");
                                while ($objRs->queryfetchrow("NAME")) {
                                        $objGrids[0]->addrow();
                                        $objGrids[0]->setitem(null,"u_year",$ctr);
                                        $objGrids[0]->setitem(null,"u_businessline",$objRs->fields["u_businessline"]);
                                        $objGrids[0]->setitem(null,"u_unitcount",$objRs->fields["u_unitcount"]);
                                        $objGrids[0]->setitem(null,"u_btaxlinetotal",formatNumericAmount(0));
                                        $objGrids[0]->setitem(null,"u_taxclass",$objRs->fields["u_taxclass"]);
                                        $objGrids[0]->setitem(null,"u_capital",formatNumericAmount(0));
                                        $objGrids[0]->setitem(null,"u_essential",formatNumericAmount(0));
                                        $objGrids[0]->setitem(null,"u_nonessential",formatNumericAmount(0));
                                        $objGrids[0]->setitem(null,"u_surcharge",formatNumericAmount(0));
                                        $objGrids[0]->setitem(null,"u_interest",formatNumericAmount(0));
                                        $objGrids[0]->setitem(null,"u_lastyrgrsales",formatNumericAmount($objRs->fields["u_nonessential"]+$objRs->fields["u_essential"]+$objRs->fields["u_capital"]));
                                }
                                
                                $objRs->queryopen("select u_feecode, u_feedesc, sum(u_amount) as u_amount, u_interest, u_surcharge,u_seqno,u_businessline,u_taxbase, u_common, u_quantity, u_regulatory
                                                    from (
                                                      select a.u_feecode, a.u_feedesc, a.u_amount, 0 as u_interest, 0 as u_surcharge,a.u_seqno,a.u_businessline,a.u_taxbase, a.u_common, a.u_quantity, a.u_regulatory from u_bplappfees a where a.company='$obju_BPLApps->company' and a.branch='$obju_BPLApps->branch' and a.docid='$obju_BPLApps->docid' and u_feecode != '0001'
                                                      union all
                                                      select code as u_feecode, name u_feedesc, 0 as amount,0 as u_interest,0 as u_surcharge,u_seqno,'' u_businessline,0 as u_taxbase, 0 as u_common, 0 as u_quantity, u_regulatory   from u_bplfees ) as x
                                                    group by x.u_feecode
                                                    order by x.u_seqno");
                                while ($objRs->queryfetchrow("NAME")) {
                                        $objGrids[4]->addrow();
                                        $objGrids[4]->setitem(null,"u_year",$ctr);
                                        $objGrids[4]->setitem(null,"u_feecode",$objRs->fields["u_feecode"]);
                                        $objGrids[4]->setitem(null,"u_feedesc",$objRs->fields["u_feedesc"]);
                                        $objGrids[4]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                                        $objGrids[4]->setitem(null,"u_interest",formatNumericAmount($objRs->fields["u_interest"]));
                                        $objGrids[4]->setitem(null,"u_surcharge",formatNumericAmount($objRs->fields["u_surcharge"]));
                                        $objGrids[4]->setitem(null,"u_taxbase",formatNumericAmount($objRs->fields["u_taxbase"]));
                                        $objGrids[4]->setitem(null,"u_businessline",$objRs->fields["u_businessline"]);
                                        $objGrids[4]->setitem(null,"u_common",$objRs->fields["u_common"]);
                                        $objGrids[4]->setitem(null,"u_quantity",$objRs->fields["u_quantity"]);
                                        $objGrids[4]->setitem(null,"u_regulatory",$objRs->fields["u_regulatory"]);
                                        $objGrids[4]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
                                        if($objRs->fields["u_regulatory"] == 1){
                                            $firefee+=$objRs->fields["u_amount"];
                                        }
                                        $total+=$objRs->fields["u_amount"];
                                }
                                
                        }
                        
			
                        $firefee = $firefee *.15;
                        $page->setitem("u_asstotal",formatNumericAmount($total));
                        $page->setitem("u_fireasstotal",formatNumericAmount($firefee));
                        
                        
		} elseif ($obju_BPLMDs->getbykey($appdata["u_bpno"])) {
			$page->setitem("u_businessname",$obju_BPLMDs->name);
			$page->setitem("u_lastname",$obju_BPLApps->getudfvalue("u_businessname"));
			$page->setitem("u_firstname",$obju_BPLApps->getudfvalue("u_firstname"));
			$page->setitem("u_middlename",$obju_BPLApps->getudfvalue("u_middlename"));
		}	
	} else if ($appdata["u_businessname"]!="") { //From button create in listing
		$page->setitem("u_businessname",$appdata["u_businessname"]);
		$page->setitem("u_tradename",$appdata["u_businessname"]);
		$page->setitem("u_apptype",$appdata["u_apptype"]);
                
                $total=0;	
                $firefee=0;	
                $objRs->queryopen("select code, name, u_amount,u_regulatory,u_seqno from u_bplfees order by u_seqno asc ");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[4]->addrow();
                        $objGrids[4]->setitem(null,"u_year",date('Y'));
                        $objGrids[4]->setitem(null,"u_feecode",$objRs->fields["code"]);
                        $objGrids[4]->setitem(null,"u_feedesc",$objRs->fields["name"]);
                        $objGrids[4]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                        $objGrids[4]->setitem(null,"u_interest",formatNumericAmount(0));
                        $objGrids[4]->setitem(null,"u_surcharge",formatNumericAmount(0));
                        $objGrids[4]->setitem(null,"u_taxbase",formatNumericAmount(0));
                        $objGrids[4]->setitem(null,"u_common",1);
                        $objGrids[4]->setitem(null,"u_quantity",1);
                        $objGrids[4]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
                        $objGrids[4]->setitem(null,"u_regulatory",$objRs->fields["u_regulatory"]);
                        if($objRs->fields["u_regulatory"] == 1){
                            $firefee+=$objRs->fields["u_amount"];
                        }
                        $total+=$objRs->fields["u_amount"];
                }
                $firefee = $firefee *.15;
                $page->setitem("u_asstotal",formatNumericAmount($total));
                $page->setitem("u_fireasstotal",formatNumericAmount($firefee));
	} else { // New Application
            
            $total=0;	
            $firefee=0;	
            $objRs->queryopen("select code, name, u_amount,u_regulatory,u_seqno from u_bplfees  order by u_seqno asc ");
            while ($objRs->queryfetchrow("NAME")) {
                    $objGrids[4]->addrow();
                    $objGrids[4]->setitem(null,"u_year",date('Y'));
                    $objGrids[4]->setitem(null,"u_feecode",$objRs->fields["code"]);
                    $objGrids[4]->setitem(null,"u_feedesc",$objRs->fields["name"]);
                    $objGrids[4]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                    $objGrids[4]->setitem(null,"u_interest",formatNumericAmount(0));
                    $objGrids[4]->setitem(null,"u_surcharge",formatNumericAmount(0));
                    $objGrids[4]->setitem(null,"u_taxbase",formatNumericAmount(0));
                    $objGrids[4]->setitem(null,"u_common",1);
                    $objGrids[4]->setitem(null,"u_quantity",1);
                    $objGrids[4]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
                    $objGrids[4]->setitem(null,"u_regulatory",$objRs->fields["u_regulatory"]);
                    if($objRs->fields["u_regulatory"] == 1){
                        $firefee+=$objRs->fields["u_amount"];
                    }
                    $total+=$objRs->fields["u_amount"];
            }
            $firefee = $firefee *.15;
            $page->setitem("u_asstotal",formatNumericAmount($total));
            $page->setitem("u_fireasstotal",formatNumericAmount($firefee));
        }	

	
	
	$objGrids[1]->clear();
	$objGrids[2]->clear();
	$objGrids[3]->clear();
//	$objGrids[4]->clear();
	$objGrids[7]->clear();
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select code, name, u_office, u_feecode, u_feedesc, u_chkfee, u_uchkfee from u_bplreqs where u_common=1 and u_reqdtls=1 order by u_seqno asc");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[2]->addrow();
		$objGrids[2]->setitem(null,"u_reqcode",$objRs->fields["code"]);
		$objGrids[2]->setitem(null,"u_reqdesc",$objRs->fields["name"]);
		$objGrids[2]->setitem(null,"u_amount",formatNumericAmount(0));
		$objGrids[2]->setitem(null,"u_office",$objRs->fields["u_office"]);
		$objGrids[2]->setitem(null,"u_feecode",$objRs->fields["u_feecode"]);
		$objGrids[2]->setitem(null,"u_feedesc",$objRs->fields["u_feedesc"]);
		$objGrids[2]->setitem(null,"u_chkfee",$objRs->fields["u_chkfee"]);
		$objGrids[2]->setitem(null,"u_uchkfee",$objRs->fields["u_uchkfee"]);
	}

	$objRs->queryopen("select code, name, u_office, u_feecode, u_feedesc, u_chkfee, u_uchkfee from u_bplreqs where u_common=1 and u_reqdtls=0 order by u_seqno asc");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[1]->addrow();
		$objGrids[1]->setitem(null,"u_reqcode",$objRs->fields["code"]);
		$objGrids[1]->setitem(null,"u_reqdesc",$objRs->fields["name"]);
		$objGrids[1]->setitem(null,"u_feecode",$objRs->fields["u_feecode"]);
		$objGrids[1]->setitem(null,"u_feedesc",$objRs->fields["u_feedesc"]);
		$objGrids[1]->setitem(null,"u_chkfee",$objRs->fields["u_chkfee"]);
		$objGrids[1]->setitem(null,"u_uchkfee",$objRs->fields["u_uchkfee"]);
	}

        
//	$total1=0;	
//	$objRs->queryopen("select code, name, u_amount from u_bplfirefees order by u_seqno asc, name asc");
//	while ($objRs->queryfetchrow("NAME")) {
//		$objGrids[7]->addrow();
//		$objGrids[7]->setitem(null,"u_feecode",$objRs->fields["code"]);
//		$objGrids[7]->setitem(null,"u_feedesc",$objRs->fields["name"]);
//		$objGrids[7]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
//		$objGrids[7]->setitem(null,"u_common",1);
//		$total1+=$objRs->fields["u_amount"];
//	}
//        $page->setitem("fireasstotal",formatNumericAmount($total1));
	
//        $total=0;
//        $objRs->queryopen("select u_feecode, u_feedesc, u_uchkfee from u_bplreqs where u_feecode<>'' order by name asc");
//	while ($objRs->queryfetchrow("NAME")) {
//		$objGrids[3]->addrow();
//		$objGrids[3]->setitem(null,"u_feecode",$objRs->fields["u_feecode"]);
//		$objGrids[3]->setitem(null,"u_feedesc",$objRs->fields["u_feedesc"]);
//		$objGrids[3]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_uchkfee"]));
//		$objGrids[3]->setitem(null,"u_common",3);
//		$total+=$objRs->fields["u_uchkfee"];
//	}
//	$page->setitem("u_asstotal",formatNumericAmount($total));
        
	return true;
}

function onPrepareAddGPSBPLS(&$override) { 
	return true;
}

function onBeforeAddGPSBPLS() { 
	return true;
}

function onAfterAddGPSBPLS() {
        
	return true;
}

function onPrepareEditGPSBPLS(&$override) { 
	return true;
}

function onBeforeEditGPSBPLS() { 
	return true;
}

function onAfterEditGPSBPLS() { 
	global $objConnection;
	global $page;
	global $objGridA;
	global $appdata;
        
        $objGridA->clear();
        $objRs = new recordset(null,$objConnection);
        $objRs->queryopen("SELECT IF(U_PAYMODE='A','Annually',IF(U_PAYMODE='S','Semi - Annually',IF(U_PAYMODE='Q','Quarterly',U_PAYMODE))) as U_PAYMODE,U_YEAR,U_APPREFNO,U_DATE,U_AUTHORIZEDBY,DOCNO  FROM U_BPLUPDGROSS WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND U_ACCOUNTNO='".$page->getitemstring("u_appno")."' ORDER BY DOCNO DESC");
	while ($objRs->queryfetchrow("NAME")) {
		$objGridA->addrow();
		$objGridA->setitem(null,"docno",$objRs->fields["DOCNO"]);
		$objGridA->setitem(null,"u_year",$objRs->fields["U_YEAR"]);
		$objGridA->setitem(null,"u_refno",$objRs->fields["U_APPREFNO"]);
		$objGridA->setitem(null,"u_date",formatDateToHttp($objRs->fields["U_DATE"]));
		$objGridA->setitem(null,"u_paymode",$objRs->fields["U_PAYMODE"]);
		$objGridA->setitem(null,"u_authorizedby",$objRs->fields["U_AUTHORIZEDBY"]);
	}
	return true;
}

function onPrepareUpdateGPSBPLS(&$override) { 
	return true;
}

function onBeforeUpdateGPSBPLS() { 
	return true;
}

function onAfterUpdateGPSBPLS() { 
	global $httpVars;
	//var_dump($httpVars["pf_photodata"]);
	if ($httpVars["pf_photodata"]!="") {
		mkdirr($httpVars["pf_photopath"]);
		$filename = realpath($httpVars["pf_photopath"]) ."\photo.png";
		//var_dump($filename);
		//var_dump($filename);
		$src = $httpVars["pf_photodata"];
		$src  = substr($src, strpos($src, ",") + 1);
		$decoded = base64_decode($src);
		
		$fp = fopen($filename,'wb');
		fwrite($fp, $decoded);
		fclose($fp);
	}
        
	
	return true;
}

function onPrepareDeleteGPSBPLS(&$override) { 
	return true;
}

function onBeforeDeleteGPSBPLS() { 
	return true;
}

function onAfterDeleteGPSBPLS() { 
	return true;
}

resetTabindex();

$objGridA = new grid("T101");

$objGridA->addcolumn("docno");
$objGridA->addcolumn("u_year");
$objGridA->addcolumn("u_refno");
$objGridA->addcolumn("u_date");
$objGridA->addcolumn("u_paymode");
$objGridA->addcolumn("u_authorizedby");
$objGridA->columntitle("docno","Reference No");
$objGridA->columntitle("u_year","Year");
$objGridA->columntitle("u_refno","Application No");
$objGridA->columntitle("u_date","Date");
$objGridA->columntitle("u_paymode","Payment Mode");
$objGridA->columntitle("u_authorizedby","Authorized By");
$objGridA->columnwidth("docno",10);
$objGridA->columnwidth("u_year",10);
$objGridA->columnwidth("u_refno",10);
$objGridA->columnwidth("u_date",11);
$objGridA->columnwidth("u_paymode",8);
$objGridA->columnwidth("u_authorizedby",30);
$objGridA->columnlnkbtn("docno","openupdpays()");
//$objGridA->columnvisibility("docno",false);
$objGridA->dataentry = false;
//$objGridA->automanagecolumnwidth = true;

$page->businessobject->items->settabindex("u_lastname");
$page->businessobject->items->settabindex("u_firstname");
$page->businessobject->items->settabindex("u_middlename");
$page->businessobject->items->settabindex("u_telno");
$page->businessobject->items->settabindex("u_email");
$page->businessobject->items->settabindex("u_owneraddress");
$page->businessobject->items->settabindex("u_tradename");
$page->businessobject->items->settabindex("u_regno");
$page->businessobject->items->settabindex("u_regdate");
$page->businessobject->items->settabindex("u_orgtype");
$page->businessobject->items->settabindex("u_tlastname");
$page->businessobject->items->settabindex("u_businessarea");
$page->businessobject->items->settabindex("u_mempcount");
$page->businessobject->items->settabindex("u_fempcount");
$page->businessobject->items->settabindex("u_emplgucount");


$page->businessobject->items->settabindex("u_bbrgy");
$page->businessobject->items->settabindex("u_bbldgno");
$page->businessobject->items->settabindex("u_bblock");
$page->businessobject->items->settabindex("u_blotno");
$page->businessobject->items->settabindex("u_bstreet");
$page->businessobject->items->settabindex("u_bphaseno");
$page->businessobject->items->settabindex("u_baddressno");
$page->businessobject->items->settabindex("u_btelno");
$page->businessobject->items->settabindex("u_bvillage");
$page->businessobject->items->settabindex("u_bbldgname");
$page->businessobject->items->settabindex("u_bunitno");
$page->businessobject->items->settabindex("u_bfloorno");
$page->businessobject->items->settabindex("u_blandmark");

$page->businessobject->items->settabindex("u_corpname");
$page->businessobject->items->settabindex("u_secregno");
$page->businessobject->items->settabindex("u_secregdate");
$page->businessobject->items->settabindex("u_llastname");
$page->businessobject->items->settabindex("u_monthlyrental");
$page->businessobject->items->settabindex("u_ltelno");
$page->businessobject->items->settabindex("u_lessoraddress");
$page->businessobject->items->settabindex("u_lemail");

$page->businessobject->items->settabindex("u_contactperson");
$page->businessobject->items->settabindex("u_contacttelno");
$page->businessobject->items->settabindex("u_ctcno");
$page->businessobject->items->settabindex("u_tin");

$schema["alreadyrenewed"] = createSchemaUpper("alreadyrenewed");
$schema["billingexist"] = createSchemaUpper("billingexist");
$schema["prevappno"] = createSchemaUpper("prevappno");
$schema["prevappno"]["attributes"] = "disabled";
$schema["regdate"] = createSchemaDate("regdate");
$schema["regdate"]["attributes"] = "disabled";
$schema["lastpayyear"] = createSchemaUpper("lastpayyear");
$schema["lastpayyear"]["attributes"] = "disabled";
$schema["lastpayqtr"] = createSchemaUpper("lastpayqtr");
$schema["lastpayqtr"]["attributes"] = "disabled";

$page->businessobject->items->setcfl("u_appdate","Calendar");
$page->businessobject->items->setcfl("u_releaseddate","Calendar");
$page->businessobject->items->setcfl("u_decisiondate","Calendar");
$page->businessobject->items->setcfl("u_retireddate","Calendar");
$page->businessobject->items->setcfl("u_regdate","Calendar");
$page->businessobject->items->setcfl("u_secregdate","Calendar");

$page->businessobject->items->seteditable("u_apptype",false);
$page->businessobject->items->seteditable("u_approverremarks",false);
$page->businessobject->items->seteditable("u_btaxamount",false);
$page->businessobject->items->seteditable("u_empcount",false);
$page->businessobject->items->seteditable("u_prevappno",false);
$page->businessobject->items->seteditable("u_onhold",false);
$page->businessobject->items->seteditable("u_retired",false);
$page->businessobject->items->seteditable("u_capital",false);
$page->businessobject->items->seteditable("u_nonessential",false);
$page->businessobject->items->seteditable("u_businessname",false);


$page->businessobject->items->setcfl("u_reqappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_pin","OpenCFLfs()");
$page->businessobject->items->setcfl("u_bstreet","OpenCFLfs()");
$page->businessobject->items->setcfl("u_appno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_bbldgname","OpenCFLfs()");
$page->businessobject->items->setcfl("u_bvillage","OpenCFLfs()");

$objGrids[0]->columndataentry("u_year","type","select");
$objGrids[0]->columndataentry("u_year","options","loadenumyear");

$objGrids[4]->columndataentry("u_year","type","select");
$objGrids[4]->columndataentry("u_year","options","loadenumyear");

$objGrids[0]->columnwidth("u_year",8);
$objGrids[0]->columnwidth("u_businessline",30);
$objGrids[0]->columnwidth("u_lastyrgrsales",20);
$objGrids[0]->columnwidth("u_taxclass",50);
$objGrids[0]->columntitle("u_nonessential","Gross Sales");
$objGrids[0]->columntitle("u_lastyrgrsales","Last Yr. Gross/Capital");
$objGrids[0]->columnvisibility("u_unitcount",false);
$objGrids[0]->columnvisibility("u_essential",false);
$objGrids[0]->columnvisibility("u_surcharge",false);
$objGrids[0]->columnvisibility("u_interest",false);
$objGrids[0]->automanagecolumnwidth = false;
//$objGrids[0]->columnvisibility("u_lastyrgrsales",false);
//$objGrids[0]->columntitle("u_btaxamount","Business Tax");

$objGrids[1]->columnwidth("u_check",2);
$objGrids[1]->columnwidth("u_reqdesc",80);
$objGrids[1]->columnwidth("u_remarks",50);
$objGrids[1]->columninput("u_check","type","checkbox");
$objGrids[1]->columninput("u_check","value",1);
$objGrids[1]->columninput("u_remarks","type","text");
$objGrids[1]->columnvisibility("u_reqcode",false);
//$objGrids[1]->columnvisibility("u_remarks",false);
$objGrids[1]->dataentry = false;

$objGrids[1]->automanagecolumnwidth = true;
//$objGrids[1]->width = 520;
//$objGrids[1]->height = 380;

$objGrids[2]->columnwidth("u_check",2);
$objGrids[2]->columnwidth("u_reqdesc",50);
$objGrids[2]->columnwidth("u_office",18);
$objGrids[2]->columnwidth("u_amount",9);
$objGrids[2]->columnwidth("u_payrefno",10);
$objGrids[2]->columnwidth("u_remarks",20);
$objGrids[2]->columninput("u_check","type","checkbox");
$objGrids[2]->columninput("u_check","value",1);
$objGrids[2]->columninput("u_amount","type","text");
$objGrids[2]->columninput("u_issdate","type","text");
$objGrids[2]->columninput("u_payrefno","type","text");
$objGrids[2]->columninput("u_remarks","type","text");
$objGrids[2]->columnvisibility("u_reqcode",false);
$objGrids[2]->columnvisibility("u_amount",false);
$objGrids[2]->columndataentry("u_check","type","label");
//$objGrids[2]->columndataentry("u_amount","type","label");
$objGrids[2]->columndataentry("u_issdate","type","label");
$objGrids[2]->columndataentry("u_payrefno","type","label");
$objGrids[2]->columndataentry("u_remarks","type","label");
$objGrids[2]->columndataentry("u_office","type","label");
$objGrids[2]->columndataentry("u_reqdesc","type","label");
$objGrids[2]->dataentry = false;
//$objGrids[2]->dataentry = true;
$objGrids[2]->showactionbar = false;
$objGrids[2]->setaction("reset",false);
$objGrids[2]->setaction("add",false);
$objGrids[2]->height = 580;

$objGrids[3]->columnwidth("u_check",2);
$objGrids[3]->columnwidth("u_reqdesc",20);
$objGrids[3]->columnwidth("u_office",18);
$objGrids[3]->columnwidth("u_amount",9);
$objGrids[3]->columnwidth("u_payrefno",10);
$objGrids[3]->columnwidth("u_remarks",20);
$objGrids[3]->columninput("u_check","type","checkbox");
$objGrids[3]->columninput("u_check","value",1);
$objGrids[3]->columninput("u_amount","type","text");
$objGrids[3]->columninput("u_issdate","type","text");
$objGrids[3]->columninput("u_payrefno","type","text");
$objGrids[3]->columninput("u_remarks","type","text");
$objGrids[3]->columnvisibility("u_reqcode",false);
$objGrids[3]->dataentry = false;

//$objGrids[1]->automanagecolumnwidth = false;
$objGrids[2]->automanagecolumnwidth = true;
$objGrids[3]->automanagecolumnwidth = false;

$objGrids[4]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[4]->columncfl("u_feedesc","OpenCFLfs()");
$objGrids[4]->columnvisibility("u_quantity",false);
$objGrids[4]->columnvisibility("u_taxbase",false);
$objGrids[4]->columnvisibility("u_businessline",false);
$objGrids[4]->columnvisibility("u_seqno",false);
$objGrids[4]->columnwidth("u_year",8);
$objGrids[4]->columnwidth("u_feecode",8);
$objGrids[4]->columnwidth("u_interest",8);
$objGrids[4]->columnwidth("u_surcharge",8);
$objGrids[4]->columnwidth("u_feedesc",20);
//$objGrids[4]->columnwidth("u_amount",30);
$objGrids[4]->automanagecolumnwidth= false;
$objGrids[4]->width = 620;

$objGrids[7]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[7]->columncfl("u_feedesc","OpenCFLfs()");

$objGrids[5]->columnwidth("u_lastname",20);
$objGrids[5]->columnwidth("u_firstname",20);
$objGrids[5]->columnwidth("u_middlename",20);
$objGrids[5]->columnwidth("u_gender",10);
$objGrids[5]->columnwidth("u_position",20);

$objGrids[6]->columnwidth("u_check",2);
$objGrids[6]->columnwidth("u_reqdesc",40);
$objGrids[6]->columnwidth("u_remarks",25);
$objGrids[6]->columninput("u_check","type","checkbox");
$objGrids[6]->columninput("u_check","value",1);
$objGrids[6]->columninput("u_remarks","type","text");
$objGrids[6]->columnvisibility("u_reqcode",false);
$objGrids[6]->dataentry = false;
$objGrids[6]->width = 520;
$objGrids[6]->height = 280;

$objGrids[8]->dataentry = false;
$objGrids[8]->automanagecolumnwidth=true;
$objGrids[8]->width = 620;
$objGrids[8]->columnwidth("u_amount",10);
$objGrids[8]->columnwidth("u_feecode",10);
$objGrids[8]->columnwidth("u_feedesc",20);

$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
//$page->toolbar->setaction("new",false);

?> 

