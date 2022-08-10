<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
                        <td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Code</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
			<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_seqno") ?>>Sequence No</label></td>
			<td >&nbsp;<input type="text" size="16"<?php $page->businessobject->items->userfields->draw->text("u_seqno") ?> /></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Name</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
			<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Unit Price</label></td>
			<td >&nbsp;<input type="text" size="16"<?php $page->businessobject->items->userfields->draw->text("u_amount") ?> /></td>
			<td width="268"></td>
			<td >&nbsp;</td>
			<td width="168"></td>
			<td >&nbsp;</td>
		</tr>
	</table>
</td></tr>	
<tr class="fillerRow5px"><td></td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td colspan="3">
        	<?php $objGrids[0]->draw(true) ?>	  
		</td></tr>
		<tr class="fillerRow5px"><td colspan="3"></td></tr>
	</table>
</td></tr>    
<?php $page->resize->addgridobject($objGrids[0],-1,390) ?>

