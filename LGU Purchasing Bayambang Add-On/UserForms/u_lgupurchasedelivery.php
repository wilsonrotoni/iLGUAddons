<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr><td width="187" ><label <?php $page->businessobject->items->userfields->draw->caption("u_bpcode") ?>>*Supplier</label></td>
		<td width="1142" align=left><?php genLinkedButtonHtml("u_bpcode", "", "OpenLnkBtnu_supplier()") ?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bpcode") ?>/></td>
		<td width="200" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >*No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="200" align=left>&nbsp;<input type="text"  size="18" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bpname") ?>>*Supplier Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bpname") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >*Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
      <tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>*Profit Center</label></td>
		<td align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_profitcenter") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>*Date</label></td>
		<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcentername") ?>>*Profit Center Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_profitcentername") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_duedate") ?>>*Due Date</label></td>
		<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_duedate") ?>/></td>
        </tr>
        <tr>
            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_procmode") ?>>Mode of Procurement</label></td>
		<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_procmode") ?>></select></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_drno") ?>>Delivery Receipt No</label></td>
		<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_drno") ?>/></td>
	</tr>
        <tr>
                <td ></td>
		<td  align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_proctype","Program") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_proctype") ?>>Program/Project</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_proctype","Procurement") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_proctype") ?>>Procurement Plan</label></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_invoiceno") ?>>Invoice Number</label></td>
		<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_invoiceno") ?>/></td>
                
	</tr>
        <tr>
        <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_projcode") ?>>Program/Project</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_projcode") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_projname") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_icsno") ?>>ICS Number</label></td>
		<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_icsno") ?>/></td>
                
<!--                
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_jevno") ?>>JEV No.</label></td>
		<td align=left><?php if($page->getitemstring("docstatus")!="D") genLinkedButtonHtml("u_jevno","","OpenLnkBtnJournalVouchers()"); else echo "&nbsp;"; ?><input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_jevno") ?>/></td>-->
	</tr>	
	</table>
</td></tr>	
<tr><td>&nbsp;</td></tr>
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Contents">	
        <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width = "187"><label <?php $page->businessobject->items->userfields->draw->caption("u_doctype") ?>>Item/Service Type</label></td>
            <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_doctype") ?>></select></td>
		</tr>
        </table>
                    <div id="divItem" name="divItem" style="display:none">
			<?php $objGrids[0]->draw(true) ?>
                    </div>
                    <div id="divService" name="divService" style="display:none">
			<?php $objGrids[1]->draw(true) ?>
                    </div>	  
		</div>
        <div class="tabbertab" title="Remarks">	
			<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_remarks") ?></TEXTAREA>
                </td></tr>
            </table>  
		</div>
	</div>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_requestedbyname") ?>>Requested By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_requestedbyname") ?>/>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_requestedbyposition") ?>/></td>
		    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
			<td width="168">&nbsp;<input type="text" size="17"<?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_reviewedby") ?>>Reviewed By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_reviewedby") ?>/>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_reviewedbyposition") ?>/></td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_approvedby") ?>>Approved By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_approvedby") ?>/>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_approvedbyposition") ?>/></td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
	</table>
</td></tr>
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,400) ?>
<?php $page->resize->addgridobject($objGrids[1],20,400) ?>
<?php $page->resize->addinput("u_remarks",30,245); ?>	

