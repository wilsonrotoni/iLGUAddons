<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUPurchasing");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUPurchasing");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUPurchasing");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUPurchasing");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUPurchasing");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUPurchasing");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUPurchasing");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUPurchasing");
$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUPurchasing");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUPurchasing");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUPurchasing");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUPurchasing");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUPurchasing");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUPurchasing");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUPurchasing");

$page->businessobject->events->add->drawGridColumnLabel("onGridColumnTextDrawGPSLGUPurchasing");

//$page->businessobject->events->add->GridColumnTextDraw("onGridColumnTextDrawGPSLGUPurchasing");

function onGridColumnTextDrawGPSLGUPurchasing($table,$column,$row,&$label,&$style) {
            
            global $indicator;
		switch ($column) {
			case "indicator":
                                $indicator = $label;
				switch ($label) {
                                        case "GI": $style="background-color:#d13d3d"; break;
				}
				$label="&nbsp";	
				break;
                        default :
                                switch ($indicator){
                                    case "GI": $style="color:#d13d3d"; break;
                                }
                                break;
		}
		
}
function onCustomActionGPSLGUPurchasing($action) {
	return true;
}

function onBeforeDefaultGPSLGUPurchasing() {
	return true;
}

function onAfterDefaultGPSLGUPurchasing() {
        global $objConnection;
	global $page;
	global $objGrids;
        
        $objRs_CostMethod = new recordset(null,$objConnection);
	$objRs_CostMethod->queryopen("select u_costmethod from u_lgupurchasesetup WHERE COMPANY = '".$_SESSION["company"]."' AND BRANCH = '".$_SESSION["branch"]."'");
	if ($objRs_CostMethod->queryfetchrow("NAME")) {
            $page->setitem("u_costmethod",$objRs_CostMethod->fields["u_costmethod"]);
        }
        
//        $page->setitem("u_date",currentdate());
	return true;
}

function onPrepareAddGPSLGUPurchasing(&$override) { 
	return true;
}

function onBeforeAddGPSLGUPurchasing() { 
	return true;
}

function onAfterAddGPSLGUPurchasing() { 
	return true;
}

function onPrepareEditGPSLGUPurchasing(&$override) { 
	return true;
}

function onBeforeEditGPSLGUPurchasing() { 
	return true;
}

function onAfterEditGPSLGUPurchasing() { 
	global $objConnection;
	global $page;
	global $objGridA;
	global $objGridB;
	global $appdata;
        
        $objGridA->clear();
        $objGridB->clear();
        
        $objRs = new recordset(null,$objConnection);
//        $code = $_SESSION["company"] . "-" . $_SESSION["branch"] . "-" . $objRs->fields["U_WHSCODE"] . "-" . $objRs->fields["U_ITEMCODE"];
        $objRs->queryopen("SELECT U_WHSCODE,U_INSTOCKQTY,U_ORDEREDQTY,U_AVAILABLEQTY,U_AVGPRICE,U_LASTPRICE FROM U_LGUSTOCKCARDSUMMARY WHERE U_ITEMCODE = '".$page->getitemstring("code")."' ");
	while ($objRs->queryfetchrow("NAME")) {
		$objGridA->addrow();
		$objGridA->setitem(null,"u_whscode",$objRs->fields["U_WHSCODE"]);
		$objGridA->setitem(null,"u_instockqty",$objRs->fields["U_INSTOCKQTY"]);
		$objGridA->setitem(null,"u_orderedqty",$objRs->fields["U_ORDEREDQTY"]);
		$objGridA->setitem(null,"u_availableqty",$objRs->fields["U_AVAILABLEQTY"]);
		$objGridA->setitem(null,"u_avgprice",$objRs->fields["U_AVGPRICE"]);
		$objGridA->setitem(null,"u_lastprice",$objRs->fields["U_LASTPRICE"]);
	}
        
        $objRs->queryopen(" SELECT TYPE,U_DATE,DOCNO,U_BPCODE,U_BPNAME,U_QUANTITY,U_UNITCOST,U_COST FROM (
                                SELECT 'GR' as TYPE,A.U_DATE,A.DOCNO,A.U_BPCODE,A.U_BPNAME,B.U_QUANTITY,B.U_UNITCOST,B.U_COST FROM U_LGUPURCHASEDELIVERY A
                                INNER JOIN U_LGUPURCHASEDELIVERYITEMS B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID AND B.U_ITEMCODE = '".$page->getitemstring("code")."' 
                                WHERE DOCSTATUS NOT IN ('CN','D') AND A.COMPANY = '". $_SESSION["company"]."' AND A.BRANCH = '". $_SESSION["branch"]."'
                                UNION ALL
                                SELECT 'GI' as TYPE,A.U_DATE,A.DOCNO,A.U_EMPID,A.U_EMPNAME,B.U_QUANTITY,B.U_ITEMCOST,B.U_LINETOTAL FROM U_LGUGOODSISSUE A
                                INNER JOIN U_LGUGOODSISSUEITEMS B ON A.COMPANY = B.COMPANY AND A.BRANCH = B.BRANCH AND A.DOCID = B.DOCID AND B.U_ITEMCODE = '".$page->getitemstring("code")."'
                                WHERE DOCSTATUS NOT IN ('CN','D') AND A.COMPANY = '". $_SESSION["company"]."' AND A.BRANCH = '". $_SESSION["branch"]."' ) AS X
                            ORDER BY U_DATE DESC ");
	while ($objRs->queryfetchrow("NAME")) {
                $indicator = "";
		$objGridB->addrow();
                $indicator = $objRs->fields["TYPE"];
		$objGridB->setitem(null,"indicator",$objRs->fields["TYPE"]);
		$objGridB->setitem(null,"u_date",$objRs->fields["U_DATE"]);
		$objGridB->setitem(null,"u_docno",$objRs->fields["DOCNO"]);
		$objGridB->setitem(null,"u_bpno",$objRs->fields["U_BPCODE"]);
		$objGridB->setitem(null,"u_bpname",$objRs->fields["U_BPNAME"]);
		$objGridB->setitem(null,"u_qty",$objRs->fields["U_QUANTITY"]);
		$objGridB->setitem(null,"u_unitcost",$objRs->fields["U_UNITCOST"]);
		$objGridB->setitem(null,"u_cost",$objRs->fields["U_COST"]);
	}
	return true;
}

