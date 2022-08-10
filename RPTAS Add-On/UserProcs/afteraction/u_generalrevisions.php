<?php

	if (isEditMode()) {
                if ($page->getitemstring("u_status")=="O") {
                        $page->toolbar->addbutton("u_addposseries","Execute GR","u_executeGeneralRevisionGPSRPTAS()","left");
		} else {
                        $page->businessobject->items->seteditable("u_approvedby",false);
                        $page->businessobject->items->seteditable("u_approveddate",false);
                        $page->businessobject->items->seteditable("u_recommendby",false);
                        $page->businessobject->items->seteditable("u_recommenddate",false);
                        $page->businessobject->items->seteditable("u_assessedby",false);
                        $page->businessobject->items->seteditable("u_assesseddate",false);
                } 
                $page->businessobject->items->seteditable("u_revisionyearfrom",false);
                $page->businessobject->items->seteditable("u_revisionyearto",false);
                $page->businessobject->items->seteditable("u_oldbarangay",false);
                $page->businessobject->items->seteditable("u_kind",false);
	} 
	
	//$deleteoption = false;
?>