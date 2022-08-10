<?php
	$progid = "u_hisqueuegenerator";

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
	
	$page->objectcode = "QUEUEINGGENERATOR";
	//$page->paging->formid = "./UDP.php?&objectcode=u_lguqueuegenerator";
	//$page->formid = "./UDO.php?&objectcode=U_BPLAPPS";
	$page->objectname = "Queueing Generator";
	
	function onFormAction($action) {
		global $objConnection;
		global $page;
		$actionReturn = true;
		
		return $actionReturn;
	}
		
	require("./inc/formactions.php");
	
	$objrs = new recordset(null,$objConnection);

	$page->toolbar->setaction("print",false);
	
	$rptcols = 6; 
	
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
<style>
A.buttonDialer { FONT-SIZE: 40px; FONT-FAMILY: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif}

A.buttonDialer:link {        BORDER-RIGHT: 1px solid; PADDING-RIGHT: 13px; BORDER-TOP: 1px solid; PADDING-LEFT: 13px; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; BORDER-LEFT: 1px solid; PADDING-TOP: 0px; BORDER-BOTTOM: 3px solid; FONT-FAMILY: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; TEXT-ALIGN: center; TEXT-DECORATION: none;
-moz-border-radius:5px; /* Firefox */
-webkit-border-radius: 5px; /* Safari, Chrome */
-khtml-border-radius: 5px; /* KHTML */
border-radius: 5px; /* CSS3 */
behavior:url("border-radius.htc");}
A.buttonDialer:visited {        BORDER-RIGHT: 1px solid; PADDING-RIGHT: 13px; BORDER-TOP: 1px solid; PADDING-LEFT: 13px; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; BORDER-LEFT: 1px solid; PADDING-TOP: 0px; BORDER-BOTTOM: 3px solid; FONT-FAMILY: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; TEXT-ALIGN: center; TEXT-DECORATION: none;
-moz-border-radius:5px; /* Firefox */
-webkit-border-radius: 5px; /* Safari, Chrome */
-khtml-border-radius: 5px; /* KHTML */
border-radius: 5px; /* CSS3 */
behavior:url("border-radius.htc");}
A.buttonDialer:active {        BORDER-RIGHT: 1px solid; PADDING-RIGHT: 13px; BORDER-TOP: 1px solid; PADDING-LEFT: 13px; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; BORDER-LEFT: 1px solid; PADDING-TOP: 0px; BORDER-BOTTOM: 3px solid; FONT-FAMILY: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; TEXT-ALIGN: center; TEXT-DECORATION: none;
-moz-border-radius:5px; /* Firefox */
-webkit-border-radius: 5px; /* Safari, Chrome */
-khtml-border-radius: 5px; /* KHTML */
border-radius: 5px; /* CSS3 */
behavior:url("border-radius.htc");}
A.buttonDialer:hover {        BORDER-RIGHT: 1px solid; PADDING-RIGHT: 13px; BORDER-TOP: 1px solid; PADDING-LEFT: 13px; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; BORDER-LEFT: 1px solid; PADDING-TOP: 0px; BORDER-BOTTOM: 3px solid; FONT-FAMILY: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; TEXT-ALIGN: center; TEXT-DECORATION: none;
-moz-border-radius:5px; /* Firefox */
-webkit-border-radius: 5px; /* Safari, Chrome */
-khtml-border-radius: 5px; /* KHTML */
border-radius: 5px; /* CSS3 */
behavior:url("border-radius.htc");}

