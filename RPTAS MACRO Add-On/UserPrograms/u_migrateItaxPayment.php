<?php
	$progid = "u_migrateitaxpayment";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/masterdatalinesschema.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
//	include_once("./inc/formaccess.php");
        


	$search=false;
	$retired = false;
	$page->restoreSavedValues();
        
        $page->objectcode = "U_MIGRATEITAXPAYMENT";
	$page->paging->formid = "./UDP.php?&objectcode=U_MIGRATEITAXPAYMENT";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Rpta Migrate iTax Payment";
        
        $schema["u_ordatefr"] = createSchemaDate("u_ordatefr");
	$schema["u_ordateto"] = createSchemaDate("u_ordateto");
	$schema["u_orfr"] = createSchemaUpper("u_orfr");
	$schema["u_orto"] = createSchemaUpper("u_orto");
	$schema["u_curtotalamount"] = createSchemaUpper("u_curtotalamount");
	$schema["u_migtotalamount"] = createSchemaUpper("u_migtotalamount");
        
        $schema["u_curtotalamount"]["attributes"] = "disabled";
        $schema["u_migtotalamount"]["attributes"] = "disabled";
        
        $objGridA = new grid("T1",$httpVars);
	$objGridA->addcolumn("u_ornumber");
	$objGridA->addcolumn("u_paidby");
	$objGridA->addcolumn("u_paidamount");
	$objGridA->addcolumn("u_date");
	$objGridA->addcolumn("u_status");
	$objGridA->addcolumn("u_cashierid");
	
	$objGridA->columntitle("u_ornumber","Receipt No");
	$objGridA->columntitle("u_paidby","Paid By");
	$objGridA->columntitle("u_paidamount","Amount Paid");
	$objGridA->columntitle("u_date","Receipt Date");
	$objGridA->columntitle("u_status","Status");
	$objGridA->columntitle("u_cashierid","Cashier Id");
        
	$objGridA->columnwidth("u_ornumber",10);
	$objGridA->columnwidth("u_paidby",16);
	$objGridA->columnwidth("u_paidamount",10);
	$objGridA->columnwidth("u_date",10);
	$objGridA->columnwidth("u_status",10);
	$objGridA->columnwidth("u_cashierid",15);
        
	$objGridA->columnsortable("u_ornumber",true);
	$objGridA->columnsortable("u_paidby",true);
	$objGridA->columnsortable("u_paidamount",true);
	$objGridA->columnsortable("u_date",true);
	$objGridA->columnsortable("u_status",true);
	$objGridA->columnsortable("u_cashierid",true);
        $objGridA->height = 300;
        $objGridA->width = 800;
	$objGridA->automanagecolumnwidth = true;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "ornumber";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGridA->setsort($lookupSortBy,$lookupSortAs);
        
        $objGridB = new grid("T2",$httpVars);
	$objGridB->addcolumn("u_ornumber");
	$objGridB->addcolumn("u_paidby");
	$objGridB->addcolumn("u_paidamount");
	$objGridB->addcolumn("u_date");
	$objGridB->addcolumn("u_status");
	$objGridB->addcolumn("u_cashierid");
	
	$objGridB->columntitle("u_ornumber","Receipt No");
	$objGridB->columntitle("u_paidby","Paid By");
	$objGridB->columntitle("u_paidamount","Amount Paid");
	$objGridB->columntitle("u_date","Receipt Date");
	$objGridB->columntitle("u_status","Status");
	$objGridB->columntitle("u_cashierid","Cashier Id");
        
	$objGridB->columnwidth("u_ornumber",10);
	$objGridB->columnwidth("u_paidby",16);
	$objGridB->columnwidth("u_paidamount",10);
	$objGridB->columnwidth("u_date",10);
	$objGridB->columnwidth("u_status",10);
	$objGridB->columnwidth("u_cashierid",15);
        
	$objGridB->columnsortable("u_ornumber",true);
	$objGridB->columnsortable("u_paidby",true);
	$objGridB->columnsortable("u_paidamount",true);
	$objGridB->columnsortable("u_date",true);
	$objGridB->columnsortable("u_status",true);
	$objGridB->columnsortable("u_cashierid",true);
        $objGridB->height = 300;
        $objGridB->width = 800;

	$objGridB->automanagecolumnwidth = true;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "ornumber";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGridB->setsort($lookupSortBy,$lookupSortAs);
        

        function onFormAction($action) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
                if($action=="migratepayment"){
                    
                        $httpVars = array_merge($_POST,$_GET);
                        $obju_Pos = new documentschema_br(null,$objConnection,"u_lgupos");
                        $obju_PosItems = new documentlinesschema_br(null,$objConnection,"u_lgupositems");

                        $obju_Taxes = new documentschema_br(null,$objConnection,"u_rptaxes");
                        $obju_TaxArps = new documentlinesschema_br(null,$objConnection,"u_rptaxarps");

                        $objConnection->beginwork();

                        $odbccon = @odbc_connect("itax","sysdba","masterkey",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
                        
                        $objRsFees = new recordset(null,$objConnection);
                        $objRsFees->queryopen("select A.U_RPPROPTAX AS U_RPPROPTAXFEECODE, D.NAME AS U_RPPROPTAXFEEDESC, A.U_RPSEF AS U_RPSEFFEECODE, B.NAME AS U_RPSEFFEEDESC, A.U_RPIDLELAND AS U_RPIDLELANDFEECODE, C.NAME AS U_RPIDLELANDFEEDESC, D.U_PENALTYCODE AS U_RPPROPTAXPENALTYFEECODE, E.NAME AS U_RPPROPTAXPENALTYFEEDESC, B.U_PENALTYCODE AS U_RPSEFPENALTYFEECODE, F.NAME AS U_RPSEFPENALTYFEEDESC from U_LGUSETUP A 
                                                                        LEFT JOIN U_LGUFEES B ON B.CODE=A.U_RPSEF
                                                                        LEFT JOIN U_LGUFEES C ON C.CODE=A.U_RPIDLELAND
                                                                        LEFT JOIN U_LGUFEES D ON D.CODE=A.U_RPPROPTAX
                                                                        LEFT JOIN U_LGUFEES E ON E.CODE=D.U_PENALTYCODE
                                                                        LEFT JOIN U_LGUFEES F ON F.CODE=B.U_PENALTYCODE");
                        if (!$objRsFees->queryfetchrow("NAME")) {
                                return raiseError("No setup found for real property tax fees.");
                        }
                        
                        $filterExp1 = "";
                        $filterExp1 = genSQLFilterDate("A.PAYMENTDATE",$filterExp1,$httpVars['df_u_ordatefr'],$httpVars['df_u_ordateto']);
                        $filterExp1 = genSQLFilterString("A.RECEIPTNO",$filterExp1,$httpVars['df_u_orfr'],$httpVars['df_u_orto']);	
                        if ($filterExp1 !="") $filterExp1 = " AND " . $filterExp1;
                      
                        $odbcres = @odbc_exec($odbccon,"SELECT a.PAYMENT_ID, a.LOCAL_TIN, a.PAIDBY, B.OWNERNAME, a.PAYMENTDATE, a.VALUEDATE, a.AMOUNT, a.PAYMODE_CT, a.CHECKNO, a.RECEIPTNO, a.PRINT_BV, a.STATUS_CT, a.REMARK, a.USERID, a.TRANSDATE, a.PAYGROUP_CT, a.ORB_ID, a.DCL_BV, a.RCDNUMBER, a.AFTYPE
                        FROM PAYMENT a LEFT JOIN TAXPAYER B ON A.LOCAL_TIN = B.LOCAL_TIN where PAYMODE_CT='CSH' AND STATUS_CT='SAV' $filterExp1 ORDER BY PAYMENTDATE, RECEIPTNO") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
                       
                        
                      
	while(odbc_fetch_row($odbcres)) {	
		//Build tempory

		for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
		   $field_name = odbc_field_name($odbcres, $j);
		  // $this->temp_fieldnames[$j] = $field_name;
		   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
		}
		//if (!$obju_Pos->getbykey($ar["RECEIPTNO"])) {
			$num_rows++;
			//var_dump($ar["PAYMENT_ID"]);
			$obju_Taxes->prepareadd();
			$obju_Taxes->docseries = -1;
			$obju_Taxes->docno = getNextNoByBranch("u_lgubills",'',$objConnection);
			$obju_Taxes->docid = getNextIDByBranch("u_rptaxes",$objConnection);
			$obju_Taxes->docstatus = "C";
			$obju_Taxes->setudfvalue("u_migrated",1);
			$obju_Taxes->setudfvalue("u_paymode","A");
			$obju_Taxes->setudfvalue("u_tin",$ar["LOCAL_TIN"]);
			$obju_Taxes->setudfvalue("u_declaredowner",$ar["OWNERNAME"]);
			$obju_Taxes->setudfvalue("u_assdate",$ar["PAYMENTDATE"]);
			$obju_Taxes->setudfvalue("u_ornumber",$ar["RECEIPTNO"]);
			$obju_Taxes->setudfvalue("u_ordate",$ar["PAYMENTDATE"]);
			$obju_Taxes->setudfvalue("u_paidamount",$ar["AMOUNT"]);
			
			
			$tax=0;
			$penalty=0;
			$sef=0;
			$sefpenalty=0;
			$taxdisc=0;
			$sefdisc=0;
			$total=0;
                        
			$odbcres3 = @odbc_exec($odbccon,"SELECT a.PAYMENT_ID, a.PROPERTYKIND_CT, c.PINNO, a.TAXTRANS_ID, b.TDNO, a.TAXYEAR, SUM(IIF(a.ITAXTYPE_CT='BSC' AND a.CASETYPE_CT='REG',a.AMOUNT,0)) AS TAX, SUM(IIF(a.ITAXTYPE_CT='BSC' AND a.CASETYPE_CT='PEN',a.AMOUNT,0)) AS TAXPEN, SUM(IIF(a.ITAXTYPE_CT='BSC' AND a.CASETYPE_CT='DED',a.AMOUNT,0)) AS TAXDISC,
                        SUM(IIF(a.ITAXTYPE_CT='SEF' AND a.CASETYPE_CT='REG',a.AMOUNT,0)) AS SEF, SUM(IIF(a.ITAXTYPE_CT='SEF' AND a.CASETYPE_CT='PEN',a.AMOUNT,0)) AS SEFPEN, SUM(IIF(a.ITAXTYPE_CT='SEF' AND a.CASETYPE_CT='DED',a.AMOUNT,0)) AS SEFDISC
                        FROM PAYMENTCLASSDETAIL a LEFT JOIN RPTASSESSMENT b ON b.TAXTRANS_ID=a.TAXTRANS_ID LEFT JOIN PROPERTY c ON c.PROP_ID=b.PROP_ID WHERE a.PAYMENT_ID='".$ar["PAYMENT_ID"]."' GROUP BY PAYMENT_ID, a.PROPERTYKIND_CT, c.PINNO, a.TAXTRANS_ID, b.TDNO, a.TAXYEAR") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
			 while(odbc_fetch_row($odbcres3)) {
				//Build tempory
				for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
				   $field_name = odbc_field_name($odbcres3, $j);
				  // $this->temp_fieldnames[$j] = $field_name;
				   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
				}
				
				$obju_Taxes->setudfvalue("u_yearfrom",$ar3["TAXYEAR"]);
				$obju_Taxes->setudfvalue("u_yearto",$ar3["TAXYEAR"]);
			
				//if ($ar["PAYMENT_ID"]==21297) var_dump( $ar3);
				$obju_TaxArps->prepareadd();
				$obju_TaxArps->docid = $obju_Taxes->docid;
				$obju_TaxArps->lineid = getNextIDByBranch("u_rptaxarps",$objConnection);
				$obju_TaxArps->setudfvalue("u_selected",1);
				$obju_TaxArps->setudfvalue("u_kind",iif($ar3["PROPERTYKIND_CT"]=="L","LAND",iif($ar3["PROPERTYKIND_CT"]=="B","BUILDING","MACHINERY")));
				$obju_TaxArps->setudfvalue("u_pinno",$ar3["PINNO"]);
				$obju_TaxArps->setudfvalue("u_arpno",$ar3["TAXTRANS_ID"]);
				$obju_TaxArps->setudfvalue("u_tdno",$ar3["TDNO"]);
				$obju_TaxArps->setudfvalue("u_assvalue",0);
				$obju_TaxArps->setudfvalue("u_yrfr",$ar3["TAXYEAR"]);
				$obju_TaxArps->setudfvalue("u_yrto",$ar3["TAXYEAR"]);
				$obju_TaxArps->setudfvalue("u_taxdue",$ar3["TAX"]);
				$obju_TaxArps->setudfvalue("u_penalty",$ar3["TAXPEN"]);
				$obju_TaxArps->setudfvalue("u_billdate",$ar["PAYMENTDATE"]);
				$obju_TaxArps->setudfvalue("u_sef",$ar3["SEF"]);
				$obju_TaxArps->setudfvalue("u_sefpenalty",$ar3["SEFPEN"]);
				$obju_TaxArps->setudfvalue("u_discperc",($ar3["TAXDISC"]/$ar3["TAX"])*100);
				$obju_TaxArps->setudfvalue("u_taxdisc",$ar3["TAXDISC"]*-1);
				$obju_TaxArps->setudfvalue("u_sefdisc",$ar3["SEFDISC"]*-1);
				$obju_TaxArps->setudfvalue("u_taxtotal",$ar3["TAX"]+$ar3["TAXDISC"]+$ar3["TAXPEN"]);
				$obju_TaxArps->setudfvalue("u_seftotal",$ar3["SEF"]+$ar3["SEFDISC"]+$ar3["SEFPEN"]);
				$tax+=$ar3["TAX"];
				$penalty+=$ar3["TAXPEN"];
				$sef+=$ar3["SEF"];
				$sefpenalty+=$ar3["SEFPEN"];
				$taxdisc+=$ar3["TAXDISC"]*-1;
				$sefdisc+=$ar3["SEFDISC"]*-1;
				$total+=$ar3["TAX"]-$ar3["TAXDISC"]+$ar3["TAXPEN"]+$ar3["SEF"]+$ar3["SEFDISC"]+$ar3["SEFPEN"];
				$obju_TaxArps->privatedata["header"] = $obju_Taxes;
				$actionReturn = $obju_TaxArps->add();
				if (!$actionReturn) break;			
				
				$obju_Taxes->setudfvalue("u_yearfrom",$ar["TAXYEAR"]);
				$obju_Taxes->setudfvalue("u_yearto",$ar["TAXYEAR"]);
					

				
			}
			if (!$actionReturn) break;			

			$obju_Taxes->setudfvalue("u_tax",$tax);
			$obju_Taxes->setudfvalue("u_seftax",$sef);
			$obju_Taxes->setudfvalue("u_discamount",$taxdisc);
			$obju_Taxes->setudfvalue("u_sefdiscamount",$sefdisc);
			$obju_Taxes->setudfvalue("u_penalty",$penalty);
			$obju_Taxes->setudfvalue("u_sefpenalty",$sefpenalty);
			$obju_Taxes->setudfvalue("u_totaltaxamount",$total);
//			if ($ar["PAYMENT_ID"]==21297) var_dump(array($tax,$penalty,$sef,$sefpenalty,$taxdisc,$sefdisc,$total));
			if ($obju_Taxes->getudfvalue("u_totaltaxamount")!=$total) {
					$actionReturn = raiseError("Mismatch Payment Form Total [".$obju_Taxes->getudfvalue("u_totaltaxamount")."] and Payment details total [".$totalamount."] for Payment ID [".$obju_Pos->docno."].");
			}
			$actionReturn = $obju_Taxes->add();
				
			if (!$actionReturn) break;
			
			
			$obju_Pos->prepareadd();
			$obju_Pos->docseries = -1;
			$obju_Pos->docno = "ITAX:".$ar["PAYMENT_ID"];;
			$obju_Pos->setudfvalue("u_orno",$ar["RECEIPTNO"]);
			$obju_Pos->docid = getNextIDByBranch("u_lgupos",$objConnection);
			$obju_Pos->setudfvalue("u_custno",$ar["LOCAL_TIN"]);
			$obju_Pos->setudfvalue("u_custname",$ar["PAIDBY"]);
			$obju_Pos->setudfvalue("u_status",iif($ar["STATUS_CT"]=="CNL","CN","C"));
			$obju_Pos->setudfvalue("u_date",$ar["PAYMENTDATE"]);
			$obju_Pos->setudfvalue("u_paidamount",$ar["AMOUNT"]);
			$obju_Pos->setudfvalue("u_totalamount",$ar["AMOUNT"]);
			$obju_Pos->setudfvalue("u_totalbefdisc",$ar["AMOUNT"]);
			$obju_Pos->setudfvalue("u_trxtype","S");
			$obju_Pos->setudfvalue("u_cashamount",iif($ar["PAYMODE_CT"]=="CSH",$ar["AMOUNT"],0));
			$obju_Pos->setudfvalue("u_chequeamount",iif($ar["PAYMODE_CT"]!="CSH",$ar["AMOUNT"],0));
			$obju_Pos->setudfvalue("u_collectedcashamount",iif($ar["PAYMODE_CT"]=="CSH",$ar["AMOUNT"],0));
			$obju_Pos->setudfvalue("u_profitcenter","RP");
			$obju_Pos->setudfvalue("u_module","Real Property Tax");
			$obju_Pos->setudfvalue("u_paymode",$obju_Taxes->getudfvalue("u_paymode"));
			if ($obju_Taxes->getudfvalue("u_totaltaxamount")>0) {
				$obju_Pos->setudfvalue("u_billno",$obju_Taxes->docno);
				$obju_Pos->setudfvalue("u_billdate",$obju_Taxes->getudfvalue("u_assdate"));
				$obju_Pos->setudfvalue("u_billduedate",$obju_Taxes->getudfvalue("u_assdate"));
			}	
			$obju_Pos->setudfvalue("u_doccnt",1);
			//$obju_Pos->setudfvalue("u_remark",$ar["REMARK"]);

			$totalamount=0;
			$odbcres3 = @odbc_exec($odbccon,"SELECT a.PAYMENT_ID, a.ITAXTYPE_CT, SUM(a.AMOUNTPAID) AS AMOUNTPAID, SUM(IIF(a.CASETYPE_CT='PEN',0,a.AMOUNTPAID)) AS TAX, SUM(IIF(a.CASETYPE_CT='PEN',a.AMOUNTPAID,0)) AS PEN
FROM PAYMENTDETAIL a WHERE a.PAYMENT_ID='".$ar["PAYMENT_ID"]."' GROUP BY PAYMENT_ID, ITAXTYPE_CT") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
			 while(odbc_fetch_row($odbcres3)) {
			
				//Build tempory
				for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
				   $field_name = odbc_field_name($odbcres3, $j);
				  // $this->temp_fieldnames[$j] = $field_name;
				   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
				}
				
				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="BSC" && $ar3["TAX"]>0) {
					if ($objRsFees->fields["U_RPPROPTAXFEECODE"]=="") return raiseError("No setup found for Real Property Tax.");
					$obju_PosItems->prepareadd();
					$obju_PosItems->docid = $obju_Pos->docid;
					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPPROPTAXFEECODE"]);
					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPPROPTAXFEEDESC"]);
					$obju_PosItems->setudfvalue("u_quantity",1);
					$obju_PosItems->setudfvalue("u_unitprice",$ar3["TAX"]);
					$obju_PosItems->setudfvalue("u_price",$ar3["TAX"]);
					$obju_PosItems->setudfvalue("u_linetotal",$ar3["TAX"]);
					$totalamount+=$ar3["TAX"];
					$obju_PosItems->privatedata["header"] = $obju_Pos;
					$actionReturn = $obju_PosItems->add();
				

                                }	
				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="SEF" && $ar3["TAX"]>0) {
					if ($objRsFees->fields["U_RPSEFFEECODE"]=="") return raiseError("No setup found for SEF.");
					$obju_PosItems->prepareadd();
					$obju_PosItems->docid = $obju_Pos->docid;
					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPSEFFEECODE"]);
					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPSEFFEEDESC"]);
					$obju_PosItems->setudfvalue("u_quantity",1);
					$obju_PosItems->setudfvalue("u_unitprice",$ar3["TAX"]);
					$obju_PosItems->setudfvalue("u_price",$ar3["TAX"]);
					$obju_PosItems->setudfvalue("u_linetotal",$ar3["TAX"]);
					$totalamount+=$ar3["TAX"];
					$obju_PosItems->privatedata["header"] = $obju_Pos;
				
                                    $actionReturn = $obju_PosItems->add();
				}	

				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="BSC" && $ar3["PEN"]>0) {
					if ($objRsFees->fields["U_RPPROPTAXPENALTYFEECODE"]=="") return raiseError("No setup found for Real Property Tax - Penalty.");
					$obju_PosItems->prepareadd();
					$obju_PosItems->docid = $obju_Pos->docid;
					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPPROPTAXPENALTYFEECODE"]);
					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPPROPTAXPENALTYFEEDESC"]);
					$obju_PosItems->setudfvalue("u_quantity",1);
					$obju_PosItems->setudfvalue("u_unitprice",$ar3["PEN"]);
					$obju_PosItems->setudfvalue("u_price",$ar3["PEN"]);
					$obju_PosItems->setudfvalue("u_linetotal",$ar3["PEN"]);
					$totalamount+=$ar3["PEN"];
					$obju_PosItems->privatedata["header"] = $obju_Pos;
					$actionReturn = $obju_PosItems->add();

                                        }	
				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="SEF" && $ar3["PEN"]>0) {
					if ($objRsFees->fields["U_RPSEFPENALTYFEECODE"]=="") return raiseError("No setup found for SEF - Penalty.");
					$obju_PosItems->prepareadd();
					$obju_PosItems->docid = $obju_Pos->docid;
					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPSEFPENALTYFEECODE"]);
					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPSEFPENALTYFEEDESC"]);
					$obju_PosItems->setudfvalue("u_quantity",1);
					$obju_PosItems->setudfvalue("u_unitprice",$ar3["PEN"]);
					$obju_PosItems->setudfvalue("u_price",$ar3["PEN"]);
					$obju_PosItems->setudfvalue("u_linetotal",$ar3["PEN"]);
					$totalamount+=$ar3["PEN"];
					$obju_PosItems->privatedata["header"] = $obju_Pos;
					$actionReturn = $obju_PosItems->add();

                                        }	
				
			}
			if (!$actionReturn) break;			
			
			if ($obju_Pos->getudfvalue("u_totalamount")!=$totalamount) {
					$actionReturn = raiseError("Mismatch Header Total [".$obju_Pos->getudfvalue("u_totalamount")."] and Payment details total [".$totalamount."] for Payment ID [".$obju_Pos->docno."].");
			}
			$actionReturn = $obju_Pos->add();

                        if (!$actionReturn) break;

		//} else {
			//echo $ar["RECEIPTNO"] ."</br>";
                        	 //var_dump($actionReturn);
		}
                
                if ($actionReturn) {
                        $objConnection->commit();
                        var_dump($num_rows);
                } else {
                        $objConnection->rollback();
                        echo $_SESSION["errormessage"];
       
                }
                
                    
                    
                }
                

		return $actionReturn;
	}
		
	require("./inc/formactions.php");
        
        $filterExp = "";
