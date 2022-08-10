<div <?php genPopWinHDivHtml("popupFrameCancel","Cancellation Info",10,30,550,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td width="138"><label <?php $page->businessobject->items->userfields->draw->caption("u_cancelleddate") ?>>Cancelled Date</label></td>
			<td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_cancelleddate") ?>/></td>
		</tr>
		
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cancelledremarks") ?>>Remarks</label></td>
			<td >&nbsp;<textarea rows="2" cols="48" size="48" <?php $page->businessobject->items->userfields->draw->textarea("u_cancelledremarks") ?>/><?php echo $page->getitemstring("u_cancelledremarks") ?></textarea></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cancelledby") ?>>Cancelled By</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_cancelledby") ?>/></td>
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
