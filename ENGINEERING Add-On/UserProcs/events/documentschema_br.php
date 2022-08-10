<?php
 


function onBeforeAddEventdocumentschema_brGPSENGINEERING($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_buildingpermitapps":
			
			
			  //for appno
					if($objTable->getudfvalue("u_appno")=="") {
                           
                                 $prefix = strtoupper("APBUILD");
                            } 
							
							$appdate = $objTable->getudfvalue("u_appdate");
							$appdate2 = str_replace('/','',$appdate);
							$appdate3 = date('Ymd',strtotime($appdate2));
                            $acctno = str_pad(getNextIdByBranch("u_bpappno".$prefix,$objConnection),5,"0",STR_PAD_LEFT);
                        
						     $objTable->setudfvalue("u_appno",$prefix."-".$appdate3."-".$acctno);
			
			if ($objTable->docstatus=="Assessing") $objTable->setudfvalue("u_assessingdatetime",date('Y-m-d h:i:s'));
			$actionReturn = onCustomEventupdatebpmddocumentschema_brGPSENGINEERING($objTable);
			
			break;
	}
	return $actionReturn;
}



function onAddEventdocumentschema_brGPSENGINEERING($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
	case "u_BUILDINGPERMITAPPS":
			if ($objTable->docstatus!="Encoding") {
				//$objTable->setudfvalue("u_expdate",dateadd('yyyy',$objRsu_LGUSetup->fields["U_MTOPVALIDITY"],$objTable->getudfvalue("u_appdate")));
//				if ($actionReturn) $actionReturn = onCustomEventupdatefaasdocumentschema_brGPSRPTAS($objTable);
			}	
			break;
	}
	return $actionReturn;
}



function onBeforeUpdateEventdocumentschema_brGPSENGINEERING($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_buildingpermitapps":
		
		
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
	
				 /***** Re-Assessment*****/
				if  (($objTable->docstatus=="Encoding") ||
                            ($objTable->docstatus=="Encoding" && ($objTable->fields["DOCSTATUS"]=="Approved" || $objTable->fields["DOCSTATUS"]=="Disapproved"))) { 
				
                               if ($objTable->docstatus=="Encoding" && ($objTable->fields["DOCSTATUS"]=="Approved")) {
				if ($actionReturn && $objTable->fields["DOCSTATUS"]=="Approved") {
					$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
					if ($objLGUBills->getbysql("U_APPNO='".$objTable->docno."' AND U_PREASSBILL=0 AND U_MODULE='Building Permit' AND U_DUEAMOUNT>0")) {
						$actionReturn = $objLGUBills->executesql("DELETE FROM U_LGUBILLITEMS WHERE COMPANY='$objLGUBills->company' AND BRANCH='$objLGUBills->branch' AND DOCID='$objLGUBills->docid'",true);
						if ($actionReturn) $actionReturn = $objLGUBills->delete();
					} else {
						$page->docstatus=$objTable->fields["DOCSTATUS"];
						return raiseError("Unable to find Unpaid Bill Document to remove.");
					}	

				}
			}	
							}
			
			if ($objTable->docstatus=="Approved" && $objTable->fields["DOCSTATUS"]=="Assessed") {
				if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
				if ($actionReturn) $actionReturn = onCustomEventcreatebilldocumentschema_brGPSENGINEERING($objTable);
				 
				if (!$actionReturn) $page->setitem("docstatus","Assessed");
			} elseif ($objTable->docstatus=="Disapproved" && $objTable->fields["DOCSTATUS"]=="Assessed") {
				if ($objTable->getudfvalue("u_decisiondate")=="") $objTable->setudfvalue("u_decisiondate",currentdateDB());
			}	
			if ($actionReturn) $actionReturn = onCustomEventupdatebpmddocumentschema_brGPSENGINEERING($objTable);
			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSMTOP()");
	return $actionReturn;
}
	
	


