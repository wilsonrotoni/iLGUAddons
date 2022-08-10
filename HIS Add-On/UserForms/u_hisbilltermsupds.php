<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Reference Type /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_refno") ?>>No.</label></td>
	  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_reftype",null,null,null,null,"width:126px") ?>/></select><?php $page->businessobject->items->userfields->draw->lnkbtn("u_refno","","OpenLnkBtnu_hispatientregs()") ?><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_refno") ?>/></td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Patient ID</label></td>
	  <td  align=left>&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_patientid") ?>/>&nbsp;<input type="text" size="45" <?php $page->businessobject->items->userfields->draw->text("u_patientname") ?>/></td>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date /</label><label <?php $page->businessobject->items->userfields->draw->caption("u_time") ?>>Time</label></td>
	  <td align=left>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/>&nbsp;<input type="text" size="6" <?php $page->businessobject->items->userfields->draw->text("u_time") ?>/></td>
	  </tr>
					
<tr>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_paymentterm") ?>>Payment Term</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_paymentterm") ?>></select></td>
					  <td width="168"></td><td width="168" >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_billing",1) ?>/>
				      <label <?php $page->businessobject->items->userfields->draw->caption("u_billing") ?>>w/ Billing</label>&nbsp;&nbsp;</td>
				  </tr>					
<tr>
  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_disccode") ?>>Discount</label></td>
	  <td  align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_disccode") ?>></select></td>
  <td  align=left>&nbsp;</td>
  <td  align=left>&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->radio("u_scdisc",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_scdisc") ?>>Senior Citizen</label></td>
</tr>

	<tr>
	  <td>&nbsp;</td>
	  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Charge</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaid",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaid") ?>>Partial Payment</label></td>
	  <td  align=left>&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>				
<tr class="fillerRow5px"><td></td></tr>	    
	<tr><td>&nbsp;</td>
		<td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr><td width="10">&nbsp;</td><td><?php $objGrids[0]->draw(true); ?></td></tr>
			</table>		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>	
<tr class="fillerRow5px"><td></td></tr>	    
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
		<td  align=left rowspan="3" valign="top">&nbsp;<textarea type="text" rows="2" cols="60" <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks") ?></textarea></td>
	    <td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	</table>
</td></tr>	
