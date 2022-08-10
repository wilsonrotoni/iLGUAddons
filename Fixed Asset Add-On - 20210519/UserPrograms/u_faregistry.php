<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_faregistry";
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formutils.php");
	include_once("./sls/brancheslist.php");
	include_once("./sls/departments.php");
	include_once("./sls/projects.php");
	include_once("./sls/profitcenterdistributionrules.php");
	require("./inc/formactions.php");
	require("./inc/formaccess.php");
		
	$page->restoreSavedValues();	
	
	$page->objectcode = $progid;
	$page->formid = getUserProgramFilePath("u_faregistry.php");
	
	$_SERVER['PHP_SELF'] .= "?objectcode=".$progid;
	$schema["branch"]["name"]="branch";
	$schema["department"]["name"]="department";
	$schema["projcode"]["name"]="projcode";
	$schema["profitcenter"]["name"]="profitcenter";
	$schema["empid"]["name"]="empid";
	$schema["facode"]["name"]="facode";
	$schema["facode"]["description"]="facode";
	$schema["engineno"] = createSchema("engineno");
	$schema["plateno"] = createSchema("plateno");
	$schema["crno"] = createSchema("crno");
	$schema["empid"] = createSchema("empid");
	$schema["empname"] = createSchema("empname");
	$schema["facode"] = createSchema("facode");
	$schema["faclass"] = createSchema("faclass");
	$filterExp = "";

	$schema["projcode"]["cfl"]="OpenCFLprojects()";
	$schema["profitcenter"]["cfl"]="OpenCFLprofitcenterdistributionrules()";
	$schema["empid"]["cfl"]="OpenCFLemployees()";
	$schema["facode"]["cfl"]="OpenCFLmasterdata()";
	$schema["faclass"]["cfl"]="OpenCFLmasterdata()";



	$schema["empname"]["attributes"]="disabled";


	$objRs = new recordset(null,$objConnection);
	$objRs2 = new recordset(null,$objConnection);

	$objRs->queryopen("select u_famgmnt from companies where companycode='".$_SESSION["company"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["famgmnt"] = $objRs->fields["u_famgmnt"]; 
	}
	if ($page->privatedata["famgmnt"]=="BR") {
		$page->setitem("branch",$_SESSION["branch"]);
		$schema["branch"]["attributes"] = "disabled";
	}
	
	//$filterExp = genSQLFilterString("company",$filterExp,$company);
	$filterExp = genSQLFilterString("A.U_BRANCH",$filterExp,$httpVars['df_branch']);
	$filterExp = genSQLFilterString("A.U_DEPARTMENT",$filterExp,$httpVars['df_department']);
	$filterExp = genSQLFilterString("A.U_PROJCODE",$filterExp,$httpVars['df_projcode']);
	$filterExp = genSQLFilterString("A.U_PROFITCENTER",$filterExp,$httpVars['df_profitcenter']);
	$filterExp = genSQLFilterString("A.U_EMPID",$filterExp,$httpVars['df_empid']);
	$filterExp = genSQLFilterString("A.CODE",$filterExp,$httpVars['df_facode']);
	$filterExp = genSQLFilterString("A.U_FACLASS",$filterExp,$httpVars['df_faclass']);
//	$filterExp = genSQLFilterString("A.U_CRNO",$filterExp,$httpVars['df_crno']);
//	$filterExp = genSQLFilterString("A.U_CUSTNAME",$filterExp,$httpVars['df_custname']);
	/*
	if($httpVars['df_facode']!="") {
		if ($filterExp !="") $filterExp .= " and A.U_CHASSISNO like '".$httpVars['df_facode']."%'";
		else $filterExp = " A.U_CHASSISNO like '".$httpVars['df_facode']."%'";

	} 
	*/
	//if ($filterExp !="") $filterExp = " AND " . $filterExp . " AND u_serialstatus=1";
	if ($filterExp !="") $filterExp = " WHERE " . $filterExp;
	
	//echo $filterExp;
	
	$sqlins = "";
	$objGrid = new grid("T1",$httpVars,true);
//	$objGrid->addcolumn("u_type");
	$objGrid->addcolumn("code");
	$objGrid->addcolumn("u_itemdesc");
	$objGrid->addcolumn("u_branchname");
	$objGrid->addcolumn("u_department");
	$objGrid->addcolumn("u_empid");
	$objGrid->addcolumn("u_empname");
	$objGrid->addcolumn("u_projcode");
	$objGrid->addcolumn("u_profitcenter");
	$objGrid->columntitle("code","Fixed Asset Code");
	$objGrid->columnwidth("code",35);
	$objGrid->columntitle("u_itemdesc","Description");
	$objGrid->columnwidth("u_itemdesc",50);
	$objGrid->columntitle("u_branchname","Branch");
	$objGrid->columnwidth("u_branchname",20);
	$objGrid->columntitle("u_department","Department");
	$objGrid->columnwidth("u_department",10);
	$objGrid->columntitle("u_empid","Employee ID");
	$objGrid->columnwidth("u_empid",10);
	$objGrid->columntitle("u_empname","Employee Name");
	$objGrid->columnwidth("u_empname",30);
	$objGrid->columntitle("u_projcode","Project");
	$objGrid->columnwidth("u_projcode",15);
	$objGrid->columntitle("u_profitcenter","Profit Center");
	$objGrid->columnwidth("u_profitcenter",15);
	$objGrid->columnsortable("code",true);
	$objGrid->columnsortable("u_itemdesc",true);
	$objGrid->columnsortable("u_empid",true);
	$objGrid->columnsortable("u_empname",true);
	$objGrid->columnsortable("u_branchname",true);
	$objGrid->columnsortable("u_department",true);
	$objGrid->columnsortable("u_projcode",true);
	$objGrid->columnsortable("u_profitcenter",true);
	//$objGrid->columnsortable("u_cardname",true);
	//$objGrid->columnsortable("u_cardcode",true);
	//$objGrid->columnsortable("u_model",true);
	$objGrid->dataentry = false;
	
	if($page->getvarstring("lookupSortBy")=="") $objGrid->sortby="CODE";
	
	$orderExp = "";
	if($page->getvarstring("lookupSortBy")!="") {
		$orderExp .= " ORDER BY ".$page->getvarstring("lookupSortBy")." ".$page->getvarstring("lookupSortAs");
	}else{
		$orderExp .= " ORDER BY CODE";
	}
	
	//echo $filterExp;
	$objGrid->clear();
	
	$objMotorOwnership = new masterdatalinesschema_br(NULL,$objConnection,"u_motorownership");

	
	
	//setBranch($page->getitemstring("branchcode"));
	$objRs->setdebug();
	//if ($page->getitemstring("branchcode")!="" || $httpVars['df_documentstatus']!="" || $httpVars['df_facode']!="" || $httpVars['df_plateno']!="") {
		$objRs->queryopen("SELECT A.CODE, A.U_ITEMDESC, A.U_DEPARTMENT, A.U_EMPID, A.U_EMPNAME, A.U_PROJCODE, A.U_PROFITCENTER, C.BRANCHNAME FROM U_FA A LEFT JOIN BRANCHESLIST C ON C.BRANCHCODE=A.U_BRANCH $filterExp $orderExp", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
		
		//var_dump($objRs->sqls);
		while ($objRs->queryfetchrow("NAME")) {
			$objGrid->addrow();
			$objGrid->setitem(null,"code",$objRs->fields["CODE"]);
			$objGrid->setitem(null,"u_itemdesc",$objRs->fields["U_ITEMDESC"]);
			$objGrid->setitem(null,"u_branchname",$objRs->fields["BRANCHNAME"]);
			$objGrid->setitem(null,"u_department",$objRs->fields["U_DEPARTMENT"]);
			$objGrid->setitem(null,"u_empid",$objRs->fields["U_EMPID"]);
			$objGrid->setitem(null,"u_empname",$objRs->fields["U_EMPNAME"]);
			$objGrid->setitem(null,"u_projcode",$objRs->fields["U_PROJCODE"]);
			$objGrid->setitem(null,"u_profitcenter",$objRs->fields["U_PROFITCENTER"]);
			$objGrid->setkey(null,$objRs->fields["CODE"]);
			if (!$page->paging_fetch()) break;
		}						
		$page->paging_recordcount($objRs->recordcount());
	//}
	
	$page->resize->addgridobject($objGrid,10,200);
	
	
	//load document status
	function loaddocstatus($p_selected) {
		$_html = "";
		$selected = "";	
		$options = array(0 => array(0 => "IV", 1 => "In-Vault"),
						 1 => array(0 => "BR", 1 => "Borrowed"),
						 2 => array(0 => "RL", 1 => "Released")
						 );
						 
					 
		foreach($options as $option) {
			$selected = "";
			if($p_selected == $option[0]) $selected = "selected";
			$_html = $_html . "<option " . $selected . " value=". $option[0] . ">" . $option[1] . "</option>";
		}		
		echo @$_html;
	}
	
	$page->toolbar->setaction("print",false);
?>


 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
<SCRIPT language=JavaScript src="cfls/employees.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/projects.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/masterdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/profitcenterdistributionrules.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidateprojects.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidateprofitcenterdistributionrules.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidateemployees.js" type=text/javascript></SCRIPT>

<script language="JavaScript">
	function onFormSubmit(action) {
		
		if(action=="edit") {
			
		}

		return true;
	}
	
	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function formPrint(output) {
		OpenPDF('');
		return false;
	}
	
	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("facode");
			inputs.push("engineno");
			inputs.push("plateno");
			inputs.push("crno");
			inputs.push("branch");
			inputs.push("department");
			inputs.push("projcode");
			inputs.push("profitcenter");
			inputs.push("empid");
			inputs.push("empname");
			inputs.push("faclass");
		return inputs;
	}
	
	function onEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				return formView(null,"./udo.php?objectcode=u_fa");
				break;
		}		
		return false;
	}
	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "branch":
			case "department":
				clearTable("T1");
				formPageReset(); 
			break;	
		}
		return true;
	}
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "empid":
				if (getInput(column) != "") { 
					result = ajaxxmlvalidateemployees(element.value);
					if (result.getAttribute("result") == '0') {
						setInput("empid","");
						setInput("empname","");
						setStatusMsg('Invalid Employee ID!');
						return false;
					}	
					setInput("empid",result.childNodes.item(0).getAttribute("empid"));
					setInput("empname",result.childNodes.item(0).getAttribute("fullname"));
				} else {
					setInput("empid","");
					setInput("empname","");
				}
				clearTable("T1");
				formPageReset(); 
				break;
			case "profitcenter":
				if (getInput("profitcenter") != "") { 
					result = ajaxxmlvalidateprofitcenterdistributionrules(element.value);
					if (result.getAttribute("result") == '0') {
						setStatusMsg('Invalid Distribution Rule!');
						return false;
					}	
					setInput("profitcenter",result.childNodes.item(0).getAttribute("drcode"));
				}
				clearTable("T1");
				formPageReset(); 
				break;
			case "projcode":
				if (getInput("projcode") != "") { 
					result = ajaxxmlvalidateprojects(element.value);
					if (result.getAttribute("result") == '0') {
						setStatusMsg('Invalid Project!');
						return false;
					}	
					setInput("projcode",result.childNodes.item(0).getAttribute("projcode"));
				}
				clearTable("T1");
				formPageReset(); 
				break;
			break;	
		}
		return true;
	}

	function onElementCFLGetParams(element) {
		var params = new Array();
		switch (element.id) {	
			case "df_facode": 
				params["params"] = "UDT:U_FA;-WHERE U_ONHOLD=0"; 
				break;
			case "df_faclass": 
				params["params"] = "UDT:U_FACLASS"; 
				break;
		}	
		return params;
	}
	
		
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="onResizePage()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" id="lookupReturn" name="lookupReturn" value="<?php echo $lookupReturn;  ?>">
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;<?php echo @$pageHeader ?>&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr>
<td>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	  <!--DWLayoutTable-->
		<tr>
		  <td width="168"><label <?php genCaptionHtml($schema["branch"],"") ?> >Branch</label></td>
		  <td ><select <?php genSelectHtml($schema["branch"],array("loadbrancheslist","",":[All]")) ?> ></select></td>
		  <td width="168"><label <?php genCaptionHtml($schema["facode"],"") ?> >Fixed Asset Code</label></td>
		  <td width="168"><input type="text" size="18" <?php genInputTextHtml($schema["facode"]) ?> /></td>
		</tr>
		<tr>
			<td ><label <?php genCaptionHtml($schema["department"],"") ?> >Department</label></td>
			<td ><select <?php genSelectHtml($schema["department"],array("loaddepartments","",":[All]")) ?> ></select></td>
			<td ><label <?php genCaptionHtml($schema["faclass"],"") ?> >Fixed Asset Class</label></td>
		    <td ><input type="text" size="18" <?php genInputTextHtml($schema["faclass"]) ?> /></td>
		</tr>
		<tr>
			<td ><label <?php genCaptionHtml($schema["profitcenter"],"") ?> >Profit Center</label></td>
			<td ><input type="text" <?php genInputTextHtml($schema["profitcenter"]) ?> /></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr class="fillerRow5px">
		  <td ><label <?php genCaptionHtml($schema["projcode"],"") ?> >Project</label></td>
			<td ><input type="text" <?php genInputTextHtml($schema["projcode"]) ?> /></td>
		  <td><!--DWLayoutEmptyCell-->&nbsp;</td>
		  <td><!--DWLayoutEmptyCell-->&nbsp;</td>
		  </tr>
		<tr class="fillerRow5px"><td ><label <?php genCaptionHtml($schema["empid"],"") ?> >Employee ID</label></td>
			<td ><input type="text" <?php genInputTextHtml($schema["empid"]) ?> />&nbsp;<input type="text" size="50" <?php genInputTextHtml($schema["empname"]) ?> /></td>
		  <td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr class="fillerRow5px">
		  <td height="19"></td>
		  <td><a class="button" href="" onClick="formSearch();return false;">Search</a></td>
		  <td></td>
		  <td></td>
		  </tr>
		<tr class="fillerRow5px">
		  <td height="19"></td>
		  <td><!--DWLayoutEmptyCell-->&nbsp;</td>
		  <td></td>
		  <td></td>
		  </tr>
	</table>
</td>
<tr>
<td>
	<?php $objGrid->draw(true) ?>
</td>
</tr>
<?php $page->writeRecordLimit(); ?>
<?php if ($requestorId == "") { ?>
<tr><td>
<?php require("./GenericListToolbar.php");  ?>
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
//	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("U_GenericListToolbar.php");
//	include("setbottomtoolbar.php"); 
//	include("setstatusmsg.php");
        $htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess']  . $page->toolbar->generateQueryString() . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
