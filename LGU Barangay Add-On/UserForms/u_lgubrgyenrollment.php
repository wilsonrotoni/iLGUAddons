<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
        <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_daycare") ?>>Daycare Center</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_daycare") ?>></select></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr>
    	<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_teacher") ?>>Teacher Name</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_teacher") ?>></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
    <tr>
    	<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_class") ?>>Class Name</label></td>
		<td>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_class") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
		<td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	</tr>		
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Student List">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,200) ?>		

