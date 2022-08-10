
<?php
        $progid = "u_uploadpostransactions";
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
 
	include_once("../common/classes/connection.php");
	require_once ("../common/classes/excel.php");
        include_once("../common/classes/grid.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./utils/postingperiods.php");
	include_once("./utils/companies.php");
	include_once("./utils/branches.php");
        require_once("./dtw/udos.php");
        require_once("./inc/dtw.php");
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
        if (document.formData.df_filename.value.substr(document.formData.df_filename.value.length-4,4).toLowerCase() == "xlsx" || document.formData.df_filename.value.substr(document.formData.df_filename.value.length-4,4).toLowerCase() == ".xls" ) {
	  
        }else{
            alert("Invalid filename! Only XLS and XLSX files are allowed.");
            return false;   
        }
	document.formData.formAction.value = "a";
	document.formData.submit();
	return false;
}

	function onFormSubmitReturn(action,success,error) {
		if (success) {
			alert("Successfully uploaded the pos transaction.");
                        
		} else {
			alert(error);
		}
		
	}
	
</script>

<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="resizeObjects()">
<FORM name="formData" autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']?>?">

<?php

$objGrid1 = new grid("T1");
$objGrid1->addcolumn(array('name'=>'branch','description'=>'Branch','length'=>5));
$objGrid1->addcolumn(array('name'=>'docdate','description'=>'Date','length'=>10));
$objGrid1->addcolumn(array('name'=>'orno','description'=>'OR No.','length'=>10));
$objGrid1->addcolumn(array('name'=>'acctbranch','description'=>'Acct Branch','length'=>10));
$objGrid1->addcolumn(array('name'=>'custno','description'=>'Customer No.','length'=>15));
$objGrid1->addcolumn(array('name'=>'custname','description'=>'Customer Name','length'=>30));
$objGrid1->addcolumn(array('name'=>'acctno','description'=>'Account No.','length'=>15));
$objGrid1->addcolumn(array('name'=>'principal','description'=>'Principal','length'=>9,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'rebate','description'=>'Rebate','length'=>6,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'penalty','description'=>'Penalty','length'=>6,'align'=>'right'));
$objGrid1->addcolumn(array('name'=>'uploadremarks','description'=>'Upload Remarks','length'=>50));
$objGrid1->automanagecolumnwidth=false;
$objGrid1->dataentry = false;

$httpVars['df_company'] = $_SESSION['company'];
$httpVars['df_branch'] = $_SESSION['branch'];

