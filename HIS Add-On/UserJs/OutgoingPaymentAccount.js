// JavaScript Document

page.events.add.load('onPageLoadGPSHIS');
//page.elements.events.add.validate('onElementValidateGPSHIS');
//page.tables.events.add.reset('onTableResetRowGPSHIS');

function onPageLoadGPSHIS() {
	//if (getGlobal("roleid")=="FIN-CASHIER") {	
		setInput("u_cashierid",getGlobal("userid"));	
	//}
}

function onElementValidateGPSHIS(element,column,table,row) {
	return true;
}

function onTableResetRowGPSHIS(table) {
}
