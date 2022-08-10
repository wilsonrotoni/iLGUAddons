<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
                <td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_bpcode") ?>>*Supplier</label></td>
		<td width="1097" align=left>&nbsp;<input type="text" size="25" <?php $page->businessobject->items->userfields->draw->text("u_bpcode") ?>/></td>
		<td width="211" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="141"><label <?php $page->businessobject->items->draw->caption("docno") ?> >Contract No.</label></td><td width="70" align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text"  size="17" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bpname") ?>>*Supplier Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bpname") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
    	<tr>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>*Profit Center</label></td>
		<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_profitcenter") ?>/></td>
		<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Award Date</label></td>
		<td align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	</tr>
    <tr>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcentername") ?>>*Profit Center Name</label></td>
		<td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_profitcentername") ?>/></td>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_contracteffdate") ?>>Contract Effectivity Date</label></td>
		<td align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_contracteffdate") ?>/></td>
		</tr>	
    <tr>
                <td > <label <?php $page->businessobject->items->userfields->draw->caption("u_projcode") ?>>Program/Project</label></td>
		<td  align=left>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_projcode") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_projname") ?>/></td>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_contractexpdate") ?>>Contract End Date</label></td>
		<td align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_contractexpdate") ?>/></td>
	</tr>
	</table>
</td></tr>	
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
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
                    	<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_controlno") ?>>Control Number:</label></td>
						<td width="375">&nbsp;</td>
                        <td width="422"></td>
						<td>&nbsp;</td>
					</tr>
                    <tr>
                    	<td width="168">&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_controlno") ?>/></td>
						<td>&nbsp;</td>
                        <td width="422"></td>
						<td>&nbsp;</td>
					</tr>
                    <tr>
                    	<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bidtitle") ?>>Bid Notice Title:</label></td>
						<td>&nbsp;</td>
                        <td width="422"><label <?php $page->businessobject->items->userfields->draw->caption("u_category") ?>>Category:</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
                    	<td colspan="2">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_bidtitle") ?>/></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_category") ?>/></td>
                        <td width="656"></td>
						<td width="23">&nbsp;</td>
					</tr>
                    <tr>
                    	<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_procmode") ?>>Procurement Mode:</label></td>
						<td>&nbsp;</td>
                        <td width="422"><label <?php $page->businessobject->items->userfields->draw->caption("u_procrules") ?>>Procurement Rules:</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
                    	<td colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_procmode") ?>> </select></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_category") ?>/></td>
                        <td width="656"></td>
						<td width="23">&nbsp;</td>
					</tr>
                    <tr>
                    	<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_class") ?>>Classification</label></td>
						<td>&nbsp;</td>
                        <td width="422"><label <?php $page->businessobject->items->userfields->draw->caption("u_fundsource") ?>>Source of Funds</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
                    	<td colspan="2">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_class") ?>/></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_fundsource") ?>/></td>
                        <td width="656"></td>
						<td width="23">&nbsp;</td>
					</tr>
                    <tr>
                    	<td width="168"><label class="lblobjs"><b>Awardee Details</b></label></td>
						<td>&nbsp;</td>
                        <td width="422"></td>
						<td>&nbsp;</td>
					</tr>
                    <tr>
                    	<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_contactperson") ?>>Contact Person</label></td>
						<td>&nbsp;</td>
                        <td width="422"><label <?php $page->businessobject->items->userfields->draw->caption("u_contactdesignation") ?>>Designation</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
                    	<td colspan="2">&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_contactperson") ?>/></td>
						<td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_contactdesignation") ?>/></td>
                        <td width="656"></td>
						<td width="23">&nbsp;</td>
					</tr>
                     <tr>
                    	<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_contactaddress") ?>>Address</label></td>
						<td>&nbsp;</td>
                        <td width="422"></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
                    	<td colspan="2">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_contactaddress") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_contactaddress") ?></TEXTAREA></td>
						<td>&nbsp;</td>
                        <td width="656"></td>
						<td width="23">&nbsp;</td>
					</tr>
				</table>
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
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_requestedbyname") ?>/>&nbsp;<input type="text" size="30"<?php $page->businessobject->items->userfields->draw->text("u_requestedbyposition") ?>/></td>
		    <td width="268"></td>
			<td width="168">&nbsp;</td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_reviewedby") ?>>Reviewed By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_reviewedby") ?>/>&nbsp;<input type="text" size="30"<?php $page->businessobject->items->userfields->draw->text("u_reviewedbyposition") ?>/></td>
		    <td width="268"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalcontractamt") ?>>Total Contract Amount</label></td>
			<td width="168">&nbsp;<input type="text" size="17"<?php $page->businessobject->items->userfields->draw->text("u_totalcontractamt") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_approvedby") ?>>Approved By</label></td>
			<td>&nbsp;<input type="text" size="35"<?php $page->businessobject->items->userfields->draw->text("u_approvedby") ?>/>&nbsp;<input type="text" size="30"<?php $page->businessobject->items->userfields->draw->text("u_approvedbyposition") ?>/></td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
	</table>
</td></tr>			
<?php $page->resize->addtab("tab1",20,280); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,340) ?>
<?php $page->resize->addgridobject($objGrids[1],20,340) ?>
<?php $page->resize->addinput("u_remarks",30,245); ?>			

