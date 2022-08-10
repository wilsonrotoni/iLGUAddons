<?php
	//$progid = "u_rplist";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/masterdataschema.php");
	include_once("./classes/masterdatalinesschema.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
//	include_once("./inc/formaccess.php");

	$actionReturn = true;

	$objRs = new recordset(null,$objConnection);
	
	$obju_Lands = new masterdataschema(null,$objConnection,"u_rplands");
	$obju_LandSubclasses = new masterdatalinesschema(null,$objConnection,"u_rplandsubclasses");

	$obju_LandClasses = new masterdataschema(null,$objConnection,"u_rplandclasses");
	
	$obju_ActUses = new masterdataschema(null,$objConnection,"u_rpactuses");
	$obju_Machineries = new masterdataschema(null,$objConnection,"u_rpmachineries");
	$obju_Improvements = new masterdataschema(null,$objConnection,"u_rpimprovements");
	$obju_Improvementfmvs = new masterdatalinesschema(null,$objConnection,"u_rpimprovementfmvs");

	$obju_Faas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
	$obju_Faas1a = new documentschema_br(null,$objConnection,"u_rpfaas1a");
	$obju_Faas1b = new documentlinesschema_br(null,$objConnection,"u_rpfaas1b");
	$obju_Faas1c = new documentlinesschema_br(null,$objConnection,"u_rpfaas1c");

	$obju_Faas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
	$obju_Faas2a = new documentschema_br(null,$objConnection,"u_rpfaas2a");
	$obju_Faas2b = new documentlinesschema_br(null,$objConnection,"u_rpfaas2b");
	$obju_Faas2d = new documentlinesschema_br(null,$objConnection,"u_rpfaas2d");
	$obju_Faas2e = new documentlinesschema_br(null,$objConnection,"u_rpfaas2e");

	$obju_Faas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
	$obju_Faas3a = new documentschema_br(null,$objConnection,"u_rpfaas3a");
	$obju_Faas3b = new documentlinesschema_br(null,$objConnection,"u_rpfaas3b");

	$obju_BldgPropGroups = new masterdataschema(null,$objConnection,"u_rpbldgpropgroups");
	$obju_BldgProps = new masterdataschema(null,$objConnection,"u_rpbldgprops");
	$obju_BldgExtraItems = new masterdataschema(null,$objConnection,"u_rpbldgextraitems");

	$obju_BldgStructTypes = new masterdataschema(null,$objConnection,"u_rpbldgstructypes");
	$obju_BldgKinds = new masterdataschema(null,$objConnection,"u_rpbldgkinds");
	$obju_BldgClasses = new masterdataschema(null,$objConnection,"u_rpbldgclasses");
	$obju_BldgStructureClassRates = new masterdataschema(null,$objConnection,"u_rpbldgstructclassrates");



	$obju_MachTypes = new masterdataschema(null,$objConnection,"u_rpmachtypes");
	$obju_MachConditions = new masterdataschema(null,$objConnection,"u_rpmachconditions");

	$obju_Faas1Prev = new documentschema_br(null,$objConnection,"u_rpfaas1");
	$obju_Faas2Prev = new documentschema_br(null,$objConnection,"u_rpfaas2");
	$obju_Faas3Prev = new documentschema_br(null,$objConnection,"u_rpfaas3");

	$obju_Pos = new documentschema_br(null,$objConnection,"u_lgupos");
	$obju_PosItems = new documentlinesschema_br(null,$objConnection,"u_lgupositems");
	
	$obju_Taxes = new documentschema_br(null,$objConnection,"u_rptaxes");
	$obju_TaxArps = new documentlinesschema_br(null,$objConnection,"u_rptaxarps");

	$objConnection->beginwork();

	
	$odbccon = @odbc_connect("itax","sysdba","masterkey",SQL_CUR_USE_ODBC) or  die("<B>Error!</B> Couldn't Connect To Database. Error Code:  ".odbc_error());
	

	
	
	
	if ($actionReturn) $actionReturn = $obju_Lands->executesql("delete from u_rplands",false);
	if ($actionReturn) $actionReturn = $obju_Lands->executesql("delete from u_rplandclasses",false);
	if ($actionReturn) $actionReturn = $obju_LandSubclasses->executesql("delete from u_rplandsubclasses",false);
	if ($actionReturn) $actionReturn = $obju_ActUses->executesql("delete from u_rpactuses",false);
	if ($actionReturn) $actionReturn = $obju_Improvements->executesql("delete from u_rpimprovements",false);
	if ($actionReturn) $actionReturn = $obju_Improvementfmvs->executesql("delete from u_rpimprovementfmvs",false);
	if ($actionReturn) $actionReturn = $obju_Machineries->executesql("delete from u_rpmachineries",false);
	if ($actionReturn) $actionReturn = $obju_BldgPropGroups->executesql("delete from u_rpbldgpropgroups",false);
	if ($actionReturn) $actionReturn = $obju_BldgProps->executesql("delete from u_rpbldgprops",false);
	if ($actionReturn) $actionReturn = $obju_BldgProps->executesql("delete from u_rpbldgextraitems",false);
	if ($actionReturn) $actionReturn = $obju_BldgStructTypes->executesql("delete from u_rpbldgstructypes",false);
	if ($actionReturn) $actionReturn = $obju_BldgKinds->executesql("delete from u_rpbldgkinds",false);
	if ($actionReturn) $actionReturn = $obju_BldgClasses->executesql("delete from u_rpbldgclasses",false);
	if ($actionReturn) $actionReturn = $obju_BldgClasses->executesql("delete from u_rpbldgstructclassrates",false);
	if ($actionReturn) $actionReturn = $obju_MachTypes->executesql("delete from u_rpmachtypes",false);
	if ($actionReturn) $actionReturn = $obju_MachConditions->executesql("delete from u_rpmachconditions",false);
	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT a.CODE as CLASSCODE_CT, a.DESCRIPTION as CLASSCODE, a.MUNICIPAL_ID, a.USERID, a.TRANSDATE, a.SORTORDER
FROM T_CLASSIFICATION a") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_Lands->prepareadd();
			$obju_Lands->code = trim($ar["CLASSCODE_CT"]);
			$obju_Lands->name = $ar["CLASSCODE"];
			//$obju_ActUses->setudfvalue("u_assesslevel",$ar["ASSESSMENTLEVEL"]*100);
			$actionReturn = $obju_Lands->add();
			if (!$actionReturn) break;

			//$odbcres3 = @odbc_exec($odbccon,"SELECT a.CLASSCODE_CT, a.DESCRIPTION, a.LONGDESCRIPTION, a.MUNICIPAL_ID, a.USERID, a.TRANSDATE FROM T_SUBCLASSIFICATION a WHERE a.CLASSCODE_CT='".$ar["CLASSCODE_CT"]."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
			$odbcres3 = @odbc_exec($odbccon,"SELECT a.CLASSCODE_CT, c.DESCRIPTION as SUBCLASS, d.DESCRIPTION as CLASSLEVEL FROM RPTLANDAPPRAISAL a LEFT JOIN T_SUBCLASSIFICATION c ON c.CODE=a.SUBCLASS_CT LEFT JOIN T_CLASSLEVEL d ON d.CODE=a.CLASSLEVEL_CT WHERE a.CLASSCODE_CT='".$ar["CLASSCODE_CT"]."' GROUP BY a.CLASSCODE_CT, c.DESCRIPTION, d.DESCRIPTION") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
			 while(odbc_fetch_row($odbcres3)) {
			
				//Build tempory
				for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
				   $field_name = odbc_field_name($odbcres3, $j);
				  // $this->temp_fieldnames[$j] = $field_name;
				   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
				}
				
				$obju_LandSubclasses->prepareadd();
				$obju_LandSubclasses->code = $obju_Lands->code;
				$obju_LandSubclasses->lineid = getNextIDByBranch("u_rplandsubclasses",$objConnection);
				$obju_LandSubclasses->setudfvalue("u_subclass",$ar3["SUBCLASS"]);
				$obju_LandSubclasses->setudfvalue("u_class",$ar3["CLASSLEVEL"]);
				
				$actionReturn = $obju_LandSubclasses->add();
				if (!$actionReturn) break;
			}
			if (!$actionReturn) break;			
			
		}		
	}	
	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT a.CODE as CLASSCODE_CT, a.DESCRIPTION as CLASSCODE FROM T_CLASSLEVEL a") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_LandClasses->prepareadd();
			$obju_LandClasses->code = trim($ar["CLASSCODE"]);
			$obju_LandClasses->name = $ar["CLASSCODE"];
			//$obju_ActUses->setudfvalue("u_assesslevel",$ar["ASSESSMENTLEVEL"]*100);
			$actionReturn = $obju_LandClasses->add();
			if (!$actionReturn) break;
		}		
	}	
	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT b.CODE as ACTUALUSE_CT, b.DESCRIPTION as ACTUALUSE, a.ASSESSMENTLEVEL
FROM T_ACTUALUSE b INNER JOIN T_ASSESSMENTLEVEL a ON a.ACTUALUSE_CT=b.CODE AND a.PROPERTYKIND_CT='L' AND A.YEARSTART = '2013' GROUP BY b.CODE, b.DESCRIPTION, a.ASSESSMENTLEVEL") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_ActUses->prepareadd();
			$obju_ActUses->code = $ar["ACTUALUSE_CT"];
			$obju_ActUses->name = $ar["ACTUALUSE"];
			$obju_ActUses->setudfvalue("u_assesslevel",$ar["ASSESSMENTLEVEL"]*100);
			$actionReturn = $obju_ActUses->add();
			if (!$actionReturn) break;

			$obju_Improvements->prepareadd();
			$obju_Improvements->code = $ar["ACTUALUSE_CT"];
			$obju_Improvements->name = $ar["ACTUALUSE"];
			$actionReturn = $obju_Improvements->add();
			if (!$actionReturn) break;
			
			$odbcres3 = @odbc_exec($odbccon,"SELECT a.HASRANGE_BV, a.VALUEFROM, a.VALUETO, a.ASSESSMENTLEVEL
FROM T_ASSESSMENTLEVEL a WHERE a.ACTUALUSE_CT='".$ar["ACTUALUSE_CT"]."' and a.PROPERTYKIND_CT='B'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
			 while(odbc_fetch_row($odbcres3)) {
			
				//Build tempory
				for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
				   $field_name = odbc_field_name($odbcres3, $j);
				  // $this->temp_fieldnames[$j] = $field_name;
				   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
				}
				
				$obju_Improvementfmvs->prepareadd();
				$obju_Improvementfmvs->code = $obju_Improvements->code;
				$obju_Improvementfmvs->lineid = getNextIDByBranch("u_rpimprovementfmvs",$objConnection);
				if (intval($ar3["HASRANGE_BV"])==1) {
					$obju_Improvementfmvs->setudfvalue("u_fmvover",$ar3["VALUEFROM"]);
					$obju_Improvementfmvs->setudfvalue("u_fmvbutnotover",$ar3["VALUETO"]);
				} else {
					$obju_Improvementfmvs->setudfvalue("u_fmvover",0);
					$obju_Improvementfmvs->setudfvalue("u_fmvbutnotover",0);
				}	
				$obju_Improvementfmvs->setudfvalue("u_assesslevel",$ar3["ASSESSMENTLEVEL"]*100);
				
				$actionReturn = $obju_Improvementfmvs->add();
				if (!$actionReturn) break;
			}
			if (!$actionReturn) break;			
			
		}		
	}	
	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT a.ACTUALUSE_CT, b.DESCRIPTION as ACTUALUSE, a.ASSESSMENTLEVEL
