// JavaScript Document

page.events.add.load('onPageLoadGPSHIS');
//page.elements.events.add.validate('onElementValidateGPSHIS');
//page.tables.events.add.reset('onTableResetRowGPSHIS');

function onPageLoadGPSHIS() {
	if (getGlobal("roleid")=="FIN-PURCH") {	
		setInputSelectedText("docseries","RR",true);	
	}
}

function onElementValidateGPSHIS(element,column,table,row) {
	return true;
}

function onTableResetRowGPSHIS(table) {
}
