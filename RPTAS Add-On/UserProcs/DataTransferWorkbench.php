<?php

require_once ("../common/classes/recordset.php");
require_once ("./utils/taxes.php");

$page->dtw->events->add->onInit("onBusinessObjectOnInitGPSWheeltek");

$page->dtw->events->add->beforeUpload("onBusinessObjectBeforeUploadGPSWheeltek");

$page->dtw->events->add->prepareData("onBusinessObjectPrepareDataGPSWheeltek");

$page->dtw->events->add->setData("onBusinessObjectSetDataGPSWheeltek");

$page->dtw->events->add->beforeAdd("onBusinessObjectBeforeAddGPSWheeltek");
//$page->dtw->events->add->afterAdd("onBusinessObjectAfterAddGPSWheeltek");

function onBusinessObjectOnInitGPSWheeltek($objectcode,$dtw) {
	switch ($objectcode) {
		case "u_customer":
			$dtw->updateifexists = true;
			break;
		case "u_faclass":
			$dtw->updateifexists = true;
			break;
		case "u_serialaging":
			$dtw->updateifexists = true;
			break;
	}		
}

function onBusinessObjectBeforeUploadGPSWheeltek($objectcode,$dtw) {
	switch ($objectcode) {
		case "u_customer":
			$dtw->sheets["Customer"]["obj"]->shareddatatype = "COMPANY";
			break;
		case "u_sales":
			//$dtw->sheets["Sales"]["obj"]->shareddatatype = "COMPANY";
			//$dtw->sheets["Sales Items"]["obj"]->shareddatatype = "COMPANY";
			//$dtw->sheets["Downpayments"]["obj"]->shareddatatype = "COMPANY";
			//$dtw->sheets["Amortization"]["obj"]->shareddatatype = "COMPANY";
			break;
		case "u_payments":
			//$dtw->sheets["Payments"]["obj"]->shareddatatype = "COMPANY";
			break;
		case "u_soldout":
			//$dtw->sheets["Sold Out"]["obj"]->shareddatatype = "COMPANY";
			break;
	}		
}

function onBusinessObjectPrepareDataGPSWheeltek($objectcode,$sheetname,$fieldname,&$value,$obj,$dtw) {
	global $objConnection;

	switch ($objectcode) {
		case "arinvoices":
		case "arcreditmemos":
			switch ($fieldname) {
				case "bpcode":
					if ($value!="" && $obj->trxtype=="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select CODE from u_trxsync where u_brcode='$value'");
						if ($objRs->queryfetchrow("NAME")) {
							$value = $objRs->fields["CODE"] . "-" . $obj->remarks;
							$obj->remarks = "";
						}
					}	
					break;
			}
			break;
	}		
}