function onCustomEventcreatebilldocumentschema_brGPSENGINEERING($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objLGUBills = new documentschema_br(null,$objConnection,"u_lgubills");
	$objLGUBillItems = new documentlinesschema_br(null,$objConnection,"u_lgubillitems");
	$ctr=0;
	$div=1;
	/*if ($objTable->getudfvalue("u_paymode")=="S") $div=2;
	elseif ($objTable->getudfvalue("u_paymode")=="Q") $div=4;*/


	$objLGUBills->prepareadd();
	$objLGUBills->docno = getNextNoByBranch("u_lgubills","",$objConnection);
	$objLGUBills->docid = getNextIdByBranch("u_lgubills",$objConnection);
	$objLGUBills->docstatus = "O";
	$objLGUBills->setudfvalue("u_profitcenter","Building Permit");
	$objLGUBills->setudfvalue("u_module","Building Permit");
	$objLGUBills->setudfvalue("u_paymode",$objTable->getudfvalue("u_paymode"));
	$objLGUBills->setudfvalue("u_appno",$objTable->docno);
	$objLGUBills->setudfvalue("u_custno",$objTable->getudfvalue("u_appno"));
	if ($objTable->getudfvalue("u_lastname")!="") {
		$objLGUBills->setudfvalue("u_custname",trim($objTable->getudfvalue("u_lastname").", ".$objTable->getudfvalue("u_firstname")." ".$objTable->getudfvalue("u_middlename")));
	} else {
		$objLGUBills->setudfvalue("u_custname",trim($objTable->getudfvalue("u_dlastname").", ".$objTable->getudfvalue("u_dfirstname")." ".$objTable->getudfvalue("u_dmiddlename")));
	}			
	$objLGUBills->setudfvalue("u_docdate",$objTable->getudfvalue("u_decisiondate"));
	$startdate = getmonthstartDB($objTable->getudfvalue("u_decisiondate"));
	 $objLGUBills->setudfvalue("u_duedate",dateadd("d",20,$objTable->getudfvalue("u_decisiondate")));
	//$objLGUBills->setudfvalue("u_duedate",$objLGUBills->getudfvalue("u_docdate"));//dateadd("d",20,$objTable->getudfvalue("u_decisiondate")));
       
	
	$objLGUBills->setudfvalue("u_remarks","Building Permit- ".date('Y',strtotime($objLGUBills->getudfvalue("u_docdate"))) );
	//$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, IFNULL(B.U_YEARLY,0) AS U_YEARLY, A.U_AMOUNT FROM U_MTOPAPPFEES A LEFT JOIN U_MTOPFEES B ON B.CODE=A.U_FEECODE where A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid'");
	$objRs->queryopen("SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT FROM U_BUILDINGPERMITAPPFEES A INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE
                                    WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0 
                                    UNION ALL 
                                    SELECT A.U_FEECODE, A.U_FEEDESC, A.U_AMOUNT  FROM U_BUILDINGREQAPPFEES A INNER JOIN U_LGUFEES B ON B.CODE=A.U_FEECODE
                                    WHERE A.COMPANY='".$_SESSION["company"]."' AND A.BRANCH='".$_SESSION["branch"]."' AND A.DOCID='$objTable->docid' AND  A.U_AMOUNT>0 ");
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

	

	//if ($actionReturn) $actionReturn = raiseError("onCustomEventcreatebilldocumentschema_brGPSMTOP()");
	return $actionReturn;
}

function onCustomEventupdatebpmddocumentschema_brGPSENGINEERING($objTable,$edit=false) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$objBPMDs = new masterdataschema_br(null,$objConnection,"u_buildingpermitmds");
      

	//$objTable->setudfvalue("u_expdate", substr($objTable->getudfvalue("u_appdate"),0,4)."-12-31");
	//$objTable->setudfvalue("u_year", substr($objTable->getudfvalue("u_appdate"),0,4));

	
	$actionReturn = $objBPMDs->getbykey($objTable->getudfvalue("u_appno"));
	//if (!$actionReturn && $objTable->getudfvalue("u_apptype")=="NEW"){
	if (!$actionReturn){
		$objBPMDs->prepareadd();
		$objBPMDs->code = $objTable->getudfvalue("u_appno");
	}
	
	
	$objBPMDs->name = $objTable->getudfvalue("u_firstname");
	//$objBPMDs->setudfvalue("u_ownername", $objTable->getudfvalue("u_tradename"));

	$objBPMDs->setudfvalue("u_ownerlastname", $objTable->getudfvalue("u_lastname"));
	$objBPMDs->setudfvalue("u_ownerfirstname", $objTable->getudfvalue("u_firstname"));
	$objBPMDs->setudfvalue("u_ownermiddlename", $objTable->getudfvalue("u_middlename"));
	$objBPMDs->setudfvalue("u_owneraddress", $objTable->getudfvalue("u_barangay") && $objTable->getudfvalue("u_municipality"));
	$objBPMDs->setudfvalue("u_applicationno", $objTable->getudfvalue("u_appno"));
	$objBPMDs->setudfvalue("u_areano", $objTable->getudfvalue("u_areano"));
	
	$objBPMDs->setudfvalue("u_apprefno", $objTable->docno);
	$objBPMDs->setudfvalue("u_appdate", $objTable->getudfvalue("u_appdate"));
	$objBPMDs->setudfvalue("u_year", substr($objTable->getudfvalue("u_appdate"),0,4));
	$objBPMDs->setudfvalue("u_regddate",$objTable->getudfvalue("u_appdate"));
	//$objBPMDs->setudfvalue("u_firstyear",$objTable->getudfvalue("u_year"));
	
	
	
         
//        if ($objTable->docstatus=="Approved" && $objTable->docstatus!=$objTable->fields["DOCSTATUS"]) {
//		$objBPLMDs->setudfvalue("u_regdate",currentdateDB());
//	}
        
	
        
	if ($objBPMDs->rowstat=="N") $actionReturn = $objBPMDs->add();
	else $actionReturn = $objBPMDs->update($objBPMDs->code,$objBPMDs->rcdversion);
	//if ($actionReturn) $actionReturn = raiseError("onCustomEventupdatebplmddocumentschema_brGPSBPLS()");
	return $actionReturn;
}



?>