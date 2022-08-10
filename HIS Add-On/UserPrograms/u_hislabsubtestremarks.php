<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
// Cross-Browser Rich Text Editor
// Written by Kevin Roth (http://www.kevinroth.com/rte/)
// License: http://creativecommons.org/licenses/by/2.5/
//-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Cross-Browser Rich Text Editor (RTE)</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="keywords" content="cross-browser rich text editor, rte, textarea, htmlarea, content management, cms, blog, internet explorer, firefox, safari, opera, netscape, konqueror" />
	<meta name="description" content="The cross-browser rich-text editor (RTE) is based on the designMode() functionality introduced in Internet Explorer 5, and implemented in Mozilla 1.3+ using the Mozilla Rich Text Editing API." />
	<meta name="author" content="Kevin Roth" />
	<meta name="ROBOTS" content="ALL" />
	<!-- html2xhtml.js written by Jacob Lee <letsgolee@lycos.co.kr> //-->
	<script language="JavaScript" type="text/javascript" src="./cbrte/html2xhtml.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="./cbrte/richtext.js"></script>
</head>
<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
<!-- START Demo Code -->
<form name="RTEDemo">
<script language="JavaScript" type="text/javascript">
<!--
function submitForm() {
	//make sure hidden and iframe values are in sync for all rtes before submitting form
	updateRTEs();
	
	//change the following line to true to submit form
	//alert("rte1 = " + (document.RTEDemo.rte1.value));
	return document.RTEDemo.rte1.value;
}

//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML, encHTML)
initRTE("./cbrte/images/", "./cbrte/", "", true);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>

<script language="JavaScript" type="text/javascript">
var rte1 = new richTextEditor('rte1');

function buildrte(width,height,readonly) {
	rte1.html = parent.getInput("u_remarks");
	if (readonly==1) {
		height = height + 65;
		rte1.width = width;
		rte1.height = height;
		rte1.readOnly = true;
		rte1.build();
		return;
	}
	rte1.width = width;
	rte1.height = height;
	//else rte1.readOnly = false;
	rte1.cmdFormatBlock = true;
	rte1.cmdFontName = true;
	rte1.cmdFontSize = true;
	rte1.cmdIncreaseFontSize = true;
	rte1.cmdDecreaseFontSize = true;
	
	rte1.cmdBold = true;
	rte1.cmdItalic = true;
	rte1.cmdUnderline = true;
	rte1.cmdStrikethrough = true;
	rte1.cmdSuperscript = true;
	rte1.cmdSubscript = true;
	
	rte1.cmdJustifyLeft = true;
	rte1.cmdJustifyCenter = true;
	rte1.cmdJustifyRight = true;
	rte1.cmdJustifyFull = true;
	
	rte1.cmdInsertHorizontalRule = true;
	rte1.cmdInsertOrderedList = true;
	rte1.cmdInsertUnorderedList = true;
	
	rte1.cmdOutdent = true;
	rte1.cmdIndent = true;
	rte1.cmdForeColor = true;
	rte1.cmdHiliteColor = true;
	rte1.cmdInsertLink = false;
	rte1.cmdInsertImage = false;
	rte1.cmdInsertSpecialChars = true;
	rte1.cmdInsertTable = true;
	rte1.cmdSpellcheck = true;
	
	rte1.cmdCut = true;
	rte1.cmdCopy = true;
	rte1.cmdPaste = true;
	rte1.cmdUndo = true;
	rte1.cmdRedo = true;
	rte1.cmdRemoveFormat = true;
	rte1.cmdUnlink = false;
	
	rte1.toggleSrc = false;
	
	rte1.build();
}

buildrte(<?php echo $width ?>,<?php echo $height ?>,<?php echo $readonly ?>);
</script>
</form>
</body>
</html>
