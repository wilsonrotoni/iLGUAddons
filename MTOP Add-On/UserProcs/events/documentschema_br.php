<?php
 


function onBeforeAddEventdocumentschema_brGPSMTOP($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_mtopapps":
			if ($objTable->getudfvalue("u_franchiseseries")!="-1") {
				$objTable->setudfvalue("u_franchiseno",getNextSeriesNoByBranch("U_MTOPAPPFRANCHISENO",$objTable->getudfvalue("u_franchiseseries"),$objTable->getudfvalue("u_appdate"),$objConnection,true));
				if ($objTable->getudfvalue("u_franchiseno")=="-2") return raiseError("Unable to generate franchise no. Please try submitting the page again.");
			}
			if ($objTable->getudfvalue("u_caseseries")!="-1") {
				$objTable->setudfvalue("u_caseno",getNextSeriesNoByBranch("U_MTOPAPPCASENO",$objTable->getudfvalue("u_caseseries"),$objTable->getudfvalue("u_appdate"),$objConnection,true));
				if ($objTable->getudfvalue("u_caseno")=="-2") return raiseError("Unable to generate case no. Please try submitting the page again.");
			}
			
			if ($objTable->docstatus=="Assessing") $objTable->setudfvalue("u_assessingdatetime",date('Y-m-d h:i:s'));
			$actionReturn = onCustomEventupdatemtopmddocumentschema_brGPSMTOP($objTable);
			break;
	}
	return $actionReturn;
}



function onAddEventdocumentschema_brGPSMTOP($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		
		case "u_mtoppay":
			if ($objTable->docstatus!="D") {
				$actionReturn = onCustomEventcreateformultibilldocumentschema_brGPSMTOP($objTable);
//				if ($actionReturn) $actionReturn = onCustomEventupdatefaasdocumentschema_brGPSRPTAS($objTable);
			}	
			break;
	}
	return $actionReturn;
}



function onBeforeUpdateEventdocumentschema_brGPSMTOP($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_mtopapps":
			if ($objTable->docstatus=="Assessing" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_assessingdatetime",date('Y-m-d h:i:s'));
			} elseif ($objTable->docstatus=="Assessed" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_assesseddatetime",date('Y-m-d h:i:s'));
                              //  $objTable->setudfvalue("u_assessedby",$_SESSION["userid"]);
			} elseif ($objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_approveddatetime",date('Y-m-d h:i:s'));
                               // $objTable->setudfvalue("u_approvedby",$_SESSION["userid"]);
			} elseif ($objTable->docstatus=="Disapproved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_disapproveddatetime",date('Y-m-d h:i:s'));
			} elseif ($objTable->docstatus=="Printing" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
				$objTable->setudfvalue("u_paiddatetime",date('Y-m-d h:i:s'));
			}	

			if ($objTable->docstatus=="Assessing" && ($objTable->fields["DOCSTATUS"]=="Approved" || $objTable->fields["DOCSTATUS"]=="Disapproved")) {
				if ($actionReturn && $objTable->fields["DOCSTATUS"]=="Approved") {
					$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
					if ($objLGUBills->getbysql("U_APPNO='".$objTable->docno."' AND U_PREASSBILL=0 AND U_MODULE='MTOP' AND U_DUEAMOUNT>0")) {
						$actionReturn = $objLGUBills->executesql("DELETE FROM U_LGUBILLITEMS WHERE COMPANY='$objLGUBills->company' AND BRANCH='$objLGUBills->branch' AND DOCID='$objLGUBills->docid'",true);
						if ($actionReturn) $actionReturn = $objLGUBills->delete();
					} else {
						$page->docstatus=$objTable->fields["DOCSTATUS"];
						return raiseError("Unable to find Unpaid Bill Document to remove.");
					}	

				}
			}	
			
			if ($objTable->docstatus=="Approved" && $objTable->fields["DOCSTATUS"]=="Assessed") {
				if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
				if ($actionReturn) $actionReturn = onCustomEventcreatebilldocumentschema_brGPSMTOP($objTable);
				if (!$actionReturn) $page->setitem("docstatus","Assessed");
			} elseif ($objTable->docstatus=="Disapproved" && $objTable->fields["DOCSTATUS"]=="Assessed") {
				if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
			}	
			if ($actionReturn) $actionReturn = onCustomEventupdatemtopmddocumentschema_brGPSMTOP($objTable);
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSMTOP()");
	return $actionReturn;
}

