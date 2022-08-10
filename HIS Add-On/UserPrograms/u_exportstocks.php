<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_exportstocks";
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/journalvouchers.php");
	include_once("./classes/journalvoucheritems.php");
	include_once("./sls/enumyear.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./utils/users.php");
	include_once("./utils/trxlog.php");
	include_once("./utils/branches.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/masterdatalinesschema.php");
	require_once ("../common/classes/excel.php");	
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select code,name from u_hissections");
	while ($objRs->queryfetchrow("NAME")) {
		$enumsections[$objRs->fields["code"]] = $objRs->fields["name"];
	}	
	
	function loadenumsections($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumsections;
		reset($enumsections);
		while (list($key, $val) = each($enumsections)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}
	
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
			case "u_view":
			case "u_print":
			
				$objRs = new recordset(null,$objConnection);
				
				$objExcel = new Excel;
				$sheet="";
				
				$itemclassdata = array();				
				$itemclassdata2 = array();				
				$department = $page->getitemstring("department");
				$date = $page->getitemdate("date");
				$objRs->queryopen("select companyname from companies where companycode='".$_SESSION["company"]."'");
				while ($objRs->queryfetchrow("NAME")) {
					$companyname = $objRs->fields["companyname"];
				}
				$objRs->queryopen("select name from u_hissections where code='$department'");
				while ($objRs->queryfetchrow("NAME")) {
					$departmentname = $objRs->fields["name"];
				}

				$objRs->queryopen("call sp_stock_status_all_whse_w_cost('".$_SESSION["company"]."','".$_SESSION["branch"]."','$department','','','','','','','$date','withbegin')");
				while ($objRs->queryfetchrow("NAME")) {
					//var_dump($objRs->fields["ITEMDESC"]);
					if ($objRs->fields["ITEMCLASS"]=="") $objRs->fields["ITEMCLASS"]="UNKNOWN";
					if (!isset($itemclassdata[$objRs->fields["ITEMCLASS"]])) $itemclassdata[$objRs->fields["ITEMCLASS"]] = array();
					if (!isset($itemclassdata2[$objRs->fields["ITEMCLASS"]])) $itemclassdata2[$objRs->fields["ITEMCLASS"]] = array();
					$data = array();
					$data["itemcode"] = $objRs->fields["ITEMCODE"];
					$data["itemdesc"] = $objRs->fields["ITEMDESC"];
					$data["uom"] = $objRs->fields["UOM"];
					$data["beginqty"] = $objRs->fields["BEGINQTY"];
					$data["inqty"] = $objRs->fields["INQTY"];
					$data["outqty"] = $objRs->fields["OUTQTY"]*-1;
					$data["endqty"] = $objRs->fields["ENDQTY"];
					$data["endcost"] = $objRs->fields["ENDCOST"];
					array_push($itemclassdata[$objRs->fields["ITEMCLASS"]],$data);
				}
				//$page->console->insertVar($itemclassdata);
				$objExcel->openxl("","","");
				foreach ($itemclassdata as $sheet => $classdata) {
					$objExcel->addsheet($sheet);
					$col=1;
					$row=4;
					$data = array();
					array_push($data,$sheet);
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+12,$row)  ,$data);
					setCellFontWeight($objExcel,$sheet,$row,1,$row,1,true);
					$row+=2;
					$data = array();
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"");
					array_push($data,"STOCK CARD");
					array_push($data,"");
					array_push($data,"ACTUAL");
					$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+12,$row)  ,$data);
					setMerge($objExcel,$sheet,$row,$col+7,$row,$col+8,true);
					setMerge($objExcel,$sheet,$row,$col+9,$row,$col+10,true);
					setCellFontWeight($objExcel,$sheet,$row,$col+7,$row,$col+9,true);
					setCellBGColor($objExcel,$sheet,$row,$col+7,$row,$col+10,6);
					setCellWidth($objExcel,$sheet,$col,$col,6);
					setCellWidth($objExcel,$sheet,$col+1,$col+1,28);
					setCellWidth($objExcel,$sheet,$col+2,$col+2,5);
					setCellWidth($objExcel,$sheet,$col+4,$col+4,7);
					setCellWidth($objExcel,$sheet,$col+5,$col+5,7);
					setCellWidth($objExcel,$sheet,$col+6,$col+6,7);
					setCellWidth($objExcel,$sheet,$col+7,$col+7,7);
					setCellWidth($objExcel,$sheet,$col+8,$col+8,11);
					setCellWidth($objExcel,$sheet,$col+9,$col+9,7);
					setCellWidth($objExcel,$sheet,$col+10,$col+10,11);
					setCellFormat($objExcel,$sheet,0,$col+3,0,$col+3,"#,##0.00_);(#,##0.00)");
					setCellFormat($objExcel,$sheet,0,$col+7,0,$col+7,"#,##0_);(#,##0)");
					setCellFormat($objExcel,$sheet,0,$col+8,0,$col+8,"#,##0.00_);(#,##0.00)");
					setCellFormat($objExcel,$sheet,0,$col+9,0,$col+9,"#,##0_);(#,##0)");
					setCellFormat($objExcel,$sheet,0,$col+10,0,$col+10,"#,##0.00_);(#,##0.00)");
					$row++;
					$data = array();
					array_push($data,"CODE");
					array_push($data,"ITEM DESCRIPTION");
					array_push($data,"UNIT");
					array_push($data,"COST");
					array_push($data,"BEG QTY");
					array_push($data,"QTY IN");
					array_push($data,"QTY OUT");
					array_push($data,"QTY");
					array_push($data,"AMOUNT");
					array_push($data,"QTY");
					array_push($data,"AMOUNT");
					array_push($data,"VARIANCE");
					$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+12,$row)  ,$data);
					setCellFontWeight($objExcel,$sheet,$row,$col,$row,$col+11,true);
					$row++;
											
					foreach ($classdata as $itemdata) {
						$data = array();
						array_push($data,$itemdata["itemcode"]);
						array_push($data,$itemdata["itemdesc"]);
						array_push($data,$itemdata["uom"]);
						array_push($data,$itemdata["endcost"]);
						array_push($data,$itemdata["beginqty"]);
						array_push($data,$itemdata["inqty"]);
						array_push($data,$itemdata["outqty"]);
						//array_push($data,$itemdata["endqty"]);
						array_push($data,"=E".$row."+F".$row."-G".$row);
						array_push($data,"=D".$row."*H".$row);
						array_push($data,0);
						array_push($data,"=D".$row."*J".$row);
						array_push($data,"=H".$row."-J".$row);
						$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+12,$row)  ,$data);
						$row++;		
					}
					$col+=7;
					$data = array();
					array_push($data,"=sum(H8:H".($row-1).")");
					array_push($data,"=sum(I8:I".($row-1).")");
					array_push($data,"=sum(J8:J".($row-1).")");
					array_push($data,"=sum(K8:K".($row-1).")");
					array_push($data,"=sum(L8:L".($row-1).")");
					$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+4,$row)  ,$data);
					$itemclassdata2[$sheet]["row"]=$row;
				}
				$sheet="Summary";
				$col=1;
				$row=5;
				$objExcel->addsheet($sheet);
				setMerge($objExcel,$sheet,1,$col,1,$col+3,true);
				setMerge($objExcel,$sheet,2,$col,2,$col+3,true);
				setMerge($objExcel,$sheet,3,$col,3,$col+3,true);
				$objExcel->writerange($sheet,getCellAddress($col,1).":".getCellAddress($col,1)  ,array(strtoupper($companyname)));
				$objExcel->writerange($sheet,getCellAddress($col,2).":".getCellAddress($col,2)  ,array(strtoupper($departmentname)));
				$objExcel->writerange($sheet,getCellAddress($col,3).":".getCellAddress($col,3)  ,array("FOR THE MONTH ENDED ". strtoupper(date('F d, Y',strtotime($date)))));
				
				setCellWidth($objExcel,$sheet,$col,$col,35);
				setCellWidth($objExcel,$sheet,$col+1,$col+1,25);
				setCellWidth($objExcel,$sheet,$col+2,$col+2,32);
				setCellWidth($objExcel,$sheet,$col+3,$col+3,25);
				
				$data = array();
				array_push($data,"CATEGORY");
				array_push($data,"STOCK CARD");
				array_push($data,"ACTUAL COUNT");
				array_push($data,"VARIANCE");
				$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+3,$row)  ,$data);
				setCellFontWeight($objExcel,$sheet,$row,$col,$row,$col+3,true);

				$row++;
				foreach ($itemclassdata2 as $key => $classdata) {
					$data = array();
					array_push($data,$key);
					array_push($data,"='".$key."'!I".$classdata["row"]);
					array_push($data,"='".$key."'!K".$classdata["row"]);
					array_push($data,"=C".$row."-B".$row);
					$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+5,$row)  ,$data);
					$row++;					
				}
				$data = array();
				array_push($data,"TOTAL INVENTORY");
				array_push($data,"=sum(B6:B".($row-1).")");
				array_push($data,"=sum(C6:C".($row-1).")");
				array_push($data,"");
				$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+4,$row)  ,$data);
				setCellFontWeight($objExcel,$sheet,$row,$col,$row,$col,true);
				
				$data = array();
				array_push($data,"TOTAL VARIANCE");
				array_push($data,"");
				array_push($data,"");
				array_push($data,"=sum(D6:D".($row-1).")");
				$objExcel->writerange($sheet,getCellAddress($col,$row+1).":".getCellAddress($col+4,$row+1)  ,$data);
				setCellFontWeight($objExcel,$sheet,$row+1,$col,$row+1,$col,true);
				
				$row+=4;
				$data = array();
				array_push($data,"PREPARED BY: RODERICK TIMKANG");
				array_push($data,"");
				array_push($data,"");
				array_push($data,"");
				$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+4,$row)  ,$data);
				setCellFontWeight($objExcel,$sheet,$row,$col,$row,$col,true);
				setCellBorderTop($objExcel,$sheet,$row,$col,$row,$col,1,3);

				$row+=2;
				$data = array();
				array_push($data,"");
				array_push($data,"");
				array_push($data,"CHECKED BY: JOYLYN F. CABALLERO");
				array_push($data,"");
				$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+4,$row)  ,$data);
				setCellFontWeight($objExcel,$sheet,$row,$col+2,$row,$col+2,true);
				setCellBorderTop($objExcel,$sheet,$row,$col+2,$row,$col+2,1,3);

				$row+=1;
				$data = array();
				array_push($data,"RECEIVED BY: GRACE V. PATENTE");
				array_push($data,"");
				array_push($data,"");
				array_push($data,"");
				$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+4,$row)  ,$data);
				setCellFontWeight($objExcel,$sheet,$row,$col,$row,$col,true);
				setCellBorderTop($objExcel,$sheet,$row,$col,$row,$col,1,3);

				$row+=2;
				$data = array();
				array_push($data,"");
				array_push($data,"");
				array_push($data,"NOTED BY: JOHN YRICK ERA, CPA");
				array_push($data,"");
				$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+4,$row)  ,$data);
				setCellFontWeight($objExcel,$sheet,$row,$col+2,$row,$col+2,true);
				setCellBorderTop($objExcel,$sheet,$row,$col+2,$row,$col+2,1,3);

				$row+=1;
				$data = array();
				array_push($data,"AUDITED BY: JAN MICHAEL CARBONNEL");
				array_push($data,"");
				array_push($data,"");
				array_push($data,"");
				$objExcel->writerange($sheet,getCellAddress($col,$row).":".getCellAddress($col+4,$row)  ,$data);
				setCellFontWeight($objExcel,$sheet,$row,$col,$row,$col,true);
				setCellBorderTop($objExcel,$sheet,$row,$col,$row,$col,1,3);
				
				/*
				if ($empid!="" && $date!="") {
					$objRs->queryopen("select a.name as u_empname, a.u_date, b.name as u_compname, b.u_address as u_compaddress, b.u_date as u_compdate from u_employees a left join u_companies b on b.code=a.u_company where (a.code='$empid' or a.u_date='$date')");
				} elseif ($empid!="") {	
					$objRs->queryopen("select a.name as u_empname, a.u_date, b.name as u_compname, b.u_address as u_compaddress, b.u_date as u_compdate from u_employees a left join u_companies b on b.code=a.u_company where a.code='$empid'");
				} else {
					$objRs->queryopen("select a.name as u_empname, a.u_date, b.name as u_compname, b.u_address as u_compaddress, b.u_date as u_compdate from u_employees a left join u_companies b on b.code=a.u_company where a.u_date='$date'");
				}
				if ($objRs->queryfetchrow("NAME")) {
					$empname = strtoupper($objRs->fields["u_empname"]);
					$tin = $objRs->fields["u_date"];
					$compname = strtoupper($objRs->fields["u_compname"]);
					$compaddress = strtoupper($objRs->fields["u_compaddress"]);
					$comptin = $objRs->fields["u_compdate"];
				}
				
				$department = $page->getitemnumber("department");
				$qtrto = $page->getitemnumber("qtrto");
				$year = $page->getitemnumber("year");
				
				//$objRs->setdebug();
				$sourcepath = realpath("../Addons/GPS/FTSI Add-On/UserPrograms");
				$objExcel->openxl("2307",$sourcepath."\\Templates\\",$sheet);
				//$objRs->setdebug();
				for($qtr=$department;$qtr<=$qtrto;$qtr++) {
					switch ($qtr) {
						case 1: $sheet = "2307 1"; $date1 = $year ."-01-01"; $date2 = $year ."-02-01"; $date3 = $year ."-03-01"; $date4 = $year ."-03-31";	break;
						case 2: $sheet = "2307 2"; $date1 = $year ."-04-01"; $date2 = $year ."-05-01"; $date3 = $year ."-06-01"; $date4 = $year ."-06-30"; break;
						case 3: $sheet = "2307 3"; $date1 = $year ."-07-01"; $date2 = $year ."-08-01"; $date3 = $year ."-09-01"; $date4 = $year ."-09-30"; break;
						case 4: $sheet = "2307 4"; $date1 = $year ."-10-01"; $date2 = $year ."-11-01"; $date3 = $year ."-12-01"; $date4 = $year ."-12-31"; break;
					}
					$m1 =0;
					$m2 =0;
					$m3 =0;
					$mt = $m1+$m2+$m3;
					$wtaxperc=15/100;
					$wtaxamt = 0;
			
					try {
						
						$sheets = $objExcel->ex->Application->Worksheets($sheet);
						$sheets->activate;
						
						$objRs->queryopen("select u_taxgroup, u_taxgroupname, sum(u_gross1) as u_gross1, sum(u_tax1) as u_tax1, sum(u_gross2) as u_gross2, sum(u_tax2) as u_tax2, sum(u_gross3) as u_gross3, sum(u_tax3) as u_tax3 from (select b.u_taxgroup, d.name as u_taxgroupname, sum(b.u_gross) as u_gross1, sum(b.u_tax) as u_tax1, 0 as u_gross2, 0 as u_tax2, 0 as u_gross3, 0 as u_tax3 from u_payrolls a, u_payrollitems b, u_employees c, u_taxgroups d where d.code=b.u_taxgroup and c.code=b.u_empid and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and (c.code='$empid' or c.u_date='$tin') and a.u_docdate >='$date1' and a.u_docdate<'$date2' and a.docstatus not in ('D') group by b.u_taxgroup union all 
										  select b.u_taxgroup, d.name as u_taxgroupname, 0 as u_gross1, 0 as u_tax1, sum(b.u_gross) as u_gross2, sum(b.u_tax) as u_tax2, 0 as u_gross3, 0 as u_tax3 from u_payrolls a, u_payrollitems b, u_employees c, u_taxgroups d where d.code=b.u_taxgroup and c.code=b.u_empid and b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and (c.code='$empid' or c.u_date='$tin') and a.u_docdate >='$date2' and a.u_docdate<'$date3' and a.docstatus not in ('D') group by b.u_taxgroup union all 
										  select b.u_taxgroup, d.name as u_taxgroupname, 0 as u_gross1, 0 as u_tax1, 0 as u_gross2, 0 as u_tax2, sum(b.u_gross) as u_gross3, sum(b.u_tax) as u_tax3 from u_payrolls a, u_payrollitems b, u_employees c, u_taxgroups d where d.code=b.u_taxgroup and c.code=b.u_empid and  b.company=a.company and b.branch=a.branch and b.docid=a.docid and a.company='$company' and a.branch='$branch' and (c.code='$empid' or c.u_date='$tin') and a.u_docdate >='$date3' and a.u_docdate<='$date4' and a.docstatus not in ('D')) as x group by u_taxgroup");
						//var_dump($objRs->sqls);				  
						$row=33;				  
						while ($objRs->queryfetchrow("NAME")) {
							$u_gross1 = 0;
							$u_gross2 = 0;
							$u_gross3 = 0;
							if ($objRs->fields["u_tax1"]!=0) $u_gross1 = $objRs->fields["u_gross1"];
							if ($objRs->fields["u_tax2"]!=0) $u_gross2 = $objRs->fields["u_gross2"];
							if ($objRs->fields["u_tax3"]!=0) $u_gross3 = $objRs->fields["u_gross3"];
							$objExcel->writerange($sheet,"A".$row.":A".$row ,array($objRs->fields["u_taxgroupname"]));
							$objExcel->writerange($sheet,"M".$row.":M".$row ,array($objRs->fields["u_taxgroup"]));
							$objExcel->writerange($sheet,"Q".$row.":Q".$row ,array($u_gross1));
							$objExcel->writerange($sheet,"V".$row.":V".$row ,array($u_gross2));
							$objExcel->writerange($sheet,"AA".$row.":AA".$row ,array($u_gross3));
							$objExcel->writerange($sheet,"AF".$row.":AF".$row ,array($u_gross1+$u_gross2+$u_gross3));
							$objExcel->writerange($sheet,"AK".$row.":AK".$row ,array($objRs->fields["u_tax1"]+$objRs->fields["u_tax2"]+$objRs->fields["u_tax3"]));
							$row++;
						}	

						$rec = $sheets->shapes("Rectangle 540");
						$rec->textframe->characters->text = $empname;
					
						$rec = $sheets->shapes("Rectangle 27");
						$rec->textframe->characters->text = substr($tin,0,3);
						$rec = $sheets->shapes("Rectangle 25");
						$rec->textframe->characters->text = substr($tin,4,3);
						$rec = $sheets->shapes("Rectangle 24");
						$rec->textframe->characters->text = substr($tin,8,3);
					
						$rec = $sheets->shapes("Rectangle 138");
						$rec->textframe->characters->text = substr($date1,5,2);
						$rec = $sheets->shapes("Rectangle 543");
						$rec->textframe->characters->text = substr($date1,8,2);
						$rec = $sheets->shapes("Rectangle 19");
						$rec->textframe->characters->text = substr($date1,2,2);
					
						$rec = $sheets->shapes("Rectangle 292");
						$rec->textframe->characters->text = substr($date4,5,2);
						$rec = $sheets->shapes("Rectangle 541");
						$rec->textframe->characters->text = substr($date4,8,2);
						$rec = $sheets->shapes("Rectangle 287");
						$rec->textframe->characters->text = substr($date4,2,2);
					
						$rec = $sheets->shapes("Rectangle 496");
						$rec->textframe->characters->text = substr($comptin,0,3);
						$rec = $sheets->shapes("Rectangle 494");
						$rec->textframe->characters->text = substr($comptin,4,3);
						$rec = $sheets->shapes("Rectangle 493");
						$rec->textframe->characters->text = substr($comptin,8,3);
					
						$rec = $sheets->shapes("Rectangle 526");
						$rec->textframe->characters->text = $compname;
						
						
						$rec = $sheets->shapes("Rectangle 528");
						$rec->textframe->characters->text = substr($compaddress,0,66);
						$uploadpath = realpath("../Addons/GPS/FTSI Add-On/UserPrograms/");
						$uploadpath .= "2307\\";
						mkdirr($uploadpath);
						$objExcel->saveas($uploadpath .session_id() ,"","xls") ; 
										
						//$objExcel->saveas("d:\\rey2307" ,"","xls") ; 
						
					} catch (Exception $e) {
						var_dump($e->getMessage());
					}				
				}	
			*/
				$uploadpath = realpath("../Addons/GPS/HIS Add-On/UserPrograms/");
				$uploadpath .= "Export\\";
				mkdirr($uploadpath);
				$objExcel->saveas($uploadpath .session_id() ,"","xls") ; 
					
				$objExcel->closeXL();
				unset($objExcel);
			
				$_SESSION["dbmodified"] = 1;
				break;
		}
		
		return $actionReturn;
	}
	
	$filter="";
	
	$schema["department"]=createSchema("department","Section","varchar",0,"any");
	$schema["date"]=createSchemaDate("date","As of");

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

	function onElementChange(element,column,table,row) {
		var result,result2,hiddenhtml,rowHtml="",rc,params="",grossamount=0,vat=0,interbranch=0;
		switch (column) {
			case "department":
				setInput("qtrto",getInput("department"));
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
		if(action=="u_view") {
			if(isInputEmpty("department")) return false;
			if(isInputEmpty("date")) return false;
		}
		
		return true;
	}	
	
	function onFormSubmitReturn(action,success,error) {
		if (success) {
			iframeDownloader.location = "../Addons/GPS/HIS Add-On/UserPrograms/Export/"+getGlobal("sessionid")+".xls";
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
			
			
			 
			<tr>
			  <td width="168"><label <?php genCaptionHtml($schema["department"],"") ?> >Section</label></td>
				<td>&nbsp;<select <?php genSelectHtml($schema["department"],array("loadenumsections","",":[Select]")) ?> ></select></td>
				<td width="168"><!--DWLayoutEmptyCell-->&nbsp;</td>
			  <td width="208" align=right><!--DWLayoutEmptyCell-->&nbsp;</td>
			  <td></td>
			  </tr>
			
			 <td><label <?php genCaptionHtml($schema["date"],"") ?> >As of</label></td>
				<td>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["date"]) ?> /></td>
				<td>&nbsp;</td>
			  <td align=right>&nbsp;</td>
			  <td></td>
			  </tr>
			
			<tr class="fillerRow5px"><td colspan="5">&nbsp;</td></tr>
			<tr>
			  <td></td>
			  <td>&nbsp;<a class="button" href="" onClick="<?php echo @$toolbarframe ?>formSubmit('u_view');return false;">Export</a></td>
			  <td><!--DWLayoutEmptyCell-->&nbsp;</td>
			  <td align=right><!--DWLayoutEmptyCell-->&nbsp;</td>
			  <td></td>
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
