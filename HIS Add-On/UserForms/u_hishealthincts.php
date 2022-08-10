<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Type</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
		</tr>
		<tr><td ><label <?php $page->businessobject->items->draw->caption("name") ?> >Description</label></td>
			<td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->draw->text("name") ?> /></td>
		</tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_rvufr") ?> >RVU</label></td>
			<td >&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_rvufr") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_rvuto") ?> >To</label>&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_rvuto") ?> /></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Hospital Fees">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Professional Fees">	
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,200) ?>
<?php $page->resize->addgridobject($objGrids[1],20,200) ?>		

