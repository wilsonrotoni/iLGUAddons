<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSBPLS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSBPLS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSBPLS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSBPLS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSBPLS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSBPLS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSBPLS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSBPLS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSBPLS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSBPLS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSBPLS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSBPLS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSBPLS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSBPLS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSBPLS");

function onCustomActionGPSBPLS($action) {
	return true;
}

function onBeforeDefaultGPSBPLS() { 
	return true;
}

function onAfterDefaultGPSBPLS() { 
        global $objConnection;
	global $page;
	global $objGrids;
	
        
        $objRs_uLGUSetup = new recordset(null,$objConnection);
	$objRs_uLGUSetup->queryopen("select A.u_MUNICIPALITY, A.u_province from U_LGUSETUP A");
	if ($objRs_uLGUSetup->queryfetchrow("NAME")) {
		$page->setitem("u_bcity",$objRs_uLGUSetup->fields["u_MUNICIPALITY"]);
		$page->setitem("u_bprovince",$objRs_uLGUSetup->fields["u_province"]);
	}
        $page->setitem("u_year",date('Y'));
        $page->setitem("u_planappdate",currentdate());
        $page->setitem("u_engiappdate",currentdate());
        $page->setitem("u_rhuappdate",currentdate());
        
        $objGrids[0]->clear();
	$objGrids[1]->clear();
	$objGrids[2]->clear();
	$objGrids[3]->clear();
        
        $engitotal=0;
        $objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select code, name, u_amount from u_bldgfees order by u_seqno asc, name asc");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[0]->addrow();
		$objGrids[0]->setitem(null,"u_feecode",$objRs->fields["code"]);
		$objGrids[0]->setitem(null,"u_feedesc",$objRs->fields["name"]);
		$objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
		$engitotal+=$objRs->fields["u_amount"];
	}
        
        $page->setitem("u_engiasstotal",formatNumericAmount($engitotal));
        
        $plantotal=0;	
	$objRs->queryopen("select code, name, u_amount from u_zoningfees order by u_seqno asc, name asc");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[1]->addrow();
		$objGrids[1]->setitem(null,"u_feecode",$objRs->fields["code"]);
		$objGrids[1]->setitem(null,"u_feedesc",$objRs->fields["name"]);
		$objGrids[1]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
		$plantotal+=$objRs->fields["u_amount"];
	}
        
        $page->setitem("u_planasstotal",formatNumericAmount($plantotal));
        
        $sanitarytotal=0;	
	$objRs->queryopen("select code, name, u_amount from u_sanitaryfees order by u_seqno asc, name asc");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[2]->addrow();
		$objGrids[2]->setitem(null,"u_feecode",$objRs->fields["code"]);
		$objGrids[2]->setitem(null,"u_feedesc",$objRs->fields["name"]);
		$objGrids[2]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
		$sanitarytotal+=$objRs->fields["u_amount"];
	}
        $page->setitem("u_rhuasstotal",formatNumericAmount($sanitarytotal));
         
	$objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM U_bldgpermitcompute A
                            INNER JOIN U_bldgpermitcomputeitems B ON A.CODE = B.CODE
                            ORDER BY A.CODE");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[3]->addrow();
		$objGrids[3]->setitem(null,"u_code",$objRs->fields["code"]);
		$objGrids[3]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
		$objGrids[3]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
		$objGrids[3]->setitem(null,"u_quantity",0);
		$objGrids[3]->setitem(null,"u_linetotal",formatNumericAmount(0));
//		$sanitarytotal+=$objRs->fields["u_amount"];
	}
         
	$objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM U_mechanicalcompute A
                            INNER JOIN u_mechanicalcomputeitems B ON A.CODE = B.CODE
                            ORDER BY A.CODE");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[4]->addrow();
		$objGrids[4]->setitem(null,"u_code",$objRs->fields["code"]);
		$objGrids[4]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
		$objGrids[4]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
		$objGrids[4]->setitem(null,"u_quantity",0);
		$objGrids[4]->setitem(null,"u_linetotal",formatNumericAmount(0));
