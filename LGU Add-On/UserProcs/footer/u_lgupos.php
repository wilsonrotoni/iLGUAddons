<div <?php genPopWinHDivHtml("popupFramePayments","Payments",10,30,1000,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" height="270">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr>
			<td >
				<div id="divCash" name="divCash">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" >
					<tr><td width="130" ><label <?php $page->businessobject->items->userfields->draw->caption("u_collectedcashamount") ?>>Amount Collected</label></td>
						<td >&nbsp;<input type="text" style="font-size:24px;height:28px;text-align:right;" size="12"   <?php $page->businessobject->items->userfields->draw->text("u_collectedcashamount") ?>/></td>
					</tr>
					<tr><td width="130" ></td>
						<td align="left">&nbsp;</td>
					</tr>
				</table>
				</div>	
				<div id="divCheck" name="divCheck" style="display:none">
					<?php $objGrids[1]->draw(true) ?>	 
				</div>
				<div id="divCreditCard" name="divCreditCard" style="display:none">
					<?php $objGrids[2]->draw(true) ?>	 
				</div>
				<div id="divOther" name="divOther" style="display:none">
				<?php $objGrids[3]->draw(true) ?>	 
				</div>	
				<div id="divBankTransfer" name="divBankTransfer" style="display:none">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" >
					<tr><td width="130" ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfrefno") ?>>Reference No.</label></td>
						<td >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_tfrefno") ?>/></td>
					</tr>
					<tr><td width="130" ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfbank") ?>>Bank</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_tfbank",array("loadhousebanksbycountry",$_SESSION["branch"].":PH",":")) ?>></select></td>
					</tr>
					<tr><td width="130" ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfbankbranch") ?>>Bank Branch</label></td>
						<td >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_tfbankbranch") ?>/></td>
					</tr>
					<tr><td width="130" ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfbankacctno") ?>>Bank Account No.</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_tfbankacctno",array("loadhousebankaccounts",$_SESSION["branch"].":PH:".$page->getitemstring("tfbank"),"")) ?>></select></td>
					</tr>
					<tr><td width="130" ><label <?php $page->businessobject->items->userfields->draw->caption("u_tfamt") ?>>Amount</label></td>
						<td >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_tfamt") ?>/></td>
					</tr>
				</table>
				</div>
				<div id="divDP" name="divDP" style="display:none">
				<?php $objGrids[4]->draw(true) ?>	 
				</div>	
				
			</td>
			<td valign="top" width="320">
				<div>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr><td width="130">&nbsp;<label class="lblobjs">Total Amount</label></td>
							<td >&nbsp;<input type="text" style="font-size:24px;height:28px;text-align:right;" size="12" disabled <?php genInputTextHtml(array("name"=>"totalamountpaymentdisplay")) ?>/></td>
						</tr>
						<tr class="fillerRow5px"><td colspan="2"></td></tr>
						<tr><td ><input type="radio" <?php genInputRadioHtml("paymenttype","Cash") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_cashamount") ?>>Cash</label></td>
							<td >&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_cashamount") ?>/></td>
						</tr>
						<tr class="fillerRow5px"><td colspan="2"></td></tr>
						<tr><td ><input type="radio" <?php genInputRadioHtml("paymenttype","Check") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_chequeamount") ?>>Check</label></td>
							<td >&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_chequeamount") ?>/></td>
						</tr>
						<tr class="fillerRow5px"><td colspan="2"></td></tr>
						<tr><td ><input type="radio" <?php genInputRadioHtml("paymenttype","CreditCard") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_creditcardamount") ?>>Credit Card</label></td>
							<td >&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_creditcardamount") ?>/></td>
						</tr>
						<tr class="fillerRow5px"><td colspan="2"></td></tr>
						<tr><td ><input type="radio" <?php genInputRadioHtml("paymenttype","Other") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_otheramount") ?>>Others</label></td>
							<td >&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_otheramount") ?>/></td>
						</tr>
						<tr class="fillerRow5px"><td colspan="2"></td></tr>
						<tr><td ><input type="radio" <?php genInputRadioHtml("paymenttype","BankTransfer") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_tfamount") ?>>Bank Transfer</label></td>
							<td >&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_tfamount") ?>/></td>
						</tr>
						<tr class="fillerRow5px"><td colspan="2"></td></tr>
						<tr><td ><input type="radio" <?php genInputRadioHtml("paymenttype","Downpayment") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_dpamount") ?>>Customer Deposits</label></td>
							<td >&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_dpamount") ?>/></td>
						</tr>
						<tr class="fillerRow5px"><td colspan="2"></td></tr>
						<tr><td ><input type="checkbox" <?php genInputCheckboxHtml("u_ar",1) ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_aramount") ?>>A/R</label></td>
							<td >&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_aramount") ?>/></td>
						</tr>
						<tr class="fillerRow5px"><td colspan="2"></td></tr>
						<tr><td colspan="2"><hr /></td>
						<tr class="fillerRow5px"><td colspan="2"></td></tr>
						</tr>
						<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_changecashamount") ?>>Change/Balance</label></td>
							<td >&nbsp;<input type="text" style="font-size:24px;height:28px;text-align:right;" size="12"  <?php $page->businessobject->items->userfields->draw->text("u_changecashamount") ?>/></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr ><td colspan="2" align="center"><a class="button" href="" onClick="hidePopupFrame('popupFramePayments');return false;" >Esc - Go Back</a>&nbsp;<a class="button" href="" onClick="formSubmit();return false;" >F9 - Accept Payment</a></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