function onPrepareUpdateGPSLGUPurchasing(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUPurchasing() { 
	return true;
}

function onAfterUpdateGPSLGUPurchasing() { 
	return true;
}

function onPrepareDeleteGPSLGUPurchasing(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUPurchasing() { 
	return true;
}

function onAfterDeleteGPSLGUPurchasing() { 
	return true;
}

//$objGrid->columncfl("u_glacctno","OpenCFLfs()");
//$objGrid->columncfl("u_glacctname","OpenCFLfs()");
//
//$objGrid->columnwidth("u_glacctno",20);


$page->businessobject->items->setcfl("u_glacctno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_glacctname","OpenCFLfs()");

$objGridA = new grid("T101");
//$objGridA->addcolumn("docno");
//$objGridA->addcolumn("edit");
$objGridA->addcolumn("u_whscode");
$objGridA->addcolumn("u_instockqty");
$objGridA->addcolumn("u_orderedqty");
$objGridA->addcolumn("u_availableqty");
//$objGridA->addcolumn("u_stockvalue");
$objGridA->addcolumn("u_avgprice");
$objGridA->addcolumn("u_lastprice");

$objGridA->columntitle("u_whscode", "Warehouse");
$objGridA->columntitle("u_instockqty", "In-Stock");
$objGridA->columntitle("u_orderedqty", "Ordered");
$objGridA->columntitle("u_availableqty", "Available");
$objGridA->columntitle("u_stockvalue", "Stock Value");
$objGridA->columntitle("u_avgprice", "Item Cost");
$objGridA->columntitle("u_lastprice", "Last Price");
$objGridA->columntitle("edit", "");
$objGridA->columntitle("docno", "");

$objGridA->columnwidth("u_whscode", 20);
$objGridA->columnwidth("u_instockqty", 15);
$objGridA->columnwidth("u_orderedqty", 15);
$objGridA->columnwidth("u_availableqty", 15);
$objGridA->columnwidth("u_stockvalue", 15);
$objGridA->columnwidth("u_avgprice", 15);
$objGridA->columnwidth("u_lastprice", 15);

$objGridA->columnalignment("u_instockqty", "right");
$objGridA->columnalignment("u_orderedqty", "right");
$objGridA->columnalignment("u_availableqty", "right");
$objGridA->columnalignment("u_stockvalue", "right");
$objGridA->columnalignment("u_avgprice", "right");
$objGridA->columnalignment("u_lastprice", "right");


$objGridA->columndataentry("docno", "type", "label");
$objGridA->columndataentry("u_whscode", "type", "label");
//$objGridA->columnvisibility("docno", false);

$objGridA->dataentry = true;
$objGridA->showactionbar = true;
//$objGridA->automanagecolumnwidth = false;
$objGridA->setaction("reset", false);
$objGridA->setaction("add", false);

$objGridA->width = 1380;
$objGridA->height = 260;

$objGridA->columninput("edit", "type", "link");
$objGridA->columninput("edit", "caption", "[Edit]");
$objGridA->columninput("edit", "dataentrycaption", "[Add Bidder]");


$objGridB = new grid("T201");
//$objGridA->addcolumn("docno");
//$objGridA->addcolumn("edit");
$objGridB->addcolumn("indicator");
$objGridB->addcolumn("u_date");
$objGridB->addcolumn("u_docno");
$objGridB->addcolumn("u_bpno");
$objGridB->addcolumn("u_bpname");
$objGridB->addcolumn("u_qty");
$objGridB->addcolumn("u_unitcost");
$objGridB->addcolumn("u_cost");

$objGridB->columntitle("indicator", "");
$objGridB->columntitle("u_date", "Date");
$objGridB->columntitle("u_docno", "Reference No");
$objGridB->columntitle("u_bpno", "BP Code");
$objGridB->columntitle("u_bpname", "Supplier/Employee Name");
$objGridB->columntitle("u_qty", "Quantity");
$objGridB->columntitle("u_unitcost", "Unit Cost");
$objGridB->columntitle("u_cost", "Line Total");
$objGridB->columntitle("edit", "");
$objGridB->columntitle("docno", "");

$objGridB->columnwidth("indicator", 2);
$objGridB->columnwidth("u_date", 15);
$objGridB->columnwidth("u_docno", 15);
$objGridB->columnwidth("u_bpno", 15);
$objGridB->columnwidth("u_bpname", 50);
$objGridB->columnwidth("u_qty", 15);
$objGridB->columnwidth("u_unitcost", 15);
$objGridB->columnwidth("u_cost", 15);

$objGridB->columnalignment("u_qty", "right");
$objGridB->columnalignment("u_unitcost", "right");
$objGridB->columnalignment("u_cost", "right");
$objGridB->columnalignment("indicator", "center");


$objGridB->columndataentry("docno", "type", "label");
$objGridB->columndataentry("u_whscode", "type", "label");
//$objGridA->columnvisibility("docno", false);

$objGridB->dataentry = false;
$objGridB->showactionbar = true;
$objGridB->automanagecolumnwidth = false;
$objGridB->setaction("reset", false);
$objGridB->setaction("add", false);

$objGridB->width = 1380;
$objGridB->height = 260;
?> 

