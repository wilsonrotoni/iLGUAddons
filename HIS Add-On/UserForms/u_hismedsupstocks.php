<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectdoctype,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_fromdepartment") ?>>From Section</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_fromdepartment",array("loadudflinktable","warehouses:warehouse:warehousename:u_type in ('".$page->getitemstring("u_trxtype")."')",":")) ?>/></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	 <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_todepartment") ?>>To Section</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_todepartment",array("loadudflinktable",iif($page->getitemstring("u_trxtype")=="WAREHOUSE" || $page->getitemstring("u_trxtype")=="CSR" || $page->getitemstring("u_trxtype")=="PHARMACY","warehouses:warehouse:warehousename","warehouses:warehouse:warehousename:u_type='".$page->getitemstring("u_trxtype")."'"),":")) ?>/></select>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_multipledepartment",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_multipledepartment") ?>>Multiple</label></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Date</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	</tr>
	
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_type") ?>>Type of Issuance</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_type") ?>/></select></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_requestno") ?>>Request No.</label></td>
	  <td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_requestno") ?>/></td>
	  </tr>
	<tr>
	  <td>&nbsp;</td>
	  <td  align=left>&nbsp;<?php if (!isEditMode() || isEditMode()) {?><a class="button" href="" onClick="showPopupFrame('popupFrameFilter');return false;">Advance Filter</a><?php } ?></td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
<tr class="fillerRow5px"><td></td></tr>
<tr><td>
	<?php $objGrids[0]->draw(true); ?>
</td></tr>		
<tr class="fillerRow5px"><td></td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
	<tr>
	  <td width="168" valign="top"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
	  <td  align=left rowspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="1" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
	  <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
	  <td width="168" align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>	
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,241); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","tab1Pictures") ?>
<?php $page->resize->addgridobject($objGrids[0],10,253) ?>		
<?php //$page->resize->addgridobject($objGrids[1],-1,258) ?>		
<?php //$page->resize->addinput("u_remarks",35,245) ?>		


