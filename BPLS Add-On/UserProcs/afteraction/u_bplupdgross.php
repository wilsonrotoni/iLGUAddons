<?php

	
         if (isEditMode()) {
           $page->setvar("formAccess","R");
            $objGrids[0]->columninput("u_auditedgross","type","label");
            $objGrids[0]->columninput("u_auditedbtaxamount","type","label");
         }
        
	$objMaster->reportaction = "QS";
	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
?> 

