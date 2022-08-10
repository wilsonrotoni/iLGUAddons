<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code:name:u_type in ('IP')",":[All]")) ?>></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_meal") ?>>Meal</label></td>
	<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_meal",array("loadudfenums","u_hisdietscheds:meal",":[All]")) ?>></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/>	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_diettype") ?>>Type of Diet</label></td>
	<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_diettype",array("loadudflinktable","u_hisdiettypes:code:name",":[All]")) ?>></select>&nbsp;<?php if (!isEditMode()) {?><a class="button" href="" onClick="formSubmit('?');return false;">Retrieve</a><?php } ?></td>
	  <td width="168">&nbsp;</td>
		<td >&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
<tr class="fillerRow5px"><td></td></tr>	
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],10,150) ?>		

