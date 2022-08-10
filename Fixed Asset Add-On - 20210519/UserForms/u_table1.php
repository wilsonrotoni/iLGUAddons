<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Code</label></td>
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
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_field2") ?>>Field 2</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_field2",null,null,null,null,"width:168px") ?>></select></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_field1") ?>>Field 1</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_field1") ?>></select></td>
					</tr>
		
				</table>
			</div>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
		

