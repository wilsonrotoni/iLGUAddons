<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
		<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","u_hissections:code:name:u_type in ('".$page->getitemstring("u_trxtype") . "')",":")) ?>></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Visit Type</label></td>
	<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype") ?>></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_startdate") ?>>Date From</label></td>
		<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_startdate") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_enddate") ?>>To</label>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_enddate") ?>/>&nbsp;<?php if (!isEditMode()) {?><a class="button" href="" onClick="formSubmit('?');return false;">Retrieve</a><?php } ?></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>Reference No.</label></td>
	<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
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
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalpf") ?>>Professional Fees</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalpf") ?>/></td>
		</tr>
		<tr>
		  <td width="168">&nbsp;</td>
		  <td >&nbsp;</td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_discamount") ?>>Discount</label></td>
			<td width="168">&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_discamount") ?>/></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalhf") ?>>Hospital Fees</label></td>
			<td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalhf") ?>/></td>
		</tr>
	</table>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,195) ?>		

