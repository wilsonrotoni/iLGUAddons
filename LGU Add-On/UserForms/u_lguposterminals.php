<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Cashier ID</label></td>
                    <td >&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		</tr>
<!--		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >IP Address</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		</tr>-->
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_quegroup") ?>>Que Groups</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_quegroup", null, null, null, null, "width:258px") ?>/></select></td>
		</tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Series">	
			<?php $objGridA->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGridA,20,180) ?>		
<?php // $page->resize->addgridobject($objGrids[0],20,200) ?>		

