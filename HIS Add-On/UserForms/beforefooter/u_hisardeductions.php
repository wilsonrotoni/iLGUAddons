<div <?php genPopWinHDivHtml("popupFrameARs","List of A/R",15,135,416,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr>
		  <td width="78"><label <?php genCaptionHtml(createSchema("custname"),"T11") ?>>Name</label></td>
		  <td align=left>&nbsp;<input type="text" size="40" disabled <?php genInputTextHtml(createSchema("custname"),"T11") ?>/></td>
		  </tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td colspan="2"><?php $objGridARs->draw(false); ?></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