require("./inc/upload.php");
require("./inc/formobjs.php");
require("./inc/formactions.php");

        function UploadtoPos(){
            GLOBAL $httpVars;
            GLOBAL $objGrid1;
            GLOBAL $objConnection;
            global $page;
            $objPos= new documentschema_br(null,$objConnection,"u_lgupos");
            $objPosItems = new documentlinesschema_br(null,$objConnection,"u_lgupositems");
            $objposupload = new masterdataschema_br(null,$objConnection,"u_lguposupload");
            
            $objRs = new recordset(null,$objConnection);
            $objRs->queryopen("select u_ornumber,u_date,u_custname,u_amount1,u_amount2,u_amount3,u_amount4,u_amount5,u_amount6,u_amount7,u_amount8,u_amount9,u_amount10,u_amount11,u_amount12,u_amount13,u_amount14,u_amount15,u_amount16,u_amount17,u_amount18,u_amount19,u_amount20,u_amount21,u_amount22,u_amount23,u_amount24,u_amount25,u_amount26,u_amount27,u_amount28,u_amount29,u_amount30,u_amount31,u_amount32 from u_lguposupload ");
//            var_dump($objRs);
            $objConnection->beginwork();
            while ($objRs->queryfetchrow("NAME")) {
//                var_dump($objRs->fields["u_ornumber"]);
                if($objPos->getbysql("DOCNO = '".$objRs->fields["u_ornumber"]."'")){
                }else{
                    $objPos->prepareadd();
                    $objPos->docid = getNextIdByBranch("u_lgupos",$objConnection);
                    $objPos->docno = $objRs->fields["u_ornumber"];
                    $objPos->setudfvalue("u_tfcountry","PH");
                    $objPos->setudfvalue("u_custname",$objRs->fields["u_custname"]);
                    $objPos->setudfvalue("u_custno","Cash");
                    $objPos->setudfvalue("u_date",$objRs->fields["u_date"]) ;
                    $objPos->setudfvalue("u_status","O");
                    $totalamount=0;
                   
                    for($i = 1; $i <= 32; $i++) {
                        $field = "U_AMOUNT" . $i;
                        if($objRs->fields[$field]>0){
                        $objPosItems->prepareadd();
                        $objPosItems->docid = $objPos->docid;
                        $objPosItems->lineid = getNextIdByBranch("u_lgupositems",$objConnection);
                        $objRs1 = new recordset(null,$objConnection);
                        $objRs1->queryopen("select u_glacctcode,code from u_lgufeesubgroups where u_seqno = ".$i." ");
                        $objPosItems->setudfvalue("u_itemcode",$objRs1->fields["u_glacctcode"]);
                        $objPosItems->setudfvalue("u_itemdesc",$objRs1->fields["code"]);
                        $objPosItems->setudfvalue("u_quantity",1);
                        $objPosItems->setudfvalue("u_unitprice",$objRs->fields[$field]);
                        $objPosItems->setudfvalue("u_linetotal",$objRs->fields[$field]);
                        $objPosItems->setudfvalue("u_price",$objRs->fields[$field]);
                        $objPosItems->setudfvalue("u_selected",1);
                        $objPosItems->privatedata["header"] = $objPos;
                        $actionReturn = $objPosItems->add();
                        var_dump($actionReturn);
                        $totalamount+= $objRs->fields[$field];
                        }
                    }
                    
                        if ($actionReturn) {
                        $objPos->setudfvalue("u_paidamount",$totalamount);
                        $objPos->setudfvalue("u_totalamount",$totalamount);
                        $objPos->setudfvalue("u_cashamount",$totalamount);
                        $actionReturn = $objPos->add();
                        }
                }
//                var_dump($actionReturn);
               
                
                
                
                if (!$actionReturn) break;
                
            }
            if ($actionReturn){
                    $objConnection->commit();
            }else{
                    $objConnection->rollback();
//                    break;
            }
            
            $actionReturn = $objposupload->executesql("DELETE FROM u_lguposupload",false);
            
            return true;
        }
	
	function uploadPosTransaction($uploadfile) {
		GLOBAL $httpVars;
		GLOBAL $objGrid1;
		GLOBAL $objConnection;
		global $page;

//		$companydata = getcurrentcompanydata("DEPARTMENTALSALES");
//		$branchdata = getcurrentbranchdata("POSCUSTNO");
	
//		$objIntBrLegacyCollections = new masterdataschema_br(NULL,$objConnection,"u_intbrlegacycollections");
	
//		$objrs = new recordset(null,$objConnection);
//		$objConnection->beginwork();
		$actionReturn = true;
		$_SESSION['errormessage'] = "";
			
		if(isFileUploaded($uploadfile) == false) return false;

                    $objDTWu_posUpload= new dtw_udos("lguposupload");
                    $actionReturn = $objDTWu_posUpload->upload($uploadfile);
                
//		$objExcel = new Excel;
//		$objExcel->openxl(realpath($uploadfile),"",""); 
//		
//		
//		$sheet = "Inter-Branch Collections";
//		$row = 2;
//		$i = 1;
//		$rowidx = 1;
//		
//		$rollback = false;		
//		$errormessages = "";
//
//		if (isset($objGrid1)) $objGrid1->clear();
//		
//		
//		$error = false;
//		while($content = $objExcel->readrange($sheet,"A".$row.":G".$row)) {
//			if(implode('',$content) == "") break;
//			
//			if ($content[5]=="") $content[5] = $content[4];
//			
//			$isvalid = true;		
//			
//			$idx = 'T1r'.$rowidx;
//			$data['df_orno'.$idx] = ltrim($content[0]);
//			$data['df_docdate'.$idx] = ltrim($content[1]);
//			$data['df_acctno'.$idx] = ltrim($content[2]);
//			$data['df_custname'.$idx] = ltrim($content[3]);
//			$data['df_principal'.$idx] = formatNumericAmount(ltrim($content[4]));
//			$data['df_rebate'.$idx] = formatNumericAmount(ltrim($content[5]));
//			$data['df_penalty'.$idx] = formatNumericAmount(ltrim($content[6]));
//			//$objGrid1->addrowdata($data);
//			
//			if (formatNumeric($data['df_principal'.$idx],0)==0 && formatNumeric($data['df_rebate'.$idx],0)==0 && formatNumeric($data['df_penalty'.$idx],0)==0) {
//				$row++;
//				/*
//				$data['df_uploadremarks'.$idx] = "<b>Line #".$row.": Ignored. Inter Branch Fee.</b>";
//				$objGrid1->addrowdata($data);
//				
//				$rowidx++;
//				*/
//				continue;
//			}	
//			
//			$errormessage = "";
//			
//			if (substr($data['df_orno'.$idx],4,1)=="-") {
//				$branchcode =substr($data['df_orno'.$idx],0,5);
//			} else {
//				$a = substr($data['df_orno'.$idx],0,1);
//				$b = substr($data['df_orno'.$idx],1,1);
//				$c = substr($data['df_orno'.$idx],2,1);
//				$branchcode = "$a";
//				if (!is_numeric($b)) $branchcode .= $b;
//				if (!is_numeric($c)) $branchcode .= $c;
//			}			
//			
//			$objrs->queryopen("select CODE,U_AUTOPOST from u_trxsync where u_orprefix = '".$branchcode."' or u_orprefix2 = '".$branchcode."' or u_orprefix3 = '".$branchcode."'");
//			if ($objrs->queryfetchrow("NAME")) {
//				
//				if ($objrs->fields["U_AUTOPOST"]=="NP") {
//					$row++;
//					/*$data['df_uploadremarks'.$idx] = "<b>Line #".$row.": Ignored. Live portal already (not postable).</b>";
//					$objGrid1->addrowdata($data);
//					$rowidx++;						
//					*/
//					continue;
//				}
//				
//				$data['df_branch'.$idx] = $objrs->fields["CODE"];
//				//$objrs->queryopen("select migratedate from brancheslist where branchcode='".$data['df_branch'.$idx]."' and migrated='1'");
//				//if ($objrs->queryfetchrow("NAME")) {
//				//	$row++;
//					/*
//					$data['df_uploadremarks'.$idx] = "<b>Line #".$row.": Ignored. Live portal already (migrated).</b>";
//					$objGrid1->addrowdata($data);
//					$rowidx++;						
//					*/
//				//	continue;
//				//}							
//			} else {	
//				$errormessage .= " Invalid OR Prefix[$branchcode]";
//				$error = true;
//			}
//
//			if ($errormessage=="") {
//				
//				$objrs->queryopen("select a.BRANCH  as BRANCHCODE, a.U_CUSTNO as CUSTNO from u_sales a, u_trxsync b where b.code = a.branch and b.u_autopost='NP' and a.u_acctno='".$data['df_acctno'.$idx]."'");
//				if ($objrs->queryfetchrow("NAME")) {
//					$data['df_acctbranch'.$idx] = $objrs->fields["BRANCHCODE"];
//					$data['df_custno'.$idx] = $objrs->fields["CUSTNO"];
//					/*
//					$objrs->queryopen("select MIGRATEDATE from BRANCHESLIST where branchcode='".$data['df_acctbranch'.$idx]."'");
//					if ($objrs->queryfetchrow("NAME")) {
//						$cutoffloat = floatval(str_replace("-","",$objrs->fields["MIGRATEDATE"]));
//						$docdatefloat = floatval(date('Ymd',strtotime(strval($data['df_docdate'.$idx]))));
//						if ($docdatefloat < $cutoffloat) {
//							$row++;
//							$data['df_uploadremarks'.$idx] = "<b>Line #".$row.": Ignored. Collection is within cut-off date [".$objrs->fields["U_CUTOFF"]."].</b>";
//							$objGrid1->addrowdata($data);
//							$rowidx++;						
//							continue;
//						}
//					} else {
//						$errormessage .= " Invalid Loan Data Migration (".$data['df_acctbranch'.$idx].")";				
//						$error = true;
//					}
//					*/
//				} else {	
//					$errormessage .= " Invalid Account No..";
//					$error = true;
//				}
//				
//			}
//						
//			if($data['df_branch'.$idx] == "" && $errormessage=="") $errormessage .= " Branch is required.";
//			if($data['df_orno'.$idx] == "" && $errormessage=="") $errormessage .= " OR No. is required.";
//			if($data['df_docdate'.$idx] == "" && $errormessage=="") $errormessage .= " Transaction Date is required.";
//			if($data['df_acctno'.$idx] == "" && $errormessage=="") $errormessage .= " Item Code is required.";
//			if($errormessage <> "") {
//				$data['df_uploadremarks'.$idx] = "Line #".$row.": " . $errormessage;
//				$errormessages .= $errormessage;
//				$error = true;
//				$isvalid=false;
//			}
//
//			if ($isvalid) {
//				if (!$objIntBrLegacyCollections->getbykey($data['df_branch'.$idx] . "-" . $data['df_orno'.$idx])) {
//					$objIntBrLegacyCollections->prepareadd();
//					$objIntBrLegacyCollections->code = $data['df_branch'.$idx] . "-" . $data['df_orno'.$idx];
//					$objIntBrLegacyCollections->name = $objIntBrLegacyCollections->code;
//					$objIntBrLegacyCollections->userfields["u_branch"]["value"] = $data['df_branch'.$idx];
//					$objIntBrLegacyCollections->userfields["u_orno"]["value"] = $data['df_orno'.$idx];
//					$objIntBrLegacyCollections->userfields["u_docdate"]["value"] = date('Y-m-d',strtotime(strval($data['df_docdate'.$idx])));
//					$objIntBrLegacyCollections->userfields["u_acctbranch"]["value"] = $data['df_acctbranch'.$idx];
//					$objIntBrLegacyCollections->userfields["u_custno"]["value"] = $data['df_custno'.$idx];
//					$objIntBrLegacyCollections->userfields["u_custname"]["value"] = $data['df_custname'.$idx];
//					$objIntBrLegacyCollections->userfields["u_acctno"]["value"] = $data['df_acctno'.$idx];
//					$objIntBrLegacyCollections->userfields["u_principal"]["value"] = formatNumeric($data['df_principal'.$idx],"",0);
//					$objIntBrLegacyCollections->userfields["u_rebate"]["value"] = formatNumeric($data['df_rebate'.$idx],"",0);
//					$objIntBrLegacyCollections->userfields["u_penalty"]["value"] = formatNumeric($data['df_penalty'.$idx],"",0);
//					
//					$actionReturn = isPostingDateValid($objIntBrLegacyCollections->userfields["u_docdate"]["value"],$objIntBrLegacyCollections->userfields["u_docdate"]["value"],$objIntBrLegacyCollections->userfields["u_docdate"]["value"]);
//					if ($actionReturn) {
//						if (!$objIntBrLegacyCollections->add()) {
//							$actionReturn = false;
//							$data['df_uploadremarks'.$idx] = "Line #".$row.": " . $_SESSION["errormessage"];
//							$errormessages .= $_SESSION["errormessage"];
//							$error = true;
//							if (isset($objGrid1)) {
//								$objGrid1->addrowdata($data);
//								$rowidx++;	
//							}	
//						} else {
//							$data['df_uploadremarks'.$idx] = "Line #".$row.": Uploaded.";
//							$objGrid1->addrowdata($data);
//							$rowidx++;	
//						}	
//					} else {
//						$data['df_uploadremarks'.$idx] = "Line #".$row.": " . $_SESSION["errormessage"];
//						$errormessages .= $_SESSION["errormessage"];
//						$error = true;
//						if (isset($objGrid1)) {
//							$objGrid1->addrowdata($data);
//							$rowidx++;	
//						}	
//					}	
//				} else {	
//					$data['df_uploadremarks'.$idx] = "<i>Line #".$row.": Uploaded Already.</i>";
//					$objGrid1->addrowdata($data);
//					$rowidx++;	
//				}	
//			} else {
//				$objGrid1->addrowdata($data);
//				$rowidx++;	
//			}	
//			
//			$row++;
//			$i++;
//			//break;
//			//$rowidx++;
//		} # end while
		
		
		if ($errormessages!="") {
			$_SESSION['errormessage'] = $errormessages;
		}
                
		
//		$objExcel->closeXL();
//		unset($objExcel);
		if ($actionReturn) $actionReturn = UploadtoPos();
		return $actionReturn;
	}
	
	function onFormAdd() {
		global $objGrid1;
		$actionReturn = true;
		$uploadfile = "upload/" . basename($_FILES['df_filename']['name']);
		
		$actionReturn = uploadPosTransaction($uploadfile);
		
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
       	          <td class="labelPageHeader" >&nbsp;Upload: POS Collections</td>
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
			<td colspan="3"><a class="button" href="" onClick="formSubmit();return false;" > &nbsp; &nbsp; Upload &nbsp; &nbsp; </a></td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4"><?php if ($objGrid1->recordcount>0) $objGrid1->draw() ?></td>
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