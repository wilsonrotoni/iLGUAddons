<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td >&nbsp;</td>
		  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_scdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_scdisc") ?>>Senior Citizen</label>&nbsp;&nbsp;</td>
		  <td colspan="2">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Charge</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Partial Payment</label></td>
	  </tr>
		<td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
		<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable",iif($page->getitemstring("u_trxtype")=="BILLING","u_hissections:code:name","u_hissections:code:name:u_type in ('".$page->getitemstring("u_trxtype")."')"),":")) ?>/></select></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_starttime") ?>/></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
	  <td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_pricelist") ?>>Price List</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pricelist",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_paymentterm") ?>>Payment Term</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_paymentterm") ?>/></select></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_disccode") ?>>Discount</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_disccode",null,null,null,null,"width:148px") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_chargeno") ?>>Charge Invoice No.</label></td>
		<td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_chargeno","","OpenLnkBtnRequestNoGPSHIS()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_chargeno") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_adjby") ?>>Adjusted By</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_adjby",array("loaduserbyparam",$page->getitemstring("u_adjby"),"")) ?>/></select></td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>	
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1-1">
		<div class="tabbertab" title="Items & Services">				
			<?php $objGrids[0]->draw(false) ?>	  
		</div>
		<?php if (isEditMode()) { ?>
		<div class="tabbertab" title="Accounting">
			<div id="divacct" style="overflow-y:auto; overflow-x:auto;" >
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr >
					<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_ardocno") ?>>A/R No.</label></td>
						<td align=left><?php genLinkedButtonHtml("u_ardocno","","OpenLnkBtnARInvoices()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ardocno") ?>/></td>	
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
                    <tr >
                      <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_arcmdocno") ?>>A/R CM No.</label></td>
						<td align=left><?php genLinkedButtonHtml("u_arcmdocno","","OpenLnkBtnARCreditMemos()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_arcmdocno") ?>/></td>	
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
					<tr >
					<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_arcndocno") ?>>A/R Cancel No.</label></td>
						<td align=left><?php genLinkedButtonHtml("u_arcndocno","","OpenLnkBtnARInvoices()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_arcndocno") ?>/></td>	
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
                    <tr >
                      <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_arcmcndocno") ?>>A/R CM Cancel No.</label></td>
						<td align=left><?php genLinkedButtonHtml("u_arcmcndocno","","OpenLnkBtnARCreditMemos()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_arcmcndocno") ?>/></td>	
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
		<?php } ?>
	</div>		
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	
	
	<tr class="fillerRow5px">
	  <td></td>
	  <td ></td>
	  <td></td>
	  <td  align=left></td>
	  </tr>
	<tr >
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
	  <td rowspan="3" valign="top">&nbsp;<textarea cols="32" rows="2" <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks"); ?></textarea></td>
	  <td width="138"><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Total Adjustment</label></td>
				<td  width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>	
	</tr>
	<tr >
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>	
	</tr>
	<tr >
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	</table>
</td></tr>		

<?php 
	$page->resize->addtab("tab1",-1,307);
	if (isEditMode()) $page->resize->addtabpage("tab1","acct");
	$page->resize->addgridobject($objGrids[0],30,311);
?>

