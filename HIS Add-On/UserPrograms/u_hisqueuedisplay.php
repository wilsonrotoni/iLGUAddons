<?php 
	$progid = "u_hisqueuedisplay";

	//header('Access-Control-Allow-Origin: *');
	//header('Access-Control-Allow-Methods: GET, POST, HEAD, OPTIONS'); 

	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./sls/enumdocstatus.php");
	include_once("./utils/companies.php");
	include_once("./inc/formutils.php");
	//include_once("./inc/formaccess.php");
	//../Videos/ads.mp4
	
	$objrs = new recordset(null,$objConnection);

	$filepath = "";
	$objrs->queryopen("select u_queingfilepath from u_hissetup");
	if ($objrs->queryfetchrow()) {
		$filepath = strtolower($objrs->fields[0]);
	}

	$pos1 = strpos($filepath, ".mp4");
	if (!($pos1 === false)) $filepath = $filepath . "?autoplay=1&hl=en_US&cc_lang_pref=en_US&cc_load_policy=1&height=100%&width=100%";
	
	//$filepath = "http://localhost:81/ads.php";
?>
<body topmargin="0" leftmargin="0" rightmargin="0" onResize="setPos()" style="background-color:#000000">

<iframe id="queue" style="border:none; width: 100%; height: 100%;   display: block; position: absolute;" scrolling="no" src="./udp.php?objectcode=u_hisqueuedisplay2&df_u_group=<?php echo $group ?>">
</iframe>
<iframe id="ads" name="ads" style="background-color:#000000; width: 100px; height: 608px; top: 316px;    left: 50px;    display: block;  position: absolute;" scrolling="no" srclang="en" src="<?php echo $filepath ?>">
</iframe>
</body>

<script>

function pauseVideo() {
	alert('1');
	try {
	alert('2');
		var vids = this.ads.document.getElementsByTagName('video');
	alert('3');
		alert(vids.length);
		if (vids.length>0) vids.item(0).pause();
	} catch (theError) {
		alert(theError.message);
	}
}


function setPos() {
	//alert("<?php echo $filepath ?>");
	var d= document, root= d.documentElement, body= d.body;
	 var wid= window.innerWidth || root.clientWidth || body.clientWidth, 
	 hi= window.innerHeight || root.clientHeight || body.clientHeight ;
	 //alert(wid + ":" + hi);
	 
	 document.getElementById('ads').style.left=((wid/2)-3)+"px";
	 document.getElementById('ads').style.width=((wid/2)-17)+"px";
}

setPos();

function playVideo() {
	//alert(document.getElementById('ads').src);
	//document.getElementById('ads').src = "http://localhost:81/ads.mp4?autoplay=1&hl=en_US&cc_lang_pref=en_US&cc_load_policy=1&height=100%&width=100%";
	//alert(document.getElementById('ads').src);

}
	 
setTimeout("playVideo()",10000);	 
</script>