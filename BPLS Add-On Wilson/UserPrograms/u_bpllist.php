<?php
	$progid = "u_bpllist";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/grid.php");
	include_once("../common/classes/recordset.php");
	include_once("./schema/bankdeposits.php");
	include_once("./schema/paymentcheques.php");
	include_once("./classes/payments.php");
	include_once("./classes/paymentcheques.php");
	include_once("./sls/countries.php");
	include_once("./sls/banks.php");
	include_once("./sls/housebankaccounts.php");
	include_once("./classes/documentschema_br.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./boschema/checkprinting.php");
	include_once("./utils/businessobjects.php");
	include_once("./sls/enumdocstatus.php");

	
	$expired=false;
	$match=false;
	$matchstatus="";
	$search=false;
	$retired = false;
	$page->restoreSavedValues();	

	unset($enumdocstatus["D"],$enumdocstatus["O"],$enumdocstatus["C"],$enumdocstatus["CN"]);
	if ($page->getitemstring("opt")!="Print") {
		$enumdocstatus["Encoding"]="Encoding";
		//$enumdocstatus["Assessing"]="Assessing";
		//$enumdocstatus["Assessed"]="Assessed";
		$enumdocstatus["Approved"]="Approved";
		$enumdocstatus["Disapproved"]="Disapproved";
		$enumdocstatus["Expired"]="Expired";
                $enumdocstatus["Retired"]="Retired";
	}	
	$enumdocstatus["Printing"]="Printing";
	$enumdocstatus["Paid"]="Paid";
        
        $enumviewby = array();
	$enumviewby["Acctno"]="Account No";
	$enumviewby["Applications"]="Applications";
	$enumviewby["OnlineApplications"]="Online Applications";
	
	$page->objectcode = "U_BPLLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_bpllist";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Business Permit Application";
        $page->reportlayouts = true;
	$page->getsettings();
        $page->settings->setschema($boschema_checkprinting);
	

	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["acctno"] = createSchemaUpper("acctno");
	$schema["u_businesskind"] = createSchemaUpper("u_businesskind");
	$schema["u_businesschar"] = createSchemaUpper("u_businesschar");
	$schema["u_taxclass"] = createSchemaUpper("u_taxclass");
	$schema["u_businesscategory"] = createSchemaUpper("u_businesscategory");
	$schema["u_businessname"] = createSchemaUpper("u_businessname");
	$schema["viewby"] = createSchemaUpper("viewby");
	$schema["u_businessline"] = createSchemaUpper("u_businessline");
	$schema["u_ownername"] = createSchemaUpper("u_ownername");
	$schema["u_lastname"] = createSchemaUpper("u_lastname");
	$schema["u_firstname"] = createSchemaUpper("u_firstname");
	$schema["u_middlename"] = createSchemaUpper("u_middlename");
	$schema["u_appdatefr"] = createSchemaDate("u_appdatefr");
	$schema["u_appdateto"] = createSchemaDate("u_appdateto");
        $schema["u_bunitno"] = createSchemaUpper("u_bunitno");
	$schema["u_bstreet"] = createSchemaUpper("u_bstreet");
	$schema["u_bblock"] = createSchemaUpper("u_bblock");
        $schema["u_bfloorno"] = createSchemaUpper("u_bfloorno");
	$schema["u_bvillage"] = createSchemaUpper("u_bvillage");
	$schema["u_bbrgy"] = createSchemaUpper("u_bbrgy");
	$schema["u_bbldgname"] = createSchemaUpper("u_bbldgname");
	$schema["u_baddressno"] = createSchemaUpper("u_baddressno");
	$schema["u_bphaseno"] = createSchemaUpper("u_bphaseno");
	$schema["u_blotno"] = createSchemaUpper("u_blotno");
	$schema["u_corpname"] = createSchemaUpper("u_corpname");
//	$schema["u_birthdate"]["cfl"] = "Calendar";
	
	$objGrid = new grid("T1",$httpVars);
        
        $objGrid->addcolumn("indicator");
        $objGrid->addcolumn("action");
	$objGrid->addcolumn("docno");
	$objGrid->addcolumn("u_appno");
	$objGrid->addcolumn("u_appdate");
	$objGrid->addcolumn("u_businessname");
	$objGrid->addcolumn("u_corpname");
	$objGrid->addcolumn("u_lastname");
	$objGrid->addcolumn("u_firstname");
	$objGrid->addcolumn("u_middlename");
	$objGrid->addcolumn("u_businessaddress");
	$objGrid->addcolumn("u_apprefno");
	$objGrid->addcolumn("docstatus");
        
	$objGrid->columntitle("indicator","");
	$objGrid->columntitle("docno","Reference No.");
	$objGrid->columntitle("u_appno","Account No.");
	$objGrid->columntitle("u_appdate","Date");
	$objGrid->columntitle("u_businessname","Business Name");
	$objGrid->columntitle("u_corpname","Corporation Name");
	$objGrid->columntitle("u_lastname","Last Name");
	$objGrid->columntitle("u_firstname","First Name");
	$objGrid->columntitle("u_middlename","Middle Name");
	$objGrid->columntitle("u_businessaddress","Business Address");
	$objGrid->columntitle("docstatus","Status");
        
	$objGrid->columnwidth("indicator",2);
	$objGrid->columnwidth("docno",14);
	$objGrid->columnwidth("u_appno",14);
	$objGrid->columnwidth("u_appdate",10);
	$objGrid->columnwidth("u_businessname",50);
	$objGrid->columnwidth("u_corpname",50);
	$objGrid->columnwidth("u_lastname",15);
	$objGrid->columnwidth("u_firstname",15);
	$objGrid->columnwidth("u_middlename",15);
	$objGrid->columnwidth("u_businessaddress",50);
	$objGrid->columnwidth("docstatus",12);
        
	$objGrid->columnsortable("docno",true);
	$objGrid->columnsortable("u_appno",true);
	$objGrid->columnsortable("u_appdate",true);
	$objGrid->columnsortable("u_businessname",true);
	$objGrid->columnsortable("u_corpname",true);
	$objGrid->columnsortable("u_lastname",true);
	$objGrid->columnsortable("u_firstname",true);
	$objGrid->columnsortable("u_middlename",true);
	$objGrid->columnsortable("docstatus",true);
	$objGrid->columnvisibility("u_apprefno",false);
	$objGrid->columnvisibility("indicator",true);
        
        $objGrid->columnalignment("indicator","center");
        
	$objGrid->addcolumn("print");
	$objGrid->columntitle("print","");
	$objGrid->columnalignment("print","center");
	$objGrid->columninput("print","type","link");
	$objGrid->columninput("print","caption","Print");
	$objGrid->columnwidth("print",5);
	$objGrid->columnvisibility("print",false);
        
