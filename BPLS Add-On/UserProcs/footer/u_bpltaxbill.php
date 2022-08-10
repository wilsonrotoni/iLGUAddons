
<div <?php genPopWinHDivHtml("popupFrameOnhold","Un-Hold Business",10,30,550,false) ?>>
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
		<tr><td valign="top"><label <?php genCaptionHtml(createSchema("remarks"),"T51") ?> >Remarks</label></td>
			<td >&nbsp;<TEXTAREA <?php genTextAreaHtml(createSchema("remarks"),"T51") ?>  rows="5" cols="47"></TEXTAREA></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="unHoldBusinessGPSBPLS();return false;" >Confirm</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>

<div <?php genPopWinHDivHtml("popupFrameUnretire","Un-Retire Business",10,30,550,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("userid"),"T61") ?> >User ID</label></td>
			<td >&nbsp;<input type="text" <?php genInputTextHtml(createSchema("userid"),"T61") ?> /></td>
		</tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("password"),"T61") ?> >Password</label></td>
			<td >&nbsp;<input type="password" <?php genInputTextHtml(createSchema("password"),"T61") ?> /></td>
		</tr>
		<tr><td valign="top"><label <?php genCaptionHtml(createSchema("remarks"),"T61") ?> >Remarks</label></td>
			<td >&nbsp;<TEXTAREA <?php genTextAreaHtml(createSchema("remarks"),"T61") ?>  rows="5" cols="47"></TEXTAREA></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="u_unretireGPSBPLS();return false;" >Confirm</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>

<div <?php genPopWinHDivHtml("popupFrameAuditedGross","Audited Gross",10,30,550,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("userid"),"T71") ?> >User ID</label></td>
			<td >&nbsp;<input type="text" <?php genInputTextHtml(createSchema("userid"),"T71") ?> /></td>
		</tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("password"),"T71") ?> >Password</label></td>
			<td >&nbsp;<input type="password" <?php genInputTextHtml(createSchema("password"),"T71") ?> /></td>
		</tr>
		<tr><td valign="top"><label <?php genCaptionHtml(createSchema("remarks"),"T71") ?> >Remarks</label></td>
			<td >&nbsp;<TEXTAREA <?php genTextAreaHtml(createSchema("remarks"),"T71") ?>  rows="5" cols="47"></TEXTAREA></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="u_BillAdjustmentsGPSBPLS();return false;" >Confirm</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>

<div <?php genPopWinHDivHtml("popupFrameDeleteRecord","Delete Record",10,30,350,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("userid"),"T81") ?> >User ID</label></td>
			<td >&nbsp;<input type="text" <?php genInputTextHtml(createSchema("userid"),"T81") ?> /></td>
		</tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("password"),"T81") ?> >Password</label></td>
			<td >&nbsp;<input type="password" <?php genInputTextHtml(createSchema("password"),"T81") ?> /></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="u_deleteGPSBPLS();return false;" >Confirm</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>

<div <?php genPopWinHDivHtml("popupFrameInsertPaymentHistory","Add Payment History",10,30,350,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("userid"),"T91") ?> >User ID</label></td>
			<td >&nbsp;<input type="text" <?php genInputTextHtml(createSchema("userid"),"T91") ?> /></td>
		</tr>
		<tr><td width="108"><label <?php genCaptionHtml(createSchema("password"),"T91") ?> >Password</label></td>
			<td >&nbsp;<input type="password" <?php genInputTextHtml(createSchema("password"),"T91") ?> /></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="u_InsertPaymentHistoryGPSBPLS();return false;" >Confirm</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
