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
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="<?php autocaching('js/popupframe.js'); ?>"></SCRIPT>

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
	showAjaxProcess();
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
$objGrid1->addcolumn(array('name'=>'yr','description'=>'Year','length'=>15));
$objGrid1->addcolumn(array('name'=>'profitcenter','description'=>'Profit Center','length'=>15));
$objGrid1->addcolumn(array('name'=>'profitcentername','description'=>'Profit Center Name','length'=>35));
$objGrid1->addcolumn(array('name'=>'glacctno','description'=>'G/L Account No.','length'=>15));
$objGrid1->addcolumn(array('name'=>'glacctname','description'=>'Description','length'=>35));
$objGrid1->addcolumn(array('name'=>'slcode','description'=>'S/L Code','length'=>10));
$objGrid1->addcolumn(array('name'=>'sldesc','description'=>'Description','length'=>35));
$objGrid1->addcolumn(array('name'=>'amount','description'=>'Amount','length'=>10,'align'=>'right'));
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

		$obju_LGUBudget = new masterdataschema_br(NULL,$objConnection,"u_lgubudget");
		$obju_LGUBudgetGLs = new masterdatalinesschema_br(NULL,$objConnection,"u_lgubudgetgls");
		$obju_LGUPCSubsidiaries = new masterdataschema_br(NULL,$objConnection,"u_lgupcsubsidiaries");
		$obju_LGUPCSubsidiaryAccts = new masterdatalinesschema_br(NULL,$objConnection,"u_lgupcsubsidiaryaccts");
		$objChartOfAccounts = new chartofaccounts(null,$objConnection);
		$objProfitCenters = new profitcenters(null,$objConnection);
		
		$objrs = new recordset(null,$objConnection);
		$objConnection->beginwork();
		$actionReturn = true;
		$_SESSION['errormessage'] = "";
		
		if(isFileUploaded($uploadfile) == false) return false;

		$objrs->queryopen("select code, u_glacctno, max(u_code) as u_code from u_lgupcsubsidiaryaccts group by code, u_glacctno");
		while ($objrs->queryfetchrow("NAME")) {
			$subsidiaries[$objrs->fields["code"]."-".$objrs->fields["u_glacctno"]] = ord($objrs->fields["u_code"]);
		}				

		$objExcel = new Excel;
		$objExcel->openxl(realpath($uploadfile),"",""); 
		
		
		$sheet = "Budget";
		$row = 1;
		$i = 1;
		$rowidx = 1;
		
		$rollback = false;		
		$errormessages = "";

		$date = "";
		$type = "";
		$remarks = "";
		$timelogs = array();
		$nonbudgets = array();
		if (isset($objGrid1)) $objGrid1->clear();
		
		$profitcenters = array();
		$error = false;
		
		$subsidiaries = array();
		
		
		
		while($content = $objExcel->readrange($sheet,"A".$row.":J".$row)) {
			if(implode('',$content) == "") break;

			if ($row==1) {
				if ($content[0]!="Year" || $content[1]!="Profit Center" || $content[2]!="G/L Account No."  || $content[3]!="Description" || $content[4]!="S/L Description"  || $content[5]!="Amount" ) {
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
			$data['df_yr'.$idx] = ltrim($content[0]);
			$data['df_profitcenter'.$idx] = ltrim($content[1]);
			$data['df_glacctno'.$idx] = ltrim($content[2]);
			$data['df_sldesc'.$idx] = ltrim($content[4]);
			$data['df_amount'.$idx] = floatval(ltrim($content[5]));
			$data['df_glacctname'.$idx] = "";
			$data['df_profitcentername'.$idx] = "";
			$data['df_slcode'.$idx] = "";

			$errormessage = "";
			
			if($data['df_yr'.$idx] == "") $errormessage .= " Year is required.";
			if($data['df_profitcenter'.$idx] == "" && $page->getitemstring("ignoreincomplete")!="1") $errormessage .= " Profit Center is required.";
			if($data['df_glacctno'.$idx] == "") $errormessage .= " G/L Account No. is required.";
			if($data['df_amount'.$idx] == 0 && $page->getitemstring("ignoreincomplete")!="1") $errormessage .= " Amount is required.";
			//if($data['df_time'.$idx] == "") $errormessage .= " Time is required.";
			
			if ($data['df_amount'.$idx]>0 && $data['df_profitcenter'.$idx]=="") {
				$errormessage .= " Profit Center is required.";
				$isvalid=false;
			}
			
			if ($data['df_profitcenter'.$idx]!="") {
				if (!isset($profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]])) {
					if ($objProfitCenters->getbykey($data['df_profitcenter'.$idx])) {
						$data['df_profitcentername'.$idx] = $objProfitCenters->profitcentername;
						
						$profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]] = array();
						$profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["yr"] =  $data['df_yr'.$idx];
						$profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["code"] =  $data['df_profitcenter'.$idx];
						$profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["name"] =  $data['df_profitcentername'.$idx];
						$profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["items"] = array();
					} else {
						$errormessage .= " Profit Center is invalid.";
						$isvalid=false;
					}
				} else {
					$data['df_profitcentername'.$idx] = $profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["name"];
				}				
			}	
			if ($data['df_glacctno'.$idx]!="") {
				if ($objChartOfAccounts->getbykey($data['df_glacctno'.$idx])) {
					$data['df_glacctname'.$idx] = $objChartOfAccounts->acctname;
					if ($objChartOfAccounts->budget==0) {
						if ($page->getitemstring("autotagglbudget")!="1") {
							$errormessage .= " G/L Account No. is not allowed in budget.";
							$isvalid=false;
						} else {
							$nonbudgets[$objChartOfAccounts->formatcode] = $objChartOfAccounts->acctname;
						}	
					}
				} else {
					$errormessage .= " G/L Account No. is invalid.";
					$isvalid=false;
				}
				
			}	 
			/*if ($data['df_sldesc'.$idx]!="") {
				$objrs->queryopen("select u_code, u_description from u_lgupcsubsidiaryaccts where code='".$data['df_profitcenter'.$idx]."' and u_glacctno='".$data['df_glacctno'.$idx]."' and u_description='".$data['df_sldesc'.$idx]."'");
				if ($objrs->queryfetchrow("NAME")) {
					$data['df_slcode'.$idx] = $objrs->fields["u_code"];
					$data['df_sldesc'.$idx] = $objrs->fields["u_description"];
				} else {	
					if ($page->getitemstring("autocreatesl")!="1") {
						$errormessage .= " S/L Description is invalid.";
						$isvalid=false;
					}	
				}
			}*/			

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
				if ($data['df_amount'.$idx]>0) {
					$item = array();
					$item["glacctno"] = $data['df_glacctno'.$idx];
					$item["glacctname"] = $data['df_glacctname'.$idx];
					$item["slcode"] = $data['df_slcode'.$idx];
					$item["sldesc"] = $data['df_sldesc'.$idx];
					$item["amount"] = $data['df_amount'.$idx];
					array_push($profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["items"],$item);
				}	
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
			//$settings = getBusinessObjectSettings("ARINVOICE");
			
			foreach ($nonbudgets as $key => $data) {
				if ($objChartOfAccounts->getbykey($key)) {
					$objChartOfAccounts->budget=1;
					$actionReturn = $objChartOfAccounts->update($objChartOfAccounts->formatcode,$objChartOfAccounts->rcdversion);
				}
				if (!$actionReturn) break;
			}
			
			if ($actionReturn) {
				foreach ($profitcenters as $key => $pcdata) {
					
					if ($obju_LGUBudget->getbysql("u_yr='".$pcdata["yr"]."' and u_profitcenter='".$pcdata["code"]."'")) {
						//if ($obju_LGUBudget->docstatus=="Approved") {
						//	$actionReturn = raiseError($pcdata["yr"] . " Appropriation for [".$pcdata["name"]."] cannot be uploaded if already approved.");
						//} else {
							$actionReturn = $objrs->executesql("delete from u_lgubudgetgls where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and code='$obju_LGUBudget->code'",false);
						//}
					} else {
						$obju_LGUBudget->prepareadd();
						$obju_LGUBudget->code = $pcdata["yr"] . "-" . $pcdata["code"];
						$obju_LGUBudget->name = $pcdata["name"];
						$obju_LGUBudget->setudfvalue("u_yr",$pcdata["yr"]);
						$obju_LGUBudget->setudfvalue("u_profitcenter",$pcdata["code"]);
					}
					$totalamount = 0;
					$totalps = 0;
					$totalmooe = 0;
					$totalco = 0 ;
					$totalfe = 0 ;
					if ($actionReturn) {
						foreach ($pcdata["items"] as $item) {
						
							if (!$objChartOfAccounts->getbykey($item["glacctno"])) {
								$actionReturn = raiseError("Unable to find G/L Account No. [".$item["glacctno"]."].");
								break;
							}
						
							if ($item["slcode"]=="" && $item["sldesc"]!="") {
								if (!$obju_LGUPCSubsidiaries->getbykey($pcdata["code"])) {
									$obju_LGUPCSubsidiaries->prepareadd();
									$obju_LGUPCSubsidiaries->code = $pcdata["code"];
									$obju_LGUPCSubsidiaries->name = $pcdata["name"];
									$obju_LGUPCSubsidiaries->setudfvalue("u_profitcenter",$pcdata["code"]);
								}
								
								if (!isset($subsidiaries[$pcdata["code"]."-".$item["glacctno"]])) $subsidiaries[$pcdata["code"]."-".$item["glacctno"]] = 64;
								$subsidiaries[$pcdata["code"]."-".$item["glacctno"]]++;
								
								$obju_LGUPCSubsidiaryAccts->prepareadd();
								$obju_LGUPCSubsidiaryAccts->code = $obju_LGUPCSubsidiaries->code;
								$obju_LGUPCSubsidiaryAccts->lineid = getNextIdByBranch("u_lgupcsubsidiaryaccts",$objConnection);
								$obju_LGUPCSubsidiaryAccts->setudfvalue("u_glacctno",$item["glacctno"]);
								$obju_LGUPCSubsidiaryAccts->setudfvalue("u_glacctname",$item["glacctname"]);
								$obju_LGUPCSubsidiaryAccts->setudfvalue("u_code",chr($subsidiaries[$pcdata["code"]."-".$item["glacctno"]]));
								$obju_LGUPCSubsidiaryAccts->setudfvalue("u_description",$item["sldesc"]);
								$obju_LGUPCSubsidiaryAccts->privatedata["header"] = $obju_LGUPCSubsidiaries;
								$actionReturn = $obju_LGUPCSubsidiaryAccts->add();
								
								if ($actionReturn) {
									if ($obju_LGUPCSubsidiaries->rowstat=="N") $actionReturn = $obju_LGUPCSubsidiaries->add();
									else $actionReturn = $obju_LGUPCSubsidiaries->update($obju_LGUPCSubsidiaries->code, $obju_LGUPCSubsidiaries->rcdversion);
								}	
								
								$item["slcode"] = chr($subsidiaries[$pcdata["code"]."-".$item["glacctno"]]);
							}
							
							if (!$actionReturn) break;
							
							$obju_LGUBudgetGLs->prepareadd();
							$obju_LGUBudgetGLs->code = $obju_LGUBudget->code;
							$obju_LGUBudgetGLs->lineid = getNextIdByBranch("u_lgubudgetgls",$objConnection);
							$obju_LGUBudgetGLs->setudfvalue("u_expgroupno",$objChartOfAccounts->getudfvalue("u_expgroupno"));
							$obju_LGUBudgetGLs->setudfvalue("u_expclass",$objChartOfAccounts->getudfvalue("u_expclass"));
							$obju_LGUBudgetGLs->setudfvalue("u_glacctno",$item["glacctno"]);
							$obju_LGUBudgetGLs->setudfvalue("u_glacctname",$item["glacctname"]);
							$obju_LGUBudgetGLs->setudfvalue("u_slcode",$item["slcode"]);
							$obju_LGUBudgetGLs->setudfvalue("u_sldesc",$item["sldesc"]);
							$obju_LGUBudgetGLs->setudfvalue("u_yr",$item["amount"]);
							$monthly = round($item["amount"]/12,2);
							
							$obju_LGUBudgetGLs->setudfvalue("u_m1",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m2",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m3",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m4",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m5",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m6",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m7",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m8",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m9",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m10",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m11",$monthly);
							$obju_LGUBudgetGLs->setudfvalue("u_m12",$item["amount"]-($monthly*11));
							
							$obju_LGUBudgetGLs->setudfvalue("u_q1",$obju_LGUBudgetGLs->getudfvalue("u_m1")+$obju_LGUBudgetGLs->getudfvalue("u_m2")+$obju_LGUBudgetGLs->getudfvalue("u_m3"));
							$obju_LGUBudgetGLs->setudfvalue("u_q2",$obju_LGUBudgetGLs->getudfvalue("u_m4")+$obju_LGUBudgetGLs->getudfvalue("u_m5")+$obju_LGUBudgetGLs->getudfvalue("u_m6"));
							$obju_LGUBudgetGLs->setudfvalue("u_q3",$obju_LGUBudgetGLs->getudfvalue("u_m7")+$obju_LGUBudgetGLs->getudfvalue("u_m8")+$obju_LGUBudgetGLs->getudfvalue("u_m9"));
							$obju_LGUBudgetGLs->setudfvalue("u_q4",$obju_LGUBudgetGLs->getudfvalue("u_m10")+$obju_LGUBudgetGLs->getudfvalue("u_m11")+$obju_LGUBudgetGLs->getudfvalue("u_m12"));
							
							$actionReturn = $obju_LGUBudgetGLs->add();
							if (!$actionReturn) break;
							
							switch ($obju_LGUBudgetGLs->getudfvalue("u_expclass")) {
								case "PS": $totalps += $item["amount"]; break;
								case "MOOE": $totalmooe += $item["amount"]; break;
								case "FE": $totalfe += $item["amount"]; break;
								case "CO": $totalco += $item["amount"]; break;
									
							}
							
							$totalamount += $item["amount"];
						}
					}	
					
					if (!$actionReturn) break;
					
					$obju_LGUBudget->setudfvalue("u_totalamount",$totalamount);
					$obju_LGUBudget->setudfvalue("u_totalps",$totalps);
					$obju_LGUBudget->setudfvalue("u_totalmooe",$totalmooe);
					$obju_LGUBudget->setudfvalue("u_totalfe",$totalfe);
					$obju_LGUBudget->setudfvalue("u_totalco",$totalco);
					
					if ($obju_LGUBudget->rowstat=="N") $actionReturn = $obju_LGUBudget->add();
					else  $actionReturn = $obju_LGUBudget->update($obju_LGUBudget->code,$obju_LGUBudget->rcdversion);
					
					if (!$actionReturn) break;
					
				}
			}
			//if ($actionReturn) $actionReturn = raiseError("rey");			
			
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
		     <td width="600">&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema["ignoreincomplete"],1) ?> /><label <?php genCaptionHtml($schema["ignoreincomplete"],"") ?> >Ignore Empty (Profit Center, Amount)</label></td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema["autocreatesl"],1) ?> /><label <?php genCaptionHtml($schema["autocreatesl"],"") ?> >Auto Create S/L</label>&nbsp;<input type="checkbox" <?php genInputCheckboxHtml($schema["autotagglbudget"],1) ?> /><label <?php genCaptionHtml($schema["autotagglbudget"],"") ?> >Auto tag G/L as budget</label></td>
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
<?php require("./bofrms/ajaxprocess.php"); ?>	
</body>

<?php include("setbottomtoolbar.php"); ?>
<?php include("setstatusmsg.php"); ?>

<script>
resizeObjects();
</script>