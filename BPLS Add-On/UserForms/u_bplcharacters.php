<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Code</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
			<td >&nbsp;</td>
			<td >&nbsp;</td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Name</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
			<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_mayorsamount") ?>>Mayor's Permit Fee</label></td>
			<td >&nbsp;<input type="text" size="16"<?php $page->businessobject->items->userfields->draw->text("u_mayorsamount") ?> /></td>
			<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_environmentamount") ?>>Environmental Fee</label></td>
			<td >&nbsp;<input type="text" size="16"<?php $page->businessobject->items->userfields->draw->text("u_environmentamount") ?> /></td>
			<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Sanitary Inspection Fee</label></td>
			<td >&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?> /></td>
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
<!--		<tr><td><label class="lblobjs" ><b>Mayor's Permit</b></label></td><td>&nbsp;</td><td><label class="lblobjs" ><b>Collection Imposition</b></label></td></tr>
		<tr><td><?php $objGrids[1]->draw(true) ?></td><td>&nbsp;</td><td><?php $objGrids[2]->draw(true) ?></td></tr>-->
	</table>
</td></tr>    
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addgridobject($objGrids[0],0,-1) ?>
<?php //$page->resize->addgridobject($objGrids[1],730,390) ?>		
<?php //$page->resize->addgridobject($objGrids[2],-1,390) ?>		

