<?php
	$progid = "";

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
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	
	$pageHeader = "Inventory";
	unset($enumyear[""]);
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_YBLIPRLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_inventory_grid";
	$page->formid = "./UDO.php?&objectcode=U_YBLIPRS";
	$page->objectname = "IPR List";
	
	$schema["branchcode"] = createSchemaUpper("branchcode");
	$schema["datefrom"] = createSchemaDate("datefrom");
	$schema["dateto"] = createSchemaDate("dateto");

	$schema["valopt"] = createSchemaUpper("valopt");
	$schema["byopt"] = createSchemaUpper("byopt");
	$schema["topopt"] = createSchemaNumeric("topopt");

	$schema["firstview"] = createSchemaNumeric("firstview");
	
	if ($_SESSION["branchtype"]=="B") {
		$schema["branchcode"]["attributes"] = "disabled";
	}	
	
	//$enumbyopt= array('month' => 'Month', 'branch' => 'Branch', 'item' => 'Item', 'value' => 'Item Group','itemclass' => 'Item Class', 'itemsubclass' => 'Item Sub-Class', 'itemindicator' => 'Item Indicator',  'customer' => 'Customer','salesperson' => 'Sales Person');
	//,'salesperson' => 'Sales Person'
	$enumbyopt= array('item' => 'Item', 'itemgroup' => 'Item Group');

	$enumvalopt= array('value' => 'Value', 'qty' => 'Qty', 'daysqty' => 'Days to Finish', 'fm' => 'Fast Moving', 'sm' => 'Slow Moving', 'nm' => 'Non Moving');

	function loadenumbyopt($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumbyopt;
		reset($enumbyopt);
		while (list($key, $val) = each($enumbyopt)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}

	function loadenumvalopt($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumvalopt;
		reset($enumvalopt);
		while (list($key, $val) = each($enumvalopt)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}

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
	$objGrid->addcolumn("rank");
	$objGrid->addcolumn("itemdesc");
	$objGrid->addcolumn("value");
	$objGrid->addcolumn("nostock");
	$objGrid->addcolumn("qty");
	$objGrid->addcolumn("soldqty");
	$objGrid->addcolumn("avgqty");
	$objGrid->addcolumn("daysqty");
	$objGrid->columnvisibility("nostock",false);
	
	
	$objGrid->columnwidth("rank",4);
	$objGrid->columnwidth("value",12);
	$objGrid->columnwidth("nostock",14);
	$objGrid->columnwidth("soldqty",12);
	$objGrid->columnwidth("avgqty",12);
	$objGrid->columnwidth("daysqty",12);

	$objGrid->columntitle("rank","#");
	$objGrid->columntitle("itemdesc","Description");
	$objGrid->columntitle("qty","Quantity");
	$objGrid->columntitle("value","Value");
	$objGrid->columntitle("nostock","Store w/o Stock");
	$objGrid->columntitle("soldqty","Sales Qty");
	$objGrid->columntitle("avgqty","Avg Sales Qty");
	$objGrid->columntitle("daysqty","Days to Finish");


	$objGrid->columnalignment("qty","right");
	$objGrid->columnalignment("value","right");
	$objGrid->columnalignment("nostock","right");
	$objGrid->columnalignment("soldqty","right");
	$objGrid->columnalignment("avgqty","right");
	$objGrid->columnalignment("daysqty","right");

	$objGrid->columndataentry("rank","type","label");
	$objGrid->columndataentry("itemdesc","type","label");
	$objGrid->columndataentry("qty","type","label");
	$objGrid->columndataentry("value","type","label");
	$objGrid->columndataentry("nostock","type","label");
	$objGrid->columndataentry("soldqty","type","label");
	$objGrid->columndataentry("avgqty","type","label");
	$objGrid->columndataentry("daysqty","type","label");
	
	$objGrid->automanagecolumnwidth = false;
	$objGrid->dataentry = false;
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
	if (!isset($httpVars['df_datefrom'])) {
		$page->setitem("datefrom",getmonthstart(dateadd("m",-1,currentdateDB())));
		$page->setitem("dateto",getmonthend(dateadd("m",-1,currentdateDB())));
		$page->setitem("month",date('m'));
		if ($_SESSION["userid"]=="nep") $page->setitem("topopt",20);
		else $page->setitem("topopt",50);
		$page->setitem("branchcode","");
		$page->setitem("byopt","item");
		$page->setitem("valopt","fm");
		$page->setitem("firstview",1);
		if ($_SESSION["branchtype"]=="B") {
			$page->setitem("branchcode",$_SESSION["branch"]);
		}
		
	}
	$limitExp = $page->getitemstring("topopt");
	$start = $page->getitemdate("datefrom");
	$end = $page->getitemdate("dateto");
	//var_dump(array($start,$end));
	/*if ($page->getitemstring("month")=="") {
		$start = $page->getitemstring("year") ."-01-01";
		$end = $page->getitemstring("year") ."-12-31";
	} else {
		$month = $page->getitemnumeric("month")-1;
		$year = $page->getitemstring("year");
		if ($month==0) {
			$month=12;
			$year--;
		}
		$month = str_pad( $month, 2, "0", STR_PAD_LEFT); 
		$start = $year ."-".$month."-01";
		$end = getmonthendDB($start);
	}
	*/
	if ($page->getitemstring("byopt")=="item") {
		$objGrid->columnwidth("itemdesc",60);
		$byoptExp = "itemdesc";	
	} elseif ($page->getitemstring("byopt")=="itemgroup") {
		$objGrid->columnwidth("itemdesc",20);
		$byoptExp = "itemgroupname";
	}

	$mn = strtoupper(date('M',strtotime($start)));
	
	$objGrid->columnwidth("0",12);

	$objrs = new recordset(null,$objConnection);
	$objrs->setdebug();
	$branchcodeExp = "";
	$branchcodeExp2 = "";
	
	/*
	$sortExp = $page->getitemstring("valopt");
	if ($page->getitemstring("valopt")=="label") {
		switch ($page->getitemstring("byopt")) {
			case "month": $sortExp = "docmonth"; break;
			case "branch": $sortExp = "branchname"; break;
			case "customer": $sortExp = "customername"; break;
			case "itemgroup": $sortExp = "itemgroupname"; break;
		}
	}	
	*/
	switch ($page->getitemstring("valopt")) {
			case "value": $sortExp = "order by `value` desc"; break;
			case "qty": $sortExp = "order by qty desc"; break;
			case "daysqty": $sortExp = "having avgqty >= .5 order by daysqty asc, avgqty desc"; break;
			case "fm": $sortExp = "order by avgqty desc, `qty` desc"; break;
			case "sm": $sortExp = "having avgqty > 0 order by avgqty asc, `qty` desc"; break;
			case "nm": $sortExp = "having avgqty = 0 order by qty desc"; break;
	}
	
	if ($page->getitemstring("branchcode")!="") {
		$branchcodeExpPOS = " and a.branch='".$page->getitemstring("branchcode")."'";
		$branchcodeExpIQ = " iq.company='".$_SESSION["company"]."' and iq.branch='".$page->getitemstring("branchcode")."' and ";
	}
	$dbname = $_SESSION["dbname"];
	$suppnoExp = "";
	if ($_SESSION["userid"]=="nep") $suppnoExp = "it.preferredsuppno='s0040' and";
	
	$nostockExp = "";
	/*if ($page->getitemstring("byopt")=="item" && $page->getitemstring("branchcode")=="") {
		$objGrid->columnvisibility("nostock",true);
		$nostockExp = "union all

  select itemcode, itemdesc, '' as itemgroupname, 0 as qty, 0 as `value`, count(*) as nostock, 0 as soldqty, 0 as avgqty from (
    select it.itemcode, it.itemdesc, ifnull(iq.instockqty,0) as instockqty, br.branchcode from branches br
      inner join items it on $suppnoExp it.itemgroup not in ('freebies','service') and it.itemclass not in ('paper bag')
      left join itemgroups ig on ig.itemgroup=it.itemgroup left join itemsquantities iq on iq.company='ghz' and iq.branch=br.branchcode and iq.itemcode=it.itemcode
      where br.branchcode not in ('website','moa','lipa1','lazada','corpsales')
      having instockqty=0) as x group by itemcode";
	}*/
	
	$unionExp = "";
	$unionExp .= iif($unionExp!=""," UNION ALL ","");
	$unionExp .= "select it.itemcode, it.itemdesc, ig.itemgroupname, sum(iq.instockqty) as qty, sum(iq.stockvalue) as `value`, 0 as nostock, 0 as soldqty, 0 as avgqty from items it
   inner join itemgroups ig on ig.itemgroup=it.itemgroup left join itemsquantities iq on iq.company='".$_SESSION["company"]."' $branchcodeExpIQ and iq.itemcode=it.itemcode
   where $suppnoExp it.itemgroup in ('MED','SUP') group by it.itemcode
       $nostockExp
  union all
  select it.itemcode, it.itemdesc, ig.itemgroupname, 0 as qty, 0 as `value`, 0 as nostock, sum(b.quantity) as soldqty, round(sum(b.quantity) / (DATEDIFF('$end','$start')+1),2) as avgqty from arinvoices a
    inner join arinvoiceitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
    inner join items it on it.itemcode=b.itemcode and $suppnoExp it.itemgroup in ('MED','SUP')
    inner join itemgroups ig on ig.itemgroup=it.itemgroup where a.company='".$_SESSION["company"]."' $branchcodeExpPOS and a.docstatus not in ('D') and a.docdate between '$start' and '$end' group by it.itemcode
";

	/*$unionExp .= "select it.itemcode, it.itemdesc, sum(iq.instockqty) as qty, sum(iq.stockvalue) as `value`, 0 as nostock, 0 as avgqty from items it
   left join itemsquantities iq on iq.itemcode=it.itemcode
   where it.itemgroup not in ('FREEBIES','service') and it.itemclass not in ('paper bag') group by it.itemcode
  union all

  select itemcode, itemdesc, 0 as qty, 0 as `value`, count(*) as nostock, 0 as avgqty from (
    select it.itemcode, it.itemdesc, ifnull(iq.instockqty,0) as instockqty, br.branchcode from branches br
      inner join items it on it.itemgroup not in ('freebies','service') and it.itemclass not in ('paper bag')
      left join itemsquantities iq on iq.company='ghz' and iq.branch=br.branchcode and iq.itemcode=it.itemcode
      where br.branchcode not in ('website','moa','lipa1','lazada','corpsales')
      having instockqty=0) as x group by itemcode
  union all
  select it.itemcode, it.itemdesc, 0 as qty, 0 as `value`, 0 as nostock, round(sum(b.u_quantity) / (DATEDIFF('$end','$start')+1),1) as avgqty from u_pos a
    inner join u_positems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
    inner join items it on it.itemcode=b.u_itemcode and it.itemgroup not in ('freebies','service') and it.itemclass not in ('paper bag')
    where a.company='ghz' and a.u_status not in ('D','CN') and a.u_date between '$start' and '$end' group by it.itemcode
";
*/
	//var_dump($unionExp);
	$objrs->queryopen("select itemcode, itemdesc, itemgroupname, sum(qty) as qty, sum(`value`) as `value`, sum(nostock) as nostock, sum(soldqty) as soldqty, sum(avgqty) as avgqty, sum(qty)/sum(avgqty) as daysqty from ( $unionExp) as x2 group by $byoptExp $sortExp limit $limitExp");
//having qty>0 
 //var_dump($objrs->sqls);

	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		$objGrid->setitem(null,"rank",$objGrid->recordcount);
		$objGrid->setitem(null,"itemdesc",$objrs->fields[$byoptExp]);
		$objGrid->setitem(null,"qty",formatNumeric($objrs->fields["qty"]));
		$objGrid->setitem(null,"value",formatNumericAmount($objrs->fields["value"]));
		$objGrid->setitem(null,"nostock",formatNumeric($objrs->fields["nostock"]));
		$objGrid->setitem(null,"soldqty",formatNumeric($objrs->fields["soldqty"]));
		$objGrid->setitem(null,"avgqty",formatNumericQuantity($objrs->fields["avgqty"]));
		$objGrid->setitem(null,"daysqty",formatNumeric($objrs->fields["qty"]/$objrs->fields["avgqty"]));
		$totals[0] += $objrs->fields["qty"];
		$totals[1] += $objrs->fields["value"];
	}	
	//$objGrid->setitem(1,"qty",formatNumericAmount($totals[0]));
	//$objGrid->setitem(2,"value",formatNumericAmount($totals[1]));

	/*for ($i=1;$i<=count($objGrid->columns)-2;$i++) {
		if ($objGrid->getcolumndecimal(1,));
		$objGrid->setitem($i,"totalperc",formatNumericAmount(($objGrid->getitemdecimal($i,"0") / $totals[0])*100));
	}*/
	
	/*
	if ($page->getitemstring("month")=="") {
		foreach ($totals as $key => $value) {
			if ($value==0){
				$objGrid->columnvisibility($key,false);
				$objGrid->columnvisibility($key.p,false);
			}
		}
	}*/
	//$page->resize->addgrid("T1",20,80,false);
	$page->resize->addgridobject($objGrid,15,50); 
	
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
			parent.barFrame.location = "../FusionCharts/MySamples/his_inventory_graph.php?datefrom=" + formatDateToDB(getInput("datefrom")) + "&dateto=" + formatDateToDB(getInput("dateto")) + "&dbname=" + getGlobal("dbname") + "&companycode=" + getGlobal("company") + "&branchcode=" + getInput("branchcode") + "&byopt=" + getInput("byopt") + "&valopt=" + getInput("valopt") + "&topopt=" + getInput("topopt") + "&userid=" + getGlobal("userid");
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
			case "branchcode":
				formSearchNow();
			case "byopt":
				formSearchNow();
				break;
			case "valopt":
				formSearchNow();
				break;
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
			case "datefrom":
			case "dateto":
			case "topopt":
				clearTable("T1"); 
				break;
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_datefrom":
			case "df_dateto":
			case "df_branchcode":
			case "df_byopt":
			case "df_valopt":
			case "df_topopt":
			return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("datefrom");
			inputs.push("dateto");
			inputs.push("branchcode");
			inputs.push("byopt");
			inputs.push("valopt");
			inputs.push("topopt");
			inputs.push("firstview");
		return inputs;
	}
	
	function formAddNew() {
		var targetObjectId = '';
		OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hispatients' + '' + '&targetId=' + targetObjectId ,targetObjectId);
	}
	
	function formSearchNow() {
		acceptText();
		if (isInputEmpty("datefrom")) return false;
		if (isInputEmpty("dateto")) return false;
		if (isInputNegative("topopt")) return false;
		setInput("firstview",0);
		parent.barFrame.location = "../FusionCharts/MySamples/his_inventory_graph.php?datefrom=" + formatDateToDB(getInput("datefrom")) + "&dateto=" + formatDateToDB(getInput("dateto")) + "&dbname=" + getGlobal("dbname") + "&companycode=" + getGlobal("company") + "&branchcode=" + getInput("branchcode") + "&byopt=" + getInput("byopt") + "&valopt=" + getInput("valopt") + "&topopt=" + getInput("topopt") + "&userid=" + getGlobal("userid");
		//parent.pieFrame.location = "../FusionCharts/MySamples/fci_sales_ytdbyitemgroups2_graph.php?year=" + getInput("year") + "&month=" + getInput("month") + "&mnl=" + getInput("mnl") + "&dvo=" + getInput("dvo") + "&ceb=" + getInput("ceb");
		
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "df_datefrom":
			case "df_dateto":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
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
	  <td ><label <?php genCaptionHtml($schema["datefrom"],"") ?>>Sales From</label>&nbsp;<input type="text" size=12 <?php genInputTextHtml($schema["datefrom"]) ?> /><label <?php genCaptionHtml($schema["dateto"],"") ?>>To</label>&nbsp;<input type="text" size=12 <?php genInputTextHtml($schema["dateto"]) ?> /><label <?php genCaptionHtml($schema["branchcode"],"") ?>>Branch</label>&nbsp;<select <?php genSelectHtml($schema["branchcode"],array("loadbrancheslist","",":[All]"),null,null,null,"width:168px") ?> ></select>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Go</a></td>
      <td width="450"  align=right><label <?php genCaptionHtml($schema["byopt"],"") ?>>Display By</label>&nbsp;<select <?php genSelectHtml($schema["byopt"],array("loadenumbyopt","","")) ?> ></select><label <?php genCaptionHtml($schema["valopt"],"") ?>>Sort By</label>&nbsp;<select <?php genSelectHtml($schema["valopt"],array("loadenumvalopt","","")) ?> ></select><label <?php genCaptionHtml($schema["topopt"],"") ?>>Top</label>&nbsp;<input type="text" size=3 <?php genInputTextHtml($schema["topopt"]) ?> /></td>
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