function onCustomEventcreatebilldocumentschema_brGPSMTOP($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
	$ctr=0;
	$div=1;
	/*if ($objTable->getudfvalue("u_paymode")=="S") $div=2;
	elseif ($objTable->getudfvalue("u_paymode")=="Q") $div=4;*/

	$objRsu_LGUSetup = new recordset(null,$objConnection);
	$objRsu_LGUSetup->queryopen("select A.U_MTOPVALIDITY, A.U_MTOPDUEDAY from U_LGUSETUP A");
	if (!$objRsu_LGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for franchise validity and due day.");
	}	
	$objLGUBills->prepareadd();
	$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
	$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
	$objLGUBills->docstatus = "O";
	$objLGUBills->setudfvalue("u_profitcenter","MTOP");
	$objLGUBills->setudfvalue("u_module","MTOP");
	$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
	$objLGUBills->setudfvalue("u_appno",$objTable->docno);
	$objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_franchiseno"));
	if ($objTable->getudfvalue("u_lastname")!="") {
		$objLGUBills->setudfvalue("u_custname",trim($objTable->getudfvalue("u_lastname").", ".$objTable->getudfvalue("u_firstname")." ".$objTable->getudfvalue("u_middlename")));
	} else {
		$objLGUBills->setudfvalue("u_custname",trim($objTable->getudfvalue("u_dlastname").", ".$objTable->getudfvalue("u_dfirstname")." ".$objTable->getudfvalue("u_dmiddlename")));
	}			
	$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
	$startdate = getmonthstartDB($objTable->getudfvalue("u_decisiondate"));
	$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRsu_LGUSetup->fields["U_MTOPDUEDAY"]-1,$startdate));
	//$objLGUBills->setudfvalue("u_duedate",$objLGUBills->getudfvalue("u_docdate"));//dateadd("d",20,$objTable->getudfvalue("u_decisiondate")));
	if (intval($objTable->getudfvalue("u_type"))==0 || intval($objTable->getudfvalue("u_type"))==1) {
		$objTable->setudfvalue("u_expdate",date('Y',strtotime($objLGUBills->getudfvalue("u_docdate")))."-12-31");	
	} else {
		if ($objTable->getudfvalue("u_type")=="NEW") {
			$duedate2 = dateadd("yyyy",$objRsu_LGUSetup->fields["U_MTOPVALIDITY"],$objTable->getudfvalue("u_decisiondate"));
		} elseif ($objTable->getudfvalue("u_type")=="RENEW") {
			$duedate2 = dateadd("yyyy",$objRsu_LGUSetup->fields["U_MTOPVALIDITY"],$objTable->getudfvalue("u_prevexpdate"));
		} else {
			$duedate2 = $objTable->getudfvalue("u_prevexpdate");
		}
		$objTable->setudfvalue("u_expdate",$duedate2);	
	}	
	//	var_dump(array($objLGUBills->getudfvalue("u_docdate"),$objLGUBills->getudfvalue("u_duedate")));
	$objLGUBills->setudfvalue("u_remarks","MTOP - ".date('Y',strtotime($objLGUBills->getudfvalue("u_docdate"))) );
	//$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, IFNULL(B.U_YEARLY,0) AS U_YEARLY, A.U_AMOUNT FROM U_MTOPAPPFEES A LEFT JOIN U_MTOPFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
	$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT FROM U_MTOPAPPFEES A where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
	$totalamount=0;
	while ($objRs->queryfetchrow("NAME")) {
		/*if ($ctr!=1) {
			if ($objRs->fields["U_YEARLY"]!=1) continue;
		}*/	
		//var_dump($objRs->fields);
		$objLGUBillItems->prepareadd();
		$objLGUBillItems->docid = $objLGUBills->docid;
		$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
		$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
		$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
		$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
		$objLGUBillItems->privatedata["header"] = $objLGUBills;
		$actionReturn = $objLGUBillItems->add();
		if (!$actionReturn) break;
		$totalamount+=round($objRs->fields["U_AMOUNT"],2);
	}
	if ($actionReturn) {
		$objLGUBills->setudfvalue("u_totalamount",$totalamount);
		$objLGUBills->setudfvalue("u_dueamount",$totalamount);
		$actionReturn = $objLGUBills->add();
	}

	
