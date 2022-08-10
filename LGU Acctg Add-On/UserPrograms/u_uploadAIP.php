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

$objGrid1 = new grid("T1");
$objGrid1->addcolumn(array('name'=>'yr','description'=>'Year','length'=>4));
$objGrid1->addcolumn(array('name'=>'profitcenter','description'=>'Profit Center','length'=>10));
$objGrid1->addcolumn(array('name'=>'profitcentername','description'=>'Profit Center Name','length'=>35));
$objGrid1->addcolumn(array('name'=>'aipcode','description'=>'AIP Code','length'=>30));
$objGrid1->addcolumn(array('name'=>'description','description'=>'Program/Project/Activity','length'=>35));
$objGrid1->addcolumn(array('name'=>'office','description'=>'Implementing Office','length'=>15));
$objGrid1->addcolumn(array('name'=>'start','description'=>'Start Date','length'=>15));
$objGrid1->addcolumn(array('name'=>'completion','description'=>'Completion Date','length'=>15));
$objGrid1->addcolumn(array('name'=>'expectedoutput','description'=>'Expected Output','length'=>35));
$objGrid1->addcolumn(array('name'=>'funding','description'=>'Funding Source','length'=>15));
$objGrid1->addcolumn(array('name'=>'ps','description'=>'PS','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'mooe','description'=>'MOOE','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'co','description'=>'CO','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'total','description'=>'Total','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'cca','description'=>'CCA','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'ccm','description'=>'CCM','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'cctc','description'=>'CCTC','length'=>35));
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

		$obju_LGUAIP = new documentschema_br(NULL,$objConnection,"u_lguaip");
		$obju_LGUAIPItems = new documentlinesschema_br(NULL,$objConnection,"u_lguaipitems");
		$objProfitCenters = new profitcenters(null,$objConnection);
		
		$objrs = new recordset(null,$objConnection);
		$objConnection->beginwork();
		$actionReturn = true;
		$_SESSION['errormessage'] = "";
		
		if(isFileUploaded($uploadfile) == false) return false;

		$objExcel = new Excel;
		$objExcel->openxl(realpath($uploadfile),"",""); 
		
		
		$sheet = "AIP";
		$row = 1;
		$i = 1;
		$rowidx = 1;
		
		$rollback = false;		
		$errormessages = "";

		$date = "";
		$type = "";
		$remarks = "";
		$seqs = array();
		if (isset($objGrid1)) $objGrid1->clear();
		
		$profitcenters = array();
		$error = false;
		
		$subsidiaries = array();
		
		
		
		while($content = $objExcel->readrange($sheet,"A".$row.":P".$row)) {
			if(implode('',$content) == "") break;

			if ($row==1) {
				if ($content[0]!="Year" || $content[1]!="Profit Center" || $content[2]!="AIP Code" || $content[3]!="Program/Project/Activity" || $content[4]!="Implementing Office" || 
					$content[5]!="Start Date" || $content[6]!="Completion Date" || $content[7]!="Expected Output" || $content[8]!="Funding Source" || $content[9]!="PS" || 
					$content[10]!="MOOE" || $content[11]!="CO" || $content[12]!="Total" || $content[13]!="CCA" || $content[14]!="CCM" || 
					$content[15]!="CCTC" ) {
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
			$data['df_aipcode'.$idx] = ltrim($content[2]);
			$data['df_description'.$idx] = ltrim($content[3]);
			$data['df_office'.$idx] = ltrim($content[4]);
			$data['df_start'.$idx] = ltrim($content[5]);
			$data['df_completion'.$idx] = ltrim($content[6]);
			$data['df_expectedoutput'.$idx] = ltrim($content[7]);
			$data['df_funding'.$idx] = ltrim($content[8]);
			$data['df_ps'.$idx] = floatval(ltrim($content[9]));
			$data['df_mooe'.$idx] = floatval(ltrim($content[10]));
			$data['df_co'.$idx] = floatval(ltrim($content[11]));
			$data['df_total'.$idx] = floatval(ltrim($content[12]));
			$data['df_cca'.$idx] = floatval(ltrim($content[13]));
			$data['df_ccm'.$idx] = floatval(ltrim($content[14]));
			$data['df_cctc'.$idx] = ltrim($content[15]);
			$data['df_profitcentername'.$idx] = "";
			
			$errormessage = "";
			
			if($data['df_yr'.$idx] == "") $errormessage .= " Year is required.";
			if($data['df_profitcenter'.$idx] == "") $errormessage .= " Profit Center is required.";
			if (($data['df_ps'.$idx]+$data['df_mooe'.$idx]+$data['df_co'.$idx])>0) {
				if(($data['df_ps'.$idx]+$data['df_mooe'.$idx]+$data['df_co'.$idx])!=$data['df_total'.$idx]) $errormessage .= " Total is incorrect.";
			}	
			//if ($data['df_cca'.$idx]!=0 || $data['df_ccm'.$idx]) {
			//	if(($data['df_cca'.$idx]+$data['df_ccm'.$idx])!=$data['df_total'.$idx]) $errormessage .= " CCA+CCM is incorrect.";
			//}	
			//if($data['df_time'.$idx] == "") $errormessage .= " Time is required.";
			
			if ($data['df_profitcenter'.$idx]!="") {
				if (!isset($profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]])) {
					if ($objProfitCenters->getbykey($data['df_profitcenter'.$idx])) {
						$data['df_profitcentername'.$idx] = $objProfitCenters->profitcentername;
						
						$profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]] = array();
						$profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["yr"] =  $data['df_yr'.$idx];
						$profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["code"] =  $data['df_profitcenter'.$idx];
						$profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["name"] =  $data['df_profitcentername'.$idx];
						$profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["items"] = array();
						
						array_push($seqs,$data['df_profitcenter'.$idx]);
					} else {
						$errormessage .= " Profit Center is invalid.";
						$isvalid=false;
					}
				} else {
					$data['df_profitcentername'.$idx] = $profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["name"];
				}				
			}	

			if($errormessage != "") {
				$data['df_uploadremarks'.$idx] = "Line #".$row.": " . $errormessage;
				$errormessage = "";
				$error = true;
				$isvalid=false;
			}

			if ($isvalid) {
				$item = array();
				$item["aipcode"] = $data['df_aipcode'.$idx];
				$item["description"] = $data['df_description'.$idx];
				$item["office"] = $data['df_office'.$idx];
				$item["start"] = $data['df_start'.$idx];
				$item["completion"] = $data['df_completion'.$idx];
				$item["expectedoutput"] = $data['df_expectedoutput'.$idx];
				$item["funding"] = $data['df_funding'.$idx];
				$item["ps"] = $data['df_ps'.$idx];
				$item["mooe"] = $data['df_mooe'.$idx];
				$item["co"] = $data['df_co'.$idx];
				$item["total"] = $data['df_total'.$idx];
				$item["cca"] = $data['df_cca'.$idx];
				$item["ccm"] = $data['df_ccm'.$idx];
				$item["cctc"] = $data['df_cctc'.$idx];
				array_push($profitcenters[$data['df_yr'.$idx]."-".$data['df_profitcenter'.$idx]]["items"],$item);
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
			$seq=10;
			foreach ($seqs as $pc) {
				if ($objProfitCenters->getbykey($pc)) {
					$objProfitCenters->setudfvalue("u_seq",$seq);
					$actionReturn = $objProfitCenters->update($objProfitCenters->profitcenter,$objProfitCenters->rcdversion);
					$seq=$seq+50;
				}
				if (!$actionReturn) break;

			}
			
			if ($actionReturn) {
				foreach ($profitcenters as $key => $pcdata) {
					if ($obju_LGUAIP->getbysql("u_yr='".$pcdata["yr"]."' and u_profitcenter='".$pcdata["code"]."'")) {
						/*if ($obju_LGUPPMP->docstatus=="Approved") {
							$actionReturn = raiseError($pcdata["yr"] . " Appropriation for [".$pcdata["name"]."] cannot be uploaded if already approved.");
						} else {*/
							$actionReturn = $objrs->executesql("delete from u_lguaipitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_LGUAIP->docid'",false);
						//}
					} else {
						$obju_LGUAIP->prepareadd();
						$obju_LGUAIP->docid = getNextIdByBranch("u_lguaip",$objConnection);
						$obju_LGUAIP->docseries = getDefaultSeries("u_lguaip",$objConnection);
						$obju_LGUAIP->docno = getNextSeriesNoByBranch("u_lguaip",$obju_LGUAIP->docseries,'',$objConnection);
						$obju_LGUAIP->docstatus = "O";
						$obju_LGUAIP->setudfvalue("u_yr",$pcdata["yr"]);
						$obju_LGUAIP->setudfvalue("u_profitcenter",$pcdata["code"]);
						$obju_LGUAIP->setudfvalue("u_profitcentername",$pcdata["name"]);
					}
					$totalamount = 0;
					$ps = 0;
					$mooe = 0;
					$co = 0;
					if ($actionReturn) {
						foreach ($pcdata["items"] as $item) {
							$obju_LGUAIPItems->prepareadd();
							$obju_LGUAIPItems->docid = $obju_LGUAIP->docid;
							$obju_LGUAIPItems->lineid = getNextIdByBranch("u_lguaipitems",$objConnection);
							$obju_LGUAIPItems->setudfvalue("u_refcode",$item["aipcode"]);
							$obju_LGUAIPItems->setudfvalue("u_description",$item["description"]);
							$obju_LGUAIPItems->setudfvalue("u_office",$item["office"]);
							$obju_LGUAIPItems->setudfvalue("u_start",$item["start"]);
							$obju_LGUAIPItems->setudfvalue("u_completion",$item["completion"]);
							$obju_LGUAIPItems->setudfvalue("u_expectedoutput",$item["expectedoutput"]);
							$obju_LGUAIPItems->setudfvalue("u_funding",$item["funding"]);
							$obju_LGUAIPItems->setudfvalue("u_ps",$item["ps"]);
							$obju_LGUAIPItems->setudfvalue("u_mooe",$item["mooe"]);
							$obju_LGUAIPItems->setudfvalue("u_co",$item["co"]);
							$obju_LGUAIPItems->setudfvalue("u_total",$item["total"]);
							$obju_LGUAIPItems->setudfvalue("u_cca",$item["cca"]);
							$obju_LGUAIPItems->setudfvalue("u_ccm",$item["ccm"]);
							$obju_LGUAIPItems->setudfvalue("u_cctc",$item["cctc"]);
							$obju_LGUAIPItems->privatedata["header"] = $obju_LGUAIP;
							$actionReturn = $obju_LGUAIPItems->add();
							if (!$actionReturn) break;
							
							$totalamount += $item["total"];
							$ps += $item["ps"];
							$mooe += $item["mooe"];
							$co += $item["co"];
						}
					}	
					
					if (!$actionReturn) break;
					
					$obju_LGUAIP->setudfvalue("u_totalamount",$totalamount);
					$obju_LGUAIP->setudfvalue("u_ps",$ps);
					$obju_LGUAIP->setudfvalue("u_mooe",$mooe);
					$obju_LGUAIP->setudfvalue("u_co",$co);
					
					if ($obju_LGUAIP->rowstat=="N") $actionReturn = $obju_LGUAIP->add();
					else  $actionReturn = $obju_LGUAIP->update($obju_LGUAIP->docno,$obju_LGUAIP->rcdversion);
					
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