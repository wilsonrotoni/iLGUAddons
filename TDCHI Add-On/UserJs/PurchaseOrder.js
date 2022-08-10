page.elements.events.add.cflgetparams('onElementCFLGetParamsGPSTDCHI');

function onElementCFLGetParamsGPSTDCHI(Id,params) {
	switch (Id) {
		case "df_bpcode":
			params["params"] = ";-WHERE:A.SUPPGROUP='5';" + params["params"];
			break;
	}
	return params;
}

