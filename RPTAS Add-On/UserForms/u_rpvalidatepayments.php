<tr><td>
        
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_ordate") ?>>Payment Date</label></td>
		<td width="795" align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_ordate") ?>/></td>
		<td width="124" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="200" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
		<td align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
                <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_selectedtotalamount") ?>>Selected Total Amount</label></td>
		<td align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_selectedtotalamount") ?>/></td>
                <td ></td>
		<td align=left>&nbsp;</td>
	</tr>
	<tr>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Approver Remarks</label></td>
		<td align=left>&nbsp;<textarea rows=2 cols=50 <?php $page->businessobject->items->userfields->draw->text("u_approverremarks") ?>/><?php echo $page->getitemstring("u_approverremarks") ?></textarea></td>
                <td ></td>
		<td align=left>&nbsp;</td>
	</tr>
    <tr><td >&nbsp;</td>
                <td  align=left>&nbsp;<?php if(!isEditMode()){?><a class="button" href="" onClick="formSearchNow();return false;">Search</a> <?php }?></td>
		<td  >&nbsp;</td>
		<td align=left>&nbsp;</td>
	</tr>
        <tr class="fillerRow5px">
                            <td></td><td></td>
                            <td></td>
                        </tr>
    
	</table>
</td></tr>	
<tr><td>
	
			<?php $objGrids[0]->draw(true) ?>	  
	
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],10,220) ?>		

