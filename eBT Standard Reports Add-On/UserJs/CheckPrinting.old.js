// page events
page.events.add.reportgetparams('onPageReportGetParamsGPSStandardReports');


function onPageReportGetParamsGPSStandardReports(formattype,params) {
	var row = getTableSelectedRow("T1");
	if (params==null) params = new Array();
	params["source"] = "aspx";
	params["action"] = "processReport.aspx";
	params["querystring"] = generateQueryString("docno",getTableInput("T1","docno",row));
	
	switch (getTableInput("T1","bank",row)) {
		//case "BPI":
		//	params["reportfile"] = getVar("approotpath") +  "AddOns\\GPS\\Metro Preneur Add-On\\UserRpts\\checkvoucher.rpt"; 
		//	break;
		//case "BDO":
		//	params["reportfile"] = getVar("approotpath") +  "AddOns\\GPS\\Metro Preneur Add-On\\UserRpts\\checkvoucher.rpt"; 
		//	break;
		default:
			params["reportfile"] = getVar("approotpath") +  "AddOns\\GPS\\eBT Standard Reports Add-On\\UserRpts\\checkprinting\\BDO.rpt"; 
			break;
	}
	
	return params;
}