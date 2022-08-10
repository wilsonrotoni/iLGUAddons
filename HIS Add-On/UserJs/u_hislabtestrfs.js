// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
//page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.cfl('onCFLGPSHIS');
//page.events.add.cflgetparams('onCFLGetParamsGPSHIS');

// taskbar events
//page.taskbar.events.add.load('onTaskBarLoadGPSHIS');

// element events
//page.elements.events.add.focus('onElementFocusGPSHIS');
//page.elements.events.add.keydown('onElementKeyDownGPSHIS');
//page.elements.events.add.validate('onElementValidateGPSHIS');
//page.elements.events.add.validateparams('onElementGetValidateParamsGPSHIS');
//page.elements.events.add.changing('onElementChangingGPSHIS');
//page.elements.events.add.change('onElementChangeGPSHIS');
//page.elements.events.add.click('onElementClickGPSHIS');
//page.elements.events.add.cfl('onElementCFLGPSHIS');
//page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSHIS');

// table events
//page.tables.events.add.reset('onTableResetRowGPSHIS');
//page.tables.events.add.beforeInsert('onTableBeforeInsertRowGPSHIS');
//page.tables.events.add.afterInsert('onTableAfterInsertRowGPSHIS');
//page.tables.events.add.beforeUpdate('onTableBeforeUpdateRowGPSHIS');
//page.tables.events.add.afterUpdate('onTableAfterUpdateRowGPSHIS');
//page.tables.events.add.beforeDelete('onTableBeforeDeleteRowGPSHIS');
//page.tables.events.add.delete('onTableDeleteRowGPSHIS');
//page.tables.events.add.beforeSelect('onTableBeforeSelectRowGPSHIS');
//page.tables.events.add.select('onTableSelectRowGPSHIS');

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a") {
		try {
			if (window.opener.getVar("objectcode")=="U_HISTRXLIST") {
				var row = window.opener.getTableSelectedRow("T1");	
				setInput("u_examno",window.opener.getTableInput("T1","docno",row),true);
				setInput("u_reftype",window.opener.getTableInput("T1","u_reftype",row),true);
				setInput("u_refno",window.opener.getTableInput("T1","u_refno",row),true);
				setInput("u_patientid",window.opener.getTableInput("T1","u_patientid",row),true);
				setInput("u_patientname",window.opener.getTableInput("T1","u_patientname",row),true);
				setInput("u_itemcode",window.opener.getTableInput("T1","u_itemcode",row),true);
				setInput("u_itemdesc",window.opener.getTableInput("T1","u_itemdesc",row),true);
				setInput("u_amount",window.opener.getTableInput("T1","u_linetotal",row),true);
				
				var data = new Array();
				if (getInput("u_reftype")=="IP") {
					result = page.executeFormattedQuery("select c.code, c.name from u_hisips a inner join u_hisipdoctors b on b.company=a.company and b.branch=a.branch and b.docid=a.docid inner join u_hisdoctors c on c.code=b.u_doctorid where a.docno='"+getInput("u_refno")+"' group by c.code");	
				} else {
					result = page.executeFormattedQuery("select c.code, c.name from u_hisops a inner join u_hisdoctors c on c.code=a.u_doctorid where a.docno='"+getInput("u_refno")+"'");	
				}
				if (result.getAttribute("result")!= "-1") {
					if (parseInt(result.getAttribute("result"))>0) {
						for (iii = 0; iii < result.childNodes.length; iii++) {	
							data["u_doctorid"] = result.childNodes.item(iii).getAttribute("code");
							data["u_doctorname"] = result.childNodes.item(iii).getAttribute("name");
							insertTableRowFromArray("T1",data);
						}
					}
				} else {
					page.statusbar.showError("Error retrieving Doctors records. Try Again, if problem persists, check the connection.");	
					return false;
				}						
			
			}
		} catch(theError) {
		}
	}
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	return true;
}

function onCFLGPSHIS(Id) {
	return true;
}

function onCFLGetParamsGPSHIS(Id,params) {
	return params;
}

function onTaskBarLoadGPSHIS() {
}

function onElementFocusGPSHIS(element,column,table,row) {
	return true;
}

function onElementKeyDownGPSHIS(element,event,column,table,row) {
}

function onElementValidateGPSHIS(element,column,table,row) {
	return true;
}

function onElementGetValidateParamsGPSHIS(table,row,column) {
	var params = "";
	return params;
}

function onElementChangingGPSHIS(element,column,table,row) {
	return true;
}

function onElementChangeGPSHIS(element,column,table,row) {
	return true;
}

function onElementClickGPSHIS(element,column,table,row) {
	return true;
}

function onElementCFLGPSHIS(element) {
	return true;
}

function onElementCFLGetParamsGPSHIS(element,params) {
	return params;
}

function onTableResetRowGPSHIS(table) {
}

function onTableBeforeInsertRowGPSHIS(table) {
	return true;
}

function onTableAfterInsertRowGPSHIS(table,row) {
}

function onTableBeforeUpdateRowGPSHIS(table,row) {
	return true;
}

function onTableAfterUpdateRowGPSHIS(table,row) {
}

function onTableBeforeDeleteRowGPSHIS(table,row) {
	return true;
}

function onTableDeleteRowGPSHIS(table,row) {
}

function onTableBeforeSelectRowGPSHIS(table,row) {
	return true;
}

function onTableSelectRowGPSHIS(table,row) {
}

