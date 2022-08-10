<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_yr") ?>>Year</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_yr",array("loadenumyear","",":[Select]")) ?>/></select></td>
		    <td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="240" align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter") ?>/></select></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docstatus") ?>/></td>
		</tr>
		<tr>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_type") ?>>Type</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_type") ?>/></select></td>
		  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<tr><td>&nbsp;</td></tr>
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Details (Amount)">	
			<?php $objGrids[1]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Details (Qty)">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
		<div class="tabbertab" title="Remarks">	
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_remarks") ?></TEXTAREA>
                </td></tr>
            </table>
		</div>
        <?php if ($page->getitemstring("docstatus")=="For Approval") { ?>
		<div class="tabbertab" title="Decision Remarks">	
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_decisionremarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_decisionremarks") ?></TEXTAREA>
                </td></tr>
            </table>
		</div>
       <?php } ?>
       <?php if ($page->getitemstring("docstatus")!="Draft") { ?>
		<div class="tabbertab" title="Decision Remarks History">	
            <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_history") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_history") ?></TEXTAREA>
                </td></tr>
            </table>
		</div>
       <?php } ?>
	</div>
</td></tr>		
<tr><td>&nbsp;</td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_preparedby") ?>>Prepared By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_preparedby") ?>/></td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
			<td width="168">&nbsp;<input type="text" size="20"<?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_reviewedby") ?>>Reviewed By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_reviewedby") ?>/></td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_approvedby") ?>>Approved By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_approvedby") ?>/></td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,293) ?>		
<?php $page->resize->addgridobject($objGrids[1],20,293) ?>		
<?php $page->resize->addinput("u_remarks",30,245); ?>
<?php if ($page->getitemstring("docstatus")=="For Approval") $page->resize->addinput("u_decisionremarks",30,245); ?>
<?php if ($page->getitemstring("docstatus")!="Draft") $page->resize->addinput("u_history",30,245); ?>

