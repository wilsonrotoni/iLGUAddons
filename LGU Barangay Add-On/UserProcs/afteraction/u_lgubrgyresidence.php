<?php

	
	
	$photopath ="";
	if (isEditMode()) {	

		$photopath = "../Images/".$_SESSION["company"]."/".$_SESSION["mainbranch"]."/LGUBARANGAY/".$page->getitemstring("docno")."/photo";
		$page->privatedata["photopath"] = "../Images/".$_SESSION["company"]."/".$_SESSION["mainbranch"]."/LGUBARANGAY/".$page->getitemstring("docno");
		//var_dump($page->privatedata["photopath"]);
		if (file_exists($photopath . ".png")) $photopath .= ".png?version=".date('YmdHis');
		elseif (file_exists($photopath . ".jpg")) $photopath .= ".jpg?version=".date('YmdHis');
		elseif (file_exists($photopath . ".gif")) $photopath .= ".gif?version=".date('YmdHis');
		elseif (file_exists($photopath . ".bmp")) $photopath .= ".bmp?version=".date('YmdHis');
		else $photopath = "./imgs/photo.jpg";
                
                if($page->getitemstring("u_isvoter")=="0" ){
                    
                }
	}

	$page->privatedata["photodata"] = "";
	$objMaster->reportaction = "QS";
        $pageHeaderStatusText = "Status: ".$page->getitemstring("docstatus");
        
	
?> 

