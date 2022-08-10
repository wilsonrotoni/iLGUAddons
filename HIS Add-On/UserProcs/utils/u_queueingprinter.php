<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");

//constant
$rn=chr(13).chr(10);
$esc=chr(27);
$cutpaper=$esc."m";
$bold_on="\x1b\x21\x08";
$bold_off="\x1b\x21\x00";
$doublesize_on="\x1b\x21\x30";
$doublesize_off="\x1b\x21\x00";

$justify_left="\x1b\x61\x00";
$justify_center="\x1b\x61\x01";
$justify_right="\x1b\x61\x02";

$reset=pack('n', 0x1B30);

function printQueueNo($group,$no,$mobileno) {
	global $doublesize_on;
	global $doublesize_off;
	global $bold_on;
	global $bold_off;
	global $cutpaper;
	global $justify_center;

	$objRs = new recordset(null,$objConnection);
	
	$printer = "";
	$objRs->queryopen("select u_queueingprinter from u_hissetup where code='SETUP'");
	if ($objRs->queryfetchrow("NAME")) {
   		$printer = $objRs->fields["u_queueingprinter"];
	}	
	if ($printer=="") return true;
	//return raiseError("Barcode Printer was not maintained.");
	
	
    if($ph = printer_open($printer)) {
		printer_set_option($ph, PRINTER_MODE, 'RAW');
		
		//printer_write($ph, "\x1c\x70\x01\x03\n");
		printer_write($ph, $justify_center);
		//printer_write($ph, $doublesize_on."Queueing System".$doublesize_off);
		//printer_write($ph, "\n");
		//printer_write($ph, "\n");
		printer_write($ph, $doublesize_on.$group.$doublesize_off);
		printer_write($ph, "\n");
		printer_write($ph, "\n");
		printer_write($ph, $doublesize_on.$no.$doublesize_off);
		printer_write($ph, "\n");
		printer_write($ph, "\n");
		if ($mobileno!="") {
			printer_write($ph, $mobileno);
			printer_write($ph, "\n");
			printer_write($ph, "\n");
		}
		printer_write($ph, date("F d, Y h:i:s a"));
		printer_write($ph, "\n");
		printer_write($ph, "\n");
		printer_write($ph, "\n");
		printer_write($ph, "\n");
		printer_write($ph, "\n");
		printer_write($ph, $cutpaper);
			
		printer_close($ph);
    } else return raiseError("Cannot connect to printer [$printer].");
    
	return true;
	
}


?>
