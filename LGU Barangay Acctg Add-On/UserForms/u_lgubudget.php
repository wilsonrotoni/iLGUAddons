<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_yr") ?>>Year</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_yr",array("loadenumyear","",":[Select]")) ?>/></select></td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter") ?>/></select></td>
		<td width="168">&nbsp;</td>
		<td align=left width="168">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_released",1) ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_released") ?> >Released</label></td>
		</tr>
	</table>
</td></tr>	
<tr><td>&nbsp;</td></tr>
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<tr><td>&nbsp;</td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_preparedby") ?>>Prepared By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_preparedby") ?>/></td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalps") ?>>Total PS</label></td>
			<td width="168">&nbsp;<input type="text" size="20"<?php $page->businessobject->items->userfields->draw->text("u_totalps") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_reviewedby") ?>>Reviewed By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_reviewedby") ?>/></td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalmooe") ?>>Total MOOE</label></td>
			<td width="168">&nbsp;<input type="text" size="20"<?php $page->businessobject->items->userfields->draw->text("u_totalmooe") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_approvedby") ?>>Approved By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_approvedby") ?>/></td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalfe") ?>>Total FE</label></td>
			<td width="168">&nbsp;<input type="text" size="20"<?php $page->businessobject->items->userfields->draw->text("u_totalfe") ?>/></td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalco") ?>>Total CO</label></td>
			<td width="168">&nbsp;<input type="text" size="20"<?php $page->businessobject->items->userfields->draw->text("u_totalco") ?>/></td>
	  </tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
			<td width="168">&nbsp;<input type="text" size="20"<?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],10,260) ?>		