function onBusinessObjectSetDataGPSWheeltek($objectcode,$sheetname,$fieldname,$value,$obj,$dtw,$data) {
	global $objConnection;
	$actionReturn = true;

	switch ($objectcode) {
		case "u_customer":
			switch ($fieldname) {
				case "u_bday":
					if ($value=="") {
						$obj->setudfvalue($fieldname,"0000-00-00");
					} elseif (substr($value,0,1)=="#") {
						$obj->setudfvalue($fieldname,"0000-00-00");
					}
					break;
			}		
			break;
		case "u_sales":
			switch ($fieldname) {
				case "u_docdate":
					if ($value=="") {
						$obj->setudfvalue($fieldname,"0000-00-00");
					}
					break;
			}		
			break;
		case "u_salesdp":
			switch ($fieldname) {
				case "u_refdate":
					if ($value=="") {
						$obj->setudfvalue($fieldname,"0000-00-00");
					}
					break;
			}		
			break;
		case "u_payments":
			switch ($fieldname) {
				case "u_rebate":
					if ($value=="") {
						$obj->setudfvalue($fieldname,"0");
					}
					break;
			}		
			break;
		case "u_vault":
			switch ($fieldname) {
				case "u_acctbranch":
					if ($value!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select BRANCHCODE from brancheslist where branchcode='$value'");
						if (!$objRs->queryfetchrow("NAME")) {
							$objRs->queryopen("select CODE from u_trxsync where u_brcode='$value'");
							if ($objRs->queryfetchrow("NAME")) {
								$obj->setudfvalue("u_acctbranch",$objRs->fields["CODE"]);
							} else return raiseError("Invalid Account Branch [$value]");
						}	
					}	
					break;
				case "u_verificationdate":	
				case "u_vaultdate":
				case "u_transmittaldate":
				case "u_drdate":
				case "u_releasedcrdate":
					if ($value!="") $obj->setudfvalue($fieldname,formatDateToDB($value));
					else $obj->setudfvalue($fieldname,"0000-00-00");
					break;
			}
			break;
		case "u_vaultborrow":
			switch ($fieldname) {
				case "u_acctbranch":
				case "u_branchcode":
					if ($value!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select BRANCHCODE from brancheslist where branchcode='$value'");
						if (!$objRs->queryfetchrow("NAME")) {
							$objRs->queryopen("select CODE from u_trxsync where u_brcode='$value'");
							if ($objRs->queryfetchrow("NAME")) {
								$obj->setudfvalue($fieldname,$objRs->fields["CODE"]);
							} else return raiseError("Invalid $fieldname [$value]");
						}	
					}	
					break;
				case "u_requesteddate":	
				case "u_releaseddate":
				case "u_receiveddate":
				case "u_returnduedate":
				case "u_vaultindate":
				case "u_vaultoutdate":
					if ($value!="") $obj->setudfvalue($fieldname,formatDateToDB($value));
					else $obj->setudfvalue($fieldname,"0000-00-00");
					break;
				case "u_returneddate":
					if ($value!="") {
						$obj->setudfvalue("u_type","Return");
						$obj->docstatus = "Returned";
						$obj->setudfvalue($fieldname,formatDateToDB($value));
					} else {
						$obj->setudfvalue("u_type","Request");
						$obj->docstatus = "Released";
						$obj->setudfvalue($fieldname,"0000-00-00");
					}	
					break;	
			}
			break;
		case "u_vaultrelease":
			switch ($fieldname) {
				case "u_acctbranch":
					if ($value!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select BRANCHCODE from brancheslist where branchcode='$value'");
						if (!$objRs->queryfetchrow("NAME")) {
							$objRs->queryopen("select CODE from u_trxsync where u_brcode='$value'");
							if ($objRs->queryfetchrow("NAME")) {
								$obj->setudfvalue($fieldname,$objRs->fields["CODE"]);
							} else return raiseError("Invalid $fieldname [$value]");
						}	
					}	
					break;
				case "u_releaseddate":
				case "u_vaultoutdate":
					if ($value!="") $obj->setudfvalue($fieldname,formatDateToDB($value));
					else $obj->setudfvalue($fieldname,"0000-00-00");
					break;
			}
			break;			
		case "u_serialaging":
			switch ($fieldname) {
				case "u_branch":
					if ($value!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select BRANCHCODE from brancheslist where branchcode='$value'");
						if (!$objRs->queryfetchrow("NAME")) {
							$objRs->queryopen("select CODE from u_trxsync where u_brcode='$value'");
							if ($objRs->queryfetchrow("NAME")) {
								$obj->setudfvalue($fieldname,$objRs->fields["CODE"]);
							} else return raiseError("Invalid $fieldname [$value]");
						}	
					}	
					break;
				case "u_itemcode":
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select ITEMCODE,ITEMDESC from items where ITEMGROUP='101' AND (ITEMCODE='$value' OR ITEMCLASS='$value')");
						if ($objRs->queryfetchrow("NAME")) {
							$obj->setudfvalue($fieldname,$objRs->fields["ITEMCODE"]);
							$obj->name = $objRs->fields["ITEMDESC"];
						} else return raiseError("Invalid $fieldname [$value] for Chassis No.[".$obj->code."]");	
					break;
				case "u_refdate":
					if ($value!="") $obj->setudfvalue($fieldname,formatDateToDB($value));
					break;
			}
			break;	
		case "goodsreceipts":
		case "goodsissues":
			switch ($fieldname) {
				case "branchref":
					if ($value!="") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select BRANCHCODE from brancheslist where branchcode='$value'");
						if (!$objRs->queryfetchrow("NAME")) {
							$objRs->queryopen("select CODE from u_trxsync where u_brcode='$value'");
							if ($objRs->queryfetchrow("NAME")) {
								$obj->branchref = $objRs->fields["CODE"];
							} else return raiseError("Invalid Reference Branch [$value]");
						}	
					}	
					break;
			}
			break;	
		case "goodsreceiptitems":
					//var_dump($fieldname);
			switch ($fieldname) {
				case "itemcode":
					$objItems = new items(null,$objConnection);
					if ($objItems->getbykey($value)) {
						$obj->itemdesc = $objItems->itemdesc;
					} else if ($objItems->getbysql("ITEMCLASS='$value' AND ITEMGROUP='101'")) {
						$obj->itemcode = $objItems->itemcode;
						$obj->itemdesc = $objItems->itemdesc;
					}
					break;
				case "unitprice":
					if ($obj->unitprice==0 && $obj->whscode=="BN") {
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select a.PRICE, b.TAXCODEPU from itempricelists a, items b where b.itemcode=a.itemcode and a.itemcode='$obj->itemcode' and a.pricelist='1' and a.price>0");
						if ($objRs->queryfetchrow("NAME")) {
							$obj->unitprice = $objRs->fields["PRICE"]/floatval(1+(gettaxrate($objRs->fields["TAXCODEPU"])/100));
						//} else return raiseError("Item Code[$obj->itemcode] pricelist not maintained.");
						} else {
							//$obj->price = 1;
							//var_export($data);
							return raiseError("Item Code[$obj->itemcode] have no pricelist maintained.");
						}
					}
					//var_export($data);
					//var_dump($obj->whscode);
					//var_dump($obj->unitprice);
					break;
				case "whscode":
					switch ($value) {
						case "DP":
							if ($dtw->sheets["Goods Receipts"]["obj"]->branchref!=$_SESSION["branch"]) {
								$dtw->sheets["Goods Receipts"]["obj"]->setudfvalue("u_grtype","Deposit Unit- In Trust");
							} else {
								$dtw->sheets["Goods Receipts"]["obj"]->setudfvalue("u_grtype","Deposit Unit- Local");
							}
							break;
						case "RP":
							$dtw->sheets["Goods Receipts"]["obj"]->setudfvalue("u_grtype","Deposit to Repo");
							break;
					}	
					//var_dump($fieldname);
					//var_dump($obj->whscode);
					break;
			}
			break;
		case "u_itemcosts":
			switch ($fieldname) {
				case "code":
					$dtw->sheets["Item Costs"]["obj"]->setudfvalue("u_itemcode",$dtw->sheets["Item Costs"]["obj"]->code);
					break;
				case "name":
					$dtw->sheets["Item Costs"]["obj"]->setudfvalue("u_itemdesc",$dtw->sheets["Item Costs"]["obj"]->name);
					break;
				case "u_date":
					$dtw->sheets["Item Costs"]["obj"]->code = $value ."-".$dtw->sheets["Item Costs"]["obj"]->code;
					break;
			}
			break;	
	}		
	return $actionReturn;
}


