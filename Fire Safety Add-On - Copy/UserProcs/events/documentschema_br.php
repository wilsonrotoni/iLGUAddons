<?php

function onBeforeAddEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsins":
			$obju_FSApps = new documentschema_br(null,$objConnection,"u_fsapps");
			if ($obju_FSApps->getbykey($objTable->getudfvalue("u_appno"))) {
				$obju_FSApps->docstatus = "FI";
				$obju_FSApps->setudfvalue("u_insno",$objTable->docno);
				$actionReturn = $obju_FSApps->update($obju_FSApps->docno,$obju_FSApps->rcdversion);
			} else return raiseError("Unable to find Application No.[".$objTable->getudfvalue("u_appno")."].");
			break;
	}
	return $actionReturn;
}


/*
function onAddEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/


function onBeforeUpdateEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsins":
			if ($objTable->fields["U_INSPECTBYSTATUS"]!=$objTable->getudfvalue("u_inspectbystatus")) {
				if ($objTable->getudfvalue("u_inspectbystatus")=="Passed") {
					$objTable->docstatus = "P";
				} else {
					$objTable->docstatus = "F";
				}
			}
			if ($objTable->fields["U_RECOMMENDBYSTATUS"]!=$objTable->getudfvalue("u_recommendbystatus")) {
				//var_dump($objTable->getudfvalue("u_recommendbystatus"));
				if ($objTable->getudfvalue("u_recommendbystatus")=="For Approval") {
					$objTable->docstatus = "FA";
				} else {
					$objTable->docstatus = "D";
				}
				$objTable->setudfvalue("u_recommendby",$_SESSION["userid"]);
				$objTable->setudfvalue("u_recommenddate",currentdateDB());
			}
			if ($objTable->fields["U_DISPOSITIONBYSTATUS"]!=$objTable->getudfvalue("u_dispositionbystatus")) {
				if ($objTable->getudfvalue("u_dispositionbystatus")=="Approved") {
					$objTable->docstatus = "A";
				} else {
					$objTable->docstatus = "D";
				}
				$objTable->setudfvalue("u_dispositionby",$_SESSION["userid"]);
				$objTable->setudfvalue("u_dispositiondate",currentdateDB());
			}
			
			/*if ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="FA") {
				$objTable->setudfvalue("u_recommendbystatus","Approved");
				$objTable->setudfvalue("u_recommenddate",currentdateDB());
			} elseif ($objTable->fields["DOCSTATUS"]!=$objTable->docstatus && $objTable->docstatus=="A") {
				$objTable->setudfvalue("u_dispositionby",$_SESSION["userid"]);
				$objTable->setudfvalue("u_dispositionbystatus","Approved");
				$objTable->setudfvalue("u_dispositiondate",currentdateDB());
			}*/

			break;
	}
	//if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brGPSFireSafety");
	return $actionReturn;
}



function onUpdateEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	global $page;
	$actionReturn = true;
        
	switch ($objTable->dbtable) {
		case "u_fsapps":
                        if ($objTable->fields["DOCSTATUS"]=="O" && $objTable->docstatus=="P") {
                            $actionReturn = onCustomEventcreatePOSdocumentschema_brGPSFireSafety($objTable);
                            if (!$actionReturn) $page->setitem("docstatus","O");
			} 
			break;
	}
	return $actionReturn;
}


/*
function onBeforeDeleteEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/

/*
function onDeleteEventdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	switch ($objTable->dbtable) {
		case "u_fsapps":
			break;
	}
	return $actionReturn;
}
*/
function onCustomEventcreatePOSdocumentschema_brGPSFireSafety($objTable) {
	global $objConnection;
	$actionReturn = true;
	
	$objRs = new recordset (NULL,$objConnection);
	$obju_Pos = new documentschema_br(null,$objConnection,"u_lgupos");
	$obju_PosItems = new documentlinesschema_br(null,$objConnection,"u_lgupositems");
	
        $objRsFees = new recordset(null,$objConnection);
	$objRsFees->queryopen("SELECT A.U_BPLFIREINSFEE AS U_BPLFIREFEECODE, B.NAME AS U_BPLFIREFEEDESC
                                FROM U_LGUSETUP A 
                                LEFT JOIN U_LGUFEES B ON B.CODE=A.U_BPLFIREINSFEE ");
	if (!$objRsFees->queryfetchrow("NAME")) {
		return raiseError("No setup found for Fire safety fee.");
	}

	
                $totalamount=$objTable->getudfvalue("u_orfcamt");

                $obju_Pos->prepareadd();
                $obju_Pos->docseries = -1;
                $obju_Pos->docno = $objTable->getudfvalue("u_orno");
                $obju_Pos->docid = getNextIDByBranch("u_lgupos",$objConnection);
                $obju_Pos->setudfvalue("u_custname",$objTable->getudfvalue("u_businessname"));
                $obju_Pos->setudfvalue("u_custno",$objTable->docno);
                $obju_Pos->setudfvalue("u_status","C");
                $obju_Pos->setudfvalue("u_date",$objTable->getudfvalue("u_ordate"));
                $obju_Pos->setudfvalue("u_profitcenter","FS");
                $obju_Pos->setudfvalue("u_module","Fire Safety");
                $obju_Pos->setudfvalue("u_paymode",'');
                $obju_Pos->setudfvalue("u_doccnt",1);
                $obju_Pos->setudfvalue("u_trxtype","S");
                
                $obju_PosItems->prepareadd();
                $obju_PosItems->docid = $obju_Pos->docid;
                $obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
                $obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_BPLFIREFEECODE"]);
                $obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_BPLFIREFEEDESC"]);
                $obju_PosItems->setudfvalue("u_quantity",1);
                $obju_PosItems->setudfvalue("u_unitprice",$totalamount);
                $obju_PosItems->setudfvalue("u_price",$totalamount);
                $obju_PosItems->setudfvalue("u_linetotal",$totalamount);
                $obju_PosItems->privatedata["header"] = $obju_Pos;
                $actionReturn = $obju_PosItems->add();
                
            if ($actionReturn) {
              $obju_Pos->setudfvalue("u_paidamount",$totalamount);
              $obju_Pos->setudfvalue("u_totalamount",$totalamount);
              $obju_Pos->setudfvalue("u_cashamount",$totalamount);
              $obju_Pos->setudfvalue("u_chequeamount",0);
              $obju_Pos->setudfvalue("u_collectedcashamount",$totalamount);
              $actionReturn = $obju_Pos->add();
            }
            if (!$actionReturn) return false;
			
		
		
	return $actionReturn;
}
?>
