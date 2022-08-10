<?php
        $page->toolbar->setaction("update",false);
        if (isEditMode()) {
        } else {
            $page->toolbar->addbutton("u_submit","Return Receipt","formSubmit()","left");
        }
?> 

