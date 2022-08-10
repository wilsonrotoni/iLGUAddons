<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUBuilding");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUBuilding");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUBuilding");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUBuilding");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUBuilding");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUBuilding");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUBuilding");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUBuilding");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUBuilding");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUBuilding");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUBuilding");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUBuilding");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUBuilding");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUBuilding");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUBuilding");
$appdata= array();
function onCustomActionGPSLGUBuilding($action) {
	return true;
}

function onBeforeDefaultGPSLGUBuilding() { 
        global $page;
	global $appdata;
	$appdata["u_bpno"] = $page->getitemstring("u_bpno");
	$appdata["u_apptype"] = $page->getitemstring("u_apptype");
	$appdata["u_businessname"] = $page->getitemstring("u_businessname");
	return true;
}

function onAfterDefaultGPSLGUBuilding() { 
        global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
		
        
        $objGrids[0]->clear();
	$objGrids[1]->clear();
	$objGrids[2]->clear();
	$objGrids[3]->clear();
	$objGrids[4]->clear();
        

//                $page->setitem("u_orsfamt",formatNumericAmount($engitotal));
                $objRs = new recordset(null,$objConnection);
                $objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM U_bldgpermitcompute A
                                    INNER JOIN U_bldgpermitcomputeitems B ON A.CODE = B.CODE
                                    ORDER BY A.u_seqno,b.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[0]->addrow();
                        $objGrids[0]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[0]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                        $objGrids[0]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                        $objGrids[0]->setitem(null,"u_quantity",0);
                        $objGrids[0]->setitem(null,"u_linetotal",formatNumericAmount(0));
        //		$sanitarytotal+=$objRs->fields["u_amount"];
                }

                $objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM U_mechanicalcompute A
                                    INNER JOIN u_mechanicalcomputeitems B ON A.CODE = B.CODE
                                    ORDER BY A.u_seqno,b.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[1]->addrow();
                        $objGrids[1]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[1]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                        $objGrids[1]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                        $objGrids[1]->setitem(null,"u_quantity",0);
                        $objGrids[1]->setitem(null,"u_linetotal",formatNumericAmount(0));
        //		$sanitarytotal+=$objRs->fields["u_amount"];
                }

                $objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM u_plumbingcompute A
                                    INNER JOIN u_plumbingcomputeitems B ON A.CODE = B.CODE
                                    ORDER BY A.u_seqno,b.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[2]->addrow();
                        $objGrids[2]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[2]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                        $objGrids[2]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                        $objGrids[2]->setitem(null,"u_quantity",0);
                        $objGrids[2]->setitem(null,"u_linetotal",formatNumericAmount(0));
        //		$sanitarytotal+=$objRs->fields["u_amount"];
                }

                $objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM u_electricalcompute A
                                    INNER JOIN u_electricalcomputeitems B ON A.CODE = B.CODE
                                    ORDER BY A.u_seqno,b.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[3]->addrow();
                        $objGrids[3]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[3]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                        $objGrids[3]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                        $objGrids[3]->setitem(null,"u_quantity",0);
                        $objGrids[3]->setitem(null,"u_linetotal",formatNumericAmount(0));
        //		$sanitarytotal+=$objRs->fields["u_amount"];
                }

                $objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM u_signagecompute A
                                    INNER JOIN u_signagecomputeitems B ON A.CODE = B.CODE
                                    ORDER BY A.u_seqno,b.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[4]->addrow();
                        $objGrids[4]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[4]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                        $objGrids[4]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                        $objGrids[4]->setitem(null,"u_quantity",0);
                        $objGrids[4]->setitem(null,"u_linetotal",formatNumericAmount(0));
        //		$sanitarytotal+=$objRs->fields["u_amount"];
                }
         
	
        
	return true;
}

function onPrepareAddGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeAddGPSLGUBuilding() { 
	return true;
}

function onAfterAddGPSLGUBuilding() { 
	return true;
}

function onPrepareEditGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeEditGPSLGUBuilding() { 
	return true;
}

function onAfterEditGPSLGUBuilding() { 
	return true;
}

function onPrepareUpdateGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUBuilding() { 
	return true;
}

function onAfterUpdateGPSLGUBuilding() { 
	return true;
}

function onPrepareDeleteGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUBuilding() { 
	return true;
}

