<?php
	$progid = "u_hisqueuedisplay2";

	//header('Access-Control-Allow-Origin: *');
	//header('Access-Control-Allow-Methods: GET, POST, HEAD, OPTIONS'); 

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/documentschema_br.php");
	include_once("./sls/enumdocstatus.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
//	include_once("./inc/formaccess.php");

	$enumkindofproperty = array();
	$enumkindofproperty["L"]="Land";
	$enumkindofproperty["B"]="Building and Other Structure";
	$enumkindofproperty["M"]="Machinery";

	unset($enumdocstatus["D"],$enumdocstatus["O"],$enumdocstatus["C"],$enumdocstatus["CN"]);
	$enumdocstatus["Encoding"]="Encoding";
	$enumdocstatus["Assessed"]="Assessed";
	$enumdocstatus["Recommended"]="Recommended";
	$enumdocstatus["Approved"]="Approved";
	$enumdocstatus["Active"]="Active";
	$enumdocstatus["Cancelled"]="Cancelled";
	
	$ctr = array("","","","","");
	$ref = array("","","","","");
	
	$colors = array();
	$coloridx = 1;
	$colors[0] = "#FF0000";
	$colors[1] = "#FF00FF";
	$colors[2] = "#0033FF";
	
	$expired=false;
	$match=false;
	$matchstatus="";
	$search=false;
	
	$page->restoreSavedValues();	
	
	$page->objectcode = "U_RPLIST";
	$page->paging->formid = "./UDP.php?&objectcode=u_hisqueuedisplay2";
	$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Business Permit Application";
	
	

	$schema["docstatus"] = createSchemaUpper("docstatus");
	$schema["docno"] = createSchemaUpper("docno");
	$schema["u_varpno"] = createSchemaUpper("u_varpno");
	$schema["u_tdno"] = createSchemaUpper("u_tdno");
	$schema["u_pin"] = createSchemaUpper("u_pin");
	$schema["u_ownername"] = createSchemaUpper("u_ownername");
	$schema["u_taxclass"] = createSchemaUpper("u_taxclass");
	$schema["u_kind"] = createSchemaUpper("u_kind");
	$schema["u_adminname"] = createSchemaUpper("u_adminname");
	$schema["u_street"] = createSchemaUpper("u_street");
	$schema["u_barangay"] = createSchemaUpper("u_barangay");
	$schema["u_municipality"] = createSchemaUpper("u_municipality");
	$schema["u_cadlotno"] = createSchemaUpper("u_cadlotno");
//	$schema["u_birthdate"]["cfl"] = "Calendar";
	
	function loadenumkindofproperty($p_selected) {
		$_html = "";	
		$_selected = "";
		global $enumkindofproperty;
		reset($enumkindofproperty);
		while (list($key, $val) = each($enumkindofproperty)) {
			$_selected = "";
			if ($p_selected == $key) $_selected = "selected";
			$_html = $_html . "<option " . $_selected . " value=\"" . $key  . "\">" . $val . "</option>";
		}

		echo @$_html;
	}

	function onFormAction($action) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		return $actionReturn;
	}
		
	require("./inc/formactions.php");
	
	if (!isset($httpVars["df_opt"])) {
		$page->setitem("opt",1);
		$page->setitem("opt2",0);
	} else {
		$page->setitem("opt",$page->getitemnumeric("opt")+15);
		//$page->setitem("opt2",$page->getitemnumeric("opt2")+1);
	}
	
			
	$objrs = new recordset(null,$objConnection);

	/*$objrs->queryopen("select count(*) from u_bplapps where docstatus in ('Encoding','Assessing','Assessed','Approved','Disapproved')");
	if ($objrs->queryfetchrow()) {
		if ($page->getitemnumeric("opt")> $objrs->fields[0] ) $page->setitem("opt",1);
	}*/

	if ($page->getitemstring("u_group")!="") {
		//$objrs->queryopen("select count(*) from u_hisquegroups where code in ('".str_replace(",","','",$page->getitemstring("u_group"))."')");
		$objrs->queryopen("select code from u_hisquegroups where convert(code,signed)>'".intval($page->getitemnumeric("opt2"))."' and code in ('".str_replace(",","','",$page->getitemstring("u_group"))."') order by convert(code, signed)");
		if ($objrs->queryfetchrow()) {
			$page->setitem("opt2",$objrs->fields[0]);
		} else {
			$objrs->queryopen("select code from u_hisquegroups where code in ('".str_replace(",","','",$page->getitemstring("u_group"))."') order by convert(code, signed)");
			if ($objrs->queryfetchrow()) {
				$page->setitem("opt2",$objrs->fields[0]);
			}
		}
	} else {
		$objrs->queryopen("select code from u_hisquegroups where convert(code,signed)>'".intval($page->getitemnumeric("opt2"))."' order by convert(code, signed)");
		if ($objrs->queryfetchrow()) {
			$page->setitem("opt2",$objrs->fields[0]);
		} else {
			$objrs->queryopen("select code from u_hisquegroups order by convert(code, signed)");
			if ($objrs->queryfetchrow()) {
				$page->setitem("opt2",$objrs->fields[0]);
			}
		}
		/*$objrs->queryopen("select count(*) from u_hisquegroups");
		if ($objrs->queryfetchrow()) {
			if ($page->getitemnumeric("opt2")> $objrs->fields[0] ) $page->setitem("opt2",1);
		}*/
	}	

	$refresh = 2;
	$objrs->queryopen("select name, u_color, u_voicemessage, u_refresh from u_hisquegroups where code='".$page->getitemnumeric("opt2")."'");
	if ($objrs->queryfetchrow()) {
		$colors[1] =  $objrs->fields[1];
		$quename = $objrs->fields[0];
		$voicemessage = $objrs->fields[2];
		$refresh = $objrs->fields[3];
	}
	
	$speech = "";
	$idx=0;
	$objrs->queryopen("select U_CTR, U_REF from U_HISQUE WHERE U_GROUP='".$page->getitemnumeric("opt2")."' AND U_DATE='".currentdateDB()."' ORDER BY DOCNO DESC LIMIT 10");
	while ($objrs->queryfetchrow("NAME")) {
		if ($idx==0) {
			if ($page->getitemnumeric("queno".$page->getitemnumeric("opt2"))!=$objrs->fields["U_REF"]) {
				if ($voicemessage!="") {
					$speech = str_replace("{counter}",$objrs->fields["U_CTR"],$voicemessage);
					$speech = str_replace("{tag}",$objrs->fields["U_REF"],$speech);
				}	
				$page->setitem("queno".$page->getitemnumeric("opt2"),$objrs->fields["U_REF"]);
			}
		}
		$ctr[$idx] = $objrs->fields["U_CTR"];
		$ref[$idx] = $objrs->fields["U_REF"];
		$idx++;
	}
	//var_dump($speech);
	//var_dump($ctr);
	$page->toolbar->setaction("print",false);
	
	$rptcols = 6; 
	
	/*
	<iframe id="gameIFrame1" style="width: 200px; height: 200px;   display: block; position: absolute;" src="http://www.guguncube.com">
</iframe>

<iframe id="gameIFrame2" style="width: 100px; height: 100px; top: 50px;    left: 50px;    display: block;    position: absolute;" src="http://www.guguncube.com">
</iframe>
<img id="PhotoImg" height="570" src="..\AddOns\GPS\LGU Add-On\UserPrograms\imgs\ads.png" width="100%" align="absmiddle" border=1>
*/
/*
<script>
var voices = window.speechSynthesis.getVoices();
 //alert(voices.length);
  for(var i = 0; i < voices.length; i++ ) {
  //      alert("Voice " + i.toString() + ' ' + voices[i].name + ' ' + voices[i].uri);
      }

var msg = new SpeechSynthesisUtterance('Business Permit Counter 1 No. 10');
//msg.rate = 5.2;
msg.pitch = 2;
msg.voice = voices[1];

window.speechSynthesis.speak(msg);
</script>

*/
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
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
<style type="text/css">
</style>
<style type "text/css">
<!--
/* @group Blink */
.blink {
	-webkit-animation: blink .75s linear infinite;
	-moz-animation: blink .75s linear infinite;
	-ms-animation: blink .75s linear infinite;
	-o-animation: blink .75s linear infinite;
	 animation: blink .75s linear infinite;
}
@-webkit-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-moz-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-ms-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@-o-keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
@keyframes blink {
	0% { opacity: 1; }
	50% { opacity: 1; }
	50.01% { opacity: 0; }
	100% { opacity: 0; }
}
/* @end */
-->
</style>
</head>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>

