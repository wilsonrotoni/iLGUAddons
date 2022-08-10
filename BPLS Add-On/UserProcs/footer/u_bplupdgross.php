<div <?php genPopWinHDivHtml("popupFrameAdjustPaidYear","Audited Gross",10,30,550,false) ?>>
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
			<td align="right"><a class="button" href="" onClick="AdjustPaidYearGPSRPTAS();return false;" >Confirm</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>