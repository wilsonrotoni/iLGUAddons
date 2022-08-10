// page events
page.events.add.load('onPageLoadGPSeBTStandardReports');

function onPageLoadGPSeBTStandardReports() {
	disableInput('custname');	
	document.getElementById("df_custname").style.width = "400px";
}