<script language="JavaScript">

	function onPageLoad() {
		setTimeout("formSearchNow()",<?php echo $refresh * 1000; ?>);
		try {
		<?php if ($speech!="") { ?>
				var msg = new SpeechSynthesisUtterance('<?php echo $speech; ?>');
				pauseVideo();
				//msg.rate = 5.2;
				//msg.pitch = 2;
				window.speechSynthesis.speak(msg);
				setTimeout("resumeVideo()",<?php echo $refresh * 1000; ?>);
		<?php } ?>
		} catch (theError) {
		}
	}
	
	function pauseVideo() {
		//var domain = document.domain;
		//alert(document.domain);
		/*try {
			document.domain = "company.com";
		} catch (theError) {
			alert(theError.message);
		}*/
		//alert(document.domain);
		try {
			parent.ads.postMessage("pause","*");
			var vids = parent.ads.document.getElementsByTagName('video');
			if (vids.length>0) vids.item(0).pause();
		} catch (theError) {
			//alert(theError.message);
		}
	}

	function resumeVideo() {
		try {
			parent.ads.postMessage("resume","*");
			var vids = parent.ads.document.getElementsByTagName('video');
			if (vids.length>0) vids.item(0).play();
		} catch (theError) {
		}
	}

	function onPageSaveValues(p_action) {
		var inputs = new Array();
			inputs.push("opt");
			inputs.push("opt2");
			inputs.push("queno1");
			inputs.push("queno2");
			inputs.push("queno3");
			inputs.push("queno4");
			inputs.push("queno5");
			inputs.push("u_group");
		return inputs;
	}
	
	function formSearchNow() {
		formSearch('','<?php echo $page->paging->formid; ?>');
	}


