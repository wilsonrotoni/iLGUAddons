<?php
	require_once("../common/classes/recordset.php");
	require_once("../common/utility/sqlquery.php");

	function executeTaskgisexportGPSLGU() {
		$result = array(0,"error");
		$actionReturn = true;
		echo "executeTaskgisexportGPSLGU():running\r\n";
		
		$objCon = new connection('Page Connection'); 
		$objCon->connect();
		//$objCon->selectdb("sms_lms_ho");
											
		$objRs= new recordset(null,$objCon);
		
		$objRs->queryopen("select u_gisexportfilename from u_lgusetup where u_gisexportfilename<>''");
		if ($objRs->queryfetchrow()) {
                        var_dump($objRs->fields[0]);
			$objSqlQuery = new sqlqueryclass();
			$actionReturn = $objSqlQuery->execute("set output file ".$objRs->fields[0].";select a.docno,a.u_province,a.u_municipality,a.u_barangay,a.u_pin,group_concat(a.u_ownername SEPARATOR ' / ') as u_ownername,group_concat(a.u_ownercompanyname SEPARATOR ' / ') as u_ownercompanyname, a.u_tctno, B.CLASS,a.u_assvalue,group_concat(if(a.u_tdno='',a.u_varpno,a.u_tdno) SEPARATOR ' / ') as Taxdecno,a.u_cadlotno,B.AREA,a.u_prevvalue,GROUP_CONCAT(CAST(a.u_bilyear AS CHAR) SEPARATOR ' / ') AS U_CLAIMANTSBILYEAR,MAX(A.U_BILYEAR) AS U_BILYEAR,COUNT(A.DOCNO) AS CLAIMANTCOUNT from u_rpfaas1 a INNER JOIN(SELECT SUM(E.U_SQM) AS AREA,GROUP_CONCAT(distinct C.NAME order by c.name) AS CLASS,A.COMPANY,A.BRANCH,A.DOCNO FROM U_RPFAAS1 A INNER JOIN U_RPFAAS1A E ON A.COMPANY = E.COMPANY AND A.BRANCH = E.BRANCH AND A.DOCNO = E.U_ARPNO LEFT JOIN U_RPLANDS C ON E.U_CLASS = C.CODE WHERE A.COMPANY = 'LGU' AND A.BRANCH = 'MAIN' GROUP BY A.DOCNO ) AS B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCNO = B.DOCNO WHERE a.u_cancelled <> 1 AND A.COMPANY = 'LGU' AND A.BRANCH = 'MAIN' group by u_pin order by u_pin;") ;
		}
			
		//if ($actionReturn) $actionReturn = raiseError("executeTaskgisexportGPSWheeltekSMS()");
		if (!$actionReturn) {
			$result[0] = 0;
			$result[1] = $_SESSION["errormessage"];
			//$objCon->rollback();
		} //else $objCon->commit();
		//var_dump($result);
		return $result;
	}
	
	
?>