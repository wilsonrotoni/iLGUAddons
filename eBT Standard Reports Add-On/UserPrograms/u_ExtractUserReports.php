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
	$objRs->queryopen("select * from udrs");
	while ($objRs->queryfetchrow("NAME")) {
		//var_dump($objRs->fields);
		$strExport .= "evaltrx:addUDR('".$objRs->fields["REPORTID"]."','".addslashes($objRs->fields["REPORTNAME"])."','".$objRs->fields["REPORTACTION"]."','".$objRs->fields["DBTYPE"]."',".$objRs->fields["SYSMANAGE"].");\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from udrparams where reportid in (select reportid from udrs)  order by REPORTID, SEQ, PARAMID");
	while ($objRs->queryfetchrow("NAME")) {
		//function addUDRParameter($udrid,$paramid,$paramname="",$alias="",$format="any",$descretevalue=1,$inputtype="",$seq=0,$defaultvalue=null,$mandatory=0,$linktable=null) {

		$strExport .= "evaltrx:addUDRParam('".$objRs->fields["REPORTID"]."','".$objRs->fields["PARAMID"]."','".addslashes($objRs->fields["PARAMNAME"])."','".$objRs->fields["ALIAS"]."','".$objRs->fields["FORMAT"]."',".$objRs->fields["DESCRETEVALUE"].",'".$objRs->fields["INPUTTYPE"]."',".$objRs->fields["SEQ"].",".iif($objRs->fields["SETDEFAULTVALUE"]==1,"'".addslashes($objRs->fields["DEFAULTVALUE"])."'","null").",".$objRs->fields["REQUIRED"].",".iif($objRs->fields["SETLINKTABLE"]==1,"'".addslashes($objRs->fields["LINKTABLE"])."'","null").");\r\n"; 
	}
	
	$strExport .= "\r\n"; 

	$objRs->queryopen("select * from udrparamvalues where reportid in (select reportid from udrs) order by REPORTID, PARAMID, PARAMVALUE");
	while ($objRs->queryfetchrow("NAME")) {
		//function addUDRParameter($udrid,$paramid,$paramname="",$alias="",$format="any",$descretevalue=1,$inputtype="",$seq=0,$defaultvalue=null,$mandatory=0,$linktable=null) {

		$strExport .= "evaltrx:addUDRParamValue('".$objRs->fields["REPORTID"]."','".$objRs->fields["PARAMID"]."','".$objRs->fields["PARAMVALUE"]."','".addslashes($objRs->fields["PARAMVALUEDESC"])."');\r\n"; 
	}
	
	$strExport .= "\r\n"; 

	$objRs->queryopen("select * from objectfss where objectcode like 'urpt_%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:addUDRFormattedSearch('".substr($objRs->fields["OBJECTCODE"],5)."','".$objRs->fields["FIELDNAME"]."','".$objRs->fields["FSTYPE"]."','".$objRs->fields["FSSOURCE"]."','".addslashes(iif($objRs->fields["FSSOURCE"]=="Q",$objRs->fields["FSQUERYVALUE"],$objRs->fields["FSQUERYCODE"]))."','".$objRs->fields["REFRESHTYPE"]."',".$objRs->fields["VALIDATE"].");\r\n"; 
	}
	
	$strExport .= "\r\n"; 

	$objRs->queryopen("select * from objectfstriggers where objectcode like 'urpt_%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:addUDRFormattedSearchTrigger('".substr($objRs->fields["OBJECTCODE"],5)."','".$objRs->fields["FIELDNAME"]."','".$objRs->fields["FIELDTRIGGER"]."');\r\n"; 
	}

	$strExport .= "\r\n"; 

	$objRs->queryopen("select * from objectfsvalues where objectcode like 'urpt_%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:addUDRFormattedSearchValue('".substr($objRs->fields["OBJECTCODE"],5)."','".$objRs->fields["FIELDNAME"]."','".$objRs->fields["FIELDVALUE"]."');\r\n"; 
	}

	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select * from objectcaptions where objectcode like 'urpt_%'");
	while ($objRs->queryfetchrow("NAME")) {
		//function addSysRptFormattedSearch($udrid,$paramid,$fstype="LQ",$fssource="Q",$fscommand="",$refreshtype="M",$validate=0) {
		$strExport .= "evaltrx:setUDRParamCaption('".substr($objRs->fields["OBJECTCODE"],5)."','".$objRs->fields["FIELDNAME"]."','".addslashes($objRs->fields["FIELDDESC"])."');\r\n"; 
	}
	
	$strExport .= "\r\n"; 

	$objRs->queryopen("select * from reportlayouts where progid in (select concat('urpt_',reportid) from udrs)");
	while ($objRs->queryfetchrow("NAME")) {
		//function addUDRParameter($udrid,$paramid,$paramname="",$alias="",$format="any",$descretevalue=1,$inputtype="",$seq=0,$defaultvalue=null,$mandatory=0,$linktable=null) {

		$strExport .= "evaltrx:addUDRLayout('".substr($objRs->fields["PROGID"],5)."','".$objRs->fields["PRINTLAYOUT"]."',".$objRs->fields["ISDEFAULT"].",'".addslashes($objRs->fields["LOCATION"])."','".$objRs->fields["REPORTFILE"]."','".$objRs->fields["VIEWERTYPE"]."',".$objRs->fields["ISACTIVE"].",'".$objRs->fields["USERPARAMS"]."','".$objRs->fields["USERGROUPPARAMS"]."','".$objRs->fields["USERROLEPARAMS"]."');\r\n"; 
	}
	
	$strExport .= "\r\n"; 

	$objRs->queryopen("select * from reportlayouts where progid in (select concat('u_',udoid) from udos)");
	while ($objRs->queryfetchrow("NAME")) {
		//function addUDRParameter($udrid,$paramid,$paramname="",$alias="",$format="any",$descretevalue=1,$inputtype="",$seq=0,$defaultvalue=null,$mandatory=0,$linktable=null) {

		$strExport .= "evaltrx:addUDOLayout('".substr($objRs->fields["PROGID"],2)."','".$objRs->fields["PRINTLAYOUT"]."',".$objRs->fields["ISDEFAULT"].",'".addslashes($objRs->fields["LOCATION"])."','".$objRs->fields["REPORTFILE"]."','".$objRs->fields["VIEWERTYPE"]."',".$objRs->fields["ISACTIVE"].",'".$objRs->fields["USERPARAMS"]."','".$objRs->fields["USERGROUPPARAMS"]."','".$objRs->fields["USERROLEPARAMS"]."');\r\n"; 
	}
	
	$strExport .= "\r\n"; 
	
	$objRs->queryopen("select a.DOCTYPE, b.DOCSERIESNAME, a.PRINTLAYOUT, a.ISDEFAULT, a.LOCATION, a.REPORTFILE, a.VIEWERTYPE, a.ISACTIVE, a.USERPARAMS, a.USERGROUPPARAMS, a.USERROLEPARAMS from docseriesprintlayouts a, docseries b where a.docseries=b.docseries and a.location not in ('[Default]','[ASP]','[Win32]','eBT Standard Reports Add-On')");
	while ($objRs->queryfetchrow("NAME")) {
		//function addUDRParameter($udrid,$paramid,$paramname="",$alias="",$format="any",$descretevalue=1,$inputtype="",$seq=0,$defaultvalue=null,$mandatory=0,$linktable=null) {

		$strExport .= "evaltrx:addPrintLayout('".$objRs->fields["DOCTYPE"]."','".addslashes($objRs->fields["DOCSERIESNAME"])."','".$objRs->fields["PRINTLAYOUT"]."',".$objRs->fields["ISDEFAULT"].",'".addslashes($objRs->fields["LOCATION"])."','".$objRs->fields["REPORTFILE"]."','".$objRs->fields["VIEWERTYPE"]."',".$objRs->fields["ISACTIVE"].",'".$objRs->fields["USERPARAMS"]."','".$objRs->fields["USERGROUPPARAMS"]."','".$objRs->fields["USERROLEPARAMS"]."');\r\n"; 
	}
	
	$strExport .= "\r\n"; 
	
	
	header('Content-Type: application/txt'); 
	header('Content-Length: '.strlen($strExport));
	header('Content-Disposition: inline; filename="'.$_SESSION["dbname"] .'_userreports.txt"'); 
	echo $strExport;
	die();
?>

