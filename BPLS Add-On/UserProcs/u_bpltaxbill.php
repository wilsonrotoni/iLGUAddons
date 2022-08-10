
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
		case "T2":	
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
		case "T3":
			if ($column=="u_businessline") {
				$objRs->queryopen("select name from u_bpllines where code='".$label."'");
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
        
        $page->setitem("u_year",date('Y'));
        $page->setitem("u_assyearto",date('Y'));
	$page->setitem("u_assdate",currentdate()); 
	$page->setitem("docstatus","Encoding");
	$page->setitem("u_apptype","NEW");
	$page->setitem("u_paymode","A");
	$page->setitem("u_envpaymode","A");
        $page->businessobject->items->seteditable("u_acctno",false);
        
	$objRs = new recordset(null,$objConnection);
	$objRs_CntYear = new recordset(null,$objConnection);
	$objRs_BusinessDetails = new recordset(null,$objConnection);
	if ($appdata["u_bpno"]!="") {
		$page->setitem("u_bpno",$appdata["u_bpno"]);
		if ($appdata["u_apptype"]=="RENEW") {
			$page->setitem("u_apptype","RENEW");
                        $page->businessobject->items->seteditable("u_businessname",false);
                        $page->businessobject->items->seteditable("u_appno",false);
//			if (date('m')=="12") {
//				$page->setitem("u_appdate",formatDateToHttp((date("Y")+1)."-01-01"));
//				$page->setitem("docno",getNextSeriesNoByBranch($page->getobjectdoctype(),$page->getitemstring("docseries"),$page->getitemdate("u_appdate"),$objConnection,false));
//			}
		}
                if ($appdata["u_apptype"]=="RETIRED") {
			$page->setitem("u_apptype","RETIRED");
                }
                if ($appdata["u_apptype"]=="ADJUSTMENT") {
			$page->setitem("u_apptype","ADJUSTMENT");
                        $page->businessobject->items->seteditable("u_businessname",false);
                        $page->businessobject->items->seteditable("u_appno",false);
                }
		$obju_BPLApps = new documentschema_br(null,$objConnection,"u_bplapps");
		$obju_BPLMDs = new masterdataschema_br(null,$objConnection,"u_bplmds");
		if ($obju_BPLApps->getbykey($appdata["u_bpno"])) {
                    
                       
                        //FOR UNPAID BILLS
                           
                        $total=0;	
                        $firefee=0;	
       
                        $objRs_BusinessDetails->queryopen("select a.u_empcount,a.u_fempcount,a.u_mempcount,a.docno,a.u_businesschar,a.u_businesskind,a.u_tradename,a.u_appno,a.u_orgtype, case when a.u_orgtype = 'single' then CONCAT(a.U_LASTNAME ,', ',a.U_FIRSTNAME ,' ', a.U_MIDDLENAME) when a.u_orgtype = 'cooperative' then a.u_corpname  when a.u_orgtype = 'corporation' and a.u_firstname = '' and a.u_lastname = '' then a.u_corpname when a.u_orgtype = 'corporation' and a.u_firstname <> '' or a.u_lastname <> '' then CONCAT(a.U_LASTNAME ,'  ',a.U_FIRSTNAME ,' ', a.U_MIDDLENAME) else a.u_corpname end as u_ownername , CONCAT(IF(IFNULL(A.U_BBLOCK,'') = '','',concat('BLK ',A.U_BBLOCK,' ')),IF(IFNULL(A.U_BLOTNO,'') = '','',concat('LOT ',A.U_BLOTNO,' ')), IF(IFNULL(A.U_BPHASENO,'') = '','',concat(A.U_BPHASENO,' ')),IF(IFNULL(A.U_BSTREET,'') = '','',concat(A.U_BSTREET,' ')), IF(IFNULL(A.U_BVILLAGE,'') = '','',concat(A.U_BVILLAGE,' ')),IF(IFNULL(A.U_BBRGY,'') = '','',concat(A.U_BBRGY,' ')),A.U_BCITY,' ',A.U_BPROVINCE) AS u_baddress from u_bplapps  a  where a.docno='".$appdata["u_bpno"]."' and a.company = '$obju_BPLApps->company' and a.branch = '$obju_BPLApps->branch'");
                        if ($objRs_BusinessDetails->queryfetchrow("NAME")) {
                                $page->setitem("u_acctno",$objRs_BusinessDetails->fields["u_appno"]);
                                $page->setitem("u_taxpayername",$objRs_BusinessDetails->fields["u_ownername"]);
                                $page->setitem("u_businessname",$objRs_BusinessDetails->fields["u_tradename"]);
                                $page->setitem("u_owneraddress",$objRs_BusinessDetails->fields["u_baddress"]);
                                $page->setitem("u_orgtype",$objRs_BusinessDetails->fields["u_orgtype"]);
                                $page->setitem("u_businesschar",$objRs_BusinessDetails->fields["u_businesschar"]);
                                $page->setitem("u_businesskind",$objRs_BusinessDetails->fields["u_businesskind"]);
                                $page->setitem("u_prevappno",$objRs_BusinessDetails->fields["docno"]);
                                $page->setitem("u_empcount",$objRs_BusinessDetails->fields["u_empcount"]);
                                $page->setitem("u_fempcount",$objRs_BusinessDetails->fields["u_fempcount"]);
                                $page->setitem("u_mempcount",$objRs_BusinessDetails->fields["u_mempcount"]);

                        }
                        
                        // check lastpaidyear
                        $lastpayyear = 0;
                        $lastpayyearforcurrent = 0;
                        $lastpayqtrtax = 0;
                        $lastpayqtrenvi = 0;
                        $cntpayyear = 0;
                        $objRs_CntYear->queryopen("SELECT COUNT(U_PAYYEAR) U_PAYYEAR FROM (SELECT U_PAYYEAR FROM U_BPLLEDGER WHERE company='$obju_BPLApps->company' and branch='$obju_BPLApps->branch' AND U_ACCTNO = '".$obju_BPLApps->getudfvalue("u_appno")."' AND U_ISCANCELLED <> 1 GROUP BY U_PAYYEAR) AS X");
                            if ($objRs_CntYear->queryfetchrow("NAME")) {
                                 $cntpayyear = $objRs_CntYear->fields["U_PAYYEAR"];
                            }
                        $objRs->queryopen("SELECT MAX(U_PAYYEAR) as U_PAYYEAR FROM U_BPLLEDGER  WHERE company='$obju_BPLApps->company' and branch='$obju_BPLApps->branch' AND U_ACCTNO = '".$obju_BPLApps->getudfvalue("u_appno")."' AND U_ISCANCELLED <> 1");
                        if ($objRs->queryfetchrow("NAME")) {
                            $lastpayyearforcurrent = $objRs->fields["U_PAYYEAR"];
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
                                    $objGrids[1]->addrow();
                                    $objGrids[1]->setitem(null,"u_year",$lastpayyear);
                                    $objGrids[1]->setitem(null,"u_feecode","0001");
                                    $objGrids[1]->setitem(null,"u_feedesc","Business Tax");
                                    $objGrids[1]->setitem(null,"u_amount",formatNumericAmount($annualtaxamount));
                                    $objGrids[1]->setitem(null,"u_interest",formatNumericAmount(0));
                                    $objGrids[1]->setitem(null,"u_surcharge",formatNumericAmount(0));
                                    $objGrids[1]->setitem(null,"u_taxbase",formatNumericAmount($grosssales1));
                                    $objGrids[1]->setitem(null,"u_businessline",$objRs->fields["u_businessline"]);
                                    $objGrids[1]->setitem(null,"u_common",1);
                                    $objGrids[1]->setitem(null,"u_quantity",1);
                                    $objGrids[1]->setitem(null,"u_regulatory",0);
                                    $objGrids[1]->setitem(null,"u_seqno",0);
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
                                    $objGrids[1]->addrow();
                                    $objGrids[1]->setitem(null,"u_year",$lastpayyear);
                                    $objGrids[1]->setitem(null,"u_feecode","0005");
                                    $objGrids[1]->setitem(null,"u_feedesc","Environmental Fee");
                                    $objGrids[1]->setitem(null,"u_amount",formatNumericAmount($totalevni));
                                    $objGrids[1]->setitem(null,"u_interest",formatNumericAmount(0));
                                    $objGrids[1]->setitem(null,"u_surcharge",formatNumericAmount(0));
                                    $objGrids[1]->setitem(null,"u_taxbase",formatNumericAmount(0));
                                    $objGrids[1]->setitem(null,"u_businessline","");
                                    $objGrids[1]->setitem(null,"u_common",1);
                                    $objGrids[1]->setitem(null,"u_quantity",1);
                                    $objGrids[1]->setitem(null,"u_regulatory",1);
                                    $objGrids[1]->setitem(null,"u_seqno",30);
                                    $firefee+=$objRs->fields["u_amount"];
                                    $total+=$annualtaxamount;
                                }
                        }
                        
                        //FOR ADJUSTMENT 
                        
                        if ($appdata["u_apptype"] == "ADJUSTMENT"){
                            $objRs->queryopen("select u_businessline, u_unitcount, u_taxclass, sum(u_capital) as u_capital, sum(u_nonessential) as u_nonessential, u_btaxlinetotal, sum(u_essential) as u_essential from u_bplapplines where company='$obju_BPLApps->company' and branch='$obju_BPLApps->branch' and docid='$obju_BPLApps->docid' group by u_businessline");
                                while ($objRs->queryfetchrow("NAME")) {
                                        $objGrids[0]->addrow();
                                        $objGrids[0]->setitem(null,"u_year",date('Y'));
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
                                
                                $objRs->queryopen(" select u_feecode, u_feedesc, if(xx.u_amount>0,xx.u_amount,a.u_amount) as u_amount, u_interest, u_surcharge,if(xx.u_seqno > 0,xx.u_seqno,a.u_seqno) as u_seqno,u_businessline,u_taxbase, u_common, u_quantity, xx.u_regulatory
                                                    from(
                                                          select u_feecode, u_feedesc, sum(x.u_amount) as u_amount, u_interest, u_surcharge,x.u_seqno as u_seqno,u_businessline,u_taxbase, u_common, u_quantity, sum(x.u_regulatory) u_regulatory
                                                            from (
                                                                    select a.u_feeid as u_feecode, a.u_feedesc, a.u_amountpaid u_amount, 0 as u_interest, 0 as u_surcharge,a.u_rownumber as u_seqno,a.u_businesslineid as u_businessline,a.u_taxbase, 0 as u_common, 1 as u_quantity, 0 as u_regulatory from u_bplledger a where a.company='$obju_BPLApps->company' and a.branch='$obju_BPLApps->branch' and a.u_acctno='".$obju_BPLApps->getudfvalue("u_appno")."' and u_feeid != '0001' AND U_PAYYEAR = '$lastpayyearforcurrent'
                                                                    union all
                                                                    select  code as u_feecode, name u_feedesc, 0 as amount,0 as u_interest,0 as u_surcharge,u_seqno,'' u_businessline,0 as u_taxbase, 0 as u_common, 0 as u_quantity, u_regulatory   from u_bplfees 
                                                            ) as x
                                                            group by x.u_feecode
                                                            order by x.u_seqno
                                                    ) as xx
                                                    left join u_bplfees a on xx.u_feecode = a.code
                                                    ");
                                while ($objRs->queryfetchrow("NAME")) {
                                        $objGrids[1]->addrow();
                                        $objGrids[1]->setitem(null,"u_year",$ctr);
                                        $objGrids[1]->setitem(null,"u_feecode",$objRs->fields["u_feecode"]);
                                        $objGrids[1]->setitem(null,"u_feedesc",$objRs->fields["u_feedesc"]);
                                        $objGrids[1]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                                        $objGrids[1]->setitem(null,"u_interest",formatNumericAmount($objRs->fields["u_interest"]));
                                        $objGrids[1]->setitem(null,"u_surcharge",formatNumericAmount($objRs->fields["u_surcharge"]));
                                        $objGrids[1]->setitem(null,"u_taxbase",formatNumericAmount($objRs->fields["u_taxbase"]));
                                        $objGrids[1]->setitem(null,"u_businessline",$objRs->fields["u_businessline"]);
                                        $objGrids[1]->setitem(null,"u_common",$objRs->fields["u_common"]);
                                        $objGrids[1]->setitem(null,"u_quantity",$objRs->fields["u_quantity"]);
                                        $objGrids[1]->setitem(null,"u_regulatory",$objRs->fields["u_regulatory"]);
                                        $objGrids[1]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
                                        if($objRs->fields["u_regulatory"] == 1){
                                            $firefee+=$objRs->fields["u_amount"];
                                        }
                                        $total+=$objRs->fields["u_amount"];
                                }
                               
                                
                        }
                        
                        //FOR Tax Credit 
                        $objRs->queryopen("SELECT u_year,u_feecode,u_feedesc,u_amount,u_interest,u_surcharge,u_businessline,u_acctno FROM U_BPLTAXCREDITS  WHERE COMPANY='$obju_BPLApps->company' and BRANCH='$obju_BPLApps->branch' AND U_ACCTNO = '".$obju_BPLApps->getudfvalue("u_appno")."' AND U_STATUS = 'O'");
                        while ($objRs->queryfetchrow("NAME")) {
                            $objGrids[2]->addrow();
                            $objGrids[2]->setitem(null,"u_year",$objRs->fields["u_year"]);
                            $objGrids[2]->setitem(null,"u_feecode",$objRs->fields["u_feecode"]);
                            $objGrids[2]->setitem(null,"u_feedesc",$objRs->fields["u_feedesc"]);
                            $objGrids[2]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                            $objGrids[2]->setitem(null,"u_interest",formatNumericAmount($objRs->fields["u_interest"]));
                            $objGrids[2]->setitem(null,"u_surcharge",formatNumericAmount($objRs->fields["u_surcharge"]));
                            $objGrids[2]->setitem(null,"u_businessline",$objRs->fields["u_businessline"]);
                            $objGrids[2]->setitem(null,"u_acctno",$objRs->fields["u_acctno"]);
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
                        $objGrids[1]->addrow();
                        $objGrids[1]->setitem(null,"u_year",date('Y'));
                        $objGrids[1]->setitem(null,"u_feecode",$objRs->fields["code"]);
                        $objGrids[1]->setitem(null,"u_feedesc",$objRs->fields["name"]);
                        $objGrids[1]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                        $objGrids[1]->setitem(null,"u_interest",formatNumericAmount(0));
                        $objGrids[1]->setitem(null,"u_surcharge",formatNumericAmount(0));
                        $objGrids[1]->setitem(null,"u_taxbase",formatNumericAmount(0));
                        $objGrids[1]->setitem(null,"u_common",1);
                        $objGrids[1]->setitem(null,"u_quantity",1);
                        $objGrids[1]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
                        $objGrids[1]->setitem(null,"u_regulatory",$objRs->fields["u_regulatory"]);
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
                    $objGrids[1]->addrow();
                    $objGrids[1]->setitem(null,"u_year",date('Y'));
                    $objGrids[1]->setitem(null,"u_feecode",$objRs->fields["code"]);
                    $objGrids[1]->setitem(null,"u_feedesc",$objRs->fields["name"]);
                    $objGrids[1]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                    $objGrids[1]->setitem(null,"u_interest",formatNumericAmount(0));
                    $objGrids[1]->setitem(null,"u_surcharge",formatNumericAmount(0));
                    $objGrids[1]->setitem(null,"u_taxbase",formatNumericAmount(0));
                    $objGrids[1]->setitem(null,"u_common",1);
                    $objGrids[1]->setitem(null,"u_quantity",1);
                    $objGrids[1]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
                    $objGrids[1]->setitem(null,"u_regulatory",$objRs->fields["u_regulatory"]);
                    if($objRs->fields["u_regulatory"] == 1){
                        $firefee+=$objRs->fields["u_amount"];
                    }
                    $total+=$objRs->fields["u_amount"];
            }
            $firefee = $firefee *.15;
            $page->setitem("u_asstotal",formatNumericAmount($total));
            $page->setitem("u_fireasstotal",formatNumericAmount($firefee));
        }	
        
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
        $objRs->queryopen("SELECT IF(U_PAYMODE='A','Annually',IF(U_PAYMODE='S','Semi - Annually',IF(U_PAYMODE='Q','Quarterly',U_PAYMODE))) as U_PAYMODE,U_YEAR,U_APPREFNO,U_DATE,U_AUTHORIZEDBY,DOCNO  FROM U_BPLUPDGROSS WHERE COMPANY='".$_SESSION["company"]."' AND BRANCH='".$_SESSION["branch"]."' AND U_ACCOUNTNO='".$page->getitemstring("u_acctno")."' ORDER BY DOCNO DESC");
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

$page->businessobject->items->setcfl("u_assdate","Calendar");

$page->businessobject->items->seteditable("u_apptype",false);
$page->businessobject->items->seteditable("u_year",false);
$page->businessobject->items->seteditable("u_btaxamount",false);
$page->businessobject->items->seteditable("u_empcount",false);
$page->businessobject->items->seteditable("u_prevappno",false);
$page->businessobject->items->seteditable("u_capital",false);
$page->businessobject->items->seteditable("u_nonessential",false);
$page->businessobject->items->seteditable("u_businessname",false);
$page->businessobject->items->seteditable("u_fireasstotal",false);
$page->businessobject->items->seteditable("u_asstotal",false);


$page->businessobject->items->setcfl("u_reqappno","OpenCFLfs()");
//$page->businessobject->items->setcfl("u_acctno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_appno","OpenCFLfs()");

$objGrids[0]->columndataentry("u_year","type","select");
$objGrids[0]->columndataentry("u_year","options","loadenumyear");

$objGrids[1]->columndataentry("u_year","type","select");
$objGrids[1]->columndataentry("u_year","options","loadenumyear");

$objGrids[0]->columnwidth("u_year",8);
$objGrids[0]->columnwidth("u_businessline",30);
$objGrids[0]->columnwidth("u_lastyrgrsales",20);
$objGrids[0]->columnwidth("u_taxclass",40);
$objGrids[0]->columntitle("u_nonessential","Gross Sales");
$objGrids[0]->columntitle("u_lastyrgrsales","Last Yr. Gross/Capital");
$objGrids[0]->columnvisibility("u_unitcount",false);
$objGrids[0]->columnvisibility("u_essential",false);
$objGrids[0]->columnvisibility("u_surcharge",false);
$objGrids[0]->columnvisibility("u_interest",false);
$objGrids[0]->automanagecolumnwidth = false;


$objGrids[1]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[1]->columncfl("u_feedesc","OpenCFLfs()");
$objGrids[1]->columnvisibility("u_quantity",false);
$objGrids[1]->columnvisibility("u_taxbase",false);
$objGrids[1]->columnvisibility("u_businessline",false);
$objGrids[1]->columnvisibility("u_seqno",false);
$objGrids[1]->columnwidth("u_year",12);
$objGrids[1]->columnwidth("u_feecode",12);
$objGrids[1]->columnwidth("u_interest",12);
$objGrids[1]->columnwidth("u_surcharge",12);
$objGrids[1]->columnwidth("u_feedesc",25);
//$objGrids[4]->columnwidth("u_amount",30);
$objGrids[1]->automanagecolumnwidth= false;
$objGrids[1]->width = 620;

$objGrids[2]->columnwidth("u_year",12);
$objGrids[2]->columnwidth("u_acctno",12);
$objGrids[2]->columnwidth("u_orrefno",15);
$objGrids[2]->columnwidth("u_feecode",10);
$objGrids[2]->columnwidth("u_interest",12);
$objGrids[2]->columnwidth("u_surcharge",12);
$objGrids[2]->columnwidth("u_feedesc",25);
$objGrids[2]->automanagecolumnwidth= false;
$objGrids[2]->width = 620;
$objGrids[2]->dataentry = false;
$objGrids[2]->showactionbar = false;


$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);
//$page->toolbar->setaction("new",false);

?> 

