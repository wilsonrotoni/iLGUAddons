<?php 
	$progid = "u_lguqueuedisplaymonitor2";

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./classes/documentschema_br.php");
	include_once("./sls/enumdocstatus.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	include_once("./inc/formaccess.php");
?>
<body topmargin="0" leftmargin="0" rightmargin="0" onResize="setPos()" style="background-color:#000000">

<iframe id="queue" style="border:none; width: 100%; height: 100%;   display: block; position: absolute;" scrolling="no" src="./udp.php?objectcode=u_lguqueuedisplay2monitor2">
</iframe>
<!--<iframe id="ads" name="ads" style="background-color:#000000; width: 100px; height: 570px; top: 316px;    left: 50px;    display: block;  position: absolute;" scrolling="no" srclang="en" src="../Addons/GPS/LGU Add-On/UserPrograms/imgs/ads.png?autoplay=1&hl=en_US&cc_lang_pref=en_US&cc_load_policy=1&height=100%&width=100%">
</iframe>-->
</body>

<script>

function setPos() {
	var d= document, root= d.documentElement, body= d.body;
	 var wid= window.innerWidth || root.clientWidth || body.clientWidth, 
	 hi= window.innerHeight || root.clientHeight || body.clientHeight ;
	 //alert(wid + ":" + hi);
	 
	 document.getElementById('ads').style.left=((wid/2)-3)+"px";
	 document.getElementById('ads').style.width=((wid/2)-17)+"px";
}

setPos();
	 
</script>