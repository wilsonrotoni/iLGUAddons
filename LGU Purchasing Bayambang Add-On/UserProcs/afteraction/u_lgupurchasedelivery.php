<?php


    if (isEditMode()) {
         $page->businessobject->items->seteditable("docstatus",false);
        if ($page->getitemstring("docstatus") != "D") {
            $page->businessobject->items->seteditable("u_drno",false);
            $page->businessobject->items->seteditable("u_invoiceno",false);
        } else {
           
        }
    }
?> 

