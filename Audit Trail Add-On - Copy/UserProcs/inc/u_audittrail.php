<?php

include_once("./classes/logins.php");
include_once("./classes/progids.php");
include_once("./classes/postingperiodstatus.php");
include_once("./classes/masterdataschema.php");
include_once("./classes/masterdatalinesschema.php");
include_once("./classes/documentschema_br.php");
include_once("./classes/documentlinesschema_br.php");
include_once("./sls/users.php");

$auditbranchTmp = "";
$auditprocessing = false;
$objAudittrail = new objtable(NULL,$objConnection);

function addAudittrailGPSAuditTrail($action,$objTable) {
	global $httpVars;
	global $objConnection;
	global $page;
	global $objAudittrail;
	global $auditprocessing;
	
	global $company;
	global $companyTmp;
	global $branch;
	global $branchTmp;

	global $objLicMgr;
	
	if (!$objLicMgr->checkAddon("GPS.Audit Trail","Standard")) return false;

	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select haltaccess from trxsync");
	if ($objRs->queryfetchrow("NAME")) {
		//file_put_contents("c:\\users\\audit.txt",serialize($objRs->fields));
		if ($objRs->fields["haltaccess"]==1) return true;
	}
	
	$resetSelectedBranch = false;
	$actionReturn = true;

	switch($objTable->dbtable) {
		case "u_audittrailsetup":
		case "u_audittrail":
		case "u_audittrailtables":
		case "u_audittrailfields":
		case "u_audittrailexceptions":
		case "u_audittrailexceptionfields":
			return $actionReturn;
	}
	
	if($httpVars["menuid"]=="") {
		$menuid = ($httpVars["gf_progid2"]=="") ? $httpVars["gf_progid"] : $httpVars["gf_progid2"];
	} else $menuid = $httpVars["menuid"];

	if($menuid=="" || strpos($menuid,"trxsync.php")!== false) return $actionReturn;

	if($company <> $companyTmp || $branch <> $branchTmp) {
		$companyTemporary = $company;
		$branchTemporary = $branch;
		$resetSelectedBranch = true;
		resetCompanyBranch();
		$_SESSION["company"] = $company;
		$_SESSION["branch"] = $branch;
	}

	if($auditprocessing==false) {
		$continue = true;
		$objAudittrailsetup = new masterdataschema(NULL,$objConnection,"u_audittrailsetup");
		if($objAudittrailsetup->getbysql("CODE='$menuid'")) {
			if($objAudittrailsetup->getudfvalue("u_log")=="No") $continue = false;
			$code = $objAudittrailsetup->code;
			$name = $objAudittrailsetup->name;
		} else {
			$objProgid = new progids(NULL,$objConnection);
			$objProgid->getbysql("PROGID='$menuid'");
			$code = $menuid;
			$name = $objProgid->progname;
			if($_SESSION['branchtype'] == "M") {
				$objAudittrailsetup->shareddatatype = "COMPANY";
				$objAudittrailsetup->prepareadd();
				$objAudittrailsetup->code = $menuid;
				$objAudittrailsetup->name = $objProgid->progname;
				$objAudittrailsetup->setudfvalue("u_log","Yes");
				if ($actionReturn) $actionReturn = $objAudittrailsetup->add();
			} else {
				if ($actionReturn) $actionReturn = writeTrxOutLog($_SESSION["branch"],"INTERBRANCH:PRC",$_SESSION["mainbranch"],"U_AUDITTRAILSETUP","A",1,$menuid);
			}
		}

		if($continue == true) {
			if($_SESSION["branch"] <> $branch) {
				$auditbranchTmp = $_SESSION["branch"];
				$_SESSION["branch"] = $branch;
			}
			$objLogins = new logins(NULL,$objConnection);

			$auditprocessing = true;
			$docno = ($httpVars["df_code"]=="") ? $httpVars["df_docno"] : $httpVars["df_code"];
			if($docno=="") {
				$objRs = new recordset(NULL,$objConnection);
				switch (strtolower($objTable->dbtable)) {
					case "itempricelists":
						$objRs->queryopen("select pricelistname from pricelists where pricelist='$objTable->pricelist'");
						if($objRs->queryfetchrow("NAME")) {
							$docno = $objRs->fields["pricelistname"];
						}
						break;
					default:
						$objRs->queryopen("SHOW KEYS FROM ".$objTable->dbtable." WHERE Non_unique=0 and Column_name not in ('COMPANY','BRANCH','DOCID')");
						if ($objRs->recordcount()==1) {
							if($objRs->queryfetchrow("NAME")) {
								$fieldname = strtolower($objRs->fields["Column_name"]);
								$docno = $objTable->$fieldname;
							}
						}
				}		
				/*while($objRs->queryfetchrow("NAME")) {
					$fieldname = strtolower($objRs->fields["Column_name"]);
					if ($docno!="") $docno .= "-"; 
					$docno .= $objTable->$fieldname;
				}*/
				
			}
			
			switch($httpVars["formAction"]) {
				case "a": $action = "Add"; break;
				case "d": $action = "Delete"; break;
				case "sc": $action = "Update"; break;
				case "unpost": $action = "Unpost"; break;
				case "post": $action = "post"; break;
				case "cc":
				case "cnd": $action = "Cancel"; break;
			}
			//wilson
			$log = true;
			$objAudittrailexception = new masterdataschema(NULL,$objConnection,"u_audittrailexceptions");
			if($objAudittrailexception->getbykey($objTable->dbtable,0)) {
				if($objAudittrailexception->getudfvalue("u_log")=="No") $log = false;
			} elseif($_SESSION["company"]=="WMSC") $log = false;

			if($log == true) {
				$objAudittrailexceptionfields = new masterdatalinesschema(NULL,$objConnection,"u_audittrailexceptionfields");
				if($objAudittrailexceptionfields->getbysql("CODE='".$objTable->dbtable."'") || $_SESSION["company"]!="WMSC") {
					$objAudittrail = new documentschema_br(NULL,$objConnection,"u_audittrail");
					$objAudittrail->prepareadd();
					$objAudittrail->docid = getNextIdByBranch($objAudittrail->dbtable,$objConnection);
					$objAudittrail->docseries = -1;
					$objAudittrail->docno = $objAudittrail->docid;
					$objAudittrail->setudfvalue("u_userid",$_SESSION["userid"]);
					$objAudittrail->setudfvalue("u_username",slgetdisplayusers($_SESSION["userid"]));
					$objAudittrail->setudfvalue("u_menucommand",$code);
					$objAudittrail->setudfvalue("u_menuname",$name);
					$objAudittrail->setudfvalue("u_objectcode",$httpVars["objectcode"]);
					$objAudittrail->setudfvalue("u_progid",$httpVars["gf_progid"]);
					$objAudittrail->setudfvalue("u_progid2",$httpVars["gf_progid2"]);
					$objAudittrail->setudfvalue("u_action",$action);
					$objAudittrail->setudfvalue("u_code", $docno);
					
					if($objLogins->getbysql("SESSIONID='".$httpVars["gf_sessionid"]."'")) {
						$objAudittrail->setudfvalue("u_sessionid",$objLogins->sessionid);
						$objAudittrail->setudfvalue("u_ip",$objLogins->ip);
					}
					if ($actionReturn) $actionReturn = $objAudittrail->add();
				}
			}
			
			if($auditbranchTmp <> "") {
				$_SESSION["branch"] = $auditbranchTmp;
				$auditbranchTmp = "";
			}
		}
	}

	if($auditprocessing==true) {
		if($_SESSION["branch"] <> $branch) {
			$auditbranchTmp = $_SESSION["branch"];
			$_SESSION["branch"] = $branch;
		}
		
		$objRs = new recordset(NULL,$objConnection);
		$objAudittrailexception = new masterdataschema(NULL,$objConnection,"u_audittrailexceptions");
		$objAudittrailtables = new documentlinesschema_br(NULL,$objConnection,"u_audittrailtables");

		//$branch = ($objTable->branch=="") ? $_SESSION['branch'] : $objTable->branch;
		$code = ($objTable->code=="") ? $objTable->docno : $objTable->code;
		$docid = ($objTable->docid=="") ? 0 : $objTable->docid;
		$lineid = ($objTable->lineid=="") ? 0 : $objTable->lineid;
		$rcdversion = $objTable->rcdversion + 1;
		//var_dump($code);
		if($code=="") {
			switch (strtolower($objTable->dbtable)) {
				case "itempricelists":
					$code = $objTable->itemcode;
					break;
				default:
					$objRs->queryopen("SHOW KEYS FROM ".$objTable->dbtable." WHERE Non_unique=0 and Column_name not in ('COMPANY','BRANCH','DOCID')");
					//if($objRs->queryfetchrow("NAME")) {
					//	$fieldname = strtolower($objRs->fields["Column_name"]);
					//	$code = $objTable->$fieldname;
					//}
					while($objRs->queryfetchrow("NAME")) {
						$fieldname = strtolower($objRs->fields["Column_name"]);
						if ($code!="") $code .= "-"; 
						$code .= $objTable->$fieldname;
					}
			}		
		}
		//var_dump($code);
		$log = true;
		$fielddate = "";
		if($objAudittrailexception->getbykey($objTable->dbtable,0)) {
			if($objAudittrailexception->getudfvalue("u_log")=="No") $log = false;
			$fielddate = $objAudittrailexception->getudfvalue("u_fielddate");
		} elseif($_SESSION["company"]=="WMSC") $log = false;

		if($log == true && $objAudittrail->docid>0) {
			$datecreated = ($objTable->datecreated == "CURRENT_TIMESTAMP") ? date("Y-m-d H:i:s") : $objTable->datecreated;
			if ($datecreated=="") $datecreated = date("Y-m-d H:i:s");
			
			$objAudittrailtables->prepareadd();
			$objAudittrailtables->docid = $objAudittrail->docid;
			$objAudittrailtables->lineid = getNextIdByBranch($objAudittrailtables->dbtable,$objConnection);
			$objAudittrailtables->setudfvalue("u_tablename", $objTable->dbtable);
			$objAudittrailtables->setudfvalue("u_action", $action);
			$objAudittrailtables->setudfvalue("u_branchcode", $branch);
			$objAudittrailtables->setudfvalue("u_code", $code);
			$objAudittrailtables->setudfvalue("u_docid", $docid);
			$objAudittrailtables->setudfvalue("u_lineid", $lineid);
			$objAudittrailtables->setudfvalue("u_rcdversion", $rcdversion);
			$objAudittrailtables->setudfvalue("u_createtimestamp", $datecreated);
			
			if($fielddate<>"") {
				if(substr($fielddate,0,2)=="u_") $objAudittrailtables->setudfvalue("u_documentdate", $objTable->getudfvalue($fielddate));
				else $objAudittrailtables->setudfvalue("u_documentdate",$objTable->$fielddate);
			}
			
			$objAudittrailtables->objTable = $objTable;
			if($actionReturn) $actionReturn = $objAudittrailtables->add();

			if($action=="Update") {
				$objAudittrailexceptionfields = new masterdatalinesschema(NULL,$objConnection,"u_audittrailexceptionfields");
				$objAudittrailfields = new documentlinesschema_br(NULL,$objConnection,"u_audittrailfields");
				//print_r($objTable);
				//print_r($httpVars);
				foreach($objTable->fields as $fieldname => $fieldvalue) {
					$fieldname = strtolower($fieldname);
					$logfield = true;
					if($objAudittrailexceptionfields->getbysql("CODE='".$objTable->dbtable."' AND LOWER(U_FIELDNAME)='".$fieldname."'")) {
						if($objAudittrailexceptionfields->getudfvalue("u_log")==0) $logfield = false;
						//print "$fieldname = $logfield = ".$objAudittrailexceptionfields->getudfvalue("u_log")."<br>";
					}
					if($logfield == true) {
						if(substr($fieldname,0,2)=="u_") {
							$oldvalue = ($objTable->fields[strtoupper($fieldname)]=="0000-00-00") ? "" : $objTable->fields[strtoupper($fieldname)];
							$newvalue = ($objTable->getudfvalue($fieldname)=="0000-00-00") ? "" : $objTable->getudfvalue($fieldname);

							if($objTable->userfields[$fieldname]["format"]=="numeric" || $objTable->userfields[$fieldname]["format"]=="amount") {
								$oldvalue = round($objTable->fields[strtoupper($fieldname)],6);
								$newvalue = round($objTable->getudfvalue($fieldname),6);
							}
							
							if((string)$newvalue <> (string)$oldvalue) {
								//print $objTable->dbtable." = $fieldname = $oldvalue = $newvalue <br>";
								$objAudittrailfields->prepareadd();
								$objAudittrailfields->docid = $objAudittrail->docid;
								$objAudittrailfields->lineid = getNextIdByBranch($objAudittrailfields->dbtable,$objConnection);
								$objAudittrailfields->setudfvalue("u_lineid", $objAudittrailtables->lineid);
								$objAudittrailfields->setudfvalue("u_tablename", $objTable->dbtable);
								$objAudittrailfields->setudfvalue("u_fieldname", $fieldname);
								$objAudittrailfields->setudfvalue("u_oldvalue", $objTable->fields[strtoupper($fieldname)]);
								$objAudittrailfields->setudfvalue("u_newvalue", $objTable->getudfvalue($fieldname));
								if($actionReturn) $actionReturn = $objAudittrailfields->add();
							}
						} else {
							switch($fieldname) {
								case "branch":
								case "company":
								case "createdby":
								case "datecreated":
								case "lastupdated":
								case "lastupdatedby":
								case "rcdversion": break;
								default:
									$oldvalue = ($objTable->fields[strtoupper($fieldname)]=="0000-00-00") ? "" : $objTable->fields[strtoupper($fieldname)];
									$newvalue = ($objTable->$fieldname=="0000-00-00") ? "" : $objTable->$fieldname;
									
									if($objTable->dbtable == "POSTINGPERIODS" && ($fieldname == "status" || $fieldname == "islocked")) {
										//fetch old value on table POSTINGPERIODSTATUS 
										$objPostingPeriodStatus = new postingperiodstatus(null,$objConnection);
										if ($objPostingPeriodStatus->getbykey($objTable->periodcode,0)) {
											$oldvalue = $objPostingPeriodStatus->$fieldname;
										} else {
											$newvalue = $httpVars["df_".$fieldname];
										}
									}
									
									//print $objTable->dbtable." = $fieldname = $oldvalue = $newvalue <br>";
									if((string)$newvalue <> (string)$oldvalue) {
										//print $objTable->dbtable." = $fieldname = $oldvalue = $newvalue ============<br>";
										$objAudittrailfields->prepareadd();
										$objAudittrailfields->docid = $objAudittrail->docid;
										$objAudittrailfields->lineid = getNextIdByBranch($objAudittrailfields->dbtable,$objConnection);
										$objAudittrailfields->setudfvalue("u_lineid", $objAudittrailtables->lineid);
										$objAudittrailfields->setudfvalue("u_tablename", $objTable->dbtable);
										$objAudittrailfields->setudfvalue("u_fieldname", $fieldname);
										$objAudittrailfields->setudfvalue("u_oldvalue", $oldvalue);
										$objAudittrailfields->setudfvalue("u_newvalue", $newvalue);
										if($actionReturn) $actionReturn = $objAudittrailfields->add();
									}
							} # end switch
						}
					} # end $logfield
				}
			}
		} # end $log
		if($auditbranchTmp <> "") {
			$_SESSION["branch"] = $auditbranchTmp;
			$auditbranchTmp = "";
		}
	}

	if ($resetSelectedBranch == true) {
		setCompanyBranch($companyTemporary,$branchTemporary);
		$_SESSION["company"] = $company;
		$_SESSION["branch"] = $branch;
	}
	//if ($actionReturn) $actionReturn = raiseError("addAudittrailGPSAuditTrail");
	return $actionReturn;
}

?>