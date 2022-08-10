

function getCollectionsGPSLGUAcctg() {
        showAjaxProcess();
	if (isInputEmpty("u_datefrom")) return false;
	if (isInputEmpty("u_dateto")) return false;
	var data = new Array(),data2 = new Array();
	clearTable("T1",true);
	clearTable("T2",true);
	var u_tax=0,u_discamount=0,u_penalty=0,u_sef=0,u_sefdiscamount=0,u_sefpenalty=0,u_epsf =0,unionExp="",u_cityrptax=0,u_brgyrptax=0,u_brgydisc=0,u_bldgpermit=0,u_citybldg=0,u_ngasbldg=0;
	var u_taxadv=0,u_discamountadv=0,u_sefadv=0,u_sefdiscamountadv=0,u_epsfadv = 0,u_cityrppenalty = 0,u_brgyrppenalty = 0;
	var u_bplamount=0,u_bldgamount=0;
	
	var result = page.executeFormattedQuery("select sum(u_epsf) as u_epsf, sum(u_tax) as u_tax, sum(u_discamount) as u_discamount, sum(u_penalty) as u_penalty,sum(u_sef) as u_sef, sum(u_sefdiscamount) as u_sefdiscamount, sum(u_sefpenalty) as u_sefpenalty  from ( select sum(c.u_epsf) as u_epsf, sum(c.u_taxdue) as u_tax, sum(c.u_taxdisc) as u_discamount, sum(c.u_penalty) as u_penalty,sum(c.u_sef) as u_sef, sum(c.u_sefdisc) as u_sefdiscamount, sum(c.u_sefpenalty) as u_sefpenalty from bacoor2022a.u_lgupos a inner join bacoor2022a.u_rptaxbill b on b.company=a.company and b.branch=a.branch and b.docno=a.u_billno inner join bacoor2022a.u_rptaxbillarps c on b.company=c.company and b.branch=c.branch and b.docid=c.docid and c.u_selected = 1 where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and c.u_yrfr <= year('"+formatDateToDB(getInput("u_datefrom"))+"') and a.u_status not in ('CN','D') and a.u_module in ('Real Property Tax Bill') union all select sum(c.u_epsf  * -1) as u_epsf, sum(c.u_taxdue * -1) as u_tax, 0 as u_discamount, sum(c.u_penalty  * -1) as u_penalty,sum(c.u_sef  * -1) as u_sef, 0 as u_sefdiscamount, sum(c.u_sefpenalty  * -1) as u_sefpenalty from bacoor2022a.u_lgupos a inner join bacoor2022a.u_rptaxbill b on b.company=a.company and b.branch=a.branch and b.docno=a.u_billno inner join bacoor2022a.u_rptaxbillcredits c on b.company=c.company and b.branch=c.branch and b.docid=c.docid and c.u_selected = 1 where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and c.u_year <= year('"+formatDateToDB(getInput("u_datefrom"))+"') and a.u_status not in ('CN','D') and a.u_module in ('Real Property Tax Bill') ) as x");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (iii = 0; iii < result.childNodes.length; iii++) {
				u_epsf = parseFloat(result.childNodes.item(iii).getAttribute("u_epsf"));
				u_tax = parseFloat(result.childNodes.item(iii).getAttribute("u_tax"));
				u_discamount = parseFloat(result.childNodes.item(iii).getAttribute("u_discamount"));
				u_penalty = parseFloat(result.childNodes.item(iii).getAttribute("u_penalty"));
				u_sef = parseFloat(result.childNodes.item(iii).getAttribute("u_sef"));
				u_sefdiscamount = parseFloat(result.childNodes.item(iii).getAttribute("u_sefdiscamount"));
				u_sefpenalty = parseFloat(result.childNodes.item(iii).getAttribute("u_sefpenalty"));
			}
		}
		if (u_tax>0 && getGlobal("branch") == "100") {
			u_cityrptax = (u_tax ) *.70;
			u_brgyrptax = u_tax-u_cityrptax;
			u_cityrppenalty = (u_penalty ) *.70;
			u_brgyrppenalty = u_penalty-u_cityrppenalty;
			u_brgydisc = u_discamount *.30;
			
			unionExp += " union all select '4-01-05-020' as u_glacctcode, 'Tax Revenue - Fines and Penalties - Property Taxes' as u_glacctname, '' as u_sl, 0 as u_debit, "+u_cityrppenalty+" as u_credit, "+u_cityrppenalty+" as u_amount ";
			unionExp += " union all select '4-02-01-190' as u_glacctcode, 'Garbage Fees' as u_glacctname, 'EPSF' as u_sl, 0 as u_debit, "+u_epsf+" as u_credit, "+u_epsf+" as u_amount ";
			unionExp += " union all select '4-01-02-040' as u_glacctcode, 'Real Property Tax - Basic' as u_glacctname, '' as u_sl, 0 as u_debit, "+u_cityrptax+" as u_credit, "+u_cityrptax+" as u_amount ";
			unionExp += " union all select '2-02-01-070' as u_glacctcode, 'Due to LGUs' as u_glacctname, 'Barangay' as u_sl, 0 as u_debit, "+u_brgyrptax+" as u_credit, "+u_brgyrptax+" as u_amount ";
			unionExp += " union all select '1-03-01-020' as u_glacctcode, 'Real Property Tax Receivable' as u_glacctname, '' as u_sl, 0 as u_debit, "+(u_tax)+" as u_credit, "+(u_tax)+" as u_amount ";
			unionExp += " union all select '2-05-01-010' as u_glacctcode, 'Deferred Real Property Tax' as u_glacctname, '' as u_sl, "+(u_tax )+" as u_debit, 0 as u_credit, "+((u_tax)*-1)+" as u_amount ";
			unionExp += " union all select '4-01-02-041' as u_glacctcode, 'Discount on Real Property Tax - Basic' as u_glacctname, '' as u_sl, "+u_discamount+" as u_debit, 0 as u_credit, "+u_discamount*-1+" as u_amount ";
			unionExp += " union all select '4-01-02-041' as u_glacctcode, 'Discount on Real Property Tax - Basic' as u_glacctname, '' as u_sl, 0 as u_debit, "+u_brgydisc+" as u_credit, "+u_brgydisc+" as u_amount ";
			unionExp += " union all select '2-02-01-070' as u_glacctcode, 'Due to LGUs' as u_glacctname, 'Barangay' as u_sl, "+u_brgydisc+" as u_debit, 0 as u_credit, "+u_brgydisc*-1+" as u_amount ";
			unionExp += " union all select '2-02-01-070' as u_glacctcode, 'Due to LGUs' as u_glacctname, 'Barangay' as u_sl, 0 as u_debit, "+u_brgyrppenalty+" as u_credit, "+u_brgyrppenalty+" as u_amount ";
			
		}
		
		
		if (u_sef>0 && getGlobal("branch") == "200") {
			
			unionExp += " union all select '4-01-02-050' as u_glacctcode, 'Special Education Tax' as u_glacctname, '' as u_sl, 0 as u_debit, "+u_sef+" as u_credit, "+u_sef+" as u_amount ";
			unionExp += " union all select '1-03-01-030' as u_glacctcode, 'Special Education Tax Receivable' as u_glacctname, '' as u_sl, 0 as u_debit, "+u_sef+" as u_credit, "+u_sef+" as u_amount ";
			unionExp += " union all select '2-05-01-020' as u_glacctcode, 'Deferred Special Education Tax' as u_glacctname, '' as u_sl, "+u_sef+" as u_debit, 0 as u_credit, "+((u_sef)*-1)+" as u_amount ";
			unionExp += " union all select '4-01-02-051' as u_glacctcode, 'Discount on Special Education Tax' as u_glacctname, '' as u_sl, "+u_sefdiscamount+" as u_debit, 0 as u_credit, "+u_sefdiscamount*-1+" as u_amount ";
			
		}
		
	} else {
		page.statusbar.showError("Error retrieving collections for real property taxes. Try Again, if problem persists, check the connection.");	
		return false;
	}
	//For Business Permit
		//unionExp += " union all select '4-01-01-050' as u_glacctcode, 'Community Tax' as u_glacctname, 'Barangay' as u_sl, (u_linetotal / 2)  as u_debit, 0 as u_credit, (u_linetotal / 2) * -1 as u_amount from bacooruat.u_lgupos a inner join bacooruat.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode in ('0016','0017') and b.u_linetotal > 0 inner join bacooruat.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_date between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.u_fundtype = '"+getGlobal("branch")+"' ";
		
	
	//For Advance Payment
	var result = page.executeFormattedQuery("select sum(u_epsf) as u_epsf, sum(u_tax) as u_tax, sum(u_discamount) as u_discamount, sum(u_penalty) as u_penalty,sum(u_sef) as u_sef, sum(u_sefdiscamount) as u_sefdiscamount, sum(u_sefpenalty) as u_sefpenalty  from ( select sum(c.u_epsf) as u_epsf ,sum(c.u_taxdue) as u_tax, sum(c.u_taxdisc) as u_discamount, sum(c.u_penalty) as u_penalty,sum(c.u_sef) as u_sef, sum(c.u_sefdisc) as u_sefdiscamount, sum(c.u_sefpenalty) as u_sefpenalty from bacoor2022a.u_lgupos a inner join bacoor2022a.u_rptaxbill b on b.company=a.company and b.branch=a.branch and b.docno=a.u_billno inner join bacoor2022a.u_rptaxbillarps c on b.company=c.company and b.branch=c.branch and b.docid=c.docid and c.u_selected = 1 where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and c.u_yrfr > year('"+formatDateToDB(getInput("u_datefrom"))+"') and a.u_status not in ('CN','D') and a.u_module in ('Real Property Tax Bill') union all select sum(c.u_epsf * -1) as u_epsf ,sum(c.u_taxdue  * -1) as u_tax, sum(0) as u_discamount, sum(c.u_penalty  * -1) as u_penalty,sum(c.u_sef  * -1) as u_sef, sum(0) as u_sefdiscamount, sum(c.u_sefpenalty  * -1) as u_sefpenalty from bacoor2022a.u_lgupos a inner join bacoor2022a.u_rptaxbill b on b.company=a.company and b.branch=a.branch and b.docno=a.u_billno inner join bacoor2022a.u_rptaxbillcredits c on b.company=c.company and b.branch=c.branch and b.docid=c.docid and c.u_selected = 1 where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and c.u_year > year('"+formatDateToDB(getInput("u_datefrom"))+"') and a.u_status not in ('CN','D') and a.u_module in ('Real Property Tax Bill') ) as x");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (iii = 0; iii < result.childNodes.length; iii++) {
				u_epsfadv = parseFloat(result.childNodes.item(iii).getAttribute("u_epsf"));
				u_taxadv = parseFloat(result.childNodes.item(iii).getAttribute("u_tax"));
				u_discamountadv = parseFloat(result.childNodes.item(iii).getAttribute("u_discamount"));
				u_sefadv = parseFloat(result.childNodes.item(iii).getAttribute("u_sef"));
				u_sefdiscamountadv = parseFloat(result.childNodes.item(iii).getAttribute("u_sefdiscamount"));
			}
		}	
		if (u_taxadv>0 && getGlobal("branch") == "100") {
				unionExp += " union all select '2-05-01-990' as u_glacctcode, 'Other Deferred Credits(advance RPT)' as u_glacctname, 'A' as u_sl, 0 as u_debit, "+(u_taxadv - u_discamountadv)+" as u_credit, "+(u_taxadv - u_discamountadv)+" as u_amount ";
				unionExp += " union all select '2-05-01-990' as u_glacctcode, 'Other Deferred Credits(advance EPSF)' as u_glacctname, 'B' as u_sl, 0 as u_debit, "+u_epsfadv+" as u_credit, "+u_epsfadv+" as u_amount ";
				/*unionExp += " union all select '1-03-01-020' as u_glacctcode, 'Real Property Tax Receivable' as u_glacctname, '' as u_sl, 0 as u_debit, "+(u_tax)+" as u_credit, "+(u_tax)+" as u_amount ";
				unionExp += " union all select '2-05-01-010' as u_glacctcode, 'Deferred Real Property Tax' as u_glacctname, '' as u_sl, "+(u_tax )+" as u_debit, 0 as u_credit, "+((u_tax)*-1)+" as u_amount ";
				unionExp += " union all select '4-01-02-041' as u_glacctcode, 'Discount on Real Property Tax - Basic' as u_glacctname, '' as u_sl, "+u_discamount+" as u_debit, 0 as u_credit, "+u_discamount*-1+" as u_amount ";
				unionExp += " union all select '4-01-02-041' as u_glacctcode, 'Discount on Real Property Tax - Basic' as u_glacctname, '' as u_sl, 0 as u_debit, "+u_brgydisc+" as u_credit, "+u_brgydisc+" as u_amount ";
				unionExp += " union all select '2-02-01-070' as u_glacctcode, 'Due to LGUs' as u_glacctname, 'Barangay' as u_sl, "+u_brgydisc+" as u_debit, 0 as u_credit, "+u_brgydisc*-1+" as u_amount ";
				*/
		}
		if (u_sefadv>0 && getGlobal("branch") == "200") {
				unionExp += " union all select '2-05-01-990' as u_glacctcode, 'Other Deferred Credits(advance RPT)' as u_glacctname, 'A' as u_sl, 0 as u_debit, "+(u_sefadv - u_sefdiscamountadv)+" as u_credit, "+(u_sefadv - u_sefdiscamountadv)+" as u_amount ";
				/*unionExp += " union all select '1-03-01-020' as u_glacctcode, 'Real Property Tax Receivable' as u_glacctname, '' as u_sl, 0 as u_debit, "+(u_tax)+" as u_credit, "+(u_tax)+" as u_amount ";
				unionExp += " union all select '2-05-01-010' as u_glacctcode, 'Deferred Real Property Tax' as u_glacctname, '' as u_sl, "+(u_tax )+" as u_debit, 0 as u_credit, "+((u_tax)*-1)+" as u_amount ";
				unionExp += " union all select '4-01-02-041' as u_glacctcode, 'Discount on Real Property Tax - Basic' as u_glacctname, '' as u_sl, "+u_discamount+" as u_debit, 0 as u_credit, "+u_discamount*-1+" as u_amount ";
				unionExp += " union all select '4-01-02-041' as u_glacctcode, 'Discount on Real Property Tax - Basic' as u_glacctname, '' as u_sl, 0 as u_debit, "+u_brgydisc+" as u_credit, "+u_brgydisc+" as u_amount ";
				unionExp += " union all select '2-02-01-070' as u_glacctcode, 'Due to LGUs' as u_glacctname, 'Barangay' as u_sl, "+u_brgydisc+" as u_debit, 0 as u_credit, "+u_brgydisc*-1+" as u_amount ";
				*/
		}
		
	} else {
				page.statusbar.showError("Error retrieving collections for real property taxes. Try Again, if problem persists, check the connection.");	
				return false;
	}	

		
		var result = page.executeFormattedQuery("select sum(u_linetotal) as u_amount from bacoor2022a.u_lgupos a inner join bacoor2022a.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemdesc in ('Building Permit Fee') inner join bacoor2022a.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and a.u_module not in ('Real Property Tax Bill') and c.u_fundtype = '"+getGlobal("branch")+"'");
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (iii = 0; iii < result.childNodes.length; iii++) {
					u_bldgpermit = parseFloat(result.childNodes.item(iii).getAttribute("u_amount"));	
				}
			}
		} else {
			page.statusbar.showError("Error retrieving collections for building permit. Try Again, if problem persists, check the connection.");	
			return false;
		}	
		
		if (u_bldgpermit>0) {
			u_citybldg = u_bldgpermit*.80;
			u_ngasbldg = u_bldgpermit-u_citybldg;
			//unionExp += " union all select '2-02-01-050' as u_glacctcode, 'Due to NGAs' as u_glacctname, 'Bldg Permit 20%' as u_sl, 0 as u_debit, "+u_ngasbldg+" as u_credit, "+u_ngasbldg+" as u_amount ";
			//unionExp += " union all select '4-02-01-040' as u_glacctcode, 'Clearance and Certification Fees' as u_glacctname, '' as u_sl, 0 as u_debit, "+u_citybldg+" as u_credit, "+u_citybldg+" as u_amount ";
			
		}
		
		//For CTC Barangay for Bacooruatrpt
                unionExp += " union all select c.u_glacctcode, c.u_glacctname, if(d.u_apptype = 'I','A','C') as u_sl, 0 as u_debit, u_linetotal as u_credit, u_linetotal as u_amount from bacoor2022a.u_lgupos a inner join bacoor2022a.u_ctcapps d on a.company = d.company and a.branch = d.branch and a.docno = d.u_orno inner join bacoor2022a.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemdesc not in ('Building Permit Fee') inner join bacoor2022a.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.code not in ('0008','0009') and c.u_fundtype = '"+getGlobal("branch")+"'";
		
		unionExp += " union all select '4-01-01-050' as u_glacctcode, 'Community Tax' as u_glacctname, 'Barangay' as u_sl, (u_linetotal / 2)  as u_debit, 0 as u_credit, (u_linetotal / 2) * -1 as u_amount from bacoor2022a.u_lgupos a inner join bacoor2022a.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode in ('0016','0017') and b.u_linetotal > 0 inner join bacoor2022a.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.u_fundtype = '"+getGlobal("branch")+"' ";
		unionExp += " union all select '4-01-01-050' as u_glacctcode, 'Community Tax' as u_glacctname, 'Barangay' as u_sl, 0 as u_debit, u_linetotal  as u_credit, u_linetotal  as u_amount from bacoor2022a.u_lgupos a inner join bacoor2022a.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode in ('0016','0017') and b.u_linetotal > 0 inner join bacoor2022a.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.u_fundtype = '"+getGlobal("branch")+"' ";
		unionExp += " union all select '2-02-01-070' as u_glacctcode, 'Due to LGUs' as u_glacctname, 'Barangay' as u_sl, 0 as u_debit, (u_linetotal / 2)  as u_credit, (u_linetotal / 2)  as u_amount from bacoor2022a.u_lgupos a inner join bacoor2022a.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode in ('0016','0017') and b.u_linetotal > 0 inner join bacoor2022a.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.u_fundtype = '"+getGlobal("branch")+"' ";
		//unionExp += " union all select c.u_glacctcode, c.u_glacctname, if(c.u_sl<>'',c.u_sl,b.u_itemdesc) as u_sl, 0 as u_debit, u_linetotal as u_credit, u_linetotal as u_amount from bacoor2022a.u_lgupos a inner join bacoor2022a.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode in ('0016','0017') and b.u_linetotal > 0 inner join bacoor2022a.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_date between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.u_fundtype = '"+getGlobal("branch")+"' ";
			
                 
                //For CTC Barangay for Bacooruat
                unionExp += " union all select c.u_glacctcode, c.u_glacctname, if(d.u_apptype = 'I','A','C') as u_sl, 0 as u_debit, u_linetotal as u_credit, u_linetotal as u_amount from bacooruat.u_lgupos a inner join bacooruat.u_ctcapps d on a.company = d.company and a.branch = d.branch and a.docno = d.u_orno inner join bacooruat.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemdesc not in ('Building Permit Fee') inner join bacooruat.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.code not in ('0008','0009') and c.u_fundtype = '"+getGlobal("branch")+"'";
		
		unionExp += " union all select '4-01-01-050' as u_glacctcode, 'Community Tax' as u_glacctname, 'Barangay' as u_sl, (u_linetotal / 2)  as u_debit, 0 as u_credit, (u_linetotal / 2) * -1 as u_amount from bacooruat.u_lgupos a inner join bacooruat.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode in ('0016','0017') and b.u_linetotal > 0 inner join bacooruat.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.u_fundtype = '"+getGlobal("branch")+"' ";
		unionExp += " union all select '4-01-01-050' as u_glacctcode, 'Community Tax' as u_glacctname, 'Barangay' as u_sl, 0 as u_debit, u_linetotal as u_credit, u_linetotal as u_amount from bacooruat.u_lgupos a inner join bacooruat.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode in ('0016','0017') and b.u_linetotal > 0 inner join bacooruat.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.u_fundtype = '"+getGlobal("branch")+"' ";
		unionExp += " union all select '2-02-01-070' as u_glacctcode, 'Due to LGUs' as u_glacctname, 'Barangay' as u_sl, 0 as u_debit, (u_linetotal / 2) as u_credit, (u_linetotal / 2) as u_amount from bacooruat.u_lgupos a inner join bacooruat.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode in ('0016','0017') and b.u_linetotal > 0 inner join bacooruat.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.u_fundtype = '"+getGlobal("branch")+"' ";
		//unionExp += " union all select c.u_glacctcode, c.u_glacctname, if(c.u_sl<>'',c.u_sl,b.u_itemdesc) as u_sl, 0 as u_debit, u_linetotal as u_credit, u_linetotal as u_amount from bacooruat.u_lgupos a inner join bacooruat.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_itemcode in ('0016','0017') and b.u_linetotal > 0 inner join bacooruat.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_date between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and c.u_fundtype = '"+getGlobal("branch")+"' ";
		
		//FOR Business
