<?php
	$progid = "u_arvsap_grid";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./schema/journalentries.php");
	include_once("./sls/enumyear.php");
	include_once("./sls/enummonth.php");
	include_once("./sls/brancheslist.php");
	include_once("./sls/customergroups.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	
	$pageHeader = "A/R vs A/P";
	unset($enumyear[""]);
	$page->restoreSavedValues();	
	
	$page->objectcode = "A/R vs A/P";
	$page->paging->formid = "./UDP.php?&objectcode=u_arvsap_grid";
	$page->formid = "./UDO.php?&objectcode=U_YBLIPRS";
	$page->objectname = "A/R vs A/P Dashboard";
	
	$schema["branchcode"] = createSchemaUpper("branchcode");
	$schema["year"] = createSchemaUpper("year");
	$schema["month"] = createSchema("month");

	$schema["custgroup"] = createSchemaUpper("custgroup");
	$schema["valopt"] = createSchemaUpper("valopt");
	$schema["byopt"] = createSchemaUpper("byopt");
	
	$schema["firstview"] = createSchemaNumeric("firstview");

	function loadenumdvo($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumdvo;
		reset($enumdvo);
		while (list($key, $val) = each($enumdvo)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}
	
	$objrs = new recordset(null,$objConnection);

	$objGrid = new grid("T1",$httpVars);
	$objGrid->addcolumn("no");
	$objGrid->addcolumn("name");
	$objGrid->addcolumn("total");
	$objGrid->addcolumn("0");
	$objGrid->addcolumn("1");
	$objGrid->addcolumn("31");
	$objGrid->addcolumn("61");
	$objGrid->addcolumn("91");
	$objGrid->addcolumn("pdc");
	
	$objGrid->columntitle("no","#");
	if ($page->getitemstring("byopt")=="group") {
		$objGrid->columntitle("name","Customer Group");
	} else {
		$objGrid->columntitle("name","Customer");
	}

	$objGrid->columntitle("total","Balance");
	
	$objGrid->columnwidth("no",4);
	$objGrid->columnwidth("name",40);
	
	$objGrid->columnalignment("no","right");
	$objGrid->columnalignment("total","right");
	$objGrid->columnalignment("0","right");
	$objGrid->columnalignment("1","right");
	$objGrid->columnalignment("31","right");
	$objGrid->columnalignment("61","right");
	$objGrid->columnalignment("91","right");
	$objGrid->columnalignment("pdc","right");

	$objGrid->columndataentry("no","type","label");
	$objGrid->columndataentry("name","type","label");
	$objGrid->columndataentry("total","type","label");
	$objGrid->columndataentry("0","type","label");
	$objGrid->columndataentry("1","type","label");
	$objGrid->columndataentry("31","type","label");
	$objGrid->columndataentry("61","type","label");
	$objGrid->columndataentry("91","type","label");
	$objGrid->columndataentry("pdc","type","label");
	
	$objGrid->automanagecolumnwidth = false;
	$objGrid->dataentry = true;
	$objGrid->showactionbar = false;
	
	if ($lookupSortBy == "") {
		$lookupSortBy = "docno";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
	
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
		switch ($column) {
			case "u_dueamount": 
			case "u_paidamount": 
			case "u_balamount": 
				if ($label=="0.00") $label="";
				break;
		}
	}
	
	require("./inc/formactions.php");
	
	$date = date('Y-m-d');
	if (!isset($httpVars['df_branchcode'])) {
		$page->setitem("branchcode","");
		$page->setitem("byopt","group");
		$page->setitem("valopt","balance");
		$page->setitem("firstview",1);
		
	}
	
	$byopt = $page->getitemstring("byopt");
	$valopt = $page->getitemstring("valopt");
	$custgroup = $page->getitemstring("custgroup");
		
	$objGrid->columnwidth("0",13);
	$objGrid->columnwidth("total",13);

	$objGrid->columntitle("0","Current");
	$objGrid->columntitle("1","1 to 30 Days");
	$objGrid->columntitle("31","31 to 60 Days");
	$objGrid->columntitle("61","61 to 90 Days");
	$objGrid->columntitle("91","90 Days Above");
	$objGrid->columntitle("pdc","PDC");
		
	$objGrid->columnwidth("0",13);
	$objGrid->columnwidth("1",13);
	$objGrid->columnwidth("31",13);
	$objGrid->columnwidth("61",13);
	$objGrid->columnwidth("91",13);
	$objGrid->columnwidth("pdc",13);
	
	
	
	$objrs = new recordset(null,$objConnection);
	/*
	$objrs->queryopen("select A.DOCNO, A.u_startdate, A.u_starttime, A.U_PATIENTID, A.U_PATIENTNAME, A.U_BEDNO, A.U_ROOMDESC, B.NAME AS DEPARTMENTNAME, A.DOCSTATUS, A.U_PAYMENTTERM from U_HISIPS A LEFT OUTER JOIN U_HISSECTIONS B ON B.CODE=A.U_DEPARTMENT WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp" . $page->paging_getlimit());
	setTableRCVar("T1",$objrs->recordcount());
	if ($objrs->recordcount() > 0) setTableVRVar("T1",$objrs->recordcount());
	
	$page->paging_recordcount($objrs->recordcount());
	*/
	$totals = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	/*$totals['jan'] = 0;
	$totals['feb'] = 0;
	$totals['mar'] = 0;
	$totals['apr'] = 0;
	$totals['may'] = 0;
	$totals['jun'] = 0;
	$totals['jul'] = 0;
	$totals['aug'] = 0;
	$totals['sep'] = 0;
	$totals['oct'] = 0;
	$totals['nov'] = 0;
	$totals['dec'] = 0;
	$totals['totals'] = 0;*/
	//$objrs->setdebug();
	//var_dump($page->getitemstring("u_nursed"));
	$unionExp = "";
	/*if ($page->getitemstring("locopt")=="mnl") {
		$objConnection->selectdb("fci_mnl_live");
		$sql = "call sp_fci_bi_receivables( 'FCI_MNL', 'MAIN', '".currentdateDB()."','','".$page->getitemstring("byopt")."','".$page->getitemstring("valopt")."')";
	}
	if ($page->getitemstring("locopt")=="ceb") {
		$objConnection->selectdb("fci_ceb_live");
		$sql = "call sp_fci_bi_receivables( 'PORTAL', 'MAIN', '".currentdateDB()."','','".$page->getitemstring("byopt")."','".$page->getitemstring("valopt")."')";
	}
	if ($page->getitemstring("locopt")=="dvo") {
		$objConnection->selectdb("fci_dvo_live");
		$sql = "call sp_fci_bi_receivables( 'FCI', 'DVO', '".currentdateDB()."','','".$page->getitemstring("byopt")."','".$page->getitemstring("valopt")."')";
	}
	if ($page->getitemstring("locopt")=="enasia_import") {
		$objConnection->selectdb("enasia_import");
		$sql = "call sp_fci_bi_receivables( 'EN_IMP', 'MAIN', '".currentdateDB()."','','".$page->getitemstring("byopt")."','".$page->getitemstring("valopt")."')";
	}*/
	//var_dump($sql);
	//var_dump($unionExp);
	//var_dump($custgroup);
	
	$custgroupExp = "";
	if ($custgroup!="") $custgroupExp = " and c.custgroup='$custgroup'";
	
	$byoptExp = "";
	switch ($byopt) {
		case "group": 
			$byoptExp = "custgroupdesc"; 
			break;
		case "customer": 
			$byoptExp = "bpname"; 
			break;
	}
	
	$sortExp = "";
	switch ($valopt) {
		case "balance": 
			$sortExp = "balance desc"; 
			break;
		case "current": 
			$sortExp = "current desc, balance desc"; 
			break;
		case "d1": 
			$sortExp = "d1 desc"; 
			break;
		case "d31": 
			$sortExp = "d31 desc"; 
			break;
		case "d61": 
			$sortExp = "d61 desc"; 
			break;
		case "up90": 
			$sortExp = "up90 desc"; 
			break;
	}
	
	$sql = "select 'A/R' as type, sum(totalamount) as balance,
  sum(if(age = 0,totalamount,0)) as current,
  sum(if(age between 1 and 30,totalamount,0)) as d1,
  sum(if(age between 31 and 60,totalamount,0)) as d31,
  sum(if(age between 61 and 90,totalamount,0)) as d61,
  sum(if(age > 90,totalamount,0)) as up90
  from (
select a.bpcode, a.bpname, c.custgroup, d.groupname as custgroupdesc, a.docdate, a.dueamount as totalamount, datediff('$date', date_format(a.docdate, '%y-%m-%d')) as age from arinvoices a
  inner join customers c on c.company=a.company and c.branch=a.branch and c.custno=a.bpcode $custgroupExp
  inner join customergroups d on d.custgroup=c.custgroup
  where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docstatus='O'
  union all
select a.bpcode, a.bpname, c.custgroup, d.groupname as custgroupdesc, a.docdate, a.dueamount*-1 as totalamount, datediff('$date', date_format(a.docdate, '%y-%m-%d')) as age from arcreditmemos a
  inner join customers c on c.company=a.company and c.branch=a.branch and c.custno=a.bpcode $custgroupExp
  inner join customergroups d on d.custgroup=c.custgroup
  where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docstatus='O'
  union all
select b.itemno as bpcode, b.itemname as bpname, c.custgroup, d.groupname as custgroupdesc, a.docdate, b.balanceamount as totalamount, datediff('$date', date_format(a.docdate, '%y-%m-%d')) as age from journalvouchers a
  inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.itemtype='C' and b.reftype='' and b.balanceamount<>0
  inner join customers c on c.company=b.company and c.branch=b.branch and c.custno=b.itemno $custgroupExp
  inner join customergroups d on d.custgroup=c.custgroup
  where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."'
  union all
select a.bpcode, a.bpname, c.custgroup, d.groupname as custgroupdesc, a.docdate, a.balanceamount*-1 as totalamount, datediff('$date', date_format(a.docdate, '%y-%m-%d')) as age from collections a
  inner join customers c on c.company=a.company and c.branch=a.branchcode and c.custno=a.bpcode $custgroupExp
  inner join customergroups d on d.custgroup=c.custgroup
  where a.company='".$_SESSION["company"]."' and a.branchcode='".$_SESSION["branch"]."' and a.collfor='DP' and a.docstat='O'
  ) as x union all select 'A/P' as type, sum(totalamount)*-1 as balance,
  sum(if(age = 0,totalamount,0))*-1 as current,
  sum(if(age between 1 and 30,totalamount,0))*-1 as d1,
  sum(if(age between 31 and 60,totalamount,0))*-1 as d31,
  sum(if(age between 61 and 90,totalamount,0))*-1 as d61,
  sum(if(age > 90,totalamount,0))*-1 as up90
  from (
select a.bpcode, a.bpname, c.suppgroup as custgroup, d.groupname as custgroupdesc, a.docdate, a.dueamount as totalamount, datediff('$date', date_format(a.docdate, '%y-%m-%d')) as age from apinvoices a
  inner join suppliers c on c.suppno=a.bpcode $custgroupExp
  inner join suppliergroups d on d.suppgroup=c.suppgroup
  where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docstatus='O'
  union all
select a.bpcode, a.bpname, c.suppgroup as custgroup, d.groupname as custgroupdesc, a.docdate, a.dueamount*-1 as totalamount, datediff('$date', date_format(a.docdate, '%y-%m-%d')) as age from apcreditmemos a
  inner join suppliers c on c.suppno=a.bpcode $custgroupExp
  inner join suppliergroups d on d.suppgroup=c.suppgroup
  where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docstatus='O'
  union all
select b.itemno as bpcode, b.itemname as bpname, c.suppgroup as custgroup, d.groupname as custgroupdesc, a.docdate, b.balanceamount*-1 as totalamount, datediff('$date', date_format(a.docdate, '%y-%m-%d')) as age from journalvouchers a
  inner join journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.itemtype='S' and b.reftype='' and b.balanceamount<>0
  inner join suppliers c on c.suppno=b.itemno $custgroupExp
  inner join suppliergroups d on d.suppgroup=c.suppgroup
  where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."'
  union all
select a.bpcode, a.bpname, c.suppgroup as custgroup, d.groupname as custgroupdesc, a.docdate, a.balanceamount*-1 as totalamount, datediff('$date', date_format(a.docdate, '%y-%m-%d')) as age from payments a
  inner join suppliers c on c.suppno=a.bpcode $custgroupExp
  inner join suppliergroups d on d.suppgroup=c.suppgroup
  where a.company='".$_SESSION["company"]."' and a.branchcode='".$_SESSION["branch"]."' and a.collfor='DP' and a.docstat='O'
  ) as x union all select 'Cash in Bank' as type, sum(totalamount) as balance,
  sum(if(age = 0,totalamount,0)) as current,
  0 as d1,
  0 as d31,
  0 as d61,
  0 as up90
  from (
select sum(b.gldebit-b.glcredit) as totalamount, 0 as age from chartofaccounts c
	  inner join journalentryitems b on b.company='".$_SESSION["company"]."' and b.branch='".$_SESSION["branch"]."' and b.glacctno=c.formatcode
    inner join journalentries a on a.company=b.company and a.branch=b.branch and a.docid=b.docid and a.sbo_post_flag=b.sbo_post_flag
	  where c.cashinbankacct=1
  ) as x union all select 'Cash oh Hand' as type, sum(totalamount) as balance,
  sum(if(age = 0,totalamount,0)) as current,
  0 as d1,
  0 as d31,
  0 as d61,
  0 as up90
  from (
select sum(b.gldebit-b.glcredit) as totalamount, 0 as age from chartofaccounts c
	  inner join journalentryitems b on b.company='".$_SESSION["company"]."' and b.branch='".$_SESSION["branch"]."' and b.glacctno=c.formatcode
    inner join journalentries a on a.company=b.company and a.branch=b.branch and a.docid=b.docid and a.sbo_post_flag=b.sbo_post_flag
	  where c.cashonhand=1
  ) as x";
   
	$objrs->queryopen($sql);
	//var_dump($sql);
	
	//$page->paging_recordcount($objrs->recordcount());
	$ctr=0;
	while ($objrs->queryfetchrow("NAME")) {
		$ctr++;
		$objGrid->addrow();
		
		$objGrid->setitem(null,"no",str_replace(".00","",formatNumericAmount($ctr)));
		$objGrid->setitem(null,"name",$objrs->fields["type"]);
		$objGrid->setitem(null,"total",formatNumericAmount($objrs->fields["balance"]));
		$objGrid->setitem(null,"0",formatNumericAmount($objrs->fields["current"]));
		$objGrid->setitem(null,"1",formatNumericAmount($objrs->fields["d1"]));
		$objGrid->setitem(null,"31",formatNumericAmount($objrs->fields["d31"]));
		$objGrid->setitem(null,"61",formatNumericAmount($objrs->fields["d61"]));
		$objGrid->setitem(null,"91",formatNumericAmount($objrs->fields["up90"]));
		$objGrid->setitem(null,"pdc",formatNumericAmount($objrs->fields["pdc"]));
		//$objGrid->setitem(null,"totalperc",formatNumericPercent($objrs->fields["all".$sufix]));
		$totals[0] += $objrs->fields["balance"];
		$totals[1] += $objrs->fields["current"];
		$totals[2] += $objrs->fields["d1"];
		$totals[3] += $objrs->fields["d31"];
		$totals[4] += $objrs->fields["d61"];
		$totals[5] += $objrs->fields["up90"];
		$totals[6] += $objrs->fields["pdc"];
		
	}	
	$objGrid->setdefaultvalue("no",str_replace(".00","",formatNumericAmount($ctr)));
	$objGrid->setdefaultvalue("total",formatNumericAmount($totals[0]));
	$objGrid->setdefaultvalue("0",formatNumericAmount($totals[1]));
	$objGrid->setdefaultvalue("1",formatNumericAmount($totals[2]));
	$objGrid->setdefaultvalue("31",formatNumericAmount($totals[3]));
	$objGrid->setdefaultvalue("61",formatNumericAmount($totals[4]));
	$objGrid->setdefaultvalue("91",formatNumericAmount($totals[5]));
	$objGrid->setdefaultvalue("pdc",formatNumericAmount($totals[6]));
	
/*	for ($i=1;$i<=$objGrid->recordcount ;$i++) {
		$objGrid->setitem($i,"totalperc",formatNumericAmount(($objGrid->getitemdecimal($i,"0") / $totals[0])*100));
	}*/
	
/*	if ($page->getitemstring("month")=="") {
		foreach ($totals as $key => $value) {
			if ($value==0){
				$objGrid->columnvisibility($key,false);
			}
		}
	}*/
	//$page->resize->addgrid("T1",20,80,false);
	$page->resize->addgridobject($objGrid,15,85); 
	
	//$rptcols = 6; 
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
<SCRIPT language=JavaScript src="<?php autocaching('js/formobjs.js'); ?>" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../Addons/GPS/HIS Add-On/UserJs/lnkbtns/u_hispatients.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="../Addons/GPS/HIS Add-On/UserJs/lnkbtns/u_hispatientregs.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function OpenLnkBtnRefNos(targetObjectId) {
		var row = targetObjectId.indexOf("T1r");		
		if (getTableInput("T1",'dvo',targetObjectId.substring(row+3,targetObjectId.length))=="IP") {
			OpenLnkBtnu_hisips(targetObjectId);
		} else {
			OpenLnkBtnu_hisops(targetObjectId);
		}
		
	}
	

	function onPageLoad() {
		if (getInput("firstview")=="1") {
			parent.barFrame.location = "../FusionCharts/MySamples/his_arvsap_graph.php?valopt=" + getInput("valopt")  + "&byopt=" + getInput("byopt") + "&custgroup=" + getInput("custgroup")   + "&dbname=" + getGlobal("dbname") + "&companycode=" + getGlobal("company")  + "&branchcode=" + getInput("branchcode");
		}	
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				/*if (getTableInput("T1","docstatus",p_rowIdx)=="O") {
					if (getTableInput("T1","u_prepaid",p_rowIdx)=="1" && getTableInput("T1","u_payrefno",p_rowIdx)=="") {
						page.statusbar.showWarning("Request is prepaid and not yet paid.");
					} else {
						var targetObjectId = '';
						OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hischarges' + '' + '&df_u_trxtype='+getInput("u_trxtype") + '&targetId=' + targetObjectId ,targetObjectId);
					}	
					//setKey("keys",getTableKey("T1","keys",p_rowIdx));
					//return formView(null,"<?php echo $page->formid; ?>");
				}*/	
							
				setKey("keys",getTableKey("T1","keys",p_rowIdx));
				return formView(null,"<?php echo $page->formid; ?>");
				//var targetObjectId = 'u_hispatients';
				//OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				break;
		}		
		return false;
	}
	
	function onLnkBtnGetParams(elementId) {
		var params = new Array();
		switch (elementId) {
			case "u_hispatients":
				if (getTableSelectedRow("T1")>0) params["keys"] = getTableKey("T1","keys",getTableSelectedRow("T1"));
				break;
		}
		return params;
	}	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "year":
			case "month":
				clearTable("T1");
				formPageReset(); 
				break;
			case "custgroup":
				if (getInput("custgroup")!="") {
					setInput("byopt","customer");
					formSearchNow();
				} else {
					setInput("byopt","group");
					formSearchNow();
				}
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
				clearTable("T1");
				formPageReset(); break;
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		switch (column) {
			case "valopt":
				formSearchNow();
				break;
			case "byopt":
				if (getInput("byopt")=="group") {
					setInput("custgroup","");
					formSearchNow();
				} else {
					if (getInput("custgroup")!="") {
						formSearchNow();
					} else focusInput("custgroup");
				} 
				break;
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_branchcode":
			case "df_byopt":
			case "df_valopt":
			case "df_custgroup":
			return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("branchcode");
			inputs.push("byopt");
			inputs.push("valopt");
			inputs.push("custgroup");
			inputs.push("firstview");
		return inputs;
	}
	
	function formAddNew() {
		var targetObjectId = '';
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	}
	
	function formSearchNow() {
		acceptText();
		setInput("firstview",0);
		parent.barFrame.location = "../FusionCharts/MySamples/his_arvsap_graph.php?valopt=" + getInput("valopt")  + "&byopt=" + getInput("byopt")  + "&custgroup=" + getInput("custgroup")  + "&dbname=" + getGlobal("dbname") + "&companycode=" + getGlobal("company")  + "&branchcode=" + getInput("branchcode");
		

		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "mnl":
			case "u_datefr":
			case "u_dateto":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
				}
				break;
			case "ceb":
			case "u_conductor":
			case "year":
			case "docstatus":
			case "dvo":
			case "u_tellerby":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				}
				break;
		}
	}	
	
	function OpenLnkBtnu_hisrequests(targetId) {
		OpenLnkBtn(1024,600,'./udo.php?objectcode=u_hisrequests' + '' + '&targetId=' + targetId ,targetId);
		
	}
	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" id="df_u_trxtype" name="df_u_trxtype" value="<?php echo $page->getitemstring("u_trxtype");  ?>">
