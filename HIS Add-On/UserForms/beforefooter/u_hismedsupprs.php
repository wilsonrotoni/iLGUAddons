<div <?php genPopWinHDivHtml("popupFrameFilter","Advance Filter",185,122,500,false) ?>>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table class="tableFreeForm"  width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168">&nbsp;</td>
	  <td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_replenish",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_replenish") ?>>Replenish</label></td>
		</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_itemclass") ?>>Item Class</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_itemclass") ?>/></select></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;<?php if (!isEditMode()) {?><a id="btnRetrieve" name="btnRetrieve" class="button" href="" onClick="formSubmit('?');return false;">Retrieve</a><?php } ?></td>
	  </tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
