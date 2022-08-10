<?php

	$u_birthdate = $_GET["birthdate"];
	$u_date = $_GET["date"];
	
	if ($u_birthdate=="" || $u_date=="") {
		$age = array(0,0,0);
	} else {
		$age = getAgeOnDate($u_birthdate,$u_date);
	}	
	$data = '<validate result="1">';
	
	$data = $data . '<age ';
	$data = $data . 'y="' . $age[0] . '" ';
	$data = $data . 'm="' . $age[1] . '" ';
	$data = $data . 'd="' . $age[2] . '" ';
	$data = $data . '></age></validate>';
	
	
	echo $data;


?>
