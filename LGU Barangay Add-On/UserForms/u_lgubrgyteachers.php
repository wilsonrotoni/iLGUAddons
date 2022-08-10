<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Employee ID</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Name</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		</tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_salutation") ?>>Salutation</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_salutation") ?>></select></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_civilstatus") ?>>Civil Status</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_civilstatus") ?>></select></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
					</tr>
		
				</table>
			</div>
		</div>
		<div class="tabbertab" title="Educational Attainment">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,180) ?>		

