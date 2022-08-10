<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_empid") ?>>Employee ID</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_empid") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>	
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_empname") ?>>Employee Name</label></td>
		<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_empname") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Amount</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr><td width="168" valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_purpose") ?>>Purpose</label></td>
		<td colspan="3">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_purpose") ?>rows="5" cols="80"><?php echo $page->getitemstring("u_purpose") ?></TEXTAREA></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cvno") ?>>Check Voucher #</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_cvno") ?>/></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cvdate") ?>>Check Released Date</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_cvdate") ?>/></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

