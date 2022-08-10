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
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_testtype") ?>>Type of Test</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_testtype") ?>/></select></td>
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
		  <td colspan="2">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Charge</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Partial Payment</label></td>
          <td width="168" align=left>&nbsp;</td>
	  </tr>
		<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
		<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code:name:u_type <> ''",":")) ?>/></select></td>
		<td width="118" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	    <td width="168" rowspan="5" align="center" valign="top"><img id="PhotoImg" height=110 src="<?php echo $photopath; ?>" width=138 align="absmiddle" border=1 ></td>
	  </tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_starttime") ?>/></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_pricelist") ?>>Price List</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pricelist",null,null,null,null,"width:148px") ?>/></select></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_paymentterm") ?>>Payment Term</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_paymentterm") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_requestno") ?>>Request No.</label></td>
		<td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_requestno","","OpenLnkBtnu_hisrequests()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_requestno") ?>/></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_disccode") ?>>Discount</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_disccode",null,null,null,null,"width:148px") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_payrefno") ?>>O.R. No.</label></td>
			  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_payrefno") ?>/></td>	
	</tr>
    <?php 	if ($page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="HEARTSTATION") { } else { ?>
    
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Doctor</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid",null,null,null,null,"width:148px") ?>/></select></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td align="center" valign="top">&nbsp;</td>
	  </tr>
      <?php } ?>
	</table>
