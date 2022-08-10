<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_facode") ?>>Asset Code</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_facode") ?>/></td>
					    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Posting Date</label></td>
						<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_faname") ?>>Asset Name</label></td>
						<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_faname") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_faclass") ?>>Asset Class</label></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_faclass") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cost") ?>>Asset Cost</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_cost") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_accumdepre") ?>>Accumulated Dep.</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_accumdepre") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_salvagevalue") ?>>Salvage Value</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_salvagevalue") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bookvalue") ?>>Book Value</label></td>
						<td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_bookvalue") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remainlifem") ?>>Remaining Life(Months)</label></td>
						<td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_remainlifem") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
					
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
	  </tr>
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_newlifem") ?>>New Life(Months)</label></td>
						<td>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_newlifem") ?>/></td>
					    <td>&nbsp;</td>
					    <td>&nbsp;</td>
					</tr>
	</table>
</td></tr>	
		

