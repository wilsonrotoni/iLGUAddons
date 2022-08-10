<?php

$objGridDetailDocno = new grid("T20");
//$objGridDetail->addcolumn("chkno");
$objGridDetailDocno->addcolumn("branch");
$objGridDetailDocno->addcolumn("docno");
$objGridDetailDocno->addcolumn("docdate");
//$objGridDetailDocno->addcolumn("bpname");
$objGridDetailDocno->addcolumn("remarks");

//$objGridDetail->columntitle("chkno","*");
$objGridDetailDocno->columntitle("branch","Branch Code");
$objGridDetailDocno->columntitle("docno","Document No.");
$objGridDetailDocno->columntitle("docdate","Document Date");
//$objGridDetailDocno->columntitle("bpname","Vendor Name");
$objGridDetailDocno->columntitle("remarks","Remarks");

//$objGridDetail->columnwidth("chkno",3);
$objGridDetailDocno->columnwidth("branch",16);
$objGridDetailDocno->columnwidth("docno",16);
$objGridDetailDocno->columnwidth("docdate",12);
//$objGridDetailDocno->columnwidth("bpname",25);
$objGridDetailDocno->columnwidth("remarks",50);
//$objGridDetail->columninput("chkno","type","checkbox");
//$objGridDetail->columninput("chkno","value",1);

$objGridDetailDocno->automanagecolumnwidth = false;

$objGridDetailDocno->width = 760;
$objGridDetailDocno->height = 300;

$objGridDetailDocno->selectionmode = 2;

$objGridDetail = new grid("T10");
//$objGridDetail->addcolumn("chkno");
$objGridDetail->addcolumn("branch");
$objGridDetail->addcolumn("itemcode");
$objGridDetail->addcolumn("itemdesc");
$objGridDetail->addcolumn("quantity");
$objGridDetail->addcolumn("openquantity");
$objGridDetail->addcolumn("unitprice");
$objGridDetail->addcolumn("linetotal");
$objGridDetail->addcolumn("whse");
$objGridDetail->addcolumn("lineid");
$objGridDetail->addcolumn("docid");
$objGridDetail->addcolumn("docno");
$objGridDetail->addcolumn("objcode");
$objGridDetail->addcolumn("remarks");

//$objGridDetail->columntitle("chkno","*");
$objGridDetail->columntitle("branch","Branch Code");
$objGridDetail->columntitle("itemcode","Item Code");
$objGridDetail->columntitle("itemdesc","Item Desc");
$objGridDetail->columntitle("quantity","Quantity");
$objGridDetail->columntitle("openquantity","Remaining Qty");
$objGridDetail->columntitle("unitprice","Unit Price");
$objGridDetail->columntitle("whse","Ware House");
$objGridDetail->columntitle("linetotal","Line Total");
$objGridDetail->columntitle("remarks","Remarks");


//$objGridDetail->columnwidth("chkno",3);
$objGridDetail->columnwidth("branch",15);
$objGridDetail->columnwidth("itemcode",20);
$objGridDetail->columnwidth("itemdesc",30);
$objGridDetail->columnwidth("quantity",12);
$objGridDetail->columnwidth("openquantity",12);
$objGridDetail->columnwidth("unitprice",12);
$objGridDetail->columnwidth("linetotal",12);
$objGridDetail->columnwidth("whse",12);
$objGridDetail->columnwidth("lineid",12);
$objGridDetail->columnwidth("docid",12);
$objGridDetail->columnwidth("docno",12);
$objGridDetail->columnwidth("objcode",12);
$objGridDetail->columnwidth("remarks",50);

$objGridDetail->columnalignment("quantity","right");
$objGridDetail->columnalignment("openquantity","right");
$objGridDetail->columnalignment("unitprice","right");
$objGridDetail->columnalignment("linetotal","right");

$objGridDetail->selectionmode = 2;

$objGridDetail->columnvisibility("lineid",false);
$objGridDetail->columnvisibility("docid",false);
$objGridDetail->columnvisibility("docno",false);
$objGridDetail->columnvisibility("objcode",false);
$objGridDetail->columnvisibility("remarks",false);

$objGridDetail->columninput("quantity","type","text");
$objGridDetail->columninput("unitprice","type","text");
$objGridDetail->columninput("whse","type","text");
$objGridDetail->columncfl("whse","OpenCFLfs()");
$objGridDetail->automanagecolumnwidth = false;
$objGridDetail->dataentry = false;
$objGridDetail->width = 760;
$objGridDetail->height = 300;

$page->toolbar->addbutton("cf","Copy From Purchase Request","formSubmit('cf')","left");

?>