//	$objGrid->addcolumn("action2");
//	$objGrid->columntitle("action2","*");
//	$objGrid->columnalignment("action2","center");
//	$objGrid->columninput("action2","type","link");
//	$objGrid->columninput("action2","caption","");
//	$objGrid->columnwidth("action2",15);
//	$objGrid->columnvisibility("action2",false);
        
	$objGrid->columntitle("action","*");
	$objGrid->columnalignment("action","center");
	$objGrid->columninput("action","type","link");
	$objGrid->columninput("action","caption","");
	$objGrid->columnwidth("action",15);
	$objGrid->columnvisibility("action",false);
        
	//$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hispatients()");
	$objGrid->automanagecolumnwidth = false;
        $objGrid->selectionmode = 2;
	
        function loadenumsearchby($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumviewby;
		reset($enumviewby);
		while (list($key, $val) = each($enumviewby)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}
        if ($page->getitemstring("u_ownername") != "") {
            $lookupSortBy = "U_LASTNAME,U_FIRSTNAME ";
        } else {
            $lookupSortBy = "";
        }
       
	if ($lookupSortBy == "") {
		$lookupSortBy = "NAME, U_YEAR DESC ";
	} else	$lookupSortBy = strtolower($lookupSortBy);
	$objGrid->setsort($lookupSortBy,$lookupSortAs);
		
	
	if ($page->getitemstring("opt")=="Print") {
		if (!isset($httpVars["df_docstatus"])) {
			$page->setitem("docstatus","Printing");
		}
		//$schema["docstatus"]["attributes"]="disabled";
		$objGrid->columnvisibility("docstatus",false);
		$objGrid->columnvisibility("indicator",false);
		$objGrid->columnvisibility("print",true);
	
	} else {
		$objGrid->columnvisibility("action",true);
                $objGrid->columnvisibility("action2",true);
	}

	function onFormAction($action) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		$keys = explode("`",$page->getkey("keys"));
		$obju_BPLApps = new documentschema_br(null,$objConnections,"u_bplapps"); 
		$objConnection->beginwork();
		if ($obju_BPLApps->getbykey($keys[0])) {
				$obju_BPLApps->docstatus = "Paid";
				$obju_BPLApps->setudfvalue("u_printed",1);
				$obju_BPLApps->setudfvalue("u_printeddatetime",date('Y-m-d h:i:s'));
				$actionReturn = $obju_BPLApps->update($obju_BPLApps->docno,$obju_BPLApps->rcdversion);
		} else $actionReturn = raiseError("Unable to find Application No. [".$keys[0]."]");
		if ($actionReturn) {
			$objConnection->commit();
		} else {
			$objConnection->rollback();
		}
		return $actionReturn;
	}
		
	require("./inc/formactions.php");
	
	$filterExp = "";
	
	
	$filterExp = genSQLFilterDate("B.U_APPDATE",$filterExp,$httpVars['df_u_appdatefr'],$httpVars['df_u_appdateto']);
	if ($page->getitemstring("docstatus")=="Expired") {
		$filterExp = genSQLFilterNumeric("A.U_YEAR",$filterExp,date('Y'),null,"<");
	}else if($page->getitemstring("docstatus")=="Retired") {
            $filterExp = genSQLFilterString("B.U_RETIRED",$filterExp,1);
        }else {
		$filterExp = genSQLFilterString("B.DOCSTATUS",$filterExp,$httpVars['df_docstatus']);
	}
        
	$filterExp = genSQLFilterString("B.DOCNO",$filterExp,$httpVars['df_docno']);
	$filterExp = genSQLFilterString("B.U_APPNO",$filterExp,$httpVars['df_acctno']);
	//$filterExp = genSQLFilterString("B.U_BUSINESSKIND",$filterExp,$httpVars['df_u_businesskind']);
	$filterExp = genSQLFilterString("B.U_TAXCLASS",$filterExp,$httpVars['df_u_taxclass']);
	$filterExp = genSQLFilterString("B.U_BUSINESSCHAR",$filterExp,$httpVars['df_u_businesschar']);
	$filterExp = genSQLFilterString("B.U_CORPNAME",$filterExp,$httpVars['df_u_corpname'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BUSINESSCATEGORY",$filterExp,$httpVars['df_u_businesscategory']);
	
	$filterExp = genSQLFilterString("CONCAT(A.U_LASTNAME,' ',A.U_FIRSTNAME,' ',A.U_MIDDLENAME)",$filterExp,$httpVars['df_u_ownername'],null,null,true);
//	$filterExp = genSQLFilterString("A.U_FIRSTNAME",$filterExp,$httpVars['df_u_ownername'],null,null,true);
//	$filterExp = genSQLFilterString("A.U_MIDDLENAME",$filterExp,$httpVars['df_u_ownername'],null,null,true);
	$filterExp = genSQLFilterString("C.U_BUSINESSLINE",$filterExp,$httpVars['df_u_businessline'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BUNITNO",$filterExp,$httpVars['df_u_bunitno'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BSTREET",$filterExp,$httpVars['df_u_bstreet'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BFLOORNO",$filterExp,$httpVars['df_u_bfloorno'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BBLOCK",$filterExp,$httpVars['df_u_bblock'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BVILLAGE",$filterExp,$httpVars['df_u_bvillage'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BBRGY",$filterExp,$httpVars['df_u_bbrgy'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BBLDGNAME",$filterExp,$httpVars['df_u_bbldgname'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BADDRESSNO",$filterExp,$httpVars['df_u_baddressno'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BPHASENO",$filterExp,$httpVars['df_u_bphaseno'],null,null,true);
	$filterExp = genSQLFilterString("B.U_BLOTNO",$filterExp,$httpVars['df_u_blotno'],null,null,true);
        
        if($page->getitemstring("viewby")=="Acctno" || $page->getitemstring("viewby")=="") $filterExp = genSQLFilterString("A.NAME",$filterExp,$httpVars['df_u_businessname'],null,null,true);
        else $filterExp = genSQLFilterString("B.U_BUSINESSNAME",$filterExp,$httpVars['df_u_businessname'],null,null,true);
	//if ($filterExp!="") $filterExp = "(".str_replace(" AND "," OR ",$filterExp).")";
	
	if ($filterExp !="") $filterExp = " AND " . $filterExp;
        
	$objrs = new recordset(null,$objConnection);
      
	if ($page->getitemstring("u_businessname")!="") {
		$search=true;
	}
//        var_dump($filterExp);
	if ($page->getitemstring("u_businessname")!="" || $page->getitemstring("u_ownername")!="" || $page->getitemstring("u_corpname")!="" || $page->getitemstring("u_appdatefr")!="" || $page->getitemstring("u_appdateto")!="" || $page->getitemstring("acctno")!="" || $page->getitemstring("docno")!="" || $page->getitemstring("u_bunitno")!="" || $page->getitemstring("u_bfloorno")!="" || $page->getitemstring("u_bvillage")!=""  || $page->getitemstring("u_bstreet")!=""  || $page->getitemstring("u_bblock")!=""  || $page->getitemstring("u_bbrgy")!="" || $page->getitemstring("u_bbldgname")!="" || $page->getitemstring("u_baddressno")!="" || $page->getitemstring("u_bphaseno")!="" || $page->getitemstring("u_blotno")!="") {
            if($page->getitemstring("viewby")=="Acctno" || $page->getitemstring("viewby")==""){
                $objrs->queryopenext("SELECT    A.U_APPREFNO AS U_APPNOMDS,GROUP_CONCAT(C.U_BUSINESSLINE) AS U_BUSINESSLINE,B.U_APPNO,B.U_RETIRED,B.U_ONHOLD,ifnull(B.DOCNO,A.CODE) AS CODE, ifnull(B.U_APPDATE,A.U_APPDATE) as U_APPDATE, A.NAME, A.U_LASTNAME, A.U_FIRSTNAME, A.U_MIDDLENAME, ifnull(B.U_YEAR,A.U_YEAR) as U_YEAR, B.DOCSTATUS, A.U_APPREFNO,B.U_CORPNAME,
                                                CONCAT(
                                                IF(IFNULL(b.u_baddressno,'')='','',CONCAT(b.u_baddressno,', ')),
                                                IF(IFNULL(b.u_bbldgno,'') = '','',CONCAT(b.u_bbldgno,' ')),
                                                IF(IFNULL(b.u_bunitno,'') = '','',CONCAT(b.u_bunitno,', ')),
                                                IF(IFNULL(b.u_bfloorno,'') = '','',CONCAT(b.u_bfloorno,', ')),
                                                IF(IFNULL(b.u_bbldgname,'') = '','',CONCAT(b.u_bbldgname,', ')),
                                                IF(IFNULL(b.u_bblock,'')='','',CONCAT('BLK ',b.u_bblock,' ')),
                                                IF(IFNULL(b.u_blotno,'')='','',CONCAT('LOT ',b.u_blotno,' ')),
                                                IF(IFNULL(b.u_bphaseno,'')='','',CONCAT('PHASE ',b.u_bphaseno,', ')),
                                                IF(IFNULL(b.u_bstreet,'')='','',CONCAT(b.u_bstreet,', ')),
                                                IF(IFNULL(b.u_bvillage,'')='','',CONCAT(b.u_bvillage,', ')),
                                                IF(IFNULL(b.u_bbrgy,'')='','',CONCAT(b.u_bbrgy,', ')),
                                                IF(IFNULL(b.u_bcity,'')='','',CONCAT(b.u_bcity,', ')),
                                                IF(IFNULL(b.u_bprovince,'')='','',CONCAT(b.u_bprovince,' ')),
                                                IF(IFNULL(b.u_blandmark,'')='','',CONCAT(' (',b.u_blandmark,')'))
                                              ) AS BUSINESSADDRESS   
                                     from U_BPLMDS A LEFT JOIN U_BPLAPPS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCNO=A.U_APPREFNO LEFT JOIN U_BPLAPPLINES AS C ON B.COMPANY = C.COMPANY AND B.BRANCH = C.BRANCH AND B.DOCID = C.DOCID WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' $filterExp  GROUP BY a.code,C.DOCID", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
            }else{
                $objrs->queryopenext("SELECT    A.U_APPREFNO AS U_APPNOMDS, GROUP_CONCAT(C.U_BUSINESSLINE) AS U_BUSINESSLINE,B.U_APPNO,B.U_RETIRED,B.U_ONHOLD,B.DOCNO AS CODE, B.U_APPDATE as U_APPDATE, B.U_BUSINESSNAME AS NAME, B.U_LASTNAME, B.U_FIRSTNAME, B.U_MIDDLENAME, B.U_YEAR as U_YEAR, B.DOCSTATUS, b.DOCNO AS U_APPREFNO,B.U_CORPNAME, 
                                                CONCAT(
                                                IF(IFNULL(b.u_baddressno,'')='','',CONCAT(b.u_baddressno,', ')),
                                                IF(IFNULL(b.u_bbldgno,'') = '','',CONCAT(b.u_bbldgno,' ')),
                                                IF(IFNULL(b.u_bunitno,'') = '','',CONCAT(b.u_bunitno,', ')),
                                                IF(IFNULL(b.u_bfloorno,'') = '','',CONCAT(b.u_bfloorno,', ')),
                                                IF(IFNULL(b.u_bbldgname,'') = '','',CONCAT(b.u_bbldgname,', ')),
                                                IF(IFNULL(b.u_bblock,'')='','',CONCAT('BLK ',b.u_bblock,' ')),
                                                IF(IFNULL(b.u_blotno,'')='','',CONCAT('LOT ',b.u_blotno,' ')),
                                                IF(IFNULL(b.u_bphaseno,'')='','',CONCAT('PHASE ',b.u_bphaseno,', ')),
                                                IF(IFNULL(b.u_bstreet,'')='','',CONCAT(b.u_bstreet,', ')),
                                                IF(IFNULL(b.u_bvillage,'')='','',CONCAT(b.u_bvillage,', ')),
                                                IF(IFNULL(b.u_bbrgy,'')='','',CONCAT(b.u_bbrgy,', ')),
                                                IF(IFNULL(b.u_bcity,'')='','',CONCAT(b.u_bcity,', ')),
                                                IF(IFNULL(b.u_bprovince,'')='','',CONCAT(b.u_bprovince,' ')),
                                                IF(IFNULL(b.u_blandmark,'')='','',CONCAT(' (',b.u_blandmark,')'))
                                              ) AS BUSINESSADDRESS
                                      from U_BPLAPPS B  INNER JOIN U_BPLMDS A ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND  B.U_APPNO = A.CODE LEFT JOIN U_BPLAPPLINES AS C ON B.COMPANY = C.COMPANY AND B.BRANCH = C.BRANCH AND B.DOCID = C.DOCID WHERE B.COMPANY = '$company' AND B.BRANCH = '$branch' $filterExp GROUP BY B.DOCNO,C.DOCID", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
            }
            if ($page->getitemstring("viewby")=="OnlineApplications"){
                 $objrs->queryopenext("SELECT    A.U_APPREFNO AS U_APPNOMDS,GROUP_CONCAT(C.U_BUSINESSLINE) AS U_BUSINESSLINE,B.U_APPNO,B.U_RETIRED,B.U_ONHOLD,ifnull(B.DOCNO,A.CODE) AS CODE, ifnull(B.U_APPDATE,A.U_APPDATE) as U_APPDATE, A.NAME, A.U_LASTNAME, A.U_FIRSTNAME, A.U_MIDDLENAME, ifnull(B.U_YEAR,A.U_YEAR) as U_YEAR, B.DOCSTATUS, A.U_APPREFNO,B.U_CORPNAME,
                                                CONCAT(
                                                IF(IFNULL(b.u_baddressno,'')='','',CONCAT(b.u_baddressno,', ')),
                                                IF(IFNULL(b.u_bbldgno,'') = '','',CONCAT(b.u_bbldgno,' ')),
                                                IF(IFNULL(b.u_bunitno,'') = '','',CONCAT(b.u_bunitno,', ')),
                                                IF(IFNULL(b.u_bfloorno,'') = '','',CONCAT(b.u_bfloorno,', ')),
                                                IF(IFNULL(b.u_bbldgname,'') = '','',CONCAT(b.u_bbldgname,', ')),
                                                IF(IFNULL(b.u_bblock,'')='','',CONCAT('BLK ',b.u_bblock,' ')),
                                                IF(IFNULL(b.u_blotno,'')='','',CONCAT('LOT ',b.u_blotno,' ')),
                                                IF(IFNULL(b.u_bphaseno,'')='','',CONCAT('PHASE ',b.u_bphaseno,', ')),
                                                IF(IFNULL(b.u_bstreet,'')='','',CONCAT(b.u_bstreet,', ')),
                                                IF(IFNULL(b.u_bvillage,'')='','',CONCAT(b.u_bvillage,', ')),
                                                IF(IFNULL(b.u_bbrgy,'')='','',CONCAT(b.u_bbrgy,', ')),
                                                IF(IFNULL(b.u_bcity,'')='','',CONCAT(b.u_bcity,', ')),
                                                IF(IFNULL(b.u_bprovince,'')='','',CONCAT(b.u_bprovince,' ')),
                                                IF(IFNULL(b.u_blandmark,'')='','',CONCAT(' (',b.u_blandmark,')'))
                                              ) AS BUSINESSADDRESS   
                                     from U_BPLMDS A LEFT JOIN U_BPLAPPS B ON B.COMPANY=A.COMPANY AND B.BRANCH=A.BRANCH AND B.DOCNO=A.U_APPREFNO LEFT JOIN U_BPLAPPLINES AS C ON B.COMPANY = C.COMPANY AND B.BRANCH = C.BRANCH AND B.DOCID = C.DOCID WHERE A.COMPANY = '$company' AND A.BRANCH = '$branch' AND U_ISONLINEAPP = 1 $filterExp  GROUP BY B.DOCNO,C.DOCID", $objGrid->sortby,"",$objGrid->sortas,$page->paging->currentrow,$page->paging->numperpage,"");
            }
        }
		$page->paging_recordcount($objrs->recordcount());
                    while ($objrs->queryfetchrow("NAME")) {
			if ($search) {
				if ($page->getitemstring("u_businessname")==$objrs->fields["NAME"]) {
					$match=true;
					$matchstatus=$objrs->fields["DOCSTATUS"];
				}
			}
                        
                        $indicator = "";
			$objGrid->addrow();
			$objGrid->setitem(null,"u_appno",$objrs->fields["U_APPNO"]);
			$objGrid->setitem(null,"docno",$objrs->fields["CODE"]);
			$objGrid->setitem(null,"u_appdate",formatDateToHttp($objrs->fields["U_APPDATE"]));
			$objGrid->setitem(null,"u_businessname",$objrs->fields["NAME"]);
			$objGrid->setitem(null,"u_corpname",$objrs->fields["U_CORPNAME"]);
			$objGrid->setitem(null,"u_businessaddress", strtoupper($objrs->fields["BUSINESSADDRESS"]));
			$objGrid->setitem(null,"u_lastname",$objrs->fields["U_LASTNAME"]);
			$objGrid->setitem(null,"u_firstname",$objrs->fields["U_FIRSTNAME"]);
			$objGrid->setitem(null,"u_middlename",$objrs->fields["U_MIDDLENAME"]);
			$objGrid->setitem(null,"u_lastname",$objrs->fields["U_LASTNAME"]);
			$objGrid->setitem(null,"u_apprefno",$objrs->fields["U_APPREFNO"]);
                        $objGrid->setitem(null,"action2","Retire w/ Payment");
			if ($objrs->fields["U_YEAR"]<date('Y')) {
                                $indicator="Expired";
				$objGrid->setitem(null,"docstatus","Expired");
				$objGrid->setitem(null,"action","Click to Renew");
                                $objGrid->setitem(null,"indicator",$indicator);
			} elseif ($objrs->fields["DOCSTATUS"]=="Paid" && date('m')=="12") {
				$objGrid->setitem(null,"docstatus","Paid");
				//$objGrid->setitem(null,"action","Click to Renew");
			} elseif ($objrs->fields["DOCSTATUS"]=="Paid") {
				$objGrid->setitem(null,"docstatus","Paid");
				$objGrid->setitem(null,"action","Update");
			}else $objGrid->setitem(null,"docstatus",$objrs->fields["DOCSTATUS"]);
                        
                        if ($objrs->fields["U_RETIRED"]== 1) {
                            $indicator="Retired";
                            $objGrid->setitem(null,"indicator",$indicator);
                            $objGrid->setitem(null,"docstatus","Retired");
                            $objGrid->setitem(null,"action","");
                            $objGrid->setitem(null,"action2","");
                            $retired = true;
                        }
                        if ($objrs->fields["U_ONHOLD"]== 1) {
                            $indicator="On-Hold";
                            $objGrid->setitem(null,"indicator",$indicator);
                            $objGrid->setitem(null,"docstatus","On-Hold");
                            $objGrid->setitem(null,"action","");
                            $objGrid->setitem(null,"action2","");
                            $retired = true;
                        }
                        if ($objrs->fields["U_APPNOMDS"] != $objrs->fields["U_APPREFNO"]) {
                            $objGrid->setitem(null,"action","");
                            $objGrid->setitem(null,"action2","");
                        }
                      
			$objGrid->setkey(null,$objrs->fields["CODE"]); 
			if (!$page->paging_fetch()) break;
                    }	
	//}
                
        $indicator = '';       
	function onGridColumnTextDraw($table,$column,$row,&$label,&$style) {
            
            global $indicator;
		switch ($column) {
			case "indicator":
                                $indicator = $label;
				switch ($label) {
                                        case "Expired": $style="background-color:#d13d3d"; break;
                                        case "Retired": $style="background-color:#1a3300"; break;
                                        case "On-Hold": $style="background-color:#00b33c"; break;
                                        default : $style="background-color:#0000b3"; break;
				}
				$label="&nbsp";	
				break;
                        default :
                                switch ($indicator){
                                    case "Expired": $style="color:#d13d3d"; break;
                                    case "Retired": $style="color:#1a3300";break;
                                    case "On-Hold": $style="color:#00b33c";break;
                                    default : $style="color:#0000b3"; break;
                                }
                                break;
		}
		
	}
	
//	resetTabindex();
//	setTabindex($schema["u_lastname"]);
//	setTabindex($schema["u_firstname"]);
//	setTabindex($schema["u_middlename"]);
//	setTabindex($schema["u_birthdate"]);
//	setTabindex($schema["u_patientid"]);		
	
	$page->resize->addgrid("T1",20,240,false);
	$page->toolbar->setaction("print",false);
	
	$rptcols = 6; 
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<!--<link rel="stylesheet" type="text/css" href="css/txtobjs2.css">-->
<!--<link rel="stylesheet" type="text/css" href="css/txtobjs.css">-->
<link rel="stylesheet" type="text/css" href="css/txtobjs2_<?php echo @$_SESSION["theme"] ; ?>.css">
<!--<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">-->
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard2.css">
<link rel="stylesheet" type="text/css" href="css/standard2_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css"> 
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<style type="text/css">
</style>

</head>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	var autoprint = false;
	var autoprintdocno = "";
	
	function onPageLoad() {
		focusInput(getInput("u_focusinput"));
		//"u_lastname");
		try {
			if (window.opener.getVar("objectcode")=="U_LGUPOS") {
				autoprint = true;
				autoprintdocno = window.opener.getPrivate("bpno");
				searchTableInput("T1","docno",autoprintdocno,true);
				setTimeout("OpenReportSelect('printer')",100);
			} else {
				if (getInput("docstatus")!="") {
					setTimeout("formSearchNow()",60000);
				}
			}
		} catch (theError) {
		}
	}
     

	function onFormSubmit(action) {
		if (action=="checkprinted") {
			setKey("keys",getTableInput("T100","docno")+"`"+getTableInput("T100","checkno"));
			hidePopupFrame('popupFrameCheckPrintedStatus');
		}
		return true;
	}

	function onFormSubmitReturn(action,success,error) {
		if (success) {
			try {
				if (window.opener.getVar("objectcode")=="U_LGUPOS") {
					window.close();
				}
			} catch (theError) {
			}
		}	
		return true;
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onReport(p_formattype) {
		if (getTableSelectedRow("T1")==0) {
			page.statusbar.showWarning("No selected application.");
			return false;
		}
		if (isPopupFrameOpen('popupFrameCheckPrintedStatus')) {
			page.statusbar.showWarning("Check Printing Status window is still open.");
			return false;
		}
		return true;
	}
	

        function onReportGetParams(p_formattype,p_params) {
		var params = new Array();
                var rc =  getTableRowCount("T1"),selectedcnt = 0,cnt = 0;
                var docno = '',noticeno = '';
                for (i = 1; i <= rc; i++) {
                    if(getTableInput("T1","rowstat",i) != "X") {
                            if(getTableInput("T1","checked",i)=="1") {
                                    selectedcnt += 1;
                            }
                    }
                }
                
                for (iii = 1; iii <= rc; iii++) {
                    if(getTableInput("T1","rowstat",iii) != "X") {
                            if(getTableInput("T1","checked",iii)=="1") {
                                    cnt += 1;
                                    if (cnt == selectedcnt) {
                                       docno = docno + getTableInput("T1","docno",iii);
                                    } else {
                                       docno = docno + getTableInput("T1","docno",iii) + ",";
                                    }
                            }
                    }
                }
		if (p_params!=null) params = p_params;
		params = getReportLayout("<?php echo $page->progid?>",p_formattype,params);

		params["source"] = "aspx";
		//params["dbtype"] = "mysql";
		//params["reportaction"] = getInput("reportaction");
		//params["action"] = "processReport.aspx"
		if (params["querystring"]==undefined) {
			params["querystring"] = "";
			params["querystring"] += generateQueryString("docno",docno);
		}
//                switch (getTableInput("T1","u_kind",getTableSelectedRow("T1"))) {
//                        case "LAND":
//                                params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\LGU Bacoor Add-On\\UserRpts\\RPTAS\\u_rpfaas1.rpt"; 
//                                break;
//                        case "BUILDING":
//                                params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\LGU Bacoor Add-On\\UserRpts\\RPTAS\\u_rpfaas2.rpt"; 
//                                break;
//                        case "MACHINERY":
//                                params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\LGU Bacoor Add-On\\UserRpts\\RPTAS\\u_rpfaas3.rpt"; 
//                                break;
//                }
		// params["recordselectionformula"]="{u_rpfaas1.docno} = '"+getTableInput("T1","docno",getTableSelectedRow("T1"))+"'";
		//page.statusbar.showWarning(params["source"]);
//		params["recordselectionformula"] = "{items.ITEMCODE}='A1000'";
		return params;
	}

	function onReportReturn(p_formattype) {
		var row = getTableSelectedRow("T1");
	//	page.statusbar.showWarning("previewed");
		setTableInput("T100","docno",getTableInput("T1","docno",row));
		setTableInput("T100","u_businessname",getTableInput("T1","u_businessname",row));
		setTableInput("T100","u_lastname",getTableInput("T1","u_lastname",row));
		setTableInput("T100","u_firstname",getTableInput("T1","u_firstname",row));
		setTableInput("T100","u_middlename",getTableInput("T1","u_middlename",row));
                if (getInput("opt")=="Print") {
                    showPopupFrame('popupFrameCheckPrintedStatus',true);
                }
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				if (getInput("opt")=="Print") {
					setTimeout("OpenReportSelect('printer')",100);
				} else {
					if (getTableInput("T1","u_apprefno",p_rowIdx)!="") {
						setKey("keys",getTableInput("T1","u_apprefno",p_rowIdx),p_rowIdx);
						return formView(null,"./UDO.php?&objectcode=U_BPLAPPS");
					} else page.statusbar.showWarning("No application record for the selected business. This was uploaded as opening record.");
				}	
				/*if (getTableInput("T1","u_expired",p_rowIdx)=="1") {
					page.statusbar.showError("Patient already expired. Cannot add new registration.");
				} else if (getTableInput("T1","u_active",p_rowIdx)=="0") {
					page.statusbar.showError("Patient record is not active. Cannot add new registration.");
				} else {
					if (getInput("u_trxtype")=="IP") {
						return formView(null,"./UDO.php?&objectcode=U_HISIPS&patientidtoreg="+getTableKey("T1","keys",p_rowIdx));
					} else {
						return formView(null,"./UDO.php?&objectcode=U_HISOPS&patientidtoreg="+getTableKey("T1","keys",p_rowIdx));
					}	
				}	*/
				//var targetObjectId = 'u_hisips';
				//OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisips' + '' + '&targetId=' + targetObjectId ,targetObjectId);
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
			case "docstatus": 
			case "u_appdatefr": 
			case "u_appdateto": 
			case "u_businesskind": 
			case "u_taxclass": 
			case "u_businessline": 
			case "u_businesschar": 
			case "u_businesscategory": 
				setInput("u_businessname","");
				setInput("u_ownername","");
				setInput("u_corpname","");
				setInput("u_lastname","");
				setInput("u_firstname","");
				setInput("u_middlename","");
				formPageReset(); 
				clearTable("T1");
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
				formPageReset(); 
				clearTable("T1");
				break;
			case "u_appdatefr": 
			case "u_appdateto": 
			case "docno": 
				setInput("u_businessname","");
				setInput("u_ownername","");
				setInput("u_corpname","");
				setInput("u_lastname","");
				setInput("u_firstname","");
				setInput("u_middlename","");
				formPageReset(); 
				clearTable("T1");
				break;	
			case "u_businessname": 
			case "u_ownername": 
			case "u_corpname": 
			case "u_lastname": 
			case "u_firstname": 
			case "u_middlename": 
				setInput("docstatus","");
				setInput("u_appdatefr","");
				setInput("u_appdateto","");
				setInput("u_businesskind","");
				setInput("u_taxclass","");
				setInput("u_businessline","");
				setInput("u_businesschar","");
				setInput("u_businesscategory","");
				formPageReset(); 
				clearTable("T1");
				break;	
		}
		return true;
	}	
	
	function onElementClick(element,column,table,row) {
		switch (table) {
			case "T1":
				switch (column) {
					case "print":
						setTimeout("OpenReportSelect('printer')",100);

						/*if (row==0) row = undefined;
						OpenPopupViewMap("viewmap","CUSTOMER",getTableInput(table,"street",row),getTableInput(table,"barangay",row),getTableInput(table,"city",row),getTableInput(table,"zip",row),getTableInput(table,"county",row),getTableInput(table,"province",row),getTableInput(table,"country",row),"");
						*/
						break;
					case "action":
						setInput("u_bpno",getTableInput("T1","docno",row));
						setInput("u_apptype","RENEW");
						setKey("keys","");
						return formView(null,"<?php echo $page->formid; ?>");
						break;
                                        case "action2":
						setInput("u_bpno",getTableInput("T1","docno",row));
						setInput("u_apptype","RETIRED");
						setKey("keys","");
						return formView(null,"<?php echo $page->formid; ?>");
						break;
                                                
                                        
				}	
				break;
		}
	}
		
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_docstatus":
			case "df_viewby":
			case "df_docno":
			case "df_acctno":
			case "df_u_businesskind":
			case "df_u_taxclass":
			case "df_u_businesschar":
			case "df_u_businesscategory":
			case "df_u_businessname":
			case "df_u_businessline":
			case "df_u_ownername":
			case "df_u_corpname":
			case "df_u_lastname":
			case "df_u_firstname":
			case "df_u_middlename":
			case "df_u_appdatefr":
			case "df_u_appdateto":
                            
			case "df_u_bunitno": 
			case "df_u_bstreet": 
			case "df_u_bfloorno": 
			case "df_u_bblock": 
			case "df_u_bvillage": 
			case "df_u_bbrgy": 
			case "df_u_bbldgname": 
			case "df_u_baddressno": 
			case "df_u_bphaseno": 
			case "df_u_blotno": 
				return false;
		}
		return true;
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("opt");
			inputs.push("u_appdatefr");
			inputs.push("u_appdateto");
			inputs.push("viewby");
			inputs.push("docno");
			inputs.push("acctno");
			inputs.push("u_bpno");
			inputs.push("u_apptype");
			inputs.push("docstatus");
			inputs.push("u_businesskind");
			inputs.push("u_taxclass");
			inputs.push("u_businesschar");
			inputs.push("u_businesscategory");
			inputs.push("u_businessname");
			inputs.push("u_businessline");
			inputs.push("u_ownername");
			inputs.push("u_corpname");
			inputs.push("u_lastname");
			inputs.push("u_firstname");
			inputs.push("u_middlename");
			inputs.push("u_bunitno");
			inputs.push("u_bstreet");
			inputs.push("u_bfloorno");
			inputs.push("u_bblock");
			inputs.push("u_bvillage");
			inputs.push("u_bbrgy");
			inputs.push("u_bbldgname");
			inputs.push("u_baddressno");
			inputs.push("u_bphaseno");
			inputs.push("u_blotno");
			
		return inputs;
	}
	
	
	function formAddNew() {
		setInput("u_businessname",getInput("u_businessname"));
		setInput("u_apptype","NEW");
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		acceptText();
		if (isPopupFrameOpen('popupFrameCheckPrintedStatus')) {
			page.statusbar.showWarning("Check Printing Status window is still open.");
			return false;
		}
                if(getInput("acctno")=="" && getInput("docno")=="" &&  getInput("u_appdatefr")=="" &&  getInput("u_appdateto")=="" &&  getInput("u_businessname")=="" &&  getInput("u_lastname")=="" &&  getInput("u_firstname")=="" && getInput("u_middlename")=="" && getInput("u_bunitno")=="" && getInput("u_bfloorno")=="" && getInput("u_bvillage")=="" && getInput("u_bstreet")=="" && getInput("u_bblock")=="" && getInput("u_bbrgy")=="" && getInput("u_bbldgname")==""&& getInput("u_baddressno")==""&& getInput("u_bphaseno")==""&& getInput("u_blotno")==""){
                    page.statusbar.showError("Please input atleast one filter");	
                    return false;
                }
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "acctno":
			case "docno":
			case "u_appdatefr":
			case "u_appdateto":
			case "u_businesschar":
			case "viewby":
			case "docstatus":
			case "u_businessline":
			case "u_businesscategory":
			case "u_taxclass":
			case "u_bunitno":
			case "u_bfloorno":
			case "u_bvillage":
			case "u_bstreet":
			case "u_bblock":
			case "u_bbrgy":
			case "u_bbldgname":
			case "u_bphaseno":
			case "u_baddressno":
			case "u_blotno":
			case "u_ownername":
			case "u_corpname":
			case "u_lastname":
			case "u_firstname":
			case "u_middlename":
			case "u_birthdate":
			case "u_patientid":
			case "u_businessname":
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
	
	function formSearchBusinessName() {
		if (isInputEmpty("u_businessname")) return false;
		formSearchNow();
	}
	function formSearchOwnerName() {
		//if (isInputEmpty("u_lastname")) return false;
		//if (isInputEmpty("u_firstname")) return false;
		//if (isInputEmpty("u_middlename")) return false;
		formSearchNow();
	}
	
	
</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="<?php echo $objGrid->sortby;  ?>">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="<?php echo $objGrid->sortas;  ?>">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("opt") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_bpno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_apptype") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow5px"><td></td>
</tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td class="labelPageHeader" >&nbsp;Business Permit List&nbsp;</td>
		<td><input type="hidden" name="firstvisualrec" value="<?php echo $firstvisualrec;  ?>">
			<input type="hidden" name="filterstr" value="<?php echo $filterstr;  ?>">                  </td>
	</tr>
</table></td></tr>
<tr class="fillerRow5px"><td></td>
</tr>
<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td>
      <div style="width:900px;<?php if ($page->getitemstring("docno")!="" && $page->getitemstring("opt")=="Print") echo ';display:none'; ?>" >
    <table width="937" border="0"  cellpadding="0" cellspacing="0" class="tableFreeForm">
        <tr>
          <td width="173"><label <?php genCaptionHtml($schema["acctno"],"") ?>>Account No.</label></td>
          <td width="328"  align=left>&nbsp;<input type="text" size="16" <?php genInputTextHtml($schema["acctno"]) ?> /></td>
          <td  width="204"><label <?php genCaptionHtml($schema["viewby"],"") ?>>Search By</label></td>
          <td  align=left width="195">&nbsp;<select <?php genSelectHtml($schema["viewby"],array("loadenumsearchby","",""),null,null,null,"width:140px") ?> ></select></td>
        </tr>
        <tr>
          <td width="173"><label <?php genCaptionHtml($schema["docno"],"") ?>>Reference No.</label></td>
          <td width="328"  align=left>&nbsp;<input type="text" size="20" <?php genInputTextHtml($schema["docno"]) ?> /></td>
          <td  width="204"><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Status</label></td>
          <td  align=left width="195">&nbsp;<select <?php genSelectHtml($schema["docstatus"],array("loadenumdocstatus","",":[All]"),null,null,null,"width:200px") ?> ></select></td>
        </tr>
         <tr>
          <td ><label <?php genCaptionHtml($schema["u_businesschar"],"") ?>>Business Character</label></td>
          <td >&nbsp;<select <?php genSelectHtml($schema["u_businesschar"],array("loadudflinktable","u_bplcharacters:code:name",":[All]"),null,null,null,"width:200px") ?> ></select></td>
          <td ><label <?php genCaptionHtml($schema["u_businesscategory"],"") ?>>Business Category</label></td>
          <td >&nbsp;<select <?php genSelectHtml($schema["u_businesscategory"],array("loadudflinktable","u_bplcategories:code:name",":[All]"),null,null,null,"width:200px") ?> ></select></td>
        </tr>
         <tr>
          <td ><label <?php genCaptionHtml($schema["docno"],"") ?>>Application Date</label></td>
          <td >&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_appdatefr"]) ?> /><label <?php genCaptionHtml($schema["u_appdateto"],"") ?>>To</label>&nbsp;<input type="text" size="12" <?php genInputTextHtml($schema["u_appdateto"]) ?> /></td>
          <td ><label <?php genCaptionHtml($schema["docstatus"],"") ?>>Tax Classification</label></td>
          <td >&nbsp;<select <?php genSelectHtml($schema["u_taxclass"],array("loadudflinktable","u_bpltaxclasses:code:name",":[All]"),null,null,null,"width:200px") ?> ></select></td>
        </tr>
        <tr class="fillerRow5px">
          <td ></td>
          <td align=left></td>
        </tr>
        <tr>
          <td >&nbsp;</td>
          <td align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
          </tr>
         <tr class="fillerRow5px">
          <td ></td>
          <td align=left></td>
        </tr>
    </table>
    </div>
</td>
<td>
</td>
<td>
  <?php if ($page->getitemstring("opt")=="Print") { ?>
	<div style="display:none">
  <?php } ?>		
<div class="tabber" id="tab1">
    <div class="tabbertab" title="Name\s">
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr> 
              <td width="100" ><label class="lblobjs" <?php genCaptionHtml($schema["u_businessname"],"") ?>><b>Business Name</b></label></td>
              <td width="200" align=left><input type="text" size="50" <?php genInputTextHtml($schema["u_businessname"]) ?> /></td>
            </tr>
            <tr> 
              <td width="100" ><label <?php genCaptionHtml($schema["u_ownername"],"") ?>><b>Tax Payer Name</b></label></td>
              <td width="200" align=left><input type="text" size="50" <?php genInputTextHtml($schema["u_ownername"]) ?> /></td>
            </tr>
            <tr> 
              <td width="100" ><label <?php genCaptionHtml($schema["u_corpname"],"") ?>><b>Corporation Name</b></label></td>
              <td width="200" align=left><input type="text" size="50" <?php genInputTextHtml($schema["u_corpname"]) ?> /></td>
            </tr>
            <tr class="fillerRow5px">
              <td align=left></td>
            </tr>
            <tr>
                <td align=left></td>
              <td align=left><a class="button" href="" onClick="formSearchNow();return false;">Search</a><?php if($search && !$match || $retired) {?>&nbsp;<a class="button" href="" onClick="formAddNew();return false;">Create</a><?php } ?><?php if($search && $match && $expired) {?>&nbsp;<a class="button" href="" onClick="formRenew();return false;">Renew</a><?php } ?><?php if($search && $match && !$expired && ($matchstatus=="Paid" || $matchstatus=="Printing")) {?>&nbsp;<a class="button" href="" onClick="formUpdate();return false;">Additional</a>&nbsp;<a class="button" href="" onClick="formTransfer();return false;">Transfer</a>&nbsp;<a class="button" href="" onClick="formAmmendment();return false;">Ammendment</a><?php } ?></td>
              <!--<td align=left><a class="button" href="" onClick="formSearchBusinessName();return false;">Search</a><?php if($search && !$match || $retired) {?>&nbsp;<a class="button" href="" onClick="formAddNew();return false;">Create</a><?php } ?><?php if($search && $match && $expired) {?>&nbsp;<a class="button" href="" onClick="formRenew();return false;">Renew</a><?php } ?><?php if($search && $match && !$expired && ($matchstatus=="Paid" || $matchstatus=="Printing")) {?>&nbsp;<a class="button" href="" onClick="formUpdate();return false;">Additional</a>&nbsp;<a class="button" href="" onClick="formTransfer();return false;">Transfer</a>&nbsp;<a class="button" href="" onClick="formAmmendment();return false;">Ammendment</a><?php } ?></td>-->
            </tr>
        </table>
    </div>	
<!--    <div class="tabbertab" title="Tax Payer Name">
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td width="250" align=left><input type="text"  size="50" <?php genInputTextHtml($schema["u_ownername"]) ?> /></td>
            </tr>
            <tr class="fillerRow5px">
               <td align=left></td>
           </tr>
            <tr>
              <td align=left><a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
            </tr>
        </table>
    </div>	-->
    <div class="tabbertab" title="Business Address">
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        
        <tr>
          <td width="80" ><label <?php genCaptionHtml($schema["u_bunitno"],"") ?>>Unit No</label></td>
          <td width="150" align=left><input type="text" size="12"  <?php genInputTextHtml($schema["u_bunitno"]) ?> /></td>
          <td width="80" ><label <?php genCaptionHtml($schema["u_bstreet"],"") ?>>Street</label></td>
          <td width="150" align=left><input type="text" size="12" <?php genInputTextHtml($schema["u_bstreet"]) ?> /></td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_bfloorno"],"") ?>>Floor No</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_bfloorno"]) ?> /></td>
           <td ><label <?php genCaptionHtml($schema["u_bblock"],"") ?>>Block</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_bblock"]) ?> /></td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_bvillage"],"") ?>>Subdivision</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_bvillage"]) ?> /></td>
           <td ><label <?php genCaptionHtml($schema["u_bbrgy"],"") ?>>Barangay</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_bbrgy"]) ?> /></td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_bbldgname"],"") ?>>Building Name</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_bbldgname"]) ?> /></td>
           <td ><label <?php genCaptionHtml($schema["u_baddressno"],"") ?>>Address No</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_baddressno"]) ?> /></td>
          </tr>
        <tr>
          <td ><label <?php genCaptionHtml($schema["u_bphaseno"],"") ?>>Phase No</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_bphaseno"]) ?> /></td>
           <td ><label <?php genCaptionHtml($schema["u_blotno"],"") ?>>Lot No</label></td>
          <td  align=left><input type="text"  size="12"<?php genInputTextHtml($schema["u_blotno"]) ?> /></td>
          </tr>

    </table>
    </div>
    <div class="tabbertab" title="Business Line">
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td width="250" align=left><select <?php genSelectHtml($schema["u_businessline"],array("loadudflinktable","u_bpllines:code:name",":[All]"),null,null,null,"width:400px") ?> ></select></td>
            </tr>
            <tr class="fillerRow5px">
              <td align=left></td>
            </tr>
            <tr>
              <td align=left><a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
            </tr>
        </table>
    </div>	
</div> 
	  <?php if ($page->getitemstring("opt")=="Print") { ?>
		</div>
	  <?php } ?>		
</td>
</tr></table>
</td></tr>	
<tr class="fillerRow10px"><td></td></tr>
<tr><td><?php $objGrid->draw() ?></td></tr>
<?php $page->writeRecordLimit(); ?>
<?php if ($requestorId == "")	require("./GenericListToolbar.php");  ?>

<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>	
<div <?php genPopWinHDivHtml("popupFrameCheckPrintedStatus","Check Printed Status",10,30,560,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["docno"],"T100") ?> >Reference No.</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["docno"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_businessname"],"T100") ?> >Business Name</label></td>
			<td >&nbsp;<input type="text" disabled size="45" <?php genInputTextHtml($schema["u_businessname"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_lastname"],"T100") ?> >Last Name</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["u_lastname"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_firstname"],"T100") ?> >First Name</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["u_firstname"],"T100") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php genCaptionHtml($schema["u_middlename"],"T100") ?> >Middle Name</label></td>
			<td >&nbsp;<input type="text" disabled <?php genInputTextHtml($schema["u_middlename"],"T100") ?> /></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="formSubmit('checkprinted');return false;" >Printed</a>&nbsp;<a class="button" href="" onClick="hidePopupFrame('popupFrameCheckPrintedStatus');return false;" >Close</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
    <?php require("./bofrms/ajaxprocess.php"); ?>	
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_GenericListToolbar.php");
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
