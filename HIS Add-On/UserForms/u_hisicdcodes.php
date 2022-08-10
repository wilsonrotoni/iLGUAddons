<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="220" ><label <?php $page->businessobject->items->draw->caption("code") ?>>Code</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Description</label></td>
			<td >&nbsp;<input type="text" size="100" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_chapter") ?>>Chapter</label></td>
			<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_chapter",null,null,null,null,"width:725px") ?>></select></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_block") ?>>Block</label></td>
			<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_block",null,null,null,null,"width:725px") ?>></select></td>
		</tr>
		
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_level") ?>>Level</label></td>
			<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_level") ?>></select></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_classplace") ?>>Place in Classification Tree</label></td>
			<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_classplace") ?>></select></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_terminalnode") ?>>Terminal Node</label></td>
			<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_terminalnode") ?>></select></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_mortality1") ?>>Mortality 1</label></td>
			<td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_mortality1") ?>/></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_mortality2") ?>>Mortality 2</label></td>
			<td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_mortality2") ?>/></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_mortality3") ?>>Mortality 3</label></td>
			<td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_mortality3") ?>/></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_mortality4") ?>>Mortality 4</label></td>
			<td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_mortality4") ?>/></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_morbidity") ?>>Morbidity</label></td>
			<td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_morbidity") ?>/></td>
		</tr>
	</table>
</td></tr>	

		

