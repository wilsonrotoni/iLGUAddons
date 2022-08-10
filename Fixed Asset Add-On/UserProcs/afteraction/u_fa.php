<?php
	
	$photopath ="";
	if (isEditMode()) {
		$photopath = "../Images/".$_SESSION["company"]."/".$_SESSION["mainbranch"]."/Fixed Asset/".$page->getitemstring("code")."/".$page->getitemstring("u_empid")."/photo";
		if (file_exists($photopath . ".jpg")) $photopath .= ".jpg";
		elseif (file_exists($photopath . ".png")) $photopath .= ".png";
		elseif (file_exists($photopath . ".gif")) $photopath .= ".gif";
		elseif (file_exists($photopath . ".bmp")) $photopath .= ".bmp";
		else $photopath = "./imgs/photo.jpg";
		
		if ($page->getitemstring("u_branch")!="") $page->setitem("u_branchname",slgetdisplaybrancheslist($page->getitemstring("u_branch")));
		if ($page->getitemstring("u_department")!="") $page->setitem("u_departmentname",slgetdisplaydepartments($page->getitemstring("u_department")));
	}
	
	$objRs = new recordset(null,$objConnection);
	$objRs->queryopen("select u_famgmnt from companies where companycode='".$_SESSION["company"]."'");
	if ($objRs->queryfetchrow("NAME")) {
		$page->privatedata["famgmnt"] = $objRs->fields["u_famgmnt"]; 
	}
	if ($page->privatedata["famgmnt"]=="BR") {
		$page->toolbar->setaction("navigation",false);
		$page->toolbar->setaction("find",false);
//		$httpVars["formAccessData"]["branchtype"]="";	
//		$httpVars["formAccess"] = "";	
	}
?>