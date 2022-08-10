<?php
 
if (isEditMode()) {
        if ($page->getitemstring("docstatus")=="O" && ($page->getitemstring("docstatus")=="CN" || $page->getitemstring("docstatus")=="D")) {
                            $page->toolbar->addbutton("u_payment","Payment","u_paymentGPSLGUBarangay()","left");
        }
}

?> 

