<?php
        set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_uploadRPTOR";
        
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
$objGrid1->addcolumn(array('name'=>'date','description'=>'Date','length'=>10));
$objGrid1->addcolumn(array('name'=>'ownername','description'=>'Owner Name','length'=>50));
$objGrid1->addcolumn(array('name'=>'tdno','description'=>'TD Number','length'=>15));
$objGrid1->addcolumn(array('name'=>'barangay','description'=>'Barangay','length'=>25));
$objGrid1->addcolumn(array('name'=>'ornumber','description'=>'Receipt Number','length'=>15));
$objGrid1->addcolumn(array('name'=>'yearfrm','description'=>'Year From','length'=>10));
$objGrid1->addcolumn(array('name'=>'yearto','description'=>'Year To','length'=>10));
$objGrid1->addcolumn(array('name'=>'amountpaid','description'=>'Amount Paid','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'currenttax','description'=>'Current Tax','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'precedingtax','description'=>'Preceding Tax','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'currentpenalty','description'=>'Current Penalty','length'=>10,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'precedingpenalty','description'=>'Preceding Penalty','length'=>15,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'discount','description'=>'Discount','length'=>10,'align'=>'right'));
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

		$obju_RPTaxes = new documentschema_br(NULL,$objConnection,"u_rptaxes");
		$obju_RPTaxArps = new documentlinesschema_br(NULL,$objConnection,"u_rptaxarps");
		$objProfitCenters = new profitcenters(null,$objConnection);
		
		$objrs = new recordset(null,$objConnection);
		$objConnection->beginwork();
		$actionReturn = true;
		$_SESSION['errormessage'] = "";
		
		if(isFileUploaded($uploadfile) == false) return false;

		$objExcel = new Excel;
		$objExcel->openxl(realpath($uploadfile),"",""); 
		
		
		$sheet = "RPTAXES";
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
		
		
		
		while($content = $objExcel->readrange($sheet,"A".$row.":N".$row)) {
			if(implode('',$content) == "") break;

			if ($row==1) {
				if ($content[0]!="Date" || $content[1]!="Owner Name" || $content[2]!="OR Number" || $content[3]!="Paid By" || $content[4]!="Amount Paid" || 
					$content[5]!="TD Number" || $content[6]!="Barangay" || $content[7]!="Tax Year From" || $content[8]!="Tax Year To" || $content[9]!="Current Year Basic" || 
					$content[10]!="Preceding Year Basic" || $content[11]!="Current Penalties" || $content[12]!="Preceding Penalties" || $content[13]!="Discount" ) {
					$actionReturn = raiseError("Invalid Header!");
					$error = true;
					break;
				}	
				$row++;
				continue;
			}
			
			$isvalid = true;	
			$idx = 'T1r'.$rowidx;
			$data['df_date'.$idx] = ltrim($content[0]);
			$data['df_ownername'.$idx] = ltrim($content[1]);
			$data['df_ornumber'.$idx] = ltrim($content[2]);
			$data['df_amountpaid'.$idx] = floatval(ltrim($content[4]));
			$data['df_tdno'.$idx] = ltrim($content[5]);
			$data['df_barangay'.$idx] = ltrim($content[6]);
			$data['df_yearfrm'.$idx] = ltrim($content[7]);
			$data['df_yearto'.$idx] = ltrim($content[8]);
			$data['df_currenttax'.$idx] = floatval(ltrim($content[9]));
			$data['df_precedingtax'.$idx] = floatval(ltrim($content[10]));
			$data['df_currentpenalty'.$idx] = floatval(ltrim($content[11]));
			$data['df_precedingpenalty'.$idx] = floatval(ltrim($content[12]));
			$data['df_discount'.$idx] = floatval(ltrim($content[13]));
			$errormessage = "";
			
			if($data['df_yearfrm'.$idx] == "") $errormessage .= " Year from is required. ";
			if($data['df_yearto'.$idx] == "") $errormessage .= " Year to is required. ";
			if($data['df_tdno'.$idx] == "") $errormessage .= " Tax declaration is required. ";
			if($data['df_date'.$idx] == "") $errormessage .= " Date is required. ";
			if($data['df_ornumber'.$idx] == "") $errormessage .= " OR Number is required. ";
			if (($data['df_currenttax'.$idx]+$data['df_precedingtax'.$idx]+$data['df_currentpenalty'.$idx]+$data['df_precedingpenalty'.$idx])>0) {
                            
                            if($data['df_yearfrm'.$idx] == substr($data['df_date'.$idx],0,4) ){
                                $amount = ($data['df_currenttax'.$idx]+$data['df_currentpenalty'.$idx]) *2;
                                $resultamount = $data['df_amountpaid'.$idx] -  $amount;
                                $resultamount2 = number_format($resultamount,2);
                                if($resultamount2 != 0) $errormessage .= " Current tax amount[".$amount."] is not eqaul to Total amount[".$data['df_amountpaid'.$idx]."]. ";
                            }else if($data['df_yearfrm'.$idx] < substr($data['df_date'.$idx],0,4) && $data['df_yearto'.$idx] < substr($data['df_date'.$idx],0,4)){
                                $amount2 = ($data['df_precedingtax'.$idx]+$data['df_precedingpenalty'.$idx]) *2 ;
                                 $resultamount = $data['df_amountpaid'.$idx] -  $amount2;
                                $resultamount2 = number_format($resultamount,2);
                                if($data['df_discount'.$idx] > 0)  $errormessage .= "Discount amount['".$data['df_discount'.$idx]."'] is invalid.";
                                if($resultamount2 != 0) $errormessage .= " Preceding tax amount[".$amount2."] is not eqaul to Total amount[".$data['df_amountpaid'.$idx]."]. ";
                            }else if($data['df_yearfrm'.$idx] < substr($data['df_date'.$idx],0,4) && $data['df_yearto'.$idx] >= substr($data['df_date'.$idx],0,4)){
                                $amount3 = ($data['df_currenttax'.$idx]+$data['df_precedingtax'.$idx]+$data['df_currentpenalty'.$idx]+$data['df_precedingpenalty'.$idx]) * 2;
                                $resultamount = $data['df_amountpaid'.$idx] -  $amount3;
                                $resultamount2 = number_format($resultamount,2);
                                if($resultamount2 != 0 ) $errormessage .= " Tax amount[".$amount3."] and Total amount is not equal[".$data['df_amountpaid'.$idx]."] . ";
                            }else{
                                    $errormessage .= "Date Year From/To is invalid. ";
                                    $isvalid=false;
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
				$item["date"] = $data['df_date'.$idx];
				$item["ownername"] = $data['df_ownername'.$idx];
				$item["ornumber"] = $data['df_ornumber'.$idx];
				$item["amountpaid"] = $data['df_amountpaid'.$idx];
				$item["tdno"] = $data['df_tdno'.$idx];
				$item["barangay"] = $data['df_barangay'.$idx];
				$item["yearfrm"] = $data['df_yearfrm'.$idx];
				$item["yearto"] = $data['df_yearto'.$idx];
				$item["currenttax"] = $data['df_currenttax'.$idx];
				$item["precedingtax"] = $data['df_precedingtax'.$idx];
				$item["currentpenalty"] = $data['df_currentpenalty'.$idx];
				$item["precedingpenalty"] = $data['df_precedingpenalty'.$idx];
				$item["discount"] = $data['df_discount'.$idx];
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
					if ($obju_RPTaxes->getbysql("u_ornumber='".$data["ornumber"]."' ")) {
						/*if ($obju_LGUPPMP->docstatus=="Approved") {
							$actionReturn = raiseError($pcdata["yr"] . " Appropriation for [".$pcdata["name"]."] cannot be uploaded if already approved.");
						} else {*/
							$actionReturn = $objrs->executesql("delete from u_rptaxarps where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid='$obju_RPTaxes->docid'",false);
						//}
					} else {
						$obju_RPTaxes->prepareadd();
						$obju_RPTaxes->docid = getNextIdByBranch("u_rptaxes",$objConnection);
						$obju_RPTaxes->docno = getNextNoByBranch("u_lgubills",'',$objConnection);
						$obju_RPTaxes->docstatus = "O";
						$obju_RPTaxes->setudfvalue("u_isupload",1);
						$obju_RPTaxes->setudfvalue("u_uploadeddate",currentdateDB());
						$obju_RPTaxes->setudfvalue("u_assdate",$data["date"]);
						$obju_RPTaxes->setudfvalue("u_ordate",$data["date"]);
						$obju_RPTaxes->setudfvalue("u_ornumber",$data["ornumber"]);
						$obju_RPTaxes->setudfvalue("u_partialpay",0);
						$obju_RPTaxes->setudfvalue("u_paymode","A");
						$obju_RPTaxes->setudfvalue("u_tdno",$data["tdno"]);
						$obju_RPTaxes->setudfvalue("u_declaredowner",$data["ownername"]);
						$obju_RPTaxes->setudfvalue("u_paidby",$data["ownername"]);
						$obju_RPTaxes->setudfvalue("u_yearfrom",$data["yearfrm"]);
						$obju_RPTaxes->setudfvalue("u_yearto",$data["yearto"]);
					}
					$totalamount = 0;
					$tax = 0;
					$sef = 0;
					$discount = 0;
					$sefdiscount = 0;
					$penalty = 0;
					$sefpenalty = 0;
                                        
                                        $assvalue = ($data["currenttax"] + ($data["discount"] / 2)) * 100;
                                        if($actionReturn){
                                            if($data["yearfrm"] < substr($data["date"],0,4) && $data["yearto"] < substr($data["date"],0,4) || ($data["yearfrm"] < substr($data["date"],0,4) && $data["yearto"] >= substr($data["date"],0,4)) ){
                                                    
                                                    $obju_RPTaxArps->prepareadd();
                                                    $obju_RPTaxArps->docid = $obju_RPTaxes->docid;
                                                    $obju_RPTaxArps->lineid = getNextIdByBranch("u_rptaxarps",$objConnection);
                                                    $obju_RPTaxArps->setudfvalue("u_selected",1);
                                                    $obju_RPTaxArps->setudfvalue("u_arpno",$data["tdno"]);
                                                    $obju_RPTaxArps->setudfvalue("u_ownername",$data["ownername"]);
                                                    $obju_RPTaxArps->setudfvalue("u_tdno",$data["tdno"]);
                                                    $obju_RPTaxArps->setudfvalue("u_barangay",$data["barangay"]);
                                                    $obju_RPTaxArps->setudfvalue("u_noofyrs",( substr($data["date"],0,4) - $data["yearfrm"]) + 1);
                                                    if($data["yearfrm"] < substr($data["date"],0,4) && $data["yearto"] < substr($data["date"],0,4)){
                                                        $obju_RPTaxArps->setudfvalue("u_yrfr",$data["yearfrm"]); 
                                                        $obju_RPTaxArps->setudfvalue("u_yrto",$data["yearto"]);
                                                        $assvalue = ($data["precedingtax"]) * 100;
                                                    }else if($data["yearfrm"] < substr($data["date"],0,4) && $data["yearto"] >= substr($data["date"],0,4)){
                                                        $obju_RPTaxArps->setudfvalue("u_yrfr",$data["yearfrm"]); 
                                                        $obju_RPTaxArps->setudfvalue("u_yrto",substr($data["date"],0,4) - 1);
                                                    }
                                                    
                                                    $obju_RPTaxArps->setudfvalue("u_assvalue",$assvalue);
                                                    $obju_RPTaxArps->setudfvalue("u_taxdue",round($data["precedingtax"] ,2 ));
                                                    $obju_RPTaxArps->setudfvalue("u_penalty",$data["precedingpenalty"]);
                                                    $obju_RPTaxArps->setudfvalue("u_sef",round($data["precedingtax"] ,2));
                                                    $obju_RPTaxArps->setudfvalue("u_sefpenalty",$data["precedingpenalty"]);
                                                    $obju_RPTaxArps->setudfvalue("u_discperc",0);
                                                    $obju_RPTaxArps->setudfvalue("u_taxdisc",0);
                                                    $obju_RPTaxArps->setudfvalue("u_sefdisc",0);
                                                    $obju_RPTaxArps->setudfvalue("u_taxtotal",round($data["precedingtax"] + $data["precedingpenalty"],2));
                                                    $obju_RPTaxArps->setudfvalue("u_seftotal",round($data["precedingtax"] + $data["precedingpenalty"],2));
                                                    $obju_RPTaxArps->setudfvalue("u_linetotal",round(($data["precedingtax"] + $data["precedingpenalty"])*2,2));
                                                    $obju_RPTaxArps->setudfvalue("u_billdate",$data["date"]);
                                                    $obju_RPTaxArps->privatedata["header"] = $obju_RPTaxes;
                                                    $actionReturn = $obju_RPTaxArps->add();
                                                    if (!$actionReturn) break;
                                                    
                                                    $tax += $data["precedingtax"];
                                                    $sef += $data["precedingtax"];
                                                    $discount += 0;
                                                    $sefdiscount += 0;
                                                    $penalty += $data["precedingpenalty"];
                                                    $sefpenalty += $data["precedingpenalty"];
                                            }
                                            if($data["yearfrm"] == substr($data["date"],0,4) || ($data["yearfrm"] < substr($data["date"],0,4) && $data["yearto"] >= substr($data["date"],0,4)) ){ 
                                                    $obju_RPTaxArps->prepareadd();
                                                    $obju_RPTaxArps->docid = $obju_RPTaxes->docid;
                                                    $obju_RPTaxArps->lineid = getNextIdByBranch("u_rptaxarps",$objConnection);
                                                    $obju_RPTaxArps->setudfvalue("u_selected",1);
                                                    $obju_RPTaxArps->setudfvalue("u_arpno",$data["tdno"]);
                                                    $obju_RPTaxArps->setudfvalue("u_ownername",$data["ownername"]);
                                                    $obju_RPTaxArps->setudfvalue("u_tdno",$data["tdno"]);
                                                    $obju_RPTaxArps->setudfvalue("u_barangay",$data["barangay"]);
                                                    $obju_RPTaxArps->setudfvalue("u_assvalue",$assvalue);
                                                    $obju_RPTaxArps->setudfvalue("u_noofyrs",(substr($data["date"],0,4) - $data["yearfrm"]) + 1);
                                                    if($data["yearfrm"] = substr($data["date"],0,4)){
                                                        $obju_RPTaxArps->setudfvalue("u_yrfr",$data["yearfrm"]); 
                                                        $obju_RPTaxArps->setudfvalue("u_yrto",$data["yearto"]);
                                                    }else if($data["yearfrm"] < substr($data["date"],0,4) && $data["yearto"] >= substr($data["date"],0,4)){
                                                        $obju_RPTaxArps->setudfvalue("u_yrto",$data["yearto"]); 
                                                        $obju_RPTaxArps->setudfvalue("u_yrfrm",substr($data["date"],0,4));
                                                    }
                                                    $obju_RPTaxArps->setudfvalue("u_taxdue",round($data["currenttax"] + ($data["discount"] / 2)  ,2 ));
                                                    $obju_RPTaxArps->setudfvalue("u_penalty",$data["currentpenalty"]);
                                                    $obju_RPTaxArps->setudfvalue("u_sef",round($data["currenttax"] + ($data["discount"] / 2) ,2));
                                                    $obju_RPTaxArps->setudfvalue("u_sefpenalty",$data["currentpenalty"]);
                                                    $obju_RPTaxArps->setudfvalue("u_discperc",round((($data["discount"] / 2) / ($data["currenttax"] + ($data["discount"] / 2)))* 100,2));
                                                    $obju_RPTaxArps->setudfvalue("u_taxdisc",round($data["discount"] / 2 ,2));
                                                    $obju_RPTaxArps->setudfvalue("u_sefdisc",round($data["discount"] / 2,2));
                                                    // value from excel file the discount is already been deducted to the current tax 
                                                    $obju_RPTaxArps->setudfvalue("u_taxtotal",round($data["currenttax"] + $data["currentpenalty"],2));
                                                    $obju_RPTaxArps->setudfvalue("u_seftotal",round($data["currenttax"] + $data["currentpenalty"],2));
                                                    $obju_RPTaxArps->setudfvalue("u_linetotal",round(($data["currenttax"] + $data["currentpenalty"])*2,2));
                                                    $obju_RPTaxArps->setudfvalue("u_billdate",$data["date"]);
                                                    $obju_RPTaxArps->privatedata["header"] = $obju_RPTaxes;
                                                    $actionReturn = $obju_RPTaxArps->add();
                                                    if (!$actionReturn) break;

                                                    //I Add discount because of the excel format that bacoor gave to me
                                                    $tax += $data["currenttax"] + ($data["discount"]/2);
                                                    $sef += $data["currenttax"] + ($data["discount"]/2);
                                                    $discount += ($data["discount"] / 2);
                                                    $sefdiscount += ($data["discount"] / 2);
                                                    $penalty += $data["currentpenalty"];
                                                    $sefpenalty += $data["currentpenalty"];
                                            }  
                                        }
                                       
					
					
					if (!$actionReturn) break;
					
					
					$obju_RPTaxes->setudfvalue("u_tax",$tax);
					$obju_RPTaxes->setudfvalue("u_seftax",$sef);
					$obju_RPTaxes->setudfvalue("u_discamount",$discount);
					$obju_RPTaxes->setudfvalue("u_sefdiscamount",$sefdiscount);
					$obju_RPTaxes->setudfvalue("u_penalty",$penalty);
					$obju_RPTaxes->setudfvalue("u_sefpenalty",$sefpenalty);
                                        $obju_RPTaxes->setudfvalue("u_totaltaxamount",($tax+$sef+$penalty+$sefpenalty - ($discount + $sefdiscount)));
                                        $obju_RPTaxes->setudfvalue("u_paidamount",($tax+$sef+$penalty+$sefpenalty- ($discount + $sefdiscount)));
					
					if ($obju_RPTaxes->rowstat=="N") $actionReturn = $obju_RPTaxes->add();
					else  $actionReturn = $obju_RPTaxes->update($obju_RPTaxes->docno,$obju_RPTaxes->rcdversion);
					
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