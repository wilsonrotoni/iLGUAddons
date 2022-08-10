<?php  if ($edtopt=="testpreview") { ?>
<input type="hidden" <?php genInputHiddenDFHtml("u_scdisc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_isstat") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_prepaid") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_department") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("docseries") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("docno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_reftype") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_refno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_patientid") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_patientname") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("docstatus") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_reqdate") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_reqtime") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_birthdate") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_paymentterm") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_disccode") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_pricelist") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_startdate") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_starttime") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_requestno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_doctorid") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_specimen") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_specimendate") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_specimentime") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_enddate") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_endtime") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_testby") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_doctorid2") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_quantity") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_unitprice") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_statperc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_statunitprice") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_price") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_vatamount") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_amount") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_payrefno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_pfamount") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_pfperc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_pfdiscamount") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_pfdiscperc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_pfnetamount") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_pfdiscperc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_pfapno") ?> >
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
	<tr>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_type") ?>>Type of Test</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_type") ?>/></select></td>
	  <td width="168" >&nbsp;</td>
		<td width="168"  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_template") ?>>Template</label></td>
	  <td  align=left>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_template") ?>/></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>/></select></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age</label></td>
	  <td  align=left>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Test Cases">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,261); ?>
<?php $page->resize->addgridobject($objGrids[0],20,164) ?>		
<?php } else { ?>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td >&nbsp;</td>
		  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_scdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_scdisc") ?>>Senior Citizen</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_isstat",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isstat") ?>>Stat</label></td>
		  <td colspan="2">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Charged</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Downpayment</label></td>
	  </tr>
		<td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
		<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code:name:u_type in ('XRAY','CTSCAN','ULTRASOUND','RADIOLOGY','LABORATORY','HEARTSTATION')",":")) ?>/></select></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_fileno") ?>>File No.</label></td>
				<td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_fileno") ?>/></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reqdate") ?>>Test Requested</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_reqdate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_reqtime") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_birthdate") ?>>Birth /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>/></select>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_birthdate") ?>/>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Request Received</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_starttime") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Requesting Physician</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_specimen") ?>>Specimen</label></td>
						<td >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_specimen") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_type") ?>>Type of Test/</label><label <?php $page->businessobject->items->userfields->draw->caption("u_template") ?>>Template</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_type") ?>/></select>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_template") ?>/></td>
	 <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_specimendate") ?>>Submitted On</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_specimendate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_specimentime") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_payrefno") ?>>O.R. No.</label></td>
	  <td width="168"  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_payrefno") ?>/></td>
	  
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="General">
			<div id="divgen" style="overflow-y:auto; overflow-x:auto;" >
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					
					<tr class="fillerRow5px">
					  <td width="188" ></td>
					  <td align=left></td>
					  <td ></td>
					  <td width="168" ></td>
				  </tr>
					<tr >
					  <td >&nbsp;<label class="lblobjs"><b>Result:</b></label></td>
					  <td align=left>&nbsp;</td>
					  <td >&nbsp;<label class="lblobjs"><b>Reader's Fee:</b></label></td>
					  <td >&nbsp;</td>
				  </tr>
					<tr >
					  <td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_enddate") ?>>Date Verified</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_endtime") ?>/></td>
					  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_pfamount") ?>>Fee</label></td>
				<td >&nbsp;<input type="text" size="11" <?php $page->businessobject->items->userfields->draw->text("u_pfamount") ?>/>&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_pfperc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_pfperc") ?>>%</label></td>
				  </tr>
					<tr >
					  <td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_testby") ?>>Medical Technologist</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_testby",array("loadudflinktable","users:userid:username:(u_medtechflag=1 and ''='".$page->getitemstring("u_trxtype")."' or (u_radtechflag=1 and '".$page->getitemstring("u_trxtype")."' in ('XRAY','ULTRASOUND','CTSCAN','RADIOLOGY'))) or (u_cardiacsonographerflag=1 and 'HEARTSTATION'='".$page->getitemstring("u_trxtype")."')",":")) ?>/></select></td>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_pfdiscamount") ?>>Discount</label></td>
				<td >&nbsp;<input type="text" size="11" <?php $page->businessobject->items->userfields->draw->text("u_pfdiscamount") ?>/>&nbsp;<input type="text" size="3" <?php $page->businessobject->items->userfields->draw->text("u_pfdiscperc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_pfdiscperc") ?>>%</label></td>
				  </tr>
					<tr >
					  <td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid2") ?>>Phatologist</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid2",array("loadudflinktable","u_hisdoctors:code:name:(u_pathologistflag=1 and ''='".$page->getitemstring("u_trxtype")."' or (u_radiologistflag=1 and '".$page->getitemstring("u_trxtype")."' in ('XRAY','ULTRASOUND','CTSCAN','RADIOLOGY'))) or (u_cardiologistflag=1 and 'HEARTSTATION'='".$page->getitemstring("u_trxtype")."')",":")) ?>/></select></td>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_pfnetamount") ?>>Net Amount</label></td>
				<td >&nbsp;<input type="text" size="11" <?php $page->businessobject->items->userfields->draw->text("u_pfnetamount") ?>/></td>
				  </tr>
					<tr >
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_pfapno") ?>>A/P No.</label></td>
				<td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_pfapno") ?>/></td>
				  </tr>
				</table>			
			</div>	
		</div>
		<div class="tabbertab" title="Test Cases">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Notes">
			<textarea cols="120" rows="8" <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks"); ?></textarea>
		</div>		
		<div class="tabbertab" title="Attachments">	
			<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
				  <tr >
					<td width="300" valign="top"><?php $objGrids[1]->draw() ?></td>
					<td width="5" valign="top">&nbsp;</td>
					<td valign="top">
						<div id="divPictures" style="overflow-y:auto; overflow-x:auto;" >
							<img id="PictureImg" src="" align="absmiddle" border=1 alt="" onDblClick="uploadAttachment()" style="display:none">
							<video id="video" name="video" src="" controls width="100%" height="100%" style="display:none">Your browser does not support the <code>video</code> element.</video>
							<object id="contentarea" name="contentarea" data="" type="application/pdf" width="100%" height="100%" style="display:none"><p>It appears you don't have a PDF plugin for this browser.
  No biggie... you can <a href="myfile.pdf">click here to download the PDF file.</a></p></object>
						</div>		
					</td>
				  </tr>
			</table>
		</div>
		<div class="tabbertab" title="Sub-Tests">
			<?php $objGrid->draw(false); ?>
		</div>		
		<div class="tabbertab" title="Re-Open Remarks">	
			<?php $objGrids[2]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,261); ?>
<?php //$page->resize->addtabpage("tab1","pf") ?>
<?php //$page->resize->addtab("tab1-1",-1,305); ?>
<?php $page->resize->addtabpage("tab1","gen") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php $page->resize->addinput("u_remarks",45,270); ?>
<?php //$page->resize->addtabpage("tab1","tab1Editor") ?>
<?php $page->resize->addgridobject($objGrids[0],20,264) ?>		
<?php $page->resize->addgridobject($objGrids[1],-1,278) ?>		
<?php $page->resize->addgridobject($objGrids[2],20,264) ?>		
<?php $page->resize->addelement("divPictures",362,262) ?>
<?php //$page->resize->addelement("iframeRemarks",35,281) ?>		
<?php $page->resize->addgridobject($objGrid,20,278) ?>
<?php } ?>		


