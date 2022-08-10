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
	
	$pageHeader = "Profit & Loss";
	unset($enumyear[""]);
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_YBLIPRLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_pnl_grid";
	$page->formid = "./UDO.php?&objectcode=U_YBLIPRS";
	$page->objectname = "IPR List";
	
	$schema["branchcode"] = createSchemaUpper("branchcode");
	$schema["year"] = createSchemaUpper("year");
	$schema["month"] = createSchema("month");
	$schema["month"]["attributes"] = "disabled";

	$schema["valopt"] = createSchemaUpper("valopt");
	$schema["byopt"] = createSchemaUpper("byopt");

	$schema["firstview"] = createSchemaNumeric("firstview");

	if ($_SESSION["branchtype"]=="B") {
		$schema["branchcode"]["attributes"] = "disabled";
	}	
	
	//$enumbyopt= array('month' => 'Month', 'branch' => 'Branch', 'item' => 'Item', 'value' => 'Item Group','itemclass' => 'Item Class', 'itemsubclass' => 'Item Sub-Class', 'itemindicator' => 'Item Indicator',  'customer' => 'Customer','salesperson' => 'Sales Person');
	//,'salesperson' => 'Sales Person'
	$enumbyopt= array('month' => 'Month', 'branch' => 'Branch', 'profitcenter' => 'Profit Center', 'profitcentergroup' => 'Profit Center Group');

	$enumvalopt= array('label' => 'Caption', 'sales' => 'Sales', 'costofsales' => 'Cost of Sales', 'grossprofit' => 'Gross Profit', 'expenses' => 'Expenses', 'netprofit' => 'Net Profit', 'gpr' => 'GPR',  'npr' => 'NPR');

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
	$objGrid->addcolumn("label");
	$objGrid->addcolumn("0");
	
	$objGrid->columntitle("0","TOTAL (PHP)");
	
	if ($page->getitemstring("byopt")=="customer" || $page->getitemstring("byopt")=="custgroup" || $page->getitemstring("byopt")=="industry" || $page->getitemstring("byopt")=="item") {
		$objGrid->columnwidth("label",20);
	} else {
		$objGrid->columnwidth("label",11);
	}
	
	$objGrid->columntitle("label","");
	$objGrid->columndataentry("label","type","label");

	$objGrid->columnalignment("0","right");
	$objGrid->columndataentry("0","type","label");
	
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
	
	if (!isset($httpVars['df_year'])) {
		$page->setitem("year",date('Y'));
		$page->setitem("month","");
		$page->setitem("mnl",1);
		$page->setitem("ceb",1);
		$page->setitem("dvo",1);
		$page->setitem("branchcode","");
		if ($_SESSION["branchtype"]=="B") $page->setitem("branchcode",$_SESSION["branch"]);
		$page->setitem("byopt","month");
		$page->setitem("valopt","label");
		$page->setitem("firstview",1);
		
	}
	
	if ($page->getitemstring("month")=="") {
		$start = $page->getitemstring("year") ."-01-01";
		$end = $page->getitemstring("year") ."-12-31";
	} else {
		$start = $page->getitemstring("year") ."-".$page->getitemstring("month")."-01";
		$end = getmonthendDB($start);
	}

	if ($page->getitemstring("byopt")=="month") {
		$byoptExp = "docmonth";	
	} elseif ($page->getitemstring("byopt")=="branch") {
		$byoptExp = "branchname";	
	} elseif ($page->getitemstring("byopt")=="customer") {
		$byoptExp = "customername";
	} elseif ($page->getitemstring("byopt")=="salesperson") {
		$byoptExp = "salespersonname";
	} elseif ($page->getitemstring("byopt")=="profitcenter") {
		$byoptExp = "profitcenter";
	} elseif ($page->getitemstring("byopt")=="profitcentergroup") {
		$byoptExp = "profitcentergroup";
	}

	$mn = strtoupper(date('M',strtotime($start)));
	
	$objGrid->columnwidth("0",12);

	if ($page->getitemstring("valopt")=="label") {
		if ($page->getitemstring("byopt")=="month") {
			for ($i=1;$i<=12;$i++) {
				$objGrid->addcolumn($i);
				$objGrid->columntitle($i,strtoupper($enummonth[$i]));
				$objGrid->columnwidth($i,11);
				$objGrid->columnalignment($i,"right");
				$objGrid->columndataentry($i,"type","label");
			}
		} elseif ($page->getitemstring("byopt")=="branch") {
			$objrs->queryopen("select branchname from brancheslist order by branchname");
			while ($objrs->queryfetchrow()) {
				$objGrid->addcolumn($objrs->fields[0]);
				$objGrid->columntitle($objrs->fields[0],strtoupper(substr($objrs->fields[0],0,15)));
				$objGrid->columnwidth($objrs->fields[0],15);
				$objGrid->columnalignment($objrs->fields[0],"right");
				$objGrid->columndataentry($objrs->fields[0],"type","label");
			}
		} elseif ($page->getitemstring("byopt")=="profitcenter") {
			$objrs->queryopen("select profitcenter from profitcenters order by profitcenter");
			while ($objrs->queryfetchrow()) {
				$objGrid->addcolumn($objrs->fields[0]);
				$objGrid->columntitle($objrs->fields[0],strtoupper(substr($objrs->fields[0],0,15)));
				$objGrid->columnwidth($objrs->fields[0],15);
				$objGrid->columnalignment($objrs->fields[0],"right");
				$objGrid->columndataentry($objrs->fields[0],"type","label");
			}
		} elseif ($page->getitemstring("byopt")=="profitcentergroup") {
			$objrs->queryopen("select profitcentergroup from profitcentergroups order by profitcentergroup");
			while ($objrs->queryfetchrow()) {
				$objGrid->addcolumn($objrs->fields[0]);
				$objGrid->columntitle($objrs->fields[0],strtoupper(substr($objrs->fields[0],0,15)));
				$objGrid->columnwidth($objrs->fields[0],15);
				$objGrid->columnalignment($objrs->fields[0],"right");
				$objGrid->columndataentry($objrs->fields[0],"type","label");
			}
		}
	}
		
	$objrs = new recordset(null,$objConnection);
	$objrs->setdebug();
	$branchcodeExp = "";
	$branchcodeExp2 = "";
	
	$sortExp = $page->getitemstring("valopt");
	if ($page->getitemstring("valopt")=="label") {
		switch ($page->getitemstring("byopt")) {
			case "month": $sortExp = "docmonth"; break;
			case "branch": $sortExp = "branchname"; break;
			case "customer": $sortExp = "customername"; break;
			case "profitcenter": $sortExp = "profitcenter"; break;
			case "profitcentergroup": $sortExp = "profitcentergroup"; break;
		}
	}	
	
	if ($page->getitemstring("branchcode")!="") {
		$branchcodeExp = " and a.branch='".$page->getitemstring("branchcode")."'";
		$branchcodeExp2 = " and a.branchcode='".$page->getitemstring("branchcode")."'";
	}
	$dbname = $_SESSION["dbname"];
	$unionExp = "";
	$unionExp .= iif($unionExp!=""," UNION ALL ","");
	/*$unionExp .= "select br.branchname, sp.salespersonname, a.bpname as customername, ig.itemgroupname, a.docdate, b.dropship, b.quantity, b.linetotal-b.linetotaldisc as linetotal, b.linecost*-1 as linecost, 0 as expenses from $dbname.arinvoices a inner join $dbname.arinvoiceitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join $dbname.items c on c.itemcode=b.itemcode inner join branches br on br.branchcode=a.branch inner join $dbname.itemgroups ig on ig.itemgroup = c.itemgroup left join salespersons sp on sp.salesperson=a.salesperson where a.company='".$_SESSION["company"]."' $branchcodeExp and a.docstatus in ('O','C') and a.docdate between '$start' and '$end' 
union all 
select br.branchname, sp.salespersonname, a.bpname as customername, ig.itemgroupname, a.docdate, b.dropship, b.quantity *-1 as quantity, (if(a.basetype='',b.linetotal-b.linetotaldisc,b.linetotal-b.baselinetotaldisc))*-1 as linetotal, b.linecost, 0 as expenses from $dbname.arcreditmemos a inner join $dbname.arcreditmemoitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join $dbname.items c on c.itemcode=b.itemcode inner join branches br on br.branchcode=a.branch inner join $dbname.itemgroups ig on ig.itemgroup = c.itemgroup left join salespersons sp on sp.salesperson=a.salesperson where a.company='".$_SESSION["company"]."' $branchcodeExp and a.docstatus in ('O','C') and a.docdate between '$start' and '$end'
union all 
select br.branchname, '' as salespersonname, '' as customername, '' as itemgroupname, a.docdate, 1 as dropship, 0 as quantity, 0 as linetotal, 0 as linecost, (b.linetotal-b.linetotaldisc)*-1 as expenses from $dbname.apinvoices a inner join $dbname.apinvoiceitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and substr(b.glacctno,1,1) in ('4','5','6','7','8') inner join branches br on br.branchcode=a.branch where a.company='".$_SESSION["company"]."' $branchcodeExp and a.doctype='S' and a.docdate between '$start' and '$end'
union all
select br.branchname, '' as salespersonname, '' as customername, '' as itemgroupname, a.docdate, 1 as dropship, 0 as quantity, 0 as linetotal, 0 as linecost, (b.linetotal-b.linetotaldisc) as expenses from $dbname.apcreditmemos a inner join $dbname.apcreditmemoitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and substr(b.glacctno,1,1) in ('4','5','6','7','8') inner join branches br on br.branchcode=a.branch where a.company='".$_SESSION["company"]."' $branchcodeExp and a.doctype='S' and a.docdate between '$start' and '$end' 
union all 
select br.branchname, '' as salespersonname, '' as customername, '' as itemgroupname, a.docdate, 1 as dropship, 0 as quantity, 0 as linetotal, 0 as linecost, (b.debit-b.credit)*-1 as expenses from $dbname.journalvouchers a inner join $dbname.journalvoucheritems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.itemtype='A' and substr(b.itemno,1,1) in ('4','5','6','7','8') inner join branches br on br.branchcode=a.branch where a.company='".$_SESSION["company"]."' $branchcodeExp and a.docdate between '$start' and '$end' 
union all 
select br.branchname, '' as salespersonname, '' as customername, '' as itemgroupname, a.docdate, 1 as dropship, 0 as quantity, 0 as linetotal, 0 as linecost, b.amount*-1 as expenses from $dbname.payments a inner join $dbname.paymentaccounts b on b.company=a.company and b.branch=a.branchcode and b.docid=a.docid and substr(b.glacctno,1,1) in ('4','5','6','7','8') inner join branches br on br.branchcode=a.branchcode where a.company='".$_SESSION["company"]."' $branchcodeExp2 and a.docstat in ('D','O','C') and a.docdate between '$start' and '$end' 
union all 
select br.branchname, '' as salespersonname, '' as customername, '' as itemgroupname, a.docdate, 1 as dropship, 0 as quantity, 0 as linetotal, 0 as linecost, b.amount as expenses from $dbname.collections a inner join $dbname.collectionsaccounts b on b.company=a.company and b.branch=a.branchcode and b.docid=a.docid and substr(b.glacctno,1,1) in ('4','5','6','7','8') inner join branches br on br.branchcode=a.branchcode where a.company='".$_SESSION["company"]."' $branchcodeExp2 and a.docstat in ('D','O','C') and a.docdate between '$start' and '$end'";
*/
	$unionExp .= "select br.branchname, '' as salespersonname, '' as customername, b.profitcenter, pc.groupcode as profitcentergroup, a.docdate, 1 as dropship, 0 as quantity, if(substr(b.glacctno,1,1) in ('4'),b.glcredit-b.gldebit,0) as linetotal, if(substr(b.glacctno,1,1) in ('5'),b.glcredit-b.gldebit,0)  as linecost, if(substr(b.glacctno,1,1) in ('6','7','8'),b.glcredit-b.gldebit,0)  as expenses from $dbname.journalentries a inner join $dbname.journalentryitems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and substr(b.glacctno,1,1) in ('4','5','6','7','8') inner join $dbname.branches br on br.branchcode=a.branch left join $dbname.profitcenters pc on pc.profitcenter=b.profitcenter where a.company='".$_SESSION["company"]."' $branchcodeExp and a.docdate between '$start' and '$end'";

	//var_dump($unionExp);
	$objrs->queryopen("select branchname, salespersonname, customername, profitcenter, profitcentergroup, month(docdate) as docmonth,
  sum(linetotal) as `sales`,
  sum(linecost) as `costofsales`,
  sum(expenses) as `expenses`,
  sum(linetotal) + sum(linecost) as grossprofit, 
  sum(linetotal) + sum(linecost) + sum(expenses) as netprofit,
  ((sum(linetotal) + sum(linecost)) / sum(linetotal)) * 100 as gpr, 
  ((sum(linetotal) + sum(linecost) + sum(expenses)) / sum(linetotal)) * 100 as npr
  from ( $unionExp ) as x group by $byoptExp order by $sortExp desc ");

  //var_dump($objrs->sqls);
	$objGrid->addrow();
	$objGrid->addrow();
	$objGrid->addrow();
	$objGrid->addrow();
	$objGrid->addrow();
	$objGrid->addrow();
	$objGrid->addrow();
	
	$objGrid->setitem(1,"label","Sales");
	$objGrid->setitem(2,"label","Cost of Sales");
	$objGrid->setitem(3,"label","Gross Profit");
	$objGrid->setitem(4,"label","Expenses");
	$objGrid->setitem(5,"label","Net Profit");
	$objGrid->setitem(6,"label","GPR");
	$objGrid->setitem(7,"label","NPR");

	$page->paging_recordcount($objrs->recordcount());
	while ($objrs->queryfetchrow("NAME")) {
		
		$sufix = "";
		//if ($page->getitemstring("valopt")=="qty") $sufix = "_qty";
		if ($page->getitemstring("byopt")=="month") {
			if ($page->getitemstring("valopt")!="label") {
				$objGrid->addcolumn($objrs->fields["docmonth"]);
				$objGrid->columntitle($objrs->fields["docmonth"],strtoupper($enummonth[$objrs->fields["docmonth"]]));
				$objGrid->columnwidth($objrs->fields["docmonth"],12);
				$objGrid->columnalignment($objrs->fields["docmonth"],"right");
				$objGrid->columndataentry($objrs->fields["docmonth"],"type","label");
			}	
			$objGrid->setitem(1,$objrs->fields["docmonth"],formatNumericAmount($objrs->fields["sales"]));
			$objGrid->setitem(2,$objrs->fields["docmonth"],formatNumericAmount($objrs->fields["costofsales"]));
			$objGrid->setitem(3,$objrs->fields["docmonth"],formatNumericAmount($objrs->fields["grossprofit"]));
			$objGrid->setitem(4,$objrs->fields["docmonth"],formatNumericAmount($objrs->fields["expenses"]));
			$objGrid->setitem(5,$objrs->fields["docmonth"],formatNumericAmount($objrs->fields["netprofit"]));
			$objGrid->setitem(6,$objrs->fields["docmonth"],formatNumericPercent(($objrs->fields["grossprofit"] / $objrs->fields["sales"])*100));
			$objGrid->setitem(7,$objrs->fields["docmonth"],formatNumericPercent(($objrs->fields["netprofit"] / $objrs->fields["sales"])*100));
		} elseif ($page->getitemstring("byopt")=="branch") {
			$objGrid->addcolumn($objrs->fields["branchname"]);
			$objGrid->columntitle($objrs->fields["branchname"],strtoupper(substr($objrs->fields["branchname"],0,15)));
			$objGrid->columnwidth($objrs->fields["branchname"],15);
			$objGrid->columnalignment($objrs->fields["branchname"],"right");
			$objGrid->columndataentry($objrs->fields["branchname"],"type","label");

			$objGrid->setitem(1,$objrs->fields["branchname"],formatNumericAmount($objrs->fields["sales"]));
			$objGrid->setitem(2,$objrs->fields["branchname"],formatNumericAmount($objrs->fields["costofsales"]));
			$objGrid->setitem(3,$objrs->fields["branchname"],formatNumericAmount($objrs->fields["grossprofit"]));
			$objGrid->setitem(4,$objrs->fields["branchname"],formatNumericAmount($objrs->fields["expenses"]));
			$objGrid->setitem(5,$objrs->fields["branchname"],formatNumericAmount($objrs->fields["netprofit"]));
			$objGrid->setitem(6,$objrs->fields["branchname"],formatNumericPercent(($objrs->fields["grossprofit"] / $objrs->fields["sales"])*100));
			$objGrid->setitem(7,$objrs->fields["branchname"],formatNumericPercent(($objrs->fields["netprofit"] / $objrs->fields["sales"])*100));
		} elseif ($page->getitemstring("byopt")=="salesperson") {
			$objGrid->addcolumn($objrs->fields["salespersonname"]);
			$objGrid->columntitle($objrs->fields["salespersonname"],strtoupper(substr($objrs->fields["salespersonname"],0,15)));
			$objGrid->columnwidth($objrs->fields["salespersonname"],15);
			$objGrid->columnalignment($objrs->fields["salespersonname"],"right");
			$objGrid->columndataentry($objrs->fields["salespersonname"],"type","label");

			$objGrid->setitem(1,$objrs->fields["salespersonname"],formatNumericAmount($objrs->fields["sales"]));
			$objGrid->setitem(2,$objrs->fields["salespersonname"],formatNumericAmount($objrs->fields["costofsales"]));
			$objGrid->setitem(3,$objrs->fields["salespersonname"],formatNumericAmount($objrs->fields["grossprofit"]));
			$objGrid->setitem(4,$objrs->fields["salespersonname"],formatNumericAmount($objrs->fields["expenses"]));
			$objGrid->setitem(5,$objrs->fields["salespersonname"],formatNumericAmount($objrs->fields["netprofit"]));
			$objGrid->setitem(6,$objrs->fields["salespersonname"],formatNumericPercent(($objrs->fields["grossprofit"] / $objrs->fields["sales"])*100));
			$objGrid->setitem(7,$objrs->fields["salespersonname"],formatNumericPercent(($objrs->fields["netprofit"] / $objrs->fields["sales"])*100));
		} elseif ($page->getitemstring("byopt")=="customer") {
			$objGrid->addcolumn($objrs->fields["customername"]);
			$objGrid->columntitle($objrs->fields["customername"],strtoupper(substr($objrs->fields["customername"],0,15)));
			$objGrid->columnwidth($objrs->fields["customername"],15);
			$objGrid->columnalignment($objrs->fields["customername"],"right");
			$objGrid->columndataentry($objrs->fields["customername"],"type","label");

			$objGrid->setitem(1,$objrs->fields["customername"],formatNumericAmount($objrs->fields["sales"]));
			$objGrid->setitem(2,$objrs->fields["customername"],formatNumericAmount($objrs->fields["costofsales"]));
			$objGrid->setitem(3,$objrs->fields["customername"],formatNumericAmount($objrs->fields["grossprofit"]));
			$objGrid->setitem(4,$objrs->fields["customername"],formatNumericAmount($objrs->fields["expenses"]));
			$objGrid->setitem(5,$objrs->fields["customername"],formatNumericAmount($objrs->fields["netprofit"]));
			$objGrid->setitem(6,$objrs->fields["customername"],formatNumericPercent(($objrs->fields["grossprofit"] / $objrs->fields["sales"])*100));
			$objGrid->setitem(7,$objrs->fields["customername"],formatNumericPercent(($objrs->fields["netprofit"] / $objrs->fields["sales"])*100));
		} elseif ($page->getitemstring("byopt")=="profitcenter") {
			$objGrid->addcolumn($objrs->fields["profitcenter"]);
			$objGrid->columntitle($objrs->fields["profitcenter"],strtoupper(substr($objrs->fields["profitcenter"],0,15)));
			$objGrid->columnwidth($objrs->fields["profitcenter"],15);
			$objGrid->columnalignment($objrs->fields["profitcenter"],"right");
			$objGrid->columndataentry($objrs->fields["profitcenter"],"type","label");

			$objGrid->setitem(1,$objrs->fields["profitcenter"],formatNumericAmount($objrs->fields["sales"]));
			$objGrid->setitem(2,$objrs->fields["profitcenter"],formatNumericAmount($objrs->fields["costofsales"]));
			$objGrid->setitem(3,$objrs->fields["profitcenter"],formatNumericAmount($objrs->fields["grossprofit"]));
			$objGrid->setitem(4,$objrs->fields["profitcenter"],formatNumericAmount($objrs->fields["expenses"]));
			$objGrid->setitem(5,$objrs->fields["profitcenter"],formatNumericAmount($objrs->fields["netprofit"]));
			$objGrid->setitem(6,$objrs->fields["profitcenter"],formatNumericPercent(($objrs->fields["grossprofit"] / $objrs->fields["sales"])*100));
			$objGrid->setitem(7,$objrs->fields["profitcenter"],formatNumericPercent(($objrs->fields["netprofit"] / $objrs->fields["sales"])*100));
		} elseif ($page->getitemstring("byopt")=="profitcentergroup") {
			$objGrid->addcolumn($objrs->fields["profitcentergroup"]);
			$objGrid->columntitle($objrs->fields["profitcentergroup"],strtoupper(substr($objrs->fields["profitcentergroup"],0,15)));
			$objGrid->columnwidth($objrs->fields["profitcentergroup"],15);
			$objGrid->columnalignment($objrs->fields["profitcentergroup"],"right");
			$objGrid->columndataentry($objrs->fields["profitcentergroup"],"type","label");

			$objGrid->setitem(1,$objrs->fields["profitcentergroup"],formatNumericAmount($objrs->fields["sales"]));
			$objGrid->setitem(2,$objrs->fields["profitcentergroup"],formatNumericAmount($objrs->fields["costofsales"]));
			$objGrid->setitem(3,$objrs->fields["profitcentergroup"],formatNumericAmount($objrs->fields["grossprofit"]));
			$objGrid->setitem(4,$objrs->fields["profitcentergroup"],formatNumericAmount($objrs->fields["expenses"]));
			$objGrid->setitem(5,$objrs->fields["profitcentergroup"],formatNumericAmount($objrs->fields["netprofit"]));
			$objGrid->setitem(6,$objrs->fields["profitcentergroup"],formatNumericPercent(($objrs->fields["grossprofit"] / $objrs->fields["sales"])*100));
			$objGrid->setitem(7,$objrs->fields["profitcentergroup"],formatNumericPercent(($objrs->fields["netprofit"] / $objrs->fields["sales"])*100));
		}

		$totals[0] += $objrs->fields["sales"];
		$totals[1] += $objrs->fields["costofsales"];
		$totals[2] += $objrs->fields["grossprofit"];
		$totals[3] += $objrs->fields["expenses"];
		$totals[4] += $objrs->fields["netprofit"];
		
	}	
	$objGrid->setitem(1,"0",formatNumericAmount($totals[0]));
	$objGrid->setitem(2,"0",formatNumericAmount($totals[1]));
	$objGrid->setitem(3,"0",formatNumericAmount($totals[2]));
	$objGrid->setitem(4,"0",formatNumericAmount($totals[3]));
	$objGrid->setitem(5,"0",formatNumericAmount($totals[4]));
	$objGrid->setitem(6,"0",formatNumericPercent(($totals[2] / $totals[0])*100));
	$objGrid->setitem(7,"0",formatNumericPercent(($totals[4] / $totals[0])*100));

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
			parent.barFrame.location = "../FusionCharts/MySamples/his_pnl_graph.php?year=" + getInput("year") + "&month=" + getInput("month")  + "&dbname=" + getGlobal("dbname") + "&companycode=" + getGlobal("company") + "&branchcode=" + getInput("branchcode") + "&byopt=" + getInput("byopt") + "&valopt=" + getInput("valopt") ;
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
				clearTable("T1");
				formPageReset(); break;
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
		parent.barFrame.location = "../FusionCharts/MySamples/his_pnl_graph.php?year=" + getInput("year") + "&month=" + getInput("month")  + "&dbname=" + getGlobal("dbname") + "&companycode=" + getGlobal("company") + "&branchcode=" + getInput("branchcode") + "&byopt=" + getInput("byopt") + "&valopt=" + getInput("valopt") ;
		//parent.pieFrame.location = "../FusionCharts/MySamples/fci_sales_ytdbyitemgroups2_graph.php?year=" + getInput("year") + "&month=" + getInput("month") + "&mnl=" + getInput("mnl") + "&dvo=" + getInput("dvo") + "&ceb=" + getInput("ceb");
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
	  <td width="60" ><label <?php genCaptionHtml($schema["year"],"") ?>>Year</label></td>
	  <td  align=left>&nbsp;<select <?php genSelectHtml($schema["year"],array("loadenumyear","","")) ?> ></select>&nbsp;<select <?php genSelectHtml($schema["month"],
	  array("loadenummonth","",":[All]")) ?> ></select>&nbsp;&nbsp;<select <?php genSelectHtml($schema["branchcode"],array("loadbrancheslist","",":[All]"),null,null,null,"width:168px") ?> ></select>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Go</a></td>
      <td width="380"  align=left><label <?php genCaptionHtml($schema["byopt"],"") ?>>Display By</label>&nbsp;<select <?php genSelectHtml($schema["byopt"],array("loadenumbyopt","","")) ?> ></select><label <?php genCaptionHtml($schema["valopt"],"") ?>>Sort By</label>&nbsp;<select <?php genSelectHtml($schema["valopt"],array("loadenumvalopt","","")) ?> ></select></td>
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
