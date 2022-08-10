<?php
	$progid = "u_lguqueuedisplay2monitor2";

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
	
	$ctr2 = array("","","","","");
	$ref2 = array("","","","","");
	
	$ctr3 = array("","","","","");
	$ref3 = array("","","","","");
	
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
	$page->paging->formid = "./UDP.php?&objectcode=u_lguqueuedisplay2monitor2";
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
		$page->setitem("opt2",1);
	} else {
		$page->setitem("opt",$page->getitemnumeric("opt")+25);
		$page->setitem("opt2",$page->getitemnumeric("opt2")+1);
	}
	
			
	$objrs = new recordset(null,$objConnection);
	$objrs2 = new recordset(null,$objConnection);
	$objrs3 = new recordset(null,$objConnection);

	$objrs->queryopen("select count(*) from u_bplapps where u_year = 2017 and docstatus in ('Encoding','Assessing','Assessed','Approved','Disapproved')");
	if ($objrs->queryfetchrow()) {
		if ($page->getitemnumeric("opt")> $objrs->fields[0] ) $page->setitem("opt",1);
	}

	$objrs->queryopen("select count(*) from u_lguquegroups where u_monitor = 2");
	if ($objrs->queryfetchrow()) {
		if ($page->getitemnumeric("opt2")> $objrs->fields[0] ) $page->setitem("opt2",1);
	}

	$refresh = 3;
	$objrs->queryopen("select name, u_color, u_voicemessage, u_refresh from u_lguquegroups where u_groupcount='".$page->getitemnumeric("opt2")."' and u_monitor = 2");
	if ($objrs->queryfetchrow()) {
		$colors[1] =  $objrs->fields[1];
		$quename = $objrs->fields[0];
		$voicemessage = $objrs->fields[2];
		$refresh = $objrs->fields[3];
	}
        $objrs->queryopen("select  u_voicemessage from u_lguquegroups where u_groupcount = 1 and u_monitor = 2");
	if ($objrs->queryfetchrow()) {
		$voicemessage = $objrs->fields[0];
        }
        $objrs->queryopen("select  u_voicemessage from u_lguquegroups where u_groupcount = 2 and u_monitor = 2");
	if ($objrs->queryfetchrow()) {
		$voicemessage2 = $objrs->fields[0];
        }
        $objrs->queryopen("select  u_voicemessage from u_lguquegroups where u_groupcount = 3 and u_monitor = 2");
	if ($objrs->queryfetchrow()) {
		$voicemessage3 = $objrs->fields[0];
        }
	
	$speech = "";
	$idx=0;
	$objrs->queryopen("select U_CTR, U_REF from U_LGUQUE WHERE u_groupcount = 1 AND U_DATE='".currentdateDB()."'  and u_monitor = 2 ORDER BY DOCNO DESC LIMIT 1");
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
        
        $speech2 = "";
	$idx2=0;
	$objrs2->queryopen("select U_CTR, U_REF from U_LGUQUE WHERE u_groupcount = 2 AND U_DATE='".currentdateDB()."'  and u_monitor = 2 ORDER BY DOCNO DESC LIMIT 1");
	while ($objrs2->queryfetchrow("NAME")) {
		if ($idx2==0) {
			if ($page->getitemnumeric("queno".$page->getitemnumeric("opt2"))!=$objrs2->fields["U_REF"]) {
				if ($voicemessage2!="") {
					$speech2 = str_replace("{counter}",$objrs2->fields["U_CTR"],$voicemessage2);
					$speech2 = str_replace("{tag}",$objrs2->fields["U_REF"],$speech2);
				}	
				$page->setitem("queno".$page->getitemnumeric("opt2"),$objrs->fields["U_REF"]);
			}
		}
		$ctr2[$idx2] = $objrs2->fields["U_CTR"];
		$ref2[$idx2] = $objrs2->fields["U_REF"];
		$idx2++;
	}
        
        $speech3 = "";
	$idx3=0;
	$objrs3->queryopen("select U_CTR, U_REF from U_LGUQUE WHERE u_groupcount = 3 AND U_DATE='".currentdateDB()."'  and u_monitor = 2 ORDER BY DOCNO DESC LIMIT 1");
	while ($objrs3->queryfetchrow("NAME")) {
		if ($idx3==0) {
			if ($page->getitemnumeric("queno".$page->getitemnumeric("opt2"))!=$objrs3->fields["U_REF"]) {
				if ($voicemessage3!="") {
					$speech3 = str_replace("{counter}",$objrs3->fields["U_CTR"],$voicemessage3);
					$speech3 = str_replace("{tag}",$objrs3->fields["U_REF"],$speech3);
				}	
				$page->setitem("queno".$page->getitemnumeric("opt2"),$objrs3->fields["U_REF"]);
			}
		}
		$ctr3[$idx3] = $objrs3->fields["U_CTR"];
		$ref3[$idx3] = $objrs3->fields["U_REF"];
		$idx3++;
	}