function onAfterDeleteGPSLGUBuilding() { 
	return true;
}
$addoptions = false;
$deleteoption = false;


$page->businessobject->items->seteditable("u_bldgtotal",false);
$page->businessobject->items->seteditable("u_mechtotal",false);
$page->businessobject->items->seteditable("u_plumbingtotal",false);
$page->businessobject->items->seteditable("u_electricaltotal",false);
$page->businessobject->items->seteditable("u_signagetotal",false);

$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->columnwidth("u_code",20);
//$objGrids[1]->columnwidth("u_desc",30);
$objGrids[0]->columninput("u_quantity","type","text");
$objGrids[0]->columndataentry("u_quantity","type","label");
$objGrids[0]->columndataentry("u_unitprice","type","label");
$objGrids[0]->columndataentry("u_linetotal","type","label");
$objGrids[0]->columndataentry("u_desc","type","label");
$objGrids[0]->columndataentry("u_code","type","label");
$objGrids[0]->height = 200;
$objGrids[0]->width = 820;
$objGrids[0]->showactionbar = false;

$objGrids[1]->automanagecolumnwidth = false;
//$objGrids[2]->columnwidth("u_quantity",8);
//$objGrids[2]->columnwidth("u_unitprice",8);
//$objGrids[2]->columnwidth("u_linetotal",8);
$objGrids[1]->columnwidth("u_code",20);
//$objGrids[2]->columnwidth("u_desc",30);
$objGrids[1]->columninput("u_quantity","type","text");
$objGrids[1]->columndataentry("u_quantity","type","label");
$objGrids[1]->columndataentry("u_unitprice","type","label");
$objGrids[1]->columndataentry("u_linetotal","type","label");
$objGrids[1]->columndataentry("u_desc","type","label");
$objGrids[1]->columndataentry("u_code","type","label");
$objGrids[1]->showactionbar = false;

$objGrids[2]->automanagecolumnwidth = false;
//$objGrids[3]->columnwidth("u_quantity",8);
//$objGrids[3]->columnwidth("u_unitprice",8);
//$objGrids[3]->columnwidth("u_linetotal",8);
$objGrids[2]->columnwidth("u_code",20);
//$objGrids[3]->columnwidth("u_desc",30);
$objGrids[2]->columninput("u_quantity","type","text");
$objGrids[2]->columndataentry("u_quantity","type","label");
$objGrids[2]->columndataentry("u_unitprice","type","label");
$objGrids[2]->columndataentry("u_linetotal","type","label");
$objGrids[2]->columndataentry("u_desc","type","label");
$objGrids[2]->columndataentry("u_code","type","label");
$objGrids[2]->showactionbar = false;

$objGrids[3]->automanagecolumnwidth = false;
//$objGrids[4]->columnwidth("u_quantity",8);
//$objGrids[4]->columnwidth("u_unitprice",8);
//$objGrids[4]->columnwidth("u_linetotal",8);
$objGrids[3]->columnwidth("u_code",20);
//$objGrids[4]->columnwidth("u_desc",30);
$objGrids[3]->columninput("u_quantity","type","text");
$objGrids[3]->columndataentry("u_quantity","type","label");
$objGrids[3]->columndataentry("u_unitprice","type","label");
$objGrids[3]->columndataentry("u_linetotal","type","label");
$objGrids[3]->columndataentry("u_desc","type","label");
$objGrids[3]->columndataentry("u_code","type","label");
$objGrids[3]->showactionbar = false;

$objGrids[4]->automanagecolumnwidth = false;
//$objGrids[5]->columnwidth("u_quantity",8);
//$objGrids[5]->columnwidth("u_unitprice",8);
//$objGrids[5]->columnwidth("u_linetotal",8);
$objGrids[4]->columnwidth("u_code",20);
//$objGrids[5]->columnwidth("u_desc",30);
$objGrids[4]->columninput("u_quantity","type","text");
$objGrids[4]->columndataentry("u_quantity","type","label");
$objGrids[4]->columndataentry("u_unitprice","type","label");
$objGrids[4]->columndataentry("u_linetotal","type","label");
$objGrids[4]->columndataentry("u_desc","type","label");
$objGrids[4]->columndataentry("u_code","type","label");
$objGrids[4]->showactionbar = false;


?>