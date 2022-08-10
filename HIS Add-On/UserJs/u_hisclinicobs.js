// page events
page.events.add.load('onPageLoadGPSHIS');
//page.events.add.resize('onPageResizeGPSHIS');
page.events.add.submit('onPageSubmitGPSHIS');
//page.events.add.submitreturn('onPageSubmitReturnGPSHIS');
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
page.elements.events.add.click('onElementClickGPSHIS');
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
//page.tables.events.add.afterEdit('onTableAfterEditRowGPSHIS');

function onPageLoadGPSHIS() {
	try {
		if (getVar("formSubmitAction")=="a") {
			if(window.opener.getVar("objectcode")=="U_HISOPS") {
				setInput("u_reftype","OP");
			} else {
				setInput("u_reftype","IP");
			}
			setInput("u_refno",window.opener.getInput("docno"),true);
			//setInput("u_od_ad",window.opener.getInput("u_remarks"));
		}
		
	} catch (theError) {
	}
	
}

function onPageResizeGPSHIS(width,height) {
}

function onPageSubmitGPSHIS(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_pl")) return false;
		if (isInputEmpty("u_pl_site")) return false;
		if (getInput("u_pl_tubal")=="1") {
			if (isInputEmpty("u_pl_tubalpart")) return false;
			if (isInputEmpty("u_pl_ruptured")) return false;
		}
		if (getInput("u_pl_embryo")=="1") {
			if (isInputEmpty("u_pl_embryostate")) return false;
		}
		if (isInputEmpty("u_od_aog")) return false;
		//if (isInputEmpty("u_od_ad")) return false;
		//if (isInputEmpty("u_od_fd")) return false;
		if (getInput("u_fo_general")=="Preterm/Postterm") {
			if (isInputEmpty("u_od_preterm")) return false;
			if (isInputEmpty("u_od_pa")) return false;
		}
		if (isInputEmpty("u_od_presentation")) return false;
		if (getInput("u_od_presentation")=="Breech") {
			if (isInputEmpty("u_od_presentationbreech")) return false;
		}
		if (isInputEmpty("u_od_laborstatus")) return false;
		if (isInputEmpty("u_od_mannerofdelivery")) return false;
		if (getInput("u_od_mannerofdelivery")=="Vaginal Assisted Delivery") {
			if (isInputEmpty("u_od_vaginal")) return false;
		}
		if (getInput("u_od_mannerofdelivery")=="Cesarean Section" || getInput("u_od_mannerofdelivery")=="Repeat Cesarean Section") {
			if (isInputEmpty("u_od_cesarean")) return false;
		}
		if (getInput("u_od_mannerofdelivery")=="Cesarean Section") {
			if (isInputEmpty("u_od_cesarean_info")) return false;
		}
		if (getInput("u_od_mannerofdelivery")=="Repeat Cesarean Section") {
			if (isInputEmpty("u_od_repeatcesarean_info")) return false;
		}
		if (getInput("u_od_mannerofdelivery")=="Other") {
			if (isInputEmpty("u_od_mod")) return false;
		}
		if (getInput("u_od_mm")=="1") {
			if (isInputEmpty("u_od_mm")) return false;
		}
		if (getInput("u_od_sm")=="1") {
			if (isInputEmpty("u_od_sm")) return false;
		}
		if (getInput("u_od_bt")=="1") {
			if (isInputEmpty("u_od_bt")) return false;
		}
		
		if (isInputEmpty("u_fo_general")) return false;
		if (getInput("u_fo_general")=="Stillbirth") {
			if (isInputEmpty("u_fo_generalstillbirth")) return false;
		}
		if (isInputEmpty("u_fo_sex")) return false;
		if (getInput("u_fo_snc")=="1") {
			if (isInputEmpty("u_fo_snc_info")) return false;
		}
		if (getInput("u_fo_spa")=="1") {
			if (isInputEmpty("u_fo_spa_info")) return false;
		}
		
		
	}
	return true;
}

function onPageSubmitReturnGPSHIS(action,success,error) {
	try {
		if (success) {	
			//window.opener.setKey("keys",window.opener.getInput("docno"));
			//window.opener.formEdit();
			window.close();
		}
	} catch(TheError) {
	}
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
	switch (column) {
		case "u_fo_general":
			setInput("u_fo_generalstillbirth","");
			setInput("u_od_preterm","");
			break;
		case "u_od_mannerofdelivery":
			setInput("u_od_vaginal","");
			setInput("u_od_cesarean","");
			break;
		case "u_od_presentation":
			setInput("u_od_presentationbreech","");
			break;
	}
	return true;
}

function onElementCFLGPSHIS(Id) {
	return true;
}

function onElementCFLGetParamsGPSHIS(Id,params) {
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

function onTableAfterEditRowGPSHIS(table,row) {
}

