<?php
        set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_uploadLRAData";
        
	include_once("../common/classes/connection.php");
	require_once("../common/classes/excel.php");
	include_once("../common/classes/grid.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/masterdatalinesschema_br.php");
	include_once("./classes/chartofaccounts.php");
	include_once("./classes/profitcenters.php");
	include_once("./utils/postingperiods.php");
	include_once("./utils/companies.php");
	include_once("./utils/branches.php");
	include_once("./utils/customers.php");
?>

<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo @$_SESSION["theme"] ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo @$_SESSION["theme"] ; ?>.css">

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
	if (document.formData.df_filename.value.substr(document.formData.df_filename.value.length-4,4).toLowerCase() != ".xls" && document.formData.df_filename.value.substr(document.formData.df_filename.value.length-5,5).toLowerCase() != ".xlsx") {
		alert("Invalid filename! Only XLS/XLSX files are allowed.");
		return false;
	}
	document.formData.formAction.value = "a";
	document.formData.submit();
	return false;
}

function onFormSubmitReturn(action,success,error) {
        if (success) {
                alert("Successfully uploaded the file.");
        } else {
                alert(error);
        }

}
	
</script>

<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="resizeObjects()">
<FORM name="formData" autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']?>?">

<?php

$objGrid1 = new grid("T1");
$objGrid1->addcolumn(array('name'=>'propin','description'=>'Proposed PIN','length'=>12));
$objGrid1->addcolumn(array('name'=>'sn','description'=>'Serial No','length'=>10));
$objGrid1->addcolumn(array('name'=>'titleno','description'=>'Title No','length'=>12));
$objGrid1->addcolumn(array('name'=>'titletype','description'=>'Title Type','length'=>15));
$objGrid1->addcolumn(array('name'=>'ownersname','description'=>'Owners Name','length'=>15));
$objGrid1->addcolumn(array('name'=>'ownersaddress','description'=>'Owners Address','length'=>10));
$objGrid1->addcolumn(array('name'=>'planno','description'=>'Plan No','length'=>10));
$objGrid1->addcolumn(array('name'=>'blockno','description'=>'Block No','length'=>10));
$objGrid1->addcolumn(array('name'=>'lotno','description'=>'Lot No','length'=>10));
$objGrid1->addcolumn(array('name'=>'titledate','description'=>'Title Date','length'=>10));
$objGrid1->addcolumn(array('name'=>'location','description'=>'Location','length'=>30));
$objGrid1->addcolumn(array('name'=>'surveyarea','description'=>'Survey Area','length'=>15));
$objGrid1->addcolumn(array('name'=>'octno','description'=>'OCT No','length'=>15));
$objGrid1->addcolumn(array('name'=>'precedingtitle','description'=>'Preceding title','length'=>25));
$objGrid1->addcolumn(array('name'=>'techdesc','description'=>'Technical Description','length'=>150));
$objGrid1->addcolumn(array('name'=>'barangay','description'=>'Barangay','length'=>150));
$objGrid1->addcolumn(array('name'=>'uploadremarks','description'=>'Upload Remarks','length'=>100));
$objGrid1->automanagecolumnwidth=false;
$objGrid1->dataentry = false;

$httpVars['df_company'] = $_SESSION['company'];
$httpVars['df_branch'] = $_SESSION['branch'];

