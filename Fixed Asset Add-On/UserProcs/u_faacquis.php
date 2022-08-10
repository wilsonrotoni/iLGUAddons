<?php
 
	require_once("./sls/brancheslist.php"); 
	require_once("./sls/departments.php"); 
$page->businessobject->events->add->customAction("onCustomActionGPSFixedAsset");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSFixedAsset");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSFixedAsset");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSFixedAsset");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSFixedAsset");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSFixedAsset");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSFixedAsset");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSFixedAsset");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSFixedAsset");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSFixedAsset");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSFixedAsset");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSFixedAsset");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSFixedAsset");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSFixedAsset");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSFixedAsset");
function onGridColumnHeaderDraw($table, $column , &$ownerdraw) {
	global $page;
	global $objGrids;
	if ($table=="T1" && $column=="u_selected" && ($page->getitemstring("docstatus")=="O" || $page->getitemstring("docstatus")=="Pending")) {
		$checked["name"] = $column;
		$checked["attributes"] = "";
		$checked["input"]["value"] = 1;
		echo "<input type=\"checkbox\"" . $objGrids[0]->genInputCheckBoxHtml($checked,$table) . "/>";
		$ownerdraw = true;
	}
}
function onCustomActionGPSFixedAsset($action) {
        global $objConnection;
	global $page;
	global $objGrids;
        switch ($action) {
                case "search":
                    $branchExp = "";
                    if ($page->privatedata["famgmnt"]=="BR") {
                            $branchExp = " and A.BRANCH='".$_SESSION["branch"]."' ";
                    }

                    $objRs = new recordset(null,$objConnection);
            //	$objRs->queryopen("select A.LINEID, A.BRANCH, A.U_SRCDOC, A.U_SRCNAME, A.U_SRCLINE, A.U_SRCWHS, A.U_ACQUIDATE, A.U_ITEMCODE, A.U_ITEMDESC, A.U_CONTRAGLACCTNO, A.U_AMOUNT, A.U_COST, A.U_PROFITCENTER, A.U_PROJCODE, A.U_SERIALNO, A.U_MFRSERIALNO, A.U_PROPERTY1, A.U_PROPERTY2, A.U_PROPERTY3, A.U_PROPERTY4, A.U_PROPERTY5, A.U_ASSETTYPE, A.U_ASSETCODE, B.U_ISFIXEDASSET, B.U_FACLASS from u_faxmtal A LEFT JOIN ITEMS B ON B.ITEMCODE=A.U_ITEMCODE where A.U_STATUS='' $branchExp");
                    $objRs->queryopen("SELECT B.LINEID, B.BRANCH, A.DOCNO AS U_SRCDOC, 'LGU Goods Receipt' as U_SRCNAME, A.DOCID AS U_SRCLINE, B.U_WHSCODE AS U_SRCWHS, A.U_DATE AS U_ACQUIDATE, B.U_ITEMCODE, B.U_ITEMDESC, D.U_PURCHACCT AS U_CONTRAGLACCTNO, B.U_UNITCOST AS U_AMOUNT, B.U_OPENQUANTITY,B.U_COST, A.U_PROFITCENTER, A.U_PROJCODE, '' AS U_SERIALNO, '' AS U_MFRSERIALNO, '' AS U_PROPERTY1, '' AS U_PROPERTY2, '' AS U_PROPERTY3, '' AS U_PROPERTY4, '' AS U_PROPERTY5,  '' AS U_ASSETTYPE, '' AS U_ASSETCODE, C.U_ISFIXEDASSET, C.U_FACLASS
                                        from u_lgupurchasedelivery A
                                        INNER JOIN U_LGUPURCHASEDELIVERYITEMS B ON A.DOCID = B.DOCID AND A.BRANCH = B.BRANCH AND A.COMPANY = B.COMPANY AND B.U_ITEMTYPE IN ('F','Fixed Asset')
                                        LEFT JOIN U_LGUITEMS C ON C.CODE=B.U_ITEMCODE
                                        LEFT JOIN U_FACLASS D ON C.U_FACLASS = D.CODE
                                        where A.DOCSTATUS NOT IN ('CN','D') AND B.U_LINESTATUS = 'O' and A.DOCNO LIKE '%".$page->getitemstring("sourcedocument")."%' $branchExp");
                                    while ($objRs->queryfetchrow("NAME")) {
                                            $ctr = 0;
                                            while ($ctr < $objRs->fields["U_OPENQUANTITY"]){
                                                $ctr++;
                                                $objGrids[0]->addrow();
                                                $objGrids[0]->setitem(null,"u_xmtallineid",$objRs->fields["LINEID"]);
                                                $objGrids[0]->setitem(null,"u_srcbr",$objRs->fields["BRANCH"]);
                                                $objGrids[0]->setitem(null,"u_srcdoc",$objRs->fields["U_SRCDOC"]);
                                                $objGrids[0]->setitem(null,"u_srcname",$objRs->fields["U_SRCNAME"]);
                                                $objGrids[0]->setitem(null,"u_srcline",$objRs->fields["U_SRCLINE"]);
                                                $objGrids[0]->setitem(null,"u_srcwhs",$objRs->fields["U_SRCWHS"]);
                                                $objGrids[0]->setitem(null,"u_acquidate",formatDate($objRs->fields["U_ACQUIDATE"]));
                                                $objGrids[0]->setitem(null,"u_itemcode",$objRs->fields["U_ITEMCODE"]);
                                                $objGrids[0]->setitem(null,"u_itemdesc",$objRs->fields["U_ITEMDESC"]);
                                                $objGrids[0]->setitem(null,"u_amount",formatNumericAmount(iif($objRs->fields["U_COST"]>0,$objRs->fields["U_COST"],$objRs->fields["U_AMOUNT"])));
                                                $objGrids[0]->setitem(null,"u_contraglacctno",$objRs->fields["U_CONTRAGLACCTNO"]);
                                                $objGrids[0]->setitem(null,"u_cost",formatNumericAmount($objRs->fields["U_AMOUNT"]));
                                                $objGrids[0]->setitem(null,"u_profitcenter",$objRs->fields["U_PROFITCENTER"]);
                                                $objGrids[0]->setitem(null,"u_projcode",$objRs->fields["U_PROJCODE"]);
                                                $objGrids[0]->setitem(null,"u_serialno",$objRs->fields["U_SERIALNO"]);
                                                $objGrids[0]->setitem(null,"u_mfrserialno",$objRs->fields["U_MFRSERIALNO"]);
                                                $objGrids[0]->setitem(null,"u_property1",$objRs->fields["U_PROPERTY1"]);
                                                $objGrids[0]->setitem(null,"u_property2",$objRs->fields["U_PROPERTY2"]);
                                                $objGrids[0]->setitem(null,"u_property3",$objRs->fields["U_PROPERTY3"]);
                                                $objGrids[0]->setitem(null,"u_property4",$objRs->fields["U_PROPERTY4"]);
                                                $objGrids[0]->setitem(null,"u_property5",$objRs->fields["U_PROPERTY5"]);
                                                $objGrids[0]->setitem(null,"u_assettype",$objRs->fields["U_ASSETTYPE"]);
                                                $objGrids[0]->setitem(null,"u_assetcode",$objRs->fields["U_ASSETCODE"]);
                                                if ($objRs->fields["U_ISFIXEDASSET"]=="1") {
                                                        $objGrids[0]->setitem(null,"u_assettype","F");
                                                        $objGrids[0]->setitem(null,"u_assetcode",$objRs->fields["U_FACLASS"]);
                                                }
                                                $objGrids[0]->setitem(null,"u_accumdepre",0);

                                                //if ($page->privatedata["famgmnt"]=="BR") {
                                                        $objGrids[0]->setitem(null,"u_branch",$_SESSION["branch"]);
                                               // }
                                            }

                                    }
                break;    
        }
	return true;
}

function onBeforeDefaultGPSFixedAsset() { 
	return true;
}

function onAfterDefaultGPSFixedAsset() { 
	global $objConnection;
	global $page;
	global $objGrids;
	$page->setitem("u_docdate",currentdate());

	
	
	return true;
}

function onPrepareAddGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeAddGPSFixedAsset() { 
	return true;
}

function onAfterAddGPSFixedAsset() { 
	return true;
}

function onPrepareEditGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeEditGPSFixedAsset() { 
	return true;
}

function onAfterEditGPSFixedAsset() { 
	return true;
}

function onPrepareUpdateGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeUpdateGPSFixedAsset() { 
	return true;
}

function onAfterUpdateGPSFixedAsset() { 
	return true;
}

function onPrepareDeleteGPSFixedAsset(&$override) { 
	return true;
}

function onBeforeDeleteGPSFixedAsset() { 
	return true;
}

function onAfterDeleteGPSFixedAsset() { 
	return true;
}
$schema["sourcedocument"] = createSchemaUpper("sourcedocument");
$schema["department"] = createSchemaUpper("department");
$schema["employee"] = createSchemaUpper("employee");
$schema["startproptag"] = createSchemaNumeric("startproptag");

$schema["department"]["cfl"] = "OpenCFLfs()";	
$schema["employee"]["cfl"] = "OpenCFLfs()";	
$objGrids[0]->columnwidth("u_selected",2);
$objGrids[0]->columnwidth("u_srcbr",12);
$objGrids[0]->columnwidth("u_srcdoc",15);
$objGrids[0]->columnwidth("u_srcname",15);
$objGrids[0]->columnwidth("u_acquidate",8);
$objGrids[0]->columnwidth("u_itemcode",20);
$objGrids[0]->columnwidth("u_itemdesc",20);
$objGrids[0]->columnwidth("u_contraglacctno",20);
$objGrids[0]->columnwidth("u_accumdepre",15);
$objGrids[0]->columnwidth("u_assettype",10);
$objGrids[0]->columnwidth("u_assetcode",20);
$objGrids[0]->columnwidth("u_profitcenter",20);
$objGrids[0]->columnwidth("u_projcode",20);
$objGrids[0]->columnwidth("u_empid",12);
$objGrids[0]->columnwidth("u_empname",20);
$objGrids[0]->columnwidth("u_branch",20);
$objGrids[0]->columnwidth("u_department",20);
$objGrids[0]->columnwidth("u_serialno",20);
$objGrids[0]->columnwidth("u_mfrserialno",20);
$objGrids[0]->columnwidth("u_property1",15);
$objGrids[0]->columnwidth("u_property2",15);
$objGrids[0]->columnwidth("u_property3",15);
$objGrids[0]->columnwidth("u_property4",15);
$objGrids[0]->columnwidth("u_property5",15);

$objGrids[0]->columnattributes("u_itemdesc","disabled");
$objGrids[0]->columnattributes("u_empname","disabled");

$objGrids[0]->columnvisibility("u_accumdepre",false);

$objGrids[0]->columninput("u_selected","type","checkbox");
$objGrids[0]->columninput("u_selected","value",1);
$objGrids[0]->columndataentry("u_selected","type","hidden");

$objGrids[0]->columncfl("u_assetcode","OpenCFLmasterdata()");
$objGrids[0]->columncfl("u_itemcode","OpenCFLitems()");
$objGrids[0]->columncfl("u_contraglacctno","OpenCFLchartofaccounts()");
$objGrids[0]->columncfl("u_profitcenter","OpenCFLprofitcenterdistributionrules()");
$objGrids[0]->columncfl("u_projcode","OpenCFLprojects()");
$objGrids[0]->columncfl("u_empid","OpenCFLemployees()");

$objGrids[0]->columninput("u_cost","type","text");
$objGrids[0]->columninput("u_assetcode","type","text");
$objGrids[0]->columninput("u_contraglacctno","type","text");
$objGrids[0]->columninput("u_profitcenter","type","text");
$objGrids[0]->columninput("u_projcode","type","text");
$objGrids[0]->columninput("u_empid","type","text");
$objGrids[0]->columninput("u_property1","type","text");
$objGrids[0]->columninput("u_property2","type","text");
$objGrids[0]->columninput("u_property3","type","text");
$objGrids[0]->columninput("u_property4","type","text");
$objGrids[0]->columninput("u_property5","type","text");
$objGrids[0]->columninput("u_serialno","type","text");
$objGrids[0]->columninput("u_mfrserialno","type","text");

$objGrids[0]->columninput("u_assettype","type","select");
$objGrids[0]->columninput("u_assettype","options",array("loadudfenums","u_faacquiitems:assettype",""));
$objGrids[0]->columninput("u_branch","type","select");
$objGrids[0]->columninput("u_branch","options",array("loadbrancheslist","",":[Select]"));
$objGrids[0]->columninput("u_department","type","select");
$objGrids[0]->columninput("u_department","options",array("loaddepartments","",":[Select]"));


$objGrids[0]->dataentry = false;

$page->businessobject->items->setcfl("u_docdate","Calendar");

$page->businessobject->items->seteditable("u_jvno",false);

//$addoptions = false;
$deleteoption = false;
//$page->toolbar->setaction("update",false);

$objRs = new recordset(null,$objConnection);
$objRs->queryopen("select u_famgmnt from companies where companycode='".$_SESSION["company"]."'");
if ($objRs->queryfetchrow("NAME")) {
	$page->privatedata["famgmnt"] = $objRs->fields["u_famgmnt"]; 
}

?> 

