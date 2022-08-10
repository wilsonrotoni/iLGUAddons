<tr><td>
        
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_asofdate") ?>>As of</label></td>
		<td width="795" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_asofdate") ?>/></td>
		<td width="124" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="200" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_cashierby") ?>>Cashier Name</label></td>
		<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_cashierby", null, null, null, null, "width:158px") ?>></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
    <tr><td >&nbsp;</td>
		<td  align=left>&nbsp;</td>	
	<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    <tr><td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
		<td align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
	</tr>
    <tr><td >&nbsp;</td>
		<td  align=left>&nbsp;<a class="button" href="" onClick="formSearchNow();return false;">Search</a></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_remittancedate") ?>>Remittance Date</label></td>
		<td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_remittancedate") ?>/> &nbsp;<a class="button" href="" onClick="ApplyRemittanceDate();return false;">Apply</a></td>
	</tr>
    
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="POS Details">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
                <div class="tabbertab" title="Registers">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
            <div class="tabbertab" title="Denominations">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
	
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,250) ?>		