<input type="hidden" id="df_firstview" name="df_firstview" value="<?php echo $page->getitemstring("firstview");  ?>">
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;<?php echo $pageHeader ?>&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td ><label <?php genCaptionHtml($schema["branchcode"],"") ?> >Branch</label>&nbsp;<select <?php genSelectHtml($schema["branchcode"],array("loadbrancheslist","",":[All]")) ?> ></select>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Go</a></td>
      <td  align=left><input type="radio" <?php genInputRadioHtml($schema["valopt"],"balance") ?> /><label <?php genOptionCaptionHtml($schema["valopt"],"") ?> >Balance</label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["valopt"],"current") ?> /><label <?php genOptionCaptionHtml($schema["valopt"],"current") ?> >Current</label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["valopt"],"d1") ?> /><label <?php genOptionCaptionHtml($schema["valopt"],"") ?> >1-30</label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["valopt"],"d31") ?> /><label <?php genOptionCaptionHtml($schema["valopt"],"") ?> >31-60</label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["valopt"],"d61") ?> /><label <?php genOptionCaptionHtml($schema["valopt"],"") ?> >61-90</label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["valopt"],"up90") ?> /><label <?php genOptionCaptionHtml($schema["valopt"],"") ?> >>90</label></td>
	  <td  align=left><input type="radio" <?php genInputRadioHtml($schema["byopt"],"customer") ?> /><label <?php genOptionCaptionHtml($schema["byopt"],"") ?> >Customer</label>&nbsp;<input type="radio" <?php genInputRadioHtml($schema["byopt"],"group") ?> /><label <?php genOptionCaptionHtml($schema["byopt"],"") ?> >Customer Group</label>&nbsp;<select <?php genSelectHtml($schema["custgroup"],array("loadcustomergroups","",":[All]")) ?> ></select></td>
	  </tr>
	</table></td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw(true) ?></td></tr>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