//		unionExp += " union all select B.u_glacctcode,B.u_glacctname,B.u_sl,0 as u_debit, A.u_amountdue as u_credit, A.u_amountdue as u_amount FROM bacoor2022a.u_bplledger A INNER JOIN bacoor2022a.U_LGUFEES B ON A.U_FEEID = B.CODE WHERE a.u_ordate between '"+formatDateToDB(getInput("u_datefrom"))+"' and '"+formatDateToDB(getInput("u_dateto"))+"' and u_iscancelled = 0 and b.u_fundtype = '"+getGlobal("branch")+"' and a.u_feeid not in ('0004')";
		
                //For Business and Building Permit 
               var result = page.executeFormattedQuery("select sum(a.u_surcharge) as u_surcharge,sum(a.u_amountdue) as u_amountdue,b.u_fundtype,IF(C.CODE=A.U_FEEID,1,0) AS u_isbldg,U_FEEID,B.u_glacctcode,B.u_glacctname,B.u_sl,0 as u_debit, IF(C.CODE=A.U_FEEID,sum(a.u_amountpaid * .8),sum(a.u_amountpaid)) as u_cityamount,IF(C.CODE=A.U_FEEID,sum(a.u_amountpaid * .05),sum(a.u_amountpaid)) as u_dpwhamount,IF(C.CODE=A.U_FEEID,sum(a.u_amountpaid * .15),sum(a.u_amountpaid)) as u_oboamount,sum(a.u_amountpaid) as u_amount FROM bacoor2022a.u_bplledger A INNER JOIN bacoor2022a.U_LGUFEES B ON A.U_FEEID = B.CODE  LEFT JOIN bacoor2022a.u_bldgfees C ON A.U_FEEID = C.CODE INNER JOIN bacoor2022a.U_LGUPOS D ON D.DOCNO = A.U_ORNO AND A.COMPANY = D.COMPANY AND A.BRANCH = D.BRANCH  WHERE d.u_profitcenter  = 'BPL' and  a.u_ordate between '"+formatDateToDB(getInput("u_datefrom"))+"' and '"+formatDateToDB(getInput("u_dateto"))+"' and u_iscancelled = 0  and a.u_feeid not in ('0004') group by b.u_glacctcode,b.u_sl");
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (iii = 0; iii < result.childNodes.length; iii++) {
                                    if (getGlobal("branch") == result.childNodes.item(iii).getAttribute("u_fundtype")) {
                                        if (result.childNodes.item(iii).getAttribute("U_FEEID")  == "0001" || result.childNodes.item(iii).getAttribute("U_FEEID")  == "0002") {
                                                unionExp += " union all select '4-02-01-980' as u_glacctcode, 'Fines and Penalties - Service Income' as u_glacctname, '' as u_sl,0 as u_debit,  "+result.childNodes.item(iii).getAttribute("u_surcharge")+" as u_credit, "+result.childNodes.item(iii).getAttribute("u_surcharge")+" as u_amount ";
                                                unionExp += " union all select '"+result.childNodes.item(iii).getAttribute("u_glacctcode")+"' as u_glacctcode, '"+result.childNodes.item(iii).getAttribute("u_glacctname")+"' as u_glacctname, '"+result.childNodes.item(iii).getAttribute("u_sl")+"' as u_sl,0 as u_debit,  "+result.childNodes.item(iii).getAttribute("u_amountdue")+" as u_credit, "+result.childNodes.item(iii).getAttribute("u_amountdue")+" as u_amount ";
                                        } else {
                                                unionExp += " union all select '"+result.childNodes.item(iii).getAttribute("u_glacctcode")+"' as u_glacctcode, '"+result.childNodes.item(iii).getAttribute("u_glacctname")+"' as u_glacctname, '"+result.childNodes.item(iii).getAttribute("u_sl")+"' as u_sl,0 as u_debit,  "+result.childNodes.item(iii).getAttribute("u_cityamount")+" as u_credit, "+result.childNodes.item(iii).getAttribute("u_cityamount")+" as u_amount ";
                                        }
                                    }
                                    if (result.childNodes.item(iii).getAttribute("u_isbldg") == 1 && getGlobal("branch") == "300") {
                                        unionExp += " union all select '2-99-99-990' as u_glacctcode, 'Other Payables' as u_glacctname, 'E' as u_sl,0 as u_debit,  "+result.childNodes.item(iii).getAttribute("u_dpwhamount")+" as u_credit, "+result.childNodes.item(iii).getAttribute("u_dpwhamount")+" as u_amount ";
                                        unionExp += " union all select '2-99-99-990' as u_glacctcode, 'Other Payables' as u_glacctname, 'EE' as u_sl,0 as u_debit,  "+result.childNodes.item(iii).getAttribute("u_oboamount")+" as u_credit, "+result.childNodes.item(iii).getAttribute("u_oboamount")+" as u_amount ";
                                    }
				}
			}
		} else {
			page.statusbar.showError("Error retrieving Business Collection. Try Again, if problem persists, check the connection.");	
			return false;
		}
                
		//FOR Misc and Other Collection without RPT Fees and Cedula
		//alert("select u_glacctcode, u_glacctname, u_sl, sum(u_debit) as u_debit, sum(u_credit) as u_credit, sum(u_amount) as u_amount from (select c.u_glacctcode, c.u_glacctname, c.u_sl as u_sl, 0 as u_debit, u_linetotal as u_credit, u_linetotal as u_amount from bacoor2022a.u_lgupos a inner join bacoor2022a.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid  and b.u_linetotal > 0 inner join bacoor2022a.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and a.u_profitcenter <> 'BPL' and c.code not in ('0008','0009','0010','0019') and c.u_fundtype = '"+getGlobal("branch")+"' "+unionExp+") as x group by u_glacctcode, u_sl");
		//var result = page.executeFormattedQuery("select u_glacctcode, u_glacctname, u_sl, sum(u_debit) as u_debit, sum(u_credit) as u_credit, sum(u_amount) as u_amount from (select c.u_glacctcode, c.u_glacctname, c.u_sl as u_sl, 0 as u_debit, u_linetotal as u_credit, u_linetotal as u_amount from bacoor2022a.u_lgupos a inner join bacoor2022a.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid  and b.u_linetotal > 0 inner join bacoor2022a.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and a.u_profitcenter <> 'BPL' and c.code not in ('0008','0009','0010','0019','0014','0015','0016','0017','00454') and c.u_fundtype = '"+getGlobal("branch")+"' ) as x group by u_glacctcode, u_sl");
		var result = page.executeFormattedQuery("select u_glacctcode, u_glacctname, u_sl, sum(u_debit) as u_debit, sum(u_credit) as u_credit, sum(u_amount) as u_amount from (select c.u_glacctcode, c.u_glacctname, c.u_sl as u_sl, 0 as u_debit, u_linetotal as u_credit, u_linetotal as u_amount from bacoor2022a.u_lgupos a inner join bacoor2022a.u_lgupositems b on b.company=a.company and b.branch=a.branch and b.docid=a.docid  and b.u_linetotal > 0 inner join bacoor2022a.u_lgufees c on c.code=b.u_itemcode where a.company = 'LGU' and a.branch = 'MAIN' and a.u_remittancedate between '"+formatDateToDB(getInput("u_datefrom"))+"' AND '"+formatDateToDB(getInput("u_dateto"))+"' and a.u_status not in ('CN','D') and a.u_profitcenter <> 'BPL' and c.code not in ('0008','0009','0010','0019','0014','0015','0016','0017','0454') and c.u_fundtype = '"+getGlobal("branch")+"' "+unionExp+") as x group by u_glacctcode, u_sl");
		if (result.getAttribute("result")!= "-1") {
			if (parseInt(result.getAttribute("result"))>0) {
				for (iii = 0; iii < result.childNodes.length; iii++) {
					data["u_index"] = 1;
					data["u_glacctno"] = result.childNodes.item(iii).getAttribute("u_glacctcode");
					data["u_glacctname"] = result.childNodes.item(iii).getAttribute("u_glacctname");
					data["u_remarks"] = result.childNodes.item(iii).getAttribute("u_sl");
					data["u_debit"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_debit"));
					data["u_credit"] = formatNumericPrice(result.childNodes.item(iii).getAttribute("u_credit"));
					data["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
					insertTableRowFromArray("T1",data);
				}
			}
		} else {
			page.statusbar.showError("Error retrieving miscellaneous collections. Try Again, if problem persists, check the connection.");	
			return false;
		}	
	

	/*
	var result = page.executeFormattedQuery("select u_bank, u_bankacctno, sum(u_amount) as u_amount from u_lgubankdps where company='"+getGlobal("company")+"' and branch='"+getGlobal("branch")+"' and u_date between '"+formatDateToDB(getInput("u_datefrom"))+"' and '"+formatDateToDB(getInput("u_dateto"))+"' and docstatus not in ('D') group by u_bank, u_bankacctno");
	if (result.getAttribute("result")!= "-1") {
		if (parseInt(result.getAttribute("result"))>0) {
			for (iii = 0; iii < result.childNodes.length; iii++) {
				data2["u_bank"] = result.childNodes.item(iii).getAttribute("u_bank");
				data2["u_bankacctno"] = result.childNodes.item(iii).getAttribute("u_bankacctno");
				data2["u_amount"] = formatNumericAmount(result.childNodes.item(iii).getAttribute("u_amount"));
				insertTableRowFromArray("T2",data2);
			}
		}
	} else {
		page.statusbar.showError("Error retrieving bank deposits. Try Again, if problem persists, check the connection.");	
		return false;
	}	
	*/
       
       hideAjaxProcess();
}

