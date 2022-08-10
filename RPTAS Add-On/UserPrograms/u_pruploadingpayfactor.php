<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	require_once ("../common/classes/excel.php");
	include_once("../common/classes/grid.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./utils/postingperiods.php");
	include_once("./sls/users.php");
	include_once("./classes/users.php");
	include_once("./classes/userbranches.php");
	if ($_SESSION["theme"]=="sp" || $_SESSION["theme"]=="sf" || $_SESSION["theme"]=="gps") { 
		$objRs = new recordset(null,$objConnection);
		$objRs->queryopen("SELECT u_font FROM u_prglobalsetting");
		while ($objRs->queryfetchrow("NAME")) {
			$csspath =  "../Addons/GPS/PayRoll Add-On/UserPrograms/".$objRs->fields["u_font"]."/";
		}
	}
?>	

<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/mainheader.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/standard.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/tblbox.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/deobjs.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="<?php echo $csspath ?>css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">

<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>

<script type="text/javascript">
document.write('<style type="text/css">.tabber{display:none;}<\/style>');
var tabberOptions = {
  'onLoad': function(argsObj) { },
  'onClick': function(argsObj) { },
  'addLinkId': true
};
</script>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>

<script>

function resizeObjects() {
	if (document.getElementById("divT1")) {
		document.getElementById("divT1").style.width = browserInnerWidth() - 20 + 'px';
		document.getElementById("divT1").style.height = browserInnerHeight() - 160 + 'px';
		document.getElementById("divHdrT1").style.width = browserInnerWidth() - 39 + 'px';
	}	
}

function formSubmit() {
	if (isInputEmpty("filename")) return false;
	if (document.formData.df_filename.value.substr(document.formData.df_filename.value.length-4,4).toLowerCase() != ".csv") {
		alert("Invalid filename! Only CSV files are allowed.");
		return false;
	}
	document.formData.formAction.value = "a";
	document.formData.submit();
	return false;
}

	function onFormSubmitReturn(action,success,error) {
		if (success) {
			alert("Successfully uploaded Identity");
		} else {
			alert(error);
		}
		
	}
	
</script>

<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="resizeObjects()">
<FORM name="formData" autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']?>?">

<?php

$httpVars['df_company'] = $_SESSION['company'];
$httpVars['df_branch'] = $_SESSION['branch'];

