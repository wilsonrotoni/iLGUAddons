<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	require_once ("../common/classes/excel.php");
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

	$schema["autocreatesl"] = createSchema("autocreatesl");
	$schema["ignoreincomplete"] = createSchema("ignoreincomplete");
	$schema["autotagglbudget"] = createSchema("autotagglbudget");
	
	

$objGrid1 = new grid("T1");
$objGrid1->addcolumn(array('name'=>'annex','description'=>'Annex','length'=>15));
$objGrid1->addcolumn(array('name'=>'yr','description'=>'Year','length'=>15));
$objGrid1->addcolumn(array('name'=>'appcode','description'=>'APP Code','length'=>15));
$objGrid1->addcolumn(array('name'=>'description','description'=>'Program/Project','length'=>35));
$objGrid1->addcolumn(array('name'=>'profitcenter','description'=>'PMO/End-User','length'=>15));
$objGrid1->addcolumn(array('name'=>'profitcentername','description'=>'PMO/End-User Description','length'=>35));
$objGrid1->addcolumn(array('name'=>'procmode','description'=>'Mode of Procurement','length'=>15));
$objGrid1->addcolumn(array('name'=>'schedule','description'=>'Schedule of Each Procurement Activity','length'=>35));
$objGrid1->addcolumn(array('name'=>'sourcefunds','description'=>'Source of Funds','length'=>10));
$objGrid1->addcolumn(array('name'=>'estbudget','description'=>'Total','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'estmooe','description'=>'MOOE','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'estco','description'=>'CO','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'remarks','description'=>'Remarks','length'=>35));
$objGrid1->addcolumn(array('name'=>'uploadremarks','description'=>'Upload Remarks','length'=>50));
$objGrid1->automanagecolumnwidth=false;
$objGrid1->dataentry = false;

$httpVars['df_company'] = $_SESSION['company'];
$httpVars['df_branch'] = $_SESSION['branch'];

require("./inc/upload.php");
require("./inc/formobjs.php");
require("./inc/formactions.php");
	
	function uploadLegacyIntBrCollections($uploadfile) {
		GLOBAL $httpVars;
		GLOBAL $objGrid1;
		GLOBAL $objConnection;
		global $page;
		$dspcode="";

		$obju_LGUProjs = new masterdataschema_br(NULL,$objConnection,"u_lguprojs");
		$obju_LGUProjRevs = new documentschema_br(NULL,$objConnection,"u_lguprojrevs");
		$obju_LGUProjRevItems = new documentlinesschema_br(NULL,$objConnection,"u_lguprojrevitems");
		$objProfitCenters = new profitcenters(null,$objConnection);
		
		$objrs = new recordset(null,$objConnection);
		$objConnection->beginwork();
		$actionReturn = true;
		$_SESSION['errormessage'] = "";
		
		if(isFileUploaded($uploadfile) == false) return false;

		$objrs->queryopen("select code, u_glacctno, max(u_code) as u_code from u_lgupcsubsidiaryaccts group by code, u_glacctno, u_code");
		while ($objrs->queryfetchrow("NAME")) {
			$subsidiaries[$objrs->fields["code"]."-".$objrs->fields["u_glacctno"]] = ord($objrs->fields["u_code"]);
		}				

		$objExcel = new Excel;
		$objExcel->openxl(realpath($uploadfile),"",""); 
		
		
		$sheet = "Programs & Projects - Revisions";
		$row = 1;
		$i = 1;
		$rowidx = 1;
		
		$rollback = false;		
		$errormessages = "";

		$date = "";
		$type = "";
		$remarks = "";
		$apps = array();
		$nonbudgets = array();
		if (isset($objGrid1)) $objGrid1->clear();
		
		$profitcenters = array();
		$error = false;
		
		while($content = $objExcel->readrange($sheet,"A".$row.":M".$row)) {
			if(implode('',$content) == "") break;
								 			
			if ($row==1) {
				if ($content[0]!="Annex" || $content[1]!="Year" || $content[2]!="Reference APP Code (PAP)" || $content[3]!="APP Code (PAP)" || $content[4]!="Procurement Program/Project"  || $content[5]!="PMO/End-User" || $content[6]!="Mode of Procurement"  || 
				    $content[7]!="Schedule of Each Procurement Activity"  || $content[8]!="Source of Funds"  || $content[9]!="Total"  || $content[10]!="MOOE"  || $content[11]!="CO"  || $content[12]!="Remarks (Brief Description of Program/Project)"  ) {
					$actionReturn = raiseError("Invalid Header!");
					$error = true;
					break;
				}	
				$row++;
				continue;
			}
			
			$isvalid = true;		
			//var_dump($content);
			$idx = 'T1r'.$rowidx;
/*
$objGrid1->addcolumn(array('name'=>'annex','description'=>'Annex','length'=>15));
$objGrid1->addcolumn(array('name'=>'yr','description'=>'Year','length'=>15));
$objGrid1->addcolumn(array('name'=>'appcode','description'=>'APP Code','length'=>15));
$objGrid1->addcolumn(array('name'=>'description','description'=>'Program/Project','length'=>35));
$objGrid1->addcolumn(array('name'=>'profitcenter','description'=>'PMO/End-User','length'=>15));
$objGrid1->addcolumn(array('name'=>'profitcentername','description'=>'PMO/End-User Description','length'=>35));
$objGrid1->addcolumn(array('name'=>'procmode','description'=>'Mode of Procurement','length'=>15));
$objGrid1->addcolumn(array('name'=>'schedule','description'=>'Schedule of Each Procurement Activity','length'=>35));
$objGrid1->addcolumn(array('name'=>'sourcefunds','description'=>'Source of Funds','length'=>10));
$objGrid1->addcolumn(array('name'=>'estbudget','description'=>'Total','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'estmooe','description'=>'MOOE','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'estco','description'=>'CO','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'uploadremarks','description'=>'Upload Remarks','length'=>50));
*/			
			$data['df_annex'.$idx] = ltrim($content[0]);
			$data['df_yr'.$idx] = ltrim($content[1]);
			$data['df_refappcode'.$idx] = ltrim($content[2]);
			$data['df_appcode'.$idx] = ltrim($content[3]);
			$data['df_description'.$idx] = ltrim($content[4]);
			$data['df_profitcenter'.$idx] = ltrim($content[5]);
			$data['df_procmode'.$idx] = ltrim($content[6]);
			$data['df_schedule'.$idx] = ltrim($content[7]);
			$data['df_sourcefunds'.$idx] = ltrim($content[8]);
			$data['df_estbudget'.$idx] = floatval(ltrim($content[9]));
			$data['df_estmooe'.$idx] = floatval(ltrim($content[10]));
			$data['df_estco'.$idx] = floatval(ltrim($content[11]));
			$data['df_remarks'.$idx] = ltrim($content[12]);
			$data['df_profitcentername'.$idx] = "";
			
			$errormessage = "";
			
			if($data['df_yr'.$idx] == "") $errormessage .= " Year is required.";
			if($data['df_profitcenter'.$idx] == "") $errormessage .= " Profit Center is required.";
			if($data['df_refappcode'.$idx] == "") $errormessage .= " Reference APP Code (PAP) is required.";
			if($data['df_appcode'.$idx] == "") $errormessage .= " APP Code (PAP) is required.";
			if($data['df_estbudget'.$idx] == 0) $errormessage .= " Total is required.";
			//if($data['df_time'.$idx] == "") $errormessage .= " Time is required.";
			
			//if ($data['df_profitcenter'.$idx]!="") {
			if (!isset($profitcenters[$data['df_profitcenter'.$idx]])) {
				if ($objProfitCenters->getbykey($data['df_profitcenter'.$idx])) {
					$data['df_profitcentername'.$idx] = $objProfitCenters->profitcentername;
					$profitcenters[$data['df_profitcenter'.$idx]] = $data['df_profitcentername'.$idx];
				} else {
					$errormessage .= " Profit Center is invalid.";
					$isvalid=false;
				}
			} else {
				$data['df_profitcentername'.$idx] = $profitcenters[$data['df_profitcenter'.$idx]];
			}				
			//}
			
			if (!isset($apps[$data['df_refappcode'.$idx]])) {
				$objrs->queryopen("select u_estbudget from u_lguprojs where code='".$data['df_yr'.$idx]."-".$data['df_refappcode'.$idx]."' and u_status=''");
				if (!$objrs->queryfetchrow("NAME")) {
					$errormessage .= " Reference APP Code (PAP) is invalid.";
					$isvalid=false;
				} else {
					$apps[$data['df_refappcode'.$idx]] = array();
					$apps[$data['df_refappcode'.$idx]]["items"] = array();
					$apps[$data['df_refappcode'.$idx]]["estbudget"] = floatval($objrs->fields["u_estbudget"]);
					$apps[$data['df_refappcode'.$idx]]["revbudget"] = 0;
					
				}
			}
			
				
			//if ($data['df_sldesc'.$idx]!="") {
				$objrs->queryopen("select code from u_lguprocmodes where code='".addslashes($data['df_procmode'.$idx])."'");
				if (!$objrs->queryfetchrow("NAME")) {
					$errormessage .= " Mode of Procurement is invalid.";
					$isvalid=false;
				}
			//}			

			/*
			if (!isset($employees[$data['df_deviceid'.$idx]."-".$data['df_userid'.$idx]])) {
				$objrs->queryopen("select b.code, b.name from u_employeedeviceids a inner join u_employees b on b.code=a.code where a.u_deviceid='".$data['df_deviceid'.$idx]."' and a.u_userid='".$data['df_userid'.$idx]."'");
				if ($objrs->queryfetchrow("NAME")) {
					$employees[$data['df_deviceid'.$idx]."-".$data['df_userid'.$idx]]["empid"] = $objrs->fields["code"];
					$employees[$data['df_deviceid'.$idx]."-".$data['df_userid'.$idx]]["empname"] = $objrs->fields["name"];
				} else {	
					$errormessage .= " Device and User ID is invalid.";
					$isvalid=false;
				}
			}			
			
			if (isset($employees[$data['df_deviceid'.$idx]."-".$data['df_userid'.$idx]])) {
				$data['df_empid'.$idx] = $employees[$data['df_deviceid'.$idx]."-".$data['df_userid'.$idx]]["empid"];
				$data['df_empname'.$idx] = $employees[$data['df_deviceid'.$idx]."-".$data['df_userid'.$idx]]["empname"];
			}
			*/
			if($errormessage != "") {
				$data['df_uploadremarks'.$idx] = "Line #".$row.": " . $errormessage;
				$errormessage = "";
				$error = true;
				$isvalid=false;
			}
			if ($isvalid) {
					$item = array();
					$item["annex"] = $data['df_annex'.$idx];
					$item["yr"] = $data['df_yr'.$idx];
					$item["refappcode"] = $data['df_refappcode'.$idx];
					$item["appcode"] = $data['df_appcode'.$idx];
					$item["description"] = $data['df_description'.$idx];
					$item["profitcenter"] = $data['df_profitcenter'.$idx];
					$item["procmode"] = $data['df_procmode'.$idx];
					$item["schedule"] = $data['df_schedule'.$idx];
					$item["sourcefunds"] = $data['df_sourcefunds'.$idx];
					$item["estbudget"] = $data['df_estbudget'.$idx];
					$item["estmooe"] = $data['df_estmooe'.$idx];
					$item["estco"] = $data['df_estco'.$idx];
					$item["remarks"] = $data['df_remarks'.$idx];
					$apps[$data['df_refappcode'.$idx]]["revbudget"] += $data['df_estbudget'.$idx];
					array_push($apps[$data['df_refappcode'.$idx]]["items"],$item);
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
			//var_dump($apps);
			$objConnection->beginwork();
			//$settings = getBusinessObjectSettings("ARINVOICE");
			
			if ($actionReturn) {
				foreach ($apps as $key => $appdata) {
					if ($apps[$key]["estbudget"]!=$apps[$key]["revbudget"]) {
						$actionReturn = raiseError($appdata["items"][0]["yr"] . " Program/Project for [".$key."] Estimated Total [".formatNumericAmount($apps[$key]["estbudget"])."] and Revised Total [".formatNumericAmount($apps[$key]["revbudget"])."] must be the same amount.");
						break;
					}
					
					if ($obju_LGUProjs->getbysql("u_yr='".$appdata["items"][0]["yr"]."' and u_appcode='".$key."'")) {
						if ($obju_LGUProjs->getudfvalue("u_status")!="") {
							$actionReturn = raiseError($appdata["items"][0]["yr"] . " Program/Project for [".$key."] cannot be uploaded if already revised.");
							break;
						}
						//$obju_LGUProjs->setudfvalue("u_status","Revised");
						//$actionReturn = $obju_LGUProjs->update($obju_LGUProjs->code,$obju_LGUProjs->rcdversion);
					} else {
						$actionReturn = raiseError("Unable to find [" . $appdata["items"][0]["yr"] . "] Program/Project for [".$key."].");
						break;
					}
					if ($actionReturn) {
						$obju_LGUProjRevs->prepareadd();
						$obju_LGUProjRevs->docseries = getDefaultSeries("u_lguprojrevs",$objConnection);
						$obju_LGUProjRevs->docno = getNextSeriesNoByBranch("u_lguprojrevs",$obju_Collections->docseries,'',$objConnection);
						$obju_LGUProjRevs->docid = getNextIdByBranch("u_lguprojrevs");
						$obju_LGUProjRevs->docstatus = "O";
						$obju_LGUProjRevs->setudfvalue("u_yr",$obju_LGUProjs->getudfvalue("u_yr"));
						$obju_LGUProjRevs->setudfvalue("u_appcode",$obju_LGUProjs->getudfvalue("u_appcode"));
						$obju_LGUProjRevs->setudfvalue("u_description",$obju_LGUProjs->name);
						$obju_LGUProjRevs->setudfvalue("u_profitcenter",$obju_LGUProjs->getudfvalue("u_profitcenter"));
						$obju_LGUProjRevs->setudfvalue("u_annex",$obju_LGUProjs->getudfvalue("u_annex"));
						$obju_LGUProjRevs->setudfvalue("u_procmode",$obju_LGUProjs->getudfvalue("u_procmode"));
						$obju_LGUProjRevs->setudfvalue("u_schedule",$obju_LGUProjs->getudfvalue("u_schedule"));
						$obju_LGUProjRevs->setudfvalue("u_sourcefunds",$obju_LGUProjs->getudfvalue("u_sourcefunds"));
						$obju_LGUProjRevs->setudfvalue("u_estbudget",$obju_LGUProjs->getudfvalue("u_estbudget"));
						$obju_LGUProjRevs->setudfvalue("u_estmooe",$obju_LGUProjs->getudfvalue("u_estmooe"));
						$obju_LGUProjRevs->setudfvalue("u_estco",$obju_LGUProjs->getudfvalue("u_estco"));
						$obju_LGUProjRevs->setudfvalue("u_remarks",$obju_LGUProjs->getudfvalue("u_remarks"));
						foreach ($appdata["items"] as $data) {
							$obju_LGUProjRevItems->prepareadd();
							$obju_LGUProjRevItems->docid = $obju_LGUProjRevs->docid;
							$obju_LGUProjRevItems->lineid = getNextIdByBranch("u_lguprojrevitems");
							$obju_LGUProjRevItems->setudfvalue("u_appcode",$data["appcode"]);
							$obju_LGUProjRevItems->setudfvalue("u_description",$data["description"]);
							$obju_LGUProjRevItems->setudfvalue("u_procmode",$data["procmode"]);
							$obju_LGUProjRevItems->setudfvalue("u_schedule",$data["schedule"]);
							$obju_LGUProjRevItems->setudfvalue("u_sourcefunds",$data["sourcefunds"]);
							$obju_LGUProjRevItems->setudfvalue("u_estbudget",$data["estbudget"]);
							$obju_LGUProjRevItems->setudfvalue("u_estmooe",$data["estmooe"]);
							$obju_LGUProjRevItems->setudfvalue("u_estco",$data["estco"]);
							$obju_LGUProjRevItems->setudfvalue("u_remarks",$data["remarks"]);
							$obju_LGUProjRevItems->privatedata["header"] = $obju_LGUProjRevs;
							$actionReturn = $obju_LGUProjRevItems->add();
							if (!$actionReturn) break;

							$obju_LGUProjRevs->setudfvalue("u_revbudget",$obju_LGUProjRevs->getudfvalue("u_revbudget")+$data["estbudget"]);
							$obju_LGUProjRevs->setudfvalue("u_revmooe",$obju_LGUProjRevs->getudfvalue("u_revmooe")+$data["estmooe"]);
							$obju_LGUProjRevs->setudfvalue("u_revco",$obju_LGUProjRevs->getudfvalue("u_revco")+$data["estco"]);
						}
						if ($actionReturn) {
							$actionReturn = $obju_LGUProjRevs->add();
						}
						if (!$actionReturn) break;				
					}		
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
		
		//if ($objGrid1->recordcount>0) $actionReturn = raiseError("One or more Record is invalid.");
		
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
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5"><?php if ($objGrid1->recordcount>0) $objGrid1->draw() ?></td>
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