<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_ob",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ob") ?>>Opening Balance</label>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_showall",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_showall") ?>>Show All Items</label></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_department") ?>>Section</label></td>
						<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_department",array("loadudflinktable","warehouses:warehouse:warehousename:u_type in ('".$page->getitemstring("u_trxtype")."')","")) ?>/></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_itemgroup") ?>>Item Group</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_itemgroup") ?>></select></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	  </tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_itemclass") ?>>Item Class</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_itemclass") ?>></select></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_iqwhdocno") ?>>I.Q. Doc. No.</label></td>
						<td><?php genLinkedButtonHtml("u_iqdocno","","OpenLnkBtnInitialQuantities()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_iqdocno") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Items">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,190) ?>
<?php //$page->resize->addgridobject($objGrids[1],20,260) ?>		

