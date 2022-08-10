page.events.add.submit('onPageSubmitGPSTDCHI');

function onPageSubmitGPSTDCHI(action) {
	if (action=="a" || action=="sc") {
		if (isInputEmpty("u_chqexp",null,null,"tab1",10)) {
			hideAjaxProcess();
			return false;
		}
	}
	return true;
}