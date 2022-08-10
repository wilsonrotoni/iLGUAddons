<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="19" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reg Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><select <?php $page->businessobject->items->userfields->draw->select("u_reftype",null,null,null,null,"width:112px") ?>/></select>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Reg Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
		<td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Bill Date /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_doctime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_doctime") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Summary">	
			<div class="tabber" id="tab1-1">
				<div class="tabbertab" title="By Transactions">	
					<?php $objGrids[10]->draw(false) ?>	 
				</div>	 
				<div class="tabbertab" title="By Sections">	
					<?php $objGrids[8]->draw(true) ?>	 
				</div>	 
				<div class="tabbertab" title="By Fees">	
					<?php $objGrids[9]->draw(true) ?>	 
				</div>	 
				<?php if (isEditMode() && $page->getitemstring("docstatus")!="D") { ?>
				<div class="tabbertab" title="Accounting">
					<div id="divacct" style="overflow-y:auto; overflow-x:auto;" >
						<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr >
							  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_billby") ?>>Billed By</label></td>
			  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_billby",array("loaduserbyparam",$page->getitemstring("u_billby"),"")) ?>/></select></td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
						  </tr>
							<tr >
							  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_jvdocno") ?>>JV No.</label></td>
								<td align=left><?php genLinkedButtonHtml("u_jvdocno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_jvdocno") ?>/></td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
						  </tr>
							<tr >
							  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cancelledby") ?>>Cancelled By</label></td>
			  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_cancelledby",array("loaduserbyparam",$page->getitemstring("u_cancelledby"),"")) ?>/></select></td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
						  </tr>
							<tr >
							  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_jvcndocno") ?>>JV Cancel No.</label></td>
								<td align=left><?php genLinkedButtonHtml("u_jvcndocno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_jvcndocno") ?>/></td>
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
							<tr >
							  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_balance") ?>>Balance</label></td>
								<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_balance") ?>/></td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
						  </tr>
							<tr >
							  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_credited") ?>>Credited</label></td>
								<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_credited") ?>/></td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
						  </tr>
						</table>
					</div>		
				</div>				
				<?php } ?>
			</div>	
		</div>
		<div class="tabbertab" title="Rooms">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Special Rooms">	
			<?php $objGrids[5]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Examinations">	
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Consultations">	
			<?php $objGrids[2]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Medicines & Supplies">	
			<?php $objGrids[3]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Miscellaneous">	
			<?php $objGrids[4]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Health Benefits">	
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr><td><?php $objGrids[6]->draw(true) ?></td>
					<td width="18">
						<div><a class="button2" href="" onClick="u_moveRowUpHealthBenefitsGPSHIS();return false;" ><img src="imgs/asc_<?php echo $_SESSION["theme"] ?>.gif" border="0"></a></div>
						<div><a class="button2" href="" onClick="u_moveRowDnHealthBenefitsGPSHIS();return false;" ><img src="imgs/desc_<?php echo $_SESSION["theme"] ?>.gif" border="0"></a></div>
					</td>
				</tr>
			</table>			
		</div>
		<div class="tabbertab" title="DM/CM/PN">	
			<?php $objGrids[7]->draw(false) ?>	  
		</div>
	</div>
</td></tr>		
<tr class="fillerRow5px"><td ></td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td width="176" ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
		<td  valign="top" rowspan="3">&nbsp;<textarea cols="47" rows="2" <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks"); ?></textarea></td>
		<td width="112" ><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Actual Charges</label></td>
	  <td width="118" align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>
		<td  width="112" ><label <?php $page->businessobject->items->userfields->draw->caption("u_pnamount") ?>>Collector</label></td>
	  <td width="118" align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_pnamount") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_discamount") ?>>Discount</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_discamount") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_netamount") ?>>Net Charges</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_netamount") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_insamount") ?>>Insurance Benefits</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_insamount") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dueamount") ?>>Due Amount</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_dueamount") ?>/></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_dpamt") ?>>Credits/Partial Payments</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_dpamt") ?>/><input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_dpcr") ?>/><input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_dpbal") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_hmoamount") ?>>HMO/LGU/Co</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_hmoamount") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_paidamount") ?>>Paid</label></td>
	  <td align=left>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_paidamount") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") 
/*
		
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td width="168"></td>
						<td></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_patientname") ?>>Patient Name</label></td>
						<td>&nbsp;</td>
					</tr>
					
					<tr><td width="168"></td>
						<td></td>
					</tr>
					
					<tr><td width="168"></td>
						<td >&nbsp;</td>
					</tr>
				</table>
			</div>
		</div>

*/?>
<?php $page->resize->addgridobject($objGrids[0],20,245) ?>		
<?php $page->resize->addgridobject($objGrids[1],20,245) ?>		
<?php $page->resize->addgridobject($objGrids[2],20,245) ?>		
<?php $page->resize->addgridobject($objGrids[3],20,245) ?>		
<?php $page->resize->addgridobject($objGrids[4],20,245) ?>		
<?php $page->resize->addgridobject($objGrids[5],20,244) ?>		
<?php //$page->resize->addgridobject($objGrids[6],20,322) ?>		
<?php $page->resize->addgridobject($objGrids[6],38,281) ?>		
<?php $page->resize->addgridobject($objGrids[7],20,259) ?>		
<?php $page->resize->addgridobject($objGrids[8],30,289) ?>		
<?php $page->resize->addgridobject($objGrids[9],30,289) ?>		
<?php $page->resize->addgridobject($objGrids[10],30,303) ?>		
<?php $page->resize->addtab("tab1-1",-1,286); ?>
<?php if (isEditMode() && $page->getitemstring("docstatus")!="D") $page->resize->addtabpage("tab1-1","acct"); ?>