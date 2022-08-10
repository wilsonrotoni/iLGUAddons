<?php
	
	$photopath = "./imgs/photo.jpg";
	$page->privatedata["photopath"] = "../Images/".$_SESSION["company"]."/".$_SESSION["branch"]."/HIS/Patients/".$page->getitemstring("code");
	if (isEditMode()) {
		$page->businessobject->items->seteditable("u_series",false);
		$photopath = "../Images/".$_SESSION["company"]."/".$_SESSION["branch"]."/HIS/Patients/".$page->getitemstring("code")."/photo";
		if (file_exists($photopath . ".jpg")) $photopath .= ".jpg";
		elseif (file_exists($photopath . ".png")) $photopath .= ".png";
		elseif (file_exists($photopath . ".gif")) $photopath .= ".gif";
		elseif (file_exists($photopath . ".bmp")) $photopath .= ".bmp";
		else $photopath = "./imgs/photo.jpg";
		
		
		if ($page->getvarstring("formType")!="lnkbtn") {
		} else {
			//$page->setvar("formAccess","R");
			$page->toolbar->setaction("navigation",false);
			$page->toolbar->setaction("find",false);
		}	
		
	} else {
		if ($page->getitemstring("u_series")!=-1) {
			$page->businessobject->items->seteditable("code",false);
		}
	}
	
	if ($page->getitemstring("u_newbornstat")=="0") {
		$page->businessobject->items->seteditable("u_birthtime",false);
	}
	
	$page->privatedata["photodata"] = "";
	
?>