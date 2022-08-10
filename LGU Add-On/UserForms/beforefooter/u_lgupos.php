<div <?php genPopWinHDivHtml("popupFrameFreebies","Freebies",10,30,890,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
<td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr class="fillerRow5px"><td colspan="3"></td></tr>
	<tr><td width="10">&nbsp;</td>
	<td>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td width="128"><label <?php genCaptionHtml(createSchema("u_itemcode"),"T100") ?> >Item Code</label></td>
				<td >&nbsp;<input type="text" disabled <?php genInputTextHtml(createSchema("u_itemcode"),"T100") ?> /></td>
			</tr>
			<tr><td width="128"><label <?php genCaptionHtml(createSchema("u_itemdesc"),"T100") ?> >Item Description</label></td>
				<td >&nbsp;<input type="text" disabled size="70" <?php genInputTextHtml(createSchema("u_itemdesc"),"T100") ?> /></td>
			</tr>
			<tr><td width="128"><label <?php genCaptionHtml(createSchema("u_promocode"),"T100") ?> >Promo Code</label></td>
				<td >&nbsp;<input type="text" disabled <?php genInputTextHtml(createSchema("u_promocode"),"T100") ?> /></td>
			</tr>
			<tr><td width="128"><label <?php genCaptionHtml(createSchema("u_freebielimit"),"T100") ?> >Freebie Limit</label></td>
				<td >&nbsp;<input type="text" disabled <?php genInputTextHtml(createSchemaAmount("u_freebielimit"),"T100") ?> /></td>
			</tr>
			<tr><td width="128"><label <?php genCaptionHtml(createSchema("u_freebieamount"),"T100") ?> >Freebie Amount</label></td>
				<td >&nbsp;<input type="text" disabled <?php genInputTextHtml(createSchemaAmount("u_freebieamount"),"T100") ?> /><input type="hidden" disabled <?php genInputTextHtml(createSchema("u_row"),"T100") ?> /></td>
			</tr>
		</table>
	</td>
	<td width="10">&nbsp;</td>
	</tr>
	</table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td colspan="2"><?php $objGridFreebies->draw(true); ?></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td width="10">&nbsp;</td>
	<td>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td >&nbsp;</td>
				<td align="right"><a class="button" href="" onClick="setFreebiesGPSPOS();return false;" >OK</a></td>
			</tr>
			<tr class="fillerRow5px"><td colspan="2"></td></tr>
		</table>
	</td>
	<td width="10">&nbsp;</td>
	</tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameTotalAmount","Discount By Total",10,30,290,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount2") ?>>Total Amount</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_totalamount2") ?>/></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameQueue","Queue",900,60,230,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_queue") ?>>Current Number</label></td>
			<td >&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_queue") ?>/></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
        <tr><td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_autoqueue",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_autoqueue") ?>>Auto</label></td>
            <td align="right"><a class="button" href="" onClick="getNextQueGPSPOS();return false;" >Get Next Number</a></td>
        </tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameCTCApplication","Community Tax Certificate Application",10,30,400,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr>
                    <td width="160px">&nbsp;</td>
                    <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_ctctype", "I") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_ctctype") ?>>Individual</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_ctctype", "C") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_ctctype") ?>>Corporation</label></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcgender") ?>>Gender</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_ctcgender", null, null, null, null, "width:158px") ?>/></select></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctccivilstatus") ?>>Civil Status</label></td>
                    <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_ctccivilstatus", null, null, null, null, "width:158px") ?>/></select></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctccitizenship") ?>>Citizenship</label></td>
                    <td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ctccitizenship") ?>/></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcpob") ?>>Place of Birth</label></td>
                    <td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ctcpob") ?>/></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcdob") ?>>Date of Birth</label></td>
                    <td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ctcdob") ?>/></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcoccupation") ?>>Occupation</label></td>
                    <td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ctcoccupation") ?>/></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcgross") ?>>Gross Amount</label></td>
                    <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_ctcgross") ?>/></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
        <tr><td >&nbsp;<a class="button" href="" onClick="setCTCFees();return false;" >Continue</a></td>
            <td align="left"></td>
        </tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameTransferTaxApplication","Transfer Tax Application",10,30,400,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ttdosdate") ?>>Deed of Sale Date</label></td>
                    <td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ttdosdate") ?>/></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_tttctnumber") ?>>TCT Number</label></td>
                    <td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_tttctnumber") ?>/></td>
		</tr>
		<tr>
                    <td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_tttdno") ?>>TD Number</label></td>
                    <td>&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_tttdno") ?> rows="2" cols="25"><?php echo $page->getitemstring("u_tttdno"); ?></TEXTAREA></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ttsqm") ?>>Area</label></td>
                    <td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ttsqm") ?>/></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ttlotno") ?>>Lot No</label></td>
                    <td >&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ttlotno") ?>/></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ttgross") ?>>Value Amount</label></td>
                    <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_ttgross") ?>/></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
        <tr><td >&nbsp;<a class="button" href="" onClick="setTranserTaxFees();return false;" >Continue</a></td>
            <td align="left"></td>
        </tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameFranchiseTaxApplication","Franchise Tax Application",10,30,400,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ftduedate") ?>>Due Date</label></td>
                    <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_ftduedate") ?>/></td>
		</tr>
		<tr>
                    <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ftgross") ?>>Capital/Gross Amount</label></td>
                    <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_ftgross") ?>/></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
        <tr><td >&nbsp;<a class="button" href="" onClick="setFranchiseTaxFees();return false;" >Continue</a></td>
            <td align="left"></td>
        </tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>

<div <?php genPopWinHDivHtml("popupFrameCancel","Cancel",10,30,550,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("userid"),"T51") ?> >User ID</label></td>
			<td >&nbsp;<input type="text" <?php genInputTextHtml(createSchema("userid"),"T51") ?> /></td>
		</tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("password"),"T51") ?> >Password</label></td>
			<td >&nbsp;<input type="password" <?php genInputTextHtml(createSchema("password"),"T51") ?> /></td>
		</tr>
		<tr><td valign="top"><label <?php genCaptionHtml(createSchema("cancelreason"),"T51") ?> >Reason</label></td>
			<td >&nbsp;<select <?php genSelectHtml(createSchema("cancelreason"),array("loadudflinktable","u_lguposcancelreasons:code:name",":"),"T51",null,null,"width:350px") ?> ></select></td>
		</tr>
		<tr><td valign="top"><label <?php genCaptionHtml(createSchema("remarks"),"T51") ?> >Remarks</label></td>
			<td >&nbsp;<TEXTAREA <?php genTextAreaHtml(createSchema("remarks"),"T51") ?>  rows="5" cols="47"></TEXTAREA></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="formSubmit('cnd');return false;" >OK</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>


