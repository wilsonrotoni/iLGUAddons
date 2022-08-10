<?php
	
	$photopath ="";
	if (isEditMode()) {
		$photopath = "../Images/".$_SESSION["company"]."/".$_SESSION["branch"]."/HIS/Doctors/".$page->getitemstring("code")."/photo";
		if (file_exists($photopath . ".jpg")) $photopath .= ".jpg";
		elseif (file_exists($photopath . ".png")) $photopath .= ".png";
		elseif (file_exists($photopath . ".gif")) $photopath .= ".gif";
		elseif (file_exists($photopath . ".bmp")) $photopath .= ".bmp";
		else $photopath = "./imgs/photo.jpg";
		
	}
?>