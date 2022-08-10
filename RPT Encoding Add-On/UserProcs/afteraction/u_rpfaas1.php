<?php
        $page->privatedata["curdate"] = currentdate();
        if (isEditMode()) {
            if ($page->getitemstring("docstatus")!="Encoding") {
                $page->businessobject->items->seteditable("u_bookno",false);
            }
        }
?>