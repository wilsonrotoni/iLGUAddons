<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Sales</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Partial Payment</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",3) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Final Bill</label>&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_scdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_scdisc") ?>>Senior Citizen</label></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_payreftype") ?>>Payment For /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_payrefno") ?>>No.</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_payreftype") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_payrefno","","OpenLnkBtnPayRefNo()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_payrefno") ?>/></td>
	  <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label>&nbsp;<label class="lblobjs">-</label><label <?php $page->businessobject->items->userfields->draw->caption("u_doctime") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/>&nbsp;<input type="text" size="4" <?php $page->businessobject->items->userfields->draw->text("u_doctime") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
		<td  align=left><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;-&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_colltype") ?>>Type of Collection</label></td>
	  <td  align=left>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_colltype") ?>/></td>
	  </tr>
	<tr>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Before Discount</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" style="font-size:18px;height:24px;width:133px;text-align:right;" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code:name",":"),null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_discamount") ?>>Discount</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" style="font-size:18px;height:24px;width:133px;text-align:right;" <?php $page->businessobject->items->userfields->draw->text("u_discamount") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_disccode") ?>>Discount Code</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_disccode",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dueamount") ?>>Total Amount</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" style="font-size:18px;height:24px;width:133px;text-align:right;" <?php $page->businessobject->items->userfields->draw->text("u_dueamount") ?>/></td>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_pricelist") ?>>Price List</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_pricelist",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_creditamount") ?>>Credits</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" style="font-size:18px;height:24px;width:133px;text-align:right;" <?php $page->businessobject->items->userfields->draw->text("u_creditamount") ?>/></td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_otheramount") ?>>Health Benefits</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" style="font-size:18px;height:24px;width:133px;text-align:right;" <?php $page->businessobject->items->userfields->draw->text("u_otheramount") ?>/></td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<?php if ($page->getitemstring("u_trxtype")=="") {?>  
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_creditcardamount") ?>>Credit Card Payment</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" style="font-size:18px;height:24px;width:133px;text-align:right;" <?php $page->businessobject->items->userfields->draw->text("u_creditcardamount") ?>/></td>
	  <td >&nbsp;<label class="lblobjs"><b>Check Payment Detail:</b></label></td>
	  <td align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_checkamount") ?>>Check Payment</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" style="font-size:18px;height:24px;width:133px;text-align:right;" <?php $page->businessobject->items->userfields->draw->text("u_checkamount") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bank") ?>>Bank</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bank",null,null,null,null,"width:148px") ?>/></select></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_recvamount") ?>>Cash Payment /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_chngamount") ?>>Change</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" style="font-size:18px;height:24px;width:133px;text-align:right;" <?php $page->businessobject->items->userfields->draw->text("u_recvamount") ?>/>&nbsp;/&nbsp;<input type="text" size="18" style="font-size:18px;height:24px;width:133px;text-align:right;" <?php $page->businessobject->items->userfields->draw->text("u_chngamount") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_checkno") ?>>Check No.</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_checkno") ?>/></td>
	  </tr>
	<?php } else {?>  
		<input type="hidden" size="18" <?php $page->businessobject->items->userfields->draw->text("u_creditcardamount") ?>/>
		<input type="hidden" size="18" <?php $page->businessobject->items->userfields->draw->text("u_checkamount") ?>/>
		<input type="hidden" size="18" <?php $page->businessobject->items->userfields->draw->text("u_recvamount") ?>/>
		<input type="hidden" size="18" <?php $page->businessobject->items->userfields->draw->text("u_chngamount") ?>/>
	<?php } ?>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_balanceamount") ?>>Due Amount</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" style="font-size:18px;height:24px;width:133px;text-align:right;" <?php $page->businessobject->items->userfields->draw->text("u_balanceamount") ?>/></td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<!--<div class="tabbertab" title="Items">
			<?php $objGrids[1]->draw(true); ?>
		</div> -->
		<div class="tabbertab" title="Health Benefits">
			<?php $objGrids[2]->draw(true); ?>
		</div>
		<?php if ($page->getitemstring("u_trxtype")=="") {?>  
			<div class="tabbertab" title="Credit Card Payment">
				<?php $objGrids[0]->draw(true); ?>
			</div>
		<?php } ?>	
		<div class="tabbertab" title="Credits/Partial Payments/Bills">
			<?php $objGrids[3]->draw(true); ?>
		</div>
		<div class="tabbertab" title="Accounting">
			<div id="divacct" style="overflow-y:auto; overflow-x:auto;" >
				<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr >
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cashierid") ?>>Cashier ID</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_cashierid",array("loaduserbyparam",$page->getitemstring("u_cashierid"),"")) ?>/></select></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr >
					  	<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_ardocno") ?>>A/R No.</label></td>
	  					<td align=left><?php genLinkedButtonHtml("u_ardocno","","OpenLnkBtnDocNo()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ardocno") ?>/></td>
					   	<td width="168">&nbsp;</td>
	  					<td width="168">&nbsp;</td>
				  	</tr>
					<tr >
					<td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_apdocno") ?>>A/P No.</label></td>
	  					<td align=left><?php genLinkedButtonHtml("u_apdocno","","OpenLnkBtnAPInvoices()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_apdocno") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_credited") ?>>Credited</label></td>
	  					<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_credited") ?>/></td>
				  </tr>
					<tr >
					  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_jvdocno") ?>>JV No.</label></td>
	  					<td align=left><?php genLinkedButtonHtml("u_jvdocno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_jvdocno") ?>/></td>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_balance") ?>>Balance</label></td>
	  					<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_balance") ?>/></td>
				  </tr>
					<tr >
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cancelledby") ?>>Cancelled By</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_cancelledby",array("loaduserbyparam",$page->getitemstring("u_cancelledby"),"")) ?>/></select></td>
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
					  					  <td width="168">&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_apcmdocno") ?>>A/P CM No.</label></td>
	  					<td align=left><?php genLinkedButtonHtml("u_apcmdocno","","OpenLnkBtnAPCreditMemos()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_apcmdocno") ?>/></td>

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
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,261); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php if ($page->getitemstring("u_trxtype")=="") $page->resize->addgridobject($objGrids[0],20,430) ?>		
<?php //$page->resize->addgridobject($objGrids[1],20,360) ?>
<?php 	if ($page->getitemstring("u_trxtype")=="") {
			$page->resize->addtab("tab1",-1,391);
			$page->resize->addgridobject($objGrids[2],20,430);
			$page->resize->addgridobject($objGrids[3],20,394);
		} else {
			$page->resize->addtab("tab1",-1,321);
			$page->resize->addgridobject($objGrids[2],20,360);
			$page->resize->addgridobject($objGrids[3],20,324);
		}	
		$page->resize->addtabpage("tab1","acct");
?>		
		