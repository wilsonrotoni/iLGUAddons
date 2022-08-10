<?php
    
       
	if (isEditMode()) {
          $objGrids[0]->columnvisibility("u_issuedto",true);
          $objGrids[0]->columnattributes("u_issuedto","disabled");
          $objGrids[0]->dataentry = false;
	} else {
            $page->toolbar->addbutton("u_submit","Submit - F4","formSubmit()","left");
        }

?> 