//		$sanitarytotal+=$objRs->fields["u_amount"];
	}
         
	$objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM u_plumbingcompute A
                            INNER JOIN u_plumbingcomputeitems B ON A.CODE = B.CODE
                            ORDER BY A.CODE");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[5]->addrow();
		$objGrids[5]->setitem(null,"u_code",$objRs->fields["code"]);
		$objGrids[5]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
		$objGrids[5]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
		$objGrids[5]->setitem(null,"u_quantity",0);
		$objGrids[5]->setitem(null,"u_linetotal",formatNumericAmount(0));
//		$sanitarytotal+=$objRs->fields["u_amount"];
	}
         
	$objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM u_electricalcompute A
                            INNER JOIN u_electricalcomputeitems B ON A.CODE = B.CODE
                            ORDER BY A.CODE");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[6]->addrow();
		$objGrids[6]->setitem(null,"u_code",$objRs->fields["code"]);
		$objGrids[6]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
		$objGrids[6]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                $objGrids[6]->setitem(null,"u_quantity",0);
		$objGrids[6]->setitem(null,"u_linetotal",formatNumericAmount(0));
//		$sanitarytotal+=$objRs->fields["u_amount"];
	}
         
	$objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM u_signagecompute A
                            INNER JOIN u_signagecomputeitems B ON A.CODE = B.CODE
                            ORDER BY A.CODE");
	while ($objRs->queryfetchrow("NAME")) {
		$objGrids[7]->addrow();
		$objGrids[7]->setitem(null,"u_code",$objRs->fields["code"]);
		$objGrids[7]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
		$objGrids[7]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                $objGrids[7]->setitem(null,"u_quantity",0);
		$objGrids[7]->setitem(null,"u_linetotal",formatNumericAmount(0));
//		$sanitarytotal+=$objRs->fields["u_amount"];
	}
        
       
        
        
	return true;
}

function onPrepareAddGPSBPLS(&$override) { 
	return true;
}

function onBeforeAddGPSBPLS() { 
	return true;
}

function onAfterAddGPSBPLS() { 
	return true;
}

function onPrepareEditGPSBPLS(&$override) { 
	return true;
}

function onBeforeEditGPSBPLS() { 
	return true;
}

function onAfterEditGPSBPLS() { 
	return true;
}

function onPrepareUpdateGPSBPLS(&$override) { 
	return true;
}

function onBeforeUpdateGPSBPLS() { 
	return true;
}

function onAfterUpdateGPSBPLS() { 
	return true;
}

function onPrepareDeleteGPSBPLS(&$override) { 
	return true;
}

function onBeforeDeleteGPSBPLS() { 
	return true;
}

function onAfterDeleteGPSBPLS() { 
	return true;
}

$page->businessobject->items->setcfl("u_rhuappdate","Calendar");
$page->businessobject->items->setcfl("u_engiappdate","Calendar");
$page->businessobject->items->setcfl("u_planappdate","Calendar");

$page->businessobject->items->seteditable("docstatus",false);
$objGrids[0]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[0]->columncfl("u_feedesc","OpenCFLfs()");
$objGrids[0]->columnwidth("u_feecode",12);
$objGrids[0]->columnwidth("u_feedesc",30);
$objGrids[0]->width = 600;

$objGrids[1]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[1]->columncfl("u_feedesc","OpenCFLfs()");

$objGrids[2]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[2]->columncfl("u_feedesc","OpenCFLfs()");

