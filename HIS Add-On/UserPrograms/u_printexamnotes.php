<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_printexamnotes";
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/journalvouchers.php");
	include_once("./classes/journalvoucheritems.php");
	include_once("./sls/enummonth.php");
	include_once("./sls/enumyear.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./utils/users.php");
	include_once("./utils/trxlog.php");
	include_once("./utils/branches.php");
	include_once("./classes/companies.php");
	include_once("./classes/branches.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/masterdatalinesschema.php");
	
	unset($enumyear["-"]);
	
	//var_dump($enummonth);
	
	$page->objectcode = $progid;
	$objCompanies = new companies(null,$objConnection);
	$objBranches = new branches(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	
	$objCompanies->getbykey($_SESSION["company"]);
	$objBranches->getbykey($_SESSION["company"],$_SESSION["branch"]);
	//$objRs->setdebug();
	$objRs->queryopen("select A.DOCNO, A.U_FILENO, A.U_REFTYPE, A.U_REFNO, A.U_PATIENTID, A.U_PATIENTNAME, A.U_REQDATE, A.U_REQTIME, A.U_STARTDATE, A.U_STARTTIME, A.U_ENDDATE, A.U_ENDTIME, A.U_GENDER, A.U_AGE, B.NAME AS U_SECTIONNAME, A.U_REMARKS_RTF, IF(C.NAME IS NULL, A.U_EXDOCTORNAME, C.NAME) AS U_DOCTORNAME, D.NAME AS U_DOCTORNAME2, E.USERNAME AS U_TESTBYNAME, IF(A.U_REFTYPE='IP',F.U_ROOMNO,'') AS U_ROOMNO from u_hischarges A LEFT JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT LEFT JOIN U_HISDOCTORS C ON C.CODE= A.U_DOCTORID LEFT JOIN U_HISDOCTORS D ON D.CODE=A.U_DOCTORID2 LEFT JOIN USERS E ON E.USERID=A.U_TESTBY LEFT JOIN U_HISIPS F ON F.DOCNO=A.U_REFNO AND A.U_REFTYPE='IP' LEFT JOIN U_HISOPS G ON G.DOCNO=A.U_REFNO AND A.U_REFTYPE='OP' where A.company='".$_SESSION["company"]."' and A.branch='".$_SESSION["branch"]."' and A.docno='".$_GET["docno"]."'");
	//var_dump($objRs->sqls);
	$objRs->queryfetchrow("NAME");
	
	;
		
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<style>
body.cl {font-size:-1;}
</style>
<body class="cl">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td align="center"><b><?php echo strtoupper($objCompanies->companyname); ?></b></td></tr>
	<tr><td align="center"><?php echo ucwords(strtolower(getAddress($objBranches->street,$objBranches->barangay,$objBranches->city,$objBranches->province,$objBranches->zip,$objBranches->country,false)))?></td></tr>
	<tr><td align="center"><b>DEPARTMENT OF RADIOLOGY</b></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td ><font size="-1">Name</font></td>
				<td width="10">:</td>
				<td><font size="-1"><b><?php echo $objRs->fields["U_PATIENTNAME"]; ?></b></font></td>
			    <td ><font size="-1">Hosp. No.</font></td>
			    <td width="10">:</td>
			    <td><font size="-1"><?php echo $objRs->fields["U_PATIENTID"]; ?></font></td>
			    <td ><font size="-1">No.</font></td>
			    <td width="10">:</td>
			    <td><?php echo $objRs->fields["U_FILENO"]; ?></td>
			<tr><td><font size="-1">Age / Sex</font></td><td>:</td><td><font size="-1"><?php echo $objRs->fields["U_AGE"]; ?> / <?php echo $objRs->fields["U_GENDER"]; ?></font></td>
			  <td><font size="-1">Adm No.</font></td>
			  <td>:</td>
			  <td><font size="-1"><?php echo $objRs->fields["U_REFTYPE"]; ?> - <?php echo $objRs->fields["U_REFNO"]; ?></font></td>
			  <td><font size="-1">Req Date</font></td>
			  <td>:</td>
			  <td><font size="-1"><?php echo formatDateToHTTP($objRs->fields["U_REQDATE"]); ?> - <?php echo formatTimeToHTTP($objRs->fields["U_REQTIME"]); ?></font></td>
			<tr><td><font size="-1">Room / Ward</font></td><td>:</td><td><font size="-1"><?php echo $objRs->fields["U_ROOMNO"]; ?></font></td>
			  <td><font size="-1">O.R. No.</font></td>
			  <td>:</td>
			  <td><font size="-1"><?php echo $objRs->fields["U_PAYREFNO"]; ?></font></td>
			  <td><font size="-1">Exam Date</font></td>
			  <td>:</td>
			  <td><font size="-1"><?php echo formatDateToHTTP($objRs->fields["U_STARTDATE"]); ?> - <?php echo formatTimeToHTTP($objRs->fields["U_STARTTIME"]); ?></font></td>
			<tr><td><font size="-1">Requested By</font></td><td>:</td><td><font size="-1"><?php echo $objRs->fields["U_DOCTORNAME"]; ?></font></td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td><font size="-1">Result Date</font></td>
			  <td>:</td>
			  <td><font size="-1"><?php echo formatDateToHTTP($objRs->fields["U_ENDDATE"]); ?> - <?php echo formatTimeToHTTP($objRs->fields["U_ENDTIME"]); ?></font></td>
			</tr>	
		</table>
	</td></tr>
	<tr><td><hr></td></tr>
	<tr><td align="center"><?php echo strtoupper($objRs->fields["U_SECTIONNAME"]); ?></td></tr>
	<tr><td><hr></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><div><?php echo $objRs->fields["U_REMARKS_RTF"]; ?></div></td></tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
			  <td>&nbsp;</td>
			  <td align="center">&nbsp;</td>
			  <td>&nbsp;</td>
			  <td align="center">&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td align="center">&nbsp;</td>
			  <td>&nbsp;</td>
			  <td align="center">&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
			<tr>
			  <td width= "10">&nbsp;</td>
			  <td width="300" align="center"><font size="-1"><b><?php echo $objRs->fields["U_DOCTORNAME2"]; ?></b></font></td>
			  <td>&nbsp;</td>
			  <td width="300" align="center"><font size="-1"><b><?php echo $objRs->fields["U_TESTBYNAME"]; ?></b></font></td>
		      <td width= "10">&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
				<td><hr></td>
				<td>&nbsp;</td>
				<td><hr></td>
			    <td>&nbsp;</td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td align="center"><font size="-1">Radiologist</font></td>
			  <td>&nbsp;</td>
			  <td align="center"><font size="-1">Radiologic Technologist/Radiology Secretary</font></td>
		      <td>&nbsp;</td>
		  </tr>
			<tr>
			  <td>&nbsp;</td>
			  <td align="center">&nbsp;</td>
			  <td>&nbsp;</td>
			  <td align="center">&nbsp;</td>
			  <td>&nbsp;</td>
		  </tr>
		</table>
	</td></tr>	
</table></td></tr>  
</table>
</body>
<script>
	window.print();
</script>
</html>

