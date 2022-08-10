<?php

        $page->privatedata["year"] = date('Y');
	if (isEditMode()) {
                        
                        
                if ($page->getitemstring("docstatus")=="C" || $page->getitemstring("docstatus")=="CN" ) {
			$page->setvar("formAccess","R");
                }
                if ($page->getitemstring("docstatus")=="O") {
                  
                }
                
               
	} else {
          
//		$objGrids[0]->columninput("u_taxdue","type","text");
//		$objGrids[0]->columninput("u_sef","type","text");
//		$objGrids[0]->columninput("u_sefdisc","type","text");
//		$objGrids[0]->columninput("u_taxdisc","type","text");
//		$objGrids[0]->columninput("u_penalty","type","text");
//		$objGrids[0]->columninput("u_sefpenalty","type","text");
//                        $objGrids[0]->columnattributes("u_taxdue","disabled");
//                        $objGrids[0]->columnattributes("u_sef","disabled");
//                        $objGrids[0]->columnattributes("u_sefdisc","disabled");
//                        $objGrids[0]->columnattributes("u_taxdisc","disabled");
//                        $objGrids[0]->columnattributes("u_penalty","disabled");
//                        $objGrids[0]->columnattributes("u_sefpenalty","disabled");
                       
                    
//		$objGrids[0]->columninput("u_epsf","type","text");
                
	}
//	$pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
	//$deleteoption = false;
?>