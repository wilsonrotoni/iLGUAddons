
<div <?php genPopWinHDivHtml("popupFrameAddEditRemarks","Add/Edit Remarks",50,50,550,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td valign="top"><label <?php genCaptionHtml(createSchema("remarks"),"T51") ?> >Remarks</label></td>
			<td >&nbsp;<TEXTAREA <?php genTextAreaHtml(createSchema("remarks"),"T51") ?>  rows="10" cols="60"></TEXTAREA></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="setRemarksGPSLGUPurchasing();return false;" >OK</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