function onBusinessObjectBeforeAddGPSWheeltek($objectcode,$sheetname,$obj,$dtw) {
	global $objConnection;
	global $page;
	$actionReturn = true;

	switch ($objectcode) {
		case "u_customer":
			$obj->setudfvalue("u_country","PH");
			break;
		case "u_payments":
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select U_CUSTNO, U_CUSTNAME, U_ADDRESS from u_sales where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_acctno='".$obj->getudfvalue("u_acctno")."'");
			if ($objRs->queryfetchrow("NAME")) {
				$obj->setudfvalue("u_custno",$objRs->fields["U_CUSTNO"]);
				$obj->setudfvalue("u_custname",$objRs->fields["U_CUSTNAME"]);
				$obj->setudfvalue("u_address",$objRs->fields["U_ADDRESS"]);
			} else return raiseError("Invalid Sales Acct No. [".$obj->getudfvalue("u_acctno")."] for Payments Migration.");
			$obj->setudfvalue("u_doctype","PYMT");
			$obj->setudfvalue("u_accttype","Installment");
			break;
		case "u_sales":
			if ($obj->getudfvalue("u_cancelledposted")=="0") $obj->setudfvalue("u_cancelleddate","0000-00-00");
			if ($obj->getudfvalue("u_returnposted")=="0") $obj->setudfvalue("u_returndate","0000-00-00");
			$obj->setudfvalue("u_changeprice",1);
			if ($obj->getudfvalue("u_saletype")=="Installment") {
				$obj->docstatus = "";
				if ($obj->getudfvalue("u_info3")!="") $obj->docstatus = "Lost";
				
				$objRs = new recordset(NULL,$objConnection);
				$objRs->queryopen("select U_ADDRESS from u_customer where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno='".$obj->getudfvalue("u_custno")."'");
				if ($objRs->queryfetchrow("NAME")) {
					$obj->setudfvalue("u_address",$objRs->fields["U_ADDRESS"]);
				} else return raiseError("Invalid Customer No. [".$obj->getudfvalue("u_custno")."].");
	
				$obj->setudfvalue("u_finamount",floatval($obj->getudfvalue("u_price"))-floatval($obj->getudfvalue("u_dppaid")));
				$obj->setudfvalue("u_pnamount",floatval($obj->getudfvalue("u_mi"))*floatval($obj->getudfvalue("u_term")));
				$obj->setudfvalue("u_tip",floatval($obj->getudfvalue("u_pnamount"))+floatval($obj->getudfvalue("u_dppaid")));
				
				$objAmortization = new documentlinesschema_br(NULL,$objConnection,"u_amortization");
				//$objAmortization->shareddatatype = "COMPANY";
				
				for($i=0;$i<$dtw->sheets["Sales"]["obj"]->userfields["u_term"]["value"];$i++) {
					$dueday = date("d",strtotime($dtw->sheets["Sales"]["obj"]->userfields["u_sop"]["value"]));
					$dateadd = dateadd("m",$i,date("Y-m-01",strtotime($dtw->sheets["Sales"]["obj"]->userfields["u_sop"]["value"])));
					$month = date("m",strtotime($dateadd));
					$year = date("Y",strtotime($dateadd));
					$days = date("t",strtotime($dateadd));
					
					$objAmortization->prepareadd();
					$objAmortization->docid = $dtw->sheets["Sales"]["obj"]->docid;
					$objAmortization->lineid = getNextIdByBranch($objAmortization->dbtable,$objConnection);
					$objAmortization->userfields["u_recno"]["value"] = $i+1;
					$objAmortization->userfields["u_acctno"]["value"] = $dtw->sheets["Sales"]["obj"]->userfields["u_acctno"]["value"];
					$objAmortization->userfields["u_duedate"]["value"] = (checkdate($month,$dueday,$year)) ? date("Y-m-d",mktime(0,0,0,$month,$dueday,$year)) : date("Y-m-d",mktime(0,0,0,$month,$days,$year));
					$objAmortization->userfields["u_mi"]["value"] = $dtw->sheets["Sales"]["obj"]->userfields["u_mi"]["value"];
					if($actionReturn) $actionReturn = $objAmortization->add();
					if (!$actionReturn) break;
				}
				
				//return raiseError("here");
				
			}
			
			break;
		case "u_foreclosure":
			$objSales = new documentschema_br(NULL,$objConnection,"u_sales");
			$objSalesactivitylogs = new documentlinesschema_br("u_salesactivitylogs",$objConnection,"u_salesactivitylogs");
			$objPayments = new documentschema_br(NULL,$objConnection,"u_payments");
			$objRs = new recordset(NULL,$objConnection);
			$objRs->queryopen("select DOCNO, U_CUSTNO, U_CUSTNAME, U_ADDRESS from u_sales where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_acctno='".$obj->getudfvalue("u_acctno")."'");
			if ($objRs->queryfetchrow("NAME")) {
				$obj->setudfvalue("u_drno",$objRs->fields["DOCNO"]);
				$obj->setudfvalue("u_custno",$objRs->fields["U_CUSTNO"]);
				$obj->setudfvalue("u_custname",$objRs->fields["U_CUSTNAME"]);
			} else return raiseError("Invalid Sales Acct No. [".$obj->getudfvalue("u_acctno")."] for Foreclosure Migration.");
			
			$obj->setudfvalue("u_acctbranch",$_SESSION["branch"]);
			$obj->setudfvalue("u_info1",'-');
			$obj->setudfvalue("u_info3",'-');
			$obj->setudfvalue("u_info6",'-');
			$obj->setudfvalue("u_repoby",'-');
			$obj->setudfvalue("u_depositno",'-');
			$obj->setudfvalue("u_itemcondition",'Migrated');
			
			//$objSales->shareddatatype = "COMPANY";
			//$objPayments->shareddatatype = "COMPANY";
			if ($objSales->getbysql("U_ACCTNO='" . $obj->getudfvalue("u_acctno") . "'")) {
				if ($obj->getudfvalue("u_repodate")!="0000-00-00") {
					/*
					if (floatval($obj->getudfvalue("u_pnbalance"))!=0) {
						$objPayments->prepareadd();
						$objPayments->docid = getNextIdByBranch("u_payments",$objConnection);
						$objPayments->docseries = getDefaultSeries("u_payments",$objConnection);
						$objPayments->docno = getNextSeriesNoByBranch("u_payments", $objPayments->docseries, "", $objConnection);
						$objPayments->docstatus = "C";
						$objPayments->setudfvalue("u_date",$obj->getudfvalue("u_repodate"));
						$objPayments->setudfvalue("u_type","MI");
						if (floatval($obj->getudfvalue("u_pnbalance"))>0) {
							$objPayments->setudfvalue("u_formtype","IN");
							$objPayments->setudfvalue("u_tendered","CM");
						} else {
							$objPayments->setudfvalue("u_formtype","OUT");
							$objPayments->setudfvalue("u_tendered","DM");
						}	
						$objPayments->setudfvalue("u_refno",$obj->getudfvalue("u_repono"));
						$objPayments->setudfvalue("u_acctno",$objSales->getudfvalue("u_acctno"));
						$objPayments->setudfvalue("u_custno",$objSales->getudfvalue("u_custno"));
						$objPayments->setudfvalue("u_custname",$objSales->getudfvalue("u_custname"));
						$objPayments->setudfvalue("u_address",$objSales->getudfvalue("u_address"));
						$objPayments->setudfvalue("u_amount",abs(floatval($obj->getudfvalue("u_pnbalance"))));
						$objPayments->setudfvalue("u_paidamt",$objPayments->getudfvalue("u_amount"));
						$objPayments->setudfvalue("u_address",$objSales->getudfvalue("u_address"));
						$objPayments->setudfvalue("u_doctype","FC");
						$objPayments->setudfvalue("u_accttype","Installment");
						$objPayments->setudfvalue("u_accttype","Foreclosed Account ".$objPayments->getudfvalue("u_tendered")." - Principal");
						$actionReturn = $objPayments->add();
					}
					
					if ($actionReturn && floatval($obj->getudfvalue("u_penaltybalance"))!=0) {
						$objPayments->prepareadd();
						$objPayments->docid = getNextIdByBranch("u_payments",$objConnection);
						$objPayments->docseries = getDefaultSeries("u_payments",$objConnection);
						$objPayments->docno = getNextSeriesNoByBranch("u_payments", $objPayments->docseries, "", $objConnection);
						$objPayments->docstatus = "C";
						$objPayments->setudfvalue("u_date",$obj->getudfvalue("u_repodate"));
						$objPayments->setudfvalue("u_type","PEN");
						if (floatval($obj->getudfvalue("u_penaltybalance"))>0) {
							$objPayments->setudfvalue("u_formtype","IN");
							$objPayments->setudfvalue("u_tendered","CM");
						} else {
							$objPayments->setudfvalue("u_formtype","OUT");
							$objPayments->setudfvalue("u_tendered","DM");
						}	
						$objPayments->setudfvalue("u_refno",$obj->getudfvalue("u_repono"));
						$objPayments->setudfvalue("u_acctno",$objSales->getudfvalue("u_acctno"));
						$objPayments->setudfvalue("u_custno",$objSales->getudfvalue("u_custno"));
						$objPayments->setudfvalue("u_custname",$objSales->getudfvalue("u_custname"));
						$objPayments->setudfvalue("u_address",$objSales->getudfvalue("u_address"));
						$objPayments->setudfvalue("u_amount",abs(floatval($obj->getudfvalue("u_penaltybalance"))));
						$objPayments->setudfvalue("u_paidamt",$objPayments->getudfvalue("u_amount"));
						$objPayments->setudfvalue("u_address",$objSales->getudfvalue("u_address"));
						$objPayments->setudfvalue("u_doctype","FC");
						$objPayments->setudfvalue("u_accttype","Installment");
						$objPayments->setudfvalue("u_accttype","Foreclosed Account ".$objPayments->getudfvalue("u_tendered")." - Penalty");
						$actionReturn = $objPayments->add();
					}					
					*/
				}
				if ($actionReturn) {
					$objSalesactivitylogs->prepareadd();
					$objSalesactivitylogs->docid = $objSales->docid;
					$objSalesactivitylogs->lineid = getNextIdByBranch($objSalesactivitylogs->dbtable,$objConnection);
					
					if ($obj->getudfvalue("u_repodate")=="0000-00-00" && $obj->getudfvalue("u_resumedate")=="0000-00-00") {
						$objSalesactivitylogs->setudfvalue("u_activity","Deposited");
						$objSalesactivitylogs->setudfvalue("u_refno", $obj->getudfvalue("docno"));
						$objSalesactivitylogs->setudfvalue("u_refdate", $obj->getudfvalue("u_date"));
					} elseif ($obj->getudfvalue("u_resumedate")!="0000-00-00") {
						$objSalesactivitylogs->setudfvalue("u_activity","Resumed");
						$objSalesactivitylogs->setudfvalue("u_refno", $obj->getudfvalue("u_resumeslipno"));
						$objSalesactivitylogs->setudfvalue("u_refdate", $obj->getudfvalue("u_resumedate"));
					} elseif ($obj->getudfvalue("u_repodate")!="0000-00-00") {
						$objSalesactivitylogs->setudfvalue("u_activity","Foreclosed");
						$objSalesactivitylogs->setudfvalue("u_refno", $obj->getudfvalue("u_repono"));
						$objSalesactivitylogs->setudfvalue("u_refdate", $obj->getudfvalue("u_repodate"));
					}	
					if ($objSalesactivitylogs->getudfvalue("u_refdate")=="") $objSalesactivitylogs->setudfvalue("u_refdate", "0000-00-00");
					$objSalesactivitylogs->setudfvalue("u_particulars", $obj->getudfvalue("u_reporemarks"));
					if($actionReturn) $actionReturn = $objSalesactivitylogs->add();
				}
				
				if ($actionReturn) {
					
					if ($obj->getudfvalue("u_repodate")=="0000-00-00" && $obj->getudfvalue("u_resumedate")=="0000-00-00") {
						$objSales->docstatus = "Deposited";
						$obj->setudfvalue("u_depostockposted",-1);
					} elseif ($obj->getudfvalue("u_resumedate")!="0000-00-00") {
						$objSales->docstatus = "Resumed";
						$obj->setudfvalue("u_depostockposted",-1);
						$obj->setudfvalue("u_resumestockposted",-1);
					} elseif ($obj->getudfvalue("u_repodate")!="0000-00-00") {
						$objSales->docstatus = "Foreclosed";
						$obj->setudfvalue("u_depostockposted",-1);
						$obj->setudfvalue("u_repostockposted",-1);
					}	
					$actionReturn = $objSales->update($objSales->docno,$objSales->rcdversion);
					
				}	
				
				//if ($actionReturn) return raiseError("here");
			} else return raiseError("Invalid Sales Acct No. [".$obj->getudfvalue("u_acctno")."] for Foreclosure Migration (2nd pass).");
			break;
		case "u_soldout":
			$objSales = new documentschema_br(NULL,$objConnection,"u_sales");
			$objSalesItems = new documentschema_br(NULL,$objConnection,"u_salesitems");
			$objPayments = new documentschema_br(NULL,$objConnection,"u_payments");
			$objRs = new recordset(NULL,$objConnection);
			//$objSales->shareddatatype = "COMPANY";
			//$objSalesItems->shareddatatype = "COMPANY";
			//$objPayments->shareddatatype = "COMPANY";
			/*
			if ($objSales->getbysql("U_ACCTNO='" . $obj->code . "'")) {
				$objRs->queryopen("select sum(if(u_formtype='OUT',u_amount*-1,u_amount)) as PAIDAMOUNT from u_payments where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and u_acctno='".$objSales->getudfvalue("u_acctno")."' and u_type='MI'");
				if ($objRs->queryfetchrow("NAME")) {
					//var_dump(array($objSales->getudfvalue("u_acctno"),$objSales->getudfvalue("u_pnamount"),$objRs->fields["PAIDAMOUNT"]));
					if ($objSales->getudfvalue("u_pnamount")>$objRs->fields["PAIDAMOUNT"]) {
						$objPayments->prepareadd();
						$objPayments->docid = getNextIdByBranch("u_payments",$objConnection);
						$objPayments->docseries = getDefaultSeries("u_payments",$objConnection);
						$objPayments->docno = getNextSeriesNoByBranch("u_payments", $objPayments->docseries, "", $objConnection);
						$objPayments->docstatus = "C";
						$objPayments->setudfvalue("u_date",$obj->getudfvalue("u_date"));
						$objPayments->setudfvalue("u_formtype","IN");
						$objPayments->setudfvalue("u_type","MI");
						$objPayments->setudfvalue("u_tendered","CM");
						$objPayments->setudfvalue("u_refno","OLD ACCT NO CM");
						$objPayments->setudfvalue("u_acctno",$objSales->getudfvalue("u_acctno"));
						$objPayments->setudfvalue("u_custno",$objSales->getudfvalue("u_custno"));
						$objPayments->setudfvalue("u_custname",$objSales->getudfvalue("u_custname"));
						$objPayments->setudfvalue("u_address",$objSales->getudfvalue("u_address"));
						$objPayments->setudfvalue("u_amount",$objSales->getudfvalue("u_pnamount")-$objRs->fields["PAIDAMOUNT"]);
						$objPayments->setudfvalue("u_paidamt",$objPayments->getudfvalue("u_amount"));
						$objPayments->setudfvalue("u_address",$objSales->getudfvalue("u_address"));
						$objPayments->setudfvalue("u_doctype","PYMT");
						$objPayments->setudfvalue("u_accttype","Installment");
						$actionReturn = $objPayments->add();
					}
				}
				if ($actionReturn) {
					if ($objSalesItems->getbysql("DOCID='$objSales->docid' AND CHASSISNO<>''")) {
						$objSalesItems->setudfvalue("u_soldto",$obj->getudfvalue("u_acctno"));
						$actionReturn = $objSalesItems->update($objSalesItems->docid,$objSalesItems->lineid,$objSalesItems->rcdversion);
					}
				}	
				if ($actionReturn) {
					$objSales->setudfvalue("u_soldto",$obj->getudfvalue("u_acctno"));
					$actionReturn = $objSales->update($objSales->docno,$objSales->rcdversion);
				}	
				//if ($actionReturn) return raiseError("here");
			} else return raiseError("Invalid Sales Acct No. [".$obj->code."].");
			*/
			break;	
		case "u_vault":
			if ($obj->getudfvalue("u_registeredname")=="") $obj->setudfvalue("u_registeredname",$obj->getudfvalue("u_custname"));
			$obj->setudfvalue("u_registeredname_orig",$obj->getudfvalue("u_registeredname"));
			$obj->setudfvalue("u_acctbranch_orig",$obj->getudfvalue("u_acctbranch"));
			$obj->setudfvalue("u_acctno_orig",$obj->getudfvalue("u_acctno"));
			$obj->setudfvalue("u_drdate_orig",$obj->getudfvalue("u_drdate"));
			$obj->setudfvalue("u_drno_orig",$obj->getudfvalue("u_drno"));
			$obj->setudfvalue("u_custno_orig",$obj->getudfvalue("u_custno"));
			$obj->setudfvalue("u_custname_orig",$obj->getudfvalue("u_custname"));
			break;
		case "u_vaultborrow":
			$obju_Vault = new documentschema_br(NULL,$objConnection,"u_vault");
			setBranch($_SESSION["mainbranch"]);
			if ($obju_Vault->getbysql("U_ACCTNO='".$obj->getudfvalue("u_acctno")."'")) {
				$obj->setudfvalue("u_chassisno",$obju_Vault->getudfvalue("u_chassisno"));
				$obj->setudfvalue("u_color",$obju_Vault->getudfvalue("u_color"));
				$obj->setudfvalue("u_custno",$obju_Vault->getudfvalue("u_custno"));
				$obj->setudfvalue("u_custname",$obju_Vault->getudfvalue("u_custname"));
				$obj->setudfvalue("u_drno",$obju_Vault->getudfvalue("u_drno"));
				$obj->setudfvalue("u_engineno",$obju_Vault->getudfvalue("u_engineno"));
				$obj->setudfvalue("u_stockcode",$obju_Vault->getudfvalue("u_stockcode"));
				//$obj->setudfvalue("u_type",$obju_Vault->getudfvalue("u_chassino"));
				//var_dump("here");
				//var_dump($obj->docstatus);
				if ($obj->docstatus=="Released") {
					$obju_Vault->docstatus = "Borrowed";
					//var_dump($obju_Vault->userfields);
				}	
				$obju_Vault->setudfvalue("u_branchcode",$_SESSION["branch"]);
				$actionReturn = $obju_Vault->update($obju_Vault->docno,$obju_Vault->rcdversion);
			} else $actionReturn = raiseError("Unable to find Vault record for Account No.[".$obj->getudfvalue("u_acctno")."]");
			resetBranch();
			break;
		case "u_vaultrelease":
			$obju_Vault = new documentschema_br(NULL,$objConnection,"u_vault");
			setBranch($_SESSION["mainbranch"]);
			if ($obju_Vault->getbysql("U_ACCTNO='".$obj->getudfvalue("u_acctno")."'")) {
				$obj->setudfvalue("u_chassisno",$obju_Vault->getudfvalue("u_chassisno"));
				$obj->setudfvalue("u_color",$obju_Vault->getudfvalue("u_color"));
				$obj->setudfvalue("u_custno",$obju_Vault->getudfvalue("u_custno"));
				$obj->setudfvalue("u_custname",$obju_Vault->getudfvalue("u_custname"));
				$obj->setudfvalue("u_drno",$obju_Vault->getudfvalue("u_drno"));
				$obj->setudfvalue("u_engineno",$obju_Vault->getudfvalue("u_engineno"));
				$obj->setudfvalue("u_stockcode",$obju_Vault->getudfvalue("u_stockcode"));
				
				$obj->setudfvalue("u_requestedby",$obj->getudfvalue("u_releasedby"));
				$obj->setudfvalue("u_receivedby",$obj->getudfvalue("u_releasedby"));
				$obj->setudfvalue("u_releasedtocustby",$obj->getudfvalue("u_releasedby"));
 	 	 	 	$obj->setudfvalue("u_requesteddate",$obj->getudfvalue("u_releaseddate"));
				$obj->setudfvalue("u_receiveddate",$obj->getudfvalue("u_releaseddate"));
				$obj->setudfvalue("u_releasedtocustdate",$obj->getudfvalue("u_releaseddate"));
				 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 				
				//$obj->setudfvalue("u_type",$obju_Vault->getudfvalue("u_chassino"));
				//var_dump("here");
				$obj->docstatus = "Release-Cust";
				$obju_Vault->docstatus = "Released";
				$obju_Vault->setudfvalue("u_branchcode",$_SESSION["branch"]);
				$obju_Vault->setudfvalue("u_releasedcrdate",$obj->getudfvalue("u_releaseddate"));
				$obju_Vault->setudfvalue("u_releasedby",$obj->getudfvalue("u_releasedby"));
				$actionReturn = $obju_Vault->update($obju_Vault->docno,$obju_Vault->rcdversion);
			} else $actionReturn = raiseError("Unable to find Vault record for Account No.[".$obj->getudfvalue("u_acctno")."]");
			resetBranch();
			break;			
	}		
	//if ($actionReturn) $actionReturn = raiseError("onBusinessObjectBeforeAddGPSWheeltek()");
	return $actionReturn;
}
?> 

