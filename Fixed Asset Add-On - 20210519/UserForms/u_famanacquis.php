<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Posting Date</label></td>
						<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_faclass") ?>>Asset Class</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_faclass") ?>/></select></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jvno") ?>>JV No.</label></td>
						<td><?php genLinkedButtonHtml("u_jvno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_jvno") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>	
	</table>
</td></tr>	
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<?php $page->resize->addgridobject($objGrids[0],10,200) ?>		