//	
	$filterExp = genSQLFilterDate("A.U_DATE",$filterExp,$httpVars['df_u_ordatefr'],$httpVars['df_u_ordateto']);
	$filterExp = genSQLFilterString("A.DOCNO",$filterExp,$httpVars['df_u_orfr'],$httpVars['df_u_orto']);	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
        
	$objrs = new recordset(null,$objConnection);

		$objrs->queryopenext("select A.DOCNO,A.U_CUSTNAME,A.U_PAIDAMOUNT,A.U_DATE,IF(A.U_STATUS='CN','Cancelled','Saved') AS U_STATUS,A.CREATEDBY from U_LGUPOS A  WHERE U_PROFITCENTER = 'RP' AND  A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
 //		//var_dump($objrs->sqls);
		$page->paging_recordcount($objrs->recordcount());
                $curtotal = 0;
		while ($objrs->queryfetchrow("NAME")) {
			
			$objGridA->addrow();
	
			$objGridA->setitem(null,"u_ornumber",$objrs->fields["DOCNO"]);
			$objGridA->setitem(null,"u_paidby",$objrs->fields["U_CUSTNAME"]);
			$objGridA->setitem(null,"u_paidamount",$objrs->fields["U_PAIDAMOUNT"]);
			$objGridA->setitem(null,"u_date",formatDateToHttp($objrs->fields["U_DATE"]));
			$objGridA->setitem(null,"u_status",$objrs->fields["U_STATUS"]);
			$objGridA->setitem(null,"u_cashierid",$objrs->fields["CREATEDBY"]);
			$curtotal+= iif($objrs->fields["U_STATUS"]=="CN",0,$objrs->fields["U_PAIDAMOUNT"]) ;
			//$objGridA->setkey(null,$objrs->fields["u_ornumber"]); 
		 if (!$page->paging_fetch()) break;
		}
                $page->setitem("u_curtotalamount",formatNumericAmount($curtotal));
//	//}
//	
	resetTabindex();
	setTabindex($schema["u_ordatefr"]);
	setTabindex($schema["u_ordateto"]);
	setTabindex($schema["u_orfr"]);
	setTabindex($schema["u_orto"]);
        
        //ITAX DATA
        
	$odbccon = @odbc_connect("itax","sysdba","masterkey",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
            if($page->getitemstring("u_ordatefr")=="" && $page->getitemstring("u_ordateto")=="" && $page->getitemstring("u_orto")=="" && $page->getitemstring("u_orfr")==""){
//                
            }else{
                
                $filterExp1 = "";
                $filterExp1 = genSQLFilterDate("A.PAYMENTDATE",$filterExp1,$httpVars['df_u_ordatefr'],$httpVars['df_u_ordateto']);
                $filterExp1 = genSQLFilterString("A.RECEIPTNO",$filterExp1,$httpVars['df_u_orfr'],$httpVars['df_u_orto']);	
                if ($filterExp1 !="") $filterExp1 = " AND " . $filterExp1;
//                    var_dump($filterExp1);
                $odbcres = @odbc_exec($odbccon,"SELECT a.PAYMENT_ID, a.LOCAL_TIN, a.PAIDBY, a.PAYMENTDATE, a.VALUEDATE, a.AMOUNT, a.PAYMODE_CT, a.CHECKNO, a.RECEIPTNO, a.PRINT_BV, a.STATUS_CT, a.REMARK, a.USERID, a.TRANSDATE, a.PAYGROUP_CT, a.ORB_ID, a.DCL_BV, a.RCDNUMBER, a.AFTYPE
                FROM PAYMENT a where PAYMODE_CT='CSH' AND STATUS_CT='SAV' $filterExp1 ORDER BY PAYMENTDATE, RECEIPTNO") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
            
            }
            $itaxtotal = 0;
       // $page->paging_recordcount(odbc_num_fields($odbcres));
        while(odbc_fetch_row($odbcres)) {	
		//Build tempory
		for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
		   $field_name = odbc_field_name($odbcres, $j);
		  // $this->temp_fieldnames[$j] = $field_name;
		   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
		}
                    
                        $objGridB->addrow();
			$objGridB->setitem(null,"u_ornumber",$ar["RECEIPTNO"]);
			$objGridB->setitem(null,"u_paidby",$ar["PAIDBY"]);
			$objGridB->setitem(null,"u_paidamount",$ar["AMOUNT"]);
			$objGridB->setitem(null,"u_date",formatDateToHttp($ar["PAYMENTDATE"]));
			$objGridB->setitem(null,"u_status",iif($ar["STATUS_CT"]=="CNL","Cancelled","Saved"));
			$objGridB->setitem(null,"u_cashierid",$ar["USERID"]);
                        $itaxtotal += $ar["AMOUNT"];
                //if (!$page->paging_fetch()) break;  
        }
            $page->setitem("u_migtotalamount",formatNumericAmount($itaxtotal));


       
	
//?>

<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>
</head>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>

<script language="JavaScript">
    function onPageLoad() {
		focusInput(getInput("u_focusinput"));
		//"u_street");
	}

	function onFormSubmit(action) {
            var rc = getTableRowCount("T2");
        
                if(action=="migratepayment"){
                    if(rc==0){
                        page.statusbar.showWarning("No Data found.");
                        return false;
                    }else{
                        if(confirm("Migrate this data?")){
                            return true;
                        }else{
                            return false;
                        } 
                    }
                    
                }    
            
            
	}

	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
				formPageReset(); 
				clearTable("T1");
				clearTable("T2");
				break;
			case "u_ordateto": 
			case "u_ordatefr": 
			case "u_orto": 
			case "u_orfr": 
				formPageReset(); 
				clearTable("T1");
				clearTable("T2");
				break;	
		}
		return true;
	}	
	


	function onPageSaveValues(p_action) {
		var inputs = new Array();
			
			inputs.push("u_ordateto");
			inputs.push("u_ordatefr");
			inputs.push("u_orto");
			inputs.push("u_orfr");
			inputs.push("u_curtotalamount");
			inputs.push("u_migtotalamount");
			
		return inputs;
	}
	
	function formAddNew() {
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		acceptText();
		
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_ordatefr":
			case "u_ordateto":
			case "u_orfr":
			case "u_orto":
			case "u_curtotalamount":
			case "u_migtotalamount":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
                                        var rc2=getTableSelectedRow("T2");
					selectTableRow("T2",rc2+1);
				}
				break;
		}
	}	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("opt") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("getprevarpno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_apptype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Macro Migrate RPT Payments&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td>
</tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
      <div style="border:0px solid gray" >
     
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;<label <?php genCaptionHtml($schema["u_ordatefr"],"") ?>>Date From</label></td>
		<td width="795" align=left>&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema["u_ordatefr"]) ?> /></td>
		<td width="124" ></td>
		<td width="166" align=left>&nbsp;</td>
	</tr>
	<tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_ordateto"],"") ?>>Date To</label></td>
		<td  align=left>&nbsp;<input type="text" size="10" <?php genInputTextHtml($schema["u_ordateto"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    <tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_orfr"],"") ?>>Or # From</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_orfr"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
   <tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_orto"],"") ?>>Or # To</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_orto"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    <tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_curtotalamount"],"") ?>>Current Data Total Amount</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_curtotalamount"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    <tr><td >&nbsp;<label <?php genCaptionHtml($schema["u_migtotalamount"],"") ?>>iTax Data Total Amount</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["u_migtotalamount"]) ?> /></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    <tr><td >&nbsp;</td>
		<td  align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a> &nbsp;&nbsp;&nbsp; <a class="button" href="" onClick="formSubmit('migratepayment');return false;">Migrate Data</a></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    
	</table>
    </div>
</td>
<td>
</td>
</tr></table>
</td></tr>	

<tr>
    <td>
   <div class="tabber" id="tab1">
		<div class="tabbertab" title="RPT PAYMENTS">
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                        <td ><label class="lblobjs"><b>iTax Data</b></label> <?php $objGridB->draw()?></td>
                        <td>&nbsp;</td>
                        <td><label class="lblobjs"><b>Current Data</b></label> <?php $objGridA->draw()?></td>
                        </tr>
                 
                    </table>  
                </div> 

	</div>
        
    </td>
</tr>
<?php $page->writeRecordLimit(); ?>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>	
	
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess']  . $page->toolbar->generateQueryString() . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>