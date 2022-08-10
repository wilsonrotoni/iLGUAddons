<?php
 

//$page->businessobject->events->add->customAction("onCustomActionGPSQUEUING");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSQUEUING");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSQUEUING");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSQUEUING");
$page->businessobject->events->add->beforeAdd("onBeforeAddGPSQUEUING");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSQUEUING");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSQUEUING");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSQUEUING");
$page->businessobject->events->add->afterEdit("onAfterEditGPSQUEUING");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSQUEUING");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSQUEUING");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSQUEUING");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSQUEUING");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSQUEUING");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSQUEUING");

$appdata= array();
function onCustomActionGPSQUEUING($action) {
	return true;
}

function onBeforeDefaultGPSQUEUING() { 
	return true;
}

function onAfterDefaultGPSQUEUING() { 
        global $objConnection;
	global $page;
        global $objGridA;
       
        
        $page->setitem("u_cashiername",$_SESSION["userid"]);
	 $page->setitem("docstatus",1);
        
        $objGridA->clear();
        $objRs = new recordset(null,$objConnection);
//	$objRs->setdebug();
	$objRs->queryopen("SELECT DOCNO,U_CUSTNAME, U_CASHIERNAME, U_BILLNO,DOCSTATUS from U_QUEUING where DOCSTATUS = 1");
//	var_dump($objRs->sqls);
	
	while ($objRs->queryfetchrow("NAME")) {
          
		$objGridA->addrow();
		$objGridA->setitem(null,"docno",$objRs->fields["DOCNO"]);
                $objGridA->setitem(null,"u_custname",$objRs->fields["U_CUSTNAME"]);
                $objGridA->setitem(null,"u_cashiername",$objRs->fields["U_CASHIERNAME"]);
                $objGridA->setitem(null,"u_billno",$objRs->fields["U_BILLNO"]);
          
		
	}
	return true;
}

function onPrepareAddGPSQUEUING(&$override) { 
	return true;
}

function onBeforeAddGPSQUEUING() { 
    	global $objConnection;
        global $page;
        
     $objRsUpdate = new recordset("queuing",$objConnection);
     $company = $_SESSION["company"];
     $branch = $_SESSION["branch"];
     $cashier=$page->getitemstring("u_cashiername");
     $update_query = "UPDATE";
     $set_query = "SET docstatus=0";
     $where_query = " WHERE u_cashiername = '$cashier' and docstatus = 1";
      $objRsUpdate->executesql($update_query." u_queuing ".$set_query.$where_query,false);
 
/*				
               $objRs = new recordset(null,$objConnection);

           if ($actionReturn) $actionReturn = $objRs->executesql("update u_queuing set docstatus = 0 where DOCSTATUS = 1 and u_cashiername = ".$page->getitemstring("u_cashiername")."");
*/
	return true;
}

function onAfterAddGPSQUEUING() { 
	return true;
}

function onPrepareEditGPSQUEUING(&$override) { 
	return true;
}

function onBeforeEditGPSQUEUING() { 
	return true;
}

function onAfterEditGPSQUEUING() { 
    
    global $objConnection;
	global $page;
        global $objGridA;
       
        
       
        $objGridA->clear();
        $objRs = new recordset(null,$objConnection);
//	$objRs->setdebug();
	$objRs->queryopen("SELECT DOCNO,U_CUSTNAME, U_CASHIERNAME, U_BILLNO,DOCSTATUS from U_QUEUING where DOCSTATUS = 1");
//	var_dump($objRs->sqls);
	
	while ($objRs->queryfetchrow("NAME")) {
          
		$objGridA->addrow();
		$objGridA->setitem(null,"docno",$objRs->fields["DOCNO"]);
                $objGridA->setitem(null,"u_custname",$objRs->fields["U_CUSTNAME"]);
                $objGridA->setitem(null,"u_cashiername",$objRs->fields["U_CASHIERNAME"]);
                $objGridA->setitem(null,"u_billno",$objRs->fields["U_BILLNO"]);
          
		
	}
   
       
	return true;
}

function onPrepareUpdateGPSQUEUING(&$override) { 
	return true;
}

function onBeforeUpdateGPSQUEUING() { 
	return true;
}

function onAfterUpdateGPSQUEUING() { 
	return true;
}

function onPrepareDeleteGPSQUEUING(&$override) { 
	return true;
}

function onBeforeDeleteGPSQUEUING() { 
	return true;
}

function onAfterDeleteGPSQUEUING() { 
	return true;
}


$addoptions = false;
$page->toolbar->setaction("add",false);
$page->toolbar->setaction("navigation",false);
$page->toolbar->setaction("update",false);

$page->businessobject->items->setcfl("u_billno","OpenCFLfs()");




        $objGridA = new grid("T101");
	$objGridA->addcolumn("docno");
	$objGridA->addcolumn("u_cashiername");
	$objGridA->addcolumn("u_billno");
	$objGridA->addcolumn("u_custname");

	
	$objGridA->columntitle("docno","Queuing No.");
	$objGridA->columntitle("u_cashiername","Cashier.");
	$objGridA->columntitle("u_billno","Bill Number");
	$objGridA->columntitle("u_custname","Customer Name");
	
	
	$objGridA->columnwidth("indicator",-1);
	$objGridA->columnwidth("docno",15);
	$objGridA->columnwidth("u_cashiername",35);
	$objGridA->columnwidth("u_billno",15);
	$objGridA->columnwidth("u_custname",50);

	
	$objGridA->columnsortable("docno",true);
	$objGridA->columnsortable("u_cashiername",true);
	$objGridA->columnsortable("u_billno",true);
	$objGridA->columnsortable("u_custname",true);

/*
	$objGrid->addcolumn("print");
	$objGrid->columntitle("print","");
	$objGrid->columnalignment("print","center");
	$objGrid->columninput("print","type","link");
	$objGrid->columninput("print","caption","Print");
	$objGrid->columnwidth("print",5);
	$objGrid->columnvisibility("print",false);
	$objGrid->addcolumn("action");
	$objGrid->columntitle("action","");
	$objGrid->columnalignment("action","center");
	$objGrid->columninput("action","type","link");
	$objGrid->columninput("action","caption","");
	$objGrid->columnwidth("action",7);
	*/
	$objGridA->columnvisibility("u_municipality",false);
	$objGridA->columnvisibility("u_city",false);
	$objGridA->columnvisibility("u_province",false);
	//$objGrid->columnvisibility("action",false);
	//$objGrid->columnlnkbtn("docno","OpenLnkBtnu_hispatients()");
	$objGridA->automanagecolumnwidth = false;

//$objGrids[1]->width = 520;
//$objGrids[1]->height = 280;

?> 

