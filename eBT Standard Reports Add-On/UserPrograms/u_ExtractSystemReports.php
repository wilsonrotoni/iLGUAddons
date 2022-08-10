<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	//$progid = "u_ExtractInitDataFromSBO";
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./inc/formutils.php");
	include_once("./classes/sbotrxsync.php");
	//include_once("./inc/formaccess.php");

	$strExport = "";
	$objRs = new recordset(null,$objConnection);
	$objRs2 = new recordset(null,$objConnection);
	$objRs->queryopen("select * from sysrpts");
	while ($objRs->queryfetchrow("NAME")) {
		//var_dump($objRs->fields);
		$strExport .= "evaltrx:addSysRpt('".$objRs->fields["REPORTID"]."','".addslashes($objRs->fields["REPORTNAME"])."','".$objRs->fields["REPORTACTION"]."','".$objRs->fields["DBTYPE"]."');\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from sysrptparams where reportid in (select reportid from sysrpts)  order by REPORTID, SEQ, PARAMID");
	while ($objRs->queryfetchrow("NAME")) {
		//function addUDRParameter($udrid,$paramid,$paramname="",$alias="",$format="any",$descretevalue=1,$inputtype="",$seq=0,$defaultvalue=null,$mandatory=0,$linktable=null) {

		$strExport .= "evaltrx:addSysRptParam('".$objRs->fields["REPORTID"]."','".$objRs->fields["PARAMID"]."','".addslashes($objRs->fields["PARAMNAME"])."','".$objRs->fields["ALIAS"]."','".$objRs->fields["FORMAT"]."',".$objRs->fields["DESCRETEVALUE"].",'".$objRs->fields["INPUTTYPE"]."',".$objRs->fields["SEQ"].",".iif($objRs->fields["SETDEFAULTVALUE"]==1,"'".addslashes($objRs->fields["DEFAULTVALUE"])."'","null").",".$objRs->fields["REQUIRED"].",".iif($objRs->fields["SETLINKTABLE"]==1,"'".addslashes($objRs->fields["LINKTABLE"])."'","null").");\r\n"; 
	}
	
	$strExport .= "\r\n"; 

	$objRs->queryopen("select * from sysrptparamvalues where reportid in (select reportid from sysrpts) order by REPORTID, PARAMID, PARAMVALUE");
	while ($objRs->queryfetchrow("NAME")) {
		//function addUDRParameter($udrid,$paramid,$paramname="",$alias="",$format="any",$descretevalue=1,$inputtype="",$seq=0,$defaultvalue=null,$mandatory=0,$linktable=null) {

		$strExport .= "evaltrx:addSysRptParamValue('".$objRs->fields["REPORTID"]."','".$objRs->fields["PARAMID"]."','".$objRs->fields["PARAMVALUE"]."','".addslashes($objRs->fields["PARAMVALUEDESC"])."');\r\n"; 
	}
	
	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from objectfss where objectcode like 'sysRpt%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:addSysRptFormattedSearch('".substr($objRs->fields["OBJECTCODE"],6)."','".$objRs->fields["FIELDNAME"]."','".$objRs->fields["FSTYPE"]."','".$objRs->fields["FSSOURCE"]."','".iif($objRs->fields["FSSOURCE"]=="Q",$objRs->fields["FSQUERYVALUE"],$objRs->fields["FSQUERYCODE"])."','".$objRs->fields["REFRESHTYPE"]."',".$objRs->fields["VALIDATE"].");\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from objectfstriggers where objectcode like 'sysRpt%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:addSysRptFormattedSearchTrigger('".substr($objRs->fields["OBJECTCODE"],6)."','".$objRs->fields["FIELDNAME"]."','".$objRs->fields["FIELDTRIGGER"]."');\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from objectfsvalues where objectcode like 'sysRpt%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:addSysRptFormattedSearchValue('".substr($objRs->fields["OBJECTCODE"],6)."','".$objRs->fields["FIELDNAME"]."','".$objRs->fields["FIELDVALUE"]."');\r\n"; 
	}
	
	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from objectcaptions where objectcode like 'sysRpt%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:setSysRptParamCaption('".substr($objRs->fields["OBJECTCODE"],6)."','".$objRs->fields["FIELDNAME"]."','".addslashes($objRs->fields["FIELDDESC"])."');\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from reportlayouts where progid like 'sysRpt%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addUDRParameter($udrid,$paramid,$paramname="",$alias="",$format="any",$descretevalue=1,$inputtype="",$seq=0,$defaultvalue=null,$mandatory=0,$linktable=null) {

		$strExport .= "evaltrx:addSysRptLayout('".substr($objRs->fields["PROGID"],6)."','".$objRs->fields["PRINTLAYOUT"]."',".$objRs->fields["ISDEFAULT"].",'".addslashes($objRs->fields["LOCATION"])."','".$objRs->fields["REPORTFILE"]."','".$objRs->fields["VIEWERTYPE"]."',".$objRs->fields["ISACTIVE"].",'".$objRs->fields["USERPARAMS"]."','".$objRs->fields["USERGROUPPARAMS"]."','".$objRs->fields["USERROLEPARAMS"]."');\r\n"; 
	}
	
	$strExport .= "\r\n"; 

	$objRs->queryopen("select a.DOCTYPE, b.DOCSERIESNAME, a.PRINTLAYOUT, a.ISDEFAULT, a.LOCATION, a.REPORTFILE, a.VIEWERTYPE, a.ISACTIVE, a.USERPARAMS, a.USERGROUPPARAMS, a.USERROLEPARAMS from docseriesprintlayouts a, docseries b where a.docseries=b.docseries and a.location in ('[Default]','[ASP]','[Win32]','eBT Standard Reports Add-On')");
	while ($objRs->queryfetchrow("NAME")) {
		//function addUDRParameter($udrid,$paramid,$paramname="",$alias="",$format="any",$descretevalue=1,$inputtype="",$seq=0,$defaultvalue=null,$mandatory=0,$linktable=null) {

		$strExport .= "evaltrx:addPrintLayout('".$objRs->fields["DOCTYPE"]."','".addslashes($objRs->fields["DOCSERIESNAME"])."','".$objRs->fields["PRINTLAYOUT"]."',".$objRs->fields["ISDEFAULT"].",'".addslashes($objRs->fields["LOCATION"])."','".$objRs->fields["REPORTFILE"]."','".$objRs->fields["VIEWERTYPE"]."',".$objRs->fields["ISACTIVE"].",'".$objRs->fields["USERPARAMS"]."','".$objRs->fields["USERGROUPPARAMS"]."','".$objRs->fields["USERROLEPARAMS"]."');\r\n"; 
	}
	
	$strExport .= "\r\n"; 

	header('Content-Type: application/txt'); 
	header('Content-Length: '.strlen($strExport));
	header('Content-Disposition: inline; filename="'.$_SESSION["dbname"] .'_systemreports.txt"'); 
	echo $strExport;
	die();
?>

