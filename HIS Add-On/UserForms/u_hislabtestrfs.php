<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
		
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reg Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
		<td><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><select <?php $page->businessobject->items->userfields->draw->select("u_reftype",null,null,null,null,"width:112px") ?>/></select>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_examno") ?>>Examination No.</label></td>
		<td width="168">&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_examno") ?>/></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
		<td><?php $page->businessobject->items->userfields->draw->lnkbtn("u_patientid","","OpenLnkBtnu_hispatients()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_itemcode") ?>>Item Code</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_itemcode") ?>/></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_itemdesc") ?>>Description</label></td>
		<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_itemdesc") ?>/></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Amount</label></td>
		<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	</table>
</td></tr>	
<tr class="fillerRow5px"><td></td></tr>
<tr><td>
	<?php $objGrids[0]->draw(true) ?>	  
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],10,220) ?>		

