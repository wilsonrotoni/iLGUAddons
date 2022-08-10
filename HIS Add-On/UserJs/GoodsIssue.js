// JavaScript Document

page.events.add.load('onPageLoadGPSHIS');
page.elements.events.add.validate('onElementValidateGPSHIS');
//page.tables.events.add.reset('onTableResetRowGPSHIS');

var drcodeGPSHIS = "";

function onPageLoadGPSHIS() {
	if (getVar("formSubmitAction")=="a") {
		if (getGlobal("roleid")!="") {
			setTableInput("T1","whscode",getGlobal("roleid"));
			setTableInputDefault("T1","whscode",getGlobal("roleid"));
			disableTableInput("T1","whscode");
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
						onElementValidate(isElement("df_whscodeT1"),"whscode","T1",0);
					}

					if (drcodeGPSHIS!="") {
						setTableInput(table,"drcode",drcodeGPSHIS);
						onElementValidate(isElement("df_drcodeT1"),"drcode","T1",0);
					}

					if (getTableInput(table,"whscode")!="")	focusTableInput(table,"quantity");
					if (getGlobal("roleid")!="") {
						disableTableInput("T1","whscode");
					}

					break;
				case "drcode":
					drcodeGPSHIS = element.value;
					break;
			}
			break;
	}
	return true;
}

function onTableResetRowGPSHIS(table) {
	switch (table) {
		case "T1":
			if (getGlobal("roleid")!="") {
				disableTableInput("T1","whscode");
			}
			break;
	}
}