</td></tr>	
<tr><td>
	<?php 	if ($page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="HEARTSTATION") { ?>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Charges">
	<?php } ?>			
			<div class="tabber" id="tab1-1">
				<div class="tabbertab" title="Items & Services">				
					<?php $objGrids[3]->draw(true) ?>	  
				</div>
				<div class="tabbertab" title="Package Items">				
					<?php $objGrids[4]->draw(true) ?>	  
				</div>
				<div class="tabbertab" title="Accounting">
					<div id="divacct" style="overflow-y:auto; overflow-x:auto;" >
						<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr >
								<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_ardocno") ?>>A/R No.</label></td>
								<td align=left><?php genLinkedButtonHtml("u_ardocno","","OpenLnkBtnARInvoices()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ardocno") ?>/></td>
								<td width="168">&nbsp;</td>
								<td width="168">&nbsp;</td>
							</tr>
							<tr >
							<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dndocno") ?>>Delivery No.</label></td>
								<td align=left><?php genLinkedButtonHtml("u_dndocno","","OpenLnkBtnSalesDeliveries()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_dndocno") ?>/></td>
							  <td width="168">&nbsp;</td>
								<td width="168">&nbsp;</td>
						  </tr>
							<tr >
							<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_arcmdocno") ?>>A/R CM No.</label></td>
								<td align=left><?php genLinkedButtonHtml("u_arcmdocno","","OpenLnkBtnARCreditMemos()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_arcmdocno") ?>/></td>	
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
						  </tr>
							<tr >
												  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_rtdocno") ?>>Return No.</label></td>
								<td align=left><?php genLinkedButtonHtml("u_rtdocno","","OpenLnkBtnSalesReturns()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_rtdocno") ?>/></td>
		
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
						  </tr>
							<tr >
							  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_billno") ?>>Bill No.</label></td>
								<td align=left>&nbsp;<input type="text" size="18" disabled <?php $page->businessobject->items->userfields->draw->text("u_billno") ?>/></td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
						  </tr>
						</table>
					</div>		
				</div>				
				<?php if ($page->getitemstring("docstatus")=="CN") { ?>
				<div class="tabbertab" title="Cancellation Info">
					<div id="divcancel" style="overflow-y:auto; overflow-x:auto;" >
						<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr >
							  	<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cancelledby") ?>>Cancelled By</label></td>
			  					<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_cancelledby",array("loaduserbyparam",$page->getitemstring("u_cancelledby"),"")) ?>/></select></td>
							  	<td>&nbsp;</td>
							  	<td>&nbsp;</td>
						  	</tr>
							<tr >
							  	<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_cancelledreason") ?>>Reason</label></td>
								<td align=left>&nbsp;<input type="text" size="70" <?php $page->businessobject->items->userfields->draw->text("u_cancelledreason") ?>/></td>
							  	<td>&nbsp;</td>
							  	<td>&nbsp;</td>
						  	</tr>
							<tr >
							  	<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_cancelledremarks") ?>>Remarks</label></td>
								<td align=left>&nbsp;<input type="text" size="70" <?php $page->businessobject->items->userfields->draw->text("u_cancelledremarks") ?>/></td>
							  	<td>&nbsp;</td>
							  	<td>&nbsp;</td>
						  	</tr>
						</table>
					</div>		
				</div>				
				<?php } ?>
			</div>		
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
				
				
				<tr class="fillerRow5px">
				  <td></td>
				  <td ></td>
				  <td ></td>
				  <td ></td>
				  <td></td>
				  <td  align=left></td>
				  </tr>
				<tr >
				 <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_chargeby") ?>>Charged/Rendered By</label></td>
	  				<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_chargeby",array("loaduserbyparam",$page->getitemstring("u_chargeby"),""),null,null,null,"width:238px") ?>/></select></td>
				  <td width="138"><label <?php $page->businessobject->items->userfields->draw->caption("u_amountbefdisc") ?>>Total Before Disc</label></td>
				  <td  align=left  width="138">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_amountbefdisc") ?>/></td>
				  <td width="118"><label <?php $page->businessobject->items->userfields->draw->caption("u_vatamount") ?>>VAT Amount</label></td>
				  <td  align=left  width="168">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_vatamount") ?>/></td>
				</tr>
				<tr >
				  <td >&nbsp;</td>
				  <td >&nbsp;</td>
				  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_discamount") ?>>Discount</label></td>
				<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_discamount") ?>/></td>
				  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Total Amount</label></td>
				<td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>			
				</tr>
				</table>
		</div>
		<?php if ($page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="HEARTSTATION") { ?>
			<div class="tabbertab" title="Examination">
				<div id="divexam" style="overflow-y:none; overflow-x:none;" >
			<div class="tabber" id="tab1-2">
					<div class="tabbertab" title="Summary">	
						<div id="divgen" style="overflow-y:auto; overflow-x:auto;" >
							<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr >
								  <td >&nbsp;<label class="lblobjs"><b>Request:</b></label></td>
				  <td  align=left>&nbsp;</td>
								   <td width="168">&nbsp;<label class="lblobjs"><b>Samples:</b></label></td>
		  <td width="168">&nbsp;</td>
							  </tr>
								<tr >
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_reqdate") ?>>Date</label></td>
		  <td >&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_reqdate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_reqtime") ?>/></td>
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_specimendate") ?>>Specimen</label></td>
				  <td align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_specimen") ?>/></td>
							  </tr>
								<tr >
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid") ?>>Internal Physician</label></td>
				  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid") ?>/></select></td>
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_specimendate") ?>>Submitted On</label></td>
				  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_specimendate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_specimentime") ?>/></td>
							  </tr>
								<tr >
									<td width="188">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_exdoctorname") ?>>External Physician</label></td>
		  <td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_exdoctorname") ?>/></td>
									<td  >&nbsp;</td>
									<td >&nbsp;</td>
								</tr>
								<tr >
									<td width="188">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_testtype") ?>>Type of Test/</label><label <?php $page->businessobject->items->userfields->draw->caption("u_template") ?>>Template</label></td>
		  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_testtype") ?>/></select>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_template") ?>/></td>
									<td >&nbsp;</td>
				  <td align=left>&nbsp;</td>
								</tr>
								<tr class="fillerRow5px">
								  <td ></td>
								  <td align=left></td>
								  <td ></td>
								  <td ></td>
							  </tr>
								<tr >
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_birthdate") ?>>Birth /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age</label></td>
		  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>/></select>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_birthdate") ?>/>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/></td>
								  <td width="168" >&nbsp;<label class="lblobjs"><b>Reader's Fee:</b></label></td>
							<td >&nbsp;</td>
							  </tr>
								<tr >
								  <td >&nbsp;<label class="lblobjs"><b>Result:</b></label></td>
								  <td align=left>&nbsp;</td>
								  <td width="168" >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_pfamount") ?>>Fee</label></td>
							<td >&nbsp;<input type="text" size="11" <?php $page->businessobject->items->userfields->draw->text("u_pfamount") ?>/>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_pfperc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_pfperc") ?>>%</label></td>
							  </tr>
								<tr >
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_enddate") ?>>Date Verified</label></td>
				  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_endtime") ?>/></td>
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_pfdiscamount") ?>>Discount</label></td>
							<td >&nbsp;<input type="text" size="11" <?php $page->businessobject->items->userfields->draw->text("u_pfdiscamount") ?>/>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_pfdiscperc") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_pfdiscperc") ?>>%</label></td>
							  </tr>
								<tr >
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_testby") ?>>Medical Technologist</label></td>
				  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_testby",array("loadudflinktable","users:userid:username:(u_medtechflag=1 and 'LABORATORY'='".$page->getitemstring("u_trxtype")."' or (u_radtechflag=1 and '".$page->getitemstring("u_trxtype")."' in ('XRAY','ULTRASOUND','CTSCAN','RADIOLOGY'))) or (u_cardiacsonographerflag=1 and 'HEARTSTATION'='".$page->getitemstring("u_trxtype")."')",":")) ?>/></select></td>
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_pfnetamount") ?>>Net Amount</label></td>
							<td >&nbsp;<input type="text" size="11" <?php $page->businessobject->items->userfields->draw->text("u_pfnetamount") ?>/></td>
							  </tr>
								<tr >
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_doctorid2") ?>>Phatologist</label></td>
				  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctorid2",array("loadudflinktable","u_hisdoctors:code:name:(u_pathologistflag=1 and 'LABORATORY'='".$page->getitemstring("u_trxtype")."' or (u_radiologistflag=1 and '".$page->getitemstring("u_trxtype")."' in ('XRAY','ULTRASOUND','CTSCAN','RADIOLOGY'))) or (u_cardiologistflag=1 and 'HEARTSTATION'='".$page->getitemstring("u_trxtype")."')",":")) ?>/></select></td>
								  <td >&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_pfapno") ?>>A/P No.</label></td>
							<td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_pfapno") ?>/></td>
							  </tr>
								<tr >
								  <td width="188">&nbsp;&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_fileno") ?>>File No.</label></td>
		  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_fileno") ?>/></td>
								  <td >&nbsp;</td>
								  <td >&nbsp;</td>
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
			<div class="tabbertab" title="RTF Notes">
				<div id="divtab1Editor"  style=" overflow:scroll">
					<div id="divEditor" name="divEditor" class="editable"><?php echo $page->getitemstring("u_remarks_rtf") ?></div>
				</div>	
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
		</div>
		<?php } if ($page->getitemstring("u_trxtype")=="LABORATORY") { ?>
				<input type="hidden" <?php $page->businessobject->items->userfields->draw->text("u_reqdate") ?>/>
				<input type="hidden" <?php $page->businessobject->items->userfields->draw->text("u_reqtime") ?>/>
				<input type="hidden" <?php $page->businessobject->items->userfields->draw->text("u_specimen") ?>/>
				<input type="hidden" <?php $page->businessobject->items->userfields->draw->text("u_doctorid") ?>/>
				<input type="hidden" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/>
				<input  type="hidden" <?php $page->businessobject->items->userfields->draw->text("u_gender") ?>/>
				<input type="hidden" <?php $page->businessobject->items->userfields->draw->text("u_birthdate") ?>/>
		<?php } ?>	
	<?php 	if ($page->getitemstring("u_trxtype")=="LABORATORY" || $page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="HEARTSTATION") { ?>

	</div>
<?php } ?>
</td></tr>		

