function OpenLnkBtnu_hispatientregs(targetObjectId) {
	if (getInput("u_reftype")=="IP") {
		OpenLnkBtnu_hisips(targetObjectId);
	} else if (getInput("u_reftype")=="OP") {
		OpenLnkBtnu_hisops(targetObjectId);
	} else page.statusbar.showWarning('No available registration record.');
}

function OpenLnkBtnu_hisips(targetObjectId) {
	OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisips' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}

function OpenLnkBtnu_hisops(targetObjectId) {
	OpenLnkBtn(1024,570,'./udo.php?objectcode=u_hisops' + '' + '&targetId=' + targetObjectId ,targetObjectId);
}
