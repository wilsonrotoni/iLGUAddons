
<div <?php genPopWinHDivHtml("popupFrameColorPicker","Color Picker",10,30,95,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
        <?php $schema_colorpicker["colorpicker"] = createSchema("colorpicker"); ?>
		<tr><td colspan="2">&nbsp;<input type="color" <?php genInputTextHtml($schema_colorpicker["colorpicker"],"T999") ?>></td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="center"><a class="button" href="" onClick="selectColorGPSPOS();return false;" >OK</a></td>
		</tr>
        <tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>