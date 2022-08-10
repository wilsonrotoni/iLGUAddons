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
		<tr><td width="100"><label <?php genCaptionHtml($schema["u_template"],"T103") ?> >Template</label></td>
			<td >&nbsp;<select <?php genSelectHtml($schema["u_template"],array("loaddepartments","",":".$departmentlookupfiller),"T103") ?> ></select></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr>
		  <td colspan="2"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td><textarea cols="83" rows="20" <?php genTextAreaHtml(createSchema("remarks"),"T103") ?>/></textarea></td></tr></table></td>
		  </tr>
		
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="setInput('u_remarks',getTableInput('T103','remarks'));hidePopupFrame('popupFrameNotesTemplate');return false;" >OK</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameRTFNotesTemplate","RTF Notes Template",10,30,615,false) ?>>
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
			<td align="right"><a class="button" href="" onClick="setElementHTMLById('divEditor',getElementHTMLById('divEditorT102'));hidePopupFrame('popupFrameRTFNotesTemplate');return false;" >OK</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
<div <?php genPopWinHDivHtml("popupFrameAuthentication","Authentication",10,30,350,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="128"><label <?php genCaptionHtml(createSchema("userid"),"T50") ?> >User ID</label></td>
			<td >&nbsp;<input type="text" <?php genInputTextHtml(createSchema("userid"),"T50") ?> /></td>
		</tr>
		<tr><td width="128"><label <?php genCaptionHtml(createSchema("password"),"T50") ?> >Password</label></td>
			<td >&nbsp;<input type="password" <?php genInputTextHtml(createSchema("password"),"T50") ?> /></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="formSubmit();return false;" >OK</a></td>
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
			<td >&nbsp;<select <?php genSelectHtml(createSchema("cancelreason"),array("loadudflinktable","u_hiscancelreasons:code:name",":"),"T51",null,null,"width:350px") ?> ></select></td>
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

