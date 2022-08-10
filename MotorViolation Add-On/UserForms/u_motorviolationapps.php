
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>	
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
				
						
				<tr><td><label class="lblobjs"><b>Application Date</b></label></td>
							<td colspan="2"><label class="lblobjs"><b>License No.</b></label></td
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
					<tr>
					
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_licenseno") ?>/> <?php genLinkedButtonHtml("u_licenseno", "", "OpenLnkBtnu_u_licenseno()") ?> </td>
						
						
					</tr>
				
					
					 <tr><td colspan="2"><label class="lblobjs"><b>Name of Violator</b></label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>*Last Name</label></td>
                            <td width="238"><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>*First Name</label></td>
                            <td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
                            <td width="206"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
						<tr><td colspan="2"><label class="lblobjs"><b>Vehicle Details</b></label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_vehicletype") ?>>*Vehicle Type</label></td>
                            <td width="238"><label <?php $page->businessobject->items->userfields->draw->caption("u_plateno") ?>>*Plate No.</label></td>
                            <td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>*Ticket By</label></td>
                            <td width="206"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_vehicletype") ?>></select></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_plateno") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ticketby") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
						
						
						  <tr class="fillerRow5px"><td colspan="3"></td></tr>
        <tr>
                <td  colspan="5">&nbsp;&nbsp;<?php $objGrids[0]->draw(true); ?></td>		
            <td>&nbsp; </td>
            <td >&nbsp;</td>
        </tr>
		
				</table>
				<table class="tableFreeForm" width="75%" border="0" cellpadding="0" cellspacing="0" >
                        <tr class="fillerRow5px">
                            <td></td><td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>REMARKS:</label></td>
                            <td rowspan="3" valign="top">&nbsp;<textarea rows=2 cols=50 <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks") ?></textarea></td>
                           
                        
							<td width="168"></td>
                            <td width="168">&nbsp;</td>
                           	<td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>TOTAL:</label></td>
                            <td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
							
							</tr>
							
                        
                    </table>	
				 
			</div>
		</div>

		
		       
</td></tr>		
	</div>
	 <?php $page->resize->addgridobject($objGrids[0], 300, 450) ?>
	<?php $page->resize->addtab("tab1",-1,141); ?>

 

		