require("./inc/upload.php");
require("./inc/formobjs.php");
require("./inc/formactions.php");
	
	function uploadTimeKeeping($uploadfile) {
		global $httpVars;
		global $objGrid1;
		global $objConnection;
		global $page;
		
		$obju_Identity = new masterdataschema_br(NULL,$objConnection,"u_premploymentinfo");
		$obju_Global = new masterdataschema_br(NULL,$objConnection,"u_prglobalsetting");
		$obju_IdentityHR = new masterdataschema_br(NULL,$objConnection,"u_hremploymentinfo");
		$dir = "D:/sf/";
		
		if (is_dir($dir)){
		  if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
				$objConnection->beginwork();
				$actionReturn = true;
				$_SESSION['errormessage'] = "";
				$uploadfile = 'D:/sf/'.$files;
				$files = substr(str_replace('c:/users/sf/','',$uploadfile),0,31);
				var_dump($file);
				
				/*if(isFileUploaded($uploadfile) == false) return false;
			
				$objExcel = new Excel;
				$objExcel->openxl(realpath($uploadfile),"",""); 
				
				
				$sheet = $files;
				$row = 2;
				$i = 1;
				$rowidx = 1;
				
				$rollback = false;		
				$errormessages = "";
				
				while($content = $objExcel->readrange($sheet,"A".$row.":BB".$row)) {
		
					if(implode('',$content) == "")  break;
		
					$isvalid = true;
					
					$STATUS = ltrim($content[0]);
					$USERID = ltrim($content[1]);
					$USERNAME = ltrim($content[2]);
					$EMAIL = ltrim($content[3]);
					$GENDER = ltrim($content[4]);
					$LASTNAME = ltrim($content[5]);
					$FIRSTNAME = ltrim($content[6]);
					$MI = ltrim($content[7]);
					$NICKNAME = ltrim($content[8]);
					$SUFFIX = ltrim($content[9]);
					$DEPARTMENT = ltrim($content[10]);
					$DIVISION = ltrim($content[11]);
					$LOCATION = ltrim($content[12]);
					$JOBCODE = ltrim($content[13]);
					$HIREDATE = ltrim($content[14]);
					$TIMEZONE = ltrim($content[15]);
					$MANAGER = ltrim($content[16]);
					$HR = ltrim($content[17]);
					$DEFAULT_LOCALE = ltrim($content[18]);
					$EMPID = ltrim($content[19]);
					$TITLE = ltrim($content[20]);
					$BIZ_PHONE = ltrim($content[21]);
					$HOME_PHONE = ltrim($content[22]);
					$CELL_PHONE = ltrim($content[23]);
					$FAX = ltrim($content[24]);
					$ADDR1 = ltrim($content[25]);
					$ADDR2 = ltrim($content[26]);
					$ADDR3 = ltrim($content[27]);
					$CITY = ltrim($content[28]);
					$STATE = ltrim($content[29]);
					$ZIP = ltrim($content[30]);
					$COUNTRY = ltrim($content[31]);
					$REVIEW_FREQ = ltrim($content[32]);
					$LAST_REVIEW_DATE = ltrim($content[33]);
					$CUSTOM01 = ltrim($content[34]);
					$CUSTOM02 = ltrim($content[35]);
					$CUSTOM03 = ltrim($content[36]);
					$CUSTOM04 = ltrim($content[37]);
					$CUSTOM05 = ltrim($content[38]);
					$CUSTOM06 = ltrim($content[39]);
					$CUSTOM07 = ltrim($content[40]);
					$CUSTOM08 = ltrim($content[41]);
					$CUSTOM09 = ltrim($content[42]);
					$CUSTOM10 = ltrim($content[43]);
					$CUSTOM11 = ltrim($content[44]);
					$CUSTOM12 = ltrim($content[45]);
					$CUSTOM13 = ltrim($content[46]);
					$CUSTOM14 = ltrim($content[47]);
					$CUSTOM15 = ltrim($content[48]);
					$MATRIX_MANAGER = ltrim($content[49]);
					$CUSTOM_MANAGER = ltrim($content[50]);
					$PROXY = ltrim($content[51]);
					$SECOND_MANAGER = ltrim($content[52]);
					$LOGIN_METHOD = ltrim($content[53]);
		
					$date = date("Y-m-d",strtotime(ltrim($content[14])));
					
					if($STATUS = 'Active') {
						$currentstatus = 'R';	
					} else {
						$currentstatus = 'IA';
					}
					
					$name = ltrim($content[5]).", ".ltrim($content[6])." ".ltrim($content[7]);
					//var_dump($name);
					//if($PF == "" && $errormessage=="") $errormessage .= " Profit Center is required.";
					//if($ID == "" && $errormessage=="") $errormessage .= " ID is required.";
					//if($PD == "" && $errormessage=="") $errormessage .= " Payroll Due is required.";
					//var_dump($errormessage_msg);
					if ($isvalid) {
						if($obju_Global->getbysql("U_COMPANYNAME='".$CUSTOM04."'")) {
							if($obju_Identity->getbysql("U_LNAME='".$LASTNAME."' AND U_MNAME='".$MI."' AND U_FNAME='".$FIRSTNAME."'")) {
								
							} else {
								//$obju_Identity->setdebug();
								$obju_Identity->prepareadd();
								$obju_Identity->code = getNextSeriesNoByBranch("u_premploymentinfo","93",formatDateToDB(currentdate()),$objConnection,true);
								$obju_Identity->name = $name;
								$obju_Identity->setudfvalue("u_lname",$LASTNAME);
								$obju_Identity->setudfvalue("u_fname",$FIRSTNAME);
								$obju_Identity->setudfvalue("u_mname",$MI);
								$obju_Identity->setudfvalue("u_sname",$SUFFIX);
								$obju_Identity->setudfvalue("u_gender",$GENDER);
								$obju_Identity->setudfvalue("u_currentstatus",$currentstatus);
								
								//$obju_IdentityHR->setdebug();
								$obju_IdentityHR->prepareadd();
								$obju_IdentityHR->code = $obju_Identity->code;
								$obju_IdentityHR->setudfvalue("u_telno",$BIZ_PHONE);
								$obju_IdentityHR->setudfvalue("u_mobileno",$CELL_PHONE);
								$obju_IdentityHR->setudfvalue("u_email",$EMAIL);
								$obju_IdentityHR->setudfvalue("u_street",$ADDR1);
								$obju_IdentityHR->setudfvalue("u_city",$CITY);
								$obju_IdentityHR->setudfvalue("u_province",$STATE);
								$obju_IdentityHR->setudfvalue("u_zipcode",$ZIP);
								//$obju_IdentityHR->setudfvalue("u_branch",$COUNTRY);
								$actionReturn = $obju_IdentityHR->add();
								//var_dump($obju_IdentityHR->sqls);
								
								$actionReturn = $obju_Identity->add();
								//var_dump($obju_Identity->sqls);
								if (!$actionReturn) break;
								
								if (!$actionReturn) break;
								
							}
						}
					} else {
						return raiseError($errormessage_msg);
					}
					
					$row++;
					$i++;
				} # end while
				if ($errormessages=="") $objConnection->commit();
				else $objConnection->rollback();
				if ($errormessages!="") {
					$_SESSION['errormessage'] = $errormessage_msg;
				}
				
				$objExcel->closeXL();
				unset($objExcel);
				//if ($actionReturn) $actionReturn = raiseError("here");
				return $actionReturn;
				*/
			}
			closedir($dh);
		  }
		}	
	}
	
	function onFormAdd() {
		global $objGrid1;
		$actionReturn = true;
		$uploadfile = "upload/" . basename($_FILES['df_filename']['name']);
		
		$actionReturn = uploadTimeKeeping($uploadfile);
		
		//if ($objGrid1->recordcount>0) $actionReturn = raiseError("One or more Record is invalid.");
		
		return $actionReturn;
			
	} # end onFormAdd()

	saveErrorMsg();
	
	$page->settimeout(0);
	
	$imgfile =  "../AddOns/GPS/PayRoll Add-On/UserPrograms/images/biometrix.png";
	$imgfile2 = "../AddOns/GPS/PayRoll Add-On/UserPrograms/imgs/leftArrow.gif";

?>

<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
	<tr><td>&nbsp;</td>
	</tr>
	<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
       	<tr>
       	          <td class="labelPageHeader" >&nbsp;Upload: Attendance Summary Sheet</td>
                  <td>&nbsp; </td>
	  	</tr>
	</table></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="10%">&nbsp;<label class="lblobjs">Filename</label></td>
             <td><input type="file" size="40" <?php genInputTextHtml(array("name"=>"filename")) ?> onClick="this.blur()" /></td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="3">
           	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="25"><img id="PhotoImg" height=60 src="<?php echo $imgfile; ?>" width=60 style="cursor:pointer;cursor:hand" onClick="formSubmit();return false;" alt="Click"></td>
                    <td width="25"><img id="PhotoImg" height=25 src="<?php echo $imgfile2; ?>" width=25 ></td>
                    <td align="left"><label class="lblobjs"><b>&nbsp;Upload Attendance Summary Sheet ----</b></label></td>
                </tr>
            </table></td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4"></td>
		</tr>
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
</body>

<?php include("setbottomtoolbar.php"); ?>
<?php include("setstatusmsg.php"); ?>

<script>
resizeObjects();
</script>