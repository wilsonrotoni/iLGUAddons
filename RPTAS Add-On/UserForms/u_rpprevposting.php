<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
		<td align=left><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ownertin") ?>>Tin</label></td>
		<td  align=left><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ownertin") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ownername") ?>>Declared Owner</label></td>
		<td  align=left><input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_ownername") ?>/></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="Posting Details">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Property Assessment">	
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php  $page->resize->addgridobject($objGrids[0],20,300) ?>		

