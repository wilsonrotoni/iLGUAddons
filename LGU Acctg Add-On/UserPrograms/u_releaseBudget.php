<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_releaseBudget";
	
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
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	require_once ("../common/classes/excel.php");	
	
	$objRs = new recordset(null,$objConnection);
	
	function onFormAction($action,$opt="") {
		global $objConnection;
		global $page;
		global $company;
		global $branch;		
		$actionReturn = true;
		switch (strtolower($action)) {
			case "u_release":
			
				$objRs = new recordset(null,$objConnection);
				
				$year= $page->getitemstring("year");
				$profitcenter= $page->getitemstring("profitcenter");
				$rldate= $page->getitemdate("rldate");
				$rlq1= $page->getitemstring("rlq1");
				$rlq2= $page->getitemstring("rlq2");
				$rlq3= $page->getitemstring("rlq3");
				$rlq4= $page->getitemstring("rlq4");
				$rl = round(($rlq1*0.25)+($rlq2*0.25)+($rlq3*0.25)+($rlq4*0.25),0);
				
				$profitcenterExp = "";
				if ($profitcenter!="") $profitcenterExp = " and a.u_profitcenter='$profitcenter'";
				$rlq1Exp = ", u_rlq1=".$rlq1;
				$rlq2Exp = ", u_rlq2=".$rlq2;
				$rlq3Exp = ", u_rlq3=".$rlq3;
				$rlq4Exp = ", u_rlq4=".$rlq4;
				$rlq1Exp2 = "+round(u_q1*".($rlq1/100).",2)";
				$rlq2Exp2 = "+round(u_q2*".($rlq2/100).",2)";
				$rlq3Exp2 = "+round(u_q3*".($rlq3/100).",2)";
				$rlq4Exp2 = "+round(u_q4*".($rlq4/100).",2)";
				
				$obju_LGUBudget = new masterdataschema_br(null,$objConnection,"u_lgubudget");
				$obju_LGUBudgetGLs = new masterdatalinesschema_br(null,$objConnection,"u_lgubudgetgls");
				
				$objConnection->beginwork();
				
				$objRs->queryopen("select a.code, a.u_profitcenter, b.u_glacctno, b.u_glacctname, b.u_slcode, b.u_sldesc, b.u_yr, b.u_rl, b.u_rlamt from u_lgubudget a inner join u_lgubudgetgls b on b.company=a.company and b.branch=a.branch and b.code=a.code where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_yr = '$year' $profitcenterExp");
				while ($objRs->queryfetchrow("NAME")) {
					if ($rl>$objRs->fields["u_rl"]) {
						if ($obju_LGUBudget->getbykey($objRs->fields["code"])) {
							if ($obju_LGUBudgetGLs->getbysql("CODE='".$objRs->fields["code"]."' AND U_GLACCTNO='".$objRs->fields["u_glacctno"]."' AND U_SLCODE='".$objRs->fields["u_slcode"]."'")) {
								$rlamt = $objRs->fields["u_yr"]*($rl/100);
								$rldiff = $rlamt - $objRs->fields["u_rlamt"];
								$obju_LGUBudgetGLs->setudfvalue("u_rlq1",$rlq1);
								$obju_LGUBudgetGLs->setudfvalue("u_rlq2",$rlq2);
								$obju_LGUBudgetGLs->setudfvalue("u_rlq3",$rlq3);
								$obju_LGUBudgetGLs->setudfvalue("u_rlq4",$rlq4);
								$obju_LGUBudgetGLs->setudfvalue("u_rlamt",$rlamt);
								$obju_LGUBudgetGLs->setudfvalue("u_rl",$rl);
								$obju_LGUBudgetGLs->setudfvalue("u_lstrldate",$rldate);
								$obju_LGUBudgetGLs->privatedata["header"] = $obju_LGUBudget;
								
								$actionReturn = $obju_LGUBudgetGLs->update($obju_LGUBudgetGLs->code,$obju_LGUBudgetGLs->lineid,$obju_LGUBudgetGLs->rcdversion);
								/*if ($actionReturn) {
									$obju_LGuBudgetReleaseLogs = new masterdataschema_br(NULL,$objConnection,"u_lgubudgetreleaselogs");
									$obju_LGuBudgetReleaseLogs->prepareadd();
									$obju_LGuBudgetReleaseLogs->code = str_pad( getNextId("u_lgubudgetreleaselogs",$objConnection), 7, "0", STR_PAD_LEFT);
									$obju_LGuBudgetReleaseLogs->name = $obju_LGuBudgetReleaseLogs->code;
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_date",currentdateDB());
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_yr",$year);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_profitcenter",$objRs->fields["u_profitcenter"]);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_glacctno",$objRs->fields["u_glacctno"]);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_glacctname",$objRs->fields["u_glacctname"]);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_slcode",$objRs->fields["u_slcode"]);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_sldesc",$objRs->fields["u_sldesc"]);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq1",$rlq1);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq2",$rlq2);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq3",$rlq3);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq4",$rlq4);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_rl",$rl);
									$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlamt",$rldiff);
									$actionReturn = $obju_LGuBudgetReleaseLogs->add();
								}*/
							}	
						//$actionReturn = $objRs->executesql("update u_lgubudgetgls set rcdversion=rcdversion+1,u_rlamt=0 $rlq1Exp2 $rlq2Exp2 $rlq3Exp2 $rlq4Exp2, u_rlq1='$rlq1', u_rlq2='$rlq2', u_rlq3='$rlq3', u_rlq4='$rlq4'  where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and code='".$objRs->fields["code"]."'",false);
						//var_dump("update u_lgubudgetgls set rcdversion=rcdversion+1,u_rlamt=0 $rlq1Exp2 $rlq2Exp2 $rlq3Exp2 $rlq4Exp2, u_rlq1='$rlq1', u_rlq2='$rlq2', u_rlq3='$rlq3', u_rlq4='$rlq4'  where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and code='".$objRs->fields["code"]."'");
							//var_dump($objRs->fields);
						}
					}		
					if (!$actionReturn) break;
					//break;
				}					
				
				/*if ($actionReturn) {
					$obju_LGuBudgetReleaseLogs = new masterdataschema_br(NULL,$objConnection,"u_lgubudgetreleaselogs");
					$obju_LGuBudgetReleaseLogs->prepareadd();
					$obju_LGuBudgetReleaseLogs->code = str_pad( getNextId("u_lgubudgetreleaselogs",$objConnection), 7, "0", STR_PAD_LEFT);
					$obju_LGuBudgetReleaseLogs->name = $obju_LGuBudgetReleaseLogs->code;
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_yr",$year);
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_profitcenter",$profitcenter);
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq1",$rlq1);
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq2",$rlq2);
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq3",$rlq3);
					$obju_LGuBudgetReleaseLogs->setudfvalue("u_rlq4",$rlq4);
					$actionReturn = $obju_LGuBudgetReleaseLogs->add();
				}*/
				
				if ($actionReturn) $objConnection->commit();
				else $objConnection->rollback();
				
				$_SESSION["dbmodified"] = 1;
				break;
		}
		
		return $actionReturn;
	}
	
	$filter="";
	
	$schema["year"]=createSchema("year","Year");
	$schema["profitcenter"]=createSchema("profitcenter","Profit Center");
	$schema["rlq1"]=createSchemaNumeric("rlq1","1st Qtr");
	$schema["rlq2"]=createSchemaNumeric("rlq2","2nd Qtr");
	$schema["rlq3"]=createSchemaNumeric("rlq3","3rd Qtr");
	$schema["rlq4"]=createSchemaNumeric("rlq4","4th Qtr");
	$schema["rldate"]=createSchemaDate("rldate","Release Date");
	
	if (!isset($httpVars["df_year"])) {
		$page->setitem("year",date('Y'));
		$page->setitem("rlq1",0);
		$page->setitem("rlq2",0);
		$page->setitem("rlq3",0);
		$page->setitem("rlq4",0);
		//$page->setitem("rldate",currentdate());
	//	$page->setitem("type","M");
	}

	$objGrid = new grid("T1");
	$objGrid->addcolumn(array('name'=>'profitcenter','description'=>'Profit Center','length'=>9));
	$objGrid->addcolumn(array('name'=>'profitcentername','description'=>'Profit Center Name','length'=>25));
	$objGrid->addcolumn(array('name'=>'glacctno','description'=>'G/L Account No.','length'=>12));
	$objGrid->addcolumn(array('name'=>'glacctname','description'=>'Description','length'=>25));
	$objGrid->addcolumn(array('name'=>'slcode','description'=>'S/L Code','length'=>6));
	$objGrid->addcolumn(array('name'=>'sldesc','description'=>'Description','length'=>25));
	$objGrid->addcolumn(array('name'=>'r1q1','description'=>'1st Qtr','length'=>5));
	$objGrid->addcolumn(array('name'=>'r1q2','description'=>'2nd Qtr','length'=>5));
	$objGrid->addcolumn(array('name'=>'r1q3','description'=>'3rd Qtr','length'=>5));
	$objGrid->addcolumn(array('name'=>'r1q4','description'=>'4th Qtr','length'=>5));
	$objGrid->addcolumn(array('name'=>'r1','description'=>'Overall','length'=>5));
	$objGrid->addcolumn(array('name'=>'r1amt','description'=>'Amount','length'=>10));
	$objGrid->addcolumn(array('name'=>'date','description'=>'Date','length'=>13));
	$objGrid->addcolumn(array('name'=>'datetime','description'=>'Date/Time','length'=>13));
	$objGrid->addcolumn(array('name'=>'username','description'=>'User Name','length'=>25));
	$objGrid->automanagecolumnwidth=false;
	$objGrid->columnalignment("r1amt","right");
	//$objGrid->height=100;
	//$objGrid->width=325;
	$objGrid->dataentry = false;
	
	require("./inc/formactions.php");
	
	if ($_SESSION["errormessage"]=="") {
	
		$objGrid->clear();
		$profitcenterExp="";
		if ($page->getitemstring("profitcenter")!="") {
			$profitcenterExp=" and (a.u_profitcenter='' or a.u_profitcenter='".$page->getitemstring("profitcenter")."')";
		}
		
		$objRs->queryopen("select a.u_profitcenter, b.profitcentername as u_profitcentername, a.u_glacctno, a.u_glacctname, a.u_slcode, a.u_sldesc, a.u_rlq1, a.u_rlq2, a.u_rlq3, a.u_rlq4, a.u_rl, a.u_rlamt, a.u_date, a.datecreated, c.username from u_lgubudgetreleaselogs a inner join users c on c.userid=a.createdby left join profitcenters b on b.profitcenter=a.u_profitcenter where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_yr='".$page->getitemstring("year")."' $profitcenterExp order by u_yr, u_profitcenter, u_glacctno, u_slcode, datecreated");
		while ($objRs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			
			//$objGrid->setitem(null,"profitcenter",formatDateToHttp($objRs->fields["u_asof"]));
			$objGrid->setitem(null,"profitcenter",$objRs->fields["u_profitcenter"]);
			$objGrid->setitem(null,"profitcentername",$objRs->fields["u_profitcentername"]);
			$objGrid->setitem(null,"glacctno",$objRs->fields["u_glacctno"]);
			$objGrid->setitem(null,"glacctname",$objRs->fields["u_glacctname"]);
			$objGrid->setitem(null,"slcode",$objRs->fields["u_slcode"]);
			$objGrid->setitem(null,"sldesc",$objRs->fields["u_sldesc"]);
			$objGrid->setitem(null,"r1q1",$objRs->fields["u_rlq1"]);
			$objGrid->setitem(null,"r1q2",$objRs->fields["u_rlq2"]);
			$objGrid->setitem(null,"r1q3",$objRs->fields["u_rlq3"]);
			$objGrid->setitem(null,"r1q4",$objRs->fields["u_rlq4"]);
			$objGrid->setitem(null,"r1",$objRs->fields["u_rl"]);
			$objGrid->setitem(null,"r1amt",formatNumericAmount($objRs->fields["u_rlamt"]));
			$objGrid->setitem(null,"date",formatDateToHttp($objRs->fields["u_date"]));
			$objGrid->setitem(null,"datetime",formatDateTimeToHttp($objRs->fields["datecreated"]));
			$objGrid->setitem(null,"username",$objRs->fields["username"]);
		}	
	}	
	
	$page->resize->addgrid("T1",20,160,false);
	
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
		switch (column) {
			case "year":
			case "profitcenter":
				formSubmit();
				break;
		}
		return true;
	}
	
	function onElementClick(element,column,table,row) {
		var rlamt=0;
		switch (column) {
			case "rlq1":
			case "rlq2":
			case "rlq3":
			case "rlq4":
				if (column=="rlq1" && getInputNumeric("rlq1")==0) {
					setInput("rlq2","0");
					setInput("rlq3","0");
					setInput("rlq4","0");
				}
				if (column=="rlq2" && getInputNumeric("rlq2")==0) {
					setInput("rlq3","0");
					setInput("rlq4","0");
				}
				if (column=="rlq3" && getInputNumeric("rlq3")==0){
					setInput("rlq4","0");
				}
				//if (column=="rlq2" && getInputNumeric("rlq1")==0) setInput("rlq2","0");
				//if (column=="rlq3" && getInputNumeric("rlq2")==0) setInput("rlq3","0");
				//if (column=="rlq4" && getInputNumeric("rlq3")==0) setInput("rlq4","0");

				if (column=="rlq2" && getInputNumeric("rlq2")==1) { 
					setInput("rlq1","1");
				}	
				if (column=="rlq3" && getInputNumeric("rlq3")==1) {
					setInput("rlq1","1");
					setInput("rlq2","1");
				}	
				if (column=="rlq4" && getInputNumeric("rlq4")==1) {
					setInput("rlq1","1");
					setInput("rlq2","1");
					setInput("rlq3","1");
				}	
			
				break;
		}
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
		if(action=="u_release") {
			if(isInputEmpty("year")) return false;
			if(isInputEmpty("rldate")) return false;
			if(getInputNumeric("rlq1")+getInputNumeric("rlq2")+getInputNumeric("rlq3")+getInputNumeric("rlq4")==0) {
				page.statusbar.showError("You must enter % in any of the quarters.");
				return false;
			}
		}
		
		return true;
	}	
	
	function onFormSubmitReturn(action,success,error) {
		if (success) {
			iframeDownloader.location = "../Addons/GPS/LGU Acctg Add-On/UserPrograms/Downloads/"+getGlobal("sessionid")+".xlsx";
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
			    <tr>
			      <td><label <?php genCaptionHtml($schema["rldate"],"") ?> >Release Date</label></td>
			      <td>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["rldate"]) ?> /></td>
		        </tr>
                <tr>
				  <td><!--DWLayoutEmptyCell-->&nbsp;</td>
				  <td>&nbsp;<label <?php genCaptionHtml($schema["rlq1"],"") ?> >1st Qtr:</label>&nbsp;<input type="text" size="3" <?php genInputTextHtml($schema["rlq1"]) ?> /><label class="lblobjs">%</label>&nbsp;&nbsp;<label <?php genCaptionHtml($schema["rlq2"],"") ?> >2nd Qtr:</label>&nbsp;<input type="text" size="3" <?php genInputTextHtml($schema["rlq2"]) ?> /><label class="lblobjs">%</label>&nbsp;&nbsp;<label <?php genCaptionHtml($schema["rlq3"],"") ?> >3rd Qtr:</label>&nbsp;<input type="text" size="3" <?php genInputTextHtml($schema["rlq3"]) ?> /><label class="lblobjs">%</label>&nbsp;&nbsp;<label <?php genCaptionHtml($schema["rlq4"],"") ?> >4th Qtr:</label>&nbsp;<input type="text" size="3" <?php genInputTextHtml($schema["rlq4"]) ?> /><label class="lblobjs">%</label></td>
			    </tr>
			    <tr>
			  <td></td>
			  <td>&nbsp;<a class="button" href="" onClick="<?php echo @$toolbarframe ?>formSubmit('u_release');return false;">Release</a></td>
			  </tr>
			<tr>
			  <td></td>
			  <td><!--DWLayoutEmptyCell-->&nbsp;</td>
			  </tr>
			<tr>
			  <td colspan="2"><?php $objGrid->draw(); ?></td>
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