<?php 
	$page->resize->addtab("tab1",-1,284);
	if ($page->getitemstring("u_trxtype")=="RADIOLOGY" || $page->getitemstring("u_trxtype")=="HEARTSTATION") {
		$page->resize->addtabpage("tab1","exam");

		$page->resize->addtab("tab1-2",-1,268);
		$page->resize->addtabpage("tab1-2","gen");

		$page->resize->addtab("tab1-1",-1,317);
		$page->resize->addtabpage("tab1-1","acct");
		if ($page->getitemstring("docstatus")=="CN") $page->resize->addtabpage("tab1-1","cancel");

		//$page->resize->addtabpage("tab1","tab1Pictures")
		$page->resize->addinput("u_remarks",45,270);
		$page->resize->addtabpage("tab1-1","tab1Editor");
		$page->resize->addgridobject($objGrids[0],30,269);
		$page->resize->addgridobject($objGrids[1],-1,283);
		$page->resize->addgridobject($objGrids[2],30,269);
		$page->resize->addelement("divPictures",372,267);
		//$page->resize->addelement("iframeRemarks",35,281);
		$page->resize->addgridobject($objGrid,30,283);
		$page->resize->addgridobject($objGrids[3],30,356);
		$page->resize->addgridobject($objGrids[4],30,356);
	} else {
		$page->resize->addtab("tab1-1",-1,310);
		$page->resize->addtabpage("tab1-1","acct");
		if ($page->getitemstring("docstatus")=="CN") $page->resize->addtabpage("tab1-1","cancel");
		//$page->resize->addgridobject($objGrids[3],30,346);
		//$page->resize->addgridobject($objGrids[4],30,346);
		if ($page->getitemstring("u_trxtype")=="LABORATORY") {
			$page->resize->addgridobject($objGrids[4],30,325);
		} else {
			$page->resize->addgridobject($objGrids[4],30,325);
		}	
	}
?>

<?php } ?>		


