healthinsmodified = false;

function u_moveRowUpHealthBenefitsGPSHIS(table) {
	var rc = getTableRowCount(table),sr = 0;	
	if (healthinsmodified) {
		page.statusbar.showWarning("Please update page prior to moving up/dn health benefits.");
		return;
	}
	if (rc==0) return;
	sr = getTableSelectedRow(table);
	var keys,u_inscode,u_hmo,u_insdesc,u_memberid,u_membername,u_membertype,u_membertypedesc,u_scdisc;
	var keys2,u_inscode2,u_hmo2,u_insdesc2,u_memberid2,u_membername2,u_membertype2,u_membertypedesc2,u_scdisc2;
	if (sr!=1) {
		keys = getTableKey(table,"keys",sr);
		u_inscode = getTableInput(table,"u_inscode",sr);
		u_hmo = getTableInput(table,"u_hmo",sr);
		u_scdisc = getTableInput(table,"u_scdisc",sr);
		u_memberid = getTableInput(table,"u_memberid",sr);
		u_membername = getTableInput(table,"u_membername",sr);
		u_membertype = getTableInput(table,"u_membertype",sr);
		u_insdesc = page.executeFormattedSearch("select name from u_hishealthins where code='"+u_inscode+"'");
		u_membertypedesc = page.executeFormattedSearch("select name from u_hishealthinmemtypes where code='"+u_membertype+"'");

		keys2 = getTableKey(table,"keys",sr - 1);
		u_inscode2 = getTableInput(table,"u_inscode",sr - 1);
		u_hmo2 = getTableInput(table,"u_hmo",sr - 1);
		u_scdisc2 = getTableInput(table,"u_scdisc",sr - 1);
		u_memberid2 = getTableInput(table,"u_memberid",sr - 1);
		u_membername2 = getTableInput(table,"u_membername",sr - 1);
		u_membertype2 = getTableInput(table,"u_membertype",sr - 1);
		u_insdesc2 = page.executeFormattedSearch("select name from u_hishealthins where code='"+u_inscode2+"'");
		u_membertypedesc2 = page.executeFormattedSearch("select name from u_hishealthinmemtypes where code='"+u_membertype2+"'");
		
		setTableKey(table,"keys",keys,sr - 1);
		setTableInput(table,"u_inscode",u_inscode,sr - 1,u_insdesc);
		setTableInput(table,"u_hmo",u_hmo,sr - 1);
		setTableInput(table,"u_scdisc",u_scdisc,sr - 1);
		setTableInput(table,"u_memberid",u_memberid,sr - 1);
		setTableInput(table,"u_membername",u_membername,sr - 1);
		setTableInput(table,"u_membertype",u_membertype,sr - 1,u_membertypedesc);
		
		setTableKey(table,"keys",keys2,sr);
		setTableInput(table,"u_inscode",u_inscode2,sr,u_insdesc2);
		setTableInput(table,"u_hmo",u_hmo2,sr);
		setTableInput(table,"u_scdisc",u_scdisc2,sr);
		setTableInput(table,"u_memberid",u_memberid2,sr);
		setTableInput(table,"u_membername",u_membername2,sr);
		setTableInput(table,"u_membertype",u_membertype2,sr,u_membertypedesc2);
			
		selectTableRow(table,sr - 1);
	}			
}

function u_moveRowDnHealthBenefitsGPSHIS(table) {
	var rc = getTableRowCount(table),sr=0;
	if (healthinsmodified) {
		page.statusbar.showWarning("Please update page prior to moving up/dn health benefits.");
		return;
	}
	if (rc==0) return;
	sr = getTableSelectedRow(table);	
	var keys,u_inscode,u_hmo,u_insdesc,u_memberid,u_membername,u_membertype,u_membertypedesc,u_scdisc;
	var keys2,u_inscode2,u_hmo2,u_insdesc2,u_memberid2,u_membername2,u_membertype2,u_membertypedesc2,u_scdisc2;
	if (sr!=rc) {
		keys = getTableKey(table,"keys",sr);
		u_inscode = getTableInput(table,"u_inscode",sr);
		u_hmo = getTableInput(table,"u_hmo",sr);
		u_scdisc = getTableInput(table,"u_scdisc",sr);
		u_memberid = getTableInput(table,"u_memberid",sr);
		u_membername = getTableInput(table,"u_membername",sr);
		u_membertype = getTableInput(table,"u_membertype",sr);
		u_insdesc = page.executeFormattedSearch("select name from u_hishealthins where code='"+u_inscode+"'");
		u_membertypedesc = page.executeFormattedSearch("select name from u_hishealthinmemtypes where code='"+u_membertype+"'");
		
		keys2 = getTableKey(table,"keys",sr + 1);
		u_inscode2 = getTableInput(table,"u_inscode",sr + 1);
		u_hmo2 = getTableInput(table,"u_hmo",sr + 1);
		u_scdisc2 = getTableInput(table,"u_scdisc",sr + 1);
		u_memberid2 = getTableInput(table,"u_memberid",sr + 1);
		u_membername2 = getTableInput(table,"u_membername",sr + 1);
		u_membertype2 = getTableInput(table,"u_membertype",sr + 1);
		u_insdesc2 = page.executeFormattedSearch("select name from u_hishealthins where code='"+u_inscode2+"'");
		u_membertypedesc2 = page.executeFormattedSearch("select name from u_hishealthinmemtypes where code='"+u_membertype2+"'");

		setTableKey(table,"keys",keys,sr + 1);
		setTableInput(table,"u_inscode",u_inscode,sr + 1,u_insdesc);
		setTableInput(table,"u_hmo",u_hmo,sr + 1);
		setTableInput(table,"u_scdisc",u_scdisc,sr + 1);
		setTableInput(table,"u_memberid",u_memberid,sr + 1);
		setTableInput(table,"u_membername",u_membername,sr + 1);
		setTableInput(table,"u_membertype",u_membertype,sr + 1,u_membertypedesc);
		
		setTableKey(table,"keys",keys2,sr);
		setTableInput(table,"u_inscode",u_inscode2,sr,u_insdesc2);
		setTableInput(table,"u_hmo",u_hmo2,sr);
		setTableInput(table,"u_scdisc",u_scdisc2,sr);
		setTableInput(table,"u_memberid",u_memberid2,sr);
		setTableInput(table,"u_membername",u_membername2,sr);
		setTableInput(table,"u_membertype",u_membertype2,sr,u_membertypedesc2);
			
		selectTableRow(table,sr + 1);
	}	
	
}
