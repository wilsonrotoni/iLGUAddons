<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_custgroup") ?>>Customer Group</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_custgroup") ?>></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_glacctno") ?>>G/L Account</label></td>
	<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_glacctno") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_glacctname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>	  </tr>
	</table>
</td></tr>	
<tr class="fillerRow5px"><td></td></tr>	
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<tr class="fillerRow5px"><td></td></tr>	
<tr><td>
	<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
		<tr>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_preparedby") ?>>Prepared By</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_preparedby") ?>></select></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalbalance") ?>>Total Balance</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalbalance") ?>/></td>
		</tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_jvdocno") ?>>JV No.</label></td>
	<td><?php genLinkedButtonHtml("u_jvdocno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_jvdocno") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totaldeduction") ?>>Total Deduction</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totaldeduction") ?>/></td>
	  </tr>
	</table>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],0,175) ?>		

