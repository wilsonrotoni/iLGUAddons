<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_yr") ?>>Year</label></td>
						<td>&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_yr") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_period") ?>>Period</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_period") ?>/></select></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Department</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loaddepartments","",":[Select]")) ?>/></select></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_glacctno") ?>>G/L Account No.</label></td>
			<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_glacctno") ?>/></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Name</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Amount</label></td>
			<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_weightperc") ?>>Projects Total %</label></td>
			<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_weightperc") ?>/></td>
		</tr>
	</table>
</td></tr>	
<tr><td>&nbsp;</td></tr>		
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<?php $page->resize->addgridobject($objGrids[0],10,250) ?>		