//	var_dump($speech);
//	var_dump($speech2);
//	var_dump($speech3);
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
var msg = new SpeechSynthesisUtterance('Business Permit Counter 1 No. 10');
//msg.rate = 5.2;
msg.pitch = 2;
var voices = window.speechSynthesis.getVoices();
 alert(voices.length);
  for(var i = 0; i < voices.length; i++ ) {
        alert("Voice " + i.toString() + ' ' + voices[i].name + ' ' + voices[i].uri);
      }

//window.speechSynthesis.speak(msg);
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
<SCRIPT language=JavaScript src="js/cflcommon.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js"></SCRIPT>
<SCRIPT language=JavaScript src="cfls/customers.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>
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
                    <?php if ($speech2!="") { ?>
				
				var msg2 = new SpeechSynthesisUtterance('<?php echo $speech2; ?>');
				pauseVideo();
				window.speechSynthesis.speak(msg2);
				setTimeout("resumeVideo()",<?php echo $refresh * 1000; ?>);
		<?php } ?>
                    <?php if ($speech3!="") { ?>
				var msg3 = new SpeechSynthesisUtterance('<?php echo $speech3; ?>');
				pauseVideo();
				window.speechSynthesis.speak(msg3);
				setTimeout("resumeVideo()",<?php echo $refresh * 1000; ?>);
                    <?php } ?>
		} catch (theError) {
		}
	}
	
	function pauseVideo() {
		try {
			var vids = parent.ads.document.getElementsByTagName('video');
			if (vids.length>0) vids.item(0).pause();
		} catch (theError) {
		}
	}

	function resumeVideo() {
		try {
			var vids = parent.ads.document.getElementsByTagName('video');
			if (vids.length>0) vids.item(0).play();
		} catch (theError) {
		}
	}

	function onFormSubmit(action) {
		if (action=="checkprinted") {
			setKey("keys",getTableInput("T100","docno")+"`"+getTableInput("T100","checkno"));
			hidePopupFrame('popupFrameCheckPrintedStatus');
		}
		return true;
	}

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}

	function onReport(p_formattype) {
		if (getTableSelectedRow("T1")==0) {
			page.statusbar.showWarning("No selected Check.");
			return false;
		}
		if (isPopupFrameOpen('popupFrameCheckPrintedStatus')) {
			page.statusbar.showWarning("Check Printing Status window is still open.");
			return false;
		}
		return true;
	}
	
	function onReportGetParams(p_formattype,p_params) {
		var params = new Array();
		if (p_params!=null) params = p_params;
		//params = getReportLayout("<?php echo $page->progid?>",p_formattype,params);

		params["source"] = "aspx";
		params["dbtype"] = "mysql";
		//params["reportaction"] = getInput("reportaction");
		params["action"] = "processReport.aspx"
		if (params["querystring"]==undefined) {
			params["querystring"] = "";
			params["querystring"] += generateQueryString("docno",getTableInput("T1","docno",getTableSelectedRow("T1")));
		}	
		params["reportfile"] = getVar("approotpath") + "AddOns\\GPS\\BPLS Add-On\\UserRpts\\u_bplapp.rpt"; 
		params["recordselectionformula"]="{u_bplapps.docno} = '"+getTableInput("T1","docno",getTableSelectedRow("T1"))+"'";
		//page.statusbar.showWarning(params["source"]);
//		params["recordselectionformula"] = "{items.ITEMCODE}='A1000'";
		return params;
	}

	function onReportReturn(p_formattype) {
		var row = getTableSelectedRow("T1");
	//	page.statusbar.showWarning("previewed");
		setTableInput("T100","docno",getTableInput("T1","docno",row));
		setTableInput("T100","u_adminname",getTableInput("T1","u_adminname",row));
		setTableInput("T100","u_street",getTableInput("T1","u_street",row));
		setTableInput("T100","u_barangay",getTableInput("T1","u_barangay",row));
		setTableInput("T100","u_municipality",getTableInput("T1","u_municipality",row));
		showPopupFrame('popupFrameCheckPrintedStatus',true);
	}

	function onBeforeEditRow(p_tableId,p_rowIdx) {
		switch (p_tableId) {
			case "T1":
				//if (getInput("opt")=="Print") {
				//	setTimeout("OpenReportSelect('printer')",100);
				//} else {
					setKey("keys",getTableInput("T1","docno",p_rowIdx),p_rowIdx);
					switch (getTableInput("T1","u_kind",p_rowIdx)) {
						case "LAND":
							return formView(null,"./UDO.php?&objectcode=U_RPFAAS1");
							break;
						case "BUILDING":
							return formView(null,"./UDO.php?&objectcode=U_RPFAAS2");
							break;
						case "MACHINERY":
							return formView(null,"./UDO.php?&objectcode=U_RPFAAS3");
							break;
					}
				//}	
				/*if (getTableInput("T1","u_expired",p_rowIdx)=="1") {
					page.statusbar.showError("Patient already expired. Cannot add new registration.");
				} else if (getTableInput("T1","u_active",p_rowIdx)=="0") {
					page.statusbar.showError("Patient record is not active. Cannot add new registration.");
				} else {
					if (getInput("u_trxtype")=="IP") {
						return formView(null,"./UDO.php?&objectcode=U_HISIPS&patientidtoreg="+getTableKey("T1","keys",p_rowIdx));
					} else {
						return formView(null,"./UDO.php?&objectcode=U_HISOPS&patientidtoreg="+getTableKey("T1","keys",p_rowIdx));
					}	
				}	*/
				//var targetObjectId = 'u_hisips';
				//OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisips' + '' + '&targetId=' + targetObjectId ,targetObjectId);
				break;
		}		
		return false;
	}
	
	function onLnkBtnGetParams(elementId) {
		var params = new Array();
		switch (elementId) {
			case "u_hispatients":
				if (getTableSelectedRow("T1")>0) params["keys"] = getTableKey("T1","keys",getTableSelectedRow("T1"));
				break;
		}
		return params;
	}	
	function onElementChange(element,column,table,row) {
		switch (column) {
			case "docstatus": 
			case "u_kind": 
				formPageReset(); 
				clearTable("T1");
				break;	
		}
		return true;
	}	
	
	function onElementValidate(element,column,table,row) {
		switch (column) {
			case "page_vr_limit":
				formPageReset(); 
				clearTable("T1");
				break;
			case "u_varpno": 
			case "u_tdno": 
			case "u_pin": 
			case "u_ownername": 
			case "u_adminname": 
			case "u_street": 
			case "u_barangay": 
			case "u_municipality": 
			case "u_cadlotno": 
				formPageReset(); 
				clearTable("T1");
				break;	
		}
		return true;
	}	
	
	function onElementClick(element,column,table,row) {
		switch (table) {
			case "T1":
				switch (column) {
					case "print":
						setTimeout("OpenReportSelect('printer')",100);

						/*if (row==0) row = undefined;
						OpenPopupViewMap("viewmap","CUSTOMER",getTableInput(table,"street",row),getTableInput(table,"barangay",row),getTableInput(table,"city",row),getTableInput(table,"zip",row),getTableInput(table,"county",row),getTableInput(table,"province",row),getTableInput(table,"country",row),"");
						*/
						break;
					case "action":
						setInput("getprevarpno",getTableInput("T1","docno",row));
						setKey("keys","");
						switch (getTableInput("T1","u_kind",row)) {
							case "LAND":
								return formView(null,"./UDO.php?&objectcode=U_RPFAAS1");
								break;
							case "BUILDING":
								return formView(null,"./UDO.php?&objectcode=U_RPFAAS2");
								break;
							case "MACHINERY":
								return formView(null,"./UDO.php?&objectcode=U_RPFAAS3");
								break;
						}
						break;
				}	
				break;
		}
	}
		
	function onReadOnlyAccess(elementId) {
		switch (elementId) {
			case "df_page_vr_limit":
			case "df_docstatus":
			case "df_u_pin":
			case "df_u_taxclass":
			case "df_u_ownername":
			case "df_u_kind":
			case "df_u_adminname":
			case "df_u_street":
			case "df_u_barangay":
			case "df_u_municipality":
			case "df_u_cadlotno":
			case "df_u_varpno":
			case "df_u_tdno":
				return false;
		}
		return true;
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
			inputs.push("getprevarpno");
			inputs.push("u_varpno");
			inputs.push("u_tdno");
			inputs.push("u_pin");
			inputs.push("u_ownername");
			inputs.push("u_adminname");
			inputs.push("u_street");
			inputs.push("u_barangay");
			inputs.push("u_municipality");
			inputs.push("u_cadlotno");
			inputs.push("u_province");
			inputs.push("docstatus");
			inputs.push("u_kind");
			
		return inputs;
	}
	
	function formAddNew() {
		return formView(null,"<?php echo $page->formid; ?>");
	}
	
	function formSearchNow() {
		formSearch('','<?php echo $page->paging->formid; ?>');
	}

	function onElementKeyDown(element,event,column,table,row) {
		switch (column) {
			case "u_varpno":
			case "u_tdno":
			case "u_pin":
			case "u_ownername":
			case "u_adminname":
			case "u_street":
			case "u_barangay":
			case "u_municipality":
			case "u_cadlotno":
				var sc_press = keyModifiers(event) + keyCharCode(event.keyCode);
				if (sc_press=="ENTER") {
					formSearchNow();	
				} else if (sc_press=="UP" || sc_press=="DN") {
					var rc=getTableSelectedRow("T1");
					selectTableRow("T1",rc+1);
				}
				break;
		}
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
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr class="fillerRow10px"><td></td></tr>
<tr>
	<td>
		<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#000000">
            <tr>
              <td colspan="2" width="100%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="100">
                    	<td align="center" width="100%"><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $quename ?></b></font></td>
                    </tr>
                </table>
              </td>
            </tr>
            <tr>
            	<td width="50%" valign="top" >
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="100">
                    	<td align="center" width="280" ><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="<?php iif($ctr[0]!="","tab blink","") ?>">Renew</label></b></font></td>
                        <td align="center" rowspan="2" ><font size="10" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="tab blink" style="font-size:150px" ><?php echo $ref[0] ?></label></b></font></td>
                    </tr>
                	<tr height="100">
                    	<td align="center" ><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="tab blink"><?php echo $ctr[0] ?></label></b></font></td>
                    </tr>
                </table>
              </td>
                <td width="50%" rowspan="3" valign="top">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr>
                    	<td align="center" width="140"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Application No.</b></font></td>
                        <td align="center" ><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Businessname</b></font></td>
                        <td align="center" width="150"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Status</b></font></td>
                    </tr>
 	<?php 	
	$ctr=0;
	$objrs->queryopen("select u_businessname, docno, docstatus from u_bplapps where u_year = 2017 and docstatus in ('Encoding','Assessing','Assessed','Approved','Disapproved') order by u_businessname limit " . ($page->getitemnumeric("opt")-1) . ",25");
	while ($objrs->queryfetchrow("NAME")) {
		$ctr++;
	?>                   
                	<tr>
                    	<td align="center" width="140"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><?php echo $objrs->fields["docno"] ?></font></td>
                        <td align="center" ><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><?php echo $objrs->fields["u_businessname"] ?></font></td>
                        <td align="center" width="150"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><?php echo $objrs->fields["docstatus"] ?></font></td>
                    </tr>
    <?php } 
	while ($ctr<15) 
            { $ctr++;
            ?>
    	
                	<tr>
                    	<td align="center" width="140"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">&nbsp;</font></td>
                        <td align="center" ><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">&nbsp;</font></td>
                        <td align="center" width="150"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">&nbsp;</font></td>
                    </tr>
    
	<?php }	?>                
                </table>
              </td>
<!--              <td width="50%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="50">
                    	<td align="center" width="240"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b>Counter1</b></font></td>
                        <td align="center" rowspan="2"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ref[1] ?></b></font></td>
                    </tr>
                	<tr height="50">
                    	<td align="center"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><?php echo $ctr[1] ?></b></font></td>
                    </tr>
                </table>
              </td>-->
              
            </tr>
            <tr>
            <td width="50%" valign="top" >
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="100">
                    	<td align="center" width="280" ><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="<?php iif($ctr2[0]!="","tab blink","") ?>">New</label></b></font></td>
                        <td align="center" rowspan="2" ><font size="10" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="tab blink" style="font-size:150px" ><?php echo $ref2[0] ?></label></b></font></td>
                    </tr>
                	<tr height="100">
                    	<td align="center" ><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="tab blink"><?php echo $ctr2[0] ?></label></b></font></td>
                    </tr>
                </table>
              </td>
              <td width="50%">&nbsp;</td>
            </tr>
            <tr>
              <td width="50%" valign="top" >
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="100">
                    	<td align="center" width="280" ><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="<?php iif($ctr3[0]!="","tab blink","") ?>">For Payment</label></b></font></td>
                        <td align="center" rowspan="2" ><font size="10" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="tab blink" style="font-size:150px"><?php echo $ref3[0] ?></label></b></font></td>
                    </tr>
                	<tr height="100">
                    	<td align="center" ><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="<?php echo $colors[$coloridx] ?>"><b><label class="tab blink"><?php echo $ctr3[0] ?></label></b></font></td>
                    </tr>
                </table>
              </td>
              
            </tr>
            
            <tr>
         
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
