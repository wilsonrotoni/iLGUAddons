<div <?php genPopWinHDivHtml("popupFrameNotesTemplate","Notes Template",10,30,615,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="100"><label <?php genCaptionHtml($schema["u_template"],"T102") ?> >Template</label></td>
			<td >&nbsp;<select <?php genSelectHtml($schema["u_template"],array("loaddepartments","",":".$departmentlookupfiller),"T102") ?> ></select></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr>
		  <td colspan="2"><table width="100%" cellpadding="0" cellspacing="0" border="1"><tr><td><div id="divEditorT102" name="divEditorT102" style="overflow:scroll; height:300px; width:600px"></div></td></tr></table></td>
		  </tr>
		
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="setElementHTMLById('divEditor',getElementHTMLById('divEditorT102'));hidePopupFrame('popupFrameNotesTemplate');return false;" >OK</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