</script>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?" target="iframeBody">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<input type="hidden" id="lookupSortBy" name="lookupSortBy" value="">
<input type="hidden" id="lookupSortAs" name="lookupSortAs" value="">
<input type="hidden" <?php genInputHiddenSFHtml("keys") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("opt") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("opt2") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("queno1") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("queno2") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("queno3") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("queno4") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("queno5") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("getprevarpno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_apptype") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_group") ?> >
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr>
	<td>
		<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td colspan="2" width="100%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="100">
                    	<td align="center" width="100%"><font size="10" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $quename ?></b></font></td>
                    </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ref[1] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ctr[1] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%" rowspan="2" >
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="100">
                    	<td align="center" width="280" ><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="<?php iif($ctr[0]!="","tab blink","") ?>">Counter</label></b></font></td>
                        <td align="center" rowspan="2" ><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo iif($ref[0]!="","NOW SERVING</br>",""); ?><label class="tab blink"><?php echo $ref[0] ?></label></b></font></td>
                    </tr>
                	<tr height="100">
                    	<td align="center" ><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="tab blink"><?php echo $ctr[0] ?></label></b></font></td>
                    </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ref[2] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ctr[2] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%">&nbsp;</td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ref[3] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ctr[3] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%" rowspan="20" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ref[4] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ctr[4] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%">&nbsp;</td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ref[5] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ctr[5] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%">&nbsp;</td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ref[6] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ctr[6] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%">&nbsp;</td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ref[7] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ctr[7] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%">&nbsp;</td>
            </tr>
            <tr>
              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b>Counter</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ref[8] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ctr[8] ?></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%">&nbsp;</td>
            </tr>
		</table>
	</td>
</tr>	
<tr class="fillerRow10px"><td></td></tr>
</table></td><td width="10">&nbsp;</td></tr></table>	
	
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php $page->writePopupMenuHTML(true);?>
</body>
</html>


<?php $page->writeEndScript(); ?>
<?php	
	$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess']  . $page->toolbar->generateQueryString() . "&toolbarscript=./GenericListToolbar.php";
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>

<script>
resizeObjects();
</script>
