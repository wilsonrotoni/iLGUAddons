<?php


//    if ($page->getitemstring("docstatus")=="O") {
//           $page->toolbar->addbutton("copyinfratemplate","Copy From Template","u_CopyTemplateGPSLGUPurchasing()","left");
//    }
    $objGridA->addbutton("u_edit","[Edit]","u_editwhseGLGPSLGUPurchasing()","right");
    $photopath ="";
    if (isEditMode()) {
                $photopath = "../Images/".$_SESSION["company"]."/".$_SESSION["mainbranch"]."/ITEMS/".$page->getitemstring("code")."/photo";
		$page->privatedata["photopath"] = "../Images/".$_SESSION["company"]."/".$_SESSION["mainbranch"]."/ITEMS/".$page->getitemstring("code");
		//var_dump($page->privatedata["photopath"]);
		if (file_exists($photopath . ".png")) $photopath .= ".png?version=".date('YmdHis');
		elseif (file_exists($photopath . ".jpg")) $photopath .= ".jpg?version=".date('YmdHis');
		elseif (file_exists($photopath . ".gif")) $photopath .= ".gif?version=".date('YmdHis');
		elseif (file_exists($photopath . ".bmp")) $photopath .= ".bmp?version=".date('YmdHis');
		else $photopath = "./imgs/photo.jpg";
    } 
    $page->privatedata["photodata"] = "";
?> 