/*	
	while (true) {
		$ctr++;
		$objLGUBills->prepareadd();
		$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
		$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
		$objLGUBills->docstatus = "O";
		$objLGUBills->setudfvalue("u_profitcenter","MTOP");
		$objLGUBills->setudfvalue("u_module","MTOP");
		$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
		$objLGUBills->setudfvalue("u_appno",$objTable->docno);
		$objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_franchiseno"));
		if ($objTable->getudfvalue("u_lastname")!="") {
			$objLGUBills->setudfvalue("u_custname",trim($objTable->getudfvalue("u_lastname").", ".$objTable->getudfvalue("u_firstname")." ".$objTable->getudfvalue("u_middlename")));
		} else {
			$objLGUBills->setudfvalue("u_custname",trim($objTable->getudfvalue("u_dlastname").", ".$objTable->getudfvalue("u_dfirstname")." ".$objTable->getudfvalue("u_dmiddlename")));
		}			
		if ($ctr==1) {
			$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
			$objLGUBills->setudfvalue("u_duedate",$objLGUBills->getudfvalue("u_docdate"));//dateadd("d",20,$objTable->getudfvalue("u_decisiondate")));
		} else {	
			$objLGUBills->setudfvalue("u_docdate",dateadd("yyyy",$ctr-1,$objTable->getudfvalue("u_year")."-01-01"));
			$objLGUBills->setudfvalue("u_duedate",dateadd("d",19,$objLGUBills->getudfvalue("u_docdate")));
		}	
		$objTable->setudfvalue("u_expdate",date('Y',strtotime($objLGUBills->getudfvalue("u_docdate")))."-12-31");	
		//	var_dump(array($objLGUBills->getudfvalue("u_docdate"),$objLGUBills->getudfvalue("u_duedate")));
		$objLGUBills->setudfvalue("u_remarks","MTOP - ".date('Y',strtotime($objLGUBills->getudfvalue("u_docdate"))) );
		$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, IFNULL(B.U_YEARLY,0) AS U_YEARLY, A.U_AMOUNT FROM U_MTOPAPPFEES A LEFT JOIN U_MTOPFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
		$totalamount=0;
		while ($objRs->queryfetchrow("NAME")) {
			if ($ctr!=1) {
				if ($objRs->fields["U_YEARLY"]!=1) continue;
			}	
			//var_dump($objRs->fields);
			$objLGUBillItems->prepareadd();
			$objLGUBillItems->docid = $objLGUBills->docid;
			$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
			$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
			$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
			$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
			$objLGUBillItems->privatedata["header"] = $objLGUBills;
			$actionReturn = $objLGUBillItems->add();
			if (!$actionReturn) break;
			$totalamount+=round($objRs->fields["U_AMOUNT"],2);
		}
		if ($actionReturn) {
			$objLGUBills->setudfvalue("u_totalamount",$totalamount);
			$objLGUBills->setudfvalue("u_dueamount",$totalamount);
			$actionReturn = $objLGUBills->add();
		}
		if ($ctr==$objRsu_LGUSetup->fields["U_MTOPVALIDITY"]) break;
	}	
*/
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcreatebilldocumentschema_brGPSMTOP()");

	return $actionReturn;
}

