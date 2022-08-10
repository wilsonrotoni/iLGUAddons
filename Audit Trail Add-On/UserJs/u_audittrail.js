page.events.add.submit('onSubmitGPSAuditTrail');

function onSubmitGPSAuditTrail(action) {
	if (isInputEmpty("datefrom") || isInputEmpty("dateto")) return false;
	return true;
}

function OpenLnkBtnu_audittrailGPSAuditTrail(targetObjectId) {
	OpenLnkBtn(1000,600,'./udo.php?&objectcode=u_audittraildetails&keys=' + getTableKey("T1","keys",targetObjectId.substring(14,targetObjectId.length)) + '&targetId=' + targetObjectId ,targetObjectId);
}

function onSaveElementValues(p_action) {
	return "df_datefrom`df_dateto`df_branchcode`df_menuid`df_userid`objectcode";
}