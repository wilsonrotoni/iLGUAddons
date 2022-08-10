<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	
	<tr>
	  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_gltype","A") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_glacctno") ?>>G/L Account</label></td>
	<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_glacctno") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_glacctname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>	  </tr>
	<tr>
	  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_gltype","CIB") ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_bank") ?>>Bank /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_bank") ?>>Account No.</label></td>
	  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bank",array("loadhousebanksbycountry",$_SESSION["branch"].":PH",":")) ?>></select>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bankacctno",array("loadhousebankaccounts",$_SESSION["branch"].":PH:".$page->getitemstring("u_bank"),":")) ?>></select></td>
	  <td >&nbsp;</td>
	  <td>&nbsp;</td>
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
		<tr>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_preparedby") ?>>Prepared By</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_preparedby") ?>></select></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
		</tr>
		<tr>
		  <td >&nbsp;</td>
		  <td>&nbsp;</td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalwtax") ?>>W/Tax</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalwtax") ?>/></td>
	  </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_jvdocno") ?>>JV No.</label></td>
	<td><?php genLinkedButtonHtml("u_jvdocno","","OpenLnkBtnJournalVouchers()") ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_jvdocno") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalpayment") ?>>Payment</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalpayment") ?>/></td>
	  </tr>
		<tr>
		  <td >&nbsp;</td>
		  <td>&nbsp;</td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalbalance") ?>>Balance</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalbalance") ?>/></td>
	  </tr>
	</table>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],0,215) ?>		

