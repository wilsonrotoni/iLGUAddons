<?php
	include_once("./inc/formutils.php");
	
	$u_openincomingrequests = 0;
	$u_countincomingrequests = 0;
	$u_openincomingstockrequests = 0;
	$u_countincomingstockrequests = 0;
	$u_incomingrequesttrxtype = "";

	$u_openincomingpatients = 0;
	$u_countincomingpatients = 0;

	$u_openpredischargealert = 0;
	$u_countpredischargealert = 0;
	
	$objUsers = new users(NULL,$objConnection);
	$u_objRs = new recordset(NULL,$objConnection);
	if ($objUsers->getbykey($_SESSION["userid"],0)) {
		if ($objUsers->roleid!="") {
			$u_openincomingrequests = 1;
			$u_openincomingpatients = 1;
			$u_openpredischargealert = 1;
			$u_objRs->queryopen("select U_TYPE, U_PREDISCALERT, U_STOCKTFREQALERT from u_hissections where code='".$objUsers->roleid."'");
			if ($u_objRs->queryfetchrow()) {
				$u_incomingrequesttrxtype = $u_objRs->fields[0];
				$u_openpredischargealert = $u_objRs->fields[1];
				$u_openincomingstockrequests = $u_objRs->fields[2];
			}	
			//$u_objRs->setdebug();
			$u_objRs->queryopen("select count(*) from U_HISREQUESTS A, U_HISREQUESTITEMS B WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND  A.U_DEPARTMENT IN ('".$objUsers->roleid."') AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCSTATUS NOT IN ('CN','C','D') AND B.U_QUANTITY > (B.U_CHRGQTY + B.U_RTQTY)");
			if ($u_objRs->queryfetchrow()) $u_countincomingrequests = $u_objRs->fields[0];
			//var_dump($u_objRs->sqls);

			$u_objRs->queryopen("select count(*) from U_HISIPS A WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.U_NURSED=0 AND A.DOCSTATUS NOT IN ('Cancelled') A.U_DEPARTMENT='".$_SESSION["roleid"]."'");
			if ($u_objRs->queryfetchrow()) $u_countincomingpatients = $u_objRs->fields[0];

			$u_objRs->queryopen("select A.ROLEID AS DEPARTMENT, B.U_TYPE AS TYPE FROM USERS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.ROLEID WHERE A.USERID='".$_SESSION["userid"]."'");
			if ($u_objRs->queryfetchrow("NAME")) {
				if ($u_objRs->fields["TYPE"]=="NURSE") {
					$schema["u_department"]["attributes"] = "disabled";
					$userdepartment = $u_objRs->fields["DEPARTMENT"];
				}	
			}

	
			$filterExp = "";
			$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$userdepartment);
			
			if ($filterExp !="") $filterExp = " AND " . $filterExp;
			
			$u_objRs->queryopen("select count(*) from U_HISIPS A WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND A.U_PREDISCHARGE=1 AND A.U_MGH=0 $filterExp ");
			if ($u_objRs->queryfetchrow()) $u_countpredischargealert = $u_objRs->fields[0];

			$u_objRs->queryopen("select count(*) from U_HISMEDSUPSTOCKREQUESTS A, U_HISMEDSUPSTOCKREQUESTITEMS B WHERE B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCID=A.DOCID AND  A.U_TODEPARTMENT IN ('".$objUsers->roleid."') AND A.COMPANY = '".$_SESSION["company"]."' AND A.BRANCH = '".$_SESSION["branch"]."' AND A.DOCSTATUS NOT IN ('CN','D','C')");
			if ($u_objRs->queryfetchrow()) $u_countincomingstockrequests = $u_objRs->fields[0];
			
		}	
	}
?>