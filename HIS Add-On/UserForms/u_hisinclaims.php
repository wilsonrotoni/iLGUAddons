<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_billno") ?>>Bill No.</label></td>
	  <td align=left colspan="2">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_billno") ?>/></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_inscode") ?>>Health Benefit</label></td>
	  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_inscode") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
		<td  align=left ><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;-</td>
		<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	    <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_icdcode") ?>>ICD</label></td>
		<td  align=left width="140">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_icdcode") ?>/>&nbsp;-</td>
	    <td  align=left rowspan="3" valign="top">&nbsp;<textarea name="textarea" cols="48" rows="2" <?php $page->businessobject->items->userfields->draw->text("u_icddesc") ?>/><?php echo $page->getitemstring("u_icddesc") ?></textarea></td>
	    <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_rvscode") ?>>RVS</label></td>
		<td  align=left valign="top">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_rvscode") ?>/>&nbsp;-</td>
	    <td  align=left rowspan="3" valign="top">&nbsp;<textarea name="textarea" cols="48" rows="2" <?php $page->businessobject->items->userfields->draw->text("u_rvsdesc") ?>/><?php echo $page->getitemstring("u_rvsdesc") ?></textarea></td>
	    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_rvu") ?>>RVU</label></td>
		<td  align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_rvu") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_discalloc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_discalloc") ?>>Enter benefit as %</label></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Case Type">	
			<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				<tr>
				  <td  align=left><select <?php $page->businessobject->items->userfields->draw->select("u_casetype",array("loadudflinktable","u_hishealthincts:code:name:u_inscode='".$page->getitemstring("u_inscode")."'",":")) ?>/></select></td>
			  </tr>
			</table>
			<?php $objGrids[0]->draw(true) ?>	  

		</div>
		<div class="tabbertab" title="Room & Board">	
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Drugs & Medicines">	
			<?php $objGrids[2]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="X-Ray, Lab & Others">	
			<?php $objGrids[3]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Operation Room">	
			<?php $objGrids[4]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Professional Fees">	
			<?php $objGrids[5]->draw(true) ?>	  
		</div>
		<?php if (isEditMode() && $page->getitemstring("docstatus")!="D") { ?>
		<div class="tabbertab" title="Accounting">
			<div id="divacct" style="overflow-y:auto; overflow-x:auto;" >
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr >
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_postedby") ?>>Posted By</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_postedby",array("loaduserbyparam",$page->getitemstring("u_postedby"),"")) ?>/></select></td>
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
				</table>
			</div>		
		</div>				
		<?php } ?>		
	</div>
</td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
		<td  align=left rowspan="2" valign="top">&nbsp;<textarea name="textarea" cols="48" rows="1" <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks") ?></textarea></td>
		<td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Actual Charges</label></td>
	  <td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_thisamount") ?>>This Benefits</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_thisamount") ?>/></td>
	  </tr>
	
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_xmtalno") ?>>Transmittal No./Date</label></td>
	  <td  align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_xmtalno") ?>/>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_xmtaldate") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_netamount") ?>>Net Charges</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_netamount") ?>/></td>
	  </tr>
	</table>
</td></tr>	
	
<?php $page->resize->addgridobject($objGrids[0],20,385) ?>		
<?php $page->resize->addgridobject($objGrids[1],20,365) ?>		
<?php $page->resize->addgridobject($objGrids[2],20,365) ?>		
<?php $page->resize->addgridobject($objGrids[3],20,365) ?>		
<?php $page->resize->addgridobject($objGrids[4],20,365) ?>		
<?php $page->resize->addgridobject($objGrids[5],20,365) ?>		
<?php //$page->resize->addgridobject($objGrids[6],20,322) ?>		
<?php //$page->resize->addgridobject($objGrids[6],20,285) ?>	
<?php $page->resize->addtab("tab1",-1,342); ?>	
<?php if (isEditMode() && $page->getitemstring("docstatus")!="D") $page->resize->addtabpage("tab1","acct"); ?>