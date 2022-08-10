<?php
        $objRs1 = new recordset(null,$objConnection);
		
       if ($page->getitemstring("docstatus")=="O" ) {

                $page->businessobject->items->seteditable("docstatus",false);
               // $page->businessobject->items->seteditable("u_docseries",false);
               // $page->businessobject->items->seteditable("u_feecode",false);
	
             $page->toolbar->addbutton("u_submit","F4 - Post Transaction","PostTransaction()","left");
        }
		
		  if ($page->getitemstring("docstatus")=="C" ) {

                $page->businessobject->items->seteditable("docstatus",false);
               // $page->businessobject->items->seteditable("u_docseries",false);
                //$page->businessobject->items->seteditable("u_gross",false);
	
             $page->toolbar->addbutton("u_submit","Re-Asseesment","u_reassessGPSMotorViolation()","left");
        }

	$objMaster->reportaction = "QS";
//	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

