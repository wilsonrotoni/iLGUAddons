<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_ob",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_ob") ?>>Opening Balance</label></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="200" align=left>&nbsp;<input type="text" size="23" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr>
    <td width="168">&nbsp;</td>
						<td>&nbsp;</td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_profitcenter") ?>/>&nbsp;</td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
						<td>&nbsp;<input type="text" size="23" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	  </tr>
	<tr>
	  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcentername") ?>>Profit Center Name</label></td>
	  <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_profitcentername") ?>/></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>Reference No.</label></td>
						<td>&nbsp;<input type="text" size="23" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="G/L Accounts">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<tr class="fillerRow5px"><td>
</td></tr>		
<tr><td>
    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>					</tr>
        
        <tr>					</tr>
        
        <tr>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
            <td rowspan="3"><?php if($page->getitemstring("docstatus")!="D") genLinkedButtonHtml("u_jevno","","OpenLnkBtnJournalVouchers()"); else echo "&nbsp;"; ?><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?> rows="2" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totaldebit") ?>>Total Debit</label></td>
            <td width="188">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totaldebit") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalcredit") ?>>Total Credit</label></td>
            <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalcredit") ?>/></td>
        </tr>
        <tr>
          <td width="168">&nbsp;</td>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_variance") ?>>Variance</label></td>
            <td width="168">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_variance") ?>/></td>
        </tr>
    </table>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,295) ?>	

