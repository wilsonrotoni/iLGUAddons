<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid2") ?>>Patient ID To Merge</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid2") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_patientname2") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
		<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	  </tr>	
	</table>
</td></tr>	
		

