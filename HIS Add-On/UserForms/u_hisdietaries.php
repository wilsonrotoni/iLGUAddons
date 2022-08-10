<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department") ?>></select></td>
		<td><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_meal") ?>>Meal</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_meal") ?>></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_time") ?>>Time</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_time") ?>/></td>
	  </tr>	
	</table>
</td></tr>	
<tr class="fillerRow5px"><td></td></tr>		
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<tr class="fillerRow5px"><td></td></tr>		
<tr><td>
	<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_editby") ?>>Edited By</label></td>
			<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_editby") ?>/></td>
		    <td width="168">&nbsp;</td>
		    <td width="168">&nbsp;</td>
		</tr>
	</table>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],0,150) ?>		