FROM T_ASSESSMENTLEVEL a LEFT JOIN T_ACTUALUSE b ON b.CODE=a.ACTUALUSE_CT where propertykind_ct='M' AND A.YEARSTART = '2013'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_Machineries->prepareadd();
			$obju_Machineries->code = $ar["ACTUALUSE_CT"];
			$obju_Machineries->name = $ar["ACTUALUSE"];
			$obju_Machineries->setudfvalue("u_assesslevel",$ar["ASSESSMENTLEVEL"]*100);
			$actionReturn = $obju_Machineries->add();
			if (!$actionReturn) break;
		}		
	}	
	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT a.CODE, a.DESCRIPTION, a.SORTORDER
FROM T_BLDGCOMPONENT a") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_BldgPropGroups->prepareadd();
			$obju_BldgPropGroups->code = $ar["DESCRIPTION"];
			$obju_BldgPropGroups->name = $ar["DESCRIPTION"];
			$obju_BldgPropGroups->setudfvalue("u_seqno",$ar["SORTORDER"]);
			$actionReturn = $obju_BldgPropGroups->add();
			if (!$actionReturn) break;
		}		
	}	
	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT b.DESCRIPTION as BLDGCOMPONENT, a.DESCRIPTION, MAX(c.UNITPRICE) as UNITPRICE
FROM T_BLDGEXTRAITEM a LEFT JOIN T_BLDGCOMPONENT b ON b.CODE=a.BLDGCOMPONENT_CT LEFT JOIN RPTBLDGEXTRA c ON c.EXTRAITEM_CT=a.CODE GROUP BY a.DESCRIPTION, b.DESCRIPTION") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_BldgExtraItems->prepareadd();
			$obju_BldgExtraItems->code = $ar["BLDGCOMPONENT"]."-".$ar["DESCRIPTION"];
			$obju_BldgExtraItems->name = $ar["DESCRIPTION"];
			$obju_BldgExtraItems->setudfvalue("u_group",$ar["BLDGCOMPONENT"]);
			$obju_BldgExtraItems->setudfvalue("u_unitvalue",$ar["UNITPRICE"]);
			$actionReturn = $obju_BldgExtraItems->add();
			if (!$actionReturn) break;
		}		
	}	
	
	

	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT b.DESCRIPTION as BLDGCOMPONENT, c.DESCRIPTION as BLDGMATERIAL
	FROM RPTBLDGSTRUCTURALMATERIAL a LEFT JOIN T_BLDGCOMPONENT b ON b.CODE=a.BLDGCOMPONENT_CT LEFT JOIN T_BLDGMATERIAL c ON c.CODE=a.BLDGMATERIAL_CT GROUP BY b.DESCRIPTION, c.DESCRIPTION") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_BldgProps->prepareadd();
			$obju_BldgProps->code = $ar["BLDGCOMPONENT"]."-".$ar["BLDGMATERIAL"];
			$obju_BldgProps->name = $ar["BLDGMATERIAL"];
			$obju_BldgProps->setudfvalue("u_group",$ar["BLDGCOMPONENT"]);
			//$obju_BldgProps->setudfvalue("u_seqno",$ar["SORTORDER"]);
			$actionReturn = $obju_BldgProps->add();
			if (!$actionReturn) break;
		}		
	}	
	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT a.CODE, a.DESCRIPTION, a.LONGDESCRIPTION, a.USERID, a.TRANSDATE, a.YEARSTART
FROM T_BLDGSTRUCTURETYPE a WHERE A.YEARSTART = '2013'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_BldgStructTypes->prepareadd();
			$obju_BldgStructTypes->code = $ar["DESCRIPTION"];
			$obju_BldgStructTypes->name = $ar["LONGDESCRIPTION"];
			$actionReturn = $obju_BldgStructTypes->add();
			if (!$actionReturn) break;
		}		
	}	

	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT a.CODE, a.DESCRIPTION, a.USERID, a.TRANSDATE
FROM T_BLDGKIND a") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_BldgKinds->prepareadd();
			$obju_BldgKinds->code = $ar["DESCRIPTION"];
			$obju_BldgKinds->name = $ar["DESCRIPTION"];
			$actionReturn = $obju_BldgKinds->add();
			if (!$actionReturn) break;
		}		
	}	

	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT a.CODE, a.DESCRIPTION, a.USERID, a.TRANSDATE, a.YEARSTART
FROM T_BLDGCLASS a WHERE A.YEARSTART = '2013'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_BldgClasses->prepareadd();
			$obju_BldgClasses->code = $ar["DESCRIPTION"];
			$obju_BldgClasses->name = $ar["DESCRIPTION"];
			$actionReturn = $obju_BldgClasses->add();
			if (!$actionReturn) break;
		}		
	}	

	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT b.DESCRIPTION as STRUCTURETYPE, c.DESCRIPTION as BLDGCLASS, max(a.UNITVALUE) as UNITVALUE
FROM RPTBLDGFLOOR a LEFT JOIN T_BLDGSTRUCTURETYPE b ON b.CODE=A.STRUCTURETYPE_CT LEFT JOIN T_BLDGCLASS c ON c.CODE=a.BLDGCLASS_CT GROUP BY b.DESCRIPTION, c.DESCRIPTION") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_BldgStructureClassRates->prepareadd();
			$obju_BldgStructureClassRates->code = $ar["STRUCTURETYPE"]." - ".$ar["BLDGCLASS"];
			$obju_BldgStructureClassRates->name = $ar["STRUCTURETYPE"]." - ".$ar["BLDGCLASS"];
			$obju_BldgStructureClassRates->setudfvalue("u_structuretype",$ar["STRUCTURETYPE"]);
			$obju_BldgStructureClassRates->setudfvalue("u_subclass",$ar["BLDGCLASS"]);
			$obju_BldgStructureClassRates->setudfvalue("u_unitvalue",$ar["UNITVALUE"]);
			$actionReturn = $obju_BldgStructureClassRates->add();
			if (!$actionReturn) break;
		}		
	}	



	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT a.DESCRIPTION
