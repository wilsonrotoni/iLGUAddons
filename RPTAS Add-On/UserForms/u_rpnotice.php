<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr><td width="187" ><label <?php $page->businessobject->items->userfields->draw->caption("u_tin") ?>>Tin</label></td>
		<td width="1142" align=left><?php genLinkedButtonHtml("u_tin", "", "OpenLnkBtnu_TIN()") ?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tin") ?>/></td>
		<td width="200" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >*No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="200" align=left>&nbsp;<input type="text"  size="18" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_declaredowner") ?>>Declared Owner</label></td>
		<td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_declaredowner") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >*Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
        <tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
		<td align=left>&nbsp;<input type="text" size="8" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>*Date</label></td>
		<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	</tr>
        <tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("searchby") ?>>Search By</label></td>
		<td align=left><input type="radio" <?php genInputRadioHtml($schema["searchby"],"L") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("searchby") ?>>Land</label>
                                <input type="radio" <?php genInputRadioHtml($schema["searchby"],"B") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("searchby") ?>>Building</label>
                                <input type="radio" <?php genInputRadioHtml($schema["searchby"],"M") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("searchby") ?>>Machinery</label>
                </td>
		
	</tr>
	
	</table>
</td></tr>	
<tr><td>&nbsp;</td></tr>
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Contents">	
                    <div id="divItem" name="divItem" >
			<?php $objGrids[0]->draw(true) ?>
                    </div>
                    	  
		</div>
      
	</div>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
                    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
                    <td rowspan="3">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_remarks") ?></TEXTAREA></td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalmarketvalue") ?>>Total Market Value</label></td>
                    <td width="168">&nbsp;<input type="text" size="17"<?php $page->businessobject->items->userfields->draw->text("u_totalmarketvalue") ?>/></td>
		</tr>
		<tr>
                    <td width="168">&nbsp;</td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalassvalue") ?>>Total Assessed Value</label></td>
                    <td width="168">&nbsp;<input type="text" size="17"<?php $page->businessobject->items->userfields->draw->text("u_totalassvalue") ?>/></td>
		</tr>
	</table>
</td></tr>
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,300) ?>

