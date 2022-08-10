// JavaScript Document

page.events.add.load('onPageLoadGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a") {
		disableInput("pricelist");
		setInput("pricelist","{BS}");
		if (getGlobal("roleid")!="") {
			setTableInput("T1","whscode",getGlobal("roleid"));
			setTableInputDefault("T1","whscode",getGlobal("roleid"));
		}
	}
}

function onElementValidateGPSHIS(element,column,table,row) {
	switch (table) {
		case "T1":
			switch(column) {
				case "itemcode":
				case "itemdesc":
					if (getTableInput(table,"whscode")=="" && getGlobal("roleid")!="") {
						setTableInput("T1","whscode",getGlobal("roleid"));
					}
					if (getTableInput(table,"whscode")!="")	focusTableInput(table,"quantity");
					break;
			}
			break;
	}
	return true;
}

