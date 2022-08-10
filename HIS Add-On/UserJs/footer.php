<script>

  	var winPreDisChargeAlert = null;
	var winPreIncomingPatients = null;
	var winIncomingRequests = null;
	var winIncomingStockRequests = null;

	function closeAlertWindowGPSHIS() {
		try {
			if (!winPreDisChargeAlert.closed) {
				winPreDisChargeAlert.close();
				winPreDisChargeAlert=null;				
			}	
			if (!winPreIncomingPatients.closed) {
				winPreIncomingPatients.close();
				winPreIncomingPatients=null;				
			}	
			if (!winIncomingRequests.closed) {
				winIncomingRequests.close();
				winIncomingRequests=null;				
			}	
			if (!winIncomingStockRequests.closed) {
				winIncomingStockRequests.close();
				winIncomingStockRequests=null;				
			}	
		} catch	(theError) {
			winPreDisChargeAlert=null;				
			winPreIncomingPatients=null;				
			winIncomingRequests=null;
			winIncomingStockRequests=null;				
		}
	}
	
	function OpenWindowPreDisChargeAlert(win_width,win_height,url) {
		
		win_left = (screen.width  - win_width);
		win_top = 0; //(screen.height / 2) - (win_height / 2);
		try { 
			if (winPreDisChargeAlert!=null) {
				if (winPreDisChargeAlert.closed) winPreDisChargeAlert = null;
			}
		} catch (theError) {
		}
		
		try { 
			if (winPreDisChargeAlert==null) winPreDisChargeAlert = window.open(url,'u_predischargealertwindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,alwaysRaised,modal=yes,titlebar=no'+",width="+win_width+",height="+win_height+",screenX="+win_left+",left="+win_left+",screenY="+win_top+",top="+win_top+"");
			else winPreDisChargeAlert.formSearchNow();
			winPreDisChargeAlert.focus();
		} catch (theError) {
		}
			
	}

	function OpenWindowIncomingPatients(win_width,win_height,url) {
		
		win_left = (screen.width  - win_width);
		win_top = 0; //(screen.height / 2) - (win_height / 2);
		try { 
			if (winPreIncomingPatients!=null) {
				if (winPreIncomingPatients.closed) winPreIncomingPatients = null;
			}
		} catch (theError) {
		}
		
		try { 
			if (winPreIncomingPatients==null) winPreIncomingPatients = window.open(url,'u_incomingpatientswindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,alwaysRaised,modal=yes,titlebar=no'+",width="+win_width+",height="+win_height+",screenX="+win_left+",left="+win_left+",screenY="+win_top+",top="+win_top+"");
			else winPreIncomingPatients.formSearchNow();
			winPreIncomingPatients.focus();
		} catch (theError) {
		}
			
	}

	function OpenWindowIncomingRequests(win_width,win_height,url) {
		
		win_left = (screen.width  - win_width);
		win_top = 0; //(screen.height / 2) - (win_height / 2);
		try { 
			if (winIncomingRequests!=null) {
				if (winIncomingRequests.closed) winIncomingRequests = null;
			}
		} catch (theError) {
		}
		
		try { 
			if (winIncomingRequests==null) winIncomingRequests = window.open(url,'u_incomingrequestswindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,alwaysRaised,modal=yes,titlebar=no'+",width="+win_width+",height="+win_height+",screenX="+win_left+",left="+win_left+",screenY="+win_top+",top="+win_top+"");
			else winIncomingRequests.formSearchNow();
			winIncomingRequests.focus();
		} catch (theError) {
		}
			
	}

	function OpenWindowIncomingStockRequests(win_width,win_height,url) {
		
		win_left = (screen.width  - win_width);
		win_top = 0; //(screen.height / 2) - (win_height / 2);
		try { 
			if (winIncomingStockRequests!=null) {
				if (winIncomingStockRequests.closed) winIncomingStockRequests = null;
			}
		} catch (theError) {
		}
		
		try { 
			if (winIncomingStockRequests==null) winIncomingStockRequests = window.open(url,'u_incomingstockrequestswindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,alwaysRaised,modal=yes,titlebar=no'+",width="+win_width+",height="+win_height+",screenX="+win_left+",left="+win_left+",screenY="+win_top+",top="+win_top+"");
			else winIncomingStockRequests.formSearchNow();
			winIncomingStockRequests.focus();
		} catch (theError) {
		}
			
	}

	function ajaxchecku_incomingstockrequestsGPSHIS() {
		var msgcnt = 0;	
		try {
			http = getHTTPObject(); 
			http.open("GET", "udp.php?objectcode=u_ajaxchecku_incomingstockrequests", true);
			http.onreadystatechange = function () {
				if (http.readyState == 4) {
	//				document.getElementById("maiboxTxt").innerHTML = http.responseText;
					msgcnt = parseInt(http.responseText);
					if (msgcnt == 0) {
						//document.getElementById("maiboxTxt").innerHTML = '';
						//document.images['mailboxImg'].src = './imgs/mailc.gif';
					} else if (msgcnt == 1) {
						//document.getElementById("maiboxTxt").innerHTML = '&nbsp;1 new message.';
						//document.images['mailboxImg'].src = './imgs/mailani2.gif';
						openu_incomingstockrequestsGPSHIS();					
					} else if (msgcnt > 1) {
						//document.getElementById("maiboxTxt").innerHTML = '&nbsp;' + msgcnt + ' new messages.';
						//document.images['mailboxImg'].src = './imgs/mailani2.gif';
						openu_incomingstockrequestsGPSHIS();				
					}
					
					checku_incomingstockrequestsGPSHIS();
				} 
			}
			http.send(null);
		} catch (theError) {
		}	
	}

	function ajaxchecku_incomingrequestsGPSHIS() {
		var msgcnt = 0;	
		try {
			//alert('querying');
			http = getHTTPObject(); 
			http.open("GET", "udp.php?objectcode=u_ajaxchecku_incomingrequests", true);
			http.onreadystatechange = function () {
				if (http.readyState == 4) {
					//alert(http.responseText);
	//				document.getElementById("maiboxTxt").innerHTML = http.responseText;
					msgcnt = parseInt(http.responseText);
					if (msgcnt == 0) {
						//document.getElementById("maiboxTxt").innerHTML = '';
						//document.images['mailboxImg'].src = './imgs/mailc.gif';
					} else if (msgcnt == 1) {
						//document.getElementById("maiboxTxt").innerHTML = '&nbsp;1 new message.';
						//document.images['mailboxImg'].src = './imgs/mailani2.gif';
						openu_incomingrequestsGPSHIS();					
					} else if (msgcnt > 1) {
						//document.getElementById("maiboxTxt").innerHTML = '&nbsp;' + msgcnt + ' new messages.';
						//document.images['mailboxImg'].src = './imgs/mailani2.gif';
						openu_incomingrequestsGPSHIS();				
					}
					
					checku_incomingrequestsGPSHIS();
				} 
			}
			http.send(null);
		} catch (theError) {
		}	
	}

	function ajaxchecku_incomingpatientsGPSHIS() {
		var msgcnt = 0;	
		try {
			http = getHTTPObject(); 
			http.open("GET", "udp.php?objectcode=u_ajaxchecku_incomingpatients", true);
			http.onreadystatechange = function () {
				if (http.readyState == 4) {
					//alert(http.responseText);
	//				document.getElementById("maiboxTxt").innerHTML = http.responseText;
					msgcnt = parseInt(http.responseText);
					if (msgcnt == 0) {
						//document.getElementById("maiboxTxt").innerHTML = '';
						//document.images['mailboxImg'].src = './imgs/mailc.gif';
					} else if (msgcnt == 1) {
						//document.getElementById("maiboxTxt").innerHTML = '&nbsp;1 new message.';
						//document.images['mailboxImg'].src = './imgs/mailani2.gif';
						openu_incomingpatientsGPSHIS();					
					} else if (msgcnt > 1) {
						//document.getElementById("maiboxTxt").innerHTML = '&nbsp;' + msgcnt + ' new messages.';
						//document.images['mailboxImg'].src = './imgs/mailani2.gif';
						openu_incomingpatientsGPSHIS();				
					}
					
					checku_incomingpatientsGPSHIS();
				} 
			}
			http.send(null);
		} catch (theError) {
		}	
	}

	function ajaxchecku_predischargealertGPSHIS() {
		var msgcnt = 0;	
		try {
			http = getHTTPObject(); 
			http.open("GET", "udp.php?objectcode=u_ajaxchecku_predischarge", true);
			http.onreadystatechange = function () {
				if (http.readyState == 4) {
					//alert(http.responseText);
	//				document.getElementById("maiboxTxt").innerHTML = http.responseText;
					msgcnt = parseInt(http.responseText);
					if (msgcnt == 0) {
						//document.getElementById("maiboxTxt").innerHTML = '';
						//document.images['mailboxImg'].src = './imgs/mailc.gif';
					} else if (msgcnt == 1) {
						//document.getElementById("maiboxTxt").innerHTML = '&nbsp;1 new message.';
						//document.images['mailboxImg'].src = './imgs/mailani2.gif';
						openu_predischargealertGPSHIS();					
					} else if (msgcnt > 1) {
						//document.getElementById("maiboxTxt").innerHTML = '&nbsp;' + msgcnt + ' new messages.';
						//document.images['mailboxImg'].src = './imgs/mailani2.gif';
						openu_predischargealertGPSHIS();				
					}
					
					checku_predischargealertGPSHIS();
				} 
			}
			http.send(null);
		} catch (theError) {
		}	
	}

  	function checku_incomingrequestsGPSHIS() {
		setTimeout("ajaxchecku_incomingrequestsGPSHIS()",<?php echo ($checkinboxmins * 50000) ?>);
  	}	

	function openu_incomingrequestsGPSHIS() {	  
		OpenWindowIncomingRequests(1024,500,'./udp.php?objectcode=u_histrxlist&df_u_trxtype=<?php echo $u_incomingrequesttrxtype ?>');
	}	

  	function checku_incomingpatientsGPSHIS() {
		setTimeout("ajaxchecku_incomingpatientsGPSHIS()",<?php echo ($checkinboxmins * 80000) ?>);
  	}	

	function openu_incomingpatientsGPSHIS() {	  
		OpenWindowIncomingPatients(1024,260,'./udp.php?objectcode=u_hisipinlist&df_u_trxtype=<?php echo $u_incomingrequesttrxtype ?>');
	}	

	function checku_predischargealertGPSHIS() {
		setTimeout("ajaxchecku_predischargealertGPSHIS()",<?php echo ($checkinboxmins * 60000) ?>);
	}
	
	function openu_predischargealertGPSHIS() {
		OpenWindowPreDisChargeAlert(800,400,'./udp.php?objectcode=u_hispredischargelist&df_u_trxtype=<?php echo $u_incomingrequesttrxtype ?>');
	}

  	function checku_incomingstockrequestsGPSHIS() {
		setTimeout("ajaxchecku_incomingstockrequestsGPSHIS()",<?php echo ($checkinboxmins * 70000) ?>);
  	}	

	function openu_incomingstockrequestsGPSHIS() {	  
		OpenWindowIncomingStockRequests(1024,500,'./udp.php?objectcode=u_hisstocktrxlist&df_u_trxtype=<?php echo $u_incomingrequesttrxtype ?>');
	}	
	
