<?php 

$ftp_server = "https://sftp10.successfactors.com/#/outgoing/";
$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
$login = ftp_login($ftp_conn, "21863766T", "2w8HPguCZE");

// get file list of current directory
$file_list = ftp_nlist($ftp_conn, ".");
var_dump($file_list);

// close connection
ftp_close($ftp_conn);

?>