A.buttonDialer:link { BORDER-LEFT-COLOR: #3E82C5; BORDER-BOTTOM-COLOR: #A8BBDF; COLOR: #F4FAFF; BORDER-TOP-COLOR: #3E82C5; BACKGROUND-COLOR: #02359A; BORDER-RIGHT-COLOR: #A8BBDF}
A.buttonDialer:visited {BORDER-LEFT-COLOR: #3E82C5; BORDER-BOTTOM-COLOR: #A8BBDF; COLOR: #F4FAFF; BORDER-TOP-COLOR: #3E82C5; BACKGROUND-COLOR: #02359A; BORDER-RIGHT-COLOR: #A8BBDF}
A.buttonDialer:active { BORDER-LEFT-COLOR: #3E82C5; BORDER-BOTTOM-COLOR: #A8BBDF; COLOR: #F4FAFF; BORDER-TOP-COLOR: #3E82C5; BACKGROUND-COLOR: #02359A; BORDER-RIGHT-COLOR: #A8BBDF}
A.buttonDialer:hover { BORDER-LEFT-COLOR: #3E82C5; BORDER-BOTTOM-COLOR: #A8BBDF; COLOR: #02359A; BORDER-TOP-COLOR: #3E82C5; BACKGROUND-COLOR: #F4FAFF; BORDER-RIGHT-COLOR: #A8BBDF}


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
	
	function pressDial(btn) {
		switch (btn) {
			case "<":
				if (getInput("mobileno").length>0) {
					setInput("mobileno",getInput("mobileno").substring(0,getInput("mobileno").length-1));
					if (getInput("mobileno").length==9) setInput("mobileno",getInput("mobileno").substring(0,getInput("mobileno").length-1));
					if (getInput("mobileno").length==5) setInput("mobileno",getInput("mobileno").substring(0,getInput("mobileno").length-1));
					beep();	
				}	
				break;
			case "C":
				if (getInput("mobileno").length>0) {
					setInput("mobileno","");
					beep();	
				}	
				break;
			default:
				if (getInput("mobileno").length==0 && btn!="0") setInput("mobileno","09");
				if (getInput("mobileno").length==4) setInput("mobileno",getInput("mobileno")+"-");
				if (getInput("mobileno").length==8) setInput("mobileno",getInput("mobileno")+"-");
				if (getInput("mobileno").length<13) {
					setInput("mobileno",getInput("mobileno")+btn);
					beep();	
				}
				break;	
		}
		//focusInput("mobileno");
	}
	
	function rePrint() {
		beep();	
		try {
			http = getHTTPObject(); 
			http.open("GET", "udp.php?objectcode=u_ajaxqueingRePrint&group="+getInputCaption("queuegroup")+"&no="+getInputCaption("queueno")+"&mobileno="+getInputCaption("mobileno"), false);
			http.send(null);
			/*var result = http.responseText.trim();
			if (parseInt(result)>0) {
				setCaption("mobileno",getInput("mobileno"));
				setCaption("queueno",result);
				setInput("mobileno","");
			}*/	
		} catch (theError) {
		}	
	}
	
	function pressButton(btn) {
		if (getInput("mobileno")!="") {
			if (getInput("mobileno").length!=13) {
				alert("Mobile No. must be 11 numbers.");
				return;
			}	
		}
		beep();	
		switch (btn) {
			case "1": setCaption("queuegroup","Cashier"); break;
			case "2": setCaption("queuegroup","Billing"); break;
		}
		setCaption("queueno","");
		try {
			http = getHTTPObject(); 
			http.open("GET", "udp.php?objectcode=u_ajaxqueingGetNextNo&group="+btn+"&mobileno="+getInput("mobileno"), false);
			http.send(null);
			var result = http.responseText.trim();
			if (parseInt(result)>0) {
				setCaption("mobileno",getInput("mobileno"));
				setCaption("queueno",result);
				setInput("mobileno","");
			}	
		} catch (theError) {
		}	
	}
	
	function beep() {
    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");  
    snd.play();
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
              <td width="100%" colspan="2">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="100">
                    	<td align="center" width="100%"><font size="7" face="Verdana, Arial, Helvetica, sans-serif" color="white"><b><?php echo "Queueing System" ?></b></font></td>
                    </tr>
                </table>              
              </td>
            </tr>
            <tr>
              <td width="60%" valign="top">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="88">
                    	<td align="center" colspan="4"><a href="" onClick="pressButton('1');return false;" class="buttonDialer">Cashier</a></td>
                    </tr>
                	<tr height="88">
                    	<td align="center" colspan="4"><a href="" onClick="pressButton('2');return false;" class="buttonDialer">Billing</a></td>
                    </tr>
                	<tr height="88">
                    	<td align="center" colspan="4">&nbsp;</td>
                    </tr>
                	<tr height="88">
                    	<td align="center" colspan="4">&nbsp;</td>
                    </tr>
                	<tr height="52">
                    	<td align="center" ><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="white"><label id="cf_queuegroup"></label></font></td>
                    	<td align="center" width="200"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="white"><label id="cf_mobileno"></label></font></td>
                    	<td align="center" width="60"><font size="5" face="Verdana, Arial, Helvetica, sans-serif" color="white"><label id="cf_queueno"></label></font></td>
                    	<td align="center" width="50"><a href="" onClick="rePrint();return false;"><img id="reprint" height=40 src="../Addons/GPS/HIS Add-On/UserPrograms/Images/printer_small.jpg" width=40 align=absbottom border=0></a></td>
                    </tr>
                </table>              
              </td>
              <td width="40%">
              	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="1" style="background-color:#000000">
                	<tr height="100">
                    	<td align="center" colspan="3"><input type="text" readonly size=10  style="font-size:56px;height:69px;text-align:center;" <?php genInputTextHtml(array("name"=>"mobileno"),"") ?>  placeholder="Mobile: 09" /></td>
                    </tr>
                	<tr height="76">
                    	<td align="center"><a href="" onClick="pressDial('1');return false;" class="buttonDialer">&nbsp;&nbsp;1&nbsp;&nbsp;</a></td>
                    	<td align="center"><a href="" onClick="pressDial('2');return false;" class="buttonDialer">&nbsp;&nbsp;2&nbsp;&nbsp;</a></td>
                    	<td align="center"><a href="" onClick="pressDial('3');return false;" class="buttonDialer">&nbsp;&nbsp;3&nbsp;&nbsp;</a></td>
                    </tr>
                	<tr height="76">
                    	<td align="center"><a href="" onClick="pressDial('4');return false;" class="buttonDialer">&nbsp;&nbsp;4&nbsp;&nbsp;</a></td>
                    	<td align="center"><a href="" onClick="pressDial('5');return false;" class="buttonDialer">&nbsp;&nbsp;5&nbsp;&nbsp;</a></td>
                    	<td align="center"><a href="" onClick="pressDial('6');return false;" class="buttonDialer">&nbsp;&nbsp;6&nbsp;&nbsp;</a></td>
                    </tr>
                 	<tr height="76">
                    	<td align="center"><a href="" onClick="pressDial('7');return false;" class="buttonDialer">&nbsp;&nbsp;7&nbsp;&nbsp;</a></td>
                    	<td align="center"><a href="" onClick="pressDial('8');return false;" class="buttonDialer">&nbsp;&nbsp;8&nbsp;&nbsp;</a></td>
                    	<td align="center"><a href="" onClick="pressDial('9');return false;" class="buttonDialer">&nbsp;&nbsp;9&nbsp;&nbsp;</a></td>
                    </tr>
                 	<tr height="76">
                    	<td align="center"><a href="" onClick="pressDial('0');return false;" class="buttonDialer">&nbsp;&nbsp;0&nbsp;&nbsp;</a></td>
                    	<td align="center"><a href="" onClick="pressDial('<');return false;" class="buttonDialer">&nbsp;&nbsp;<&nbsp;&nbsp;</a></td>
                    	<td align="center"><a href="" onClick="pressDial('C');return false;" class="buttonDialer">&nbsp;&nbsp;C&nbsp;&nbsp;</a></td>
                    </tr>
               </table>              
              </td>
              </tr>
		</table>
	</td>
</tr>	
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
