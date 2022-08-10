<div <?php genPopWinHDivHtml("popupFrameReOpen","Re-Open",10,30,450,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_reopenremarks") ?> >Remarks</label></td>
			<td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_reopenremarks") ?>  rows="2" cols="48"><?php echo $page->getitemstring("u_reopenremarks") ?></TEXTAREA></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="reopenGPSHIS();return false;" >Re-Open</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
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
		  <td colspan="2"><table width="100%" cellpadding="0" cellspacing="0" border="1"><tr><td><textarea cols="83" rows="20" <?php genTextAreaHtml(createSchema("remarks"),"T102") ?>/></textarea></td></tr></table></td>
		  </tr>
		
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="setInput('u_remarks',getTableInput('T102','remarks'));hidePopupFrame('popupFrameNotesTemplate');return false;" >OK</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