require("./inc/upload.php");
require("./inc/formobjs.php");
require("./inc/formactions.php");
	
	function uploadLegacyIntBrCollections($uploadfile) {
		global $httpVars;
		global $objGrid1;
		global $objConnection;
		global $page;
		$dspcode="";

		$obju_LRA = new documentschema_br(NULL,$objConnection,"u_lra");
		$objProfitCenters = new profitcenters(null,$objConnection);
		
		$objrs = new recordset(null,$objConnection);
		$objConnection->beginwork();
		$actionReturn = true;
		$_SESSION['errormessage'] = "";
		
		if(isFileUploaded($uploadfile) == false) return false;

		$objExcel = new Excel;
		$objExcel->openxl(realpath($uploadfile),"",""); 
		
		
		$sheet = "LRA";
		$row = 1;
		$i = 1;
		$rowidx = 1;
		
		$rollback = false;		
		$errormessages = "";

		$date = "";
		$type = "";
		$remarks = "";
		$apps = array();
		$seqs = array();
		if (isset($objGrid1)) $objGrid1->clear();
		
		$profitcenters = array();
		$error = false;
		
		$subsidiaries = array();
		
		
		
		while($content = $objExcel->readrange($sheet,"A".$row.":P".$row)) {
			if(implode('',$content) == "") break;

			if ($row==1) {
				if ($content[0]!="PROPIN" || $content[1]!="SN" || $content[2]!="TITLE NUMBER" || $content[3]!="TITLE TYPE" || $content[4]!="OWNERS NAME" || 
					$content[5]!="OWNERS ADDRESS" || $content[6]!="PLAN NUMBER" || $content[7]!="BLOCK NUMBER" || $content[8]!="LOT NUMBER" || $content[9]!="TITLE REGISTRATION DATE" || 
					$content[10]!="LOCATION" || $content[11]!="SURVEY AREA" || $content[12]!="OCT NUMBER" || $content[13]!="PRECEDING TITLE" || $content[14]!="TECHNICAL DESCRIPTION" || $content[15]!="BARANGAY"  ) {
					$actionReturn = raiseError("Invalid Header!");
					$error = true;
					break;
				}	
				$row++;
				continue;
			}
			
			$isvalid = true;	
			$idx = 'T1r'.$rowidx;
			$data['df_propin'.$idx] = ltrim($content[0]);
			$data['df_sn'.$idx] = ltrim($content[1]);
			$data['df_titleno'.$idx] = ltrim($content[2]);
			$data['df_titletype'.$idx] = ltrim($content[3]);
			$data['df_ownersname'.$idx] = ltrim($content[4]);
			$data['df_ownersaddress'.$idx] = ltrim($content[5]);
			$data['df_planno'.$idx] = ltrim($content[6]);
			$data['df_blockno'.$idx] = ltrim($content[7]);
			$data['df_lotno'.$idx] = ltrim($content[8]);
			$data['df_titledate'.$idx] = ltrim($content[9]);
			$data['df_location'.$idx] = ltrim($content[10]);
			$data['df_surveyarea'.$idx] = ltrim($content[11]);
			$data['df_octno'.$idx] = ltrim($content[12]);
			$data['df_precedingtitle'.$idx] = ltrim($content[13]);
			$data['df_techdesc'.$idx] = ltrim($content[14]);
			$data['df_barangay'.$idx] = ltrim($content[15]);
			$errormessage = "";
			
			if($data['df_ownersname'.$idx] == "") $errormessage .= " Owners Name is required. ";
			if($data['df_titleno'.$idx] == "") $errormessage .= " Title Number to is required. ";
                        
			if($errormessage != "") {
				$data['df_uploadremarks'.$idx] = "Line #".$row.": " . $errormessage;
				$errormessage = "";
				$error = true;
				$isvalid=false;
			}

			if ($isvalid) {
				$item = array();
				$item["propin"] = $data['df_propin'.$idx];
				$item["sn"] = $data['df_sn'.$idx];
				$item["titleno"] = $data['df_titleno'.$idx];
				$item["titletype"] = $data['df_titletype'.$idx];
				$item["ownersname"] = $data['df_ownersname'.$idx];
				$item["ownersaddress"] = $data['df_ownersaddress'.$idx];
				$item["planno"] = $data['df_planno'.$idx];
				$item["blockno"] = $data['df_blockno'.$idx];
				$item["lotno"] = $data['df_lotno'.$idx];
				$item["titledate"] = $data['df_titledate'.$idx];
				$item["location"] = $data['df_location'.$idx];
				$item["surveyarea"] = $data['df_surveyarea'.$idx];
				$item["octno"] = $data['df_octno'.$idx];
				$item["precedingtitle"] = $data['df_precedingtitle'.$idx];
				$item["techdesc"] = $data['df_techdesc'.$idx];
				$item["barangay"] = $data['df_barangay'.$idx];
				array_push($apps,$item);
			} else {
				$objGrid1->addrowdata($data);
				$rowidx++;	
			}	
			$isvalid=false;
			
			$row++;
			$i++;
			//break;
			//$rowidx++;
		} # end while
		if (!$error) {
			//var_dump($profitcenters['2019-1011']);
			//var_dump($batch);
			$objConnection->beginwork();
			
			
			if ($actionReturn) {
				foreach ($apps as $data) {
					
						$obju_LRA->prepareadd();
						$obju_LRA->docid = getNextIdByBranch("u_lra",$objConnection);
						$obju_LRA->docno = getNextNoByBranch("u_lra",'',$objConnection);
						$obju_LRA->docstatus = "O";
						$obju_LRA->setudfvalue("u_uploadeddate",currentdateDB());
						$obju_LRA->setudfvalue("u_propin",$data["propin"]);
						$obju_LRA->setudfvalue("u_sn",$data["sn"]);
						$obju_LRA->setudfvalue("u_titleno",$data["titleno"]);
						$obju_LRA->setudfvalue("u_titletype",$data["titletype"]);
						$obju_LRA->setudfvalue("u_ownersname",$data["ownersname"]);
						$obju_LRA->setudfvalue("u_owneraddress",$data["ownersaddress"]);
						$obju_LRA->setudfvalue("u_planno",$data["planno"]);
						$obju_LRA->setudfvalue("u_blockno",$data["blockno"]);
						$obju_LRA->setudfvalue("u_lotno",$data["lotno"]);
						$obju_LRA->setudfvalue("u_titledate",$data["titledate"]);
						$obju_LRA->setudfvalue("u_location",$data["location"]);
						$obju_LRA->setudfvalue("u_surveyarea",$data["surveyarea"]);
						$obju_LRA->setudfvalue("u_octno",$data["octno"]);
						$obju_LRA->setudfvalue("u_precedingtitle",$data["precedingtitle"]);
						$obju_LRA->setudfvalue("u_techdesc",$data["techdesc"]);
						$obju_LRA->setudfvalue("u_barangay",$data["barangay"]);
                                                
                                                $actionReturn = $obju_LRA->add();
                                                if (!$actionReturn) break;
					
				}
			}
						
			if ($actionReturn) $objConnection->commit();
			else $objConnection->rollback();
		}
		
		if ($errormessages!="") {
			$_SESSION['errormessage'] = $errormessages;
		}
		
		$objExcel->closeXL();
		unset($objExcel);
		//if ($actionReturn) $actionReturn = raiseError("here");
		return $actionReturn;
	}
	
	function onFormAdd() {
		global $objGrid1;
		$actionReturn = true;
		$uploadfile = "upload/" . basename($_FILES['df_filename']['name']);
		
		$actionReturn = uploadLegacyIntBrCollections($uploadfile);
		
		if ($objGrid1->recordcount>0) $actionReturn = raiseError("One or more Record is invalid.");
		
		return $actionReturn;
			
	} # end onFormAdd()

	saveErrorMsg();
	
	$page->settimeout(0);

        
        
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
	<tr><td>&nbsp;</td>
	</tr>
	<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
       	<tr>
       	          <td class="labelPageHeader" >&nbsp;<?php echo $pageHeader ?></td>
                  <td>&nbsp; </td>
	  	</tr>
	</table></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="10%">&nbsp;<label class="lblobjs">Filename</label></td>
             <td><input type="file" size="40" <?php genInputTextHtml(array("name"=>"filename")) ?> onClick="this.blur()" /></td>
		     <td width="600">&nbsp;</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td colspan="4"><a class="button" href="" onClick="formSubmit();return false;" > &nbsp; &nbsp; Upload &nbsp; &nbsp; </a></td>
		</tr>
		<tr>
                    <td colspan="5"><?php if ($objGrid1->recordcount>0){ ?> &nbsp;<label class="lblobjs" style="color: #E00F1C">Invalid data </label><br><br>&nbsp; Note: See Upload Remarks Column<?php $objGrid1->draw();  }?>
                        </td>
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