//$objGrids[3]->automanagecolumnwidth = true;
$objGrids[3]->columnwidth("u_quantity",8);
$objGrids[3]->columnwidth("u_unitprice",8);
$objGrids[3]->columnwidth("u_linetotal",8);
$objGrids[3]->columnwidth("u_code",12);
$objGrids[3]->columnwidth("u_desc",30);
$objGrids[3]->columninput("u_quantity","type","text");
$objGrids[3]->columndataentry("u_quantity","type","label");
$objGrids[3]->columndataentry("u_unitprice","type","label");
$objGrids[3]->columndataentry("u_linetotal","type","label");
$objGrids[3]->columndataentry("u_desc","type","label");
$objGrids[3]->columndataentry("u_code","type","label");
$objGrids[3]->height = 200;
$objGrids[3]->width = 820;
$objGrids[3]->showactionbar = false;
//$objGrids[3]->automanagecolumnwidth = true;
$objGrids[4]->columnwidth("u_quantity",8);
$objGrids[4]->columnwidth("u_unitprice",8);
$objGrids[4]->columnwidth("u_linetotal",8);
$objGrids[4]->columnwidth("u_code",12);
$objGrids[4]->columnwidth("u_desc",30);
$objGrids[4]->columninput("u_quantity","type","text");
$objGrids[4]->columndataentry("u_quantity","type","label");
$objGrids[4]->columndataentry("u_unitprice","type","label");
$objGrids[4]->columndataentry("u_linetotal","type","label");
$objGrids[4]->columndataentry("u_desc","type","label");
$objGrids[4]->columndataentry("u_code","type","label");
$objGrids[4]->height = 200;
$objGrids[4]->width = 820;
$objGrids[4]->showactionbar = false;
//$objGrids[3]->automanagecolumnwidth = true;
$objGrids[5]->columnwidth("u_quantity",8);
$objGrids[5]->columnwidth("u_unitprice",8);
$objGrids[5]->columnwidth("u_linetotal",8);
$objGrids[5]->columnwidth("u_code",12);
$objGrids[5]->columnwidth("u_desc",30);
$objGrids[5]->columninput("u_quantity","type","text");
$objGrids[5]->columndataentry("u_quantity","type","label");
$objGrids[5]->columndataentry("u_unitprice","type","label");
$objGrids[5]->columndataentry("u_linetotal","type","label");
$objGrids[5]->columndataentry("u_desc","type","label");
$objGrids[5]->columndataentry("u_code","type","label");
$objGrids[5]->height = 200;
$objGrids[5]->width = 820;
$objGrids[5]->showactionbar = false;
//$objGrids[3]->automanagecolumnwidth = true;
$objGrids[6]->columnwidth("u_quantity",8);
$objGrids[6]->columnwidth("u_unitprice",8);
$objGrids[6]->columnwidth("u_linetotal",8);
$objGrids[6]->columnwidth("u_code",12);
$objGrids[6]->columnwidth("u_desc",30);
$objGrids[6]->columninput("u_quantity","type","text");
$objGrids[6]->columndataentry("u_quantity","type","label");
$objGrids[6]->columndataentry("u_unitprice","type","label");
$objGrids[6]->columndataentry("u_linetotal","type","label");
$objGrids[6]->columndataentry("u_desc","type","label");
$objGrids[6]->columndataentry("u_code","type","label");
$objGrids[6]->height = 200;
$objGrids[6]->width = 820;
$objGrids[6]->showactionbar = false;
//$objGrids[3]->automanagecolumnwidth = true;
$objGrids[7]->columnwidth("u_quantity",8);
$objGrids[7]->columnwidth("u_unitprice",8);
$objGrids[7]->columnwidth("u_linetotal",8);
$objGrids[7]->columnwidth("u_code",12);
$objGrids[7]->columnwidth("u_desc",30);
$objGrids[7]->columninput("u_quantity","type","text");
$objGrids[7]->columndataentry("u_quantity","type","label");
$objGrids[7]->columndataentry("u_unitprice","type","label");
$objGrids[7]->columndataentry("u_linetotal","type","label");
$objGrids[7]->columndataentry("u_desc","type","label");
$objGrids[7]->columndataentry("u_code","type","label");
$objGrids[7]->height = 200;
$objGrids[7]->width = 820;
$objGrids[7]->showactionbar = false;

$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);

?> 

