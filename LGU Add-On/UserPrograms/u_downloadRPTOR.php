<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_downloadRPTOR";
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/journalvouchers.php");
	include_once("./classes/journalvoucheritems.php");
	include_once("./sls/enumyear.php");
	include_once("./sls/profitcenters.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./utils/users.php");
	include_once("./utils/trxlog.php");
	include_once("./utils/branches.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/masterdatalinesschema.php");
	require_once ("../common/classes/excel.php");	
	
	$objRs = new recordset(null,$objConnection);
	
	function getCellAddress($col,$row=0) {
		$ch = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
					'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
					'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
					'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
					'DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ',
					'EA','EB','EC','ED','EE','EF','EG','EH','EI','EJ','EK','EL','EM','EN','EO','EP','EQ','ER','ES','ET','EU','EV','EW','EX','EY','EZ',
					'FA','FB','FC','FD','FE','FF','FG','FH','FI','FJ','FK','FL','FM','FN','FO','FP','FQ','FR','FS','FT','FU','FV','FW','FX','FY','FZ',
					'GA','GB','GC','GD','GE','GF','GG','GH','GI','GJ','GK','GL','GM','GN','GO','GP','GQ','GR','GS','GT','GU','GV','GW','GX','GY','GZ',
					'HA','HB','HC','HD','HE','HF','HG','HH','HI','HJ','HK','HL','HM','HN','HO','HP','HQ','HR','HS','HT','HU','HV','HW','HX','HY','HZ',
					'IA','IB','IC','ID','IE','IF','IG','IH','II','IJ','IK','IL','IM','IN','IO','IP','IQ','IR','IS','IT','IU','IV','IW','IX','IY','IZ',
					'JA','JB','JC','JD','JE','JF','JG','JH','JI','JJ','JK','JL','JM','JN','JO','JP','JQ','JR','JS','JT','JU','JV','JW','JX','JY','JZ',
					'KA','KB','KC','KD','KE','KF','KG','KH','KI','KJ','KK','KL','KM','KN','KO','KP','KQ','KR','KS','KT','KU','KV','KW','KX','KY','KZ',
					'LA','LB','LC','LD','LE','LF','LG','LH','LI','LJ','LK','LL','LM','LN','LO','LP','LQ','LR','LS','LT','LU','LV','LW','LX','LY','LZ');
		/*
		$c1 = intval($col / 26);
		$c2 = $col -($c1*26);
		if ($c1>0 && $c2==0) $c2 = 1;
		if ($c1>0) return $ch[$c1-1] . $ch[$c2-1] . iif($row>0,$row,"");
		else return $ch[$c2-1] . iif($row>0,$row,"");
		
		*/
		 return $ch[$col-1] . iif($row>0,$row,"");
	}
	
	function setCellBGColor($objExcel,$sheet,$r1,$c1,$r2,$c2,$color=3) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->interior->colorindex = $color;
	}
	
	function setCellWidth($objExcel,$sheet,$start,$end,$width=12) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		$sheets->columns(getCellAddress($start).":".getCellAddress($end))->columnwidth = $width;
	}
	
	function setCellFormat($objExcel,$sheet,$r1,$c1,$r2,$c2,$format) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		if ($r1!=0)	$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->numberformat = $format;
		else $sheets->columns(getCellAddress($c1).":".getCellAddress($c2))->numberformat = $format;
	}

	function setCellFontWeight($objExcel,$sheet,$r1,$c1,$r2,$c2,$bold=true) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->font->bold = $bold;
	}
	
		function setCellBorder($objExcel,$sheet,$start,$end,$border) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		$sheets->columns(getCellAddress($start).":".getCellAddress($end))->borders->linestyle = $border;
	}

	function setCellBorderAll($objExcel,$sheet,$r1,$c1,$r2,$c2,$border=1,$thick=1) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->borders->linestyle = $border;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->borders->weight = $thick;
	}

	function setCellBorderTop($objExcel,$sheet,$r1,$c1,$r2,$c2,$border=1,$thick=1) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->borders(8)->linestyle = $border;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->borders(8)->weight = $thick;
	}

	function setCellBorderLeft($objExcel,$sheet,$r1,$c1,$r2,$c2,$border=1,$thick=1) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->borders(7)->linestyle = $border;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->borders(7)->weight = $thick;
	}

	function setCellBorderBottom($objExcel,$sheet,$r1,$c1,$r2,$c2,$border=1,$thick=1) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->borders(9)->linestyle = $border;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->borders(9)->weight = $thick;
	}

	function setCellBorderRight($objExcel,$sheet,$r1,$c1,$r2,$c2,$border=1,$thick=1) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->borders(10)->linestyle = $border;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->borders(10)->weight = $thick;
	}

	
	
	function setMerge($objExcel,$sheet,$r1,$c1,$r2,$c2,$merge=true) {
		$sheets = $objExcel->ex->Application->Worksheets($sheet);
		$sheets->activate;
		//var_dump(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2);
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->HorizontalAlignment = -4108;
		$sheets->range(getCellAddress($c1).$r1.":".getCellAddress($c2).$r2)->merge();
	}

	
	function onFormAction($action,$opt="") {
		global $objConnection;
		global $page;
		global $company;
		global $branch;		
		$actionReturn = true;
		switch (strtolower($action)) {
			case "u_download":
			
				$objRs = new recordset(null,$objConnection);
				
				$objExcel = new Excel;
				$template = realpath("../Addons/GPS/LGU Acctg Add-On/UserPrograms/");
				$file="";
				
				$sheet="AIP";
				$template .= "Templates\\AIP.xlsx".$file;
				
				$year= $page->getitemstring("year");
				$profitcenter= $page->getitemstring("profitcenter");
				$profitcenterExp = "";
				if ($profitcenter!="") $profitcenterExp = " and a.u_profitcenter='$profitcenter'";

				$objExcel->openxl($template,"","");

				$col = 1;
				$row = 2;
				
				//var_dump("select a.u_yr, a.u_profitcenter, b.u_refcode, b.u_description, b.u_office, b.u_start, b.u_completion, b.u_expectedoutput, b.u_funding, b.u_ps, b.u_mooe, b.u_co, b.u_total, b.u_cca, b.u_ccm, b.u_cctc from u_lguaip a inner join u_lguaipitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_yr = '$year' $profitcenterExp");
				$objRs->queryopen("select a.u_yr, a.u_profitcenter, b.u_refcode, b.u_description, b.u_office, b.u_start, b.u_completion, b.u_expectedoutput, b.u_funding, b.u_ps, b.u_mooe, b.u_co, b.u_total, b.u_cca, b.u_ccm, b.u_cctc from u_lguaip a inner join u_lguaipitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_yr = '$year' $profitcenterExp");
				while ($objRs->queryfetchrow("NAME")) {
					$data = array();
					array_push($data,$objRs->fields["u_yr"]);
					array_push($data,$objRs->fields["u_profitcenter"]);
					array_push($data,$objRs->fields["u_refcode"]);
					array_push($data,$objRs->fields["u_description"]);
					array_push($data,$objRs->fields["u_office"]);
					array_push($data,$objRs->fields["u_start"]);
					array_push($data,$objRs->fields["u_completion"]);
					array_push($data,$objRs->fields["u_expectedoutput"]);
					array_push($data,$objRs->fields["u_funding"]);
					array_push($data,$objRs->fields["u_ps"]);
					array_push($data,$objRs->fields["u_mooe"]);
					array_push($data,$objRs->fields["u_co"]);
					array_push($data,$objRs->fields["u_total"]);
					array_push($data,$objRs->fields["u_cca"]);
					array_push($data,$objRs->fields["u_ccm"]);
					array_push($data,$objRs->fields["u_cctc"]);
					$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+count($data),$row), $data);
					$row++;
				}					
				$uploadpath = realpath("../Addons/GPS/LGU Add-On/UserPrograms/");
				$uploadpath .= "Downloads\\";
				mkdirr($uploadpath);
				$objExcel->saveas($uploadpath .session_id() ,"","xlsx") ; 
					
				$objExcel->closeXL();
				unset($objExcel);
			
				$_SESSION["dbmodified"] = 1;
				break;
		}
		
		return $actionReturn;
	}
	
	$filter="";
	
	$schema["year"]=createSchema("year","Year");
	$schema["profitcenter"]=createSchema("profitcenter","Profit Center");
	
	if (!isset($httpVars["df_year"])) {
		$page->setitem("year",date('Y'));
	//	$page->setitem("type","M");
	}

	require("./inc/formactions.php");
	
	//$page->resize->addgridobject($objGrid,10,140,false);	
	
	//$page->resize->addgrid("T1",20,100,false);
	//$schema["gdate"]["attributes"]="disabled";
	//$httpVars["df_gdate"] = date("m/d/Y");
	
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<?php if ($formActionRequested!="u_print") { ?>
	<title><?php echo @$pageTitle ?></title>
<?php } ?>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>
</head>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/chartofaccounts.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatechartofaccounts.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
		try {
			if (window.opener.getVar("objectcode")=="U_LGUAIP") {
				setInput("year",window.opener.getInput("u_yr"));
				setInput("profitcenter",window.opener.getInput("u_profitcenter"));
				disableInput("year");
				disableInput("profitcenter");
			}
		} catch (theError) {
		}
	}	

	function onElementChange(element,column,table,row) {
		var result,result2,hiddenhtml,rowHtml="",rc,params="",grossamount=0,vat=0,interbranch=0;
		return true;
	}

	function onElementValidate(element,column,table,row) {
		return true;
	}

	function onElementCFLGetParams(element) {
		var params = new Array();
		return params;
	}
	
	function printData() {
		if(isInputEmpty("empname")) return false;
		 p_form = document.forms[0];
		 var temptarget = p_form.target;
		 p_form.target = "pdfwin";
		 setVar('formAction',"u_print");
		var win_width = screen.width; // - 300;
		var win_height = screen.height;
		var win_left = (screen.width / 2) - (win_width / 2);
		var win_top = 0;
			 
		 window.open("","pdfwin",'toolbar=no,location=no,status=no,menubar=yes,scrollbars=yes,resizable=yes,alwaysRaised,modal=yes,titlebar=no' 
		+ ",width=" + win_width + ",height=" + win_height
		+ ",screenX=" + win_left + ",left=" + win_left 
		+ ",screenY=" + win_top + ",top=" + win_top + ""); 
		p_form.submit();
		p_form.target = temptarget;
		 setVar('formAction',"");
		//hideAjaxProcess();
	}
	
	function onFormSubmit(action) {
		if(action=="u_view") {
			if(isInputEmpty("year")) return false;
		}
		
		return true;
	}	
	
	function onFormSubmitReturn(action,success,error) {
		if (success) {
			iframeDownloader.location = "../Addons/GPS/LGU Add-On/UserPrograms/Downloads/"+getGlobal("sessionid")+".xlsx";
			//alert("Operation ended successfully.");
		} else {
			alert("Error: " + error);
		}	
	}

	function onFormSubmitted(action) {
		showAjaxProcess();
	}	
	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<?php if ($formActionRequested!="u_print") { ?>
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td class="labelPageHeader" >&nbsp;<?php echo @$pageHeader ?>&nbsp;</td></tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr>
	<td>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		  <!--DWLayoutTable-->
			<tr>
			  <td width="108"><label <?php genCaptionHtml($schema["year"],"") ?> >Year</label></td>
				<td>&nbsp;<select <?php genSelectHtml($schema["year"],array("loadenumyear",$httpVars['year'],"")) ?> ></select></td>
				</tr>
			
			 <td><label <?php genCaptionHtml($schema["profitcenter"],"") ?> >Profit Center</label></td>
				<td>&nbsp;<select <?php genSelectHtml($schema["profitcenter"],array("loadprofitcenters",$httpVars['year'],":[All]")) ?> ></select></td>
				</tr>
			
			<tr class="fillerRow5px"><td colspan="2">&nbsp;</td></tr>
			<tr>
			  <td></td>
			  <td>&nbsp;<a class="button" href="" onClick="<?php echo @$toolbarframe ?>formSubmit('u_download');return false;">Download</a></td>
			  </tr>
		</table>	</td>
</tr>
<tr class="fillerRow10px"><td></td></tr>
<?php } ?> 	
<!-- end content -->
</table>
</td><td width="10">&nbsp;</td></tr></table>
</FORM>
<?php require("./bofrms/ajaxprocess.php"); ?>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>

<?php $page->writeEndScript(); ?>
<?php	
	//$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_PostNROpeningBalanceToolbar.php");
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
