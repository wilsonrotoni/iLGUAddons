<?php 
	include_once("../common/classes/connections.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./classes/journalvouchers.php");
	include_once("./classes/journalvoucheritems.php");
	include_once("./utils/suppliers.php");
	include_once("./utils/wtaxes.php");
	include_once("./utils/trxlog.php");

	function onAddEventcollectionsGPSHIS($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		if ($actionReturn && $objTable->docstat!="D" && $objTable->cleared==1 && $objTable->collfor=="SI") {
			$actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('inpay',$objTable);
		}
		
		//if ($actionReturn) $actionReturn = raiseError("onAddEventcollectionsGPSHIS");
		return $actionReturn;
	}

	function onUpdateEventcollectionsGPSHIS($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		if ($actionReturn && $objTable->docstat!=$objTable->fields["DOCSTAT"] && $objTable->docstat!="D" && $objTable->cleared!=0 && $objTable->collfor=="SI") {
			$actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('inpay',$objTable);
		} elseif ($actionReturn && $objTable->cleared!=$objTable->fields["CLEARED"] && $objTable->cleared==1 && $objTable->collfor=="SI") {
			$actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('inpay',$objTable);
		}

		//if ($actionReturn) $actionReturn = raiseError("onUpdateEventcollectionsGPSHIS");
		return $actionReturn;
	}
	
	function onDeleteEventcollectionsGPSHIS($objTable) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		if ($actionReturn && $objTable->docstat!="D" && $objTable->cleared==1 && $objTable->collfor=="SI") {
			$actionReturn = onCustomEventcollectionsPostDoctorPayableGPSHIS('inpay',$objTable,true);
		}
		
		return $actionReturn;
	}
	
	function onCustomEventcollectionsPostDoctorPayableGPSHIS($doctype='inpay',$objTable,$delete=false) {
		global $objConnection;
		$actionReturn=true;

		$canpost = true;
		$cutoff = "";
		$objRs = new recordset(null,$objConnection);
		
		$objRs->queryopen("select u_doctorpayablecutoff from u_hissetup");
		if ($objRs->queryfetchrow()) {
			if ($objRs->fields[0]!="") {
				$cutoff = $objRs->fields[0];
				if ($doctype=="inpay" || $doctype=="outpay") {
					if ($objTable->docdate<=$objRs->fields[0]) $canpost = false;
				} else {
					if ($objTable->getudfvalue("u_docdate")<=$objRs->fields[0]) $canpost = false;;
				}	
			}
		}	
			

		if ($doctype=="inpay" || $doctype=="outpay" || $doctype=="bill") {
			$objRs = new recordset(null,$objConnection);
			$objRs->queryopen("select * from u_hishealthins where code='".$objTable->bpcode."' and u_postdoctorpayable=0");
			if ($objRs->queryfetchrow()) {
				return true;
			}	
			if ($objTable->getudfvalue("u_chqexp")=="BAD DEBTS") {
				return true;
			}
		} else {
			if ($objTable->getudfvalue("u_recvamount") + $objTable->getudfvalue("u_checkamount") + $objTable->getudfvalue("u_creditcardamount") - $objTable->getudfvalue("u_chngamount")==0) return true;
		}	
		
		$reverse = false;
		if ($delete) $reverse = true;
		if ($objTable->docstat=="CN" || $objTable->docstat=="BC") $reverse = true;
		$totalqty = 0;
		
		$objRs2 = new recordset(null,$objConnection);
		$objRs3 = new recordset(null,$objConnection);
		$objRs->setdebug();
		if ($doctype=="inpay") {
			$objRs->queryopen("select B.OTHERDOCNO, B.OTHERDOCDATE, B.OTHERDOCDUEDATE, B.OTHERBPREFNO, B.OTHERBPCODE AS U_PATIENTID, B.OTHERBPNAME AS U_PATIENTNAME, B.OTHERBPCODE2 AS U_DOCTORID, B.OTHERBPNAME2 AS U_DOCTORNAME, B.OTHERTRXTYPE AS U_FEETYPE, B.REMARKS, A.REFTYPE, A.REFNO, A.AMOUNT, '' AS JVREFNO from COLLECTIONSINVOICES A INNER JOIN ARINVOICES B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCNO=A.REFNO AND B.OTHERTRXTYPE IN ('Professional Fees','Professional Materials') WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO='".$objTable->docno."' AND A.REFTYPE='ARINVOICE' union all select C.OTHERDOCNO, C.OTHERDOCDATE, C.OTHERDOCDUEDATE, C.OTHERBPREFNO, C.OTHERBPCODE AS U_PATIENTID, C.OTHERBPNAME AS U_PATIENTNAME, B.OTHERBPCODE AS U_DOCTORID, B.OTHERBPNAME AS U_DOCTORNAME, B.OTHERTRXTYPE AS U_FEETYPE, B.REMARKS, A.REFTYPE, A.REFNO, A.AMOUNT, IF (B.REFNO<>'',B.REFNO,B.OTHERREFNO) AS JVREFNO from COLLECTIONSINVOICES A INNER JOIN JOURNALVOUCHERITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCNO=A.REFNO AND  B.OTHERTRXTYPE IN ('Professional Fees','Professional Materials') INNER JOIN JOURNALVOUCHERS C ON C.COMPANY=B.COMPANY AND C.BRANCH=B.BRANCH AND C.DOCID=B.DOCID WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO='".$objTable->docno."' AND A.REFTYPE='JOURNALVOUCHER'");
		} else if ($doctype=="outpay") {
			$objRs->queryopen("select B.OTHERDOCNO, B.OTHERDOCDATE, B.OTHERDOCDUEDATE, B.OTHERBPREFNO, B.OTHERBPCODE AS U_PATIENTID, B.OTHERBPNAME AS U_PATIENTNAME, B.OTHERBPCODE2 AS U_DOCTORID, B.OTHERBPNAME2 AS U_DOCTORNAME, B.OTHERTRXTYPE AS U_FEETYPE, B.REMARKS, A.REFTYPE, A.REFNO, A.AMOUNT, '' AS JVREFNO from PAYMENTINVOICES A INNER JOIN ARINVOICES B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCNO=A.REFNO AND B.OTHERTRXTYPE IN ('Professional Fees','Professional Materials') WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO='".$objTable->docno."' AND A.REFTYPE='ARINVOICE' union all select C.OTHERDOCNO, C.OTHERDOCDATE, C.OTHERDOCDUEDATE, C.OTHERBPREFNO, C.OTHERBPCODE AS U_PATIENTID, C.OTHERBPNAME AS U_PATIENTNAME, B.OTHERBPCODE AS U_DOCTORID, B.OTHERBPNAME AS U_DOCTORNAME, B.OTHERTRXTYPE AS U_FEETYPE, B.REMARKS, A.REFTYPE, A.REFNO, A.AMOUNT, IF (B.REFNO<>'',B.REFNO,B.OTHERREFNO) AS JVREFNO from PAYMENTINVOICES A INNER JOIN JOURNALVOUCHERITEMS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCNO=A.REFNO AND  B.OTHERTRXTYPE IN ('Professional Fees','Professional Materials') INNER JOIN JOURNALVOUCHERS C ON C.COMPANY=B.COMPANY AND C.BRANCH=B.BRANCH AND C.DOCID=B.DOCID WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO='".$objTable->docno."' AND A.REFTYPE='JOURNALVOUCHER'");
		} else if ($doctype=="bill") {
			$objRs->queryopen("select A.DOCNO AS OTHERDOCNO, A.U_STARTDATE AS OTHERDOCDATE, A.U_DOCDATE AS OTHERDOCDUEDATE, A.U_REFNO AS OTHERBPREFNO, A.U_PATIENTID, A.U_PATIENTNAME, B.U_DOCTORID, C.NAME AS U_DOCTORNAME, B.U_FEETYPE, A.U_REMARKS AS REMARKS, 'BILL' AS REFTYPE, '' AS REFNO, B.U_PAIDAMOUNT*-1 AS AMOUNT, '' AS JVREFNO from U_HISBILLS A INNER JOIN U_HISBILLFEES B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND B.U_PAIDAMOUNT<>0 INNER JOIN U_HISDOCTORS C ON C.CODE=B.U_DOCTORID WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO='".$objTable->docno."'");
		} else {
			$objRs->queryopen("select '' as OTHERDOCNO, '' as OTHERDOCDATE, '' as OTHERDOCDUEDATE, '' as OTHERBPREFNO, A.U_PATIENTID, A.U_PATIENTNAME, B.U_DOCTORID, B.U_DOCTORNAME, IF(B.U_ITEMGROUP='PRF','Professional Fees','Professional Materials') as U_FEETYPE, '' as REMARKS,  'POS' as REFTYPE, A.DOCNO as REFNO, SUM(B.U_LINETOTAL) AS AMOUNT, '' AS JVREFNO from U_HISREQUESTS A, U_HISREQUESTITEMS B WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCNO='".$objTable->getudfvalue("u_payrefno")."' GROUP BY B.U_ITEMGROUP, B.U_DOCTORID");
			if ($objRs->recordcount()>1) return raiseError("Only one doctor is allowed for cash sales transaction.");
		}	
		
		while ($objRs->queryfetchrow("NAME")) {
			if (!$canpost) return raiseError("You cannot post doctor's payable within cut-off period [$cutoff]");

			if ($doctype=="pos") {
				$objRs->fields["AMOUNT"] =  $objTable->getudfvalue("u_recvamount") + $objTable->getudfvalue("u_checkamount") + $objTable->getudfvalue("u_creditcardamount") - $objTable->getudfvalue("u_chngamount");
			}
			//var_dump($objRs->fields);
			$supplierdata = getsupplierdata($objRs->fields["U_DOCTORID"],"SUPPNO,SUPPNAME,PAYMENTTERM,WTAXCODE,U_VATABLE");
			if ($supplierdata["SUPPNAME"]=="") return raiseError("Invalid Supplier [".$objRs->fields["U_DOCTORID"]."/".$objRs->fields["U_DOCTORNAME"]."].");
			$wtaxdata = array();
			if ($supplierdata["WTAXCODE"]!="") {
				$wtaxdata = getwtaxdata($supplierdata["WTAXCODE"],"WTAXCODE,WTAXDESC,WTAXCATEGORY,WTAXTYPE,WTAXBASEAMOUNTPERC");
				if ($doctype=='pos' || $doctype=='bill') {
					$wtaxdata["RATE"] = getwtaxrate($supplierdata["WTAXCODE"],$objTable->getudfvalue("u_docdate"));
				} else {
					$wtaxdata["RATE"] = getwtaxrate($supplierdata["WTAXCODE"],$objTable->docdate);
				}	
			}	

			if (!$reverse) {
				if (($objRs->fields["AMOUNT"]>0 && $doctype!="outpay") || ($objRs->fields["AMOUNT"]<0 && $doctype=="outpay")) {
					$objMarketingDocuments = new marketingdocuments(null,$objConnection,"APINVOICES");
					$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"APINVOICEITEMS");
					$objMarketingDocumentWTaxItems = new marketingdocumentwtaxitems(null,$objConnection,"APINVOICEWTAXITEMS");
					$settings = getBusinessObjectSettings("APINVOICE");
				} else {
					$objMarketingDocuments = new marketingdocuments(null,$objConnection,"APCREDITMEMOS");
					$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"APCREDITMEMOITEMS");
					$objMarketingDocumentWTaxItems = new marketingdocumentwtaxitems(null,$objConnection,"APCREDITMEMOWTAXITEMS");
					$settings = getBusinessObjectSettings("APCREDITMEMO");
				}	
			} else {
				if (($objRs->fields["AMOUNT"]>0 && $doctype!="outpay") || ($objRs->fields["AMOUNT"]<0 && $doctype=="outpay")) {
					$objMarketingDocuments = new marketingdocuments(null,$objConnection,"APCREDITMEMOS");
					$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"APCREDITMEMOITEMS");
					$objMarketingDocumentWTaxItems = new marketingdocumentwtaxitems(null,$objConnection,"APCREDITMEMOWTAXITEMS");
					$settings = getBusinessObjectSettings("APCREDITMEMO");
				} else {
					$objMarketingDocuments = new marketingdocuments(null,$objConnection,"APINVOICES");
					$objMarketingDocumentItems = new marketingdocumentitems(null,$objConnection,"APINVOICEITEMS");
					$objMarketingDocumentWTaxItems = new marketingdocumentwtaxitems(null,$objConnection,"APINVOICEWTAXITEMS");
					$settings = getBusinessObjectSettings("APINVOICE");
				}	
			}	
			
			$objMarketingDocuments->prepareadd();
			if (!$reverse) {
				if (($objRs->fields["AMOUNT"]>0 && $doctype!="outpay") || ($objRs->fields["AMOUNT"]<0 && $doctype=="outpay")) {
					$objMarketingDocuments->objectcode = "APINVOICE";
				} else {
					$objMarketingDocuments->objectcode = "APCREDITMEMO";
				}	
			} else {
				if (($objRs->fields["AMOUNT"]>0 && $doctype!="outpay") || ($objRs->fields["AMOUNT"]<0 && $doctype=="outpay")) {
					$objMarketingDocuments->objectcode = "APCREDITMEMO";
				} else {
					$objMarketingDocuments->objectcode = "APINVOICE";
				}	
			}	
			$objMarketingDocuments->sbo_post_flag = $settings["autopost"];
			$objMarketingDocuments->jeposting = $settings["jeposting"];
			if ($objMarketingDocuments->objectcode == "APINVOICE") {
				$objMarketingDocuments->docid = getNextIdByBranch("apinvoices",$objConnection);
			} else {
				$objMarketingDocuments->docid = getNextIdByBranch("apcreditmemos",$objConnection);
			}	
		
			$objMarketingDocuments->docseries = getDefaultSeries($objMarketingDocuments->objectcode,$objConnection);
			$objMarketingDocuments->docno = getNextSeriesNoByBranch($objMarketingDocuments->objectcode,$objMarketingDocuments->docseries,$objMarketingDocuments->docdate,$objConnection);
			$objMarketingDocuments->bpcode = $supplierdata["SUPPNO"];
			$objMarketingDocuments->bpname = $supplierdata["SUPPNAME"];
			$objMarketingDocuments->wtaxcode = $supplierdata["WTAXCODE"];
			$objMarketingDocuments->wtaxcategory = $wtaxdata["WTAXCATEGORY"];
			$objMarketingDocuments->doctype = "S";
			$objMarketingDocuments->currency = $_SESSION["currency"];
			$objMarketingDocuments->currencyrate = 1;

			if ($doctype=="inpay" || $doctype=="outpay") {
				$objMarketingDocuments->docdate = $objTable->docdate;
				$objMarketingDocuments->docduedate = $objTable->docdate;
				$objMarketingDocuments->taxdate = $objTable->docdate;
			} else if ($doctype=="bill") {	
				$objMarketingDocuments->docdate = $objTable->getudfvalue("u_docdate");
				$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_docdate");
				$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_docdate");
			} else {
				$objMarketingDocuments->docdate = $objTable->getudfvalue("u_docdate");
				$objMarketingDocuments->docduedate = $objTable->getudfvalue("u_docdate");
				$objMarketingDocuments->taxdate = $objTable->getudfvalue("u_docdate");
				if ($objMarketingDocuments->objectcode == "APINVOICE") {
					$objTable->setudfvalue("u_apdocno",$objMarketingDocuments->docno);
				} else {
					$objTable->setudfvalue("u_apcmdocno",$objMarketingDocuments->docno);
				}	
			}	
			$objMarketingDocuments->bprefno = $objTable->docno;
			
			$objMarketingDocuments->othertrxtype = $objRs->fields["U_FEETYPE"];
			$objMarketingDocuments->otherdocno = $objRs->fields["OTHERDOCNO"];
			$objMarketingDocuments->otherdocdate = $objRs->fields["OTHERDOCDATE"];
			$objMarketingDocuments->otherdocduedate = $objRs->fields["OTHERDOCDUEDATE"];
			$objMarketingDocuments->otherbprefno = $objRs->fields["OTHERBPREFNO"];
			$objMarketingDocuments->otherbpcode = $objRs->fields["U_PATIENTID"];
			$objMarketingDocuments->otherbpname = $objRs->fields["U_PATIENTNAME"];
			
			if ($objRs->fields["REFTYPE"]=="ARINVOICE") {
			 	if ($doctype=="inpay") {
					$objMarketingDocuments->remarks = "Based on ".iif($delete,"Cancelled ","")."Incoming Payment ".$objTable->docno.". "."Based on A/R Invoice ".$objRs->fields["REFNO"].". ".$objRs->fields["U_PATIENTNAME"].". ".$objRs->fields["U_FEETYPE"];
				} else {
					$objMarketingDocuments->remarks = "Based on ".iif($delete,"Cancelled ","")."Outgoing Payment ".$objTable->docno.". "."Based on A/R Invoice ".$objRs->fields["REFNO"].". ".$objRs->fields["U_PATIENTNAME"].". ".$objRs->fields["U_FEETYPE"];
				}	
			} elseif ($objRs->fields["REFTYPE"]=="JOURNALVOUCHER") {
				$objMarketingDocuments->otherbpcode2 = $objTable->bpcode;
				$objMarketingDocuments->otherbpname2 = $objTable->bpname;
				if ($doctype=="inpay") {
					$objMarketingDocuments->remarks = "Based on ".iif($delete,"Cancelled ","")."Incoming Payment ".$objTable->docno.". ".$objTable->bpname.". "."Based on Journal Voucher ".$objRs->fields["REFNO"].". Based on Document No ".$objRs->fields["JVREFNO"].". ".$objRs->fields["U_PATIENTNAME"].". ".$objRs->fields["U_FEETYPE"];
				} else {
					$objMarketingDocuments->remarks = "Based on ".iif($delete,"Cancelled ","")."Outgoing Payment ".$objTable->docno.". ".$objTable->bpname.". "."Based on Journal Voucher ".$objRs->fields["REFNO"].". Based on Document No ".$objRs->fields["JVREFNO"].". ".$objRs->fields["U_PATIENTNAME"].". ".$objRs->fields["U_FEETYPE"];
				}	
			} elseif ($objRs->fields["REFTYPE"]=="POS") {
				$objMarketingDocuments->remarks = "Based on ".iif($delete,"Cancelled ","")."Cash Sales ".$objTable->docno.". "."Based on Request ".$objRs->fields["REFNO"].". ".$objRs->fields["U_PATIENTNAME"].". ".$objRs->fields["U_FEETYPE"];
			} else  {
				$objMarketingDocuments->remarks = "Based on Cancelled Bill ".$objTable->docno.". ".$objRs->fields["U_PATIENTNAME"].". ".$objRs->fields["U_FEETYPE"];
			}	
			if ($objMarketingDocuments->objectcode == "APINVOICE") {
				$objMarketingDocuments->journalremark = "A/P Invoice - " . $objMarketingDocuments->bpcode;
			} else {
				$objMarketingDocuments->journalremark = "A/P Credit Memo - " . $objMarketingDocuments->bpcode;
			}	
			$objMarketingDocuments->paymentterm = $supplierdata["PAYMENTTERM"];
			
			
			$objMarketingDocumentItems->prepareadd();
			$objMarketingDocumentItems->objectcode = $objMarketingDocuments->objectcode;
			$objMarketingDocumentItems->docid = $objMarketingDocuments->docid;
			$objMarketingDocumentItems->doctype = $objMarketingDocuments->doctype;
			if ($objMarketingDocuments->objectcode == "APINVOICE") {
				$objMarketingDocumentItems->lineid = getNextIdByBranch("apinvoiceitems",$objConnection);
			} else {
				$objMarketingDocumentItems->lineid = getNextIdByBranch("apcreditmemoitems",$objConnection);
			}	
			$objMarketingDocumentItems->glacctno = "2110103";
			$objMarketingDocumentItems->glacctname = "Clearing Service Contract";
			$objMarketingDocumentItems->itemdesc = $objRs->fields["U_FEETYPE"];
			if ($objRs->fields["AMOUNT"]>0) {
				$objMarketingDocumentItems->unitprice = $objRs->fields["AMOUNT"];
			} else {
				$objMarketingDocumentItems->unitprice = $objRs->fields["AMOUNT"]*-1;
			}	
			$objMarketingDocumentItems->price = $objMarketingDocumentItems->unitprice;
			$objMarketingDocumentItems->linetotal = roundAmount($objMarketingDocumentItems->unitprice);
			if ($supplierdata["U_VATABLE"]=="1" && $objRs->fields["U_FEETYPE"]=="Professional Fees") {
				$objMarketingDocumentItems->vatcode = "VATPF";
				$objMarketingDocumentItems->vatrate = gettaxrate($objMarketingDocumentItems->vatcode,$objMarketingDocuments->taxdate);
				$objMarketingDocumentItems->vatamount = $objMarketingDocumentItems->linetotal - roundAmount($objMarketingDocumentItems->linetotal/(1+($objMarketingDocumentItems->vatrate/100)));
			} else {
				$objMarketingDocumentItems->vatcode = "VATIX";
				$objMarketingDocumentItems->vatrate = 0;
				$objMarketingDocumentItems->vatamount = 0;
			}
			$objMarketingDocumentItems->linestatus = "O";
			if ($objMarketingDocuments->wtaxcode!="" && $objRs->fields["U_FEETYPE"]=="Professional Fees") {
			
				$objMarketingDocumentItems->wtaxliable = 1;
			}
			
			$objMarketingDocuments->vatamount += $objMarketingDocumentItems->vatamount;
			$objMarketingDocuments->totalbefdisc += ($objMarketingDocumentItems->linetotal - $objMarketingDocumentItems->vatamount);
			
			$objMarketingDocumentItems->privatedata["header"] = $objMarketingDocuments;
			$actionReturn = $objMarketingDocumentItems->add();
			
			if ($actionReturn) {
				$objMarketingDocuments->totalamount = $objMarketingDocuments->totalbefdisc -  $objMarketingDocuments->discamount + $objMarketingDocuments->vatamount;	
				if ($objMarketingDocuments->wtaxcode!="") {
					$objMarketingDocumentWTaxItems->prepareadd();
					$objMarketingDocumentWTaxItems->docid = $objMarketingDocuments->docid;
					if ($objMarketingDocuments->objectcode == "APINVOICE") {
						$objMarketingDocumentWTaxItems->lineid = getNextIdByBranch("apinvoicewtaxitems",$objConnection);
					} else {
						$objMarketingDocumentWTaxItems->lineid = getNextIdByBranch("apcreditmemowtaxitems",$objConnection);
					}	
					$objMarketingDocumentWTaxItems->wtaxcode = $wtaxdata["WTAXCODE"];
					$objMarketingDocumentWTaxItems->wtaxdesc = $wtaxdata["WTAXDESC"];
					$objMarketingDocumentWTaxItems->wtaxcategory = $wtaxdata["WTAXCATEGORY"];
					$objMarketingDocumentWTaxItems->wtaxtype = $wtaxdata["WTAXTYPE"];
					$objMarketingDocumentWTaxItems->wtaxrate = $wtaxdata["RATE"];
					$objMarketingDocumentWTaxItems->wtaxbaseamountperc = $wtaxdata["WTAXBASEAMOUNTPERC"];
				
					if ($objRs->fields["U_FEETYPE"]=="Professional Fees") {
						if ($objMarketingDocumentWTaxItems->wtaxtype=="N") $objMarketingDocumentWTaxItems->taxableamount = $objMarketingDocuments->totalbefdisc;
						else $objMarketingDocumentWTaxItems->taxableamount = $objMarketingDocuments->totalamount;
						$objMarketingDocumentWTaxItems->wtaxamount = round(($objMarketingDocumentWTaxItems->wtaxrate/100)*($objMarketingDocumentWTaxItems->taxableamount * ($objMarketingDocumentWTaxItems->wtaxbaseamountperc/100)),2);
						$objMarketingDocuments->wtaxamount = $objMarketingDocumentWTaxItems->wtaxamount;
					}	
					if ($objMarketingDocumentWTaxItems->wtaxcategory=="I") {
						$objMarketingDocuments->totalamount -= $objMarketingDocuments->wtaxamount;
						$objMarketingDocuments->wtaxappliedamount = $objMarketingDocuments->wtaxamount;
					}	
					$actionReturn = $objMarketingDocumentWTaxItems->add();
				}
				//var_dump($objMarketingDocuments->wtaxamount);
				if ($actionReturn) {
					$objMarketingDocuments->dueamount = $objMarketingDocuments->totalamount;
					//var_dump($objMarketingDocuments->vatamount);
					//var_dump($objMarketingDocuments->dueamount);
					if ($objMarketingDocuments->dueamount>0) $objMarketingDocuments->docstatus = "O";
					else $objMarketingDocuments->docstatus = "C";
					$actionReturn = $objMarketingDocuments->add();
				}	
			}
			if ($actionReturn) {
				$bpdata["bpcode"] = $objMarketingDocuments->bpcode;		
				$bpdata["parentbpcode"] = $objMarketingDocuments->parentbpcode;				
				$bpdata["parentbptype"] = $objMarketingDocuments->parentbptype;			
				if (!$delete) {
					if ($objMarketingDocuments->objectcode == "APINVOICE") {
						$bpdata["balance"] = $objMarketingDocuments->dueamount *-1;
					} else {
						$bpdata["balance"] = $objMarketingDocuments->dueamount;
					}	
				} else {
					if ($objMarketingDocuments->objectcode == "APINVOICE") {
						$bpdata["balance"] = $objMarketingDocuments->dueamount;
					} else {
						$bpdata["balance"] = $objMarketingDocuments->dueamount *-1;
					}	
				}	
				$actionReturn = updatesupplierbalance($bpdata);
				//var_dump($bpdata);
			}			
			if (!$actionReturn) break;			
		}
						
		//if ($actionReturn) $actionReturn = raiseError("onCustomEventcollectionsPostDoctorPayableGPSHIS()");
		return $actionReturn;
	}	

?>
