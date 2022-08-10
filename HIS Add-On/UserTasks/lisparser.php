<?php
	require_once("../common/classes/recordset.php");
	require_once("./classes/masterdataschema_br.php");
	require_once("./classes/masterdatalinesschema_br.php");
	require_once("./classes/smsoutlog.php");
	require_once("./series.php");
	require_once("./utils/companies.php");
	require_once("./classes/usermsgs.php");

	function executeTasklisparserGPSHIS() {
		global $objConnection;
		$result = array(0,"error");
		$actionReturn = true;
		echo "executeTasklisparserGPSHIS():running\r\n";
		
		$objRs= new recordset(null,$objConnection);
		
		$objRs->queryopen("select u_lisrecvdir, u_lislogsdir from u_hissetup where u_lissenddir<>''");
		if (!$objRs->queryfetchrow("NAME")) return true;
		
		$dir = $objRs->fields["u_lisrecvdir"];
		$logs = $objRs->fields["u_lislogsdir"];
		$files = scandir($dir);
		
		foreach ($files as $file ) {
			if ($file==".") continue;
			if ($file=="..") continue;
			echo $file . "\r\n";
			
			$data = file_get_contents($dir . $file);
			$id = "";
			$result = processTasklisparserGPSHIS($data,&$id);
			//$result = processTasklisparser2GPSHIS($data,&$id);

			if ($result[0]==1) {
				unlink($dir . $file);
				file_put_contents($logs.$id."-result-".date('Ymdhis').".hl7",$data);
			}// else break;
		}
		
		//if ($actionReturn) $actionReturn = raiseError("executeTaskgpsparserGPSTracker()");
		/*if (!$actionReturn) {
			$result[0] = 0;
			$result[1] = $_SESSION["errormessage"];
			//$objCon->rollback();
		} //else $objCon->commit();*/
		var_dump($result);
		
		return $result;
	}
	
	function processTasklisparserGPSHIS($data,$id) {
		global $objConnection;
		$result = array(true,"Invalid");
		$event = "Fix";
		$actionReturn = true;
		
		echo "processTasklisparserGPSHIS():running\r\n";
		$data = explode("\r\n",$data);
		//var_dump($data);
		$_SESSION["addons"]["Audit Trail Add-On"]["status"]="Stopped";
		$docno = "";
		$u_item = array();
		$u_itemcode = "";
		$u_itemname = "";
		$u_fileno = "";
		$retrieve = false;
		
		$obju_HISItems = new masterdataschema(null,$objConnection, "u_hisitems");
		$obju_HISCharges = new documentschema_br(null,$objConnection, "u_hischarges");
		$obju_HISLabTests = new documentschema_br(null,$objConnection, "u_hislabtests");
		$obju_HISLabTestCases = new documentlinesschema_br(null,$objConnection, "u_hislabtestcases");
		
		$objRs = new recordset(null,$objConnection);
		
		$objConnection->beginwork();
		
		foreach ($data as $segment) {
			$segment = explode("|",$segment);
			switch ($segment[0]) {
				case "MSH":
					$u_remarks="";
					break;
				case "NTE":
					if ($u_remarks!="") $u_remarks .= "\r\n";
					$u_remarks .= $segment[3];
					break;
				case "ORC":
					$docno = $segment[2];
					$u_fileno = $segment[3];
					$u_endddate = substr($segment[9],0,4)."-".substr($segment[9],4,2)."-".substr($segment[9],6,2);
					$u_endtime = substr($segment[9],8,2).":".substr($segment[9],10,2).":".substr($segment[9],12,2);
					$u_verifiedby = explode("^",trim($segment[11]));
					$u_doctorid2 = $u_verifiedby[0];
					$u_pathologist = explode("^",trim($segment[13]));
					$u_testby = $u_pathologist[0];
					break;
				case "OBX":
					if ($retrieve) {
						$u_result = explode("^",$segment[3]);
						$u_resultcode = $u_result[0];
						$u_resultname = $u_result[1];
						$u_resultvalue = $segment[5];
						$u_units = $segment[6];
						$u_normalrange = $segment[7];
						//var_dump(array($u_resultname,"DOCID='$obju_HISLabTests->docid' and U_TEST='$u_resultname'"));
						$found = false;
						if ($u_resultcode!="") $found = $obju_HISLabTestCases->getbysql("DOCID='$obju_HISLabTests->docid' and U_LIS='$u_resultcode'");
						if (!$found) $found = $obju_HISLabTestCases->getbysql("DOCID='$obju_HISLabTests->docid' and U_TEST='$u_resultname'");
						if ($found) {
							$obju_HISLabTestCases->setudfvalue("u_result",$u_resultvalue);
							$obju_HISLabTestCases->setudfvalue("u_units",$u_units);
							$obju_HISLabTestCases->setudfvalue("u_normalrange",$u_normalrange);
							if (trim($obju_HISLabTestCases->getudfvalue("u_formula"))!="") {
								$str = "";
								eval("\$str = round(".$u_resultvalue.trim($obju_HISLabTestCases->getudfvalue("u_formula")).",2);");
								$obju_HISLabTestCases->setudfvalue("u_formularesult",$str);
							} 
							$actionReturn = $obju_HISLabTestCases->update($obju_HISLabTestCases->docid,$obju_HISLabTestCases->lineid,$obju_HISLabTestCases->rcdversion);
							//var_dump($segment);
						}
						
						/*$objRs->queryopen("select u_test from u_hislabtestcases where docid='$obju_HISLabTests->docid'");
						while ($objRs->queryfetchrow("NAME")) {
							var_dump($objRs->fields["u_test"]);
						}*/
									
					}
					break;
				case "OBR":
					$docno = $segment[2];
					$u_item = explode("^",$segment[4]);
					$u_itemcode = $u_item[0];
					$u_itemname = $u_item[1];
					if ($actionReturn && $retrieve && ($u_doctorid2!="" || $u_testby!="" || $u_endddate!="" || $u_remarks!="" || $u_fileno!="")) {
						var_dump(array($u_endddate,$u_endtime,$u_doctorid2,$u_testby,$u_remarks));
						$obju_HISLabTests->setudfvalue("u_enddate",$u_endddate);
						$obju_HISLabTests->setudfvalue("u_endtime",$u_endtime);
						$obju_HISLabTests->setudfvalue("u_doctorid2",$u_doctorid2);
						$obju_HISLabTests->setudfvalue("u_fileno",$u_fileno);
						$obju_HISLabTests->setudfvalue("u_testby",$u_testby);
						$obju_HISLabTests->setudfvalue("u_remarks",$u_remarks);
						$actionReturn = $obju_HISLabTests->update($obju_HISLabTests->docno,$obju_HISLabTests->rcdversion);
					}
					if ($actionReturn && $obju_HISCharges->getbykey($docno)) {
						if ($obju_HISItems->getbykey($u_itemcode)) {
							//if (!$retrieve) {
								$id = $docno . "-" . $obju_HISItems->getudfvalue("u_template");
								//var_dump("U_REQUESTNO='$docno' AND U_TYPE='".$obju_HISItems->getudfvalue("u_template")."'");
								$retrieve = $obju_HISLabTests->getbysql("U_REQUESTNO='$docno' AND U_TYPE='".$obju_HISItems->getudfvalue("u_template")."'"); 
								if (!$retrieve) {
									$obju_HISLabTests->prepareadd();
									$obju_HISLabTests->docid = getNextIdByBranch("u_hislabtests",$objConnection);
									$obju_HISLabTests->docno = getNextSeriesNoByBranch("u_hislabtests",0,currentdateDB(),$objConnection,true);
									$obju_HISLabTests->docstatus = "O";
									$obju_HISLabTests->setudfvalue("u_requestno",$docno);
									$obju_HISLabTests->setudfvalue("u_type",$obju_HISItems->getudfvalue("u_template"));
									$obju_HISLabTests->setudfvalue("u_department",$obju_HISCharges->getudfvalue("u_department"));
									$obju_HISLabTests->setudfvalue("u_reftype",$obju_HISCharges->getudfvalue("u_reftype"));
									$obju_HISLabTests->setudfvalue("u_refno",$obju_HISCharges->getudfvalue("u_refno"));
									$obju_HISLabTests->setudfvalue("u_patientid",$obju_HISCharges->getudfvalue("u_patientid"));
									$obju_HISLabTests->setudfvalue("u_patientname",$obju_HISCharges->getudfvalue("u_patientname"));
									$obju_HISLabTests->setudfvalue("u_birthdate",$obju_HISCharges->getudfvalue("u_birthdate"));
									$obju_HISLabTests->setudfvalue("u_gender",$obju_HISCharges->getudfvalue("u_gender"));
									$obju_HISLabTests->setudfvalue("u_age",$obju_HISCharges->getudfvalue("u_age"));
									$obju_HISLabTests->setudfvalue("u_paymentterm",$obju_HISCharges->getudfvalue("u_paymentterm"));
									$obju_HISLabTests->setudfvalue("u_disccode",$obju_HISCharges->getudfvalue("u_disccode"));
									$obju_HISLabTests->setudfvalue("u_pricelist",$obju_HISCharges->getudfvalue("u_pricelist"));
									$obju_HISLabTests->setudfvalue("u_reqdate",$obju_HISCharges->getudfvalue("u_startdate"));
									$obju_HISLabTests->setudfvalue("u_reqtime",$obju_HISCharges->getudfvalue("u_starttime"));
									$obju_HISLabTests->setudfvalue("u_startdate",$obju_HISCharges->getudfvalue("u_startdate"));
									$obju_HISLabTests->setudfvalue("u_starttime",$obju_HISCharges->getudfvalue("u_starttime"));
									
									$objRs->queryopen("select a.u_itemcode, b.u_specimen, a.u_seq, a.u_seq2, a.u_group, a.u_print, a.u_test, a.u_normalrange, a.u_formula, a.u_formulanormalrange, a.u_units, a.u_formulaunits, a.u_lis from u_hislabtesttypecases a, u_hislabtesttypes b, u_hischarges c, u_hischargeitems d where d.company=c.company and d.branch=c.branch and d.docid=c.docid and c.company='".$_SESSION["company"]."' and c.branch='".$_SESSION["branch"]."' and c.docno='".$obju_HISLabTests->getudfvalue("u_requestno")."' and d.u_template='".$obju_HISLabTests->getudfvalue("u_type")."' and a.u_itemcode=d.u_itemcode and b.code=a.code and a.code='".$obju_HISLabTests->getudfvalue("u_type")."' and (a.u_gender='' or (a.u_gender<>'' and a.u_gender='".$obju_HISLabTests->getudfvalue("u_gender")."')) and (a.u_agefr=0 or (a.u_agefr<>0 and a.u_agefr<=".$obju_HISLabTests->getudfvalue("u_age").")) and (a.u_ageto=0 or (a.u_ageto<>0 and a.u_ageto>=".$obju_HISLabTests->getudfvalue("u_age").")) group by a.u_itemcode, a.u_seq, a.u_seq2, a.u_group, a.u_print, a.u_test order by a.u_itemcode, a.u_seq, a.u_seq2");
									while ($objRs->queryfetchrow("NAME")) {
										$obju_HISLabTests->setudfvalue("u_specimen",$objRs->fields["u_specimen"]);
										$obju_HISLabTestCases->prepareadd();
										$obju_HISLabTestCases->docid = $obju_HISLabTests->docid;
										$obju_HISLabTestCases->lineid = getNextIdByBranch("u_hislabtestcases",$objConnection);
										$obju_HISLabTestCases->setudfvalue("u_itemcode", $objRs->fields["u_itemcode"]);
										$obju_HISLabTestCases->setudfvalue("u_test", $objRs->fields["u_test"]);
										$obju_HISLabTestCases->setudfvalue("u_seq", $objRs->fields["u_seq"]);
										$obju_HISLabTestCases->setudfvalue("u_seq2", $objRs->fields["u_seq2"]);
										$obju_HISLabTestCases->setudfvalue("u_group", $objRs->fields["u_group"]);
										$obju_HISLabTestCases->setudfvalue("u_print", $objRs->fields["u_print"]);
										$obju_HISLabTestCases->setudfvalue("u_test", $objRs->fields["u_test"]);
										$obju_HISLabTestCases->setudfvalue("u_normalrange", $objRs->fields["u_normalrange"]);
										$obju_HISLabTestCases->setudfvalue("u_units", $objRs->fields["u_units"]);
										$obju_HISLabTestCases->setudfvalue("u_formula", $objRs->fields["u_formula"]);
										$obju_HISLabTestCases->setudfvalue("u_formulanormalrange", $objRs->fields["u_formulanormalrange"]);
										$obju_HISLabTestCases->setudfvalue("u_formulaunits", $objRs->fields["u_formulaunits"]);
										$obju_HISLabTestCases->setudfvalue("u_lis", $objRs->fields["u_lis"]);
										$actionReturn = $obju_HISLabTestCases->add();
										//var_dump(array($objRs->fields["u_test"],$actionReturn));
										if (!$actionReturn) break;
									} 
		
									if ($actionReturn) $obju_HISLabTests->add();
									if ($actionReturn) $retrieve = $obju_HISLabTests->getbysql("U_REQUESTNO='$docno' AND U_TYPE='".$obju_HISItems->getudfvalue("u_template")."'"); 
									if (!$actionReturn) break;
								}
							//}
						}
					}	
					break;
			}
			//var_dump($segment);
		}
		if ($actionReturn && $retrieve && ($u_doctorid2!="" || $u_testby!="" || $u_endddate!="" || $u_remarks!="" || $u_fileno!="")) {
			var_dump(array($u_endddate,$u_endtime,$u_doctorid2,$u_testby,$u_remarks));
			$obju_HISLabTests->setudfvalue("u_enddate",$u_endddate);
			$obju_HISLabTests->setudfvalue("u_endtime",$u_endtime);
			$obju_HISLabTests->setudfvalue("u_doctorid2",$u_doctorid2);
			$obju_HISLabTests->setudfvalue("u_fileno",$u_fileno);
			$obju_HISLabTests->setudfvalue("u_testby",$u_testby);
			$obju_HISLabTests->setudfvalue("u_remarks",$u_remarks);
			$actionReturn = $obju_HISLabTests->update($obju_HISLabTests->docno,$obju_HISLabTests->rcdversion);
		}
		//if ($actionReturn) $actionReturn = false;
		
		if ($actionReturn) {
			$objConnection->commit();
		} else {
			$objConnection->rollback();
		}
		$result[0] = iif($actionReturn,1,0);
		/*if ($retrieve) {
			$result[0] = $obju_HISLabTests->update($obju_HISLabTests->docno,$obju_HISLabTests->rcdversion);
		}*/
		return $result;
	}

	function processTasklisparser2GPSHIS($data,$id) {
		global $objConnection;
		$result = array(true,"Invalid");
		$event = "Fix";
		$actionReturn = true;
		
		echo "processTasklisparser2GPSHIS():running\r\n";
		$data = explode("\r\n",$data);
		//var_dump($data);
		$_SESSION["addons"]["Audit Trail Add-On"]["status"]="Stopped";
		$docno = "";
		$u_item = array();
		$u_itemcode = "";
		$u_itemname = "";
		$retrieve = false;
		
		$obju_HISItems = new masterdataschema(null,$objConnection, "u_hisitems");
		$obju_HISCharges = new documentschema_br(null,$objConnection, "u_hischarges");
		$obju_HISLabTests = new documentschema_br(null,$objConnection, "u_hislabtests");
		$obju_HISLabTestCases = new documentlinesschema_br(null,$objConnection, "u_hislabtestcases");
		
		$objRs = new recordset(null,$objConnection);
		
		$objConnection->beginwork();
		
		foreach ($data as $segment) {
			$segment = explode("|",$segment);
			switch ($segment[0]) {
				case "MSH":
					$u_remarks="";
					break;
				case "NTE":
					if ($u_remarks!="") $u_remarks .= "\r\n";
					$u_remarks .= $segment[3];
					break;
				case "ORC":
					$docno = $segment[2];
					$u_endddate = substr($segment[9],0,4)."-".substr($segment[9],4,2)."-".substr($segment[9],6,2);
					$u_endtime = substr($segment[9],8,2).":".substr($segment[9],10,2).":".substr($segment[9],12,2);
					$u_verifiedby = explode("^",trim($segment[11]));
					$u_doctorid2 = $u_verifiedby[0];
					$u_pathologist = explode("^",trim($segment[13]));
					$u_testby = $u_pathologist[0];
					break;
				case "OBX":
					if ($retrieve) {
						$u_result = explode("^",$segment[3]);
						$u_resultcode = $u_result[0];
						$u_resultname = $u_result[1];
						$u_resultvalue = $segment[5];
						$u_units = $segment[6];
						$u_normalrange = $segment[7];
						//var_dump(array($u_resultname,"DOCID='$obju_HISLabTests->docid' and U_TEST='$u_resultname'"));
						$found = false;
						if ($u_resultcode!="") $found = $obju_HISLabTestCases->getbysql("DOCID='$obju_HISLabTests->docid' and U_LIS='$u_resultcode'");
						if (!$found) $found = $obju_HISLabTestCases->getbysql("DOCID='$obju_HISLabTests->docid' and U_TEST='$u_resultname'");
						if ($found) {
							$obju_HISLabTestCases->setudfvalue("u_result",$u_resultvalue);
							$obju_HISLabTestCases->setudfvalue("u_units",$u_units);
							$obju_HISLabTestCases->setudfvalue("u_normalrange",$u_normalrange);
							if (trim($obju_HISLabTestCases->getudfvalue("u_formula"))!="") {
								$str = "";
								eval("\$str = round(".$u_resultvalue.trim($obju_HISLabTestCases->getudfvalue("u_formula")).",2);");
								$obju_HISLabTestCases->setudfvalue("u_formularesult",$str);
							} 
							$actionReturn = $obju_HISLabTestCases->update($obju_HISLabTestCases->docid,$obju_HISLabTestCases->lineid,$obju_HISLabTestCases->rcdversion);
							//var_dump($segment);
						}
						
						/*$objRs->queryopen("select u_test from u_hislabtestcases where docid='$obju_HISLabTests->docid'");
						while ($objRs->queryfetchrow("NAME")) {
							var_dump($objRs->fields["u_test"]);
						}*/
									
					}
					break;
				case "OBR":
					$docno = $segment[2];
					$u_item = explode("^",$segment[4]);
					$u_itemcode = $u_item[0];
					$u_itemname = $u_item[1];
					if ($obju_HISItems->getbykey($u_itemcode)) {
						if (!$retrieve) {
							$id = $docno . "-" . $obju_HISItems->getudfvalue("u_template");
							//var_dump("U_REQUESTNO='$docno' AND U_TYPE='".$obju_HISItems->getudfvalue("u_template")."'");
							$retrieve = $obju_HISLabTests->getbykey($docno); 
						}
					}	
					break;
			}
			//var_dump($segment);
		}
		if ($actionReturn && $retrieve && ($u_doctorid2!="" || $u_testby!="" || $u_endddate!="" || $u_remarks!="")) {
			var_dump(array($u_endddate,$u_endtime,$u_doctorid2,$u_testby,$u_remarks));
			$obju_HISLabTests->setudfvalue("u_enddate",$u_endddate);
			$obju_HISLabTests->setudfvalue("u_endtime",$u_endtime);
			$obju_HISLabTests->setudfvalue("u_doctorid2",$u_doctorid2);
			$obju_HISLabTests->setudfvalue("u_testby",$u_testby);
			$obju_HISLabTests->setudfvalue("u_remarks",$u_remarks);
			$actionReturn = $obju_HISLabTests->update($obju_HISLabTests->docno,$obju_HISLabTests->rcdversion);
		}
		//if ($actionReturn) $actionReturn = false;
		
		if ($actionReturn) {
			$objConnection->commit();
		} else {
			$objConnection->rollback();
		}
		$result[0] = iif($actionReturn,1,0);
		/*if ($retrieve) {
			$result[0] = $obju_HISLabTests->update($obju_HISLabTests->docno,$obju_HISLabTests->rcdversion);
		}*/
		return $result;
	}
	

?>