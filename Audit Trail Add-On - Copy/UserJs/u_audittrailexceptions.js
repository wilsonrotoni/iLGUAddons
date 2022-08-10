page.events.add.load('onPageLoadGPSAuditTrail');
page.events.add.submit('onSubmitGPSAuditTrail');
page.elements.events.add.validate('onElementValidateGPSAuditTrail');
page.elements.events.add.click('onElementClickGPSAuditTrail');

var selectionall=false;

function onPageLoadGPSAuditTrail() {
	if(getInput("code") != "") {
		var result = page.executeFormattedQuery("SHOW COLUMNS FROM  "+getInput("code")+"");
		for (var i=0;i<result.childNodes.length;i++) {
			switch(result.childNodes.item(i).getAttribute("field")) {
				case "branch":
				case "company":
				case "createdby":
				case "datecreated":
				case "lastupdated":
				case "lastupdatedby":
				case "rcdversion": break;
				default:
					var exists = false;
					for (var j = 1; j <= getTableRowCount("T1"); j++) {
						if(result.childNodes.item(i).getAttribute("field") == getTableInput("T1","u_fieldname",j)) exists = true;
					}
					if(exists == false) {
						var data = new Array();
						data["u_fieldname"] = result.childNodes.item(i).getAttribute("field");
						insertTableRowFromArray("T1",data);
					}
			}
		}
	}
}

function onSubmitGPSAuditTrail(action) {
	return true;
}

function onElementClickGPSAuditTrail(element,column,table,row) {
	switch (table) {
		case "T1":
			switch (column) {
				case "u_log":
					setInputCheckAllAuditTrail(table,column,row);
					break;
			}
			break;
	}
	return true;
}

function onElementValidateGPSAuditTrail(element,column,table,row) {
	var result,result2;

	switch (table) {
		default:
			switch (column) {
				case "code":
					if(getInput("code") == "") {
						setInput("name","");
						clearTable("T1");
						return false;
					}
					
					var code = page.executeFormattedSearch("select code from u_audittrailexceptions where code='" + getInput("code") + "'");
					if(code != "") {
						setKey("keys",code+"`0");
						setElementValueById("formAction","e");
						document.formData.submit();
						return false;
					}
					
					var result = page.executeFormattedQuery("SHOW TABLE STATUS WHERE Name='"+getInput("code")+"'");
					if (result.getAttribute("result") != "-1" && result.getAttribute("result") != "0") {
						var comment = result.childNodes.item(0).getAttribute("comment");
						setInput("name",comment.substring(0, comment.indexOf(";")));
						
						result = page.executeFormattedQuery("SHOW COLUMNS FROM  "+getInput("code")+"");
						for (var i=0;i<result.childNodes.length;i++) {
							switch(result.childNodes.item(i).getAttribute("field")) {
								case "branch":
								case "company":
								case "createdby":
								case "datecreated":
								case "lastupdated":
								case "lastupdatedby":
								case "rcdversion": break;
								default:
									var data = new Array();
									data["u_fieldname"] = result.childNodes.item(i).getAttribute("field");
									data["u_log"] = 1;
									insertTableRowFromArray("T1",data);
									if(i==0) checkedTableInput("T1","u_log"); 
							}
						}
					}
					break;
				case "u_fielddate":
					result = page.executeFormattedQuery("SHOW COLUMNS FROM `"+getInput("code")+"` LIKE '"+getInput("u_fielddate")+"'");
					if (result.getAttribute("result") != "-1" && result.getAttribute("result") != "0") {
					} else {
						setStatusMsg("Invalid Fieldname ["+getInput("u_fielddate")+"] on Table ["+getInput("code")+"].");
						return false;
					}
					break;
			}
	}
	return true;
}

function setInputCheckAllAuditTrail(table,column,row) {
	if(row == 0 || row == null) {
		selectionall = true;
		
		for (var i = 1; i <= getTableRowCount(table); i++) {
			if (!isTableRowDeleted(table,i)) {
				var valbefore = (isTableInputChecked(table,column,i)) ? 1 : 0;
				
				if(isTableInputChecked(table,column)) checkedTableInput(table,column,i); 
				else uncheckedTableInput(table,column,i); 
				
				elementClick(document.getElementById("df_" + column + table + "r" + i),'checkbox',column,table,i);
				
				var valafter = (isTableInputChecked(table,column,i)) ? 1 : 0;
				if(valbefore != valafter && getTableRowStatus(table,i)=="E") setTableRowStatus(table,i,"U");
			}
		}
		selectionall = false;
		
	} else if(selectionall==false) {
		var selected = 0;
		for (var i = 1; i <= getTableRowCount(table); i++) {
			if (!isTableRowDeleted(table,i)) {
				if(isTableInputChecked(table,column,i)) selected++;
			}
		}
		if(selected == getTableRowCount(table,true)) checkedTableInput(table,column); 
		else uncheckedTableInput(table,column); 
	}
}