function onCustomEventupdatemtopmddocumentschema_brGPSMTOP($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objMTOPMDs = new masterdataschema_br(null,$objConnection,"u_mtopmds");

	//$objTable->setudfvalue("u_expdate", substr($objTable->getudfvalue("u_appdate"),0,4)."-12-31");
	//$objTable->setudfvalue("u_year", $objTable->getudfvalue("u_year"));

	$actionReturn = $objMTOPMDs->getbykey($objTable->getudfvalue("u_franchiseno"));
	//if (!$actionReturn && $objTable->getudfvalue("u_apptype")=="NEW"){
	if (!$actionReturn){
		$objMTOPMDs->prepareadd();
		$objMTOPMDs->code = $objTable->getudfvalue("u_franchiseno");
	}
	
	$objMTOPMDs->name = $objTable->getudfvalue("u_businessname");
	//$objMTOPMDs->setudfvalue("u_tradename", $objTable->getudfvalue("u_tradename"));
	$objMTOPMDs->setudfvalue("u_lastname", $objTable->getudfvalue("u_lastname"));
	$objMTOPMDs->setudfvalue("u_firstname", $objTable->getudfvalue("u_firstname"));
	$objMTOPMDs->setudfvalue("u_middlename", $objTable->getudfvalue("u_middlename"));
	$objMTOPMDs->setudfvalue("u_apprefno", $objTable->docno);
	$objMTOPMDs->setudfvalue("u_appdate", $objTable->getudfvalue("u_appdate"));
       if ($objTable->fields["U_APPTYPE"]=="RETIRED" && $objMTOPMDs->fields["U_RETIRED"] == 0) {
         
           $objMTOPMDs->setudfvalue("u_retireddate",currentdateDB());
                $objMTOPMDs->setudfvalue("u_retired",1);
	}
	if (intval($objTable->getudfvalue("u_type"))==0 || intval($objTable->getudfvalue("u_type"))==2) {
		$objMTOPMDs->setudfvalue("u_expdate", $objTable->getudfvalue("u_expdate"));
	}
	$objMTOPMDs->setudfvalue("u_year", $objTable->getudfvalue("u_year"));
	if ($objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
		$objMTOPMDs->setudfvalue("u_regdate",currentdateDB());
	}	
	if (intval($objMTOPMDs->getudfvalue("u_firstyear"))==0) {
		if ($objTable->getudfvalue("u_apptype")=="NEW") {
			$objMTOPMDs->setudfvalue("u_firstyear",$objTable->getudfvalue("u_year"));
		} else {
			$objMTOPMDs->setudfvalue("u_firstyear",$objTable->getudfvalue("u_year")-1);
		}
	}

	if ($objMTOPMDs->rowstat=="N") $actionReturn = $objMTOPMDs->add();
	else $actionReturn = $objMTOPMDs->update($objMTOPMDs->code,$objMTOPMDs->rcdversion);
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatemtopmddocumentschema_brGPSMTOP()");
	return $actionReturn;
}
function onCustomEventcreateformultibilldocumentschema_brGPSMTOP($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
	$ctr=0;
	$div=1;
	/*if ($objTable->getudfvalue("u_paymode")=="S") $div=2;
	elseif ($objTable->getudfvalue("u_paymode")=="Q") $div=4;*/

	$objRsu_LGUSetup = new recordset(null,$objConnection);
	$objRsu_LGUSetup->queryopen("select A.U_MTOPVALIDITY, A.U_MTOPDUEDAY from U_LGUSETUP A");
	if (!$objRsu_LGUSetup->queryfetchrow("NAME")) {
		return raiseError("No setup found for franchise validity and due day.");
	}	
	$objLGUBills->prepareadd();
	$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
	$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
	$objLGUBills->docstatus = "O";
	$objLGUBills->setudfvalue("u_profitcenter","MTOP");
	$objLGUBills->setudfvalue("u_module","MTOP");
	$objLGUBills->setudfvalue("u_mtoppays",1);
        $objLGUBills->setudfvalue("u_appno",$objTable->docno);
	$objLGUBills->setudfvalue("u_custno",$objTable->docno);
	$objLGUBills->setudfvalue("u_custname",$objTable->getudfvalue("u_operatorname"));
	$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_assdate"));
	
        $startdate = getmonthstartDB($objTable->getudfvalue("u_assdate"));
	$objLGUBills->setudfvalue("u_duedate",dateadd("d",$objRsu_LGUSetup->fields["U_MTOPDUEDAY"]-1,$startdate));
        $objLGUBills->setudfvalue("u_remarks","MTOP - ".date('Y',strtotime($objLGUBills->getudfvalue("u_docdate"))) );
	//$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, IFNULL(B.U_YEARLY,0) AS U_YEARLY, A.U_AMOUNT FROM U_MTOPAPPFEES A LEFT JOIN U_MTOPFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
//	$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT FROM U_MTOPAPPFEES A where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
	$objRs->queryopen("SELECT U_FEECODE,U_AMOUNT,U_FEEDESC FROM (SELECT b.u_itemcode AS U_FEECODE,sum(b.u_amount) AS U_AMOUNT,b.u_itemdesc AS U_FEEDESC FROM u_lgubills A INNER JOIN  u_lgubillitems b ON a.docid = b.docid AND a.company = b.company AND a.branch = b.branch WHERE a.docno IN(SELECT B.U_BILLNO FROM U_MTOPPAY A INNER JOIN U_MTOPPAYITEMS B ON A.DOCID = B.DOCID WHERE A.DOCNO = '$objTable->docno' AND B.U_SELECTED = 1 ) AND A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND b.u_amount <> 0 GROUP BY b.u_itemcode) AS A");
	$totalamount=0;
        
	while ($objRs->queryfetchrow("NAME")) {
		/*if ($ctr!=1) {
			if ($objRs->fields["U_YEARLY"]!=1) continue;
		}*/	
//		var_dump($objRs->fields);
		$objLGUBillItems->prepareadd();
		$objLGUBillItems->docid = $objLGUBills->docid;
		$objLGUBillItems->lineid = getNextIdByBranch("u_lgubillitems",$objConnection);
		$objLGUBillItems->setudfvalue("u_itemcode",$objRs->fields["U_FEECODE"]);
		$objLGUBillItems->setudfvalue("u_itemdesc",$objRs->fields["U_FEEDESC"]);
		$objLGUBillItems->setudfvalue("u_amount",$objRs->fields["U_AMOUNT"]);
		$objLGUBillItems->privatedata["header"] = $objLGUBills;
		$actionReturn = $objLGUBillItems->add();
		if (!$actionReturn) break;
		$totalamount+=round($objRs->fields["U_AMOUNT"],2);
	}
	if ($actionReturn) {
		$objLGUBills->setudfvalue("u_totalamount",$totalamount);
		$objLGUBills->setudfvalue("u_dueamount",$totalamount);
		$actionReturn = $objLGUBills->add();
                $actionReturn = $objRs->executesql("UPDATE U_LGUBILLS SET DOCSTATUS = 'CN' WHERE DOCNO IN (SELECT B.U_BILLNO FROM U_MTOPPAY A INNER JOIN U_MTOPPAYITEMS B ON A.DOCID = B.DOCID WHERE A.DOCNO = '$objTable->docno' AND B.U_SELECTED = 1)",false);
	}


	return $actionReturn;
}

/*
function onUpdateEventdocumentschema_brGPSMTOP($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_cylindercustobs":
			break;
	}
	return $actionReturn;
}
*/

/*
function onBeforeDeleteEventdocumentschema_brGPSMTOP($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_cylindercustobs":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSMTOP($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_cylindercustobs":
			break;
	}
	return $actionReturn;
}
*/

?>