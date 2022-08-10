<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_terminalid") ?>>Terminal ID</label></td>
			<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_terminalid") ?>/></td>
		    <td width="168">&nbsp;</td>
		    <td width="208">&nbsp;</td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cashierid") ?>>User ID</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_cashierid") ?>/></select></td>
		    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_startseqno") ?>>Start Receipt No.</label></td>
		    <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_startseqno") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_opendate") ?>>Open Date/Time</label></td>
			<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_opendate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_opentime") ?>/></td>
		    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_endseqno") ?>>End Receipt No.</label></td>
		    <td>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_endseqno") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_closedate") ?>>Closed Date/Time</label></td>
			<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_closedate") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_closetime") ?>/></td>
		    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_seqnocount") ?>>Total Receipts/Payments</label></td>
		    <td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_seqnocount") ?>/>&nbsp;<label class="lblobjs">/</label>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_rfcount") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_status") ?>>Status</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_status") ?>/></select><?php if (!isEditMode() && $page->getitemstring("u_mode")=="1") {?><a class="button" href="" onClick="formSubmit('?');return false;">Retrieve</a><?php } ?><?php if (isEditMode() && $page->getitemstring("u_mode")=="0" && $page->getitemstring("u_status")!="C") {?>&nbsp;<a class="button" href="" onClick="u_refreshRegisterGPSPOSAsddon();return false;">Retrieve</a><?php } ?></td>
		    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_seqnocount") ?>>Cancelled/Missing</label></td>
		    <td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_cncount") ?>/>&nbsp;<label class="lblobjs">/</label>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_mscount") ?>/></td>
		</tr>
	</table>
</td></tr>	
<tr><td>&nbsp;</td></tr>
<tr><td>
	<div style="display:<?php if (isEditMode() || $page->getitemstring("u_mode")=="1") echo "none"; else echo "block"; ?>">
	<?php $objGrids[0]->draw(true) ?>	  
	</div>
	<div style="display:<?php if (isEditMode() || $page->getitemstring("u_mode")=="1") echo "block"; else echo "none"; ?>">
		<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="380">
					<div class="tabber" id="tab1">
						<div class="tabbertab" title="Payment For">
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td rowspan="13" valign="top"><?php $objGrids[2]->draw(true) ?></td><td>&nbsp;<label class="lblobjs"><b>Cash On Hand:</b></label></td>
		  <td>&nbsp;</td>
		  <td><label class="lblobjs"><b>Payments</b></label></td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td width="158">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_openamount") ?>>Starting Drawer</label></td>
			<td width="138">&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_openamount") ?>/></td>
		    <td width="238">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_cashamount") ?>>Cash</label></td>
			<td width="138">&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_cashamount") ?>/></td>
		</tr>
		<tr>
		  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_incashamount") ?>>Incoming</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_incashamount") ?>/></td>
			<td>&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_checkamount") ?>>Checks</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_checkamount") ?>/></td>
		</tr>
		<tr>
		  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_outcashamount") ?>>Less: Outgoing</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_outcashamount") ?>/></td>
			<td>&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_creditcardamount") ?>>Credit Card</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_creditcardamount") ?>/></td>
		</tr>
		<tr>
		  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_closeamount") ?>>Ending Drawer</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_closeamount") ?>/></td>
			<td>&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
		</tr>
		<tr>
		  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_cashvariance") ?>>Short(+)/Over(-)</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_cashvariance") ?>/></td>
			<td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>&nbsp;<label class="lblobjs"><b>Bank (Cash):</b></label></td>
			<td>&nbsp;</td>
			<td>&nbsp;<label class="lblobjs"><b>Bank (Checks):</b></label></td>
		  <td>&nbsp;</td>
			</tr>
		<tr>
		  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_bankdpamount") ?>>Deposited</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_bankdpamount") ?>/></td>
			<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_bankdp2amount") ?>>Deposited</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_bankdp2amount") ?>/></td>
		</tr>
		
		<tr>
		  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_cashafterbankdp") ?>>Undeposited</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_cashafterbankdp") ?>/></td>
			<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_checkafterbankdp") ?>>Undeposited</label></td>
			<td>&nbsp;<input type="text" size="14"<?php $page->businessobject->items->userfields->draw->text("u_checkafterbankdp") ?>/></td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
	</table>				
						</div>
						<div class="tabbertab" title="Cash">
							<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr><td><?php $objGrids[1]->draw(true) ?></td><td>&nbsp;</td><td><?php $objGrids[3]->draw(false) ?></td></tr>
							</table>	
						</div>	
						<div class="tabbertab" title="Checks">
							<?php $objGrids[4]->draw(false) ?>
						</div>	
						<div class="tabbertab" title="Credit Cards">
							<?php $objGrids[5]->draw(false) ?>						
						</div>	
						<div class="tabbertab" title="Cancelled/Missing">
							<?php $objGrids[6]->draw(false) ?>
						</div>	
						<div class="tabbertab" title="Bank Deposits">
							<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
								  <td>&nbsp;<label class="lblobjs"><b>Cash Deposits:</b></label></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;<label class="lblobjs"><b>Check Deposits:</b></label></td>
							  </tr>
								<tr><td><?php $objGrids[7]->draw(true) ?></td>
									<td>&nbsp;</td>
									<td><?php $objGrids[8]->draw(true) ?></td>
								</tr>
							</table>	
						</div>	
					</div>				</td>
			</tr>
		</table>
	</div>
</td></tr>		
<?php $page->resize->addgridobject($objGrids[0],-1,205) ?>		
<?php $page->resize->addgridobject($objGrids[1],-1,245) ?>		
<?php $page->resize->addgridobject($objGrids[2],-1,210) ?>		
<?php $page->resize->addgridobject($objGrids[3],250,210) ?>	
<?php $page->resize->addgridobject($objGrids[4],15,245) ?>		
<?php $page->resize->addgridobject($objGrids[5],15,245) ?>		
<?php $page->resize->addgridobject($objGrids[6],15,245) ?>		
<?php $page->resize->addgridobject($objGrids[7],-1,265) ?>		
<?php $page->resize->addgridobject($objGrids[8],-1,265) ?>		

