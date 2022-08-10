<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>Reference No</label></td>
		<td align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_custname") ?>>Customer Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_custname") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
    <tr><td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_stallno") ?>>Stall No</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_stallno") ?>/>&nbsp;&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bldg") ?>></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("u_date") ?> >Date</label></td>
		<td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	</tr>
    <tr><td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("u_year") ?> >Year</label></td>
		<td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
	</tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="PMR Payment Details">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,180) ?>		