<?php if ($u_openincomingrequests==1 && $u_countincomingrequests>0) { ?>
	try {
		openu_incomingrequestsGPSHIS(); 
	} catch	(theError) {
	}
<?php } ?>

<?php if ($u_openincomingrequests==1) { ?>
	try {
		checku_incomingrequestsGPSHIS(); 
	} catch	(theError) {
	}
<?php } ?>

<?php if ($u_openincomingpatients==1 && $u_countincomingpatients>0) { ?>
	try {
		openu_incomingpatientsGPSHIS(); 
	} catch	(theError) {
	}
<?php } ?>

<?php if ($u_openincomingpatients==1) { ?>
	try {
		checku_incomingpatientsGPSHIS(); 
	} catch	(theError) {
	}
<?php } ?>

<?php if ($u_openpredischargealert==1 && $u_countpredischargealert>0) { ?>
	try {
		openu_predischargealertGPSHIS(); 
	} catch	(theError) {
	}
<?php } ?>

<?php if ($u_openpredischargealert==1) { ?>
	try {
		checku_predischargealertGPSHIS(); 
	} catch	(theError) {
	}
<?php } ?>

<?php if ($u_openincomingstockrequests==1 && $u_countincomingstockrequests>0) { ?>
	try {
		openu_incomingstockrequestsGPSHIS(); 
	} catch	(theError) {
	}
<?php } ?>

<?php if ($u_openincomingstockrequests==1) { ?>
	try {
		checku_incomingstockrequestsGPSHIS(); 
	} catch	(theError) {
	}
<?php } ?>

</script>