FROM T_MACHINETYPE a GROUP BY a.DESCRIPTION") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_MachTypes->prepareadd();
			$obju_MachTypes->code = $ar["DESCRIPTION"];
			$obju_MachTypes->name = $ar["DESCRIPTION"];
			$actionReturn = $obju_MachTypes->add();
			if (!$actionReturn) break;
		}		
	}	
	
	if ($actionReturn) {
	
		$odbcres = @odbc_exec($odbccon,"SELECT a.CODE, a.DESCRIPTION, a.USERID, a.TRANSDATE, a.DEPMINVAL, a.DEPMAXVAL, a.YEARLY_BV
FROM T_MACHINECONDITION a") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
		 while(odbc_fetch_row($odbcres)) {
	
			//Build tempory
			for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
			   $field_name = odbc_field_name($odbcres, $j);
			  // $this->temp_fieldnames[$j] = $field_name;
			   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
			}
			
			$obju_MachConditions->prepareadd();
			$obju_MachConditions->code = $ar["DESCRIPTION"];
			$obju_MachConditions->name = $ar["DESCRIPTION"];
			$actionReturn = $obju_MachConditions->add();
			if (!$actionReturn) break;
		}		
	}	
		
	if ($actionReturn)	{
		$objRs->queryopen("select sum(cnt) as cnt from (
select count(*) as cnt from u_rpfaas1 union all select count(*) as cnt from u_rpfaas2 union all select count(*) as cnt from u_rpfaas3) as x");
		$objRs->queryfetchrow();
		

	//if ($objRs->fields[0]==0) {
		$odbcres = @odbc_exec($odbccon,"SELECT a.TAXTRANS_ID, a.PREVTAXTRANS_ID, a.PROP_ID, a.TDNO, a.TAXABILITY_BV, a.STARTQUARTER, a.STARTYEAR, a.ENDED_BV, a.ENDQUARTER, a.ENDYEAR, a.CANCELLATIONDATE, a.SUBMISSIONDATE, a.ASSESSEDBY, a.TRANSDATE, 
	a.USERID, a.SUBMITTEDBY, a.TRANSCODE_CT, a.MEMORANDA, a.APPROVEDBY, a.APPROVEDDATE, a.RECOMAPPROVEDBY, a.RECOMAPPROVEDDATE, a.ASSESSEDDATE, a.GRYEAR, a.ANNOTATION, a.FORAPPROVAL_BV, a.RPTRESTRICTION_CT, 
	a.PREDOMCLASSCODE_CT, a.PREDOMRPTDETAILID, a.SPECIALSTATUS_CT, a.CHANGEUSERID, a.TMCR_REMARK, a.SWORNSTATEMENT_BV, 
	b.PROP_ID, b.PREVPROP_ID, b.PINNO, b.NEWPINNO, b.PROPERTYKIND_CT, b.OWNERTYPE_CT, b.CERTIFICATETITLENO, b.CADASTRALLOTNO, b.STREET, b.BARANGAY_CT, w.DESCRIPTION as BARANGAY, b.MUNICIPAL_ID, x.DESCRIPTION as MUNICIPAL, b.PROVINCE_CT, y.DESCRIPTION as PROVINCE, b.EXPIRED_BV, b.EXPIREDYEAR, 
	b.USERID, b.TRANSDATE, b.LOTNO, b.BOUNDARYNORTH, b.BOUNDARYEAST, b.BOUNDARYSOUTH, b.BOUNDARYWEST, b.PROPSECTION, b.SURVEYNO, b.BLKNO, b.CERTIFICATETITLEDATE, b.PROPERTYREF_ID, b.SKETCH, b.PARCELNO, b.SECTION, 
        c.PROP_ID, c.LOCAL_TIN, c.VALIDFROM, c.VALIDUNTIL, c.USERID, c.TRANSDATE, d.LOCAL_TIN, d.OWNERNAME, d.OWNERADDRESS, d.FIRSTNAME, d.LASTNAME, d.MI, d.TINNO, d.CITIZENSHIP, d.CIVILSTATUS, d.COUNTRY_CT, d.ACTIVE_BV, d.USERID, d.TRANSDATE, d.DATEOFBIRTH, d.PLACEOFBIRTH, d.HEIGHT, d.WEIGHT, d.OCCUPATION, d.TELNO, d.ORGANIZATIONKIND_CT, 
	d.DATEREGINCORPORATION, d.PLACEOFINCORPORATION, d.ICRNO, d.EMAIL, d.GENDER, d.MERGE_BV, d.PARENT_TIN, e.NUMSTOREY, e.YEARCONSTRUCTED, e.YEAROCCUPIED, e.USERID, e.TRANSDATE, e.DESCRIPTION, e.PERMITNO, e.PERMITDATE, e.YEARRENOVATED, e.BLDGAGE, e.CCT, e.CCTCOMPISSUED, e.CCTOCCISSUED, e.YEARCOMPLETED
	FROM RPTASSESSMENT a LEFT JOIN PROPERTY b ON b.PROP_ID=a.PROP_ID LEFT JOIN PROPERTYOWNER c ON c.PROP_ID=b.PROP_ID LEFT JOIN TAXPAYER d ON d.LOCAL_TIN=c.LOCAL_TIN LEFT JOIN T_BARANGAY w ON w.CODE=b.BARANGAY_CT AND w.MUNICIPAL_ID=b.MUNICIPAL_ID AND w.PROVINCE_CT=b.PROVINCE_CT LEFT JOIN T_MUNICIPALITY x ON x.MUNICIPAL_ID=b.MUNICIPAL_ID LEFT JOIN T_PROVINCE y ON y.CODE=b.PROVINCE_CT LEFT JOIN RPTBLDGINFO e ON e.TAXTRANS_ID=a.TAXTRANS_ID ORDER BY a.TAXTRANS_ID") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
		
	  while(odbc_fetch_row($odbcres)) {
	
		//Build tempory
		for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
		   $field_name = odbc_field_name($odbcres, $j);
		  // $this->temp_fieldnames[$j] = $field_name;
		   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
		}
	
		//var_dump( $ar);
		if ($ar["PROPERTYKIND_CT"]=="B") {
                     if($obju_Faas2->getbykey($ar["TAXTRANS_ID"])){
                        var_dump( $ar["TAXTRANS_ID"]);
                    }else{
                        $num_rows++;
			$obju_Faas2->prepareadd();
			$obju_Faas2->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
			$obju_Faas2->docseries = -1;
			$obju_Faas2->docno = $ar["TAXTRANS_ID"];
			$obju_Faas2->setudfvalue("u_pin",$ar["PINNO"]);
			$obju_Faas2->setudfvalue("u_prefix",substr($ar["PINNO"],0,strlen($ar["PINNO"])-5));
			$obju_Faas2->setudfvalue("u_suffix",substr($ar["PINNO"],strlen($ar["PINNO"])-4,4));
			$obju_Faas2->setudfvalue("u_tdno",$ar["TDNO"]);
			$obju_Faas2->setudfvalue("u_trxcode",$ar["TRANSCODE_CT"]);
	
			//$obju_Faas2->setudfvalue("u_tctno",$ar["CERTIFICATETITLENO"]);
			$obju_Faas2->setudfvalue("u_surveyno",$ar["SURVEYNO"]);
			$obju_Faas2->setudfvalue("u_lotno",$ar["LOTNO"]);
			$obju_Faas2->setudfvalue("u_block",$ar["BLKNO"]);
			//$obju_Faas2->setudfvalue("u_cadlotno",$ar["CADASTRALLOTNO"]);
	
			$obju_Faas2->setudfvalue("u_street",$ar["STREET"]);
			$obju_Faas2->setudfvalue("u_barangay",strtoupper($ar["BARANGAY"]));
			$obju_Faas2->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
			$obju_Faas2->setudfvalue("u_province",$ar["PROVINCE"]);
	
			//$obju_Faas2->setudfvalue("u_north",$ar["BOUNDARYNORTH"]);
			//$obju_Faas2->setudfvalue("u_east",$ar["BOUNDARYEAST"]);
			//$obju_Faas2->setudfvalue("u_south",$ar["BOUNDARYSOUTH"]);
			//$obju_Faas2->setudfvalue("u_west",$ar["BOUNDARYWEST"]);
	
			if ($ar["LASTNAME"]=="") {
				$obju_Faas2->setudfvalue("u_ownertype","C");
				$obju_Faas2->setudfvalue("u_ownercompanyname",$ar["OWNERNAME"]);
			}	
			
			$obju_Faas2->setudfvalue("u_ownerlastname",$ar["LASTNAME"]);
			$obju_Faas2->setudfvalue("u_ownerfirstname",$ar["FIRSTNAME"]);
			$obju_Faas2->setudfvalue("u_ownermiddlename",$ar["MI"]);
			$obju_Faas2->setudfvalue("u_ownername",$ar["OWNERNAME"]);
			$obju_Faas2->setudfvalue("u_ownertin",$ar["LOCAL_TIN"]);
			$obju_Faas2->setudfvalue("u_ownertelno",$ar["TELNO"]);
			$obju_Faas2->setudfvalue("u_owneraddress",$ar["OWNERADDRESS"]);
	
			$obju_Faas2->setudfvalue("u_taxable",$ar["TAXABILITY_BV"]);
			$obju_Faas2->setudfvalue("u_effdate",$ar["STARTYEAR"]."-".$ar["STARTQUARTER"]."-01");
			$obju_Faas2->setudfvalue("u_effqtr",$ar["STARTQUARTER"]);
			$obju_Faas2->setudfvalue("u_effyear",$ar["STARTYEAR"]);
			if ($ar["ENDED_BV"]==1) {
				$obju_Faas2->setudfvalue("u_cancelled",1);
				$obju_Faas2->setudfvalue("u_expdate",$ar["CANCELLATIONDATE"]);
				$obju_Faas2->setudfvalue("u_expqtr",$ar["ENDQUARTER"]);
				$obju_Faas2->setudfvalue("u_expyear",$ar["ENDYEAR"]);
			}	
			$obju_Faas2->setudfvalue("u_recommendby",$ar["RECOMAPPROVEDBY"]);
			$obju_Faas2->setudfvalue("u_recommenddate",$ar["RECOMAPPROVEDDATE"]);
			$obju_Faas2->setudfvalue("u_approvedby",$ar["APPROVEDBY"]);
			$obju_Faas2->setudfvalue("u_approveddate",$ar["APPROVEDDATE"]);
			$obju_Faas2->setudfvalue("u_memoranda",$ar["MEMORANDA"]);
			$obju_Faas2->docstatus = "Approved";
	
			$obju_Faas2->setudfvalue("u_cct",$ar["CCT"]);
			$obju_Faas2->setudfvalue("u_ccidate",$ar["CCTCOISSUED"]);
			$obju_Faas2->setudfvalue("u_coidate",$ar["CCTOCISSUED"]);
			$obju_Faas2->setudfvalue("u_floorcount",$ar["NUMSTOREY"]);
			$obju_Faas2->setudfvalue("u_bpno",$ar["PERMITNO"]);
			$obju_Faas2->setudfvalue("u_bpdate",$ar["PERMITDATE"]);
			$obju_Faas2->setudfvalue("u_startyear",$ar["YEARCONSTRUCTED"]);
			$obju_Faas2->setudfvalue("u_endyear",$ar["YEARCOMPLETED"]);
			$obju_Faas2->setudfvalue("u_occyear",$ar["YEAROCCUPIED"]);
			$obju_Faas2->setudfvalue("u_renyear",$ar["YEARRENOVATED"]);
			
			if ($ar["PREVTAXTRANS_ID"]!="") {
				$obju_Faas2->setudfvalue("u_prevarpno",$ar["PREVTAXTRANS_ID"]);
				
				$odbcres2 = @odbc_exec($odbccon,"SELECT a.TDNO, a.STARTQUARTER, a.STARTYEAR, b.PINNO, d.OWNERNAME
	FROM RPTASSESSMENT a LEFT JOIN PROPERTY b ON b.PROP_ID=a.PROP_ID LEFT JOIN PROPERTYOWNER c ON c.PROP_ID=b.PROP_ID LEFT JOIN TAXPAYER d ON d.LOCAL_TIN=c.LOCAL_TIN WHERE a.TAXTRANS_ID='".$ar["PREVTAXTRANS_ID"]."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
				 if(odbc_fetch_row($odbcres2)) {
				
					//Build tempory
					for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
					   $field_name = odbc_field_name($odbcres2, $j);
					  // $this->temp_fieldnames[$j] = $field_name;
					   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
					}
					
					$obju_Faas2->setudfvalue("u_prevpin",$ar2["PINNO"]);
					$obju_Faas2->setudfvalue("u_prevtdno",$ar2["TDNO"]);
					$obju_Faas2->setudfvalue("u_prevowner",$ar2["OWNERNAME"]);
					if ($ar2["STARTYEAR"]!="") $obju_Faas2->setudfvalue("u_preveffdate",$ar2["STARTYEAR"]."-".$ar2["STARTQUARTER"]."-01");
				}	
				if ($obju_Faas2Prev->getbykey($ar["PREVTAXTRANS_ID"])) {
					$obju_Faas2->setudfvalue("u_prevvalue",$obju_Faas2Prev->getudfvalue("u_assvalue"));
				}
			}		
			$actionReturn = $obju_Faas2->add();

			$u_building="";
			$u_structuretype="";
			if ($actionReturn) {
				$odbcres2 = @odbc_exec($odbccon,"SELECT a.FLOOR_ID, a.TAXTRANS_ID, a.FLOORNUMBER, b.DESCRIPTION as BLDGKIND, c.DESCRIPTION as STRUCTURETYPE, d.DESCRIPTION as BLDGCLASS, a.CLASSCODE_CT, a.ACTUALUSE_CT, 
	a.PERCENTCOMPLETED, a.BLDGAGE, a.USERID, a.TRANSDATE, a.UNITVALUE, a.SFMVBLDGCOST_ID, a.TAXABILITY_BV, a.YEARCONREN, a.TAXDECDETAIL_ID, a.MARKETVALUE, a.AREA, 
	e.RATE, e.USERID, e.TRANSDATE, e.BLDGAGE, e.DEPVALUE, e.DEPMARKETVALUE, e.ADJRATE, e.ADJVALUE, e.ADJMARKETVALUE
	FROM RPTBLDGFLOOR a LEFT JOIN RPTBLDGDEPRECIATION e ON e.TAXTRANS_ID=a.TAXTRANS_ID AND e.FLOORNUMBER=a.FLOORNUMBER AND e.SOURCE_CT='bldgfloor' LEFT JOIN T_BLDGKIND b ON b.CODE=a.BLDGKIND_CT LEFT JOIN T_BLDGSTRUCTURETYPE c ON c.CODE=a.STRUCTURETYPE_CT LEFT JOIN T_BLDGCLASS d ON d.CODE=a.BLDGCLASS_CT WHERE a.TAXTRANS_ID='".$obju_Faas2->docno."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
				 while(odbc_fetch_row($odbcres2)) {
				
					//Build tempory
					for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
					   $field_name = odbc_field_name($odbcres2, $j);
					  // $this->temp_fieldnames[$j] = $field_name;
					   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
					}
					
					
					$u_building = $ar2["BLDGKIND"];
					$u_structuretype = $ar2["STRUCTURETYPE"];
					
					$obju_Faas2a->prepareadd();
					$obju_Faas2a->docid = getNextIDByBranch("u_rpfaas2a",$objConnection);
					$obju_Faas2a->docno = getNextNoByBranch("u_rpfaas2a",'',$objConnection);
					//var_dump(array($obju_Faas2a->docid,$obju_Faas2a->docno));
					$obju_Faas2a->setudfvalue("u_arpno",$obju_Faas2->docno);
					$obju_Faas2a->setudfvalue("u_class",trim($ar2["CLASSCODE_CT"]));
					$obju_Faas2a->setudfvalue("u_actualuse",trim($ar2["ACTUALUSE_CT"]));
					
					//$obju_Faas2a->setudfvalue("u_taxability",$ar2["TAXABILITY_BV"]);
					$obju_Faas2a->setudfvalue("u_floor",$ar2["FLOORNUMBER"]);
					$obju_Faas2a->setudfvalue("u_structuretype",$ar2["STRUCTURETYPE"]);
					$obju_Faas2a->setudfvalue("u_subclass",$ar2["BLDGCLASS"]);
					$obju_Faas2a->setudfvalue("u_class",trim($ar2["CLASSCODE_CT"]));
					$obju_Faas2a->setudfvalue("u_sqm",$ar2["AREA"]);
					$obju_Faas2a->setudfvalue("u_startyear",$ar2["YEARCONREN"]);
					$obju_Faas2a->setudfvalue("u_age",$ar2["BLDGAGE"]);
					$obju_Faas2a->setudfvalue("u_unitvalue",$ar2["UNITVALUE"]);
					$obju_Faas2a->setudfvalue("u_completeperc",$ar2["PERCENTCOMPLETED"]*100);
					$obju_Faas2a->setudfvalue("u_floorbasevalue",$ar2["MARKETVALUE"]);
					$obju_Faas2a->setudfvalue("u_flooradjperc",$ar2["ADJRATE"]*100);
					$obju_Faas2a->setudfvalue("u_flooradjvalue",$ar2["ADJVALUE"]);
					$obju_Faas2a->setudfvalue("u_floordeprevalue",$ar2["DEPVALUE"]);
					$obju_Faas2a->setudfvalue("u_flooradjmarketvalue",$ar2["ADJMARKETVALUE"]);
					$basevalue=$ar2["MARKETVALUE"];
					$adjvalue=$ar2["ADJVALUE"];
					$deprevalue=$ar2["DEPVALUE"];
					$adjmarketvalue=$ar2["ADJMARKETVALUE"];
					
					$odbcres3 = @odbc_exec($odbccon,"SELECT b.DESCRIPTION as BLDGCOMPONENT, c.DESCRIPTION as BLDGMATERIAL, a.USERID, a.TRANSDATE 
	FROM RPTBLDGSTRUCTURALMATERIAL a LEFT JOIN T_BLDGCOMPONENT b ON b.CODE=a.BLDGCOMPONENT_CT LEFT JOIN T_BLDGMATERIAL c ON c.CODE=a.BLDGMATERIAL_CT WHERE a.TAXTRANS_ID='".$ar2["TAXTRANS_ID"]."' and a.FLOORNUMBER=0") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
					 while(odbc_fetch_row($odbcres3)) {
					
						//Build tempory
						for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
						   $field_name = odbc_field_name($odbcres3, $j);
						  // $this->temp_fieldnames[$j] = $field_name;
						   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
						}
						
						$obju_Faas2d->prepareadd();
						$obju_Faas2d->docid = $obju_Faas2a->docid;
						$obju_Faas2d->lineid = getNextIDByBranch("u_rpfaas2d",$objConnection);
						$obju_Faas2d->setudfvalue("u_selected",1);
						$obju_Faas2d->setudfvalue("u_prop",trim($ar3["BLDGCOMPONENT"])."-".trim($ar3["BLDGMATERIAL"]));
						
						$actionReturn = $obju_Faas2d->add();
						if (!$actionReturn) break;
					}
					if (!$actionReturn) break;			
					
					$odbcres3 = @odbc_exec($odbccon,"SELECT a.EXTRA_ID, a.TAXTRANS_ID, a.SFMVBLDGEXTRA_ID, b.DESCRIPTION AS EXTRAITEM, a.AREA, a.UNITPRICE, a.EXTRAAMOUNT, a.USERID, a.TRANSDATE, a.FLOOR_ID, a.FLOORNUMBER, a.YEARCONREN, a.PERCENTCOMPLETED, a.BLDGAGE, a.TAXABILITY_BV, 
	a.ACTUALUSE_CT, a.CLASSCODE_CT, 
	e.RATE, e.USERID, e.TRANSDATE, e.BLDGAGE, e.DEPVALUE, e.DEPMARKETVALUE, e.ADJRATE, e.ADJVALUE, e.ADJMARKETVALUE
	FROM RPTBLDGEXTRA a  LEFT JOIN T_BLDGEXTRAITEM b ON b.CODE=a.EXTRAITEM_CT LEFT JOIN RPTBLDGDEPRECIATION e ON e.TAXTRANS_ID=a.TAXTRANS_ID AND e.FLOORNUMBER=a.FLOORNUMBER AND e.SOURCE_CT='bldgextra' AND e.FLOORITEM=b.DESCRIPTION
	WHERE a.FLOOR_ID='".$ar2["FLOOR_ID"]."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
					 while(odbc_fetch_row($odbcres3)) {
					
						//Build tempory
						for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
						   $field_name = odbc_field_name($odbcres3, $j);
						  // $this->temp_fieldnames[$j] = $field_name;
						   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
						}
						
						$obju_Faas2b->prepareadd();
						$obju_Faas2b->docid = $obju_Faas2a->docid;
						$obju_Faas2b->lineid = getNextIDByBranch("u_rpfaas2b",$objConnection);
						$obju_Faas2b->setudfvalue("u_itemdesc",trim($ar3["EXTRAITEM"]));
						$obju_Faas2b->setudfvalue("u_sqm",$ar3["AREA"]);
						$obju_Faas2b->setudfvalue("u_startyear",$ar3["YEARCONREN"]);
						$obju_Faas2b->setudfvalue("u_completeperc",$ar3["PERCENTCOMPLETED"]*100);
						$obju_Faas2b->setudfvalue("u_unitvalue",$ar3["UNITPRICE"]);
						$obju_Faas2b->setudfvalue("u_quantity",$ar3["EXTRAAMOUNT"]/$ar3["UNITPRICE"]);
						$obju_Faas2b->setudfvalue("u_basevalue",$ar3["EXTRAAMOUNT"]);
						$obju_Faas2b->setudfvalue("u_adjperc",$ar3["ADJRATE"]*100);
						$obju_Faas2b->setudfvalue("u_adjvalue",$ar3["ADJVALUE"]);
						$obju_Faas2b->setudfvalue("u_deprevalue",$ar3["DEPVALUE"]);
						$obju_Faas2b->setudfvalue("u_adjmarketvalue",$ar3["ADJMARKETVALUE"]);
						$basevalue+=$ar3["MARKETVALUE"];
						$adjvalue+=$ar3["ADJVALUE"];
						$deprevalue+=$ar3["DEPVALUE"];
						$adjmarketvalue+=$ar3["ADJMARKETVALUE"];
						
						$actionReturn = $obju_Faas2b->add();
						if (!$actionReturn) break;
					}
					if (!$actionReturn) break;			
	
					$odbcres3 = @odbc_exec($odbccon,"SELECT b.DESCRIPTION as BLDGCOMPONENT, c.DESCRIPTION as BLDGMATERIAL, a.USERID, a.TRANSDATE 
	FROM RPTBLDGSTRUCTURALMATERIAL a LEFT JOIN T_BLDGCOMPONENT b ON b.CODE=a.BLDGCOMPONENT_CT LEFT JOIN T_BLDGMATERIAL c ON c.CODE=a.BLDGMATERIAL_CT WHERE a.TAXTRANS_ID='".$ar2["TAXTRANS_ID"]."' and a.FLOORNUMBER='".$ar2["FLOORNUMBER"]."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
					 while(odbc_fetch_row($odbcres3)) {
					
						//Build tempory
						for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
						   $field_name = odbc_field_name($odbcres3, $j);
						  // $this->temp_fieldnames[$j] = $field_name;
						   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
						}
						
						$obju_Faas2e->prepareadd();
						$obju_Faas2e->docid = $obju_Faas2a->docid;
						$obju_Faas2e->lineid = getNextIDByBranch("u_rpfaas2e",$objConnection);
						$obju_Faas2e->setudfvalue("u_selected",1);
						$obju_Faas2e->setudfvalue("u_prop",trim($ar3["BLDGCOMPONENT"])."-".trim($ar3["BLDGMATERIAL"]));
						
						$actionReturn = $obju_Faas2e->add();
						if (!$actionReturn) break;
					}
					if (!$actionReturn) break;			
					
					$obju_Faas2a->setudfvalue("u_basevalue",$basevalue);
					$obju_Faas2a->setudfvalue("u_adjvalue",$adjvalue);
					$obju_Faas2a->setudfvalue("u_deprevalue",$deprevalue);
					$obju_Faas2a->setudfvalue("u_adjmarketvalue",$adjmarketvalue);
					
					if ($actionReturn) $actionReturn = $obju_Faas2a->add();
					if (!$actionReturn) break;
				}	
				
				if ($actionReturn) {
					if ($obju_Faas2->getbykey($obju_Faas2->docno)) {
						$obju_Faas2->setudfvalue("u_building",$u_building);
						$obju_Faas2->setudfvalue("u_structuretype",$u_structuretype);
						$actionReturn = $obju_Faas2->update($obju_Faas2->docno,$obju_Faas2->rcdversion);
					} else return raiseError("Unable to retrieve Building Arp No. to update building kind and structure type.");	
				}
	
			}
                    }
			
	
		} elseif ($ar["PROPERTYKIND_CT"]=="M") {
                    if($obju_Faas3->getbykey($ar["TAXTRANS_ID"])){
                        var_dump( $ar["TAXTRANS_ID"]);
                    }else{
                        $num_rows++;
			$obju_Faas3->prepareadd();
			$obju_Faas3->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
			$obju_Faas3->docseries = -1;
			$obju_Faas3->docno = $ar["TAXTRANS_ID"];
			$obju_Faas3->setudfvalue("u_pin",$ar["PINNO"]);
			$obju_Faas3->setudfvalue("u_prefix",substr($ar["PINNO"],0,strlen($ar["PINNO"])-5));
			$obju_Faas3->setudfvalue("u_suffix",substr($ar["PINNO"],strlen($ar["PINNO"])-4,4));
			$obju_Faas3->setudfvalue("u_tdno",$ar["TDNO"]);
			$obju_Faas3->setudfvalue("u_trxcode",$ar["TRANSCODE_CT"]);
	
			//$obju_Faas3->setudfvalue("u_tctno",$ar["CERTIFICATETITLENO"]);
			//$obju_Faas3->setudfvalue("u_surveyno",$ar["SURVEYNO"]);
			//$obju_Faas3->setudfvalue("u_lotno",$ar["LOTNO"]);
			//$obju_Faas3->setudfvalue("u_block",$ar["BLKNO"]);
			//$obju_Faas3->setudfvalue("u_cadlotno",$ar["CADASTRALLOTNO"]);
	
			$obju_Faas3->setudfvalue("u_street",$ar["STREET"]);
			$obju_Faas3->setudfvalue("u_barangay",strtoupper($ar["BARANGAY"]));
			$obju_Faas3->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
			$obju_Faas3->setudfvalue("u_province",$ar["PROVINCE"]);
	
			//$obju_Faas3->setudfvalue("u_north",$ar["BOUNDARYNORTH"]);
			//$obju_Faas3->setudfvalue("u_east",$ar["BOUNDARYEAST"]);
			//$obju_Faas3->setudfvalue("u_south",$ar["BOUNDARYSOUTH"]);
			//$obju_Faas3->setudfvalue("u_west",$ar["BOUNDARYWEST"]);
	
			if ($ar["LASTNAME"]=="") {
				$obju_Faas3->setudfvalue("u_ownertype","C");
				$obju_Faas3->setudfvalue("u_ownercompanyname",$ar["OWNERNAME"]);
			}	
			
			$obju_Faas3->setudfvalue("u_ownerlastname",$ar["LASTNAME"]);
			$obju_Faas3->setudfvalue("u_ownerfirstname",$ar["FIRSTNAME"]);
			$obju_Faas3->setudfvalue("u_ownermiddlename",$ar["MI"]);
			$obju_Faas3->setudfvalue("u_ownername",$ar["OWNERNAME"]);
			$obju_Faas3->setudfvalue("u_ownertin",$ar["LOCAL_TIN"]);
			$obju_Faas3->setudfvalue("u_ownertelno",$ar["TELNO"]);
			$obju_Faas3->setudfvalue("u_owneraddress",$ar["OWNERADDRESS"]);
	
			$obju_Faas3->setudfvalue("u_taxable",$ar["TAXABILITY_BV"]);
			$obju_Faas3->setudfvalue("u_effdate",$ar["STARTYEAR"]."-".$ar["STARTQUARTER"]."-01");
			$obju_Faas3->setudfvalue("u_effqtr",$ar["STARTQUARTER"]);
			$obju_Faas3->setudfvalue("u_effyear",$ar["STARTYEAR"]);
			if ($ar["ENDED_BV"]==1) {
				$obju_Faas3->setudfvalue("u_cancelled",1);
				$obju_Faas3->setudfvalue("u_expdate",$ar["CANCELLATIONDATE"]);
				$obju_Faas3->setudfvalue("u_expqtr",$ar["ENDQUARTER"]);
				$obju_Faas3->setudfvalue("u_expyear",$ar["ENDYEAR"]);
			}	
			$obju_Faas3->setudfvalue("u_recommendby",$ar["RECOMAPPROVEDBY"]);
			$obju_Faas3->setudfvalue("u_recommenddate",$ar["RECOMAPPROVEDDATE"]);
			$obju_Faas3->setudfvalue("u_approvedby",$ar["APPROVEDBY"]);
			$obju_Faas3->setudfvalue("u_approveddate",$ar["APPROVEDDATE"]);
			$obju_Faas3->setudfvalue("u_memoranda",$ar["MEMORANDA"]);
			$obju_Faas3->docstatus = "Approved";
	
			if ($ar["PREVTAXTRANS_ID"]!="") {
				$obju_Faas3->setudfvalue("u_prevarpno",$ar["PREVTAXTRANS_ID"]);
				$odbcres2 = @odbc_exec($odbccon,"SELECT a.TDNO, a.STARTQUARTER, a.STARTYEAR, b.PINNO, d.OWNERNAME
	FROM RPTASSESSMENT a LEFT JOIN PROPERTY b ON b.PROP_ID=a.PROP_ID LEFT JOIN PROPERTYOWNER c ON c.PROP_ID=b.PROP_ID LEFT JOIN TAXPAYER d ON d.LOCAL_TIN=c.LOCAL_TIN WHERE a.TAXTRANS_ID='".$ar["PREVTAXTRANS_ID"]."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
				 if(odbc_fetch_row($odbcres2)) {
				
					//Build tempory
					for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
					   $field_name = odbc_field_name($odbcres2, $j);
					  // $this->temp_fieldnames[$j] = $field_name;
					   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
					}
					
					$obju_Faas3->setudfvalue("u_prevpin",$ar2["PINNO"]);
					$obju_Faas3->setudfvalue("u_prevtdno",$ar2["TDNO"]);
					$obju_Faas3->setudfvalue("u_prevowner",$ar2["OWNERNAME"]);
					if ($ar2["STARTYEAR"]!="") $obju_Faas3->setudfvalue("u_preveffdate",$ar2["STARTYEAR"]."-".$ar2["STARTQUARTER"]."-01");
				}	
				if ($obju_Faas3Prev->getbykey($ar["PREVTAXTRANS_ID"])) {
					$obju_Faas3->setudfvalue("u_prevvalue",$obju_Faas3Prev->getudfvalue("u_assvalue"));
				}
			}		
			$actionReturn = $obju_Faas3->add();
	
			if ($actionReturn) {
				$odbcres2 = @odbc_exec($odbccon,"SELECT a.MACHAPPRAISAL_ID, a.TAXTRANS_ID, a.MACHGROUP_CT, b.DESCRIPTION as MACHTYPE, a.CLASSCODE_CT, a.ACTUALUSE_CT, a.BRAND, a.MODEL, a.CAPACITY, a.YEARACQUIRED, d.DESCRIPTION as MACHCONDITION, a.ESTIMATEDECOLIFE, a.REMAININGECOLIFE, a.YEARINSTALLATION, a.YEAROPERATION, a.REMARK, a.NUMUNIT, a.ACQUISITIONCOST, a.FREIGHTCOST, a.INSURANCECOST, a.INSTALLATIONCOST, a.OTHERCOST, a.MARKETVALUE, a.DEPRECIATION, a.DEPMARKETVALUE, a.USERID, a.TRANSDATE, a.TAXABILITY_BV, a.IMPORTED_BV, a.TAXDECDETAIL_ID
	FROM RPTMACHAPPRAISAL a LEFT JOIN T_MACHINETYPE b ON b.CODE=a.MACHTYPE_CT LEFT JOIN T_MACHINECONDITION d ON d.CODE=a.MACHCONDITION_CT WHERE a.TAXTRANS_ID='".$obju_Faas3->docno."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
				 while(odbc_fetch_row($odbcres2)) {
				
					//Build tempory
					for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
					   $field_name = odbc_field_name($odbcres2, $j);
					  // $this->temp_fieldnames[$j] = $field_name;
					   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
					}
					
					$obju_Faas3a->prepareadd();
					$obju_Faas3a->docid = getNextIDByBranch("u_rpfaas3a",$objConnection);
					$obju_Faas3a->docno = getNextNoByBranch("u_rpfaas3a",'',$objConnection);
					//var_dump(array($obju_Faas3a->docid,$obju_Faas3a->docno));
					$obju_Faas3a->setudfvalue("u_arpno",$obju_Faas3->docno);
					//$obju_Faas3a->setudfvalue("u_class",trim($ar2["CLASSCODE_CT"]));
					$obju_Faas3a->setudfvalue("u_actualuse",trim($ar2["ACTUALUSE_CT"]));
					
					//$obju_Faas3a->setudfvalue("u_taxability",$ar2["TAXABILITY_BV"]);
					$obju_Faas3a->setudfvalue("u_machine",$ar2["MACHTYPE"]);
					$obju_Faas3a->setudfvalue("u_brand",$ar2["BRAND"]);
					$obju_Faas3a->setudfvalue("u_model",$ar2["MODEL"]);
					$obju_Faas3a->setudfvalue("u_capacity",$ar2["CAPACITY"]);
					//$obju_Faas3a->setudfvalue("u_acqdate",$ar2["AREA"]);
					$obju_Faas3a->setudfvalue("u_condition",$ar2["MACHCONDITION"]);
					$obju_Faas3a->setudfvalue("u_estlife",$ar2["ESTIMATEDECOLIFE"]);
					$obju_Faas3a->setudfvalue("u_remlife",$ar2["ESTIMATEDECOLIFE"]-$ar2["REMAININGECOLIFE"]);
					$obju_Faas3a->setudfvalue("u_insyear",$ar2["YEARINSTALLATION"]);
					$obju_Faas3a->setudfvalue("u_inityear",$ar2["YEARACQUIRED"]);
					//$obju_Faas3a->setudfvalue("u_orgcost",$ar2["ACQUISITIONCOST"]*100);
					$obju_Faas3a->setudfvalue("u_orgcost",$ar2["MARKETVALUE"]);
					$obju_Faas3a->setudfvalue("u_cnvfactor",$ar2["ADJVALUE"]);
					$obju_Faas3a->setudfvalue("u_quantity",$ar2["NUMUNIT"]);
					//$obju_Faas3a->setudfvalue("u_rcn",$ar2["ADJMARKETVALUE"]);
					$obju_Faas3a->setudfvalue("u_useyear",$ar2["YEAROPERATION"]);
					//$obju_Faas3a->setudfvalue("u_depreratefr",$ar2["ADJMARKETVALUE"]);
					//$obju_Faas3a->setudfvalue("u_deprerateto",$ar2["ADJMARKETVALUE"]);
					$obju_Faas3a->setudfvalue("u_depreperc",$ar2["DEPRECIATION"]*100);
					$obju_Faas3a->setudfvalue("u_deprevalue",$ar2["MARKETVALUE"]-$ar2["DEPMARKETVALUE"]);
					$obju_Faas3a->setudfvalue("u_remvalue",$ar2["DEPMARKETVALUE"]);
					
					$obju_Faas3a->privatedata["header"] = $obju_Faas3;
					if ($actionReturn) $actionReturn = $obju_Faas3a->add();
					if (!$actionReturn) break;
				}	
	
			}
                    }
			
	
		} elseif ($ar["PROPERTYKIND_CT"]=="L") {
                    if($obju_Faas1->getbykey($ar["TAXTRANS_ID"])){
                        var_dump( $ar["TAXTRANS_ID"]);
                    }else{
                        $num_rows++;
			$obju_Faas1->prepareadd();
			$obju_Faas1->docid = getNextIDByBranch("u_rpfaas1",$objConnection);
			$obju_Faas1->docseries = -1;
			$obju_Faas1->docno = $ar["TAXTRANS_ID"];
			$obju_Faas1->setudfvalue("u_pin",$ar["PINNO"]);
			$obju_Faas1->setudfvalue("u_tdno",$ar["TDNO"]);
			$obju_Faas1->setudfvalue("u_trxcode",$ar["TRANSCODE_CT"]);
	
			$obju_Faas1->setudfvalue("u_tctno",$ar["CERTIFICATETITLENO"]);
			$obju_Faas1->setudfvalue("u_surveyno",$ar["SURVEYNO"]);
			$obju_Faas1->setudfvalue("u_lotno",$ar["LOTNO"]);
			$obju_Faas1->setudfvalue("u_block",$ar["BLKNO"]);
			$obju_Faas1->setudfvalue("u_cadlotno",$ar["CADASTRALLOTNO"]);
	
			$obju_Faas1->setudfvalue("u_street",$ar["STREET"]);
			$obju_Faas1->setudfvalue("u_barangay",strtoupper($ar["BARANGAY"]));
			$obju_Faas1->setudfvalue("u_municipality",$ar["MUNICIPAL"]);
			$obju_Faas1->setudfvalue("u_province",$ar["PROVINCE"]);
	
			$obju_Faas1->setudfvalue("u_north",$ar["BOUNDARYNORTH"]);
			$obju_Faas1->setudfvalue("u_east",$ar["BOUNDARYEAST"]);
			$obju_Faas1->setudfvalue("u_south",$ar["BOUNDARYSOUTH"]);
			$obju_Faas1->setudfvalue("u_west",$ar["BOUNDARYWEST"]);
	
			if ($ar["LASTNAME"]=="") {
				$obju_Faas1->setudfvalue("u_ownertype","C");
				$obju_Faas1->setudfvalue("u_ownercompanyname",$ar["OWNERNAME"]);
			}	
			
			$obju_Faas1->setudfvalue("u_ownerlastname",$ar["LASTNAME"]);
			$obju_Faas1->setudfvalue("u_ownerfirstname",$ar["FIRSTNAME"]);
			$obju_Faas1->setudfvalue("u_ownermiddlename",$ar["MI"]);
			$obju_Faas1->setudfvalue("u_ownername",$ar["OWNERNAME"]);
			$obju_Faas1->setudfvalue("u_ownertin",$ar["LOCAL_TIN"]);
			$obju_Faas1->setudfvalue("u_ownertelno",$ar["TELNO"]);
			$obju_Faas1->setudfvalue("u_owneraddress",$ar["OWNERADDRESS"]);
	
			$obju_Faas1->setudfvalue("u_taxable",$ar["TAXABILITY_BV"]);
			$obju_Faas1->setudfvalue("u_effdate",$ar["STARTYEAR"]."-".$ar["STARTQUARTER"]."-01");
			$obju_Faas1->setudfvalue("u_effqtr",$ar["STARTQUARTER"]);
			$obju_Faas1->setudfvalue("u_effyear",$ar["STARTYEAR"]);
			if ($ar["ENDED_BV"]==1) {
				$obju_Faas1->setudfvalue("u_cancelled",1);
				$obju_Faas1->setudfvalue("u_expdate",$ar["CANCELLATIONDATE"]);
				$obju_Faas1->setudfvalue("u_expqtr",$ar["ENDQUARTER"]);
				$obju_Faas1->setudfvalue("u_expyear",$ar["ENDYEAR"]);
			}	
			$obju_Faas1->setudfvalue("u_recommendby",$ar["RECOMAPPROVEDBY"]);
			$obju_Faas1->setudfvalue("u_recommenddate",$ar["RECOMAPPROVEDDATE"]);
			$obju_Faas1->setudfvalue("u_approvedby",$ar["APPROVEDBY"]);
			$obju_Faas1->setudfvalue("u_approveddate",$ar["APPROVEDDATE"]);
			$obju_Faas1->setudfvalue("u_memoranda",$ar["MEMORANDA"]);
			$obju_Faas1->docstatus = "Approved";
			
			if ($ar["PREVTAXTRANS_ID"]!="") {
				$obju_Faas1->setudfvalue("u_prevarpno",$ar["PREVTAXTRANS_ID"]);
				$odbcres2 = @odbc_exec($odbccon,"SELECT a.TDNO, a.STARTQUARTER, a.STARTYEAR, b.PINNO, d.OWNERNAME
	FROM RPTASSESSMENT a LEFT JOIN PROPERTY b ON b.PROP_ID=a.PROP_ID LEFT JOIN PROPERTYOWNER c ON c.PROP_ID=b.PROP_ID LEFT JOIN TAXPAYER d ON d.LOCAL_TIN=c.LOCAL_TIN WHERE a.TAXTRANS_ID='".$ar["PREVTAXTRANS_ID"]."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
				 if(odbc_fetch_row($odbcres2)) {
				
					//Build tempory
					for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
					   $field_name = odbc_field_name($odbcres2, $j);
					  // $this->temp_fieldnames[$j] = $field_name;
					   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
					}
					
					$obju_Faas1->setudfvalue("u_prevpin",$ar2["PINNO"]);
					$obju_Faas1->setudfvalue("u_prevtdno",$ar2["TDNO"]);
					$obju_Faas1->setudfvalue("u_prevowner",$ar2["OWNERNAME"]);
					if ($ar2["STARTYEAR"]!="") $obju_Faas1->setudfvalue("u_preveffdate",$ar2["STARTYEAR"]."-".$ar2["STARTQUARTER"]."-01");
				}	
				if ($obju_Faas1Prev->getbykey($ar["PREVTAXTRANS_ID"])) {
					$obju_Faas1->setudfvalue("u_prevvalue",$obju_Faas1Prev->getudfvalue("u_assvalue"));
				}
			}		
			$actionReturn = $obju_Faas1->add();
			
			if ($actionReturn) {
				$odbcres2 = @odbc_exec($odbccon,"SELECT a.TAXDECDETAIL_ID, a.TAXTRANS_ID, a.ACTUALUSE_CT, a.MARKETVALUE, a.ASSESSEDVALUE, a.CLASSCODE_CT, a.GRYEAR, a.ASSESSMENTLEVEL, b.LANDAPPRAISAL_ID, b.SFMVLAND_ID, d.DESCRIPTION as CLASSLEVEL, b.SUBCLASS_CT, c.DESCRIPTION AS SUBCLASS, b.UNITVALUE, b.MARKETVALUE as BASEVALUE, b.AREA
	FROM RPTASSESSMENTDETAIL a LEFT JOIN RPTLANDAPPRAISAL b ON b.TAXDECDETAIL_ID=a.TAXDECDETAIL_ID LEFT JOIN T_SUBCLASSIFICATION c ON c.CODE=b.SUBCLASS_CT LEFT JOIN T_CLASSLEVEL d ON d.CODE=b.CLASSLEVEL_CT WHERE a.TAXTRANS_ID='".$obju_Faas1->docno."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
				 while(odbc_fetch_row($odbcres2)) {
				
					//Build tempory
					for ($j = 1; $j <= odbc_num_fields($odbcres2); $j++) {
					   $field_name = odbc_field_name($odbcres2, $j);
					  // $this->temp_fieldnames[$j] = $field_name;
					   $ar2[$field_name] = odbc_result($odbcres2, $field_name) . "";
					}
					
					$obju_Faas1a->prepareadd();
					$obju_Faas1a->docid = getNextIDByBranch("u_rpfaas1a",$objConnection);
					$obju_Faas1a->docno = getNextNoByBranch("u_rpfaas1a",'',$objConnection);
					//var_dump(array($obju_Faas1a->docid,$obju_Faas1a->docno));
					$obju_Faas1a->setudfvalue("u_arpno",$obju_Faas1->docno);
					$obju_Faas1a->setudfvalue("u_class",trim($ar2["CLASSCODE_CT"]));
					
					if ($ar2["SUBCLASS_CT"]!="") {
						$obju_Faas1b->prepareadd();
						$obju_Faas1b->docid = $obju_Faas1a->docid;
						$obju_Faas1b->lineid = getNextIDByBranch("u_rpfaas1b",$objConnection);
						$obju_Faas1b->setudfvalue("u_class",trim($ar2["CLASSLEVEL"]));
						$obju_Faas1b->setudfvalue("u_subclass",trim($ar2["SUBCLASS"]));
						$obju_Faas1b->setudfvalue("u_sqm",floatval($ar2["AREA"]));
						$obju_Faas1b->setudfvalue("u_unitvalue",$ar2["UNITVALUE"]);
						$obju_Faas1b->setudfvalue("u_basevalue",$ar2["BASEVALUE"]);
						
						$actionReturn = $obju_Faas1b->add();
						if (!$actionReturn) break;
					}	
	
				
					$adj=0;
					$odbcres3 = @odbc_exec($odbccon,"SELECT b.DESCRIPTION AS ADJUSTMENTTYPE, c.DESCRIPTION AS ADJUSTMENT, a.PERCENTADJUSTMENT, a.VALUEADJUSTMENT FROM RPTVALUEADJUSTMENT a LEFT JOIN T_ADJUSTMENTTYPE b ON b.CODE=a.ADJUSTMENTTYPE_CT LEFT JOIN T_ADJUSTMENT c ON c.CODE=a.ADJUSTMENT_CT WHERE a.LANDAPPRAISAL_ID='".$ar2["LANDAPPRAISAL_ID"]."'") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
					 while(odbc_fetch_row($odbcres3)) {
					
						//Build tempory
						for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
						   $field_name = odbc_field_name($odbcres3, $j);
						  // $this->temp_fieldnames[$j] = $field_name;
						   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
						}
						
						$obju_Faas1c->prepareadd();
						$obju_Faas1c->docid = $obju_Faas1a->docid;
						$obju_Faas1c->lineid = getNextIDByBranch("u_rpfaas1c",$objConnection);
						
						$obju_Faas1c->setudfvalue("u_adjtype",trim($ar3["ADJUSTMENTTYPE"]));
						$obju_Faas1c->setudfvalue("u_adjfactor",trim($ar3["ADJUSTMENT"]));
						$obju_Faas1c->setudfvalue("u_adjperc",$ar3["PERCENTADJUSTMENT"]*100);
						$obju_Faas1c->setudfvalue("u_adjvalue",$ar3["VALUEADJUSTMENT"]);
						
						$actionReturn = $obju_Faas1c->add();
						if (!$actionReturn) break;
						$adj += $ar3["VALUEADJUSTMENT"];
					}
								
	
					$obju_Faas1a->setudfvalue("u_sqm",floatval($ar2["AREA"]));
					$obju_Faas1a->setudfvalue("u_basevalue",$ar2["MARKETVALUE"]-$adj);
					$obju_Faas1a->setudfvalue("u_adjvalue",$adj);
					$obju_Faas1a->setudfvalue("u_marketvalue",$ar2["MARKETVALUE"]);
					$obju_Faas1a->setudfvalue("u_actualuse",trim($ar2["ACTUALUSE_CT"]));
					$obju_Faas1a->setudfvalue("u_asslvl",$ar2["ASSESSMENTLEVEL"] * 100);
					$obju_Faas1a->setudfvalue("u_assvalue",$ar2["ASSESSEDVALUE"]);
					
					if ($actionReturn) $actionReturn = $obju_Faas1a->add();
					if (!$actionReturn) break;
				}	
	
			} 
                    }
			
		}	
		$i++;
		if (!$actionReturn) break;
		//if ($num_rows>10) break;
	 }
//} 

 if ($objRs->fields[0]==0 && $actionReturn) {
	$odbcres = @odbc_exec($odbccon,"SELECT b.TAXTRANS_ID, b.PROPERTYKIND_CT, MAX(b.TAXYEAR) AS TAXYEAR
FROM PAYMENT a INNER JOIN PAYMENTCLASSDETAIL b ON b.PAYMENT_ID=a.PAYMENT_ID 
where a.STATUS_CT='SAV' and b.itaxtype_ct='BSC' GROUP BY b.TAXTRANS_ID, b.PROPERTYKIND_CT ORDER BY b.taxtrans_id") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
	while(odbc_fetch_row($odbcres)) {	
		//Build tempory
		for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
		   $field_name = odbc_field_name($odbcres, $j);
		  // $this->temp_fieldnames[$j] = $field_name;
		   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
		}
		if ($ar["PROPERTYKIND_CT"]=="L") {
			if ($obju_Faas1->getbykey($ar["TAXTRANS_ID"])) {
				$obju_Faas1->setudfvalue("u_bilyear",$ar["TAXYEAR"]);
				$actionReturn = $obju_Faas1->update($obju_Faas1->docno,$obju_Faas1->rcdversion);
				if (!$actionReturn) break;
			} else echo "Unable to retrieve Land ARP No.[".$ar["TAXTRANS_ID"]."] to update last payment year.<br>";
		} elseif ($ar["PROPERTYKIND_CT"]=="B") {
			if ($obju_Faas2->getbykey($ar["TAXTRANS_ID"])) {
				$obju_Faas2->setudfvalue("u_bilyear",$ar["TAXYEAR"]);
				$actionReturn = $obju_Faas2->update($obju_Faas2->docno,$obju_Faas2->rcdversion);
				if (!$actionReturn) break;
			} else echo "Unable to retrieve BUILDING ARP No.[".$ar["TAXTRANS_ID"]."] to update last payment year.<br>";
		} elseif ($ar["PROPERTYKIND_CT"]=="M") {
			if ($obju_Faas3->getbykey($ar["TAXTRANS_ID"])) {
				$obju_Faas3->setudfvalue("u_bilyear",$ar["TAXYEAR"]);
				$actionReturn = $obju_Faas3->update($obju_Faas3->docno,$obju_Faas3->rcdversion);
				if (!$actionReturn) break;
			} else echo "Unable to retrieve MACHINE ARP No.[".$ar["TAXTRANS_ID"]."] to update last payment year.<br>";
		}
		if (!$actionReturn) break;
	}
 }
 
 
  if ($objRs->fields[0]==0 && $actionReturn) {
 
 	$objRsFees = new recordset(null,$objConnection);
	$objRsFees->queryopen("select A.U_RPPROPTAX AS U_RPPROPTAXFEECODE, D.NAME AS U_RPPROPTAXFEEDESC, A.U_RPSEF AS U_RPSEFFEECODE, B.NAME AS U_RPSEFFEEDESC, A.U_RPIDLELAND AS U_RPIDLELANDFEECODE, C.NAME AS U_RPIDLELANDFEEDESC, D.U_PENALTYCODE AS U_RPPROPTAXPENALTYFEECODE, E.NAME AS U_RPPROPTAXPENALTYFEEDESC, B.U_PENALTYCODE AS U_RPSEFPENALTYFEECODE, F.NAME AS U_RPSEFPENALTYFEEDESC from U_LGUSETUP A 
							LEFT JOIN U_LGUFEES B ON B.CODE=A.U_RPSEF
							LEFT JOIN U_LGUFEES C ON C.CODE=A.U_RPIDLELAND
							LEFT JOIN U_LGUFEES D ON D.CODE=A.U_RPPROPTAX
							LEFT JOIN U_LGUFEES E ON E.CODE=D.U_PENALTYCODE
							LEFT JOIN U_LGUFEES F ON F.CODE=B.U_PENALTYCODE");
	if (!$objRsFees->queryfetchrow("NAME")) {
		return raiseError("No setup found for real property tax fees.");
	}	
	
	$odbcres = @odbc_exec($odbccon,"SELECT a.PAYMENT_ID, a.LOCAL_TIN, a.PAIDBY, a.PAYMENTDATE, a.VALUEDATE, a.AMOUNT, a.PAYMODE_CT, a.CHECKNO, a.RECEIPTNO, a.PRINT_BV, a.STATUS_CT, a.REMARK, a.USERID, a.TRANSDATE, a.PAYGROUP_CT, a.ORB_ID, a.DCL_BV, a.RCDNUMBER, a.AFTYPE
FROM PAYMENT a where PAYMENTDATE>='2017-01-01' AND PAYMODE_CT='CSH' AND STATUS_CT='SAV' ORDER BY PAYMENTDATE, RECEIPTNO") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
	while(odbc_fetch_row($odbcres)) {	
		//Build tempory
		for ($j = 1; $j <= odbc_num_fields($odbcres); $j++) {
		   $field_name = odbc_field_name($odbcres, $j);
		  // $this->temp_fieldnames[$j] = $field_name;
		   $ar[$field_name] = odbc_result($odbcres, $field_name) . "";
		}
		//if (!$obju_Pos->getbykey($ar["RECEIPTNO"])) {
			$num_rows++;
			//var_dump($ar["PAYMENT_ID"]);
			$obju_Taxes->prepareadd();
			$obju_Taxes->docseries = -1;
			$obju_Taxes->docno = getNextNoByBranch("u_lgubills",'',$objConnection);
			$obju_Taxes->docid = getNextIDByBranch("u_rptaxes",$objConnection);
			$obju_Taxes->docstatus = "C";
			$obju_Taxes->setudfvalue("u_migrated",1);
			$obju_Taxes->setudfvalue("u_paymode","A");
			$obju_Taxes->setudfvalue("u_tin",$ar["LOCAL_TIN"]);
			$obju_Taxes->setudfvalue("u_declaredowner",$ar["PAIDBY"]);
			$obju_Taxes->setudfvalue("u_assdate",$ar["PAYMENTDATE"]);
			
			
			$tax=0;
			$penalty=0;
			$sef=0;
			$sefpenalty=0;
			$taxdisc=0;
			$sefdisc=0;
			$total=0;
			$odbcres3 = @odbc_exec($odbccon,"SELECT a.PAYMENT_ID, a.PROPERTYKIND_CT, c.PINNO, a.TAXTRANS_ID, b.TDNO, a.TAXYEAR, SUM(IIF(a.ITAXTYPE_CT='BSC' AND a.CASETYPE_CT='REG',a.AMOUNT,0)) AS TAX, SUM(IIF(a.ITAXTYPE_CT='BSC' AND a.CASETYPE_CT='PEN',a.AMOUNT,0)) AS TAXPEN, SUM(IIF(a.ITAXTYPE_CT='BSC' AND a.CASETYPE_CT='DED',a.AMOUNT,0)) AS TAXDISC,
 SUM(IIF(a.ITAXTYPE_CT='SEF' AND a.CASETYPE_CT='REG',a.AMOUNT,0)) AS SEF, SUM(IIF(a.ITAXTYPE_CT='SEF' AND a.CASETYPE_CT='PEN',a.AMOUNT,0)) AS SEFPEN, SUM(IIF(a.ITAXTYPE_CT='SEF' AND a.CASETYPE_CT='DED',a.AMOUNT,0)) AS SEFDISC
FROM PAYMENTCLASSDETAIL a LEFT JOIN RPTASSESSMENT b ON b.TAXTRANS_ID=a.TAXTRANS_ID LEFT JOIN PROPERTY c ON c.PROP_ID=b.PROP_ID WHERE a.PAYMENT_ID='".$ar["PAYMENT_ID"]."' GROUP BY PAYMENT_ID, a.PROPERTYKIND_CT, c.PINNO, a.TAXTRANS_ID, b.TDNO, a.TAXYEAR") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
			 while(odbc_fetch_row($odbcres3)) {
				//Build tempory
				for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
				   $field_name = odbc_field_name($odbcres3, $j);
				  // $this->temp_fieldnames[$j] = $field_name;
				   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
				}
				
				$obju_Taxes->setudfvalue("u_yearfrom",$ar3["TAXYEAR"]);
				$obju_Taxes->setudfvalue("u_yearto",$ar3["TAXYEAR"]);
			
				//if ($ar["PAYMENT_ID"]==21297) var_dump( $ar3);
				$obju_TaxArps->prepareadd();
				$obju_TaxArps->docid = $obju_Taxes->docid;
				$obju_TaxArps->lineid = getNextIDByBranch("u_rptaxarps",$objConnection);
				$obju_TaxArps->setudfvalue("u_selected",1);
				$obju_TaxArps->setudfvalue("u_kind",iif($ar3["PROPERTYKIND_CT"]=="L","LAND",iif($ar3["PROPERTYKIND_CT"]=="B","BUILDING","MACHINERY")));
				$obju_TaxArps->setudfvalue("u_pinno",$ar3["PINNO"]);
				$obju_TaxArps->setudfvalue("u_arpno",$ar3["TAXTRANS_ID"]);
				$obju_TaxArps->setudfvalue("u_tdno",$ar3["TDNO"]);
				$obju_TaxArps->setudfvalue("u_assvalue",0);
				$obju_TaxArps->setudfvalue("u_yrfr",$ar3["TAXYEAR"]);
				$obju_TaxArps->setudfvalue("u_yrto",$ar3["TAXYEAR"]);
				$obju_TaxArps->setudfvalue("u_taxdue",$ar3["TAX"]);
				$obju_TaxArps->setudfvalue("u_penalty",$ar3["TAXPEN"]);
				$obju_TaxArps->setudfvalue("u_billdate",$ar["PAYMENTDATE"]);
				$obju_TaxArps->setudfvalue("u_sef",$ar3["SEF"]);
				$obju_TaxArps->setudfvalue("u_sefpenalty",$ar3["SEFPEN"]);
				$obju_TaxArps->setudfvalue("u_discperc",($ar3["TAXDISC"]/$ar3["TAX"])*100);
				$obju_TaxArps->setudfvalue("u_taxdisc",$ar3["TAXDISC"]*-1);
				$obju_TaxArps->setudfvalue("u_sefdisc",$ar3["SEFDISC"]*-1);
				$obju_TaxArps->setudfvalue("u_taxtotal",$ar3["TAX"]+$ar3["TAXDISC"]+$ar3["TAXPEN"]);
				$obju_TaxArps->setudfvalue("u_seftotal",$ar3["SEF"]+$ar3["SEFDISC"]+$ar3["SEFPEN"]);
				$tax+=$ar3["TAX"];
				$penalty+=$ar3["TAXPEN"];
				$sef+=$ar3["SEF"];
				$sefpenalty+=$ar3["SEFPEN"];
				$taxdisc+=$ar3["TAXDISC"]*-1;
				$sefdisc+=$ar3["SEFDISC"]*-1;
				$total+=$ar3["TAX"]-$ar3["TAXDISC"]+$ar3["TAXPEN"]+$ar3["SEF"]+$ar3["SEFDISC"]+$ar3["SEFPEN"];
				$obju_TaxArps->privatedata["header"] = $obju_Taxes;
				$actionReturn = $obju_TaxArps->add();
				if (!$actionReturn) break;			
				
				$obju_Taxes->setudfvalue("u_yearfrom",$ar["TAXYEAR"]);
				$obju_Taxes->setudfvalue("u_yearto",$ar["TAXYEAR"]);
					

				
			}
			if (!$actionReturn) break;			

			$obju_Taxes->setudfvalue("u_tax",$tax);
			$obju_Taxes->setudfvalue("u_seftax",$sef);
			$obju_Taxes->setudfvalue("u_discamount",$taxdisc);
			$obju_Taxes->setudfvalue("u_sefdiscamount",$sefdisc);
			$obju_Taxes->setudfvalue("u_penalty",$penalty);
			$obju_Taxes->setudfvalue("u_sefpenalty",$sefpenalty);
			$obju_Taxes->setudfvalue("u_totaltaxamount",$total);
			if ($ar["PAYMENT_ID"]==21297) var_dump(array($tax,$penalty,$sef,$sefpenalty,$taxdisc,$sefdisc,$total));
			if ($obju_Taxes->getudfvalue("u_totaltaxamount")!=$total) {
					$actionReturn = raiseError("Mismatch Payment Form Total [".$obju_Taxes->getudfvalue("u_totaltaxamount")."] and Payment details total [".$totalamount."] for Payment ID [".$obju_Pos->docno."].");
			}
			$actionReturn = $obju_Taxes->add();
				
			if (!$actionReturn) break;
			
			
			$obju_Pos->prepareadd();
			$obju_Pos->docseries = -1;
			$obju_Pos->docno = "ITAX:".$ar["PAYMENT_ID"];;
			$obju_Pos->setudfvalue("u_orno",$ar["RECEIPTNO"]);
			$obju_Pos->docid = getNextIDByBranch("u_lgupos",$objConnection);
			$obju_Pos->setudfvalue("u_custno",$ar["LOCAL_TIN"]);
			$obju_Pos->setudfvalue("u_custname",$ar["PAIDBY"]);
			$obju_Pos->setudfvalue("u_status",iif($ar["STATUS_CT"]=="CNL","CN","C"));
			$obju_Pos->setudfvalue("u_date",$ar["PAYMENTDATE"]);
			$obju_Pos->setudfvalue("u_paidamount",$ar["AMOUNT"]);
			$obju_Pos->setudfvalue("u_totalamount",$ar["AMOUNT"]);
			$obju_Pos->setudfvalue("u_totalbefdisc",$ar["AMOUNT"]);
			$obju_Pos->setudfvalue("u_trxtype","S");
			$obju_Pos->setudfvalue("u_cashamount",iif($ar["PAYMODE_CT"]=="CSH",$ar["AMOUNT"],0));
			$obju_Pos->setudfvalue("u_chequeamount",iif($ar["PAYMODE_CT"]!="CSH",$ar["AMOUNT"],0));
			$obju_Pos->setudfvalue("u_collectedcashamount",iif($ar["PAYMODE_CT"]=="CSH",$ar["AMOUNT"],0));
			$obju_Pos->setudfvalue("u_profitcenter","RP");
			$obju_Pos->setudfvalue("u_module","Real Property Tax");
			$obju_Pos->setudfvalue("u_paymode",$obju_Taxes->getudfvalue("u_paymode"));
			if ($obju_Taxes->getudfvalue("u_totaltaxamount")>0) {
				$obju_Pos->setudfvalue("u_billno",$obju_Taxes->docno);
				$obju_Pos->setudfvalue("u_billdate",$obju_Taxes->getudfvalue("u_assdate"));
				$obju_Pos->setudfvalue("u_billduedate",$obju_Taxes->getudfvalue("u_assdate"));
			}	
			$obju_Pos->setudfvalue("u_doccnt",1);
			//$obju_Pos->setudfvalue("u_remark",$ar["REMARK"]);

			$totalamount=0;
			$odbcres3 = @odbc_exec($odbccon,"SELECT a.PAYMENT_ID, a.ITAXTYPE_CT, SUM(a.AMOUNTPAID) AS AMOUNTPAID, SUM(IIF(a.CASETYPE_CT='PEN',0,a.AMOUNTPAID)) AS TAX, SUM(IIF(a.CASETYPE_CT='PEN',a.AMOUNTPAID,0)) AS PEN
FROM PAYMENTDETAIL a WHERE a.PAYMENT_ID='".$ar["PAYMENT_ID"]."' GROUP BY PAYMENT_ID, ITAXTYPE_CT") or die("<B>Error!</B> Couldn't Run Query:<br><br>" . $query . "<br><br>Error Code:  ".odbc_error());
			 while(odbc_fetch_row($odbcres3)) {
			
				//Build tempory
				for ($j = 1; $j <= odbc_num_fields($odbcres3); $j++) {
				   $field_name = odbc_field_name($odbcres3, $j);
				  // $this->temp_fieldnames[$j] = $field_name;
				   $ar3[$field_name] = odbc_result($odbcres3, $field_name) . "";
				}
				
				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="BSC" && $ar3["TAX"]>0) {
					if ($objRsFees->fields["U_RPPROPTAXFEECODE"]=="") return raiseError("No setup found for Real Property Tax.");
					$obju_PosItems->prepareadd();
					$obju_PosItems->docid = $obju_Pos->docid;
					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPPROPTAXFEECODE"]);
					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPPROPTAXFEEDESC"]);
					$obju_PosItems->setudfvalue("u_quantity",1);
					$obju_PosItems->setudfvalue("u_unitprice",$ar3["TAX"]);
					$obju_PosItems->setudfvalue("u_price",$ar3["TAX"]);
					$obju_PosItems->setudfvalue("u_linetotal",$ar3["TAX"]);
					$totalamount+=$ar3["TAX"];
					$obju_PosItems->privatedata["header"] = $obju_Pos;
					$actionReturn = $obju_PosItems->add();
				

                                }	
				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="SEF" && $ar3["TAX"]>0) {
					if ($objRsFees->fields["U_RPSEFFEECODE"]=="") return raiseError("No setup found for SEF.");
					$obju_PosItems->prepareadd();
					$obju_PosItems->docid = $obju_Pos->docid;
					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPSEFFEECODE"]);
					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPSEFFEEDESC"]);
					$obju_PosItems->setudfvalue("u_quantity",1);
					$obju_PosItems->setudfvalue("u_unitprice",$ar3["TAX"]);
					$obju_PosItems->setudfvalue("u_price",$ar3["TAX"]);
					$obju_PosItems->setudfvalue("u_linetotal",$ar3["TAX"]);
					$totalamount+=$ar3["TAX"];
					$obju_PosItems->privatedata["header"] = $obju_Pos;
				
                                    $actionReturn = $obju_PosItems->add();
				}	

				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="BSC" && $ar3["PEN"]>0) {
					if ($objRsFees->fields["U_RPPROPTAXPENALTYFEECODE"]=="") return raiseError("No setup found for Real Property Tax - Penalty.");
					$obju_PosItems->prepareadd();
					$obju_PosItems->docid = $obju_Pos->docid;
					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPPROPTAXPENALTYFEECODE"]);
					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPPROPTAXPENALTYFEEDESC"]);
					$obju_PosItems->setudfvalue("u_quantity",1);
					$obju_PosItems->setudfvalue("u_unitprice",$ar3["PEN"]);
					$obju_PosItems->setudfvalue("u_price",$ar3["PEN"]);
					$obju_PosItems->setudfvalue("u_linetotal",$ar3["PEN"]);
					$totalamount+=$ar3["PEN"];
					$obju_PosItems->privatedata["header"] = $obju_Pos;
					$actionReturn = $obju_PosItems->add();

                                        }	
				if ($actionReturn && $ar3["ITAXTYPE_CT"]=="SEF" && $ar3["PEN"]>0) {
					if ($objRsFees->fields["U_RPSEFPENALTYFEECODE"]=="") return raiseError("No setup found for SEF - Penalty.");
					$obju_PosItems->prepareadd();
					$obju_PosItems->docid = $obju_Pos->docid;
					$obju_PosItems->lineid = getNextIDByBranch("u_lgupositems",$objConnection);
					$obju_PosItems->setudfvalue("u_itemcode",$objRsFees->fields["U_RPSEFPENALTYFEECODE"]);
					$obju_PosItems->setudfvalue("u_itemdesc",$objRsFees->fields["U_RPSEFPENALTYFEEDESC"]);
					$obju_PosItems->setudfvalue("u_quantity",1);
					$obju_PosItems->setudfvalue("u_unitprice",$ar3["PEN"]);
					$obju_PosItems->setudfvalue("u_price",$ar3["PEN"]);
					$obju_PosItems->setudfvalue("u_linetotal",$ar3["PEN"]);
					$totalamount+=$ar3["PEN"];
					$obju_PosItems->privatedata["header"] = $obju_Pos;
					$actionReturn = $obju_PosItems->add();

                                        }	
				
			}
			if (!$actionReturn) break;			
			
			if ($obju_Pos->getudfvalue("u_totalamount")!=$totalamount) {
					$actionReturn = raiseError("Mismatch Header Total [".$obju_Pos->getudfvalue("u_totalamount")."] and Payment details total [".$totalamount."] for Payment ID [".$obju_Pos->docno."].");
			}
			$actionReturn = $obju_Pos->add();

                        if (!$actionReturn) break;

		//} else {
			//echo $ar["RECEIPTNO"] ."</br>";
                        	 //var_dump($actionReturn);
		}	

 }

}
       

 if ($actionReturn) {
 	$objConnection->commit();
        var_dump('wilson');
 	var_dump($num_rows);
 } else {
 	$objConnection->rollback();
        echo $_SESSION["errormessage"];
       
}	
?>