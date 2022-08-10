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
	
	$pageHeader = "Collections";
	unset($enumyear[""]);
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_YBLIPRLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_collections_grid";
	$page->formid = "./UDO.php?&objectcode=U_YBLIPRS";
	$page->objectname = "IPR List";
	
	$schema["branchcode"] = createSchemaUpper("branchcode");
	$schema["mnl"] = createSchemaUpper("mnl");
	$schema["ceb"] = createSchemaUpper("ceb");
	$schema["dvo"] = createSchemaUpper("dvo");
	$schema["year"] = createSchemaUpper("year");
	$schema["month"] = createSchema("month");
	$schema["automins"] = createSchemaNumeric("automins");

	$schema["valopt"] = createSchemaUpper("valopt");
	$schema["byopt"] = createSchemaUpper("byopt");

	$schema["branchcode"]["visible"] = false;
	$schema["valopt"]["visible"] = false;
	
	$schema["firstview"] = createSchemaNumeric("firstview");

	if ($_SESSION["branchtype"]=="B") {
		$schema["branchcode"]["attributes"] = "disabled";
	}	

	$enumbyopt= array('profitcenter' => 'Profit Center', 'item' => 'Fees & Charges');

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
	$objGrid->addcolumn("itemgroupname");
	$objGrid->addcolumn("totalperc");
	$objGrid->addcolumn("0");
	$objGrid->addcolumn("1");
	$objGrid->addcolumn("2");
	$objGrid->addcolumn("3");
	$objGrid->addcolumn("4");
	$objGrid->addcolumn("5");
	$objGrid->addcolumn("6");
	$objGrid->addcolumn("7");
	$objGrid->addcolumn("8");
	$objGrid->addcolumn("9");
	$objGrid->addcolumn("10");
	$objGrid->addcolumn("11");
	$objGrid->addcolumn("12");
	$objGrid->addcolumn("13");
	$objGrid->addcolumn("14");
	$objGrid->addcolumn("15");
	$objGrid->addcolumn("16");
	$objGrid->addcolumn("17");
	$objGrid->addcolumn("18");
	$objGrid->addcolumn("19");
	$objGrid->addcolumn("20");
	$objGrid->addcolumn("21");
	$objGrid->addcolumn("22");
	$objGrid->addcolumn("23");
	$objGrid->addcolumn("24");
	$objGrid->addcolumn("25");
	$objGrid->addcolumn("26");
	$objGrid->addcolumn("27");
	$objGrid->addcolumn("28");
	$objGrid->addcolumn("29");
	$objGrid->addcolumn("30");
	$objGrid->addcolumn("31");
	
	$objGrid->columntitle("rank","#");
	$objGrid->columntitle("0","TOTAL (PHP)");
	$objGrid->columntitle("totalperc","%");
	
	$objGrid->columnalignment("rank","right");
	$objGrid->columnalignment("0","right");
	$objGrid->columnalignment("totalperc","right");
	$objGrid->columnalignment("1","right");
	$objGrid->columnalignment("2","right");
	$objGrid->columnalignment("3","right");
	$objGrid->columnalignment("4","right");
	$objGrid->columnalignment("5","right");
	$objGrid->columnalignment("6","right");
	$objGrid->columnalignment("7","right");
	$objGrid->columnalignment("8","right");
	$objGrid->columnalignment("9","right");
	$objGrid->columnalignment("10","right");
	$objGrid->columnalignment("11","right");
	$objGrid->columnalignment("12","right");
	$objGrid->columnalignment("13","right");
	$objGrid->columnalignment("14","right");
	$objGrid->columnalignment("15","right");
	$objGrid->columnalignment("16","right");
	$objGrid->columnalignment("17","right");
	$objGrid->columnalignment("18","right");
	$objGrid->columnalignment("19","right");
	$objGrid->columnalignment("20","right");
	$objGrid->columnalignment("21","right");
	$objGrid->columnalignment("22","right");
	$objGrid->columnalignment("23","right");
	$objGrid->columnalignment("24","right");
	$objGrid->columnalignment("25","right");
	$objGrid->columnalignment("26","right");
	$objGrid->columnalignment("27","right");
	$objGrid->columnalignment("28","right");
	$objGrid->columnalignment("29","right");
	$objGrid->columnalignment("30","right");
	$objGrid->columnalignment("31","right");

	$objGrid->columndataentry("rank","type","label");
	$objGrid->columndataentry("itemgroupname","type","label");
	$objGrid->columndataentry("totalperc","type","label");
	$objGrid->columndataentry("0","type","label");
	$objGrid->columndataentry("1","type","label");
	$objGrid->columndataentry("2","type","label");
	$objGrid->columndataentry("3","type","label");
	$objGrid->columndataentry("4","type","label");
	$objGrid->columndataentry("5","type","label");
	$objGrid->columndataentry("6","type","label");
	$objGrid->columndataentry("7","type","label");
	$objGrid->columndataentry("8","type","label");
	$objGrid->columndataentry("9","type","label");
	$objGrid->columndataentry("10","type","label");
	$objGrid->columndataentry("11","type","label");
	$objGrid->columndataentry("12","type","label");
	$objGrid->columndataentry("13","type","label");
	$objGrid->columndataentry("14","type","label");
	$objGrid->columndataentry("15","type","label");
	$objGrid->columndataentry("16","type","label");
	$objGrid->columndataentry("17","type","label");
	$objGrid->columndataentry("18","type","label");
	$objGrid->columndataentry("19","type","label");
	$objGrid->columndataentry("20","type","label");
	$objGrid->columndataentry("21","type","label");
	$objGrid->columndataentry("22","type","label");
	$objGrid->columndataentry("23","type","label");
	$objGrid->columndataentry("24","type","label");
	$objGrid->columndataentry("25","type","label");
	$objGrid->columndataentry("26","type","label");
	$objGrid->columndataentry("27","type","label");
	$objGrid->columndataentry("28","type","label");
	$objGrid->columndataentry("29","type","label");
	$objGrid->columndataentry("30","type","label");
	$objGrid->columndataentry("31","type","label");
	
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
	
	
	if (!isset($httpVars['df_year'])) {
		$page->setitem("year",date('Y'));
		$page->setitem("month",date('m'));
		$page->setitem("mnl",1);
		$page->setitem("ceb",1);
		$page->setitem("dvo",1);
		$page->setitem("branchcode","");
		$page->setitem("byopt","branch");
		$page->setitem("valopt","value");
		$page->setitem("automins",0.5);
		$page->setitem("firstview",1);
		$page->setitem("byopt","item");
		
	}
	
	if ($page->getitemstring("month")=="") {
		$start = $page->getitemstring("year") ."-01-01";
		$end = $page->getitemstring("year") ."-12-31";
	} else {
		$start = $page->getitemstring("year") ."-".$page->getitemstring("month")."-01";
		$end = getmonthendDB($start);
	}
	$mn = strtoupper(date('M',strtotime($start)));
	
	if ($page->getitemstring("byopt")=="profitcenter") {
		$objGrid->columntitle("itemgroupname","Profit Center");
	} elseif ($page->getitemstring("byopt")=="item") {
		$objGrid->columntitle("itemgroupname","Fees & Charges");
	}

	if ($page->getitemstring("byopt")=="profitcenter" || $page->getitemstring("byopt")=="item") {
		$objGrid->columnwidth("itemgroupname",20);
	} else {
		$objGrid->columnwidth("itemgroupname",11);
	}
	

	$objGrid->columnwidth("rank",4);
	$objGrid->columnwidth("totalperc",5);

	if ($page->getitemstring("month")=="") {
		$objGrid->columnwidth("0",12);
		$objGrid->columntitle("1","JAN");
		$objGrid->columntitle("2","FEB");
		$objGrid->columntitle("3","MAR");
		$objGrid->columntitle("4","APR");
		$objGrid->columntitle("5","MAY");
		$objGrid->columntitle("6","JUN");
		$objGrid->columntitle("7","JUL");
		$objGrid->columntitle("8","AUG");
		$objGrid->columntitle("9","SEP");
		$objGrid->columntitle("10","OCT");
		$objGrid->columntitle("11","NOV");
		$objGrid->columntitle("12","DEC");
		
		$objGrid->columnwidth("1",11);
		$objGrid->columnwidth("2",11);
		$objGrid->columnwidth("3",11);
		$objGrid->columnwidth("4",11);
		$objGrid->columnwidth("5",11);
		$objGrid->columnwidth("6",11);
		$objGrid->columnwidth("7",11);
		$objGrid->columnwidth("8",11);
		$objGrid->columnwidth("9",11);
		$objGrid->columnwidth("10",11);
		$objGrid->columnwidth("11",11);
		$objGrid->columnwidth("12",11);
		
	} else {
		$objGrid->columnwidth("0",10);
		$objGrid->columntitle("1",$mn. " "."1");
		$objGrid->columntitle("2",$mn. " "."2");
		$objGrid->columntitle("3",$mn. " "."3");
		$objGrid->columntitle("4",$mn. " "."4");
		$objGrid->columntitle("5",$mn. " "."5");
		$objGrid->columntitle("6",$mn. " "."6");
		$objGrid->columntitle("7",$mn. " "."7");
		$objGrid->columntitle("8",$mn. " "."8");
		$objGrid->columntitle("9",$mn. " "."9");
		$objGrid->columntitle("10",$mn. " "."10");
		$objGrid->columntitle("11",$mn. " "."11");
		$objGrid->columntitle("12",$mn. " "."12");
		$objGrid->columntitle("13",$mn. " "."13");
		$objGrid->columntitle("14",$mn. " "."14");
		$objGrid->columntitle("15",$mn. " "."15");
		$objGrid->columntitle("16",$mn. " "."16");
		$objGrid->columntitle("17",$mn. " "."17");
		$objGrid->columntitle("18",$mn. " "."18");
		$objGrid->columntitle("19",$mn. " "."19");
		$objGrid->columntitle("20",$mn. " "."20");
		$objGrid->columntitle("21",$mn. " "."21");
		$objGrid->columntitle("22",$mn. " "."22");
		$objGrid->columntitle("23",$mn. " "."23");
		$objGrid->columntitle("24",$mn. " "."24");
		$objGrid->columntitle("25",$mn. " "."25");
		$objGrid->columntitle("26",$mn. " "."26");
		$objGrid->columntitle("27",$mn. " "."27");
		$objGrid->columntitle("28",$mn. " "."28");
		$objGrid->columntitle("29",$mn. " "."29");
		$objGrid->columntitle("30",$mn. " "."30");
		$objGrid->columntitle("31",$mn. " "."31");

		$objGrid->columnwidth("1",10);
		$objGrid->columnwidth("2",10);
		$objGrid->columnwidth("3",10);
		$objGrid->columnwidth("4",10);
		$objGrid->columnwidth("5",10);
		$objGrid->columnwidth("6",10);
		$objGrid->columnwidth("7",10);
		$objGrid->columnwidth("8",10);
		$objGrid->columnwidth("9",10);
		$objGrid->columnwidth("10",10);
		$objGrid->columnwidth("11",10);
		$objGrid->columnwidth("12",10);
		$objGrid->columnwidth("13",10);
		$objGrid->columnwidth("14",10);
		$objGrid->columnwidth("15",10);
		$objGrid->columnwidth("16",10);
		$objGrid->columnwidth("17",10);
		$objGrid->columnwidth("18",10);
		$objGrid->columnwidth("19",10);
		$objGrid->columnwidth("20",10);
		$objGrid->columnwidth("21",10);
		$objGrid->columnwidth("22",10);
		$objGrid->columnwidth("23",10);
		$objGrid->columnwidth("24",10);
		$objGrid->columnwidth("25",10);
		$objGrid->columnwidth("26",10);
		$objGrid->columnwidth("27",10);
		$objGrid->columnwidth("28",10);
		$objGrid->columnwidth("29",10);
		$objGrid->columnwidth("30",10);
		$objGrid->columnwidth("31",10);
	}
	
	if ($page->getitemstring("valopt")=="qty") $objGrid->columntitle("0","TOTAL (QTY)");
	
	
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
	$objrs->setdebug();
	//var_dump($page->getitemstring("u_nursed"));
	$branchcodeExp = "";
	//if ($page->getitemstring("branchcode")!="") $branchcodeExp = " and a.branch='".$page->getitemstring("branchcode")."'";
	$unionExp = "";
	$dbname = $_SESSION["dbname"];
	$unionExp .= iif($unionExp!=""," UNION ALL ","");
	if ($page->getitemstring("byopt")=="value") {
	/*	union all 
select d.itemgroupname, a.docdate, b.dropship, b.quantity *-1 as quantity, (if(a.basetype='',b.linetotal-b.linetotaldisc,b.linetotal-b.baselinetotaldisc))*-1 as linetotal from $dbname.arcreditmemos a inner join $dbname.arcreditmemoitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join $dbname.items c on c.itemcode=b.itemcode inner join $dbname.itemgroups d on d.itemgroup = c.itemgroup where a.company='FCI_MNL' and a.branch='MAIN' and a.docstatus in ('O','C') and a.docdate between '$start' and '$end'*/
		$unionExp .= "select d.itemgroupname, a.u_date as docdate, 0 as dropship, b.u_quantity as quantity,  b.u_quantity * IF(b.u_freebie = 0,b.u_price,0) as linetotal from $dbname.u_pos a inner join $dbname.u_positems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_selected=1 inner join $dbname.items c on c.itemcode=b.u_itemcode left join $dbname.itemgroups d on d.itemgroup = c.itemgroup where a.company='GHZ' $branchcodeExp and a.u_status not in ('D','CN') and a.u_date between '$start' and '$end' 
";
	} elseif ($page->getitemstring("byopt")=="profitcenter") {
		$unionExp .= "select d.name as itemgroupname, a.u_date as docdate, 0 as dropship, b.u_quantity as quantity,  b.u_quantity * IF(b.u_freebie = 0,b.u_price,0) as linetotal from $dbname.u_lgupos a inner join $dbname.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_selected=1 inner join $dbname.u_lgufees c on c.code=b.u_itemcode inner join u_lguprofitcenters d on d.code=a.u_profitcenter where a.company='LGU' $branchcodeExp and a.u_status not in ('D','CN') and a.u_date between '$start' and '$end'";
	} elseif ($page->getitemstring("byopt")=="item") {
		$unionExp .= "select c.name as itemgroupname, a.u_date as docdate, 0 as dropship, b.u_quantity as quantity, b.u_quantity * IF(b.u_freebie = 0,b.u_price,0) as linetotal from $dbname.u_lgupos a inner join $dbname.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_selected=1 inner join $dbname.u_lgufees c on c.code=b.u_itemcode where a.company='LGU' $branchcodeExp and a.u_status not in ('D','CN') and a.u_date between '$start' and '$end'";
	}
	
	//var_dump($unionExp);
	if ($page->getitemstring("month")=="") {
		$objrs->queryopen("select itemgroupname,
  sum(if(month(docdate)=1,linetotal,0)) as jan,
  sum(if(month(docdate)=2,linetotal,0)) as feb,
  sum(if(month(docdate)=3,linetotal,0)) as mar,
  sum(if(month(docdate)=4,linetotal,0)) as apr,
  sum(if(month(docdate)=5,linetotal,0)) as may,
  sum(if(month(docdate)=6,linetotal,0)) as jun,
  sum(if(month(docdate)=7,linetotal,0)) as jul,
  sum(if(month(docdate)=8,linetotal,0)) as aug,
  sum(if(month(docdate)=9,linetotal,0)) as sep,
  sum(if(month(docdate)=10,linetotal,0)) as oct,
  sum(if(month(docdate)=11,linetotal,0)) as nov,
  sum(if(month(docdate)=12,linetotal,0)) as `dec`,
  sum(linetotal) as `all`,
  sum(if(month(docdate)=1 and dropship=0,quantity,0)) as jan_qty,
  sum(if(month(docdate)=2 and dropship=0,quantity,0)) as feb_qty,
  sum(if(month(docdate)=3 and dropship=0,quantity,0)) as mar_qty,
  sum(if(month(docdate)=4 and dropship=0,quantity,0)) as apr_qty,
  sum(if(month(docdate)=5 and dropship=0,quantity,0)) as may_qty,
  sum(if(month(docdate)=6 and dropship=0,quantity,0)) as jun_qty,
  sum(if(month(docdate)=7 and dropship=0,quantity,0)) as jul_qty,
  sum(if(month(docdate)=8 and dropship=0,quantity,0)) as aug_qty,
  sum(if(month(docdate)=9 and dropship=0,quantity,0)) as sep_qty,
  sum(if(month(docdate)=10 and dropship=0,quantity,0)) as oct_qty,
  sum(if(month(docdate)=11 and dropship=0,quantity,0)) as nov_qty,
  sum(if(month(docdate)=12 and dropship=0,quantity,0)) as `dec_qty`,
  sum(if(dropship=0,quantity,0)) as `all_qty`
  from ( $unionExp ) as x group by itemgroupname order by `all".iif($page->getitemstring("valopt")!="value","_qty","")."` desc ");
	} else {
		$objrs->queryopen("select itemgroupname,
  sum(if(day(docdate)=1,linetotal,0)) as d1,
  sum(if(day(docdate)=2,linetotal,0)) as d2,
  sum(if(day(docdate)=3,linetotal,0)) as d3,
  sum(if(day(docdate)=4,linetotal,0)) as d4,
  sum(if(day(docdate)=5,linetotal,0)) as d5,
  sum(if(day(docdate)=6,linetotal,0)) as d6,
  sum(if(day(docdate)=7,linetotal,0)) as d7,
  sum(if(day(docdate)=8,linetotal,0)) as d8,
  sum(if(day(docdate)=9,linetotal,0)) as d9,
  sum(if(day(docdate)=10,linetotal,0)) as d10,
  sum(if(day(docdate)=11,linetotal,0)) as d11,
  sum(if(day(docdate)=12,linetotal,0)) as d12,
  sum(if(day(docdate)=13,linetotal,0)) as d13,
  sum(if(day(docdate)=14,linetotal,0)) as d14,
  sum(if(day(docdate)=15,linetotal,0)) as d15,
  sum(if(day(docdate)=16,linetotal,0)) as d16,
  sum(if(day(docdate)=17,linetotal,0)) as d17,
  sum(if(day(docdate)=18,linetotal,0)) as d18,
  sum(if(day(docdate)=19,linetotal,0)) as d19,
  sum(if(day(docdate)=20,linetotal,0)) as d20,
  sum(if(day(docdate)=21,linetotal,0)) as d21,
  sum(if(day(docdate)=22,linetotal,0)) as d22,
  sum(if(day(docdate)=23,linetotal,0)) as d23,
  sum(if(day(docdate)=24,linetotal,0)) as d24,
  sum(if(day(docdate)=25,linetotal,0)) as d25,
  sum(if(day(docdate)=26,linetotal,0)) as d26,
  sum(if(day(docdate)=27,linetotal,0)) as d27,
  sum(if(day(docdate)=28,linetotal,0)) as d28,
  sum(if(day(docdate)=29,linetotal,0)) as d29,
  sum(if(day(docdate)=30,linetotal,0)) as d30,
  sum(if(day(docdate)=31,linetotal,0)) as d31,
  sum(linetotal) as `all`,
 sum(if(day(docdate)=1 and dropship=0,quantity,0)) as d1_qty,
  sum(if(day(docdate)=2 and dropship=0,quantity,0)) as d2_qty,
  sum(if(day(docdate)=3 and dropship=0,quantity,0)) as d3_qty,
  sum(if(day(docdate)=4 and dropship=0,quantity,0)) as d4_qty,
  sum(if(day(docdate)=5 and dropship=0,quantity,0)) as d5_qty,
  sum(if(day(docdate)=6 and dropship=0,quantity,0)) as d6_qty,
  sum(if(day(docdate)=7 and dropship=0,quantity,0)) as d7_qty,
  sum(if(day(docdate)=8 and dropship=0,quantity,0)) as d8_qty,
  sum(if(day(docdate)=9 and dropship=0,quantity,0)) as d9_qty,
  sum(if(day(docdate)=10 and dropship=0,quantity,0)) as d10_qty,
  sum(if(day(docdate)=11 and dropship=0,quantity,0)) as d11_qty,
  sum(if(day(docdate)=12 and dropship=0,quantity,0)) as d12_qty,
  sum(if(day(docdate)=13 and dropship=0,quantity,0)) as d13_qty,
  sum(if(day(docdate)=14 and dropship=0,quantity,0)) as d14_qty,
  sum(if(day(docdate)=15 and dropship=0,quantity,0)) as d15_qty,
  sum(if(day(docdate)=16 and dropship=0,quantity,0)) as d16_qty,
  sum(if(day(docdate)=17 and dropship=0,quantity,0)) as d17_qty,
  sum(if(day(docdate)=18 and dropship=0,quantity,0)) as d18_qty,
  sum(if(day(docdate)=19 and dropship=0,quantity,0)) as d19_qty,
  sum(if(day(docdate)=20 and dropship=0,quantity,0)) as d20_qty,
  sum(if(day(docdate)=21 and dropship=0,quantity,0)) as d21_qty,
  sum(if(day(docdate)=22 and dropship=0,quantity,0)) as d22_qty,
  sum(if(day(docdate)=23 and dropship=0,quantity,0)) as d23_qty,
  sum(if(day(docdate)=24 and dropship=0,quantity,0)) as d24_qty,
  sum(if(day(docdate)=25 and dropship=0,quantity,0)) as d25_qty,
  sum(if(day(docdate)=26 and dropship=0,quantity,0)) as d26_qty,
  sum(if(day(docdate)=27 and dropship=0,quantity,0)) as d27_qty,
  sum(if(day(docdate)=28 and dropship=0,quantity,0)) as d28_qty,
  sum(if(day(docdate)=29 and dropship=0,quantity,0)) as d29_qty,
  sum(if(day(docdate)=30 and dropship=0,quantity,0)) as d30_qty,
  sum(if(day(docdate)=31 and dropship=0,quantity,0)) as d31_qty,
  sum(if(dropship=0,quantity,0)) as `all_qty`
  from ( $unionExp ) as x group by itemgroupname order by `all".iif($page->getitemstring("valopt")!="value","_qty","")."` desc ");
  	}
	//var_dump($objrs->sqls);
	
	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		$objGrid->addrow();
		
		$sufix = "";
		if ($page->getitemstring("valopt")=="qty") $sufix = "_qty";
		
		$objGrid->setitem(null,"rank",$objGrid->recordcount);
		if ($page->getitemstring("month")=="") {
			$objGrid->setitem(null,"itemgroupname",$objrs->fields["itemgroupname"]);
			$objGrid->setitem(null,"1",formatNumericAmount($objrs->fields["jan".$sufix]));
			$objGrid->setitem(null,"2",formatNumericAmount($objrs->fields["feb".$sufix]));
			$objGrid->setitem(null,"3",formatNumericAmount($objrs->fields["mar".$sufix]));
			$objGrid->setitem(null,"4",formatNumericAmount($objrs->fields["apr".$sufix]));
			$objGrid->setitem(null,"5",formatNumericAmount($objrs->fields["may".$sufix]));
			$objGrid->setitem(null,"6",formatNumericAmount($objrs->fields["jun".$sufix]));
			$objGrid->setitem(null,"7",formatNumericAmount($objrs->fields["jul".$sufix]));
			$objGrid->setitem(null,"8",formatNumericAmount($objrs->fields["aug".$sufix]));
			$objGrid->setitem(null,"9",formatNumericAmount($objrs->fields["sep".$sufix]));
			$objGrid->setitem(null,"10",formatNumericAmount($objrs->fields["oct".$sufix]));
			$objGrid->setitem(null,"11",formatNumericAmount($objrs->fields["nov".$sufix]));
			$objGrid->setitem(null,"12",formatNumericAmount($objrs->fields["dec".$sufix]));
			$objGrid->setitem(null,"0",formatNumericAmount($objrs->fields["all".$sufix]));
			//$objGrid->setitem(null,"totalperc",formatNumericPercent($objrs->fields["all".$sufix]));
			$totals[0] += $objrs->fields["all".$sufix];
			$totals[1] += $objrs->fields["jan".$sufix];
			$totals[2] += $objrs->fields["feb".$sufix];
			$totals[3] += $objrs->fields["mar".$sufix];
			$totals[4] += $objrs->fields["apr".$sufix];
			$totals[5] += $objrs->fields["may".$sufix];
			$totals[6] += $objrs->fields["jun".$sufix];
			$totals[7] += $objrs->fields["jul".$sufix];
			$totals[8] += $objrs->fields["aug".$sufix];
			$totals[9] += $objrs->fields["sep".$sufix];
			$totals[10] += $objrs->fields["oct".$sufix];
			$totals[11] += $objrs->fields["nov".$sufix];
			$totals[12] += $objrs->fields["dec".$sufix];
		} else {
			$objGrid->setitem(null,"itemgroupname",$objrs->fields["itemgroupname"]);
			$objGrid->setitem(null,"1",formatNumericAmount($objrs->fields["d1".$sufix]));
			$objGrid->setitem(null,"2",formatNumericAmount($objrs->fields["d2".$sufix]));
			$objGrid->setitem(null,"3",formatNumericAmount($objrs->fields["d3".$sufix]));
			$objGrid->setitem(null,"4",formatNumericAmount($objrs->fields["d4".$sufix]));
			$objGrid->setitem(null,"5",formatNumericAmount($objrs->fields["d5".$sufix]));
			$objGrid->setitem(null,"6",formatNumericAmount($objrs->fields["d6".$sufix]));
			$objGrid->setitem(null,"7",formatNumericAmount($objrs->fields["d7".$sufix]));
			$objGrid->setitem(null,"8",formatNumericAmount($objrs->fields["d8".$sufix]));
			$objGrid->setitem(null,"9",formatNumericAmount($objrs->fields["d9".$sufix]));
			$objGrid->setitem(null,"10",formatNumericAmount($objrs->fields["d10".$sufix]));
			$objGrid->setitem(null,"11",formatNumericAmount($objrs->fields["d11".$sufix]));
			$objGrid->setitem(null,"12",formatNumericAmount($objrs->fields["d12".$sufix]));
			$objGrid->setitem(null,"13",formatNumericAmount($objrs->fields["d13".$sufix]));
			$objGrid->setitem(null,"14",formatNumericAmount($objrs->fields["d14".$sufix]));
			$objGrid->setitem(null,"15",formatNumericAmount($objrs->fields["d15".$sufix]));
			$objGrid->setitem(null,"16",formatNumericAmount($objrs->fields["d16".$sufix]));
			$objGrid->setitem(null,"17",formatNumericAmount($objrs->fields["d17".$sufix]));
			$objGrid->setitem(null,"18",formatNumericAmount($objrs->fields["d18".$sufix]));
			$objGrid->setitem(null,"19",formatNumericAmount($objrs->fields["d19".$sufix]));
			$objGrid->setitem(null,"20",formatNumericAmount($objrs->fields["d20".$sufix]));
			$objGrid->setitem(null,"21",formatNumericAmount($objrs->fields["d21".$sufix]));
			$objGrid->setitem(null,"22",formatNumericAmount($objrs->fields["d22".$sufix]));
			$objGrid->setitem(null,"23",formatNumericAmount($objrs->fields["d23".$sufix]));
			$objGrid->setitem(null,"24",formatNumericAmount($objrs->fields["d24".$sufix]));
			$objGrid->setitem(null,"25",formatNumericAmount($objrs->fields["d25".$sufix]));
			$objGrid->setitem(null,"26",formatNumericAmount($objrs->fields["d26".$sufix]));
			$objGrid->setitem(null,"27",formatNumericAmount($objrs->fields["d27".$sufix]));
			$objGrid->setitem(null,"28",formatNumericAmount($objrs->fields["d28".$sufix]));
			$objGrid->setitem(null,"29",formatNumericAmount($objrs->fields["d29".$sufix]));
			$objGrid->setitem(null,"30",formatNumericAmount($objrs->fields["d30".$sufix]));
			$objGrid->setitem(null,"31",formatNumericAmount($objrs->fields["d31".$sufix]));
			$objGrid->setitem(null,"0",formatNumericAmount($objrs->fields["all".$sufix]));
			$totals[0] += $objrs->fields["all".$sufix];
			$totals[1] += $objrs->fields["d1".$sufix];
			$totals[2] += $objrs->fields["d2".$sufix];
			$totals[3] += $objrs->fields["d3".$sufix];
			$totals[4] += $objrs->fields["d4".$sufix];
			$totals[5] += $objrs->fields["d5".$sufix];
			$totals[6] += $objrs->fields["d6".$sufix];
			$totals[7] += $objrs->fields["d7".$sufix];
			$totals[8] += $objrs->fields["d8".$sufix];
			$totals[9] += $objrs->fields["d9".$sufix];
			$totals[10] += $objrs->fields["d10".$sufix];
			$totals[11] += $objrs->fields["d11".$sufix];
			$totals[12] += $objrs->fields["d12".$sufix];
			$totals[13] += $objrs->fields["d13".$sufix];
			$totals[14] += $objrs->fields["d14".$sufix];
			$totals[15] += $objrs->fields["d15".$sufix];
			$totals[16] += $objrs->fields["d16".$sufix];
			$totals[17] += $objrs->fields["d17".$sufix];
			$totals[18] += $objrs->fields["d18".$sufix];
			$totals[19] += $objrs->fields["d19".$sufix];
			$totals[20] += $objrs->fields["d20".$sufix];
			$totals[21] += $objrs->fields["d21".$sufix];
			$totals[22] += $objrs->fields["d22".$sufix];
			$totals[23] += $objrs->fields["d23".$sufix];
			$totals[24] += $objrs->fields["d24".$sufix];
			$totals[25] += $objrs->fields["d25".$sufix];
			$totals[26] += $objrs->fields["d26".$sufix];
			$totals[27] += $objrs->fields["d27".$sufix];
			$totals[28] += $objrs->fields["d28".$sufix];
			$totals[29] += $objrs->fields["d29".$sufix];
			$totals[30] += $objrs->fields["d30".$sufix];
			$totals[31] += $objrs->fields["d31".$sufix];
		}
		
	}	
	$objGrid->setdefaultvalue("1",formatNumericAmount($totals[1]));
	$objGrid->setdefaultvalue("2",formatNumericAmount($totals[2]));
	$objGrid->setdefaultvalue("3",formatNumericAmount($totals[3]));
	$objGrid->setdefaultvalue("4",formatNumericAmount($totals[4]));
	$objGrid->setdefaultvalue("5",formatNumericAmount($totals[5]));
	$objGrid->setdefaultvalue("6",formatNumericAmount($totals[6]));
	$objGrid->setdefaultvalue("7",formatNumericAmount($totals[7]));
	$objGrid->setdefaultvalue("8",formatNumericAmount($totals[8]));
	$objGrid->setdefaultvalue("9",formatNumericAmount($totals[9]));
	$objGrid->setdefaultvalue("10",formatNumericAmount($totals[10]));
	$objGrid->setdefaultvalue("11",formatNumericAmount($totals[11]));
	$objGrid->setdefaultvalue("12",formatNumericAmount($totals[12]));
	$objGrid->setdefaultvalue("13",formatNumericAmount($totals[13]));
	$objGrid->setdefaultvalue("14",formatNumericAmount($totals[14]));
	$objGrid->setdefaultvalue("15",formatNumericAmount($totals[15]));
	$objGrid->setdefaultvalue("16",formatNumericAmount($totals[16]));
	$objGrid->setdefaultvalue("17",formatNumericAmount($totals[17]));
	$objGrid->setdefaultvalue("18",formatNumericAmount($totals[18]));
	$objGrid->setdefaultvalue("19",formatNumericAmount($totals[19]));
	$objGrid->setdefaultvalue("20",formatNumericAmount($totals[20]));
	$objGrid->setdefaultvalue("21",formatNumericAmount($totals[21]));
	$objGrid->setdefaultvalue("22",formatNumericAmount($totals[22]));
	$objGrid->setdefaultvalue("23",formatNumericAmount($totals[23]));
	$objGrid->setdefaultvalue("24",formatNumericAmount($totals[24]));
	$objGrid->setdefaultvalue("25",formatNumericAmount($totals[25]));
	$objGrid->setdefaultvalue("26",formatNumericAmount($totals[26]));
	$objGrid->setdefaultvalue("27",formatNumericAmount($totals[27]));
	$objGrid->setdefaultvalue("28",formatNumericAmount($totals[28]));
	$objGrid->setdefaultvalue("29",formatNumericAmount($totals[29]));
	$objGrid->setdefaultvalue("30",formatNumericAmount($totals[30]));
	$objGrid->setdefaultvalue("31",formatNumericAmount($totals[31]));
	$objGrid->setdefaultvalue("0",formatNumericAmount($totals[0]));
	
	for ($i=1;$i<=$objGrid->recordcount ;$i++) {
		$objGrid->setitem($i,"totalperc",formatNumericAmount(($objGrid->getitemdecimal($i,"0") / $totals[0])*100));
	}
	
	if ($page->getitemstring("month")=="") {
		foreach ($totals as $key => $value) {
			if ($value==0){
				$objGrid->columnvisibility($key,false);
			}
		}
	}
	//$page->resize->addgrid("T1",20,80,false);
	$page->resize->addgridobject($objGrid,30,100); 
	
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
			parent.barFrame.location = "../FusionCharts/MySamples/lgu_collections_graph.php?year=" + getInput("year") + "&month=" + getInput("month") + "&valopt=" + getInput("valopt")  + "&byopt=" + getInput("byopt") + "&branchcode=" + getInput("branchcode") + "&dbname=" + getGlobal("dbname")  ;
			parent.pieFrame.location = "../FusionCharts/MySamples/lgu_collections2_graph.php?year=" + getInput("year") + "&month=" + getInput("month") + "&valopt=" + getInput("valopt")  + "&byopt=" + getInput("byopt") + "&branchcode=" + getInput("branchcode") + "&dbname=" + getGlobal("dbname") ;

		}	
		if (getInputNumeric("automins")>0) {
			setTimeout("formSearchAuto()",getInputNumeric("automins")*60000);
		} 
	}

	function formSearchAuto() {
		if (getInputNumeric("automins")>0) formSearchNow();
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
			case "byopt":
				formSearchNow();
				break;
			case "branchcode":
				formSearchNow();
				break;
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
				clearTable("T1");
				formPageReset(); break;
			case "automins":
				if (getInputNumeric("automins")>0) {
					setTimeout("formSearchAuto()",getInputNumeric("automins")*60000);
				} 
				break;	
		}
		return true;
	}	

	function onElementClick(element,column,table,row) {
		switch (column) {
			case "ceb":
			case "dvo":
			case "mnl":
				clearTable("T1");
				formPageReset(); 
				break;
			case "valopt":
				formSearchNow();
				break;
			/*case "byopt":
				formSearchNow();
				break;*/
		}
		return true;
	}	
	
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_dvo":
			case "df_year":
			case "df_month":
			case "df_mnl":
			case "df_ceb":
			case "df_branchcode":
			case "df_byopt":
			case "df_valopt":
			case "df_automins":
			return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("dvo");
			inputs.push("year");
			inputs.push("month");
			inputs.push("mnl");
			inputs.push("ceb");
			inputs.push("branchcode");
			inputs.push("byopt");
			inputs.push("valopt");
			inputs.push("automins");
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
		parent.barFrame.location = "../FusionCharts/MySamples/lgu_collections_graph.php?year=" + getInput("year") + "&month=" + getInput("month") + "&valopt=" + getInput("valopt")  + "&byopt=" + getInput("byopt") + "&branchcode=" + getInput("branchcode") + "&dbname=" + getGlobal("dbname") ;
		parent.pieFrame.location = "../FusionCharts/MySamples/lgu_collections2_graph.php?year=" + getInput("year") + "&month=" + getInput("month") + "&valopt=" + getInput("valopt")  + "&byopt=" + getInput("byopt") + "&branchcode=" + getInput("branchcode") + "&dbname=" + getGlobal("dbname") ;

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
<input type="hidden" <?php genInputHiddenDFHtml("branchcode") ?> >
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
	  <td align="left"><label <?php genCaptionHtml($schema["year"],"") ?>>Year</label>&nbsp;<select <?php genSelectHtml($schema["year"],array("loadenumyear","","")) ?> ></select>&nbsp;<select <?php genSelectHtml($schema["month"],
	  array("loadenummonth","",":[All]")) ?> ></select>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Go</a></td>
      <td align=right><label <?php genCaptionHtml($schema["byopt"],"") ?>>Display By</label>&nbsp;<select <?php genSelectHtml($schema["byopt"],array("loadenumbyopt","","")) ?> ></select>&nbsp;&nbsp;<label <?php genCaptionHtml($schema["automins"],"") ?>>Refresh Mins </label><input type="text" size=2 alt='Auto Refresh Minutes' <?php genInputTextHtml($schema["automins"]) ?> /></td>
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
