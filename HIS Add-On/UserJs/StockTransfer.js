// JavaScript Document

page.events.add.load('onPageLoadGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
page.tables.events.add.reset('onTableResetRowGPSHIS');

var whscodeGPSHIS = "";

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a") {
		if (getGlobal("roleid")!="") {
			setInput("fromwhscode",getGlobal("roleid"),true);
		}
	}
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch(column) {
				case "itemcode":
				case "itemdesc":
					if (getTableInput(table,"whscode")=="" && whscodeGPSHIS!="") {
						setTableInput(table,"whscode",whscodeGPSHIS);
						onElementValidate(isElement("df_whscodeT1"),"whscode","T1",0);
					}
					if (getTableInput(table,"whscode")!="")	focusTableInput(table,"quantity");
					else focusTableInput(table,"whscode");
					break;
				case "whscode":
					whscodeGPSHIS = element.value;
					break;
			}
			break;
	}
	return true;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			//if (getGlobal("roleid")!="") {
			//	disableTableInput("T1","whscode");
			//}
			break;
	}
}
