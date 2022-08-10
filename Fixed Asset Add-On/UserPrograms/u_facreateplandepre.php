<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_facreateplandepre";
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/masterdatalinesschema.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./sls/brancheslist.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./utils/trxlog.php");
	
	$page->objectcode = $progid;
	
	function onFormAction($action,$opt="") {
		global $objConnection;
		global $objGrid;
		global $page;
		global $filter;
		$actionReturn = true;
		
		if(strtolower($action)=="u_post") {
			$obju_FA = new masterdataschema(NULL,$objConnection,"u_fa");
			
			
			$objConnection->beginwork();
			for($i=0;$i<= $objGrid->recordcount;$i++) {
				if($page->getitemstring("chxboxT1r".$i) == "1") {
					if ($obju_FA->getbykey($page->getcolumnstring("T1",$i,"facode"))) {
						$obju_FA->setudfvalue("u_depredate",formatDateToDB($page->getcolumnstring("T1",$i,"depredate")));
						$obju_FA->setudfvalue("u_remainlife",$page->getcolumnnumeric("T1",$i,"remainlife"));
						$obju_FA->setudfvalue("u_salvagevalue",$page->getcolumndecimal("T1",$i,"salvagevalue"));
						$obju_FA->setudfvalue("u_bookvalue",$obju_FA->getudfvalue("u_cost") - $obju_FA->getudfvalue("u_accumdepre") - $obju_FA->getudfvalue("u_salvagevalue"));
						$actionReturn = $obju_FA->update($obju_FA->code,$obju_FA->rcdversion);
					} else $actionReturn = raiseError("Unable to retrieve Fixed Asset [".$page->getcolumnstring("T1",$i,"facode")."].");	
				}
				if (!$actionReturn) break;
			}
			//if ($actionReturn) raiseError("onFormAction");
			if ($actionReturn) $objConnection->commit();
			else $objConnection->rollback();
			
			onFormAction("edit",$filter);
		}
		return $actionReturn;
	}
	$filter="";
	
	$objGrid = new grid("T1",$httpVars,true);
	$objGrid->addcolumn("chxbox");
	$objGrid->addcolumn("branch");
	$objGrid->addcolumn("facode");
	$objGrid->addcolumn("faname");
	$objGrid->addcolumn("itemcode");
	$objGrid->addcolumn("itemdesc");
	$objGrid->addcolumn("cost");
	$objGrid->addcolumn("acquidate");
	$objGrid->addcolumn(createSchemaDate("depredate"));
	$objGrid->addcolumn("remainlife");
	$objGrid->addcolumn(createSchemaAmount("salvagevalue"));
	$objGrid->columntitle("branch","Branch");
	$objGrid->columntitle("facode","Asset Code");
	$objGrid->columnwidth("facode",20);
	$objGrid->columntitle("faname","Asset Name");
	$objGrid->columnwidth("faname",20);
	$objGrid->columntitle("itemcode","Item Code");
	$objGrid->columnwidth("itemcode",15);
	$objGrid->columntitle("itemdesc","Item Description");
	$objGrid->columnwidth("itemdesc",20);
	$objGrid->columntitle("cost","Cost");
	$objGrid->columnwidth("cost",12);
	$objGrid->columntitle("acquidate","Acquisition Date");
	$objGrid->columnwidth("acquidate",14);
	$objGrid->columntitle("depredate","Depreciation Date");
	$objGrid->columnwidth("depredate",14);
	$objGrid->columntitle("remainlife","Asset Life (Months)");
	$objGrid->columnwidth("remainlife",17);
	$objGrid->columntitle("salvagevalue","Salvage Value");
	$objGrid->columnwidth("salvagevalue",11);

	$objGrid->columnalignment("cost","right");
	$objGrid->columnalignment("salvagevalue","right");
	$objGrid->columnalignment("remainlife","right");

	$objGrid->columninput("depredate","type","text");
	$objGrid->columninput("remainlife","type","text");
	$objGrid->columninput("salvagevalue","type","text");
	$objGrid->columninput("depredate","disabled","{chxbox}==0");
	$objGrid->columninput("remainlife","disabled","{chxbox}==0");
	$objGrid->columninput("salvagevalue","disabled","{chxbox}==0");
	//$objGrid->columnattributes("depredate","disabled");
	//$objGrid->columnattributes("remainlife","disabled");
	//$objGrid->columnattributes("salvagevalue","disabled");
	
	$objGrid->columninput("chxbox","type","checkbox");
	$objGrid->columninput("chxbox","value",1);
	$objGrid->columnattributes("chxbox","enable");
	$objGrid->columnwidth("chxbox",2);
	$objGrid->columntitle("chxbox","");
	$objGrid->dataentry = false;
	$objGrid->automanagecolumnwidth = false;

	$schema["faclass"] = createSchema("faclass");
	
	require("./inc/formactions.php");
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select u_famgmnt from companies where companycode='".$_SESSION["company"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["famgmnt"] = $objRs->fields["u_famgmnt"]; 
	}
	
	$filterExp = "";
	$filterExp = genSQLFilterString("A.U_FACLASS",$filterExp,$page->getitemstring("faclass"));
	if ($page->privatedata["famgmnt"]=="BR") {
		$filterExp = genSQLFilterString("A.U_BRANCH",$filterExp,$_SESSION["branch"]);
	}
		
	if ($filterExp !="") $filterExp = " and " . $filterExp;
	
	if ($_SESSION["errormessage"]=="" && $page->getitemstring("faclass") != "") {
		$objGrid->clear();
		$objRs->setdebug();
		$objRs->queryopen("select A.CODE AS U_FACODE, A.NAME AS U_FANAME, A.U_FACLASS, A.U_BRANCH, A.U_ITEMCODE, A.U_ITEMDESC, A.U_COST, A.U_ACQUIDATE, A.U_DEPREDATE, A.U_LIFE, A.U_SALVAGEVALUE, B.NAME AS U_FACLASSNAME from U_FA A, U_FACLASS B where B.CODE=A.U_FACLASS AND (A.U_REMAINLIFE=0 OR ISNULL(A.U_DEPREDATE)) AND A.U_BOOKVALUE>0 $filterExp");
		
		while ($objRs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			$objGrid->setitem(null,"chxbox",0);
			$objGrid->setitem(null,"branch",$objRs->fields["U_BRANCH"]);
			$objGrid->setitem(null,"facode",$objRs->fields["U_FACODE"]);
			$objGrid->setitem(null,"faname",$objRs->fields["U_FANAME"]);
			$objGrid->setitem(null,"itemcode",$objRs->fields["U_ITEMCODE"]);
			$objGrid->setitem(null,"itemdesc",$objRs->fields["U_ITEMDESC"]);
			$objGrid->setitem(null,"cost",formatNumericAmount($objRs->fields["U_COST"]));
			$objGrid->setitem(null,"acquidate",formatDate($objRs->fields["U_ACQUIDATE"]));
			$objGrid->setitem(null,"depredate",formatDate($objRs->fields["U_DEPREDATE"]));
			$objGrid->setitem(null,"remainlife",$objRs->fields["U_LIFE"]);
                        if($objRs->fields["U_SALVAGEVALUE"] > 0) {
                            $objGrid->setitem(null,"salvagevalue",formatNumericAmount($objRs->fields["U_SALVAGEVALUE"]));
                        } else {
                            $objGrid->setitem(null,"salvagevalue",formatNumericAmount($objRs->fields["U_COST"] * .05));
                        }
			
		}
	}	
	
	//$page->resize->addgrid("T1",20,100,false);
	//$schema["gdate"]["attributes"]="disabled";
	//$httpVars["df_gdate"] = date("m/d/Y");
	$page->resize->addgridobject($objGrid,10,150,false);
	
	
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo @$pageTitle ?></title>
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
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatebusinesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="lnkbtns/salesdeliveries.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onElementClick(element,column,table,row) {
		var result;
		switch(table) {
			case "T1":
				switch (column) {
					case "chxbox":
						if (isTableInputChecked(table,column,row)) {
							enableTableInput("T1","depredate",row);
							enableTableInput("T1","remainlife",row);
							enableTableInput("T1","salvagevalue",row);
							//focusTableInput("T1","depredate",row,500);
						} else {
							disableTableInput("T1","depredate",row);
							disableTableInput("T1","remainlife",row);
							disableTableInput("T1","salvagevalue",row);
							setTableInput("T1","depredate","",row);
							setTableInput("T1","remainlife",0,row);
							setTableInputAmount("T1","salvagevalue",0,row);
						}
				}
				break;
			default:
				break;
		}					
		return true;
	}	

	function onElementValidate(element,column,table,row) {
		return true;
	}	


	function onFormSubmit(action) {
		switch (action) {
			case "u_post":
				if(getTableRowCount("T1")==0) { 
					setStatusMsg("No Item(s) to process!"); 
					return false;
				}
				
				
				var none=true;
				if(getTableRowCount("T1")>0) {
					for(i=1;i<=getTableRowCount("T1");i++) {
						if(isTableInputChecked("T1","chxbox",i)){
							if (isTableInputEmpty("T1","depredate",i) || isTableInputNegative("T1","remainlife",i)) {
								selectTableRow("T1",i);
								return false;
							}	
						 	none = false; 
						}
					}
					if(none) {
						setStatusMsg("Please select item(s) to process."); 
						return false;				
					}
				}
				break;
			case "check":
				if(getTableRowCount("T1")>0) {
					if(getInput("chxstat")=="0") setInput("chxstat","1");
					else if(getInput("chxstat")=="1") setInput("chxstat","0");
					
					for(i=1;i<=getTableRowCount("T1");i++) {
						if(getInput("chxstat")=="1") {
							checkedTableInput("T1","chxbox",i,true); 
						}else if(getInput("chxstat")=="0") {
							uncheckedTableInput("T1","chxbox",i,true); 
						}
						onElementClick(getTableInput("T1","chxbox",i),"chxbox","T1",i);
					}
				}
				return false;
				break;
			default:	
				break;
		}
				
		return true;
	}	
	
	function onElementCFLGetParams(element) {
		var params = new Array();
		switch (element.id) {	
			case "df_suppno": 
				params["params"] = "BPTYPE:S";
				break;
		}	
		return params;
	}
	
	function onSelectRow(table,row) {
		var params = new Array();
		switch (table) {
			case "T1":
				params["focus"] = false;
				/*if (isTableInputChecked(table,"chxbox",row)) {
					focusTableInput(table,"depredate",row,500);				
				}*/	
				break;
		}		
		return params;
	}
	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td class="labelPageHeader" >&nbsp;<?php echo @$pageHeader ?>&nbsp;</td></tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr>
  <td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	  <!--DWLayoutTable-->
		<tr>
		 <td ><label <?php genCaptionHtml($schema["faclass"],"") ?> >Asset Class</label></td>
			<td align=left valign="bottom"><select <?php genSelectHtml($schema["faclass"],array("loadudflinktable","u_faclass:code:name",":[All]")) ?> ></select></td>
		  <td align=left><!--DWLayoutEmptyCell-->&nbsp;</td>
		  </tr>
		
		<tr>
		  <td>&nbsp;</td>
		  <td align=left><!--DWLayoutEmptyCell-->&nbsp;</td>
		  <td align=left></td>
		  </tr>
		<tr> 
			<td width="150">&nbsp;</td>
			<td width="805" align=left><a class="button" href="" onClick="<?php echo @$toolbarframe ?>formEntry();return false;">Retrieve</a></td>
			<td width="805" align=left></td>
		</tr>
		
		<tr class="fillerRow5px"><td></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
	</table></td>
</tr>
<tr>
<td>
	<?php $objGrid->draw(true) ?>
</td>
</tr>
<tr class="fillerRow10px">
	<td width="100%" align=left><a class="button" href="" onClick="<?php echo @$toolbarframe ?>formSubmit('check');return false;">Check / Uncheck All</a></td>
	<input type="hidden" readonly="readonly" size=5 name="df_chxstat" id="df_chxstat" value="0" />
</tr>
<?php if ($requestorId == "") { ?>
<tr><td>
<?php //require(getUserProgramFilePath("u_MotorRegBatchPostingToolbar.php"));  ?>
</td></tr>
<?php } ?>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>

<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_facreateplandepreToolbar.php");
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
