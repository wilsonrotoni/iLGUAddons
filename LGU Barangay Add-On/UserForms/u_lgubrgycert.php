<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_apprefno") ?>>Resident No.</label></td>
		<td align=left>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_apprefno") ?>/> <a class="button" href="" onClick="AddNewResidence();return false;">Add New</a></td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size = "18"<?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("u_appdate") ?> >Date</label></td>
		<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
	</tr>
    <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("u_residentsince") ?> >Resident Since</label></td>
		<td align=left>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_residentsince") ?>/></td>
	</tr>
     <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_intention") ?>>Intention For</label></td>
		<td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_intention") ?>/></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>	
     <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_sitio") ?>>Purok/Sitio</label></td>
		<td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_sitio") ?>></select></td>
		<td ></td>
		<td align=left>&nbsp;</td>
	</tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="Payment">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_orno") ?>>Receipt Number</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_orno") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_paiddate") ?>>Paid Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_paiddate") ?>/></td>
					</tr>
                    <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dueamount") ?>>Due Amount</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_dueamount") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_paidamount") ?>>Paid Amount</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_paidamount") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_changeamount") ?>>Change Amount</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_changeamount") ?>/></td>
					</tr>
                    <tr><td width="168" colspan="2"><?php $objGrids[0]->draw(true) ?>	</td>
						<td>&nbsp;</td>
					</tr>
				 
				</table>
			</div>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,360) ?>		

